<?php
define('BASE_PATH', '/'); //��վ��Ŀ¼
define("REDIS_IP","127.0.0.1");

// ���ݿ�����
define('DB_HOST', 'localhost'); 	//	���ݿ������������ַ
define('DB_USER', 'root'); 		//	���ݿ��ʺ�
define('DB_PW', 'root');//	���ݿ�����
define('DB_NAME', 'bj_shop'); 		//	���ݿ���
define('DB_PRE', 'bm_'); 			//	���ݿ��ǰ׺
define('DB_CHARSET', 'gbk'); 		//	���ݿ��ַ���
define('DB_PCONNECT','0'); 			//	0 ��1���Ƿ�ʹ�ó־�����
define('DB_SHOW_ERROR',1); 			//  �Ƿ���ʾ���ݿ����

//΢�Žӿ�
define('TOKEN', 'D1GIbjpYVKp3g28Id5SmDqRmEGUzNgjh');
define('WECHAT_APPID', 'wx0533b72549f278b3');
define('WECHAT_APPSECRET' , 'b0754ca5b77118a18b290d3cdfb00e35');
//·������
define('CACHE_PATH', BJ_ROOT.'date/cache/'); //����Ĭ�ϴ洢·��
define('ADS_PATH',BJ_ROOT.'Static/ads/');
define('PLUGIN_PATH',BJ_ROOT.'Lib/Plugin/');

//ģ���������
define('TPL_ROOT', BJ_ROOT.'Tpl/'); //ģ�屣������·��
define('TPL_NAME', 'Default/'); 	//��ǰģ�巽��Ŀ¼
define('TPL_CSS', 'Default'); 		//��ǰ��ʽĿ¼
define('CPD_ROOT', BJ_ROOT.'Cache/Compiled/');
define('CACHE_DIR',BJ_ROOT."Cache/Caches");
define('COMPILE_DIR',BJ_ROOT."Cache/Compiled");
define('IN_BM',true);

//COOKIE
define('C_DOMAIN_AREA','/');

//LOG
define('LOG_OPEN','1');//������־����
define('LOG_OPEN_ALL','1');//ȫ��������־����
define('LOGIN_LOCKED_TIME',900);
define('LOGIN_LOCKED_NUMBER',4);

//�����������
define('UPLOAD_ROOT', BJ_ROOT.'uploadfile/'); //������������·��
define('UPLOAD_URL', 'uploadfile/'); //����Ŀ¼����·��
define('BIG_IMG_SIZE','250');
define('BIG_IMG_HEIGHT','600');
define('CHARSET', 'gbk');
define('TIMEZONE', 'Etc/GMT-8');
define('AUTH_KEY', 'YUsf120sDR'); //Cookie��Կ
define('PASSWORD_KEY', 'werbd567FD');
define('URL_KEY','sideRdpfvt*$6688');//url��key
define('ALLOWED_HTMLTAGS', '<a><p><br><hr><h1><h2><h3><h4><h5><h6><font><u><i><b><strong><div><span><ol><ul><li><img><table><tr><td><map>'); //ǰ̨������Ϣ�����HTML��ǩ���ɷ�ֹXSS��վ����
?>