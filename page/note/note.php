<?php
	require_once '../../function.php';
	
	ls_include_tpl();

	//пути к категориям
	get_product_root_dir();	


	tab_page_header($tab_arr['tab_note_order']['title']);

	//абсолютный пусть к файлам
	root_dir();

	get_tab_main_page();
	//модальное окно успешно выполнено функция
	success_done();
	//модальное коно не выполнено функция
	fail_notify();
	

	//выводим перекючения вкладок 
	$get_return_tab =  array(
		'tab_note_order'
	); 

	get_current_tab($arr = array(
		'link_list' => $get_return_tab,
		'registry_tab_link' => $tab_arr,
		'default_link' => 'tab_note_order',
		'modify_class' => '',
		'parent_modify_class' => ''
	));		

?>


<div class="terminal_main">
	<?php require_once GET_ROOT_DIRS.$tab_arr['tab_note_order']['tab_link'];	?>
</div>


<?php get_right_modal(); ?>