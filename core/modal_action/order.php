<?php  
require_once $_SERVER['DOCUMENT_ROOT'].'/core/template/tpl_function.php';
require_once '../../function.php';
$template = $twig->load('/component/modal/modal_view.twig');

// первым агрументом передаем ключ который будем искать в массиве и который нужно вывести
function get_data_exists($key, $arr) {
	if(array_key_exists($key, $arr)) {
		return $arr[$key];
	} else {
		return false;
	}
}


function modal_fields_list($stock_base, $page_config) {
/**
 * первым  агрументом передаем функцию $page_config = page_data_list($type, $page);
 * вторым агрументом массив данных товара - $stock_base;
 * пришлось костылить проверкой ключа в value. Ведь данные могу не прийти в массиве $stock_base[] потому что данные 
 * очищаются в зависимости от доступа пользователя.
 * Если данные статичные, например минимальное количество заказа то указываем ключ `value` => 1 
 * если данные динамические и нужно взять из массива то указываем ключ `data_value` => `тут указываем ключ массива (данные)ы`  
 */
	$fileds_list = $page_config['modal']['modal_fields'];
	$modal_fields = [
		'user' => array(
			'id' => $stock_base['stock_id'],
			'value' => ''
		),

		//terminal
		'stock_name' => array(
			'include_block'	 	=> 'order_name',
			'data_value' 		=> 'stock_name'
		),
		'stock_imei' => array(
			'include_block' 	=> 'order_imei',
			'data_value'		=> 'stock_phone_imei' 
		),										
		'stock_provider' => array(
			'include_block' 	=> 'order_provider',
			'data_value' 		=> 'stock_provider'
		),
		'spoiler_filter' => array(
			'include_block' 	=> 'spoiler_group',
			'value'		 		=> array(								
				array(
					'include_block' => 'order_ls_filter',
					'ls_filter' => filter_category($page_config['filter_fields'], $stock_base['stock_id'])
				),
			)
		),					
		'order_note' => array(
			'include_block'		 => 'order_note',
			'value'				 => ''
		),					
		'order_first_price' => array(
			'include_block' 	=> 'first_price',
			'data_value' 		=> 'stock_first_price',
		),
		'order_second_price' => array(
			'include_block' 	=> 'order_second_price',
			'value' 			=> '',
		),
		'order_total_amount' => array(
			'include_block' 	=> 'order_total_amount',
			'value' 			=> ''
		),
		'order_hidden_count' => array(
			'include_block' 	=> 'order_hidden_count',
			'value'				=> '1'
		),	
		'order_stock_count' => array(
			'include_block' 	=> 'order_count',
			'value' 			=> ''
		),							
		'order_submit' => array(
			'include_block' 	=> 'submit_order',
			'value' 			=> ''
		),
		//terminal end
		
		//edit start
		'edit_stock_name' => array(
			'include_block' 	=> 'edit_name',
			'data_value' 		=> 'stock_name'
		),
		'edit_stock_imei' => array(
			'include_block'	 	=> 'edit_imei',
			'data_value' 		=> 'stock_phone_imei'
		),
		'edit_stck_provider' => array(
			'include_block' 	=> 'edit_provider',
			'data_value' 		=> 'stock_provider',
			'list_item' 		=> get_provider_list() 
		),
		'edit_stock_category' => array(
			'include_block' 	=> 'edit_category',
			'data_value' 		=> 'stock_provider'
		),
		'edit_stock_count' => array(
			'include_block' 	=> 'edit_count',
			'data_value' 		=> 'stock_count'	
		),		 
		'edit_stock_first_price' => array(
			'include_block' 	=> 'edit_first_price',
			'data_value'		=> 'stock_first_price'
		),
		'edit_stock_second_price' => array(
			'include_block' 	=> 'edit_second_price',
			'data_value' 		=> 'stock_second_price'
		),
		'edit_stock_hidden_count' => array(
			'include_block' 	=> 'edit_hidden_count',
			'value' 			=> '1'
		),
		'edit_filter_spoiler' => array(
			'include_block' 	=> 'spoiler_group',
			'value' 			=> array(								
				array(
					'include_block' => 'edit_ls_filter',
					'ls_filter' => filter_category($page_config['filter_fields'], $stock_base['stock_id'])
				),
			)
		),
		'edit_save' => array(
			'include_block' 	=> 'save_modal',
			'value' 			=> ''
		),
		//edit end

		//report start
		'report_order_price' => array(
			'include_block'		=> 'order_second_price',
			'data_value' 		=> 'order_stock_sprice'
		),
		'report_order_count' => array(
			'include_block' 	=> 'order_count',
			'data_value'		=> 'order_stock_count'
		),
		'report_save_edit'	=> array(
			'include_block' 	=>'save_report',
			'value'				=> ''
		),
		'report_order_id'	=> array(
			'include_block' => 'report_order_id',
			'data_value'	=> 'order_stock_id'
		)
		//report end
	];

	// $fields_name = это название поля в массиве $modal_fields котрый нужно найти
	foreach($fileds_list as $row => $fields_name) {
		if(array_key_exists($fields_name, $modal_fields)) {
			//если данные нужно получить из массива данных $stock_base, то получаем ключ из `data_value`
			//если такой ключ есть в массиве 
			if(array_key_exists('data_value', $modal_fields[$fields_name]) 
			&& array_key_exists($modal_fields[$fields_name]['data_value'], $stock_base)) {
				//создаем ключ `value` с значением $stock_base[`data_value`]
				$modal_fields[$fields_name]['value'] = $stock_base[$modal_fields[$fields_name]['data_value']];	
			} 
			//получем только те поля у которых есть ключ `value` даже если они пустые
			//остальные без `value` были отсеяны, например если у пользователя нет прав смотреть эти данные
			if(array_key_exists('value', $modal_fields[$fields_name])) {
				$result[$fields_name] = $modal_fields[$fields_name];
			}
		}
	}
	return $result;
}


if(isset($_POST['product_id'], $_POST['type'], $_POST['page'])) {
	//массив в который будем заносить данные товара
	$stock_list = [];

	//тип или вкладка
	$type = $_POST['type'];
	//страница 
	$page = $_POST['page'];
	//id товара или записи
	$id = $_POST['product_id'];

	//получаем конфиги вкладки и страницы 
	$page_config = page_data_list([
		'type' => $type,
		'page' => $page
	]);	
	//если тип товара амбар или транкзакци 
	if($page == 'terminal') {
		echo 'ds';
		//делаем запрос в базу с id  и знаносим результат в переменную
		$stock = render_data_template([
			'type' => $type,
			'page' => $page,
			'search' => [
				'param' => " AND stock_id = :stock_id ",
				'bindList' => array(
					'stock_id' => $id
				)
			]
		]);		
	}		
	
	//если тип товара амбар или транкзакци 
	if($page == 'stock') {
		//делаем запрос в базу с id  и знаносим результат в переменную
		$stock = render_data_template([
			'type' => $type,
			'page' => $page,
			'search' => [
				'param' => " AND stock_id = :stock_id ",
				'bindList' => array(
					'stock_id' => $id
				)
			]
		]);
	}

	if($page == 'report') {
		if(isset($_POST['order_id'])) {
			$order_id = $_POST['order_id'];

			$stock = render_data_template([
				'type' => $type,
				'page' => $page,
				'search' => [
					'param' => " AND order_stock_id = :order_id ",
					'bindList' => array(
						'order_id' => $order_id
					)
				]
			]);
		}
	}


	if($stock && !empty($stock)) {
		//данные товаров
		$stock_base = $stock['base_result'][0];	
		
		$modal = modal_fields_list($stock_base, $page_config);
		
		render_modal($page_config, $modal, $template);
	}


}






function render_modal($page_config, $modal, $template) {
	echo $template->renderBlock($page_config['modal']['template_block'], ['res' => $modal] );
}



exit();
if(isset($_POST['product_id'], $_POST['type'], $_POST['page'])) {
	//массив в который будем заносить данные товара
	$stock_list = [];

	//тип или вкладка
	$type = $_POST['type'];
	//страница 
	$page = $_POST['page'];
	//id товара или записи
	$id = $_POST['product_id'];

	//если тип товара амбар или транкзакци 
	if($type == 'stock' || 'terminal') {
		//делаем запрос в базу с id  и знаносим результат в переменную
		$stock = render_data_template([
			'type' => $type,
			'page' => $page,
			'search' => [
				'param' => " AND stock_id = :stock_id ",
				'bindList' => array(
					'stock_id' => $id
				)
			]
		]);
	}
	//если запрос тру и не пустой 
	if($stock) {
	
		$page_data_list = page_data_list([
			'type' => $type,
			'page' => $page
		]);			
		
		//данные товаров
		$stock_base = $stock['base_result'][0];
	
		// $stock_list['res'] = [];
		$stock_list['user'] = ['id' => $stock_base['stock_id']]; 
		
		//тут получаем массив полей для модального окна, которые связаны с даннмыи товара
		if(array_key_exists('fields_with_data', $page_data_list['modal'])) {
			foreach ($page_data_list['modal']['fields_with_data'] as $data_name => $stock_row) {
				if(array_key_exists($stock_row['data_row_name'], $stock_base)) {
					$stock_list[$data_name] = [
						'include_block' => $stock_row['block_name'],
						'value' => $stock_base[$stock_row['data_row_name']]

					];
				} 
			}
		}
		// ls_var_dump($stock_list);

		// //компоненты модального ока
		// if(array_key_exists('fields_component', $page_data_list['modal'])) {
		// 	if(array_key_exists('ls_filter', $page_data_list['modal']['fields_component'])) {
		// 		$stock_list['ls_filter'] = ['val' => filter_category($page_data_list['modal']['fields_component']['ls_filter'], $stock_base['stock_id'])];
		// 	}
		// }

		// //поля модального окна
		// if(array_key_exists('fields', $page_data_list['modal'])) {
		// 	foreach($page_data_list['modal']['fields'] as $data_name => $row) {
		// 		$stock_list[$data_name] = ['val' => $row];
		// 	}
		// }
	

		$stock_list['res'] = $stock_list;
		// // ls_var_dump($stock_list);
	
		echo $template->renderBlock($page_data_list['modal']['template_block'], $stock_list);

	}	
}



exit();
if(isset($_POST['product_id'], $_POST['type'])) {
	$stock_list = [];

	$type = $_POST['type'];
	$page = $_POST['page'];
	
	$id = $_POST['product_id'];

	if($type == 'stock' || 'terminal') {
		$stock = render_data_template([
			'type' => $type,
			'page' => $page,
			'search' => [
				'param' => " AND stock_id = :stock_id ",
				'bindList' => array(
					'stock_id' => $id
				)
			]
		]);
	}

	if($type == 'report') {
		// $stock = render_data_template([
		// 	'type' => $type,
		// 	'page' => $page,
		// 	'search' => [
		// 		'param' => " AND stock_order_report.order_stock_id = :stock_id   ",
		// 		'bindList' => array(
		// 			'stock_id' => 1274
		// 		)
		// 	]
		// ]);
	}
	
	if($stock) {
		$page_data_list = page_data_list([
			'type' => $type,
			'page' => $page
		]);			
		
		//данные товаров
		$stock_base = $stock['base_result'][0];
	
		// $stock_list['res'] = [];
		$stock_list['user'] = ['id' => $stock_base['stock_id']]; 
		
		//тут получаем массив полей для модального окна, которые связаны с даннмыи товара
		if(array_key_exists('fields_with_data', $page_data_list['modal'])) {
			foreach ($page_data_list['modal']['fields_with_data'] as $data_name => $stock_row) {
				if(array_key_exists($stock_row, $stock_base)) {
					$stock_list[$data_name] = ['val' => $stock_base[$stock_row]];
				} 
			}
		}

		//компоненты модального ока
		if(array_key_exists('fields_component', $page_data_list['modal'])) {
			if(array_key_exists('ls_filter', $page_data_list['modal']['fields_component'])) {
				$stock_list['ls_filter'] = ['val' => filter_category($page_data_list['modal']['fields_component']['ls_filter'], $stock_base['stock_id'])];
			}
		}

		//поля модального окна
		if(array_key_exists('fields', $page_data_list['modal'])) {
			foreach($page_data_list['modal']['fields'] as $data_name => $row) {
				$stock_list[$data_name] = ['val' => $row];
			}
		}
	

		$stock_list['res'] = $stock_list;
		// ls_var_dump($stock_list);
	
		echo $template->renderBlock($page_data_list['modal']['template_block'], $stock_list);
	}

}







exit();
if(isset($_POST['get_product_tab'])) {
	//списко категорий товара
	get_product_category();
	//тип таблицы (terminal, stock, report и тд)
	get_table_svervice_type();

	//id товара
	$id  = $_POST['product_id'];

	//Категория товара
	$give_prdouct_cat =  ls_trim($_POST['get_product_cat']);

	//тип события  (терминал, склда и тд..)
	$give_action_type =  ls_trim($_POST['get_product_tab']);

	//получаем товар
	$get_order = $dbpdo->prepare("SELECT * FROM stock_list WHERE stock_id =:stockId");
	$get_order->bindParam('stockId', $id, PDO::PARAM_INT);
	$get_order->execute();
	$get_order_row = $get_order->fetch(PDO::FETCH_ASSOC);	
	if($get_order->rowCount()>0){
		$prod_name 			=  $get_order_row['stock_name'];
		$prod_imei 			=  $get_order_row['stock_phone_imei'];
		$prod_count 		=  $get_order_row['stock_count'];
		$edit_stock_name 	=  $get_order_row['stock_name'];
		$edit_stock_imei 	=  $get_order_row['stock_phone_imei'];
		$second_price 		=  $get_order_row['stock_second_price'];	
		$first_price 		=  $get_order_row['stock_first_price'];
		$provider 			=  $get_order_row['stock_provider'];		
	}


	//НАЧАЛО: если события терминал
	if($give_action_type == $terminal) {
		// проверяем на категорию товара телефон
		if($give_prdouct_cat === $product_phone) {

			$filter = filter_category(['color', 'storage', 'ram', 'used'], $id);
			echo $template->renderBlock('terminal_order', [
				'user' 			=> array('show' => true, 'id' => $id),
				'name' 			=> array('show' => true, 'val' => $prod_name),
				'imei' 			=> array('show' => true, 'val' => $prod_imei),
				'first_price'	=> array('show' => true, 'val' => $first_price),
				'price'			=> array('show' => true, 'val' => ''),
				'note' 			=> array('show' => true, 'val' => ''),
				'hidden_count' 	=> array('show' => true, 'val' => ''),
				'total_amount'	=> array('show' => true, 'val' => ''),
				'ls_filter'		=> array('show' => true, 'val' => $filter)
			]);
		}

		//проверяем на категорию товара акссесуар
		if($give_prdouct_cat === $product_akss) {
			$filter = filter_category(['color', 'storage', 'used'], $id);
			//вызываем шаблон заказа для акссесуара	
			echo $template->renderBlock('terminal_order', [
				'user' 			=> array('show' => true, 'id' => $id),
				'name' 			=> array('show' => true, 'val' => $prod_name),
				'category'		=> array('show' => true, 'val' => $provider),
				'price'			=> array('show' => true, 'val' => ''),
				'note' 			=> array('show' => true, 'val' => ''),
				'count' 		=> array('show' => true, 'val' => ''),
				'total_amount'	=> array('show' => true, 'val' => ''),
				'ls_filter'		=> array('show' => true, 'val' => $filter)
			]);		
		}
	}
	//КОНЕЦ: если события терминал

	//НАЧАЛО: если события склад
	if($give_action_type == $stock) {

		//проверяем на категорию товара телефон
		if($give_prdouct_cat === $product_phone) {
			//получаем массив филтров по категории
			$filter = filter_category(['color', 'storage', 'ram', 'used', 'brand'], $id);

			//это работает нормально
			echo $template->renderBlock('edit_product', [
				'user'			=> array('show' => true, 'id' => $id),
				'name'			=> array('show' => true, 'val' => $prod_name),
				'imei'			=> array('show' => true, 'val' => $prod_imei),
				'provider'		=> array('show' => true, 'val' => $provider),
				'first_price' 	=> array('show' => true, 'val' => $first_price),
				'sales_pirce' 	=> array('show' => true, 'val' => $second_price),
				'hide_count'  	=> array('show' => true, 'val' => $prod_count ),
				'ls_filter'		=> array('show' => true, 'val' => $filter)
			]);
		}

		//проверяем на категорию товара акссесуар
		if($give_prdouct_cat === $product_akss) {

			//получаем массив филтров по категории
			$filter = filter_category(['color', 'storage', 'used'], $id);

			//это работает нормально
			echo $template->renderBlock('edit_product', [
				'user'			=> array('show' => true, 'id' => $id),
				'name'			=> array('show' => true, 'val' => $prod_name),
				'provider'		=> array('show' => true, 'val' => $provider),
				'first_price' 	=> array('show' => true, 'val' => $first_price),
				'sales_pirce' 	=> array('show' => true, 'val' => $second_price),
				'count'  		=> array('show' => true, 'val' => $prod_count ),
				'ls_filter'		=> array('show' => true, 'val' => $filter)
			]);
		
		}		
	}
	//КОНЕЦ: если события терминал

	//НАЧАЛО: если события отчёт(report)
	if($give_action_type === $report){
		
		$get_report_order = $dbpdo->prepare("SELECT * FROM stock_order_report 
			WHERE order_stock_id = :product_id 
			AND stock_order_visible = 0");
		$get_report_order->bindParam('product_id', $id);
		$get_report_order->execute();

		if($get_report_order->rowCount()>0) {
			$retunr_report = $get_report_order->fetch();
			//количество заказа
			$return_product_count = $retunr_report['order_stock_count']; 
			//id товара
			$return_product_id = $retunr_report['stock_id'];
			#id - id заказа в отчете
			get_report_order_modal($id, $return_product_id, $return_product_count);
		}

	}
	//КОНЕЦ: если события отчёт(report)
}



