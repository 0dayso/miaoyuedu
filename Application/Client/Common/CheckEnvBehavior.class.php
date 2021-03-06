<?php
/**
 * 模块: 移动客户端
 *
 * 功能: 检测、设置系统运行环境
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: CheckEnvBehavior.class.php 1438 2016-12-29 09:46:37Z dingzi $
 */

namespace Client\Common;

use Think\Behavior;

defined('THINK_PATH') or exit();

/**
 * 检测系统环境
 */
class CheckEnvBehavior extends Behavior {
    /**
     * 进行一些初始化设置，并加载相应的文件
     * @param type $content
     */
    public function run(&$content) {
        //只对客户端模块起作用
        if (MODULE_NAME != 'Client') {
            return;
        }
        define('ROOT_URL', strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']));
        //检测来访的客户端，默认为html5
        $client = I('client', '', 'strtolower');
        if (!$client) {
            //取域名
            $domain = $_SERVER['HTTP_HOST'];
            if (in_array($domain, array_column(C('CLIENT'), 'domain'))) {
                foreach (C('CLIENT') as $k => $v) {
                    if ($v['domain'] === $domain) {
                        $client = $k;
                    }
                }
            } else {
                if (!$client = cookie('client_name')) {
                    $agent = I('server.HTTP_USER_AGENT', '', 'strtolower');
                    if (strpos($agent, 'mac os x') && !strpos($agent, 'chrome') && strpos($agent, 'safari/')) {
                        //IOS
                        $client = 'ios';
                    }
                }
            }
        }
        $client  = strtolower($client);
        $clients = C('CLIENT');
        if (!$client || !isset($clients[$client])) {
            $client = 'html5';
        }
        //兼容一下原来的站点名称设置
        if (C('CLIENT.' . $client . '.name')) {
            C('SITECONFIG.SITE_NAME', C('CLIENT.' . $client . '.name'));
        }
        if (C('CLIENT.' . $client . '.MONEY_NAME')) {
            C('SITECONFIG.MONEY_NAME', C('CLIENT.' . $client . '.MONEY_NAME'));
        }
        if (C('CLIENT.' . $client . '.EMONEY_NAME')) {
            C('SITECONFIG.EMONEY_NAME', C('CLIENT.' . $client . '.EMONEY_NAME'));
        }
        //保存一下默认的性别以及风格
        if (C('CLIENT.' . $client . '.DEFAULT_SEX')) {
            C('DEFAULT_SEX', C('CLIENT.' . $client . '.DEFAULT_SEX'));
        }
        if (C('CLIENT.' . $client . '.DEFAULT_STYLE')) {
            C('DEFAULT_STYLE', C('CLIENT.' . $client . '.DEFAULT_STYLE'));
        }
        //cookie('client_name', $client);
        $version = I('version', '', 'trim');
        if (!$version) {
            //if (!$version = cookie('client_version')) {
            $version = C('CLIENT.' . $client . '.version');
            if (is_array($version)) {
                if (isset($version['version'])) {
                    $version = $version['version'];
                } else {
                    $version = '';
                }
            }
            //}
        }
        if (!$version) {
            $version = '1.0.0';
        }
        //cookie('client_version', $version);
        define('CLIENT_NAME', $client);
        define('CLIENT_VERSION', $version);
        //设置模板的常量
        $static = C('TMPL_PARSE_STRING.__STATICURL__');
        $tk     = 'TMPL_PARSE_STRING';
        C($tk . '.__PUBLIC__', $static . '/Public/');
        C($tk . '.__JS__', $static . '/Public/' . MODULE_NAME . '/' . $client . '/' . $version . '/js');
        C($tk . '.__CSS__', $static . '/Public/' . MODULE_NAME . '/' . $client . '/' . $version . '/css');
        C($tk . '.__IMG__', $static . '/Public/' . MODULE_NAME . '/' . $client . '/' . $version . '/images');
        C($tk . '.__ROOT__', ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://' . C('CLIENT.' . $client . '.domain'));
        C('DEFAULT_V_LAYER', 'View/' . $client . '/' . $version);
        //因为Zepto中没找到设置callback的方法，所以这里特别处理一下：
        C('VAR_JSONP_HANDLER', 'callback');     //注意，这里的callback一定要与zepto的JSONP回调标志相同
        //设置默认的AJAX数据处理方式
        if ((C('VAR_JSONP_HANDLER') && isset($_GET[C('VAR_JSONP_HANDLER')])) || (C('DEFAULT_JSONP_HANDLER') && isset($_GET[C('DEFAULT_JSONP_HANDLER')]))) {
            C('DEFAULT_AJAX_RETURN', 'JSONP');
        }
        //加载客户端的一些自定义配置
        $file = APP_PATH . '/' . MODULE_NAME . '/Conf/' . CLIENT_NAME . '.php';
        if (file_exists($file)) {
            C(include $file);
        }
        //加载客户端当前版本的一些自定义配置
        $file = APP_PATH . '/' . MODULE_NAME . '/Conf/' . CLIENT_NAME . '.' . CLIENT_VERSION . '.php';
        if (file_exists($file)) {
            C(include $file);
        }
        if (canTest()) {
            header('CLIENT_NAME:' . CLIENT_NAME);
            header('CLIENT_VERSION:' . CLIENT_VERSION);
        }
    }

}
