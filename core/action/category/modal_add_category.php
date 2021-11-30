<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

include 'category.controller.php';

header('Content-type: Application/json');

if(!empty($_POST) && count($_POST['post_data']) > 0) {
	try {
		add_new_category($_POST['post_data'], $dbpdo);

        $res = ls_db_request([
            'table_name' => ' stock_category ',
            'col_list' => ' * ',
            'base_query' => ' WHERE visible = "visible" ',
            'param' => [
                'query' => [
                    'param' => '',
                    'joins' => '',
                    'bindList' => array(
                    )
                ],
                'sort_by' => 'ORDER BY category_id DESC LIMIT 1'
            ]           
        ]);


		echo json_encode([
            'success' => 'ok',
			'category_id' => $res[0]['category_id'],
			'category_name' => $res[0]['category_name'],
		]);	
	} catch (Exception $e) {
		echo json_encode([
			'error' => "Ошибка"
		]);
	}
}