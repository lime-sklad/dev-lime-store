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

	$active_filter_id = false;
	$active_filter_val = false;
	$active = false;
	$array = [];
	$get_order_filter = $dbpdo->prepare(" SELECT * FROM user_control

		INNER JOIN stock_filter ON stock_filter.stock_id = :id 

		INNER JOIN filter_list ON filter_list.filter_list_prefix = :prefix

		INNER JOIN filter ON filter.filter_type = filter_list.filter_list_id

		AND stock_filter.active_filter_id = filter.filter_id

		");			
	$get_order_filter->bindParam('id', $id);
	$get_order_filter->bindParam('prefix', $prefix);
	$get_order_filter->execute();
	$get_filter_row = $get_order_filter->fetch();

	if($get_order_filter->rowCount()>0) {
		$active_filter_id = $get_filter_row['filter_id']; 
		$active_filter_val = $get_filter_row['filter_value']; 
		$active = 'actived';
		$array[] = ['res' => $active, 'filter_id' => $active_filter_id, 'filter_val' => $active_filter_val];
	} 

	return $array;
}




/**  обновляем фильтры продукта или добавляем
*	 example
*
*	@param array (
*		'stock_id' => $id,
*		'filter_id' => array(id, id, id)
*	);
**/
function ls_update_filter($param) {
	global $dbpdo;

	foreach ($param as $stock => $row) {
		$stock_id = $row['stock_id'];

		ls_reset_filter($stock_id);
		
		foreach ($row['filter'] as $key => $filter_id) {
			ls_insert_filter($stock_id, $filter_id);
		}		
	}
}

//добавляем фильтры пользователя
function ls_insert_filter($stock_id, $filter_id) {
	global $dbpdo;
	$insert_filter = $dbpdo->prepare("INSERT INTO stock_filter (stock_id, active_filter_id) VALUES (?, ?) ");
	$insert_filter->execute([$stock_id, $filter_id]);	
}
//сбрасываем фильры пользователя
function ls_reset_filter($stock_id) {
	global $dbpdo;
	$reset_filter = $dbpdo->prepare('DELETE FROM stock_filter WHERE stock_id = :stock_id');
	$reset_filter->bindParam('stock_id', $stock_id);
	$reset_filter->execute();	
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
		];
	}

	return $result;
}

/**
 * собираем фильтры в единий массив
 * @return array
 */
function ls_collect_filter() {
	$filter_prefix_list = get_all_filter_prefix_list();
	$filter_value_list = get_all_filter_value_list();

	foreach ($filter_prefix_list as $key => $prefix_value) {
		$filter_prefix_list[$key] = array_merge(['list' => $filter_value_list[$prefix_value['filter_prefix_id']]], $filter_prefix_list[$key]);
	}

	return $filter_prefix_list;
}


/**
 * добавляем фильтры в базу для товара
 */
function ls_insert_stock_filter(array $datas, int $stock_id) {
	$dd = [];
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
	
	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $datas) && !empty($datas[$key])) {
			$dd[] = [
				$value['col_name'] => $datas[$key],
				'stock_id' => $stock_id
			];
		}
	}

	if($dd) {
		ls_db_insert('stock_filter', $dd);
	} else {
		return false;
	}
	
	return true;
}