<?php
/**
* FILE_NAME :  Qqfangdao.class.php   
* 模块:HS
* 域名:
*
* 功能:qq防盗版基类
*
* 
* @copyright Copyright (c) 2015 – www.hongshu.com
* @author fzfz@hongshu.com
* @date 2016年10月28日 下午2:42:57
* @version $Id: $
*/

namespace HS;

class Qqfangdao extends MemcacheRedis {
    private $is_send_data;//是否发送到redis数据的开关
    private $send_to_redis_key;//发送到的reids的key
    public function __construct(){
        $is_send_data_key = 'txtxiaoshuo:qqfd:issendlogs';
        $this->send_to_redis_key = 'txtxiaoshuo:queue:qqfd';
        $this->is_send_data = $this->getRedis($is_send_data_key);
        parent::__construct();
    }
    /**
     * 向redis list队列中添加一条数据
     */
    public function postDyData($uid){
        if(!$this->is_send_data){
            return;
        }
        //TODO:用户ip
        $dat['ip'] = get_client_ip();
        $dat['host'] = $_SERVER['HTTP_HOST'];
        //print_r($_SERVER);
        $url = $this->getrefererUrl($_SERVER['REQUEST_URI']);
        $prehead = $_SERVER['REQUEST_METHOD']."\r\n";
        $protocol = $_SERVER['HTTTPS'] == 'on'?'https://':'http://';
        $headurl = $url;
        if(substr($url,-1) == '&'){
            $headurl = substr($url,0,-1);
        }
        $prehead .= $protocol.$_SERVER['HTTP_HOST'].'/'.$headurl."\r\n".$_SERVER['SERVER_PROTOCOL']."\r\n";
        $dat['head'] = $prehead.implode("\r\n", $this->fetchClientHeaders());
        $dat['method'] = $_SERVER['REQUEST_METHOD'];
        if(strpos($url,'?') > 0){
            $dat['cgi'] = substr($url,0,strpos($url,'?'));
        }else{
            $dat['cgi'] = $url;
        }
        $dat['params'] = $this->getParams();
        //$ref = 'http://android.client.hongshu.com/Book/buyviplist.do?action=orderform&bid=75217&chpid=11694513&P30=3903862hVlsCNsQuWqJqMhYgDuLXxqMadKZ%2FVNR3iJPB%2FcuqP7h78Xncv2ZycnYVaPKV3vetYYDPwC8VJDNxWBNAd9s6&P31=nan&P30=3903862hVlsCNsQuWqJqMhYgDuLXxqMadKZ%2FVNR3iJPB%2FcuqP7h78Xncv2ZycnYVaPKV3vetYYDPwC8VJDNxWBNAd9s6&P31=nan';
        $dat['referer'] = $this->getrefererUrl($_SERVER['HTTP_REFERER']);
        $dat['agent'] = $_SERVER['HTTP_USER_AGENT'];
        $dat['req_time'] = date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']);
        //TODO:用户uid
        $dat['uid'] = (string)$uid;
        $result = $this->getredisObj()->rpush($this->send_to_redis_key,$dat);
//         $dat['res'] = $result;
//         \Think\Log::write(print_r($dat, 1), 'INFO', '', LOG_PATH . 'FANGDAO_DATA');
        $this->getredisObj()->close();
        return $result;
        
    }

    /**
     * 获得redis实例
     * @return unknown|boolean|\Think\Cache\Driver\Redis
     */
    public function  getredisObj(){
        static $_handler;
        $options = array_merge(array(
            'host'       => C('rdconfig.host') ? : '127.0.0.1',
            'port'       => C('rdconfig.port') ? : 6379,
            'timeout'    => C('rdconfig.expire') ? : false,
            'persistent' => false,
        ), array());
        $this->options = $options;
        $this->options['expire'] = isset($options['expire']) ? $options['expire'] : C('DATA_CACHE_TIME');
        $this->options['prefix'] = isset($options['prefix']) ? $options['prefix'] : C('DATA_CACHE_PREFIX');
        $this->options['length'] = isset($options['length']) ? $options['length'] : 0;
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        if (!$_handler) {
            //echo 'i init<br>';
            $_handler = new \Redis;
            $options['timeout'] === false ?
            $_handler->$func($options['host'], $options['port']) :
            $_handler->$func($options['host'], $options['port'], $options['timeout']);
            $_handler->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
            if(!$_handler){
                E("redis server has gone way");
            }
        }
        return $_handler;
    }
    
    public function unparse_url($parsed_url) {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
    public function getrefererUrl($url){
        $cant_send_key = array('P30','P27','P28','P29','COOKIE','FROMSID','CLIENT');
        $ret = parse_url($url);
        if($ret['query']){
            $res = '';
            $tmp = explode('&',$ret['query']);
            if($tmp){
                //print_r($tmp);
                foreach ($tmp as $v1){
                    $tmp1 = explode('=',$v1);
                    $k = $tmp1[0];
                    $v = $tmp1[1];
                    //print_r($tmp1);
                    if(!in_array(strtoupper($k),$cant_send_key)){
                        $res .= $k.'='.$v.'&';
                    }
                    else{
                        $res .= $k.'=###';
                    }
                }
                $ret['query'] = $res;
            }
        }
    
        return $this->unparse_url($ret);
    }
    
    public function getParams(){
        $res = '';
        $cant_send_key = array('P30','P27','P28','P29','COOKIE','FROMSID','CLIENT');
        parse_str($_SERVER['QUERY_STRING'],$querys);
        foreach($querys as $key => $vo){
            if (in_array(strtoupper($key), $cant_send_key)){
                $res .= $key.'=###&';
            }else{
                $res .= $key.'='.$vo.'&';
            }
        }
        if(substr($res,-1) == '&'){
            $res = substr($res,0,-1);
        }
        return $res;
    }
    
    
    protected function fetchClientHeaders(){
    	$headers = array();
    	$cant_send_key = array('HOST','COOKIE','X-FORWARDED-FOR','X-REAL-FORWARDED-FOR','X-CONNECTING-IP','REFERER','USER-AGENT');
    	foreach($_SERVER as $h=>$v){
    	if(ereg('HTTP_(.+)',$h,$hp))
    	   $head_name = str_replace('_','-',$hp[1]);
    	//print_r($head_name);
    	   if($head_name==''){ 
    	       continue;
    	   }elseif(in_array(strtoupper($head_name), $cant_send_key)){
    	       $headers[]=$head_name.':###';
    	       
    	       continue;
    	   }
    	   $headers[]=$head_name.':'.$v;
    	}
    	return $headers;
    }
}