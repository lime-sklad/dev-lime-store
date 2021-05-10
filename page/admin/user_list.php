<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';


	//получаем тип таблицы
	get_table_svervice_type();

	//получем категорию товара
	get_product_category();

	//заголовок таблицы
	get_table_header();	

	ls_include_tpl();

	get_tab_main_page();
?>


<div class="view_stock_wrapper">
	<div class="view_stock_box_wrp">

			<!-- начало формы добавления пользователя в базу -->
			<div class="add_stock_sell_wrp flex-cntr">
				<div class="new_stock_box">

					<ul class="add_stock_box_form">
						<li class="add_stock_form_list new_stock_box_header_description">
							<span>İSTIFADƏÇI əlavə edin</span>
						</li>
						<div class="row">
							<div class="col-w50">
								<li class="add_stock_form_list">
									<div class="input_wrapper">
										<span class="add_stock_description">Ad</span>
										<input type="text" autocomplete="off" class="add_stock_input  add_user_name" data-name="name">
									</div>
								</li>
								<li class="add_stock_form_list">
									<span class="add_stock_description">
										Parol
										<span class="mark text-red text-bold" style="font-size: 13px;"> * (Parol 2 simvoldan çox olmalıdır)</span>
									</span>
									<input type="text" autocomplete="off" class="add_stock_input  add_user_password">
								</li>
								<!-- user role start  Vəzifə -->
								<li class="add_stock_form_list">
									<div class="radio-wrapper flex-basis-100" ls-radio-initial="user_role">
										<div class="radio-title">Vəzifə</div>
										<div class="radio-list">
											<a href="javascript:void(0)" class="radio-button" ls-radio-for="user_role" ls-radio-value="admin">
												<span class="radio-state-mark"></span>
												<span class="radio-icon-mark hide"></span>
												<span class="radio-value">Administrator</span>
											</a>
											<a href="javascript:void(0)" class="radio-button" ls-radio-for="user_role" ls-radio-value="admin_seller">
												<span class="radio-state-mark"></span>
												<span class="radio-icon-mark hide"></span>
												<span class="radio-value">Baş satıcı</span>
											</a>
											<a href="javascript:void(0)" class="radio-button" ls-radio-for="user_role" ls-radio-value="seller">
												<span class="radio-state-mark"></span>
												<span class="radio-icon-mark hide"></span>
												<span class="radio-value">Satıcı</span>
											</a>																						
										</div>
									</div>							
								</li>
								<!-- user role end -->
							</div>



							<div class="col-w50">
								<div class="row">

									<div class="add_stock_form_list">
										<span class="add_stock_description">Istifadəçi giriş hüquqları</span>
										<ul class="filter-custom-section-list">
											<li class="filter-check-header">
												<span class="filter-title">Səhifəyə giriş hüququ</span>
											</li>
											<li>	
												<ul class="filter-check-list ls-custom-scrollbar user_rights_parent" data-access-type="ACCESS_PAGE">
													<?php
														foreach ($menu_query as $row) {
															$title = $row['title'];
															$link = $row['link'];
															$menu_compare = array(
																'title' 		=>  $title,
																'value'			=>  $link,
																'icon'			=> '<img src="/img/icon/lock-white.png">',
																'id'			=> '',
																'text'			=> '',
																'modify_class' 	=> '',
																'parent_class' 	=> ''
															);

															get_access_page_list_tpl($menu_compare);
														}

													?>				
												</ul>
											</li>								
										</ul>
										<ul class="filter-custom-section-list">
											<li class="filter-check-header">
												<span class="filter-title">Məlumat hüququ</span>
											</li>
											<li>	
												<ul class="filter-check-list ls-custom-scrollbar user_rights_parent" data-access-type="ACCESS_DATA">
													<?php
														$access_data_param = page_data_access_list();	
														foreach ($access_data_param as $par) {
															$get_th = $dbpdo->prepare('SELECT * FROM th_list WHERE th_description = :param');
															$get_th->bindParam('param', $par);
															$get_th->execute();
															$th_row = $get_th->fetch();
															
															if($get_th->rowCount()>0) {
																$title = $th_row['th_name'];
																$value = $th_row['th_description'];
																$id    = $th_row['th_id'];
																
																$menu_compare = array(
																	'title' 		=>  $title,
																	'value'			=>  $id,
																	'id'			=> 	$id,
																	'icon'			=> '<img src="/img/icon/lock-white.png">',
																	'text'			=>	'',
																	'modify_class' 	=> '',
																	'parent_class' 	=> ''
																);

																get_access_page_list_tpl($menu_compare);															
															}
														}
 
															
																													
													?>				
												</ul>
											</li>								
										</ul>																				
									</div>	
								</div>
							</div>
							<li class="add_stock_form_list submit_list">
								<a href="javascript:void(0)" class="btn add_stock_style add_user">Yüklə</a>
							</li>													
						</div>
					</ul>
				</div>	
			</div>
		</div>		
			<!-- конец формы добавления пользователя в базу -->

		<div class="search_filter_wrapper">
			<div class="search_filter_block">
				<?php //here table navigation ?>
			</div>
		</div>	

		<div class="stock_view_wrapper">
			<div class="stock_view_list ls-custom-scrollbar">
				<table class="stock_table">
					<thead>
						<tr>
							<th class="th_w40">ID</th>
							<th class="th_w250">Ad</th>
							<th class="th_w120">Parol</th>
							<th class="th_w120">Qeydiyyat tarixi</th>
							<th class="th_w120">Показать детальней</th>
							<th class="th_w120">Redaktə etmək</th>
						</tr>
					</thead>
					<tbody class="stock_list_tbody">
						<?php 
							$get_all_user = $dbpdo->prepare('SELECT * FROM user_control WHERE user_visible = 0 ORDER BY user_id DESC');
							$get_all_user->execute();

							while ($row = $get_all_user->fetch(PDO::FETCH_LAZY)) {
								$user_name 		= $row->user_name;
								$user_id 		= $row->user_id;
								$user_password 	= $row->user_password;
								$user_reg_date 	= $row->alert_date;

								$compare = array(
									'u_id'	=> $user_id,
									'u_name' => $user_name,
									'u_password' => $user_password,
									'u_reg_date' => $user_reg_date
								);
								//вызываем шаблон таблицы
								admin_user_list_tr_tpl($compare);

							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>	