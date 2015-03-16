<?php
/*
@ ��ܺ�����
@ PHP Filter ����
@ �����滻�������ж�
@ ��Ҫ���ˣ�

*/
class Filter{
	
	public $_filterType = '';// ��ʽ���滻||�ж�
	
	public function __construct(){
		
	}
//----------------------------- ���� --------------------------------

	//@ Base ����
	public function filterBase(){
		// ���ݲ��������ַ�����
		$_POST 		= new_addslashes($_POST);
		$_GET 		= new_addslashes($_GET);
		$_COOKIE 	= new_addslashes($_COOKIE);
		// ���ݲ�������xss��֤
		if(!defined('IN_ADMIN')){
			$_POST 		= filter_xss($_POST, ALLOWED_HTMLTAGS);
			$_GET 		= filter_xss($_GET, ALLOWED_HTMLTAGS);
			$_COOKIE 	= filter_xss($_COOKIE, ALLOWED_HTMLTAGS);
		}
		//@extract($_POST);@extract($_GET);@extract($_COOKIE);//��ֹ�ͷ�
	}
	
	//@ English Ӣ���ж�
	public function checkEnglish($val){
		if(preg_match("/[^a-zA-Z]/",$val,$rlt)){
			return false;
		}else{
			return true;	
		}
	}
	
	//@ NumEn ���ֺ�Ӣ��
	public function checkNumEn($val){
		if(preg_match("/[^a-zA-Z0-9]/",$val,$rlt)){
			return false;
		}else{
			return true;	
		}
	}
	
	//@ ����
	public function checkChinese($val){
		if(preg_match("/[^\x{4e00}-\x{9fa5}]/u",$val,$rlt)){
			return false;
		}else{
			return true;	
		}	
	}
	
	//@ Email �����ж�
	public function checkEmail($val){
		if(preg_match("/^[0-9a-zA-Z-]+@[0-9a-zA-Z-]+\.[0-9a-zA-Z]+/",$val,$rlt)){
			return true;
		}else{
			return false;	
		}
	}

	//@ Sql ���ݿ����⺯������
	public function filterSql($val,$type = 'filter'){
		$filterArray = array(
				'#','--','/\*','\*/', 	// sqlע�Ͳ���
				'grant','privileges','execute','update','count','chr',"truncate","declare","select","create","delete","insert", //sql���
				"%20","$","^","%",		// �����ַ�
				"[\x80-\xFF]",			//ʮ�������ַ�
		);
		return $type == 'filter' ? $this->filterReplace($val, $filterArray) : $this->filterCheck($val, $filterArray);
	}
	
	//@ tag html���˱�ǩ
	public function filterTag($val){
		
	}
	
	//@ xss ����
	public function filterXss($val){
			
	}
	
//----------------------------- ���� --------------------------------

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