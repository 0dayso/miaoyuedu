<?php
/**
 * 模块: 三合一
 *
 * 功能: 点赞日志记录数据表模型
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: ZanLogsModel.class.php 1001 2016-09-02 09:53:04Z dingzi $
 */
namespace Client\Model;
use \HS\Model;
class ZanLogsModel extends Model {

    private function _checkData($data) {
		if(isset($data['comment_id']) && isset($data['reply_id'])) {
			$this->error = '点赞类型错误！';
			return false;
		}
		if(!isset($data['uid']) || !$data['uid']){
			$this->error = '点赞的用户未设置！';
		}
		if(!$data['create_time']) {
			$data['create_time'] = NOW_TIME;
		}
        return true;
    }

    protected function _before_insert(&$data, $options) {
        return $this->_checkData($data);
    }

    protected function _before_update(&$data, $options) {
        return $this->_checkData($data);
    }
    /**
     * 获取一个/一个评论的点亮数
     * @param int|array $commentids 评论id
     * 
     * @return false|array(0=>array('comment_id'=>123,'num'=>23)) 
     */
    public function getLightnumByCid($commentids){
        if(!$commentids){
            return false;
        }
        $cids = implode(',', $commentids);
        $where = array('comment_id'=>array('IN',$cids));
        $res = $this->field('comment_id,count(*) as num')->where($where)->group('comment_id')->select();
        return $res;
    }
    /**
     * 获取某个人的点亮记录
     * @param int $uid
     * @param string 要查询的字段名
     * @return false|array
     */
    public function getZanLogsByUid($uid,$field='*'){
        if(!intval($uid)){
            return false;
        }
        $logmap = array('uid'=>$uid);
        return $this->field($field)->where($logmap)->select();
    }
    /**
     * 检查某个人是否对某条记录点赞
     * @param int comment_id
     * @param int $uid
     * 
     * @return false|array
     */
    public function checkZanByUidCid($comment_id,$uid){
        if(!intval($comment_id) || !intval($uid)){
            return false;
        }
        $where = array('comment_id'=>$comment_id,'uid'=>$uid);
        $res = $this->where($where)->find();
        return $res;
    }
    /**
     * 写赞记录
     * @param int $comment_id (书评/回复的id)
     * @param int $uid
     * 
     * @return false|int
     */
    public function InsertLogByCid($comment_id,$uid){
        if(!intval($comment_id) || !intval($uid)){
            return false;
        }
        $statu = $this->checkZanByUidCid($comment_id, $uid);
        if($statu){
            return false;
        }
        $data = array('uid'=>$uid,'comment_id'=>$comment_id,'create_time'=> NOW_TIME);
        $res = $this->add($data);
        return $res;
    }
}
