<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

	ls_include_tpl();
	//Показывать товары которые находться в базе больше 15 дней 
	// settShowOldProduct();
	//выводим заголовок страницы
	tab_page_header('Parametrlər');

	//блок для принта чека
	printModal();
	//пути к категориям
	get_product_root_dir();

	//абсолютный пусть к файлам
	root_dir();

	//выводим вкладки 
	$get_return_tab = array(
		'tab_admin_user',
	);
	//выводим перекючения вкладок 
	get_current_tab(array(
		'link_list' => $get_return_tab,
		'registry_tab_link' => $tab_arr,
		'default_link' => 'tab_admin_user',
		'modify_class' => '',
		'parent_modify_class' => ''
	));		
?>


<div class="admin_panel_content">
 	<?php require_once GET_ROOT_DIRS.$tab_arr['tab_admin_user']['tab_link']; ?>
</div>

<?php get_right_modal(); ?>