<?php
IA("IndexBaseAction");
class WeChatAction extends IndexBaseAction{
	var $weChat;
	public function __construct(){
		$this->weChat = M("WeChat");
		$this->weChat->getToken();
	}
	
	public function index(){
        
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
}
?>