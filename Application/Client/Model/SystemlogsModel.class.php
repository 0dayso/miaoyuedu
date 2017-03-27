<?php
/**
 * 模块: 三合一
 *
 * 功能: 点赞日志记录数据表模型
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: SystemlogsModel.class.php 1480 2017-02-07 12:25:52Z dingzi $
 */

namespace Client\Model;

use \HS\Model;

class SystemlogsModel extends Model {
    /* 自动完成项目 */
    protected $_auto = array(
        array('logtime', NOW_TIME, self::MODEL_BOTH),
        array('siteid', 'getsiteconfig', self::MODEL_BOTH, 'function', 'fromsiteid'),
        array('chgtype', 0, self::MODEL_BOTH),
        array('runprograme', 'getThinkPara', self::MODEL_BOTH, 'function')
    );

    /**
     * 添加系统日志
     * @param int $fromid 发起者UID
     * @param string $fromname 发起者名称
     * @param int $toid 被操作人UID
     * @param string $toname 被操作人名称
     * @param string $chglog 操作日志
     * @param int $id 为0则是添加记录，否则为修改记录
     * @param int $chgtype 默认0，9是串号
     * @return int 操作是否成功
     */
    public function addSyslog($fromid, $fromname = '', $toid = 0, $toname = '', $chglog = '', $id = 0, $chgtype = 0) {
        if (is_array($fromid)) {
            $data = $fromid;
            if (isset($data['id'])) {
                $id = $data['id'];
            }
        } else {
            $data             = array();
            $data["fromid"]   = $fromid;
            $data["fromname"] = $fromname;
            $data["toid"]     = $toid;
            $data["toname"]   = $toname;
            $data["chglog"]   = $chglog;
            $data['chgtype'] = $chgtype;
        }
        $result = $this->token(false)->create($data);
        if ($result) {
            if ($id == 0) {
                $ret = $this->add();
            } else {
                $ret = $this->where("logid = " . $id)->save();
            }
        } else {
            //写日志失败，记录一下错误日志
            $data = array(
                'data'  => $data,
                'error' => $this->getError()
            );
        }
        return $ret;
    }

}
