<?php
require_once '../../function.php';

header('Content-type: Application/json');

//получем категорию товара
get_product_category();

if(isset($_POST['update_note'])) {

	if(!empty($_POST['update_note'] && $_POST['get_upd_name'] && $_POST['get_upd_dsecrpt'])) {

		$note_id 			= $_POST['update_note'];
		$note_name 			= $_POST['get_upd_name'];
		$note_description 	= $_POST['get_upd_dsecrpt'];

		$upd_note = $dbpdo->prepare('UPDATE no_availible_order 
			SET order_stock_name = :note_name,
			order_stock_description = :note_description
			WHERE order_stock_id = :note_id');
		$upd_note->bindValue('note_name', $note_name, PDO::PARAM_INT);
		$upd_note->bindValue('note_description', $note_description, PDO::PARAM_STR);
		$upd_note->bindValue('note_id', $note_id, PDO::PARAM_INT);
		$upd_note->execute();

		$success = array('success' => 'ok', );

		echo json_encode($success);
		exit();

	}

}