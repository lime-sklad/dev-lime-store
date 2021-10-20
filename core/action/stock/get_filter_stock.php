<?php
require_once  $_SERVER['DOCUMENT_ROOT'].'/function.php';
header('Content-type: Application/json');

// ls_var_dump($_POST);
if(!isset($_POST['type'], $_POST['page'])) {
	echo json_encode([
		'error' => "Ошибка! Параметры не найдены \n Обновите страницу и попробуйде снова, если ошибка сохранится, обратитесь в тех потдержку"
	]);

	exit;
	die;
}

$type = $_POST['type'];
$page = $_POST['page'];
$data = page_data($page);

$td_data = $data['page_data_list'];

$base_result = [];
$res = [];
$table = '';

$sql_query_data = $data['sql'];

$param 			= $sql_query_data['param'];
$bind_list 		= $sql_query_data['param']['query']['bindList'];
$table_name 	= $sql_query_data['table_name'];
$base_query 	= $sql_query_data['base_query'];
$sort_by 		= $sql_query_data['param']['sort_by'];
$joins 			= $sql_query_data['param']['query']['joins'];

$page_data_row = $td_data['get_data'];

if(isset($_POST['id'])) {
	$filter_list = $_POST['id'];
	$filter_query = [];
	foreach($filter_list as $key => $row) {
		$filter_query[$row['filter_type']][] = $row['filter_id'];
	}
	
	foreach($filter_query as $filter_type => $filter_id_list) {
		$id = implode(',', $filter_id_list);
		$table_prexif = 'table_name'.$filter_type;
		$query[] = " INNER JOIN stock_filter AS $table_prexif 
					 ON $table_prexif.active_filter_id IN($id) 
					 AND stock_list.stock_id = $table_prexif.stock_id ";
	}	
	$query = implode("\n", $query);
} else {
	$query = '';
}



$search_array = [
    'table_name' => 'user_control',
    'col_list'   => " * ",
    'base_query' => $base_query,			
    'param' => [
        'query' => [
            'param' => $param['query']['param'],
            'joins' => $query . $joins,
            'bindList' => array(
            )
        ],
        'sort_by' 	 => $sort_by,
    ]
];

$render_tpl = render_data_template($search_array, $td_data);


// exit();



$table = $twig->render('/component/include_component.twig', [
	'renderComponent' => [
		'/component/table/table_row.twig' => [		
			'table' => $render_tpl['result'],
			'table_tab' => $page,
			'table_type' => $type
		]  
	]
]);	


$total = $twig->render('/component/include_component.twig', [
	'renderComponent' => [
		'/component/table/table_footer_row.twig' => [		
			'table_total' => table_footer_result($td_data['table_total_list'], $render_tpl['base_result'])  
		]  
	]
]);


echo json_encode([
	'table' => $table,
	'total' => $total
]);

exit();
