<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

// в первом массиве мы должны описать и связвать данные $_POST с таблицей

$option = [
	'before' => " UPDATE stock_list, user_control SET ",
	'after' => " WHERE stock_id = :stock_id",
	'post_list' => [
		//id
		'upd_product_id' => [ 
			'query' => false,
			'bind' => 'stock_id',
			'require' => true
		],	
		//изменить название товра
		'product_name' => [
			'query' => "stock_list.stock_name = :prod_name",
			'bind' => 'prod_name',
		],
		//изменить описание товара (старое imei)
		'product_description' => [
			'query' => "stock_list.stock_phone_imei = :prod_imei",
			'bind' => 'prod_imei'
		],
		//изменить поставщика
		'provider_id' => [
			'query' => "stock_list.product_provider = :provider_id",
			'bind' => 'provider_id'
		],
		//изменить категорию
		'category_id' => [
			'query' => "stock_list.product_category = :category_id",
			'bind' => 'category_id'
		],
		//изменить количество товара
		'plus_minus_product_count' => [
			'query' => "stock_list.stock_count = :add_count",
			'bind' => 'add_count',
		],					
		//изменить себе стоимость товара
		'product_first_price' => [
			'query' => "stock_list.stock_first_price = :f_price",
			'bind' => 'f_price',
		],
		//изменить стоимость
		'product_second_price' => [
			'query' => "stock_list.stock_second_price = :s_price",
			'bind' => 's_price'
		],
		//изменить минимальное количество товара
		'change_min_quantity' => [
			'query' => "stock_list.min_quantity_stock = :min_count",
			'bind' => 'min_count',	
		],

		//изменить количество товара
		'change_product_count' => [
			'query' => "stock_list.stock_count = :change_count",
			'bind' => 'change_count',
		],			

	]
];


/**
 * исправить массив что бы выдывал только 1 результат
 * в js дописать js функцию update_table_row 
 * добавить в function.php id к каждой строке в табице для обновления резальутатата
 */

if(!empty($_POST) && count($_POST) > 1) {
	echo ls_db_upadte($option, $_POST);

	ls_edit_stock_filter($_POST, $_POST['upd_product_id']);

} else {
	echo json_encode([
		'error' => 'Вы ничего не изменили'
	]);
}
