<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

	header('Content-type: Application/json');

	if(isset($_POST['note_delete_id'])) {

		$note_id = $_POST['note_delete_id'];

		$delete_note_query = $dbpdo->prepare("DELETE FROM no_availible_order WHERE order_stock_id = ?");
		$delete_note_query->execute([$note_id]);


		$error_mgs = [ 'success' => 'true' ];									  								   
		//выводим сообщение и останавливаем выполнение заказа
		echo json_encode($error_mgs);
		exit();		

	}