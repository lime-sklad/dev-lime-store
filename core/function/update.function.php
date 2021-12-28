<?php 
require_once $_SERVER['DOCUMENT_ROOT']. '/db/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/function/db.wrapper.php';
/**
 * получаем сегодняшний день недели 
 */
function get_current_week() {
    $today_list = getdate();
	return $today_list['wday'];
}

/**
 * проверяем есть интернет у пользоваетля
 */
function is_check_connetion() {
	$ch = curl_init('https://www.google.com/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$get_connectino = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	if($info['http_code'] == '200') {
		return true;
	} else {
		return false;
	}
}

/**
 * конфиг обновления
 * @return array массив с конфигом
 */
function update_config() {
    // корневая папка программы 
	$root_dir =  $_SERVER['DOCUMENT_ROOT'];

    return array(
        /**
         * день недели когда проверять обновление
         * 1 - это понедельник
         * 2 - это вторник 
         * 4 - это четверг
         * и тд
         */        
        'get_date_to_check_update' => 5,

        // папка куда сохраняем бэкап
        'get_backup_dir' => $root_dir.'/backup/',
        
        // папка куда сохраняется скачанный архив с обновлением 
        'get_update_temp_dir' => $root_dir.'/update/',
        
        // путь к файлу с текущей версией 
        'get_local_version_file_path' => $root_dir.'/version.txt',

        // ссылка на версию на сервере
        'get_server_version_file_path' => 'https://raw.githubusercontent.com/lime-sklad/update_limestore/master/version.txt',

        // архив с обновлением
        'get_update_file_path' => 'https://github.com/lime-sklad/update_limestore/raw/master/testupdate.zip',
    );
}


/**
 * получаем текушюю версию
 * @return int version
 */
function get_current_version() {
    $upd_config = update_config();

   return file_get_contents($upd_config['get_local_version_file_path']);
}

/**
 * получаем версию
 */
function get_last_version() {
    $update_config = update_config();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $update_config['get_server_version_file_path']);
    curl_setopt($ch, CURLPROTO_HTTPS,1);	
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);// таймаут в секундах
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/17.17134');

    $get_verison = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if($info['http_code'] == '200') {
        return trim($get_verison);
    } 
    
    return false;
}


/**
 * делаем бэкап
 */
function make_backup() {
  $update_config = update_config();
  $source = $_SERVER['DOCUMENT_ROOT'];
  $destination = $update_config['get_backup_dir'].'backup_'.date("d.m.Y").'.zip';
  // sql bakcup
  include $_SERVER['DOCUMENT_ROOT'].'/core/function/db.dump.php';

  if (!extension_loaded('zip') || !file_exists($source)) {
    return false;
  }
 
  $zip = new ZipArchive();
  if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
    return false;
  }
 
  $source = str_replace('\\', '/', realpath($source));
 
  if (is_dir($source) === true) {
    $files[] = $_SERVER['REQUEST_URI'];

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
 
    foreach ($files as $file){
        $file = str_replace('\\', '/', $file);
 		$strip_folders = array(
            'backup', 
            'ajax_page_php', 
            'akssesuar', 
            'update.zip', 
            'charts', 
            'backup.zip', 
            'charts',
            '.git',
            '.vscode',
            'oldfiles',
            'test',
            '.gitignore',
            '1.diff',
            '1.txt',
            'test.php',
            'test2.php'
        );
        
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
  } else if (is_file($source) === true){
    $zip->addFromString(basename($source), file_get_contents($source));
  }
  return $zip->close();
}

/**
 * скачиваем обновление
 */
function download_update_file() {
    $update_config = update_config();

    $zipFile = $update_config['get_update_temp_dir']."update.zip"; // Rename .zip file
    $zipResource = fopen($zipFile, "w");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $update_config['get_update_file_path']);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt($ch, CURLOPT_FILE, $zipResource);

    $page = curl_exec($ch);

    curl_close($ch);

    if(is_check_connetion()) {
        return $page;		
    } else {
        return false;
    }
}


/**
 * извлекаем архив и устанавливем обновление 
 */
function unpack_update($root_dir) {	
    $update_config = update_config();

	$zip = new ZipArchive;
	if($zip->open($update_config['get_update_temp_dir'].'update.zip') === TRUE) {
		//делаем бэкап
		make_backup();		
			
		$zip->extractTo($root_dir);
	    $zip->close();
	    
	    file_put_contents($update_config['get_local_version_file_path'], trim(get_last_version()));		    
        return true;
    } else {
		return false;
	}
}

/**
 * вызываем если есть ошибки
 */
function get_error($twig) {
    echo json_encode([
        'error' => $twig->render('/component/modal/update/update-error.twig', [
            'error' => [
                'text' => 'Проверьте подключение к интернету и попробуйте еще раз'
            ]
        ])
    ]); 	
}

/**
 * проверяем есть ли обновление
 * @return false/true
 */
function is_check_update() {
    $upd_config = update_config();

    // если есть инетренет и в базе есть увидомление об обновлении
    if(check_update_notify()) {
        return true;
    } 

    /**
     * если есть инетренет но в базе нет увидомления об обновлении
     * делаем запрос и проверяем обновление
     */
    else if (is_check_connetion() && check_update_notify() == false) {
        if(get_current_week() == $upd_config['get_date_to_check_update']) {
            $checked_version = get_last_version();
            $current_version = get_current_version();
     
            if($checked_version && $current_version) {
                return is_new_version($current_version, $checked_version);
            }
     
            return false;
        }        
    }

    return false;
}

/**
 * Проверяем наличие папки если нет, то создаем папку
 * @param string $dir path
 */
function check_and_create_dir($dir) {
    if(!file_exists($dir)) {
        mkdir($dir, 0700);
    }

    return true;
}

/**
 * получаем с базы данных увидомление об обновлении
 */
function check_update_notify() {
    $res = ls_db_request([
        'table_name' => 'ls_notify',
        'col_list' => '*',
        'base_query' => ' WHERE notify_type = :notfy_type ',
        'param' => [
            'query' => [
                'param' => '',
                'joins' => '',
                'bindList' => [
                    ':notfy_type' => 'update'
                ]
            ],
            'sort_by' => ' ORDER BY notify_id DESC'
        ]
    ]);

    if(count($res) > 0) {
        // в базе увидомление есть
        return true;
    } else {
        // в базе увидомления нет
        return false;
    }

    return false;
}


/**
 * добавляем увидобление в базу об обновлении 
 */
function insert_update_notify() {
    return ls_db_insert('ls_notify', [
        array(
            'notify_text' => 'new update' . date('d.m.Y'),
            'notify_type' => 'update'            
        )
    ]);
}


function reset_update_notify() {
    global $dbpdo;
    
    ls_db_delete([
        array(
            'table_name' => 'ls_notify',
            'where' => ' notify_type = :not_type ',
            'bindList' => [
                ':not_type' => 'update'
            ]
        )
    ], $dbpdo);
}


/**
 * проверяет есть ли обновление сравнивая верссиии
 * @param int $currentVersion
 * @param int $serverVersion
 * @return true/false
 */
function is_new_version($current_version, $serverVersion) {
    if($current_version !== $serverVersion) {
        insert_update_notify();
        return true;
    } 

    return false;
}

/**
 * Обновляем
 */
function ls_update() {
    global $dbpdo,
           $twig; // дикий треш - исправить
    $update_config = update_config();

    if(is_check_connetion()) {
        // проверяем наличие папки backup
        check_and_create_dir($update_config['get_backup_dir']);
        // проверяем наличие папки update
        check_and_create_dir($update_config['get_update_temp_dir']);
    
        //скачиваем обновление
        
        if(download_update_file()) {
            // устанавливаем обновление
            unpack_update($_SERVER['DOCUMENT_ROOT']);

            // сбрасываем увидомление 
            reset_update_notify();

            // выводим сообщение успех
            echo json_encode([
                'success' => $twig->render('/component/modal/update/update-success.twig', [
                    'success' => [
                        'text' => "Обновление установлено! \n Пожалуйста, перезагрузите программу!"
                    ]
                ])
            ]);
        } else {
            get_error($twig);
        }
    } else {
        get_error($twig);
    }
}

