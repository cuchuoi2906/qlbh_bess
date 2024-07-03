<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
//checkAddEdit("delete");

$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("survey_users.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    //Delete data with ID
    $sqlDelete = "DELETE FROM survey_users WHERE survey_id  = " . $recordId;
    $db_del = new db_execute($sqlDelete, 1);

    $msg = " Đã xóa thành công bản ghi !";
    unset($db_del);
    echo '<script>alert("'.$msg.'")</script>';
}

redirect(url_back());
?>