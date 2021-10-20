<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

if(!empty($_POST['id'])) {


    $id = $_POST['id'];

    $row = ls_db_request([
        'table_name' => 'stock_list as tb',
        'col_list'   => '*',
        'base_query' => 'INNER JOIN stock_list ON stock_list.stock_visible != 3 ',			
        'param' => [
			'query' => array(
				'param' =>  " AND stock_list.stock_count >= stock_list.min_quantity_stock
							  AND stock_list.stock_visible = 0 AND stock_list.barcode_article = :id ",
				"joins" => "  LEFT JOIN stock_provider ON stock_provider.provider_id = stock_list.product_provider
							  LEFT JOIN stock_category ON stock_category.category_id = stock_list.product_category ",		
				'bindList' => array(
					'id' => $id
				)
			),
			'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "
        ]
    ]);	

	$row = $row[0];

    echo json_encode([
        'res_id' => $row['stock_id']
    ]);

}