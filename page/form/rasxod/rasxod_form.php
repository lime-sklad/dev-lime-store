<?php 
$page = $_POST['page'];
$tab =  $_POST['tab'];

$this_data = page_data($page);

$page_config = $this_data['page_data_list'];

// $modal = $page_config['modal'];

// $modal_tpl_name = $modal['template_block'];
// $modal_fields = $modal['modal_fields'];


echo $twig->render('/component/inner_container.twig', [
    'renderComponent' => [
        '/component/form/stock_form/stock_form.twig' => [
            'stock_form_parent_class_list' => 'rasxod-form-content width-50',
            'res' => $page_config['form_fields_list']
        ],	
    ]
]);
    