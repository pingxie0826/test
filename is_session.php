<?php
header("content-type:text/html;charset=utf-8");
session_start();
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";
//EXIT();
if(!(isset($_SESSION['UNAME'])))
{
	$str = "<script>";
	$str.="alert('您无权访问该页面');";
	$str.="location.href='index.html';";
	$str.="</script>";
	echo $str;
	exit();
}
error_reporting(0);
$uname = isset($_SESSION['UNAME'])?$_SESSION['UNAME']:'';
?>
