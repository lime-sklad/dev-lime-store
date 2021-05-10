<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/function.php';

// $value = $_GET['value'];

// $type = $_GET['type'];


// // $val = '("'.implode('","', $value).'")';

$id_arr = [];

$id = $_GET['id'];

$ids = implode(',', $id);

$id_arr = $id;

$place_holders = implode(',', array_fill(0, count($id_arr), '?'));

$filter_prod = [];
$get_product = $dbpdo->prepare("SELECT * FROM user_control 
								LEFT JOIN stock_filter ON stock_filter.filter_id IN ($place_holders)


								INNER JOIN stock_list ON stock_list.stock_id = stock_filter.stock_id
								
								GROUP BY stock_list.stock_id  DESC
								");

$get_product->execute($id_arr);


	while($row = $get_product->fetch(PDO::FETCH_BOTH))
		$filter_prod [] = $row;
		
		foreach ($filter_prod as $row) {
	$stock_id 				= $row['stock_id'];
	$stock_name 			= $row['stock_name'];
	$stock_imei 			= $row['stock_phone_imei'];
	$stock_first_price 		= $row['stock_first_price'];
	$stock_second_price 	= $row['stock_second_price'];
	$stock_return_status 	= $row['stock_return_status'];
	$stock_provider		 	= $row['stock_provider'];
	
	$get_product_table = array( 'stock_id' 				=> 	$stock_id,
								'stock_name' 			=> 	$stock_name,
								'stock_phone_imei' 		=> 	$stock_imei,
								'stock_first_price' 	=> 	$stock_first_price, 
								'stock_second_price' 	=> 	$stock_second_price,
								'stock_return_status' 	=> 	$stock_return_status,
								'stock_provider'		=> 	$stock_provider, 
								'manat_image' 			=>  $manat_image,
								'stock_return_image' 	=>  $stock_return_image
							);

	get_terminal_phone_table_row($get_product_table);	
}