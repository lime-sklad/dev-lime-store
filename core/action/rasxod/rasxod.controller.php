<?php 

function add_new_rasxod($post_data, $dbpdo) {
    $data = [];
    $arr = [];
    $col_post_list = [
        'add_rasxod_description' => [
            'col_name' => 'rasxod_description',
            'required' => false
        ],
		'add_rasxod_sum' => [
			'col_name' => 'rasxod_money',
			'required'  => true,
		]
    ];
    

	foreach ($col_post_list as $key => $value) {
		if(array_key_exists($key, $post_data)) {
			$data = array_merge($data, [
				$value['col_name'] => $post_data[$key]
			]);
		}
	}

	$default_data = [
		'rasxod_visible' => 'visible',
		'rasxod_day_date' => date('d.m.Y'),
		'rasxod_year_date' => date('m.Y')
	];

	$data = array_merge($data, $default_data);
    
    ls_db_insert('rasxod', [$data]);

    return true;
}