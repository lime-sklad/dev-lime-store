<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

// autocmplt-type
if(!isset($_POST['type'], $_POST['page'])) {
	echo 'error';
	exit();
}

$get_data 	  = [];
$search_value = ls_trim($_POST['value']);
$type 		  = $_POST['type'];
$page 	      = $_POST['page'];

$th_list = get_th_list();

$sql_data = page_data($page);

$td_data = $sql_data['page_data_list'];

$auto_type = $_POST['autocmplt_type'];

$sql_query_data = $sql_data['sql'];

$param 			= $sql_query_data['param'];
$bind_list 		= $sql_query_data['param']['query']['bindList'];
$table_name 	= $sql_query_data['table_name'];
$base_query 	= $sql_query_data['base_query'];
$sort_by 		= $sql_query_data['param']['sort_by'];
$joins 			= $sql_query_data['param']['query']['joins'];

$page_data_row = $td_data['get_data'];


foreach($page_data_row as $key => $col_name_prefix) {
	$th_this = $th_list[$key];


	$data_sort = $th_this['data_sort'];
	

	if($data_sort) {
		$search_array = [
			'table_name' => 'user_control',
			'col_list'   => " DISTINCT $col_name_prefix ",
			'base_query' => $base_query,			
			'param' => [
				'query' => [
					'param' => $param['query']['param'],
					'joins' => $joins . " WHERE $col_name_prefix LIKE :search ",
					'bindList' => array(
						'search' =>  "%{$search_value}%"
					)
				],
				'sort_by' 	 => $sort_by,
			]
		];
		
		$d = ls_db_request($search_array);		

		foreach($d as $key) {
			if(array_key_exists($col_name_prefix, $key)) {
				echo $twig->render('/component/search/search_list.twig', [
					'data' 				=>  $key[$col_name_prefix],
					'link_modify_class' => 'get_item_by_filter search-item area-closeable selectable-search-item',
					'data_sort_value' 	=> true,
					'data_sort' 		=> $data_sort,
					'mark'				=> ''
				]);		
			}
		}
	}
}
