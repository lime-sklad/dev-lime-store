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

$page_config = page_data_list([
	'type' => $type,
	'page' => $page
]);

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




$render_tpl = render_data_template([
	'type' => $type,
	'page' => $page,
	'search' => [
		'param' =>  $query,
		'bindList' => array(
		)
	]       
]);  


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
			'table_total' => get_table_total(['total_list' => $page_config['table_total_list'],  'data' => $render_tpl['base_result']])  
		]  
	]
]);


echo json_encode([
	'table' => $table,
	'total' => $total
]);

exit();
