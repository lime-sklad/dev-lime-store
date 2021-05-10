<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
//заголовок таблицы
get_table_header();	

	
$product_category = $_POST['prod_cat'];	

?>

<div class="report-button-advance">
	<!-- START плагин конверации в excel -->
	<div class="row">
		<div class="conver_excel_pulgin_div flex-cntr">
			<?php require_once  $_SERVER['DOCUMENT_ROOT'].'/core/pulgin/convert_excel.php'; ?>
		</div>
	</div>
	<!-- END плагин конверации в excel -->	

	<!-- START добавть сумму без товара в отчет -->
	<div class="row">
		<div class="add_profit">
			<div class="add_profit_btn">
				<a href="javascript:void(0)" class="add_prfit_style add_prfit_action btn">Xidmət+</a>
			</div>
			<div class="modal add_profit_modal">

			<div class="close_modal_btn close_modal"><img src="/img/icon/cancel-white.png"></div>
				<div class="add_proft_modal_form_wrp">
					<div class="add_profit_modal_list">
						<span class="module_order_desrioption">Xidmətin adı</span>
						<input type="text" class="order_input profit_name profit_name_style">
					</div>
					<div class="add_profit_modal_list">
						<span class="module_order_desrioption">Xeyir</span>
						<input type="text" class="order_input profit_money profit_money_style">
					</div>
					<div class="add_profit_modal_list">
						<a href="javascript:void(0)" class="add_profit_action btn add_profit_style">Əlavə edin</a>
					</div>										
				</div>
			</div>
		</div>
	</div>
	<!-- END добавть сумму без товара в отчет -->


</div>