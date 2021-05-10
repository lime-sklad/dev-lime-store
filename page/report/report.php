<?php

//в этой функции описываем какие данные выводим для определенной категории


	// get_report_date_list($type);

	//параметры поиска
	$search_arr = array(
		'input_class' 	 => 'auto-cmplt-input stock_auto_compelete', //классы поля ввода поиска
		'parent_class'	 => 'auto-cmplt-parent search_filter', //класс для родителя инпута
		'label'			 => 'Axtar', //заполнить/оставить пустым или 
		'label_title' 	 => '',
		'clear_button' 	 => array(
			'value' 			=> get_my_dateyear(),
			'data_sort_value' 	=> get_my_dateyear(),
			'data_sort_type'	=> 'date_year',
			'modify_class' 		=> ''
		),
		'autocomplete' 	 => array(
			'type' => 'search'
		)
	);
	$page_config = page_data_list(['type' => $type, 'page' => $page ]);

	$table_result = render_data_template([
		'type' => $type,
		'page' => $page,
		'search' => array(
			'param' => " AND stock_order_report.order_my_date = :mydateyear ",
			'bindList' => array(
				'mydateyear' => get_my_dateyear()
			)
		)

	]);

	// ls_var_dump($table_result['result']);
	
	echo $twig->render('/component/inner_container.twig', [
		'renderComponent' => [
			'/component/related_component/include_tooltip.twig' => [
				'/component/widget/report_date_picker.twig' => [
					'res' => get_report_date_list($type)
				],
				'/component/search/search.twig' => $search_arr,				
			],
			'/component/table/table_wrapper.twig' => [
				'table'				=> $table_result['result'],
				'table_tab' 		=> $page,
				'table_type' 		=> $type,				
			],
			'/component/table/table_footer_wrapper.twig' => [
				'table_total' => get_table_total(['total_list' => $page_config['table_total_list'],  'data' => $table_result['base_result']])
			]
		]
	]);


?>
