<?php

namespace Client\Model;
use \HS\Model;

class NewcommentModel extends Model {

    /**
     * 为指定用户送花并发一条书评,更新数据缓存
     *
     * @param int 书号
     * @param string 留言
     * @param int 送花的数量
     * @param array 用户信息
     * @return int
     */
    public function addFlower($bid, $content, $num, $user) {
        if ($content != "") {
            $this->add("", $content, $bid, $user, $num);
        }
        $bookmodel = D('Book');
        $fensimodel = D("Fensi");
        //更新书的鲜花次数（缓存）
        $bookmodel->addFlowerCount($bid, $num);
        //更新书的动态
        $bookmodel->addBooktrend($bid, 1, $user, $num);
        //更新粉丝的积分
        $fensimodel->addFansIntegral($bid, $user["uid"], $num * C("INTEGRAL")["flower"]);
        unset($bookmodel);
        unset($fensimodel);



        $now = time();
        $tomorrow = strtotime(date('Y-m-d',strtotime('+1 day')));
        $expire = $tomorrow - $now;

        $memcache = new \Think\Cache\Driver\Memcache();
        $flowercount = $memcache->get("flowercount".$user["uid"]);
        if ($flowercount) {
            $memcache->set("flowercount".$user["uid"], (intval($flowercount) + intval($num)), $expire);
        }
        else {
            $memcache->set("flowercount".$user["uid"], intval($num), $expire);
        }
        unset($memcache);
        return 1;
    }

    /**
     * 为指定用户送红票并发一条书评,更新数据缓存
     *
     * @param int 书号
     * @param string 留言
     * @param int 送红票的数量
     * @param array 用户信息
     * @return int
     */
    public function addTicket($bid, $content, $num, $user, $addtime, $isdeduct = 0) {
        // 判断是否是黑名单作品
        $xishu = 1.3;
        $map["type"] = 1;
        $map["bk_id"] = $bid;
        $blackcount = M("red_ticket_blacklist")->where($map)->count();
        if($blackcount){
            $xishu = 1;
        }

        $data["user_id"] = $user["uid"];
        $data["user_name"] = $user["username"];
        $data["month"] = date("ym");
        $data["i_date"] = date("Y-m-d", $addtime);
        $data["i_time"] = date("H:i:s", $addtime);
        $data["bk_id"] = $bid;
        $data["num"] = $num * $xishu;
        $data["num_true"] = $num;
        $data["ip"] = get_client_ip();
        $data["type"] = "01";
        $ret = M("red_ticket_ballot")->add($data);
        if($ret > 0){
            //减去用户当月的票
            if ($isdeduct == 0) {
                $map2["user_id"] = $user["uid"];
                $map2["month"] = date("ym");
                $ticketmodel = M("red_ticket_user");
                $ticketmodel->where($map2)->setDec('num', $num);
                unset($ticketmodel);
            }
            if($content){
            //插入评论
                $this->add("", $content, $bid, $user, 0);
            }
            $bookmodel = new BookModel();
            // 增加书的票
            $bookmodel->addticket($bid, $num);

            $fensimodel = D("fensi");
            //更新书的动态
            $bookmodel->addBooktrend($bid, 8, $user, $num);
            //更新粉丝的积分
            $fensimodel->addFansIntegral($bid, $user["uid"], $num * C("INTEGRAL")["ticket"]);

            unset($bookmodel);
            unset($fensimodel);
        }
        return $ret;
    }

    /**
     * 检测违禁词，并插入违禁词
     * @param string $title 标题
     * @param string $content 内容
     * @param int $bid 书号
     * @param int $commentId 书评id
     * @param int $replyId 回复id
     * @param int $replyChildId 楼中楼
     * @param int $level 检测等级 1：只对比 2：匹配正则表达式
     * @return int
     */
    function addCommentBreakWord($title, $content, $bid, $commentId = 0, $replyId=0, $replyChildId=0, $level = 1){
        if($level == 1){
            $breakWord = $this->getBreakWord($title.$content);
        }
        else{
            $breakWord = $this->getBreakWordR($title.$content);
        }
        $data['comment_id'] = $commentId;
        $data['reply_id'] = $replyId;
        $data['reply_reply_id'] = $replyChildId;
        $data['bk_id'] = $bid;
        if($breakWord){
            $data["word_con_txt"] = $breakWord;
            $data["word_con_num"] = count(explode("|",$breakWord));
        }
        $data['content'] = addslashes($content);
        $data["i_time"] = date("Y-m-d H:i:s",time());
        $ret = M("newcomment_updatelogs")->add($data);
        return $ret;
    }

    /**
     * 检测违禁词，不进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWord($text){
//         parent::initMemcache();
//         S('rdconfig');
//         $words = S("breakword_comment");
        $cache = new \HS\MemcacheRedis();
        $words = $cache->getRedis("breakword_comment");
        $words = explode("|",$words);
        $breakWord ='';
        foreach($words as $word){
            if(strpos($text, $word) !== false){
                $breakWord .= $word."|";
            }
        }
        if($breakWord){
            $breakWord = mb_substr($breakWord, 0, mb_strlen($breakWord, "utf-8") - 1, "utf-8");
        }
        return $breakWord;
    }

    /**
     * 检测违禁词，进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWordS($text){
//         parent::initMemcache();
//         S('rdconfig');
//         $words = S("breakword_comment2");
        $cache = new \Think\Cache\Driver\Redis();
        $words = $cache->get('breakword_comment2');
        $words = explode("|",$words);
        $breakWord = '';
        foreach ($words as $word){
            $matches = false;
            preg_match_all($word, $text, $matches);
            if($matches){
                $breakWord .= $matches[0]."|";
            }
        }
        if($breakWord){
            $breakWord = mb_substr($breakWord, 0, mb_strlen($breakWord, "utf-8") - 1, "utf-8");
        }
        return $breakWord;
    }

    /**
     * 检测违禁词，进行违禁词完全对比后，再进行正则匹配
     *
     * @param string 要检测的内容
     * @return string
     */
    function getBreakWordR($content){
        $breakWord = $this->getBreakWord(addslashes($content));
        $breakWordS = $this->getBreakWordS(addslashes($content));
        $breakWordR = "";
        if($breakWord) {
            $breakWordR = $breakWord;
            if($breakWordS){
                $breakWordR .= "|".$breakWordS;
            }
        }
        else{
            $breakWordR = $breakWordS;
        }
        return $breakWordR;
    }

    /**
     * 添加书评
     *
     * @param string $title 标题
     * @param string $content 内容
     * @param int $bid 书号
     * @param array $user 用户信息数组
     * @param int $flowers 鲜花数
     * @param int $lcomment 是否长评 0：不是 1：是
     * @param int $chapterid 章节id
     * @param string $chaptername 章节名
     * @param int $is_redtitle 是否红色标题
     * @param int $forbidden_flag 是否审核
     * @return int
     */
    public function add($title, $content, $bid, $user, $flowers, $lcomment = 0, $chapterid = 0, $chaptername = 0, $is_redtitle = 0, $forbidden_flag = 1) {
        $bookmodel = M("book");
        if(preg_match_all('/\《[^《^》]+\》/iu',$content, $catename)){
            foreach($catename[0] as $k => $v){
                $v = str_replace(array("《","》"), array("", ""), $v);
                $map["catename"] = $v;
                $info = $bookmodel->where($map)->field("bid")->find();
                if($info['bid']){
                    $v = stripslashes($v);
                    $content = str_replace('《'.$v.'》','<a href="'.url('Book/view', array('bid'=>$info['bid'])).'" target="_blank">《'.$v.'》</a>',$content);
                }
            }
        }
        unset($bookmodel);
        $charnum = 0;
        $data["title"] = $title;
        if($content){
            $charnum = mb_strlen($content, 'utf-8');
        }
        if($lcomment == 1){
            if($charnum <= 300){
                $lcomment = 0;
            }
        }
        $commentmodel = M("newcomment");
        $data["content"] = $content;
        $data["charnum"] = $charnum;
        $data["uid"] = $user["uid"];
        $data["bid"] = $bid;
        $data["nickname"] = $user["nickname"];
        $data["username"] = $user["username"];
        $data["highlight_flag"] = 0;
        $data["is_locked"] = 0;
        $data["deleted_flag"] = 0;
        $data["forbidden_flag"] = $forbidden_flag;
        $data["is_lcomment"] = $lcomment;
        $data["is_redtitle"] = $is_redtitle;
        $data["flowers"] = $flowers;
        $data["support"] = 0;
        $data["creation_date"] = time();
        $data["star"] = 0;
        $data["reply_amount"] = 0;
        $data["last_reply_date"] = time();
        $data["chapterid"] = $chapterid;
        $data["chaptername"] = $chaptername;
        $ret = $commentmodel->add($data);
        if($ret > 0){
            //加积分
            $todaybegin = strtotime(date('Y-m-d 00:00:00'));
            $cmap['uid']=$user['uid'];
            $cmap['creation_date'] = array('egt',$todaybegin);
            $totalsendcount = $commentmodel->where($cmap)->count();
            if ($totalsendcount == 1) {
                $fensimodel = D("Fensi");
                $fensimodel->addFansIntegral($bid, $user["uid"], C("INTEGRAL")["comment"]);
                unset($fensimodel);
            }
            //检索留言的违禁词
            $this->addCommentBreakWord($title, $content, $bid, $ret, 0, 0);
            //更新书的动态
            $bookmodel = new BookModel();
            $bookmodel->addBooktrend($bid, 3, $user, 0);
            unset($bookmodel);
        }
        unset($commentmodel);
        return $ret;
    }

    /**
     * 添加书评
     *
     * @param string $title 标题
     * @param string $content 内容
     * @param int $bid 书号
     * @param array $user 用户信息数组
     * @param int $flowers 鲜花数
     * @param int $lcomment 是否长评 0：不是 1：是
     * @param int $chapterid 章节id
     * @param string $chaptername 章节名
     * @param int $is_redtitle 是否红色标题
     * @param int $forbidden_flag 是否审核
     * @return int
     */
    public function addPro($bid, $num, $price, $pid, $sexflag, $user, $addtime, $money = 0, $egold = 0){
        $data['bid'] = intval($bid);
        $data['num'] = intval($num);
        $data['pid'] = intval($pid);
        $data['uid'] = intval($user["uid"]);
        $data['price'] = intval($price);
        $data['sex_flag'] = intval($sexflag);
        $data['addtime'] = $addtime;
        $data['money'] = floatval($money);
        $data['egold'] = floatval($egold);
        $promodel = M("book_pro");
        $ret = $promodel->add($data);
        if($ret > 0){
            //更新书的属性
            $map["bid"] = $bid;
            M("book")->where($map)->setInc("total_pro", $num);
            M("book")->where($map)->setInc("month_pro", $num);
            M("book")->where($map)->setInc("week_pro", $num);
            //更新本书动态
            $bookmodel = new BookModel();
            $bookPro = $bookmodel->getProInfo($pid);
            $bookmodel->addBooktrend($bid, 7, $user, $num, $bookPro['name']);
            unset($bookmodel);
        }
        unset($promodel);
        return $ret;
    }

    /**
     * 赞
     *
     * @param int $commentId 书评id
     * @param int $uid
     * @return string
     */
    public function addZan($commentId,$uid) {
        parent::initMemcache();
        $lastZanTime = S("lastZanTime#".$commentId."#".get_client_ip());
        if($lastZanTime){
            return 'zanfail';
        }
        $map['comment_id'] = $commentId;
        $commentmodel = M("newcomment");
        $ret = $commentmodel->where($map)->setInc("zan_amount", 1);
        unset($commentmodel);
        if($ret){
            S("lastZanTime#".$commentId."#".get_client_ip(), time(), C('ALLOWZAN'));
            //写赞记录
            if($uid){
                $zanModel = new \Client\Model\ZanLogsModel();
                $zanModel->InsertLogByCid($commentId, $uid);
            }
            return 'zansuc';
        }
        else{
            return 'zanfail';
        }
    }

    /**
     * 添加回复
     *
     * @param int $commentid 书评id
     * @param int $bid 书号
     * @param string $content 内容
     * @param array $user 用户信息数组
     * @return int
     */
    public function addreply($commentid, $bid, $content, $user){
        $bookmodel = new BookModel();
        if(preg_match_all('/\《[^《^》]+\》/iu',$content, $catename)){
            foreach($catename[0] as $k => $v){
                $v = str_replace(array("《","》"), array("", ""), $v);
                $map["catename"] = $v;
                $info = $bookmodel->where($map)->field("bid")->find();
                if($info['bid']){
                    $v = stripslashes($v);
                    $content = str_replace('《'.$v.'》','<a href="'.url('Book/view', array('bid'=>$info['bid'])).'" target="_blank">《'.$v.'》</a>',$content);
                }
            }
        }

        $data["comment_id"] = $commentid;
        $data["bid"] = $bid;
        $data["content"] = $content;
        $data["uid"] = $user['uid'];
        $data["username"] = $user['username'];
        $data["nickname"] = $user['nickname'];
        $data["forbidden_flag"] = 1;
        $data["delete_flag"] = 0;
        $data["reply_amount"] = 0;
        $data["creation_date"] = time();
        $ret = M("newcomment_reply")->add($data);
        if($ret > 0) {
            //评论的回复数加一
            $map["comment_id"] = $commentid;
            M("newcomment")->where($map)->setInc("reply_amount");
            //找出违禁词
            $this->addCommentBreakWord($title, $content, $bid, $commentid, $ret, 0);
            //更新书的动态
            $bookmodel->addBooktrend($bid, 4, $user, 0);
        }
        unset($bookmodel);
        return $ret;
    }

    //书评设置key
    function get_comment_set_rediskey($bid) {
        return C('cache_prefix') . ':comset:' . $bid;
    }

    function getCommentByBid($bid) {
        return $this->get_comment_set_cache($bid);
    }
    //书评缓存设置
    function get_comment_set_cache($bid) {
        $key = $this->get_comment_set_rediskey($bid);
        $redis = new \Think\Cache\Driver\Redis();
        $ret = $redis->gethash($key, array('totalnum', 'banzhu', 'fubanzhu', 'keyong_jh_num'));
        return $ret;
    }

    //根据数据库重建缓存设置
    function flush_comment_set($bid) {
        $key = $this->get_comment_set_rediskey($bid);
        $map['bid'] = $bid;
        $setinfo = M('newcomment_set')->where($map)->field('totalnum,keyong_jh_num')->find();
        if($setinfo['totalnum']<1) {
            $nmap = array(
                'bid'=>$bid,
                'deleted_flag'=>array('neq',1),
                'content'=>array('neq',''),
            );
            $setinfo['totalnum'] = M('Newcomment')->where($nmap)->count();
            M('newcomment_set')->where($map)->token(false)->save($setinfo);
        }
        $map = array();
        $map['bid'] = $bid;
        $map['sq_status'] = 3;
        $banzh_tmp = M('newcomment_banzhushenqing')->where($map)->select();
        $banzh = array();
        foreach ($banzh_tmp as $k => $val) {
            $banzh[$val['uid']] = $val;
        }

        foreach ($banzh as $uid => $bz) {
            if ($bz['sq_type'] <= 1) {
                $setinfo['banzhu'][$uid] = $bz;
            } else {
                $setinfo['fubanzhu'][$uid] = $bz;
            }
        }
        $setinfo['banzhu'] = json_encode($setinfo['banzhu']);
        $setinfo['fubanzhu'] = json_encode($setinfo['fubanzhu']);
        $redis = new \Think\Cache\Driver\Redis();
        $redis->rm($key);
        $ret = $redis->sethash($key, $setinfo);
        $ret = $redis->sethash("commentset#" . $bid, $setinfo);
        return $ret;
    }
    /**
     * 获取某个用户的评论
     * @param int $uid
     * @param int $limit (=100则不限制条数)   查询的条数
     * @param array $terms  查询条件
     * @param string $sort  排序条件
     */
    public function getCommentByUid($uid,$limit=5,$sort='',$terms=array()){
        if(!$uid){
            return false;
        }
        if(!intval($limit)){
            $limit = 5;
        }
        $where = array();
        if($terms){
            $where = $terms;
        }else{
            $where = array(
                'uid'=>$uid,
                'deleted_flag'=>0,
                'content'=>array('neq',''),
            );
        }
        if(!$sort){
            $sort = 'creation_date DESC';
        }
        if($limit == 100){
            $list = $this->where($where)->select();
        }else{
            $list = $this->where($where)->order($sort)->limit($limit)->select();
        }
        return $list;
    }
    /**
     * 检测用户对某本书是否禁言
     * @param int $bid
     * @param int $uid
     * 
     * @return boolean
     *  true = 用户被禁言
     *  false = 未被禁言
     */
    public function checkKillUser($bid,$uid){
        $killModel = M('NewcommentKilluser');
        $map = array(
            'bid' => $bid,
            'uid' => $uid
        );
        $res = $killModel->where($map)->find();
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 检测用户是否已申请助理
     * @param array $map
     * 
     * @return false|array
     */
    public function getBanzhuInfo($map){
        $banzhuModel = M('NewcommentBanzhushenqing');
        $res = $banzhuModel->where($map)->find();
        if($res && is_array($res)){
            return $res;
        }else{
            return false;
        }
    }
    /**
     * 申请版主
     * @param int $uid
     * @param int $bid
     * @param int $type(1=版主，2=副版主)
     */
    public function addBanzhu($bid,$uid,$type){
        $data = array();
        $data['bid'] = $bid;
        $data['uid'] = $uid;
        $data['sq_time'] = NOW_TIME;
        $data['sq_type'] = $type;
        $data['sq_status'] = 1;
        $banzhuModel = M('NewcommentBanzhushenqing');
        $res = $banzhuModel->add($data);
    }
    /**
     * 审核版主
     * @param array $where 条件
     * @param array $data 更新的数据
     */
    public function updateBanzhu($where,$data){
        $banzhuModel = M('NewcommentBanzhushenqing');
        $res = $banzhuModel->where($where)->save($data);
        return $res;
    }
    
}
