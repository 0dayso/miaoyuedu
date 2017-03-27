<?php

namespace Client\Controller;

use Client\Common\Controller;
use Home\Controller\NvindexController;
use HS\MemcacheRedis;
use Think\Cache\Driver\Memcached;

class ChannelController extends Controller {
    /**
     * 搜索(sphinx)
     *
     * @param string $sex_flag nan=男,nv=女
     *
     * @return string $sex_flag nan/nv
     * @return string $category $sex_flag对应的分类
     */
    public function searchAction() {
        $sex_flag = I("get.sex_flag", C('DEFAULT_SEX'), "trim");
        $classid  = I("get.classid", 0, "intval");
        $category = C('CATEGORY');
        //判断是否需要在二级页面打开
        $gourl    = I('get.isnewwin', 0, 'intval') ? 'hg_gotoUrl' : 'doChild';
        $this->assign('gourl', $gourl);
        //关键字
        $keyword  = I("get.keyword", "", "trim,urldecode");
        $pretitle = '男生小说';
        if ($sex_flag == 'nv') {
            $pretitle = '女生小说';
        }
        if (CLIENT_NAME == 'yqm' || (CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0')) {
            $this->pageTitle = '书库';

        } else if (CLIENT_NAME == 'myd') {
            $pretitle = '女生小说';
            $this->pageTitle = $pretitle . "搜索";
        } else {
            if ($classid && $sex_flag=='nan') {
                $this->pageTitle = $pretitle."-".$category[$classid]['title'];
            }elseif($classid && $sex_flag == 'nv'){
                $this->pageTitle = $pretitle."-".$category[2]['subclass'][$classid]['title'];
            } else {
                $this->pageTitle = $pretitle . "-搜索";
            }
        }
        if($keyword){
            $this->pageTitle .= "-".$keyword;
        }
        //喵阅读，获取标签
        if(CLIENT_NAME == 'myd'){
            $bookModel = new \Client\Model\BookModel();
            $tags = $bookModel->getTags(20);
            $this->assign('tags',$tags);
            //作品分类的数据来自于Conf/category.php在喵阅读中只取女频分类
            $category = $category[2]['subclass'];
            //完结榜,TODO 暂时取index_wanben
            $cacheModel = new \HS\MemcacheRedis();
            $finishlist = $cacheModel->getRedis('_bangindex_wanjiebang');
            $this->assign('index_wanjiebang',$finishlist['booklists']);
        }

        $this->assign("keyword", $keyword);
        $this->assign("classid", $classid);
        $this->assign("sex_flag", $sex_flag);
        $this->assign("category", $category);
        $this->display();
    }

    /**
     * 男生首页(榜单)
     *
     * @param 无
     *
     * @return 无
     */
    public function indexAction() {
        $sex_flag = I('get.sex_flag', C('DEFAULT_SEX'), 'trim');
        $category = C('CATEGORY');
        if ($sex_flag) {
            if ($sex_flag === 'nan') {
                $this->pageTitle = "男生";
                unset($category[2]);
            } else {
                $this->pageTitle = "女生";
                $category        = $category[2];
                //言情控过滤掉短篇美文
                foreach($category['subclass'] as $key=>$val){
                    if($val['title'] == '短篇美文'){
                        unset($category['subclass'][$key]);
                    }
                }
            }
            $this->assign('style', $sex_flag);
            $this->assign('category', $category);
            $this->assign("sex_flag", $sex_flag);
            $this->display($sex_flag);
        } else {
            $this->display('Index:index');
        }
    }

    /**
     * 红文馆(sphinx--lastweek_salenum)
     *
     * @param 无
     *
     * @return 无
     */
    public function hongwenAction() {
        $sex_flag = I("get.sex_flag", C('DEFAULT_SEX'), "trim");
        if (!in_array($sex_flag, array("nan", "nv"))) {
            if (CLIENT_NAME == 'ios') {
                header("Location:" . $this->M_forward);
                exit;
            } else {
                $this->redirect('Index/index', '', 2, '参数错误');
            }
        }
        if ($sex_flag == "nan") {
            $this->pageTitle = "男生红文街";
        } else {
            $this->pageTitle = "女生红文街";
        }

        $this->assign("sex_flag", $sex_flag);
        $this->display();
    }

    /**
     * 排行榜(sphinx---lastweek_salenum)
     *
     * @param string $sex_flag nan=男 nv=女
     *
     * @return string $sex_flag
     */
    public function rankAction() {
        $sex_flag = I('get.sex_flag', C('DEFAULT_SEX'), 'trim');
        $this->assign('sex_flag', $sex_flag);
        $pretitle = '男生';
        if ($sex_flag == 'nv') {
            $pretitle = '女生';
        }
        $this->pageTitle = $pretitle . "排行榜";
        if(CLIENT_NAME == 'ios' && CLIENT_VERSION >= '2.0.0'){
            $this->pageTitle = '排行榜';
        }
        /*获取排行首页数据
        $books = array();
        $searchModel = new \Client\Model\SearchModel();
        $keyword = '';
        $keywordtype = 1;
        if($sex_flag == 'nv'){
            $pclassidAry = array(2);
        }else {
            $pclassidAry = array_keys(C('CATEGORY'));
            unset($pclassidAry[array_search(2, $pclassidAry, true)]);
        }
        $classidAry = array();
        $limitcharnum_min = 1;
        $limitcharnum_max = 50000000;
        $limitday = 0;
        $offset = 0;
        $pagesize = I('param.pagesize',20,'intval');
        $shouquaninfo = array(9);
        $finish = array(0,1);
        $sort_mod = 1;
        $sortby = 'lastweek_salenum';
        $sourceidary = array();
        $publishstatus = array(2,3,4,5,6,7,8,9);
        $filter_mob_copyright = 1;
        $lists = $searchModel->getSearchResult($keyword, $keywordtype, $pclassidAry, $classidAry, $limitcharnum_min, $limitcharnum_max, $limitday, $offset, $pagesize, $shouquaninfo, $finish, $sort_mod, $sortby, $sourceidary, $publishstatus, $filter_mob_copyright);
        if($lists){
            $books = $lists['bookinfo'];    
        }
        unset($searchModel);
        
        $this->assign('books',$books);
        */
        if(CLIENT_NAME == 'myd'){
            $this->pageTitle = '排行榜';
            //点击榜
            $bookModel = new \Client\Model\BookModel();
            $clicklist = array();
            $clicklist['day'] = $bookModel->getHitRank('nan', 'week');
            $clicklist['week'] = $bookModel->getHitRank('nan', 'month');
            $clicklist['month'] = $bookModel->getHitRank('nan', 'total');
            //订阅榜
            $orderlist = array();
            $orderlist['day'] = $bookModel->getSaleRank('nan', 'total');
            $orderlist['week'] = $bookModel->getSaleRank('nan', 'week');
            $orderlist['month'] = $bookModel->getSaleRank('nan', 'month');
            //收藏榜,日，周，月
            $favlist = array();
            $favlist['day'] = $bookModel->getFavRank('nan', 'total');   //TODO 日收藏没数据，暂时取男频总收藏
            $favlist['week'] = $bookModel->getFavRank('nv', 'total');   //TOTO 周收藏没数据，暂时取女频总收藏
            $favlist['month'] = $bookModel->getFavRank('nan', 'total'); //TODO 月收藏暂时取男频总收藏
            //更新榜 TODO 暂时取男频点击榜
            $updatelist = array();
            $updatelist['day'] = $bookModel->getHitRank('nan', 'total');
            $updatelist['week'] = $bookModel->getHitRank('nan', 'week');
            $updatelist['month'] = $bookModel->getHitRank('nan', 'month');
            //点赞榜(鲜花榜)
            $zanlist = array();            
            $zanlist['day'] = $bookModel->getFlowerRank('nan', 'week'); //TODO 暂时取男频周鲜花榜
            $zanlist['week'] = $bookModel->getFlowerRank('nv', 'week'); //TODO 暂时取女频周鲜花榜
            $zanlist['month'] = $bookModel->getFlowerRank('nan', 'total');  //TODO 暂时取男频总鲜花榜
            //打赏榜,TODO 暂时取女生点击榜
            $dashanglist = array();
            $dashanglist['day'] = $bookModel->getHitRank('nv', 'week');
            $dashanglist['week'] = $bookModel->getHitRank('nv', 'month');
            $dashanglist['month'] = $bookModel->getHitRank('nv', 'total');
            
            
            $this->assign('dashanglist',$dashanglist);
            $this->assign('zanlist',$zanlist);
            $this->assign('updatelist',$updatelist);
            $this->assign('favlist',$favlist);
            $this->assign('orderlist',$orderlist);
            $this->assign('clicklist',$clicklist);
        }
        $this->display();
    }

    /**
     * 免费(榜单)
     * (android_freenan_benjizhudai 男)
     *
     * @param string $sex_flag nan=男/nv=女/both=今日免费(包括男/女)
     *
     * @return string $sex_flag
     */
    public function freeAction() {
        $sex_flag = I("get.sex_flag", "", "trim");
        if ($sex_flag == "nan") {
            $this->pageTitle = "免费小说-男生";
        } else {
            $this->pageTitle = "免费小说-女生";
        }
            
        $this->assign("sex_flag", $sex_flag);
        $this->display();
    }

    /**
     * 特价(榜单取)
     *
     * @param string $sex_flag nan=男/nv=女
     *
     * @return string $sex_flag
     */
    public function tejiaAction() {
        $sex_flag = I("get.sex_flag", C('DEFAULT_SEX'), "trim");
        $pretitle = '男生';
        if ($sex_flag == 'nv') {
            $pretitle = '女生';
        }
        $this->pageTitle = $pretitle . "天天特价";


        $this->assign("sex_flag", $sex_flag);
        $this->display();
    }

    /**
     * 新书(榜单)
     *
     * @param string $sex_flag nan=男/nv=女
     *
     * @return string $sex_flag
     */
    public function xinshuAction() {
        $sex_flag = I("get.sex_flag", C('DEFAULT_SEX'), "trim");
        if (!in_array($sex_flag, array("nan", "nv"))) {
            if (CLIENT_NAME == 'ios') {
                header("Location:" . $this->M_forward);
                exit;
            } else {
                $this->redirect('Index/index', '', 2, '参数错误');
            }
        }
        if ($sex_flag == "nan") {
            $this->pageTitle = "男生新书";
        } else {
            $this->pageTitle = "女生新书";
        }

        $this->assign("sex_flag", $sex_flag);
        $this->display();
    }
    /**
     * 元气萌ios精选页面
     * 
     */
    public function jingxuanAction(){
        $this->pageTitle = '精选';
        
        $this->display();
    }
    /**
     * 言情控：免费新书榜
     */
    public function rankfreeAction(){
        $this->pageTitle = '免费新书';
        
        $this->display();
    }

    /**
     *福利页面渲染视图
     */
    public function fuliAction(){
        $this->display();
    }

    /**
     * 喵阅读版权声明
     * 注意：榜单前面需要加上 _bang 前缀，否则取不到值
     * 为方便区分，所以用 . 隔开
     * 取到的数据为html代码，如果只取其中某元素传值到前台无法显示
     */
    public function _copyright_myd(){
        $cacheModel = new MemcacheRedis();
        $top_lists = $cacheModel->get('_bang' . 'static_copyright_top');
        $msg_lists = $cacheModel->get('_bang' . 'static_copyright_msg');
        $star_lists = $cacheModel->get('_bang' . 'static_copyright_star');
        $god_lists = $cacheModel->get('_bang' . 'static_copyright_god');
        $book_lists = $cacheModel->get('_bang' . 'static_copyright_book');
        $static_copyright = array(
            'top' => $top_lists,
            'msg' => $msg_lists,
            'star' => $star_lists,
            'god' => $god_lists,
            'book' => $book_lists,
        );
        $this->assign($static_copyright);
        $this->display();
    }
}
