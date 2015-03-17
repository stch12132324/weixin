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
}
?>