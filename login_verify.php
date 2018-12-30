<?php
//echo "aaaaaaaaaaa";
	error_reporting(0);
	session_start();
	header("content-type:text/html;charset=utf-8");
	define('IN_PHP','ok');
	include_once 'class/mysql.class.php';
	$host='localhost';
	$root='root';
	$pass='root';
	$dbname='wu';
	$dbObj=new db_mysql($host,$root,$pass,$dbname);
	//echo "<pre>";
	//print_r($_SESSION);
	//echo "</pre>";
	//EXIT();
	if(!empty($_POST))
	{
		$uname=isset($_POST['uname'])?$_POST['uname']:'';
		$upwd =isset($_POST['upwd'])?$_POST['upwd']:'';
		$auths=isset($_POST['auths'])?$_POST['auths']:'';
		$urole=isset($_POST['urole'])?$_POST['urole']:'';
		//echo $urole."<br>";
		//exit();
		$codeArr=explode('|',$_SESSION['code']);
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		//exit();
		if($uname=="" || $upwd=="" || $auths=="")
		{
			echo "<script>";
			echo "alert('账号,密码验证码不得为空');";
			echo "location.href='login.html';";
			echo "</script>";
		}
		if(time()- $codeArr[1]>60)
		{
			echo "<script>";
			echo "alert('验证码超时');";
			echo "location.href='login.html';";
			echo "</script>";
		}
		//判断验证码是否正确

		if(strtoupper($auths) != strtoupper($codeArr[0]))
		{
			echo "<script>";
			echo "alert('验证码不正确');";
			echo "location.href='login.html';";
			echo "</script>";
		}
			$sql="select * from  register where uname ='{$uname}'";
			$unameArr=$dbObj->getone($sql);
			//echo "<pre>";
			//print_r($unameArr);
			//echo "</pre>";
			//exit();
			if(!$unameArr)//账号不存在
			{
				echo "<script>";
				echo "alert('账号不存在');";
				echo "location.href='login.html';";
				echo "</script>";
				exit();		
			}
			else//账号存在
			{ 
				//echo md5($upwd)."<br>";
				//echo $unameArr['upwd'];
				//exit();
				 if(md5($upwd)!= $unameArr['upwd'])
				 {
					echo "<script>";
					echo "alert('密码错误');";
					echo "location.href='login.html';";
					echo "</script>";
					exit();
				 }
				 else
				 {
					 //echo $unameArr['urole']."<br>";
					// echo $urole;
					// exit();
					 if($unameArr['urole']==1 && $urole=='学生')
					 {
						$_SESSION['UNAME']=$uname;
						echo "<script>";
						echo "alert('登录成功');";
						echo "location.href='index.php';";
						echo "</script>";
					 }
					else
					{
						if($unameArr['urole']==3 && $urole=='老师')
						{
							$_SESSION['UNAME']=$uname;
							echo "<script>";
							echo "alert('登录成功');";
						
							echo "location.href='index2.php';";
							echo "</script>";
						}
						if($unameArr['urole']==9 && $urole=='管理员')
						{
							$_SESSION['UNAME']=$uname;

							echo "<script>";
							echo "alert('登录成功');";
							echo "location.href='index.html';";
							echo "</script>";
						}
							
							else
							{
								echo "<script>";
								echo "alert('身份不匹配');";
								echo "location.href='login.html';";
								echo "</script>";
							}

					}
				 }
			}
	}
?>