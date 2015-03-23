<?php
IA("IndexBaseAction");
class WeChatAction extends IndexBaseAction{
	var $weChat;
	public function __construct(){
		$this->weChat = M("WeChat");
		$this->weChat->getToken();
	}
	
	public function index(){
        echo '404 not found';
	}

	// 创建菜单
	public function createMenu(){
		$menuData = '{
    "button": [
        {
            "name": "联系我们", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "公司网站", 
                    "url": "http://www.3135.com", 
                    "sub_button": [ ]
                }
            ]
        }
    ]
}';
 		if($this->weChat->createMenu($menuData)){
			echo '提交成功';	
		}else{
			echo '提交失败';
		}
	}

	// 获取菜单
	public function getMenu(){
		$weChatMenu = $this->weChat->getMenu();
		print_r($weChatMenu);
	}

	// 删除菜单
	public function deleteMenu(){
		if($this->weChat->removeMenu()){
			echo '删除成功';	
		}else{
			echo '删除失败';	
		}
	}
	
	// 获取消息
	public function getMessage(){
		// $this->weChat->checkSignature(); 验证URL
		
		// 获取用户消息，根据设置方式进行回复
        $data = $this->weChat->getMessage();
		$this->weChat->autoReply($data);
	}
	
	// 发送消息
	public function sendMessage(){
		$openid = 'o3zqZjqHjmUYRd0RA1wKwSe3k7_8';
		$string = '您好，有什么能为您服务！';
		$this->weChat->sendMessage($openid , $string);	
	}
	
	// 上传图片
	public function uploadImage(){
		$imageUrl 	= BJ_ROOT.'0.jpg';
		$mediaType  = 'image';
		$media_id 	= $this->weChat->uploadMedia($imageUrl , $mediaType);
		echo $media_id;
	}
	
	// 群发消息
	public function SendMessageToAll(){
		
		// 获取站内资讯
		$send_number = 5;
		$newsList    = $this->weChat->db->table("news")->where("thumb!=''")->order("add_time desc")->limit($send_number)->fetch_all();
		
		// 上传图文素材
		$news_media = $this->weChat->uploadNpMedia($newsList);
		
		// 发送给测试者 预览
		$openid = 'o3zqZjqHjmUYRd0RA1wKwSe3k7_8';
		if($this->weChat->sendToView($news_media , $openid)){
			echo 'send success!';	
		}else{
			// 类里已做异常处理
		}
		// 群发给所有用户
		//$this->weChat->sendToAll($news_media);
		
	}
	
	// 获取用户列表
	public function getUser(){
		$userList = $this->weChat->getAllUser();
		print_r($userList);exit;
	}
	
	// 获取用户基本信息
	public function getUserInfo(){
		$openid = 'o3zqZjlbrMQW46x7d6WC-7GaiGCk';
		$userInfo = $this->weChat->getUserInfo($openid);
		print_r($userInfo);
	}
}
?>