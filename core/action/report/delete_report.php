<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type', 'Application/json');

$option = [
	'before' => " UPDATE stock_list
                  JOIN stock_order_report ON stock_order_report.order_stock_id = :delete_id
                  SET stock_list.stock_count = stock_list.stock_count + stock_order_report.order_stock_count, 
                  stock_order_report.order_stock_count = 0,
				  stock_order_report.stock_order_visible = 3,
				  stock_list.stock_return_status = 1 ",
    'after' => "  WHERE stock_list.stock_id = stock_order_report.stock_id ",    
	'post_list' => [
		'report_id' => [
			'query' => false,
			'bind' => 'delete_id'
		]
	]
];


// ls_var_dump($_POST);
echo ls_db_upadte($option, $_POST);

