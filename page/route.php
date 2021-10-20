<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';

    define('root_dir', $_SERVER['DOCUMENT_ROOT']);

    $menu = page_tab_list();

    if(isset($_POST['data_page_route'])) {
        $page = $_POST['data_page_route'];
        if(array_key_exists($page, $menu)) {
            $this_menu = $menu[$page];
            include root_dir.'/page/base.php';
        }    
    }

    if(isset($_POST['tab'])) {
        if(isset($_POST['page'])) {
            $page = $_POST['page'];
        }
        $get_tab = $_POST['tab'];
        
        
        $tab = get_tab_data([$get_tab]);

        $tab_this = $tab[$get_tab];

        $type = $tab_this['type'];

        $link = $tab_this['tab_link'];

        include root_dir.$link;
    }



 