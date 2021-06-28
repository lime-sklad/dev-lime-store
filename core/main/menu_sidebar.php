<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/function.php');
	get_product_root_dir();

?>




<div class="sidebar_menu_block">
	<div class="sider_bar_menu_header">
		<span class="module_order_desrioption">Menu</span>
	</div>
	<div class="sider_bar_list_wrp ls-custom-scrollbar">
		<ul class="sider_bar_list">
			<li class="menu_list menu_header_list">
				<a href="javascript:void(0)" class="user_profile_select select_page_main scroll_top" data-link="/page/admin/admin.php">
					<div class="user_profile_img" style="display: none;"><img src="/img/icon/profile.png"></div>
					<span><?= getUser('get_name'); ?></span>
				</a>
			</li>		
<?php
	//tab link for main page
	get_tab_main_page();
	$menu_count = count($menu_query);
	for ($row = 0; $row < $menu_count; $row++) {
		$title = $menu_query[$row]['title'];
		$img = $menu_query[$row]['img_small'];
		$link = $menu_query[$row]['link'];
		//some one
		?>
			<li class="menu_list">
				<a href="javascript:void(0)" class="sidebar_link select_page_main scroll_top" data-link="<?php echo $link ?>">
					<div class="sidebar_menu_image">
						<img src="<?php echo $img; ?>">
					</div>					
					<span class="sidebar_menu_link_nmae"><?php echo $title; ?></span>
				</a>
			</li>
	<?php				
	} ?>	

			<li class="menu_list_bottom">
				<a href="/core/action/logout?logout" class="sidebar_link">
					<div class="sidebar_menu_image">
						<img src="/img/icon/sidebar/logout_white.png">
					</div>					
					<span class="sidebar_menu_link_nmae">Çıxış</span>
				</a>
			</li>																
		</ul>
	</div>
</div>