<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户相关
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: BookModel.class.php 1556 2017-03-13 07:50:30Z changliu $
 */

namespace Client\Model;

use HS\Model;

class BookModel extends Model {
    /**
     * 获得章节内容部分
     *
     * @param int 书号
     * @param int 章节id
     * @return string
     */
    public function getChapterContent($bid, $chpid) {
        $bookFilepath = C('CONTENT_ROOT') . '/' . $this->getBookStaticFilepath($bid) . '/' . $chpid . '.txt';
        if (file_exists($bookFilepath)) {
            $content = read($bookFilepath);
            $content = abacaAddslashes($content);
            //清掉一个特殊字符
            $content = str_replace(chr(0xE2).chr(0x80).chr(0xA8), '',$content);
            //如果客户端是IOS和安卓则原样返回
            if (defined('CLIENT_NAME') && (constant('CLIENT_NAME') === 'ios' || constant('CLIENT_NAME') === 'android')) {
                return $content;
            }
//            $content = preg_replace_callback('@</p>@iS', function($matches) {
//                        $tags = array('i', 'strong', 'span', 'font'); //标签混淆
//                        $styles = array('display:none', 'font-size:0px;'); //样式混淆
//                        $tag = $tags[mt_rand(0, 3)];
//                        $style = $styles[mt_rand(0, 1)];
//                        return '<' . $tag . ' style=\'' . $style . '\'>' . randomstr(10, '~!@#$%^&*()_+{}|:",.?/\';\\][=-') . '</' . $tag . '>' . $matches[0];
//                    }, $content) . '<span style="display:none;">' . randomstr(10, '~!@#$%^&*()_+{}|:",.?/\';\\][=-') . '</span>';
            if (session('uid')) {
                $content = trim($content);
                $result  = preg_match_all('@<p[^>]*>.+?</p>[^<]*@is', $content, $matches);
                if ($result) {
                    $c       = $matches[0];
                    //if(implode('', $c)==$content){
                    $key     = array_rand($c);
                    $c[$key] = str_replace('</p>', encode10to64(session('uid')) . '</p>', $c[$key]);
                    $content = implode('', $c);
                    //}
                }
            }
            return $content;
        } else {
            $result = '';
            try {
//                 $client = new \Yar_Client(C('RPCURL') . "/chapter.php");
                $client = new \HS\Yar("chapter");
                $result = $client->getChapterContent($bid, $chpid);
            }
            catch (\Yar_Server_Exception $e) {
                //接口访问出错，则直接返回空的
                $result = '';
            }
            catch (\Exception $e) {
                //未知的异常
                $result = '';
            }
            if (strlen($result) > 10) {
                return $result;
            }
            return '章节内容不存在!';
        }
    }

    /**
     * 取章节缓存
     */
    public function getChapter($bid, $source = 0) {
        if ($source == 0) {
            parent::initMemcache();
            $array = S('chpt_normal#' . $bid);
        }
        return $array;
    }

    public function getChapterPrice($bid, $nobuy_vipchparys, $user, $fromSite = 1) {
        if (!($user && is_array($user))) {
            $viplevel = 1;
        } else {
            if ($user['viplevel'] == "0") {
                $viplevel = 1;
            } else {
                $viplevel = $user["viplevel"];
            }
        }

        //根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        $pricebeishu      = 250;
//         $client = new \Yar_Client(C('RPCURL') . "/discountset.php");
        $client           = new \HS\Yar("discountset");
        $result           = $client->getDiscountCustomXianmianStatus($bid, $viplevel, $fromSite);

        $discount_set     = $result['discount_set'];
        $is_discount      = $discount_set['is_open'];
        $is_bookdiscount  = $discount_set['is_bookdiscount'];
        $custom_price_set = $result['custom_price_set'];
        $xianmian_set     = $result['xianmian_set'];
        $pricebeishu      = $result['pricebeishu'];
        unset($result);
        unset($client);


        $need_totalmoney = 0;
        $is_buyall       = false;
        if ($discount_set['is_bookdiscount'] && $is_buyall) {
            $is_use_bookdisoucnt = true;
        }
        if ($discount_set && 1 == $discount_set['is_open']) {
            $is_discount = true;
        }

        for ($i = 0; $i < count($nobuy_vipchparys); $i++) {
            $temp_totalmoney = floor($nobuy_vipchparys[$i]['charnum'] / $pricebeishu);

            if ($is_use_bookdisoucnt) {
                if ($discount_set && $discount_set['book_discount']) {
                    $tmpprice = ceil($tmpprice * ($discount_set['book_discount'] / 10));  // 10是折扣基准
                } else {
                    $tmpprice = 0;
                }

                if ($tmpprice > 0) {
                    $temp_totalmoney = $tmpprice;
                }
            } elseif ($is_discount) {
                if ($discount_set) {
                    $tmpprice = ceil($tmpprice * ($discount_set['discount'] / 10));
                } else {
                    $tmpprice = 0;
                }
                if ($tmpprice > 0) {
                    $temp_totalmoney = $tmpprice;
                }
            }

            $is_chpt_free = false;
            //是否是限免章节
            if ($xianmian_set) {
                if (!$xianmian_set || !$chapterid) {
                    $is_chpt_free = false;
                }
                if ($xianmian_set['freetype'] == 2) {//1=全书免费 2=免N章
                    if (!$xianmian_set['free_chapterid']) {
                        $is_chpt_free = false;
                    }
                    $freechptary = explode("|", $xianmian_set['free_chapterid']);

                    if (in_array($chapterid, $freechptary)) {
                        $is_chpt_free = true;
                    } else {
                        $is_chpt_free = false;
                    }
                } else {
                    $is_chpt_free = true;
                }

                if ($is_chpt_free) {
                    $temp_totalmoney = 0;
                }
            }
            $need_totalmoney += $temp_totalmoney;
        }

        return $need_totalmoney;
    }

    public function getBookPrice($bid, $user) {
        if (!($user && is_array($user))) {
            $viplevel = 1;
        } else {
            if ($user['viplevel'] == "0") {
                $viplevel = 1;
            } else {
                $viplevel = $user["viplevel"];
            }
        }
        $map['isvip']       = 1;
        $map['ispublisher'] = "1";
        $map['bid']         = $bid;

        $chpmodel   = M($this->getChpTableName($bid));
        $array      = $chpmodel->where($map)->field("chapterid,juanid,bid,title,isvip,charnum,publishtime")
                ->order("chporder DESC, chapterid asc")->select();
        unset($chpmodel);
        $totalprice = $this->getChapterPrice($bid, $array, $user);
        return $totalprice;
    }

    /**
     * 获得书
     *
     * @param int 书号
     * @param int 来源 0：缓存 1：数据库
     * @return array
     */
    public function getBook($bid, $source = 0) {
        if ($bid && is_numeric($bid)) {
            $array = array();
            if (!$source) {
                parent::initMemcache();
                $array = S("book_normal#" . $bid);
            }
            if ($source || !$array) {
                $map["bid"] = $bid;
                $bookmodel  = M("Book");
                $array      = $bookmodel->where($map)->find();
                unset($bookmodel);
            }
        } else {
            $array = null;
        }
        return $array;
    }

    /**
     * 添加收藏
     *
     * @param int 用户id
     * @param int 书号
     * @param string 书签
     * @param int 类别id
     * @return int
     */
    public function addFav($user, $bid, $bookmark, $categoryid) {
        $data["uid"]         = $user["uid"];
        $data["bid"]         = $bid;
        $data["bookmark"]    = $bookmark;
        $data["category_id"] = $categoryid;
        $favmodel            = M("Fav");
        $ret                 = $favmodel->add($data);
        if ($ret) {
            $map['bid'] = $bid;
            $bookmodel  = M("book");
            //$bookmodel->where($map)->setInc("total_fav", 1);
            //$bookmodel->where($map)->setInc("month_fav", 1);
            //$bookmodel->where($map)->setInc("week_fav", 1);
            $data       = array(
                'total_fav' => array('exp', 'total_fav+1'),
                'month_fav' => array('exp', 'month_fav+1'),
                'week_fav'  => array('exp', 'week_fav+1')
            );
            $bookmodel->where($map)->save($data);
            unset($bookmodel);
            $this->addBooktrend($bid, 2, $user, 0);
        }
        unset($favmodel);
        return $ret;
    }

    /**
     * 取消收藏
     *
     * @param int 用户id
     * @param int 书号
     * @return int
     */
    public function delFav($uid, $bid) {
        $map["uid"] = $uid;
        $map["bid"] = $bid;
        $favmodel   = M("Fav");
        $ret        = M("Fav")->where($map)->delete();
        unset($favmodel);
        if ($ret) {
            $map2['bid'] = $bid;
            $bookmodel   = M("book");
            //2016-06-20  by dingzi,week_fav/month_fav/total_fav是否为0
            $bookinfo    = $bookmodel->where($map2)->find();
            if ($bookinfo) {
                $data = array();
                if ($bookinfo['total_fav'] >= 1) {
                    //$bookmodel->where($map2)->setDec("total_fav", 1);
                    $data['total_fav'] = $bookinfo['total_fav'] - 1;
                }
                if ($bookinfo['month_fav'] >= 1) {
                    //$bookmodel->where($map2)->setDec("month_fav", 1);
                    $data['month_fav'] = $bookinfo['total_fav'] - 1;
                }
                if ($bookinfo['week_fav'] >= 1) {
                    //$bookmodel->where($map2)->setDec("week_fav", 1);
                    $data['week_fav'] = $bookinfo['total_fav'] - 1;
                }
                if ($data) {
                    $bookmodel->where($map2)->data($data)->save();
                }
            }
            unset($bookmodel);
        }
        return $ret;
    }

    /**
     * 检查是否已经收藏
     *
     * @param int 用户id
     * @param int 书号
     * @return int
     */
    public function getFavCount($uid, $bid) {
        if ($uid) {
            $map["uid"] = $uid;
        }
        if ($bid) {
            $map["bid"] = $bid;
        }
        $favmodel = M("Fav");
        $ret      = $favmodel->where($map)->count();
        unset($favmodel);
        return $ret;
    }

    /**
     * 获取书架号
     *
     * @param int 用户id
     * @return int
     */
    public function getCategoryId($uid) {
        $map["uid"]    = $uid;
        $categorymodel = M("bookshelf_category");
        $ret           = $categorymodel->where($map)->order("category_id desc")->field('category_id')->find();
        unset($categorymodel);
        if ($ret == false) {
            $ret = "";
        }
        return $ret;
    }

    /**
     * 增加鲜花数
     *
     * @param int $bid 书号
     * @param int $num 数量
     *
     */
    public function addFlowerCount($bid, $num) {
        $map["bid"] = $bid;
        $bookmodel  = M("book");
        //$bookmodel->where($map)->setInc('total_flower', $num);
        //$bookmodel->where($map)->setInc('month_flower', $num);
        //$bookmodel->where($map)->setInc('week_flower', $num);
        $data       = array(
            'total_flower' => array('exp', 'total_flower+' . $num),
            'month_flower' => array('exp', 'month_flower+' . $num),
            'week_flower'  => array('exp', 'week_flower+' . $num)
        );
        $bookmodel->where($map)->save($data);
        // $book = $this->getBook($bid);
        // $book['total_flower'] = $book['total_flower'] + $num;
        // $book['month_flower'] = $book['month_flower'] + $num;
        // $book['week_flower'] = $book['week_flower'] + $num;
        // $cachemodel = D('Cache');
        // $cachemodel->set("book_normal#".$bid, $book);
        // unset($cachemodel);
        unset($bookmodel);
    }

    /**
     * 更新某本书的本书动态，每本书的记录数限制500个
     * @param int 书号
     * @param int 1：鲜花 2：收藏 3：评论 4：回复评论 5：阅读免费章节 6：阅读VIP章节 7:道具 8:红票
     * @param array 用户信息
     * @param int $num
     * @param string $addtime
     * @return string
     */
    function addBooktrend($bid, $type, $user, $num, $pro = '') {
        $book = $this->getBook($bid);
        parent::initRedis();
        $arr  = S('booktrend#' . $bid);
        if (count($arr) >= 500) {
            array_pop($arr);
        }
        $newTrend = array('bid'      => $bid, 'bookname' => $book["catename"], 'type'     => $type,
            'uid'      => $user['uid'], 'nickname' => ($user['nickname'] == "" ? $user['username'] : $user['nickname']), 'num'      => $num, 'addtime'  => time(), 'pro'      => $pro);
        if ($arr && is_array($arr)) {
            array_unshift($arr, $newTrend);
        } else {
            $arr = array(0 => $newTrend);
        }
        S('booktrend#' . $bid, $arr);
    }

    /**
     * 获取本书动态
     *
     * @param int $bid 书号
     * @return array
     */
    function getBooktrend($bid) {
        $redis = new \Think\Cache\Driver\Redis();
        $arr   = $redis->get('booktrend#' . $bid);
        unset($redis);
        return $arr;
    }

    function incryBookFansIntegral($bid, $integral) {
        $bookmodel = D("Book");
        $book      = $bookmodel->getBook($bid);
        unset($bookmodel);
        $sex_flag  = $book["sex_flag"];

        $alltype = array('total', 'week', 'month', 'day');

        foreach ($alltype as $typename) {
            $this->incryBook($bid, $integral, $typename);
            if ($sex_flag) {
                $this->incryBook($bid, $integral, $sex_flag . '_' . $typename);
            }
        }
    }

    function incryBook($bid, $integral = 1, $type = 'total') {
        $redis = new \Think\Cache\Driver\Redis();
        $ret   = $redis->zIncrBy("bookrank#" . $type, $integral, $bid);
        if (!$ret) {
            return false;
        }
        if ($type != 'total') {
            $endtime = false;
            if (strpos($type, 'week') != false) {
                getRelativeTime(time(), 'thisweek', $starttime, $endtime);
            }
            if (strpos($type, 'month') != false) {
                getRelativeTime(time(), 'thismonth', $starttime, $endtime);
            }
            if (strpos($type, 'day') != false) {
                getRelativeTime(time(), 'thisday', $starttime, $endtime);
            }
        }
        unset($redis);
        return $ret;
    }

    /**
     * 获取本书的某道具数
     *
     * @param int $pid 道具id
     * @param int $bid 书号
     * @return array
     */
    function getPropertiesCount($pid, $bid) {
        $map["pid"] = $pid;
        $map["bid"] = $bid;
        $sum        = M("book_pro")->where($map)->sum("num");
        if (!$sum) {
            $sum = 0;
        }
        return $sum;
    }

    /**
     * 获取本书的某道具数
     *
     * @param int $pid 道具id
     * @param int $bid 书号
     * @return array
     */
    function getBooKPro($bid, $order, $limit) {
        $map["bid"] = $bid;
        $promodel   = M("book_pro");
        $usermodel  = new \Client\Model\UserModel();
        $arr        = $promodel->where($map)->order($order)->limit($limit)->select();
        for ($i = 0; $i < count($arr); $i++) {
            $arrPro              = $this->getProInfo($id                  = $arr[$i]['pid']);
            $arr[$i]["imgurl"]   = $arrPro['img'];
            $arr[$i]["nickname"] = $usermodel->getUserNickname($arr[$i]['uid']);
        }
        unset($promodel);
        return $arr;
    }

    /**
     * 获取道具信息
     *
     * @param int $id 道具id
     * @return array
     */
    public function getProInfo($id) {
        $properties = array_merge(C("PROPERTIES")['boy'], C("PROPERTIES")['girl']);
        $arr        = null;
        for ($i = 0; $i < count($properties); $i++) {
            if ($id == $properties[$i]['id']) {
                $arr = $properties[$i];
                break;
            }
        }
        return $arr;
    }

    /**
     * 获取本书的道具总数
     *
     * @param int $bid 书号
     * @return int
     */
    public function getProCount($bid) {
        $map['bid'] = $bid;
        $promodel   = M("book_pro");
        $ret        = $promodel->where($map)->sum("num");
        unset($promodel);
        return $ret;
    }

    /**
     * 获取本书的总销量排名
     *
     * @param int $sign 2：男频 3：女频
     * @param int $bid 书号
     * @return array
     */
    public function getBookRank($sign, $bid) {
        $bookmodel  = M("book");
        $map['bid'] = $bid;

        $selfsalenum = $bookmodel->where($map)->getField('salenum');

        $map['publishstatus'] = 1;

        $map['salenum'] = array('GT', $selfsalenum);

        if ($sign == 2) {
            $map['classid'] = array('NEQ', 2);
            unset($map['bid']);
            $count          = $bookmodel->where($map)->count();
        } elseif ($sign == 3) {
            $map['classid'] = 2;
            unset($map['bid']);
            $count          = $bookmodel->where($map)->count();
        }
        unset($bookmodel);
        return ($count + 1);
    }

    /**
     * 检查某用户是否已收藏某书
     *
     * @param int $uid 用户id
     * @param int $bid 书号
     * @return int 0：未收藏 1：已收藏
     */
    public function checkFav($uid, $bid) {
        $map['uid'] = $uid;
        $map['bid'] = $bid;
        $favmodel   = M("fav");
        $ret        = $favmodel->where($map)->count();
        unset($favmodel);
        return $ret;
    }

    /**
     * 获取掌阅ab类书籍
     *
     * @param int $bid 书号
     * @param int $sign 2：男频 3：女频
     * @return array
     */
    public function getOthorLook($bid, $sign) {
        parent::initMemcache();
        // S("zyothorlook".$bid, null);
        $arr = S("zyothorlook" . $bid . $sign);
        if (!($arr && is_array($arr))) {
            $where = "";
            if ($sign == 2) {
                $where = " and wis_book.classid <> 2";
            } else if ($sign == 3) {
                $where = " and wis_book.classid = 2";
            }
            // $arr = M()->query("SELECT * FROM
            // (SELECT bid, catename FROM wis_book where shouquaninfo >= 3 and publishstatus = 1 ".$where." ORDER BY (total_hit * 0.2 + salenum * 1.0 + total_flower * 0.6 + total_fav * 0.8 + total_pro * 0.4) DESC LIMIT 0, 500)
            // t ORDER BY RAND(".$bid.") LIMIT 6");

            $arr = M()->query("SELECT wis_empower4.corp_bk_name as 'catename', wis_empower4.bid FROM wis_empower4 INNER JOIN wis_book ON wis_empower4.bid = wis_book.bid WHERE wis_empower4.corp_id =10 AND wis_empower4.grade IN ( 99, 98 ) " . $where . " AND wis_book.publishstatus =1");
            S("zyothorlook" . $bid . $sign, $arr, 36000);
        }

        $result['isrollshow']   = 1;
        $result['rollshowtime'] = 900;
        $result['booklists']    = $arr;
        $rearr                  = parent::listHandle($result, 6);
        return $rearr;
    }

    /**
     * 获取某书的所有章节
     *
     * @param int $bid 书号
     * @return array
     */
    public function getChplistByBid($bid) {
        $chapter = $this->getChapter($bid); //59956多卷
        if (isset($chapter['tt_time'])) {
            unset($chapter['tt_time']);
        }
        $vipchpcount  = 0;
        $freechpcount = 0;
        $chplistindex = 0;
        $pos          = 1;
        //第一章的章节序号
        $firstchporder = $chapter[1]['chparys'][1]['chporder'];
        for ($i = 0; $i < count($chapter); $i++) {
            $chparr    = $chapter[$i]['chparys'];
            $juantitle = $chapter[$i]['juantitle'];
            for ($j = 1; $j <= count($chparr); $j++) {
                if (strtolower($chparr[$pos]["candisplay"]) == "y") {
                    $chparr[$pos]['juantitle'] = $juantitle;
                    //如果第一章的章节序号<1则所有章节序号+1
                    if($firstchporder < 1){
                        $chparr[$pos]['chporder'] += 1;
                    }
                    $chplist[$chplistindex]    = $chparr[$pos];
                    $chplistindex++;
                }
                $pos++;
                if ($chparr[$pos]["isvip"] == "1") {
                    $vipchpcount++;
                } else {
                    $freechpcount++;
                }
            }
        }
        $chpinfo['list']         = $chplist;
        $chpinfo['vipchpcount']  = $vipchpcount;
        $chpinfo['freechpcount'] = $freechpcount;
        return $chpinfo;
    }

    /**
     * 获取某书的单个章节
     *
     * @param int $bid 书号
     * @param int $cid 章节id
     * @return array
     */
    public function getChapterByCid($bid, $cid) {
        $curchp  = null;
        $chplist = $this->getChplistByBid($bid);
        for ($i = 0; $i < count($chplist['list']); $i++) {
            if ($cid == $chplist['list'][$i]['chapterid']) {
                $curchp = $chplist['list'][$i];
                break;
            }
        }
        return $curchp;
    }

    /**
     * 获取某书的首个vip章节
     *
     * @param int $bid 书号
     * @return array
     */
    public function getFirstVipChapter($bid) {
        $curchp  = null;
        $chplist = $this->getChplistByBid($bid);
        for ($i = 0; $i < count($chplist['list']); $i++) {
            if (1 == intval($chplist['list'][$i]['isvip'])) {
                $curchp = $chplist['list'][$i];
                break;
            }
        }
        return $curchp;
    }

    /**
     * 获取某书的最后一章
     *
     * @param int $bid 书号
     * @return array
     */
    public function getLastChapter($bid) {
        $curchp  = false;
        $chplist = $this->getChplistByBid($bid);
        if (count($chplist['list']) > 0) {
            $curchp = array_pop($chplist['list']); //[count($chplist['list']) - 1];
        }
        return $curchp;
    }

    /**
     * 获取某书的VIP章节数
     *
     * @param int $bid 书号
     * @return int
     */
    public function getVipChapterCount($bid) {
        $chplist = $this->getChplistByBid($bid);
        return $chplist['vipchpcount'];
    }

    private function getChpTableName($bid) {
        $indexNum = str_pad(floor($bid / intval(C('CHPTABLESIZE'))), 2, "0", STR_PAD_LEFT);
        return "chapter" . $indexNum;
    }

    /**
     * 订购章节
     *
     * @param int $bid 书号
     * @param int $chapterid 章节id
     * @param array $user 用户信息
     * @param bool $is_buyall 是否全买
     *              全买的含义为：购买当前书的所有未购买章节
     * @param int $autoorder 是否自动订阅 3：是 0：不是
     * @return string
     */
    public function orderChapter($bid, $chapterid, $user, $is_buyall, $autoorder = -1, $fromSite = 1) {
        $Model            = new Model();
        //根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        $pricebeishu      = 250;
//         $client = new \Yar_Client(C('RPCURL') . "/discountset.php");
        $client           = new \HS\Yar("discountset");
        $result           = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromSite);

        if (!$result) {
            unset($bookmodel);
            unset($Model);
            return 'orderchperror';
        }
        $discount_set     = $result['discount_set'];
        $is_discount      = $discount_set['is_open'];
        $is_bookdiscount  = $discount_set['is_bookdiscount'];
        $custom_price_set = $result['custom_price_set'];
        $xianmian_set     = $result['xianmian_set'];
        $pricebeishu      = $result['pricebeishu'];
        unset($result);
        unset($client);
        // 获得用户已订购章节ids
        $aleady_buyids    = '';
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client           = new \HS\Yar("dingoujson");
        $result           = $client->checkUserAll($bid, $user['uid'], 'ids');
        if (false === $result) {
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }
        if ($result != 'N') {
            $aleady_buyids = $result;
        }
        if ($is_buyall == false) {

            $already_arr = explode(',', $aleady_buyids);
            if (array_search($chapterid, $already_arr) != false) {
                unset($Model);
                return 'nochapterorder';
            }
        }
        //根据已订阅章节,选择的章节,自动订阅状态,是否全订,获得所有需要订阅的章节数组
//         $client = new \Yar_client(C('RPCURL') . '/dingyuechapter.php');
        $client = new \HS\Yar("dingyuechapter");
        if (!is_array($chapterid)) {
            $need_buyids = $chapterid;
        } else {
            $need_buyids = implode(',', $chapterid);
        }
        //$need_buyids = current($chapterid);
        $result = $client->getNoBuychapterArys($bid, 0, $aleady_buyids, $is_buyall, $need_buyids);

        if ($result != false) {
            $nobuy_vipchparys = $result['nobuy_vipchparys'];
            $aleady_buyarys   = $result['aleady_buyarys'];
        }
        // 没有发现需要订阅的章节
        $salenum = count($nobuy_vipchparys);
        if (!is_array($nobuy_vipchparys) || $salenum <= 0) {
            unset($Model);
            unset($result);
            unset($client);
            return 'nochapterorder';
        }
        unset($result);
        unset($client);

        $need_buyids     = $this->implodeIds($nobuy_vipchparys, 'chapterid');
        // 计算每个单章价格和总价
        $need_totalmoney = 0;
//         $client = new \Yar_Client(C('RPCURL') . "/dingyuechapter.php");
        $client          = new \HS\Yar("dingyuechapter");
        $result          = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, $is_buyall, $custom_price_set, $xianmian_set);

        if (false === $result) {
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }
        $need_totalmoney  = $result['need_totalmoney'];
        $nobuy_vipchparys = $result['nobuy_vipchparys'];

//         $client = new \Yar_Client(C('RPCURL') . "/usermoney.php");
        $client = new \HS\Yar("usermoney");
        //var_dump($nobuy_vipchparys);
        $result = $client->simulate_buychapters_egoldFirst2($user['uid'], $nobuy_vipchparys);
        // var_dump($result);
        if (false === $result) {
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }

        $usermodel               = new \Client\Model\UserModel();
        $usermoney               = $usermodel->getUserMoney($user['uid']);
        //8.2 判断金币是否够用
        $usermoney['totalmoney'] = intval($usermoney[$user['uid']]['money']) + intval($usermoney[$user['uid']]['egold']);

        if ($usermoney['totalmoney'] < intval($need_totalmoney) && intval($need_totalmoney) > 0) {
            unset($Model);
            return 'consumefail';
        }
        unset($usermodel);

        //9.1.1 模拟得出需要扣多少金币
        $need_total_money = $result['money'];
        if ($need_total_money < 0) {
            return 'consumefail';
        }
        //9.1.1 模拟得出需要扣多少egold
        $need_total_egold = $result['egold'];
        if ($need_total_egold < 0) {
            return 'consumefail';
        }
        //每章包含了monetype的模拟扣费结果
        $nobuy_vipchparys = $result['nobuy_vipchparys'];

        unset($result);
        unset($client);
        $tmp_total_money = $need_total_money + $need_total_egold;

        if ($need_totalmoney > 0) {
//             $client = new \Yar_Client(C('RPCURL') . "/usermoney.php");
            $client = new \HS\Yar("usermoney");
            $result = $client->subUserMoney($user['uid'], 'moneyAndegold', $need_total_money, $need_total_egold);

            //var_dump($result);
            if (false === $result) {
                unset($Model);
                unset($result);
                unset($client);
                return 'orderchperror';
            }

            //9.2.1 判断扣费是否成功
            if ($result['result'] != 'Y') {
                if ($result['result'] == 'N1') {
                    unset($Model);
                    return 'consumefail';
                } else {
                    unset($Model);
                    return 'orderchperror';
                }
            }
            unset($result);
            unset($client);
        }

        if ($autoorder == 3 || $autoorder == 0) {
            $this->compareAndUpdateAutoStatus($bid, $user['uid'], $user['username'], $autoorder, $fromSite);
        }
        //9.3.1 写订购关系
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client  = new \HS\Yar("dingoujson");
        $result1 = $client->chSaveAll($bid, $user['uid'], $nobuy_vipchparys);
        unset($client);

        //9.3.2 写销售记录表
//         $client = new \Yar_Client(C('RPCURL') . "/dingyuechapter.php");
        $client  = new \HS\Yar("dingyuechapter");
        $result2 = $client->add_multi_salelogs($bid, $user['uid'], $fromSite, $nobuy_vipchparys);


        // 9.3.3 网络失败或写订购关系失败或写销售记录表失败
        if (false === $result1 || $result1 != "Y" || false === $result2 || $result2 != 'Y') {
            if (false === $result1 || $result1 != 'Y') {
                $failed_chsaveall = true;
            }
            if (false === $result2 || $result2 != 'Y') {
                $failed_addsalelogs = true;
            }

            //9.3.3.1生成记录日志数组
            foreach ($nobuy_vipchparys as $chpid => $chapter) {
                //9.3.3.2限免章节不记录
                if ($chapter['is_free']) {
                    continue;
                }
                if ($chapter['moneytype'] == 1) {
                    $__money = 0;
                    $__egold = $chapter['truesaleprice'];
                } else {
                    $__egold = 0;
                    $__money = $chapter['truesaleprice'];
                }
                $fail_log_chsave_all_Arys[]  = array(
                    'uid'           => $user['uid'],
                    'bid'           => $bid,
                    'title'         => $chapter['title'],
                    'chpid'         => $chapter['chapterid'],
                    'money'         => $__money,
                    'egold'         => $__egold,
                    'addtime'       => NOW_TIME,
                    'fromSite'      => $fromSite,
                    'errordescribe' => '保存订购关系失败');
                $fail_log_addsalelogs_Arys[] = array(
                    'uid'           => $user['uid'],
                    'bid'           => $bid,
                    'title'         => $chapter['title'],
                    'chpid'         => $chapter['chapterid'],
                    'money'         => $__money,
                    'egold'         => $__egold,
                    'addtime'       => NOW_TIME,
                    'fromSite'      => $fromSite,
                    'errordescribe' => '扣费成功但没写入订阅表');
            }

            if ($failed_chsaveall && count($fail_log_addsalelogs_Arys) > 0) {
                M("usersalelogs")->addAll($fail_log_chsave_all_Arys);
            }
            if ($failed_addsalelogs && count($fail_log_addsalelogs_Arys) > 0) {
                M("usersalelogs")->addAll($fail_log_addsalelogs_Arys);
            }
            unset($Model);
            unset($fail_log_addsalelogs_Arys);
            unset($fail_log_chsave_all_Arys);
            if ($is_buyall) {
                $this->addBooktrend($bid, 10, $user, 1, '');
            } else {
                $this->addBooktrend($bid, 9, $user, 1, '');
            }
            return 'orderchperror';
        }

        unset($result1);
        unset($result2);
        unset($client);

        // $this->userSale($user['uid'], $need_totalmoney);
        $sesuser          = session();
        $sesuser['money'] = (intval($sesuser['money']) - intval($need_total_money));
        $sesuser['egold'] = (intval($sesuser['egold']) - intval($need_total_egold));
        session('money', $sesuser['money']);
        session('egold', $sesuser['egold']);

        $fensimodel = new \Client\Model\FensiModel();
        $integral   = C('INTEGRAL')['order'] * $need_total_money;
        $fensimodel->addFansIntegral($bid, $user['uid'], $integral);
        unset($fensimodel);

        //9.3.3 总销售记录
        $map["bid"] = $bid;
        M("book")->where($map)->setInc('salenum', $salenum);

        //9.3.4 章节销售记录
        $chpt_tablename = $this->getChpTableName($bid);
        $Model->execute("update wis_" . $chpt_tablename . " set salenum=salenum+1 where chapterid in (" . $need_buyids . ")");

        //折扣订阅统计
        if ($is_discount || $is_bookdiscount) {//插入折扣统计表
            foreach ($nobuy_vipchparys as $chpid => $chapter) {
                if ($chapter['is_free']) {
                    continue;
                }
                $discount_log_Arys[] = array('uid' => $user['uid'], 'bid' => $bid, 'title' => $chapter['title'], 'saleprice' => $chapter['truesaleprice'], 'chapterid' => $chapter['chapterid'], 'moneytype' => $chapter['moneytype'],
                    'fromSite' => 1, 'saletime' => time());
            }
            if (count($discount_log_Arys) > 0) {
                M("salelogs_activity")->add($discount_log_Arys);
            }
            unset($discount_log_Arys);
        }

        //消费后增加红票
        $red_ticket_salemodel = M("red_ticket_sale");
        $saleNum              = $red_ticket_salemodel->where("month='" . date("ym") . "' and user_id='" . $user["uid"] . "'")->field("user_id,money")->find();

        if ($saleNum) {
            $yue = $saleNum["money"];
        } else {
            $yue = 0;
        }

        $bujin = C('USERVIP')[$user["viplevel"]]['bujin'];
        // 计算可得票数

        $getNum = floor(($yue + $need_total_money) / $bujin);

        // 充红票
        if ($getNum > 0) {
            $this->addUserRedTicket($user['uid'], $user['username'], "G02", $getNum, $getNum * $bujin);
            $yuMoney = ($yue + $need_total_money) - $getNum * $bujin;
        } else {
            $yuMoney = $yue + $need_total_money;
        }

        // 更新余额
        $sdata = array();
        if ($saleNum) {
            $smap['month']        = date("ym");
            $smap['user_id']      = $user['uid'];
            //$red_ticket_salemodel->where($smap)->setInc('total_money', $need_total_money);
            $sdata['total_money'] = array('exp', 'total_money+' . $need_total_money);
            $sdata['money']       = $yuMoney;
            $red_ticket_salemodel->where($smap)->save($sdata);
        } else {
            $lastTotalmoney       = $red_ticket_salemodel->where("month='" . date("ym", strtotime("-1 month")) . "' and user_id='" . $user["uid"] . "'")->field("total_money")->find();
            $sdata['month']       = date("ym");
            $sdata['user_id']     = $user['uid'];
            $sdata['money']       = $yuMoney;
            $sdata['total_money'] = $yuMoney;
            $red_ticket_salemodel->add($sdata);
        }
        unset($red_ticket_salemodel);
        unset($Model);
        return 'orderchpsuc';
    }

    /**
     * 增加用户红票数
     *
     * @param int $uid 用户id
     * @param string $username 用户名
     * @param string $type 红票类型
     * @param int $num 红票数
     * @param int $memo 红票数乘以系数
     * @return
     */
    function addUserRedTicket($uid, $username, $type, $num, $memo) {

        $month = date("ym");

        $red_ticket_usermodel = M("red_ticket_user");
        $map['user_id']       = $uid;
        $map['month']         = $month;
        $count                = $red_ticket_usermodel->where($map)->find();

        if ($count) {
            $red_ticket_usermodel->where($map)->setInc("num", $num);
        } else {
            $data              = array();
            $data['user_id']   = $uid;
            $data['user_name'] = $username;
            $data['month']     = $month;
            $data['num']       = $num;
            $red_ticket_usermodel->add($data);
        }
        unset($red_ticket_usermodel);

        // 记录红票增加日志
        $red_ticket_gainmodel = M("red_ticket_gain");
        $data2                = array();
        $data2['user_id']     = $uid;
        $data2['user_name']   = $username;
        $data2['type']        = $type;
        $data2['month']       = date("ym");
        $data2['i_date']      = date("Y-m-d");
        $data2['i_time']      = date("H:i:s");
        $data2['num']         = $num;
        $data2['memo']        = $memo;
        $data2['ip']          = get_client_ip();
        $red_ticket_gainmodel->add($data2);
        unset($red_ticket_gainmodel);
    }

    /**
     * 获取用户自动订阅状态
     *
     * @param int $bid 书号
     * @param array $user 用户信息数组
     * @return array
     */
    public function getAutoOrderStatus($bid, $user) {
        //获得,设置,删除自动订阅自动订阅状态
        if ($user && is_array($user)) {
//             $client = new \Yar_Client(C('RPCURL') . "/autodingyuestatus.php");
            $client = new \HS\Yar("autodingyuestatus");
            $result = $client->checkAutoStatus($bid, $user['uid']);
            unset($client);
        } else {
            $result = array('autoDinyueInfo' => false);
        }
        return $result;
    }

    public function compareAndUpdateAutoStatus($bid, $uid, $username, $autoDinyueType, $fromSite = 1) {
        //获得,设置,删除自动订阅自动订阅状态
//         $client = new \Yar_Client(C('RPCURL') . "/autodingyuestatus.php");
        $client = new \HS\Yar("autodingyuestatus");
        $result = $client->compareAndUpdateAutoStatus($bid, $uid, $username, $autoDinyueType, $fromSite);
        if ($result) {
            $autoDinyueType    = $result['autoDinyueType'];
            $autoDinyueInfo    = $result['autoDinyueInfo'];
            $change_autodytype = $result['change_autodytype'];
        }
        unset($client);
        return $result;
    }

    /**
     * 制作二维码
     *
     * @param string $data url地址
     * @param int $bid 书号
     * @param string $type 类型
     * @param int $cid 章节id
     * @return string
     */
    public function makeBarCode($data, $bid, $type, $cid) {
        if (file_exists('/' . $bid . '/' . $type . '/' . md5($bid . $type . $cid) . '.png')) {
            return '/' . $bid . '/' . $type . '/' . md5($bid . $type . $cid) . '.png';
        }

        $path = C('BARCODEPATH') . '/' . $bid . '/' . $type;
        // echo $path;
        // echo "<br/>";
        createDir($path);
        if (!is_dir($path)) {
            mkdir($path);
        }
        vendor("phpqrcode.phpqrcode");
        $filename = $path . '/' . md5($bid . $type . $cid) . '.png';
        if (!file_exists($filename)) {
            \QRcode::png($data, $filename, 'L', 4, 2);

            $QR             = imagecreatefromstring(file_get_contents($filename));
            $logo           = imagecreatefromstring(file_get_contents(C('IMG1_ROOT')."/Public/images/barcodelogo.jpg"));
            $QR_width       = imagesx($QR);
            $QR_height      = imagesy($QR);
            $logo_width     = imagesx($logo);
            $logo_height    = imagesy($logo);
            $logo_qr_width  = $QR_width / 5;
            $scale          = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width     = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

            imagepng($QR, $filename);
        }
        return '/' . $bid . '/' . $type . '/' . md5($bid . $type . $cid) . '.png';
    }

    /**
     * 获取书
     *
     * @param int $bid 书号
     * @param string $field 字段
     * @return array
     */
    public function getBookByBid($bid, $field) {
        $map['bid'] = $bid;
        $bookmodel  = M("book");
        $book       = $bookmodel->where($map)->field($field)->find();
        unset($bookmodel);
        return $book;
    }

    /**
     * 获取某书未买章节的价格
     *
     * @param int $bid 书号
     * @param array $user 用户信息
     * @return int
     */
    public function getBookNoBuyPrice($bid, $user, $fromSite = 1) {
        //根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        $pricebeishu      = 250;
//         $client = new \Yar_Client(C('RPCURL') . "/discountset.php");
        $client           = new \HS\Yar("discountset");
        $result           = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromSite);

        if (!$result) {
            unset($bookmodel);
            return 0;
        }
        $discount_set     = $result['discount_set'];
        $is_discount      = $discount_set['is_open'];
        $is_bookdiscount  = $discount_set['is_bookdiscount'];
        $custom_price_set = $result['custom_price_set'];
        $xianmian_set     = $result['xianmian_set'];
        $pricebeishu      = $result['pricebeishu'];
        unset($result);
        unset($client);
        // 获得用户已订购章节ids
        $aleady_buyids    = '';
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client           = new \HS\Yar("dingoujson");
        $result           = $client->checkUserAll($bid, $user['uid'], 'ids');
        if (false === $result) {
            unset($result);
            unset($client);
            return 0;
        }
        if ($result != 'N') {
            $aleady_buyids = $result;
        }
        $is_buyall = true;
        if ($is_buyall == false) {
            $already_arr = explode(',', $aleady_buyids);
            if (array_search($chapterid, $already_arr) != false) {
                unset($Model);
                return 0;
            }
        }
        //根据已订阅章节,选择的章节,自动订阅状态,是否全订,获得所有需要订阅的章节数组
//         $client = new \Yar_client(C('RPCURL') . '/dingyuechapter.php');
        $client = new \HS\Yar("dingyuechapter");
        $result = $client->getNoBuychapterArys($bid, 0, $aleady_buyids, $is_buyall, 0);

        if ($result != false) {
            $nobuy_vipchparys = $result['nobuy_vipchparys'];
            $aleady_buyarys   = $result['aleady_buyarys'];
        }
        // 没有发现需要订阅的章节
        $salenum = count($nobuy_vipchparys);
        if (!is_array($nobuy_vipchparys) || $salenum <= 0) {
            unset($result);
            unset($client);
            return 0;
        }
        unset($result);
        unset($client);

        $need_buyids     = $this->implodeIds($nobuy_vipchparys, 'chapterid');
        // 计算每个单章价格和总价
        $need_totalmoney = 0;
//         $client = new \Yar_Client(C('RPCURL') . "/dingyuechapter.php");
        $result          = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, $is_buyall, $custom_price_set, $xianmian_set);

        if (false === $result) {
            unset($result);
            unset($client);
            return 0;
        }
        $need_totalmoney = $result['need_totalmoney'];
        return $need_totalmoney;
    }

    /**
     * 获取某书未买章节的数量
     *
     * @param int $bid 书号
     * @param array $user 用户信息
     * @return int
     */
    public function getBookNoBuyCount($bid, $user, $fromSite = 1) {
        //根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        $pricebeishu      = 250;
//         $client = new \Yar_Client(C('RPCURL') . "/discountset.php");
        $client           = new \HS\Yar("discountset");
        $result           = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromSite);

        if (!$result) {
            unset($bookmodel);
            return 0;
        }
        $discount_set     = $result['discount_set'];
        $is_discount      = $discount_set['is_open'];
        $is_bookdiscount  = $discount_set['is_bookdiscount'];
        $custom_price_set = $result['custom_price_set'];
        $xianmian_set     = $result['xianmian_set'];
        $pricebeishu      = $result['pricebeishu'];
        unset($result);
        unset($client);
        // 获得用户已订购章节ids
        $aleady_buyids    = '';
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client           = new \HS\Yar("dingoujson");
        $result           = $client->checkUserAll($bid, $user['uid'], 'ids');
        if (false === $result) {
            unset($result);
            unset($client);
            return 0;
        }
        if ($result != 'N') {
            $aleady_buyids = $result;
        }
        $is_buyall = true;
        if ($is_buyall == false) {
            $already_arr = explode(',', $aleady_buyids);
            if (array_search($chapterid, $already_arr) != false) {
                unset($Model);
                return 0;
            }
        }
        //根据已订阅章节,选择的章节,自动订阅状态,是否全订,获得所有需要订阅的章节数组
//         $client = new \Yar_client(C('RPCURL') . '/dingyuechapter.php');
        $client = new \HS\Yar("dingyuechapter");
        $result = $client->getNoBuychapterArys($bid, 0, $aleady_buyids, $is_buyall, 0);

        if ($result != false) {
            $nobuy_vipchparys = $result['nobuy_vipchparys'];
            $aleady_buyarys   = $result['aleady_buyarys'];
        }
        // 没有发现需要订阅的章节
        $salenum = count($nobuy_vipchparys);
        return $salenum;
    }

    /**
     * 为某本书增加红票
     *
     * @param int $bid 书号
     * @param int $ticket 红票数
     * @return
     */
    public function addticket($bid, $ticket) {
        $map['bid'] = $bid;
        $bookmodel  = M("book");
        $bookmodel->where($map)->setInc("redTicket", $ticket);
        unset($bookmodel);
    }

    /**
     * 获取某书的二级IP
     *
     * @param int $bid 书号
     * @param int $pid 一级IP的id
     * @return array
     */
    public function getBookIp($bid, $pid = -1) {
        $map['a.bid'] = $bid;
        if ($pid != -1) {
            $map['a.ippid'] = $pid;
        }
        $bookmodel  = M("book_ip");
        $bookiplist = $bookmodel->alias('a')->join('wis_ip b ON a.ipid = b.id ')->join('wis_ip c ON a.ippid = c.id')->field('a.ipid,a.unit,a.contacts,a.phone,a.address,a.timelimit,a.link,a.remarks,b.title,c.title as "ptitle"')->where($map)->select();
        // echo M()->getLastSql();
        unset($bookmodel);
        return $bookiplist;
    }

    /**
     * 根据一级IP的id获取子ip
     *
     * @param int $pid 一级IP的id
     * @return array
     */
    public function getIp($pid = 0) {
        $map['pid'] = $pid;
        $bookmodel  = M("ip");
        $iplist     = $bookmodel->where($map)->order("id asc")->select();
        unset($bookmodel);
        return $iplist;
    }
    /**
     * 获取书籍已取得的ip版权
     * @param int $bid
     */
    public function getIpListByBid($bid){
        $bookipModel = M('BookIp');
        $where = array(
            'b.bid'=>$bid,
        );
        $iplists = $bookipModel->alias('b')->join('wis_ip AS i ON b.ipid=i.id OR b.ippid=i.id')->where($where)->select();
        $ips = $this->getIp();
        foreach($ips as &$ip){
            $ip['isget'] = 0;
            if($iplists && is_array($iplists)){
                foreach($iplists as $key => $vo){
                    if($vo['ippid'] == $ip['id']){
                        $ip['isget'] = 1;
                        //获取到版权信息之后就删除该记录并跳出循环获取下一个版权信息
                        unset($iplists[$key]);
                        break;
                    }
                }
            }
        }
        return $ips;
    }

    /*
     * 更新书的砖块数量
     *
     * @param int $bid 书的id
     * @param int $num 增的数量
     *
     * */
    public function addZhuanCount($bid, $num) {
        $map["bid"] = $bid;
        $bookmodel  = M("book");
        //$bookmodel->where($map)->setInc('total_zhuan', $num);
        //$bookmodel->where($map)->setInc('month_zhuan', $num);
        //$bookmodel->where($map)->setInc('week_zhuan', $num);
        //$bookmodel->where($map)->setInc('credit', $num);
        $data       = array(
            'total_zhuan' => array('exp', 'total_zhuan+' . $num),
            'month_zhuan' => array('exp', 'month_zhuan+' . $num),
            'week_zhuan'  => array('exp', 'week_zhuan+' . $num),
            'credit'      => array('exp', 'credit+' . $num),
        );
        $bookmodel->where($map)->save($data);
        // $book = $this->getBook($bid);
        // $book['total_flower'] = $book['total_flower'] + $num;
        // $book['month_flower'] = $book['month_flower'] + $num;
        // $book['week_flower'] = $book['week_flower'] + $num;
        // $cachemodel = D('Cache');
        // $cachemodel->set("book_normal#".$bid, $book);
        // unset($cachemodel);
        unset($bookmodel);
    }

    /**
     * 获得书的TXT资源路径
     *
     * @param int 书号
     * @return string
     */
    public function getBookStaticFilepath($bid) {
        return floor($bid / 10000) . '/' . floor(($bid % 10000) / 100) . '/' . $bid;
    }

    /**
     * 根据书号获取粉丝数
     *
     * @param type $bid
     */
    function getFansNumber($bid = 0) {
        return 0;
        if ($bid < 1) {
            return 0;
        }
        $redis = new \Think\Cache\Driver\Redis();
        $arr   = $redis->HGETALL('book_fensi#' . $bid);
        return is_array($arr) ? count($arr) : 0;
    }

    /**
     * 根据书号获取指定用户ID的头衔数据
     *
     * @param type $bid
     * @param type $uid
     */
    function getFansInfo($bid, $uid = 0) {
        $result = array(
            'rank'     => 0,
            'integral' => 0,
            'groupid'  => 0
        );
        return $result;
        if ($uid <= 0) {
            return $result;
        }
        $rank     = $temprank = $integral = 0;
        if (class_exists('Redis')) {
            $redis    = new \Think\Cache\Driver\Redis();
            $arr      = $redis->HGETALL('book_fensi#' . $bid);
            arsort($arr);
            $rank     = 0;
            $temprank = 0;
            foreach ($arr as $k => $v) {
                $temprank ++;
                if (intval($k) == intval($uid)) {
                    $integral = $v;
                    $rank     = $temprank;
                    break;
                }
            }
            unset($redis);
        } else {
            $fModel = M('BookFans');
            $where  = array(
                'bid' => $bid,
                'uid' => $uid
            );
            $row    = $fModel->where($where)->find();
            if ($row) {
                $integral = $row['jifen'];
            }
        }

        $bookgroup = C('BOOKUSERGROUP');

        for ($i = 0; $i < count($bookgroup); $i ++) {
            if (intval($integral) >= intval($bookgroup[$i]['start']) && intval($integral) < intval($bookgroup[$i]['end'])) {
                $groupid = $bookgroup[$i]["level"];
                $rank    = $i;
                break;
            }
        }
        $result = array(
            'rank'     => $rank,
            'integral' => $integral,
            'groupid'  => $groupid
        );

        return $result;
    }

    /**
     * 订购章节
     *
     * @param int $bid 书号
     * @param int $chapterid 章节id
     * @param array $user 用户信息
     * @param bool $is_buyall 是否全买
     *              全买的含义：购买指定书的所有未购买章节
     * @param int $autoorder 是否自动订阅 3：是 0：不是
     * @return string
     */
    public function orderChapterByCache($bid, $chapterid, $user, $is_buyall, $autoorder = -1, $fromSite = 1) {
        $cacheObj = false;
        //if ($autoorder !== -1) {
        $cacheObj = new \HS\CacheLock(':buyvipchpt:' . $user['uid'].':'.$bid.':'.$chapterid);
        //$cacheObj->unlock();
        if (!$cacheObj->lock()) {
//             exit(S('buyvipchpt_' . $user['uid']));
            unset($cacheObj);
            return 'orderisruning';
        }
        //}
        //}
        $Model            = new Model();
        //TODO 根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        $pricebeishu      = 250;
//         $client = new \Yar_Client(C('RPCURL') . "/discountset.php");
        $client           = new \HS\Yar("discountset");
        $result           = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromSite);

        if (!$result) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($client);
            unset($Model);
            return 'orderchperror';
        }
        $discount_set     = $result['discount_set'];
        $is_discount      = $discount_set['is_open'];
        $is_bookdiscount  = $discount_set['is_bookdiscount'];
        $custom_price_set = $result['custom_price_set'];
        $xianmian_set     = $result['xianmian_set'];
        $pricebeishu      = $result['pricebeishu'];
        unset($result);
        unset($client);
        if(CLIENT_NAME == 'yqm' || (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0')){
            $pricebeishu = 333;
        }
        // 获得用户已订购章节ids
        $aleady_buyids = '';
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client        = new \HS\Yar("dingoujson");
        $result        = $client->checkUserAll($bid, $user['uid'], 'ids');
        if (false === $result) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }
        if ($result != 'N') {
            $aleady_buyids = $result;
        }
        if ($is_buyall == false) {
            $already_arr = explode(',', $aleady_buyids);
            if (array_search($chapterid, $already_arr) != false) {
                if ($cacheObj) {
                    $cacheObj->unlock();
                    unset($cacheObj);
                }
                unset($Model);
                return 'nochapterorder';
            }
        }
        //根据已订阅章节,选择的章节,自动订阅状态,是否全订,获得所有需要订阅的章节数组
//         $client = new \Yar_client(C('RPCURL') . '/dingyuechapter.php');
        $client = new \HS\Yar("dingyuechapter");
        if (!is_array($chapterid)) {
            $need_buyids = $chapterid;
        } else {
            $need_buyids = implode(',', $chapterid);
        }
        //$need_buyids = current($chapterid);
        $result = $client->getNoBuychapterArys($bid, 0, $aleady_buyids, $is_buyall, $need_buyids);

        if ($result != false) {
            $nobuy_vipchparys = $result['nobuy_vipchparys'];
            $aleady_buyarys   = $result['aleady_buyarys'];
        }
        // 没有发现需要订阅的章节
        $salenum = count($nobuy_vipchparys);
        if (!is_array($nobuy_vipchparys) || $salenum <= 0) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($Model);
            unset($result);
            unset($client);
            return 'nochapterorder';
        }
        unset($result);
        unset($client);

        $need_buyids     = $this->implodeIds($nobuy_vipchparys, 'chapterid');
        // 计算每个单章价格和总价
        $need_totalmoney = 0;
//         $client = new \Yar_Client(C('RPCURL') . "/dingyuechapter.php");
        $client          = new \HS\Yar("dingyuechapter");
        $result          = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, $is_buyall, $custom_price_set, $xianmian_set);

        if (false === $result) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }
        $need_totalmoney  = $result['need_totalmoney'];
        $nobuy_vipchparys = $result['nobuy_vipchparys'];

//         $client = new \Yar_Client(C('RPCURL') . "/usermoney.php");
        $client = new \HS\Yar("usermoney");
        //var_dump($nobuy_vipchparys);
        $result = $client->simulate_buychapters_egoldFirst2($user['uid'], $nobuy_vipchparys);
        // var_dump($result);
        if (false === $result) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($Model);
            unset($result);
            unset($client);
            return 'orderchperror';
        }

        $usermodel               = new \Client\Model\UserModel();
        $usermoney               = $usermodel->getUserMoney($user['uid']);
        //8.2 判断金币是否够用
        $usermoney['totalmoney'] = intval($usermoney[$user['uid']]['money']) + intval($usermoney[$user['uid']]['egold']);

        if ($usermoney['totalmoney'] < intval($need_totalmoney) && intval($need_totalmoney) > 0) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            unset($Model);
            return 'consumefail';
        }
        unset($usermodel);

        //9.1.1 模拟得出需要扣多少金币
        $need_total_money = $result['money'];
        if ($need_total_money < 0) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            return 'consumefail';
        }
        //9.1.1 模拟得出需要扣多少egold
        $need_total_egold = $result['egold'];
        if ($need_total_egold < 0) {
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            return 'consumefail';
        }
        //每章包含了monetype的模拟扣费结果
        $nobuy_vipchparys = $result['nobuy_vipchparys'];

        unset($result);
        unset($client);
        $tmp_total_money = $need_total_money + $need_total_egold;

        if ($need_totalmoney > 0) {
//             $client = new \Yar_Client(C('RPCURL') . "/usermoney.php");
            $client = new \HS\Yar("usermoney");
            $result = $client->subUserMoney($user['uid'], 'moneyAndegold', $need_total_money, $need_total_egold);

            //var_dump($result);
            if (false === $result) {
                if ($cacheObj) {
                    $cacheObj->unlock();
                    unset($cacheObj);
                }
                unset($Model);
                unset($result);
                unset($client);
                return 'orderchperror';
            }

            //9.2.1 判断扣费是否成功
            if ($result['result'] != 'Y') {
                if ($result['result'] == 'N1') {
                    if ($cacheObj) {
                        $cacheObj->unlock();
                        unset($cacheObj);
                    }
                    unset($Model);
                    return 'consumefail';
                } else {
                    if ($cacheObj) {
                        $cacheObj->unlock();
                        unset($cacheObj);
                    }
                    unset($Model);
                    return 'orderchperror';
                }
            }
            unset($result);
            unset($client);
        }

        if ($autoorder == 3 || $autoorder == 0) {
            $this->compareAndUpdateAutoStatus($bid, $user['uid'], $user['username'], $autoorder, $fromSite);
        }
        //9.3.1 写订购关系
//         $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
        $client  = new \HS\Yar("dingoujson");
        $result1 = $client->chSaveAll($bid, $user['uid'], $nobuy_vipchparys);
        unset($client);

        //9.3.2 写销售记录表
//         $client = new \Yar_Client(C('RPCURL') . "/dingyuechapter.php");
        $client  = new \HS\Yar("dingyuechapter");
        $result2 = $client->add_multi_salelogs($bid, $user['uid'], $fromSite, $nobuy_vipchparys);


        // 9.3.3 网络失败或写订购关系失败或写销售记录表失败
        if (false === $result1 || $result1 != "Y" || false === $result2 || $result2 != 'Y') {
            if (false === $result1 || $result1 != 'Y') {
                $failed_chsaveall = true;
            }
            if (false === $result2 || $result2 != 'Y') {
                $failed_addsalelogs = true;
            }

            //9.3.3.1生成记录日志数组
            foreach ($nobuy_vipchparys as $chpid => $chapter) {
                //9.3.3.2限免章节不记录
                if ($chapter['is_free']) {
                    continue;
                }
                if ($chapter['moneytype'] == 1) {
                    $__money = 0;
                    $__egold = $chapter['truesaleprice'];
                } else {
                    $__egold = 0;
                    $__money = $chapter['truesaleprice'];
                }
                $fail_log_chsave_all_Arys[]  = array(
                    'uid'           => $user['uid'],
                    'bid'           => $bid,
                    'title'         => $chapter['title'],
                    'chpid'         => $chapter['chapterid'],
                    'money'         => $__money,
                    'egold'         => $__egold,
                    'addtime'       => NOW_TIME,
                    'fromSite'      => $fromSite,
                    'errordescribe' => '保存订购关系失败');
                $fail_log_addsalelogs_Arys[] = array(
                    'uid'           => $user['uid'],
                    'bid'           => $bid,
                    'title'         => $chapter['title'],
                    'chpid'         => $chapter['chapterid'],
                    'money'         => $__money,
                    'egold'         => $__egold,
                    'addtime'       => NOW_TIME,
                    'fromSite'      => $fromSite,
                    'errordescribe' => '扣费成功但没写入订阅表');
            }

            if ($failed_chsaveall && count($fail_log_addsalelogs_Arys) > 0) {
                M("usersalelogs")->addAll($fail_log_chsave_all_Arys);
            }
            if ($failed_addsalelogs && count($fail_log_addsalelogs_Arys) > 0) {
                M("usersalelogs")->addAll($fail_log_addsalelogs_Arys);
            }
            unset($Model);
            unset($fail_log_addsalelogs_Arys);
            unset($fail_log_chsave_all_Arys);
            if ($is_buyall) {
                $this->addBooktrend($bid, 10, $user, 1, '');
            } else {
                $this->addBooktrend($bid, 9, $user, 1, '');
            }
            if ($cacheObj) {
                $cacheObj->unlock();
                unset($cacheObj);
            }
            return 'orderchperror';
        }

        unset($result1);
        unset($result2);
        unset($client);

        // $this->userSale($user['uid'], $need_totalmoney);
        $sesuser          = session();
        $sesuser['money'] = (intval($sesuser['money']) - intval($need_total_money));
        $sesuser['egold'] = (intval($sesuser['egold']) - intval($need_total_egold));
        session($sesuser);

        $fensimodel = new \Client\Model\FensiModel();
        $integral   = C('INTEGRAL')['order'] * $need_total_money;
        $fensimodel->addFansIntegral($bid, $user['uid'], $integral);
        unset($fensimodel);

        //9.3.3 总销售记录
//         $map["bid"] = $bid;
//         M("book")->where($map)->setInc('salenum', $salenum);

        //9.3.4 章节销售记录
        $chpt_tablename = $this->getChpTableName($bid);
        $Model->execute("update wis_" . $chpt_tablename . " set salenum=salenum+1 where chapterid in (" . $need_buyids . ")");

        //折扣订阅统计
        if ($is_discount || $is_bookdiscount) {//插入折扣统计表
            foreach ($nobuy_vipchparys as $chpid => $chapter) {
                if ($chapter['is_free']) {
                    continue;
                }
                $discount_log_Arys[] = array(
                    'uid'       => $user['uid'],
                    'bid'       => $bid,
                    'title'     => $chapter['title'],
                    'saleprice' => $chapter['truesaleprice'],
                    'chapterid' => $chapter['chapterid'],
                    'moneytype' => $chapter['moneytype'],
                    'fromSite'  => $fromSite,
                    'saletime'  => time());
            }
            if (count($discount_log_Arys) > 0) {
                M("salelogs_activity")->add($discount_log_Arys);
            }
            unset($discount_log_Arys);
        }

        //消费后增加红票
        $red_ticket_salemodel = M("red_ticket_sale");
        $saleNum              = $red_ticket_salemodel->where("month='" . date("ym") . "' and user_id='" . $user["uid"] . "'")->field("user_id,money")->find();

        if ($saleNum) {
            $yue = $saleNum["money"];
        } else {
            $yue = 0;
        }

        $bujin  = C('USERVIP')[$user["viplevel"]]['bujin'];
        // 计算可得票数
        $getNum = floor(($yue + $need_total_money) / $bujin);

        // 充红票
        if ($getNum > 0) {
            $this->addUserRedTicket($user['uid'], $user['username'], "G02", $getNum, $getNum * $bujin);
            $yuMoney = ($yue + $need_total_money) - $getNum * $bujin;
        } else {
            $yuMoney = $yue + $need_total_money;
        }

        // 更新余额
        $sdata = array();
        if ($saleNum) {
            $smap['month']        = date("ym");
            $smap['user_id']      = $user['uid'];
            //$red_ticket_salemodel->where($smap)->setInc('total_money', $need_total_money);
            $sdata['total_money'] = array('exp', 'total_money+' . $need_total_money);
            $sdata['money']       = $yuMoney;
            $red_ticket_salemodel->where($smap)->save($sdata);
        } else {
            $lastTotalmoney       = $red_ticket_salemodel->where("month='" . date("ym", strtotime("-1 month")) . "' and user_id='" . $user["uid"] . "'")->field("total_money")->find();
            $sdata['month']       = date("ym");
            $sdata['user_id']     = $user['uid'];
            $sdata['money']       = $yuMoney;
            $sdata['total_money'] = $yuMoney;
            $red_ticket_salemodel->add($sdata);
        }
        if ($cacheObj) {
            $cacheObj->unlock();
            unset($cacheObj);
        }
        unset($red_ticket_salemodel);
        unset($Model);
        return 'orderchpsuc';
    }

    private function implodeIds($arrays, $keys = null) {
        $ids   = $comma = '';
        if (is_array($arrays) && count($arrays)) {
            foreach ($arrays as $ida) {
                if ($keys && intval($ida[$keys])) {
                    $ids .= $comma . $ida[$keys];
                    $comma = ",";
                } else if (!is_array($ida) && intval($ida)) {
                    $ids.=$comma . $ida;
                    $comma = ",";
                }
            }
        }
        return $ids;
    }
    /**
     * 根据作者获取书籍,先取缓存，缓存取不到则取数据库并重建缓存
     * @param int $authorid
     */
    public function getBookByAuthorId($authorid,$bid=0){
        $key = ':authorbooklist:'.$authorid;
        $cacheModel = new \HS\MemcacheRedis();
        $bids = $cacheModel->getRedis($key);
        $booklists = array();
        if($bids && is_array($bids)){
            foreach($bids as $id){
                if($bid != $id){
                    $booklists[] = $this->getBook($id);
                }
            }
        }else{
            //缓存中没有，则查数据库并重建缓存
            $map = array('authorid'=>$authorid);
            if($bid > 0){
                $map['bid'] = array('NEQ',$bid);
            }
            $lists = $this->where($map)->select();
            if($lists && is_array($lists)){
                $booklists = $lists;
                //建立缓存
                $ids = array_column($booklists, 'bid');
                $cacheModel->setRedis($key, json_encode($ids));
            }
        }
        if($booklists){
            foreach ($booklists as &$vo){
                $vo['cover'] = getBookfacePath($vo['bid']);
            }
        }
        return $booklists;
    }

    /**
     * 喵阅读获取首页各种更新榜
     * @return array
     *      all:最新更新
     *      vip:vip更新
     *      free:免费更新
     */
    public function getUpdateBooks($key){
        $res = array();
        $cacheModel = new \HS\MemcacheRedis();
        $updateDatas = $cacheModel->get($key);
        if($updateDatas && is_array($updateDatas)){
            //格式化数据
            foreach($updateDatas as $key => $vo){
                foreach ($vo as $k => $val){
                    $updateDatas[$key][$k]['lastupdatetime'] = 0;    //最后更新时间
                    $updateDatas[$key][$k]['isvipchapter'] = 0;  //最后更新章节是否是vip章节(0不是，1是)
                    $updateDatas[$key][$k]['lastupdatechpid'] = 0;   //最后更新章节id
                    $updateDatas[$key][$k]['lastupdatetitle'] = '';   //最后更新章节名
                    if(intval($val['last_vipupdatechpid']) > 0){
                        $updateDatas[$key][$k]['lastupdatechpid'] = $val['last_vipupdatechpid'];
                        $updateDatas[$key][$k]['lastupdatetitle'] = trim($val['last_vipupdatechptitle']);
                        $updateDatas[$key][$k]['isvipchapter'] = 1;
                        $updateDatas[$key][$k]['lastupdatetime'] = date('m-d H:i',$val['last_vipupdatetime']);
                    }else{
                        $updateDatas[$key][$k]['lastupdatechpid'] = $val['last_updatechpid'];
                        $updateDatas[$key][$k]['lastupdatetitle'] = trim($val['last_updatechptitle']);
                        $updateDatas[$key][$k]['lastupdatetime'] = date('m-d H:i',$val['last_updatetime']);
                    }
                }
            }
            $lastupdatetimes = array();
            //最新更新
            $res['all'] = array_merge($updateDatas['last_nanbooklist'],$updateDatas['last_vipnanbooklist'],$updateDatas['last_nvbooklist'],$updateDatas['last_vipnvbooklist']);
            //vip更新
            $res['vip'] = array_merge($updateDatas['last_vipnanbooklist'],$updateDatas['last_vipnvbooklist']);
            //免费更新
            $res['free'] = array_merge($updateDatas['last_nanbooklist'],$updateDatas['last_nvbooklist']);
            foreach ($res as $kk => $list){
                $lastupdatetimes = array_column($list, 'lastupdatetime');
                array_multisort($lastupdatetimes,SORT_DESC,$list);
                $res[$kk] = array_slice($list, 0,30);
            }
        }
        return $res;
    }
    /**
     * 喵阅读首页热度精选
     */
    public function getHotBooks(){
        //index_nvqt,index_nanqt
        $cacheModel = new \HS\MemcacheRedis();
        $hotbooks_nan = $cacheModel->get('index_nanft');
        $hotbooks_nv = $cacheModel->get('index_nvft');
        $hotbooks_all = array_slice(array_merge($hotbooks_nan['booklists'],$hotbooks_nv['booklists']),0,4);
        return $hotbooks_all;
    }
/**
	 * 获取红票排行
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getTicketRank($sexflag, $type) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->get("ticketrank_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取销售排行
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getSaleRank($sexflag, $type) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->get("sales_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取更新榜
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getUpdateRank($sexflag, $type) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->get("update_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取收藏
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getFavRank($sexflag, $type) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->getRedis("fav_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取点击排行
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getHitRank($sexflag, $type) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->get("hit_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取鲜花排行
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @return array
	 */
	public function getFlowerRank($sexflag, $type) {
	    $cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->get("flower_". $sexflag . "_" .$type);
		if(isset($arr['booklists'])){
		    $arr['booklists'] = array_slice($arr['booklists'], 0, 10);
		    foreach ($arr['booklists'] as &$vo){
		        $vo['cover'] = getBookfacePath($vo['bid']);
		    }
		    return $arr['booklists'];
		}else{
		    return array();
		}
	}

	/**
	 * 获取粉丝推荐榜
	 *
	 * @param string $sexflag 男女频
	 * @param string $type 类型：年、月、周
	 * @param int $limit 一页取多少条
	 * @return array
	 */
	public function getFansrecRank($sexflag, $type, $limit) {
		$cacheModel = new \HS\MemcacheRedis();
		$arr = $cacheModel->getredisObj()->ZREVRANGE("bookrank#".$sexflag."_".$type, 0, $limit);
		return $arr;
	}
	/**
	 * 获取收藏的书籍收藏的书
	 * @param array $map 条件
	 * @param int $start 开始位置
	 * @param int $limit 取多少条
	 * @param string $order 排序
	 * @return array
	 */
	public function getFavBook($map, $start, $limit, $order='DESC') {
		$favmodel = M("fav");
		$list = $favmodel->where($map)->order('fid '.$order)->limit($start, $limit)->select();
		if(!$list || !is_array($list)){
		    return array();
		}
		foreach($list as $key=>$val){
		    $bookinfo = $this->getBook($val['bid']);
		    $list[$key] = array_merge($val,$bookinfo);
		}
		return $list;
	}
	/**
	 * 获取收藏总数
	 * @param array $map 条件
	 */
	public function getFavBookCount($map){
	    $favModel = M('fav');
	    $count = $favModel->where($map)->count();
	    return $count;
	}
	/**
	 * 获取最后一次订购信息
	 *
	 * @param int $uid 用户id
	 * @param int $bid 书号
	 * @return array
	 */
	public function getLastDingGouTime($uid, $bid) {
        $client = new \HS\Yar('dinggoujson');
	    $result = $client->getLastDingGouTime($uid, $bid);
	    unset($client);
	    return $result;
	}
	/**
	 * 根据uid获取订阅的书籍
	 * @param array $map 条件
	 * @param string $field 要查询的字段
	 */
	public function getAutoOderBooks($map,$field){
	    $dingyueModel = M('autodingyueset');
	    $res = $dingyueModel->where($map)->field($field)->select();
	    return $res;
	}
	/**
	 * 获取某个人的书架
	 * @param int $uid
	 * @param string $field 要查询的字段
	 */
	public function getShelfByUid($uid,$field = '*'){
	    $shelfModel = M('BookshelfCategory');
	    $map = array('uid'=>$uid);
	    $res = $shelfModel->where($map)->field($field)->select();
	    return $res;
	}
	/**
	 * 获取一本书的打赏记录
	 * @param array $map
	 * @param string $field(字段名)
	 * @param int $start
	 * @param int $length
	 */
	public function getRewardRecordByBid($bid,$start=0,$length=10){
	    $proModel = M('BookPro');
	    $map = array(
	        'p.bid'=>$bid
	    );
	    $res = $proModel->alias('p')->join('__READ_USER__ AS u ON u.uid = p.uid' ,'left')
	           ->field('p.pid,p.bid,p.uid,p.num,p.addtime,u.username,u.nickname')
	           ->where($map)->order('p.addtime DESC')
	           ->limit($start,$length)->select();
	    if($res && is_array($res)){
	        //拼接道具名称
	        $allPros = array_merge(C('PROPERTIES.boy'), C('PROPERTIES.girl'));
	        foreach($res as $key=>$val){
	            foreach($allPros as $vo){
	                if($val['pid'] == $vo['id']){
	                    $res[$key]['name'] = $vo['name'];
	                    $res[$key]['unit'] = $vo['unit'];
	                    break;
	                }
	            }
	        }
	        return $res;
	    }else{
	        return false;
	    }
	}
	/**
	 * 获取一本书的打赏总记录数
	 * @param int $bid
	 */
	public function getRewardCountByBid($bid){
	    $proModel = M('BookPro');
	    $map = array('bid'=>$bid);
	    $res = $proModel->field('pid,count(pid) AS num')->where($map)->group('pid')->select();
	    return $res;
	}
	/**
	 * 按使用量获取标签（去重复）
	 *
	 * @param int $limit 取多少条
	 * @return array
	 */
	public function getTags($limit) {
	    $tagcensus = M('tagcensus');
	    $list = $tagcensus->field('SUM(count),tags')->group('tags')->order("SUM(count) desc")->limit($limit)->select();
	    unset($tagcensus);
	    return $list;
	}

}

if (!function_exists('encode10to64')) {
    function encode10to64($dec) {
        $base   = '0123456789:;abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //$base='.。,、！？：；`﹑•＂^…‘’“”〝〞~\∕|¦‖—﹐﹕﹔！？﹖﹏＇ˊ-﹫︳_＿￣¯︴@―ˋ´﹋﹌¿¡;︰¸﹢﹦﹤­˜﹟﹩﹠﹪﹡﹨';
        $result = '';

        do {
            $result = $base[$dec % 64] . $result;
            $dec    = intval($dec / 64);
        } while ($dec != 0);

        return $result;
    }

}
