<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

$data = [];
$arr = [];
$col_post_list = [
	'add_category_name' => [
		'col_name' => 'category_name',
		'required' => true
	],
];


if(!empty($_POST) && count($_POST['post_data']) > 0) {
	$post_data = $_POST['post_data'];

	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $post_data)) {
			$data = array_merge($data, [
				$value['col_name'] => $post_data[$key]
			]);
		}
	}

	$default_data = [
		'visible' => 'visible',
	];

	$data = array_merge($data, $default_data);

	try {
		ls_db_insert('stock_category', [$data]);

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