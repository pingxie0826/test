<?php
class WeChat{
	private $_appid;
	private $_appdsecret;
	private $_token;
	public function __construct($_appid,$_appsecret,$_token){
			$this->_appid=$_appid;
			$this->_appsecret=$_appsecret;
			$this->_token=$_token;
	}
	
	public function _request($curl,$https=true,$method='GET',$data=null){
			$ch =curl_init();
			curl_setopt($ch,CURLOPT_URL,$curl);
			curl_setopt($ch,CURLOPT_HEADER,false);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			if($https){
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				//curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,true);
			}
			if($method == 'POST'){
				curl_setopt($ch,CURLOPT_POST,true);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			}
			$content=curl_exec($ch);
			curl_close($ch);
			return $content;
	}
	//appID     wx7bacd3a1711ae62a
	//appsecret 056def9f4d39def9a403210d7b282003
	//https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx7bacd3a1711ae62a&secret=056def9f4d39def9a403210d7b282003
	public function _getAccessToken(){
		$file = './accesstoken';
		if(file_exists($file)){
			$content = file_get_contents($file);
			$content = json_decode($content);
			if(time() - filemtime($file)<$content->expires_in){
				return $content->access_token;
			}
		}
		$curl ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_appsecret;  //注意不能有空格
		$content = $this->_request($curl);
		file_put_contents($file,$content);
		$content = json_decode($content);
		return $content->access_token;
	}
	public function _getTicket($sceneid, $type='temp', $expire_seconds=604800){
		if($type=='temp'){
			$data = '{
				"expire_seconds": %s,
				"action_name": "QR_SCENE",
				"action_info": {
					"scene": {"scene_id":%s}
				}
			}';
			$data = sprintf($data, $expire_seconds, $sceneid);
		}else{
			$data = '{
				"action_name": "QR_LIMIT_SCENE",
				"action_info": {
					"scene": {"scene_id": 123}
				}
			}';
			$data = sprintf($data, $sceneid);
		}
		$curl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->_getAccessToken();
		$content = $this->_request($curl, true, 'POST', $data);
		$content = json_decode($content);
		return $content->ticket;
	}
	public function _getQRCode($sceneid, $type = 'temp', $expire_seconds = '604800'){
		$ticket = $this->_getTicket($sceneid,$type,$expire_seconds);
		//https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=TICKET
		$content = $this->_request('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket));
		return $content;
	}
}
$wechat=new WeChat('wx977294886f8c6653','0c92ebf95fee8a37a29f9e387007bf93','');
//echo $wechat->_request('https://www.baidu.com');
//echo $wechat->_getAccessToken();
header('Content-type:image/jpeg'); //告诉浏览器输出图像
echo $wechat->_getQRCode('30');
?>
