<?php  

require_once '../../function.php';
//списко категорий товара
get_product_category();
//тип таблицы (terminal, stock, report и тд)
get_table_svervice_type();

//если заметки
if(isset($_POST['get_note_id'])) {

	$id = $_POST['get_note_id'];

	$new_order_list = [];
	$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
		WHERE order_stock_id = :get_note_id
		AND order_stock_visible = 0 ");
	$order_stock_view->bindValue('get_note_id', $id);
	$order_stock_view->execute();	
	$note_row = $order_stock_view->fetch(PDO::FETCH_BOTH);


	$get_note_type		= $note_row['note_type'];
	$note_name 			= $note_row['order_stock_name'];
	$note_descrpt 		= $note_row['order_stock_description'];

	//если запрос по заметки 
	if($get_note_type == $note_category) {
		order_note_template_upd($id, $note_name, $note_descrpt);
	}

}

if(isset($_POST['get_rasxod_id'])) {

	$id = $_POST['get_rasxod_id'];


	$get_rasxod_info_qry = $dbpdo->prepare("SELECT * FROM rasxod WHERE rasxod_id =:get_info_rasxod AND rasxod_visible = 0");
	$get_rasxod_info_qry->bindParam('get_info_rasxod', $id, PDO::PARAM_INT);
	$get_rasxod_info_qry->execute();
	$get_info_row = $get_rasxod_info_qry->fetch();

	$rasxod_day_date 		= $get_info_row['rasxod_day_date'];
	$rasxod_price 			= $get_info_row['rasxod_money'];
	$rasxod_descriptuon 	= $get_info_row['rasxod_description'];

	$get_rsaxod_val = array(
		'rasxod_id' 			 => $id,
		'rasxod_vlue'			 => $rasxod_price,
		'rasxod_description'	 => $rasxod_descriptuon 
	);

	rasxod_order_tamplate($get_rsaxod_val);			

}

?>