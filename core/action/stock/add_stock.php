<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

$data = [];
$arr = [];
$col_post_list = [
	'add_stock_name' => [
		'col_name' => 'stock_name',
		'required' => true
	],
	'add_stock_description' => [ 
		'col_name' => 'stock_phone_imei' 
	],
	'add_stock_provider_id' => [
		'col_name' => 'product_provider'
	],
	'add_stock_category_id' => [
		'col_name' => 'product_category'
	],
	'add_stock_count' => [
		'col_name' => 'stock_count'
	],	
	'add_stock_min_quantity' => [
		'col_name' => 'min_quantity_stock'
	],
	'add_stock_first_price' => [
		'col_name' => 'stock_first_price' 
	],
	'add_stock_second_price' => [
		'col_name' => 'stock_second_price' 
	],	
];

if(!empty($_POST) && count($_POST) > 0) {
	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $_POST)) {
			$data = array_merge($data, [
				$value['col_name'] => $_POST[$key]
			]);
		}
	}

	$default_data = [
		'stock_visible' 	=> 0,
		'stock_get_fdate' 	=> date("d.m.Y"),
		'stock_get_year' 	=> date("m.Y"),
		'product_added' 	=> getUser('get_id')
	];

	$data = array_merge($data, $default_data);


	// ls_var_dump($data);

	try {
		ls_db_insert('stock_list', [$data]);

		echo json_encode([
			'success' => 'bla-bla'
		]);
	} catch (Exception $e) {
		echo json_encode([
			'error' => "Ошибка"
		]);
	}

}