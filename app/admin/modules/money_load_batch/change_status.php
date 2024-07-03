<?php
require_once 'inc_security.php';

if(!empty($_POST['idArr'])){
    $idArr = json_decode($_POST['idArr']);
    $v_arr_temp = [];
    for($i=0;$i <count($idArr);$i++){
        $id = intval($idArr[$i]);
        if($id > 0){
            $item = \App\Models\Moneyloadbatch::findByID($id);
            if ($item) {
                if($item->status !=1){
                    $result['message'] = 'Tồn tại bản ghi có trạng thái không hợp lệ';
                    $result['suscess'] = 1;
                    echo json_encode($result);die;
                }
                
                $old_status = $item->status;
                $item->status = 0;
                $item->money_admin_id = $admin_id;
                $item->update();
                $v_arr_temp[] = $id;
                admin_log($admin_id, ADMIN_LOG_ACTION_ACTIVE, $id, 'Đã thay đổi trạng thái yêu cầu nạp tiền (' . $id . ') từ ' . $old_status . ' thành ' . $item->status);
            }
        }
    }
    if(check_array($v_arr_temp)){
        $result['message'] = 'Đã thay đổi trạng thái yêu cầu nạp tiền. Danh sách id: '.implode(',', $v_arr_temp);
        $result['suscess'] = 1;
        echo json_encode($result);die;
    }
}
die;
?>
