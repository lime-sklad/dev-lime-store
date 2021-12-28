<?php 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'function.php';
require_once 'vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/function/update.function.php';


	echo $twig->render('/component/include_component.twig', [
		'renderComponent' => [
			'/component/index/head.twig' => [
				'lib_list' => lib_include_list(),
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
	
	echo $twig->render('/component/related_component/main_page.twig', [
		'renderComponent' => [

			// sidebar
			'/component/index/sidebar.twig' => [
				'menu_list' => [
					'data' => page_tab_list()
				]
			],

			// main content
			'/component/container.twig' => [
				'includs' => [
					'renderMain' => [
						// header
						'/component/index/top_nav_content/top_nav.twig' => [
							'includs' => [
								'renderTopNavComponent' => [
									'/component/index/top_nav_content/nav_list_options.twig' => [
										'username' => getUser('get_name'),
										// вложеность в шаблоне, рендерим друигие шаблоны
										'includs' => [
											'renderUpdateNotify' => [
												'/component/notify/update/update_notify_item.twig' => [
													'update_notify' => is_check_update()
												]
											],	
										],							
									]
								],
							]
						],

						// menu
						'/component/main/menu_list.twig' => [
							'menu' => page_tab_list()
						],	
						
						// main
						'/component/main/main.twig' => [
							//data
						],
						
						// modal
						'/component/modal/modal_wrapper.twig' => [
							//data
						]							 
					]
				]
			]
		],
	]);
