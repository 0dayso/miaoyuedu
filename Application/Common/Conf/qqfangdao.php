<?php
/**
 * QQ防盗记录规则：
 * 模块/控制器/方法=array(
 *      method:页面的访问方式：get,post,ajax......
 *      param:规则，需要判断请求中是否包含有指定参数,
 *      enabled:是否开启,
 *      record:
 * )
 */
return array(
    'QQ_FANGDAO' => array(
        'Client/Ajax/clientrequest'=>array(),
        'Client/Alipyhelp/AlipayFromWexin'=>array(),
        'Client/Androidajax/androidrequest'=>array(),
        'Client/Channel/hongwen'=>array(),
        'Client/Channel/index'=>array(),
        'Client/Channel/jingxuan'=>array(),
        'Client/Channel/rankfree'=>array(),
        'Client/Channel/rank'=>array(),
        'Client/Chanenl/search'=>array(),
        'Client/Channel/tejia'=>array(),
        'Client/Channel/xinshu'=>array(),
        'Client/Channel/free'=>array(),
        'Client/Bookajax/addbookshelf'=>array('session'=>'start'),
        'Client/Bookajax/addComment'=>array('session'=>'start'),
        'Client/Bookajax/addreply'=>array('session'=>'start'),
        'Client/Bookajax/chapterlist'=>array(),
        'Client/Bookajax/checkfav'=>array('session'=>'start'),
        'Client/Bookajax/comment'=>array('session'=>'start'),
        'Client/Bookajax/dashang'=>array('session'=>'start'),
        'Client/Bookajax/downbook'=>array('session'=>'start'),
        'Client/Bookajax/downloadchapter'=>array('session'=>'start'),
        'Client/Bookajax/downloadchapterlist'=>array('session'=>'start'),
        'Client/Bookajax/downloadchptchapters'=>array('session'=>'start'),
        'Client/Bookajax/downloadfreechapters'=>array('session'=>'start'),
        'Client/Bookajax/downloadfullchapters'=>array('session'=>'start'),
        'Client/Bookajax/getAllChapter'=>array('session'=>'start'),
        'Client/Bookajax/getBarragerData'=>array('session'=>'start'),
        'Client/Bookajax/getbookcover'=>array('session'=>'start'),
        'Client/Bookajax/getBookStatus'=>array('session'=>'start'),
        'Client/Bookajax/getchptcountandlzinfo'=>array('session'=>'start'),
        'Client/Bookajax/getComments'=>array('session'=>'start'),
        'Client/Bookajax/getOrderInfo'=>array('session'=>'start'),
        'Client/Bookajax/getOrderTmpInfo'=>array('session'=>'start'),
        'Client/Bookajax/getPreNextChapter'=>array(),
        'Client/Bookajax/getProList'=>array(),
        'Client/Bookajax/getreadlog'=>array(),
        'Client/Bookajax/getRecommendBooks'=>array(),
        'Client/Bookajax/getversion'=>array(),
        'Client/Bookajax/getViewReadLog'=>array(),
        'Client/Bookajax/hotkeywords'=>array(),
        'Client/Bookajax/orderChapter'=>array('session'=>'start'),
        'Client/Bookajax/replyComment'=>array(),
        'Client/Bookajax/getDiscountList'=>array(),
        'Client/Bookajax/search'=>array(),
        'Client/Bookajax/getfreelist'=>array(),
        'Client/Bookajax/searchtip'=>array(),
        'Client/Bookajax/sendBarrage'=>array('session'=>'start'),
        'Client/Bookajax/sendFlower'=>array('session'=>'start'),
        'Client/Bookajax/sendRedTicket'=>array('session'=>'start'),
        'Client/Bookajax/sendZan'=>array('session'=>'start'),
        'Client/Bookajax/updateReadLog'=>array(),
        'Client/Bookajax/view'=>array(),
        'Client/Bookajax/yqmdashang'=>array(),
        'Client/Bookajax/getBarragerData'=>array(),
        'Client/Book/buyVipList'=>array('session'=>'start'),
        'Client/Book/chapterlist'=>array(),
        'Client/Book/comment'=>array(),
        'Client/Book/cookiebookshelf'=>array(),
        'Client/Book/dashang'=>array(),
        'Client/Book/downapp'=>array(),
        'Client/Book/follow'=>array(),
        'Client/Book/read'=>array(),
        'Client/Book/readVip'=>array(),
        'Client/Book/replyComment'=>array(),
        'Client/Book/sharepage'=>array(),
        'Client/Book/view'=>array(),
        'Client/Channelajax/savehotkeywords'=>array(),   
        'Client/Channelajax/searchapi'=>array(),
        'Client/Feedbackajax/getfeedbackreplylist'=>array(),
        'Client/Feedbackajax/savafeedback'=>array(),
        'Client/Feedbackajax/saveaddfeedback'=>array(),
        'Client/Feedbackajax/saveaddreplycommun'=>array(),
        'Client/Feedback/index'=>array(),
        'Client/Feedback/getfeedback'=>array(),
        'Client/Feedback/feedbackReply'=>array(),
        'Client/Help/index'=>array(),
        'Client/Help/aboutHelp'=>array(),
        'Client/Help/Article'=>array(),
        'Client/Index/index'=>array(),
        'Client/Payajax/getpayid'=>array('session'=>'start'),
        'Client/Payajax/getPayActivity'=>array(),
        'Client/Pay/index'=>array(),
        'Client/Userajax/autodingyue'=>array('session'=>'start'),
        'Client/Userajax/autoregister'=>array('session'=>'start'),
        'Client/Userajax/changenickname'=>array('session'=>'start'),
        'Client/Userajax/checkusercode'=>array('session'=>'start'),
        'Client/Userajax/checklogin'=>array('session'=>'start'),
        'Client/Userajax/checkpointqiandao'=>array('session'=>'start'),
        'Client/Userajax/delfav'=>array('session'=>'start'),
        'Client/Userajax/getRedDot'=>array('session'=>'start'),
        'Client/Userajax/getshelflist'=>array('session'=>'start'),
        'Client/Userajax/insertfav'=>array('session'=>'start'),
        'Client/Userajax/lingjiang'=>array('session'=>'start'),
        'Client/Userajax/myCommentReplies'=>array('session'=>'start'),
        'Client/Userajax/myComments'=>array('session'=>'start'),
        'Client/Userajax/myReplyComments'=>array('session'=>'start'),
        'Client/Userajax/paylogs'=>array('session'=>'start'),
        'Client/Userajax/salelogs'=>array('session'=>'start'),
        'Client/Userajax/saveautodingyue'=>array('session'=>'start'),
        'Client/Userajax/shuQuanBooks'=>array('session'=>'start'),
        'Client/User/authorLogin'=>array(),
        'Client/User/authorReg'=>array(),
        'Client/User/ChangeAccount'=>array(),
        'Client/User/changenickname'=>array(),
        'Client/User/changepwd'=>array(),
        'Client/User/index'=>array(),
        'Client/User/lingjiang'=>array(),
        'Client/User/login'=>array(),
        'Client/User/logout'=>array(),
        'Client/User/losepwd'=>array(),
        'Client/User/mobbind'=>array('session'=>'start'),
        'Client/User/paylogs'=>array('session'=>'start'),
        'Client/User/personal'=>array('session'=>'start'),
        'Client/User/qiandao'=>array('session'=>'start'),
        'Client/User/register'=>array(),
        'Client/User/salelogs'=>array('session'=>'start'),
        'Client/User/setbooking'=>array('session'=>'start'),
        'Client/User/shelf'=>array('session'=>'start'),
        'Client/User/shuquan'=>array('session'=>'start'),
    )
);