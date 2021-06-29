<?php 
    require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

    // header('Content-type: application/json');

if(isset($_POST['items'])) {

    $myPost = $_POST['items'];

    // ls_var_dump($myPost);
   echo $twig->render('/component/cart/cart-item.twig',  ['items' => $myPost]);

}