<?php

// 应用入口文件
define('IN_M', true);

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', false);

//关闭生成安全文件
define('BUILD_DIR_SECURE', False);

// 定义应用目录
define('APP_PATH','./Application/');
define('BIND_MODULE', 'Client');
define('BIND_CONTROLLER', 'Thirdlogin');
define('BIND_ACTION', 'index');

// 定义应用目录
define('APP_PATH', $dir . '/Application/');

// 引入ThinkPHP入口文件
require '/data/server/www/tpresource/ThinkPHP/ThinkPHP.php';
