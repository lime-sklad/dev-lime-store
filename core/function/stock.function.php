<?php 
/**
 * в этом файле описываем логику работы с товарами
 * и отчетами 
 */




 function get_last_added_stock() {
    return ls_db_request([
        'table_name' => 'stock_list',
        'col_list' => '*',
        'base_query' => ' WHERE stock_visible = 0 ',
        'param' => [
            'sort_by' => 'ORDER BY stock_id DESC LIMIT 1'
        ]
    ])[0];
 }