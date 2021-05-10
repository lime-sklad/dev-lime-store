<!DOCTYPE html>
<html>
<head>
	<?php 
		include $_SERVER['DOCUMENT_ROOT'].'/function.php';

		require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';


		echo $twig->render('/component/include_component.twig', [
			'renderComponent' => [
				'/component/index/head.twig' => [
					'lib_list' => lib_include_list(),
					'v' => time() 
				]
			]
		]); 

		?>
</head>
<style type="text/css">
	.des-row {
	    display: flex;
	    padding: 25px;
	}

	.des-header {
	    width: 100%;
	    margin-bottom: 15px;
	    line-height: 30px;
	    font-size: 30px;
	}

	.design-content {
	    padding: 15px;
	    border: 1px solid #f3f3f3;
	    margin-right: 11px;
	}	
</style>
<body>


<section class="ui-section">
	<div class="ui-header">drop down list</div>
	<div class="ui-content">
	
		<div class="main main__active">
			<div class="area-container">
				<div class="area-button widget__button">Open</div>

				<div class="widget__content area-content select-list">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quaerat nesciunt dolores voluptatibus maiores commodi blanditiis debitis maxime itaque deleniti magni dolorum numquam nam vel doloribus odit, suscipit, culpa, qui aperiam?
				Animi a maxime aperiam eius! Minus quae, inventore a vel modi dolorem sequi reiciendis nihil repellat deserunt neque voluptatem enim recusandae fuga labore officia harum aliquam velit tenetur. Cum, alias.
				Ex ipsa nesciunt cum officiis aspernatur, atque voluptatibus! Et harum voluptates doloremque omnis, consequatur maxime error commodi eligendi fuga ducimus rem repellendus odit expedita dolorem, provident cupiditate? Quo, atque ex!
				In asperiores accusantium velit iste. Eveniet, corrupti deserunt officia rem sint libero quisquam aliquam ad repellat, ratione soluta cum pariatur! Labore cupiditate illum voluptate ad id porro accusantium saepe rerum.
				Sapiente molestias itaque quae a, numquam provident. Ea minima architecto accusamus laudantium qui! Exercitationem aperiam earum explicabo quasi distinctio quibusdam odit adipisci placeat magni? Excepturi quod fugiat adipisci corporis laudantium.
				Sed consequatur omnis laudantium soluta sapiente iusto alias vel voluptate vero, est voluptates, ipsa facilis quae. A inventore quisquam, ab minus sequi nemo. Dicta vitae iusto labore doloremque soluta eius!
				Repellendus vitae adipisci in, neque tempore minus est ducimus qui esse! Necessitatibus labore voluptate modi voluptatibus excepturi expedita. Recusandae aliquam cumque vel quaerat praesentium illum, eligendi adipisci doloremque blanditiis impedit?
				Nam esse beatae quam sunt, nostrum vero harum, asperiores neque repellendus cum officiis mollitia ipsam voluptatibus laboriosam laudantium consequuntur corrupti suscipit ducimus. Magni quae dignissimos architecto eveniet aliquam nostrum velit!
				Cumque necessitatibus in voluptatibus repudiandae placeat quaerat veritatis a voluptate molestiae cupiditate corporis autem excepturi odit, hic ratione officiis sit sunt ipsum. Quo necessitatibus dolor omnis modi facere magnam? Consectetur.
				Laudantium aut natus accusamus excepturi modi, obcaecati exercitationem dolor et illo temporibus tenetur quae tempora quis totam eius aliquid ab mollitia optio nesciunt magni voluptatibus facere facilis reprehenderit. Incidunt, dolorem.</div>
			</div>
		</div>
	
	</div>
</section>













	<div class="row des-row">
		<div class="des-header">check-box-list</div>
		<div class="design-content">
			<!-- фильтыр с чекбок с прокруткой -->
			<ul class="filter-custom-section-list">
				<li class="filter-check-header">
					<span class="filter-title">Reng</span>
				</li>

				<ul class="filter-check-list ls-custom-scrollbar" type="color">
					<li class="filter-check-list-style">
						<a href="javascript:void(0)" class="filter-check-style filter-check" id="1" filter-type="color" filter-val="Green">
							<span class="mark mark-filter-checked-icon"><img src="/img/icon/check-white.png"></span>
							<span class="mark filter-name">Green</span>
							<span class="mark filter-mark-text"></span>
						</a>	
					</li>
					<li class="filter-check-list-style">
						<a href="javascript:void(0)" class="filter-check-style filter-check" id="2" filter-type="color" filter-val="Red">
							<span class="mark mark-filter-checked-icon"><img src="/img/icon/check-white.png"></span>
							<span class="mark filter-name">Red</span>
							<span class="mark filter-mark-text"></span>
						</a>	
					</li>					
				</ul>							
			</ul>
		</div>


		<div class="row">
			<div class="des-header">Готовая</div>
			<div class="design-content">
				<div class="flilter_wrapper">
					<div class="filter-custom-section-block ls-custom-scrollbar">
						<div class="filter_wrapper_title">Filter</div> 
						<div class="filter-custom-list-wrp">
							<ul class="filter-custom-section-list">
								<li class="filter-check-header">
									<span class="filter-title">Reng</span>
								</li>

								<ul class="filter-check-list ls-custom-scrollbar" type="color">
									<?php //ger_filter_param('color', ''); ?>
								</ul>							
							</ul>
																
						</div>
					</div>
				</div>				
			</div>
		</div>

<!-- 
	для того что бы создать автозаполняющийся инпут нужно блоку родителю в котором находитьсяинпут задать класс .auto-cmlpt-parent
	самому инпуту задать класс auto-cmplt-input и атрибут data-name="prod_name/prod_category"
	блок со списокм расположить внутри .auto-cmplt-parent после инпута и задать касс блоку .auto-cmplt-select

	в выподающем окне варианту даем класс .auto-cmplt-list
 -->
		<div class="row">

			<div class="des-header">Autocompelete</div>
			<div class="design-content auto-cmplt-parent ls-relative">
				<div class="asd">
					<input type="text" class="ls-input add_stock_input auto-cmplt-input">
				</div>

				<div class="auto-cmplt-select auto-compelete-list-style">
					<ul class="ls-select-list-option ls-custom-scrollbar">
						<li class="ls-select-li">
							<a href="javascript:void(0)" class="choice-style auto-cmplt-list" id="" value="Blue">
								<span class="mark filter-name">Blue</span>
								<span class="mark filter-mark-text"> </span>
							</a>
						</li>	

						<li class="ls-select-li">
							<a href="javascript:void(0)" class="choice-style auto-cmplt-list" id="" value="Black">
								<span class="mark filter-name">Black</span>
								<span class="mark filter-mark-text"> </span>
							</a>
						</li>					
					</ul>					
				</div>
			</div>

		</div>


		<div class="row">

			<div class="des-header">Autocompelete 2</div>
			<div class="design-content auto-cmplt-parent ls-relative">
				<div class="asd">
					<input type="text" class="ls-input add_stock_input auto-cmplt-input">
				</div>

				<div class="auto-cmplt-select auto-compelete-list-style">
					<ul class="ls-select-list-option ls-custom-scrollbar">
						<li class="ls-select-li">
							<a href="javascript:void(0)" class="choice-style auto-cmplt-list" id="" value="Joer">
								<span class="mark filter-name">Joer</span>
								<span class="mark filter-mark-text"> </span>
							</a>
						</li>	

						<li class="ls-select-li">
							<a href="javascript:void(0)" class="choice-style auto-cmplt-list" id="" value="Fil">
								<span class="mark filter-name">Fil</span>
								<span class="mark filter-mark-text"> </span>
							</a>
						</li>					
					</ul>					
				</div>
			</div>

		</div>
<!-- radio -->
		<div class="row">
			<div class="des-header">Radio Button</div>
			<div class="desing-content">
					
				<div class="radio-wrapper" ls-radio-initial="admin_role">
					<div class="radio-title">Должность</div>
					<div class="radio-list">
						<a href="javascript:void(0)" class="radio-button" ls-radio-for="admin_role" ls-radio-value="val">
							<span class="radio-state-mark"></span>
							<span class="radio-icon-mark hide"></span>
							<span class="radio-value">Ivan</span>
						</a>
						<a href="javascript:void(0)" class="radio-button" ls-radio-for="admin_role" ls-radio-value="val">
							<span class="radio-state-mark"></span>
							<span class="radio-icon-mark hide"></span>
							<span class="radio-value">Petrov</span>
						</a>
						<a href="javascript:void(0)" class="radio-button" ls-radio-for="admin_role" ls-radio-value="val">
							<span class="radio-state-mark"></span>
							<span class="radio-icon-mark hide"></span>
							<span class="radio-value">Vali</span>
						</a>																	
					</div>
				</div>

			</div>
		</div>
			<script type="text/javascript">
				$(document).ready(function(){
					//открывекм выпадающий списко еси инпут активный
					$('body').on('focusin', '.auto-cmplt-input',function(){
						$(this).closest('.auto-cmplt-parent')
						.find('.auto-cmplt-select').fadeIn();
						console.log('focused');
					}).focusout(function(){
						hide_autocomplte_list();
					});

					//закрываем все октрытые авто-списки
					function hide_autocomplte_list() {
						$('.auto-cmplt-select').each(function(){
							$(this).fadeOut();
						});					
					}


					//если пользователь выбрал вариант
					$('body').on('click', '.auto-cmplt-list', function(){
						var $this = $(this);

						//получем общий родитель
						var $parent = $('.auto-cmplt-parent');
						//получем инпут
						var $input = $('.auto-cmplt-input');
						//выбраный вариант
						var value = $this.text().trim();

						$this.closest($parent).find($input).val(value);

					});
				});	



//radio button start
	$('body').on('click', '.radio-button', function(){
		var $this = $(this);
		var radio_initial = $(this).attr('ls-radio-for');

		$(`.radio-button[ls-radio-for='${radio_initial}']`).each(function(){
			$(this).removeClass('radio-active');
		});

		$(this).addClass('radio-active');
	});
//radio button end






			</script>

</body>
</html>



<style type="text/css">

section.ui-section {
    width: 1000vw;
    height: 100vh;
    display: flex;
    border: 1px solid;
    margin-top: 100px;
    margin-bottom: 100px;
    flex-direction: column;
}

.ui-header {
    width: 100%;
    font-size: 42px;
    padding: 10px 15px;
    background: #fff;
}

.ui-content {
    display: flex;
    height: inherit;
}


.desing-content {
    width: 500px;
}	

.radio-wrapper {
    display: block;
    background: #fff;
    border-radius: 5px;
    box-shadow: var(--shadow-border);
}
.radio-title {
    font-size: 17px;
    font-weight: 700;
    color: #353345;
    line-height: 1.6;
    margin-bottom: 5px;
    padding: 8px 15px;
    box-shadow: rgba(0,0,0,.11) 0 1px;
}
.radio-list {
    display: flex;
    flex-direction: column;
    /* padding: 0px 10px; */
}
.radio-button {
    display: flex;
    margin-bottom: 8px;
    height: 40px;
    width: 100%;
    align-items: center;
    padding: 0 15px;
    /* transition: .5s; */
}
.radio-state-mark {
    --radio-state-mark-height: 15px;
    width: var(--radio-state-mark-height);
    height: var(--radio-state-mark-height);
    border-radius: var(--radio-state-mark-height);
    display: block;
    margin-right: 10px;
    transition: .2s cubic-bezier(0.59, 0.03, 0.68, 0.94);
}
.radio-value {
    display: block;
    font-size: 16px;
    color: var(--primary-text-color);
    color: #313131;
    font-weight: 600;
}

.radio-button:not(.radio-active) > .radio-state-mark {
    border: 2px solid rgb(148 148 148);
    background: var(--color-portage-10);
}

.radio-button:not(.radio-active):hover > .radio-state-mark {
    background: var(--color-portage-20);
}
.radio-button:hover  {
    /* box-shadow: rgba(0,0,0,.2) 0 1px 1px 0px; */
    background: rgb(160 160 160 / 10%);
}
.radio-button.radio-active > .radio-state-mark {
    background: var(--color-portage-100);
    border: 3px solid rgb(255 255 255);
    box-shadow: 0px 0px 0px 3px var(--color-portage-100);
}
.radio-button.radio-active > .radio-value {
    color: rgb(36 77 197);
}








</style>