----------------------------------------------------------------------------
TABLE : bm_weixin_config ΢�����ñ�
----------------------------------------------
DROP TABLE if exists `bm_weixin_config`;
CREATE TABLE `bm_weixin_config`(
	id				int(10)			UNSIGNED NOT NULL AUTO_INCREMENT,
	config_name		varchar(40)		not null default '',	
	config_cn		varchar(40)		not null default '',
	config_val		mediumtext,
	primary key `id` (id),
	index `config_name` (config_name)
)ENGINE=MyISAM DEFAULT CHARACTER SET=gbk COLLATE=gbk_chinese_ci;
----------------------------------------------------------------------------
delete from bm_weixin_config where config_name='wx_dkf';
insert into bm_weixin_config (config_name,config_cn,config_val) values ('wx_dkf','������ͷ�','0');

delete from bm_weixin_config where config_name='wx_auto_reply';
insert into bm_weixin_config (config_name,config_cn,config_val) values ('wx_auto_reply','�Զ��ظ�','1');

delete from bm_weixin_config where config_name='wx_keyword_number';
insert into bm_weixin_config (config_name,config_cn,config_val) values ('wx_keyword_number','�Զ��ظ��ؼ�����','100');

delete from bm_weixin_config where config_name='wx_welcome';
insert into bm_weixin_config (config_name,config_cn,config_val) values ('wx_welcome','æʱ�Զ��ظ�','��ӭ����');

----------------------------------------------------------------------------
TABLE : bm_weixin_reply_keywords ΢���Զ��ظ��ؼ���
----------------------------------------------
DROP TABLE if exists `bm_weixin_reply_keywords`;
CREATE TABLE `bm_weixin_reply_keywords`(
	id				int(10)			UNSIGNED NOT NULL AUTO_INCREMENT,
	key_name		varchar(100)		not null default '',	
	key_val			mediumtext,
	primary key `id` (id),
	index `key_name` (key_name)
)ENGINE=MyISAM DEFAULT CHARACTER SET=gbk COLLATE=gbk_chinese_ci;
----------------------------------------------------------------------------



----------------------------------------------------------------------------
TABLE : bm_weixin_message ΢����Ϣ��
connect_type : 
1.�Զ��ظ�
2.ת�ӿͷ�
3.��̨�ȴ�
----------------------------------------------
DROP TABLE if exists `bm_weixin_message`;
CREATE TABLE `bm_weixin_message`(
	id				int(10)		UNSIGNED NOT NULL AUTO_INCREMENT,
	from_user_name	varchar(30)	not null default '',
	to_user_name	varchar(30)	not null default '',
	create_time		int(10)		not null default 0,
	msg_type		varchar(12)	not null default '',
	content			mediumtext,
	msgid			varchar(64)	not null default '',
	reply			mediumtext,
	connect_type	tinyint(1)	not null deafult 1,
	status			tinyint(1)  not null default 0,
	primary key `id` (id),
	index `from_user_name` (from_user_name),
	index `msgid`	(msgid)
)ENGINE=MyISAM DEFAULT CHARACTER SET=gbk COLLATE=gbk_chinese_ci;
----------------------------------------------------------------------------

