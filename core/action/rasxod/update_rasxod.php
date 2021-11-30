<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

// в первом массиве мы должны описать и связвать данные $_POST с таблицей

$option = [
	'before' => " UPDATE rasxod SET ",
	'after' => " WHERE rasxod_id  = :rasxod_id ",
	'post_list' => [
		//id
		'rasxod_id' => [ 
			'query' => false,
			'bind' => 'rasxod_id',
			'require' => true
		],	
		//изменить название категории
		'upd_rasxod_description' => [
			'query' => "rasxod_description = :description_name",
			'bind' => 'description_name',
		],
		'upd_rasxod_amount' => [
			'query' => 'rasxod_money = :amount',
			'bind' => 'amount'
		]
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
