<?php 

require_once 'function.php';

if(isset($_POST['get_print_id']))
{
	$get_print_id = trim($_POST['get_print_id']);

	$get_print_info = $dbpdo->prepare("SELECT * FROM stock_order_report WHERE stock_id =:get_print_id GROUP BY order_stock_id DESC");
	$get_print_info->bindParam('get_print_id', $get_print_id, PDO::PARAM_INT);
	$get_print_info->execute();

	$get_print_row = $get_print_info->fetch(PDO::FETCH_BOTH);

	?>

<div class="print_total_wrp" id="printJS-form">
	<div class="print_total_hd">
		<span><?php echo getUserName(); ?></span>
	</div>
	<div class="print_total_list_box">
			<div class="print_total print_total_who_sell">
				<span class="print_total_span">Satici: </span>
				<a><?php echo getUserName() ?></a>
			</div>
			<div class="print_total print_total_veon">
				<span class="print_total_span">Vöen: </span>
				<a>14212412412412</a>
			</div>	

<table>
	<thead>
		<tr>
			<th>Satış günü</th>
			<th>Malın adı</th>
			<th>IMEI</th>
			<th>Qiymət</th>
		</tr>
	</thead>
<tbody>
<tr>
	<td><?php echo $get_print_row['order_date']; ?></td>
	<td><?php echo $get_print_row['order_stock_name']; ?> </td>
	<td><?php echo $get_print_row['order_stock_imei']; ?></td>
	<td><?php echo $get_print_row['order_stock_sprice'].$manat_image ?></td>
</tr>
<tr>
  <td class="total_chec_form_primter_td" colspan="7" style="text-align:right">Cemi: <?php echo $get_print_row['order_stock_total_price'].$manat_image ?></td>
</tr>	
	</tbody>
</table>

	<div class="contact_input_form">
		<div class="print_input_form_box">
			<div class="who_sell_print">
				<span>Təhvil verdi </span>
				<input type="text" class="who_sell_print_input">
			</div>
			<div class="who_take_product_box">
				<span>Təhvil aldı </span>
				<input type="text" class="take_sell_print_inut">
			</div>
		</div>
	</div>


	</div>
</div>
		<div class="prin_total_btn print_total_btn_report">
			 <button type="button" class="delete_print_file" onclick="
			  printJS({
				  printable: 'printJS-form',
				  type: 'html',
				  css: 'css/print_main.css'
				 })
			 ">
			   Çap et
			 </button>
		</div>
		<div class="delte_total_pribt_list">
			<a href="javascript:void(0)" class="delete_report_print delete_print_file">Çeki silin</a>
		</div>		
	<?php

}