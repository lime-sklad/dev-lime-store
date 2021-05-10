
<?= $a = '4';  if($a == '4') 'hello world' ; ?>

<?php 
exit();
require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
root_dir();
$link =  $_POST['link'];
echo $_SERVER['DOCUMENT_ROOT'].'/'.$link;
include $_SERVER['DOCUMENT_ROOT'].'/'.$link;