<?php
/**
 * 模块: 三合一
 *
 * 功能: 弹幕数据表模型
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: BarragerModel.class.php 1429 2016-12-22 12:49:15Z dingzi $
 */

namespace Client\Model;

use \HS\Model;

class BarragerModel extends Model {
    protected $autoCheckFields = false;

    public function getConfig(){
        $cache = new \HS\MemcacheRedis();
        $key = 'barrager_config';
        $config = $cache->getRedis($key);
        if(!$config) {
            $config = D('BarrageConfig')->getField('key,value', true);
            $cache->set($key, $config);
            $config['from'] = 'DB';
        }
        return $config;
    }
    public function checkBadWord($data) {
        $data['content'] = trim(strip_tags($data['content']));
        if (!$data['content']) {
            $this->error = '请输入弹幕内容！';
            return false;
        }
        if ($this->getBreakWord($data['content'])) {
            $this->error = '请注意你的发言！';
            return false;
        }
        return true;
    }

    protected function _before_insert(&$data, $options) {
        return $this->checkBadWord($data);
    }

    protected function _before_update(&$data, $options) {
        return $this->checkBadWord($data);
    }

    /**
     * 检测违禁词，不进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWord($text) {
        S(C('rdconfig'));
        $words     = S("breakword_comment");
        $words     = explode("|", $words);
        $breakWord = '';
        foreach ($words as $word) {
            if (strpos($text, $word) !== false) {
                $breakWord .= $word . "|";
            }
        }
        if ($breakWord) {
            $breakWord = mb_substr($breakWord, 0, mb_strlen($breakWord, "utf-8") - 1, "utf-8");
        }
        return $breakWord;
    }

    /**
     * 检测违禁词，进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWordS($text) {
        parent::initMemcache();
        $words     = S("breakword_comment2");
        $words     = explode("|", $words);
        $breakWord = '';
        foreach ($words as $word) {
            $matches = false;
            preg_match_all($word, $text, $matches);
            if ($matches) {
                $breakWord .= $matches[0] . "|";
            }
        }
        if ($breakWord) {
            $breakWord = mb_substr($breakWord, 0, mb_strlen($breakWord, "utf-8") - 1, "utf-8");
        }
        return $breakWord;
    }

    /**
     * 检测违禁词，进行违禁词完全对比后，再进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWordR($content) {
        $breakWord  = $this->getBreakWord(addslashes($content));
        $breakWordS = $this->getBreakWordS(addslashes($content));
        $breakWordR = "";
        if ($breakWord) {
            $breakWordR = $breakWord;
            if ($breakWordS) {
                $breakWordR .= "|" . $breakWordS;
            }
        } else {
            $breakWordR = $breakWordS;
        }
        return $breakWordR;
    }

    /**
     * 根据书号、章节ID获取一批弹幕数据
     *
     * @param mixed $bid
     * @param mixed $cid
     * @param mixed $limit
     */
    public function getData($bid, $cid, $limit = 10) {
        $map = array(
            'is_show' => 1,
            'bid' => $bid,
            'cid' => $cid,
            'dig_num' => array('GT', 0)
        );

        $tabNum  = str_pad(floor($bid / C('CHPTABLESIZE')), 2, "0", STR_PAD_LEFT);
        $tabName = "Barrage" . $tabNum;
        $model   = M($tabName);

        $digg = $model->where($map)->limit($limit)->select();
        if (!$digg) {
            $digg = array();
        }
        $limit          = 50 - count($digg);
        $map['dig_num'] = array('EQ', 0);
        $data           = $model->where($map)->limit($limit)->select();
        if (!$data) {
            $data = array();
        }
        $r      = array_merge($digg, $data);
        $result = array();
        foreach ($r as $v) {
            $result[] = array(
                'bid'              => $bid,
                'cid'              => $cid,
                'id'               => $v['id'],
                'reader_info'      => array(
                    //'avatar_thumb_url'=>'',
                    'uid'      => $v['uid'],
                    'nickname' => $v['nickname'],
                ),
                'tsukkomi_content' => $v['content'],
            );
        }
        $result = array(
            'total_num'     => count($result),
            'tsukkomi_list' => $result
        );
        return $result;
    }

}
