<?php
include $_SERVER['DOCUMENT_ROOT'].'/db/config.php';
if(!isset($_SESSION['user']))
{
	// header("Location: index.php");
}
else if(isset($_SESSION['user'])!="")
{
	// header("Location: index.php");
}

if(isset($_GET['logout']))
{
	session_destroy();
	unset($_SESSION['user']);
	session_write_close();
	header("Location: /");
}
