<?php
require_once("bootstrap.php");
require_once 'resource/PHPExcel/Classes/PHPExcel.php';


$fs_filepath = "/home/oo91mah3s4eq/public_html/public/temp/vuaduoc_user.xlsx";
$inputFileType = PHPExcel_IOFactory::identify($fs_filepath);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($fs_filepath);
$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$i=0;
$j=0;
$inserdata =array();
$dataerr =array();
foreach ($allDataInSheet as $value) {
	if(empty($value['B']) || !intval($value['E'])){
		continue;
	}
	$inserdata[$i]['name'] 	= $value['A'];
    $inserdata[$i]['addres'] = $value['B'];
	$inserdata[$i]['district'] = $value['C'];
    $inserdata[$i]['provice'] = $value['D'];
    $inserdata[$i]['phone'] = $value['E'];
	$i++;
}

for($j=0;$j<count($inserdata);$j++){
    $phone = $inserdata[$j]['phone'];
    $name = $inserdata[$j]['name'];
    $provice = $inserdata[$j]['provice'];
    $district = $inserdata[$j]['district'];
    $name = explode("-",$name)[0];
    $addres = $inserdata[$j]['addres']." ( ".$inserdata[$j]['provice']." )";
    $check_user = \App\Models\Users\Users::where('use_login=\'' . $phone . '\' AND use_active != -99')->find();
    if ($check_user) {
        continue;
    }

    $confirm_code = rand(1000, 9999);

    $user = [
        'use_active' => 0,
        'use_login' => $phone,
        'use_loginname' => $phone,
        'use_mobile' => $phone,
        'use_phone' => $phone,
        'use_password' => md5($phone), // mặc định passwork là sdt đăng ký
        'use_gender' => 1,
        'use_email' => "",
        'use_name' => $name,
        'use_referral_id' => 0,
        'use_register_confirm_code' => $confirm_code,
        'use_avatar' => 'a4cdd34e884618b1bbf8568cfb56e082.jpg',
        'use_province_id' => 0,
        'use_district_id' => 0,
        'use_ward_id' => 0,
        'use_address_register' => $addres,
        'use_province_registry' => $provice,
        'use_district_registry' => $district,
        'use_job_code' => 1,
        'use_referer_code'=>'',
        'use_partner_note'=>'',
        'use_content'=>""
    ];
    $user_id = \App\Models\Users\Users::insert($user);
}