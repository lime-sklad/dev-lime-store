<?php 
// require_once $_SERVER['DOCUMENT_ROOT'].'/function.php';
ls_include_tpl();

//check_quiz
$quiz_name = 'get_quiz_theme';
$get_theme_quiz = $dbpdo->prepare('SELECT * FROM function_settting WHERE sett_name = :quiz_name AND sett_on = 0');
$get_theme_quiz->execute([$quiz_name]);

$quiz_row = $get_theme_quiz->fetch(PDO::FETCH_ASSOC);

if($get_theme_quiz->rowCount()>0) {
	get_theme_quiz_tpl();
}


