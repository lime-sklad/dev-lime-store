<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');


//удалить товар
if(isset($_POST['stock_id']) && !empty($_POST['stock_id'])) {

	$product_id = $_POST['stock_id'];

	$update_data = [
		'before' => 'UPDATE stock_list SET ',
		'after' => ' WHERE stock_id = :prod_id ',
		'post_list' => [
			'id' => [
				'query' => ' stock_list.stock_visible = 1 ',
				'bind' => 'prod_id'
			]
		]
	];

	echo ls_db_upadte($update_data, [
		'id' => $product_id
	]);

	
} else {
	echo json_encode(['error' => 'Cant find id']);
}
