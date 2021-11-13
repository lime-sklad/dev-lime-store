<?php

	define('root_dir', $_SERVER['DOCUMENT_ROOT']);
	//корневая папка программы
	$root_dir = root_dir;

	//путь к папке с бэкапами
	$backup_dir = $root_dir.'/backup/';

	//путь к фалам с обновами
	$update_dir = $root_dir.'/update/';

	//путь к версии пользователя
	$path_user_version = $root_dir.'/version.txt';
	//версия пользоватея
	$current_version = file_get_contents($path_user_version);
	$current_version = trim(stripcslashes(strip_tags(htmlspecialchars($current_version))));

	//версия на сервере
	$url_version = 'https://raw.githubusercontent.com/lime-sklad/update_limestore/master/version.txt';

	//ссылка на архив
	$url_zip = "https://github.com/lime-sklad/update_limestore/raw/master/last_update.zip"; 

	//получем версию на сайте
	$get_verison = check_last_version($url_version, $current_version);

	//если есть обновления
	if($current_version !== $get_verison) {
		//если не запущено обновление
	   if(!isset($_POST['download_upd'])) {
	   		show_upd_notify();
	   }
	   //если нажали запустить обновление
	   if(isset($_POST['download_upd'])) {
	   		//создаем папку бэкап если нету
			if(!file_exists($backup_dir)) {
				mkdir($backup_dir, 0700);
			}
			//если если нет папкм updates то создаем и скачмвем
			if(!file_exists($update_dir)) {
				mkdir($update_dir, 0700);
			} 

			//скачиваем update.zip 
			donwload_update($update_dir, $url_zip);
							
			//запускаем установку обнов.
			ls_install_update($update_dir, $root_dir, $backup_dir, $path_user_version, $get_verison);
	    }
	} 


	function check_last_version($url_version, $current_version) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_version);
		curl_setopt($ch, CURLPROTO_HTTPS,1);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);// таймаут в секундах
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/17.17134');

		$get_verison = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		if($info['http_code'] == '200') {
			$get_verison = trim(stripcslashes(strip_tags(htmlspecialchars($get_verison))));
		} else {
			$get_verison = $current_version;
		}
		return $get_verison;
	}


	//делаем бэкап
	function get_backup($source, $destination){
	  if (!extension_loaded('zip') || !file_exists($source)) {
	    return false;
	  }
	 
	  $zip = new ZipArchive();
	  if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
	    return false;
	  }
	 
	  $source = str_replace('\\', '/', realpath($source));
	 
	  if (is_dir($source) === true){
	    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	 
	    foreach ($files as $file){
	        $file = str_replace('\\', '/', $file);
	 		$strip_folders = array('backup', 'ajax_page_php', 'akssesuar', 'update', 'charts', 'backup.zip', 'charts');
	 		$file = str_replace($strip_folders, ' ', $file);
	        // Ignore "." and ".." folders
	        if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
	            continue;

	        $file = realpath($file);
	        $file = str_replace('\\', '/', $file);
	         
	        if (is_dir($file) === true){
	            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	        }else if (is_file($file) === true){
	            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
	        }
	    }
	  }else if (is_file($source) === true){
	    $zip->addFromString(basename($source), file_get_contents($source));
	  }
	  return $zip->close();
	}

	function donwload_update($update_dir, $url) {
		$zipFile = $update_dir."update.zip"; // Rename .zip file
		$zipResource = fopen($zipFile, "w");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_FILE, $zipResource);

		$page = curl_exec($ch);

		curl_close($ch);
		return $page;		
	}


	//извлекаем архив и устанавливем обновление 
	function ls_install_update($update_dir, $root_dir, $backup_dir, $path_user_version, $get_verison) {	 
		$zip = new ZipArchive;
		if($zip->open($update_dir.'update.zip') === TRUE) {
			//делаем бэкап
			get_backup($root_dir, $backup_dir.'backup_'.date("d.m.Y").'.zip');		
				
			$zip->extractTo($root_dir);
		    $zip->close();
		    
		    file_put_contents($path_user_version, $get_verison);
		    get_error('success');		    
		} else {
			get_error("error");
		}
	}


	function get_error($type) {
		header('Content-type: Application/json');

		$product_err = [
		  'notify' 		=> $type,
		  'error'		=> $type
		];			

		//выводим сообщение и останавливаем
		echo json_encode($product_err);	

		exit();
		return $product_err;	
	}


	//показывает блок оповещение об обновлении
	function show_upd_notify() {
	?>
		<div class="update_modal">
		  <div class="update_modal_wrapper">
		    <div class="update_modal_content upd_modal_console">
		      
		      <div class="upd_console_header">
		        <div class="upd_console_hdr_list">
		          <div class="upd_console_logo">LS$</div>
		          <div class="upd_console_name">LS:\limestore\update</div>
		        </div>
		      </div>
		      
		      <div class="upd_console_content_wrp">
		        <div class="upd_console_cmd_list">
		          <div class="upd_console_cmd">
		            <div class="upd_console_cmd_name">$lime@store></div>
		            <div class="upd_console_content_descripton">Yeniləmə yüklənir</div>

		            <div class="upd_console_load">
		              <div></div> <div></div> <div></div> <div></div> <div></div>
		              <div></div> <div></div> <div></div> <div></div> <div></div>
		              <div></div> <div></div>          
		            </div>            
		          </div>
		          
		          <div class="upd_console_cmd">
		            <div class="upd_console_cmd_name">$lime@store></div>
		            <div class="upd_console_content_descripton upd_warning_notify">PROQRAMI BAĞLAMAYIN!</div>   
		          </div> 
		          
		        </div>  
		      </div>
		    </div>

		      <div class="upd_info_modal upd_err_modal">
			      <div class="update_error flex-c100">
			      	<div class="update_error_img">
			      		<img src="/img/icon/error_upd.png">
			      	</div>
			      	<div class="update_install_err_description">
			      		<span class="error_span error_description_strong">Xəta!</span>
			      		<span class="error_span error_descrpt_second">İnternet bağlantılarınızı yoxlayın və yenidən cəhd edin</span>
			      	</div>
			      	<div class="update_err_btn">
			      		<a href="/" class="btn accpet_err_upd_btn">Ok</a>
			      	</div>
			      </div>
			  </div>

		      <div class="upd_info_modal upd_succ_modal">
			      <div class="update_error flex-c100">
			      	<div class="update_error_img">
			      		<img src="/img/icon/success_upd.png">
			      	</div>
			      	<div class="update_install_err_description">
			      		<span class="error_span error_description_strong">Yeniləmə yükləndi</span>
			      		<span class="error_span error_descrpt_second">Davam etmək üçün "OK" düyməsini basın</span>
			      	</div>
			      	<div class="update_err_btn">
			      		<a href="/core/action/logout.php?logout" class="btn accpet_err_upd_btn">Ok</a>
			      	</div>
			      </div>
			  </div>

		  </div>
		</div>
		<div class="update_block">
			<div class="upd_wrapper">
				<div class="upd_header">
					<span class="">Yeniləmə mövcuddur</span>
				</div>
				<div class="upd_dwnld">
					<button class="btn download_btn_s download_btn">Yükləyin</button>
				</div>
			</div>
		</div>
	<?php
	}

?> 

<script type="text/javascript" defer="">
	$(document).ready(function(){
		$('body').on('click', '.download_btn', function(){	
			var $get_preloader = $('.update_modal');
			$get_preloader.fadeIn();
			alert('Yeniləmələr yüklənir, zəhmət olmasa yükləmənin sonuna qədər gözləyin.\nDavam etmək üçün "OK" düyməsini basın');

			setTimeout(function(){
				get_request_upd();
			}, 3000);

			function get_request_upd() {
				var download_upd = 'dsfds';
				var link_path = 'core/main/update_check.php';
				$.ajax({
					type: 'POST',
					url: link_path,
					dataType: 'json',
					data: {
						download_upd: download_upd
					},
					cache: false,
					success: (data) => {
						var res = data['notify'];

						$('.upd_modal_console').fadeOut();

						if(res === 'success') {
							$('.upd_succ_modal').fadeIn();
							  setTimeout(function(){
							  		window.location.href= "/core/action/logout.php?logout";
							  }, 2500);
						}
						if(res === 'error') {
							$('.upd_err_modal').fadeIn();
						}					
					}			

				});
			}

		});
	});
</script>


<link rel="stylesheet" type="text/css" href="/css/update_style.css?<?php echo time(); ?>">