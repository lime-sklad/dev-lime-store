<?php 
// here include config file
require_once $_SERVER['DOCUMENT_ROOT'].'/db/config.php';


// if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/backup/')) {
//     mkdir($_SERVER['DOCUMENT_ROOT'].'/backup/', 0700);
// }

// $db_backup_dir = $_SERVER['DOCUMENT_ROOT'].'/backup/db/';
// //создаем папку для бэкапа бд
// if(!file_exists($db_backup_dir)) {
// 	mkdir($db_backup_dir, 0700);
// }    
// $dir = $db_backup_dir.'db'.date('-(d.m.Y)-(H-i)').'.sql';

// $dump_dir = dirname($_SERVER['DOCUMENT_ROOT']) . '/mysql/bin/mysqldump';

// exec("$dump_dir --user=".DBUSER." --password=".DBPASS." --host=".DBHOST." ".DBNAME." --result-file={$dir} 2>&1", $output);


/**
 * конфиг бэкапа
 */
function db_dump_config() {
    return [
        //общяя папка с бэкапами
        'get_backup_dir'            => $_SERVER['DOCUMENT_ROOT'] . '/backup/',
        
        // папка для бэкапа базы данных
        'get_db_backup_dir'         => $_SERVER['DOCUMENT_ROOT'] . '/backup/db/',
        
        // автоматическое название бэкапа
        'get_db_auto_name'          => 'db'.date('-(d.m.Y)'),
        
        // путь до исполняемого файла для бэкапа
        'get_mysql_dump_path'       => dirname($_SERVER['DOCUMENT_ROOT']) . '/mysql/bin/mysqldump'
    ];
}

/**
 * Проверяем наличие папки если нет, то создаем папку
 * @param string $dir path
 */
function check_and_create_db_dir($dir) {
    if(!file_exists($dir)) {
        mkdir($dir, 0700);
    }

    return true;
}


/**
 * делаем бэкап базы данных
 * берем данные из конфига
 */
function make_db_dump() {
    $dump_db_config = db_dump_config();

    if(is_db_backup_exists($dump_db_config['get_db_auto_name'], $dump_db_config['get_db_backup_dir']) == false) {
        ls_db_dump($dump_db_config['get_backup_dir'], $dump_db_config['get_db_backup_dir'], $dump_db_config['get_mysql_dump_path'], $dump_db_config['get_db_auto_name']);
    }
}


/**
 * @param string $backup_dir путь до общей папки с бэкапами
 * @param string $db_backup_dir путь до папки с бэкапами для базы данных
 * @param string $dump_exe_path путь до исполняемого файла бэкапа
 * @param strign $dump_name название файла бэкапа 
 */
function ls_db_dump($backup_dir, $db_backup_dir, $dump_exe_path, $dump_name) {
    if(check_and_create_db_dir($backup_dir) && check_and_create_db_dir($db_backup_dir)) {
        $save_to = $db_backup_dir . $dump_name . '.sql';
        if(exec("$dump_exe_path --user=".DBUSER." --password=".DBPASS." --host=".DBHOST." ".DBNAME." --result-file={$save_to} 2>&1", $output)) {
            return true;
        }
    }

    return false;
}


/**
 * 
 */
 function is_db_backup_exists($db_dump_name, $db_dump_file_path) {
    $dump_name = $db_dump_file_path . $db_dump_name . '.sql';

    if(file_exists($dump_name)) {
        return true;
    } else {
        return false;
    }
 }