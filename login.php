<?php 
require $_SERVER['DOCUMENT_ROOT'].'/db/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/core/function/db.wrapper.php';
require $_SERVER['DOCUMENT_ROOT'].'/core/function/user.function.php';
require $_SERVER['DOCUMENT_ROOT'].'/include/lib_include.php';

if(isset($_SESSION['user'])) {
	require_once $_SERVER['DOCUMENT_ROOT']."/core/main/check_files.php";
	exit();
}

	echo $twig->render('/component/include_component.twig', [
		'renderComponent' => [
			'/component/index/head.twig' => [
				'lib_list' => [
					'css' => [
						'css/fonts.css',
						'css/style_var.css',
						'css/template.css',
						'css/animate.min.css',
						'lib/css_lib/line-awesome/css/line-awesome.min.css',
						'css/new.style.css'
					],
					'script' => [
						'lib/js_lib/jquery-3.3.1.min.js',
						'js/upd.function.js',
						'js/upd.ajax.js',				
					]
				],
				'v' => time() 
			],
			'/component/widget/notice.twig' => [
				//code
			],
			'/component/related_component/body_preloader.twig' => [
				//data
			],
			'/component/related_component/overlay.twig' => [
				//data
			],				
		]
	]); 

	$user_name_list = get_all_user_list();
	$user_name_list = array_column($user_name_list, 'user_name');

	echo $twig->render('/component/container.twig', [
		'includs' => [
			'renderLogin' => [
				'/component/login/login_form.twig' => [
					'login' => $user_name_list,
				]
			]
		]
	]);
