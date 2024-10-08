<?php
require_once("bootstrap.php");
require_once 'resource/PHPExcel/Classes/PHPExcel.php';


$fs_filepath = "/home/oo91mah3s4eq/public_html/public/temp/vuaduoc_sp.xlsx";
$inputFileType = PHPExcel_IOFactory::identify($fs_filepath);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($fs_filepath);
$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$i=0;
$j=0;
$inserdata =array();
$dataerr =array();

foreach ($allDataInSheet as $value) {
	if(empty($value['B']) || !intval($value['A'])){
		continue;
	}
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

	$inserdata[$i]['name'] 	= $value['B'];
	$inserdata[$i]['code'] 	= $value['C'];
	//$inserdata[$i]['price'] = intval(str_replace(',','',$value['D']));
	$inserdata[$i]['price'] = 0;
	$inserdata[$i]['hot'] 	= intval($value['E']);
	//$inserdata[$i]['money_type'] = $value['D'];
	$i++;
}

$use_name = getValue('money_file_name', 'str', 'POST', '');
for($j=0;$j<count($inserdata);$j++){
	$data = [];
	$pri_created_at = gmdate("Y-m-d H:i:s", time());
	
	$myform = new generate_form();
	//myform->add('money_type', 'money_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money_type'], 1);
	$myform->add('pro_name_vn', 'pro_name_vn', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['name'], '');
	$myform->add('pro_price', 'pro_price', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['price'], '');
	$myform->add('pro_is_hot', 'pro_is_hot', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['hot'], 0);
	$myform->add('pro_code', 'pro_code', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['code'], 1, 'Bạn chưa nhập mã sản phẩm', 1, 'Mã sản phẩm đã tồn tại. Vui lòng kiểm tra lại');
	
	$myform->addTable('products');
	$myform->evaluate();
	
	$source  = '/home/oo91mah3s4eq/public_html/public/temp/img_product.jpg';
	$sourceNew  = '/home/oo91mah3s4eq/public_html/public/upload/products/'.$inserdata[$j]['code'].'.jpg';

    if (copy($source,$sourceNew)) {	
	
		$db_excute = new db_execute_return();
        $last_id = $db_excute->db_execute("
			INSERT INTO products (pro_name_vn,pro_teaser_vn, pro_price,pro_discount_price,pro_code,pro_active,pro_quantity,pro_category_id,pro_type, pro_is_hot)
				VALUES ('".$inserdata[$j]['name']."','".$inserdata[$j]['name']."', ".$inserdata[$j]['price'].",".$inserdata[$j]['price'].",'".$inserdata[$j]['code']."',1,1,238,'ORDERFAST',".$inserdata[$j]['hot'].")
				ON DUPLICATE KEY UPDATE 
				pro_name_vn = '".str_replace("'",'',$inserdata[$j]['name'])."',
				pro_teaser_vn = '".str_replace("'",'',$inserdata[$j]['name'])."',
				pro_price = ".$inserdata[$j]['price'].",
				pro_discount_price = ".$inserdata[$j]['price'].",
				pro_code = '".$inserdata[$j]['code']."',
				pro_active = 1,
				pro_quantity = 1,
				pro_category_id = 238,
				pro_type = 'ORDERFAST',
				pro_is_hot = ".$inserdata[$j]['hot'].";
		
		");
		// Kiểm tra xem ID có được lấy thành công hay không
		/*if ($last_id) {
			$insert_image = new db_query("
				INSERT INTO products_images (pri_product_id, pri_file_name,pri_is_avatar) 
				VALUES ('$last_id', '".$inserdata[$j]['code'].".jpg',1)
			");
		}*/
		//$db_excute->close();
	}
	if($j == 500){
		//usleep(5000);
	}
}