<?php 
/*
 * Redis ������
 */
class MyRedis{
	var $redis;
	var $redisHost = "127.0.0.1";
	var $redisPort = "6379";
	var $redisTimeOut = 2;
	function __construct(){
		return $this->initRedis();
	}
	/*
	 * ��ʼ��Redis��������
	 */
	function initRedis(){
		try{
			$this->redis = new Redis();
			$this->redis->connect($this->redisHost,$this->redisPort,$this->redisTimeOut);
			return true;
		}catch(Exception $e){
			// �����ʼ�����
			return false;
		}
	}
	/*
	 * ѡ�����ݿ� 0��Ĭ�ϻ��棬15�Ź̶�user-send-list
	 */
	function redisSelectDb($dbNumber){
		return $this->redis->select($dbNumber);
	}
//---------------------------- STRING --------------------------------------
	/*
	 * STRING������ȡkey��ֵ
	 */
	function redisGet($key){
		return $this->redis->get($key);
	}
	
	/*
	 * STRING�����洢key��ֵ
	*/
	function redisSet($key,$value){
		return $this->redis->set($key,$value);
	}
	/*
	*  STRING����mget
	*/
	function redisMget($keys){
		if(is_array($keys)){
			return $this->redis->mGet($keys);
		}else{
			return false;	
		}
	}
//---------------------------- HASH --------------------------------------
	/*
	 * HASH������ȡkey������ֵ
	 */
	function redisHGet($key){
		return $this->redis->hGetAll($key);
	}
	
	/*
	 * HASH�����洢key��ֵ
	 * $key
	 * $data array����
	 */
	function redisHSet($key,$data){
		return $this->redis->hMset($key,$data);
	}
	/*
	 * HASH��������
	 * $key
	 * $k
	 * $step 
	 */
	function redisHIncr($key,$k,$step=1){
		return $this->redis->hIncrBy($key,$k ,$step);
	}
	
	function redisHMset($key,$array){
		return $this->redis->hMset($key,$array);
	}
	function redisHGetAll($key){
		return $this->redis->hGetAll($key);
	}
//---------------------------- LIST --------------------------------------
	/*
	 * LIST���� rPush
	 * $key
	 * $k
	 * $step 
	 * ����ֵ List ����
	 */
	function redisRPush($key,$val){
		return $this->redis->rPush($key,$val);
	}
	/*
	 * LIST���� lRange
	 * $key
	 * $start
	 * $end
	 */
	function redisLRange($key,$start,$end){
		return $this->redis->lRange($key,$start,$end);
	}
	
	function redisLSize($key){
		return $this->redis->lSize($key);	
	}
	
	function redisLRem($key,$val){
		return $this->redis->lRem($key,$val,0);	
	}
//---------------------------- OTHER --------------------------------------
	/*
	*	del ɾ������
	*	$key
	*/
	function redisDel($key){
		return $this->redis->delete($key);
	}
	
	/*
	 * setTimeout ���ù���ʱ��
	 * $key
	 * $life
	 */
	function redisTimeout($key,$life){
		return $this->redis->setTimeout($key,$life);	
	}
	
	/*
	 * keys ������������keys
	 * $param
	 *
	 */
	 function redisKeys($param=''){
	 	return $this->redis->keys($param); 
	 }
	
}
?>