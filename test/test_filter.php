<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/function.php';

get_table_svervice_type();

//получем категорию товара
get_product_category();

//заголовок таблицы
get_table_header();		
?>

<!DOCTYPE html>
<html>
<head>
	<title>Lime Store</title>
	<link rel="icon" type="image/x-icon" href="/img/favicon.ico">	
	<link rel="stylesheet" type="text/css" href="/css/style.css?v=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="/css/print.min.css?v=<?php echo time(); ?>">

	<link rel="stylesheet" type="text/css" href="/css/fonts.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php require_once  $_SERVER['DOCUMENT_ROOT'].'/include/lib_include.php'; ?>	
</head>
<body>
<div class="main" id="main"></div>
<script type="text/javascript" defer="">
		
	const arr = [];
	var id = '1';
	var name = 'sadas';
	mydata = [
		id: { id},
		name: { name}
	];

	arr.push(mydata);

	console.log(arr['']['name']);


</script>

</body>