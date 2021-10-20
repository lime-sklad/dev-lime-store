<?php 
	$data_page = page_data($page);

	$page_config = $data_page['page_data_list'];
	
	if(array_key_exists('form_fields_list', $page_config)) {
		$form_fields = $page_config['form_fields_list'];
	} else {
		$form_fields = false;
	}

	//параметры поиска
	$search_arr = array(
		'input_class' 	 => 'search-auto area-input', 	//классы поля ввода поиска
		'parent_class'	 => 'search-container-width', 			//класс для родителя инпута
		'input_placeholder' => 'Axtar', //заполнить/оставить пустым или
		'reset' => true, 
		'input_icon' => [
			'icon' => 'la-search',
		],
		'widget_class_list' => '',
		'widget_container_class_list' => 'flex-cntr',
		'autocomplete' 	 => array(
			'type' => 'search',
			'parent_modify_class' => '',
			'autocomlete_class_list' => 'get_item_by_filter search-item area-closeable selectable-search-item'
		)
	);
	
	$table_result = render_data_template($data_page['sql'], $data_page['page_data_list']);

	echo $twig->render('/component/inner_container.twig', [
		'renderComponent' => [
			'/component/related_component/include_widget.twig' => [
				'/component/filter/filter_sort.twig' => [
					'filter_list' => filter_category($page_config['filter_fields'], NULL)
				],
				'/component/search/search.twig' => $search_arr
			],
			'/component/table/table_wrapper.twig' => [
				'table' => $table_result['result'],
				'table_tab' => $page,
				'table_type' => $type,
			],
			'/component/table/table_footer_wrapper.twig' => [
				'table_total' => table_footer_result($page_config['table_total_list'], $table_result['base_result'])
			]
		]
	]);


?>
