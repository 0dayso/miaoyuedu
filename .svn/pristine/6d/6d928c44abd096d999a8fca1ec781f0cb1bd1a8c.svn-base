<extend name="base" />
<block name="body">
    <header>
        <h1>接口：{$service}</h1>
        <dl class="">
            <dt>{$description}</dt>
            <dd>{$descComment}</dd>
        </dl>
    </header>
    <notempty name="params">
    <h3>接口参数</h3>
    <table class="table table-striped" >
        <thead>
        <tr><th>参数</th><th>名称</th><th>类型</th><th>是否必须</th><th>默认值</th><th>说明</th></tr>
        <volist name="params" id="param">
            <tr><td>{$param.title}</td><td>{$param.name}</td><td>{$param.type|getParamType}</td><td><if condition="$param[required]"><font color="red">必须</font><else/>可选</if></td><td>{$param.default}</td><td><if condition="$param[desc]"><pre class="lang-php">{$param.desc}</pre></if></td></tr>
        </volist>
    </table>
    </notempty>
    <notempty name="return">
    <h3>返回结果</h3>
    <table class="table table-striped" >
        <thead>
        <tr><th>类型</th><th>说明</th></tr>
        <tr><td>{$return.type}</td><td>{$return.name}<notempty name="return[desc]"><pre class="lang-php">{$return.desc}</pre></notempty></td></tr>
    </table>
    </notempty>
    <div class="panel">
        <div class="panel-heading">示例代码：</div>
        <div class="panel-body">
            <pre class="code brush: php">
    //业务代码
    ┊
    //初始化 begin
<php>$pf=array();</php><volist name="params" id="param"><php>$pf[]='$'.$param['name'];</php>
    ${$param['name']} = ..........;                     //初始化{$param.title}<php>echo "\r\n";</php>
            </volist>
    //初始化 end
            <php>$pf=implode(', ', $pf);</php>

    $yar_client = new \HS\Yar("{$yar}");
    $result     = $yar_client->{$func}({$pf});
    //业务代码
    ┊
    </pre>
        </div>
    </div>
</block>
<block name="script">
    <script src="//cdn.bootcss.com/SyntaxHighlighter/3.0.83/scripts/shCore.min.js"></script>
    <script src="//cdn.bootcss.com/SyntaxHighlighter/3.0.83/scripts/shAutoloader.min.js"></script>
    <link href="//cdn.bootcss.com/SyntaxHighlighter/3.0.83/styles/shCore.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/SyntaxHighlighter/3.0.83/styles/shCoreRDark.min.css" rel="stylesheet">
<script type="text/javascript">
    function path() {
        var args = arguments,
		result = [];
        for (var i = 0; i < args.length; i++)
            result.push(args[i].replace('$', '//cdn.bootcss.com/SyntaxHighlighter/3.0.83/scripts/'));
        return result
    }
    $(function () {
        SyntaxHighlighter.autoloader.apply(null, path(
            'php    $shBrushPhp.js'
        ));
        SyntaxHighlighter.all();
    });
</script>
</block>
