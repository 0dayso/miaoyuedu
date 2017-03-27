<?php
namespace Client\Controller;

use Client\Common\Controller;

class ChannelajaxController extends Controller{
    /**
     * 保存热搜词
     */
    public function _savehotkeywords_ios(){
        $keyword = I("get.keyword","","trim");
        $sex_flag = I("get.sex_flag","","trim");
        if(!$keyword || !$sex_flag){
            exit;
        }        
        $this_month = date('ym');
        $key = ':otsk:ios:'.$this_month.':'.$sex_flag;
        $redisModel =new \Think\Cache\Driver\Redis();
        $redisModel->zIncrBy($key, 1, $keyword);
        if($redisModel->zSize($key) > 100){
            $redisModel->zRemRangeByRank($key, 100, -1);
        }
        unset($redisModel);
        exit;
    }
    /**
     * 搜索接口（IOS/SEARCHAPI.PHP）
     *
     */
    public function _searchapi_ios(){
        $action = I("get.action","","trim");
        if($action == "searchtip"){
            $keyword = I("get.keyword","","trim");
            $sex_flag = I("get.sex_flag","","trim");
            if($sex_flag == "nv"){
                $pclassid = array(2);
            }else{
                $pclassid = array();
            }
            $postdata="";
            $postdata["method"] = "search";
            $postdata["Pclassids"] = $pclassid;
            $postdata["classid"]     = array();
            $postdata["free"] = 0;
            $postdata["finish"] = 0;
            $postdata["charnum"] = 0;
            $postdata["updatetime"] = 0;
            $postdata["keywordtype"] = 2;
            $postdata["order"] = 4;
            $postdata["page"] = 1;
            $postdata["pagesize"] = 3;
            $postdata["keyword"] = $keyword;
            $limitcharnum_min = 1;
            $limitcharnum_max = 50000000;
            $limitday = 0;
            $offset = 0;
            $shouquaninfo = array();
            $finish = array(0, 1);
            $sortby = 'week_hit*0.1+shouquaninfo+lastweek_salenum+week_fav+(redticket*2)';
            $sourceidary   = array();
            $publishstatus = array(2, 3, 4, 5, 6, 7, 8,9); //待审,已删等状态的书籍不显示
            $filter_mob_copyright = 1;
            $searchModel = new \Client\Model\SearchModel();
            $arr = $searchModel->getSearchResult($keyword, $postdata["keywordtype"], $postdata["Pclassids"], $postdata["classid"], $limitcharnum_min, $limitcharnum_max, $limitday, $offset, $postdata["pagesize"], $shouquaninfo, $finish, $postdata["order"], $sortby, $sourceidary, $publishstatus, $filter_mob_copyright);
            //books
            foreach ($arr['bookinfo'] as $tmp){
                $res['books'][] = $tmp['catename'];
            }
            $postdata="";
            $postdata["method"] = "search";
            $postdata["Pclassids"] = $pclassid;
            $postdata["classid"] = "";
            $postdata["free"] = 0;
            $postdata["finish"] = 0;
            $postdata["charnum"] = 0;
            $postdata["updatetime"] = 0;
            $postdata["keywordtype"] = 3;
            $postdata["order"] = 4;
            $postdata["page"] = 1;
            $postdata["pagesize"] = 3;
            $postdata["keyword"] = $keyword;
            unset($arr);
            $arr = $searchModel->getSearchResult($keyword, $postdata["keywordtype"], $postdata["Pclassids"], $postdata["classid"], $limitcharnum_min, $limitcharnum_max, $limitday, $offset, $postdata["pagesize"], $shouquaninfo, $finish, $postdata["order"], $sortby, $sourceidary, $publishstatus, $filter_mob_copyright);
            //authors
            foreach ($arr['bookinfo'] as $tmp){
                $res['authors'][] = $tmp['authorname'];
            }
            $this->ajaxReturn($res);
        }
        if($action == "hotkeywords"){
            $sex_flag = I("get.sex_flag","","trim");
            $this_month = date('ym');
            $key=':otsk:ios:'.$this_month.':'.$sex_flag;
            $redisModel = new \Think\Cache\Driver\Redis();
            $result = $redisModel->zRevRange($key,0,-1);
            unset($redisModel);
            $this->ajaxReturn($result);
        }
    }
} 