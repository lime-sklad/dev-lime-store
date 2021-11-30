<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
include 'category.controller.php';

header('Content-type: Application/json');

if(!empty($_POST) && count($_POST['post_data']) > 0) {
	try {
		add_new_category($_POST['post_data']);

		$page = $_POST['page'];
		$type = $_POST['type'];
		$this_data = page_data($page);
		$page_config = $this_data['page_data_list'];

		$this_data['sql']['param']['sort_by'] = " GROUP BY stock_category.category_id DESC ORDER BY stock_category.category_id DESC LIMIT 1";

		$table_result = render_data_template($this_data['sql'], $page_config);

		$table = $twig->render('/component/include_component.twig', [
			'renderComponent' => [
				'/component/table/table_row.twig' => [		
					'table' => $table_result['result'],
					'table_tab' => $page,
					'table_type' => $type
				]  
			]
		]);	

		echo json_encode([
			'success' => 'ok',
			'table' => $table
		]);	
	} catch (Exception $e) {
		echo json_encode([
			'error' => "Ошибка"
		]);
	}
}