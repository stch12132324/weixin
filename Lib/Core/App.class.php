<?php
class App{
	//@ 主方法
	static public function run(){

		$_args = trim(PATH_INFO,"/");
		$_args = explode("-",$_args);
		
		// 分组
		list($_group, $_groupVal)			=  	self::_initGroup($_args);
		
		// 获取module和action
		list($_module, $_action)  			=  	self::_initController($_args);
		
		// 路由验证
		list($_module_name, $_module_file)	=	self::_initRouterVerify($_module, $_groupVal);

		// 获取GET参数
		self::_initGet($_args);
		
		// 自定义路由 - 能等待改进
		self::_initRouter($_module, $_action);
		
		// 语言包
		self::_initLang();
		// 路由进行
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
			// 异常action 处理
			header("Location:/");
		}
	}
	
//--------------------------------- 可配置 --------------------------

	//@ 自定义路由
	public static function _initRouter(&$_module, &$_action){

	}
	
	//@ Action 运行  / 初始化Controller后 到 运行Action之间运行事件
	public static function _initRun($act, $_group, $_module, $_action){

		//@ Action 基类注入
		$_module 		= 	lcfirst($_module);
		$act->_action	= 	$_action;
		$act->_module 	= 	$_module;
		$act->_group 	= 	$_group;

        //@ filter注入
        $act->_filter   =  LC("Filter");
        $act->_filter->filterBase();

		$act->$_action();
	}

//--------------------------------- 非配置 --------------------------

	//@ 获取分组
	public static function _initGroup(&$_args){
		$ConfigGroupArray = array( // 后续加入配置文件
			'Admin',
			'Mobile',
		);
		if(in_array($_args[0],$ConfigGroupArray)){
			$_group = array_shift($_args);
			$_groupVal = $_group."/";
		}
		return array($_group,$_groupVal);
	}
	
	//@ 获取Controller
	public static function _initController(&$_args){
		$_module = array_shift($_args);
		$_action = array_shift($_args);
		$_module = $_module==''?'Index':$_module;
		$_action = $_action==''?'index':$_action;
		return array($_module, $_action);
	}
	
	//@ 验证Module 是否存在
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
	
	//@ 获取get
	public static function _initGet(&$_args){
		//unset($_GET); //清除非路由get参数
		$Len = count($_args);
		for($n = 0; $n < $Len; $n = $n+2){
			$_GET[$_args[$n]] = $_args[$n+1];
		}	
	}
	
	//@ 初始化语言包
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