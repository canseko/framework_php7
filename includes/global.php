<?php date_default_timezone_set("America/Mexico_City");
@session_start();
header ('Content-type: text/html; charset=utf-8');
$page = curPage();


$isMobile = (bool)preg_match('#\b(ip(hone|od|ad)|android|opera m(ob|in)i|windows (phone|ce)|blackberry|tablet|s(ymbian|eries60|amsung)|p(laybook|alm|rofile/midp|laystation portable)|nokia|fennec|htc[\-_]|mobile|up\.browser|[1-4][0-9]{2}x[1-4][0-9]{2})\b#i', $_SERVER['HTTP_USER_AGENT'] );

if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
   $isMobile = false;
}

if($show_error)
	error_reporting(E_ALL);
else
    error_reporting(0);

$obj = new db();
$today = date("Y-m-d H:i:s");

if(empty($_SESSION["panel"]->id_admin) && $page != "index.php"){
	header("Location: index.php");
}

function getCategoriaAcademia($id){
	global $obj;

	return $obj->get_var("select categoria from categoria_academia where id_categoria_academia = {$id} limit 1");
}
function getNombreArea($id){
	global $obj;

	return $obj->get_var("select area from area where id_area = {$id} limit 1");
}