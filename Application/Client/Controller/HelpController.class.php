<?php
/**
 * 模块: 客户端
 *
 * 功能: 用户
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: dingzi
 * @version: $Id: HelpController.class.php 1554 2017-03-13 03:56:28Z guonong $
 */

namespace Client\Controller;

use Client\Common\Controller;

class HelpController extends Controller{

    public function _youku(){
        exit("<embed src='http://player.youku.com/player.php/sid/XMTgxODQyNDI4MA==/v.swf' allowFullScreen='true' quality='high' width='480' height='400' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash'></embed>");
    }
    public function _youkuframe(){
        exit("<iframe height=498 width=510 src='http://player.youku.com/embed/XMTgxODQyNDI4MA==' frameborder=0 'allowfullscreen'></iframe>");
    }
    public function _qq(){
        exit('<embed src="https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=c0381bu4n4u&auto=0" allowFullScreen="true" quality="high" width="480" height="400" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>');
    }
    /**
	 * 帮助列表（帮助一级页面）
	 * @return string 帮助问题列表
	 * */
	public function _index() {
	    $this->pageTitle = "帮助中心";
        S(C('rdconfig'));
	    $help_mobilefaq = S(C("cache_prefix").'_newstaticfilelistmobilefaq');
        if ($help_mobilefaq) {
            foreach ($help_mobilefaq as &$vo) {
                $vo['add_time'] = date("Y-m-d", $vo['add_time']);
            }
        }
        $this->assign('help_mobilefaq',$help_mobilefaq);
	    $this->display();
	}
	/**
	 * 元气萌帮助中心
	 */
	public function _index_yqm(){
	    $this->pageTitle = '帮助中心';
	    $cacheModel = new \HS\MemcacheRedis();
	    //读者常见问题TOP10
	    $readerfaq = $cacheModel->get('help_readtopfaq');
	    if(count($readerfaq) > 10){
	       $readerfaq = array_slice($readerfaq, 0,10);
	    }
	    //社区规则
	    $siterules = $cacheModel->get('help_bbsrule');
	    //作者帮助中心
	    $author_help = $cacheModel->get('help_authortopfaq');

	    $assAry = array('readerfaq','siterules','author_help');
	    foreach($assAry as $vo){
	        $this->assign($vo,$$vo);
	    }
	    $this->display();
	}

	/**
	 * 帮助二级页面
	 *
	 * @param int $helpid 帮助id
	 *
	 * @return string 帮助问题及解决方案
	 * */
	public function ArticleAction(){
	    $helpid = I('get.article_id',0,"intval");
	    if(!$helpid){
	        if((CLIENT_NAME == 'ios' && CLIENT_VERSION >= '1.4.3') || CLIENT_NAME == 'myd'){
	            _exit('参数错误！');
	        }
	        if(CLIENT_NAME == 'ios'){
	            header("Location:".$this->M_forward);
	            exit;
	        }else{
	            $this->redirect($this->M_forward,'',2,'暂无相关帮助内容');
	        }
	    }
	    $map = array("article_id"=>$helpid);
	    $arr = M('article')->field('article_id,cat_id,title,content,love,admin_name')->where($map)->find();
	    $this->pageTitle = $arr['title'];

	    //相关问题，暂不显示
// 	    if(CLIENT_NAME == 'yqm'){
// 	        $relateFaq = $this->getRelateFaq($arr['cat_id'], $helpid);
// 	        $this->assign('relatefaq',$relateFaq);
// 	    }
        $arr['content'] = html_entity_decode($arr['content']);

        if(CLIENT_NAME == 'myd'){
            $this->assign('articleinfo',$arr);
        }
	    $this->assign('title',$arr['title']);
	    $this->assign('content',$arr['content']);
	    $this->display();
	}
	/**
	 * 关于我们(index)、联系我们(lxwm)、如何投稿(tgsm)、版权声明(bqsm)、用户协议(yhxy)
	 * 客服中心(kefu)
	 *
	 */
	public function aboutHelpAction(){
	   $article_id = I('get.article_id','','trim');
	   if(!$article_id){
	       _exit('页面不存在，可能还未上传或已删除，请稍后再试');
	   }
	   if(is_numeric($article_id)){
	       $clean = intval($article_id);
	   }else{
	       $clean = strtolower($article_id);
	       $clean = preg_replace("/[^a-z0-9]/", "_", $clean);
	       $clean = substr($clean,0,30);
	   }
	   $category = $this->getHelpCate();
	   $cat_id = $category['about'];
	   $map['url_flag'] = $article_id;
	   $map['cat_id'] = $cat_id;
	   $article = M('article')->where($map)->find();
	   if(empty($article)){
	       $map=array();
	       $map['article_id'] = $article_id;
	       $article = M('article')->where($map)->find();
	       S('help_'.$article_id,$article);
	   }else{
	       S('help_about_'.$article_id,$article);
	   }
	   if(empty($article)){
	       _exit('参数错误');
	   }else{
	       $article['date']=date("Y/m/d",$article['edit_time']);
	       $article['content'] = htmlspecialchars_decode($article['content']);
	   }
	   $this->pageTitle = $article['title'];

	   $this->assign('aboutarticle',$article);
	   $this->assign('active',$article_id);
	   $this->display();
	}

    /**
     * 微信中打开支付宝帮助页面
     */
    public function AlipayFromWeixinAction(){
        $this->pageTitle = "支付宝充值帮助";

        $this->display();
    }

    public function _weixin_html5(){
        $sex_flag = I('sex_flag', '', 'trim');
        if(!$sex_flag) {
            $sex_flag = cookie('sex_flag');
        }
        if(!$sex_flag || $sex_flag!=='nan') {
            $sex_flag = 'nv';
        }
        $this->assign('sex_flag', $sex_flag);
        $this->display('weixin');
    }

    /**
     * 获取帮助的相关问题
     */
    private function getRelateFaq($cat_id,$article_id){
        if(!$cat_id || !$article_id){
            return false;
        }
        $category = $this->getHelpCate();
        foreach($category as $k => $vo){
            if($vo == $cat_id){
                $type = $k;
                break;
            }
        }
        if(empty($type)){
            return false;
        }else{
            $cacheModel = new \HS\MemcacheRedis();
            $releateArrTmp = $cacheModel->get('help_'.$type);
            foreach($releateArrTmp as $k=>$v){
                if($v['article_id']!=$article_id){
                    $v['date'] = date("Y.m",$v['edit_time']);
                    $releateArr[] = $v;
                }
            }
            return $releateArr;
        }
    }

    /**
     * 返回帮助分类
     */
    private function getHelpCate(){
        return $category = array(
            "readtopfaq"=>1,
            "readnewfaq"=>2,
            "bbsrule"=>3,
            "webfaq"=>4,
            "mobilefaq"=>5,
            "rarefaq"=>6,
            "readfeedback"=>7,
            "authortopfaq"=>8,
            "authornewfaq"=>9,
            "authorwelfare"=>10,
            "authorfeedback"=>11,
            "otherhelp"=>12,
            "news"=>13,
            "gonggao"=>14,
            "gonggao_nan"=>15,
            "gonggao_nv"=>16,
            "gonggao_zuozhe"=>17,
            "about"=>18,
            "gonggao_nanfuli"=>19,
            "gonggao_nvfuli"=>20,
            "fuli"=>21,"zhaopin"=>22,
            "gonggao_dreamfactory"=>23,
            "gonggao_chuban"=>24,
            "gonggao_wangbian"=>25,
            "gonggao_zhengwen"=>26
        );
    }
    /**
     * 新闻列表页,喵阅读
     * @param string $type post(gonggao=公告，news=新闻)
     * @param int $pagesize 每页显示记录数  post
     * @param int $pagenum 当前页码 post
     */
    public function newsListAction(){
        $this->pageTitle = '新闻';
        $type = I('param.type','news','trim');
        $pagesize = I('post.pagesize',8,'intval');
        $pagenum = I('post.pagenum',1,'intval');
        $newslist = array();
        $totalpage = 0;
        $articleModel = new \Client\Model\ArticleModel();
        if($type == 'news'){
            $news = $articleModel->getNewsList();
        }elseif($type == 'gonggao'){
            $news = $articleModel->getNoticeList();
        }
        if($news && is_array($news)){
            $totalnum = count($news);
            $totalpage = ceil($totalnum/$pagesize);
            if($pagenum > $totalpage){
                $pagenum = $totalpage;
            }
            $start = ($pagenum - 1) * $pagenum;
            $newslist = array_slice($news,$start,$pagesize);
            foreach($newslist as &$vo){
                $vo['date'] = date('Y.m.d',$vo['edit_time']);
                $vo['content'] = str_replace('<p>&#160;</p>', '', html_entity_decode($vo['content']));
                $vo['shortContent'] = mb_substr(strip_tags($vo['content']), 0,50,'UTF-8');
            }
        }

        $this->assign('pagenum',$pagenum);
        $this->assign('totalpage',$totalpage);
        $this->assign('newslist',$newslist);
        $this->assign('active',$type);
        $this->display();
    }
    /**
     * 新闻详情页，喵阅读
     * @param int $articleid 新闻的id
     *
     */
    public function newsDetailAction(){
        $articleId = I('get.articleid',0,'intval');
        if(!$articleId){
            _exit('参数错误！');
        }
        $articleModel = new \Client\Model\ArticleModel();
        $articleInfo = $articleModel->getNewsInfo($articleId);
        if(!$articleInfo){
            _exit('参数错误，找不到该记录');
        }
        //格式化日期
        $articleInfo['date'] = date('Y.m.d',$articleInfo['edit_time']);
        $articleInfo['content'] = html_entity_decode($articleInfo['content']);

        $this->assign('articleinfo',$articleInfo);
        $this->display();
    }
    /**
     * 新闻、公告点赞
     * @param int $articleid get
     */
    public function dosupportAction(){
        $output = array('status'=>0,'message'=>'','url'=>'');
        $articleid = I('get.articleid',0,'intval');
        if(!$articleid){
            $output['message'] = '参数错误！';
            $this->ajaxReturn($output);
        }
        $cacheModel = new \HS\MemcacheRedis();
        $key = "lastArticleZanTime#".$articleid."#".get_client_ip();
        $lastZanTime = $cacheModel->getMc($key);
        if($lastZanTime){
            $output['message'] = '对不起，您的操作过于频繁，请稍后再试';
            $this->ajaxReturn($output);
        }
        $articleModel = new \Client\Model\ArticleModel();
        $info = $articleModel->getNewsInfo($articleid);
        if(!$info){
            $output['message'] = '该记录不存在';
            $this->ajaxReturn($output);
        }
        $articleModel->addLove($articleid);
        $cacheModel->setMc($key, 1,300);
        $output['status'] = 1;
        $output['message'] = '点赞成功';
        $output['num'] = $info['love'] + 1;
        $this->ajaxReturn($output);
    }
    /**
     * 喵阅读,帮助首页
     * 读者常见问题TOP10 readTopFaq
     * 最新问题   readnewfaq
     * 喵阅读社区规则   bbsrule
     * 网页版问题   webfaq
     * 手机端问题   mobilefaq
     * 其他疑难杂症   rarefaq
     * 作者常见问题TOP10 authortopfaq
     * 作者最新问题   authornewfaq
     * 作者权益和福利    authorwelfare
     *
     */
    public function _index_myd(){
        $articleModel = new \Client\Model\ArticleModel();
        $helplist = $articleModel->getHelpList();

        $this->assign($helplist);
//         $this->assign('readTopFaq',$helplist['readtopfaq']);
        $this->assign('active','help');
        $this->display();
    }


}