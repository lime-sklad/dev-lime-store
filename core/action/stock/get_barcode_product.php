<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');


$id = $_POST['id'];
$get_prod = $dbpdo->prepare('SELECT * FROM stock_list WHERE stock_id = :id');
$get_prod->bindParam('id', $id);
$get_prod->execute();
$row = $get_prod->fetch(PDO::FETCH_ASSOC);

$stock_id 				= $row['stock_id'];
$stock_name 			= $row['stock_name'];
$stock_second_price 	= $row['stock_second_price'];
$stock_count 			= $row['stock_count'];
	
$complete = array(
	'stock_id'  		  => $stock_id,	
	'stock_name' 		  => $stock_name, 		
	'stock_second_price'  => $stock_second_price, 
	'stock_count'         => $stock_count, 	 
	'manat_image' 		  => $manat_image 	 	 
);


$json_show = array(
	'param' => $complete
); 

echo json_encode($json_show);



