<?php

$data_page = page_data($page);

$page_config = $data_page['page_data_list'];

$table_result = render_data_template($data_page['sql'], $data_page['page_data_list']);	

echo $twig->render('/component/inner_container.twig', [
	'renderComponent' => [
		'/component/table/table_wrapper.twig' => [
			'table' => $table_result['result'],
			'table_tab' => $page,
			'table_type' => $type,
		],
	]
]);