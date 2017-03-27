<?php
/**
 * 模块: 客户端
 *
 * 功能: 模板标签库
 *
 * @copyright Copyright (c) 2015 – www.hongshu.com
 * @author: guonong
 * @version: $Id: Hongshu.class.php 480 2016-08-04 10:22:40Z guonong $
 */
namespace Client\TagLib;

use Think\Template\TagLib;

/**
 * 标签库驱动
 */
class Hongshu extends TagLib {

    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'bangdan'    => array(
            'attr' => 'name,value,id,items,full,cutorder',   //items:要提取的数量, full:显示小说的完整信息，cutorder:数据截取方式，如果未填或者填为roll则以系统原有的方式进行截取
        ),
    );

    /**
    * 处理榜单
    *
    * @param array $tag 标签属性
    * @param string $content 标签内嵌的模板内容
    * @return string
    */
    public function _bangdan($tag, $content) {
        $name = $tag['name'];
        /**
        * 要显示的条数
        *
        * @var int
        */
        $items = intval($tag['items']);
        /**
        * 数组输出时的键名
        * 默认为k
        * @var string
        */
        $key = $tag['key']? : 'k';
        /**
        * 数组输出时的值的名称
        * 默认为row
        * @var string
        */
        $item = $tag['item']?:'row';
        /**
        * 数据截取方式，取值范围：first：从第一条起截取，end：从最后一条截取，rand：随机截取，roll：使用系统原有的方式进行截取
        * 默认值：roll
        * @var string
        */
        $order = isset($tag['cutorder'])?$tag['cutorder']:'roll';
        /**
        * 是否获取完整的书籍信息
        * 默认为1
        * @var int
        */
        $full = (isset($tag['full'])&&$tag['full']==='false')?0:1;
        S(C('rdconfig'));
        if (strpos($name, '{')) {
            $name = str_replace(array('{', '}'), array('".', '."'), $name);
        }
        $parseStr = "<!-- 显示指定榜单 -->\n";
        $parseStr .= <<<EOT
<php>
\$return = "{$tag['return']}";       //是否需要直接返回？
if('$order'==='roll'){
    \$result = _process_bangdan("$name", $items);
} else {
    \$result = _process_bangdan("$name");
}
\$books = \$result;
if (is_array(\$result) && isset(\$result['booklists'])) {
    \$books = \$result['booklists'];
}
if (is_array(\$books)){
    if({$items} && {$items}<count(\$books)){
        if ('$order' === 'end') {
            //取结尾的
            \$books = array_slice(\$books, count(\$books) - {$items}, {$items});
        } else if ('$order' === 'first') {
            //取头部的
            \$books = array_slice(\$books, 0, {$items});
        } else if('$order' === 'rand' ) {
            //随机
            \$_rand = array_rand(\$books, {$items});
            \$tmp = \$books;
            \$books = array();
            if (is_array(\$_rand)) {
                foreach (\$_rand as \$k) {
                    \$books[\$k] = \$tmp[\$k];
                }
            } else {
                \$books[\$_rand] = \$tmp[\$_rand];
            }
        }
    }
    if ({$items} && {$items} < count(\$books)) {
        \$books = array_slice(\$books, 0, {$items});
    }
    if({$full}){
        \$_bModel = D('Book');
        foreach(\$books as &\$_v){
            \$_bid = \$_v['bid'];
            \$_v = array_merge(format_bookinfo(\$_bModel->getBook(\$_bid, 1)), \$_v);
        }
    }
} else {
    \$books = preg_replace('@http[s]*://[^/]+/book\.php\?bid=(\d+)@is', ROOT_URL.'/Book/view/bid/\\1.html', \$books);
}
if(\$return){
    \$\$return = \$books;
    </php>
        {$content}
    <php>
} else if(is_array(\$books)){
    foreach (\$books as \${$key} =>\${$item}) {
        \${$item}['face'] = getBookfacePath(\${$item}['bid'], 'middle');
        </php>
        {$content}
        <php>
    }
} else {
        \${$item} = \$books;
        </php>
        {$content}
        <php>
}
    </php>
<!-- 指定榜单处理完毕 -->
EOT;
        return $parseStr;
    }
}

