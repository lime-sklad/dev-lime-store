<?php 
	$page_config = page_data_list(['type' => $type, 'page' => $page]);

	//filter_list
	if(array_key_exists('filter_fields', $page_config)) {
		$page_filter_list =  filter_category($page_config['filter_fields'], NULL);
	} else {
		$page_filter_list = false;
	}
	
	if(array_key_exists('form_fields_list', $page_config)) {
		$form_fields = $page_config['form_fields_list'];
	} else {
		$form_fields = false;
	}

	//параметры поиска
	$search_arr = array(
		'input_class' 	 => '', 	//классы поля ввода поиска
		'parent_class'	 => '', 			//класс для родителя инпута
		'label'			 => 'Axtar', 									//заполнить/оставить пустым или 
		'label_title' 	 => '',
		// 'clear_button' 	 => array(
		// 	'modify_class' 		 => '',
		// 	'value'		  		 => '',
		// 	'data_sort_value' 	 => '',
		// 	'data_sort_type' 	 => 'name'
		// ),
		'autocomplete' 	 => array(
			'type' => 'search'
		)
	);
	

	$table_data = render_data_template([
		'type' => $type,
		'page' => $page
	]);


	echo $twig->render('/component/inner_container.twig', [
		'renderComponent' => [
			// '/component/form/stock_form/add_stock.twig' => [
			// 	'title' 		=> $tab_this['tab_title'],
			// 	'fields' 		=> $form_fields,
			// 	'ls_filter'		=> array('show' => true, 'val' => $page_filter_list)
			// ],			
			'/component/related_component/include_widget.twig' => [
				'/component/filter/filter_sort.twig' => [
					'filter_list' => $page_filter_list
				],
				'/component/search/search.twig' => $search_arr,
			],
			'/component/table/table_wrapper.twig' => [
				'table' => $table_data['result'],
				'table_tab' => $page,
				'table_type' => $type,
			],
			'/component/table/table_footer_wrapper.twig' => [
				'table_total' => table_footer_result($page_config['table_total_list'], $table_data['base_result'])
			]
		]
	]);


?>
