<?php
/*
@ 框架核心类
@ PHP Filter 过滤
@ 过滤替换和类型判断
@ 主要过滤：

*/
class Filter{
	
	public $_filterType = '';// 方式，替换||判断
	
	public function __construct(){
		
	}
//----------------------------- 类型 --------------------------------

	//@ Base 基础
	public function filterBase(){
		// 传递参数进行字符过滤
		$_POST 		= new_addslashes($_POST);
		$_GET 		= new_addslashes($_GET);
		$_COOKIE 	= new_addslashes($_COOKIE);
		// 传递参数进行xss验证
		if(!defined('IN_ADMIN')){
			$_POST 		= filter_xss($_POST, ALLOWED_HTMLTAGS);
			$_GET 		= filter_xss($_GET, ALLOWED_HTMLTAGS);
			$_COOKIE 	= filter_xss($_COOKIE, ALLOWED_HTMLTAGS);
		}
		//@extract($_POST);@extract($_GET);@extract($_COOKIE);//禁止释放
	}
	
	//@ English 英文判断
	public function checkEnglish($val){
		if(preg_match("/[^a-zA-Z]/",$val,$rlt)){
			return false;
		}else{
			return true;	
		}
	}
	
	//@ NumEn 数字和英文
	public function checkNumEn($val){
		if(preg_match("/[^a-zA-Z0-9]/",$val,$rlt)){
			return false;
		}else{
			return true;	
		}
	}
	
	//@ 中文
	public function checkChinese($val){
		if(preg_match("/[^\x{4e00}-\x{9fa5}]/u",$val,$rlt)){
			return false;
		}else{
			return true;	
		}	
	}
	
	//@ Email 邮箱判断
	public function checkEmail($val){
		if(preg_match("/^[0-9a-zA-Z-]+@[0-9a-zA-Z-]+\.[0-9a-zA-Z]+/",$val,$rlt)){
			return true;
		}else{
			return false;	
		}
	}

	//@ Sql 数据库特殊函数过滤
	public function filterSql($val,$type = 'filter'){
		$filterArray = array(
				'#','--','/\*','\*/', 	// sql注释部分
				'grant','privileges','execute','update','count','chr',"truncate","declare","select","create","delete","insert", //sql语句
				"%20","$","^","%",		// 特殊字符
				"[\x80-\xFF]",			//十六进制字符
		);
		return $type == 'filter' ? $this->filterReplace($val, $filterArray) : $this->filterCheck($val, $filterArray);
	}
	
	//@ tag html过滤标签
	public function filterTag($val){
		
	}
	
	//@ xss 过滤
	public function filterXss($val){
			
	}
	
//----------------------------- 函数 --------------------------------

	//@function filterReplace
	public function filterReplace($val,$filterArray){
		if($val!=''){
			foreach($filterArray as $ft){
				$val = preg_replace("|".$ft."|i"," ",$val);	
			}
		}
		return $val;
	}
	//@function filterCheck
	public function filterCheck($val,$filterArray){
		if($val!=''){
			foreach($filterArray as $ft){
				preg_match("|".$ft."|i",$val,$rlt);	
				return false;
			}
		}
		return true;
	}
}
?>