<?php
/**
 * 微信公众号开发
 * 测试用账号信息：
 * AppID(应用ID):wxc6e062e9f9ee9315
 * AppSecret(应用密钥):dc622b2c52412e2f3ecf648e9260d22b
 *
 * 正式账号信息：
 * AppID(应用ID):wx6c945733843fd13f
 * AppSecret(应用密钥):b0f0c9756f8aa7341d7df03616302fd4
 *
 */

namespace Client\Controller;

use Com\Wechat;
use Com\WechatAuth;

class HomeController extends \Think\Controller {
    public static $subscriptmsg = '';
    public static $defaultmsg   = '';
    var $wid                 = 4;
    var $data                = '';

    /**
     * 响应用户的请求
     * @return string
     */
    public function WechatAction() {
        self::$subscriptmsg = "言情故事，少女心房\r\n" .
            "感谢亲的关注，情感路上，我们一路同行\r\n\r\n" .
            "↓ ↓ ↓\r\n\r\n" .
            "<a href=\"http://m.yanqingkong.com/gift.html\">▲点我领红包，领到你手软！</a> ";
        self::$defaultmsg   = "【颜值/代金券问题】回复 1\r\n" .
            "【阅读问题】回复 2\r\n" .
            "【充值问题】回复 3\r\n" .
            "【其他问题】回复 0\r\n\r\n" .
            "↓ ↓ ↓\r\n" .
            "<a href=\"http://m.yanqingkong.com/gift.html\">▲点我领红包，领到你手软！</a> ";
        $data               = array();
        S(C('rdconfig'));
        //这里要根据来的ID判断一下是哪个站地
        $lModel             = D('WechatList');
        $wid                = defined('wid') ? constant('wid') : I('wid', '0', 'intval');
        $appid              = defined('appid') ? constant('appid') : I('appid', '', 'trim');
        $wInfo              = '';
        if ($appid) {
            $wInfo = $lModel->where(array('appid' => $appid))->find();
        } else if ($wid) {
            $wInfo = $lModel->find($wid);
        } else {
            $wInfo = $lModel->find($this->wid);
        }
        $oWechat = new Wechat($wInfo);
        try {
            $istest = false;
            //添加一段可以自己在线测试的代码
            if (I('test') == 'hoping') {
                C('APP_DEBUG', true);
                C('TMPL_ACTION_ERROR', realpath(dirname(COMMON_PATH . '/../Activity/dispatch_jump.tpl')) . '/dispatch_jump.tpl');
                $type = I('key');
                if ($type == 'test') {
                    echo 'l';
                    dump(C('WECHAT_CONFIG'));
                    echo (C('WECHAT_CONFIG.TOKEN'));
                    pre($oWechat);
                    exit;
                } else {
                    $istest          = true;
                    $data['MsgType'] = I('type', Wechat::MSG_TYPE_TEXT, 'trim');
                    $data['Event']   = I('event', '', 'trim');
                    $data['Content'] = $type;
                }
            } else {
                /* 获取请求信息 */
                $data = $oWechat->request();
            }
            $this->data = $data;
            if (!$wInfo) {
                $org_id = $this->data['ToUserName'];
                $where  = array('org_id' => $org_id);
                $wInfo  = $lModel->where($where)->find();
            }
            if ($wInfo) {
                //$tmp                     = $wInfo;
                //unset($tmp['subscriptmsg'], $tmp['defaultmsg']);
                //\Think\Log::write(print_r($tmp, 1), 'DATA', '', LOG_PATH . 'WECHAT_INFO');
                $wid                     = $wInfo['id'];
                $this->wid               = $wid;                          //保存一下以供后面的过程调用
                $oWechat->appId          = $wInfo['appid'];
                $oWechat->token          = $wInfo['token'];
                $oWechat->appSecret      = $wInfo['appsecret'];
                $oWechat->encodingAESKey = $wInfo['aeskey'];
                $data['wechat_title']    = $wInfo['title'];
                self::$subscriptmsg      = $wInfo['subscriptmsg'];
                self::$defaultmsg        = $wInfo['defaultmsg'];
            } else {
                //微信给的ID不对，而且也没有指定合适的wid(站内公众号ID)，记录一下
                //\Think\Log::write(print_r($data, 1), 'ERROR', '', LOG_PATH . 'WECHAT_QUERY_OTHER');
                $oWechat->replyText(self::$defaultmsg);
                exit;
            }
            if ($istest) {
                pre($wInfo);
            }
            switch ($data['MsgType']) {
                case Wechat::MSG_TYPE_EVENT:
                    switch ($data['Event']) {
                        case Wechat::MSG_EVENT_SUBSCRIBE:
                            //开始关注
                            //\Think\Log::write(print_r($data, 1), 'SUBSCRIBE', '', LOG_PATH . 'WECHAT_SUBSCRIBE');
                            //获取用户信息
                            $openid  = $data['FromUserName'];
                            $usr     = $this->getWechatUserInfo($openid, $wInfo);
                            //\Think\Log::write(print_r($usr, 1), 'USERINFO', '', LOG_PATH . 'WECHAT_SUBSCRIBE');
                            //{"subscribe":1,"openid":"oBWf-tm5qFLKf78j_x3lDIfSdxqs","nickname":"\u82b1\u706b","sex":2,"language":"zh_CN","city":"","province":"\u57fa\u5c14\u4ee3\u5c14","country":"\u7231\u5c14\u5170","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/VxQSbMlHVUsgpL7S27pEToTJXC1YAqoWejLtD9nS5qgjD60d3ZE8bokoeyzlW2PHp4Qxj3NfatucqDCUVLITTQ\/0","subscribe_time":1463037288,"unionid":"oNHSowfDQ-kkY61k5z2hpYDYr8SM","remark":"","groupid":0,"tagid_list":[]}
                            $wuModel = D('WechatUsers');

                            $data        = $usr;
                            $data['wid'] = $wid;
                            unset($data['language']);
                            $wuModel->add($data, array(), true);


                            $oWechat->replyText(self::$subscriptmsg);
                            break;
                        case Wechat::MSG_EVENT_UNSUBSCRIBE:
                            //取消关注，记录日志
                            $openid  = $data['FromUserName'];
                            $map     = array(
                                'wid'    => $wid,
                                'openid' => $openid
                            );
                            //取消关注状态
                            $data    = array(
                                'subscribe' => 0
                            );
                            $wuModel = D('WechatUsers');
                            $wuModel->where($map)->save($data);
                            break;
                        case 'MASSSENDJOBFINISH':           //微信群发消息的回调接口
                            $msg_id  = $this->data['MsgID'];
                            $status  = $this->data['Status'] == 'send success' ? 2 : 1;
                            $total   = $this->data['TotalCount'];
                            $filter  = $this->data['FilterCount'];
                            $sent    = $this->data['SentCount'];
                            $error   = $this->data['ErrorCount'];
                            if ($wid) {
                                $mModel            = D('WechatMass');
                                $where             = array(
                                    'msg_id' => $msg_id,
                                    'wid'    => $wid
                                );
                                $row               = $mModel->where($where)->find();
                                $row['returntime'] = date('Y-m-d H:i:s', NOW_TIME);                 //微信回调时间
                                $row['status']     = $status;
                                $row['total']      = $total;
                                $row['filter']     = $filter;
                                $row['sent']       = $sent;
                                $row['error']      = $error;
                                $mModel->token(false)->save($row);
                                exit('success');
                            } else {
                                exit('failed');
                            }
                            //群发完成的消息回发
                            break;
                        case Wechat::MSG_EVENT_VIEW:
                            if ($wid == 4 && $data['EventKey'] === 'http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=MzIzODAxOTgwMg==#wechat_webview_type=1&wechat_redirect') {
                                $result = "test";
                            }
                            break;
                        case Wechat::MSG_EVENT_CLICK:
                            $ek = strtolower($data['EventKey']);
                            if (substr($ek, 0, 5) == 'last_') {
                                $ek = substr($ek, 5);
                            }
                            switch ($ek) {
                                case 'nan':
                                case 'nv':
                                case 'vipnan':
                                case 'vipnv':
                                case 'vip':
                                case 'all':
                                    $result = $this->getNewBooks($ek);
                                    break;
                                case 'hot':
                                    //$result = $this->getHotBooks();
                                    $result = $this->getNewBooks();
                                    break;
                                case 'my_read':
                                    $result = $this->getLastRead();
                                    break;
                                case 'my_info':
                                    $result = $this->getUserInfo();
                                    break;
                                default:
                                    $result = "[hot]欢迎访问{$wInfo['title']}！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}";
                                    break;
                            }
                            if (is_string($result)) {
                                $oWechat->replyText($result);
                            } else {
                                switch ($result['type']) {
                                    case 'news':
                                        $oWechat->response($result['data'], Wechat::MSG_TYPE_NEWS);
                                        break;
                                    default:
                                        $oWechat->replyText($result['data']);
                                }
                            }

                        default:
                            //未知的微信事件请求
                            $data['wid'] = $wid;
                            //\Think\Log::write(print_r($data, 1), 'ERROR', '', LOG_PATH . 'WECHAT_QUERY_EVENT');
                            $oWechat->replyText(self::$defaultmsg);
                            break;
                    }
                    break;

                case Wechat::MSG_TYPE_TEXT:
                    $result = $this->dealText($data['Content']);
                    if (is_string($result)) {
                        $oWechat->replyText($result);
                    } else {
                        switch ($result['type']) {
                            case 'news':
                                $oWechat->response($result['data'], Wechat::MSG_TYPE_NEWS);
                                break;
                            case 'mpnews':
                                $nModel = D('WechatNews');
                                $where  = array('media_id' => $result['media_id']);
                                $news   = $nModel->where($where)->order('id ASC')->select();
                                $data   = array();
                                foreach ((array) $news as $row) {
                                    $v                = array();
                                    $v['_Id']         = $row['id'];
                                    $v['Title']       = $row['title'];
                                    $v['Description'] = $row['digest'];
                                    $v['Url']         = $row['url'];
                                    $v['PicUrl']      = 'http:' . str_replace('http:', '', $row['thumb_url']);
                                    $data[]           = $v;
                                }

                                $oWechat->response($data, Wechat::MSG_TYPE_NEWS);
                                break;
                            case 'image':
                                $oWechat->replyImage($result['media_id']);
                                break;
                            default:
                                $oWechat->replyText($result['data']);
                        }
                    }
                    break;
                default:
                    //未知的微信请求
                    \Think\Log::write(print_r($data, 1), 'ERROR', '', LOG_PATH . 'WECHAT_QUERY_OTHER');
                    $oWechat->replyText(self::$defaultmsg);
                    break;
            }
        }
        catch (\Exception $e) {
            //记录一下错误信息
            \Think\Log::write(print_r(array('Exception'=>$e->getMessage(), '_GET'=>$_GET, 'data'=>$data, 'winfo'=>$wInfo), 1), 'ERROR', '', LOG_PATH . 'WECHAT_SERVICE_ERROR');
            //友好一点，虽然系统出错但是也给用户回复一点东西
            $oWechat->replyText(self::$defaultmsg);
        }
    }

    protected function getUserInfo() {
        $uModel = D('User');
        if ($this->data['ToUserName'] == 'gh_e2b91b419e8a') {
            //正在使用测试账号
            return '你正在关注的是我们的测试帐号，所以没法返回你的帐号信息[偷笑]' . "\r\n";
        }

        $username = $this->data['FromUserName'];
        $where    = array(
            //'oLogin' => 4,
            'openid' => $username
        );
        $info     = $uModel->where($where)->find();
        $wuModel  = D('WechatUsers');
        unset($where['oLogin']);
        $rows     = array();
        $row      = $wuModel->where($where)->find();

        if ($row) {
            $where = array(
                'unionid' => $row['unionid'],
                'openid'  => array('neq', $username)
            );
            $lists = $wuModel->where($where)->getField('openid', true);
            //return print_r($lists, 1);
            if ($lists) {
                array_walk_recursive($lists, function(&$v) {
                    $v = "openid='" . $v . "' ";
                });
                $where = implode(' OR ', $lists);
                $where = str_replace('openid=\'\'  OR ', '', $where);
                $rows  = $uModel->where($where)->select();
            }
        }
        $result = '';
        if ($info === false) {
            return $uModel->getError();
        } else if (!$info && !$rows) {
            return '对不起，我暂时找不到关于你的任何信息！';
        }
        if (!$info) {
            $info = $rows[0];
            unset($rows[0]);
        }
        $info['regdate']   = date('Y-m-d H:i:s', $info['regdate']);
        $info['lastlogin'] = date('Y-m-d H:i:s', $info['lastlogin']);
        $data              = array(
            '用户ID'   => 'uid',
            '昵称'     => 'nickname',
            '注册时间'   => 'regdate',
            '积分'     => 'credit',
            '最后登录时间' => 'lastlogin',
            '登录次数'   => 'loginnum',
            '微信ID'   => 'openid'
        );
        foreach ($data as $k => $v) {
            $result.=$k . ':' . $info[$v] . "\r\n";
        }
        if ($rows) {
            foreach ($rows as $info) {
                $result .= str_repeat('=', 20) . "\r\n其它账号信息：\r\n";
                foreach ($data as $k => $v) {
                    $result.=$k . ':' . $info[$v] . "\r\n";
                }
            }
        }
        $result .= '更多信息请进入<a href="' . C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '">' . C('SITECONFIG.SITE_NAME') . '</a>进行查看!';
        return $result;
    }

    protected function dealText($str) {
        $result = $this->_dealText($str);
        if (is_string($result)) {
            $tmp = explode('|', $result);
            if (count($tmp) == 1) {
                return $result;
            }
            $type = $tmp[0];
            if ($type == 'mpnews' || $type == 'image') {
                $result = array('type' => $type, 'media_id' => $tmp[1]);
            } elseif ($type == 'func') {
                $func = trim($tmp[1]);
                $last = substr($func, -1);
                $fix  = '';
                if ($last != ';') {
                    if ($last != ')') {
                        $fix = '();';
                    } else {
                        $fix = ';';
                    }
                }
                $func .= $fix;
                $result = eval('return ' . $func);
                return $result;
            }
        }
        return $result;
    }

    private function _findKeyword($where = array()) {
        $kModel = D('WechatAutoKeywords');
        $rModel = D('WechatAutoReply');
        $result = $kModel->where($where)->select();
        if ($result) {
            $key    = array_rand($result);          //多条的话随机取一条
            $rid    = $result[$key]['rid'];
            $where  = array(
                'rid' => array('eq', $rid)
            );
            $result = $rModel->where($where)->select();         //同一个关键词可以有多条回复内容
            if ($result) {
                $key = array_rand($result);                      //随机的取一条回复内容
                return $result[$key]['content'];
            }
        }
        return false;
    }

    /**
     * 处理用户发过来的文本消息，对其识别并返回相应的结果
     */
    protected function _dealText($str) {
        $str = trim(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $str));       //清除utf8mb4字符
        $str = strtolower(trim($str));
        $str = str_replace("'", '', $str);
        if (!$str && $str!='0') {
            $result = self::$defaultmsg;
            return $result;
        }
        // 从数据库中的关键字自动回复里查找
        // 先查绝对相对地
        $where  = array(
            'keyword'    => array('eq', $str),
            'match_type' => array('eq', 1),
            'wid'        => array('eq', $this->wid)
        );
        $result = $this->_findKeyword($where);
        if ($result) {
            return $result;
        }
        // 模糊查询 以数据库中的关键字来匹配用户输入的内容
        $where  = array(
            "instr('$str', keyword)",
            'match_type' => array('eq', '0'),
            'wid'        => array('eq', $this->wid)
        );
        $result = $this->_findKeyword($where);
        if ($result) {
            return $result;
        }
        if (intval($str) && intval($str) == $str) {
            $bid = intval($str);
            return $this->getBook($bid);
        } else {
            $func = 'get' . $str;
            if (method_exists($this, $func)) {
                return $this->$func();
            }
        }

        /**
         * 可识别的命令数组（match分为any和all，如果为all的话，就必须整体匹配，否则就只需要匹配用户输入的部分字符）
         * match:匹配方式
         *      any:对用户输入的字符进行模糊匹配，比如用户输入"我要看新书"，那么，就会匹配到“新书”这条命令
         *      all:必须完全匹配
         * answer:对用户的回应
         *      可以直接返回文字或者要执行的函数，比如：
         *      'func|$this->getHotBooks'：则表示要返回$this->getHotBooks()函数的处理结果
         * alias:别外列表
         */
        $answer = array(
            'nvfuli'    => array(
                'match'  => 'all',
                'answer' => 'mpnews|iTLz1WuVdqvBB35JA8LaueMqCwyU8okCGPxUyYxbk-A',
                'alias'  => array('女生福利')
            ),
            'nanfuli'   => array(
                'match'  => 'all',
                'answer' => 'mpnews|iTLz1WuVdqvBB35JA8Lauf-DAts3SwgSA-mhktHu7iY',
                'alias'  => array('男生福利')
            ),
            'weixinqun' => array(
                'match'  => 'all',
                'answer' => 'image|iTLz1WuVdqvBB35JA8Laudl0fT3NXT05Hm78tYEpsec',
                'alias'  => array('微信群', '微信', '聊聊')
            ),
            'qqquan'    => array(
                'match'  => 'all',
                'answer' => 'image|iTLz1WuVdqvBB35JA8LaucvqXhgX_95LjCtYBQTkt80',
                'alias'  => array('QQ群', 'qq群')
            ),
            'zhengwen'  => array(
                'match'  => 'all',
                'answer' => 'mpnews|iTLz1WuVdqvBB35JA8Laub9qyvEiY1dsbTZZTakRgrw',
                'alias'  => array('征文', '红薯女频新类别征文')
            ),
            'hello'     => array(
                'match'  => 'any',
                'answer' => '谢谢，顺祝安好',
                'alias'  => array('你好'),
            ),
            'nan'       => array(
                'match'  => 'all',
                'answer' => 'func|$this->getNewBooks("nan")',
                'alias'  => array('男频', '男生', '男'),
            ),
            'nv'        => array(
                'match'  => 'all',
                'answer' => 'func|$this->getNewBooks("nv")',
                'alias'  => array('女频', '女生', '女'),
            ),
            'vipnan'    => array(
                'answer' => 'func|$this->getNewBooks("vipnan")',
                'alias'  => array('男频vip', 'vip男频', '男生vip', 'vip男生'),
            ),
            'vipnv'     => array(
                'answer' => 'func|$this->getNewBooks("vipnv")',
                'alias'  => array('女频vip', 'vip女频', '女生vip', 'vip女生'),
            ),
            'new'       => array(
                'answer' => 'func|$this->getNewBooks("all")',
                'alias'  => array('新书', '小说', 'all'),
            ),
            'hot'       => array(
                //'answer' => 'func|$this->getHotBooks',
                'answer' => 'func|$this->getNewBooks',
                'alias'  => array('热门', '推荐'),
            ),
            'free'      => array(
                'answer' => 'func|$this->getFreeBooks',
                'alias'  => array('免费'),
            ),
            'getbook'   => array(
                'answer' => 'func|$this->getBook(' . $bid . ');',
                'alias'  => array(),
            ),
            'help'      => array(
                'answer' => self::$defaultmsg,
                'alias'  => array('?', '帮助', '？'),
            ),
        );
        foreach ($answer as $k => $v) {
            if (!isset($v['match'])) {
                $v['match'] = 'any';
            }
            if (!isset($v['alias'])) {
                $v['alias'] = array();
            }
            if ($v['match'] == 'all') {
                if (($k == $str) || (in_array($str, $v['alias']))) {
                    return $v['answer'];
                }
            } else {
                if (str_replace($k, '', $str) != $str) {
                    return $v['answer'];
                }
                foreach ($v['alias'] as $vv) {
                    if (str_replace($vv, '', $str) != $str) {
                        return $v['answer'];
                    }
                }
            }
        }
        if ($str) {
            //开始根据书名进行搜索,
            $sModel = D('Search');
            $result = $sModel->getSearchResult($str, 2);
            //pre($sModel); exit;
            if ($result['totalcount'] >= 1) {
                $book   = $result['bookinfo'][0];
                $data   = array();
                $data[] = array(
                    'Title'       => $book['catename'],
                    'Description' => strip_tags($book['intro']),
                    'Url'         => C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '/books/' . $book['bid'],
                    'PicUrl'      => 'http:' . str_replace('http:', '', getBookfacePath($book['bid'], 'large'))
                );
                return array('type' => 'news', 'data' => $data);
            }
        }
        $result = self::$defaultmsg;
        return $result;
    }

    /**
     * 返回指定书号所对应的书，如果不指定书号或者指定的书号不存在则直接返回热门小说列表
     * @param type $id
     * @return type
     */
    function getBook($bid = 0) {
        if ($bid) {
            $bookmodel = new \Book\Model\BookModel();

            $book = $bookmodel->getBook($bid);
            if ($book) {
                $publishstatus = $bookmodel->getBookByBid($bid, "publishstatus");
                if (intval($publishstatus['publishstatus']) == 1) {
                    $data   = array();
                    $data[] = array(
                        'Title'       => $book['catename'],
                        'Description' => strip_tags($book['intro']),
                        'Url'         => C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '/books/' . $book['bid'],
                        'PicUrl'      => 'http:' . str_replace('http:', '', getBookfacePath($book['bid'], 'large'))
                    );
                    return array('type' => 'news', 'data' => $data);
                }
            }
        }
        return $this->getNewBooks();
    }

    /**
     * 返回免费推荐列表
     * @return string
     */
    function getFreeBooks() {
        S(C('rdconfig'));
        $key = 'txtxiaoshuo_book_customxianshi';
        $result = S($key);
        $now = NOW_TIME;
        $books = array();
        if ($result) {
            $bModel = D('Book');
            foreach ($result as $k => $list) {
                list($start, $end) = explode('-', $k);
                if ($start <= $now && $end >= $now) {
                    //合适地
                    foreach ($list as $book) {
                        $bid = $book['bid'];
                        $books[$bid] = $bModel->getBook($bid);
                    }
                }
            }
        }

        $data       = array();
        if ($books) {
            $i   = 1;
            $ids = array_rand($books, 5);
            foreach ($ids as $key) {
                $var = $books[$key];
                if ($i == 1) {
                    $ico = 'large';
                } else {
                    $ico = 'small';
                }
                $i++;
                $data[] = array(
                    'Title'       => $var['catename'],
                    'Description' => strip_tags($var['intro']),
                    'Url'         => C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '/books/' . $var['bid'],
                    'PicUrl'      => 'http:' . str_replace('http:', '', getBookfacePath($var['bid'], $ico))
                );
            }
            return array('type' => 'news', 'data' => $data);
        } else {
            return '对不起，暂时未对数据进行缓存，请稍候查询，谢谢您的来访！';
        }
    }

    /**
     * 返回首页热门推荐列表
     * @return string
     */
    function getHotBooks() {
        S(C('rdconfig'));
        $index_rmtj = S('index_rmtj');
        $books      = $index_rmtj['booklists'];
        $data       = array();
        if ($books) {
            $i = 1;
            foreach ($books as $var) {
                if ($i == 1) {
                    $ico = 'large';
                } else {
                    $ico = 'small';
                }
                $i++;
                $data[] = array(
                    'Title'       => $var['catename'],
                    'Description' => strip_tags($var['intro']),
                    'Url'         => C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '/books/' . $var['bid'],
                    'PicUrl'      => 'http:' . str_replace('http:', '', getBookfacePath($var['bid'], $ico))
                );
            }
            return array('type' => 'news', 'data' => $data);
        } else {
            return '对不起，暂时未对数据进行缓存，请稍候查询，谢谢您的来访！';
        }
    }

    /**
     * 返回最新更新小说列表
     * @param type $type
     *      all:全部
     *      nan:男频最新
     *      vipnan:男频VIP最新更新
     *      nv:女频最新
     *      vipnv:女频VIP最新更新
     * @return string
     */
    function getNewBooks($type = 'nan') {
        S(C('rdconfig'));
//        $index_zxgx = S('index_zxgx');
//
//        //男生VIP作品更新
//        for ($i = 0; $i < count($index_zxgx['last_vipnanbooklist']); $i++) {
//            $index_zxgx['last_vipnanbooklist'][$i]["isvip"] = 1;
//            $index_zxgx['last_vipnanbooklist'][$i]["realupdatetime"] = intval($index_zxgx['last_vipnanbooklist'][$i]["last_vipupdatetime"]);
//        }
//        //男生作品更新
//        for ($i = 0; $i < count($index_zxgx['last_nanbooklist']); $i++) {
//            $index_zxgx['last_nanbooklist'][$i]["isvip"] = 0;
//            $index_zxgx['last_nanbooklist'][$i]["realupdatetime"] = intval($index_zxgx['last_nanbooklist'][$i]["last_updatetime"]);
//        }
//        //男生全部更新
//        $index_zxgx_nan = array_merge($index_zxgx['last_vipnanbooklist'], $index_zxgx['last_nanbooklist']);
//
//        $index_zxgx_nantime = array();
//        for ($i = 0; $i < count($index_zxgx_nan); $i++) {
//            $index_zxgx_nantime[$i]['realupdatetime'] = intval($index_zxgx_nan[$i]['realupdatetime']);
//        }
//
//        array_multisort($index_zxgx_nantime, SORT_DESC, $index_zxgx_nan);
//        $index_zxgx['last_nanallbooklist'] = $index_zxgx_nan;
//
//        //女生VIP作品更新
//        for ($i = 0; $i < count($index_zxgx['last_vipnvbooklist']); $i++) {
//            $index_zxgx['last_vipnvbooklist'][$i]["isvip"] = 1;
//            $index_zxgx['last_vipnvbooklist'][$i]["realupdatetime"] = intval($index_zxgx['last_vipnvbooklist'][$i]["last_vipupdatetime"]);
//        }
//        //女生作品更新
//        for ($i = 0; $i < count($index_zxgx['last_nvbooklist']); $i++) {
//            $index_zxgx['last_nvbooklist'][$i]["isvip"] = 0;
//            $index_zxgx['last_nvbooklist'][$i]["realupdatetime"] = intval($index_zxgx['last_nvbooklist'][$i]["last_updatetime"]);
//        }
//        //女生全部更新
//        $index_zxgx_nv = array_merge($index_zxgx['last_vipnvbooklist'], $index_zxgx['last_nvbooklist']);
//
//        $index_zxgx_nvtime = array();
//        for ($i = 0; $i < count($index_zxgx_nv); $i++) {
//            $index_zxgx_nvtime[$i]['realupdatetime'] = intval($index_zxgx_nv[$i]['realupdatetime']);
//        }
//
//        array_multisort($index_zxgx_nvtime, SORT_DESC, $index_zxgx_nv);
//        $index_zxgx['last_nvallbooklist'] = array_slice($index_zxgx_nv, 0, 10);
//
//        $index_all = array_merge($index_zxgx['last_nvallbooklist'], $index_zxgx['last_nanallbooklist']);
//        $index_all_time = array();
//        for ($i = 0; $i < count($index_all); $i++) {
//            $index_all_time[$i]['realupdatetime'] = intval($index_all[$i]['realupdatetime']);
//        }
//        array_multisort($index_all_time, SORT_DESC, $index_all);
//        $index_zxgx['last_allbooklist'] = array_slice($index_all, 0, 10);
//        $key = 'last_' . $type . 'booklist';
//        $books = $index_zxgx[$key];
        //言情控取周销榜前30名，随机抽取6条记录
        $searchObj   = new \Home\Model\SearchModel();
        $PclassidAry = array(2);    //女频
        $pagesize    = 30;
        $res         = $searchObj->getSearchResult('', 1, $PclassidAry, array(), 0, 0, 0, 0, $pagesize, array(), array(0, 1), 1, 'lastweek_salenum', array(), array(2, 3, 4, 5, 6, 7, 8), 1);
        $data        = array();
        if ($res) {
            $lists  = $res['bookinfo'];
            $result = array_rand($lists, 6);
            $i      = 1;
            foreach ((array) $result as $key) {
                $var = $lists[$key];
                if ($i == 1) {
                    $ico = 'large';
                } else {
                    $ico = 'small';
                }
                $i++;
                $data[] = array(
                    'Title'       => $var['catename'],
                    'Description' => strip_tags($var['intro']),
                    'Url'         => C('TMPL_PARSE_STRING.__MOBDOMAIN__') . '/books/' . $var['bid'],
                    'PicUrl'      => 'http:' . str_replace('http:', '', getBookfacePath($var['bid'], $ico))
                );
            }
            unset($result); unset($lists);
            return array('type' => 'news', 'data' => $data);
        } else {
            return '对不起，暂时未对数据进行缓存，请稍候查询，谢谢您的来访！';
        }
    }

    public function pushAction() {
        if (!IS_CLI) {
            $this->error('????????');
        }
        $maModel = D('WechatMass');
        $wModel  = D('WechatList');
        $mModel  = D('WechatMaterial');
        echo "\n";
        $wObj    = array();
        if (I('test')) {
            $wid  = 2;
            $info = $wModel->find($wid);

            $appid             = $info['appid'];
            $appsec            = $info['appsecret'];
            $token             = $info['token'];
            $wObj[$wid]        = $info;
            $wObj[$wid]['obj'] = new \Com\WechatAuth($appid, $appsec);
            //发送一条文本消息

            $data   = array(
                'content' => '我要测试！'
            );
            $result = $wObj[$wid]['obj']->massSendAll($data, 'text');
            $this->showmsg(print_r($result, 1));
            exit('i am a test');
        }
        $date  = date('Y-m-d H:00:00', NOW_TIME + 3600);
        $where = array(
            'status'   => 0, //状态为未发送
            'sendtime' => array(
                array('lt', $date), //时间为当前时间起1小时内的所有记录
            //array('gt', date('Y-m-d H:00:00', NOW_TIME))      //起始时间
            )
        );
        $this->showmsg('请求时间：' . date('Y-m-d H:i:s', NOW_TIME));
        $wid   = I('wid', '0', 'intval');
        //指定公众号,未指定公众号的话为全部公众号
        if ($wid) {
            $where['wid'] = $wid;
        }
        $lists = $maModel->where($where)->select();
        if ($lists === false) {
            $this->sendMsg($maModel->getError());
            $this->showmsg($maModel->getError(), true);
        }
        if (!$lists) {
            $this->showmsg('没有找到 ' . $date . ' 之前要发送的消息', true);
        }
        $str    = '共找到 ' . count($lists) . ' 条需要发送的消息';
        $this->showmsg($str);
        $uModel = D('WechatUsers');
        if ($lists) {
            try {
                foreach ($lists as $row) {
                    //初始化推送OBJ
                    $wid = $row['wid'];
                    //$wid = 2;               //为了测试！
                    if (!isset($wObj[$wid])) {
                        $info              = $wModel->find($wid);
                        $appid             = $info['appid'];
                        $appsec            = $info['appsecret'];
                        $token             = $info['token'];
                        $wObj[$wid]        = $info;
                        $wObj[$wid]['obj'] = new \Com\WechatAuth($appid, $appsec);
                    }
                    $this->showmsg('发送对象：' . $wObj[$wid]['title']);
                    $media   = $mModel->find($row['mid']);
                    $result  = false;
                    $unionid = 'oNHSowWdnDsLzxDoezo0YxVPh17k'; //姚骏
                    $unionid = 'oNHSowXGBho47EMewNSIGXFmJpIc'; //果农
                    if (!$wObj[$wid]['preview']) {
                        $where                 = array(
                            'wid'     => $wid,
                            'unionid' => $unionid
                        );
                        $wObj[$wid]['preview'] = $uModel->where($where)->getField('openid');
                    }
                    if ($wid == 2) {
                        //测试专用！
                        $type = 'text';
                        $data = array(
                            'content' => $media ? print_r($media, 1) : date("Y-m-d H:i:s")
                        );
                    } else {
                        $type = $media['type'];
                        if ($type == 'text') {
                            $data = array(
                                'content' => $media['media_id']
                            );
                        } else {
                            $type = 'mpnews';
                            $data = $media['media_id'];
                        }
                    }
                    if (I('preview')) {
                        $result = $wObj[$wid]['obj']->massPreview($data, $type, $wObj[$wid]['preview']);
                    } else {
                        $result = $wObj[$wid]['obj']->massSendAll($data, $type);
                    }
                    if ($result) {
                        $data              = $row;
                        $data['msg_id']    = $result['msg_id'];
                        $data['msgId']     = $result['msg_id'];
                        $data['querytime'] = date("Y-m-d H:i:s", NOW_TIME);
                        //$data['msg_data_id'] = $result['msg_data_id'];
                        $data['status']    = 1;
                        $maModel->token(false)->create($data);
                        $maModel->save($data);
                    }
                    $this->showmsg(print_r($result, 1));
                }
            }
            catch (\Exception $e) {
                //echo print_r($wObj, 1);
                $this->sendMsg($e->getMessage());
                $this->showmsg($e->getMessage());
                //记录一下错误信息
                \Think\Log::write(print_r($e->getMessage(), 1), 'ERROR', '', LOG_PATH . 'WECHAT_PUSH_ERROR');
            }
        } else {
            $this->showmsg('没有要推送的消息！');
        }
    }

    /**
     * 出现错误时将错误信息进行转发
     * TODO 直接给微信号发可否？
     *
     * @param string $msg 要发送的信息内容
     */
    private function sendMsg($msg = '') {
        if (class_exists('GearmanClient')) {
            $client  = new \GearmanClient();
            $client->addServer("10.100.80.3", 4730);
            $client->addServer("10.100.80.4", 4730);
            $body    = iconv("UTF-8", "GBK//IGNORE", $msg);
            $From    = iconv("UTF-8", "GBK//IGNORE", C('MAINEMAIL'));
            $Subject = iconv("UTF-8", "GBK//IGNORE", "微信群发失败");
            $options = array('From' => $From, 'FromName' => $From, 'CharSet' => "GBK", 'Subject' => $Subject, '');

            $options['AltBody']     = "To view the message, please use an HTML compatible email viewer!";
            $options['SendtoName']  = '微信服务';
            $options['SendtoEmail'] = 'htmambo@163.com';
            $options['body']        = $body;
            $client->doBackground("gearmanWork_sendemail", serialize($options), 'wechat_push_' . date("Y-m-d-H"));
        } else {
            $this->showmsg('发送邮件通知失败！');
        }
    }

    /**
     * 为了同时支持win和unix，所以这里对命令行的输出信息进行了统一处理（转码）
     *
     * @param string $str 要输出的提示信息
     * @param boolean $isExit 输出后是否直接退出执行
     */
    private function showmsg($str, $isExit = false) {
        if (IS_WIN) {
            $str = iconv('utf-8', 'gb2312', $str);
        }
        echo $str . "\r\n";
        if ($isExit) {
            exit();
        }
    }

    /**
     * 获取微信中的用户信息
     * @param string $openid
     * @param array $wInfo
     * @return array
     */
    private function getWechatUserInfo($openid, $wInfo) {
        $appid     = $wInfo['appid'];
        $appsecret = $wInfo['appsecret'];

        $auth = new WechatAuth($appid, $appsecret);

        $usrinfo = $auth->getUserInfo($openid);
        return $usrinfo;
    }

    public function hopingAction(){
        $wid = 4;
        $m = D('WechatList');
        $info = $m->find(4);
        $openid = I('openid', '', 'trim');
        if(!$openid) {
            $openid = 'olqKGxMkd8-T2uGQiTBse_PbDBL0';
        }
        try {
        pre($this->getWechatUserInfo($openid, $info));
        } catch (\Exception $e) {
            pre($e);
        }
        pre($info);
    }
    
    /**
     * 通过sid设置cookie(_from_sid)的值,用于后续注册时候记录来源渠道id
     * @param $sid
     */
    public function setsidAction(){
    
        $max_flush_time = 86400;//同ip多长时间内算一次ip访问
    
        $reffer = $_SERVER['HTTP_REFERER'];//来路
        $cookie_union_sid = intval(cookie('_from_sid'));//如果设置了cookie中的sid
        $union_sid = I('get.sid',0,'intval');
        if(!$union_sid){
    
            //header("Location: ".$url);
            exit(0);
        }
        $model = new \Home\Model\TjsitecountModel();
    
    
        //cookie中的sid优先
        if($cookie_union_sid){
            $union_sid = $cookie_union_sid;
        }
        $siteid_conf = $model->site_conf('siteid_conf');
        if($siteid_conf[$union_sid]){
            cookie('_from_sid',$union_sid,array('expire'=>$max_flush_time));
        }
        else{
            exit('1');
        }
        $this->showImagHeader('ok');
    }
    
    /**
     * 站外来源跟踪和统计,输出图片gif头和1x1透明gif
     */
    public function refsitecountAction(){
         
         
    
        $max_flush_time = 86400;//同ip多长时间内算一次ip访问
        if(I('get.operation')!='referer'){
            $this->showImagHeader('error params');
            exit(0);
        }
    
        $tmp = explode(",",I('get.details','','trim'));
        foreach ($tmp as $tmp1){
    
            preg_match_all('/\"([^"]*)\"/', $tmp1,$matches);
             
            if(count($matches[1])==2)
                $details[$matches[1][0]] = $matches[1][1];
        }
        $reffer = $details['reffer'];
         
        $url = I('get.position','','trim');
        $find_bid_pattern = array('/bid=([0-9]*)/','/\/book\/([0-9]*)/','/\/bookreader\/([0-9]*)/','/\/content\/([0-9]*)\//');
    
        $bid = 0;
        foreach ($find_bid_pattern as $pattern){
            if(preg_match($pattern, $url,$matches)){
                $bid = $matches[1];
                break;
            }
        }
    
        $union_sid = intval(cookie('_from_sid'));//如果设置了cookie中的sid
    
    
        if(empty($reffer) || empty($url)){
    
            $this->showImagHeader('empty params');
            exit(0);
        }
    
        $model = new \Home\Model\TjsitecountModel();
    
        // 来路为空或本站的点击,则不处理
        $domain = $model->getSiteDomain($reffer);
         
        if(empty($domain) || $domain=="hongshu.com" || $domain=='ios.hongshu.com' || $domain=='www.hongshu.com' || $domain=='g.hongshu.com' || $domain=='m.hongshu.com' || $domain=='android.hongshu.com' || $domain=='gg.hongshu.com' || $domain=='mm.hongshu.com' || $domain=='work.hongshu.com' || $domain=='author.hongshu.com') {
            $this->showImagHeader('cant need static');
            exit(0);
        }
    
         
        $domain_conf = $model->site_conf('domain_conf');
        $siteid_conf = $model->site_conf('siteid_conf');
        if($domain_conf && empty($domain_conf[$domain]['name']) && $domain!='api.hongshu.com'){//可能是子域名,没有配置,尝试获取顶级域名
            $orig_domain  = $domain;
            $domain = $model->getSiteDomainTop($reffer);//顶级域名获取
        }
    
        //从联盟站导量入口进入的,获取sid,并记录到cookie
        if($domain=='api.hongshu.com'){
            $params = parse_url($reffer);
            //parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars
            parse_str($params['query'],$tmpAry);
            if(intval($tmpAry['sid'])){
                $tmpAry['sid'] = intval($tmpAry['sid']);
                $domain = $siteid_conf[$tmpAry['sid']]['site_domain'];
                $union_sid = $tmpAry['sid'];
                cookie('_from_sid',$union_sid,array('expire'=>86400));
                //cookie('_from_domain',$domain,array('expire'=>86400));
            }
        }
         
         
         
        $is_not_set_domain = false;//是否是配置中不存在的,不需要统计详细来路的域名
        if($domain_conf && $domain){//如果不是需要统计的域名则不统计
    
            if(empty($domain_conf[$domain]['name']) && $domain!='api.hongshu.com'){//没有这个域名的统计设置,同时不是从导量入口进入的
                $is_not_set_domain = true;
                //exit(0);
            }
            elseif(!$union_sid){//如果没有统计siteid,则查看site_id是否存在
                $union_sid = $domain_conf[$domain]['site_id'];
                cookie('_from_sid',$union_sid,array('expire'=>86400));
            }
            else{
                $domain = $siteid_conf[$union_sid]['site_domain'];
            }
            if(!$is_not_set_domain){
                cookie('_from_domain',$domain,array('expire'=>86400));
            }
        }
        else{
            $this->showImagHeader('cant need static 2');
            exit(0);
        }
         
        $ip = get_client_ip(0,true);
        $redis = new \Think\Cache\Driver\Redis();
        //单ip限制key
        $ip_count_key = 'tjsitecount:ip:'.$ip;
        $last_ip_count = $redis->get($ip_count_key);
    
        //domain ip pv 统计缓存
        $domain_ipcount_key = 'tjdomainipcount:'.$domain.':'.date("ymd");
        $domain_pvcount_key = 'tjdomainpvcount:'.$domain.':'.date("ymd");
        //echo time()-$last_ip_count;
         
        if(!$last_ip_count && !$is_not_set_domain){
            $redis->set($ip_count_key,time());//超过$max_flush_time秒算一次ip访问
            $redis->expire($ip_count_key, $max_flush_time);
            $redis->INCR($domain_ipcount_key, 1);
            $redis->expire($domain_ipcount_key,86400*3);//ip总数保存两个月
            $model->addSitecountlog($ip,$domain,$union_sid,$url,$reffer,$bid);
            //echo 'add';
            header("set : Y");
        }
        elseif($is_not_set_domain && !$last_ip_count){//记录未在数据库中配置的域名的每日ip
            $not_set_domain_countkey = 'tjnosetdomain:ip:'.date("ymd");
    
            $ret=$redis->hIncrBy($not_set_domain_countkey, $orig_domain, 1);
    
            $redis->expire($not_set_domain_countkey,86400*7);//ip总数保存7day
            header("set : N1");
        }
        else{
            header("set : N");
        }
        //pv都会增加
        if(!$is_not_set_domain){
            $redis->INCR($domain_pvcount_key,1);
    
            $redis->expire($domain_pvcount_key,86400*3);
        }
        header('Content-Type: image/gif');
        //a transparent pixel image
        echo "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x90\x01\x00\xff\xff\xff\x00\x00\x00\x21\xf9\x04\x01\x00\x00\x01\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x4c\x01\x00\x3b";
        exit(0);
        /*
         echo '-<br/>';
         print_r($redis->get($ip_count_key));
         echo '-<br/>';
         print_r($redis->get($domain_ipcount_key));
         echo '-<br/>';
         print_r($redis->get($domain_pvcount_key));
         */
    }
    
    /**
     * 页面位置点击统计
     */
    public function clicktrackerAction(){
        $max_flush_time = 10;//同ip多长时间内算一次ip访问
        if(I('get.operation')!='click'){
            exit(0);
        }
    
        $tmp = explode(",",I('get.details','','trim'));
        foreach ($tmp as $tmp1){
    
            preg_match_all('/\"([^"]*)\"/', $tmp1,$matches);
    
            if(count($matches[1])==2)
                $details[$matches[1][0]] = $matches[1][1];
        }
         
        //位置和排序序号
        $tmp = I('get.position','','trim');
        if(empty($tmp)){
            exit(0);
        }
        //格式  data-clktrack="位置|序号|书号"
        $tmp = explode('|', $tmp);
        $position = trim($tmp[0]);
        $order = intval($tmp[1]);
        $bid = intval($tmp[2]);
        if(empty($position) || empty($order)){
            exit(0);
        }
    
        $url = $details['url'];
        if(empty($url)){
            exit(0);
        }
        $model = new \Home\Model\TjsitecountModel();
        $hostname = $model->getSiteDomain($url);
         
        if(!isset($this->host_set_ary[$hostname])){
            exit(0);
        }
        $host_id = $this->host_set_ary[$hostname];
    
        $reffer = $details['ref'];
         
    
    
    
    
        $position_conf = $model->clicktracker_conf(true);
    
        if($position_conf && $position && $position_conf[$host_id][$position]['position_id']){
            $position_id = $position_conf[$host_id][$position]['position_id'];
        }
        else{
            exit(0);
        }
    
        $ip = get_client_ip(0,true);
        $mmc = new \Think\Cache\Driver\Memcache();
        //同ip,位置,序号限制$max_flush_time内统计一次
        $ip_count_key = 'clktracker:'.$position_id.':'.$order.':'.$ip;
    
    
        $last_ip_count = $mmc->get($ip_count_key);
         
    
         
        if(!$last_ip_count){
            $mmc->set($ip_count_key,time(),$max_flush_time);//超过$max_flush_time秒算一次ip访问
             
             
             
             
            $model->addClickTracklog($ip,$host_id,$position_id,$order,$bid,$reffer);
            header("set : Y");
        }
        else{
            header("set : N");
        }
    
    
        header('Content-Type: image/gif');
        //a transparent pixel image
        echo "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x90\x01\x00\xff\xff\xff\x00\x00\x00\x21\xf9\x04\x01\x00\x00\x01\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x4c\x01\x00\x3b";
        exit(0);
    
    }
    private function showImagHeader($msg){
         
    
        if(empty($msg)){
            header("set: ".$msg);
        }
        /*
         exit('ddd');
         */
        header('Content-Type: image/gif');
        //a transparent pixel image
        echo "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x90\x01\x00\xff\xff\xff\x00\x00\x00\x21\xf9\x04\x01\x00\x00\x01\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x4c\x01\x00\x3b";
        //exit(0);
        exit('dsdd');
    }
}
