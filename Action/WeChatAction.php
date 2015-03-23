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

	// �����˵�
	public function createMenu(){
		$menuData = '{
    "button": [
        {
            "name": "��ϵ����", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "��˾��վ", 
                    "url": "http://www.3135.com", 
                    "sub_button": [ ]
                }
            ]
        }
    ]
}';
 		if($this->weChat->createMenu($menuData)){
			echo '�ύ�ɹ�';	
		}else{
			echo '�ύʧ��';
		}
	}

	// ��ȡ�˵�
	public function getMenu(){
		$weChatMenu = $this->weChat->getMenu();
		print_r($weChatMenu);
	}

	// ɾ���˵�
	public function deleteMenu(){
		if($this->weChat->removeMenu()){
			echo 'ɾ���ɹ�';	
		}else{
			echo 'ɾ��ʧ��';	
		}
	}
	
	// ��ȡ��Ϣ
	public function getMessage(){
		// $this->weChat->checkSignature(); ��֤URL
		
		// ��ȡ�û���Ϣ���������÷�ʽ���лظ�
        $data = $this->weChat->getMessage();
		$this->weChat->autoReply($data);
	}
	
	// ������Ϣ
	public function sendMessage(){
		$openid = 'o3zqZjqHjmUYRd0RA1wKwSe3k7_8';
		$string = '���ã���ʲô��Ϊ������';
		$this->weChat->sendMessage($openid , $string);	
	}
	
	// �ϴ�ͼƬ
	public function uploadImage(){
		$imageUrl 	= BJ_ROOT.'0.jpg';
		$mediaType  = 'image';
		$media_id 	= $this->weChat->uploadMedia($imageUrl , $mediaType);
		echo $media_id;
	}
	
	// Ⱥ����Ϣ
	public function SendMessageToAll(){
		
		// ��ȡվ����Ѷ
		$send_number = 5;
		$newsList    = $this->weChat->db->table("news")->where("thumb!=''")->order("add_time desc")->limit($send_number)->fetch_all();
		
		// �ϴ�ͼ���ز�
		$news_media = $this->weChat->uploadNpMedia($newsList);
		
		// ���͸������� Ԥ��
		$openid = 'o3zqZjqHjmUYRd0RA1wKwSe3k7_8';
		if($this->weChat->sendToView($news_media , $openid)){
			echo 'send success!';	
		}else{
			// ���������쳣����
		}
		// Ⱥ���������û�
		//$this->weChat->sendToAll($news_media);
		
	}
	
	// ��ȡ�û��б�
	public function getUser(){
		$userList = $this->weChat->getAllUser();
		print_r($userList);exit;
	}
	
	// ��ȡ�û�������Ϣ
	public function getUserInfo(){
		$openid = 'o3zqZjlbrMQW46x7d6WC-7GaiGCk';
		$userInfo = $this->weChat->getUserInfo($openid);
		print_r($userInfo);
	}
}
?>