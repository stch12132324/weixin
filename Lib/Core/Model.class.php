<?php
/*
 * Model
 * 
 */
class Model{
	public $db;
	public $redis;
	public function __construct(){
		
	}
	public function _init_mysql(){
		if(!is_object($this->db)){
			$this->db = LC("db_mysqli");
		}
	}
	public function _init_redis(){
		if(!is_object($this->redis)){
			$this->redis = LC("myRedis");
		}
	}
}
?>