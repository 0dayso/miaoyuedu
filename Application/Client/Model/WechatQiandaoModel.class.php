<?php
/**
 * 模块: 微信签到送代金券
 *
 * 功能: 微信签到送券日志模型
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author $Author$
 * @version $Id: WechatQiandaoModel.class.php 801 2016-08-18 06:32:15Z guonong $
 */

namespace Client\Model;

use \HS\Model;

class WechatQiandaoModel extends Model {
    /**
     * 写入签到记录，更新用户钱包
     * @param int $uid 用户ID
     * @param int $gid 活动ID
     * @param int $value 代金券数
     */
    public function saveLog($uid = 0, $gid = 0, $value = 0) {
        $today       = date("Y-m-d", NOW_TIME);
        $is_continue = 0;
        //获取上一次签到的记录，用来计算是否连续领取
        $map         = array();
        $map['gid']  = $gid;
        $map['uid']  = $uid;

        $map['create_datetime'] = array('elt', NOW_TIME);
        $result                 = $this->where($map)->order('id DESC')->find();
        if ($result) {
            //检查最后领取的时间
            $is_continue = $result['is_continue'];
            $yestoday    = date("Y-m-d", NOW_TIME - 24 * 60 * 60);
            if ($is_continue && $yestoday > $result['create_date']) {   //时间间隔超过1天
                $is_continue = 0;
            }
        } else {
            //没有领取过
            $is_continue = 1;
        }

        //读取用户信息（金币数）
        $uModel  = new \Client\Model\UserModel();
        $usrinfo = $uModel->find($uid);
        if (!$usrinfo) {
            $this->error = '用户信息获取失败！你可能需要重新登录。';
            return false;
        }
        $money  = $usrinfo['egold'] + $value;
        //签到日志
        $data   = array(
            'gid'             => $gid,
            'uid'             => $uid,
            'username'        => $usrinfo['username'],
            'nickname'        => $usrinfo['nickname'],
            'create_date'     => $today,
            'create_datetime' => NOW_TIME,
            'is_continue'     => $is_continue,
            'before'          => $usrinfo['egold'],
            'after'           => $money,
        );
        $result = $this->token(false)->add($data);
        if ($result === FALSE) {
            return false;
        }
        //日志处理成功，更新用户账户信息
        $data = array(
            'egold' => $money
        );
        session('egold', $money);
        $uModel->where('uid=' . $uid)->save($data);   //更新用户信息
        return $is_continue;     //如果是持续领奖的，返回一个状态做后续的处理
    }

    /**
     * 获取用户是否还可以签到
     * @param int $uid 用户ID
     * @param ind $gid 活动ID
     * @param array $config 活动配置
     * @return boolean|array false:不能再领了，array(total,today)
     */
    public function getAvailable($uid = 0, $gid = 0, $config = array()) {
        $today      = date("Y-m-d");
        $map        = array();
        $map['uid'] = $uid;

        //今天领了几次
        $map['create_date'] = $today;
        $count              = $this->where($map)->count();
        if ($last_read_url      = session('_last_read_page')) {
            $jump = '<a href="' . $last_read_url . '">使用代金券，继续阅读</a>';
        } else {
            $jump = '<a href="' . url('User/index') . '">点此查看</a>';
        }

        if ($count >= $config['per_day']) {
            $this->error = '今天已经签到！<br />' . $jump . '<br />记得明天再来！';
            return false;
        }
        return array(
            'today' => $count, //今天已经领取个数
        );
    }

    /**
     * 抽奖
     * @param int $min      最小获奖金额
     * @param int $max      最大获奖金额
     */
    function getReward($min, $max) {
        $total = (max($max, $min) - min($max, $min)) * 100;
        if ($total <= 1000) {
            //如果大和小之间相关小于等10则直接去随机搞一个数就完了！
            return rand($min, $max);
        }
        $win1   = floor((1 * $total) / 100);      //如果大值给的太多，这里的1可以再调小一点
        $win2   = floor((3 * $total) / 100);
        $win3   = floor((11 * $total) / 100);
        $other  = $total - $win1 - $win2 - $win3;            //分四个名次来取值
        $return = array();
        for ($i = 0; $i < $win1; $i++) {
            $return[] = 1;
        }
        for ($j = 0; $j < $win2; $j++) {
            $return[] = 2;
        }
        for ($m = 0; $m < $win3; $m++) {
            $return[] = 3;
        }
        for ($n = 0; $n < $other; $n++) {
            $return[] = 4;
        }
        shuffle($return);
        $result  = $return[array_rand($return)];     //名次
        $step    = $total / 900;
        $rewards = array(
            '1' => $max,
            '2' => round($min + 6 * $step),
            '3' => round($min + 4 * $step),
            '4' => round($min + 2 * $step)
        );
        return rand($min, $rewards[$result]);
    }

}
