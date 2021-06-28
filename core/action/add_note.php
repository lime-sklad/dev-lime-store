<?php
require_once '../../function.php';

header('Content-type: Application/json');

//note+reminder

if( isset($_POST['note_name'])
AND isset($_POST['note_descrpt'])
AND isset($_POST['note_type']) ) {
get_table_svervice_type();

		if(empty($_POST['note_name'])) {
				$success_msg = [ 
				 'error'	=> 'Qyed sahəsini doldurun',
				];								
				//выводим сообщение и останавливаем выполнение заказа
				echo json_encode($success_msg);
				exit();
				echo "asdsa";
		}
		if(empty($_POST['note_descrpt'])) {
				$success_msg = [ 
				 'error'	=> 'Tesvir sahəsini doldurun',
				];								
				//выводим сообщение и останавливаем выполнение заказа
				echo json_encode($success_msg);
				exit();
		}	


		if(!empty($_POST['note_name'] && $_POST['note_name'] && $_POST['note_name'])) {
			$note_name 		= trim($_POST['note_name']);
			$note_descrpt 	= trim($_POST['note_descrpt']);
			$note_type 		= trim($_POST['note_type']);

			if($note_type === $note) {
				$inser_new_order = $dbpdo->prepare("INSERT INTO no_availible_order
					(order_stock_id, 
					order_stock_name, 
					order_stock_description, 
					order_stock_date, 
					order_stock_full_date,
					note_type)
					VALUES
					(NULL,?,?,?,?, 'order_note') 
					");

				$inser_new_order->execute([
					$note_name,
					$note_descrpt,
					$order_myear,
					$ordertoday]);


				$new_order_list = [];
				$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
					WHERE order_stock_visible = 0 
					GROUP BY order_stock_id DESC");
				$order_stock_view->execute();
				$order_stock_row = $order_stock_view->fetch();

				$note_id 			= $order_stock_row['order_stock_id'];
				$note_date 			= $order_stock_row['order_stock_full_date'];
				$note_name 			= $order_stock_row['order_stock_name'];
				$note_descrpt 		= $order_stock_row['order_stock_description'];

				$get_note = array(
					'note_id' 		=> $note_id,
					'note_date' 	=> $note_date, 			
					'note_name' 	=> $note_name, 			
					'note_descrpt'  => $note_descrpt 	 	
				);

				$lets_res = get_note_list($get_note);

				$success_msg = [ 
				'success'	 		=> 'ok',
				 'table'		    => $lets_res
				];								
				//выводим сообщение и останавливаем выполнение заказа
				echo json_encode($success_msg);
				exit();

			}			
		} 
	
}