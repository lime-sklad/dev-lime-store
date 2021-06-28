<?php
	echo $twig->render('/component/inner_container.twig', [
		'renderComponent' => [
			'/component/cart/cart.twig' => []
		]
	]);
		
?>