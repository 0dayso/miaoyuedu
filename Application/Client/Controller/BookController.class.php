<?php
/**
 * 模块: 客户端支持
 *
 * 功能: 小说
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: anluo
 * @version: $Id: BookController.class.php 1564 2017-03-14 06:33:37Z changliu $
 */

namespace Client\Controller;

use Client\Common\Controller;

class BookController extends Controller {
    public function _initialize() {
        parent::_initialize();
    }

    /**
     * 书籍封面页
     *
     * @param int $bid 书籍id get
     *
     * @return array
     */
    public function viewAction() {
        $bid = I('get.bid', 0, 'intval');
        $chpid = I('get.chpid',0,'intval');
        $isfav = I('get.isfav',0,'intval');
        if (!$bid) {
            _exit('参数错误!');
        }
        $tplVar = array();

        /* 书籍信息 */
        $bModel   = new \Client\Model\BookModel();
        $bookinfo = $bModel->getBook($bid);
        if (!$bookinfo || !is_array($bookinfo)) {
            _exit('参数错误');
        }
        $bookinfo['cover'] = getBookfacePath($bid);
        //男女样式标志
        $gender = 'boy';
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
            $gender = "girl";
        }

        //判断男女频
        $booksex      = $bookinfo['sex_flag'];
        if ($booksex == 'nv') {
            $sign = 3;
            $style = "nv";
        } elseif ($booksex == 'nan') {
            $sign = 2;
        } else {
            $sign = 1;
        }
        $sex_num = $sign;
        //样式标志
        $this->assign("style", $style);
        $this->assign("sex_num", $sex_num);
        $this->assign("propsList",C('PROPERTIES.'.$gender));
        //检查是否设置了阅读偏好，如果未设，则以书的性别做为初始阅读偏好
        if (!cookie('sex_flag')) {
            if (I('sex_flag') !== $style) {
                $_GET['sex_flag'] = $style;
                $this->assign('sex_flag', $style);
            }
        }
        //pagetitle
        $this->pageTitle = $bookinfo['catename']."-".$bookinfo['author']."-".$bookinfo['bid'];
        if(CLIENT_NAME == 'ios' || CLIENT_NAME == 'android'){
            $this->pageTitle = $bookinfo['catename'];
        }
        //次级导航头
        $this->assign("secondTitle",$bookinfo['catename']);
        //书籍价格,精品标志
        $fromsite        = C('CLIENT.' . CLIENT_NAME . '.fromsiteid');
        $yar_client      = new \HS\Yar("discountset");
        $result          = $yar_client->getDiscountCustomXianmianStatus($bid, 1, $fromsite);
        if ($result) {
            $bookinfo['pricebeishu'] = $result['pricebeishu'];
        }

        //最新更新章节
        if ($bookinfo['last_vipupdatechpid']) {
            $lastchpid = $bookinfo['last_vipupdatechpid'];
        } else {
            $lastchpid = $bookinfo['last_updatechpid'];
        }

        //所有章节
        $chapterlist              = $bModel->getChplistByBid($bid);
        $totalchapter = count($chapterlist['list']);
        $this->assign('totalchapter',$totalchapter);
        if (CLIENT_NAME === 'ios' || CLIENT_NAME === 'android') {
            //是否收藏
            $bookinfo['is_fav']       = false;
            if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0' && $isfav){
                $bookinfo['is_fav'] = true;
            }else{
                $this->check_user_login();
                $uid = isLogin();
                if ($uid) {
                    $favmap = array(
                        "uid" => $uid,
                        "bid" => $bid,
                    );
                    $is_fav = M("fav")->where($favmap)->count();
                    if ($is_fav) {
                        $bookinfo['is_fav'] = true;
                    } else {
                        $bookinfo['is_fav'] = false;
                    }
                }
            }
        }
        saveBookFavCookie($bookinfo['is_fav'], $bid);
        //开始/继续阅读

        /* 书籍评论 */
        $cModel           = new \Client\Model\NewcommentModel();
        $comment_per_page = 5;
        //查询条件
        $where            = array(
            'bid'            => $bid,
            'deleted_flag'   => array('neq', 1),
            'forbidden_flag' => array('neq', 1),
            'content'        => array('neq', ''),
        );
        $total_comment    = $cModel->where($where)->count();
        $corder = 'doublesort DESC,highlight_flag DESC,last_reply_date DESC';
        //元气萌按点亮数排序
        if(CLIENT_NAME == 'yqm'){
            $corder = 'zan_amount DESC';
        }
        $comments         = $cModel->where($where)->limit(0, $comment_per_page)->order($corder)->select();
        if ($comments) {
            $uids  = array_column($comments, 'uid');
            $map   = array(
                'uid' => array('IN', implode(',', $uids))
            );
            $users = M('ReadUser')->where($map)->getField('uid,username,nickname');
            foreach ($comments as $key => &$comment) {
                $userinfo = array();
                $comment['avatar'] = '';
                if (isset($users[$comment['uid']])) {
                    $userinfo = $users[$comment['uid']];
                    //用户头像
                    $comment['avatar'] = getUserFaceUrl($comment['uid']);
                }
                if ($userinfo['nickname']) {
                    $comment['nickname'] = $userinfo['nickname'];
                } else {
                    $comment['nickname'] = $userinfo['username'];
                }
            }
        }
        //手机版权
        if(in_array(CLIENT_NAME, array('html5', 'ios', 'android'))) {
            if ($bookinfo['sourceId'] == 101) {
                _exit('书籍不存在，可能已被删除或还没有上传');
            } else if ($bookinfo['copyright'] == 1) {
                _exit('书籍不存在，可能已被删除或还没有上传');
            }
        }
        //不开放
        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) {
            _exit('对不起，本书暂不开放阅读');
        }
        //最后更新的时间、章节
        $bookinfo['lastupdatetime'] = $bookinfo['last_vipupdatetime'] ? $bookinfo['last_vipupdatetime'] : $bookinfo['last_updatetime'];

        //书籍粉丝
        //粉丝总数
        $fensitotal = $bModel->getFansNumber($bid);

        if (CLIENT_NAME == 'android' || CLIENT_NAME == 'ios') {
            //拼接函数名
            $fun  = CLIENT_NAME . '_convert_bookinfo';
            $data = $fun($bookinfo, count($chapterlist['list']));

            switch (CLIENT_NAME) {
                case 'android':
                    $json              = array(
                        'Data'  => $data,
                        'Chpid' => $chapterlist['list'][0]['chapterid'],
                    );
                    $doClinetstr       = addslashes(json_encode($json));
                    break;
                case 'ios':
                    $ios_bookinfo_json = addslashes(urldecode(json_encode($data)));
                    $doClinetstr       = '{"Data":' . $ios_bookinfo_json . ',"Chpid":"' . $chapterlist['list'][0]['chapterid'] . '"}';
                    break;
            }
        }

        //分享标题
        $shareTitle = $bookinfo['catename'] . "-";
        //TODO:分享描述,分客户端
        $shareDesc  = "分享描述";
        //g站---最近的一条打赏记录
        //最近赞赏榜
        $proListTime  = $bModel->getBooKPro($bid, "addtime desc", 1);
        if($proListTime){
            $prolist = array_merge(C('PROPERTIES.boy'), C('PROPERTIES.girl'));
            foreach($prolist as $vo){
                if($vo['id'] == $proListTime[0]['pid']){
                    $proListTime[0]['proname'] = $vo['name'];
                    $proListTime[0]['unit'] = $vo['unit'];
                    break;
                }
            }
            if(!$proListTime[0]['nickname'] && intval($proListTime[0]['uid']) > 0){
                $umap = array("uid"=>intval($proListTime[0]['uid']));
                $uname = M("read_user")->field("username,nickname")->where($umap)->find();
                $proListTime[0]['nickname'] = $uname['nickname'] ? $uname['nickname'] : $uname['username'];
            }
        }
        //元气萌IOS阅读记录由客户端的chpid决定
        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
            $starttime = strtotime(date('y-m-d',NOW_TIME)." 00:00:00");
            $chapterinfo = array();
            //今日更新章节数
            $bookinfo['updatenum'] = 0;
            $bookinfo['isupdate'] = false;
            $updatenum = 0;
            foreach($chapterlist['list'] as $v){
               if($chpid >= 0 && $v['chapterid'] == $chpid){
                    $chapterinfo = $v;
                }
                if(intval($v['publishtime']) >= $starttime){
                    $updatenum ++;
                }
            }
            if($updatenum > 0){
                $bookinfo['isupdate'] = true;
                $bookinfo['updatenum'] = $updatenum;
            }
        }else{
            //获取阅读记录
            $bookinfo['lastreadchpid'] = 0;
            $bookinfo['isvip_last'] = 0;
            $cookiebook = getBookCookieFav($bid);
            if($cookiebook && $cookiebook['chapterid']){
                $bookinfo['lastreadchpid'] = $cookiebook['chapterid'];
                foreach($chapterlist['list'] as $vo){
                    if($vo['chapterid'] == $bookinfo['lastreadchpid']){
                        $bookinfo['isvip_last'] = $vo['isvip'];
                        break;
                    }
                }
            }
        }
        //元气萌ios阅读记录、元气值、收藏总数
        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
            //有阅读记录就取阅读记录，否则取第一章
            if($chapterinfo){
                $lastchapter = $chapterinfo;
            }else{
                $lastchapter = array_shift($chapterlist['list']);
            }
            $bookinfo['chapterid'] = $lastchapter['chapterid'];
            //更新doclient
            $doClinetstr = '{"Data":' . $ios_bookinfo_json . ',"Chpid":"' . $lastchapter['chapterid'] . '"}';
            $lastcontent = $bModel->getChapterContent($bid, $lastchapter['chapterid']);
            $contentRes = preg_match_all('@<p>([^<]+)</p>@iS', $lastcontent, $match);
            $chaptercontent = '';
            if ($contentRes) {
                $parts = min(20, I('parts', 20, 'intval'));  //最多取10段
                $chaptercontent = nl2p(implode("\n", array_slice($match[1], 0, $parts)), 2);
            }
            $lastcontent = array('lastchpid'=>$lastchapter['chapterid'],'lastchptitle'=>$lastchapter['title'],'content'=>$chaptercontent);
            //开始连载时间
            $bookinfo['posttime'] = date('Y-m-d',$bookinfo['posttime']);
            //元气值(道具*道具价值)
            $yuanqi = $bookinfo['total_hit'] * 100;
            $this->assign('yuanqi',$yuanqi);
            $this->assign('lastcontent',$lastcontent);
        }
        
        //喵阅读封面页需取最新更新的4个章节、作者的其他书籍、作者推荐的书、总的道具数
        if(CLIENT_NAME == 'myd'){
            //取作者头像
            $authorModel = M('author');
            $authoruid = $authorModel->where(array('authorid'=>$bookinfo['authorid']))->getField('uid');
            $bookinfo['avatar'] = getUserFaceUrl(intval($authoruid));
            //书籍最新更新的4个章节
            $last4chapters = array_slice($chapterlist['list'],-4);
            foreach($last4chapters as $k =>$v){
                $last4chapters[$k]['updatetime'] = date('Y-m-d',$v['publishtime']);
            }
            $bookinfo['newchap'] = $last4chapters;
            //作者其他书籍
            //如果$bookinfo['authorid']为0，则没有作者相关书籍
            $otherAuthorBooks = array();
            if ($bookinfo['authorid']){
                $otherAuthorBooks = $bModel->getBookByAuthorId($bookinfo['authorid'],$bookinfo['bid']);
            }
            //作者推荐书籍
            $authorRecommendBooks = array();
            if(isset($bookinfo['recbookidsarray']) && $bookinfo['recbookidsarray']){
                foreach($bookinfo['recbookidsarray'] as $vo){
                    $vo['cover'] = getBookfacePath($vo['bid']);
                    $authorRecommendBooks[] = $vo;
                }
            }
            //本书的总道具数
            $pronum = 0;
//            $bookTrend = $bModel->getBooktrend($bid);
//            if($bookTrend && is_array($bookTrend)){
//                foreach($bookTrend as $vo){
//                    if($vo['type'] == 7){
//                        $pronum += $vo['num'];
//                    }
//                }
//            }
            $bookinfo['pronum'] = $pronum;
            //版权信息
            $iplist = $bModel->getIpListByBid($bid);
            //同类热门 TODO 榜单名没确定先取男生点击榜
//            $classHotBooks = $bModel->getHitRank('nan', 'week');
            //强力推荐
            $qianglituijian = $bModel->getHitRank('nan', 'month');

            //版主信息
            $comment_set = $cModel->get_comment_set_cache($bid);
            $comment_set['banzhu'] = json_decode($comment_set['banzhu'],true);
            $comment_set['fubanzhu'] = json_decode($comment_set['fubanzhu'],true);
            
            $this->assign('banzhu',$comment_set['banzhu']);
            $this->assign('fubanzhu',$comment_set['fubanzhu']);
            //此处是喵阅读客户端模块  同类热门不需要了
//            $this->assign('classhotbooks',array_slice($classHotBooks,0,4));
            $this->assign('qianglituijian',$qianglituijian);
            $this->assign('iplist',$iplist);
            $this->assign('authorrecommendbooks',$authorRecommendBooks);
            $this->assign('otherauthorbooks',$otherAuthorBooks);
        }
        //点赞数与评论数
        $this->assign('total_flower',$bookinfo['total_flower']);
//        if (I('test') == 'flower'){
//            dump($bookinfo['total_flower']);
//        }
        $this->assign('pronum',$bookinfo['pronum']);
        //批量传值
        $tplVar     = array('bookinfo', 'comments', 'total_comment', 'chapterlist', 'fensitotal', 'doClinetstr', 'shareTitle', 'shareDesc','proListTime');
        foreach ($tplVar AS $key) {
            $this->assign($key, $$key);
        }
        $this->display();
    }

    /**
     * 阅读页面
     *
     * @param int $bid 书籍id get
     * @param int $uid 用户id session
     * @param string $chpid 章节id (数字是章节id,final跳阅读末页)
     *
     * @return array
     */
    public function readAction() {
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            _exit('没有指定要查看的书号！');
        }
        $bookobj  = new \Client\Model\BookModel();
        $bookinfo = $bookobj->getBook($bid);
        if (!$bookinfo || !is_array($bookinfo)) {
            _exit('指定的书号不存在！');
        }
        //不开放
        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) {
            _exit('对不起，本书暂不开放阅读');
        }
        $gender = "boy";
        //男女样式标志
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
            $gender = "girl";
        }
        //判断男女频
        $booksex      = $bookinfo['sex_flag'];
        if ($booksex == 'nv') {
            $sign = 3;
            $style = "nv";
        } elseif ($booksex == 'nan') {
            $sign = 2;
        } else {
            $sign = 1;
        }
        $sex_num = $sign;
        //样式标志
        $this->assign("style", $style);
        //样式标志
        $this->assign("sex_num", $sex_num);
        $this->assign("propsList",C("PROPERTIES.".$gender));
        if (!cookie('sex_flag')) {
            if (I('sex_flag') !== $style) {
                $_GET['sex_flag'] = $style;
                $this->assign('sex_flag', $style);
            }
        }
        //上下章节
        $bookinfo['prevchapter'] = array();
        $bookinfo['prevchapter']['bid'] = $bid;
        $bookinfo['prevchapter']['chpid'] = 0;
        $bookinfo['prevchapter']['isvip'] = 0;
        $bookinfo['nextchapter'] = array();
        $bookinfo['nextchapter']['bid'] = $bid;
        $bookinfo['nextchapter']['chpid'] = 0;
        $bookinfo['nextchapter']['isvip'] = 0;
        $chpid = I('chpid', '', 'trim');
        if ($chpid === "final") {
            $this->pageTitle = $bookinfo['catename'];
            //上一章章节id
            $lastchapter     = $bookobj->getLastChapter($bid);
            if ($lastchapter) {
                $bookinfo['bid'] = $bid;
                $bookinfo['prevchapter']['chpid'] = $lastchapter['chapterid'];
                $bookinfo['prevchapter']['isvip'] = $lastchapter['isvip'];
            }
            //评论总数
            $comwhere   = array(
                'bid'          => $bid,
                'deleted_flag' => array('neq', 1),
                'content'      => array('neq', ''),
            );
            $commentNum = M('newcomment')->where($comwhere)->count();
            //粉丝数量
            $bookinfo['fansnum'] = $bookinfo['salenum'] + $bookinfo['total_pro'];
            //下架状态
            $isxiajia = false;
            if($bookinfo['publishstatus'] == 9){
                $this->check_user_login();
                $uid = isLogin();
                if(!$uid || ($uid && session('viplevel') < 1)){
                    $isxiajia = true;
                }
            }
            $this->assign('isxiajia',$isxiajia);
            $this->assign("bookinfo", $bookinfo);
            $this->assign("commentnum", $commentNum);
            $this->display("readlate");
        } else {
            $chpid        = intval($chpid);
            $fromSite     = C("CLIENT." . CLIENT_NAME . ".fromsiteid");

            /* 查询书籍信息 */
            $tmparys = $bookobj->getChapter($bid);
            if (!$tmparys) {
                _exit('对不起，没有找到与本书相关的章节内容，可能本书还在审核当中！');
            }
            /* 取得$bookinfo和$tmparys */

            //潇湘的书（sourceId=101）不能显示在手机版权
            if ($bookinfo['sourceId'] == 101) {
                if (CLIENT_NAME == 'html5') {
                    _exit();
                }
            }
            if ($bookinfo['copyright'] == 1) {
                if (CLIENT_NAME == 'html5') {
                    _exit();
                }
            }

            //处理卷
            $chapterinfo = false;
            foreach ($tmparys as $juanorder => $juan) {
                $juantitle = $juan['title'];
                foreach ($juan['chparys'] as $chapterindex => $chapter) {
                    $chapter['juantitle']       = $juantitle;
                    $chapterlist[$chapterindex] = $chapter;
                    if ($chpid && $chpid == $chapter['chapterid']) {
                        $chapterinfo                 = $chapter;
                        $chapterinfo['chapterindex'] = $chapterindex;
                    }
                }
            }

            //默认阅读第一章
            if (!$chpid) {
                $chapterinfo                 = $chapterlist[1];
                $chapterindex                = 1;
                $chapterinfo['chapterindex'] = $chapterindex;
                $chpid                       = $chapterinfo['chapterid'];
            }
            if (!$chapterinfo) {
                _exit('对不起，你指定的章节不存在！');
            }
            if ($chapterinfo['ispublisher'] != C('CHPT_CANDISPLAY')) {
                    _exit('对不起，当前章节正在审核中！');
            }

            //拼接函数名
            $fun = CLIENT_NAME . '_convert_bookinfo';
            if (function_exists($fun)) {
                $data = $fun($bookinfo, count($chapterlist['list']));
                $json = '';
                switch (CLIENT_NAME) {
                    case 'android':
                        $json = array(
                            'Data'  => $data,
                            'Chpid' => $chpid,
                        );
                        break;
                    case 'ios':
                        if (CLIENT_VERSION>='2.0') {
                            $data['chpid'] = $chpid;
                            $json = $data;
                        } else {
                        //$ios_bookinfo_json = addslashes(urldecode(json_encode($data)));
                        $json = array('Data' => $data, 'Chpid' => $chpid);
                        }

                        break;
                }
                if ($json) {
                    doClient('onlineRead', $json);
                }
            }
            unset($tmparys);
            /* 是否是vip章节 */
            if ($chapterinfo['isvip']) {
                redirect(url('Book/readVip', array('bid' => $bid, 'chpid' => $chpid), 'do'));
            }
//             unset($chapterlist);
            //获取章节内容getChapterContent($bid, $chpid)
            if (!$chapterinfo['content']) {
                $chapterinfo['content'] = $bookobj->getChapterContent($bid, $chpid);
            }

            //章节更新时间
            if ((int) $chapterinfo['last_update_date']) {
                $chapterinfo['updatetime'] = date("Y-m-d H:s", $chapterinfo['last_update_date']);
            } else {
                $chapterinfo['updatetime'] = date("Y-m-d H:s", $chapterinfo['publishtime']);
            }

            $chapterinfo['status']  = 1;
            //评论总数
            $comwhere               = array(
                'bid'          => $bid,
                'deleted_flag' => array('neq', 1),
                'content'      => array('neq', ''),
            );
            $commentNum             = M('newcomment')->where($comwhere)->count();
            $bookinfo['commentnum'] = $commentNum;
            /*获取上下章节*/
            $goodchapterlist = array();
            foreach ($chapterlist as $key => $chp) {
                if (strtoupper($chp['candisplay']) == 'Y') {
                    $goodchapterlist[$key] = $chp;
                }
            }

            $tmpary = $this->get_chpt_prevnext($goodchapterlist, $chapterinfo['chapterindex']);

            if ($tmpary['prev_chpt']) {
                $bookinfo['prevchapter']['bid'] = $bid;
                $bookinfo['prevchapter']['chpid'] = $tmpary['prev_chpt']['chapterid'];
                $bookinfo['prevchapter']['isvip'] = $tmpary['prev_chpt']['isvip'];
            }
            if ($tmpary['next_chpt']) {
                $bookinfo['nextchapter']['bid'] = $bid;
                $bookinfo['nextchapter']['chpid'] = $tmpary['next_chpt']['chapterid'];
                $bookinfo['nextchapter']['isvip'] = $tmpary['next_chpt']['isvip'];
            }else{
                //是否是最后一章
                $lastchapter = $bookobj->getLastChapter($bid);
                if($lastchapter['chapterid'] == $chpid){
                    $bookinfo['nextchapter']['bid'] = $bid;
                    $bookinfo['nextchapter']['chpid'] = 'final';
                    $bookinfo['nextchapter']['isvip'] = $chapterinfo['isvip'];
                }
            }

            //title
            $this->pageTitle = $bookinfo['catename']."-".$chapterinfo['title']."-".$bookinfo['bid'];
            //次级导航title
            $this->assign("secondTitle",$chapterinfo['title']."-".$bookinfo['catename']);

            if (CLIENT_NAME == 'yqm') {
                $this->pageTitle = $chapterinfo['title'];
            }
            $shareTitle = $chapterinfo['title'] . "-" . $bookinfo['catename'] . "-";
            $this->assign("shareTitle", $shareTitle);
            $this->assign("pagename", $bookinfo['catename']);
            //$this->assign("blackwhite", $blackwhite);
            $this->assign('chapterinfo', $chapterinfo);
            $bookinfo['intro'] = cutstr($chapterinfo['content'], 150);
            $this->assign('bookinfo', $bookinfo);
            $this->assign('commentnum',$commentNum);
            $this->display();
        }
    }

    /**
     * VIP章节阅读页面
     *
     * @param int $bid 书籍id get
     * @param int $uid 用户id session
     * @param string $chpid 章节id (数字是章节id,final跳阅读末页)
     *
     * @return array
     */
    public function readVipAction() {
        $bid   = I('get.bid', 0, 'intval');
        $chpid = I('get.chpid', 0, 'trim');
        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            if(strtolower(CLIENT_NAME) === 'ios' && CLIENT_VERSION >= '1.4.3'){
                doClient('User/login');
            }else{
                redirect(url('User/Login', array('fu' => url('Book/readVip', array('bid' => $bid, 'chpid' => $chpid),'do')), 'do'));
            }
        }
        if (!$bid) {
            _exit('参数错误！');
        }
        $bookobj  = new \Client\Model\BookModel();
        $bookinfo = $bookobj->getBook($bid);
        if (!$bookinfo) {
            _exit('参数错误！');
        }
        //不开放
        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) {
            _exit('对不起，本书暂不开放阅读');
        }
        //下架
        $isxiajia = false;
        if($bookinfo['publishstatus'] == 9){
            if(!$uid || ($uid && intval(session('viplevel')) < 1)){
                $isxiajia = true;
            }
        }
        $this->assign('isxiajia',$isxiajia);
        //男女样式标志
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
        }
        //样式标志
        $this->assign("style", $style);
        if (!cookie('sex_flag')) {
            if (I('sex_flag') !== $style) {
                $_GET['sex_flag'] = $style;
                $this->assign('sex_flag', $style);
            }
        }
		$isfree = false;	//是否限免！
        //上下章节
        $bookinfo['prevchapter'] = array();
        $bookinfo['prevchapter']['bid'] = $bid;
        $bookinfo['prevchapter']['chpid'] = 0;
        $bookinfo['prevchapter']['isvip'] = 0;
        $bookinfo['nextchapter'] = array();
        $bookinfo['nextchapter']['bid'] = $bid;
        $bookinfo['nextchapter']['chpid'] = 0;
        $bookinfo['nextchapter']['isvip'] = 0;
        if ($chpid === "final") {
            $this->pageTitle = $bookinfo['catename'];
            //上一章章节id
            $lastchapter = $bookobj->getLastChapter($bid);
            if ($lastchapter) {
                $bookinfo['prevchapter']['bid'] = $bid;
                $bookinfo['prevchapter']['chpid'] = $lastchapter['chapterid'];
                $bookinfo['prevchapter']['isvip'] = $lastchapter['isvip'];
            }
            //判断是否收藏getFavCount方法
            if ($uid) {
                $faved_info = $bookobj->getFavCount($uid, $bid);
                if ($faved_info) {
                    $bookinfo['is_faved'] = true;
                } else {
                    $bookinfo['is_faved'] = false;
                }
            } else {
                $bookinfo['is_faved'] = false;
            }
            saveBookFavCookie($bookinfo['is_faved'], $bid);
            //评论总数
            $comwhere   = array(
                'bid'          => $bid,
                'deleted_flag' => array('neq', 1),
                'content'      => array('neq', ''),
            );
            $commentNum = M('newcomment')->where($comwhere)->count();
            //粉丝数量
            $bookinfo['fansnum'] = $bookinfo['salenum'] + $bookinfo['total_pro'];

            $this->assign("bookinfo", $bookinfo);
            $this->assign("commentnum", $commentNum);

            $this->display("readlate");
        } else {
            $chpid        = intval($chpid);
            //fromsiteid区分客户端
            $fromSite     = C("CLIENT." . CLIENT_NAME . ".fromsiteid");

            //潇湘的书（sourceId=101）不能显示在手机版权
            if ($bookinfo['sourceId'] == 101) {
                _exit('书籍不存在，可能已被删除或还没有上传');
            } else if ($bookinfo['copyright'] == 1) {
                _exit('书籍不存在，可能已被删除或还没有上传');
            }

            $tmparys = $bookobj->getChapter($bid);
            /* 取得$bookinfo和$tmparys */
            if (!$bookinfo || !is_array($bookinfo)) {
                _exit('参数错误！');
            }
            //判断是否收藏getFavCount方法
            $fModel     = D('Fav');
            //查询条件
            $map        = array(
                'bid' => $bid,
                'uid' => $uid,
            );
            $faved_info = $fModel->where($map)->find();
            if ($faved_info) {
                if ($chpid) {
                    $chptmp = $bookobj->getChapterByCid($bid, $chpid);
                    //存书签
                    $favmap = array('fid'=>$faved_info['fid']);
                    $title                  = $chptmp['title'];
                    $bookmark               = $title . "\t" . $chpid . "\t" . time();
                    $faved_info['bookmark'] = $bookmark;
                    $fModel->where($favmap)->save($faved_info);
                    $bookinfo['is_faved']   = true;
                }
            } else {
                $bookinfo['is_faved'] = false;
            }
            saveBookFavCookie($bookinfo['is_faved'], $bid);

            //处理卷
            $chapterinfo = false;
            foreach ($tmparys as $juanorder => $juan) {
                $juantitle = $juan['title'];
                foreach ($juan['chparys'] as $chapterindex => $chapter) {
                    $chapter['juantitle']       = $juantitle;
                    $chapterlist[$chapterindex] = $chapter;
                    if ($chpid && $chpid == $chapter['chapterid']) {
                        $chapterinfo                 = $chapter;
                        $chapterinfo['chapterindex'] = $chapterindex;
                    }
                }
            }

            //默认阅读第一章
            if (!$chpid) {
                $chapterinfo                 = $chapterlist[1];
                $chapterindex                = 1;
                $chapterinfo['chapterindex'] = $chapterindex;
                $chpid                       = $chapterinfo['chapterid'];
            }
            if (!$chapterinfo) {
                _exit('指定的章节不存在！');
            }
            if ($chapterinfo['ispublisher'] != C('CHPT_CANDISPLAY')) {
                //待审章节
                if (CLIENT_NAME == 'yqm') {
                    $chapterinfo['content'] = "章节正在审核，请稍后再试";
                } else {
                    _exit('对不起，当前章节正在审核中！');
                }
            }
            unset($tmparys);
            //客户端不会有阅读页面，如果因为某种原因导致进入此页面，应该直接输出在线阅读的命令！
            if(strtolower(CLIENT_NAME) === 'ios' && CLIENT_VERSION >= '2.0.0'){
                doClient('Book/onlineRead',array('bid'=>$bid,'chpid'=>$chpid));
            }
            if (CLIENT_NAME === 'ios' || CLIENT_NAME === 'android') {
                $fun    = CLIENT_NAME . '_convert_bookinfo';
                $data   = $fun($bookinfo);
                $result = array('Data' => $data, 'Chpid' => $chpid);
                doClient('onlineRead', $result);
            }

            /* get_chpt_prenext方法已在控制器定义，取得章节的上一章和下一章 */
            $goodchapterlist = array();
            foreach ($chapterlist as $key => $chp) {
                if (strtoupper($chp['candisplay']) == 'Y') {
                    $goodchapterlist[$key] = $chp;
                }
            }
            $tmpary = $this->get_chpt_prevnext($chapterlist, $chapterinfo['chapterindex']);
            if ($tmpary['prev_chpt']) {
                $bookinfo['prevchapter']['bid'] = $bid;
                $bookinfo['prevchapter']['chpid'] = $tmpary['prev_chpt']['chapterid'];
                $bookinfo['prevchapter']['isvip'] = $tmpary['prev_chpt']['isvip'];
            }
            if ($tmpary['next_chpt']) {
                $bookinfo['nextchapter']['bid'] = $bid;
                $bookinfo['nextchapter']['chpid'] = $tmpary['next_chpt']['chapterid'];
                $bookinfo['nextchapter']['isvip'] = $tmpary['next_chpt']['isvip'];
            } else {
                //判断是否是最后一张
                $lastchapter = $bookobj->getLastChapter($bid);
                if($chapterinfo['chapterid'] == $lastchapter['chapterid']){
                    $bookinfo['nextchapter']['bid'] = $bid;
                    $bookinfo['nextchapter']['chpid'] = 'final';
                    $bookinfo['nextchapter']['isvip'] = $chapterinfo['isvip'];
                }
            }
            unset($chapterlist);
            /* 是否是vip章节 */
            if ($chapterinfo['isvip']) {
                if($isxiajia){
                    $this->redirect(url('Book/xiajia', array(), 'do'));
                }
                $is_author = false;
                $buylog    = false;
                //判断是否是作者
                if ($bookinfo['authorid'] && session('authorid') && session('authorid') == $bookinfo['authorid']) {
                    $is_author = true;
                }
                //检测是否是屏蔽用户
                $denyobj    = M('vipchapter_user_shield');
                $pbmap = array("pb_uid"=>$uid);
                $denieduser = $denyobj->where($pbmap)->find();
                if (is_array($denieduser) && $denieduser) {
                    _exit('对不起，您暂时没有权限阅读该章节');
                }


                $buylog = false;

                $user = M("user")->find($uid);

                $xianmian     = $xianmian_num = false;

                //从接口中读取限免对应的设置
                $client = new \HS\Yar("discountset");
                $result = $client->getDiscountCustomXianmianStatus($bid, $user['viplevel'], $fromSite);
                if ($result) {
                    $discount_set     = $result['discount_set'];
                    $is_discount      = $discount_set['is_open'];
                    $is_bookdiscount  = $discount_set['is_bookdiscount'];
                    $custom_price_set = $result['custom_price_set'];
                    $xianmian         = $result['xianmian_set'];
                    $book_vip_price   = $result['pricebeishu'];
                    $xianmian_num     = $xianmian['num'];
                }
                unset($result);
                unset($client);

                if ($xianmian) {
                    $isfree = true;
                    if ($xianmian['freetype'] == 2) {
                        $freechpts = explode('|', $xianmian['free_chapterid']);
                        if (!in_array($chpid, $freechpts)) {
                            $isfree = false;
                        }
                    }
                }

                //获得用户对本章的订购关系
                unset($result);
                $client = new \HS\Yar("dingoujson");
                $result = $client->checkUserCh($bid, $chpid, $uid);
                if ($result === false) {
                   _exit('网络错误，请刷新');
                }
                if ($result == 'Y') {
                    $buylog["chapterid"] = $chpid;
                }

                //如果没有购买，而且不是作者
                if (!is_array($buylog) && !$is_author) {
                    //检查是否设置自动订阅,设置则订购章节orderChapter($bid, $chapterid, $user, $is_buyall, $autoorder = -1)
                    unset($result);
                    $yar_client = new \HS\Yar("autodingyuestatus", false);
                    $result     = $yar_client->checkAutoStatus($bid, $uid);
                    if ($result['dtype'] == 3 || $result['dtype'] == 9) {
                        //限免的书不用购买
                        if (!$isfree) {
                                //不是限免的书，但是订阅状态是9，则需要去购买
                            if ($result['dtype'] == 9) {
                                $this->redirect(url('Book/buyVipList', array('bid' => $bid, 'chpid' => $chpid), 'do'));
                            } else {
                                //自动购买
                                $orderres = $bookobj->orderChapterByCache($bid, $chpid, $user, false, $result['dtype'], $fromSite);
                                if ($orderres != "orderchpsuc") {
                                    $this->redirect(url('Book/buyVipList', array('bid' => $bid, 'chpid' => $chpid), 'do'));
                                }
                             }
                        }
                    } else {
                        $this->redirect(url('Book/buyVipList', array('bid' => $bid, 'chpid' => $chpid), 'do'));
                    }
                }
            }
            //获取章节内容getChapterContent($bid, $chpid)
            if (!$chapterinfo['content']) {
                $chapterinfo['content'] = $bookobj->getChapterContent($bid, $chpid);
            }
            //章节更新时间
            if ((int) $chapterinfo['last_update_date']) {
                $chapterinfo['updatetime'] = date("Y-m-d H:s", $chapterinfo['last_update_date']);
            } else {
                $chapterinfo['updatetime'] = date("Y-m-d H:s", $chapterinfo['publishtime']);
            }

            $chapterinfo['status']  = 1;
            //评论总数
            $comwhere               = array(
                'bid'          => $bid,
                'deleted_flag' => array('neq', 1),
                'content'      => array('neq', ''),
            );
            $commentNum             = M('newcomment')->where($comwhere)->count();
            $bookinfo['commentnum'] = $commentNum;

            $this->pageTitle = $bookinfo['catename'] ." ".$chapterinfo['title']." ".$bookinfo['bid'];
            $this->assign("secondTitle",$chapterinfo['title']."-".$bookinfo['catename']);

            if (CLIENT_NAME == 'yqm') {
                $this->pageTitle = $chapterinfo['title'];
            }
            $shareTitle = $chapterinfo['title'] . "-" . $bookinfo['catename'] . "-";
            $this->assign("shareTitle", $shareTitle);
            $this->assign("pagename", $bookinfo['catename']);
            //$this->assign("blackwhite", $blackwhite);
            $this->assign('chapterinfo', $chapterinfo);
            $this->assign('bookinfo', $bookinfo);
            $this->assign('commentnum',$commentNum);
			$this->assign('isfree', $isfree);		//有可能非限免的章节但是计算出来的价格去是0，所以，不能以销售价格为0来判断是否限免
            $this->display();
        }
    }

    /**
     * 获得一个章节的前后章节
     *
     * @param unknown_type $chapterary 以chporder排序的,自然顺序章节数组(不包含卷)=array(0=>xxx,1=>xxx,2=>xxx);
     * @param $cur_chpidx 当前章在上述数组中的索引位置
     *
     * @return array('prev_chpt'=>array(chatper),'next_chpt'=>array(chapter));
     */
    protected function get_chpt_prevnext(&$chapterary, $cur_chpidx) {
        $total = count($chapterary);
        if (($cur_chpidx - 1) >= 0) {
            $preid = $cur_chpidx - 1;
        } else {
            $preid = false;
        }
        if (($cur_chpidx + 1) <= $total) {
            $nexid = $cur_chpidx + 1;
        } else {
            $nexid = false;
        }
        if (isset($chapterary[$preid])) {
            $ret['prev_chpt'] = $chapterary[$preid];
        } else {
            $ret['prev_chpt'] = '';
        }
        if ($nexid && isset($chapterary[$nexid])) {
            $ret['next_chpt'] = $chapterary[$nexid];
        } else {
            $ret['next_chpt'] = '';
        }
        return $ret;
    }

    /**
     * 章节订阅
     * @param int $bid 书号
     * @param int $chpid 章节id
     * @param int $pl_num 批量订阅章节数量
     */
    public function buyVipListAction() {
        $client_error = array(
            'status'  => 0,
            'code'    => 0,
            'msg'     => '',
            'message' => '',
            'url'     => ''
        );
        //get接收bid和chpid、订阅的数量pl_num默认1(即本章)
        $bid          = I('get.bid', 0, 'intval');
        //$bid = 60494;
        if (!$bid) {
            _exit('参数错误');
        }
        $chpid = I('get.chpid', 0, 'intval');
        if (!$chpid) {
            _exit('参数错误！');
        }

        $this->check_user_login();
        $uid = isLogin();
        if (!$uid) {
            if(CLIENT_VERSION >= '1.4.3' && strtolower(CLEINT_NAME) === 'ios'){
                //元气萌IOS要发送命islogin
                doClient('User/login');
            }else{
                $this->redirect(url("User/login",array("fu"=>url('Book/buyVipList',array('bid'=>$bid,'chpid'=>$chpid),'do')),'do'),2,"请先登录");
            }
        } else {
            $userinfo = M('user')->find($uid);
            if (!C('USERVIP')[$userinfo['viplevel']]['price']) {
                $pricebeishu = C('USERVIP')[1]['price'];
            } else {
                $pricebeishu = C('USERVIP')[$userinfo['viplevel']]['price'];
            }
            $this->assign('userinfo', $userinfo);
        }

        //fromsiteid区分客户端
        $fromSite = C("CLIENT." . CLIENT_NAME . ".fromsiteid");
        $this->assign('bid', $bid);
        $this->assign('chpid', $chpid);
        //判断当前章节是否已经购买
        $client   = new \HS\Yar("dingoujson");
        $result   = $client->checkUserCh($bid, $chpid, $uid);
        if (false === $result) {
            _exit('网络错误，代码006，请重试');
        }
        //已订阅则跳转到阅读页面
        if ($result == "Y") {
            $this->redirect(url('Book/readvip', array('bid' => $bid, 'chpid' => $chpid), 'do'));
        }

        $pl_num = I('get.pl_num', 1, 'intval');
        if ($pl_num < 1) {
            $pl_num = 1;
        }
        $this->assign('plnum', $pl_num);
        //$orderinfo['order_count'] = $pl_num;
        $bookModel = new \Client\Model\BookModel();
        $bookinfo  = $bookModel->find($bid);
        if(!$bookinfo){
            _exit("书籍不存在，可能已被删除或还没上传，请稍后再试");
        }
        //不开放
        if (($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) || ($bookinfo['publishstatus'] == 9 && intval($userinfo['viplevel']) < 1)) {
            _exit('对不起，本书暂不开放阅读');
        }

        //判断书籍状态
        if (!is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['copyright'] == 1) {
            _exit('书籍不存在，可能已被删除或还没有上传');
        }
        $this->pageTitle = '订购:' . $bookinfo['catename'];
        //男女样式标志
        $gender = 'boy';
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
            $gender = 'girl';
        }
        //判断男女频
        $booksex      = $bookinfo['sex_flag'];
        if ($booksex == 'nv') {
            $sign = 3;
            $style = "nv";
        } elseif ($booksex == 'nan') {
            $sign = 2;
        } else {
            $sign = 1;
        }
        $sex_num = $sign;
        //样式标志
        $this->assign("sex_num", $sex_num);

        //样式标志
        $this->assign("style", $style);
        $this->assign('propsList', C('PROPERTIES.'.$gender));
//        print_r(C('PROPERTIES.'.$gender));
//        $this->assign('daojulist', $properties[$gender]);

        //书籍分类名
        $bookinfo['classname']         = C("CATEGORY." . $bookinfo['classid'] . ".title");
        $bookinfo['smallsubclassname'] = C('CATEGORY')[$bookinfo['classid']]['subclass'][$bookinfo['classid2']]['title'];
        //是否收藏
        $favmap                        = array(
            "bid" => $bid,
            "uid" => $uid,
        );
        $isfav                         = M('fav')->where($favmap)->count();
        if ($isfav) {
            $bookinfo['is_faved'] = true;
        } else {
            $bookinfo['is_faved'] = false;
        }
        saveBookFavCookie($bookinfo['is_faved'], $bid);
        //计算章节所在的表名
        $chapterinfo = $bookModel->getChapterByCid($bid, $chpid);
        if (!$chapterinfo) {
            _exit('章节不存在');
        }

        $allchapter = $bookModel->getChplistByBid($bid)['list'];
        if (!$allchapter) {
            _exit('网络错误，代码009，请返回重试');
        }
        // 根据fromStie获得本书的打折,限免,自定义价格,定价基数设置
        $discount_set     = false;
        $custom_price_set = false;
        $is_discount      = false;
        $is_bookdiscount  = false;
        unset($result);
        $yar_client       = new \HS\Yar("discountset");
        if ($userinfo['viplevel'] == 0) {
            $viplevel = 1;
        } else {
            $viplevel = $userinfo['viplevel'];
        }
        $result = $yar_client->getDiscountCustomXianmianStatus($bid, $viplevel, $fromSite);
        if ($result) {
            $discount_set     = $result['discount_set'];
            $is_discount      = $discount_set['is_open'];
            $is_bookdiscount  = $discount_set['is_bookdiscount'];
            $custom_price_set = $result['custom_price_set'];
            $xianmian_set     = $result['xianmian_set'];
            $pricebeishu      = $result['pricebeishu'];
        } else {
            _exit('网络错误，代码003，请重试');
        }
        //yqm暂时按签字三
        if(CLIENT_NAME == 'yqm' || (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0')){
            $pricebeishu = 333;
        }
        //限免书不允许批量购买
        $isfree = false;
        if ($xianmian_set) {
            $isfree = true;
            if ($xianmian_set['freetype'] == 2) {
                $freechpts = explode('|', $xianmian_set['free_chapterid']);
                if (!in_array($chpid, $freechpts)) {
                    $isfree = false;
                }
            }
        }

        if ($isfree) {
            $pl_num = 1;
        }

        $nobuy_vipchparys = array();
        $need_pay         = false;         //是否需要充值！
        $egold_pay        = 0;         //使用的银币个数
        if ($pl_num >= 10) {
            //获得用户已订购章节ids
            $aleady_buyids = '';
            unset($result);
            unset($client);
            $client        = new \HS\Yar("dingoujson");
            $result        = $client->checkUserAll($bid, $uid, 'ids');
            if (false === $result) {
                _exit('网络错误，代码006，请返回重试');
            }
            if ($result != 'N') {
                $aleady_buyids = $result;
            }
            unset($result);
            unset($yar_client);

            $already_buy      = explode(',', $aleady_buyids);
            $nobuy_vipchparys = array();
            $i                = 0;
            $notVip           = 0;
            $Paied            = 0;
            foreach ($allchapter as $v) {
                if ($v['chporder'] >= $chapterinfo['chporder']) {
                    if ($v['isvip'] && !in_array($v['chapterid'], $already_buy)) {
                        $nobuy_vipchparys[] = $v;
                    }
                    if (!$v['isvip']) {
                        $notVip++;
                    }
                    if (in_array($v['chapterid'], $already_buy)) {
                        $Paied++;
                    }
                    $i++;
                    if ($i >= $pl_num && $pl_num != 100) {
                        break;
                    }
                }
            }
            $is_buyall = false;             //是否整本购买
            $client    = new \HS\Yar("dingyuechapter", true);
            $result    = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, $is_buyall, $custom_price_set, $xianmian_set);
            if (false === $result) {
                _exit('网络错误，代码008，请重试');
            }
            $need_money      = $result['need_totalmoney'];
            $pl_star_chapter = $nobuy_vipchparys[0];
            $pl_end_chapter  = $nobuy_vipchparys[count($nobuy_vipchparys) - 1];


            $need_gold = $need_money;
            $tmp       = $nobuy_vipchparys;
            $first_chp = array_shift($tmp);
            $last_chp  = array_pop($tmp);
//            if($pl_num>1){
//                if($pl_num>=100){
//                    $msg = '订阅本章后所有已发布章节(《' . $last_chp['title'] . '》)，';
//                } else {
//                    $msg = '订单本章后'.$pl_num.'章(《' . $last_chp['title'] . '》)，';
//                }
//                $append = array();
//                if($notVip) {
//                    $append[] = '非VIP章节'.$notVip.'章';
//                }
//                if($Paied) {
//                    $append[] = '已经购买'.$Paied.'章';
//                }
//                if($append) {
//                    $append = '共需要购买'.count($nobuy_vipchparys).'章，其中：'.implode(',', $append).'。<br />';
//                } else {
//                    $append = '共需要购买'.count($nobuy_vipchparys).'章。<br />';
//                }
//                $msg .= $append;
//            } else
            if (count($nobuy_vipchparys) > 1) {
                $msg = '订阅本章~《' . $last_chp['title'] . '》共需要购买' . count($nobuy_vipchparys) . '章。<br />';
            } else {
                $msg = '订阅本章，';
            }
//            unset($uclient);
            if ($is_discount) {
                $discount  = $discount_set['discount'];
                $old_money = ceil($need_money * 10 / $discount);
                $msg       = '原价<del>' . $old_money . C('SITECONFIG.MONEY_NAME') . '</del>，限时特价仅需';
            } else {
                $msg .= '共需要：';
            }
            $msg .= '<strong style="color:red;">'.$need_money.'</strong>'.C('SITECONFIG.MONEY_NAME').'。';
//
//            $uclient         = new \HS\Yar("usermoney");
//            $need_money_info = $uclient->simulate_buychapters_egold($uid, $result['nobuy_vipchparys']);
//
//            if ($need_money_info) {
//                if ($is_discount) {
//                    $msg .= '<strong style="color:red;">' . $need_money_info . '</strong>' . C('SITECONFIG.EMONEY_NAME');
//                } else {
//                    $msg .= $need_money_info . C('SITECONFIG.EMONEY_NAME');
//                }
//                $need_gold-=$need_money_info;
//                if ($need_gold) {
//                    $msg .= '，';
//                } else {
//                    $msg .= '。';
//                }
//            }
//            if ($need_gold) {
//                if ($is_discount) {
//                    $msg .= '<strong style="color:red;">' . $need_gold . '</strong>' . C('SITECONFIG.MONEY_NAME') . '。';
//                } else {
//                    $msg .= $need_gold . C('SITECONFIG.MONEY_NAME') . '。';
//                }
//            }
            $this->assign('buy_detail_msg', $msg);

            $uclient         = new \HS\Yar("usermoney");
            $need_money_info = $uclient->simulate_buychapters_egoldFirst2($uid, $result['nobuy_vipchparys']);

            if ($need_money_info['egold'] === -1 || $need_money_info['money'] === -1) {
                $need_pay = true;       //需要充值
            } else {
                $egold_pay = $need_money_info['egold'];         //使用的银币个数
            }
        } else {
            $nobuy_vipchparys[$chapterinfo['chapterid']] = $chapterinfo;
            unset($result);
            unset($client);
            $client                                      = new \HS\Yar("dingyuechapter");
            $result                                      = $client->getNoBuyChapterTruesaleprice($bid, $nobuy_vipchparys, $pricebeishu, $discount_set, 0, $custom_price_set, $xianmian_set);

            if (false === $result) {
                _exit('网络错误，代码008，请重试');
            }
            $need_money = $result['need_totalmoney'];
            if ($userinfo['egold'] >= $need_money) {
                $egold_pay = $need_money;   //使用了多少银币
            } else if ($userinfo['money'] < $need_money) {
                $need_pay = true;           //需要充值
            }

            $msg = '订阅本章，需要';
            if ($is_discount) {
                $discount  = $discount_set['discount'];
                $old_money = ceil($need_money * 10 / $discount);
                $msg       = '订阅本章，原价<del>' . $old_money . C('SITECONFIG.MONEY_NAME') . '</del>，限时特价仅需';
            }
            if ($is_discount) {
                $str = '<strong style="color:red;">' . $need_money . '</strong>' . C('SITECONFIG.MONEY_NAME');
            } else {
                $str = $need_money . C('SITECONFIG.MONEY_NAME');
            }
//            if ($userinfo['egold'] >= $need_money) {
//                if ($is_discount) {
//                    $str = '<strong style="color:red;">' . $need_money . '</strong>' . C('SITECONFIG.EMONEY_NAME');
//                } else {
//                    $str = $need_money . C('SITECONFIG.EMONEY_NAME');
//                }
//            }
            $msg .= $str;
            $this->assign('buy_detail_msg', $msg);
        }

        $orderinfo['need_money']      = $need_money;
        $orderinfo['pl_star_chapter'] = $chapterinfo;
        $orderinfo['pl_end_chapter']  = $pl_end_chapter;
        $orderinfo['order_count']     = $pl_num;
        //章节的更新时间
        if(intval($chapterinfo['last_update_date'])){
            $chapterinfo['updatetime']    = date("Y-m-d,H:i", $chapterinfo['last_update_date']);
        }else{
            $chapterinfo['updatetime']    = date("Y-m-d,H:i", $chapterinfo['publishtime']);
        }
        /* 上下章节的id */


        $tmpary = $bookModel->getChapter($bid);
        //如果没有章节信息则上一章跳转到第一章，下一章跳当前章节
        if (!$tmpary) {
            $chapterinfo['prev_chpid'] = 0;
            $chapterinfo['next_chpid'] = $chpid;
        }
        //所有章节
        $chapterlist = array();
        foreach ($tmpary as $juanorder => $juan) {
            foreach ($juan['chparys'] as $chpidx => $chp) {
                if (strtoupper($chp['candisplay']) == "Y") {
                    $chapterlist[$chpidx] = $chp;
                }
            }
        }
        $pre_next_chps = $this->get_chpt_prevnext($chapterlist, $chapterinfo['chporder']);

        if (!$pre_next_chps) {
            $chapterinfo['prev_chpid'] = 0;
            $chapterinfo['next_chpid'] = $chpid;
        }
        if ($pre_next_chps && $pre_next_chps['prev_chpt']) {
            $chapterinfo['prev_chpid'] = $pre_next_chps['prev_chpt']['chapterid'];
        } else {
            $chapterinfo['prev_chpid'] = 0;
        }
        //没有下一章则用当前章节
        if ($pre_next_chps && $pre_next_chps['next_chpt']) {
            $chapterinfo['next_chpid'] = $pre_next_chps['next_chpt']['chapterid'];
        } else {
            $chapterinfo['next_chpid'] = $chpid;
        }
	if(CLIENT_NAME == 'myd'){
        //评论总数
        $comwhere   = array(
            'bid'          => $bid,
            'deleted_flag' => array('neq', 1),
            'content'      => array('neq', ''),
        );
	//TODO 以后凡有count的，必须带上统计的字段名
        $commentNum = M('newcomment')->where($comwhere)->count();
        $this->assign('commentnum', $commentNum);
	}
		$this->assign('isfree', $isfree);		//有可能非限免的章节但是计算出来的价格去是0，所以，不能以销售价格为0来判断是否限免
        $this->assign('readbuynum', $num);
        $this->assign('orderinfo', $orderinfo);
        $this->assign('chapterinfo', $chapterinfo);
        $this->assign('bookinfo', $bookinfo);
        $this->assign('egold_pay', $egold_pay);
        $this->assign('need_pay', $need_pay);
        session('_last_read_page', url('Client/Book/buyviplist', array('bid' => $bid, 'chpid' => $chpid)));
        $this->display('buyviplist');
    }
    /**
     * 章节目录
     *
     * @param int $bid 书籍id get
     */
    public function chapterlistAction() {
        $bid = I('get.bid', 0, 'intval');
        $pagesize = I("get.pagesize",50,"intval");
        $pagenum = I("get.pagenum",1,"intval");
        $sortby = I("get.sortby","ASC","trim,strtoupper");
        if (!$bid) {
            _exit('参数错误');
        }
        $bookModel = new \Client\Model\BookModel();
        $bookinfo  = $bookModel->getBook($bid);
        if (!$bookinfo) {
            _exit('参数错误');
        }
        //不开放
        if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY') && $bookinfo['publishstatus'] != 9) {
            _exit('对不起，本书暂不开放阅读');
        }
        //样式标志
        if ($bookinfo['classid'] == 2) {
            $style = "nv";
        } else {
            $style = "nan";
        }
        $this->pageTitle = $bookinfo['catename'].' 目录';
        //次级导航条title
        $this->assign("secondTitle","《".$bookinfo['catename']."》 目录");
        //判断sourceId以及copyright、publishstatus
        if ($bookinfo['sourceId'] == 101) {
            _exit('书籍不存在，可能已被删除或还没有上传');
        } else if ($bookinfo['copyright'] == 1) {
            _exit('书籍不存在，可能已被删除或还没有上传');
        } else {
            $this->assign('bookinfo', $bookinfo);
        }

        $tmp   = $bookModel->getChplistByBid($bid);
        $lists = $tmp['list'];
        if (!$lists) {
            _exit('网络错误，代码009，请稍候再试');
        }
        $chps     = count($lists);      //总章节数
        /*获取章节列表*/
        $chapterlist = array();
        $prepage = 0;
        $nextpage = 0;
        $totalpage = ceil($chps/$pagesize);//总页数
        $start = ($pagenum - 1) * $pagesize;//截取的起始
        if($sortby == "DESC"){
           krsort($lists);
        }
        $chapters = array_slice($lists, $start,$pagesize);
        if($chapters){
            $prepage = intval($pagenum - 1)>0 ? intval($pagenum - 1) : 0; //上一页
            $nextpage = intval($pagenum + 1)>$totalpage ? $totalpage : intval($pagenum+1); //下一页
            $chapterlist['chapters'] = $chapters;
            $chapterlist['pages']['prepage'] = $prepage;
            $chapterlist['pages']['pagenum'] = $pagenum;
            $chapterlist['pages']['nextpage'] = $nextpage;
            $chapterlist['pages']['totalpage'] = $totalpage;
        }

        $today    = date('Ymd');   //今天
        $yestoday = date('Ymd', strtotime('-1 day'));       //昨天
        $chpnum   = 0;
        foreach ($lists as $v) {
            $time = date('Ymd', $v['publishtime']);
            if ($time == $yestoday) {
                $chpnum ++;
            }
        }

        $client_bookinfo_json = '';
        if (CLIENT_NAME == 'ios') {
            $client_bookinfo_json = addslashes(urldecode(json_encode(ios_convert_bookinfo($bookinfo, $chps))));
        } elseif (CLIENT_NAME == 'android') {
            $client_bookinfo_json = addslashes(json_encode(android_convert_bookinfo($bookinfo, $chps)));
        }
        if(CLIENT_NAME == 'myd'){
            //书籍最后更新时间，封面
            $bookinfo['cover'] = getBookfacePath($bid);
            $lastupdatetime = $bookinfo['last_vipupdatetime'] ? $bookinfo['last_vipupdatetime'] : $bookinfo['last_updatetime'];
            $bookinfo['lastupdatetime'] = friendly_date($lastupdatetime);
            //目录赋值到页面(检查是否是否订阅)
            $vipchparr = array();
            $this->check_user_login();
            $uid = isLogin();
            if($uid){
                $client = new \HS\Yar("dingoujson");
                $vipchpstr = $client->checkUserAll($bid, $uid, 'ids');
                $vipchparr = explode(',', $vipchpstr);
            }
            $juantitle = $lists[0]['juantitle'];//获取到一个元素（数组）中的juantitle

            foreach($lists as $key => $vo){
                if ($key) {
                    if ($vo['juantitle'] == $juantitle) {
                        $lists[$key]['juantitle'] = '';
                    }
                    if ($vo['juantitle'] != $juantitle && $vo['juantitle']) {
                        //标出每卷的最后一张
                        $lists[$key-1]['juanlastchapter'] = 1;
                        $juantitle = $vo['juantitle'];
                    }
                }

                //是否订阅
                if($vipchparr && in_array($vo['chapterid'],$vipchparr)){
                    $lists[$key]['isorder'] = 1;
                }else{
                    $lists[$key]['isorder'] = 0;
                }
            }
            $lists[$chps-1]['alllastchapter'] = 1; 
            $this->assign('allchapters',$lists);
            $this->assign('bookinfo',$bookinfo);
        }

        $this->assign("chapterlist",$chapterlist);
        $this->assign("lz_info", $bookinfo['lzinfo']);
        $this->assign('bid', $bid);
        $this->assign('client_bookinfo_json', $client_bookinfo_json);
        $this->assign('bookname', $bookinfo['catename']);
        $this->assign('chpnum', $chpnum);
        $this->assign('totalnum', $chps);
        $this->assign('freechapternum',$tmp['freechpcount']);
        //样式标志
        $this->assign("style", $style);
        $this->display();
    }

    /**
     * 书评
     *
     * @param int $bid 书籍id get
     *
     * @return array
     */
    public function commentAction() {

        $bid = I("get.bid", 0, "intval");
        if (!$bid) {
            if (CLIENT_NAME == 'ios') {
                header("Location:" . $this->M_forward);
                exit;
            } else {
                _exit('参数错误');
            }
        }
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $is_login = 1;
        }
        //获取书籍信息,并从缓存读取分类信息
        $bookobj = new \Client\Model\BookModel();
        $bookinfo = $bookobj->getBook($bid, 0);
        if (!$bookinfo) {
            _exit('参数错误');
        }
        //男女样式标志
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
        }
        //样式标志
        $this->assign("style", $style);

        $this->pageTitle = $bookinfo['catename'] . "-书评";
        //次级导航条title
        $this->assign("secondTitle","书评");
        //总回复数
        $comModel = new \Client\Model\NewcommentModel();
        $commentinfo = $comModel->getCommentByBid($bid);
        if(intval($commentinfo['totalnum'])>0){
            $bookinfo['commentnum'] = intval($commentinfo['totalnum']);
        }else{
            $comModel->flush_comment_set($bid);
            $commentinfo = $comModel->getCommentByBid($bid);
            
            $bookinfo['commentnum'] = intval($commentinfo['totalnum']);
//            $nmap = array(
//                'bid'=>$bid,
//                'deleted_flag'=>array('neq',1),
//                'content'=>array('neq',''),
//            );
//            if(!$uid){
//                $nmap['forbidden_flag'] =  array('neq',1);
//            }else{
//                $nmap[] = array(
//                    'forbidden_flag'=>array('neq',1),
//                    'uid'=>$uid,
//                    '_logic'=>'OR',
//                );
//            }
//            $bookinfo['commentnum'] = M('newcomment')->where($nmap)->count();
        }
        //yqm增加粉丝数和粉丝榜
        if(CLIENT_NAME == 'yqm'){
            $bookinfo['fansnum'] = $bookobj->getFansNumber($bid);
            //粉丝榜
            $fansModel = new \Client\Model\FensiModel();
            $bookinfo['fanslist'] = $fansModel->getBookFans($bid,10,1);
            if($bookinfo['fanslist']){
                foreach($bookinfo['fanslist'] as &$vo){
                    $vo['avatar'] = getUserFaceUrl(intval($vo['uid']));
                }
            }
        }
        //喵阅读，精华评论数、章节评论数
        if(CLIENT_NAME == 'myd'){
            $elitemap = array(
                'bid'=>$bid,
                'forbidden_flag'=>array('NEQ',1),
                'deleted_flag'=>array('NEQ',1),
                'content'=>array('NEQ',''),
                array(
                    '_logic'=>'OR',
                    array(
                        array('highlight_flag'=>1,'is_lcomment'=>1,'_logic'=>'OR'),
                        'zan_amount'=>array('GT',0)
                    ),
                    array(
                        'zan_amount'=>array('GT',0)
                    )
                )
            );
            //精华评论数
            $eliteCommentNum = $comModel->where($elitemap)->count();
            //章节评论数
            $chaptermap = array(
                'bid'=>$bid,
                'forbidden_flag'=>array('NEQ',1),
                'deleted_flag'=>array('NEQ',1),
                'content'=>array('NEQ',''),
                'chapterid'=>array('GT',0),
            );
            $chpCommentNum = $comModel->where($chaptermap)->count();
            $bookinfo['elitecommentnum'] = $eliteCommentNum > 0 ? $eliteCommentNum : 0;
            $bookinfo['chpcommentnum'] = $chpCommentNum > 0 ? $chpCommentNum : 0;
            //书籍封面
            $bookinfo['cover'] = getBookfacePath($bid);
        }

        unset($bookobj);
        unset($fansModel);
        $this->assign('bookinfo', $bookinfo);
        $this->assign('is_login', $is_login);
        $this->display();
    }

    /**
     * 书评回复
     *
     * @param int $comment_id 书评id get
     * @param int $pagenum 当前页码 get
     * @param int $totalnum 总记录条数 get
     *
     * @return array 书评回复数组
     */
    public function replyCommentAction() {
        $comment_id = I('get.comment_id', 0, 'intval');
        $bid = I('get.bid', 0, 'intval');
        if (!$bid || !$comment_id) {
            _exit('参数错误');
        }

        $bookModel   = new \Client\Model\BookModel();
        $bookinfo    = $bookModel->getBook($bid);
        //男女样式标志
        $style = 'nan';
        if ((int) $bookinfo['classid'] == 2) {
            $style = "nv";
        }
        //样式标志
        $this->assign("style", $style);
        //查询条件
        $where       = array(
            'n.bid'          => $bid,
            'n.deleted_flag' => array('neq', 1),
            'n.content'      => array('neq', ''),
            'n.comment_id'   => $comment_id,
            'n.forbidden_flag'=>array('neq',1),
        );
        $this->check_user_login();
        $uid = isLogin();
        if($uid){
            $where       = array(
                'n.bid'          => $bid,
                'n.deleted_flag' => array('neq', 1),
                'n.content'      => array('neq', ''),
                'n.comment_id'   => $comment_id,
                array(
                    'n.forbidden_flag'=>array('neq',1),
                    'n.uid'=>$uid,
                    '_logic'=>'OR'
                ),
            );
        }
        $commentinfo = M('newcomment')->alias('n')->field('u.nickname,n.*')->join('__READ_USER__ as u on n.uid = u.uid','left')->where($where)->find();
        if(!$commentinfo){
            _exit("暂无评论");
        }
        /*更新回复的阅读状态(isread) start*/
        if(CLIENT_NAME == 'yqm'){
            if($uid && intval($commentinfo['uid']) == $uid){
                $rmap = array(
                    'comment_id' => $comment_id,
                    'delete_flag'=> array('neq',1),
                    'content' => array('neq',''),
                    array(
                        'forbidden_flag'=>array('neq',1),
                        'uid'=>$uid,
                        '_logic'=>'OR'
                    ),
                );
            M('newcomment_reply')->where($rmap)->save(array('isread'=>1));
            }
            //书籍封面
            $bookinfo['bookface'] = getBookfacePath($bid);
            //作者头像
            $authormap = array('authorid'=>$bookinfo['authorid']);
            $authoruid = M('author')->where($authormap)->getField('uid');
            $bookinfo['author_avatar'] = getUserFaceUrl($authoruid,'big');
            //粉丝值
            $bookinfo['fansval'] = $bookinfo['salenum'] + $bookinfo['total_pro'];
            //开始连载日期
            $bookinfo['lzstart'] = date('Y-m-d',$bookinfo['posttime']);
        }
        /*更新回复的阅读状态(isread) end*/
        if (!$commentinfo['nickname']) {
            $commentinfo['nickname'] = $commentinfo['username'];
        }
        //友好的时间显示
        if ($commentinfo['creation_date']) {
            $commentinfo['time'] = friendly_date($commentinfo['creation_date'], 'mohu');
        }
        //评论头像
        $commentinfo['avatar'] = '';
        if(intval($commentinfo['uid'])){
            $avatar = getUserFaceUrl($commentinfo['uid']);
            $commentinfo['avatar'] = $avatar;
        }
        //回复数
        unset($where);
        $where      = array(
            'bid'=>$bid,
            'delete_flag' => array('neq', 1),
            'content'     => array('neq', ''),
            'comment_id'  => $comment_id,
            'forbidden_flag'=>array('neq',1)
        );
        if($uid){
            $where = array(
                'bid'=>$bid,
                'delete_flag' => array('neq', 1),
                'content'     => array('neq', ''),
                'comment_id'  => $comment_id,
                array(
                    'forbidden_flag'=>array('neq',1),
                    'uid'=>$uid,
                    '_logic'=>'OR'
                )
            );
        }
        $replycount = M('newcomment_reply')->where($where)->count();
        $this->pageTitle = '《' . $bookinfo['catename'] . '》的评论';
        //喵阅读，获取权限,TODO 是否ajax取
        if(CLIENT_NAME == 'myd'){
            $isBanzhu = false;
            $this->check_user_login();
            $uid = isLogin();
            if($uid){
                $commentModel = new \Client\Model\NewcommentModel();
                $commentModel->flush_comment_set($bid);
                $comment_set = $commentModel->get_comment_set_cache($bid);
                $comment_set['banzhu'] = json_decode($comment_set['banzhu'],true);
                $comment_set['fubanzhu'] = json_decode($comment_set['fubanzhu'],true);
                if(isset($comment_set['banzhu'][$uid]) || isset($comment_set['fubanzhu'][$uid])){
                    $isBanzhu = true;
                }
            }
            $this->assign('isbanzhu',$isBanzhu);
        }

        $this->assign('replycount', intval($replycount));
        $this->assign('commentinfo', $commentinfo);
        $this->assign('bookinfo', $bookinfo);
        $this->display('replyComment');
    }

    /**
     * 道具打赏
     * @param int $bid 书号
     * @return int 无
     */
    public function dashangAction() {
        $bid = I('get.bid', 0, 'intval');
        if (!$bid) {
            _exit("查询不到书籍");
        }
        //取书
        $bookmodel = new \Client\Model\BookModel();
        $bookinfo  = $bookmodel->getBook($bid);
        /* 获取剩余鲜花数量start */
        $this->check_user_login();
        $uid = isLogin();
        if ($uid) {
            $userinfo = session();
            if (isset($userinfo['tmp_flower'])) {
                $tmp_flower = session('tmp_flower');
            }else{
                //已投的鲜花
                $memcache = new \Think\Cache\Driver\Memcache();
                $costflowers = $memcache->get("flowercount".$userinfo['uid']);
                $tmp_flower             = C("USERGROUP." . $userinfo['groupid'] . ".flowernum") - intval($costflowers);
            }
            $userinfo['tmp_flower'] = $tmp_flower;
            session('tmp_flower', $tmp_flower);   //给session中写一下方便以后调用
            $this->assign("userinfo", $userinfo);
        }
        /* 获取剩余鲜花数量end */
        //取此书是否有资格投红票赞赏道具
        $shouquaninfo = $bookinfo["shouquaninfo"]; //大于等于8有资格，参考PC book\index.tpl
        //男女样式标志
        $style = 'nan';
        //判断男女频
        $booksex      = $bookinfo['sex_flag'];
        if ($booksex == 'nv') {
            $sign = 3;
            $style = "nv";
        } elseif ($booksex == 'nan') {
            $sign = 2;
        } else {
            $sign = 1;
        }
        //样式标志
        $this->assign("style", $style);

        //道具
        $totalPCount = 0;
        if ($sign == 2) {
            $properties = C("PROPERTIES.boy");
        } elseif ($sign == 3) {
            $properties = C("PROPERTIES.girl");
        }
        //TODO 循环，查数据库，统计，优化
        $map = array(
            'bid' => $bid
        );
        $bpModel = M('BookPro');
        $lists = $bpModel->where($map)->group('pid')->getfield('pid,sum(num) AS total',true);
        for ($i = 0; $i < count($properties); $i++) {
            //$properties[$i]["count"] = $bookmodel->getPropertiesCount($properties[$i]["id"], $bid);
            $properties[$i]["count"] = (int)$lists[$properties[$i]["id"]];
            $totalPCount             = intval($totalPCount) + intval($properties[$i]["count"]);
        }
        //获取本书获得的道具
        //最近赞赏榜
        $proListTime  = $bookmodel->getBooKPro($bid, "addtime desc", 3);
        //最高赞赏榜
        $proListPrice = $bookmodel->getBooKPro($bid, "price desc", 3);

        //打赏道具名
        if(CLIENT_NAME == 'yqm'){
            $prolist = C('PROPERTIES.all');
        }else {
            $prolist = array_merge(C('PROPERTIES.boy'), C('PROPERTIES.girl'));
        }
        $tmp     = array('proListTime', 'proListPrice');
        foreach ($tmp as $tk) {
            foreach ($$tk as $key => $val) {
                foreach ($prolist as $k => $v) {
                    if ($val['pid'] == $v['id']) {
                        $val['name'] = $v['name'];
                        $out[]       = $val;
                    }
                }
            }
            $$tk = $out;
            unset($out);
        }

        //今日某个用户可有红票数量
        $uid = isLogin();
        if ($uid) {
            $uModel  = new \Client\Model\UserModel();
            $totalDayCount = $uModel->getLastTicket($uid);
        }

        //用户拥有的红票信息
        $map        = array(
            'month'   => date("ym"),
            'user_id' => $uid,
        );
        $ticketinfo = M('red_ticket_user')->where($map)->find();

        //性别数字标识
        $sex_num = $sign;
        //批量向模板传值
        $assigns = array('bid', 'bookinfo', 'shouquaninfo', 'properties', 'totalPCount', 'proListTime', 'proListPrice', 'sex_num', 'totalDayCount', 'ticketinfo','prolist');
        foreach ($assigns as $key) {
            $this->assign($key, $$key);
        }
        $this->pageTitle = $bookinfo['catename'];
        $this->assign('secondTitle', '《'.$bookinfo['catename'].'》打赏');
        $this->display();
    }



    /**
     * html5下载app页面
     */
    public function downappAction() {
        $this->pageTitle = '客户端下载';
        $this->display();
    }

    /**
     * 二维码静态页
     */
    public function followAction() {
        $this->display();
    }

    /**
     * 客户端分享着陆页
     *
     * @param string $agent 代理 server获得
     * @param int $isQQ 来源QQ,get方式获取
     * @param int $isQZone 来源QQ空间,get方式获取
     * @param int $isSina 来源新浪,get方式获取
     * @param int $isTencentWb 来源腾讯微博,get方式获取
     * @param int $isWeiXinCircle 来源微信circle,get方式获取
     * @param int $isWeiXin 来源微信,get方式获取
     * @param int $iSinweixinbrower 来源微信浏览器,get方式获取
     * @param int $bid 书号
     *
     * @return 无
     */
    public function sharepageAction() {
        $agent     = strtolower($_SERVER['HTTP_USER_AGENT']);
        $isiphone  = (strpos($agent, 'iphone')) ? true : false;
        $isandroid = (strpos($agent, 'android')) ? true : false;
        $bid       = I('request.bid', 0, 'intval');

        if (!$bid) {
            _exit('参数错误！');
        }
        if ($isiphone) {
            $this->redirect('Book/view', array('bid' => $bid));
        }

        $tpl_name = '';

        $goFrom = '';
        //获得所有来源，并变量化,TODO:用于下载链接的Channel=$goForm
        extract(I('get.'));
        if ($isQQ == '1') {
            $goFrom = 'qq';
        } elseif ($isQZone == '1') {
            $goFrom = 'qzone';
        } elseif ($isSina == '1') {
            $goFrom = 'weibo';
        } elseif ($isTencentWb == '1') {
            $goFrom = 'txweibo';
        } elseif ($isWeiXinCircle == '1' || $isWeiXin == '1') {
            $goFrom = 'weixin';
        }

        $iSinweixinbrower = false;
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') || $testweixin) {
            $iSinweixinbrower = true;
        } else {
            $iSinweixinbrower = false;
        }

        $bModel   = new \Client\Model\BookModel();
        $bookinfo = $bModel->getBook($bid);

        if ($iSinweixinbrower) {
            $tpl_name = 'weixinsetp1';
        } else {
            //获取章节第一章
            $chapterlist              = $bModel->getChplistByBid($bid);
            $firstchapter             = $chapterlist['list'][0];
            $bookinfo['firstchapter'] = $firstchapter;
        }

        if (!$bookinfo || !is_array($bookinfo) || $bookinfo['is_deleted'] == 1 || $bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
            //TODO:客户端分享错误页面
            $this->display('weixinsetp1sharepage');
            exit;
        }

        if ($bookinfo['copyright'] != 3) {
            if ($bookinfo['copyright'] == 1) {
                $this->display('weixinsetp1sharepage');
                exit;
            }
        }

        $client_version = '';
        //软件版本区分客户端
        if (CLIENT_NAME && in_array(CLIENT_NAME, array('ios', 'android'))) {
            $mmtt_version_name = "hongshu_" . CLIENT_NAME . "_versioninfo";
            //TODO：测试站为空
            S('rdconfig');
            $verinfo           = S($mmtt_version_name);
            $client_version    = $verinfo['version'];
            $this->assign('client_version', $client_version);
        }

        $this->pageTitle = $bookinfo['catename'];

        //处理书籍简介样式
        $bookinfo['intro'] = str_replace(array('<p>', '</p>'), array("\n"), $bookinfo['intro']);

        $this->assign('goFrom', $goFrom);
        $this->assign('bookinfo', $bookinfo);

        //根据来源区分模板
        $this->display($tpl_name . 'sharepage');
    }

    /**
     * yqm阅读待审页
     */
    public function daishenAction() {
        $bid   = I("get.bid", 0, "intval");
        $chpid = I("get.chpid", 0, "intval");
        if (!$bid || !$chpid) {
            _exit("参数错误");
        }
        $booKModel = new \Client\Model\BookModel();
        $bookinfo  = $booKModel->getBook($bid);
        if (!$bookinfo) {
            _exit("暂无书籍信息，请稍后重试");
        }
        //标题
        $this->pageTitle = $bookinfo['catename'];
        //获取上下章节id
        $chapterinfo     = $booKModel->getChapterByCid($bid, $chpid);
        if (!$chapterinfo) {
            _exit("暂无章节信息，请稍后重试");
        }

        $chapterlist = $booKModel->getChplistByBid($bid);
        if (!$chapterlist) {
            _exit("查询不到章节，请稍后再试");
//             $chapterinfo['prev_chpid'] = 0;
//             $chapterinfo['next_chpid'] = 0;
        } else {
            $tmpary = $this->get_chpt_prevnext($chapterlist['list'], $chapterinfo['chporder']);
            if (!$tmpary) {
                $chapterinfo['prev_chpid'] = 0;
                $chapterinfo['next_chpid'] = 0;
            } else {
                $chapterinfo['prev_chpid'] = $tmpary['prev_chpt']['chapterid'];
                $chapterinfo['next_chpid'] = $tmpary['next_chpt']['chapterid'];
            }
            //获取最后章节
            $totalnum    = count($chapterlist['list']);
            $lastchapter = $chapterlist['list'][$totalnum - 1];
            //最后一张则跳转到阅读末页
            if ($chpid == $lastchapter['chpterid']) {
                $chapterinfo['next_chpid'] = 'final';
                $chapterinfo['next_isvip'] = true;
            }
            //第一章
            $firstchapter = $chapterlist['list'][0];
            //第一章则上一章id为0
            if ($chpid == $firstchapter['chapterid']) {
                $chapterinfo['prev_chpid'] = 0;
            }
        }
        $this->assign("bookinfo", $bookinfo);
        $this->assign("chapterinfo", $chapterinfo);
        $this->display();
    }
    /**
     * 阅读记录
     * @param 无
     * @return array topbanner
     * */
    public function cookiebookshelfAction() {
        $this->pageTitle = '最近阅读';
        noCachePage();
        $this->check_user_login();
        $uid     = isLogin();

        $bookinfo    = array();
        //获取cookie阅读记录
        $cookiebooks = getcookiefavbooklist(cookie('favs'));
        if($cookiebooks) {
            $bids = array_column($cookiebooks, 'bid');
            //是否收藏
            $favbids = array();
            if ($uid) {
                $map  = array(
                    "uid" => $uid,
                    "bid" => array("IN", $bids),
                );
                $favs = M('fav')->field('bid')->where($map)->select();
                foreach ($favs as $val) {
                    $favbids[] = $val['bid'];
                }
            }
            $bookModel = new \Client\Model\BookModel();
            //总章节数、最后章节号、最后的更新时间、分类、最后阅读的章节
            foreach ($cookiebooks as $k => $vo) {
                $tmp             = array();
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
//                 $allchapters              = $bookModel->getChapter($vo['bid']); //缓存
                $allchapters = $bookModel->getChplistByBid($vo['bid']);
                $last_update_chapter_info = array(); //最后一张章节信息
                $chapterinfo              = array(); //当前章节信息
                if ($allchapters && $allchapters['list']) {
                    if (!$vo['chapterid']) {
                        foreach ($allchapters['list'] as $val) {
                            if ($val['chporder'] == $vo['chpidx']) {
                                $vo['chapterid'] = $val['chapterid'];
                                break;
                            }
                        }
                    }
                    $totalchpnum = count($allchapters['list']);
                    foreach ($allchapters['list'] as $v) {
                        //获取最后一章和当前章节信息
                        if (!$last_update_chapter_info || !$chapterinfo) {
                            if ($v['chapterid'] == $lastchpid) {
                                $last_update_chapter_info = $v;
                            }
                            if ($v['chapterid'] == $vo['chapterid']) {
                                $chapterinfo = $v;
                            }
                        } else {
                            break;
                        }
                    }
                } else {
                    unset($cookiebooks[$k]);
                    continue;
                }
                $tmp['totalChpNum'] = $totalchpnum;
                //获取最后一章章节号
                if (!$last_update_chapter_info) {
                    $last_update_chapter_info = array_pop($allchapters['list']);
                }
                if($last_update_chapter_info){
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
                    $tmp['last_readchpid']  = $vo['chapterid'];
                } else {
                    $tmp['last_readchpid']  = 0;
                    $tmp['last_readchpnum'] = 1;
                }
                //书名
                $tmp['catename'] = $vo['catename'];
                //封面图片
                $tmp['imgurl']   = getBookfacePath($vo['bid'], 'middle');
                //书id
                $tmp['bid']      = $vo['bid'];
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
            ksort($list, SORT_DESC);
            $this->assign('books', $list);
        }
        $this->display();
    }
    /**
     * 下架页面
     */
    function xiajiaAction(){

        $this->display();
    }

    public function getFlowersCountAction(){

    }
}
