<?php
/**
 * 注意，这里是通用的数据库配置文件，不允许自行修改！
 */
return array(
	//数据库配置
	'DB_TYPE'               =>  'mysql',     // 数据库类型
	'DB_HOST'               =>  '10.23.106.72', // 服务器地址
	'DB_NAME'               =>  'hongshu2',          // 数据库名
	'DB_USER'               =>  'hongshu',      // 用户名
	'DB_PWD'                =>  'D3sGsd3923y4hAlgqoeu8',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  'wis_',    // 数据库表前缀
	'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
	'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
	'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
	'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
	'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
	'DB_BIND_PARAM'         =>  true, // 数据库写入数据自动参数绑定
	'DB_DEBUG'              =>  false,  // 数据库调试模式 3.2.3新增
	'DB_LITE'               =>  false,  // 数据库Lite模式 3.2.3新增
);
