<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

header('Content-type: Application/json');

$page = 'provider_form';

$this_data = page_data($page);
$page_config = $this_data['page_data_list'];


$data =  $twig->render('/component/include_component.twig', [
    'renderComponent' => [
        '/component/form/modal/modal_form_view.twig' => [
            'res' => $page_config['form_fields_list']
        ],
    ]
]);


if($data) {
    echo json_encode([
        'success' => $data
    ]);
} else {
    echo json_encode([
        'error' => 'Error'
    ]);
}
