<?php
require_once("bootstrap.php");
require_once 'resource/PHPExcel/Classes/PHPExcel.php';

$fs_filepath = "/home/oo91mah3s4eq/public_html/public/temp/vuaduoc_sp_gia.xlsx";
$inputFileType = PHPExcel_IOFactory::identify($fs_filepath);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($fs_filepath);
$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$i=0;
$j=0;
$inserdata =array();
$dataerr =array();

foreach ($allDataInSheet as $value) {
	if(empty($value['B'])){
		continue;
	}
    $inserdata[$i]['pro_code'] 	= $value['B'];
    $check_product = \App\Models\Product::where('pro_code=\'' . $value['B'] . '\'')->find();
    if (!$check_product || ($check_product && intval($check_product->pro_price) > 0)) {
        continue;
    }
    $pro_id = $check_product->pro_id;
	# kiếm tra tồn tại sản phẩm
	/*$db_data = new db_query("
		INSERT INTO products (pro_code, name, price, quantity)
			VALUES ('P12345', 'Product Name', 19.99, 10)
			ON DUPLICATE KEY UPDATE 
			name = VALUES(name),
			price = VALUES(price),
			quantity = VALUES(quantity);
	
	");
	$row = $db_data->fetch();
	$inserdata[$i]['update'] = 0;
	if(!check_array($row)){
		$inserdata[$i]['update'] = 1;
	}*/
	$inserdata[$i]['pro_id'] = $pro_id;
	$inserdata[$i]['pro_price'] = intval(str_replace(',','',$value['C']));
	$inserdata[$i]['pro_discount_price'] = intval(str_replace(',','',$value['D']));
	$inserdata[$i][$pro_id]['sl'] 	= intval($value['E']);
	$inserdata[$i][$pro_id]['gia_sl'] 	= intval(str_replace(',','',$value['F']));
	$i++;
}
for($j=0;$j<count($inserdata);$j++){
    $pro_id = $inserdata[$j]['pro_id'];
    \App\Models\Product::where('pro_id', $pro_id)->update([
        'pro_price' => (int)$inserdata[$j]['pro_price'],
        'pro_discount_price' => $inserdata[$j]['pro_discount_price']
    ]);
    $sl = $inserdata[$j][$pro_id]['sl'];
    $gia_sl = $inserdata[$j][$pro_id]['gia_sl'];
    
    $pricePolicy = [
        'ppp_product_id'=>$pro_id,
        'ppp_price'=>$gia_sl,
    ];
    \App\Models\ProductPricePolicy::insert($pricePolicy);
    echo $pro_id.'***';
    
}