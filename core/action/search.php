<?php 

require $_SERVER['DOCUMENT_ROOT'].'/function.php';
header('Content-type: Application/json');

$search_value = ls_trim($_POST['search_item_value']); 
$page         = ls_trim($_POST['page']);
$type         = ls_trim($_POST['type']);
$get_sort_data    = ls_trim($_POST['sort_data']);

$th_list = get_th_list();
$td_data = page_data_list([
	'page' => $page,
	'type' => $type
]);

$sql_data = default_data_param_sql(['page' => $page, 'type' => $type]);

$base_result = [];
$res = [];

$table_name = $sql_data['table_name'];
$base_query = $sql_data['base_query'];
$order_sort = $sql_data['sort_by'];
$query = [
    'table_name' => $table_name,
    'base_query' => $base_query,
    'sort_by'	 => $order_sort
];
$stock_list = [];
$param[] = $sql_data['param'];

foreach($td_data['get_data'] as $key => $col_name_prefix) {
	$th_this = $th_list[$key];
    $data_sort = $th_this['data_sort'];

	if($data_sort == $get_sort_data) {
        if(!empty($search_value)) {
            $render_tpl = render_data_template([
                'type' => $type,
                'page' => $page,
                'search' => [
                    'param' =>  " AND $col_name_prefix = :search ",
                    'bindList' => array(
                        'search' =>  $search_value
                    )
                ]       
            ]);  
        } else {
            $render_tpl = render_data_template([
                'type' => $type,
                'page' => $page,
                'search' => [
                    'param' => " AND $col_name_prefix LIKE :search ",
                    'bindList' => array(
                        'search' => "%{$search_value}%"
                    )
                ]
            ]);     
        }


        
        $table = $twig->render('/component/include_component.twig', [
            'renderComponent' => [
                '/component/table/table_row.twig' => [
                    'table' => $render_tpl['result'],
                    'table_tab' => $page,
                    'table_type' => $type       
                ]
            ]
        ]);
        
        if(!empty($render_tpl['base_result'])) {
            // $base_result[] = $render_tpl['base_result'];                  
            $base_result = array_merge($base_result, $render_tpl['base_result'] );
        } 
    }    
}


$total = $twig->render('/component/include_component.twig', [
    'renderComponent' => [
        '/component/table/table_footer_row.twig' => [		
            'table_total' => get_table_total(['total_list' => $td_data['table_total_list'],  'data' => $base_result])  
        ]  
    ]
]);


echo json_encode([
    'table' => $table,
    'total' => $total
]);




// if($sort_data == 'name') {
//     $render_tpl = render_data_template([
//         'type' => $type,
//         'page' => $page,
//         'search' => [
//             'param' =>  ' AND stock_name =  :stock_name ',
//             'bindList' => array(
//                 'stock_name' =>"%{$search_value}%"
//             )
//         ]       
//     ]);
// }
// if($sort_data == 'provider') {
//     $render_tpl = render_data_template([
//         'type' => $type,
//         'page' => $page,
//         'search' => array(
//             'param' => " AND stock_provider = :stock_provider",
//             'bindList' => array(
//                 ':stock_provider' => $search_value
//             )
//         )
//     ]);     
// }

// echo $tpl->render([		
//     'table' => $render_tpl,
//     'table_tab' => $page,
//     'table_type' => $type
// ]);		
