<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

// autocmplt-type
if(!isset($_POST['type'], $_POST['page'])) {
	echo 'error';
	exit();
}

$get_data 	  = [];
$search_value = ls_trim($_POST['search_item_value']); 
$type 		  = $_POST['type'];
$page 	      = $_POST['page'];
$get_sort_data    = ls_trim($_POST['sort_data']);


$th_list = get_th_list();

$sql_data = page_data($page);

$td_data = $sql_data['page_data_list'];

$base_result = [];
$res = [];
$table = '';

$sql_query_data = $sql_data['sql'];

$param 			= $sql_query_data['param'];
$bind_list 		= $sql_query_data['param']['query']['bindList'];
$table_name 	= $sql_query_data['table_name'];
$base_query 	= $sql_query_data['base_query'];
$sort_by 		= $sql_query_data['param']['sort_by'];
$joins 			= $sql_query_data['param']['query']['joins'];

$page_data_row = $td_data['get_data'];

foreach($page_data_row as $key => $col_name_prefix) {
	$th_this = $th_list[$key];
    
	$data_sort = $th_this['data_sort'];
	
	if($data_sort == $get_sort_data) {
        if(!empty($search_value)) {
            $bind_list['search'] = "%{$search_value}%";
            $search_array = [
                'table_name' => 'user_control',
                'col_list'   => " * ",
                'base_query' => $base_query,			
                'param' => [
                    'query' => [
                        'param' => $param['query']['param'],
                        'joins' => $joins . " WHERE $col_name_prefix LIKE :search ",
                        'bindList' => $bind_list
                    ],
                    'sort_by' 	 => $sort_by,
                ]
            ];      

            $render_tpl = render_data_template($search_array, $sql_data['page_data_list']);

        } else {
            $render_tpl = render_data_template($data_page['sql'], $td_data);    
        }

        $table .= $twig->render('/component/include_component.twig', [
            'renderComponent' => [
                '/component/table/table_row.twig' => [
                    'table' => $render_tpl['result'],
                    'table_tab' => $page,
                    'table_type' => $type       
                ]
            ]
        ]);
        
        if(!empty($render_tpl['base_result'])) {                
            $base_result = array_merge($base_result, $render_tpl['base_result'] );
        } 

	}
}


$total = $twig->render('/component/include_component.twig', [
    'renderComponent' => [
        '/component/table/table_footer_row.twig' => [		
            'table_total' => table_footer_result($td_data['table_total_list'], $base_result)  
        ]  
    ]
]);

echo json_encode([
    'table' => $table,
    'total' => $total
]);