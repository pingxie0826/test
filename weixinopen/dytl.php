<?php
require_once('tuling.php');
$arr = array(
'key' => '7884435326f64e5db3a9d4dfb26dd413',//这里填写自己的机器人密钥
'info' => $keyword,
'userid' => '367924',
'loc' => ''
);
$contentStr=call_tuling_api($arr);
?>