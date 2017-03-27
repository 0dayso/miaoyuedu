<?php
/**
 * 快速排序，用来排序书签使其按加入书签降序排列
 * @parm $arr 书签数组
 * */
function quickBookSort($arr) {
    if (count($arr) <= 1) {
        return $arr;
    }
    //声明数组
    $leftArr  = array(); //addtime小
    $rightArr = array(); //addtime大
    //分解书签
    foreach ($arr as &$vo) {
        $tmp           = explode('\t', $vo['bookmark']);
        $vo['addtime'] = $tmp[2];
    }
    //选取参考值
    $standard = $arr[0]['addtime'];
    for ($i = 1; $i < count($arr); $i++) {
        if ($arr[$i]['addtime'] <= $standard) {
            $leftArr[] = $arr[$i];
        } else {
            $rightArr[] = $arr[$i];
        }
    }
    $leftArr  = quickBookSort($leftArr);
    $rightArr = quickBookSort($rightArr);

    return array_merge($rightArr, array($arr[0]), $leftArr);
}

/**
 * 获得订阅分表名字,2012年10月(含10月)之前的不分表
 * @param unknown_type $yearmonth 例如:'1210'
 * @return string
 */
function get_salelogsTablename($yearmonth = '') {
    if (!empty($yearmonth)) {
        $tableSubname = (int) $yearmonth;
    } else {
        $tableSubname = (int) date("ym");
    }
    if ($tableSubname <= 1210) {
        return 'wis_salelogs';
    }
    return 'wis_salelogs' . $tableSubname;
}

/**
 * 获得相对btime的上月,本月,今天,昨天...的起始时间star_time和结束时间end_time
 *
 * @param unknown_type $btime
 * @param unknown_type $stype=lastmonth|thismonth|lastday|thisday|lastweek|thisweek
 * @param unknown_type $star_time
 * @param unknown_type $end_time
 */
function mk_time_xiangdui($btime, $stype = 'lastmonth', &$star_time, &$end_time) {
    if (empty($btime)) {
        $btime = time();
    }
    switch ($stype) {
        case 'lastmonth':
            //$btime=mktime(0,0,0,date('m',$btime)-1,date('d',$btime),date("Y",$btime));
            $star_time = mktime(0, 0, 0, date('m', $btime) - 1, 1, date("Y", $btime));
            $end_time  = mktime(23, 59, 59, date('m', $btime), 00, date("Y", $btime));
            break;
        case 'thismonth':
            $star_time = mktime(0, 0, 0, date("m", $btime), 1, date("Y", $btime));
            //$endtime=mktime(23,59,59,date("m",$btime),date("t",$btime),date("Y",$btime));
            $end_time  = mktime(23, 59, 59, date('m', $btime) + 1, 00, date("Y", $btime));
            //$star_time=mktime(0, 0, 0, date('m',$btime),   '1',  date('Y',$btime));
            //$end_time=mktime(0, 0, 0, date('m',$btime),   '31',  date('Y',$btime));
            break;
        case 'lastday':
            $star_time = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - 1, date('Y', $btime));
            $end_time  = mktime(24, 0, 0, date('m', $btime), date('d', $btime) - 1, date('Y', $btime));
            break;
        case 'thisday':
            $star_time = mktime(0, 0, 0, date('m', $btime), date('d', $btime), date('Y', $btime));
            $end_time  = mktime(24, 0, 0, date('m', $btime), date('d', $btime), date('Y', $btime));
            break;
        case 'thisweek':
            $star_time = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - date("w", $btime) + 1, date("Y", $btime));
            $end_time  = mktime(23, 59, 59, date('m', $btime), date('d', $btime) - date("w", $btime) + 7, date("Y", $btime));
            break;
        case 'lastweek':
            $star_time = mktime(0, 0, 0, date('m', $btime), date('d', $btime) - date("w", $btime) + 1 - 7, date("Y", $btime));
            $end_time  = mktime(23, 59, 59, date('m', $btime), date('d', $btime) - date("w", $btime) + 7 - 7, date("Y", $btime));
            break;
    }
}

/**
 * 添加一本书的阅读记录
 *
 * @param unknown_type $bid
 * @param unknown_type $chpid
 * @param unknown_type $pn
 * @param unknown_type $chpidx
 */
function addonecookiefav($bid, $chpid = 0, $pn = 0, $chpidx = 0) {

    $favcookiearys       = getcookiefavary(cookie('favs'));
    unset($favcookiearys[$bid]);
    $favcookiearys[$bid] = array(
        'bid'       => $bid,
        'chapterid' => $chpid,
        'pn'        => $pn,
        'chpidx'    => $chpidx
    );
    if (count($favcookiearys) >= 11) {
        $favcookiearys = array_slice($favcookiearys, - 10, 10, true);
    }
    savecookiefav($favcookiearys);
}

/**
 * 从cookie获得记录,并返回数组格式,用于获取书目
 *
 * @param unknown_type $cookiestr
 */
function getcookiefavary($cookiestr) {
    $favcookiearys = explode(',', $cookiestr);
    if (is_array($favcookiearys)) {
        foreach ($favcookiearys as $k => $v) {
            $tmpv = explode('|', $v);
            if ($tmpv[0]) {
                $outary[$tmpv[0]] = array(
                    'bid'       => $tmpv[0],
                    'chapterid' => $tmpv[1],
                    'pn'        => $tmpv[2],
                    'chpidx'    => $tmpv[3]
                );
            }
        }
    }

    return $outary;
}

/**
 * 保存记录到cookie
 *
 * @param unknown_type $cookiearyas
 */
function savecookiefav($cookiearyas) {
    foreach ($cookiearyas as $val) {
        $chpid = (int) $val['chapterid'];

        $favcookie[] = $val['bid'] . '|' . $chpid . '|' . $val['pn'] . '|' . $val['chpidx'];
    }

    $favcookie = implode(',', $favcookie);

    cookie('favs', $favcookie, time() + 3153600);
}

/* cutstr */
function cutstr($string, $sublen, $addopt = true, $start = 0, $code = 'UTF-8') {
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if ($addopt) {
            $addoptstr = '...';
        }
        if (count($t_string[0]) - $start > $sublen) {

            return join('', array_slice($t_string[0], $start, $sublen)) . $addoptstr;
        }
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start  = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr.= substr($string, $i, 2);
                } else {
                    $tmpstr.= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129)
                $i++;
        }
        if (strlen($tmpstr) < $strlen && $addopt)
            $tmpstr.= "......";
        return $tmpstr;
    }
}

/**
 * 根据小说数据库格式格式化返回小说用于模板显示的状态信息,如是否签约,vip,分类名,更新时间...
 *
 * @param unknown_type $bookinfo
 * @param unknown_type $allclass
 */
function format_bookinfo(&$bookinfo, $allclassinfo = false) {
    $sourceList = C('SOURCELIST');
    if (!$allclassinfo) {
        $allclassinfo = S(C('cache_prefix') . '_CLASS');
    }
    if (isset($allclassinfo[$bookinfo['classid']])) {
        $class                      = $allclassinfo[$bookinfo['classid']];
        $bookinfo['classname']      = $class['title'];
        $bookinfo['smallclassname'] = $class['smalltitle'];
    }
    if ($class && isset($class['subclass'][$bookinfo['classid2']])) {
        $subclass = $class['subclass'][$bookinfo['classid2']];
        if (isset($subclass['title']) && isset($subclass['smalltitle'])) {
            $bookinfo['subclassname']      = $subclass['title'];
            $bookinfo['smallsubclassname'] = $subclass['smalltitle'];
        }
    }
    if ($bookinfo['last_updatetime'] > $bookinfo['last_vipupdatetime'] && $bookinfo['last_updatetime']) {
        $bookinfo['alllast_updatetime'] = date("Y-m-d", $bookinfo['last_updatetime']);
    }
    if ($bookinfo['last_updatetime'] < $bookinfo['last_vipupdatetime'] && $bookinfo['last_vipupdatetime']) {
        $bookinfo['alllast_updatetime'] = date("Y-m-d", $bookinfo['last_vipupdatetime']);
    }
    if (in_array($bookinfo['shouquaninfo'], C('BOOK_VIPBOOK'))) {
        $bookinfo['isvip'] = 1;
    } else {
        $bookinfo['isvip'] = 0;
    }
    if (in_array($bookinfo['shouquaninfo'], C('BOOK_QIANYUEBOOK'))) {
        $bookinfo['isqianyue'] = 1;
    } else {
        $bookinfo['isqianyue'] = 0;
    }
    if ($bookinfo['publishstatus'] == C('BOOK_IS_DELETED')) {
        $bookinfo['is_deleted'] = 1;
    }
    if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
        $bookinfo['cantdisplay'] = 1;
    }
    if ($bookinfo['tags'] != '') {
        $tmpvar = explode(' ', trim($bookinfo['tags']));
        if (is_array($tmpvar) && count($tmpvar) > 0) {
            $bookinfo['tagsary'] = $tmpvar;
        }
    }
    if ($bookinfo['sourceId']) {
        $bookinfo['sourceName'] = $sourceList[$bookinfo['sourceId']]['title'];
    }
    $bookinfo['total_starnum'] = $bookinfo['star1'] + $bookinfo['star2'] + $bookinfo['star3'] + $bookinfo['star4'] + $bookinfo['star5'];
    $bookinfo['sex_flag']      = $bookinfo['classid'] == 2 ? 'nv' : 'nan';
    $bookinfo['intro']         = nl2p($bookinfo['intro']);
    $bookinfo['note']          = nl2p($bookinfo['note']);
    return $bookinfo;
}

function implode_ids($arrays, $keys = null) {
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
 * 获得所有cookie阅读书目
 *
 * @return boolean|unknown
 */
function getcookiefavbooklist() {
    $favcookiearys = getcookiefavary(cookie('favs'));
    if (!is_array($favcookiearys) || count($favcookiearys) <= 0) {
        return false;
    }
    $tmp  = array();
    $ii   = count($favcookiearys);
    $bids = array();
    foreach ($favcookiearys as $v) {
        $tmp[$ii--] = $v;
    }
    ksort($tmp, SORT_DESC);
    foreach ($tmp as $v) {
        $bids[$v['bid']] = array();
    }
    $tmpbids = implode_ids($tmp, 'bid');
    $bModel  = new \Client\Model\BookModel();
    if ($tmpbids != '') {
        $tmpmap = array("bid" => array("IN", $tmpbids));
        $rows   = $bModel->where($tmpmap)->select();
        if ($rows === false) {
            //没有取回值
            return false;
        }
        $lists = $bids;
        foreach ((array) $rows as $tmpvar) {
            $tmpvar['chapterid'] = $favcookiearys[$tmpvar['bid']]['chapterid'];
            $tmpvar['pn']        = $favcookiearys[$tmpvar['bid']]['pn'];
            $tmpvar['chpidx']    = $favcookiearys[$tmpvar['bid']]['chpidx'];
            $bid                 = $tmpvar['bid'];
            $lists[$bid]         = $tmpvar;
            unset($bids[$bid]);
        }
        if ($bids) {
            //把已经不存在的书但是阅读记录里还存在的记录给删除掉
            foreach ($bids as $k => $v) {
                unset($favcookiearys[$k]);
            }
            savecookiefav($favcookiearys);
        }
        return $lists;
    } else {
        return false;
    }
}

/**
 * 获得指定bid的阅读记录
 *
 * @param unknown_type $bid
 */
function getBookCookieFav($bid) {
    $booklist = getcookiefavbooklist();

    if ($booklist && isset($booklist[$bid])) {
        return $booklist[$bid];
    } else {
        return false;
    }
}

/**
 * 输出可在模版中使用的doClient调用需要用到的json字串
 * @param string $command
 * @param array $Ary
 * @return string
 */
function android_output_docommand($command, $Ary) {
    $str = '{"Action":"' . $command . '",';
    foreach ($Ary as $k => $v) {
        $tmp[] = '"' . $k . '":"' . $v . '"';
    }
    $str = $str . implode(',', $tmp) . '}';
    return $str;
}

/**
 * 转换book数据库字段名为客户端字段名,并获取章节数,输出数组
 * @param unknown_type $bookinfo
 * @param unknown_type $chaptercount
 * @return array
 */
function android_convert_bookinfo($bookinfo, $chaptercount = '') {



    $converAry = array('SiteBookID'   => 'bid', 'Name'         => 'catename', 'CategoryName' => 'classname', 'Information'  => 'intro',
        'Author'       => 'author', 'CategoryID'   => 'classid2', 'IsFinished'   => 'lzinfo', 'ChapterCount' => '', 'ImageUrl'     => '');
    $result    = array();

    foreach ($converAry as $k => $v) {
        if (isset($bookinfo[$v]) && !empty($v)) {
            $result[$k] = $bookinfo[$v];
        } else {
            switch ($k) {
                case 'ImageUrl':
                    $result[$k] = ROOT_URL . url('Client/bookajax/getbookcover', array('t' => 'large', 'bid' => $bookinfo['bid']), 'do');
                    break;
                case 'ChapterCount':

                    if (empty($chaptercount) || !$chaptercount) {

                        $bookObj      = new \Client\Model\BookModel();
                        $chapterlist  = $bookObj->getChplistByBid($bookinfo['bid']);
                        //foreach ($chapterlist as $juanorder=>$juan){
                        $chaptercount = count($chapterlist['list']);
                        //}
                    }
                    $result['ChapterCount'] = $chaptercount;
            }
        }
    }

    return $result;
}

/**
 * 转换章节列表字段名为客户端用的字段名
 * @param unknown_type $chapterList 完整的带卷名的三维章节列表数组
 * @param unknown_type $filtr_xianguanjuan 是否过滤作品先关卷
 * @return array
 */
function android_convert_chapterlist($chapterList, $filtr_xianguanjuan = false) {

    $converAry  = array('ChapterName'         => 'title', 'Chapter_ID'          => 'chapterid', 'Category_ID'         => 'juanid', 'IsVipChapter'        => 'isvip', 'BookID'              => 'bid',
        'PreviousChapterId'   => '', 'NextChapterId'       => '', 'Content'             => 'content', 'ChapterCategoryName' => 'juantitle');
    $result     = array();
    $juan_order = 0;
    foreach ($chapterList as $juan) {

        if ($filtr_xianguanjuan && $juan_order == 0 && $juan['juantitle'] == '作品相关') {
            $juan_order++;
            continue;
        }
        $juantitle = $juan['juantitle'];
        $juanid    = $juan['juanid'];
        foreach ($juan['chparys'] as $chapter) {

            if (strtolower($chapter['candisplay']) != 'y') {
                continue;
            }
            $chapter['juanid']    = $juanid;
            $chapter['juantitle'] = $juantitle;
            $tmp[]                = $chapter;
        }
    }

    foreach ($tmp as $num => $chapter) {
        foreach ($converAry as $k => $v) {
            if (!empty($v) && isset($chapter[$v])) {
                $Ary[$k] = $chapter[$v];
            } else {
                switch ($k) {
                    case 'PreviousChapterId':
                        if (isset($tmp[$num - 1])) {
                            $Ary[$k] = $tmp[$num - 1]['chapterid'];
                        } elseif ($num == 0) {
                            $Ary[$k] = 'firstpage';
                        }

                        break;
                    case 'NextChapterId':
                        if (isset($tmp[$num + 1])) {
                            $Ary[$k] = $tmp[$num + 1]['chapterid'];
                        } else {
                            $Ary[$k] = 'lastpage';
                        }
                        break;
                }
            }
        }
        $result[] = $Ary;
        $Ary      = '';
    }
    return $result;
}

/**
 * ios版chapter字段转换成客户端字段数组
 * @param unknown_type $chapterlist所有章节名数组
 * @param unknown_type $src_chapter 需要转换的章节
 * @return array
 */
function ios_convert_chapter($chapterlist, $src_chapter) {
    $converAry = array('ChapterName'         => 'title', 'Chapter_ID'          => 'chapterid', 'Category_ID'         => 'juanid', 'IsVipChapter'        => 'isvip', 'BookID'              => 'bid',
        'PreviousChapterId'   => '', 'NextChapterId'       => '', 'Content'             => 'content', 'ChapterCategoryName' => 'juantitle');
    //dump($chapterlist);
    foreach ($chapterlist as $juan) {
        $juantitle = $juan['juantitle'];
        $juanid    = $juan['juanid'];
        foreach ($juan['chparys'] as $chapter) {
            $chapter['juanid']    = $juanid;
            $chapter['juantitle'] = $juantitle;
            $tmp[]                = $chapter;
        }
    }
    $result = false;
    $Ary    = false;
    //dump($tmp);
    foreach ($tmp as $num => $chapter) {

        if ($src_chapter['chapterid'] != $chapter['chapterid']) {
            continue;
        }
        foreach ($converAry as $k => $v) {

            if (!empty($v) && isset($chapter[$v])) {
                $Ary[$k] = $chapter[$v];
            } else {
                switch ($k) {
                    case 'PreviousChapterId':
                        if (isset($tmp[$num - 1])) {
                            $Ary[$k] = $tmp[$num - 1]['chapterid'];
                        } elseif ($num == 0) {
                            $Ary[$k] = 'firstpage';
                        }

                        break;
                    case 'NextChapterId':
                        if (isset($tmp[$num + 1])) {
                            $Ary[$k] = $tmp[$num + 1]['chapterid'];
                        } else {
                            $Ary[$k] = 'lastpage';
                        }
                        break;
                }
            }
        }

        if ($Ary) {
            $src_chapter['content'] = str_replace("<p>", "", $src_chapter['content']);
            $src_chapter['content'] = str_replace("</p>", "\n", $src_chapter['content']);
            $Ary['Content']         = $src_chapter['content'];
            $result                 = $Ary;
            break;
        }
    }
    return $result;
}

/**
 * 将全部章节数组转换成客户端字段格式
 * @param unknown_type $chapterList
 * @param unknown_type $filtr_xianguanjuan 是否过滤作品相关卷
 * @return array
 */
function ios_convert_chapterlist($chapterList, $filtr_xianguanjuan = false) {


    $converAry  = array('ChapterName'         => 'title', 'Chapter_ID'          => 'chapterid', 'Category_ID'         => 'juanid', 'IsVipChapter'        => 'isvip', 'BookID'              => 'bid',
        'PreviousChapterId'   => '', 'NextChapterId'       => '', 'Content'             => 'content', 'ChapterCategoryName' => 'juantitle');
    $result     = array();
    $juan_order = 0;
    foreach ($chapterList as $juan) {

        if ($filtr_xianguanjuan && $juan_order == 0 && $juan['juantitle'] == '作品相关') {
            $juan_order++;
            continue;
        }
        $juantitle = $juan['juantitle'];
        $juanid    = $juan['juanid'];
        foreach ($juan['chparys'] as $chapter) {
            if (strtolower($chapter['candisplay']) != 'y') {
                continue;
            }
            $chapter['juanid']    = $juanid;
            $chapter['juantitle'] = $juantitle;
            $tmp[]                = $chapter;
        }
    }

    foreach ($tmp as $num => $chapter) {
        foreach ($converAry as $k => $v) {
            if (!empty($v) && isset($chapter[$v])) {
                $Ary[$k] = $chapter[$v];
            } else {
                switch ($k) {
                    case 'PreviousChapterId':
                        if (isset($tmp[$num - 1])) {
                            $Ary[$k] = $tmp[$num - 1]['chapterid'];
                        } elseif ($num == 0) {
                            $Ary[$k] = 'firstpage';
                        }

                        break;
                    case 'NextChapterId':
                        if (isset($tmp[$num + 1])) {
                            $Ary[$k] = $tmp[$num + 1]['chapterid'];
                        } else {
                            $Ary[$k] = 'lastpage';
                        }
                        break;
                }
            }
        }
        $result[] = $Ary;
        $Ary      = '';
    }
    return $result;
}

/**
 * 转换数据库字段名为客户端字段名
 * @param array $bookinfo
 * @param int $chaptercount 是否输出章节总数字段
 * @return array
 */
function ios_convert_bookinfo($bookinfo, $chaptercount = '') {


    $converAry = array('SiteBookID'   => 'bid', 'Name'         => 'catename', 'CategoryName' => 'classname', 'Information'  => 'intro',
        'Author'       => 'author', 'CategoryID'   => 'classid2', 'IsFinished'   => 'lzinfo', 'ChapterCount' => '', 'ImageUrl'     => '','Chpid'=>0);
    $result    = array();

    foreach ($converAry as $k => $v) {
        if (isset($bookinfo[$v]) && !empty($v)) {
            if ($k == "Information") {
                $bookinfo[$v] = addcslashes($bookinfo[$v]);
                $bookinfo[$v] = str_replace(array("<p>", '</p>', '<br/>', '<br>'), array('', "\r\n", '', ''), $bookinfo[$v]);
                $bookinfo[$v] = strip_tags($bookinfo[$v]);
                //$bookinfo[$v]=nl2br($bookinfo[$v]);
                $bookinfo[$v] = str_replace("\r\n", '\n', $bookinfo[$v]);
                $pattern      = '/\s+/'; //去除空白
                $bookinfo[$v] = preg_replace($pattern, '', $bookinfo[$v]);
                $bookinfo[$v] = urlencode($bookinfo[$v]);
                $result[$k]   = $bookinfo[$v];
            } elseif ($k == "Name" || $k == "CategoryName" || $k == "Author") {
                $bookinfo[$v] = strip_tags($bookinfo[$v]);
                $result[$k]   = urlencode($bookinfo[$v]);
            } else {

                $result[$k] = $bookinfo[$v];
            }
        } else {
            switch ($k) {
                case 'ImageUrl':
                    $result[$k] = ROOT_URL . url('Client/bookajax/getbookcover', array('t' => 'large', 'bid' => $bookinfo['bid']), 'do');
                    break;
                case 'ChapterCount':

                    if (empty($chaptercount) || !$chaptercount) {
                        $bookObj = new \Client\Model\BookModel();

                        $chapterlist = $bookObj->getChplistByBid($bookinfo['bid']);
                        //foreach ($chapterlist as $juanorder=>$juan){

                        $chaptercount = count($chapterlist['list']);
                        //}
                    }
                    $result['ChapterCount'] = $chaptercount;
            }
        }
    }

    return $result;
}

/**
 * 获取安卓手机的UUID，设备名称等信息
 * @return array
 */
function android_get_uuid_device() {
    $headers = array();
    foreach ($_SERVER as $h => $v) {
        if (preg_match('@HTTP_(.+)@i', $h, $hp)) {
            $headers[str_replace('_', '-', $hp[1])] = $v;
        }
    }
    $uuid   = false;
    $device = false;
    $tmp    = explode('_', trim($headers['UUID']));
    $uuid   = $tmp[0];
    if ($uuid == 'null') {
        $uuid = I('P29','','trim');
        if(!$uuid){
            $uuid = false;
        }
    }
    $device = $tmp[1];
    if ($device == 'null') {
        $device = false;
    }
    $phone = $tmp[2];
    if ($phone == 'null') {
        $phone = false;
    }
    return array($uuid, $device, $phone);
}

/**
 * interface headers['hs_status']
 * @param unknown_type $msg
 */
function client_output_error($msg) {
    if (!$msg) {
        $msg = 'unkonw!';
    }
    header("hs_status: " . $msg);
    exit();
}

function client_output_json($Ary, $M_jsonp_callback) {
    header('Content-type: application/json;charset=UTF-8');
    if (!empty($M_jsonp_callback)) {
        echo $M_jsonp_callback . '(' . json_encode($Ary) . ');';
        exit;
    } else {
        exit(json_encode($Ary));
    }
}

function xor_encode2($strOld, $strKey) {

    $keyIndex = 0;
    $key_len  = strlen($strKey);
    $str_len  = strlen($strOld);
    $result   = '';
    for ($x = 0; $x < $str_len; $x++) {
        $strOld[$x] = ($strOld[$x] ^ $strKey[$keyIndex]);

        if (++$keyIndex == $key_len) {
            $keyIndex = 0;
        }
    }
    return $strOld;
}

// 建立本地目录
function mDir($dirName) {
    $dirName  = formatPath($dirName);
    $dirNames = explode('/', $dirName);
    $total    = count($dirNames);
    $temp     = '';
    for ($i = 0; $i < $total; $i++) {
        $temp .= $dirNames[$i] . '/';
        if (!is_dir($temp)) {
            $oldmask = umask(0);
            @mkdir($temp, 0777);
            umask($oldmask);
        }
    }
}

//格式化目录路径
function formatPath($path) {
    $path = str_replace("\\", "/", $path);
    // if (substr($path,-1) != "/") $path .= "/";
    return $path;
}

function _write($file_name, $data, $method = "w") { // write to file
    if ($filenum = fopen($file_name, $method)) {
        if (flock($filenum, LOCK_EX)) {
            $file_data = fwrite($filenum, $data);
            if ($file_data === false) {
                flock($filenum, LOCK_UN);
                fclose($filenum);
                return false;
            }
            flock($filenum, LOCK_UN);
            fclose($filenum);

            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * chapter json output
 * @param unknown_type $chapterlist
 * @param unknown_type $src_chapter
 */
function android_convert_chapter($chapterlist, $src_chapter) {
    $converAry = array('ChapterName'         => 'title', 'Chapter_ID'          => 'chapterid', 'Category_ID'         => 'juanid', 'IsVipChapter'        => 'isvip', 'BookID'              => 'bid',
        'PreviousChapterId'   => '', 'NextChapterId'       => '', 'Content'             => 'content', 'ChapterCategoryName' => 'juantitle');
    //dump($chapterlist);
    foreach ($chapterlist as $juan) {
        $juantitle = $juan['juantitle'];
        $juanid    = $juan['juanid'];
        foreach ($juan['chparys'] as $chapter) {
            $chapter['juanid']    = $juanid;
            $chapter['juantitle'] = $juantitle;
            $tmp[]                = $chapter;
        }
    }
    $result = false;
    $Ary    = false;
    //dump($tmp);
    foreach ($tmp as $num => $chapter) {

        if (($src_chapter['chapterid'] != $chapter['chapterid']) || strtoupper($chapter['candisplay']) != 'Y') {
            continue;
        }
        foreach ($converAry as $k => $v) {

            if (!empty($v) && isset($chapter[$v])) {
                $Ary[$k] = $chapter[$v];
            } else {
                switch ($k) {
                    case 'PreviousChapterId':
                        if (isset($tmp[$num - 1])) {
                            $Ary[$k] = $tmp[$num - 1]['chapterid'];
                        } elseif ($num == 0) {
                            $Ary[$k] = 'firstpage';
                        }

                        break;
                    case 'NextChapterId':
                        if (isset($tmp[$num + 1])) {
                            $Ary[$k] = $tmp[$num + 1]['chapterid'];
                        } else {
                            $Ary[$k] = 'lastpage';
                        }
                        break;
                }
            }
        }

        if ($Ary) {
            $Ary['Content'] = $src_chapter['content'];
            $result         = $Ary;
            break;
        }
    }
    return $result;
}

function _process_bangdan($name, $num_show = false, $order = false) {
//     global $M_redis;
//     if (!$M_redis) {
        $M_redis = new \Think\Cache\Driver\Redis();
//     }
    $truebangname = C('cache_prefix', null, 'txtxiaoshuo');
    $truebangname .= '_bang' . $name;

    $bangsetary = $M_redis->get($truebangname);

    if (!$bangsetary) {
        return '';
    }

    if (is_array($bangsetary) && isset($bangsetary['booklists'])) {
        //非3g榜单
        if (!isset($bangsetary['num_show'])) {
            if (!$num_show){// || $num_show >= $bangsetary['maxnum']) {
                return $bangsetary['booklists'];
            } else {

                // 说明:按一定时间间隔滚动调用显示榜单中的书目
                // by fzfz 2014-8-7 下午2:35:20
                if ($bangsetary['isrollshow'] && $bangsetary['rollshowtime'] > 0 && $num_show > 0) {
                    $base_key = ':rollshow_bang:' . $name;

                    $rollinfo = $M_redis->gethash($base_key, array('lastrolltime', 'offset'));
                    if (!$rollinfo || !$rollinfo['lastrolltime']) {
                        list($booklist, $rollinfo['offset']) = bangdan_get_rollbooklist(0, $num_show, $bangsetary['booklists']);
                        $rollinfo['lastrolltime'] = time();
                        $is_locked                = $M_redis->set($base_key . ':lock', 1, 10);

                        if ($is_locked) {//没有其他并发进程在试图滚动
                            $M_redis->sethash($base_key, $rollinfo);
                            $M_redis->rm($base_key . ':lock');
                        }
                    } else {
                        //到刷新时间了
                        if ((time() - $rollinfo['lastrolltime']) > $bangsetary['rollshowtime']) {
                            list($booklist, $rollinfo['offset']) = bangdan_get_rollbooklist($rollinfo['offset'], $num_show, $bangsetary['booklists']);
                            $is_locked = $M_redis->set($base_key . ':lock', 1, 10);

                            if ($is_locked) {//没有其他并发进程在试图滚动
                                $rollinfo['lastrolltime'] = time();
                                $M_redis->sethash($base_key, $rollinfo);
                                $M_redis->rm($base_key . ':lock');
                            }
                        } else {
                            list($booklist, $rollinfo['offset']) = bangdan_get_rollbooklist($rollinfo['offset'], $num_show, $bangsetary['booklists']);
                        }
                    }
                    return $booklist;
                } elseif ($order) {
                    $booklist = array_slice($bangsetary['booklists'], 0, $num_show); //按顺序
                } else {
                    $ary_randomkey = array_rand($bangsetary['booklists'], $num_show);
                    if (is_array($ary_randomkey)) {
                        foreach ($ary_randomkey as $k) {
                            $booklist[$k] = $bangsetary['booklists'][$k];
                        }
                    } else {
                        $booklist[$ary_randomkey] = $bangsetary['booklists'][$ary_randomkey];
                    }
                }
                return $booklist;
            }
        } else {
            //如果模版中定义了显示条数
            if ($num_show && $num_show < $bangsetary['num_save']) {
                $bangsetary['num_show'] = $num_show;
            }
            //dump($bangsetary);
            if ($order) {
                $booklist = array_slice($bangsetary['booklists'], 0, $bangsetary['num_show']); //按顺序
            } else {
                $ary_randomkey = array_rand($bangsetary['booklists'], $bangsetary['num_show']);
                if (is_array($ary_randomkey)) {
                    foreach ($ary_randomkey as $k) {
                        $booklist[$k] = $bangsetary['booklists'][$k];
                    }
                } else {
                    $booklist[$ary_randomkey] = $bangsetary['booklists'][$ary_randomkey];
                }
            }
            return $booklist;
        }
    } elseif (is_string($bangsetary['booklists'])) {
        return $bangsetary['booklists'];
    }
    return '';
}

if (!function_exists('bangdan_get_rollbooklist')) {

    /**
     * 从榜单booklist数组中offset位置开始滚动获取num_show个条目,不足num_show条从数组头部开始,并返回array(booklist,offset)
     * @param unknown_type $offset
     * @param unknown_type $num_show
     * @param unknown_type $booklist
     */
    function bangdan_get_rollbooklist($offset, $num_show, $booklist) {
        $tmp_booklist = array_slice($booklist, $offset, $num_show); //按顺序

        if (count($tmp_booklist) < $num_show) {//数量不足,要从头部取剩余的条数
            $need_buchong_num = $num_show - count($tmp_booklist);
            $buchong_booklist = array_slice($booklist, 0, $need_buchong_num);

            $tmp_booklist = array_merge($tmp_booklist, $buchong_booklist);
        }
        $offset++;
        if ($offset >= count($booklist)) {
            $offset = 0;
        }
        //print_r($tmp_booklist);
        //print_r($offset);

        return array($tmp_booklist, $offset);
    }

}
/**
 * 根据chapterlist打包批量输出章节内容,zip格式,有缓存
 * @param unknown_type $bid
 * @param unknown_type $chapterlist
 * @param unknown_type $onlyfreechapter
 * @return void|number
 */
function ios_output_chapters($bid, &$chapterlist, $onlyfreechapter = true, $flushcache = false, $chporder = 0) {
    $num       = 0;
    $isfindvip = false;
    $chpids    = '';
    foreach ($chapterlist as $juanid => $juan) {
        foreach ($juan['chparys'] as $chapter) {
            if ($chapter['isvip'] && $onlyfreechapter && !$chporder) {
                $isfindvip = true;
                break;
            } elseif ($chporder && $chapter['chporder'] < $chporder) {
                continue;
            }
            //为审核章节不允许下载
            if(!isset($chapter['candisplay']) || strtoupper($chapter['candisplay']) !== 'Y'){
                continue;
            }

            $chpids .=$chapter['chapterid'] . '|';
        }
        if ($isfindvip && $onlyfreechapter) {
            break;
        }
    }

    if (empty($chpids)) {
        client_output_error(C('ERRORS.system') . "infunc");
        return;
    }
    if ($onlyfreechapter) {
        $filename = C('CLIENT.' . CLIENT_NAME . '.downloadtmpdir') . '/iosdwon_onlyfree_' . $bid;
    } else {
        $filename = C('CLIENT.' . CLIENT_NAME . '.downloadtmpdir') . '/iosdown_all_' . $bid;
    }
    mDir(C('CLIENT.' . CLIENT_NAME . '.downloadtmpdir'));

    //取缓存，新版不用$CONFIG['prefix']
    S(C('rdconfig'));
    $book_chpid_md5key = '_iosdown_' . $bid;
    $old_chpids_md5    = S($book_chpid_md5key);


    //print_r($old_chpids_md5);
    //print_r(md5($chpids));
    if (empty($old_chpids_md5) || $old_chpids_md5 != md5($chpids) || $flushcache || !file_exists($filename)) {//如果chpids md5和以前的不对,或以前的没有,说明需要重新创建下载文件
        S($book_chpid_md5key, md5($chpids));
        if (file_exists($filename . '.zip')) {
            @unlink($filename);
        }
        if (file_exists($filename)) {
            @unlink($filename);
        }
        $num       = 0;
        $isfindvip = false;
        foreach ($chapterlist as $juanid => $juan) {

            foreach ($juan['chparys'] as $chapter) {
                if ($chapter['isvip'] && $onlyfreechapter) {
                    $isfindvip = true;
                    break;
                }
                //为审核章节不允许下载
                if(!isset($chapter['candisplay']) || strtoupper($chapter['candisplay']) !== 'Y'){
                    continue;
                }
                $num++;
                if ($num != 1) {
                    _write($filename, '<!!#chapter_split#!!>', "a+");
                }

                $ret = output_chapter($bid, $chapter['chapterid'], $chapter, 1, 'ios');
                _write($filename, $ret, "a+");
            }
            if ($isfindvip && $onlyfreechapter) {
                break;
            }
        }

        //$filename = $filename.'.zip';

        $zip         = new \ZipArchive();
        $zipfilename = $filename . '.zip';

        if ($zip->open($zipfilename, ZipArchive::CREATE) !== TRUE) {
            client_output_error(C('ERRORS.system'));
        }


        $zip->addFile($filename, "/" . $bid . '.txt');

        $zip->close();


        //$data = read($filename);
        //echo strlen($data);
        //exit;
        //$gzdata = gzdeflate($data,5);
        //$gzdata = gzencode($data, 5);
        //_write($filename.'.gz', $data);
    } else {
        header("contentcache: Y");
    }

    //header("idskey: ".md5($chpids));
    $filename = $filename . '.zip';
    if (file_exists($filename)) {
        $fp = fopen($filename, "r+"); //下载文件必须先要将文件打开，写入内存
        if (!$fp) {
            client_output_error(C('ERRORS.system'));
        }
        $file_size = filesize($filename); //判断文件大小
        header('Content-Type: text/gzip'); //纯文本格式
        Header("Content-type: application/octet-stream");
        //按照字节格式返回
        Header("Accept-Ranges: bytes");
        //返回文件大小
        Header("Accept-Length: " . $file_size);
        //弹出客户端对话框，对应的文件名
        Header("Content-Disposition: attachment; filename=" . $bid . '.zip');
        Header("Content-Length: " . $file_size);

        //防止服务器瞬时压力增大，分段读取
        $buffer = 4096;
        while (!feof($fp)) {
            $file_data = fread($fp, $buffer);
            echo $file_data;
            ob_flush();
            flush();
        }
        //关闭文件
        fclose($fp);
    }
    return $num;
}

/**
 * 输出安卓、苹果章节内容实体
 * @param int $bid 书号
 * @param int $chpip 章节id
 * @param array $chapter 单个章节数组
 * @param int $pl 批量输出章节，0：输出单章 1：批量
 * @return array 单章实体json数组
 */
function output_chapter($bid, $chpid, $chapter, $pl = 0) {

    $encode_key = $bid . $chpid;
    $bookModel  = new \Client\Model\BookModel();

    if (!CLIENT_NAME || !in_array(CLIENT_NAME, array('android', 'ios'))) {
        client_output_error(C('ERRORS.system'));
    }

    //拼接缓存文件名
    $dir            = C('CLIENT.' . CLIENT_NAME . '.sht_dir');
    $staticfilename = $dir . '/' . $bookModel->getBookStaticFilepath($bid) . '/' . $chpid . '.sht';
    if (file_exists($staticfilename)) {
        $android_content = read($staticfilename);
    }
    if (!empty($android_content)) {
        $chapter['content'] = $android_content;
        header("contentcache: Y");
    } else {
        $chapter['content'] = $bookModel->getChapterContent($bid, $chpid);
        $chapter['content'] = str_replace(array('<p>', "\r", "\n"), array("", "", ""), $chapter['content']);
        $chapter['content'] = str_replace(array('</p>'), array("\n"), $chapter['content']);
        $chapter['content'] = base64_encode(xor_encode2($chapter['content'], $encode_key));

        mDir($dir . '/' . $bookModel->getBookStaticFilepath($bid) . '/');
        _write($staticfilename, $chapter['content']);
    }
    $chapterlist = $bookModel->getChapter($bid);

    //区分安卓与苹果客户端
    $fun           = CLIENT_NAME . '_convert_chapter';
    $chapterresult = $fun($chapterlist, $chapter);

    if ($pl && $pl == 1) {
        if (!$chapterresult) {
            $chapterresult = array('ChapterName'         => '', 'Chapter_ID'          => $chpid, 'Category_ID'         => '', 'IsVipChapter'        => '', 'BookID'              => $bid,
                'PreviousChapterId'   => '', 'NextChapterId'       => '', 'Content'             => '', 'ChapterCategoryName' => '');
        }
        return json_encode($chapterresult);
    } else {
        if ($chapterresult) {
            client_output_json($chapterresult);
        } else {
            client_output_error(C('ERRORS.system'));
        }
    }
}

function fetchClientHeaders() {
    $headers = array();
    foreach ($_SERVER as $h => $v) {
        if (ereg('HTTP_(.+)', $h, $hp))
            $headers[str_replace('_', '-', $hp[1])] = $v;
    }
    return $headers;
}

/**
 * 检查坏词(客户端自动接口使用)
 * @param string $badwordstr
 * @param string $content
 */
function check_badword(&$badwordstr, &$content) {
    $matches = false;
    if (strlen($badwordstr) > 0) {
        if (preg_match_all('/' . $badwordstr . '/is', $content, $matches)) {
            return $matches[0];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * 获得违禁词缓存,如不存在则从/data/etc/badword_.$badwordtype.'.txt'中读入缓存并返回
 *
 * @param unknown_type $flush
 * @param unknown_type $cachetime
 * @return unknown
 */
function cached_badword($flush = false, $cachetime = 86400, $badwordtype = 'content') {
    $cacheid  = C("cache_prefix") . '_badword' . $badwordtype;
    $badwords = S($cacheid);

    if ($flush || $badwords === false) {
        S($cacheid, null);
        $badwordfile  = read(C('M_ROOT') . '/data/etc/badword/' . $badwordtype . '.txt');
        $badwordfile  = str_replace("\r", "", $badwordfile);
        $badwordarray = explode("\n", $badwordfile);
        $badwords     = implode('|', $badwordarray);
        S($cacheid, $badwords);
    }
    return $badwords;
}

function get_bookface_filepath($bid) {
    $Path = floor($bid / 10000) . '/' . floor(($bid % 10000) / 100);
    return $Path;
}

function get_bookface_filename($bid, $size = 'middle') {
    $size = in_array($size, array('large', 'middle', 'small', 'big', 'bigdft', 'big600x800', 'big218x290')) ? $size : 'middle';
    return $bid . "_{$size}.jpg";
}

/**
 * 根据bid 获得该id的章节内容保存目录名,末尾不带/
 *
 * @param unknown_type $bid
 * @return unknown
 */
function get_book_staticfilepath($bid) {
    $Path = floor($bid / 10000) . '/' . floor(($bid % 10000) / 100) . '/' . $bid;
    return $Path;
}

function do_post_request($url, $postdata = array(), $header = array(), $request_engine = 'curl') {

    if (!function_exists('curl_init')) {
        exit('not find curl');
    }
    $request_engine = 'curl';

    if ($request_engine == 'curl' && function_exists('curl_init')) {


        $header[] = 'Expect:';
        if ($postdata) {
            $postdata = http_build_query($postdata);
            $header[] = 'Content-length: ' . strlen($postdata);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        if ($postdata) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        }
        curl_setopt($curl, CURLOPT_USERAGENT, 'xydqw.com/1.0 (+http://www.hongshu.com/)');
        curl_setopt($curl, CURLOPT_REFERER, 'http://www.hongshu.com');
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, $url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);
    } else {
        if ($postdata) {
            $postdata = http_build_query($postdata);
            $header[] = 'Content-length: ' . strlen($postdata);
        }
        $headers = implode("\r\n", $header);
        $params  = array('http' => array(
                'protocol_version' => '1.1',
                'method'           => 'POST',
                'header'           => $headers,
                'content'          => $postdata,
                'timeout'          => 30,
        ));


        $ctx = stream_context_create($params);

        $response = file_get_contents($url, false, $ctx);
    }
    return $response;
}

/*
  功能：加强版stripslashes。去掉反斜线字符。与new_addslashes过程相反。
  参数：
  $string：要转化的字符串或数组
 */
function stripdslashes($string) {
    if (!is_array($string))
        return stripslashes($string);
    foreach ($string as $key => $val)
        $string[$key] = stripdslashes($val);
    return $string;
}

/**
 * 加强版addslashes。字符串加入斜线处理。可以处理数组。
 *
 * @param unknown_type $string
 * @param unknown_type $force
 * @return unknown
 */
function daddslashes(&$string, $force = 0, $M_magic_quotes_gpc, $M_db) {
    if (!$M_magic_quotes_gpc || $force) {
        if (is_array($string) && count($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = daddslashes($val, $force);
            }
        } elseif (!empty($string)) {
            if ($force) {
                if (is_object($M_db)) {
                    if (!mysql_ping($M_db->link)) {
                        $M_db->reconnect();
                    }
                    return mysql_real_escape_string($string, $M_db->link);
                } else {
                    return addslashes($string);
                }
            } else {
                return addslashes($string);
            }
        } else {
            $string = False;
        }
    }
    return $string;
}

function canTest() {
    return session('priv')!='';
    //return false;
    //session('[start]');
    //if (strpos($_SERVER['HTTP_HOST'], 'hongshutest.com')) {
    //    return true;
    //}
    //$info = dns_get_record('www.hongshutest.com');
    //if ($info[0]['ip']) {
    //    return get_client_ip() == $info[0]['ip'];
    //}
    //return false;
}

/**
 * 向客户端发送命令
 *
 * @param string $action 要执行的命令
 * @param array $param 命令所附带的参数
 * @param boolean $isExit 发送完成后是否终止程序执行
 * @param boolean $output 输出还是返回，默认输出
 */
function doClient($action, $param = array(), $isExit = true, $output = true) {
    if (!is_array($param)) {
        $param = object_to_array(json_decode($param));
    }
    if (!is_array($param)) {
        $param = array();
    }
    $html = '';
    if (CLIENT_NAME === 'android') {
        $param['Action'] = $action;
        $doCommandStr    = str_replace("'", "\\'", json_encode($param));
        $html            = '<script language="javascript">window.HongshuJs.do_command(\'' . $doCommandStr . '\');</script>';
    } else if (CLIENT_NAME === 'ios') {
        if(CLIENT_VERSION >='1.4.3') {
            $route = url($action, $param, 'do');
            $html = '<script language="javascript">window.location.href="objc://open/'.$route.'";</script>';
        } else {
            $doCommandStr = str_replace("'", "\\'", json_encode($param));
            $html         = '<script language="javascript">var params = \'' . $doCommandStr . '\';window.location.href="objc://' . $action . '//"+params;</script>';
        }
    }
    if ($html && $output) {
        //直接输出
        echo $html;
    }elseif ($html){
        //返回
        return $html;    
    }
    
    if ($isExit) {
        exit;
    }
}
/**
 * object 转 array
 */
function object_to_array($obj) {
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val       = (is_array($val)) || is_object($val) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}

function getFreeEndTime() {
    $now  = NOW_TIME;
    $end  = '';
    $wDay = date('N', $now);          //礼拜几
    $hDay = date('H', $now);
    $mDay = date('i', $now);
    //周一10点以前或者周四10点以后
    if ($wDay == 1) {
        if ($hDay < 10 || ($hDay == 10 && $mDay < 30)) {
            $end = date("Y-m-d", $now) . ' 10:30:00';
        } else {
            $end = date("Y-m-d", strtotime(4 - $wDay . " day")) . ' 10:30:00';
        }
    } else if ($wDay == 4) {
        if ($hDay < 10 || ($hDay == 10 && $mDay < 30)) {
            $end = date("Y-m-d", $now) . ' 10:30:00';
        } else {
            $end = date("Y-m-d", strtotime(8 - $wDay . " day")) . ' 10:30:00';
        }
    } else if ($wDay < 4) {
        $end = date("Y-m-d", strtotime(4 - $wDay . " day")) . ' 10:30:00';
    } else {
        $end = date("Y-m-d", strtotime(8 - $wDay . " day")) . ' 10:30:00';
    }
    $date1 = new DateTime(date("Y-m-d H:i:s", $now));
    $date2 = new DateTime($end);
    $diff  = $date1->diff($date2);
    $max   = $diff->d * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;
    return $max;
}

/**
 * 页面未找到等的处理方式，直接输出状态码
 *
 * @param mixed $code
 */
function _exit($msg = '', $code = 404) {
    send_http_status($code);
    @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    @header("Cache-Control: no-cache");
    @header("Pragma: no-cache");
    $file = dirname(__FILE__) . '/404.html';
    if (!$msg) {
        $msg = '抱歉 , 你要找的页面没有找到 , 可能是没有上传 , 也可能是被删除.';
    }
    if (file_exists($file)) {
        $tpl = new \Think\Template();
        $msg = $tpl->fetch($file, array('msg' => $msg));
    }
    exit($msg);
}

/**
 * 随机数组
 * @param type $ary
 * @param type $num
 * @return type
 */
function array_random($ary, $num = 0) {
    if (!is_array($ary) || count($ary) == 1) {
        return $ary;
    }
    if ($num < 1) {
        $num = count($ary);
    }
    do {
        $tmp      = array_rand($ary);
        $result[] = $ary[$tmp];
        unset($ary[$tmp]);
    } while ($ary);
    return $result;
}

/**
 * 关闭页面缓存
 */
function noCachePage() {
    @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    @header("Cache-Control: no-cache");
    @header("Pragma: no-cache");
}

/**
 * 检测用户是否已经登录
 * @return boolean|int 如果已经登录，则返回UID
 */
function isLogin() {
    if (!session('islogin')) {
        return false;
    } else {
        $uid = intval(session('uid'));
        if ($uid < 1) {
            session('islogin', false);
            session('uid', 0);
            return false;
        }
        return $uid;
    }
}

/**
 * 检查小说是否可用
 * @param array $bookinfo 小说信息
 * @param boolean $isExit 是否直接输出状态信息
 * @return boolean|array 可用则返回小说信息，否则返回FALSE
 */
function checkBook($bookinfo = array(), $isExit = true) {
    if (!is_array($bookinfo)) {
        if (is_int($bookinfo)) {
            $bModel   = new \Client\Model\BookModel();
            $bookinfo = $bModel->getBook($bookinfo);
        }
    }
    if (!$bookinfo) {
        if ($isExit) {
            _exit('参数错误！');
        } else {
            return false;
        }
    }
    //不开放
    if ($bookinfo['publishstatus'] != C('BOOK_CANDISPLAY')) {
        if ($isExit) {
            _exit('对不起，本书暂不开放阅读');
        } else {
            return false;
        }
    }
    //删除标志
    if ($bookinfo['publishstatus'] == C('BOOK_IS_DELETED')) {
        if ($isExit) {
            _exit('对不起，此书已被删除');
        } else {
            return false;
        }
    }
    //手机站不可显示的
    if (in_array(CLIENT_NAME, array('html5', 'ios', 'android'))) {
        if ($bookinfo['sourceId'] == 101 || $bookinfo['copyright'] == 1) {
            if ($isExit) {
                _exit('书籍不存在，可能已被删除或还没有上传');
            } else {
                return false;
            }
        }
    }
    return $bookinfo;
}

/**
 * 获取当前站点的一些配置信息，来源于Conf/client.php
 * @param string $key 要获取的配置名称，留空的话返回全部配置
 * @return string|array
 */
function getsiteconfig($key = '') {
    $config = C('CLIENT.' . CLIENT_NAME);
    if ($key && isset($config[$key])) {
        return $config[$key];
    } else {
        return $config;
    }
}

function getThinkPara() {
    return MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
}
/**
 * 将最近阅读的100本书的收藏信息保存到Cookie中，给键名前加一个字母可以避免纯数字的键会被JSON.parse自动排序而造成不能把最后阅读的书籍排在最后。
 * @param type $fav
 */
function saveBookFavCookie($fav, $bid) {
    $bookfav       = json_decode(cookie('bookfav'), true);
    if(!$bookfav) {
        $bookfav = array();
    }
    if(isset($bookfav['b'.$bid])) {
        unset($bookfav['b'.$bid]);
    }
    $bookfav['b'.$bid] = $fav?1:0;
    if(count($bookfav)>100){
        $bookfav = array_slice($bookfav, -100, 100, true);
    }
    cookie('bookfav', json_encode($bookfav), 31536000);
}
function getParamType($name, $type = 'title') {
    $typeMaps = array(
        'string'  => array(
            'title' => '字符串',
            'input' => 'text',
        ),
        'int'     => array(
            'title' => '整型',
            'input' => 'text',
        ),
        'float'   => array(
            'title' => '浮点型',
            'input' => 'text',
        ),
        'boolean' => array(
            'title' => '布尔型',
            'input' => 'text',
        ),
        'date'    => array(
            'title' => '日期',
            'input' => 'text',
        ),
        'array'   => array(
            'title' => '数组',
            'input' => 'textarea',
        ),
        'fixed'   => array(
            'title' => '固定值',
            'input' => 'text',
        ),
        'enum'    => array(
            'title' => '枚举类型',
            'input' => 'text',
        ),
        'object'  => array(
            'title' => '对象',
            'input' => 'text',
        ),
    );
    if (isset($typeMaps[$name])) {
        return $typeMaps[$name][$type];
    } else {
        if ($type != 'title') {
            return 'text';
        }
        return $name;
    }
}
