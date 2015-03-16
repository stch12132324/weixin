<?php
class IndexBaseAction extends Action{

	function __construct(){
		parent::__construct();
	}
	
	public function showmsg($msg = '', $gourl = "-1", $type = 'success', $limittime = 5, $out_put = ''){
		$this->assign('msg', $msg);
		$this->assign('gourl', $gourl);
		$this->assign('type', $type);
		$this->assign('limittime', $limittime);
		$this->assign('out_put', $out_put);
		$this->display("msg",'base');
		exit;
	}
}
?>