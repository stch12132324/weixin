<?php
/*
	
	remark  : mysqliͨ�ò����࣬����STMTģʽ��ʹ�õ���ģʽ
	author  : stch12132324
	version	: 1.0
	time	: 2014-07-20
	php		: >5.3.0+
	
*/
class Db_mysqli{
	private $conn;
	private $debug = 1;
	private $dbhost = DB_HOST;
	private $dbuser = DB_USER;
	private $dbpw = DB_PW;
	private $charset = DB_CHARSET;
	private $dbname = DB_NAME;
	private $dbpre = DB_PRE;
	
	var $rs_type = MYSQLI_ASSOC; // �����ʾ��ʽ array('name'=>'abc')
	var $unbuffered = false; // �Ƿ񲻻���
	var $safe_type = 0; // �Ƿ�ʼstmtģʽ
	
	var $query_number = 0; // ��ѯ����
	var $query_times; // ��ѯʱ��
	
	var $table_name; // ������
	var $result;
	var $paramType; // stmt ģʽ�� insert ��������
	var $_where;
	var $_field;
	var $_limit;
	var $_order;
	var $_parameters;
	var $_primary;
	
	private static $_instance;
	
	function __construct(){
		$this->connect();
	}
	private function __clone(){
	
	}
	public static function getInstance()    {    
        if(! (self::$_instance instanceof self) ) {    
            self::$_instance = new self();    
        }    
        return self::$_instance;    
    } 
//------------------����-----------------------------
	public function connect(){
		
		$this->conn = new mysqli($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname);
		if (mysqli_connect_errno()) throw_exception(mysqli_connect_error());
		if($this->version() > '4.1'){
			$serverset = $this->charset ? "SET NAMES ".$this->charset : '';
			$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',')." sql_mode='' ") : '';
			$this->conn->query($serverset);
		}
		return $this->connid;
	}
//------------------�л����ݿ�-----------------------------
	public function select_db($dbname = ''){
		$dbname = $dbname==''?$this->dbname:$dbname;
		if($this->conn->select_db($dbname)){
			return true;	
		}else{
			return false;	
		}
	}
	public function select_table($table_name){
		$this->table_name = $this->dbpre.$table_name;
	}
//------------------query-----------------------------
  function query($sql){
        if(!is_object($this->conn)){
            $this->connect();
        }
        $start_time = $this->time_used();
        $func = $this->unbuffered && function_exists("mysqli_multi_query") ? "mysqli_multi_query" : "mysqli_query";
        $this->result = @$func($this->conn,$sql);
        if(!$this->result){
			$this->show_error($this->conn->error,$sql);
            return false;
        }else{
        	$this->recordOneResult($start_time);
        	return $this->result;
		}
    }
//------------------stmtģʽ��query-----------------------------
	public function stmt_query(&$sql='',&$data=''){
		$start_time = $this->time_used();
		$stmt = $this->conn->stmt_init();
		$stmt->prepare($sql);
		$bind_params_r = array();
		// insert update ����
		if(is_array($data)){
			if($this->_parameters!=''){
				$_where_arr = explode(",",$this->_parameters);
			}
			$bind_params_r[] = $this->paramType.$_where_arr[0]; // param && where ������ iis
			// param Ԥ������ֵ
			foreach($data as $key=>$d){
				$bind_params_r[] = $d;
			}
			if($this->_parameters!=''){
				// where Ԥ������ֵ
				$_where_len = count($_where_arr);
				for($nn=1;$nn<$_where_len;$nn++){
					$bind_params_r[] = $_where_arr[$nn];	
				}
			}
			call_user_func_array(array($stmt,"bind_param"), self::refValues($bind_params_r));
		}else{
		// ��������
			if($this->_parameters!=''){
				$bind_params_r = explode(",",$this->_parameters);
				call_user_func_array(array($stmt,"bind_param"), self::refValues($bind_params_r));
			}
		}
		if($stmt->execute()){
			$this->recordOneResult($start_time);
			if(!is_array($data)){ // ��ѯʱ��
				return $stmt;
			}else{
				return true;	
			}
		}else{
			$this->show_error($stmt->error,$sql);
			return false;
		}
		$stmt->close();
	}
//------------------������ѯ-----------------------------
	public function get_all($sql='', $primary=''){
		$result = $this->safe_type==0?$this->query($sql):$this->stmt_query($sql);
		if(!$result) return false;
		$start_time = $this->time_used();
		$rlt = array();
		if($this->safe_type==1){
			$result = $result->get_result();	
		}
		while($rows = $result->fetch_array($this->rs_type)){
			if($primary && $rows[$primary]){
				$rlt[$rows[$primary]] = $rows;
			}else{
				$rlt[] = $rows;
			}
		}
		$end_time = $this->time_used();
		$this->query_times += round($end_time - $start_time,5); // �ۼӲ�ѯʱ��
		return (!empty($rlt) ? $rlt : false);
	}
	// ���ݾɵĳ���
	public function select($sql='',$primary=''){
		return $this->get_all($sql,$primary);
	}
//------------------ һ�������ѯ-----------------------------
	public function get_one($sql=''){
        $result = $this->safe_type==0?$this->query($sql):$this->stmt_query($sql);
        if(!$result) return false;
		$start_time = $this->time_used();
		if($this->safe_type==1){
			$result = $result->get_result();	
		}
        $rows = mysqli_fetch_array($result,$this->rs_type);
        $end_time = $this->time_used();
        $this->query_times += round($end_time - $start_time,5);#[��ѯʱ��]
        return $rows;
	}
	
//------------------�߼���ѯ��ʽ-----------------------------
	public function fetch(){
		$this->_field = $this->_field==''?'*':$this->_field;
		$where = $this->_where?' where '.$this->_where:'';
		$limit = $this->_limit?' limit '.$this->_limit:'';
		$order = $this->_order?' order by '.$this->_order:'';
		$sql = "select ".$this->_field." from ".$this->table_name." ".$where.$order.$limit;
		return $this->get_one($sql);
	}
	public function fetch_all(){
		$this->_field = $this->_field==''?'*':$this->_field;
		$where = $this->_where?' where '.$this->_where:'';
		$limit = $this->_limit?' limit '.$this->_limit:'';
		$order = $this->_order?' order by '.$this->_order:'';
		$primary = $this->_primary?$this->_primary:'';
		$sql = "select ".$this->_field." from ".$this->table_name." ".$where.$order.$limit;
		return $this->get_all($sql,$primary);
	}
//------------------ɾ��-----------------------------
	public function delete(){
		$where = $this->_where?' where '.$this->_where:'';
		$order = $this->_order?' order by '.$this->_order:'';
		$limit = $this->_limit?' limit '.$this->_limit:'';
		$sql = "delete from ".$this->table_name." ".$where.$order.$limit;
		// ��ͨģʽ
		if($this->safe_type==0){
			return $this->query($sql);
		}else{
		// stmtģʽ
			$stmt = $this->stmt_query($sql);
			if($stmt->affected_rows>=1){
				return true;	
			}else{
				return false;	
			}
		}
	}
//------------------����-----------------------------
	public function insert(&$data = ''){
		if(is_array($data)){
			foreach($data as $key=>$val){
				$keys[] = $key;
				$vals[] = $val;
			}
			$sql = 'insert into '.$this->table_name.' (';
			foreach($keys as $key){
				$sql .= '`'.$key.'`,';
			}
			$sql = trim($sql ,',').') values (';
			// stmt ģʽ
			if($this->safe_type==1){
				foreach($vals as $val){
					$sql .= "?,";
				}
				$sql = trim($sql ,',').')';
				$this->stmt_query($sql,$data);
			}else{
			// ����ģʽ
				foreach($vals as $val){
					if(is_string($val)){
						$sql .= "'".$val."',";
					}else{
						$sql .= $val.",";
					}
				}
				$sql = trim($sql ,',').')';
				$this->result = $this->query($sql);
			}
			return $this->insert_id();
		}else{
			return false;
		}
	}
	public function stmt_insert($data){
		$this->safe_type = 1;
		$this->insert($data);
	}
//------------------����-----------------------------
	public function update(&$data = ''){
		$where = $this->_where ? ' where '. $this->_where : '';
		if(is_array($data)){
			$sql = 'update '.$this->table_name.' set ';
			// stmt ģʽ
			if($this->safe_type==1){
				foreach($data as $key=>$val){
					if(is_string($val)){
						$sql .= "`".$key."`=?,";
					}else{
						$sql .= "`".$key."`=?,";
					}
				}
				$sql = trim($sql ,',').$where;
				return $this->stmt_query($sql,$data);
			}else{
			// ����ģʽ
				foreach($data as $key=>$val){
					if(is_string($val)){
						$sql .= "`".$key."`='".$val."',";
					}else{
						$sql .= "`".$key."`=".$val.",";
					}
				}
				$sql = trim($sql ,',').$where;
				return $this->query($sql);
			}
		}else{
			return false;	
		}
	}
//------------------��׺��������-----------------------------
	public function where($sql=''){
		$this->_where = $sql;
		return $this;
	}
	public function limit($limit=''){
		$this->_limit = $limit;
		return $this;
	}
	public function fields($string=''){
		$this->_field = $string;
		return $this;
	}
	public function primary($primary=''){
		$this->_primary = $primary;	
		return $this;
	}
	public function order($order=''){
		$this->_order = $order;
		return $this;
	}
	public function parameters($param=''){
		$this->_parameters = $param;
		return $this;
	}
	public function table($table_name=''){
		$this->table_name = $this->dbpre.$table_name;
		return $this;
	}
	public function paramType($param){
		$this->paramType = $param;
		return $this;
	}
//------------------�����ID-----------------------------
	public function insert_id(){
		return $this->conn->insert_id;
	}
//------------------ÿ��sql��ɺ�ļ�¼-----------------------------
	function recordOneResult($start_time){
		$this->query_number++;
		$end_time = $this->time_used();
		$this->query_times += round($end_time - $start_time,5);// ��ѯʱ���ۼ�
		$this->_where = $this->_limit = $this->_field = $this->_order = $this->_primary = $this->_parameters = $this->paramType ='';
	}
//------------------����ʱ��-----------------------------
	function time_used(){
        $time = explode(" ",microtime());
        $used_time = $time[0] + $time[1];
        return $used_time;
    }

//------------------ע��-----------------------------
	public function close(){
		if(is_resource($this->conn)){
			return $this->conn->close();
		}else{
			return true;
		}
	}
	function __destruct() {
        $this->close();  
    }
//------------------������ʾ-----------------------------
	private function show_error($error,$sql = ''){
		if(DB_SHOW_ERROR==1){
			echo 'MYSQL_ERROR:'.$error.' on ('.$sql.')';
			exit;
		}
	}
//-------------------���ݿ�汾-----------------------------
	private function version(){
		return mysqli_get_client_version();
	}
//------------------PHP 5.3.0 BUG ����-----------------------------
	function refValues($arr){
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$refs = array();
			foreach($arr as $key => $value){
				$refs[$key] = &$arr[$key];
			}
			return $refs;
		}
		return $arr;
	}
}
?>