<?php
    /**
     * Тут загружаем вкладки и заголовк страницы 
     */

    $menu_list = $this_menu['tab']['list'];
    $active = $this_menu['tab']['active'];
    $title = $this_menu['title'];

    $tab_list = get_tab_data($menu_list, $active);

    // ls_var_dump($this_menu);

    echo $twig->render('/component/include_component.twig', [
        'renderComponent' => [
            '/component/widget/title.twig' => [
                'title' => $title
            ],
            '/component/widget/nav.twig' => [
                'tab_list' => $tab_list,
                'route_index' => $page
            ]
        ]
    ]);
