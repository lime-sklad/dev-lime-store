<?php 
require __DIR__.'/db/config.php';
//upd 
require __DIR__.'/private.function.php';
require __DIR__.'/core/function/stock.function.php';
require __DIR__.'/core/action/admin/user.function.php';
require __DIR__.'/include/lib_include.php';

//проверка доступа страницы
access_request_action($_SERVER['REQUEST_URI']);
//проверка достпа запросов
access_request_uri($_SERVER['REQUEST_URI']);

$lincence_check = $dbpdo->prepare("SELECT * FROM licence");
$lincence_check->execute();

$lince_check_row = $lincence_check->fetch();

$lincence_status = $lince_check_row['licence_active'];

if($lincence_status == 0)
{
	header('Location: licence.php');
	exit();
}

if($lincence_status == 1)
{
	$licence_deactive_date = $lince_check_row['licence_active_deactive'];
}

$manat_image = '<img src="/img/icon/manat.png" class="manat_con_class">';
$manat_image_green = '<img src="/img/icon/manat_green.png" class="manat_con_class">';
//маркировака возврата
$stock_return_image = '<img src="img/icon/investment.png" style="width: 20px; height:20px;" title="Bu mall vazvrat olunub">';



$update_check_day = 'Wednesday'; 

$ordertoday = date("d.m.Y");
$order_myear = date("m.Y");
$weak_now = date("l/off"); //date("l");
$deactive_date = date('d.m.Y', strtotime('+30 day'));


function get_my_dateyear() {
	return date("m.Y");
}

function get_my_datetoday() {
	return  date("d.m.Y");
}

function check_connections($var) {
	$ch = curl_init('https://www.google.com/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$get_connectino = curl_exec($ch);

	$info = curl_getinfo($ch);
	curl_close($ch);

	if($info['http_code'] == '200') {
		return $var;
	} else {
		$do = 'nothing';
	}
}


function ls_trim($var) {
	$var = 	trim($var);
	$var =  htmlspecialchars($var);
	$var =  stripcslashes($var);
	$var =  strip_tags($var);
	return $var;	
}	

//получаем название фильтра
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

//собриаем фильтр и выводим массив в шаблон
function filter_category($filter_list, $id) {
	/**example $filter_list 
	*
	*	$filter_list = array(
	*		'color',
	*		'storage',
	*		'ram'
	*	);
	*
	**/
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


//получить информацию товара по id
function get_product_by_id($arr) {
	/**example
	*	$arr =  array(
	*		'id' => $id,
	*		'action' => 'get_name'
	*	);
	*/

	global $dbpdo;

	$id = $arr['id'];
	$action = $arr['action'];

	$get_prod = $dbpdo->prepare('SELECT * FROM stock_list WHERE stock_id = ?');
	$get_prod->execute([$id]);

	$row = $get_prod->fetch(PDO::FETCH_ASSOC);

	switch ($action) {
		case 'name':
			return $row['stock_name'];
			break;
		case 'imei':
			return $row['stock_phone_imei'];
			break;
		case 'provider':
			return $row['stock_provider'];
			break;
		case 'category':
			return $row['stock_provider'];
			break;			
		case 'first_price':
			return $row['stock_first_price'];
			break;
		case 'all':
			return $row['stock_name'];
			break;												
		
		default:
			# code...
			break;
	}

}


//дебаг функция
function ls_var_dump($var) {
	echo "<pre>";
		print_r($var) ;
	echo "</pre>";
}



/**  обновляем фильтры продукта или добавляем
*	 example
*
*	$param = array(
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

//тут описываем страницы 
function get_tab_main_page_test() {
	$menu_list = [
		'terminal' =>	[
			'title' 			=> 'Əməliyyatlar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-store-alt'
			],
			'link'  			=> '/page/base.php',
			'template_src'      => 'page/base_tpl.twig',
			'background_color'  => 'rgba(0, 150, 136, 0.1)',
			'tab' => array(
				'list' => [
					'tab_terminal_phone',
					'tab_terminal_akss',
					'tab_cart'
				],
				'active' => 'tab_terminal_phone'
			)
		],	
		'stock' =>	[
			'title'		 		=> 'Anbar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-boxes'
			],
			'link'  			=> '/page/base.php',		
			'template_src'      => '/page/base_tpl.twig',
			'background_color'  => 'rgba(72, 61, 139, 0.1)',
			'tab' => array(
				'list' => [
					'tab_stock_phone',
					'tab_stock_akss'
				],
				'active' => 'tab_stock_phone'			
			)
		],	
		'report' => [
			'title' 			=> 'Hesabat',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-coins'
			],
			'link'  			=> '/page/base.php',
			'template_src'		=> '/page/base_tpl.twig',
			'background_color'  => 'rgba(33, 150, 243, 0.1)',			
			'tab' => array(
				'list'=> [
					'tab_report_phone', 
					'tab_report_akss'
				],
				'active' => 'tab_report_phone'
			
			)
		],	
		'admin' => [
			'title' => 'Admin',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-user-lock'
			],
			'link'  => '/page/admin/admin.php',
			'background_color' => 'rgba(255, 48, 48, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		],			
		'note' => [
			'title' => 'Notlar',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'lar la-sticky-note'
			],
			'link'  => '/page/note/note.php',
			'background_color' => 'rgba(255, 255, 101, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		],	
		'rasxod' => [
			'title' => 'Xərc (Rasxod)',
			'icon'				=> [
				'img_big'		 	=> '',
				'img_small'			=> '',
				'modify_class' 		=> 'las la-file-invoice-dollar'
			],
			'link'  => '/page/rasxod/rasxod.php',
			'background_color' => 'rgba(255, 48, 48, 0.1)',
			'default_tab' => '',			
			'tab' => array(
								
			)
		]
	];
	return $menu_list;
}

//тут описываем вкладки и страницы
function get_tab_data($key = null, $active = null) {
	$result = [];

	$tab_arr = array(
		'tab_terminal_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/terminal/terminal.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_terminal_akss' => array(
			'type'				=> 'akss',
			'tab_title' 		=> 'DigƏr',
			'tab_link' 			=> '/page/terminal/terminal.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => '' 
		),
		'tab_stock_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/stock/stock.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_stock_akss' => array(
			'type'				=> 'akss',
			'tab_title' 		=> 'DigƏr',
			'tab_link' 			=> '/page/stock/stock.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_report_phone' => array(
			'type'				=> 'phone',
			'tab_title'			=> 'Telefonlar',
			'tab_link' 			=> '/page/report/report.php',
			'tab_icon' 			=> '/img/icon/investment.png',
			'tab_modify_class'  => ''		
		),
		'tab_report_akss' => array(
			'type'				=> 'akss',
			'tab_title'			=> 'DigƏr',
			'tab_link' 			=> '/page/report/report.php',
			'tab_icon' 			=> '',
			'tab_modify_class'  => ''
		),
		'tab_test' => array(
			'type' => 'phone',
			'tab_title' => 'Admin panel',
			'tab_link' => '/page/terminal/terminal.php',
			'tab_icon' => false,
			'tab_modify_class' => false
		),
		'tab_cart' => array(
			'type' => false,
			'tab_title' => 'Корзина',
			'tab_link' => '/page/terminal/cart.php',
			'tab_icon' => false,
			'tab_modify_class' => 'pos-relative',
			'mark' => [
				'modify_class' => 'widget__mark-rigt in-cart-count',
				'text' => ''
			]
		),	 
	);

	if(!empty($key)) {
		foreach ($key as $row => $value) {

			$result[$value] = $tab_arr[$value];
			//если значение таба активна, то присваиваем вкладке класс модификатор активной вкладки
			if($active) {
			   $result[$active]['tab_modify_class'] = 'tab_activ';
			}
	   }
	   return $result;
	} else {
		return $tab_arr;
	}
	

}

function collect_product_data($stock_list, $data_name) {
	global $manat_image,
	$manat_image_green, 
	$stock_return_image;

	$result 	= [];
	$th_list 	= [];
	$complete 	= [];

	$th 		= get_th_list();		

	// ls_var_dump($stock_list	);

	foreach ($data_name as $td_list => $td_row) {
		$th_this		 	= $th[$td_list];
		$th_title 			= $th_this['is_title'];
		$th_modify_class 	= $th_this['modify_class'];
		$td_class 			= $th_this['td_class'];
		$link_class 		= $th_this['link_class'];
		$data_sort 			= $th_this['data_sort'];
		$mark 				= $th_this['mark'];

		if($th_title) {

			$th_list[] = [
				'title' => $th_title,
				'modify_class' => $th_modify_class
			];

			$mass = [];
			foreach ($stock_list as $key => $row) {
				//fix return	
				($row['stock_return_status'] == 1) ? $row['stock_return_status'] = ' ' : $row['stock_return_status'] = false;

				if(array_key_exists($td_row, $row)) {
					$data = $row[$td_row];
				} else {
					$data = null;
				}

				$result[$key][$row['stock_id']][] = [
					'data' 			=> $data,
					'td_class' 		=> $td_class,
					'link_class' 	=> $link_class,
					'data_sort' 	=> $data_sort,
					'mark'			=> $mark
				];
			}
		}
	}

	$complete = [
		'th' => $th_list,
		'td' => $result
	];
	return $complete;	
}


function get_th_list() {
	global $manat_image,
			$manat_image_green, 
			$stock_return_image;

		$th_list = [
			'id' => array(
				'is_title' 			=> check_th_return_name('th_serial'),
				'modify_class'	 	=> 'th_w40',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)				
			),
			'name' => array(
				'is_title'  		=> check_th_return_name('th_prod_name'),
				'modify_class' 		=> 'th_w250',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_name filter-hotkey-sort',
				'data_sort' 		=> 'name',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_title'		=> 'axtar',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => ''
					)
				)	
			),									
			'imei'  => array( 
				'is_title' 			=> check_th_return_name('th_imei'),
				'modify_class' 		=> 'th_w250',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_imei',
				'data_sort' 		=> 'imei',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'first_price' => array(
				'is_title'  		=> check_th_return_name('th_buy_price'),
				'modify_class'		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_fprice',
				'data_sort' 		=> '',
				'mark_text' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class'
					)
				)	
			),
			'second_price' => array(
				'is_title'  		=> check_th_return_name('th_sale_price'),
				'modify_class'		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_sprice',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_title'		=> 'hekk',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class'
					)
				)	
			),	
			'provider' => array(
				'is_title'  		=> check_th_return_name('th_provider'),
				'modify_class'		=> 'th_w200',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_provider',
				'data_sort' 		=> 'provider',
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'return_status' => array(
				'is_title'  		=>  check_th_return_name('th_return'),
				'modify_class'		=> 'th_w40',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_modify_class' => 'mark-chips mark-danger width-100 height-100',
					'mark_text' 		=> 'Bəli',
					'mark_title'		=> 'Bu mall vazvrat olunub',
					'mark_icon'			=> array(
						'path' 		 => false,
						'icon_class' => false
					)
				)
			),												
			'count' => array(
				'is_title' 			=> check_th_return_name('th_count'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',
				'mark'				=> array(
					'mark_text' 		=> 'ədəd',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'category' => array(
				'is_title' 			=> check_th_return_name('th_category'),
				'modify_class' 		=> 'th_w200',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_provider',
				'data_sort' 		=> 'provider',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)	
			),
			'stock_add_date' => array(
				'is_title' 			=> check_th_return_name('th_buy_day'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_buy_date',
				'data_sort' 		=> 'buy_date',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'sales_date' => array(
				'is_title' 			=> check_th_return_name('th_day_sale'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both res_buy_date',
				'data_sort' 		=> 'buy_date',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'report_note' => array(
				'is_title' 			=> check_th_return_name('th_report_note'),
				'modify_class' 		=> 'th_w120',
				'td_class' 			=> '',
				'link_class' 		=> ' stock-link-text-both res_note',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '',
						'icon_class' => '',
						'icon_title' => ''
					)
				)					
			),
			'report_profit' => array(
				'is_title' 			=> check_th_return_name('th_profit'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class',
						'icon_title' => ''
					)
				)					
			),					
			'report_date_year' => array(
				'is_title' 			=> false,
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both',
				'data_sort' 		=> 'date_year',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => '/img/icon/manat.svg',
						'icon_class' => 'manat_con_class',
						'icon_title' => ''
					)
				)					
			),
			'report_order_id' => array(
				'is_title' 			=> check_th_return_name('th_report_serial'),
				'modify_class' 		=> 'th_w80',
				'td_class' 			=> '',
				'link_class' 		=> 'stock-link-text-both get_report_order_id',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_icon'			=> array(
						'path' 		 => false,
						'icon_class' => false,
						'icon_title' => false
					)
				)					
			),
			'terminal_add_basket' => array(
				'is_title' 			=> ' ',
				'modify_class' 		=> 'th_w60',
				'td_class' 			=> 'table-ui-reset',
				'link_class' 		=> 'las la-cart-plus btn btn-secondary add-basket-btn-icon add-basket-button width-100 add-to-cart',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_modify_class' => '',
					'mark_icon'			=> array(
						'path' 		 => false,
						'icon_class' => false,
						'icon_title' => false
					)
				)					
			),
			'terminal_basket_count_plus' => array(
				'is_title' 			=> ' ',
				'modify_class' 		=> 'th_w60',
				'td_class' 			=> 'table-ui-reset',
				'link_class' 		=> 'las la-plus btn btn-primary add-basket-btn-icon width-100 card-plus-count',
				'data_sort' 		=> '',				
				'mark'				=> array(
					'mark_text' 		=> '',
					'mark_modify_class' => '',
					'mark_icon'			=> array(
						'path' 		 => false,
						'icon_class' => false,
						'icon_title' => false
					)
				)					
			),											 			  					
		];
	
	return $th_list;
}


function ls_db_request($arr, $query) {
	/**
	 * функция принимает 2 агрумента в виде массива
	 * внутри первого массива, массив `request` который имеет ключи `param` и массив `bindList`
	 * 	param - параметр запроса, в котором указыается сам запрос к базе без указания названия табицы и перечисления столбцов
	 * 	массив bindList  - данные(переменные) которые используются в запросе заносим в этот массив
	 * внутри второго массива ключи table_name, base_query, sort_by
	 * table_name - Название таблицы
	 * base_query - Дефолтный sql запрос к таблице в котором указываем навазние таблицы и все поля
	 * sort_by - указваем сортировку, можно оставить пустым 
	 */	

	global $dbpdo;
	$stock_list 		= [];
	$result 			= [];
	$conditions 		= [];
	$table_name 		= $query['table_name'];
	$base_query 		= $query['base_query'];
	$sort_by			= $query['sort_by'];
	// ls_var_dump($query);

	// ls_var_dump($arr);
	// ls_var_dump($query);


	$query 	= $base_query;
	foreach($arr as $q => $query_row) {

		$param = $query_row['param'];
		// ls_var_dump($param);
		$bind_list = $query_row['bindList'];
		$query .= $param;
		$conditions = array_merge($conditions, $bind_list);		
	}
	$query .= $sort_by;

	// ls_var_dump($query);
	$stock_view = $dbpdo->prepare($query);	

	foreach($conditions as $bind_key => $bindValue) {
		$stock_view->bindValue($bind_key, $bindValue);
	}

	$stock_view->execute();
	// ls_var_dump($query);
	while ($row = $stock_view->fetch(PDO::FETCH_BOTH)) {	
		$result[] = $row;
	}
	return  $result;	
}
 
function ls_db_upadte($option, $POST) {
	global $dbpdo;

	$before 	= $option['before'];
	$after 		= $option['after'];
	$post_list  = $option['post_list'];
	$conditions = '';
	foreach($post_list as $post_key => $post_value) {
		if(array_key_exists($post_key, $_POST)) {
			if(array_key_exists('require', $post_value)) {
				if(empty($_POST[$post_key])) {
					return json_encode([
						'error' => 'Заполните все обязательные поля!'
					]);
				}
			}

			if($post_value['query']) {
				$conditions[] = $post_value['query'];
			}
			
			if($post_value['bind']) {
				$bind_list[$post_value['bind']] = $_POST[$post_key];
			}
		}
	}

	if($conditions) {
		$conditions = implode($conditions, ", ");
	}
	
	$query = $before;
	$query .= $conditions;
	$query .= $after;

	// ls_var_dump($bind_list);
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

function default_data_param_sql($arr) {
	$page = $arr['page'];
	$type = $arr['type'];
	$param = [];

	if($page == 'terminal') {
		$table_name = 'stock_list';	
		$base_query = " SELECT * FROM user_control
		INNER JOIN stock_list ON stock_visible != 3 ";
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									  AND stock_count > 0  
									  AND stock_visible = 0 ",					   
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									  AND stock_count > 0  
									  AND stock_visible = 0  " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "
				);
				break;				
		}
	}

	if($page == 'stock') {	
		$table_name = 'stock_list';	
		$base_query = " SELECT * FROM user_control 
						INNER JOIN stock_list ON stock_list.stock_visible != 3";
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type 
									  AND stock_list.stock_count > 0  
									  AND stock_list.stock_visible = 0 " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_list.stock_id DESC ORDER BY stock_list.stock_id DESC "	
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_type = :stock_type 
									AND stock_count >= 0  
									AND stock_visible = 0 " ,
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " ORDER BY stock_id DESC"	
				);
				break;				
		}
	}	

	if($page == 'report') {	
		$table_name = 'stock_list, stock_order_report';
		$base_query = "SELECT * FROM user_control 
						INNER JOIN stock_list ON stock_list.stock_id != 0
						
					    INNER JOIN stock_order_report ON  stock_order_report.stock_order_visible = 0
					   ";			
		switch($type) {
			case 'phone':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type
									  AND stock_order_report.stock_id = stock_list.stock_id
									  AND stock_order_report.order_stock_count > 0 ",
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_order_report.order_stock_id DESC
					ORDER BY stock_order_report.order_stock_id DESC"	
				);
				break;
			case 'akss':
				$param = array(
					'query' => array(
						'param' =>  " AND stock_list.stock_type = :stock_type
									  AND stock_order_report.stock_id = stock_list.stock_id
									  AND stock_order_report.order_stock_count > 0 ",
						'bindList' => array(
							':stock_type' => $type
						)
					),
					'sort_by' => " GROUP BY stock_order_report.order_stock_id DESC
								   ORDER BY stock_order_report.order_date DESC"
					
				);
				break;				
		}
	}	

	$get_param = false;
	$get_sort  = false;

	if(array_key_exists('query', $param)) {
		$get_param = $param['query'];
	}
	if(array_key_exists('sort_by', $param)) {
		$get_sort = $param['sort_by'];
	}
	// return $param;
	return [
		 'param' 		=> $get_param,
		 'sort_by' 		=> $get_sort,
		 'table_name' 	=> $table_name,
		 'base_query' 	=> $base_query
	];
}

//тут описываем какие данные нужно выводить для кажждо категории, нужно тут описывать
function page_data_list($arr) {
	$page = $arr['page'];
	$type = $arr['type'];


	if($page == 'terminal') {
		if($type == 'phone') {

			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'name'			 	=> 'stock_name',
					'imei' 				=> 'stock_phone_imei',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'provider' 			=> 'stock_provider',
					'return_status' 	=> 'stock_return_status',
					'terminal_add_basket' => null
				],
				'table_total_list' => [
					'total_count',
					'total_first_price_sum'					
				],
				'modal' => [
					'template_block' => 'terminal_order',
					'modal_fields' => [
						'user',
						'stock_name',
						'stock_imei',
						'stock_provider',
						'order_first_price',
						'spoiler_filter',
						'order_note',
						'order_hidden_count',
						'order_second_price',
						'order_total_amount',
						'order_submit'
					]
				],
				'filter_fields' => [
					'color',
					'used',
					'storage',
					'ram',
					'brand'
				]
			];	
		}
		if($type == 'akss') {		
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'name'			 	=> 'stock_name',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'count'				=> 'stock_count',
					'category' 			=> 'stock_provider',
					'terminal_add_basket' => null,

				],
				'table_total_list' => [
					'total_count'
				],
				'modal' => [
					'template_block' => 'terminal_order',
					'modal_fields' => [
						'user',
						'stock_name',
						'stock_provider',
						'spoiler_filter',
						'order_note',
						'order_stock_count',
						'order_second_price',
						'order_total_amount',
						'order_submit'
					]					
				],
				'filter_fields' => [
					'color',
					'brand'
				]
			];
		}

	}


	if($page == 'stock') {
		if($type == 'phone') {
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'stock_add_date'	=> 'stock_get_fdate',
					'name'			 	=> 'stock_name',
					'imei' 				=> 'stock_phone_imei',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'provider' 			=> 'stock_provider',
					'return_status'		=> 'stock_return_status'
				],
				'table_total_list'	=> [
					'total_count',
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'edit_stock_name',
						'edit_stock_imei',
						'edit_stck_provider',
						'edit_stock_first_price',
						'edit_stock_second_price',
						'edit_stock_hidden_count',
						'edit_filter_spoiler',
						'edit_save'
					)					
				],
				'filter_fields' => [
					'color',
					'storage',
					'ram',
					'brand'
				],
				'form_fields_list' => array(
					'name'			=> true,
					'imei'			=> true,
					'first_price'	=> true,
					'hidden_count'	=> true,
					'second_price'	=> true,
					'provider'	    => true
				)				

			];	
		
		}
	
		if($type == 'akss') {
			$res = [
				'get_data' => [
					'id' 				=> 'stock_id',
					'stock_add_date'	=> 'stock_get_fdate', 	
					'name'			 	=> 'stock_name',
					'first_price'		=> 'stock_first_price',
					'second_price' 		=> 'stock_second_price',
					'count'				=> 'stock_count',
					'category' 			=> 'stock_provider'						
				],
				'table_total_list' => [
					'total_count',
					'total_first_price_sum'
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'edit_stock_name',
						'edit_stock_category',
						'edit_stck_provider',
						'edit_stock_first_price',
						'edit_stock_second_price',
						'edit_stock_count',
						'edit_filter_spoiler',
						'edit_save'
					)					
				],
				'filter_fields' => [
					'color',
					'brand'
				],
				'form_fields_list' => array(
					'name'			=> true,
					'first_price'	=> true,
					'count'			=> true,
					'second_price'	=> true,
					'provider'	    => true
				)
			];			
		}
				
	}

	if($page == 'report') {		
		if($type == 'phone') {
			$res = [
				'get_data' => [
					'report_date_year' 	=> 'order_my_date',
					'report_order_id' 	=> 'order_stock_id',
					'sales_date'		=> 'order_date',
					'name'			 	=> 'order_stock_name',
					'imei'				=> 'stock_phone_imei',
					'second_price'		=> 'order_stock_sprice',
					'provider'			=> 'stock_provider',
					'report_note'		=> 'order_who_buy',
					'count'				=> 'order_stock_count',
					'report_profit'		=> 'order_total_profit',
				],
				'table_total_list'	=> [
					'total_count',
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'user',
						'report_order_id',
						'stock_name',
						'stock_imei',
						'stock_provider',
						'report_order_price',
						'report_order_count',
						'order_total_amount',
						'report_save_edit'
					)
				],
				'filter_fields' => [
					'color',
					'brand'
				],				

			];								
		}
	
		if($type == 'akss') {
			$res = [
				'get_data' => [
					'report_date_year'  => 'order_my_date',
					'report_order_id' 	=> 'order_stock_id',
					'sales_date'		=> 'order_date',
					'name'			 	=> 'order_stock_name',
					'second_price'		=> 'order_stock_sprice',
					'category'			=> 'stock_provider',
					'report_note'		=> 'order_who_buy',
					'count'				=> 'order_stock_count',
					'report_profit'		=> 'order_total_profit',
				],
				'table_total_list'	=> [
					'total_first_price_sum',
				],
				'modal' => [
					'template_block' => 'edit_product',
					'modal_fields' => array(
						'name',
						'report_order_id',
						'report_order_count',
						'report_save_edit'
					)
				],
				'filter_fields' => [
					'color',
					'brand'
				],				

			];					
	
		}					
	}

	// ls_var_dump($res);
	return $res;
}


// в этой функцие описываем какие данные таблицы нужны для определённой категории
// пример вызова функции
/**
 * 	$arr = array(
 * 	 'type' => 'phone/akss/..'         - обьязательное поле 
 * 	 'page => 'terminal/stock/report'  - обьязательное поле
 * 	'search' => array(                 - не обьязательное поле
 * 	 'param' =>  " AND stock_type = :stock_type AND stock_count > 0 " ,
 *		'bindList' => array(
 *			':stock_type' => $type
 *		)
 *	)
 * 	);
**/
function render_data_template($arr) {
	//категория
	$type = $arr['type'];
	//страница
	$page = $arr['page'];
	$data = default_data_param_sql([
		'type' => $type,
		'page' => $page
	]);

	// ls_var_dump($data);

	if(array_key_exists('search', $arr)) {
		$param[] = $arr['search'];
	}

	if(array_key_exists('search_sort_by', $arr)) {
		$order_sort = $arr['search_sort_by'];
	} elseif(array_key_exists('sort_by', $data)) {
		$order_sort = $data['sort_by'];
	} else {
		$order_sort = false;
	}


	$param[] = $data['param'];
	


	$table_name = $data['table_name'];
	$base_query = $data['base_query'];
	
	$get_data = page_data_list([
		'type' => $type,
		'page' => $page
	]);


	$query = [
		'table_name' => $table_name,
		'base_query' => $base_query,
		'sort_by'	 => $order_sort
	];

	// ls_var_dump($query);
	// ls_var_dump($param);
	
	$stock_list = ls_db_request($param, $query);

// ls_var_dump($stock_list);

	return [
		'result' => collect_product_data($stock_list, $get_data['get_data']),
		'base_result' => query_clear_by_user_access([ 'query' => $stock_list, 'access' => $get_data ])
	]; 	

}

//количество товаров 
function get_table_result_row_count($arr) {
	$total_count = [];
	foreach($arr as $stock) {
		$total_count[] = $stock['stock_count'];
	}

	return [
		'title' => 'Tapıldı',
		'value' => array_sum($total_count),
		'mark' 	=> 'ədəd'
	];
}

//сумма себестоимости
function get_stock_first_price_sum($stock_list) {
	$total_sum = [];
	foreach($stock_list as $key) {
		if(array_key_exists('stock_first_price', $key) && array_key_exists('stock_count', $key)) {
			$total_sum[] = $key['stock_first_price'] * $key['stock_count'];
		}	
	}
	return [
		'title' => 'Malların ümumi dəyəri',
		'value' => array_sum($total_sum),
		'mark' 	=> '',
		'mark_icon' => '/img/icon/manat.svg'
	];	
}

//вызываем данные для футреа таблицы
function get_table_total($arr) {
	if($arr['total_list']) {
		$total_list = $arr['total_list'];
		$stock_list = $arr['data'];
	
		foreach($total_list as $key) {
			if($key == 'total_count') {
				$res[] = get_table_result_row_count($stock_list);
			}
			if($key == 'total_first_price_sum') {
				$res[] = get_stock_first_price_sum($stock_list); 
			}
		}
		return $res;
	}
}




// получаем список поставщиков
function get_provider_list() {	
	$provider = ls_db_request( 
		array(
			'request' => [
				'param' => ' AND stock_type = :stock_type AND stock_count > 0   AND stock_visible = 0 ',
				'bindList' => array(
					'stock_type' => 'phone'
				)
			]
		),
		array(
			'table_name' => 'stock_list',
			'base_query' => 'SELECT DISTINCT stock_provider FROM stock_list WHERE stock_visible != 3  ',
			'sort_by' 	 => ' ORDER BY stock_id DESC  '	
		)
	);


	foreach($provider as $key => $row) {
		$provider_list[] = $row['stock_provider'];
	}

	return $provider_list;
}


function get_report_date_list($type) {
	$res = ls_db_request(
		array(
			'request' => [
				'param' => " AND stock_type = :stock_type  AND order_stock_count > 0 ",
				'bindList' => array(
					'stock_type' => $type
				)
			]
		),
		array(
			'table_name' => 'stock_order_report',
			'base_query' =>  "SELECT DISTINCT order_my_date FROM stock_order_report WHERE stock_order_visible = 0 ",
			'sort_by' => ' ORDER BY order_real_time desc '
		)
	);

	foreach($res as $key => $row) {
		$dd[] = $row['order_my_date'];
	}

	return $dd;
}

