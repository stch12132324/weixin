<?php
class ftp{
	var $ftp_server = '8.8.8.8';
	var $ftp_port = '21';
	var $ftp_user_name = '';
	var $ftp_user_pass = '';
	var $conn_id;
	public function ftp(){
		$this->conn_id = ftp_connect($this->ftp_server,$this->ftp_port) or die("Couldn't connect to ".$ftp_server);
		if(ftp_login($this->conn_id,$this->ftp_user_name,$this->ftp_user_pass)){
			ftp_pasv($this->conn_id,true); // ����ģʽ
			return '���ӳɹ�!';
		}else{
			return '����ʧ�ܣ�';	
		}
	}
	// �ϴ��ļ�
	public function upload($local_file,$destination_file){
		if(ftp_put($this->conn_id,$destination_file,$local_file,FTP_BINARY)){
			return true;	
		}else{
			return false;	
		}
		
	}
	// ɾ���ļ�
	public function ftp_del($file){
		return @ftp_delete($this->conn_id,$file);	
	}
	// ����Ŀ¼
	function ftp_mk_dir($path){
		$dir = split("/", $path);
		$path = "";
		$ret = true;
		for($i=0;$i<count($dir);$i++){
		   $path.="/".$dir[$i];
			if(!@ftp_chdir($this->conn_id,$path)){
				@ftp_chdir($this->conn_id,"/");
				if(!@ftp_mkdir($this->conn_id,$path)){
					$ret = false;
					break;
				}
			} 
		}
		return $ret;
	}
	// �ж�·���Ƿ����
	function ftp_is_dir($path){  
		$original_directory = ftp_pwd($this->conn_id); // ��ǰ·�����ȱ���
		if(@ftp_chdir($this->conn_id,$path)){  
			ftp_chdir($this->conn_id,$original_directory);  
			return true;  
		}  
		else { 
			return false;  
		}
	}
}
?>