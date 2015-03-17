<?php
define('BASE_PATH', '/'); //网站根目录
define("REDIS_IP","127.0.0.1");

// 数据库配置
define('DB_HOST', 'localhost'); 	//	数据库服务器主机地址
define('DB_USER', 'root'); 		//	数据库帐号
define('DB_PW', 'root');//	数据库密码
define('DB_NAME', 'bj_shop'); 		//	数据库名
define('DB_PRE', 'bm_'); 			//	数据库表前缀
define('DB_CHARSET', 'gbk'); 		//	数据库字符集
define('DB_PCONNECT','0'); 			//	0 或1，是否使用持久连接
define('DB_SHOW_ERROR',1); 			//  是否显示数据库错误

//微信接口
define('TOKEN', 'D1GIbjpYVKp3g28Id5SmDqRmEGUzNgjh');
define('WECHAT_APPID', 'wx0533b72549f278b3');
define('WECHAT_APPSECRET' , 'b0754ca5b77118a18b290d3cdfb00e35');
//路径设置
define('CACHE_PATH', BJ_ROOT.'date/cache/'); //缓存默认存储路径
define('ADS_PATH',BJ_ROOT.'Static/ads/');
define('PLUGIN_PATH',BJ_ROOT.'Lib/Plugin/');

//模板相关配置
define('TPL_ROOT', BJ_ROOT.'Tpl/'); //模板保存物理路径
define('TPL_NAME', 'Default/'); 	//当前模板方案目录
define('TPL_CSS', 'Default'); 		//当前样式目录
define('CPD_ROOT', BJ_ROOT.'Cache/Compiled/');
define('CACHE_DIR',BJ_ROOT."Cache/Caches");
define('COMPILE_DIR',BJ_ROOT."Cache/Compiled");
define('IN_BM',true);

//COOKIE
define('C_DOMAIN_AREA','/');

//LOG
define('LOG_OPEN','1');//基础日志开关
define('LOG_OPEN_ALL','1');//全部类型日志开关
define('LOGIN_LOCKED_TIME',900);
define('LOGIN_LOCKED_NUMBER',4);

//附件相关配置
define('UPLOAD_ROOT', BJ_ROOT.'uploadfile/'); //附件保存物理路径
define('UPLOAD_URL', 'uploadfile/'); //附件目录访问路径
define('BIG_IMG_SIZE','250');
define('BIG_IMG_HEIGHT','600');
define('CHARSET', 'gbk');
define('TIMEZONE', 'Etc/GMT-8');
define('AUTH_KEY', 'YUsf120sDR'); //Cookie密钥
define('PASSWORD_KEY', 'werbd567FD');
define('URL_KEY','sideRdpfvt*$6688');//url的key
define('ALLOWED_HTMLTAGS', '<a><p><br><hr><h1><h2><h3><h4><h5><h6><font><u><i><b><strong><div><span><ol><ul><li><img><table><tr><td><map>'); //前台发布信息允许的HTML标签，可防止XSS跨站攻击
?>