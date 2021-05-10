<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
?>
<table id="example" style="display: none;">
	<thead>
		<tr>
			<?php 
				echo  $table_header['th_sale_serial'];
				echo  $table_header['th_day_sale'];
				echo  $table_header['th_prod_name'];
				echo  $table_header['th_imei'];
				echo  $table_header['th_sale_price'];
				echo  $table_header['th_provider'];
				echo  $table_header['th_report_note'];
				echo  $table_header['th_count'];				
				echo  $table_header['th_profit'];
			?>
		</tr>
	</thead>
	<tbody>	

		<?php 

			$report_list = [];
			$report_stmt = $dbpdo->prepare("SELECT *
				FROM rasxod
				INNER JOIN stock_order_report 
				ON stock_order_report.stock_order_visible = 0
				AND stock_order_report.stock_type = :product_category
				AND stock_order_report.order_stock_count > 0

				LEFT JOIN stock_list ON stock_list.stock_id = stock_order_report.stock_id
				GROUP BY stock_order_report.order_stock_id DESC
				ORDER BY stock_order_report.order_real_time ASC
				");
			$report_stmt->bindParam('product_category', $product_category, PDO::PARAM_INT);					
			$report_stmt->execute();

			   if($report_stmt->rowCount() > 0){
					while ($report_row = $report_stmt->fetch(PDO::FETCH_BOTH))
						$report_list[] = $report_row;
						foreach ($report_list as $report_row)
						{
							$stock_id 			= $report_row['order_stock_id'];
							$order_date 		= $report_row['order_date'];
							$stock_name 		= $report_row['order_stock_name'];
							$stock_imei 		= $report_row['order_stock_imei'];
							$stock_sprice 		= $report_row['order_stock_sprice'];
							$stock_provider 	= $report_row['stock_provider'];
							$stock_count 		= $report_row['order_stock_count'];
							$order_who_buy 		= $report_row['order_who_buy'];
							$stock_profit 		= $report_row['order_total_profit'];

							$get_product_table = array(
								'stock_id'			=> $stock_id, 		
								'order_date'		=> $order_date, 	
								'stock_name'		=> $stock_name, 	
								'stock_imei'		=> $stock_imei, 	
								'stock_sprice'		=> $stock_sprice, 	
								'stock_provider'	=> $stock_provider, 
								'stock_count'		=> $stock_count, 	
								'order_who_buy'		=> $order_who_buy, 	
								'stock_profit'		=> $stock_profit,
								'manat_image'		=> $manat_image 	
							 );

							get_report_phone_tamplate($get_product_table);
						}
					
			    }
			    
		?>

	</tbody>
</table>


<script type="text/javascript">
	$(document).ready(function() {
	    $('#example').DataTable( {
	        dom: 'Bfrtip',
	        buttons: [
	            'excelHtml5',
	        ], 
    		searching: false,
    		paging: false,
    		info: false,
	    } );

	    $('.buttons-excel').text('Convert Excel').addClass('btn excel_convert_btn');
	});
</script>
