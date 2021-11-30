<?php 
// here include config file
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/dump3.sql';
    
    $dump_dir = dirname($_SERVER['DOCUMENT_ROOT']) . '/mysql/bin/mysqldump';
    
    exec("$dump_dir --user=".DBUSER." --password=".DBPASS." --host=".DBHOST." ".DBNAME." --result-file={$dir} 2>&1", $output);