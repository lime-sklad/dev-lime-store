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

$td_data = page_data_list([
	'page' => $page,
	'type' => $type
]);


$auto_type = $_POST['autocmplt_type'];

$sql_data = default_data_param_sql(['page' => $page, 'type' => $type]);

$param 			= $sql_data['param']['param'];
$bind_list 		= $sql_data['param']['bindList'];
$table_name 	= $sql_data['table_name'];
$base_query 	= $sql_data['base_query'];

foreach($td_data['get_data'] as $key => $col_name_prefix) {
	$th_this = $th_list[$key];

	$data_sort = $th_this['data_sort'];
	if($data_sort) {

		$query = " SELECT DISTINCT $col_name_prefix FROM user_control, $table_name WHERE user_control.user_id != 0 ";

		$query .=  " AND $col_name_prefix LIKE :search ";
		$query .= $param;

		$get_search = $dbpdo->prepare($query);

		foreach($bind_list as $bind_key => $bindValue) {
			$get_search->bindValue($bind_key, $bindValue);
		}
		$get_search->bindValue('search', "%{$search_value}%");
		$get_search->execute();
		$list_name = [];
		if($get_search->rowCount() > 0){
			while ($cmplt_row = $get_search->fetch(PDO::FETCH_BOTH))
				$report_list[] = $cmplt_row;
				foreach ($report_list as $cmplt_row) {
					if( !empty($cmplt_row[$col_name_prefix])) {
						if($auto_type == 'search') {
							echo $twig->render('/component/search/search_list.twig', [
								'data' 				=>  $cmplt_row[$col_name_prefix],
								'link_modify_class' => 'get_item_by_filter',
								'data_sort_value' 	=> true,
								'data_sort' 		=> $data_sort,
								'mark'				=> ''
							]);						
						}
						if($auto_type == 'form') {
							echo $twig->render('/component/search/search_list.twig', [
								'data' 				=>  $cmplt_row[$col_name_prefix],
								'link_modify_class' => ''
							]);
						}							
					}
				}
		}
			// $res = ls_db_request( 
			// 	array(
			// 		'request' => [
			// 			'param' => " AND stock_list.stock_type = :stock_type 
			// 						 AND stock_order_report.order_stock_count > 0   
			// 						 AND stock_list.stock_visible = 0 
			// 						 AND $col_name_prefix LIKE :search

			// 						 AND stock_order_report.stock_id = stock_list.stock_id
			// 						 ",
			// 			'bindList' => array(
			// 				'stock_type' => $type,
			// 				'search' => "%{$search_value}%"
			// 			)
			// 		]
			// 	),
			// 	array(
			// 		'table_name' => $table_name,
			// 		'base_query' => "SELECT DISTINCT $col_name_prefix  FROM user_control, stock_list, stock_order_report
			// 						 WHERE stock_visible = 0 
																	  	
			// 		",
			// 		'sort_by' 	 => ' ORDER BY stock_list.stock_id DESC  '	
			// 	)
			// );
		// foreach($res as $key => $row) {
		// 	echo $twig->render('/component/autocompelete_list.twig', [
		// 		'data' 				=>  $row[$col_name_prefix],
		// 		'link_modify_class' => 'get_item_by_filter',
		// 		'data_sort_value' 	=> true,
		// 		'data_sort' 		=> $data_sort,
		// 		'mark'				=> ''
		// 	]);
		// }			
	}
}


exit();


if($auto_type == 'form') {
	$param = ' AND stock_visible = 0 AND stock_type =:stock_type'; 
}

// echo $param;
// $param = $sql_data['param'];
// ls_var_dump($param);

// ls_var_dump($td_data);
foreach($td_data['get_data'] as $key => $col_name_prefix) {
	$th_this = $th_list[$key];

	$data_sort = $th_this['data_sort'];
	if($data_sort) {

		if($page == 'terminal' || 'stock') {
			$query = "SELECT DISTINCT $table_name.$col_name_prefix FROM user_control 
			INNER JOIN $table_name ON $table_name.$col_name_prefix LIKE :search";
			$query .= $param;
		}
		if($page == 'report') {
			$query .= $base_query;

			$query .=  " AND $col_name_prefix LIKE :search ";
			$query .= $param;
		}

		$get_search = $dbpdo->prepare($query);

		foreach($bind_list as $bind_key => $bindValue) {
			$get_search->bindValue($bind_key, $bindValue);
		}
		$get_search->bindValue('search', "%{$search_value}%");
		$get_search->execute();
		$list_name = [];
		if($get_search->rowCount() > 0){
			while ($cmplt_row = $get_search->fetch(PDO::FETCH_BOTH))
				$report_list[] = $cmplt_row;
				foreach ($report_list as $cmplt_row)
				{
					if( !empty($cmplt_row[$col_name_prefix])) {
						// $list_name[] = $cmplt_row[$col_name_prefix]; 
						if($auto_type == 'search') {
							// ls_var_dump($list_name); get_item_by_filter
							echo $twig->render('/component/autocompelete_list.twig', [
								'data' 				=>  $cmplt_row[$col_name_prefix],
								'link_modify_class' => 'get_item_by_filter',
								'data_sort_value' 	=> true,
								'data_sort' 		=> $data_sort,
								'mark'				=> ''
							]);						
						}
						if($auto_type == 'form') {
							echo $twig->render('/component/autocompelete_list.twig', [
								'data' 				=>  $cmplt_row[$col_name_prefix],
								'link_modify_class' => ''
							]);
						}							
					}
				}

			
		} 		
	}
}
ls_var_dump($query);
