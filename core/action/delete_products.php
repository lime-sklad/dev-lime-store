<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

//получем список категорий товаров
get_product_category();

header('Content-type: Application/json');


//удалить товар
if(isset($_POST['delete_products'])) {

	$product_id = $_POST['delete_products'];

	$delteStock = $dbpdo->prepare("UPDATE stock_list SET stock_visible = 1 
		WHERE stock_id = :product_id");
	$delteStock->bindParam('product_id', $product_id, PDO::PARAM_INT);
	$delteStock->execute();

	$success = array('ok' => 'ok');

	echo json_encode($success);

}
