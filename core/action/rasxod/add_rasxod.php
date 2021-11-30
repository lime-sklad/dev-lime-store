<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

include 'rasxod.controller.php';

header('Content-type: Application/json');


if(!empty($_POST) && count($_POST['post_data']) > 0) {
	try {
		add_new_rasxod($_POST['post_data'], $dbpdo);
		
		echo json_encode([
			'success' => 'ok',
		]);	
	} catch (Exception $e) {
		echo json_encode([
			'error' => "Ошибка"
		]);
	}
}