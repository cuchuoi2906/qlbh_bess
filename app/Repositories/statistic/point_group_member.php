<?php
// Tính point maketing và marketing f1
use App\Models\Setting;

//$_GET['user_id'] = 1259;
//$_REQUEST['user_id'] = 1259;
$total = 0;
$start_date = input('start_date');
$user_id_member = intval(input('user_id_member'));
$start_date = empty($start_date) ? '2023-01-01' : $start_date;
$end_date = input('end_date');   
$end_date = empty($end_date) ? date('Y-m-d') : $end_date;
 
$v_where = '';
if(strtotime($start_date) <= strtotime(date('2022-12-31 23:59:39')) || strtotime($end_date) <= strtotime(date('2022-12-31 23:59:39'))){
	return [
		'vars' => []
	];
}

if ($start_date) {
    $v_where = " AND orc_created_at >= '".$start_date . " 00:00:00'";
}

if ($end_date) {
    $v_where .= " AND orc_created_at <= '".$end_date . " 23:59:59'";
}

$db_data = new db_query("SELECT MONTH(orc_created_at) as month,orc_user_id use_id,0 as use_referral_id,SUM(case when orc_is_owner = 1 AND (SELECT 1 FROM orders WHERE ord_id = orc_order_id AND (ord_status_code = 'RECEIVED' OR ord_status_code = 'SUCCESS')) = 1 then orc_point end) total_point
        FROM users 
        INNER JOIN order_commissions ON orc_user_id = use_id
        WHERE orc_user_id = ".input('user_id').$v_where." GROUP BY MONTH(orc_created_at) ORDER BY orc_point DESC");
		
$v_total_money_login = 0;
$v_total_point_login_only = 0; // tổng point của chính nó
$row = $db_data->fetch();
if (!intval($row[0]['use_id'])) {
	$row[0]['use_id'] = input('user_id');
	$row[0]['total_point'] = 0;
}
if (intval($row[0]['use_id']) > 0) {
	$v_arr_point_login = [];
	foreach($row as $key=>$items){
		$v_total_point_login_only += intval($items['total_point']);
		$items['user_point'] = $row[$key]['total_point'];
		$v_arr_point_login[input('user_id')][$row[$key]['month']] = $items;
	}
    if (!function_exists('getPointLitstuser')) {
        function getPointLitstuser($arrUser = array(),$stt = 0,$v_where){
            $rs = [];
            if(check_array($arrUser)){
                $orc_user_id = [];
                foreach($arrUser as $items){
                    $orc_user_id[] = intval($items['use_id']);
                    $items['c_NoRefe'] = 1;
                    $rs[] = $items;
                }
                if(check_array($orc_user_id)){
					$orc_user_id = array_unique($orc_user_id);
                    $orc_user_id_list = implode(',', $orc_user_id);
                    $c_Refe = ($orc_user_id[0] == input('user_id')) ? 1 : 0;
                    $db_data = new db_query("SELECT MONTH(orc_created_at) as month,use_id,
							SUM(case when orc_is_owner = 1 AND (SELECT 1 FROM orders WHERE ord_id = orc_order_id AND (ord_status_code = 'RECEIVED' OR ord_status_code = 'SUCCESS')) = 1 then orc_point end) total_point
                            ,use_referral_id 
                            ,(SELECT ".$c_Refe.") c_Refe
                            ,(SELECT ".$stt.") stt_order
                            FROM users
                                INNER JOIN order_commissions ON orc_user_id = use_id 
                            WHERE use_referral_id IN(".$orc_user_id_list.")
                            ".$v_where."
                            GROUP BY use_id,month
                            ");
					// SELECT use_id, sum(orc_point) total_point ,use_referral_id ,(SELECT 1) c_Refe ,(SELECT 0) stt_order FROM users INNER JOIN order_commissions ON orc_user_id = use_id WHERE use_referral_id IN(1234) AND orc_type = 0 AND orc_status_code != 'NEW' AND orc_created_at >= '2023-01-01 00:00:00' AND orc_created_at <= '2023-01-31 23:59:59' GROUP BY use_id
                    $row1 = $db_data->fetch();
                    if (check_array($row1)){
                        //pre($row1);die;
                        $use_id_check = [];
                        foreach ($row1 as $it){
							// lay danh sach id vùa lay duoc
                            if(!in_array($it['use_referral_id'], $use_id_check)){
                                $use_id_check[] = $it['use_referral_id'];
                            }
                        }
                        // Neu có du lieu thì van co cap con
                        foreach($rs as $key=>$items){
                            if(in_array($items['use_id'], $use_id_check)){
                                $rs[$key]['c_NoRefe'] = 0;
                            }
                        }
                        $db_data->close();
                        $stt++;
                        $rs_1 = getPointLitstuser($row1,$stt,$v_where);
                        if(check_array($rs_1)){
                            $rs = array_merge($rs, $rs_1);
                        }
                    }
                }
                
            }
            return $rs;
        }
    }
    $rs_items = getPointLitstuser($row,0,$v_where);
    //pre($rs_items);die;
    //var_dump(input('user_id'));
    
    if(check_array($rs_items)){
        
        $temp_Refe = [];
        $temp_NoRefe = [];
        $temp_array = [];
		$arr_point_login_by_month = [];
        foreach ($rs_items as $v) {
            $v_total_point_login += $v['total_point'];
			$arr_point_login_by_month[$v['month']] = intval($arr_point_login_by_month[$v['month']]) + $v['total_point'];
			$v['user_point'] = $v['total_point'];
            if(input('user_id') == $v['use_id']){
                continue;
            }
            if (!isset($temp_array[$v['use_id']])){
                $temp_array[$v['stt_order']][$v['use_id']][$v['month']] = $v;
            }
            if($v['c_Refe']){
                $temp_Refe[] = $v['use_id'];
            }
            if($v['c_NoRefe']){
                $temp_NoRefe[] = $v;
            }
        }
        $Setting = Setting::where('swe_key', 'LIKE', 'tong_gia_tri_point_huong_uu_dai_%');
        $configData= $Setting->select_all();
        $arrconfigData = array();
        foreach($configData as $items){
            $arrconfigData[] = explode('_', $items->swe_value_vn);
        }
        $arrconfigData=php_multisort($arrconfigData, array(array('key'=>'0','sort'=>'desc')));
        // Tính số tiền user login theo point
		if(check_array($arr_point_login_by_month)){
			foreach($arr_point_login_by_month as $point_login){
				$v_point_login = intval($point_login);
				$v_percent_login= 0;
				foreach($arrconfigData as $data){
					$v_money_conf = $data[0];
					if($v_point_login >=$v_money_conf){
						$v_percent_login = $data[1];
						break;
					}
				}
				// Tính toán tien theo %cau hinh
				if($v_percent_login > 0){
					$v_total_money_login += $v_point_login * ($v_percent_login/100);
				}
			}
		}
        $v_total_money_login_f1 = 0;
        if($v_total_money_login > 0 || intval($user_id_member)  >0){
            if(check_array($temp_array)){
                // Sap xep theo các câp thap uu tiên lên tren de lap
				for($i=count($temp_array)-1;$i>0;$i--){
					$dataArr = $temp_array[$i];
					foreach($dataArr as $key=>$items){
						foreach($items as $month_point){
							$month = $month_point['month'];
							$refererId = $month_point['use_referral_id'];
							$temp_array[$i -1][$refererId][$month]['total_point'] += $month_point['total_point'];
							if(!isset($temp_array[$i -1][$refererId][$month]['c_Refe']) && in_array($refererId,$temp_Refe)){
								$temp_array[$i -1][$refererId][$month]['c_Refe'] = 1;
							}
						}
					}
				}
				$rs_items = $temp_array[0];
				if (!function_exists('getMoneyLeaderGroupOnly')) {
					function getMoneyLeaderGroupOnly($temp_array = []){
						// Tính toán tien cho tat ca user
                        $Setting = Setting::where('swe_key', 'LIKE', 'diem_mkt_thuong_lanh_dao_%');
                        $configData= $Setting->select_all();
						foreach($configData as $items){
							if($items->swe_key == 'diem_mkt_thuong_lanh_dao_1'){
								$moneyNoMarket = $items->swe_value_vn;// Số tiền không tính < 1tr
							}
							if($items->swe_key == 'diem_mkt_thuong_lanh_dao_2'){
								$moneyMaxOneGroupOnly = $items->swe_value_vn;  //  Số tiền quy định có 1 nhóm độc  lập của chính  f0
							}
							if($items->swe_key == 'diem_mkt_thuong_lanh_dao_3'){
								$moneyMaxGroupOnly = $items->swe_value_vn;  //  Số tiền quy định nhóm độc lập
							}
							if($items->swe_key == 'diem_mkt_thuong_lanh_dao_4'){
								$percentCommit = $items->swe_value_vn;  //  Số tiền quy định nhóm độc lập
							}
						}

						$arrGroupOnly = [];
						for($i=count($temp_array)-1;$i>=0;$i--){
	                        $dataArr = $temp_array[$i];
							foreach($dataArr as $month => $itemMonth){
								foreach($itemMonth as $key=>$items){
									$total_point = intval($items['user_point']);
									$month = intval($items['month']);
									$use_referral_id = intval($items['use_referral_id']);
									$use_id = $items['use_id'];
									$arrGroupOnly[$use_id][$key]['total_point'] = $total_point;
									 //  Nếu Nhóm bên dưới <10tr thì cộng với chính user xem có xem lớn  hơn 10tr không để tính thưởng cho cấp cha
									$moneyChildren =intval($arrGroupOnly[$use_id][$key]['moneyGroupChi']);
									if(($moneyChildren+$total_point) > $moneyMaxGroupOnly){
										$arrGroupOnly[$items['use_referral_id']][$key]['countGroupOnly']= $arrGroupOnly[$items['use_referral_id']][$key]['countGroupOnly']+1; 
										$arrGroupOnly[$items['use_referral_id']][$key]['moneyGroupOnly'] =  $moneyChildren+$total_point;
									}else{
										$arrGroupOnly[$items['use_referral_id']][$key]['moneyGroupNoOnly'] += $total_point;
										$total_point = $moneyChildren+ $total_point; // Cộng với cấp con nhỏ hơn 10tr để tính thưởng cho referrer
										$arrGroupOnly[$items['use_referral_id']][$key]['moneyGroupChi'] += $total_point;
									}
								}
							}
	                    }
	                    if(check_array($arrGroupOnly)){
							foreach($arrGroupOnly as $userId => $itemMonth){
								foreach($itemMonth as $key =>$items){
									// Điểm mkt phải lớn hơn 1tr va có nhóm độc l
									if(intval($items['countGroupOnly']) > 0 && intval($items['total_point']) > $moneyNoMarket){
										$moneyTotal = intval($items['total_point']) + intval($items['moneyGroupChi']);
										//  Nếu có  1 nhóm  độc  lập và tổng mkt + các  cấp  con <  4tr thì k  đuoc thương
										if(intval($items['countGroupOnly']) == 1 && $moneyTotal  <$moneyMaxOneGroupOnly){
											$arrGroupOnly[$userId][$key]['moneyTotalmketingGroup'] = 0;
											continue;
										}
										if($moneyTotal < $moneyMaxGroupOnly){
											$arrGroupOnly[$userId][$key]['moneyTotalmketingGroup'] =  ($percentCommit/100)*(intval($items['moneyGroupOnly']) -($moneyMaxGroupOnly - $moneyTotal));
										}else{
											$arrGroupOnly[$userId][$key]['moneyTotalmketingGroup'] =  ($percentCommit/100)*intval($items['moneyGroupOnly']);
										}
										
										continue;
									}
									$arrGroupOnly[$userId][$key]['moneyTotalmketingGroup'] = 0;
								}
							}
							$v_temp = [];
							foreach($arrGroupOnly as $userId => $itemMonth){
								foreach($itemMonth as $key =>$items){
									$v_temp[$userId]['moneyGroupNoOnly'] += intval($items['moneyGroupNoOnly']);
									$v_temp[$userId]['moneyGroupChi'] += intval($items['moneyGroupChi']);
									$v_temp[$userId]['total_point'] += intval($items['total_point']);
									$v_temp[$userId]['moneyTotalmketingGroup'] += intval($items['moneyTotalmketingGroup']);
									$v_temp[$userId]['countGroupOnly'] += intval($items['countGroupOnly']);
									$v_temp[$userId]['moneyGroupOnly'] += intval($items['moneyGroupOnly']);
								}
							}
							$arrGroupOnly = $v_temp;
	                    }
	                    return $arrGroupOnly;
					}
				}
				
				// tính toan user point danh cho màn hình chi tiết. Cộng tổng tất cả f1 rồi mới tính số tiền	
				//pre($temp_array);
				$arrTemp[0] = $v_arr_point_login;
				$temp_array = array_merge($arrTemp,$temp_array);
                $v_arr_f1_list = [];
				$v_arr_point_mkt = [];
				foreach ($rs_items as $key=>$items){
					foreach($items as $pointMonth){
						if($pointMonth['c_Refe'] == 1){
							$total_point = $pointMonth['total_point'];
							$v_percent = 0;
							foreach($arrconfigData as $data){
								$v_money_conf = $data[0];
								if($total_point >=$v_money_conf){
									$v_percent = $data[1];
									break;
								}
							}
							if($v_percent > 0){
								$v_arr_point_mkt[$key]['money'] = ($total_point*($v_percent/100));
								$v_total_money_login_f1 = $v_total_money_login_f1+($total_point*($v_percent/100));
								$v_total_money_login = $v_total_money_login-($total_point*($v_percent/100));

								$v_arr_point_mkt[$key]['total_money_member'] = ($total_point*($v_percent/100));
							}
							$v_arr_point_mkt[$key]['total_point'] = $v_arr_point_mkt[$key]['total_point'] + $total_point;
							$v_arr_point_mkt[$key]['user_point'] = $v_arr_point_mkt[$key]['user_point'] + $total_point;
						}
					}
				}
				$rs_items = $v_arr_point_mkt;
				// Tính toán lấy thưởng lãnh  đạo
				$arrGroupOnly  = getMoneyLeaderGroupOnly($temp_array);
                $moneyCommissionGroupOnly  =  0;
                $countGroupOnly  =  0;
                $moneyGroupOnly  =  0;
                $moneyGroupChi  =  0;
                if(isset($arrGroupOnly[input('user_id')])){
                    $moneyCommissionGroupOnly = $arrGroupOnly[input('user_id')]['moneyTotalmketingGroup'];
                    $countGroupOnly = $arrGroupOnly[input('user_id')]['countGroupOnly'];
                    $moneyGroupOnly = $arrGroupOnly[input('user_id')]['moneyGroupOnly'];
                    $moneyGroupChi = $arrGroupOnly[input('user_id')]['moneyGroupChi'];
                }
               	// Trả về  danh sách mảng f1
                if($user_id_member > 0){
                	foreach ($rs_items as $key => $value) {
                		# code...
                		$use_id = $value['use_id'];
                		$rs_items[$key]['moneyCommissionGroupOnly'] = $arrGroupOnly[$use_id]['moneyTotalmketingGroup'];
                		$rs_items[$key]['countGroupOnly'] = intval($arrGroupOnly[$use_id]['countGroupOnly']);
                		$rs_items[$key]['moneyGroupOnly'] = intval($arrGroupOnly[$use_id]['moneyGroupOnly']);
                		$rs_items[$key]['moneyGroupChi'] = intval($arrGroupOnly[$use_id]['moneyGroupChi'] + $rs_items[$key]['user_point']);
                		$rs_items[$key]['total_point_member'] = intval($rs_items[$key]['user_point']);
                	}
                	return [
    					'vars' =>$rs_items
    				];
                }
            }
        }
    }
}

return [
    'vars' => [
        'total_money_member' => intval($v_total_money_login)+intval($v_total_money_login_f1),   // Thưởng cá nhân
        'total_money_member_report' => intval($v_total_money_login),  // thuong  ca nhan tren bao cao
        'total_money_member_f1' => intval($v_total_money_login_f1),   // Tổng thưởng F1
        'total_point' => intval($v_total_point_login),
        'total_point_member' => intval($v_total_point_login_only),  // Điểm marketing cá nhân
        'total_point_f1' => intval($v_total_point_login) - intval($v_total_point_login_only),  // Điểm marketing các F1
        'moneyCommissionGroupOnly'=> intval($moneyCommissionGroupOnly), //  Thưởng lãnh đạo
        'countGroupOnly'=> intval($countGroupOnly), // Số nhóm độc lập
        'moneyGroupOnly'=> intval($moneyGroupOnly), // MKT nhóm độc lập
        'moneyGroupChi'=> intval($moneyGroupChi + $v_total_point_login_only),  //  MKT ngoài nhóm độc lập. (Tổng điểm marketing cá nhân + các F1 không độc lập (Nếu F1 có nhóm độc lập thì phải trừ đi điểm của nhóm độc lập đó, chỉ tính phần chưa đủ điều kiện độc lập)
    ]
];