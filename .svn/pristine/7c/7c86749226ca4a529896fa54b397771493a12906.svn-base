<?php
// +----------------------------------------------------------------------
// | 红薯网 [ Book模块下fensi模型，主要与粉丝相关的数据操作在这个类里实现 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2015 http://www.hongshu.com All rights reserved.
// +----------------------------------------------------------------------
// | Version: v2.0
// +----------------------------------------------------------------------
// | Author: jiachao <jiachao@hongshu.com>
// +----------------------------------------------------------------------
// | Date: 2015-09-08
// +----------------------------------------------------------------------
// | Last Modified by: guonong
// +----------------------------------------------------------------------
// | Last Modified time: 2016-06-01 19:20
// +----------------------------------------------------------------------

namespace Client\Model;
use HS\Model;
class FensiModel extends Model {
    /**
     * 排序获得用户粉丝积分排行
     * @param int 书号
     * @param int 取几条
     * @return array
     */
    public function oldgetBookFans($bid, $limit = 10, $source = 0) {
        return false;
        if ($source == 0) {
            $map["bid"] = $bid;
            $fensimodel = M("fensi");
            $usermodel = M("user");
            $bookgroup = C('BOOKUSERGROUP');
            $arr = $fensimodel->field("sum(jifen) as 'jifen', uid")->where($map)->group('uid')->order('sum(jifen) desc')->limit($limit)->select();
            // echo M()->getLastSql();
            for ($i = 0; $i < count($arr); $i++) {
                $map["uid"] = $arr[$i]["uid"];
                $user = $usermodel->where($map)->field("nickname,username,groupid")->find();

                $arr[$i]["nickname"] = ($user["nickname"] == "" ? $user["username"] : $user["nickname"]);
                // $arr[$i]["groupid"] = $user["groupid"];

                for ($j = 0; $j < count($bookgroup); $j++) {
                    if (intval($arr[$i]["jifen"]) >= intval($bookgroup[$j]['start'])
                        && intval($arr[$i]["jifen"]) < intval($bookgroup[$j]['end'])) {
                        $arr[$i]["groupid"] = $bookgroup[$j]["level"];
                        break;
                    }
                }
                if ($i == 0) {
                                        $usermodel = new \Client\Model\UserModel();
                    $arr[$i]["ticketcount"] = $usermodel->getUserTicketByBid($k, $bid);
                    $proarr = $usermodel->getMaxPro($k, $bid);
                    $pid = $proarr["pid"];
                      $prolist = array_merge(C('PROPERTIES')['boy'], C('PROPERTIES')['girl']);

                      for($i = 0; $i < count($prolist); $i++){
                          if($pid == $prolist[$i]['id']){
                              $curpro = $prolist[$i];
                              break;
                          }
                      }

                    $arr[$i]["maxprotitle"] = $curpro["title"];
                    $arr[$i]["maxprocount"] = $proarr["num"];
                }
                else {
                    $arr[$i]["ticketcount"] = 0;
                    $arr[$i]["maxprotitle"] = "";
                    $arr[$i]["maxprocount"] = 0;
                }
            }
            unset($fensimodel);
            unset($usermodel);
            $result = $arr;
        }
        else if($source == 1) {

               $redis = new \Think\Cache\Driver\Redis();
               $arr = $redis->HGETALL('book_fensi#'.$bid);
               arsort($arr);

               $arr = array_slice($arr, 0, $limit, true);

            //$usermodel = M("user");
            // for ($i = 0; $i < count($arr); $i++) {
            //     $map["uid"] = $arr[$i]["uid"];
            //     $user = $usermodel->where($map)->field("nickname,username,groupid")->find();

            //     $arr[$i]["nickname"] = ($user["nickname"] == "" ? $user["username"] : $user["nickname"]);
            //     $arr[$i]["groupid"] = $user["groupid"];
            // }

            $index = 0;
            $bookgroup = C('BOOKUSERGROUP');
            $usermodel = new \Client\Model\UserModel();
            foreach($arr as $k => $v){
                $map["uid"] = $k;

                $user = M('ReadUser')->where($map)->field("nickname,username")->find();

                $result[$index]["nickname"] = ($user["nickname"] == "" ? $user["username"] : $user["nickname"]);

                $result[$index]["jifen"] = $v;
                $result[$index]["uid"] = $k;

                for ($i = 0; $i < count($bookgroup); $i++) {
                    if (intval($result[$index]["jifen"]) >= intval($bookgroup[$i]['start'])
                        && intval($result[$index]["jifen"]) < intval($bookgroup[$i]['end'])) {
                        $result[$index]["groupid"] = $bookgroup[$i]["level"];
                        break;
                    }
                }

                if ($index == 0) {
                    $result[$index]["ticketcount"] = $usermodel->getUserTicketByBid($k, $bid);
                    $proarr = $usermodel->getMaxPro($k, $bid);
                    $pid = $proarr[0]["pid"];
                      $prolist = array_merge(C('PROPERTIES')['boy'], C('PROPERTIES')['girl']);

                      for($i = 0; $i < count($prolist); $i++){
                          if($pid == $prolist[$i]['id']){
                              $curpro = $prolist[$i];
                              break;
                          }
                      }

                    $result[$index]["maxprotitle"] = $curpro["name"];
                    $result[$index]["maxprocount"] = $proarr[0]["num"];
                }
                else {
                    $result[$index]["ticketcount"] = 0;
                    $result[$index]["maxprotitle"] = "";
                    $result[$index]["maxprocount"] = 0;
                }
                $index++;
            }
            unset($redis);
            unset($usermodel);
               unset($redis);
        }
        return $result;
    }

    /**
     * 获取某本书粉丝排行榜自己的排名
     *
     * @param int $bid 书号
     * @param array $user 用户信息数组
     * @return array
     */
    public function getFansRankByUid($bid, $user){
        $integral = 0;
        return false;
         $redis = new \Think\Cache\Driver\Redis();
         $arr = $redis->HGETALL('book_fensi#'.$bid);
           arsort($arr);

           $rank = 0;
           $temprank = 0;
        foreach($arr as $k => $v) {
            $temprank++;
            if (intval($k) == intval($user['uid'])) {
                $integral = $v;
                $rank = $temprank;
                break;
            }
        }


        $bookgroup = C('BOOKUSERGROUP');

        for ($i = 0; $i < count($bookgroup); $i++) {
            if (intval($integral) >= intval($bookgroup[$i]['start'])
                && intval($integral) < intval($bookgroup[$i]['end'])) {
                $groupid = $bookgroup[$i]["level"];
                break;
            }
        }
        $arr = array('rank' => $rank, 'integral' => $integral, 'groupid' => $groupid);

        unset($redis);
        return $arr;
    }


    /**
     * 增加积分
     *
     * @param int $bid 书号
     * @param int $uid 用户id
     * @param int $integral 积分值
     * @return int
     */
    public function addFansIntegral($bid, $uid, $integral){
        $integral = intval($integral);
        if($integral===0){
            //没有要进行处理的必要
            return true;
        }
        $map["uid"] = $uid;
        $usermodel = M("user");
        $ret = $usermodel->where($map)->setInc("credit", $integral);
        $map["bid"] = $bid;
        $bfModel = D('BookFans');
        $result = $bfModel->where($map)->find();
        if($result === false){
            return false;
        }elseif(!$result){
            $fensimodel = M("fensi");
            //读取用户昵称、用户名、积分、用户组
            $userinfo = $usermodel->where($map)->field("username, nickname, credit, groupid")->find();
            $nickname = ($userinfo['nickname'] == "" ? $userinfo['username'] : $userinfo['nickname']);
            $data = array();
            $data['bid'] = $bid;
            $data['uid'] = $uid;
            $data['nickname'] = $nickname;
            $data['jifen'] = $integral;
            $data['addtime'] = NOW_TIME;
            //$fensimodel->add($data);        //先保留，以便验证
            $data['jifen'] += (int)$fensimodel->where($map)->group('bid')->sum('jifen');

            $fensimodel->where($map)->delete();             //删除掉原来的记录
            unset($fensimodel);
            $data['create_time'] = NOW_TIME;
            $data['update_time'] = NOW_TIME;
            $bfModel->add($data);
        } else {
            $data = array(
                'update_time' => NOW_TIME,
                'jifen'       => $result['jifen'] + $integral
            );
            $bfModel->where($map)->save($data);
        }

        $year = date("Y");
        $month = date("m");
        $nowMonthDay = date("t");
        $monthend = strtotime($year."-".$month."-".$nowMonthDay);   //这里其实取的是月末日的前一天零点！

        $redis = new \Think\Cache\Driver\Redis();
        $redis->zIncrBy("bookrank#total", $integral, $bid);

        $redis->zIncrBy("bookrank#month", $integral, $bid);
        ///////计算月缓存时长////////
        $redis->expire("bookrank#month", $monthend - time());
        ///////计算月缓存时长结束////////

        ///////计算周缓存时长////////////
        $redis->zIncrBy("bookrank#week", $integral, $bid);

        $weekend = mktime(0, 0, 0, date("m"), date("d") - date("w")+1+7, date("Y"));
        $redis->expire("bookrank#week", $weekend - time());
        ///////计算周缓存时长结束////////////

        $redis->hIncrBy("bookfans_normal#".$bid, $uid, $integral);

        //升级
        $groups = C("USERGROUP");
        $nowgroupid = 0;
        for ($i = 1; $i <= count($groups); $i++) {
            if (intval($userinfo['credit']) >= intval($groups[$i]["higher"])
                && intval($userinfo['credit']) <= intval($groups[$i]["lower"])) {
                $nowgroupid = $groups[$i]["id"];
                break;
            }
        }
        if($nowgroupid!=$userinfo['groupid']){
            $leveldata['groupid'] = $nowgroupid;
            $levelmap = array("uid"=>$uid);
            $usermodel->where($levelmap)->save($leveldata);
        }
        unset($usermodel);
        session("groupid", $nowgroupid);
        return $ret;
    }

    /**
     * 排序获得用户粉丝积分排行
     * @param int bid 书号
     * @param int limit 取几条
     * @param int $source 取值方式，0：数据库，1：缓存
     * @return array
     */
    public function getBookFans($bid, $limit = 10, $source = 0) {
        $result = array();
        return $result;
        $_cacheLock = "_BOOK_FANS_LIST_" . $bid . '_' . $limit;  //这个是LOCK的名称外同时还是缓存的锁，书籍粉丝值默认缓存时间为1天？
        $redis = new \Think\Cache\Driver\Redis();

        //$redis->multi();
        $lock = $redis->get($_cacheLock);
        if($lock && $lock>NOW_TIME+300) {
            //有进程正在读取值,直接返回已经缓存的数据（如果有的话）
            $result = S($_cacheLock . '_DATA');
            \Think\Log::write('BID=' . $bid . ';' . $redis->get($_cacheLock) . ';' . print_r($result, 1), 'LOCK', '', LOG_PATH . 'BOOK_FANS_LIST');
            return $result;
        }
        $redis->set($_cacheLock, NOW_TIME, 300);   //保持5分钟，如果没有删除的话就过期 TODO 没有解锁？
        //$redis->exec();



        S(C('rdconfig'));
        if ($source === 1) {    //从缓存中读成取
            $result = S($_cacheLock . '_DATA');
            if ($result) {
                //读取成功，解锁返回
                $redis->rm($_cacheLock);
                return $result;
            }
        }
        //没有读取到缓存或者是直接读取原始数据
        $arr = $redis->HGETALL('book_fensi#' . $bid);
        if (!$arr) {
            //没有数据，解锁返回
            $redis->rm($_cacheLock);
            return $result;
        }
        arsort($arr);
        $arr = array_slice($arr, 0, $limit, true);

        $index = 0;
        $bookgroup = C('BOOKUSERGROUP');

        //取出所有用户ID
        $uids = implode(',', array_keys($arr));
        $map = array(
            'uid' => array('IN', $uids)
        );

        //从数据库中取出指定的用户信息
        $usermodel = M("ReadUser");
        $tmp = $usermodel->where($map)->field('uid,nickname,username')->select();
        unset($usermodel);
        $users = array();
        if ($tmp) {
            foreach ($tmp as $v) {
                $users[$v['uid']] = $v;
            }
            unset($tmp);
        } else {
            //用户数据读取失败，解锁返回
            $redis->rm($_cacheLock);
            return $result;
        }
        foreach ($arr as $k => $v) {
            if (!isset($users[$k])) {
                //数据库中已经没有此用户信息，跳过
                continue;
            }
            $user = $users[$k];
            $result[$index]["nickname"] = ($user["nickname"] == "" ? $user["username"] : $user["nickname"]);
            $result[$index]["jifen"] = $v;
            $result[$index]["uid"] = $k;
            for ($i = 0; $i < count($bookgroup); $i++) {
                if (intval($result[$index]["jifen"]) >= intval($bookgroup[$i]['start']) && intval($result[$index]["jifen"]) < intval($bookgroup[$i]['end'])) {
                    $result[$index]["groupid"] = $bookgroup[$i]["level"];
                    break;
                }
            }
            if ($index == 0) {
                $usermodel = new \Client\Model\UserModel();
                $result[$index]["ticketcount"] = $usermodel->getUserTicketByBid($k, $bid);
                $proarr = $usermodel->getMaxPro($k, $bid);
                $pid = $proarr[0]["pid"];
                $prolist = array_merge(C('PROPERTIES')['boy'], C('PROPERTIES')['girl']);

                for ($i = 0; $i < count($prolist); $i++) {
                    if ($pid == $prolist[$i]['id']) {
                        $curpro = $prolist[$i];
                        break;
                    }
                }

                $result[$index]["maxprotitle"] = $curpro["name"];
                $result[$index]["maxprocount"] = $proarr[0]["num"];
            } else {
                $result[$index]["ticketcount"] = 0;
                $result[$index]["maxprotitle"] = "";
                $result[$index]["maxprocount"] = 0;
            }
            $index++;
        }
        $redis->rm($_cacheLock);
        unset($redis);
        unset($usermodel);
        if ($result) {
            //有结果，则保存到缓存，缓存有效期为1天
            S($_cacheLock . '_DATA', $result, 24 * 60 * 60);
        }
        \Think\Log::write('BID=' . $bid . ';' . print_r($result, 1), 'RETURN', '', LOG_PATH . 'BOOK_FANS_LIST');
        return $result;
    }

}
