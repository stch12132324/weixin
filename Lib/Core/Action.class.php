<?php
class Action{
	private $_aGVal = array();
	public $_action;	// app.class.php ��ʼ��ʱ��ͻ�ע��
	public $_module;	// ͬ��
	public $_group;		// ͬ��
	public $_log;		// ͬ��
	public $_filter;

	public function __construct(){

	}

	public function display($_tplName='',$_tpfile=''){
		if(is_array($this->_aGVal)) extract($this->_aGVal);
		if(is_array($this->CONFIG_LIST)) extract($this->CONFIG_LIST);
		$action = $this->_action;
		$module = $this->_module;
		if($_tplName==''){
			include template($action,$module,$this->_group);
		}else{
			if($_tpfile=='base'){
				include template($_tplName,'',$this->_group);
			}else{
				include template($_tplName,$module,$this->_group);
			}
		}
	}

	public function assign($key='',$val=''){
		$this->_aGVal[$key] = $val;
		unset($key,$val);
	}
}
?>