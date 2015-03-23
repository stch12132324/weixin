<?php
//--消息显示页面
function adminShowMsg($msg,$gourl="-1",$onlymsg=0,$limittime=0){
	$litime = 5000;
	$func = "<script>var pgo=0;
	function JumpUrl(){
	if(pgo==0){ location='$gourl'; pgo=1; }
}\r\n";
	$rmsg = $func;
	$rmsg .= "document.write(\"<div style='width:400px;padding-top:8px;text-align:center;height:30px;border-radius:5px 5px 0 0;font-size:12px;border:1px solid #666;border-bottom:none;background:#5D5D5D;color:#FFF'><strong>提示信息</strong></div>\");\r\n";
			$rmsg .= "document.write(\"<div style='width:400px;line-height:24px;text-align:center;font-size:12px;border:1px solid #666;border-radius:0 0 5px 5px;background-color:#fff'><br/>\");\r\n";
				$rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
				$rmsg .= "document.write(\"";
				$rmsg .= "<br/><a href='".$gourl."' style='color:#454545'>如果你的浏览器没反应，请点击这里……</a>";
				$rmsg .= "<br/><br/></div>\");\r\n";
				$rmsg .= "setTimeout('JumpUrl()',$litime);";
				$rmsg .= "</script>";
				$msg  = $rmsg;
				include template('sys_msg','',"Admin");
		exit;
}
function new_htmlspecialchars($string){
	return is_array($string) ? array_map('new_htmlspecialchars', $string) : htmlspecialchars($string, ENT_QUOTES);
}

function new_addslashes($string){
	if(!is_array($string)){
		$string = str_replace("eval","",$string);
		return addslashes($string);
	}
	foreach($string as $key => $val){
		$string[$key] = new_addslashes($val);
	}
	return $string;
}

function new_stripslashes($string){
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

function filter_xss($string, $allowedtags = '', $disabledattributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload')){
	if(is_array($string)){
		foreach($string as $key => $val) $string[$key] = filter_xss($val, ALLOWED_HTMLTAGS);
	}else{
		$string = preg_replace('/\s('.implode('|', $disabledattributes).').*?([\s\>])/', '\\2', preg_replace('/<(.*?)>/ie', "'<'.preg_replace(array('/javascript:[^\"\']*/i', '/(".implode('|', $disabledattributes).")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($string, $allowedtags)));
	}
	return $string;
}
/*
@ 格式化JS
*/
function format_js($string, $isjs = 1){
	$string = addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
	return $isjs ? 'document.write("'.$string.'");' : $string;
}
if(!function_exists('image_type_to_extension')){
    function image_type_to_extension($type, $dot = true)
    {
        $e = array ( 1 => 'gif', 'jpeg', 'png', 'swf', 'psd', 'bmp' ,'tiff', 'tiff', 'jpc', 'jp2', 'jpf', 'jb2', 'swc', 'aiff', 'wbmp', 'xbm');
        $type = intval($type);
        if (!$type){
            trigger_error( 'File Type is null...', E_USER_NOTICE );
            return null;
        }
        if(!isset($e[$type])){
            trigger_error( 'Image type is wrong...', E_USER_NOTICE );
            return null;
        }
        return ($dot ? '.' : '') . $e[$type];
    }
}
/*
@ 去除换行函数
*/
function stripstr($str){
	return str_replace(array('..', "\n", "\r"), array('', '', ''), $str);
}
/*
@ 字符串切割
*/
function str_cut($string, $length, $dot = ''){
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8'){
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	}else{
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}
/*
@ 产生随机数
*/
function randomChar($length){
	$list = array_merge(range(0,9),range('A','Z'));
	for($i=0;$i<$length;$i++){
		$randnum = rand(0,35);
		$authnum .= $list[$randnum];
	}
	return $authnum;
}
/*
@ 循环创建目录
*/
function createdir($filedir){
	$dir = BJ_ROOT;
	$dirs = explode('/',$filedir);
	 foreach ($dirs as $d) {
		!empty($d) && $dir .= $d."/";
        if(!is_dir($dir)){
			$tempdir=substr($dir,0,-1);
			@mkdir($tempdir,0777);
		} 
     }
     return true;
}
/*
@ 判断email
*/
function is_email($email){
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}
/*
@ 文件下载封装函数
*/
function file_download($filepath, $filename = ''){
	if(!$filename) $filename = basename($filepath);
	$filetype = fileext($filename);
	$filesize = sprintf("%u", filesize($filepath));
	if(ob_get_length() !== false) @ob_end_clean();
	header('Pragma: public');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	header('Content-Transfer-Encoding: binary');
	header('Content-Encoding: none');
	header('Content-type: '.$filetype);
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-length: '.$filesize);
	readfile($filepath);
	exit;
}
function fileext($filename){
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}
/*
@ 获取IP地址
*/
function ip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
	{
		$ip = getenv('HTTP_CLIENT_IP');
	}
	elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
	{
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
	{
		$ip = getenv('REMOTE_ADDR');
	}
	elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : 'unknown';
}
/*
@ 运行时间
*/
function usetime(){
	$stime = explode(' ', MICROTIME_START);
	$etime = explode(' ', microtime());
	return number_format(($etime[1] + $etime[0] - $stime[1] - $stime[0]), 6);
}

/*
@ 取图片正则表达式
*/
function pic_regular($content){
    preg_match_all("/<img[^>]*src=\"(http:\/\/(.+)\/(.+)\.(jpg|gif|bmp|bnp))\"/isU", new_stripslashes($content), $img_array, PREG_PATTERN_ORDER);
    return array_unique($img_array[1]);
}
//--自动创建对象，并返回
function load($file, $dir = '', $isinit = 1){
	if(!strpos($file, '.class.php')){
		$file .= ".class.php";	
	}
	$path = BJ_ROOT.($dir ? $dir.'/' : 'include/class/').$file;
	if(!(include_once $path)) return false;
	if($isinit && strpos($file, '.class.php') !== false){
		$classname = substr($file, 0, -10);
		return new $classname();
	}
	return true;
}

//--密码密钥MD5
function password($str){
	return md5(PASSWORD_KEY.$str);
}
//--数据库结果集->单一数组
function val_to_key($array,$type,$toval){
	if(!is_array($array)) return;
	foreach ($array as $i=>$val ) {
		 $key = $val[$type]; //取出要作为key的字段 
		 $new_array[$key] = $val[$toval];
	} 
	return $new_array;
}
//--获取表单批量对象值 表单需要设定为sid
function get_ids(){
	if(is_array($_POST['sid'])){
		$ids = implode(",",$_POST['sid']);	
	}else{
		$ids = $_GET['sid'];
	}	
	return $ids;
}
function getPagination($num, $perpage, $curpage, $mpurl){
	$Paginationpage = '';
	//$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	list($mpurl) = explode(".html",$mpurl);
	//$mpurl .= '-';
	if($num > $perpage) {
		$page = 7;
		$offset = 3;
		$pages = @ceil($num / $perpage);
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $curpage + $page - $offset - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curpage - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}
		$Previous = $curpage-1;
		$Nextpage = $curpage+1;
		$Paginationpage = ($curpage - $offset > 1 && $pages > $page ? '<span><a href="'.$mpurl.'-page-1.html"  >第一页</a></span>' : '').($curpage > 1? '<span><a href="'.$mpurl.'-page-'.$Previous.'.html" >上一页</a></span>' : '');
		for($i = $from; $i <= $to; $i++) {
			$Paginationpage .= $i == $curpage ? '<span class="active"><a>&nbsp;'.$i.'</a></span>' : '<span><a href="'.$mpurl.'-page-'.$i.'.html" >'.$i.'</a></span>';
		}
		$Paginationpage .= ($curpage < $pages ? '<span><a href="'.$mpurl.'-page-'.$Nextpage.'.html"  >下一页</a></span>' : '').($to < $pages ? '<span><a href="'.$mpurl.'-page-'.$pages.'.html"  >最后一页</a></span>' : '');
		$Paginationpage = $Paginationpage ? '<div class="pagination" style="width:100%;margin:0 auto;text-align:center;"><ul>'.$Paginationpage.'</ul></div>' : '';
	}
	return $Paginationpage;
}
//--注销数组中空值
function array_remove_empty($arr){
    $narr = array();
    while(list($key, $val) = each($arr)){
        if (is_array($val)){
            $val = array_remove_empty($val);
            if (count($val)!=0){
                $narr[$key] = $val;
            }
        }
        else {
            if (trim($val) != ""){
                $narr[$key] = $val;
            }
        }
    }
    unset($arr);
    return $narr;
}
//--
function getNoTypeFileName($url){
	if($url=="/"){
		$url = "/index.html";	
	}
	$urlArray = explode("/",$url);
	$url_last = $urlArray[1];
	$url_last = explode(".",$url_last);
	return $url_last[0]; 
}
//--
function fckcreate($name,$content=""){
	return '<script src="/Static/ckeditor/ckeditor.js"></script><textarea class="ckeditor" name="'.$name.'">'.$content.'</textarea>';
}
//-- 获取最后的query_string 参数
function getLastQueryString(){
	$_queryString = $_SERVER['QUERY_STRING'];
	$_queryString = explode("&",$_queryString);
	$_queryString = array_pop($_queryString);
	return $_queryString;
}
//-- 获取视图页面url
function getViewUrl(){
	$url = $_SERVER['PHP_SELF'];
	return substr($url,10);
}
//-- 框架的ID加密
//-- 空间地址混淆
function getEncode($string , $type="encode"){
	if($type=='encode'){
		return  'Dep'.trim(base64_encode($string),'==').'fpt';
	}else{
		$string = substr($string,3);
		$string = substr($string,0,-3);
		return base64_decode($string."==");	
	}
}
//-- 创建token
function makeToken(){
	$code = base64_encode(randomChar(40).time());
	return trim($code,"==");
}
//-- 判断移动端
function checkMobile() {
	//if (isset($_SERVER['HTTP_VIA'])) return true;
	//if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) return true;
	if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) return true;
	if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0) {
		// Check whether the browser/gateway says it accepts WML.
		$br = "WML";
	} else {
		$browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
		if(empty($browser)) return true;
		$mobile_os_list		=	array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list 	=	array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
		$found_mobile		=	checkSubstrs($mobile_os_list,$browser) ||
		checkSubstrs($mobile_token_list,$browser);
		if($found_mobile)
			$br ="WML";
		else $br = "WWW";
	}
	if($br == "WML") {
		return true;
	} else {
		return false;
	}
}

function checkSubstrs($list,$str){
	$flag = false;
	for($i=0;$i<count($list);$i++){
		if(strpos($str,$list[$i]) > 0){
			$flag = true;
			break;
		}
	}
	return $flag;
}

function array_iconv($string = array() ,$inchar = '' , $outchar = ''){
	$new_array = array();
	if(is_array($string)){
		foreach($string as $key=>$arr){
			$new_array[$key] = array_iconv($arr , $inchar , $outchar);
		}
	}else{
		return iconv($inchar , $outchar.'//TRANSLIT//IGNORE' , $string );
	}
	return $new_array;
}

function object_to_array($obj){
	$_arr = is_object($obj)? get_object_vars($obj) : $obj;
	foreach ($_arr as $key => $val) {
		$val = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	return $arr;
}
?>