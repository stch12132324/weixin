<?php
class App{
	//@ ������
	static public function run(){

		$_args = trim(PATH_INFO,"/");
		$_args = explode("-",$_args);
		
		// ����
		list($_group, $_groupVal)			=  	self::_initGroup($_args);
		
		// ��ȡmodule��action
		list($_module, $_action)  			=  	self::_initController($_args);
		
		// ·����֤
		list($_module_name, $_module_file)	=	self::_initRouterVerify($_module, $_groupVal);

		// ��ȡGET����
		self::_initGet($_args);
		
		// �Զ���·�� - �ܵȴ��Ľ�
		self::_initRouter($_module, $_action);
		
		// ���԰�
		self::_initLang();
		// ·�ɽ���
		include BJ_ROOT."Lib/Core/Model.class.php";
		include BJ_ROOT."Lib/Core/Action.class.php";
        include BJ_ROOT."Lib/Class/MyException.class.php";
		include $_module_file;

		$act = new $_module_name();
		if(method_exists($act,$_action)){
            try{
                self::_initRun($act, $_group, $_module, $_action);
            } catch(Exception $e) {
                echo $e->getMessage();
            }
		}else{
			// �쳣action ����
			header("Location:/");
		}
	}
	
//--------------------------------- ������ --------------------------

	//@ �Զ���·��
	public static function _initRouter(&$_module, &$_action){

	}
	
	//@ Action ����  / ��ʼ��Controller�� �� ����Action֮�������¼�
	public static function _initRun($act, $_group, $_module, $_action){

		//@ Action ����ע��
		$_module 		= 	lcfirst($_module);
		$act->_action	= 	$_action;
		$act->_module 	= 	$_module;
		$act->_group 	= 	$_group;

        //@ filterע��
        $act->_filter   =  LC("Filter");
        $act->_filter->filterBase();

		$act->$_action();
	}

//--------------------------------- ������ --------------------------

	//@ ��ȡ����
	public static function _initGroup(&$_args){
		$ConfigGroupArray = array( // �������������ļ�
			'Admin',
			'Mobile',
		);
		if(in_array($_args[0],$ConfigGroupArray)){
			$_group = array_shift($_args);
			$_groupVal = $_group."/";
		}
		return array($_group,$_groupVal);
	}
	
	//@ ��ȡController
	public static function _initController(&$_args){
		$_module = array_shift($_args);
		$_action = array_shift($_args);
		$_module = $_module==''?'Index':$_module;
		$_action = $_action==''?'index':$_action;
		return array($_module, $_action);
	}
	
	//@ ��֤Module �Ƿ����
	public static function _initRouterVerify($_module, $_groupVal){
		$_module_name = ucfirst($_module)."Action";
		$_module_file = BJ_ROOT.'Action/'.$_groupVal.$_module_name.".php";
		if(is_file($_module_file)){
			return array($_module_name, $_module_file);
		}else{
			header("Location:/");
			exit;	
		}
	}
	
	//@ ��ȡget
	public static function _initGet(&$_args){
		//unset($_GET); //�����·��get����
		$Len = count($_args);
		for($n = 0; $n < $Len; $n = $n+2){
			$_GET[$_args[$n]] = $_args[$n+1];
		}	
	}
	
	//@ ��ʼ�����԰�
	public static function _initLang(){
		$lang_file = BJ_ROOT."Lang/common.lang.php";
		if(is_file($lang_file)){
			$_common_lang = array();
			include BJ_ROOT."Lang/common.lang.php";
			foreach($_common_lang as $key=>$val){
				define($key,$val);
			}
		}	
	}	
	
}
?>