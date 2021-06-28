<?php
require_once '../../function.php';

header('Content-type: Application/json');


$id = $_POST['id'];
$get_prod = $dbpdo->prepare('SELECT * FROM stock_list WHERE stock_id = :id');
$get_prod->bindParam('id', $id);
$get_prod->execute();

function barcode_table_template($arr) {
$stock_id 				= $arr['stock_id'];
$stock_name 			= $arr['stock_name'];
$stock_second_price 	= $arr['stock_second_price'];
$stock_count 			= $arr['stock_count'];
$manat_image			= $arr['manat_image'];
?>

<tr class="anim_new_el checkout_product" id="<?php echo $stock_id; ?>">
  <td class="">
  	<?php echo $stock_id; ?>
  </td>
  <td class="">
  	<?php echo trim($stock_name); ?></a>
  </td>
  <td class="">
  	<a href="javascript:void(0)" class="stock_info_link_block "> 
  		<span class="stock_info_text"> <?php echo $stock_second_price; ?> </span>
  		<span class="mark"> <?php echo $manat_image; ?> </span>
  	</a>
  </td>	
  <td class="">
  	<input type="number" class="checkout_prod_count" value="1">
  </td>

</tr>

<?php 
}

if($get_prod->rowCount()>0) {

$row = $get_prod->fetch(PDO::FETCH_ASSOC);

$stock_id 				= $row['stock_id'];
$stock_name 			= $row['stock_name'];
$stock_second_price 	= $row['stock_second_price'];
$stock_count 			= $row['stock_count'];

	
$complete = array(
	'stock_id'  		  => $stock_id,	
	'stock_name' 		  => $stock_name, 		
	'stock_second_price'  => $stock_second_price, 
	'stock_count'         => $stock_count, 	 
	'manat_image' 		  => $manat_image 	 	 
);
ob_start();
barcode_table_template($complete);
$res = ob_get_clean();

$json_show = array(
	'param' => $complete,
	'table' => $res
); 

echo json_encode($json_show);

?>



<?php } ?>