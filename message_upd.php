<?php
	define('IN_PHP',1);
	include_once 'class/mysql.class.php';
	$dbObj = new db_mysql('localhost','root','root','wu');
	$page = isset($_GET['page'])?$_GET['page']:$_POST['pages'];
	//echo '$page='.$page.'<br>';
	$s_id = isset($_GET['s_id'])?$_GET['s_id']:'';
	//查询要修改的新闻内容
	$sql = "select * from message where s_id='{$s_id}'";
	$arr = $dbObj->getone($sql);	
	echo "<pre>";
	//print_r($arr);
	echo "</pre>";
	
?>
<html>
<style>
<!--
.main {
width:95%;

padding: 20px 20px 20px 20px;
height:83%;


}
.main table
{
	width:96%;
	border:1px solid black;
	font-size:12;
	border-collapse:collapse;
}
.main table tr td
{
	padding-left:20px;
}
.main table input[type="text"], .main input[type="file"]
{
color: black;
width: 70%;
padding: 0px 0px 0px 10px;
border: 1px solid #c5e2ff;
background: #fbfbfb;
outline: 0;
-webkit-box-shadow:inset 0px 1px 6px #ecf3f5;
box-shadow: inset 0px 1px 6px #ecf3f5;
font-size:13px;
height: 30px;
line-height:15px;
margin: 2px 6px 5px 0px;
}

#one select
{
color: black;
width: 70%;
padding: 0px 0px 0px 5px;
border: 1px solid #c5e2ff;
background: #fbfbfb;
outline: 0;
-webkit-box-shadow:inset 0px 1px 6px #ecf3f5;
box-shadow: inset 0px 1px 6px #ecf3f5;
font-size:13px;
height: 30px;
line-height:15px;
margin: 2px 6px 5px 0px;	
}
.special select
{
color:black;
width: 26%;
padding: 0px 0px 0px 5px;
border: 1px solid #c5e2ff;
background: #fbfbfb;
outline: 0;
-webkit-box-shadow:inset 0px 1px 6px #ecf3f5;
box-shadow: inset 0px 1px 6px #ecf3f5;
font-size:13px;
height: 30px;
line-height:15px;
margin: 2px 6px 5px 0px;	
}
.icon:before { line-height: .7em; }

.btn{
font-size:16px;
border:1px solid #1e7db9;
box-shadow: 0 1px 2px #8fcaee inset,0 -1px 0 #497897 inset,0 -2px 3px #8fcaee inset;
background: -webkit-linear-gradient(top,#42a4e0,#2e88c0);
background: -moz-linear-gradient(top,#42a4e0,#2e88c0);
background: linear-gradient(top,#42a4e0,#2e88c0);
width: 80px;
line-height: 26px;
text-align: center;
font-weight: bold;
color: #fff;
text-shadow:1px 1px 1px #333;
border-radius: 5px;
margin:0 5px 5px 0;
position: relative;
overflow: hidden;	
}
.btn:hover 
{
   box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}
-->
</style>
<link rel="stylesheet" type="text/css" href="css/reset-min.css"/>    
<link rel="stylesheet" type="text/css" href="css/buttons.css"/>
<form enctype='multipart/form-data' action='message_upd2.php' method='post'>
<input type='hidden' name='pages' value="<?php echo $page; ?>">
<div class='main'>
<table border='1'>		
		<tr height="30px">
		<th>列名</th>
		<th>旧记录</th>
		<th>新记录</th>
		</tr>

		<tr>
		<td>编号</td>
		<td><?php echo $arr['s_id'];?></td>
		<td><input type='text' value="<?php echo $arr['s_id']?>" readonly='true' name='s_id'></td>
		</tr>


		<tr>
		<td>学生</td>
		<td><?php echo $arr['s_name'];?></td>
		<td><input type='text' value="<?php echo $arr['s_name']?>" name='s_name'></td>
		</tr>

		<tr>
		<td>老师</td>
		<td><?php echo $arr['s_name2'];?></td>
		<td><input type='text' value="<?php echo $arr['s_name2']?>" name='s_name2'></td>
		</tr>

		<tr>
		<td>头像</td>
		<td><img src="<?php echo $arr['s_path']?>" width='100' height='80'style="border-radius:50%;"></td>
		<td>
			<input type='file' name='hfile' id='s_path'>
		</td>
		</tr>

		<tr>
		<td>性别</td>
		<td><?php echo $arr['s_sex'];?></td>
		<td>
		<label id='one'>
		<select name='s_sex'id='s_sex'>
		<option value="男">男</option>
		<option value="女">女</option>	
		</select>
		</label>
		</td>
		</tr>

		<!----------------------------------------------->
		<!----------------------------------------------->

		<tr>
			<td>生日</td>
			<td><?php echo $arr['s_time']?></td>
			<td>
				<label class='special'>
					<script type="text/javascript" src="js/birthday.js"></script>

<select id="selYear" name='selYear'></select>年
<select id="selMonth" name="selMonth"></select>月
<select id="selDay" name="selDay"></select>日
<!--完成出生日期的选择--需写在</body>前-->

<script type="text/javascript">
var selYear = window.document.getElementById("selYear");
var selMonth = window.document.getElementById("selMonth");
var selDay = window.document.getElementById("selDay");
// 新建一个DateSelector类的实例，将三个select对象传进去
new DateSelector(selYear, selMonth, selDay, 1995, 1, 17);
</script>
				</label>
			</td>
		</tr>
		<!----------------------------------------------->
		<!----------------------------------------------->

		<tr>
			<td>家庭电话</td>
			<td><?php echo $arr['s_tel'];?></td>
			<td><input type='text' name='s_tel' id='s_tel' value="<?php echo $arr['s_tel'];?>"></td>
		</tr>

		<tr>
			<td>地址</td>
			<td><?php  echo $arr['s_add'];?></td>
			<td>
			<label class='special'>
				<script src="js/pccs.js"  type="text/javascript"></script>      
     <select name="province" id="province">
     </select>
     <select name="city" id="city">
     </select>
     <select name="county" id="county">
     </select>
     <script language="javascript">
		setup() 
      </script>
		</label>
			</td>
		</tr>

		<tr>
			<td>学生等级</td>
			<td><?php echo $arr['s_level'];?></td>
			<td>
				<input type='text' value="<?php echo $arr['s_level'];?>" name='s_level'>
			</td>
		</tr>
	
		<tr height="40">
			<td colspan='3' align='center'>
			
				<input type='submit' value="更新" class='btn'>
		<a href="message_all.php"  class="button orange shield glossy">返回</a>
			</td>
		</tr>

</table>
</div>
</form>
