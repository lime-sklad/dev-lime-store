<?php 
function ls_db_request($query) {
	global $dbpdo;

	$param_row = $query['param'];

	$result 			= [];
	$conditions 		= [];
	$table_name 		= $query['table_name'];
	$col_list 			= $query['col_list'];
	$base_query 		= $query['base_query'];
	$param				= $param_row['query']['param'];
	$joins				= $param_row['query']['joins'];
	$bind_list			= $param_row['query']['bindList'];
	$sort_by			= $param_row['sort_by'];


	$query  = "SELECT $col_list FROM $table_name ";
	$query .= $base_query;
	$query .= $param;
	$query .= $joins;
	$query .= $sort_by;


	$conditions = array_merge($conditions, $bind_list);

	$stock_view = $dbpdo->prepare($query);



	foreach($conditions as $bind_key => $bindValue) {
		$stock_view->bindValue($bind_key, $bindValue);
	}
	$stock_view->execute();

	while ($row = $stock_view->fetch(PDO::FETCH_ASSOC)) {	
		$result[] = $row;
	}
	
	return  $result;	
}

function ls_db_upadte($option, $data) {
	/**
	 * Первым аргументом передаём массив с настройками запрса: 
	 * 		$option = [
	 *         'before' => " UPDATE stock_list SET ",
	 *         'after' => " WHERE stock_id = :stock_id",
	 *         'post_list' => [
	 *             'stock_id' => [
	 *                 'query' => false,
	 *                 'bind' => 'stock_id'
	 *             ],
	 *             'order_stock_count' => [
	 *                 'query' => "stock_list.stock_count = stock_list.stock_count - :product_count",
	 *                 'bind' => 'product_count'
	 *             ]
	 *         ]  
	 *      ];
	 * 
	 *  	before - Тут указываем название таблицы
	 * 		after - тут указываем что будет в запросе после перечесления SET
	 * 		post_list - это массив в котором мы указываем массив с индексом из второго аргумента $data
	 * 			индекс напрмер как в примере выше stock_id это ключ из массива $data, мы указываем какое значение взять из data
	 * 			указывая его ключ. Далле в query - указываем сам запрос, что обновить и какой столбец. В bind указываем название бинда котору
	 * 			мы указали выше в запросе query
	 * 		
	 * 		$data это массив с данными которые будут добавлены в таблицу
	 * 		массив должен иметь такую структуру
	 * 			$data = [
	 * 				array(
	 * 					'stock_id' => 777,
	 * 					'order_stock_count' => 'some count'
	 * 				),
	 * 				array(
	 * 					'stock_id' => 888,
	 * 					'order_stock_count' => 'some count'
	 * 				)
	 * 			]; 
	 *  
	 * */	 
		
	global $dbpdo;
	$before 	= $option['before'];
	$after 		= $option['after'];
	$post_list  = $option['post_list'];
	$conditions = [];
	foreach($post_list as $post_key => $post_value) {
		if(array_key_exists($post_key, $data)) {
			if(array_key_exists('require', $post_value)) {
				if(empty($data[$post_key])) {
					return json_encode([
						'error' => 'Заполните все обязательные поля!'
					]);
				}
			}

			if($post_value['query']) {
				$conditions[] = $post_value['query'];
			}
			
			if($post_value['bind']) {
				$bind_list[$post_value['bind']] = $data[$post_key];
			}
		}
	}


	$query = $before;
	if($conditions) {
		$conditions = implode(", ", $conditions);
		$query .= $conditions;
	}
	$query .= $after;

	try {
		$update = $dbpdo->prepare($query);
	
		foreach($bind_list as $bind_key => $bind_value) {
			$update->bindValue($bind_key, $bind_value);
		}
		$update->execute();
	
		return json_encode([
			'success' => 'ok'
		]);
	} catch(PDOException $e) {
		return json_encode([
			'error' => 'Ошибка' . $e
		]);
	}
}

function ls_db_insert($table_name, $data) {
	/**
	 * 	Певрвый аргуемнт название таблицы
	 * 	Второй аргумент массив с данными которые будем добавлять в базу
	 * 	Структура массива с данными:
	 * 		 $data = [
	 * 			array(
	 * 				'Название столбца' => 'Значение',
	 * 				'Название столбца 2' => 'Значение 2'
	 * 			)
	 * 		];
	 * 
	 *	Добавлять в базу можно сразу несколько записей, нужно просто в массив $data
	 *	добавить несколько массивов как в примере выше:
	 * 
	 * 		$data = [
	 * 			array(
	 * 				'Название столбца первая запись' => ' Первое Значение',
	 * 				'Название столбца #2 первая запись ' => 'Первое Значение #2'
	 * 			),
	 * 			array(
	 * 				'Название столбца вторая запись' => ' Второе Значение',
	 * 				'Название столбца #2 вторая запись ' => 'Второе Значение #2'
	 * 			)  
	 * 		];
	 * 
	 *  
	 * 
	 */
	
	global $dbpdo;
	$col_names_list = array_keys($data[array_key_first($data)]);
	$col_names_list = implode(",", $col_names_list);
	$toBind = array();
	$valusList = array();
	$sql_val = [];
	foreach($data as $index => $row) {
		$params = array();
		
		foreach($row as $col_name => $value) {
			$params[] = '?';
			$toBind[] = $value;
		}

		$sql_val[] = "(" . implode(", ", $params) .")";
	}

	$sql_values =  implode(", ", $sql_val);

	$query = "INSERT INTO $table_name ($col_names_list) VALUES $sql_values";
		
	$stmt = $dbpdo->prepare($query);
	$stmt->execute($toBind);
}
