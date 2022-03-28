<?php 
require $_SERVER['DOCUMENT_ROOT'].'/function.php';

$error = false;

$total = 0;

$stock_data = [];

// если корзина пустая, то выводим сообщение
if(!isset($_POST['cart'])) {
    return alert_error('Корзина пуста');
    die;
}

// получаем списко товаров в корзине
$cart_list = $_POST['cart'];
// получаем дату (день, месяц, год)
$full_date = get_date('fullDate');
// получаем день и месяц
$short_date = get_date('shortDate');

// перебираем массив товаров в корзине
foreach($cart_list as $row) {
    // проверям на пустые значение - id товара, цены, количества
    if(!isset($row['id'], $row['price'], $row['count']) || empty($row['id'] && $row['price'] && $row['count']) ) {
        // если пустые, то выводим сообщение
        $error = true;
        return alert_error('Заполните все поля!');
    }
    
    // id товара
    $id = (int) $row['id'];
    // цена товара
    $order_price = (float) $row['price'];
    // количество товара
    $order_count = (int) $row['count'];  
    // заметка продажи
    $description = $row['description'];
    
    // если ввели количество 0 или отрицательно значение, то выводим сообщение
    if($order_count <= 0) {
        return alert_error('Заполните поля правильно!');
    }

    // делаем запрос к базе и достаем товар
    // в запросе указано, если в заказе указано количесто больше чем есть в базе, то вывести пустой результат
    $stock = ls_db_request([
        'table_name' => 'stock_list',
        'col_list'   => "*",
        'base_query' => "",			
        'param' => [
            'query' => [
                'param' => "  WHERE stock_id = :id AND stock_count  >= :count ",
                'bindList' => array(
                    'id' => $id,
                    'count' => $order_count
                ),
                'joins' => "",
            ],
            'sort_by' 	 => "",
        ]
    ]);


    // $stock = ls_db_request(
    //     [
    //         'request' => [
    //             'param' => " WHERE stock_id = :id AND stock_count  >= :count ",
    //             'bindList' => [
    //                 'id' => $id,
    //                 'count' => $order_count
    //             ]
    //         ]
    //     ],[
    //         'table_name' => 'stock_list',
    //         'col_list'   => '*',
    //         'joins'      => '',
    //         'base_query' => '',
    //         'sort_by' 	 => ''	
    //     ]
    // );

    // ls_var_dump($stock);


     // в запросе указано, если в заказе указано количесто больше чем есть в базе, то вывести пустой результат
    if(empty($stock)) {
        return alert_error('no result');
    }

    // массив данных товара из базы
    $stock_row       = $stock[0];

    // себестоимость товара
    $first_price    = $stock_row['stock_first_price'];
    // в переменную заносим значение себестоимость товара умноженное на количество в заказе
    $total_profit   = $first_price * $order_count ;
    // в переменную заносим значение цена указанная в заказе на количество
    $order_sum      = $order_price * $order_count;
    // высчитываем прыбыль
    $profit         = $order_sum - $total_profit;

    // готовим данные для добавления в таблицу бд
    $stock_data[$id] = [
        'stock_id'                  => $id,
        'order_stock_name'          => $stock_row['stock_name'],
        'order_stock_imei'          => $stock_row['stock_phone_imei'],
        'order_who_buy'             => $description,
        'order_stock_count'         => $order_count,
        'order_stock_sprice'        => $order_price,
        'order_stock_total_price'   => $order_sum,
        'order_total_profit'        => $profit,
        'order_date'                => $full_date,
        'order_my_date'             => $short_date,
        'order_real_time'           => date('Y-m-d'),
        'order_seller_name'         => ''
    ];
}

if($error == false) {
    $dbpdo->beginTransaction();
    try {
        // добавляем заказ в базу
        ls_db_insert('stock_order_report', $stock_data);

        // полсе добавления товра в базу, обновляем данные товара, уменьшаем количестов товара
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

            ls_db_upadte($option, $data);
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
