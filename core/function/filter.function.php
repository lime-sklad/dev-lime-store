<?php
/**
 * @param string $prefix
 * получаем название фильтра
 */
function get_filter_prefix_title($prefix) {
	global $dbpdo;
	$result = [];
	$filter_prefix = $dbpdo->prepare("SELECT * FROM filter_list WHERE filter_list_prefix = :prefix");
	$filter_prefix->bindParam('prefix', $prefix);
	$filter_prefix->execute();
	if($filter_prefix->rowCount() > 0) {
		$row = $filter_prefix->fetch();

		$title = $row['filter_list_title'];
		$short_name = $row['filter_short_name'];
		
		$result = array('title' => $title, 'short_name' => $short_name);
	}
	return $result;
}


/**
 * @param fileter_list = array(
 *		'color',
 *		'storage',
 *		'ram'
 *	  );
 * @param int $id
 * собриаем фильтр и выводим массив в шаблон
 */
function filter_category($filter_list, $id) {
	$total = [];
	foreach ($filter_list as $prefix) {
		//получем название фильтра и описание
		$title_row = get_filter_prefix_title($prefix);
		//список значений филтров 
		$list = get_filter_list_by_prefix($prefix);		
		// ативный фильтр
		$active = get_active_filters($prefix, $id);
		if($list && $title_row) {
			$title = $title_row['title'];
			$title_short_name = $title_row['short_name'];
			$total[] = [
				'title' => $title, 
				'short_name' => $title_short_name, 
				'prefix' => $prefix, 'active' => $active, 
				'compelte' => $list
			];
		}
		// array_push($total, array('title'=> $title, 'active' => $active, 'compelte' =>  $res));
	}

	return $total;
}


//получаем список значения фильтра
function get_filter_list_by_prefix($prefix) {
	global $dbpdo;

	$filter_btn_arr = [];
	$sort_name = [];
	$result = array();
	$total = array();

	$filter_button = $dbpdo->prepare("SELECT * FROM user_control
		INNER JOIN filter_list  ON filter_list.filter_list_prefix = :prefix
		LEFT JOIN filter ON filter.filter_type = filter_list.filter_list_id
		GROUP BY filter.filter_value DESC  ORDER BY ABS(`filter`.`filter_value`)  ASC");
	$filter_button->bindParam('prefix', $prefix);
	$filter_button->execute();
	if($filter_button->rowCount() > 0) {
		while ($row = $filter_button->fetch())
		$filter_btn_arr[] = $row;
		foreach ($filter_btn_arr as $row) { 
			$filter_id = $row['filter_id'];
			$filter_value = $row['filter_value'];
			$filter_type = $row['filter_type'];

			array_push($total, array(
				'filter_type' => $filter_type,
				'id'	=> $filter_id,
				'value' => $filter_value 
			));
	
		}	    	                 

		return $total;	
	}
}

//получем активный фильтр 
function get_active_filters($prefix, $id) {
	global $dbpdo;

	$array = [];

	$data = ls_db_request([
		'table_name' => 'filter',
		'col_list' => '*',
		'param' => [
			'query' => [
				'param' => "INNER JOIN stock_filter ON stock_filter.stock_id = :id
							INNER JOIN filter_list ON filter_list.filter_list_prefix = :prefix

							WHERE filter.filter_type = filter_list.filter_list_id AND filter.filter_id = stock_filter.active_filter_id
							",
				'bindList' => [
					':id' => $id,
					':prefix' => $prefix
				],
			]
		]
	], PDO::FETCH_ASSOC);

	if(count($data) > 0) {
		$row = $data[0];

		$active_filter_id = $row['filter_id']; 
		$active_filter_val = $row['filter_value']; 
		$active = 'actived';
		$array = ['res' => $active, 'filter_id' => $active_filter_id, 'filter_val' => $active_filter_val];		
	}

	return $array;
}


// ----------------------------------------- upd ------------------------------------------------ //

/**
 * получаем список всех фильтров 
 * @return array $result список названий фильтров
 */
function get_all_filter_prefix_list() {
    $r = ls_db_request([
        'table_name' => 'filter_list',
        'col_list' => '*',
        'base_query' => ' ',
        'param' => [
            'query' => [
                'param' => ' WHERE filter_list_visible = 0',
                'joins' => '',
                'bindList' => []
            ],
            'sort_by' => ''
        ]
    ]);

	$result = [];

	foreach ($r as $value) {
		$result[$value['filter_list_prefix']] = [
			'filter_prefix_id' 		=> $value['filter_list_id'],
			'filter_prefix' 		=> $value['filter_list_prefix'],
			'filter_short_name'		=> $value['filter_short_name'],
			'filter_prefix_title' 	=> $value['filter_list_title']
		];
	}

	return $result;
}


/**
 * получаем список значений фильтров
 * @return array $result - список значений фильтров
 */
function get_all_filter_value_list() {
    $r = ls_db_request([
        'table_name' => ' `filter` ',
        'col_list' => '*',
        'base_query' => ' ',
        'param' => [
            'query' => [
                'param' => ' WHERE `filter_visible` = 0 ',
                'joins' => '',
                'bindList' => []
            ],
            'sort_by' => ''
        ]
    ]);

	$result = [];

	foreach ($r as $value) {
		$result[$value['filter_type']][] = [
			'filter_id' => $value['filter_id'],
			'filter_value' => $value['filter_value'],
			'filter_type' => $value['filter_type']
		];
	}

	return $result;
}

/**
 * собираем фильтры в единий массив
 * @return array
 */
function ls_collect_filter(int $id  = null, array $type_list = array()) {
	$filter_prefix_list = get_all_filter_prefix_list();
	$filter_value_list = get_all_filter_value_list();
	
	// ну хз что тут, главное работает как мне нужно. Не помню когда и зачем, но работает. 
	foreach ($filter_prefix_list as $key => $prefix_value) {
		$filter_prefix_list[$key] = array_merge(['list' => $filter_value_list[$prefix_value['filter_prefix_id']]], $filter_prefix_list[$key]);

		$filter_prefix_list[$key]['active'] = get_active_filters($key, $id);
	}

	$res = [];

	if($type_list) {
		foreach($type_list as $key => $val) {
			if(array_key_exists($val, $filter_prefix_list)) {
				$res[$val] = array_merge($filter_prefix_list[$val]);
			}
		}
	}

	return $res;
}


/**
 * добавляем фильтры в базу для товара
 * @param array $post_data - массив $_POST
 * @param int $stock_id - id товара
 */
function ls_insert_stock_filter(array $post_data, int $stock_id) {
	$result = [];

	$col_post_list = [
		'filter_color' => [
			'col_name' => 'active_filter_id',
		],
		'filter_storage' => [ 
			'col_name' => 'active_filter_id' 
		],
		'filter_ram' => [
			'col_name' => 'active_filter_id'
		],
		'filter_used' => [
			'col_name' => 'active_filter_id'
		],
		'filter_brand' => [
			'col_name' => 'active_filter_id'
		],	
	];
	
	// хотел написать коментарий для данного куска кода, но понял, что запутаю еще сильней...
	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $post_data) && !empty($post_data[$key])) {
			$result[] = [
				$value['col_name'] => $post_data[$key],
				'stock_id' => $stock_id
			];
		}
	}

	if($result) {
		ls_db_insert('stock_filter', $result);
	} else {
		return false;
	}
	
	return true;
}


function ls_reset_stock_filter($stock_id, $filter_id) {
	ls_db_delete(array(
		[
			'table_name' => 'stock_filter',
			'joins' => ' INNER JOIN filter as tb ON tb.filter_id = :filter_id 
					     INNER JOIN filter ON filter.filter_type = tb.filter_type
			',
			'where' => ' stock_filter.active_filter_id = `filter`.filter_id AND stock_filter.stock_id = :id ',
			'bindList' => [
				':id' => $stock_id,
				':filter_id' => $filter_id
			]			
		]
	));
}




/**
 * изменить фильтр товара
 * @param array $post_data - массив с данными POST
 * @param int $id - id товара
 */
function ls_edit_stock_filter($post_data, $stock_id) {
	$result = [];

	$post_list = [
		'filter_color' 		=> true,
		'filter_storage' 	=> true,
		'filter_ram'		=> true,
		'filter_used' 		=> true,
		'filter_brand' 		=> true	
	];

	$result = array_intersect_key($post_data, $post_list);

	foreach($result as $filter_id) {
		// сбрасываем фиьтры товара с данной категорией
		ls_reset_stock_filter($stock_id, $filter_id);
	}

	// добавляем фильтер для товара
	ls_insert_stock_filter($post_data, $stock_id);
}



function get_page_stock_filter_fileds($page) {
	$page = page_data($page);

	return $page['page_data_list']['filter_fileds'];
}


function ls_get_filter_list_by_page_type($page) {
	$page_config = page_data($page);

	ls_var_dump($page_config);

	// $filter_fields = $page['page_data_list']['filter_fields'];

	// return ls_collect_filter(null, $filter_fields);
}
