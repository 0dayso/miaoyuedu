<?php
/**
 * 模块: 插画
 *
 * 功能: 插画
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: ChahuaPicModel.class.php 30 2016-07-13 11:48:56Z guonong $
 */
namespace Client\Model;
use \HS\Model;
class ChahuaPicModel extends Model {

    /**
     * 根据小说ID获取对应的表名（分表方法与章节相同）
     * @param type $bid
     */
    public function _getTableName($bid) {
        $tabName = "__CHAHUA_PIC__" . $this->_getSubTable($bid);
        return $tabName;
    }

    /**
     * 查询结果集处理
     * @param array $results
     * @param array $options
     */
    protected function _after_select(&$results, $options){
        foreach($results as &$result){
            $this->_after_find($result, $options);
        }
    }
    /**
     * 查询结果处理
     * @param unknown $result
     * @param unknown $options
     * @return array
     *              thumb: 列表用图片
     *              orig: 原始图片
     *              big: PC端显示图片
     *              mob: 移动端显示图片
     */
    protected function _after_find(&$result, $options){
        if($result['id'] && $result['bid']){
            $id = $result['id'];
            $result['picurl_thumb'] = $this->getUrl($result['bid'], $id, 'thumb');
            $result['picurl_orig'] = $this->getUrl($result['bid'], $id, 'orig');
            $result['picurl_big'] = $this->getUrl($result['bid'], $id, 'big');
            $result['picurl_mob'] = $this->getUrl($result['bid'], $id, 'mob');
        }
    }
    /**
     * 计算插画路径
     *
     * @param int $bid
     */
    public function getPath($bid = 0){
        if($bid){
            $path = C('BOOKFACE_ROOT'). "/" . floor($bid / 10000) . '/' . floor(($bid % 10000) / 100) . "/" . $bid .'/chahua/';
            if(!is_dir($path)) {
                mDir($path);
            }
        } else {
            $this->error = '参数错误';
            return false;
        }
        return $path;
    }

    public function getUrl($bid, $id, $type = 'orig') {
        if($bid && $id){
            $path = ($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').':'.C('BOOKFACE_URL'). "/" . floor($bid / 10000) . '/' . floor(($bid % 10000) / 100) . "/" . $bid .'/chahua/';
        } else {
            $this->error = '参数错误';
            return false;
        }
        return $path.$id.'_'.$type.'.jpg';
    }
}
