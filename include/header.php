<?php 
/**
 * LIME SKLAD 2020
 * ©EMIL GASANOV
 * Версия для сотовых магазинов
 */

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';


echo $twig->render('/component/include_component.twig', [
	'renderComponent' => [
		'/component/index/header.twig' => [
			'lib_list' => lib_include_list(),
			'v' => time()
		]
	]
]); 

