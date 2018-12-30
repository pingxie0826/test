<?php
	header('content-type:text/html;charset=utf-8');
	$host   = '127.0.0.1';
	$root   = 'root';
	$pass   = 'root';
	$dbname = 'qimokaoshi';

	$mysqli = new mysqli($host,$root,$pass,$dbname);
	if(mysqli_connect_errno())
	{
		echo "Err1:".mysqli_connect_error()."<br>";
		exit();
	}
	$mysqli->query('set names utf8');
?>