<button class="rout_link" data-link="/test/test-rout-include.php">Загрузить</button>

<div class="rout-res"></div>

<script type="text/javascript">
	$('body').on('click', '.rout_link', function(){
		var  link = $(this).data('link');

		$.ajax({
			url: '/test/test-rout.php',
			type: 'POST',
			data: {
				link: link,
				for_include: 'sadash'
			},
			success: (data) => {
				$('.rout-res').html(data);
			}
		});
	});
</script>