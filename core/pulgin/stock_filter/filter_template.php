<?php 
require_once  $_SERVER['DOCUMENT_ROOT'].'/function.php';
ls_include_tpl();


//верстка
?>
<div class="filter_buttons_wrapper">

	<div class="filter_block_open_wrp">
		<a href="javascript:void(0)" class="filter_wodjet_button_style open_filter_widjet">
			<span class="mark mark--img"><img src="/img/icon/filter-icon.png"></span>
			<span class="mark mark--name filter_widjet_btn_title">Filter</span>
			<span class="mark filter-count"></span>
		</a>
	</div>

	<div class="filter_content">
		<ul class="filter_button_dropdown_serch_list">
			<div class="row">
				<?php order_filter_list_tpl('terminal-filter', '', ''); ?>
			</div>	
		</ul>		
	</div>

</div>