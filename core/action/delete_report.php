<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

//уадалить из учета
if(isset($_POST['delete_report'])) {
	$delete_report_id = trim($_POST['delete_report']);

	$upd_report_row = $dbpdo->prepare("
		UPDATE stock_order_report,stock_list 
		SET stock_list.stock_count = stock_list.stock_count+stock_order_report.order_stock_count 
		WHERE stock_order_report.order_stock_id =:delete_report_id
		AND stock_list.stock_id = stock_order_report.stock_id
	");
	$upd_report_row->bindParam('delete_report_id', $delete_report_id, PDO::PARAM_INT);
	$upd_report_row->execute();

	$delete_report = $dbpdo->prepare("DELETE FROM stock_order_report WHERE order_stock_id=?");
	$delete_report->execute([$delete_report_id]); 	

	$success = array('ok' => 'ok');

	echo json_encode($success);
	exit();
}
