<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');


//удалить товар
if(isset($_POST['id']) && !empty($_POST['id'])) {

	$cateogry_id = $_POST['id'];

	$update_data = [
		'before' => 'UPDATE stock_category SET ',
		'after' => ' WHERE category_id = :prod_id ',
		'post_list' => [
			'id' => [
				'query' => ' stock_category.visible = "invisible" ',
				'bind' => 'prod_id',
			]
		]
	];

	echo ls_db_upadte($update_data, [
		'id' => $cateogry_id
	]);

	
} else {
	echo json_encode(['error' => 'Cant find id']);
}
