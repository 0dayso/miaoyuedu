<?php
/**
 * 帮助、新闻等
 */
namespace Client\Model;
use \HS\Model;

class ArticleModel extends Model{
    /**
     * 获取新闻列表
     * 
     */
    public function getNewsList(){
        $cacheModel = new \HS\MemcacheRedis();
        $news = $cacheModel->getRedis('help_news');
        $edit_time = array_column($news, 'edit_time');
        array_multisort($edit_time,SORT_DESC,$news);
        return $news;
    }
    /**
     * 获取一条新闻、公告等信息
     * @param int $articleid 新闻的id
     */
    public function getNewsInfo($articleid){
        $map = array();
        $map['article_id'] = $articleid;
        $article = $this->where($map)->find();
        if($article && is_array($article)){
            return $article;
        }else{
            return false;
        }
    }
    /**
     * 新闻、公告等，点赞
     * @param int $articleid 主题id
     */
    public function addLove($articleid){
        $map = array();
        $map['article_id'] = $articleid;
        $res = $this->where($map)->setInc('love',1);
    }
    /**
     * 获取公告列表
     */
    public function getNoticeList(){
        $cacheModel = new \HS\MemcacheRedis();
        $names = array(
            'help_indexnotice',
            'help_nannotice',
            'help_nvnotice',
            'help_zuozhenotice',
            'help_nanfulinotice',
            'help_nvfulinotice',
            'help_dreamnotice',
            'help_chubannotice',
            'help_wangbiannotice',
            'help_zhenwennotice',
        );
        $noticelist = array();
        foreach ($names as $k){
            $list = array();
            $list = $cacheModel->getRedis($k);
            if($list && is_array($list)){
                $noticelist = array_merge($noticelist,$list);
            }
        }
        $edit_time = array_column($noticelist, 'edit_time');
        array_multisort($edit_time,SORT_DESC,$noticelist);
        return $noticelist;
    }
    /**
     * 获取帮助首页列表内容
     */
    public function getHelpList(){
        $cacheModel = new \HS\MemcacheRedis();
        $names = array(
            'help_readtopfaq',
            'help_readnewfaq',
            'help_bbsrule',
            'help_webfaq',
            'help_mobilefaq',
            'help_rarefaq',
            'help_readfeedback',
            'help_authortopfaq',
            'help_authornewfaq',
            'help_authorwelfare',
            'help_authorfeedback'
        );
        $helplist = array();
        foreach ($names as $key){
            $index = substr($key,5);
            $helplist[$index] = $cacheModel->getRedis($key);
        }
        return $helplist;
    }
    
}