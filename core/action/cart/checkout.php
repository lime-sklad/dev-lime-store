<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

// ls_var_dump($_POST);
$error = false;
$total = 0;
$stock_data = [];
if(!isset($_POST['cart'])) {
    return alert_error('Корзина пуста');
}

$cart_list = $_POST['cart'];
$full_date = get_date('fullDate');
$short_date = get_date('shortDate');
$c = [];

foreach($cart_list as $row) {
    if(!isset($row['id'], $row['price'], $row['count']) || empty($row['id'] && $row['price'] && $row['count']) ) {
        $error = true;
        return alert_error('Заполните все поля!');
    }
    
    $id = (int) $row['id'];
    $order_price = (int) $row['price'];
    $order_count = (int) $row['count'];  
    // ls_var_dump($order_count);
    if($order_count <= 0) {
        return alert_error('Заполните поля правильно!');
    }
    $stock = ls_db_request(
        [
            'request' => [
                'param' => " WHERE stock_id = :id AND stock_count  >= :count ",
                'bindList' => [
                    'id' => $id,
                    'count' => $order_count
                ]
            ]
        ],[
            'table_name' => 'stock_list',
            'base_query' => 'SELECT * FROM stock_list ',
            'sort_by' 	 => '   '	
        ]
    );
    if(empty($stock)) {
        return alert_error('no result');
    }
    $stock_row = $stock[0];
    $first_price = $stock_row['stock_first_price'];
    $total_profit = $first_price * $order_count ;
    $order_sum = $order_price * $order_count;
    $profit = $order_sum - $total_profit;
    $stock_data[$id] = [
        'stock_id' => $id,
        'order_stock_count' => $order_count,
        'order_stock_sprice' => $order_price,
        'order_stock_total_price' => $order_sum,
        'order_total_profit' => $profit,
        'order_date' => $full_date,
        'order_my_date' => $short_date,
        'order_real_time' => date('Y-m-d')
    ];
}

if($error == false) {
    $dbpdo->beginTransaction();
    try {
        // ls_db_insert('stock_order_report', $stock_data);

        foreach($stock_data as $index => $data) {
            $option = [
                'before' => " UPDATE stock_list SET ",
                'after' => " WHERE stock_id = :stock_id",
                'post_list' => [
                    'stock_id' => [
                        'query' => false,
                        'bind' => 'stock_id'
                    ],
                    'order_stock_count' => [
                        'query' => "stock_list.stock_count = stock_list.stock_count - :product_count",
                        'bind' => 'product_count'
                    ]
                ]
            ];
            // ls_db_upadte($option, $data);
        }
        $dbpdo->commit();
        
        echo json_encode([
            'notice' => [
                'type' => 'success',
                'text' => 'OK!'
            ]
        ]);
    
    } catch (\Throwable $e) {
        $dbpdo->rollback();
        throw $e;
        return alert_error("Детали ( '. $e . ' ) ");
    }
    
    if($error) {
        return alert_error("Неверный запрос \n Обновите страницу и попробуйте еще раз! ");
    }
} 
