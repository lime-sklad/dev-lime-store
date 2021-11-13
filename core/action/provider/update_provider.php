<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

// в первом массиве мы должны описать и связвать данные $_POST с таблицей

$option = [
	'before' => " UPDATE stock_provider, user_control SET ",
	'after' => " WHERE stock_provider.provider_id  = :provider_id ",
	'post_list' => [
		//id
		'provider_id' => [ 
			'query' => false,
			'bind' => 'provider_id',
			'require' => true
		],	
		//изменить название категории
		'upd_provider_name' => [
			'query' => "stock_provider.provider_name = :cat_name",
			'bind' => 'cat_name',
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
} else {
	echo json_encode([
		'error' => 'Вы ничего не изменили'
	]);
}
