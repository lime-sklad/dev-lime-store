<?php 
date_default_timezone_set('Asia/Baku');
ini_set('session.cookie_lifetime', 30*60);
session_start();
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','lime_sklad');

define('LS_DIR','E:/Emil/xampp/htdocs/');
define('LS_HOST', "http://localhost/");
define('SITEEMAIL','noreply@domain.com');

try 
{
	$dbpdo = new PDO("mysql:host=".DBHOST.";charset=utf8mb4;dbname=".DBNAME, DBUSER, DBPASS);
    $dbpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbpdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    require_once LS_DIR.'/vendor/autoload.php';
	$loader = new \Twig\Loader\FilesystemLoader(LS_DIR.'/core/template/');
	$twig = new \Twig\Environment($loader);

} 
catch(PDOException $e)
{
    echo "Проблемы на сервере";
    exit();
}
?>
