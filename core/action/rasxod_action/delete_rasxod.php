<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

	header('Content-type: Application/json');

	if(isset($_POST['delete_rasxod'])) {

		$note_id = $_POST['delete_rasxod'];

		$delete_note_query = $dbpdo->prepare("DELETE FROM rasxod WHERE rasxod_id = ?");
		$delete_note_query->execute([$note_id]);


		$error_mgs = [ 'success' => 'true' ];									  								   
		//выводим сообщение и останавливаем выполнение заказа
		echo json_encode($error_mgs);
		exit();		

	}