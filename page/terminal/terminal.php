<?php

	// $tpl = $twig->load($tpl_src);
	$data_page = page_data($page);

	$page_config = $data_page['page_data_list'];

// ls_var_dump($data_page[]);
	//параметры поиска
	$search_arr = array(
		'input_class' 	 => 'search-auto area-input', //классы поля ввода поиска
		'parent_class'	 => 'search-container-width', //класс для родителя инпута
		'label'			 => '', //заполнить/оставить пустым или 
		'input_placeholder' => 'Axtar',
		'widget_class_list' => '',
		'input_icon' => [
			'icon' => 'la-search'
		],
		'widget_container_class_list' => 'flex-cntr',
		'reset' => true,
		'autocomplete' => array(
			'type' 	=> 'search' 
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
				'table_total'    	=> table_footer_result($page_config['table_total_list'], $table_result['base_result'])
			]
		]
	]);
		
?>