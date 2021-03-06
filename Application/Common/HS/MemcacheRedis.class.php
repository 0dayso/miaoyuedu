<?php
/**
* FILE_NAME :  MemcacheRedis.class.php
* 模块:HS
* 域名:
*
* 功能:memcache->redis 两层缓存基类
*
*
* @copyright Copyright (c) 2015 – www.hongshu.com
* @author fzfz@hongshu.com
* @date 2016年8月5日 上午10:29:26
* @version $Id: MemcacheRedis.class.php 1188 2016-09-19 05:37:24Z dingzi $
*/
namespace HS;

class MemcacheRedis{
    public $_memcache;
    public $_redis;

    /**
     * 构造函数
     * @param unknown $MmcConfig
     * @param unknown $RedisConfig
     */

    public function __construct(){
        try{

            $this->_memcache = new \Think\Cache\Driver\Memcache();

        }
        catch (\Think\Exception $e){

            \Think\Log::write($e->getMessage(),'ERROR');
            return false;
        }
        return $this;
    }
    /**
     * 获得redis实例
     * @return unknown|boolean|\Think\Cache\Driver\Redis
     */
    public function  getredisObj(){
        static $_redis;
        if($_redis){

            return $_redis;
        }
        else{
            try{
                $_redis = new \Think\Cache\Driver\Redis();
                $_redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
                $this->_redis = &$_redis;
            }
            catch (\Exception $e){
                \Think\Log::write($e->getMessage(),'ERROR');
                return false;
            }
            return $_redis;
        }
    }
    /**
     * 关闭时自动关闭redis,phpredis的bug
     */
    public function __destruct(){
        //$this->getredisObj()->close();
    }

    /**
     * 只写memcache
     * @param unknown $key
     * @param unknown $value
     * @param number $exp 过期时间(秒)
     * @return boolean
     */
    public function setMc($key, $value, $exp=300) {

        return $this->_memcache->set($key, $value,$exp);

    }

    /**
     * 读取memcache
     * @param unknown $key=多个key组成的数组或单个key
     * @return mixed|unknown
     */
    public function getMc($key) {

        $ret=$this->_memcache->getmemcache($key);
        return $ret;
    }

    /**
     * 删除memcache
     * @param unknown $key
     */
    public function delMc($key) {
        return $this->_memcache->rm($key);
    }

    /**
     * 批量写多个key到redis
     * @param unknown_type $key_val_arys = array($key1,$key2...)
     */
    public function msetRedis($key_val_arys){

        return $this->getredisObj()->mset($key_val_arys);
    }

   /**
    * 写入单个key到redis
    */
    public function setRedis($key, $value) {

        return $this->getredisObj()->set($key,$value);
    }

    /**
     * 读取单个(或多个)redis key
     * @param unknown $key=array|string(可以是key数组或单个key)
     * @return unknown
     */
    public function getRedis($key) {

        if(is_array($key)){

            $result = $this->getredisObj()->mget($key);

        }
        else {
            $result = $this->getredisObj()->get($key);
        }

        return $result;

    }

    /**
     * redis删除单个或多个key
     * @param unknown $key=多个key组成的数组或单个key
     */
    public function delRedis($key) {

        if(is_array($key)){
            foreach ($key as $onekey){
                $ret=$this->getredisObj()->rm($onekey);
            }
            return $ret;
        }
        else{
            return $this->getredisObj()->rm($key);
        }

    }

    /**
     * 写redis,删memcache
     * @param unknown $key
     * @param unknown $value
     * @return boolean
     */
    public function set($key, $value){
        $this->delMc($key);
        return $this->setRedis($key, $value);
    }

    /**
     * 读memcache,没有则加载redis数据到memcache 然后返回数据
     * @param unknown $key
     * @return mixed|\HS\unknown
     */
    public function get($key){
        $value = $this->getMc($key);

        if($value==""){
            $value=$this->getRedis($key);
            $this->setMc($key,$value);
        }

        return $value;
    }
    /**
     * redis批量key获取value
     * @param unknown_type $keyAry
     */
    public function getMultiRedis($keyAry){

        $value = $this->getRedis($keyAry);
        return $value;
    }

    /**
     * memcache批量key获取value,调用者要考虑mmc的特性,不要轻易调用,否则数据可能部分不存在
     * @param unknown_type $keyAry
     */
    public function getMultiMc($keyAry){
        $value = $this->getMc($keyAry);
        return $value;
    }


    /**
     * 删redis,删memcache
     * @param unknown $key
     * @return boolean
     */
    public function del($key){
        $this->delRedis($key);
        $this->delMc($key);
        return true;
    }



}