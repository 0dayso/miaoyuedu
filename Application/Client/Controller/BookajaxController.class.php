<?php

 /**
 * 模块: 客户端
 *
 * 功能: book类相关的ajax请求
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: anluo
 * @version: $Id: BookajaxController.class.php 1576 2017-03-16 11:41:13Z changliu $
 */

namespace Client\Controller;

use Client\Common\Controller;
use Client\Model\BookModel;
use Client\Model\NewcommentModel;

class BookajaxController extends Controller {
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 获取打折小说列表
     * @param int $num 要读取的小说个数，默认值：3
     * @param int $type 打折的类型，1：按章打折，2：完本打折，3：不限，默认值：3
     * @param string $order 数据截取方式，rand/cut，默认值：rand
     * @param intval $offset 起始行号，默认傎：0
     * @param string $sex 性别标志：all：全部，nan：只取男频，nv：只取女频，默认值：all
     * @return array
     *         status: 1/0:
     *         end: 距离结束的时长（单位：秒）
     *         books：小说列表
     *             bid:
     *             type: "2",
     *             starttime: 开始时间戳
     *             endtime: 结束时间戳
     *             freetype: "1",
     *             free_chapterid: "",
     *             free_num: "0",
     *             fromsite: "6",
     *             num: "57",
     *             intro: 简介
     *             catename: 标题
     *             classid: 分类ID
     *             classid2: 子分类ID
     *             author: 作者
     *             posttime: 提交时间
     *             authorid: 作者ID
     *             lzinfo: 连载状态
     *             charnum: 字数
     *             classname: 分类名称
     *             smallclassname: 分类简称
     *             subclassname: 子分类名称
     *             smallsubclassname: 子分类简称
     *             last_updatetime: 最后更新时间
     *             last_updatechpid: 最后更新章节ID
     *             last_updatechptitle: 最后更新章节名称
     *             last_updatejuanid: 最后更新卷ID
     *             last_vipupdatetime: VIP章节最后更新时间
     *             last_vipupdatechpid: VIP章节最后更新章节ID
     *             last_vipupdatejuanid: VIP章节最后更新卷ID
     *             last_vipupdatechptitle: VIP章节最后更新章节名称
     *             cover: 封面图片
     * @global \Think\Cache\Driver\Redis $M_redis
     */
    public function getDiscountListAction($num = 3, $type = 3, $order = 'rand', $offset = 0, $sex = 'all') {
        global $M_redis;
        if (!IS_AJAX && !canTest()) {
            _exit('参数错误');
        }

        $fromsiteid = C('CLIENT.' . CLIENT_NAME . '.fromsiteid');
        $num = max(1, intval($num));
        $type = min(3, max(1, intval($type)));
        $sexs = array('all', 'nan', 'nv');
        if (!in_array($sex, $sexs)) {
            $sex = 'all';
        }
        $orders = array('rand', 'cut');
        $order = strtolower($order);
        if (!in_array($order, $orders)) {
            $order = 'rand';
        }
        $offset = max(0, intval($offset));
        $key = 'book_discount_activity';

        //这个居然：1：缓存KEY没有前缀，2：并不是根据fromsiteID来搞的，而是转换成了串！！！
        $fromsiteAry = array("1" => "WWW", "4" => "3G", "5" => "WAP", "6" => "HTML5", "7" => "android", "8" => "ios");
        if (isset($fromsiteAry[$fromsiteid])) {
            $fromsite = $fromsiteAry[$fromsiteid];
        } else {
            $this->ajaxReturn('参数错误！');
        }
        $_cp = C('CACHE_PREFIX');
        C('CACHE_PREFIX', '');
        $result = $M_redis->get($key);
        if ($_cp) {
            C('CACHE_PREFIX', $_cp);
        }
        $date = new \Org\Util\Date();
        $bModel = new \Client\Model\BookModel();
        $now = NOW_TIME;
        //$now    = strtotime($date->dateAdd(-4));
        if ($result) {
            $books = array();
            $endtime = 0;
            $attr = array(
                'bid', 'intro', 'catename', 'classid', 'classid2', 'author', 'posttime', 'authorid', 'lzinfo', 'charnum', 'classname', 'smallclassname', 'subclassname', 'smallsubclassname',
                'last_updatetime', 'last_updatechpid', 'last_updatechptitle', 'last_updatejuanid', 'last_vipupdatetime', 'last_vipupdatechpid', 'last_vipupdatejuanid', 'last_vipupdatechptitle'
            );
            foreach ($result as $k => $list) {
                list($start, $end) = explode('-', $k);
                if ($start <= $now && $end >= $now) {
                    //合适地
                    foreach ($list as $_key => $info) {
                        list($from, $bid) = explode('_', $_key);
                        $info['type'] = 0;
                        if ($info['is_open']) {   //按章打折
                            $info['type'] += 1;
                        }
                        if ($info['is_bookdiscount']) {  //完本打折
                            $info['type'] += 2;
                        }
                        if ($fromsite == $from && ($info['type'] & $type)) {
                            $endtime = max($end, $endtime);
                            $bi = $bModel->getBook($bid);
                            if (!$bi) {
                                //没有找到指定的书
                                continue;
                            }
                            if ($sex == 'nan') {
                                //只取男频
                                if ($bi['sex_flag'] === 'nv') {
                                    continue;
                                }
                            } else if ($sex == 'nv') {
                                //只取女频
                                if ($bi['sex_flag'] === 'nan') {
                                    continue;
                                }
                            } else {
                                //不分男女
                            }
                            foreach ($attr as $key) {
                                $info[$key] = $bi[$key];
                            }
                            $info['intro'] = nl2p($info['intro']);
                            $info['cover'] = getBookfacePath($bid);
                            $books[] = $info;
                        }
                    }
                }
            }
            if ($books) {
                if (count($books) > $num) {
                    if ($order == 'rand') {
                        $tmp = array_rand($books, $num);
                        $tmps = $books;
                        $books = array();
                        foreach ((array) $tmp as $key) {
                            $books[] = $tmps[$key];
                        }
                    } else {
                        $books = array_slice($books, $offset, $num);
                    }
                }
                $books = array_random($books);
                $result = array(
                    'status' => 1,
                    //'now'    => $now,
                    //'nowstr' => date('Y-m-d H:i:s', $now),
                    'end'    => $endtime - $now,
                    //'endstr' => date('Y-m-d H:i:s', $endtime),
                    'books'  => $books
                );
                $this->ajaxReturn($result);
            } else {
                $this->ajaxReturn('暂无数据');
            }
        } else {
            $this->ajaxReturn('暂无数据');
        }
    }

    /**
     * 获取免费小说列表
     * @param int $num 要读取的小说个数，默认值：3
     * @param int $type 限免的类型，1：整本限免，2：指定章节限免，3：不限，默认值：3
     * @param string $order 数据截取方式，rand/cut，默认值：rand
     * @param intval $offset 起始行号，默认傎：0
     * @param string $sex 性别标志：all：全部，nan：只取男频，nv：只取女频，默认值：all
     * @return array
     *         status: 1/0:
     *         end: 距离结束的时长（单位：秒）
     *         books：小说列表
     *             bid:
     *             type: "2",
     *             starttime: 开始时间戳
     *             endtime: 结束时间戳
     *             freetype: "1",
     *             free_chapterid: "",
     *             free_num: "0",
     *             fromsite: "6",
     *             num: "57",
     *             intro: 简介
     *             catename: 标题
     *             classid: 分类ID
     *             classid2: 子分类ID
     *             author: 作者
     *             posttime: 提交时间
     *             authorid: 作者ID
     *             lzinfo: 连载状态
     *             charnum: 字数
     *             classname: 分类名称
     *             smallclassname: 分类简称
     *             subclassname: 子分类名称
     *             smallsubclassname: 子分类简称
     *             last_updatetime: 最后更新时间
     *             last_updatechpid: 最后更新章节ID
     *             last_updatechptitle: 最后更新章节名称
     *             last_updatejuanid: 最后更新卷ID
     *             last_vipupdatetime: VIP章节最后更新时间
     *             last_vipupdatechpid: VIP章节最后更新章节ID
     *             last_vipupdatejuanid: VIP章节最后更新卷ID
     *             last_vipupdatechptitle: VIP章节最后更新章节名称
     *             cover: 封面图片
     * @global \Think\Cache\Driver\Redis $M_redis
     */
    public function getFreeListAction($num = 3, $type = 3, $order = 'rand', $offset = 0, $sex = 'all', $bang = 'android_freenv_benjizhudai') {
        global $M_redis;
        if (!IS_AJAX) {
            _exit('参数错误');
        }
        $fromsite = C('CLIENT.' . CLIENT_NAME . '.fromsiteid');
        $num = max(1, intval($num));
        $type = min(3, max(1, intval($type)));
        $sexs = array('all', 'nan', 'nv');
        if (!in_array($sex, $sexs)) {
            $sex = 'all';
        }
        $orders = array('rand', 'cut');
        $order = strtolower($order);
        if (!in_array($order, $orders)) {
            $order = 'rand';
        }
        $offset = max(0, intval($offset));
        if ($bang == 'free') {
        $key = '_book_customxianshi';
        } else {
            $key = '_bang' . $bang;
        }
        $result = $M_redis->get($key);
        $bModel = new \Client\Model\BookModel();
        $now = NOW_TIME;
        if ($result) {
            $books = array();
            $endtime = 0;
            $attr = array(
                'bid', 'intro', 'catename', 'classid', 'classid2', 'author', 'posttime', 'authorid', 'lzinfo', 'charnum', 'classname', 'smallclassname', 'subclassname', 'smallsubclassname',
                'last_updatetime', 'last_updatechpid', 'last_updatechptitle', 'last_updatejuanid', 'last_vipupdatetime', 'last_vipupdatechpid', 'last_vipupdatejuanid', 'last_vipupdatechptitle'
            );
            if (isset($result['booklists'])) {
                foreach ($result['booklists'] as $book) {
                    $bi = $bModel->getBook($book['bid']);
                    if ($sex == 'nan') {
                        //只取男频
                        if ($bi['sex_flag'] === 'nv') {
                            continue;
                        }
                    } else if ($sex == 'nv') {
                        //只取女频
                        if ($bi['sex_flag'] === 'nan') {
                            continue;
                        }
                    } else {
                        //不分男女
                    }
                    foreach ($attr as $key) {
                        $book[$key] = $bi[$key];
                    }
                    $book['cover'] = getBookfacePath($book['bid']);
                    $books[] = $book;
                }
$endtime = _getFreeEnd();
            } else {
            foreach ($result as $k => $list) {
                list($start, $end) = explode('-', $k);
                if ($start <= $now && $end >= $now) {
                    //合适地
                    foreach ($list as $book) {
                        if ($book['fromsite'] == $fromsite && ($book['freetype'] & $type)) {
                            $endtime = max($end, $endtime);
                            $bi = $bModel->getBook($book['bid']);
                            if ($sex == 'nan') {
                                //只取男频
                                if ($bi['sex_flag'] === 'nv') {
                                    continue;
                                }
                            } else if ($sex == 'nv') {
                                //只取女频
                                if ($bi['sex_flag'] === 'nan') {
                                    continue;
                                }
                            } else {
                                //不分男女
                            }
                            foreach ($attr as $key) {
                                $book[$key] = $bi[$key];
                            }
                            $book['cover'] = getBookfacePath($book['bid']);
                            $books[] = $book;
                            }
                        }
                    }
                }
            }
            if ($books) {
                if (count($books) > $num) {
                    if ($order == 'rand') {
                        $tmp = array_rand($books, $num);
                        $tmps = $books;
                        $books = array();
                        foreach ((array) $tmp as $key) {
                            $books[] = $tmps[$key];
                        }
                    } else {
                        $books = array_slice($books, $offset, $num);
                    }
                }
                $books = array_random($books);
                $result = array(
                    'status' => 1,
                    //'now'    => $now,
                    //'nowstr' => date('Y-m-d H:i:s', $now),
                    'end'    => $endtime - $now,
                    //'endstr' => date('Y-m-d H:i:s', $endtime),
                    'books'  => $books
                );
                $this->ajaxReturn($result);
            } else {
                $this->ajaxReturn('暂无数据');
            }
        } else {
            $this->ajaxReturn('暂无数据');
        }
    }

    /**
     * 获取指定小说的当前状态信息（限免、打折等）
     * @param int $bid 书号
     * @return array{
     *       bid 书号
     *       isDiscount 是否打折
     *       isBookDiscount 是否整本打折
     *       isFree 是否免费
     *       discount 折扣
     *       bookDiscount 整本折扣
     *       end 距离结束的时长（秒）
     *       lastupdatetime 最后更新时间
     *       lastupdatechpid 最后更新章节ID
     *       lastupdatechptitle 最后更新章节标题
     *       lzinfo 连载状态
     *   }
     */
    public function _getBookStatus() {
        global $M_redis;
        $bid = I('bid', 0, 'intval');
        if (!$bid) {
            $this->ajaxReturn('参数错误！');
        }
        $this->check_user_login();
        $uid = isLogin();
        $viplevel = 0;
        if($uid){
            $viplevel = session('viplevel');
        }
        //检查指定的小说是否存在
        $bModel = new \Client\Model\BookModel();
        $bookinfo = $bModel->getBook($bid);
        if (!$bookinfo) {
            //没有找到书
            $this->ajaxReturn('参数错误！');
        }
        if($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9){
            $this->ajaxReturn('对不起，本书暂不开放阅读');
        }
        //下架判断
        $isxiajia = false;
        if($bookinfo['publishstatus'] == 9){
            if(!$uid || $viplevel < 1){
                $isxiajia = true;
            }
        }
        
        $chapterlist = $bModel->getChplistByBid($bid);
        if (!$chapterlist) {
            //没有找到可用的章节
            $this->ajaxReturn('参数错误！');
        }
        $bang = I('bang', 'android_freenv_benjizhudai', 'trim');
        if ($bang == 'free') {
        $fromsite = C('CLIENT.' . CLIENT_NAME . '.fromsiteid');
        $client = new \HS\Yar("discountset");
        $result = $client->getDiscountCustomXianmianStatus($bid, $viplevel, $fromsite);
        if ($result) {
            $discount_set = $result['discount_set'];
            $is_discount = $discount_set['is_open'];
            $is_bookdiscount = $discount_set['is_bookdiscount'];
            //$custom_price_set = $result['custom_price_set'];
            $xianmian = $result['xianmian_set'];
            //$book_vip_price   = $result['pricebeishu'];
            //$xianmian_num     = $xianmian['num'];
        }
        } else {
            $key = '_bang' . $bang;
            $result = $M_redis->get($key);
            $xianmian = array();
            if(isset($result['booklists'])) {
                foreach($result['booklists'] as $row) {
                    if($row['bid'] == $bid) {
                        $xianmian = array(
                            'endtime'=> _getFreeEnd()
                        );
                    }
                }
            }
        }
        //读取COOKIE中的阅读记录
        $cookiebook = getBookCookieFav($bid);
        $chapterinfo = array();
        if (!$cookiebook) {
            $chapterinfo = array_shift($chapterlist['list']); //如果没有阅读记录，则取第一章
        } else {
            foreach ($chapterlist['list'] as $vo) {
                if ($vo['chapterid'] == $cookiebook['chapterid']) {
                    $chapterinfo = $vo;
                    break;
                }
            }
        }

        //最后更新数据
        $lastupdatetime = 0;
        $lastupdatechpid = 0;
        $lastupdatechptitle = '';
        if ($bookinfo['last_vipupdatetime']) {
            $lastupdatetime = $bookinfo['last_vipupdatetime'];
            $lastupdatechpid = $bookinfo['last_vipupdatechpid'];
//             $lastupdatechptitle = $bookinfo['last_vipupdatechptitle'];
        } else {
            $lastupdatetime = $bookinfo['last_updatetime'];
            $lastupdatechpid = $bookinfo['last_updatechpid'];
//             $lastupdatechptitle = $bookinfo['last_updatechptitle'];
        }
        //最后一章
        $lastchapter =  array_pop($chapterlist['list']);
        $info = array(
            'bid'                => $bid,
            'isDiscount'         => false,
            'isBookDiscount'     => false,
            'isFree'             => false,
            'discount'           => 0,
            'bookDiscount'       => 0,
            'end'                => 0,
            'lastupdatetime'     => $lastupdatetime,
            'lastupdatechpid'    => $lastupdatechpid,
            'lastupdatechptitle' => $lastchapter['title'],
            'lzinfo'             => $bookinfo['lzinfo'],
            'lastreadchporder'   => 0,
            'lastreadchpid'      => lastreadchpid,
            'lastreadchptitle'   => '',
            'isvip'              => false,
            'isxiajia'      => $isxiajia,
            'publishstatus' => $bookinfo['publishstatus'],
            'viplevel' => $viplevel,
        );
        //阅读记录
        if ($chapterinfo) {
            $info['lastreadchptitle'] = $chapterinfo['title'];
            $info['lastreadchpid'] = intval($chapterinfo['chapterid']);
            $info['lastreadchporder'] = intval($chapterinfo['chporder']);
            $info['isvip'] = intval($chapterinfo['isvip']);
        }
        if ($xianmian) {
            //限免
            $info['isFree'] = true;
            $info['end'] = $xianmian['endtime'] - NOW_TIME;
        } else if ($discount_set) {
            //打折
            if ($is_bookdiscount) {
                $info['isBookDiscount'] = true;
                $info['bookDiscount'] = $discount_set['book_discount'];
            }
            if ($is_discount) {
                $info['isDiscount'] = true;
                $info['discount'] = $discount_set['discount'];
            }
            $info['end'] = $discount_set['endtime'] - NOW_TIME;
        }
        unset($result);
        unset($client);
        $this->ajaxReturn($info);
    }

    /**
     * 搜索调用
     *
     * @param string $keyword 要搜索的关键字，默认为空
     * @param int $keywordtype 关键字搜索方式，1：综合查询，2：根据作品名查询，3：根据作者名查询，4：根据简介查新，5：根据标签查询。默认为1
     * @param int $Pclassids 主分类ID，多个以逗号分隔
     * @param int $classids 子分类ID，多个以逗号分隔
     * @param string $sex_flag 性别标识
     * @param int $free 是否免费标识，0：全部，1：免费，2：收费。默认为0
     * @param int $finish 完本标识，0：所有，1：已完结，2：未完结。默认为0
     * @param int $charnum 字数，0：所有，1:30万字以下，2：30-50万字，3:50-100万字，4:100字以上。默认为0
     * @param int $updatetime 更新时间，0：所有，1:3日内更新的，2:7日内更新的，3：半月内更新的，4：一月内更新的。默认为0
     * @param int $order 排序模式，0：相关度排序，1：按更新时间倒序，2：点击量倒序，3：字数倒序，5：人气值排序。默认为0
     * @param string $sortby 排序的字段名，如果留空的话，则由$order来决定，如果指定字段，则$order=0是顺序排列，$order=1是降序排列
     * @param int $pagesize 每页显示条数
     * @return array 成功后返回相应的数组，格式如下：
     *      array(
     *          bookinfo: array 小说列表,
     *          pagecount: int 总页数,
     *          pagelist: string 分页导航条,
     *          totalcount: int 总条数
     *      )
     */
    public function _search() {
        
        $callback = removeXSS(I("get.callback"));
        //查询成功输出
        $arr = C('MESSAGES.getbookroomsuc');
        $searchObj = new \Client\Model\SearchModel();
        $pclassidAry = array();
        $Pclassids = I('param.Pclassids', '', 'trim'); //  0：表示所有类别  其他：以逗号隔开的classid串
        if (!empty($Pclassids)) {
            $tmp = explode(',', $Pclassids);
            foreach ($tmp as $pclassid) {
                $pclassid = intval($pclassid);
                if ($pclassid) {
                    $pclassidAry[] = $pclassid;
                    if ($pclassid == 2) {
                        $sex_flag = 'nv';
                    }
                }
            }
        } else if ($sex_flag = I('sex_flag', '', 'trim')) {         //如果没有指定主分类，但是指定了性别，则取性别对应的书籍
            $pclassidAry = array_keys(C('CATEGORY'));
            if ($sex_flag == 'nan') {
                unset($pclassidAry[array_search(2, $pclassidAry, true)]);
            } elseif ($sex_flag == 'nv') {
                $pclassidAry = array(2);
            }
        }
        if (!$sex_flag) {
            $sex_flag = 'nan';
        }
        $classids = I('param.classids', '', 'trim');
        $classidAry = array();
        if (!empty($classids)) {
            $tmp = explode(',', $classids);

            foreach ($tmp as $classid) {
                $clasid = intval($classid);
                if ($classid) {
                    $classidAry[] = $classid;
                }
            }
        }

        $free = I('param.free', 0, 'intval'); //   0：所有   1：免费作品  2：收费作品
        switch ($free) {
            case 0:
                $shouquaninfo = array();
                break;
            case 1:
                $shouquaninfo = array(7, 8);
                break;
            case 2:
                $shouquaninfo = array(9);
                break;
        }


        $finish = I('param.finish', 0, 'intval'); //   0：所有  1：已完结   2：未完结
        if ($finish) {
            switch ($finish) {
                case 1:$finish = array(1);
                    break;
                case 2:$finish = array(0);
                    break;
            }
        } else {
            $finish = array(0, 1);
        }

        $charnum = I('param.charnum', 0, 'intval'); //   0：所有  1:30万字以下   2： 30-50万字  3:50-100万字   4:100字以上
        switch ($charnum) {
            case 0:
                $limitcharnum_min = 1;
                $limitcharnum_max = 50000000;
                break;
            case 1:
                $limitcharnum_min = 1;
                $limitcharnum_max = 300000;
                break;
            case 2:
                $limitcharnum_min = 300000;
                $limitcharnum_max = 500000;
                break;
            case 3:
                if (CLIENT_NAME === 'yqm' || (CLIENT_NAME === 'ios' && CLIENT_NAME >= '2.0.0')) {
                    $limitcharnum_min = 300000;
                    $limitcharnum_max = 1000000;
                } else {
                    $limitcharnum_min = 500000;
                    $limitcharnum_max = 1000000;
                }
                break;
            case 4:
                $limitcharnum_min = 1000000;
                $limitcharnum_max = 50000000; //5千万字,一般是不会打到达到这么高的字数的
                break;
        }
        $updatetime = I('param.updatetime', 0, 'intval'); //   0：所有  1:3日内更新的  2:7日内更新的  3：半月内更新的  4：一月内更新的
        switch ($updatetime) {
            case 0:$limitday = 0;
                break;
            case 1:$limitday = 3;
                break;
            case 2:$limitday = 7;
                break;
            case 3:$limitday = 15;
                break;
            case 4:$limitday = 30;
                break;
            default:$limitday = 0;
                break;
        }

        $keyword = I('param.keyword', '', 'removeXSS'); //：关键字

        $keywordtype = I('param.keywordtype', 1, 'intval'); //（跟keyword同时传）   1：综合查询  2：根据作品名查询  3：根据作者名查询  4：根据简介查新  5：根据标签查询

        $page = I(C('VAR_PAGE'), 1, 'intval'); //：取第几页的数据  从1开始计数
        $pagesize = I('param.pagesize', 30, 'intval'); //：一页取几条
        $offset = ($page - 1) * $pagesize;

        $sort_mod = I('param.order', 0, 'intval'); // 排序  0：相关度排序  1：按更新时间倒序 2：点击量倒序 3： 字数倒序,4人气值排序
        $sortby = I('param.sortby', '', 'trim');        //排序方式
        if (!$sortby) {             // 如果未指定排序方式，则按照排序模式进行排序
            switch ($sort_mod) {
                case 0:
                    $sortby = '';
                    break;
                case 1:
                    $sortby = 'all_updatetime';
                    break;
                case 2:
                    $sortby = 'total_hit';
                    break;
                case 3:
                    $sortby = 'charnum';
                    break;
                case 4:
                    $sortby = 'week_hit*0.1+shouquaninfo+lastweek_salenum+week_fav+(redticket*2)';
                    break;
            }
        } else {
            //如果指定了排序字段，则只能是降序排列(SPH_SORT_ATTR_ASC:2,SPH_SORT_ATTR_DESC:1)
            $sort_mod = 1;
        }

        $sourceidary = array(); //来源id书籍
        $publishstatus = array(2, 3, 4, 5, 6, 7, 8,9); //待审,已删等状态的书籍不显示

        $filter_mob_copyright = I('param.copyright', 1, 'intval'); //手机版权是否过滤
        
        $res = $searchObj->getSearchResult($keyword, $keywordtype, $pclassidAry, $classidAry, $limitcharnum_min, $limitcharnum_max, $limitday, $offset, $pagesize, $shouquaninfo, $finish, $sort_mod, $sortby, $sourceidary, $publishstatus, $filter_mob_copyright);
        if (CLIENT_NAME == 'yqm') {
            if ($res && $res['bookinfo']) {
                $authorids = array_column($res['bookinfo'], 'authorid');
                $bookModel = new \Client\Model\BookModel();
                $authormap = array("uid" => array("IN", implode(",", $authorids)));
                $authorArr = M("author")->where($authormap)->field("authorid,uid")->select();
                foreach ($res['bookinfo'] as $k => $v) {
                    if (is_null($v['bid'])) {
                        unset($res['bookinfo'][$k]);
                        continue;
                    }
                    //收藏、打赏、作者头像
                    $bookinfo = $bookModel->getBook($v['bid']);
                    if ($bookinfo) {
                        $res['bookinfo'][$k]['favnum'] = $bookinfo['total_fav'] ? $bookinfo['total_fav'] : 0;
                        $res['bookinfo'][$k]['pronum'] = $bookinfo['total_pro'] ? $bookinfo['total_pro'] : 0;
                        if ((int) $bookinfo['authorid']) {
                            foreach ($authorArr as $vo) {
                                if ($vo['authorid'] == $bookinfo['authorid']) {
                                    $authorimg = getUserFaceUrl($vo['uid']);
                                    break;
                                }
                            }
                        } else {
                            $authorimg = "";
                        }
                    } else {
                        $res['bookinfo'][$k]['favnum'] = 0;
                        $res['bookinfo'][$k]['pronum'] = 0;
                        $authorimg = "";
                    }
                    $res['bookinfo'][$k]['authorimg'] = $authorimg;
                }
            }
        }
        //记录热搜索词
        if ($res && $res['bookinfo'] && $keyword && $sex_flag && $page < 2) {
            $totalnum = count($res['bookinfo']);
            if ($totalnum) {
                if (!C('CACHE_PREFIX')) {
                    C('CACHE_PREFIX', 'txtxiaoshuo');
                }

                $redis = new \Think\Cache\Driver\Redis();
                $this_month = date('ym');
                $s_key = ':otsk:' . CLIENT_NAME . ':' . $this_month . ':' . $sex_flag;
                $keyword = trim($keyword);
                if (mb_strlen($keyword, 'utf-8') >= 2 && !is_numeric($keyword)) {
                    $redis->zIncrBy($s_key, 1, $keyword);
                    //热搜词以天为单位记录，在获取记录时，自动汇总本月的记录
                    if (method_exists($redis, 'zscore')) {
                        $res['search_times'] = $redis->ZSCORE($s_key, $keyword);
                    }
                }
            }
        }
        if ($res) {
            $totalnum = $res['totalcount'];
            if ($totalnum) {
                $cp = new \HS\Pager($totalnum, $pagesize, '', $page);
                $clientmethod = I('clientmethod', '', 'trim');
                if ($clientmethod) {
                    $cp->clientmethod = $clientmethod;
                }
                $res['pagelist'] = $cp->show();
            }
            if(CLIENT_NAME == 'myd'){
                //计算分页的起始页码
                //原程序去错了前端传的值，于是我在此处做了修改
                $pagelistsize = I('param.pagesize',10,'intval');
                $pageListStart = (ceil($page/$pagelistsize)-1) * $pagelistsize + 1;
                $res['pageliststart'] = $pageListStart;
                $bookModel = new \Client\Model\BookModel();
                foreach($res['bookinfo'] as $key => $val){
                    if($val['charnum'] > 10000){
                        $res['bookinfo'][$key]['charnum'] = round($val['charnum']/10000,1).'万';
                    }
                    $res['bookinfo'][$key]['lastupdatetime'] = friendly_date($val['updatetime']);
                    $chapterlist = $bookModel->getChplistByBid($val['bid']);
                    $res['bookinfo'][$key]['totalchapters'] = count($chapterlist['list']);
                    $res['bookinfo'][$key]['totalchapters'] = count($chapterlist['list']);
                }
            }
            $res['pagenum'] = $cp->nowPage;
            $res['status'] = 1;
            $res['totalchpnum'] = $res['bookinfo'][$key]['totalchapters'];
            $arr = $res;
        } else {
            if ($searchObj->getError()) {
                $this->error($searchObj->getError());
            }
            $arr = array("totalcount" => 0, "pagecount" => 0,'status'=>0,'message'=>'暂无更多书籍','totalchpnum'=>0);
        }
        unset($searchObj);
        echoJson($arr, $callback);
        exit;
    }

    /**
     * 章节目录
     *
     * @param int $bid 书籍id get
     * @param int $pagenum 页数
     *
     * @return array 章节的json数组
     */
    public function _chapterlist() {
        $bid = I('get.bid', 0, 'intval');
        $pagenum = I(C('VAR_PAGE'), 1, 'intval');
        $pagesize = I('get.pagesize', 0, 'intval');
        //初始化将返回的json的数组
        $output = array('status' => 0, 'list' => '', 'pagenum' => $pagenum, 'nextpagenum' => $pagenum + 1, 'totalnum' => 0, 'totalpage' => 0, 'message' => '');
        if (!$bid) {
            $output['message'] = '请指定要查找的作品';
            $this->ajaxReturn($output);
        }
        //获取缓存章节总数
        $bookModel = new \Client\Model\BookModel();
        $chapters = $bookModel->getChplistByBid($bid);
        if (!$chapters['list']) {
            $output['message'] = "暂无章节信息";
            $this->ajaxReturn($output);
        }
        //取章节列表
        $chapterlist = $chapters['list'];
        if (!$chapterlist) {
            $output['message'] = '暂无章节';
            $this->ajaxReturn($output);
        }
        //分页量
        $pagesize = $pagesize ? $pagesize : 50;
        $totalnum = count($chapterlist);
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        $clientmethod = I('get.clientmethod', '', 'trim');
        if ($clientmethod) {
            $pageModel->clientmethod = $clientmethod;
        }
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('first', '|<');
        $pageModel->setConfig('last', '>|');
        $pagelist = $pageModel->show();

        //升降序
        $sortby = I('get.sortby', 'ASC', 'trim,strtoupper');
        //防止非法字符串
        if (!in_array($sortby, array('ASC', 'DESC'))) {
            $sortby = 'ASC';
        }
        if ($sortby == 'ASC') {
            ksort($chapterlist);
        } else {
            krsort($chapterlist);
        }

        $list = array_slice($chapterlist, $pageModel->firstRow, $pageModel->listRows);
        if (CLIENT_NAME == 'yqm') {
            //插画信息
            $chapterids = array_column($list, 'chapterid');
            $chahuaModel = new \Client\Model\ChahuaPicModel();
            $cmap = array(
                'bid'       => $bid,
                'chapterid' => array('IN', implode(',', $chapterids)),
            );
            $chahuainfo = $chahuaModel->field('chapterid,type')->where($cmap)->select();
        }
        $juantitle = $list[0]['juantitle'];
        foreach ($list as $k => $chapter) {
            if ($k) {
                if ($chapter['juantitle'] == $juantitle) {
                    $list[$k]['juantitle'] = '';
                }
                if ($chapter['juantitle'] != $juantitle && $chapter['juantitle']) {
                    $juantitle = $chapter['juantitle'];
                }
            }
            if (CLIENT_NAME == 'yqm') {
                //插画
                $list[$k]['chahua'] = 0;
                $list[$k]['renshe'] = 0;
                if ($chahuainfo) {
                    foreach ($chahuainfo as $vo)
                        if ($vo['chapterid'] == $chapter['chapterid'] && $vo['type'] == 0) {
                            $list[$k]['chahua'] = 1;
                        }
                    if ($vo['chapterid'] == $chapter['chapterid'] && $vo['type'] == 1) {
                        $list[$k]['renshe'] = 1;
                    }
                }
            }
        }

        //检查登录
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            // 获得用户已订购章节ids
//             $client    = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
            $client = new \HS\Yar("dingoujson");
            $vipchpstr = $client->checkUserAll($bid, $uid, 'ids');
            $vipchparr = explode(',', $vipchpstr);

            //判断用户是否购买某VIP章节
            foreach ($list as &$val) {
                if (in_array($val['chapterid'], $vipchparr)) {
                    $val['isorder'] = 1;
                } else {
                    $val['isorder'] = 0;
                }
            }
        }

        //截取章节目录数组
        //$startnum = ($pagenum - 1) * $pagesize;
        //显示各书卷名
        $output['list'] = $list;

        //其他返回值
        $output['status'] = 1;
        $output['pagelist'] = $pagelist;
        $output['pagenum'] = $pageModel->nowPage;
        $output['nextpagenum'] = $pageModel->nowPage + 1;
        $output['prepagenum'] = max($pageModel->nowPage - 1, 1);
        $output['totalnum'] = $pageModel->totalRows;
        $output['totalpage'] = ceil($totalnum / $pagesize);
        $output['sortby'] = $sortby;
        $this->ajaxReturn($output);
    }

    /**
     * 书评
     *
     * @param int $bid 书籍id get
     * @param string maction 判断请求方式(getcommentlist,ajax请求)
     * @param int $totalnum 总记录条数 get(ajax)
     * @param int $pagenum 当前页码 get(ajax)
     * @param int $total_page 总页码 get(ajax)
     *
     * @return array
     */
    public function _comment() {
        $ajaxreturn = array("status" => 0, "message" => "", "url" => "", "doublesort" => 0);
        $bid = I("get.bid", 0, "intval");
        /* 当前页码 */
        $pagenum = I(C('VAR_PAGE'), 1, 'intval');
        /* 总的记录条数 */
        $totalnum = I("get.totalnum", 0, "intval");
        $ajaxreturn = array('status' => 0, 'list' => '', 'pagenum' => $pagenum, 'nextpagenum' => $pagenum + 1, 'totalnum' => $totalnum, 'totalpage' => 0);
        if (!$bid) {
            $ajaxreturn['message'] = '无法确认书籍';
            $this->ajaxReturn($ajaxreturn);
            //$this->error('无法确认书籍', '', $ajaxreturn);
        }
        //未登录查询条件
        $where = array(
            'n.bid'          => $bid,
            'n.deleted_flag' => array('neq', 1),
            'n.content'      => array('neq', ''),
            'forbidden_flag' => array('neq', 1),
        );
        //登录的查询条件
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            unset($where);
            $where = array(
                'n.bid'          => $bid,
                'n.deleted_flag' => array('neq', 1),
                'n.content'      => array('neq', ''),
                array(
                    'n.forbidden_flag' => array('neq', 1),
                    'n.uid'            => $uid,
                    '_logic'           => 'OR',
                ),
            );
        }
        $comModel = new \Client\Model\NewcommentModel();
        if (!$totalnum) {
            //获取书评缓存设置-评论总数
            $commentcache = $comModel->get_comment_set_cache($bid);
            //如果缓存设置不存在，则从数据库查询设置
            if (intval($commentcache['totalnum']) < 1) {
                $map = array("bid" => $bid);
                $totalnum = M('newcomment')->where($map)->count();
            } else {
                $totalnum = $commentcache['totalnum'];
            }
        }
        if (!$totalnum) {
            $ajaxreturn['message'] = '暂无评论';
            $this->ajaxReturn($ajaxreturn);
        }
        /* 每页显示的书评数 */
        $maxnum = I('get.pagesize', C('COMMENTSIZE'), 'intval');
        $pageModel = new \HS\Pager($totalnum, $maxnum);
        $pagelist = $pageModel->show();
        /**
         * 从数据库获取书评-wis_newcomment
         * $where bid =$bid
         * 排序条件order by doublesort DESC,last_reply_date DESC
         */
        $res = M('newcomment')->alias('n')->join('__READ_USER__ as u ON n.uid = u.uid', 'LEFT')->
                field('u.username as uname,u.nickname as unick,n.comment_id,n.uid,n.title,n.last_reply_date,n.creation_date,n.content,n.uid,n.zan_amount,n.reply_amount,n.bid, n.forbidden_flag,n.highlight_flag,n.is_lcomment,n.doublesort')->
                where($where)->order('n.last_reply_date DESC,n.alltop DESC')->
                limit($pageModel->firstRow, $pageModel->listRows)->select();
        $comments = array();
        $lou_count = 1;
        foreach ($res as $cokey => $coval) {
            //用户头像
            $coval['avatar'] = '';
            if ((int) $coval['uid']) {
                $avatar = getUserFaceUrl($coval['uid']);
            }
            $coval['avatar'] = $avatar;
            //格式化时间
            $coval['sendtime'] = date("Y-m-d H:i:s", $coval['last_reply_date']);
            //补充用户信息
            $coval['username'] = $coval['uname'];
            $coval['nickname'] = $coval['unick'] ? $coval['unick'] : $coval['uname'];
            $coval['time'] = friendly_date($coval['creation_date'], 'mohu');
            $coval['content'] = $coval['content'];
            $coval['lou'] = ($pagenum - 1) * $maxnum + $lou_count;
            $lou_count++;
            $comments[] = $coval;
        }
        if ($comments) {
            $ajaxreturn['status'] = 1;
            $ajaxreturn['list'] = $comments;
            $ajaxreturn['pagenum'] = $pageModel->nowPage;
            $ajaxreturn['nextpagenum'] = $pageModel->nowPage + 1;
            $ajaxreturn['totalnum'] = $totalnum;
            $ajaxreturn['totalpage'] = $pageModel->totalPages;
        } else {
            //$ajaxreturn['message'] = '没有书评';
        }

        $this->ajaxReturn($ajaxreturn);
    }

    /**
     * yqm书评
     * @param int $bid
     * @param int $pagesize 翻页量
     * @param string $type 排序（zan=按点亮数，否则发表时间）
     *
     * @return array
     */
    public function _getComments_yqm() {
        $pagenum = I('get.pagenum', 1, 'intval');
        $pagesize = I('get.pagesize', C('COMMENTSIZE'), 'intval');
        $totalnum = I('get.totalnum', 0, 'intval');
        $clientMethod = I('get.clientmethod', '', 'trim');
        $bid = I('get.bid', 0, 'intval');
        $type = I('get.type', '', 'trim');
        $output = array('status' => 0, 'message' => '', 'url' => '', 'totalnum' => $totalnum, 'pagenum' => $pagenum);
        if (!$bid) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $where = array(
            'bid'            => $bid,
            'deleted_flag'   => array('neq', 1),
            'forbidden_flag' => array('neq', 1),
            'content'        => array('neq', ''),
        );
        //登录的查询条件
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $where = array(
                'bid'          => $bid,
                'deleted_flag' => array('neq', 1),
                'content'      => array('neq', ''),
                array(
                    'forbidden_flag' => array('neq', 1),
                    'uid'            => $uid,
                    '_logic'         => 'OR',
                ),
            );
        }
        $comModel = new \Client\Model\NewcommentModel();
        if (!$totalnum) {
            //取缓存的评论总数
            $comAry = $comModel->getCommentByBid($bid);
            $totalnum = intval($comAry['totalnum']);
            if ($totalnum < 1) {
                $totalnum = $comModel->where($where)->count();
            }
        }
        if (!$totalnum) {
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if ($clientMethod) {
            $pageModel->clientmethod = $clientMethod;
        }
        $pageModel->setConfig('first', '|<');
        $pageModel->setConfig('prev', '<');
        $pageModel->setConfig('next', '>');
        $pageModel->setConfig('last', '>|');
        $pagelist = $pageModel->show();
        //排序
        $sortBy = 'creation_date DESC,alltop DESC';
        if ($type == 'zan') {
            $sortBy = 'zan_amount DESC';
        }
        $comments = $comModel->field('comment_id,username,nickname,uid,content,creation_date,zan_amount,forbidden_flag')->
                where($where)->order($sortBy)->limit($pageModel->firstRow, $pageModel->listRows)->select();
        if (!$comments) {
            $output['message'] = '暂无记录，请稍后再试';
            $this->ajaxReturn($output);
        }
        //查询有效回复数
        $cids = array_column($comments, 'comment_id');
        $rmap = array(
            'delete_flag' => array('neq', 1),
            'content'     => array('neq', ''),
            'comment_id'  => array('IN', implode(',', $cids)),
        );
        if (!$uid) {
            $rmap['forbidden_flag'] = array('neq', 1);
        } else {
            $rmap[] = array(
                'forbidden_flag' => array('neq', 1),
                'uid'            => $uid,
                '_logic'         => 'OR',
            );
        }
        $replies = M('newcomment_reply')->field('comment_id,count(comment_id) as reply_amount')->where($rmap)->group('comment_id')->select();
        $floor = 1;
        foreach ($comments as &$comment) {
            //用户头像
            $comment['avatar'] = getUserFaceUrl($comment['uid']);
            $comment['time'] = date('Y-m-d H:i', $comment['creation_date']);
            $comment['floor'] = ($pagenum - 1) * $pagesize + $floor;
            $floor++;
            //计算评论内容是否超过300
            $comment['long'] = 0;
            if (mb_strlen($comment['content'], 'utf-8') > 300) {
                $comment['long'] = 1;
            }
            //回复数
            $comment['reply_amount'] = 0;
            if ($replies) {
                foreach ($replies as $reply) {
                    if ($reply['comment_id'] == $comment['comment_id']) {
                        $comment['reply_amount'] = $reply['reply_amount'];
                    }
                }
            }
        }
        $output['status'] = 1;
        $output['list'] = $comments;
        $output['pagelist'] = $pagelist;
        $output['totalnum'] = $totalnum;
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，评论
     * @param int $bid get
     * @param int $type 类型(=1最新，=2精华，=3章节,默认最新)
     * @param int $pagelistsize（分页的页码分页量,即每页显示多少页）
     * 
     */
    public function _getComments_myd(){
        $output = array('status'=>0,'message'=>'','url'=>'','list'=>array());
        $bid = I('get.bid',0,'intval');
        if(!$bid){
            $output['message'] = '书籍不存在';
            $this->ajaxReturn($output);
        }
        $chapterid = I('get.chapterid',0,'intval');
        $type = I('get.type',1,'intval');
        $pagenum = I('get.pagenum', 1, 'intval');
        $pagesize = I('get.pagesize', C('COMMENTSIZE'), 'intval');
        $totalnum = I('get.totalnum', 0, 'intval');
        $pagelistsize = I('get.pagelistsize',10,'intval');
        $commentModel = new \Client\Model\NewcommentModel();
        $map = array(
            'bid'            => $bid,
            'deleted_flag'   => array('neq', 1),
            'content'        => array('neq', ''),
        );
        $this->check_user_login();
        $uid = isLogin();
        if($uid){
            $map[] = array(
                'forbidden_flag'=>array('neq', 1),
                'uid'=>$uid,
                '_logic'=>'OR'
            );
        }else{
            $map['forbidden_flag'] = array('neq', 1);
        } 
        if ($type == 2) {
            //精华(highlight_flag = 1 or is_lcomment = 1) and zan_amount >0
            //   (highlight_flag = 0 or is_lcomment = 0) and zan_amount > 4
            $map[] = array(
                '_logic'=>'OR',
                array(
                    array('highlight_flag'=>1,'is_lcomment'=>1,'_logic'=>'OR'),
                    'zan_amount'=>array('GT',0)
                ),
                array(
                    'zan_amount'=>array('GT',4)
                )
            );
        } elseif ($type == 3){
            //章节 chapterid != 0
            if($chapterid){
                $map['chapterid'] = $chapterid;
            }else{
                $map['chapterid'] = array('GT',0);
            }
        }
        if(!$totalnum){
            $totalnum = $commentModel->where($map)->count();
        }
        if(!$totalnum){
            $output['message'] = '暂无评论';
            $this->ajaxReturn($output);
        }
        $pageModel = new \HS\Pager($totalnum,$pagesize);
        $pagelist = $pageModel->show();
        $list = $commentModel->
                field('comment_id,username,bid,title,content,creation_date,forbidden_flag,deleted_flag,highlight_flag,reply_amount,
                    is_locked,doublesort,is_lcomment,nickname,zan_amount,chapterid,chaptername,uid')->
                where($map)->order('doublesort DESC,creation_date DESC')->limit($pageModel->firstRow,$pageModel->listRows)->select();
        if(!$list || !is_array($list)){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        foreach($list as $key=>$val){
            //取头像
            $list[$key]['avatar'] = getUserFaceUrl($val['uid']);
            $list[$key]['date'] = friendly_date($val['creation_date']);
            //计算是否是精华评论
            if($val['zan_amount'] > 4 || (($val['is_lcomment'] == 1 || $val['highlight_flag'] = 1) && $val['zan_amount'] > 0)){
                $list[$key]['iselite'] = 1;
            }
        }
        //计算当前页要显示的分页起始页码
        $pageliststart = (ceil($pagenum/$pagelistsize) - 1) * $pagelistsize + 1;
        $output['status'] = 1;
        $output['list'] = $list;
        $output['pagenum'] = $pagenum;
        $output['totalpage'] = $pageModel->totalPages;
        $output['pageliststart'] = $pageliststart;
        $this->ajaxReturn($output);
    }

    /**
     * 添加书籍评论
     *
     * @param int $bid 书籍id post
     * @param string $content 评论内容 post
     *
     * @return string 添加成功/失败信息
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     */
    public function _addComment() {
        $output = array("status" => 0, "message" => "", "url" => "","needlogin"=>0);
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            $output['needlogin'] = 1;
            $output['message'] = "请先登录";
            $output['url'] = url("User/login", array(), 'do');
            $this->ajaxReturn($output);
        }
        $bid = I('post.bid', 0, 'intval');
        if (!$bid) {
            $output['message'] = "非法参数";
            $this->ajaxReturn($output);
        }
        $content = I("post.content", "", 'trim,removeXSS');
        if (strlen($content) <= 0) {
            $this->ajaxReturn('评论不能为空');
        } elseif (mb_strlen($content, "utf-8") < C('COMMENTMINSIZE') || mb_strlen($content, "utf-8") > C('COMMENTMAXSIZE')) {
            $output['message'] = '书评字数必须在' . C('COMMENTMINSIZE') . '字和' . C('COMMENTMAXSIZE') . '字之间!';
            $this->ajaxReturn($output);
        }
        //检查违禁词
        $comModel = new \Client\Model\NewcommentModel();
        $badwords = $comModel->getBreakWordR($content);
        if ($badwords) {
            $output['message'] = '评论内容不能含有违禁词';
            $this->ajaxReturn($output);
        }
        /* 检查某个用户是否被禁止在某个书内发言 */
        $forbiddenWhere = array(
            'bid' => $bid,
            'uid' => $uid,
        );
        $forbiddenUser = M('newcomment_killuser')->where($forbiddenWhere)->select();
        if (!empty($forbiddenUser)) {
            $output['message'] = '对不起,您已被禁止评论该书';
            $this->ajaxReturn($output);
        }
        //喵阅读，处理一下表情
        if(CLIENT_NAME == 'myd'){
            if(preg_match("/\[em:(\d)+:\]/iu",$content)){
                $content = str_replace('[em:', '<img src='.C('TMPL_PARSE_STRING.__IMG__') . '/face' . '/',str_replace(':]', '.gif />',$content));
            }    
        }
        /* 评论间隔30秒 */ //TODO:没效果？
        $key = 'lastCommentTime#' . get_client_ip() . session_id();
        $cacheObj = new \HS\MemcacheRedis();
        $lastCommentTime = $cacheObj->get($key);
        $btTime = intval(C('ALLOWCOMMENT', null, 30));
        $btTime = $btTime? : 30;
        if ((time() - $lastCommentTime) < $btTime) {
            $output['message'] = "评论时间间隔为" . $btTime . '秒,请稍后再试。';
            $this->ajaxReturn($output);
        }

        //计算长度,大于300 is_lcomment为1,否则为0
        $charnum = mb_strlen($content, 'utf-8');
        if ($charnum > 300) {
            $is_lcomment = 1;
        } else {
            $is_lcomment = 0;
        }
        //插入数据库并更新积分,调用Book/Model的Comment->add();
        $user = session();
        $comModel = new \Client\Model\NewcommentModel();
        //返回comment_id
        $_old = C('TOKEN_ON');
        C('TOKEN_ON', false);
        $ret = $comModel->add('', $content, $bid, $user, 1, $is_lcomment);
        C('TOKEN_ON', $_old);
        if ($ret > 0) {
            $cacheObj->setMc($key, NOW_TIME, $btTime);

            $arr = array();
            $arr["comment_id"] = $ret;
            $arr['is_lcomment'] = $is_lcomment;
            $arr['forbidden_flag'] = 1;
            $arr['uid'] = $uid;
            $arr['nickname'] = $user['nickname'];
            $arr['time'] = friendly_date(NOW_TIME, 'mohu');
            if (CLIENT_NAME == 'yqm') {
                $arr['time'] = date('Y-m-d', NOW_TIME);
                $arr['long'] = $is_lcomment;
                $arr['uid'] = $uid;
            }
            $arr['content'] = $content;
            $arr['bid'] = $bid;
            $arr['zan_amount'] = 0;
            $arr['reply_amount'] = 0;
            if (isInWechat()) {
                $sucurl = url("Book/comment", array("bid" => $bid, 'rand' => randomstr(8)), 'do');
            } else {
                $sucurl = url("Book/comment", array("bid" => $bid), 'do');
            }
            $output['status'] = 1;
            $output['list'][] = $arr;
            $output['message'] = '评论成功';
            $output['url'] = $sucurl;
            $this->ajaxReturn($output);
        } else {
            $output['message'] = C('MESSAGES.commentfail.msg');
            $this->ajaxReturn($output);
        }
    }

    /**
     * 书评回复
     *
     * @param int $comment_id 书评id get
     * @param int $pagenum 当前页码 get
     * @param int $totalnum 总记录条数 get
     * @param int $pagelistsize 每页显示多少页
     *
     * @return array 书评回复数组
     */
    public function _replyComment() {
        $comment_id = I('get.comment_id', 0, 'intval');
        $pagenum = I(C('VAR_PAGE'), 1, 'intval');
        $totalnum = I('get.totalnum', 0, 'intval');
        $pagesize = I('get.pagesize', C('REPLYSIZE'), 'intval');
        $clientmethod = I('get.clientmethod', '', 'trim');
        $this->check_user_login();
        $uid = isLogin();
        if (CLIENT_NAME == 'yqm') {
            $type = I('get.type', 0, 'intval'); //收起（1）/展开（0）标志/(3)评论详情页
        }
        $data = array('status' => 0, 'list' => '', 'pagenum' => $pagenum, 'nextpagenum' => $pagenum + 1, 'totalnum' => $totalnum, 'totalpage' => 0, 'message' => '', 'type' => $type);
        if (!$comment_id) {
            $data['message'] = '无法获得书评';
            $this->ajaxReturn($data);
        }
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            $data['message'] = '无法获得书籍信息';
            $this->ajaxReturn($data);
        }

        $restart = ($pagenum - 1) * $pagesize;
        $replyModel = M('newcomment_reply');
        if (!$totalnum) {
            $talmap = array(
                'bid'            => $bid,
                "comment_id"     => $comment_id,
                'delete_flag'    => array('neq', 1),
                'forbidden_flag' => array('neq', 1),
                'content'        => array('neq', ''),
            );
            if ($uid) {
                $talmap = array(
                    "comment_id"  => $comment_id,
                    'delete_flag' => array('neq', 1),
                    'content'     => array('neq', ''),
                    array(
                        'forbidden_flag' => array('neq', 1),
                        'uid'            => $uid,
                        '_logic'         => 'OR',
                    ),
                );
            }
            $totalnum = $replyModel->where($talmap)->count();
        }
        if (!$totalnum) {
            $data['message'] = '暂无记录';
            $this->ajaxReturn($data);
        }
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        if (CLIENT_NAME == 'yqm' || (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0')) {
            if ($clientmethod) {
                $pageModel->clientmethod = $clientmethod;
            }
            if ($type == 3) {
                $pageModel->setConfig('prev', '<');
                $pageModel->setConfig('next', '>');
                $pageModel->setConfig('first', '|<');
                $pageModel->setConfig('last', '>|');
            } else {
                $pageModel->setConfig('next', '查看更多');
                $pageModel->setConfig('theme', '%DOWN_PAGE%');
            }
        }
        $pagelist = $pageModel->show();
        $map = array(
            'n.bid'            => $bid,
            'n.delete_flag'    => array('neq', 1),
            'n.forbidden_flag' => array('neq', 1),
            'n.content'        => array('neq', ''),
            'n.comment_id'     => $comment_id,
        );
        if ($uid) {
            $map = array(
                'n.bid'         => $bid,
                'n.delete_flag' => array('neq', 1),
                'n.comment_id'  => $comment_id,
                array(
                    'n.forbidden_flag' => array('neq', 1),
                    'n.uid'            => $uid,
                    '_logic'           => 'OR',
                ),
            );
        }
        //收起则只取2条
        if (CLIENT_NAME == 'yqm') {
            if (isset($type) && $type == 1 && $totalnum > 2) {
                $pageModel->firstRow = $totalnum - 2;
                $pageModel->listRows = 2;
            }
        }
        $replies = $replyModel->alias('n')->join('__READ_USER__ as u ON n.uid = u.uid')->field('u.username,u.nickname,n.*')->
                where($map)->limit($pageModel->firstRow, $pageModel->listRows)->select();
        if ($replies) {
            $data['list'] = $replies;
            foreach ($replies as $key => $vo) {
                $data['list'][$key]['content'] = $vo['content'];
                $data['list'][$key]['time'] = friendly_date($vo['creation_date'], 'mohu');
                //补充用户信息
                $data['list'][$key]['nickname'] = $vo['nickname'] ? $vo['nickname'] : $vo['username'];
                //楼层
                $data['list'][$key]['floor'] = $restart + $key + 1;
                //头像
                $data['list'][$key]['avatar'] = '';
                if (intval($vo['uid'])) {
                    $data['list'][$key]['avatar'] = getUserFaceUrl($vo['uid']);
                } else {
                    $data['list'][$key]['avatar'] = '';
                }
            }
            $data['status'] = 1;
            if ($pagelist) {
                $data['pagelist'] = $pagelist;
            }
            //喵阅读，计算当前页显示的起始页码
            if(CLIENT_NAME == 'myd'){
                $pageListSize = I('get.pagelistsize',10,'intval');
                $pageliststart = (ceil($pagenum/$pageListSize) -1) * $pageListSize + 1;
                $data['pageliststart'] = $pageliststart;
            }
            $data['pagenum'] = $pageModel->nowPage;
            $data['nextpagenum'] = $pageModel->nowPage + 1;
            $data['totalpage'] = $pageModel->totalPages;
            $data['totalnum'] = $totalnum;
        } else {
            $data['message'] = '暂无回复';
        }

        $this->ajaxReturn($data);
    }

    /**
     * 添加书评回复
     *
     * @param int $uid 用户id session
     * @param Int $bid 书籍id post
     * @param int $commentid 书评id post
     *
     * @return string 添加成功/失败信息
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     */
    public function _addreply() {
        $this->check_user_login();
        $uid = isLogin();
        $data = array();
        if (!$uid) {
            $data['status'] = 0;
            $data['needlogin'] = 1;
            $data['message'] = C('MESSAGES.nologin.msg');
            $data['url'] = url('User/login', array(), 'do');
            $this->ajaxReturn($data);
        } else {
            $user = session();
        }
        $bid = I('post.bid', 0, 'intval');
        if (!$bid) {
            //返回首页，因为bid不存在使得返回书评不可能
            $data['message'] = C('MESSAGES.paramerror.msg');
            $data['status'] = 0;
            $data['url'] = $this->M_forward;
            $this->ajaxReturn($data);
        }
        $commentid = I('post.comment_id', 0, 'intval');
        if (!$commentid) {
            //返回到书评页
            $data['message'] = C('MESSAGES.paramerror.msg');
            $data['status'] = 0;
            $data['url'] = url('Book/comment', array('bid' => $bid), 'do');
            $this->ajaxReturn($data);
        }
        $map = array();
        $map['comment_id'] = $commentid;
        $tmp_com = M('newcomment')->where($map)->find();
        if ($tmp_com['is_locked'] == 1) {
            $data['status'] = 0;
            $data['message'] = '帖子被锁定不能回复';
            $data['url'] = url('Book/replyComment', array('bid' => $bid, 'comment_id' => $commentid), 'do');
            $this->ajaxReturn($data);
        }
        $map = array();
        $map['uid'] = $uid;
        $map['bid'] = $bid;
        $killuser = M('newcomment_killuser')->where($map)->select();
        if (!empty($killuser)) {
            $data['status'] = 0;
            $data['message'] = '您已被禁止评论';
            $data['url'] = url('Book/comment', array('bid' => $bid), 'do');
            $this->ajaxReturn($data);
        }
        $content = I("param.content", "", "trim,removeXSS");
        $nickname = I('post.nickname', '', 'trim');
        if (!$content || mb_strlen($content, "utf-8") > C('COMMENTMAXSIZE') || mb_strlen($content, "utf-8") < C('COMMENTMINSIZE')) {
            $data['status'] = 0;
            $data['message'] = '回复字数范围应在' . C('COMMENTMINSIZE') . '-' . C('COMMENTMAXSIZE') . '之间';
            $data['url'] = url('Book/replyComment', array('bid' => $bid, 'comment_id' => $commentid), 'do');
            $this->ajaxReturn($data);
        }
        //检查违禁词
        $comModel = new \Client\Model\NewcommentModel();
        $badwords = $comModel->getBreakWordR($content);
        if ($badwords) {
            $data['status'] = 0;
            $data['message'] = '回复内容不能含有违禁词';
            $this->ajaxReturn($data);
        }
        if ($nickname) {
            $content = '<b>@' . $nickname . '</b> ' . $content;
        }
        //喵阅读，处理一下表情
        if(CLIENT_NAME == 'myd'){
            if(preg_match("/\[em:(\d)+:\]/iu",$content)){
                $content = str_replace('[em:', '<img src='.C('TMPL_PARSE_STRING.__IMG__') . '/face' .'/',str_replace(':]', '.gif />',$content));
            }    
        }
        //回复时间间隔控制
        $key = 'lastReplyTime#' . get_client_ip() . session_id();
        $cacheObj = new \HS\MemcacheRedis();
        $lastCommentTime = $cacheObj->get($key);
        $btTime = intval(C('ALLOWREPLY', null, 30));
        $btTime = $btTime? : 30;

        if ($lastCommentTime) {
            $data['status'] = 0;
            $data['message'] = '请在' . $btTime . '秒后再次回复！';
            $data['url'] = url('Book/comment', array('bid' => $bid), 'do');
            $this->ajaxReturn($data);
        }

        //添加回复
        $commentmodel = new \Client\Model\NewcommentModel();
        $ret = $commentmodel->addreply($commentid, $bid, $content, $user);
        if ($ret > 0) {
            $cacheObj->setMc($key, time(), $btTime);
            $arr = array();
            $arr['time'] = friendly_date(NOW_TIME, 'mohu');
            $arr['comment_id'] = $ret;
            if (CLIENT_NAME == 'yqm') {
                $arr['comment_id'] = $commentid;
                $arr['reply_id'] = $ret;
                $arr['time'] = date('Y-m-d', NOW_TIME);
                $arr['avatar'] = getUserFaceUrl($uid);
            }
            $arr['forbidden_flag'] = 1;
            $arr['uid'] = $uid;
            $arr['content'] = $content;
            $arr['nickname'] = $user['nickname'] ? $user['nickname'] : $user['username'];
            $arr['url'] = url('Book/replyComment', array('bid' => $bid, 'comment_id' => $commentid), 'do');
            $data['status'] = 1;
            $data['url'] = url('Book/replyComment', array('bid' => $bid, 'comment_id' => $commentid), 'do');
            $data['message'] = '评论成功';
            $data['list'][] = $arr;
        } else {
            $data['content'] = $content;
            $data['message'] = C('MESSAGES.addreplyfail.msg');
            $data['status'] = 0;
        }
        unset($commentmodel);
        $this->ajaxReturn($data);
    }

    /**
     * 点赞(暂未使用)
     *
     * @param int $uid 用户id session
     * @param int $commentid 书评id get
     *
     * @return string 成功/失败信息
     */
    public function _sendZan() {
        $this->check_user_login();
        $uid = isLogin();
        $output = array('status' => 0, 'message' => '', "url" => "");
        if (!$uid) {
            $output['message'] = '请先登录';
            $this->ajaxReturn($output);
        }
        $commentid = I("get.comment_id", 0, 'intval');
        if (!$commentid) {
            $output['message'] = "参数错误";
            $this->ajaxReturn($output);
        }
        //点亮记录
        if (CLIENT_NAME == 'yqm') {
            $cacheModel = new \HS\MemcacheRedis();
            $key = 'Zanlog#' . $uid . "#" . $commentid;
            $lastZanTime = $cacheModel->get($key);
            if ($lastZanTime) {
                $output['status'] = 2;
                $output['message'] = '点亮过于频繁，请稍后再试';
                $this->ajaxReturn($output);
            }
        }

        $commentmodel = new \Client\Model\NewcommentModel();
        //TODO:原站的点赞分为评论和回复两种，具体可见原站的comment.php的poohand,PC端只有书评点赞
        $ret = $commentmodel->addZan($commentid);
        if ($ret == 'zansuc') {
            if (CLIENT_NAME == 'yqm') {
                $cacheModel->setMc($key, NOW_TIME, C('ZANTIME'));
            }

            $where = array('comment_id' => $commentid);
            $output['zan_amount'] = $commentmodel->where($where)->getField('zan_amount');
            unset($commentmodel);
            $output['status'] = 1;
            $output['message'] = '点赞成功';
        } else {
            $output['message'] = '点赞失败';
        }
        $this->ajaxReturn($output);
    }

    /*
     * 送红票
     *
     * @param int $uid 用户id session
     * @param int $bid 书籍id post
     * @param int $num 红票数量 post
     * @param string $content 评论内容 post
     *
     * @return 成功/失败信息
     */
    public function _sendRedTicket() {
        if (date('Y-m-01', time()) == date('Y-m-d', time()) && intval(date('H', time())) < 12) {
            $data['message'] = C('MESSAGES.wrongtickettime.msg');
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        $addtime = time();
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $user = session();
        } else {
            $data['status'] = 0;
            $data['message'] = '请先登录';
            $this->ajaxReturn($data);
        }
        $bid = I('post.bid', 0, 'intval');
        if (!$bid) {
            $data['message'] = C('MESSAGES.paramerror.msg');
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }

        $num = I('post.num', 0, 'intval');
        if (!$num || $num < 0) {
            $data['message'] = '红包数量应该大于1';
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        $content = I("post.content", "", 'trim');
        if (strlen($content) > 1000) {
            $data['message'] = "评论过长";
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        $bookmodel = new \Client\Model\BookModel();
        $book = $bookmodel->getBook($bid);
        if ($book === false) {
            $data['status'] = 0;
            $data['message'] = '无法获取书籍';
            $this->ajaxReturn($data);
        }
        unset($bookmodel);
        if ($book['shouquaninfo'] < 9) {
            $data['message'] = '书籍暂无权限';
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        $commentmodel = new \Client\Model\NewcommentModel();
        $uModel = new \Client\Model\UserModel();
        $userLastTicket = $uModel->getLastTicket($uid);
        if ($num > intval($userLastTicket)) {
            $data['message'] = '红票数量不足';
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
        // 先判断当月可用红票（2017.1.20起取消每天/每月的红票限额）
//         $sendMonthCount = $uModel->getSendTCount($uid, $bid, 1);
//         $totalMonthCount = C("TICKETSET")["sendmonthmax"];
//         $lastMonthCount = $totalMonthCount - ($sendMonthCount + $num);
//         //$this->ajaxReturn($lastMonthCount);
//         if ($lastMonthCount > 0) {
//             // 再判断当日可用红票
//             $senddayCount = $uModel->getSendTCount($uid, $bid, 0);
//             $totalDayCount = C("TICKETSET")["senddaymax"];
//             if ($totalDayCount - ($senddayCount + $num) >= 0) {
//                 $realLast = $totalDayCount - $senddayCount;
//                 if ($realLast >= $lastMonthCount) {
//                     $arr = array('msg' => $lastMonthCount);
//                     $realLast = $lastMonthCount;
//                 } else {
//                     $arr = array('msg' => $realLast);
//                 }
//                 if ($realLast >= $userLastTicket) {
//                     $realLast = $userLastTicket;
//                     $arr = array('msg' => $realLast);
//                 }
//             } else {
//                 $arr = array('msg' => 0);
//             }
//         } else {
//             $arr = array('msg' => 0);
//         }
//         if ($arr['msg'] == 0) {
//             $data['message'] = '红票数量不足或当日投票额度已用完';
//             $data['status'] = 0;
//             $this->ajaxReturn($data);
//         }
        //$ret为最后插入的id
        $ret = $commentmodel->addTicket($bid, $content, $num, $user, $addtime);
        unset($commentmodel);
        if ($ret > 0) {
            $data['message'] = C('MESSAGES.sendticketsuc.msg');
            $data['status'] = 1;
            $this->ajaxReturn($data);
        } else {
            $data['message'] = C('MESSAGES.sendticketfail.msg');
            $data['status'] = 0;
            $this->ajaxReturn($data);
        }
    }

    /*
     * 送花
     *
     * @param int $uid 用户id session
     * @param int $bid 书籍id post
     * @param string $content 评论内容 post
     *
     * @return 成功/失败信息
     */
    public function _sendFlower() {
        $this->check_user_login();
        $uid = isLogin();
        $output = array('status' => 0, 'message' => '', 'url' => '','remaindnum'=>0);
        if (!$uid) {
            $output['message'] = '请先登录！';
            $output['url'] = url('User/login', array(), 'do');
            $this->ajaxReturn($output);
        } else {
            $userinfo = session();
        }
        $bid = I('param.bid', 0, 'intval');
        if (!$bid) {
            $output['message'] = C('MESSAGES.paramerror.msg');
            $this->ajaxReturn($output);
        }
        $num = I('param.num', 0, 'intval');
        if (!$num) {
            $output['message'] = C('MESSAGES.paramerror.msg');
            $this->ajaxReturn($output);
        }
        $getlastfcount = intval(session("tmp_flower"));
        if (!$getlastfcount) {
            //已投的鲜花
            $memcache = new \Think\Cache\Driver\Memcache();
            $costflowers = $memcache->get("flowercount" . $uid);
            $getlastfcount = C("USERGROUP." . $userinfo['groupid'] . ".flowernum") - intval($costflowers);
            /* 获取剩余鲜花数量end */
        }
        session('tmp_flower', $getlastfcount);
        $output['remaindnum'] = $getlastfcount;
        if ($num > $getlastfcount) {
            $output['message'] = C('MESSAGES.outflowercount.msg');
            $this->ajaxReturn($output);
        }
        $content = I("param.content", '', 'trim');
        if (strlen($content) > 1000) {
            $output['message'] = C('MESSAGES.paramerror.msg');
            $this->ajaxReturn($output);
        }
        $commentmodel = new \Client\Model\NewcommentModel();
        //TODO:增加鲜花次数缓存未开，在BookModel的addFlowerCount方法中
        $ret = $commentmodel->addFlower($bid, $content, $num, $userinfo);
        unset($commentmodel);
        if ($ret > 0) {
            session("tmp_flower", $getlastfcount - $num);
            $output['status'] = 1;
            $output['message'] = C('MESSAGES.sendflowersuc.msg');
            $output['remaindnum'] = $getlastfcount - $num;
            $this->ajaxReturn($output);
        } else {
            $output['message'] = C('MESSAGES.sendflowerfail.msg');
            $this->ajaxReturn($output);
            //$this->error(C('MESSAGES.sendflowerfail.msg'));
        }
    }
    /**
     * 获取用书剩余鲜花数
     */
    public function _getremaindFlower(){
        $this->check_user_login();
        $uid = isLogin();
        $output = array('status' => 0, 'message' => '', 'url' => '','num'=>0);
        if (!$uid) {
            $output['message'] = '请先登录！';
            $output['url'] = url('User/login', array(), 'do');
            $this->ajaxReturn($output);
        } else {
            $userinfo = session();
        }
        $getlastfcount = intval(session("tmp_flower"));
        if (!$getlastfcount) {
            //已投的鲜花
            $memcache = new \Think\Cache\Driver\Memcache();
            $costflowers = $memcache->get("flowercount" . $uid);
            $getlastfcount = C("USERGROUP." . $userinfo['groupid'] . ".flowernum") - intval($costflowers);
        }
        session('tmp_flower', $getlastfcount);
        $output['status'] = 1;
        $output['num'] = $getlastfcount;
        $this->ajaxReturn($output);
    }

    /**
     * 道具打赏
     *
     * @param int $bid 书号
     * @param int $sex_num 性别标志 男女频，2：男 3：女
     * @param int $num 奖励数量
     * @param int $pid 奖品id(详见bookpro配置文件)
     * @param string $content 评论
     *
     * @return array 输出json数组
     */
    public function _dashang() {
        $this->check_user_login();
        $output = array('status' => 0, 'code' => 0, 'message' => '');
        $addtime = time();
        $uid = isLogin();
        if ($uid) {
            $user = session();
        } else {
            $output = array('status' => 0, 'code' => C('MESSAGES.nologin.code'), 'message' => C('MESSAGES.nologin.msg'));
            $this->ajaxReturn($output);
        }
        $bid = I('param.bid', 0, 'intval');
        if (!$bid || !is_numeric($bid)) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '书籍不存在');
            $this->ajaxReturn($output);
        }
        if (CLIENT_NAME != 'yqm') {
            $sex_num = I('param.sexflag', 0, 'intval');
//            var_dump($sex_num);
            if (!$sex_num) {
                $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '男女频标志不能为空');
                $this->ajaxReturn($output);
            }
        }
        $num = I('param.num', 0, 'intval');
        if (!$num || !is_numeric($num) || $num <= 0) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '打赏道具数量大于1');
            $this->ajaxReturn($output);
        }
        $pid = I('param.pid', 0, 'intval');
        if (!$pid || !is_numeric($pid)) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '未知道具');
            $this->ajaxReturn($output);
        }
        if (CLIENT_NAME == 'yqm') {
            $prolist = C('PROPERTIES.all');
        } else {
            $prolist = array_merge((array) C('PROPERTIES.boy'), (array) C('PROPERTIES.girl'), (array) C('PROPERTIES.all'));
        }
        $content = I("param.content", "", "trim");
        if (strlen($content) > 1000) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '评论过长');
            $this->ajaxReturn($output);
        }
        $oneprice = 0;

        for ($i = 0; $i < count($prolist); $i++) {
            if ($pid == $prolist[$i]['id']) {
                $oneprice = $prolist[$i]['price'];
                $curpro = $prolist[$i];
                break;
            }
        }
        if (!$oneprice) {
            $output = array('status' => 0, 'code' => C('MESSAGES.nopro.code'), 'message' => C('MESSAGES.nopro.msg'));
            $this->ajaxReturn($output);
        }
        $price = intval($oneprice) * intval($num);
        $unit = $curpro['unit'];
        $usermodel = new \Client\Model\UserModel();

        //扣费
        $moneyinfo = $usermodel->consume($user["uid"], $price);
        if ($moneyinfo["code"] == 107) {
            $is_redtitle = 0;
            $forbidden_flag = 1;
            if ($price >= 20000) {
                $is_redtitle = 1;
            }

            $bookmodel = new \Client\Model\BookModel();
            $book = $bookmodel->getBook($bid);
            unset($bookmodel);
            $username = $user['nickname'] == "" ? $user['username'] : $user['nickname'];
            $title = $username . "为《" . $book["catename"] . "》打赏" . $num . $unit . $curpro["name"] . "！";
            if (!$content) {
                $forbidden_flag = 0;
                $content = $curpro["content"];
                $need = array("#name#", "#num#", "#authorname#");
                $replace = array($username, $num, $book['author']);
                $content = str_replace($need, $replace, $content);
            }

            $commentmodel = new \Client\Model\NewcommentModel();
            $_old = C('TOKEN_ON');
            C('TOKEN_ON', false);
            $commentmodel->add($title, $content, $bid, $user, 0, 0, 0, 0, $is_redtitle, $forbidden_flag);

            $ret = $commentmodel->addPro($bid, $num, $price, $pid, $sex_num, $user, $addtime, $moneyinfo['deductmoney'], $moneyinfo['deductegold']);
            C('TOKEN_ON', $_old);
            if ($ret > 0) {
                $output['message'] = C('MESSAGES.sendprosuc.msg');
                $output["status"] = 1;
                $output["price"] = $price;
                $output["num"] = $num;
                $output["moneyinfo"] = $moneyinfo;
                //更新用户余额
                $sesuser['money'] = intval($moneyinfo['lastmoney']);
                $sesuser['egold'] = intval($moneyinfo['lastegold']);
                session('money', $sesuser['money']);
                session('egold', $sesuser['egold']);

                //加积分和送红票
                $integral = $curpro['integral'] * $num;
                $fensimodel = new \Client\Model\FensiModel();
                $fensimodel->addFansIntegral($bid, $uid, $integral);
                unset($fensimodel);

                //花费了金币
                if($moneyinfo['deductmoney']>0) {
                    //写入用户现金流变动
                    $data = array(
                        'uid'=>$user['uid'],
                        'before' => $moneyinfo['deductmoney']+$moneyinfo['lastmoney'],
                        'change' => '-'.$moneyinfo['deductmoney'],
                        'logid' => $ret,
                        'note' => '为《' . $book['catename'] . '》打赏' . $num . $unit . $curpro['name'] . '花费'.$moneyinfo['deductmoney'].'个',
                        'siteid' => C('CLIENT.' . CLIENT_NAME . '.fromsiteid')
                    );
                    $client = new \HS\Yar("userlog");
                    $client->saveUserMoneyLog($user['uid'], 'dashang', $data);
                }

                $ticket = $curpro['ticket'] * $num;
                if (intval($ticket) > 0) {
                    $ret = $commentmodel->addTicket($bid, '', $ticket, $user, $addtime, 1);
                    unset($commentmodel);
                }
            } else {
                $output['message'] = C('MESSAGES.sendprofail.msg');
            }
            unset($commentmodel);
        } else {
            $output['message'] = C('MESSAGES.consumefail.msg');
        }
        unset($usermodel);
        unset($model);
        $this->ajaxReturn($output);
    }
    /**
     * 道具打赏
     *
     * @param int $bid 书号
     * @param int $num 奖励数量
     * @param int $pid 奖品id(详见bookpro配置文件)
     * @param string $content 评论
     *
     * @return array 输出json数组
     */
    public function _yqmdashang() {
        $this->check_user_login();
        $output = array('status' => 0, 'code' => 0, 'message' => '');
        $addtime = time();
        $uid = isLogin();
        if ($uid) {
            $user = session();
        } else {
            $output = array('status' => 0, 'code' => C('MESSAGES.nologin.code'), 'message' => C('MESSAGES.nologin.msg'));
            $this->ajaxReturn($output);
        }
        $bid = I('param.bid', 0, 'intval');
        if (!$bid || !is_numeric($bid)) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '书籍不存在');
            $this->ajaxReturn($output);
        }
        $num = I('param.num', 0, 'intval');
        if (!$num || !is_numeric($num) || $num <= 0) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '打赏道具数量大于1');
            $this->ajaxReturn($output);
        }
        $pid = I('param.pid', 0, 'intval');
        if (!$pid || !is_numeric($pid)) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '未知道具');
            $this->ajaxReturn($output);
        }
        //道具列表
        $prolist = C('PROPERTIES.all');
        $content = I("param.content", "", "trim");
        if (strlen($content) > 1000) {
            $output = array('status' => 0, 'code' => C('MESSAGES.paramerror.code'), 'message' => '评论过长');
            $this->ajaxReturn($output);
        }
        $oneprice = 0;
        for ($i = 0; $i < count($prolist); $i++) {
            if ($pid == $prolist[$i]['id']) {
                $oneprice = $prolist[$i]['price'];
                $curpro = $prolist[$i];
                break;
            }
        }
        if (!$oneprice) {
            $output = array('status' => 0, 'code' => C('MESSAGES.nopro.code'), 'message' => C('MESSAGES.nopro.msg'));
            $this->ajaxReturn($output);
        }
        $price = intval($oneprice) * intval($num);
        $unit = $curpro['unit'];
        $usermodel = new \Client\Model\UserModel();
    
        //扣费
        $moneyinfo = $usermodel->consume($user["uid"], $price);
        if ($moneyinfo["code"] == 107) {
            $is_redtitle = 0;
            $forbidden_flag = 1;
            if ($price >= 20000) {
                $is_redtitle = 1;
            }
    
            $bookmodel = new \Client\Model\BookModel();
            $book = $bookmodel->getBook($bid);
            unset($bookmodel);
            $username = $user['nickname'] == "" ? $user['username'] : $user['nickname'];
            $title = $username . "为《" . $book["catename"] . "》打赏" . $num . $unit . $curpro["name"] . "！";
            if (!$content) {
                $forbidden_flag = 0;
                $content = $curpro["content"];
                $need = array("#name#", "#num#", "#authorname#");
                $replace = array($username, $num, $book['author']);
                $content = str_replace($need, $replace, $content);
            }
    
            $commentmodel = new \Client\Model\NewcommentModel();
            $commentmodel->add($title, $content, $bid, $user, 0, 0, 0, 0, $is_redtitle, $forbidden_flag);
    
            $ret = $commentmodel->addPro($bid, $num, $price, $pid, 0, $user, $addtime, $moneyinfo['deductmoney'], $moneyinfo['deductegold']);
            if ($ret > 0) {
                $output['message'] = C('MESSAGES.sendprosuc.msg');
                $output["status"] = 1;
                $output["price"] = $price;
                $output["num"] = $num;
                $output["moneyinfo"] = $moneyinfo;
                //更新用户余额
                $sesuser['money'] = intval($moneyinfo['lastmoney']);
                $sesuser['egold'] = intval($moneyinfo['lastegold']);
                session('money', $sesuser['money']);
                session('egold', $sesuser['egold']);
    
                //加积分和送红票
                $integral = $curpro['integral'] * $num;
                $fensimodel = new \Client\Model\FensiModel();
                $fensimodel->addFansIntegral($bid, $uid, $integral);
                unset($fensimodel);
    
                //花费了金币
                if($moneyinfo['deductmoney']>0) {
                    //写入用户现金流变动
                    $data = array(
                        'uid'=>$user['uid'],
                        'before' => $moneyinfo['deductmoney']+$moneyinfo['lastmoney'],
                        'change' => '-'.$moneyinfo['deductmoney'],
                        'logid' => $ret,
                        'note' => '为《' . $book['catename'] . '》打赏' . $num . $unit . $curpro['name'] . '花费'.$moneyinfo['deductmoney'].'个',
                        'siteid' => C('CLIENT.' . CLIENT_NAME . '.fromsiteid')
                    );
                    $client = new \HS\Yar("userlog");
                    $client->saveUserMoneyLog($user['uid'], 'dashang', $data);
                }
    
                $ticket = $curpro['ticket'] * $num;
                if (intval($ticket) > 0) {
                    $ret = $commentmodel->addTicket($bid, '', $ticket, $user, $addtime, 1);
                    unset($commentmodel);
                }
            } else {
                $output['message'] = C('MESSAGES.sendprofail.msg');
            }
            unset($commentmodel);
        } else {
            $output['message'] = C('MESSAGES.consumefail.msg');
        }
        unset($usermodel);
        unset($model);
        $this->ajaxReturn($output);
    }

    /**
     * 章节订阅
     *
     * @param int $uid 用户id session
     * @param int $chapterid 章节id post
     * @param string $autoorder 是否自动订阅(Y是/否N)
     * @param int $pl_num 订阅章节数量 >=40全本 /<=10 单张,其他表示订阅的数量
     *
     * @return string 订阅成功/失败信息
     */
    public function _orderChapter() {
        $this->check_user_login();
        $uid = isLogin();
        $output = array('status' => 0, 'message' => '', 'url' => '');
        if ($uid) {
            $user = M("user")->find($uid);
        } else {
            $output['message'] = '请登录...';
            $output['url'] = url('User/login', array(), 'do');
            $this->ajaxReturn($output);
        }

        if ($user['is_deleted'] == 1 || $user['is_locked'] == 1) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        if (!$user['viplevel']) {
            //TODO:如果不是vip用户,则默认为vip1
            $user['viplevel'] = 1;
            //$this->error('您还不是VIP用户，请先充值成为VIP用户'); //TODO:可跳转到购买充值页面
        }
        $bid = I('post.bid', 0, 'intval');
        if (!$bid) {
            $output['message'] = '书籍不存在';
            $this->ajaxReturn($output);
        }
        $chapterid = I('post.chpid', 0, 'intval');
        if (!$chapterid) {
            $output['message'] = '章节不存在';
            $this->ajaxReturn($output);
        }
        //自动订阅下一章
        $autoorder = I('post.autoorder', '', 'trim'); //autoorder的值为N和Y
        if ($autoorder == 'Y') {
            $autoorder = 3;
        } else {
            $autoorder = 0;
        }

        $fromsite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");

        $xianmian = $xianmian_num = false;

        //从接口中读取限免对应的设置
        $client = new \HS\Yar("discountset");
        $result = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromsite);
        if ($result) {
            $discount_set = $result['discount_set'];
            $is_discount = $discount_set['is_open'];
            $is_bookdiscount = $discount_set['is_bookdiscount'];
            $custom_price_set = $result['custom_price_set'];
            $xianmian = $result['xianmian_set'];
            $book_vip_price = $result['pricebeishu'];
            $xianmian_num = $xianmian['num'];
        }
        unset($result);
        unset($client);
        //yqm暂按千字3
        if (CLIENT_NAME == 'yqm' || (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0')) {
            $book_vip_price = 333;
        }

        $isfree = false;
        if ($xianmian) {
            $isfree = true;
            if ($xianmian['freetype'] == 2) {
                $freechpts = explode('|', $xianmian['free_chapterid']);
                if (!in_array($chapterid, $freechpts)) {
                    $isfree = false;
                }
            }
        }

//         $bookmodel = new \Client\Model\BookModel();
        if ($isfree) {
            //检查是否设置自动订阅
            $yar_client = new \HS\Yar("autodingyuestatus");
            $result = $yar_client->checkAutoStatus($bid, $uid);
            if ($result['dtype'] != 3 && $result['dtype'] != 9) {
                $yar_client->compareAndUpdateAutoStatus($bid, $user['uid'], $user['username'], 9, $fromsite);
            }
            $this->success('恭喜你开始免费阅读！');
        }

        //批量订阅
        $pl_num = I('post.pl_num', 1, 'intval');
        //if ($pl_num > 40) {
        //    $buyall = 3;
        //} else {
        $buyall = 0;
        //计算表名
        $tabName = 'chapter' . str_pad(floor($bid / intval(C('CHPTABLESIZE'))), 2, "0", STR_PAD_LEFT);
        $chpmap = array("chapterid" => $chapterid);
        $chporder = M($tabName)->where($chpmap)->getField("chporder");
        $chpid = $chapterid;
        if ($pl_num > 0) {
            $map = array(
                'chporder' => array('egt', $chporder),
                'bid'      => array('eq', $bid)
            );
            if ($pl_num > 40) {
                $ids = M($tabName)->field('chapterid')->order('chporder ASC')->where($map)->select();
            } else {
                $ids = M($tabName)->field('chapterid')->order('chporder ASC')->where($map)->limit($pl_num)->select();
            }
            if ($ids) {
                $chapterid = array();
                foreach ($ids as $vo) {
                    $chapterid[] = $vo['chapterid'];
                }
            }
        }
        //}
//        $ad = 'bid:'.$bid.'chapterid:'.$chapterid.'user:'.$user.'buyall:'.$buyall.'autoorder:'.$autoorder;
//        $this->ajaxReturn($ad);
        //购买
        $bookmodel = new \Client\Model\BookModel();

        $ret = $bookmodel->orderChapterByCache($bid, $chapterid, $user, $buyall, $autoorder, $fromsite);
        if ($ret == 'orderchpsuc') {
            $output['status'] = 1;
            $output['message'] = '订阅成功';
        } elseif ($ret == 'nochapterorder') {
            $output['status'] = 1;
            $output['message'] = '已订阅，请直接阅读';
        } elseif ($ret == 'orderchperror') {
            $output['message'] = '订阅章节错误';
        } elseif ($ret == 'consumefail') {
            $output['message'] = '余额不足，请充值';
        } elseif ($ret == 'orderisruning') {
            $output['status'] = -1;
            $output['message'] = '你还有支付操作正在处理中，请稍候再试';
        } else {
            $output['message'] = '未知错误';
        }
        if ($output['status'] == 1 && in_array(CLIENT_NAME, array('ios', 'android'))) {
            $fun = CLIENT_NAME . '_convert_bookinfo';
            $bookinfo = $bookmodel->getBook($bid);
            $data = $fun($bookinfo);
            if (CLIENT_NAME == 'android') {
                $json = array(
                    'Data'  => $data,
                    'Chpid' => $chpid,
                );
                $doClinetstr = addslashes(json_encode($json));
            } else if (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0') {
                //元气萌IOS要返回bid和chpid
                $output['bid'] = $bid;
                $output['chpid'] = $chpid;
            } else if (CLIENT_NAME == 'ios'){
                $ios_bookinfo_json = urldecode(json_encode($data));
                $doClinetstr = '{"Data":' . $ios_bookinfo_json . ',"Chpid":"' . $chpid . '"}';
            }
            $output['doClinetstr'] = $doClinetstr;
        }
        unset($bookmodel);

        $this->ajaxReturn($output);
    }

    /**
     * 下载单个章节，参考自Android和ios的downloadchapter.php
     *
     * @param int $bid 书号
     * @param int $chpid 章节id
     *
     * @return 章节内容实体
     */
    public function _downloadchapter() {
		//同IP每分钟允许请求10次
		$ispre = I('ispre', 'N', 'trim,strtoupper');
		if ($ispre !== 'Y') {
			$ispre = 'N';
		}
		if ($ispre == 'Y') {
			$ip = get_client_ip();
			$key = '_CLIENT_DOWNLOAD_BY_' . $ip;
			$mmc = new \HS\MemcacheRedis();
			$result = $mmc->getMc($key);
			//标志,如果值是99,则为禁用状态,需要等1分钟后再重试
			if ($result == 99) {
				client_output_error('server is busied');
			} else if ($result) {
				$result++;
				if ($result >= 10) {
					$mmc->setMc($key, 99, 60);
					client_output_error('client is too fast');
				} else {
					$mmc->setMc($key, $result, 60);
				}
			} else {
				$result = 1;
				$mmc->setMc($key, 1, 60);
			}
		}
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('ios', 'android'))) {
            //获得所有客户端报错信息，并变量化
            extract(C('ERRORS'));
            $this->check_user_login();
            $uid = isLogin();
            $viplevel = 0;
            if($uid){
                $viplevel = intval(session('viplevel'));
            }
            //fromsiteidqu区分来自安卓、苹果客户端
            $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");

            $bid = I('request.bid', 0, 'intval');
//             $bid = 60494;
            $chpid = I('request.chpid', 0, 'intval');
//             $chpid = 11043449;
            if (!$bid || !$chpid) {
                client_output_error($params);
            }

            /* 书籍信息 */
            $bModel = new \Client\Model\BookModel();
            $bookinfo = $bModel->getBook($bid);
            if (!$bookinfo) {
                client_output_error($booknotfind);
            }

            if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1) {
                client_output_error($booknotfind);
            }
            //潇湘的书不能显示在手机版权
            if ($bookinfo['sourceId'] == 101) {
                client_output_error($booknotfind);
            }

            //获取单个章节
            $chapterlist = $bModel->getChplistByBid($bid);
            foreach ($chapterlist['list'] as $k => $v) {
                if ($v['chapterid'] == $chpid) {
                    $chapter = $v;
                    break;
                }
            }
            if (!$chapter || $chapter['bid'] != $bid) {
                client_output_error($chapternotfind);
            }

            if (($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)) {
                client_output_error($booknotfind);
            }

            if ($chapter['ispublisher'] != C('CHPT_CANDISPLAY')) {
                client_output_error($chapternotfind);
            }
            $this->check_user_login();
            $uid = isLogin();

            if ($chapter['isvip']) {
                if (!$uid) {
                    client_output_error($needlogin);
                }
                //3 根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
                $discount_set = false;
                $custom_price_set = false;

                $is_discount = false;
                //默认值
                $uervip = C('USERVIP');
                $pricebeishu = $uervip[1]['price'];
                unset($result);
                $usermodel = new \Client\Model\UserModel();
                $userinfo = $usermodel->getUserbyUid($uid);
//                 $yar_client  = new \Yar_Client(C('RPCURL') . "/discountset.php");
                $yar_client = new \HS\Yar("discountset");
                $result = $yar_client->getDiscountCustomXianmianStatus($bid, $userinfo['viplevel'], $fromSite);
                if ($result) {
                    $discount_set = $result['discount_set'];
                    $is_discount = $discount_set['is_open'];
                    $custom_price_set = $result['custom_price_set'];
                    $xianmian_set = $result['xianmian_set'];
                    $pricebeishu = $result['pricebeishu'];
                } else {
                    client_output_error($system);
                }

                $is_chptfree = false;
                if ($xianmian_set) {//限时免费书籍
                    unset($result);
                    $result = $yar_client->getChptIsFree($xianmian_set, $chpid);
                    if (false === $result) {
                        $output['message'] = '网络错误,请重试,代码003';
                        $this->ajaxReturn($output);
                    }
                    $is_chptfree = $result['is_chptfree'];
                }
                //限时免费章节禁止连续下载章节
                if (CLIENT_NAME == 'ios') {
                    //IOS没有单章下载
                    $isdownload = "N";
                    $ispre = I('ispre', 'N', 'trim,strtoupper');
                    if ($ispre !== 'Y') {
                        $ispre = 'N';
                    }
                } else {
                    //安卓没有批量下载
                    $isdownload = I('get.isdownload', '', 'trim,strtoupper');
                    if ($isdownload !== 'N') {
                        $isdownload = 'Y';
                    }
                    //安卓没有预下载
                    $ispre = 'N';
                }

                if ($isdownload == 'Y' && $is_chptfree) {
                    client_output_error($chptnotfree);
                }
                $buylog = false;

                unset($result);
//                 $client = new \Yar_Client(C('RPCURL') . "/dingoujson.php");
                $client = new \HS\Yar("dingoujson");
                $result = $client->checkUserCh($bid, $chpid, $uid);

                if (false === $result) {
                    client_output_error($system);
                    exit();
                }
                if ($result == 'Y') {
                    echo output_chapter($bid, $chpid, $chapter);
                } else {
                    if ($ispre == 'Y') {
                        //预下载时不自动购买
                        client_output_error($system);
                        exit;
                    }
                    //下载章节时不自动扣费
                    if ($isdownload == 'Y') {
                        client_output_error($chptnotfree);
                    }

                    if ($is_chptfree) {//不产生订阅记录，直接给用户看同时增加点击数
                        $bookfreehit = M('book_freehit');
                        $freemap = array(
                            "bid"      => $bid,
                            "num"      => $xianmian_set['num'],
                            "fromsite" => $fromSite,
                        );
                        $freeinfo = $bookfreehit->where($freemap)->find();
                        if ($freeinfo['bid']) {
                            $bookfreehit->where($freemap)->setInc("free_hit", 1);
//                             $bookfreehit->execute("UPDATE wis_book_freehit SET free_hit=free_hit+1 WHERE bid=" . $bid . " and fromsite={$fromSite} and num=" . $xianmian_set['num']);
                        } else {
                            $freedata = array(
                                "bid"      => $bid,
                                "free_hit" => 1,
                                "num"      => $xianmian_set['num'],
                                "fromsite" => $fromSite,
                            );
                            $bookfreehit->add($freedata);
//                             $bookfreehit->execute("INSERT INTO wis_book_freehit(bid,free_hit,num,fromsite) values ({$bid},1,{$xianmian_set['num']},{$fromSite})");
                        }
                        echo output_chapter($bid, $chpid, $chapter);
                    }
                    //判断是否自动订阅
                    $fanyeAuto = false;
                    //自动翻页订阅判断
                    unset($result);
                    //$autoclient = new \Yar_Client(C("RPCURL") . "/autodingyuestatus.php");
                    $autoclient = new \HS\Yar("autodingyuestatus");
                    $result = $autoclient->checkAutoStatus($bid, $uid);

                    if ($result && $result['dtype'] == 3) {
                        $fanyeAuto = true;
                    }

                    //是否设置了翻页自动订阅
                    if ($fanyeAuto) {
                        $usermap = array("uid" => $uid);
                        $user = M("user")->where($usermap)->find();

                        //订阅章节，成功返回orderchpsuc
                        $newbookmodel = new \Client\Model\BookModel();
                        $orderres = $newbookmodel->orderChapterByCache($bid, $chpid, $user, false, 3, $fromSite);
                        switch ($orderres) {
                            case 'orderchpsuc':
                                //client_output_error('订阅成功');
                                echo output_chapter($bid, $chpid, $chapter);
                                break;
                            case 'nochapterorder':
                                //client_output_error('已订阅,请直接阅读');
                                echo output_chapter($bid, $chpid, $chapter);
                                break;
                            case 'orderisruning':
                                client_output_error('orderisruning...');
                                break;
                            case 'consumefail':
                                client_output_error(C("ERRORS.chptnotfree"));
                                break;
                            case 'orderchperror':
                                client_output_error('orderchperror');
                                break;
                            default :
                                client_output_error('unknown errors');
                                break;
                        }
                    } else {
                        client_output_error($chptnotfree);
                    }
                }//判断自动订阅结束
            } else {
                echo output_chapter($bid, $chpid, $chapter);
            }
        }
    }

    /**
     * 下载章节目录
     *
     * @param int $bid 书号
     *
     * @return array 返回json数组
     */
    public function _downloadchapterlist() {
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            $bid = I('request.bid', 0, 'intval');
            if (!$bid) {
                exit;
            }
            $viplevel = 0;
            $this->check_user_login();
            $uid = isLogin();
            if($uid){
                $viplevel = intval(session('viplevel'));
            }
            $bookmodel = new \Client\Model\BookModel();
            $bookinfo = $bookmodel->getBook($bid);

            //已删除书籍，潇湘书籍，以及不显示的书籍不显示
            if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['sourceId'] == 101) {
                client_output_error(C('ERRORS.booknotfind'));
            }
            if(($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)){
                client_output_error(C('ERRORS.booknotfind'));
            }    
            
            $chapterList = $bookmodel->getChapter($bid);
            $fun = CLIENT_NAME . '_convert_chapterlist';
            $output = $fun($chapterList);
            $this->check_user_login();
            $uid = isLogin();
            $vipchparr = array();
            if ($uid) {
                // 获得用户已订购章节ids
                $client = new \HS\Yar("dingoujson");
                $vipchpstr = $client->checkUserAll($bid, $uid, 'ids');
                if ($vipchpstr) {
                    $vipchparr = explode(',', $vipchpstr);
                }
            }

            foreach ($output as &$vo) {
                $vo['IsOrder'] = 0;
                if ($vipchparr && in_array($vo['Chapter_ID'], $vipchparr)) {
                    $vo['IsOrder'] = 1;
                }
            }
            $this->ajaxReturn($output);
        } else {
            _exit('请求出错！');
        }
    }

    /**
     * android、ios下载免费章节
     *
     * @param int $bid 书号
     *
     * @return array 书籍的所有免费章节json数组
     */
    public function _downloadfreechapters() {

        //TODO:fromSite区分客户端（原站未使用）
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        } else {
            _exit('请求出错！');
        }

        $bid = I('request.bid', 0, 'intval');

        if (!$bid) {
            client_output_error(C('ERRORS.params'));
        }
        $this->check_user_login();
        $viplevel = 0;
        $uid = isLogin();
        if($uid){
            $viplevel = intval(session('viplevel'));    
        }
        
        $bookmodel = new \Client\Model\BookModel();
        $bookinfo = $bookmodel->getBook($bid);

        //已删除书籍，潇湘书籍，以及不显示的书籍不显示
        if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['sourceId'] == 101) {
            client_output_error(C('ERRORS.booknotfind'));
        }
        if(($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)){
            client_output_error(C('ERRORS.booknotfind'));
        }
        
        $chapterlist = $bookmodel->getChapter($bid);

        //android下载免费章节处理
        if (CLIENT_NAME == 'android') {
            $num = 0;
            $isfindvip = false;
            foreach ($chapterlist as $juanid => $juan) {

                foreach ($juan['chparys'] as $chapter) {

                    if ($chapter['isvip']) {
                        $isfindvip = true;
                        break;
                    } else {
                        $num++;
                        if ($num != 1) {
                            echo '<!!#chapter_split#!!>' . "\n";
                        }
                        echo output_chapter($bid, $chapter['chapterid'], $chapter, 1);
                        ob_flush();
                        flush();
                    }
                }
                if ($isfindvip) {
                    break;
                }
            }
        } elseif (CLIENT_NAME == 'ios') {
            //ios下载免费章节处理
            //输出章节内容
            $num = ios_output_chapters($bid, $chapterlist, true, true);
        }
    }

    /**
     * android、ios下载所有章节(一次下载全部(包含收费)章节)
     *
     * @param int $bid 书号
     *
     * @return array 书籍所有章节json数组
     */
    public function _downloadfullchapters() {

        //fromSite区分客户端
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        } else {
            _exit('请求出错');
        }

        $bid = I('request.bid', 0, 'intval');
        $schpid = I('request.schpid', 0, 'intval');
        $down_num = I('request.dnum', 0, 'intval');

        //if (!$bid || !$schpid) {
        if (!$bid) {
            client_output_error(C('ERRORS.system'));
        }

        $bookmodel = new \Client\Model\BookModel();
        $bookinfo = $bookmodel->getBook($bid);

        //已删除书籍，潇湘书籍，以及不显示的书籍不显示
        if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['sourceId'] == 101) {
            client_output_error(C('ERRORS.booknotfind'));
        }
        $viplevel = 0;
        $this->check_user_login();
        $uid = isLogin();
        $usermodel = new \Client\Model\UserModel();
        if ($uid) {
            $userinfo = $usermodel->getUserbyUid($uid);
        } else {
            client_output_error(C('ERRORS.needlogin'));
        }

        if (is_array($userinfo) && ($userinfo['is_deleted'] == 1 || $userinfo['is_locked'] == 1)) {
            client_output_error(C('ERRORS.needlogin'));
        }
        $viplevel = intval($userinfo['viplevel']);
        if(($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)){
            client_output_error(C('ERRORS.booknotfind'));
        }
        //限免小说不允许下载
        $yar_client = new \HS\Yar("discountset");
        $result = $yar_client->getDiscountCustomXianmianStatus($bid, $userinfo['viplevel'], $fromSite);
        if ($result) {
            $xianmian_set = $result['xianmian_set'];
        } else {
            client_output_error(C('ERRORS.system'));
        }

        $is_chptfree = false;
        if ($xianmian_set) {//限时免费书籍
            client_output_error(C('ERRORS.system'));
        }

        //1 先扣费,然后在输出章节内容
        //购买全部vip章节
        $buyall = true;

        $bookmodel = new \Client\Model\BookModel();
        $ret = $bookmodel->orderChapterByCache($bid, $schpid, $userinfo, $buyall, -1, $fromSite);
        unset($bookmodel);
        switch ($ret) {
            case 'orderchpsuc':
                //client_output_error('orderchpsuc');
                break;
            case 'nochapterorder':
                //client_output_error('nochapterorder');
                break;
            case 'orderisruning':
                client_output_error('orderisruning');
                break;
            case 'consumefail':
                client_output_error('consumefail');
                break;
            case 'orderchperror':
                client_output_error('orderchperror');
                break;
            default :
                client_output_error('unknown errors');
                break;
        }

        //输出章节内容
        $bookmodel = new \Client\Model\BookModel();
        $chapterlist = $bookmodel->getChapter($bid);

        if ($ret == 'orderchpsuc' || $ret == 'nochapterorder') {
            if (CLIENT_NAME == 'android') {
                $num = 0;
                $isfindschapter = false;
                $isbreak = false;
                foreach ($chapterlist as $juanid => $juan) {
                    foreach ($juan['chparys'] as $chapter) {
                        if ($schpid && $down_num) {
                            if ($schpid == $chapter['chapterid']) {//下载起始章节
                                $isfindschapter = true;
                            }
                            if (!$isfindschapter) {//未找到下载起始章节则不做任何操作
                                continue;
                            }
                            if ($isfindschapter && $num > $down_num) {//已下载了最大章节数
                                $isbreak = true;
                                break;
                            }
                        }
                        //未审核章节不允许下载
                        if(!isset($chapter['candisplay']) || strtoupper($chapter['candisplay']) !== 'Y'){
                            continue;
                        }
                        $num++;
                        if ($num != 1) {
                            echo '<!!#chapter_split#!!>' . "\n";
                        }
                        echo output_chapter($bid, $chapter['chapterid'], $chapter, 1);
                        ob_flush();
                        flush();
                    }
                    if ($isbreak) {
                        break;
                    }
                }
            } elseif (CLIENT_NAME == 'ios') {
                $num = ios_output_chapters($bid, $chapterlist, false, false);
            }
        }
    }

    /**
     * 下载本书
     *
     * @param int $bid 书号
     *
     * @return array 书籍的json数组
     */
    public function _downbook() {
        $this->check_user_login();
        $uid = isLogin();
        $viplevel = 0;
        if($uid){
            $viplevel = intval(session('viplevel'));
        }
        $bid = I('request.bid', 0, 'intval');
        if (!$bid) {
            client_output_error(C('ERRORS.system'));
        }

        $bookmodel = new \Client\Model\BookModel();
        $bookinfo = $bookmodel->getBook($bid);

        //已删除书籍，潇湘书籍，以及不显示的书籍不显示
        if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['sourceId'] == 101) {
            client_output_error(C('ERRORS.booknotfind'));
        }
        if(($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)){
            client_output_error(C('ERRORS.booknotfind'));
        }
        

        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            //android/ios_convert_bookinfo区分客户端
            $fun = CLIENT_NAME . '_convert_bookinfo';
            $this->ajaxReturn($fun($bookinfo));
        } else {
            client_output_error("unknown client");
        }
    }

    /**
     * 客户端获得单章计费信息
     *
     * @param int $bid 书号
     * @param int $chpid 章节id
     *
     * @return array 单章费用json数组
     */
    public function _getOrderTmpInfo() {

        //查询计费信息,如果有orderUrl则弹出该页面,反之如有downloadUrl则直接下载
        $bid = I('request.bid', 0, 'intval');
        if (!$bid) {
            client_output_error(C('ERRORS.booknotfind'));
        }

        //获得单个章节，并判断章节存在性
        $chpid = I('request.chpid', 0, 'intval');

        if (!$chpid) {
            $chapter = false;
            client_output_error(C('ERRORS.chapternotfind'));
        }
        $bModel = new \Client\Model\BookModel();
        $chapterlist = $bModel->getChplistByBid($bid);
        foreach ($chapterlist['list'] as $v) {
            if ($v['chapterid'] == $chpid) {
                $chapter = $v;
                continue;
            }
        }

        if ($chapter['bid'] != $bid) {
            $chapter = false;
            client_output_error(C('ERRORS.chapternotfind'));
        }
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            client_output_error(C('ERRORS.needlogin'));
        }
        $output = array(
            'Data' => array(
                'Charging'     => array(
                    'FeeType'  => 2,
                    'OrderUrl' => url('Book/buyVipList', array('bid' => $bid, 'chpid' => $chpid), 'do')
                ),
                'DownloadInfo' => array(
                    'FileId'      => $bid,
                    'DownloadUrl' => url('Bookajax/downloadchapter', array('bid' => $bid, 'chpid' => $chpid), 'do')
                ),
            ),
        );
        if (!$chapter['isvip']) {
            $output['Data']['Charging']['FeeType'] = -1;
            $output['Data']['Charging']['OrderUrl'] = '';
            $this->ajaxReturn($output);
        }

        unset($result);
//         $client = new \Yar_Client(C("RPCURL") . "/dingoujson.php");
        $client = new \HS\Yar("dingoujson");
        $result = $client->checkUserCh($bid, $chpid, $uid);

        if (false === $result) {
            exit('网络错误,请返回后重试,错误代码006,请重试');
        }

        if ($result == "Y") {//已订阅
            $output['Data']['Charging']['FeeType'] = -1;
            $output['Data']['Charging']['OrderUrl'] = '';
            $this->ajaxReturn($output);
        } else {//未订阅
            $output['Data']['Charging']['FeeType'] = 2;
            $output['Data']['DownloadInfo']['DownloadUrl'] = '';
            $this->ajaxReturn($output);
        }
    }

    /**
     * 查询一本书的所有未付费章节计费信息
     *
     * @param int $bid 书号
     *
     * @return array json数组
     */
    public function _getOrderInfo() {
        
        $bid = I('request.bid', 0, 'intval');

        //fromSite区分客户端
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        } else {
            client_output_error("unknown client");
        }

        //取出所有错误信息配置，并变量化
        extract(C('ERRORS'));

        //查询
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            client_output_error($needlogin);
        }
        if (!$bid) {
            client_output_error($params);
        }

        if (CLIENT_NAME == 'ios') {
            //是否指定从某章开始计算
            $chpid = I('get.chpid', 0, 'intval');
            $chporder = 0;
            $limit_str = '全部';
            if ($chpid) {
                $bModel = new \Client\Model\BookModel();
                $chapterlist = $bModel->getChplistByBid($bid);
                foreach ($chapterlist['list'] as $v) {
                    if ($v['chapterid'] == $chpid) {
                        $chapter = $v;
                        continue;
                    }
                }
                if ($chapter) {
                    $chporder = $chapter['chporder'];
                    $limit_str = '本章之后';
                }
            }
        }

        $output_ary = array(
            'chaptercount'        => 0,
            'need_total_money'    => 0,
            'user_money'          => 0,
            'user_egold'          => 0,
            'isInsufficient'      => false,
            'is_buyall_discount'  => false,
            'buyall_discount_set' => 0,
        );
//         $yar_client = new \Yar_Client(C("RPCURL") . "/usermoney.php");
        $yar_client = new \HS\Yar("usermoney");
        $result = $yar_client->getUserMoney($uid);
        if ($result) {
            $userinfo = $result;
            $output_ary['user_money'] = intval(session('money'));
            $output_ary['user_egold'] = intval(session('egold'));
        } else {
            client_output_error($system);
        }

        //2 根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set = false;
        $custom_price_set = false;
        $is_buyall = true; //订购全部
        $is_discount = false;
        $is_bookdiscount = false;
        $pricebeishu = C('USERVIP')[1]['price'];
        unset($result);
        unset($yar_client);
//         $yar_client       = new \Yar_Client(C("RPCURL") . "/discountset.php");
        $yar_client = new \HS\Yar("discountset");
        $result = $yar_client->getDiscountCustomXianmianStatus($bid, $userinfo['viplevel'], $fromSite);
        //dump($result,'result');
        if ($result) {
            $discount_set = $result['discount_set'];
            $is_discount = $discount_set['is_open'];
            $is_bookdiscount = $discount_set['is_bookdiscount'];
            $custom_price_set = $result['custom_price_set'];
            $xianmian_set = $result['xianmian_set'];
            $pricebeishu = $result['pricebeishu'];
        } else {
            client_output_error($system);
        }

        $output_ary['is_buyall_discount'] = $is_bookdiscount ? true : false;
        $output_ary['buyall_discount_set'] = $discount_set['book_discount'] ? $discount_set['book_discount'] : 0;
        $title_discount_buyall = '';
        if ($output_ary['is_buyall_discount']) {
            $title_discount_buyall = '下载全部章节享受' . $output_ary['buyall_discount_set'] . '折优惠';
        }

        //6 获得用户已订购章节ids
        $aleady_buyids = '';
        unset($result);
//         $client        = new \Yar_Client(C("RPCURL") . "/dingoujson.php");
        $client = new \HS\Yar("dingoujson");
        $result = $client->checkUserAll($bid, $uid, 'ids');

        if (false === $result) {
            //$cacheLockObj->unlock();
            client_output_error($system);
        }
        if ($result != 'N') {
            $aleady_buyids = $result;
        }

        //7 根据已订阅章节,选择的章节,自动订阅状态,是否全订,获得所有需要订阅的章节数组,(未实现:并计算单章应扣金币和银币,总计 应扣金币和银币)
        unset($result);
//         $yar_client = new \Yar_Client(C("RPCURL") . '/dingyuechapter.php');
        $yar_client = new \HS\Yar("dingyuechapter");
        $result = $yar_client->getNoBuychapterArys($bid, false, $aleady_buyids, $is_buyall, '', $chporder);

        if ($result != false) {
            $nobuy_vipchparys = $result['nobuy_vipchparys'];
            $aleady_buyarys = $result['aleady_buyarys'];
        }

        //exit;
        //7.1 没有发现需要订阅的章节
        $salenum = count($nobuy_vipchparys);
        if (!is_array($nobuy_vipchparys) || $salenum <= 0) {
            $this->ajaxReturn($output_ary);
        }
        $output_ary['chaptercount'] = $salenum;
        $need_buyids = implode_ids($nobuy_vipchparys, 'chapterid');


        //8.1 计算每个单章价格和总价
        $need_totalmoney = 0;
        unset($result);
//         $client          = new \Yar_Client(C("RPCURL") . "/dingyuechapter.php");
        unset($client);
        $client = new \HS\Yar("dingyuechapter");
        $result = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, $is_buyall, $custom_price_set, $xianmian_set);

        if (false === $result) {
            //$cacheLockObj->unlock();
            client_output_error($system);
        }

        $need_totalmoney = $result['need_totalmoney'];
        $nobuy_vipchparys = $result['nobuy_vipchparys'];

        //dump($result);
        //8.2 判断金币是否够用
        $userinfo['totalmoney'] = $userinfo['money'] + $userinfo['egold'];
        if ($userinfo['totalmoney'] < $need_totalmoney && $need_totalmoney > 0) {
            //full_popmsg('抱歉,您选择的章节需要'.$need_totalmoney.'个'.C('SITECONFIG.MONEY_NAME').',但您目前只有'.$userinfo['totalmoney'].C('SITECONFIG.MONEY_NAME').',您的'.C('SITECONFIG.MONEY_NAME').'不足以支付本次订阅,请充值!');
            //$output_ary['isInsufficient']=false;
        } else {
            //$output_ary['isInsufficient']=true;
        }
        $output_ary['need_total_money'] = $need_totalmoney;
        //9 开始订购
        //9.1 模拟计算需要扣总计多少金币和银币
        unset($result);
//         $client                         = new \Yar_Client(C("RPCURL") . "/usermoney.php");
        $client = new \HS\Yar("usermoney");
        $result = $client->simulate_buychapters_egoldFirst2($uid, $nobuy_vipchparys);

        if (false === $result) {
            client_output_error($system);
        }


        //9.1.1 模拟得出需要扣多少money
        $need_total_money = $result['money'];
        //9.1.1 模拟得出需要扣多少egold
        $need_total_egold = $result['egold'];

        //每章包含了monetype的模拟扣费结果
        $nobuy_vipchparys = $result['nobuy_vipchparys'];
        unset($result);
        $tmp_total_money = $need_total_money + $need_total_egold;
        if ($tmp_total_money < 0) {
            $output_ary['isInsufficient'] = true;
        } else {
            $output_ary['isInsufficient'] = false;
        }
        //折扣,并且需扣费大于0

        if (!empty($title_discount_buyall) && $output_ary['need_total_money'] > 0) {
            $title = $title_discount_buyall . ',仅需' . $output_ary['need_total_money'] . C('SITECONFIG.MONEY_NAME');
        }//没有折扣,扣费大于0
        elseif (empty($title_discount_buyall) && $output_ary['need_total_money'] > 0) {
            //android、ios不同
            if (CLIENT_NAME == 'android') {
                $title = '下载全部章节需要' . $output_ary['need_total_money'] . C('SITECONFIG.MONEY_NAME') . ',不会重复扣费';
            } elseif (CLIENT_NAME == 'ios') {
                $title = '下载' . $limit_str . '章节需要' . $output_ary['need_total_money'] . C('SITECONFIG.MONEY_NAME') . ',不会重复扣费';
            }
        }//折扣,需扣费等于0
        elseif (!empty($title_discount_buyall) && $output_ary['need_total_money'] == 0) {
            $title = '';
        }//没有折扣,扣费等于0
        elseif (empty($title_discount_buyall) && $output_ary['need_total_money'] == 0) {
            $title = '';
        }
        if (!empty($title) && $output_ary['isInsufficient']) {
            $title .=',但您的余额不足,请充值';
        }
        $output_ary['title'] = $title;
        $output_ary['isFree'] = false;
        if ($xianmian_set) {
            //TODO 限免的书籍不允许下载，目前以余额不足来屏蔽操作。
            $output_ary['title'] = '本书限时免费中，仅可下载免费章节！';
            $output_ary['need_total_money'] = '999999999';
            $output_ary['isInsufficient'] = true;
            $output_ary['isFree'] = true;
        }
        $this->ajaxReturn($output_ary);
    }

    /**
     * 查询小说当前章节数
     *
     * @param int $bid 书号
     * @param string $action 动作名，getchaptercount：查询小说当前章节数，getlzinfo：查询小说完结状态
     *
     * @return array json
     */
    public function _getchptcountandlzinfo() {
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            //TODO:这儿原站为什么不用client_output_error，应该是header返回
            //ios_display_error(IOS_ERR_PARAMS);
            echo '参数错误';
            exit;
        }
        //此处以IP+BID做一下限制，30分钟内只返回一次
        $key = get_client_ip(1).'.'.$bid;
        $cacheObj = new \HS\MemcacheRedis();
        if($cacheObj->getMc($key)) {
            client_output_error('repeat');
        }
        $cacheObj->setMc($key, NOW_TIME, 1800);

        $bookmodel = new \Client\Model\BookModel();
        $bookinfo = $bookmodel->getBook($bid);

        if (!is_array($bookinfo)) {
            client_output_error(C('ERRORS.booknotfind'));
        }

        if ($bookinfo['copyright'] != 3) {
            if ($bookinfo['copyright'] == 1) {
                client_output_error(C('ERRORS.booknotfind'));
            }
        }

        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
            client_output_error(C('ERRORS.booknotfind'));
        }

        $action = I('param.action', '', 'trim');

        if (!$action) {
            client_output_error('unknown action!');
        }

        //查询小说当前章节数
        if ($action == 'getchaptercount') {
            $chapterlist = $bookmodel->getChapter($bid);
            foreach ($chapterlist as $juanorder => $juan) {
                $chaptercount+=count($juan['chparys']);
            }
            $this->ajaxReturn(array('ChapterCount' => $chaptercount, 'IsFinished' => $bookinfo['lzinfo']));
        }

        //查询小说完结状态
        if ($action == 'getlzinfo') {
            $this->ajaxReturn(array('IsFinished' => $bookinfo['lzinfo']));
        }
    }
    /**
     * 客户端书架批量获取更新状态
     * @param string $bids 书籍id以~连接 post
     */
    public function _getchapternum(){
        $output = array('status'=>0, 'message'=>'','url'=>'');
        $bids = I('post.bids', '', 'trim');
        if(!$bids){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $bidArr = explode('~', $bids);
        if(count($bidArr) < 1){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $booklist = array();
        $bookModel = new \Client\Model\BookModel();
        //逐个检查bid是否合法
        foreach ($bidArr as $key=>$bid){
            $bookinfo = array();
            $tmp = array();
            if(intval($bid) < 1){
                //直接删除这本书
                unset($bidArr[$key]);
                continue;
            }else{
                $bookinfo = $bookModel->getBook($bid);
                if(!$bookinfo){
                    unset($bidArr[$key]);
                    continue;
                }
                //不可显示，直接删除
                if ($bookinfo['copyright'] == 1) {
                   unset($bidArr[$key]);
                   continue;
                }
                if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
                    unset($bidArr[$key]);
                    continue;
                }
                //获取本书的章节总数
                $chapterlist = $bookModel->getChplistByBid($bid);
                $chapternum = intval($chapterlist['vipchpcount']) + intval($chapterlist['freechpcount']);
                $tmp = array('bid'=>$bid, 'ChapterCount'=>$chapternum);
                $booklist[] = $tmp;
            }
        }
        if(!$booklist){
            $output['message'] = '暂无记录';
        }else{
            $output['status'] = 1;
            $output['list'] = $booklist;
        }
        $this->ajaxReturn($output);
    }

    /**
     * 添加书籍到服务器书架书签
     *
     * @param int $bid 书号
     * @param int $chpid 章节号
     * @param string $curver 当前版本
     *
     * @return array json数组
     */
    public function _addbookshelf() {

        //获得客户端所有错误配置信息，并变量化
        extract(C('ERRORS'));
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            client_output_error($needlogin);
        }

        $chpid = I('request.chpid', 0, 'intval');
        $bid = I('request.bid', 0, 'intval');
        //TODO:原来程序未交待$chgid来源
        $chgid = I('chgid', 0, 'intval');   //intval($chgid);
        if (!$bid && !$chpid) {
            client_output_error($params);
        }

        $bookshelf = M('bookshelf_category');
        $bookmap = array("uid" => $uid);
        if (!$chgid) {
            $categoryinfo = $bookshelf->field('category_id,category_name')->where($bookmap)->order("category_id")->find();
        } else {
            $bookmap['category'] = $chgid;
            $categoryinfo = $bookshelf->field('category_id,category_name')->where($bookmap)->order("category_id")->find();
        }
        if (!$categoryinfo) {
            client_output_error($system);
        }
        $bModel = new \Client\Model\BookModel();
        if ($chpid) {//如果有章节aid
            $chapterlist = $bModel->getChplistByBid($bid);
            foreach ($chapterlist['list'] as $v) {
                if ($v['chapterid'] == $chpid) {
                    $chpinfo = $v;
                    continue;
                }
            }
            if (!is_array($chpinfo)) {
                client_output_error($chapternotfind);
            }
            $bookinfo = $bModel->getBook($bid);
            $bookinfo['last_updatechptitle'] = $chpinfo['title'];
            $bookinfo['last_updatechpid'] = $chpid;
            $bookinfo['last_updatetime'] = $chpinfo['publishtime'];
            $bid = $chpinfo['bid'];
        } elseif ($bid) {
            $bookinfo = $bModel->getBookByBid($bid);
            if (!is_array($bookinfo)) {
                client_output_error($booknotfind);
            }
        } else {
            client_output_error($system);
        }

        $favmodel = M('fav');
        $favmap = array(
            "bid" => $bid,
            "uid" => $uid,
        );
        $favinfo = $favmodel->field("fid,bid,uid")->where($favmap)->find();

        //书架上已经此书,则更新书签
        $data['bookmark'] = $bookinfo['last_updatechptitle'] . "\t" . $bookinfo['last_updatechpid'] . "\t" . $bookinfo['last_updatetime'];
        if (is_array($favinfo)) {
            unset($favmap);
            $favmap = array("fid" => $favinfo['fid']);
            $favmodel->data($data)->where($favmap)->save();
            $this->ajaxReturn(array('status' => 1, 'message' => 'ok'));
        }

        $data['bid'] = $bid;
        $data['uid'] = $uid;
        $data['category_id'] = $categoryinfo['category_id'];

        $favmodel->data($data)->add();
        unset($data);

        M("user")->execute("UPDATE  wis_user SET totalfav=totalfav+1 WHERE uid=" . $uid);

        //更新小说收藏数据

        $toupiao_num = 1; //每次加几个收藏

        $Query = "UPDATE wis_book SET total_fav=total_fav+{$toupiao_num}, ";
        $Query .= "week_fav=week_fav+{$toupiao_num}, ";
        $Query .= "month_fav=month_fav+{$toupiao_num} ";
        $Query .= "WHERE bid='" . $bid . "'";
        M("book")->execute($Query);

        //每本书只有一次收藏可以获取积分
        $votemap = array(
            "optype" => 5,
            "uid"    => $uid,
            "bid"    => $bid,
        );
        $voteinfo = M("votelogs")->where($votemap)->find();
        if (!$voteinfo) {
            //$newjifen = fensiObj()->add_fensi_jifen($uid, $bid, 1, 'coll');
            $fensimodel = new \Client\Model\FensiModel();
            $fensimodel->addFansIntegral($bid, $uid, 1);
            //TODO:调用新模型
            //commentObj()->incry_booktop_jifen_allktype($bid, $bookinfo['sex_flag'], $newjifen);
            ///////////////////写日志开始////////////////////////////
            //TODO:有redis也有数据库日志
            //add_votelog($bid, 'fav', 1);
            //缓存日志
            $user = session();
            $bModel->addBooktrend($bid, 2, $user, $toupiao_num);
            //数据库日志
            unset($data);
            $data = array(
                'optype' => 3,
                'bid'    => $bid,
                'uid'    => $uid,
                'opnum'  => 1,
                'optime' => time()
            );
            M('votelogs')->data($data)->add();
            /////////////////////日志结束//////////////////////////
            if (CLIENT_NAME == 'android') {
                $dayfav = M('android_count_dayfav');
                //统计日收藏数
                $star_time = 0;
                $end_time = 0;
                mk_time_xiangdui(time(), "thisday", $star_time, $end_time);
                $daymap = array(
                    "bid"     => $bid,
                    "addtime" => $star_time,
                );
                $info = $dayfav->field("id,bid")->where($daymap)->find();
                if ($info['bid']) {
                    $infomap = array("id" => $info['id']);
                    $dayfav->where($infomap)->setInc("favnum", 1); ;
//                     $dayfav->execute("UPDATE wis_android_count_dayfav SET favnum=favnum+1 WHERE id=" . $info['id']);
                } else {
                    $tmp = array(
                        'bid'     => $bid,
                        'favnum'  => 1,
                        'addtime' => $star_time
                    );
                    $dayfav->data($tmp)->add();
                    unset($tmp);
                }
            }
        }
        $this->ajaxReturn(array('status' => 1, 'message' => 'ok'));
    }

    /**
     * 获得书籍客户端专用封面图
     * @param string $t 图片尺寸
     * @param int $bid 书号
     * @param string $forceflush
     * @return unknown
     */
    public function _getbookcover() {
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            //TODO:这儿原站为什么不用client_output_error，应该是header返回
            //ios_display_error(IOS_ERR_PARAMS);
            echo '参数错误';
            exit;
        }
        $bookmodel = new \Client\Model\BookModel();
        $bookinfo = $bookmodel->getBook($bid);

        if (!is_array($bookinfo)) {
            client_output_error(C('ERRORS.booknotfind'));
        }

        if ($bookinfo['copyright'] != 3) {
            if ($bookinfo['copyright'] == 1) {
                client_output_error(C('ERRORS.booknotfind'));
            }
        }

        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) {
            client_output_error(C('ERRORS.booknotfind'));
        }

        $type = I('get.t', '', 'trim');
        if (empty($type)) {
            $type = 'middle';
        } else {
            $type = 'large';
        }
        $imgPath = C('IMG1_ROOT') . '/Public/Client/image';

        //默认封面则不保存cache_file
        $issave = true;
        if ($bookinfo['imgstatus'] == 3) {
            $conver_file = C('BOOKFACE_ROOT') . '/' . get_bookface_filepath($bid) . '/' . get_bookface_filename($bid, $type);
        }
        if (!file_exists($conver_file)) {
            $issave = false;
            $conver_file = $imgPath . '/001_' . $type . '.jpg';
        }
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('android', 'ios'))) {
            //缓存目录区分客户端
            $shtdir = C('CLIENT.' . CLIENT_NAME . ".sht_dir");
            $dir_prefix = $shtdir . '/' . get_book_staticfilepath($bid);
            $cache_file = $dir_prefix . '/' . 'bookface_' . $type . '.png';
            mDir($dir_prefix);
        } else {
            client_output_error('unknown client');
        }

        $forceflush = I('get.forceflush', '', 'trim');
        if (file_exists($cache_file) && $forceflush != 'Y') {
            header("Content-type: image/png");
            //header("Content-Length:".filesize($cache_file));
            //header("Content-Disposition: attachment; filename={$bid}_{$type}.png");
            echo read($cache_file);
        } else {
            if (file_exists($conver_file)) {
                if ($type == 'middle') {
                    $logoFile = $imgPath . '/cover_' . CLIENT_NAME . '_middle.png';
                } elseif ($type == 'large') {
                    $logoFile = $imgPath . '/cover_' . CLIENT_NAME . '_large.png';
                }
                if (file_exists($logoFile)) {
                    $logoImage = ImageCreateFromPNG($logoFile);
                }

                header("Content-type: image/png");

                $photoImage = ImageCreateFromJpeg($conver_file);
                if ($photoImage) {
                    ImageAlphaBlending($photoImage, true);
                    $imgW = ImageSX($photoImage);
                    $imgH = ImageSY($photoImage);

                    $logoW = ImageSX($logoImage);
                    $logoH = ImageSY($logoImage);
                    ImageCopy($photoImage, $logoImage, 0, 0, 0, 0, $logoW, $logoH);


                    if (CLIENT_NAME == 'android') {
                        $background_color = imagecolorallocate($photoImage, 255, 0, 0);
                        imagerectangle($photoImage, 0, 0, $imgW, $imgH, $background_color);
                    }
                    if (CLIENT_NAME == 'android') {
                        $rounder = new \Client\Common\RoundedCorner($photoImage, 7, 255, 0, 0);
                        $photoImage = $rounder->round_it();
                        imagecolortransparent($photoImage, $background_color);
                    }

                    ob_start();
                    header("cachdfile : N");
                    imagepng($photoImage, $cache_file, 9);
                    ImageDestroy($photoImage);
                    //ImageDestroy($logoImage);
                    //header("Content-Disposition: attachment; filename={$bid}_{$type}.png");
                    //header("Content-Length:".filesize($cache_file));

                    $image_data = read($cache_file);
                } else {
                    //测试机上居然会出现0字节的图片文件
                    //header("Content-Length:".filesize($conver_file));
                    //header("Content-Disposition: attachment; filename={$bid}_{$type}.png");
                    $image_data = read($conver_file);
                }
                echo $image_data;
                //默认封面则不保存
                if ($issave) {
                    _write($cache_file, $image_data);
                }
            } else {
                client_output_error('file not found!');
            }
        }
    }

    /**
     * 获得频道热搜词（IOS）
     *
     * @param string $sex_flag 性别标志 nan:男 nv:女
     *
     * @return array json数组
     */
    public function _hotkeywords() {
        $sex_flag = I('get.sex_flag', '', 'trim');
        $this_month = date('ym');
        $prev_month = date('ym', strtotime('-1 month'));
        if ($sex_flag !== 'nan') {
            $sex_flag = 'nv';
        }
        $s_key = ':otsk:' . CLIENT_NAME . ':' . $this_month . ':' . $sex_flag;
        header('cache_key:' . $s_key);
        //热搜
        $redis = new \Think\Cache\Driver\Redis();
        $daijiazaishouarray = $redis->ZREVRANGE($s_key, 0, -1);

        //pre($s_key);

        $this->ajaxReturn($daijiazaishouarray);
    }

    /**
     * 获得搜索框联想词(IOS)
     *
     * @param string $keyword 关键词
     * @param string $sex_flag 性别标志 nan:男 nv:女
     *
     * @return array json数组
     */
    public function _searchtip() {
        $keyword = I('request.keyword', '', 'trim');
        $sex_flag = I('get.sex_flag', '', 'trim');
        $sex_flag = $sex_flag == 'nan' ? 'nan' : 'nv';
        if ($sex_flag == 'nv') {
            $pclassid = 2;
        } else {
            $pclassid = '';
        }

        //post数据
        $postdata = array(
            "method"      => "search",
            "Pclassids"   => $pclassid,
            "classid"     => "",
            "free"        => 0,
            "finish"      => 0,
            "charnum"     => 0,
            "updatetime"  => 0,
            "keywordtype" => 2,
            "order"       => 4,
            "page"        => 1,
            "pagesize"    => 3,
            "keyword"     => $keyword,
        );

        //TODO:原站这儿写死的，要不要走配置文件？C('HOMEDOMAIN')
//         $SITECONFIG['siteurl'] = 'http://www.hongshu.com';
//         $result                = do_post_request($SITECONFIG['siteurl'] . "/homeajax.do", $postdata, "", 'curl');
//         $url = url('Bookajax/search','','do');
//         $result = do_post_request($url,$postdata,'','curl');
        foreach($postdata as $k => $v){
            $_POST[$k] = $v;
        }
        $result = $this->_search();
        $arr = json_decode($result, true);
        foreach ($arr['bookinfo'] as $tmp) {
            $res['books'][] = $tmp['catename'];
        }

        $postdata["keywordtype"] = 3;
        $_POST['keywordtype'] = 3;
//         $result                  = do_post_request($SITECONFIG['siteurl'] . "/homeajax.do", $postdata, "", 'curl');
//         $result = do_post_request($url,$postdata,'','curl');
        $result = $this->_search();
        $arr = json_decode($result, true);
        foreach ($arr['bookinfo'] as $tmp) {
            $res['authors'][] = $tmp['authorname'];
        }

        $this->ajaxReturn($res);
    }

    /**
     * 检测客户端版本更新状态
     *
     * @param string $uuid unknown
     * @param string $device unknown
     * @param string $channel 频道来源
     * @param string $curver 当前版本
     * @param string $packname 包名
     *
     * @return array json数组
     */
    public function _getversion() {//原api.php中$_POST['action']=='getver';
        $output = array('status' => 0, 'url' => '', 'version' => '1.0', 'message' => '');
        //检查客户端来源
        if (!CLIENT_NAME || !in_array(CLIENT_NAME, array('ios', 'android'))) {
            $output['message'] = '未知客户端来源';
            $this->ajaxReturn($output);
        }

        //TODO:未使用变量
        $uuid = I('post.uuid', '', 'trim');
        $device = I('post.device', '', 'trim');
        $packname = I('post.packname', '', 'trim');
        //当前客户端版本
        $curver = I('post.curver', '', 'trim');
        $channel = I('post.channel', '', 'trim');
        
        //下载域名
        $download_base_url = C('CLIENTAPPURL');
        //最新客户端
        $apk = C('CLIENT.' . CLIENT_NAME . '.apk');

        if (CLIENT_NAME == 'android') {

            //当前最新版本下载地址
            $cur_ver_download = $download_base_url . $apk;

            $output = array('status' => 0, 'url' => '', 'version' => '1.0', 'message' => '');
            $upgrade_files = C('CLIENT.' . CLIENT_NAME . '.upgrade_files');
            if (isset($upgrade_files[$curver])) {
                $output = $upgrade_files[$curver];
                $output['url'] = $cur_ver_download;
            }
        } elseif (CLIENT_NAME == 'ios') {

            //当前最新版本下载地址
            $cur_ver_download = $download_base_url . $channel . $apk;
            $output = array('status'  => 0,
                'url'     => $cur_ver_download,
                'version' => '1.2.2',
                'message' => "1. 紧急修复1.2.1无法下载章节的bug\n"
            );

            $true15 = array(15 => true);
            $upgrade_files = array(
                'yqhhy'         => $true15,
                'wwwhongshucom' => $true15,
                'hiapk'         => $true15,
                'sj360'         => $true15,
                'xiaomi'        => $true15,
                'yingyongbao'   => $true15,
                'wandoujia'     => $true15,
                'mumayi'        => $true15,
            );
            //if($channel=='wwwqihoocom' || $channel=='wwwqihoocom2' || $channel=='wwwqihoocom3'){
            //	$output = array('status'=>0);
            //}
            if (isset($upgrade_files[$channel]) && true === $upgrade_files[$channel][$curver]) {
                $output = $upgrade_files[$curver];
            }
        }

        $this->ajaxReturn($output);
    }

    /**
     * 下载指定章节（包含）之后的所有章节（IOS/downloadchptchapters.php）
     *
     * @param int $bid
     * @param int $chpid
     */
    public function _downloadchptchapters_ios() {
        $ip = get_client_ip();
        $cacheObj = new \HS\MemcacheRedis();
        //最大允许并发同ip访问;
        $max_num = 3;
        //5分钟内并发
        $max_time = 300;
        if (!empty($ip)) {
            $key = C("cache_prefix") . ":ccattack_denyip:" . $ip;
            $num = $cacheObj->get($key);
            if (!$num || $num < $max_num) {
                $num++;
                $cacheObj->setMc($key, $num, $max_num);
            } elseif ($num >= $max_num) {
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                exit;
            }
        }
        //来源
        $fromSite = C("CLIENT.ios.fromsiteid");
        $bid = I("param.bid", 0, "intval");
        $chpid = I("param.chpid", 0, "intval");
//         $isdownload = I("param.isdownload", "", "trim,strtoupper");
        if (!$bid || !$chpid) {
            client_output_error(C("ERRORS.params"));
        }
        //检测登录
        $userModel = new \Client\Model\UserModel();
        $this->check_user_login();
        $uid = isLogin();
        $viplevel = 0;
        if ($uid) {
            $userinfo = $userModel->getUserbyUid($uid);
        } else {
            client_output_error(C("ERRORS.needlogin"));
        }
        if (!$userinfo) {
            client_output_error(C("ERRORS.needlogin"));
        }
        $viplevel = intval($userinfo['viplevel']);
        //判断是否限免
        $yar_client = new \HS\Yar("discountset");
        $result = $yar_client->getDiscountCustomXianmianStatus($bid, $userinfo['viplevel'], $fromSite);
        if ($result) {
            $discount_set = $result['discount_set'];
            $is_discount = $discount_set['is_open'];
            $custom_price_set = $result['custom_price_set'];
            $xianmian_set = $result['xianmian_set'];
            $pricebeishu = $result['pricebeishu'];
            $num = $xianmian_set['num'];
            if ($num) {
                client_output_error('This chapter is read only!');
            }
        }
        //获取书籍信息
        $bookModel = new \Client\Model\BookModel();
        //判断订阅状态
//         if ($isdownload == "Y") {
//             $autoorder = -2;
//         } else {
        $orderstatus = $bookModel->getAutoOrderStatus($bid, $userinfo);
        if (is_array($orderstatus) && isset($orderstatus['autoDinyueInfo'])) {
            $autoorder = $orderstatus['autoDinyueInfo'];
        } else {
            $autoorder = false;
        }
//         }
        $bookinfo = $bookModel->getBook($bid, 0);
        if (!$bookinfo) {
            client_output_error(C("ERRORS.booknotfind"));
        }
        if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1) {
            client_output_error(C("ERRORS.booknotfind"));
        }
        //潇湘的书不能显示在手机版权
        if ($bookinfo['sourceId'] == 101) {
            client_output_error(C("ERRORS.booknotfind"));
        }
        if (($bookinfo['publishstatus'] != C("BOOK_CANDISPLAY") && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && $viplevel < 1)) {
            client_output_error(C("ERRORS.booknotfind"));
        }
        //起始章节
        $chporder = 0;
        $chapterinfo = $bookModel->getChapterByCid($bid, $chpid);
        //TODO
        //$chapterinfo = M("chapter01")->where("chapterid=11074277")->find();
        if ($chapterinfo) {
            $chporder = $chapterinfo['chporder'];
        } else {
            client_output_error(C("ERRORS.chapternotfind"));
        }
        //先扣费,然后在输出章节内容
        if (is_array($userinfo) && ($userinfo['is_deleted'] == 1 || $userinfo['is_locked'] == 1)) {
            client_output_error(C("ERRORS.needlogin"));
        }
        $chapterlist = $bookModel->getChapter($bid);
        //获取要订阅的章节
        //dump($chapterlist);
        if (!$chapterlist) {
            client_output_error(C("ERRORS.chapternotfind"));
        } else {
            $tt = array_column($chapterlist, 'chparys');
            if ($tt) {
                $chparys = array();
                foreach ($tt as $v) {
                    $chparys = array_merge($chparys, $v);
                }
            } else {
                $chparys = $chapterlist[0]['chparys'];
            }
        }
        $buyids = array();
        foreach ((array) $chparys as $vo) {
            if ((int) $vo['chporder'] >= $chporder) {
                $buyids[] = $vo['chapterid'];
            }
        }
        if (!$buyids) {
            client_output_error(C("ERRORS.params"));
        }
        //剔除选中章节之前的章节
//         foreach($chapterlist[0]['chparys'] as $key => $val){
//             if(!in_array($val['chapterid'],$buyids)){
//                 unset($chapterlist[0]['chparys'][$key]);
//             }
//         }
        //TODO 测试环境暂未加锁
        $res = $bookModel->orderChapterByCache($bid, $buyids, $userinfo, false, $autoorder, $fromSite);
        switch ($res) {
//             case "orderisruning":
//                 client_output_error(C("ERRORS.system"));
//                 break;
//             case "orderchperror":
//                 client_output_error(C("ERRORS.system"));
//                 break;
            case "consumefail":
                client_output_error(C("ERRORS.chptneedpay"));
                break;
            case "nochapterorder":
            case "orderchpsuc":
                ios_output_chapters($bid, $chapterlist, false, false, $chporder);
                break;
            default:
                client_output_error(C("ERRORS.system"));
                break;
        }
    }

    /**
     * 封面页：继续阅读
     *
     * @param int $bid get
     */
    public function _getViewReadLog() {
        $bid = I("get.bid", 0, "intval");
        $output = array("bid" => $bid, "lastreadchpid" => 0, "lastreadchporder" => 1, "isvip" => 0, "xianmian_set" => false);
        if (!$bid) {
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $chapterlist = $bookModel->getChplistByBid($bid);
        if (!$chapterlist) {
            $this->ajaxReturn($output);
        }
        $cookiebook = getBookCookieFav($bid);
        $chapterinfo = array();
        if (!$cookiebook) {
            $chapterinfo = array_shift($chapterlist['list']); //如果没有阅读记录，则取第一章
        } else {
            foreach ($chapterlist['list'] as $vo) {
                if ($vo['chapterid'] == $cookiebook['chapterid']) {
                    $chapterinfo = $vo;
                }
                if ($chapterinfo) {
                    break;
                }
            }
        }
        if (!$chapterinfo) {
            $this->ajaxReturn($output);
        }
        //是否是限免书籍
        $this->check_user_login();
        $viplevel = 0;
        $uid = isLogin();
        if ($uid) {
            $viplevel = session('viplevel');
        }
        $yar_client = new \HS\Yar("discountset");
        $fromsite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        $result = $yar_client->getDiscountCustomXianmianStatus($bid, $viplevel, $fromsite);
        if ($result) {
            $output['xianmian_set'] = $result['xianmian_set'];
        }
        $output['bid'] = $bid;
        $output['lastreadchpid'] = intval($chapterinfo['chapterid']);
        $output['lastreadchporder'] = intval($chapterinfo['chporder']);
        $output['isvip'] = intval($chapterinfo['isvip']);
        $this->ajaxReturn($output);
    }

    /**
     * 封面页ajax(右侧标签块数据、底部最新章节和插画)
     *
     * @param int $bid get
     * @param int $imgnum get 需要显示的插画数量
     * @prarm int $parts 最新章节显示的段落数量，默认为3段
     */
    public function _view_yqm() {
        $this->check_user_login();
        $output = array('status' => 0, 'message' => '', 'url' => '', 'uid' => 0);
        $imgnum = I('get.imgnum', 4, 'intval');
        $bid = I("get.bid", 0, "intval");
        if (!$bid) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $bookinfo = $bookModel->getBook($bid);
        if (!$bookinfo) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        /* 右侧标签块start */
        $lists = array();
        $lists['bid'] = $bookinfo['bid'];
        //书名
        $lists['catename'] = $bookinfo['catename'];
        //TAG
        $lists['tags'] = $bookinfo['tags'] ? explode(' ', $bookinfo['tags']) : '';
        //字数
        $lists['charnum'] = $bookinfo['charnum'];
        $lists['lzinfo'] = $bookinfo['lzinfo'];
        $lists['shouquaninfo'] = $bookinfo['shouquaninfo'];
        //粉丝值，粉丝榜中所有粉丝的积分
        $fans_num = $bookModel->getFansNumber($bid);
        if (intval($fans_num) < 1) {
            $lists['fansnum'] = 0;
        } else {
            $fansModel = new \Client\Model\FensiModel();
            $fans = $fansModel->getBookFans($bid, $fans_num, 1);
            $lists['fansnum'] = 0;
            if ($fans) {
                foreach ($fans as $vo) {
                    $lists['fansnum'] += $vo['jifen'];
                }
            }
        }
        $lists['classname'] = $bookinfo['smallclassname'];
        $lists['totalhit'] = $bookinfo['total_hit'];
        //是否收藏
        $uid = isLogin();
        if ($uid) {
            $output['uid'] = $uid;
            $lists['uid'] = $uid;
            $isfav = $bookModel->checkFav($uid, $bid);
            $lists['isfav'] = $isfav ? true : false;
        } else {
            $lists['uid'] = 0;
            $output['uid'] = 0;
            $lists['isfav'] = false;
        }
        //最新更新日期
        $lastupdatetime = $bookinfo['last_vipupdatetime'] ? $bookinfo['last_vipupdatetime'] : $bookinfo['last_updatetime'];
        $lists['lastupdatetime'] = date("Y-m-d H:i", $lastupdatetime);
        $lists['lastudtime'] = friendly_date($lastupdatetime); //n分钟前..n小时前..
        //开始连载日期
        $lists['lzdate'] = date("Y-m-d", $bookinfo['posttime']);
        //收藏数量
        $lists['favnum'] = $bookinfo['total_fav'];
        //获取最后一章章节信息
        $chapterinfo = array();
        $chapterinfo = $bookModel->getLastChapter($bid);
        if (!$chapterinfo) {
            $output['message'] = '暂无章节信息';
            $this->ajaxReturn($output);
        }
        //最后更新的章节id
        $lists['lastupdatechpid'] = $chapterinfo['chapterid'];
        //继续/开始阅读,区分vip和免费章节
        $cookiebook = getBookCookieFav($bid);
        if ($cookiebook && $cookiebook['chapterid']) {
            $lists['lastreadchpid'] = $cookiebook['chapterid'];
            $lists['isread'] = 1;
            $chapter = $bookModel->getChapterByCid($bid, $cookiebook['chapterid']);
            if ($chapter) {
                $lists['isvip'] = intval($chapter['isvip']);
            } else {
                $lists['isvip'] = 0;
            }
        } else {
            $lists['lastreadchpid'] = 0;
            $lists['isread'] = 0;
            $lists['isvip'] = 0;
        }
        //作者头像
        $authormap = array("authorid" => $bookinfo['authorid']);
        $authoruid = M('author')->where($authormap)->getField('uid');
        $authorimg = getUserFaceUrl($authoruid, "big");
        $lists['authorimg'] = $authorimg;
        //作者名
        $lists['authorname'] = $bookinfo['author'];
        /* 右侧标签块end */

        /* 底部最新章节start */
        //获取最后一章章节内容
        $chaptercontent = $bookModel->getChapterContent($bid, $chapterinfo['chapterid']);
        if (!$chaptercontent) {
            $chaptercontent = '暂无章节内容';
        } else {
            //只截取前几段内容，否则容易导致泄漏，默认为3段
            $result = preg_match_all('@<p>([^<]+)</p>@iS', $chaptercontent, $match);
            if ($result) {
                $parts = min(10, I('parts', 8, 'intval'));  //最多取10段
                $chaptercontent = nl2p(implode("\n", array_slice($match[1], 0, $parts)), 2);
            }
        }
        $chapterinfo['content'] = $chaptercontent;
        /* 底部最新章节end */

        /* 插画start */
        $chahuaModel = new \Client\Model\ChahuaPicModel();
        $chahuaModel->table($chahuaModel->_getTableName($bid));
        $chmap = array('bid' => $bid);
        $chahua = $chahuaModel->where($chmap)->limit(0, $imgnum)->order('create_time DESC')->field('id,bid,chapterid,title,type')->select();
        if ($chahua) {
            $output['imglist'] = $chahua;
        } else {
            $output['imglist'] = '';
        }
        /* 插画end */

        $output['status'] = 1;
        $output['taglist'] = $lists;
        $output['chapterlist'] = $chapterinfo;
        $this->ajaxReturn($output);
    }

    /**
     * 阅读页检测收藏
     * @param int $bid post
     * @param int $uid session
     */
    public function _checkfav() {
        $this->check_user_login();
        $output = array('isfav' => 0);
        $bid = I('post.bid', 0, 'intval');
        $uid = isLogin();
        if (!$uid || !$bid) {
            $this->ajaxReturn($output);
        }
        $map = array(
            'uid' => $uid,
            'bid' => $bid,
        );
        $bookModel = new \Client\Model\BookModel();
        $favinfo = $bookModel->checkFav($uid, $bid);
        if ($favinfo) {
            $output['isfav'] = 1;
        }
        saveBookFavCookie($output['isfav'], $bid);
        $this->ajaxReturn($output);
    }


    /**
     * 阅读检测收藏、评论、最新的章节信息
     * 把封面上需要调用三个接口才能获取的部分数据封装到一个接口中
     * 取是否收藏，取评论的第一页，取最新的4个章节
     * @param int $bid 书籍id post
     * @param int $commentNum 显示多少条评论记录
     * @param int $updateList 显示多少条最新章节
     */
    public function _multipleData_myd(){
        $this->check_user_login();
        $output = array(
            'status' => 0,
            'isFav' => 0,
            'pronum' => 0,              //打赏道具数
            'total_flower' => 0,        //获赞数
            'updateList' => array(),
            'list' => array(),
        );
        $bid = I('param.bid', 0, 'intval');
        $uid = isLogin();
        $bookModel = new BookModel();
        //阅读页检测收藏部分
        $favInfo = $bookModel->checkFav($uid,$bid);     //检查某用户是否已收藏某书
        if ($favInfo){
            $output['isFav'] = 1;
        }
        //阅读页用户评论部分,只查询前5条
        $commentNum = I("param.commentNum", 5, "intval"); //获取页面传来的显示评论数，如果不传默认显示5
        $map = array(
            'bid'            => $bid,
            'deleted_flag'   => array('neq', 1),
            'content'        => array('neq', ''),
        );
        if($uid){
            $map[] = array(
                'forbidden_flag'=>array('neq', 1),
                'uid'=>$uid,
                '_logic'=>'OR'
            );
        }else{
            $map['forbidden_flag'] = array('neq', 1);
        }
        $commentModel = new \Client\Model\NewcommentModel();
        $list = $commentModel
            ->field('comment_id,username,bid,title,content,creation_date,forbidden_flag,deleted_flag,highlight_flag,reply_amount,
                    is_locked,doublesort,is_lcomment,nickname,zan_amount,chapterid,chaptername,uid')
            ->where($map)
            ->order('doublesort DESC,creation_date DESC')
            ->limit($commentNum)
            ->select();
        //此处加上一个用户的头像获取
        foreach($list as $key=>$val){
            $list[$key]['avatar'] = getUserFaceUrl($val['uid']);//显示用户头像
            $list[$key]['updatetime'] = friendly_date($val['creation_date']);//取友好时间
        }
        if ($list){
            $output['list'] = $list;
        }
        //取书籍获赞数
        $bookInfo = $bookModel->getBook($bid);
        if ($bookInfo){
            $output['total_flower'] = $bookInfo['total_flower'];
        }
        //取书籍获得道具数
        $pronum = 0;
        $bookTrend = $bookModel->getBooktrend($bid);
        if($bookTrend && is_array($bookTrend)){
            foreach($bookTrend as $vo){
                if($vo['type'] == 7){
                    $pronum += $vo['num'];
                }
            }
        }
        $output['pronum'] = $pronum;
        //取最新四个章节
        $commentNum = I("post.ChapterNum", 4, "intval");
        $chaptersList = $bookModel->getChapter($bid);
        $chaptersList = array_reverse($chaptersList);
        foreach ($chaptersList[0]['chparys'] as $key=>$val){
            //添加时间更新时间字段返回给页面
            $chaptersList[0]['chparys'][$key]['updatetime'] = date('Y-m-d', $chaptersList[0]['chparys'][$key]['publishtime']);
        }
        $output['updateList'] = array_slice($chaptersList[0]['chparys'],-4,$commentNum);//取最新四个章节
        $output['status'] = 1;
        $this->ajaxReturn($output);
    }

    /**
     * yqm阅读页获取全部目录
     *
     * @param int $bid get·
     */
    public function _getAllChapter() {
        $output = array('status' => 0, 'message' => '', 'url' => '', 'list' => '');
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $chapters = $bookModel->getChplistByBid($bid);
        //$chapter = $bookModel->getChapter($bid);
        //每本书最后一章的相关信息
        $lastChpter = $bookModel->getLastChapter($bid);
        if (!$chapters) {
            $output['message'] = '暂无更多章节';
        } else {
            //喵阅读要检测是否订阅
            if(CLIENT_NAME == 'myd'){
                $this->check_user_login();
                $uid = isLogin();
                $client = new \HS\Yar("dingoujson");
                $vipchpstr = $client->checkUserAll($bid, $uid, 'ids');
                $vipchparr = explode(',', $vipchpstr);
                $juantitle = $chapters['list'][0]['juantitle'];//获取到一个元素（数组）中的juantitle

                foreach($chapters['list'] as $key=>&$val){
                    if ($key) {
                        if ($val['juantitle'] == $juantitle) {
                            $val['juantitle'] = '';
                        }
                        if ($val['juantitle'] != $juantitle && $val['juantitle']) {
                            //标出每卷的最后一张
                            $chapters['list'][$key-1]['juanlastchapter'] = 1;
                            $juantitle = $val['juantitle'];
                        }
                    }
                    if(in_array($val['chapterid'], $vipchparr)){
                        $chapters['list'][$key]['isorder'] = 1;
                    }else{
                        $chapters['list'][$key]['isorder'] = 0;
                    }
                    if ($chapters['list'][$key]['chapterid'] == $lastChpter['chapterid']){
                        $chapters['list'][$key]['alllastchapter'] = '1';
                    }

                }

            }
            $output['status'] = 1;
            $output['list'] = $chapters['list'];
        }
        $this->ajaxReturn($output);
    }

    /**
     * 获取弹幕数据
     *
     */
    public function getBarragerDataAction() {
        $result = array(
            'status'  => 0,
            'code'    => 0,
            'message' => '参数错误'
        );
        $bid = I('bid', 0, 'intval');
        $cid = I('cid', 0, 'intval');
        if (!$bid || !$cid) {
            $this->ajaxreturn($result);
        }
        $count = I('count', 0, 'intval');
        $count = $count > 0 ? $count : 50;
        $barrageModel = new \Client\Model\BarragerModel();
        $res = $barrageModel->getData($bid, $cid, $count);
        if($res && $res['total_num'] > 0){
            $result = array(
                'status'  => 1,
                'message' => '成功',
                'data'    => $res,
            );
        }else{
            $result['message'] = '暂无更多弹幕';
        }
        
        $this->ajaxReturn($result);
    }

    /**
     * 更新阅读记录
     * @param int $bid get
     * @param int $cid 章节id get
     */
    public function _updateReadLog() {
        $output = array("status" => 0, "message" => "", "url" => "");
        $bid = I('get.bid', 0, 'intval');
        $chpid = I('get.chpid', 0, 'intval');
        if (!$bid || !$chpid) {
            $output['message'] = "参数错误";
            $this->ajaxReturn($output);
        }
        //获取章节chporder
        $bookModel = new \Client\Model\BookModel();
        $chapterinfo = $bookModel->getChapterByCid($bid, $chpid);
        if (!$chapterinfo) {
            $output['message'] = "章节不存在";
            $this->ajaxReturn($output);
        }
        $chporder = $chapterinfo['chporder'];
        addonecookiefav($bid, $chpid, 0, $chporder);
    }

    /**
     * 发送弹幕
     * 
     * @return array
     *      needlogin字段用来告知客户端是否需要登录(=1需要登录，=0不需要登录)--元气萌客户端开始启用
     */
    public function _sendBarrage() {
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $output = array('status'=>0,'message'=>'','url'=>'');
            $bid = I('bid', 0, 'intval');
            $nuid = I('uid', 0, 'intval');
            $cid = I('cid', 0, 'intval');
            $content = I('content', '', 'trim');
            if (!$bid || !$cid || !$content) {
                $output['message'] = '参数错误';
                $this->ajaxReturn($output);
            }
            $content = trim(str_replace(array("'", '"'), '', $content));
            if (strLength($content) > C('BARRAGE_MAX_LENGTH', null, 30)) {
                $output['message'] = '写的字太多了！';
                $this->ajaxReturn($output);
            }
            $data = array(
                'bid'         => $bid,
                'cid'         => $cid,
                'uid'         => $uid,
                'create_time' => NOW_TIME,
                'content'     => $content,
                'nickname'    => session('nickname')
            );
            //组装表名
            $tabNum = str_pad(floor($bid / C('CHPTABLESIZE')), 2, "0", STR_PAD_LEFT);
            $tabName = "Barrage" . $tabNum;
            $model = new \Client\Model\BarragerModel($tabName);
            //$model = M($tabName);
            $res = $model->add($data);
            if ($res) {
                $result = array(
                    'status'  => 1,
                    'message' => '提交成功',
                    'id'      => $res
                );
            } else {
                $result = array(
                    'status'  => 0,
                    'message' => $model->getError(),
                    'id'      => 0
                );
            }
            $this->ajaxReturn($result,'json');
        }else{
            $output = array('status'=>0,'message'=>'please login first!','url'=>'','needlogin'=>1);
            $this->ajaxReturn($output,'json');
        }
    }

    /**
     * 获取阅读记录
     *
     */
    public function _getreadlog() {
        $output = array("status" => 0, "message" => "", "url" => "");
        $bookinfo = array();
        //获取cookie阅读记录
        $cookiebooks = getcookiefavbooklist();
        if (!$cookiebooks) {
            $output['message'] = '暂无阅读记录';
            $this->ajaxReturn($output);
        }
        //是否收藏
        $this->check_user_login();
        $favbids = array();
        $uid = isLogin();
        if ($uid) {
            $bids = array_column($cookiebooks, 'bid');
            $map = array(
                "uid" => $uid,
                "bid" => array("IN", $bids),
            );
            $favs = M('fav')->field('bid')->where($map)->select();
            foreach ($favs as $val) {
                $favbids[] = $val['bid'];
            }
        }
        $bookModel = new \Client\Model\BookModel();
        if ($cookiebooks) {
            //总章节数、最后章节号、最后的更新时间、分类、最后阅读的章节
            foreach ($cookiebooks as $k => $vo) {
                $tmp = array();
                //分类
                $tmp['category'] = C('CATEGORY')[$vo['classid']]['subclass'][$vo['classid2']]['smalltitle'];
                //最后更新时间
                if ($vo['last_vipupdatetime']) {
                    $lastupdatetime = $vo['last_vipupdatetime'];
                } else {
                    $lastupdatetime = $vo['last_updatetime'];
                }
                $tmp['last_vipupdatetime'] = $lastupdatetime;
                //最后更新章节id
                if ($vo['last_vipupdatechpid']) {
                    $lastchpid = $vo['last_vipupdatechpid'];
                } else {
                    $lastchpid = $vo['last_updatechpid'];
                }
                //总章节数
                $allchapters = $bookModel->getChapter($vo['bid']); //缓存
                $last_update_chapter_info = array(); //最后一张章节信息
                $chapterinfo = array(); //当前章节信息
                if ($allchapters) {
                    if (!$vo['chapterid']) {
                        foreach ($allchapters as $juan) {
                            if ($juan['chparys']) {
                                foreach ($juan['chparys'] as $chapter) {
                                    if ($chapter['chporder'] == $vo['chpidx']) {
                                        $vo['chapterid'] = $chapter['chapterid'];
                                        break;
                                    }
                                }
                            }
                            if ($vo['chapterid']) {
                                break;
                            }
                        }
                    }
                    $totalchpnum = 0;
                    foreach ($allchapters as $juan) {
                        if (isset($juan['chparys'])) {
                            $totalchpnum += count($juan['chparys']);
                            //获取最后一章和当前章节信息
                            if (!$last_update_chapter_info || !$chapterinfo) {
                                foreach ($juan['chparys'] as $chapter) {
                                    if ($chapter['chapterid'] == $lastchpid) {
                                        $last_update_chapter_info = $chapter;
                                    }
                                    if ($chapter['chapterid'] == $vo['chapterid']) {
                                        $chapterinfo = $chapter;
                                    }
                                }
                            } else {
                                continue;
                            }
                        }
                    }
                } else {
                    unset($cookiebooks[$k]);
                    continue;
                }
                $tmp['totalChpNum'] = $totalchpnum;
                //获取最后一章章节号
                if ($last_update_chapter_info) {
                    $lastchpnum = $last_update_chapter_info['chporder'];
                } else {
                    unset($cookiebooks[$k]);
                    continue;
                }
                //判断当前章节是否是vip章节
                $tmp['isvip'] = 0;
                if ($chapterinfo) {
                    $tmp['isvip'] = intval($chapterinfo['isvip']);
                }
                $tmp['last_updatechpnum'] = $lastchpnum;
                //最后阅读章节
                if ($vo['chpidx']) {
                    $tmp['last_readchpnum'] = $vo['chpidx'];
                    $tmp['last_readchpid'] = $vo['chapterid'];
                } else {
                    $tmp['last_readchpid'] = 0;
                    $tmp['last_readchpnum'] = 1;
                }
                //书名
                $tmp['catename'] = $vo['catename'];
                //封面图片
                $tmp['imgurl'] = getBookfacePath($vo['bid'], 'middle');
                //书id
                $tmp['bid'] = $vo['bid'];
                //判断是否收藏
                if (in_array($vo['bid'], $favbids)) {
                    $tmp['isfav'] = true;
                } else {
                    $tmp['isfav'] = false;
                }
                $tmp['classid2'] = $vo['classid2'];
                //最后更新章节title
                if ($vo['last_vipupdatechptitle']) {
                    $tmp['last_updatechptitle'] = $vo['last_vipupdatechptitle'];
                } else {
                    $tmp['last_updatechptitle'] = $vo['last_updatechptitle'];
                }
                //此处要做一下判断，是否VIP章节，然后返回不同的链接。
                if ($tmp['isvip']) {
                    $tmp['readurl'] = url('Book/readvip', array('bid' => $vo['bid'], 'chpid' => $vo['chapterid']), 'do');
                } else {
                    $tmp['readurl'] = url('Book/read', array('bid' => $vo['bid'], 'chpid' => $vo['chapterid']));
                }
                $list[] = $tmp;
            }
            if ($list) {
                $output['status'] = 1;
                $output['list'] = $list;
                $output['totalnum'] = count($list);
            } else {
                $output['status'] = 0;
                $output['totalnum'] = 0;
                $output['message'] = '暂无阅读记录';
            }
        } else {
            $output['status'] = 0;
            $output['totalnum'] = 0;
            $output['message'] = '暂无阅读记录';
        }
        $this->ajaxReturn($output);
    }

    /**
     * 获取上下章
     *
     * @param int $bid get
     * @param int $chpid get
     *
     * @return array
     */
    public function _getPreNextChapter() {
        $output = array(
            "status"      => 0,
            "totalchapnum" => 0,
            "chpnum" => 0,
            "prechapter"  => array("bid" => 0, "chpid" => 0, "isvip" => 0),
            "nextchapter" => array("bid" => 0, "chpid" => 0, "isvip" => 0),
        );
        $bid = I("get.bid", 0, "intval");
        $chpid = I("get.chpid", '', "trim");
        if (!$bid) {
            $this->ajaxReturn($output);
        }
        $prechapter = array(); //上一章
        $nextchapter = array(); //下一章
        $chapterinfo = array(); //当前章
        $lastchapter = array(); //最后章节
        $curidx = 0; //当前章节键名
        //获取所有章节
        $bookModel = new \Client\Model\BookModel();
        $chapterlist = $bookModel->getChplistByBid($bid);
        if ($chapterlist['list']) {
            if ($chpid == 'final') {
                $prechapter = array_pop($chapterlist['list']);
                $output['status'] = 1;
                $output['prechapter']['bid'] = $prechapter['bid'];
                $output['prechapter']['chpid'] = $prechapter['chapterid'];
                $output['prechapter']['isvip'] = $prechapter['isvip'];
                $output['nextchapter']['bid'] = $bid;
                $output['nextchapter']['chpid'] = 0;
                $output['nextchapter']['isvip'] = 0;
                $output['totalchapnum'] = count($chapterlist['list']);
                $output['chpnum'] = $chapterinfo['chporder'];
                $this->ajaxReturn($output);
            } else {
                foreach ($chapterlist['list'] as $key => $vo) {
                    if ($vo['chapterid'] == $chpid) {
                        $chapterinfo = $vo;
                        $curidx = $key;
                        break;
                    }
                }
                $lastchapter = $chapterlist['list'][count($chapterlist['list']) - 1];
            }
        } else {
            $this->ajaxReturn($output);
        }
        //获取上下章节
        //判断是否是第一章
        if ($curidx <= 0) {
            $prechapter['bid'] = $bid;
            $prechapter['chapterid'] = 0;
            $prechapter['isvip'] = 0;
        } else {
            $prechapter = $chapterlist['list'][$curidx - 1];
        }
        //判断是否是最后一张
        if ($chapterinfo['chapterid'] == $lastchapter['chapterid']) {
            $nextchapter['bid'] = $bid;
            $nextchapter['chapterid'] = 'final';
            $nextchapter['isvip'] = $chapterinfo['isvip']; //isvip跟随当前章节
        } else {
            $nextchapter = $chapterlist['list'][$curidx + 1];
        }
        $output['status'] = 1;
        $output['prechapter']['bid'] = $prechapter['bid'];
        $output['prechapter']['chpid'] = $prechapter['chapterid'];
        $output['prechapter']['isvip'] = $prechapter['isvip'];
        $output['nextchapter']['bid'] = $nextchapter['bid'];
        $output['nextchapter']['chpid'] = $nextchapter['chapterid'];
        $output['nextchapter']['isvip'] = $nextchapter['isvip'];
        $output['totalchapnum'] = count($chapterlist['list']);
        $output['chpnum'] = $chapterinfo['chporder'];
        $output['_info'] = $chapterinfo;
        $this->ajaxReturn($output);
    }

    /**
     * 元气萌获取道具列表
     */
    public function _getProList_yqm() {
        $output = array('prolist' => '');
        $prolist = C('PROPERTIES.all');
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $nickname = session('nickname') ? session('nickname') : session('username');
            foreach ($prolist as &$vo) {
                $vo['content'] = str_replace('#name#', $nickname, $vo['content']);
            }
        }
        $output['prolist'] = $prolist;
        $this->ajaxReturn($output);
    }
    /**
     * 元气萌首页推荐书籍接口
     */
    public function _getRecommendBooks(){
        $output = array('status'=>0,'message'=>'','booklist'=>array());
        $this->check_user_login();
        //精选书籍榜单名称暂未定
        $key = 'iosyqm_index_recommend';
        $recombooks = _process_bangdan($key);
        if(!$recombooks){
            $output['message'] = '暂无推荐书籍';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $comModel = new \Client\Model\NewcommentModel();
        $booklist = array();
        foreach($recombooks as $vo){
            $tmpbook = $bookModel->getBook(intval($vo['bid']));
            if($tmpbook && is_array($tmpbook)){
                $tmplist = array();
                $tmplist['SiteBookID'] = $tmpbook['bid'];
                $tmplist['Name'] = $tmpbook['catename'];
                $tmplist['CategoryName'] = $tmpbook['classname'];
                //处理简介
                $tmplist['Information'] = str_replace(array("<p>", '</p>', '<br/>', '<br>'), array('', "\r\n", '', ''), $tmpbook['intro']);
                $tmplist['Information'] = strip_tags($tmplist['Information']);
                $tmplist['Information'] = str_replace("\r\n", '\n', $tmplist['Information']);
                $pattern = '/\s+/'; //去除空白
                $tmplist['Information'] = preg_replace($pattern, '', $tmplist['Information']);
//                 $tmplist['Information'] = addslashes($tmplist['Information']);
                $tmplist['Author'] = $tmpbook['author'];
                $tmplist['CategoryID'] = $tmpbook['classid'];
                $tmplist['IsFinished'] = $tmpbook['lzinfo'];
                //总章节数
                $chapterlist = $bookModel->getChplistByBid($tmpbook['bid']);
                $tmplist['ChapterCount'] = count($chapterlist['list']);
                $tmplist['ImageUrl'] = getBookfacePath($tmpbook['bid'],'large');
                $tmplist['isFree'] = intval($tmpbook['isvip']) > 0 ? 0 : 1;
                //第一章章节id
                $tmplist['Chpid'] = $chapterlist['list'][0]['chapterid'];
                //评论数
                $tmplist['CommentNumber'] = 0;
                $cominfo = $comModel->getCommentByBid($tmpbook['bid']);
                if($cominfo && is_array($cominfo)){
                    $tmplist['CommentNumber'] = intval($cominfo['totalnum']);
                }
                $tmplist['IsCollection'] = false;
                $booklist[] = $tmplist;
            }
        }
        //检测收藏
        $bids = array_column($booklist, 'SiteBookID');
        $uid = isLogin();
        if($uid){
            $favModel = M('fav');
            $where = array(
                'bid'=>array('IN',implode(',',$bids)),
                'uid'=>$uid,
            );
            $favs = $favModel->field('bid')->where($where)->select();
            if($favs && is_array($favs)){
                $favbids = array_column($favs, 'bid');
                foreach ($booklist as &$book){
                    if(in_array($book['SiteBookID'], $favbids)){
                        $book['IsCollection'] = true;
                    }
                }
            }
            unset($favModel);
        }
        unset($bookModel);
        unset($comModel);
        
        $output['status'] = 1;
        $output['booklist'] = $booklist;
        $this->ajaxReturn($output);
        
    }
    /**
     * 获取书籍信息
     * @param int $bid 
     * 
     * @return array
     */
    public function _getBookDetail(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $bid = I('get.bid',0,'intval');
        if(!$bid){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        $bookinfo = $bookModel->getBook($bid);
        if(!$bookinfo || !is_array($bookinfo)){
            $output['message'] = '抱歉，该书籍未找到，请稍后再试';
            $this->ajaxReturn($output);
        }
        $func = CLIENT_NAME.'_convert_bookinfo';
        $formatInfo = $func($bookinfo);
        $chapterlist = $bookModel->getChplistByBid($bid);
        if(!$chapterlist || !$chapterlist['list']){
            $output['message'] = '对不起，暂无章节信息';
            $this->ajaxReturn($output);
        }
        //获取第一章
        $firstchapter = array_shift($chapterlist['list']);
        foreach($formatInfo as &$vo){
            $vo = urldecode($vo);
        }
        $output['status'] = 1;
        $output['data'] = array('data'=>$formatInfo,'Chpid'=>$firstchapter['chapterid']);
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读，书籍打赏记录
     * @param int $bid get
     */
    public function _rewardList(){
        $output = array('status'=>0,'message'=>'','url'=>'','list'=>array(),'numlist'=>array());
        $pagenum = I('get.pagenum',1,'intval');
        $pagesize = I('get.pagesize',10,'intval');
        $pageListSize = I('get.pagelistsize',5,'intval');
        $bid = I('get.bid',0,'intval');
        if(!$bid){
            $output['message'] = '参数错误';
            $this->ajaxReturn($output);
        }
        $bookModel = new \Client\Model\BookModel();
        //取每种道具的总数
        $totalnum = 0;
        $res = $bookModel->getRewardCountByBid($bid);
        if($res && is_array($res)){
            foreach($res as $val){
                $totalnum += $val['num'];
                //格式化（pid=>num）
                $output['numlist'][$val['pid']] = $val['num'];
            }
        }
        if(intval($totalnum) < 1){
            $output['message'] = '暂无记录';
            $this->ajaxReturn($output);
        }
        $map = array('bid'=>$bid);
        $pageModel = new \HS\Pager($totalnum, $pagesize);
        
        $lists = $bookModel->getRewardRecordByBid($bid, $pageModel->firstRow, $pageModel->listRows);
        if(!$lists){
            $out['message'] = '暂无数据';
            $this->ajaxReturn($output);
        }
        foreach($lists as $key => $val){
            $lists[$key]['addtime'] = date('m-d H:i', $val['addtime']);
            if(!$val['nickname']){
                $lists[$key]['nickname'] = $val['username'];
            }
        }
        //计算当前页显示分页的起始页码
        $pageliststart = (ceil($pagenum/$pageListSize) -1) * $pageListSize + 1;
        $output['pageliststart'] = $pageliststart;
        $output['status'] = 1;
        $output['list'] = $lists;
        $output['pagenum'] = $pagenum;
        $output['totalnum'] = $totalnum;
        $output['totalpage'] = $pageModel->totalPages;
        $this->ajaxReturn($output);
    }
    
}
/**
 * 目前是固定的，每周一和周四早上十点半换榜，所以这里就取的是这两个固定时间
 * 
 * @return timestamp
 */
function _getFreeEnd() {
    $endtime = 0;
    $wDay = date('N', NOW_TIME);          //礼拜几
    $hDay = date('H', NOW_TIME);
    $mDay = date('i', NOW_TIME);
    //周一10点以前或者周四10点以后
    if ($wDay == 1) {
        if ($hDay < 10 || ($hDay == 10 && $mDay < 30)) {
            $endtime = date("Y-m-d", NOW_TIME) . ' 10:30:00';
        } else {
            $endtime = date("Y-m-d", strtotime(4 - $wDay . " day")) . ' 10:30:00';
        }
    } else if ($wDay == 4) {
        if ($hDay < 10 || ($hDay == 10 && $mDay < 30)) {
            $endtime = date("Y-m-d", NOW_TIME) . ' 10:30:00';
        } else {
            $endtime = date("Y-m-d", strtotime(8 - $wDay . " day")) . ' 10:30:00';
        }
    } else if ($wDay < 4) {
        $endtime = date("Y-m-d", strtotime(4 - $wDay . " day")) . ' 10:30:00';
    } else {
        $endtime = date("Y-m-d", strtotime(8 - $wDay . " day")) . ' 10:30:00';
    }
    return strtotime($endtime);
}
