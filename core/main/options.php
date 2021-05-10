<?php
	define('GET_ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

	require_once(GET_ROOT_DIR.'/function.php');

?>

<div class="main-option-service">
	<div class="options-list">
	<?php
		$menu = get_tab_main_page_test();

		foreach($menu as $row => $value) {
			$title = $value['title'];
			$background_color = $value['background-color'];
			$page_tab = $row;
			$icon = $value['img_big'];
			$page_link = $value['link'];
			

			$default_tab = array_search('active', $value['tab']);


			// $tab_url = get_tab_data($tab_arr);

			

?>


			<div class="option-list-box flex-cntr" style="background: <?php echo $background_color; ?>">
				<div class="stock-view-link">
					<a href="javascript:void(0)" class="select_page_main select_page_btn_style flex-cntr scroll_top" 
					data-page="<?php echo $page_tab  ?>" 
					data-tab="<?php echo $default_tab; ?>"
					data-url="<?php echo $page_link; ?>">
						<span class="stock_view_header"> <?php echo $title; ?> </span>
						<div class="stock_view_icon">
							<img src="<?php echo $icon; ?>">
						</div>
					</a>				
				</div>
			</div>

<?php

		}
	?>		
	</div>
</div>			


