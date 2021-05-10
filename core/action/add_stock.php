<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');
// сюда занесем название столбцов таблицы 
$tb_name = [];
//сюда "?" для VALUES в запросе 
$cond	 = [];
// значение (данные) c $_POST которые добавляем в таблицу
$exe 	 = [];

// сессия(id) юзера 
$get_session_user = getUser('get_id');
//страница и тип данных
if($_POST['get_type'] && $_POST['get_page']) {
	$type = $_POST['get_type'];
	$page = $_POST['get_page'];
}
$data_list = page_data_list([
	'type' => $type,
	'page' => $page
]);


// связываем название столбцов таблицы с данными с $_POST
// параметр require - значить что данные обьязательные. key - ключ $_POST
$post_list = array(
	'stock_name' 			=> array('require' => true,  	'key' => 'name'),
	'stock_phone_imei' 		=> array('require' => false, 
										'unique' => true,	'key' => 'imei'),
	'stock_first_price'		=> array('require' => true, 	'key' => 'first_price'),
	'stock_second_price'	=> array('require' => false,	'key' => 'second_price'),
	'stock_count'	 		=> array('require' => true, 	'key' => 'count'),
	'stock_provider'	    => array('require' => false, 	'key' => 'provider'),
);
//дефолтные данные которые генерируются на сервере
$default_data = array(
	'product_added' 		=> $get_session_user,
	'stock_get_fdate'		=> get_my_datetoday(),
	'stock_get_year' 		=> get_my_dateyear(),
	'stock_type'			=> $type
);

	//тут перебираем данные из массива выще и проевряем их в $_POST 
	foreach($post_list as $key => $value) {
		//ключ который надо найти в $_POST
		$val_key 	= $value['key'];

		//данные которые нужно заполнить обьязательно поучить
		$is_require = $value['require'];

		//проверки
		if($is_require) {
			if(!array_key_exists($val_key, $_POST) || empty(ls_trim($_POST[$val_key]))) {
				//выводим сообщение и останавливаем
				echo json_encode(['error' => 'Bütün sahələri doldurun!']);		
				exit();
			}				  								   
		}

		
		//проверака на уникальность
		if(array_key_exists('unique', $value) && array_key_exists($val_key, $_POST) && !empty($_POST[$val_key])) {
			if($value['unique']) {
				$param[] = array(
					'param' => "  WHERE $key = :data_value AND stock_type =:stock_type AND stock_visible = 0 AND stock_count > 0",	
					'bindList' => array(
						'data_value' => $_POST[$val_key],
						'stock_type' => $type
					)
				);
				$stock_list = ls_db_request($param,
					[
						'table_name' => 'stock_list',
						'base_query' => 'SELECT * FROM stock_list',
						'sort_by'	 => ' ORDER BY stock_id DESC'
					]
				);	
				if($stock_list && !empty($stock_list)) {

					$get_unique_notice = $twig->render('/component/form/stock_form/form_error_unique_notice.twig', [
						'id' => $stock_list[0]['stock_id']
					]);
					echo json_encode([
						'error' => 'Bu məhsul artıq mövcuddur',
						'error_not_unique' => $get_unique_notice
					]);
					exit();
				}
			}
		}
		
		//выбираем из $_POST только те которые совпадают с массивом
		if(array_key_exists($val_key, $_POST)) {
			
			//названние столбцов в таблице базы данных
			$tb_name[] = $key;
			// VALUES для запроса insert
			$cond[] = '?';
			// значение которые мы добавим в базу
			$exe[] = $_POST[$val_key];
		}
	}

	//если данные не пустые/ 
	if($exe && !empty($exe)) {

		//тут мы в массив добавляем дофолтеык данные с массива выше
		foreach($default_data as $key => $val) {
			$tb_name[] 		= $key;
			$cond[] 	   	= '?';
			$exe[]	   		= $val;
		}

		//обабатываем и готовим массив для запроса
		$insert_tbname = implode(", ", $tb_name);
		$insert_values = implode(", ", $cond);

		try {
			// //добавляем товар в базу
			$query = $dbpdo->prepare("INSERT INTO stock_list ( $insert_tbname ) VALUES ($insert_values)");
			$query->execute($exe);
			
			//массив данных для рендера таблцы. тут и массив данных заголовков таблицы и данные товаров
			$get_last_added_product = render_data_template([
				'type' => $type,
				'page' => $page,
				'search_sort_by' => 'ORDER BY stock_id DESC LIMIT 1'
			]);

			//добавляем  фильтры товаров если есть
			if(isset($_POST['filter_list'])) {
				$get_stock_id = $get_last_added_product['base_result'][0];

				$filter = [];
				$filter_list = $_POST['filter_list'];

				$filter['stock_id'] = ['stock_id' => $get_stock_id['stock_id'], 'filter' => $filter_list];

				ls_update_filter($filter);
			}

			//передаем параметры и выводим шаблон таблицы
			$render_product = $twig->render('/component/include_component.twig', [
				'renderComponent' => [
					'/component/table/table_row.twig' => [
						'table' => $get_last_added_product['result'],
						'table_tab' => $page,
						'table_type' => $type       
					]
				]
			]);

			
			$default_render = render_data_template([
				'type' => $type,
				'page' => $page
			]);
			
			//передаем параметры и выводим шаблон таблицы
			$render_total = $twig->render('/component/include_component.twig', [
				'renderComponent' => [
					'/component/table/table_footer_row.twig' => [
						'table_total' => get_table_total(['total_list' => $data_list['table_total_list'],  'data' => $default_render['base_result']])    
					]
				]
			]);

				
			//выводм успех и товар в на фронт
			echo json_encode([
				'ok' => 'ok',
				'product' => $render_product,
				'total' => $render_total 
			]); 	


			// ls_var_dump(get_table_total(['total_list' => $data_list['table_total_list'],  'data' => $default_render['base_result']]));

			exit();	
		} catch(PDOException $e) {
			echo json_encode([ 'error' => 'some error.' ]); 	
			exit();	
		}
	
	}


exit();

//получаем категорию сервиса
// 	$get_cat_type			= if($_POST['get_cat_type']) { ls_trim($_POST['get_cat_type']) };
// 	//получем категорию товара
// 	$get_prod_type			= ls_trim($_POST['get_prod_type']);

// 	//имя товара
// 	$prodct_name			= ls_trim($_POST['product_name']);
// 	//imei товара
// 	$product_imei			= ls_trim($_POST['product_imei']);
// 	//количество товара
// 	$product_count   		= ls_trim($_POST['product_count']);
// 	//поставщик/категория (если акссесуар)
// 	$product_provider 		= ls_trim($_POST['product_provider']);
// 	//себестоимость товара
// 	$product_first_price	= ls_trim($_POST['product_first_price']);
// 	//стоимость товара
// 	$product_price 			= ls_trim($_POST['product_price']);

// $post_list = array(
// 	'stock_name' => $,
// 	'stock_phone_imei' => if($_POST['product_imei'])
// );


exit();



if(isset($_POST['product_name'])) {

	if(empty($_POST['product_name'] && $_POST['product_count'] && $_POST['product_first_price'] )) {
		$product_err = [
		  'error' 		=> 'Bütün sahələri doldurun!'
		];									  								   
		//выводим сообщение и останавливаем
		echo json_encode($product_err);		
		exit();
	}


	//получаем категорию сервиса
	$get_cat_type			= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['get_cat_type']))));
	//получем категорию товара
	$get_prod_type			= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['get_prod_type']))));

	//имя товара
	$prodct_name			= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_name']))));
	//imei товара
	$product_imei			= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_imei']))));
	//количество товара
	$product_count   		= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_count']))));
	//поставщик/категория (если акссесуар)
	$product_provider 		= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_provider']))));
	//себестоимость товара
	$product_first_price	= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_first_price']))));
	//стоимость товара
	$product_price 			= trim(htmlspecialchars(stripcslashes(strip_tags($_POST['product_price']))));

	//проверка на imei только если продукт телефон
	if($get_prod_type === $product_phone) {
		//проверка на imei
		$check_availible_imei = $dbpdo->prepare("SELECT * FROM stock_list WHERE stock_phone_imei =:imei AND stock_visible = 0 AND stock_type = 'phone'");
		$check_availible_imei->bindParam('imei', $product_imei, PDO::PARAM_INT);
		$check_availible_imei->execute();

		$check_availible_imei_row = $check_availible_imei->fetch(PDO::FETCH_BOTH);
		
		//если такой продук есть
		if($check_availible_imei->rowCount()>0) {
			//id продукта
			$product_availible = $check_availible_imei_row['stock_id'];

			//вызываем функцию модального окана и переменную $add_stock_available 
			add_product_available($product_availible);


			$product_err = [
			  'error_available' => $add_stock_available
			];									  								   
			//выводим сообщение и останавливаем
			echo json_encode($product_err);	
			exit();
			
		}
	}

	$add_stock_insert = $dbpdo->prepare("INSERT INTO stock_list 
		(stock_id, 
		stock_name,
		stock_phone_imei, 
		stock_first_price, 
		stock_second_price, 
		stock_count,
		stock_provider,
		stock_get_fdate,
		stock_get_year, 
		stock_type) 
		VALUES (NULL,?,?,?,?,?,?,?,?, ?) ");

	$add_stock_insert->execute([
		$prodct_name,
		$product_imei,
		$product_first_price,
		$product_price,
		$product_count,
		$product_provider,
		$ordertoday,
		$order_myear,
		$get_prod_type

	]);

	//выводим последний добавленный товар 
	$view_new_stock = $dbpdo->prepare("SELECT * FROM stock_list WHERE stock_type =:stock_type GROUP BY stock_id DESC");
	$view_new_stock->bindParam('stock_type', $get_prod_type);
	$view_new_stock->execute();
	$new_stock_row = $view_new_stock->fetch();

	$stock_id 				= $new_stock_row['stock_id'];			
	$stock_name 			= $new_stock_row['stock_name'];				
	$stock_first_price 		= $new_stock_row['stock_first_price'];	
	$stock_second_price		= $new_stock_row['stock_second_price'];
	$stock_count			= $new_stock_row['stock_count'];
	$stock_provider			= $new_stock_row['stock_provider'];	
	$stock_imei 			= $new_stock_row['stock_phone_imei'];
	$stock_date 			= $new_stock_row['stock_get_fdate'];			
	$stock_return_status 	= $new_stock_row['stock_return_status'];
	$return_image 			= '';

	//добавляем филтры товара
	add_prduct_filter_info($stock_id, $dbpdo);

	//если продукт телефон выводим шаблон таблицы
	if($get_prod_type === $product_phone) {
		
		$get_tamplate = array(
			'stock_id' 					=> $stock_id,
			'stock_date' 				=> $stock_date,
			'stock_name' 				=> $stock_name,
			'stock_imei' 				=> $stock_imei,
			'stock_first_price' 		=> $stock_first_price,
			'stock_second_price' 		=> $stock_second_price,
			'stock_provider' 			=> $stock_provider,
			'manat_image' 				=> $manat_image,
			'stock_return_image' 		=> $return_image,
			'modify_class'				=> 'append_product' 
		);


	//выводим шаблон таблицы склада для теефона 
	$stock_table_tamplate = get_stock_phone_table_row($get_tamplate);

    $success = [
    	'ok' => 'ok',
        'product' => $stock_table_tamplate
    ];

    echo json_encode($success); 
    exit();

	}

	//если продукт аксс выводим шаблон таблицы
	if($get_prod_type === $product_akss) {
		
		$get_tamplate = array(
			'stock_id' 					=> $stock_id,
			'stock_date' 				=> $stock_date,
			'stock_name' 				=> $stock_name,
			'stock_count'				=> $stock_count,
			'stock_first_price' 		=> $stock_first_price,
			'stock_second_price' 		=> $stock_second_price,
			'stock_provider' 			=> $stock_provider,
			'manat_image' 				=> $manat_image
		);

		//выводим шаблон таблицы склада для теефона 
		$stock_table_tamplate = get_stock_akss_table_row($get_tamplate);

        $success = [
        	'ok' => 'ok',
            'product' => $stock_table_tamplate
        ];

        echo json_encode($success); 
        exit();

	}
					 		
}


