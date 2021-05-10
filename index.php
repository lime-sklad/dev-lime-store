<?php 
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

	echo $twig->render('/component/include_component.twig', [
		'renderComponent' => [
			'/component/index/head.twig' => [
				'lib_list' => lib_include_list(),
				'v' => time() 
			]
		]
	]); 
	
	echo $twig->render('/component/related_component/main_page.twig', [
        'renderComponent' => [
			'/component/index/top_nav.twig' => [
				//code
			],
            '/component/main/menu_list.twig' => [
                'menu' =>  get_tab_main_page_test()
			],
			'/component/main/main.twig' => [
				//data
			],
			'/component/modal/modal_wrapper.twig' => [
				//data
			],
			'/component/related_component/body_preloader.twig' => [
				//data
			]			
		],
		'sidebar' => [
			'user_id' => getUser('get_id'),
			'user_name' => getUser('get_name'),
			'user_role' => getUser('get_role'),
			'menu_list' => [
				'data' => get_tab_main_page_test()
			]
		]
	]);
	


	// echo $twig->render('/component/container.twig', [
    //     'renderComponent' => [
	// 		'/component/index/top_nav.twig' => [
	// 			//code
	// 		],
    //         '/component/main_menu/menu_list.twig' => [
    //             'menu' =>  get_tab_main_page_test()
	// 		],
	// 		'/component/modal/modal_wrapper.twig' => [
	// 			//code
	// 		],
    //     ] 
    // ]);

	exit();



	if($update_check_day == $weak_now) {
		check_connections(require_once GET_ROOT_DIRS.'/core/main/update_check.php');
		//опросник
		check_connections(require_once  GET_ROOT_DIRS.'/core/modal_action/show_quiz.php');
	}	

exit();
?>


<div class="container" id="container">
	<div class="sidebar_menu_wrp">
		<?php require_once 'core/main/menu_sidebar.php'; ?>
	</div>

	<div class="main" id="main">
		<div class="main-wrapper" id="main_wrapper">
			<?php require_once 'core/main/options.php'; ?>
		</div>
	</div>

</div>


<!-- <script defer src="/js/check_update.js?v=<?php echo time(); ?>"></script> -->