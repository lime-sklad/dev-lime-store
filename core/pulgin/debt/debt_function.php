<?php 
//фУНКЦИИ для плагина  - debt 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';


//default tab
function debt_default_page() {
	$link = '/core/pulgin/debt/page/debt_history.php';
	return $link;
}

//получаем список
function debt_customer_history($customer_id) {
	global $dbpdo;
	//customer_id
    $id = $customer_id;
	$cust_arr = [];
	$query = $dbpdo->prepare('SELECT * FROM user_control
		INNER JOIN customer_basket ON customer_basket.customer_id = :id
		AND customer_basket.basket_stock_count > 0 
		AND customer_basket.basket_visible = 0

		LEFT JOIN stock_list 
		ON stock_list.stock_id = customer_basket.basket_stock_id
		AND stock_list.stock_visible = 0

		GROUP BY stock_list.stock_id DESC
	');
	$query->bindParam('id', $id, PDO::PARAM_INT);
	$query->execute();

	while ($row = $query->fetch(PDO::FETCH_BOTH))
		$cust_arr[] = $row;
	foreach ($cust_arr as $row) {
		$stock_id 				= $row['stock_id'];			
		$stock_name 			= $row['stock_name'];				
		$stock_first_price 		= $row['stock_first_price'];	
		$stock_second_price		= $row['stock_second_price'];
		$stock_count			= $row['stock_count'];
		$stock_provider			= $row['stock_provider'];	
		$stock_imei 			= $row['stock_phone_imei'];
		$stock_date 			= $row['stock_get_fdate'];			
		$stock_return_status 	= $row['stock_return_status'];
		$return_image           = '';

		if($stock_return_status == '1') {
			$return_image = $stock_return_image;
		}
		
		$get_tamplate = array(
			'stock_id' 					=> $stock_id,
			'stock_date' 				=> $stock_date,
			'stock_name' 				=> $stock_name,
			'stock_imei' 				=> $stock_imei,
			'stock_first_price' 		=> $stock_first_price,
			'stock_second_price' 		=> $stock_second_price,
			'stock_provider' 			=> $stock_provider,
			'manat_image' 				=> $manat_image,
			'stock_return_image' 		=> $return_image 
		);

		echo get_stock_phone_table_row($get_tamplate);	

	}
		

}