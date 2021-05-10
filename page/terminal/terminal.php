<?php

	// $tpl = $twig->load($tpl_src);
	$page_config = page_data_list(['type' => $type, 'page' => $page ]);
	//параметры поиска
	$search_arr = array(
		'input_class' 	 => 'search-auto', //классы поля ввода поиска
		'parent_class'	 => '', //класс для родителя инпута
		'label'			 => '', //заполнить/оставить пустым или 
		'input_placeholder' => 'Axtar',
		'reset' => true,
		'autocomplete' => array(
			'type' 	=> 'search' 
		)
	);
	
	$table_result = render_data_template([
		'type' => $type,
		'page' => $page
	]);


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
				'table_total'    	=> get_table_total(['total_list' => $page_config['table_total_list'],  'data' => $table_result['base_result']]) 
			]
			
		]
	]);
		
?>