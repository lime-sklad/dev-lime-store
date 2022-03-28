<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/core/template/tpl_function.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

$template = $twig->load('/component/modal/modal_view.twig');


if(isset($_POST['product_id'], $_POST['type'], $_POST['page'])) {
	//массив в который будем заносить данные товара
	$stock_list = [];
    $input_fileds_list = [];
	//тип или вкладка
	$type = $_POST['type'];
	//страница 
	$page = $_POST['page'];
	//id товара или записи
	$id = $_POST['product_id'];

	//получаем конфиги вкладки и страницы 
	$data_page = page_data($page);

	$page_config = $data_page['page_data_list'];

	$sql_query_data = $data_page['sql'];

	$param 			= $sql_query_data['param'];
	$bind_list 		= $sql_query_data['param']['query']['bindList'];
	$table_name 	= $sql_query_data['table_name'];
	$base_query 	= $sql_query_data['base_query'];
	$sort_by 		= $sql_query_data['param']['sort_by'];
	$joins 			= $sql_query_data['param']['query']['joins'];

	$sort_key = $page_config['sort_key'];

	$search_array = [
		'table_name' => ' user_control ',
		'col_list'   => " * ",
		'base_query' => $base_query,			
		'param' => [
			'query' => [
				'param' => $param['query']['param'] . " AND $sort_key = :stock_id  ",
				'joins' =>  $joins,
				'bindList' => array(
					'stock_id' => $id
				)
			],
			'sort_by' 	 =>  $sort_by . ' LIMIT 1 ',
		]
	];	


	
	//делаем запрос в базу с id  и знаносим результат в переменную
	$stock = render_data_template($search_array, $page_config);	


	$filter_modal_list = [
		'edit_stock_filter',
		'info_product_filter_list'
	];


	if($stock && !empty($stock)) {

		//данные товаров
		$stock_base = $stock['base_result'][0];	
		
        foreach ($page_config['modal']['modal_fields'] as $key => $value) {
			$data_value = '';
			$data_custom = '';

			if($value['premission']) {

				// исправить это недоразумение - если данные это фильтры
				if(in_array($key, $filter_modal_list)) {
					$value['custom_data'] = ls_collect_filter($id, $page_config['filter_fields']);
				}


				if($value['db']) {
					$data_value = !empty($stock_base[$value['db']]) && $stock_base[$value['db']] ? $stock_base[$value['db']] : '';
				}
	
				if($value['custom_data']) {
					$data_custom = !empty($value['custom_data']) && $value['custom_data'] ? $value['custom_data'] : '';	
				}
	
				$input_fileds_list[] = [
					'block_name' 	=> $key,
					'value' 		=> $data_value,
					'custom_data' 	=> $data_custom
				];
			}
		}

        $input_fileds_list['user'] = [
            'user_name'  => getUser('get_name'),
            'user_id'    => getUser('get_id'),
            'user_role'  => getUser('get_role')
        ];


		$input_fileds_list['get'] = [
			'id' => $stock_base[$sort_key]
		];


		
		// ls_var_dump($input_fileds_list);

        echo $template->renderBlock($page_config['modal']['template_block'], ['res' => $input_fileds_list]);
	}

}
