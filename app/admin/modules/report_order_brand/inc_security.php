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
$array_view_option = [
    1 => 'Cá nhân',
    2 => 'Đội nhóm',
];

$v_sub_querry_select = "
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'NEW' <!--FILLTER_DATE-->) totalNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'PENDING' <!--FILLTER_DATE-->) totalPendingNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'BEING_TRANSPORTED' <!--FILLTER_DATE-->) totalBeginTransportNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'RECEIVED' <!--FILLTER_DATE-->) totalReceicedNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'SUCCESS' <!--FILLTER_DATE-->) totalSuccessNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE a.use_referral_id = users.use_id AND ord_status_code = 'CANCEL' <!--FILLTER_DATE-->) totalCancelNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND  a.use_referral_id = users.use_id AND ord_payment_type = 'COD' <!--FILLTER_DATE-->) totalCodNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND ord_payment_type = 'WALLET' <!--FILLTER_DATE-->) totalWalletNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND ord_payment_type = 'ONLINE' <!--FILLTER_DATE-->) totalOnlineNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND ord_payment_type = 'BANKING' <!--FILLTER_DATE-->) totalBankNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND ord_payment_status = 0 <!--FILLTER_DATE-->) totalUnPaymentNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND ord_payment_status = 1 <!--FILLTER_DATE-->) totalPaymentNewreferral
,(SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id <!--FILLTER_DATE-->) totalAmountNewreferral
,(SELECT SUM(ord_shipping_fee) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id <!--FILLTER_DATE-->) totalShippingFeeNewreferral
,(SELECT SUM(ord_auto_shipping_fee) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id <!--FILLTER_DATE-->) totalAutoShippingFeeNewreferral
,(SELECT SUM(orc_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id INNER JOIN order_commissions ON orc_order_id = ord_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND orc_type = 0 AND orc_is_direct = 0 <!--FILLTER_DATE-->) totalOrcAmountNewreferral
,(SELECT SUM(orc_vat) FROM users a INNER JOIN orders ON ord_user_id = a.use_id INNER JOIN order_commissions ON orc_order_id = ord_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id AND orc_type = 0 AND orc_is_direct = 0 <!--FILLTER_DATE-->) totalOrcVatNewreferral
,(IFNULL((SELECT SUM(ord_amount) FROM users a INNER JOIN orders ON ord_user_id = a.use_id WHERE ord_status_code != 'CANCEL' AND a.use_referral_id = users.use_id <!--FILLTER_DATE-->),0)+IFNULL(SUM(case when ord_status_code != 'CANCEL' then ord_amount end),0)) AS totalreferralamountorder ";

$v_sub_querry_select_total = "
    ,SUM(a.totalNewreferral) as totalNewreferralAll
    ,SUM(a.totalPendingNewreferral) as totalPendingreferralAll
    ,SUM(a.totalBeginTransportNewreferral) as totalBeginTransportreferralAll
    ,SUM(a.totalReceicedNewreferral) as totalReceicedreferralAll
    ,SUM(a.totalSuccessNewreferral) as totalSuccessreferralAll
    ,SUM(a.totalCancelNewreferral) as totalCancelreferralAll
    ,SUM(a.totalCodNewreferral) as totalCodreferralAll
    ,SUM(a.totalWalletNewreferral) as totalWalletreferralAll
    ,SUM(a.totalOnlineNewreferral) as totalOnlinereferralAll
    ,SUM(a.totalBankNewreferral) as totalBankreferralAll
    ,SUM(a.totalUnPaymentNewreferral) as totalUnPaymentreferralAll
    ,SUM(a.totalPaymentNewreferral) as totalPaymentreferralAll
    ,SUM(a.totalAmountNewreferral) as totalAmountreferralAll
    ,SUM(a.totalShippingFeeNewreferral) as totalShippingFeereferralAll
    ,SUM(a.totalAutoShippingFeeNewreferral) as totalAutoShippingFeereferralAll
    ,SUM(a.totalOrcAmountNewreferral) as totalOrcAmountreferralAll
    ,SUM(a.totalOrcVatNewreferral) as totalOrcVatreferralAll
";
$brands = App\Models\Categories\Category::where('cat_type', 'BRAND')
    ->all();

$brands = $brands->lists('cat_id', 'cat_name_vn');

?>