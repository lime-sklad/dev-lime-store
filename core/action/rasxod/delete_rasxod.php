<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');


//удалить товар
if(isset($_POST['id']) && !empty($_POST['id'])) {

	$rasxod_id = $_POST['id'];

	$update_data = [
		'before' => 'UPDATE rasxod SET ',
		'after' => ' WHERE rasxod_id = :rasxod_id ',
		'post_list' => [
			'id' => [
				'query' => ' rasxod_visible = 1 ',
				'bind' => 'rasxod_id',
			]
		]
	];

	echo ls_db_upadte($update_data, [
		'id' => $rasxod_id
	]);

	
} else {
	echo json_encode(['error' => 'Cant find id']);
}
