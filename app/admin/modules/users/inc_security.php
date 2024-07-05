<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 14;

$module_name = "Thành viên";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "users";
$id_field = "use_id";
$name_field = "use_name";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/images/";

$per_page = 10;

$fs_fieldupload = "use_avatar";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

$genders = [
    -1 => 'Chọn giới tính',
    0 => 'Nữ',
    1 => 'Nam'
];

$status_arr = [
    0 => 'Chưa kích hoạt',
    1 => 'Đã kích hoạt'
];

$premium_arr = [
    0 => 'Người dùng thường',
    1 => 'Người dùng đặc biệt'
];

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);

$survey_job_arr = [
    0 => '-- Lựa chọn nghề nghiệp --'
    ,1 => 'Học sinh - Sinh viên'
    ,2 => 'Hưu trí'
    ,3 => 'Công nhân'
    ,4 => 'Nông dân'
    ,5 => 'Lực lượng vũ trang'
    ,6 => 'Trí thức'
    ,7 => 'Hành chính văn phòng'
    ,8 => 'Y tế'
    ,9 => 'Dịch vụ'
    ,10 => 'Công an'
    ,11 => 'Bộ đội - Quân nhân'
    ,12 => 'Việt kiều'
    ,13 => 'Khác'
];

$survey_regis_reason_arr = [
    0 => '--Lựa chọn lý do--'
    ,1 => 'Sử dụng sản phẩm'
    ,2 => 'Kinh doanh - Thêm thu nhập'
];
$survey_busined_arr = [
    0 => '--Lựa chọn thông tin--'
    ,1 => 'Đã từng kinh doanh'
    ,2 => 'Chưa từng kinh doanh'
];

$survey_busines_date_arr = [
    0 => '--Lựa chọn thông tin--'
    ,1 => 'Chưa có kinh nghiệm'
    ,2 => 'Dưới 1 năm'
    ,3 => 'Từ 1 đến 3 năm'
    ,4 => 'Trên 3 năm'
];

$survey_busines_desired_arr = [
    0 => '--Lựa chọn thông tin--'
    ,1 => 'Bán lẻ'
    ,2 => 'Theo đội - nhóm'
];

$use_job = [
  0=>'--Lựa chọn thông tin--' 
  ,1=>'Quầy thuốc' 
  ,2=>'Nhà thuốc' 
  ,3=>'Phòng khám' 
  ,4=>'Công ty dược phẩm' 
  ,5=>'Bệnh viện' 
  ,6=>'Nha khoa' 
  ,7=>'Thẩm mỹ viện' 
  ,8=>'Trung tâm y tế' 
  ,9=>'Bệnh nhân' 
  ,10=>'Dược sĩ' 
  ,11=>'Khác' 
];

$sourceUser = [
    0=>'--Lựa chọn thông tin--' 
  ,1=>'Facebook' 
  ,2=>'Zalo' 
  ,3=>'Được giới thiệu' 
];
$typeAccountUser = [ // Loại tài khoản
    0=>'--Lựa chọn thông tin--' 
  ,1=>'Tài khoản tiêu dùng' 
  ,2=>'Tài khoản kinh doanh'
];

?>