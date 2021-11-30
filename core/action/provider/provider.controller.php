<?php 

function add_new_provider($post_data, $dbpdo) {
    $data = [];
    $arr = [];
    $col_post_list = [
        'add_provider_name' => [
            'col_name' => 'provider_name',
            'required' => true
        ],
    ];
    

	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $post_data)) {
			$data = array_merge($data, [
				$value['col_name'] => $post_data[$key]
			]);
		}
	}

	$default_data = [
		'visible' => 'visible',
	];

	$data = array_merge($data, $default_data);
    
    ls_db_insert('stock_provider', [$data]);

    return true;
}