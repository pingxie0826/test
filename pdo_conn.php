<?php
	$_str = "mysql:dbname=php2;host=127.0.0.1";
	$root="root";
	$pass="root";

	try
	{
		$pdo = new PDO($_str,$root,$pass);
	}

	catch(PDOException $a)
	{
		echo "错误:".$a->getMessage()."<br>";
		exit();
	}
	$pdo->exec("set names utf8");
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
	//echo "ok";
?>