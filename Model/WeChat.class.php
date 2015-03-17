<?php
class WeChat extends Model{
	var $apiUrl		 	 = 'https://api.weixin.qq.com/cgi-bin/';
	var $weChatToken 	 = '';
	var $weChatTokenTime = 0;

	/*
	* ��Ӳ˵�
	* ע�����
	* 1��json����Ҫ�����menu{}��
	* 2������utf8����post
	*/
	public function createMenu($menuData = ''){
		$url 	= 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->weChatToken;
		$apiRlt = $this->http_post_data($url , $menuData);
		$apiRlt = json_decode($apiRlt);
		if($apiRlt->errcode == 0){
			return true;
		}else{
			throw new MyException($apiRlt->errcode , 'weChat'); 	
		}
	}
	
	/*
	* ��ȡ�˵�
	*/
	public function getMenu(){
		$url 	= 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->weChatToken;
		$apiRlt = file_get_contents($url);
		$apiRlt = str_replace("\/", "/" , $apiRlt);
		return array_iconv(json_decode($apiRlt, true) , 'utf-8' , 'gbk');
	}
	
	/*
	* ɾ���˵�
	*/
	public function removeMenu(){
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->weChatToken;
		$apiRlt = file_get_contents($url);
		$apiRlt = json_decode($apiRlt);
		if($apiRlt->errcode == 0){
			return true;
		}else{
			return false;	
		}
	}
	
	/*
	* ��ȡToken
	*/
	public function getToken(){
		session_start();
		$this->weChatToken 		= $_SESSION['weChatToken'];
		$this->weChatTokenTime	= $_SESSION['weChatTokenTime'];
		if($this->weChatToken == '' || $this->weChatTokenTime + 7200 < time()){
			$url 	= $this->apiUrl.'token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
			$apiRlt = file_get_contents($url);
			$apiRlt = json_decode($apiRlt);
			if($apiRlt->access_token !=''){
				$this->weChatToken 		= $_SESSION['weChatToken'] 	   = $apiRlt->access_token;
				$this->weChatTokenTime  = $_SESSION['weChatTokenTime'] = time();
			}else{
				throw new MyException($apiRlt->errcode , 'weChat'); 
			}
		}else{
			//return $this->weChatToken;	
		}
	}
	
	/*
	* HTTP POST �ύ��Ϣ
	*/
	public function http_post_data($url, $data_string) {
		$data_string = iconv("gbk", "utf-8//TRANSLIT//IGNORE", $data_string);
        $ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		return curl_exec($ch);
    } 
	
	/*
	* ��֤Token
	*/
	public function checkSignature(){
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature 	= $_GET["signature"];
        $timestamp 	= $_GET["timestamp"];
        $nonce 		= $_GET["nonce"];
        		
		$token 	= TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}	
	
	/*
	* ƽ̨��̨��֤�ӿ���Ȩ
	*/
	public function validToken(){
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
}
?>