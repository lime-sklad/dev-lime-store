<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

//получем категорию товара
get_product_category();

if(isset($_POST['update_rasxod'])) {

	if(!empty($_POST['update_rasxod'] && $_POST['get_upd_name'] && $_POST['get_upd_dsecrpt'])) {

		$note_id 			= $_POST['update_rasxod'];
		$note_name 			= $_POST['get_upd_name'];
		$note_description 	= $_POST['get_upd_dsecrpt'];

		$upd_note = $dbpdo->prepare('UPDATE rasxod 
			SET rasxod_money = :note_name,
			rasxod_description = :note_description
			WHERE rasxod_id = :note_id');
		$upd_note->bindValue('note_name', $note_name, PDO::PARAM_INT);
		$upd_note->bindValue('note_description', $note_description, PDO::PARAM_STR);
		$upd_note->bindValue('note_id', $note_id, PDO::PARAM_INT);
		$upd_note->execute();

		$success = array('success' => 'ok', );

		echo json_encode($success);
		exit();

	}

}