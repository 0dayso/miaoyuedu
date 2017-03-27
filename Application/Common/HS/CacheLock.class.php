<?php
/**
 * 模块: 缓存、文件锁
 *
 * 功能: 实现并发锁的控制
 *
 * @copyright Copyright (c) 2016 – www.hongshu.com
 * @author: guonong
 * @version: $Id: CacheLock.class.php 1403 2016-12-05 07:01:13Z guonong $
 */

namespace HS;

class CacheLock {

    //文件锁存放路径
    private $path = null;
    //文件句柄
    private $fp = null;
    //锁粒度,设置越大粒度越小
    private $hashNum = 10000;
    //cache key
    private $name;
    //是否存在redis标志
    private $redis = false;

    /**
     * 构造函数
     * 传入锁的存放路径，及cache key的名称，这样可以进行并发
     * @param string $name cache key
     * @param string $path 文件锁的存放目录
     */
    public function __construct($name, $path = '/dev/shm/phplock') {
        //判断是否存在redis,这里启用了redis之后可以进行内存锁提高效率
        if (extension_loaded('redis')) {
            $this->redis = new \Think\Cache\Driver\Redis();
        } else if (!$this->hasRedis) {
            if (!is_dir($path)) {
                createDir($path);
            }
            $this->path = $path . '/' . ($this->_mycrc32($name) % $this->hashNum) . '.txt';
        }

        $this->name = $name;
    }

    /**
     * 结束时检查一下，释放掉未释放的锁
     *
     */
    public function __destruct() {
        if ($this->redis) {
            if ($this->name) {
//                 $key = getCacheKey($this->name);
//                 $this->redis->delete($key);
            }
        } else {
            if ($this->fp && !is_null($this->fp)) {
                fclose($this->fp);
            }
        }
    }

    /**
     * crc32
     * crc32封装
     * @param int $string
     * @return int
     */
    private function _mycrc32($string) {
        $crc = abs(crc32($string));
        if ($crc & 0x80000000) {
            $crc ^= 0xffffffff;
            $crc += 1;
        }
        return $crc;
    }

    /**
     * 尝试加锁，成功后返回true
     */
    public function lock() {
        if ($this->redis) {
            $result = $this->redis->get($this->name);
            if ($result && $result > NOW_TIME - 300) {
                return false;
            }
            $result = $this->redis->set($this->name, NOW_TIME, 300);

            //$result = $this->redis->setnx($this->name, date('Y-m-d H:i:s'));
            //if ($result) {
            //    $this->redis->expire($this->name, 300);   //保持5分钟，如果没有删除的话就过期 TODO 没有解锁？
            //}
        } else {
            //配置目录权限可写
            $this->fp = fopen($this->path, 'w+');
            if ($this->fp === false) {
                return false;
            }
            $result = flock($this->fp, LOCK_EX);
        }
        return $result;
    }

    /**
     * 解锁
     * Enter description here ...
     */
    public function unlock() {
        $result = false;
        if ($this->redis) {
            $result = $this->redis->rm($this->name);
        } else {
            if ($this->fp !== false) {
                flock($this->fp, LOCK_UN);
                clearstatcache();
            }
            //进行关闭
            $result = fclose($this->fp);
            $this->fp = false;
        }
        $this->name = '';
        return $result;
    }

}
