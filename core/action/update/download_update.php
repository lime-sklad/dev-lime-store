<?php 
	define('root_dir', $_SERVER['DOCUMENT_ROOT']);
    require_once root_dir.'/core/function/update.function.php';

    if(isset($_POST['get_modal'])) {
        echo json_encode([
            'success' => $twig->render('/component/modal/update/load_update_loader.twig')
        ]);
    }

    if(isset($_POST['download'])) {
        if(is_check_update()) {
            return ls_update();
        }
    }