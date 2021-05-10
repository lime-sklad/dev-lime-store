<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
get_product_root_dir();
//заголовок страницы
tab_page_header('Долги');

//загружаем шаблоны
ls_include_tpl();






?>



<div class="wrapper">
	<div class="debt_wrp">
		<div class="debt_nav">
			<div class="debt_option">
				<?php 
					/*Поиск*/ /* search_input($arr = array(
									'product_type' => 'debt',
									'product_category' => 'phone',
									'product_class' => ' drop_down_btn  choice_color',
									'parent_class' => 'ls-custom-select-wrapper ',
									'auto_complete' => 'hide',
									'label' => 'show',
									'label_title' => 'Выберите клиента',
									'clear_button' => 'hide' 
								)); */
					
				?>
				<ul class="ls-select-list">
					<div class="select-drop-down">
						<input type="button" id="" class="get_product_color drop_down_btn" value="seçin" default-value="seçin">
						<div class="reset_option">
							<input type="button" class="ls-reset-option ls-reset-option-style">
						</div>
					</div>
					<div class="ls-select-option-list" style="display: none;">
						<ul class="ls-select-list-option ls-custom-scrollbar">
							<?php  
								get_customer_list($arr = array(
									'default_search_value' => '',
									'custom_class' => 'get_customer',
									'search_field' => 'show',
									'icon' => 'show',
									'icon_name' => 'customer-auto.png' 
								)); 
							?>	
						</ul>
					</div>
				</ul>				

			</div>

			<div class="new_customer_wrapper">
				<div class="add_new_customer_block">
					<a href="javascript:void(0)" class="filter_wodjet_button_style new_customer_btn_css">
						<span class="mark mark--img"><img src="/img/icon/new_customer_gray.png"></span>
						<span class="mark mark--name filter_widjet_btn_title">Добавить клиента</span>
					</a>
				</div>		
			</div>			
		</div>
		<div class="table_wrapper">
			
			<?php 

			$tab_return_link = array(
				'tab_debt_history',
				'tab_debt_transaction' 
			);

			get_current_tab($tab_return_link, $tab_arr, 'tab_debt_history');
			?>
			<div class="terminal_main">
				
			</div> 
		</div>
	</div>	
</div>


<?php get_right_modal(); ?>