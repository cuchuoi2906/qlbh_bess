<?php

$vars = false;

$user_id = input('id');
$tax_code = input('tax_code');
$cccd = input('cccd');
$business_license = input('business_license');
$pharma_license = input('pharma_license');
$gpp_cert = input('gpp_cert');

        
$affected = false;
$user = \App\Models\Users\Users::findByID((int)$user_id);

if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}

if (!preg_match('/^[a-zA-Z0-9]*$/', input('code'))) // '/[^a-z\d]/i' should also work.
{
    throw new RuntimeException('Mã giới thiệu chỉ được bao gồm chữ và số', 400);
}


$user->tax_code  = $tax_code;
if($cccd != ""){ 
    $user->cccd_img  = $cccd;
}
if($business_license != ""){
    $user->business_license_img  = $business_license;
}
if($pharma_license != ""){
    $user->pharma_license_img  = $pharma_license;
}
if($gpp_cert != ""){
    $user->gpp_cert_img  = $gpp_cert;
}
$affected = $user->update();

return [
    'vars' => $affected
];