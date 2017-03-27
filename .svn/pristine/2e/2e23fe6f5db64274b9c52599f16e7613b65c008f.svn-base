<?php

namespace HS;

use Think\Behavior;

defined('THINK_PATH') or exit();

// 系统初始化
class AppInitBehavior extends Behavior {

    // 行为扩展的执行入口必须是run
    public function run(&$content) {
        global $Uploader;

        define('DS', DIRECTORY_SEPARATOR);
        //define('ROOT_PATH', dirname(dirname(dirname(__file__))) . DS);
        if (!isset($_SERVER['HTTPS']))
            $_SERVER['HTTPS'] = 'off';

        $_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF']);
        $_SERVER['basefilename'] = basename($_SERVER['PHP_SELF']);

        define('CMS_ROOT', substr($_SERVER['PHP_SELF'], 0, - strlen($_SERVER['basefilename'])));
        define('CMS_URL', strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/') + 1)));

        define('CHARSET', 'UTF-8');

        define('FUNC_PATH', COMMON_PATH . 'Common' . DS);
        $driver = C('UPLOAD_DRIVER');
        $config = C("UPLOAD_{$driver}_CONFIG");
        $Uploader = new \Think\Upload(array(), $driver, $config);
        define('A_URL', $Uploader->urlpre);
        define('A_DIR', $Uploader->rootPath);
        //检测一下不可控的意外情况
        if (!file_exists(C('TMPL_ACTION_ERROR'))) {
            exit('对不起，系统错误信息输出模板不存在！');
        }
        if (!file_exists(C('TMPL_ACTION_SUCCESS'))) {
            exit('对不起，系统提示信息模板不存在！');
        }
    }

}
