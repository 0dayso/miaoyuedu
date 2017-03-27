<?php
/**
 * FILE_NAME :  SearchModel.class.php
 * 属于:home模块
 *
 * 功能: 提交请求到后端sphinx服务,获得doc数组,并格式化为bookinfo数组,提供搜索结果
 *
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author fzfz@hongshu.com
 * @version 2015-9-26 下午3:18:11
 */

namespace Client\Model;

use HS\Model;

class SearchModel extends Model {
    protected $autoCheckFields = false;
    public $error;

    /**
     * 获取sphinx搜索结果
     * @param string $keyword 要查询的关键字
     * @param int $keywordtype 关键字检索方式（1：综合查询 2：根据作品名查询 3：根据作者名查询 4：根据简介查新 5：根据标签查询）
     * @param array $classid
     * @param array $classid2
     * @param int $limitcharnum_min 章节最少字数
     * @param int $limitcharnum_max 章节最多字数
     * @param type $limitday
     * @param int $offset 起始行号
     * @param int $limit 获取行数
     * @param type $shouquaninfo
     * @param type $lzinfo
     * @param string $sortby 排序方式
     * @param type $sourceidary
     * @param type $publishstatus
     * @param type $filter_mob_copyright
     * @return boolean/array 查询失败或无记录返回false，查询成功返回数组
     */
    public function getSearchResult($keyword, $keywordtype, $classid = array(), $classid2 = array(), $limitcharnum_min = 0, $limitcharnum_max = 0, $limitday = 0, $offset = 0, $limit = 20, $shouquaninfo = array(), $lzinfo = array(), $sortby = 0, $sortmod = '', $sourceidary = array(), $publishstatus = array(
        2, 3, 4, 5, 6, 7, 8), $filter_mob_copyright = true) {
        $sph_result = array();
        if (is_numeric($keyword)) {
            $bid      = intval($keyword);
            S(C('rdconfig'));
            $bookinfo = S("book_normal#" . $bid);
            if ($bookinfo) {
                $sph_result = array(
                    'matches'     => array(
                        0 => array(
                            'id' => $bid,
                        )
                    ),
                    'total_found' => 1
                );
            }
        } else {
            $sph_result = $this->getSphinxData($keyword, $keywordtype, $classid, $classid2, $limitcharnum_min, $limitcharnum_max, $limitday, $offset, $limit, $shouquaninfo, $lzinfo, $sortby, $sortmod, $sourceidary, $publishstatus, $filter_mob_copyright);
        }
        if ($sph_result) {
            $bookinfoAry = $this->getBookinfoBySphres($sph_result['matches']);
            //排除的个数
            $reducenum = intval($bookinfoAry['reducenum']);
            unset($bookinfoAry['reducenum']);
            
            if(count($bookinfoAry) > 0){
                $realnum = intval($sph_result['total_found']) - $reducenum;
                $pagecount = ceil($realnum / $limit);
    
                return array(
                    'totalcount' => $realnum,
                    'pagecount' => $pagecount,
                    'bookinfo' => $bookinfoAry
                );
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获得书库书籍id结果和结果总数
     *
     * @param string $keyword
     * @param int $keywordtype
     *            1：综合查询 2：根据作品名查询 3：根据作者名查询 4：根据简介查新 5：根据标签查询
     * @param array $classid
     *            大类id
     * @param array $classid2
     *            小类id
     * @param int $limitcharnum_min
     *            字数限制下限,默认为0
     * @param int $limitcharnum_max
     *            字数限制上限 默认为0不限制
     * @param int $limitday
     *            更新天数限制,默认0不限制
     * @param int $offset
     *            起始offset
     * @param int $limit
     *            取几条
     * @param array $shouquaninfo
     *            将需要获得的版权类型作为数组元素,如array(8,9)
     * @param array $lzinfo
     *            方法同上,但取的是连载类型
     * @param int $sort_mod
     * @param string $sortby
     *            排序字段,支持total_hit,month_hit,charnum,posttime,last_updatetime,last_vipupdatetime,redTicket,total_hit,all_updatetime(这是唯一的一个特殊排序字段)
     * @param array $sourceidary
     *            将要过滤来源ID作为数组元素，如array(101)
     * @param
     *
     * @return boolean|array('total_fount','matches'=array('');
     */
    private function getSphinxData($keyword, $keywordtype, $classid = array(), $classid2 = array(), $limitcharnum_min = 0, $limitcharnum_max = 0, $limitday = 0, $offset = 0, $limit = 20, $shouquaninfo = array(), $lzinfo = array(), $sort_mod = '0', $sortby = '', $sourceidary = array(), $publishstatus = array(
        2, 3, 4, 5, 6, 7, 8), $filter_mob_copyright = true) {
        $cl = new \Org\Util\SphinxClient();

        // print_r($classid);
        // exit;
        $mode = SPH_MATCH_EXTENDED2; // 查询语句是一个表达式

        switch ($sort_mod) {
            case 0:
                $sort_mode = SPH_SORT_RELEVANCE; // 默认相关度排序
                break;
            case 1: // 按sortyby 字段值排序 更新时间

            case 2: // 点击量
            case 3: // 字数
                $sort_mode = SPH_SORT_ATTR_DESC;
                break;
            case 4: // 按sortby 公式排序
                $sort_mode = SPH_SORT_EXPR;
                break;
            default:
                $sort_mode = SPH_SORT_RELEVANCE; // 默认相关度排序
                break;
        }
        $host = C("SPHINX_HOST");
        $port = C("SPHINX_PORT");

        $ranker = SPH_RANK_PROXIMITY_BM25; // 匹配度评分算法

        $error = false; // 错误信息

        $cl->SetServer($host, $port);
        // 过滤未审核和已屏蔽
        $cl->SetFilter('publishstatus', array(
            2,
            3,
            4,
            5,
            6,
            7,
            8,9
            ), true);
        // 是否过滤只有移动版版权的书籍
        if ($filter_mob_copyright) {
            $cl->SetFilter('copyright', array(
                2
                ), true);
        } else {
            $cl->SetFilter('copyright', array(
                1
                ), true);
        }

        $index = "*";

        if ($keywordtype == 1 && !empty($keyword)) {
            $tmp = trim($this->get_word_segment($keyword));
            // print_r($tmp);
            if (!empty($tmp)) {
                $q = '@* ' . $tmp;
            } elseif (!empty($keyword)) {
                $q = '@* ' . $keyword;
            }
            // print_r($q);
            $mode = SPH_MATCH_ANY;
            $cl->SetFieldWeights(array(
                'catename' => 10,
                'intro'    => 1
            ));
        } elseif (!empty($keyword)) {
            $tmp = trim($this->get_word_segment($keyword));
            switch ($keywordtype) {
                case 2:
                    $keywordtype = 'catename';
                    break;
                case 3:
                    $keywordtype = 'author';
                    break;
                case 4:
                    $keywordtype = 'intro';
                    break;
                case 5:
                    $keywordtype = 'tags';
                    break;

                // ：根据作品名查询 3：根据作者名查询 4：根据简介查新 5：根据标签查询
            }
            if (!empty($tmp)) {
                $q = '@' . $keywordtype . ' ' . $tmp;
            } else {
                $q = '@' . $keywordtype . ' ' . $keyword;
            }

            $cl->SetFieldWeights(array(
                $keywordtype => 20000
            ));
        }

        if ($limitcharnum_max > 0) {

            $cl->SetFilterRange('charnum', $limitcharnum_min, $limitcharnum_max, false);
        }

        if (count($shouquaninfo) >= 1) {
            $cl->SetFilter('shouquaninfo', $shouquaninfo, false);
        }

        if (count($lzinfo) >= 1) {
            $cl->SetFilter('lzinfo', $lzinfo, false);
        }

        if (count($classid) >= 1) {
            $cl->SetFilter('classid', $classid, false);
        }

        if (count($classid2) >= 1) {
            $cl->SetFilter('classid2', $classid2, false);
        }

        $cl->SetMatchMode($mode);
        $cl->SetSelect("*,IF(last_updatetime>last_vipupdatetime,last_updatetime,last_vipupdatetime) AS all_updatetime");
        if ($limitday > 0) {

            $limitday_min = strtotime("- " . $limitday . ' days');
            $limitday_max = time();
            $cl->SetFilterRange('all_updatetime', $limitday_min, $limitday_max, false);
        }
        if (!empty($sortby)) { // 排序设置
            $cl->SetSortMode($sort_mode, $sortby);
        } else {
            $cl->SetSortMode($sort_mod);
        }
        if (count($sourceidary) >= 1) {
            $cl->SetFilter('sourceId', $sourceidary, true);
        }

        $cl->SetLimits($offset, $limit, 30000);
        $cl->SetRankingMode($ranker);
        $cl->SetArrayResult(TRUE);

        $res = $cl->Query($q, $index);

        if ($res === false) {
            $this->error .= "系统错误: " . $cl->GetLastError() . "<br/>";
            return false;
        }
        return $res;
    }

    /**
     * 调用接口进行中文分词
     *
     * @param unknown_type $q
     * @return 返回空格分隔的字符串
     */
    private function get_word_segment($q) {
        $postdata['str'] = $q;

        $tmp = PostData($postdata, C('INTERFACEURL') . '/wordsegment.php');

        if (!empty($tmp)) {
            return json_decode($tmp, true)['result'];
        }

        return $q;
    }

    /**
     * 根据sph搜索结果中的文档id 数组获得书籍缓存数据
     *
     * @param array $sph_result
     * @return array
     */
    private function getBookinfoBySphres($sph_result) {
//         parent::initRedis();
        $bookModel = new \Client\Model\BookModel();
        $res = array();
        $reducenum = 0;
        foreach ($sph_result as $docinfo) {
            $bookinfo = $bookModel->getBook($docinfo['id']);
//             $bookinfo = S("book_normal#" . $docinfo['id']);
            if (!$bookinfo || $bookinfo['bid'] == null || $bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
                $reducenum ++;
                continue;
            }
            $last_update_time  = $bookinfo['last_updatetime'] > $bookinfo['last_vipupdatetime'] ? $bookinfo['last_updatetime'] : $bookinfo['last_vipupdatetime'];
            $last_juanid       = $bookinfo['last_updatetime'] > $bookinfo['last_vipupdatetime'] ? $bookinfo['last_updatejuanid'] : $bookinfo['last_vipupdatejuanid'];
            $last_chapterid    = $bookinfo['last_updatetime'] > $bookinfo['last_vipupdatetime'] ? $bookinfo['last_updatechpid'] : $bookinfo['last_vipupdatechpid'];
            $last_update_title = $bookinfo['last_updatetime'] > $bookinfo['last_vipupdatetime'] ? $bookinfo['last_updatechptitle'] : $bookinfo['last_vipupdatechptitle'];
            $res[]             = array(
                'catename'          => $bookinfo['catename'],
                'bid'               => $bookinfo['bid'],
                'juanid'            => $last_juanid,
                'chapterid'         => $last_chapterid,
                'authorname'        => $bookinfo['author'],
                'sex_flag'          => $bookinfo['sex_flag'],
                'finish'            => ($bookinfo['lzinfo'] ? 1 : 2),
                'charnum'           => $bookinfo['charnum'],
                'updatetime'        => $last_update_time,
                'tag'               => $bookinfo['tagsary'],
                'total_hit'         => $bookinfo['total_hit'],
                'redticket'         => $bookinfo['redTicket'],
                'total_flower'      => $bookinfo['total_flower'],
                'authorid'          => $bookinfo['authorid'],
                'classname'         => $bookinfo['subclassname'],
                'classid'           => $bookinfo['classid'],
                'intro'             => $bookinfo['intro'], // 获取简介
                'star'              => $bookinfo['star'], // 评分
                'lzinfo'            => $bookinfo['lzinfo'], // 连载信息
                'salenum'           => $bookinfo['salenum'], // 销售数量
                'month_hit'         => $bookinfo['month_hit'], // 月点击
                'shouquanname'      => $bookinfo['shouquaninfo'] == 9 ? "VIP" : "免费",
                'bookface'          => getBookfacePath($bookinfo['bid']),
                'last_update_title' => $last_update_title,
                'total_fav'         => $bookinfo['total_fav'],       //总收藏量
            );
        }
        $res['reducenum'] = $reducenum;
        return $res;
    }

}
