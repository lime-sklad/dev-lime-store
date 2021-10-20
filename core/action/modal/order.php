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

  

	$search_array = [
		'table_name' => ' user_control ',
		'col_list'   => " * ",
		'base_query' => $base_query,			
		'param' => [
			'query' => [
				'param' => $param['query']['param'] . " AND stock_list.stock_id = :stock_id  ",
				'joins' =>  $joins. ' WHERE user_control.user_id = :u_id ',
				'bindList' => array(
					'stock_id' => $id,
                    'u_id' => getUser('get_id')
				)
			],
			'sort_by' 	 =>  $sort_by . ' LIMIT 1 ',
		]
	];	

	//если тип товара амбар или транкзакци 
	if($page == 'terminal') {
		//делаем запрос в базу с id  и знаносим результат в переменную
		$stock = render_data_template($search_array, $page_config);		
	}		
	
	//если тип товара амбар или транкзакци 
	if($page == 'stock') {
		//делаем запрос в базу с id  и знаносим результат в переменную
		$stock = render_data_template($search_array, $page_config);	
	}


	if($stock && !empty($stock)) {
		//данные товаров
		$stock_base = $stock['base_result'][0];	
		
        foreach ($page_config['modal']['modal_fields'] as $key => $value) {
			$data_value = '';
			$data_custom = '';

			if($value['premission']) {
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

		$input_fileds_list['stock'] = [
			'id' => $stock_base['stock_id']
		];
        echo $template->renderBlock($page_config['modal']['template_block'], ['res' => $input_fileds_list]);
	}

}
