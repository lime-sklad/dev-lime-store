<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

include 'provider.controller.php';

header('Content-type: Application/json');

if(!empty($_POST) && count($_POST['post_data']) > 0) {
	try {
		add_new_provider($_POST['post_data'], $dbpdo);

        $res = ls_db_request([
            'table_name' => ' stock_provider ',
            'col_list' => ' * ',
            'base_query' => ' WHERE visible = "visible" ',
            'param' => [
                'query' => [
                    'param' => '',
                    'joins' => '',
                    'bindList' => array(
                    )
                ],
                'sort_by' => 'ORDER BY provider_id DESC LIMIT 1'
            ]            
        ]);


		echo json_encode([
            'success' => 'ok',
			'provider_id' => $res[0]['provider_id'],
			'provider_name' => $res[0]['provider_name'],
		]);	
	} catch (Exception $e) {
		echo json_encode([
			'error' => "Ошибка"
		]);
	}
}