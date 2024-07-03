<?php
require_once 'inc_security.php';

if(!empty($_POST['idArr'])){
    $idArr = json_decode($_POST['idArr']);
    $v_arr_temp = [];
    for($i=0;$i <count($idArr);$i++){
        $id = intval($idArr[$i]);
        if($id > 0){
            $item = \App\Models\Moneyloadbatch::findByID($id);
            if ($item && $item->status !=1) {
                $result['message'] = 'Tồn tại bản ghi có trạng thái không hợp lệ.';
                $result['suscess'] = 0;
                echo json_encode($result);die;
            }
        }
    }
}
die;
?>
