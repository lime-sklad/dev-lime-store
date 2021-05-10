<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');



// в первом массиве мы должны описать и связвать данные $_POST с таблицей

$option = [
	'before' => " UPDATE stock_list SET ",
	'after' => " WHERE stock_id = :stock_id",
	'post_list' => [
		'upd_product_id' => [
			'query' => false,
			'bind' => 'stock_id'
		],
		'product_count' => [
			'query' => "stock_list.stock_count = stock_list.stock_count + :product_count",
			'bind' => 'product_count'
		],
		'product_name' => [
			'query' => "stock_list.stock_name = :prod_name",
			'bind' => 'prod_name'
		],
		'product_imei' => [
			'query' => "stock_list.stock_phone_imei = :prod_imei",
			'bind' => 'prod_imei'
		]
	]
];

echo ls_db_upadte($option, $_POST);




// UPDATE stock_list 
// 	SET stock_list.stock_count = stock_list.stock_count + :product_count, 
// 		stock_list.stock_name = :prod_name, 
// 		stock_list.stock_phone_imei = :prod_imei 
// 		WHERE stock_id = :stock_id

exit();
//получем категорию товара
get_product_category();

$user = getUser('get_user_role');


if( isset($_POST['upd_product_id']) ){

	if(isset($_POST['product_imei'])) $product_imei = trim(strip_tags($_POST['product_imei']));
	
	if(empty($_POST['product_imei'])) $product_imei = '';

	$upd_product_id 	= 	$_POST['upd_product_id'];
	$prod_category		=	trim(strip_tags($_POST['prod_category']));
	$upd_prod_name		=	trim(strip_tags($_POST['product_name']));					
	$product_fprice		= 	trim(strip_tags($_POST['product_fprice']));
	$product_provider	= 	trim(strip_tags($_POST['product_provider']));
	$product_sprice		=	trim(strip_tags($_POST['product_sprice']));
	$product_count  	=   trim(strip_tags($_POST['product_count']));

	//проверка на пустоту значений
	//если пустое имя
	if(empty($upd_prod_name)) {
		$error_notify = 'Məhsulun adını doldurun';

			$product_err = [
			  'error' => $error_notify
			];									  								   
			//выводим сообщение и останавливаем
			echo json_encode($product_err);	
			exit();
	}

	//проверка на пустоту значений
	//если пустое количество
	if(empty($product_count)) {
		$error_notify = 'Məhsulun sayini doldurun';
		$product_err = [
		  'error' => $error_notify
		];									  								   
		//выводим сообщение и останавливаем
		echo json_encode($product_err);	
		exit();
	}	

	//если пусто себе стоимость товара
	if($product_fprice <= 0) {
		$error_notify = 'Məhsulun alış qiyməti doldurund';

		$product_err = [
		  'error' => $error_notify
		];									  								   
		//выводим сообщение и останавливаем
		echo json_encode($product_err);	
		exit();
	}

	//обновляем телефон
	if($prod_category == $product_phone) {
		$update_phone = $dbpdo->prepare("UPDATE stock_list 
				SET stock_name= ?, 
				stock_phone_imei= ?, 
				stock_first_price = ?, 
				stock_second_price = ?, 
				stock_provider = ?
				WHERE stock_id = ?
				");
		$update_phone->execute([$upd_prod_name,
								$product_imei,
								$product_fprice,
								$product_sprice,
								$product_provider,
								$upd_product_id
							  ]);

	}

	//обновляем аксессуар
	if($prod_category == $product_akss) {

		//если прибавляют к количеству
		if(isset($_POST['prdocut_count_plus']) && empty($_POST['prdocut_count_minus'])) {
			if(!empty($_POST['prdocut_count_plus'])) {

				$product_plus_count = trim(strip_tags($_POST['prdocut_count_plus']));
				//получем количестов товара и прибавляем к нему
				$product_count = $product_count +  $product_plus_count;
			}
		}
		//если отнимают к количеству
		if(isset($_POST['prdocut_count_minus']) && empty($_POST['prdocut_count_plus'])) {
			if(!empty($_POST['prdocut_count_minus'])) {

				$product_minus_count = trim(strip_tags($_POST['prdocut_count_minus']));
				//получем количестов товара и отнимаем к нему
				$product_count = $product_count -  $product_minus_count;
			}
		}

		$update_akss = $dbpdo->prepare("UPDATE stock_list 
				SET stock_name= ?,  
				stock_first_price = ?, 
				stock_second_price = ?, 
				stock_provider = ?,
				stock_count = ?
				WHERE stock_id = ?
				");
		$update_akss->execute([$upd_prod_name,
								$product_fprice,
								$product_sprice,
								$product_provider,
								$product_count,
								$upd_product_id
							  ]);		
	}

	//обновляем фильтры
	if(isset($_POST['filter_list'])) {
		$filter = [];
		$filter_list = $_POST['filter_list'];

		$filter['stock_id'] = ['stock_id' => $upd_product_id, 'filter' => $filter_list];

		ls_update_filter($filter);
	}
	//если запрос пустой, то сбарсываем фильтры продукта 
	if(!isset($_POST['filter_list']) || empty($_POST['filter_list']) || !$_POST['filter_list']) {
		ls_reset_filter($upd_product_id);
	}



		$upd_success = [
			'success' 			=> 'ok',
			'upd_id'			=> 	$upd_product_id,
			'upd_name' 			=> 	$upd_prod_name,
			'upd_imei' 			=> 	$product_imei,
			'upd_fprice' 		=> 	$product_fprice,
			'upd_sprice'		=>	$product_sprice,
			'upd_provider'		=>	$product_provider,
			'upd_count'			=>  $product_count
		];

		echo json_encode($upd_success);
		exit();
} 
