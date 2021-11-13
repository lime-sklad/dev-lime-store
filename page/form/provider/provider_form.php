<?php
// $page = 'category_form';
$page = $_POST['page'];
$tab =  $_POST['tab'];

$this_data = page_data($page);

$page_config = $this_data['page_data_list'];

$table_result = render_data_template($this_data['sql'], $page_config);

echo $twig->render('/component/inner_container.twig', [
    'renderComponent' => [
        '/component/form/stock_form/stock_form.twig' => [
            'res' => $page_config['form_fields_list']
        ],
        '/component/table/table_wrapper.twig' => [
            'table' => $table_result['result'],
            'table_tab' => $page,
            'table_type' => $type,				
        ],
    ]
]);