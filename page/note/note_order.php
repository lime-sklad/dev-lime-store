<?php 
	require_once '../../function.php';

	ls_include_tpl();
	//получаем тип таблицы
	get_table_svervice_type();

	//получем категорию товара
	get_product_category();
	
	//получаем заголовки таблицы
	get_table_header();

?>

<div class="view_stock_wrapper">
	<div class="view_stock_box_wrp">
		<section class="note_wrapper_section">
			<div class="note_wrapper"> 
				<div class="note_add_wrp">
					<div class="note_add_form">
						<ul class="add_stock_box_form">
							<li class="add_stock_form_list">
								<span class="add_stock_description">Qeyd:</span>
								<input type="text" class="add_stock_input add_note_name_action">
							</li>
							<li class="add_stock_form_list note_custom_list">
								<span class="add_stock_description">Təsvir:</span>
								<textarea class="add_stock_input add_note_descript"></textarea>
							</li>
							<li class="add_stock_form_list submit_list">
								<input type="hidden" class="note_action_type" data-type="<?php echo $note; ?>">
								<a href="javascript:void(0)" class="btn add_note_submit flex-cntr">Saxla</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="search_filter_wrapper">
					<div class="flex f-jc-end f-ai-cntr width-100">
						<?php 
						filt_report_date($note_category, $dbpdo, $order_myear, 'note'); 
						search_input($arr = array(
							'product_type' => $note,
							'product_category' => $note_category,
							'product_class' => '',
							'parent_class' => '',
							'auto_complete' => 'hide',
							'label' => 'hide',
							'label_title' => '',
							'clear_button' => 'show'

						)); 
						?>	
					</div>
				</div>
				
				<div class="note_table_list ls-custom-scrollbar">
					<table>
						<thead class="stock_table">
							<tr>
								<?php 
									echo $table_header['th_serial'];
									echo $table_header['th_date']; 
									echo $table_header['th_note_cont']; 
									echo $table_header['th_rasxod_decsription']; 
								?>
							</tr>
						</thead>
						<tbody class="note_order_list stock_list_tbody" data-stock-page="<?php echo $note; ?>" 
							data-stock-type="<?php echo $note_category; ?>">
							<?php 

								$new_order_list = [];
								$order_stock_view = $dbpdo->prepare("SELECT * FROM no_availible_order 
									WHERE order_stock_visible = 0 
									AND note_type = :note_type
									AND order_stock_date = :cur_date
									GROUP BY order_stock_id DESC");
								$order_stock_view->bindValue('note_type', $note_category);
								$order_stock_view->bindValue('cur_date', $order_myear);
								$order_stock_view->execute();

								if($order_stock_view->rowCount() > 0) {
									while ($order_stock_row = $order_stock_view->fetch(PDO::FETCH_BOTH))
										$new_order_list[] = $order_stock_row;
									foreach ($new_order_list as $order_stock_row)
									{	
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

										echo get_note_list($get_note);
									}
								}

							?> 							
						</tbody>
					</table>					
				</div>

			</div>
		</section>
	</div>
</div>

