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
    $v_where = " AND ord_created_at >= '".$start_date . " 00:00:00'";
}

if ($end_date) {
    $v_where .= " AND ord_created_at <= '".$end_date . " 23:59:59'";
}

$db_data = new db_query("SELECT ord_user_id use_id,SUM(case when ord_status_code  != 'CANCEL' then ord_amount end) total_amount
        FROM users 
        INNER JOIN orders ON ord_user_id = use_id
        WHERE ord_user_id = ".input('user_id').$v_where." ORDER BY ord_amount DESC LIMIT 1");
		
$v_total_money_login = 0;
$v_total_point_login_only = 0; // tổng point của chính nó
$row = $db_data->fetch();
if (!intval($row[0]['use_id'])) {
	$row[0]['use_id'] = input('user_id');
	$row[0]['total_amount'] = 0;
}
if (intval($row[0]['use_id']) > 0) {
    $row[0]['use_referral_id'] = 0;
    $v_total_point_login_only = $row[0]['total_amount'];
    $rs_items = [];
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
                    $orc_user_id_list = implode(',', $orc_user_id);
                    $c_Refe = ($orc_user_id[0] == input('user_id')) ? 1 : 0;
                    $db_data = new db_query("SELECT use_id,
							SUM(case when ord_status_code  != 'CANCEL' then ord_amount end) total_amount
                            ,use_referral_id 
                            ,(SELECT ".$c_Refe.") c_Refe
                            ,(SELECT ".$stt.") stt_order
                            FROM users
                                INNER JOIN order_commissions ON orc_user_id = use_id 
                            WHERE use_referral_id IN(".$orc_user_id_list.")
                            ".$v_where."
                            GROUP BY use_id
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
    //var_dump(input('user_id'));
    //pre($rs_items);
    
    if(check_array($rs_items)){
        
        $temp_Refe = [];
        $temp_NoRefe = [];
        $temp_array = [];
		//pre($rs_items);die;
        foreach ($rs_items as $v) {
            $v_total_point_login += $v['total_amount'];
            if(input('user_id') == $v['use_id']){
                continue;
            }
            if (!isset($temp_array[$v['use_id']])){
                $temp_array[$v['stt_order']][$v['use_id']] = $v;
            }
            if($v['c_Refe']){
                $temp_Refe[] = $v;
            }
            if($v['c_NoRefe']){
                $temp_NoRefe[] = $v;
            }
        }
		//pre($temp_array);die;
        $Setting = Setting::where('swe_key', 'LIKE', 'tong_gia_tri_point_huong_uu_dai_%');
        $configData= $Setting->select_all();
        $arrconfigData = array();
        foreach($configData as $items){
            $arrconfigData[] = explode('_', $items->swe_value_vn);
        }
        $arrconfigData=php_multisort($arrconfigData, array(array('key'=>'0','sort'=>'desc')));
        // Tính số tiền user login theo point
        $v_percent_login= 0;
        foreach($arrconfigData as $data){
            $v_money_conf = $data[0];
            if($v_total_point_login >=$v_money_conf){
                $v_percent_login = $data[1];
                break;
            }
        }
        $v_total_money_login_f1 = 0;
        if($v_percent_login > 0){
            // Tính toán tien theo %cau hinh
            $v_total_money_login = $v_total_point_login * ($v_percent_login/100);
            
            if(check_array($temp_array)){
                // Sap xep theo các câp thap uu tiên lên tren de lap
				//pre($temp_array);die;
				for($i=count($temp_array)-1;$i>0;$i--){
					$dataArr = $temp_array[$i];
					foreach($dataArr as $key=>$items){
						$refererId = $items['use_referral_id'];
						$temp_array[$i -1][$refererId]['total_amount'] += $items['total_amount'];
					}
				}
				$rs_items = $temp_array[0];
				pre($rs_items);die;
				// tính toan user point danh cho màn hình chi tiết. Cộng tổng tất cả f1 rồi mới tính số tiền
				if($user_id_member > 0){
					$total_point = 0;
					foreach ($rs_items as $items){
						if($items['c_Refe'] == 1){
							$total_point += $items['total_amount'];
						}
					}
					$v_percent = 0;
					foreach($arrconfigData as $data){
						$v_money_conf = $data[0];
						if($total_point >=$v_money_conf){
							$v_percent = $data[1];
							break;
						}
					}
					if($v_percent > 0){
						$v_total_money_login_f1 = $total_point*($v_percent/100);
					}
				}else{// Tinh toan point của user login
					foreach ($rs_items as $key=>$items){
						if($items['c_Refe'] == 1){
							$total_point = $items['total_amount'];
							$v_percent = 0;
							foreach($arrconfigData as $data){
								$v_money_conf = $data[0];
								if($total_point >=$v_money_conf){
									$v_percent = $data[1];
									break;
								}
							}
							if($v_percent > 0){
                                $rs_items[$key]['money'] = ($total_point*($v_percent/100));
								$v_total_money_login_f1 = $v_total_money_login_f1+($total_point*($v_percent/100));
								$v_total_money_login = $v_total_money_login-($total_point*($v_percent/100));
							}
						}
					}
                    //pre($rs_items);
				}
            }
        }
    }
}

return [
    'vars' => [
        'total_money_member' => intval($v_total_money_login),
        'total_money_member_f1' => intval($v_total_money_login_f1),
        'total_point' => intval($v_total_point_login),
        'total_point_member' => intval($v_total_point_login_only),
        'total_point_f1' => intval($v_total_point_login) - intval($v_total_point_login_only),
    ]
];