<?php
class MyException extends Exception{
    public function __construct($error = '' , $type = 'common'){
        parent::__construct();
		switch($type){	
			case 'common':
				$this->commonError($error);
			break;
			case 'weChat':
				$this->weChatError($error);
			break;
		}
    }
	
    /*
     * common �쳣
     */
    public function commonError($error = ''){
        echo $error;
    }
	
	/*
	* weChat �����쳣
 	*/
	public function weChatError($error = ''){
		echo '΢�Žӿ��쳣���쳣���룺'.$error;	
	}
}
?>