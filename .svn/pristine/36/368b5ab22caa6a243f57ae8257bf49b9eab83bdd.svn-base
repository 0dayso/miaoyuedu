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
    <form class="form-horizontal" role="form" method="post" action="">
        <div class="panel">
            <div class="panel-heading">接口参数</div>
            <div class="panel-body">
                <volist name="params" id="param">
                <div class="form-group">
                    <label class="col-md-2 control-label">{$param.title}</label>
                    <div class="col-md-4">
                        <php>
                            $value = $param['default'];
                            if(isset($data[$param['name']])){
                                $value = $data[$param['name']];
                            }
                        </php>
                        <eq name="param[type]" value="array">
                        <textarea  name="param[{$param.name}]" id="{$param.name}" class="form-control" <if condition="$param[required]">required data-validation-required-message="{$param.title}必填！"</if>>{$value|json_encode}</textarea>
                        <else/>
                        <input type="{$param.type|getParamType=###,'input'}" name="param[{$param.name}]" id="{$param.name}" value="{$value|default=''}" class="form-control" <if condition="$param[required]">required data-validation-required-message="{$param.title}必填！"</if>>
                        </eq>
                    </div>
                    <span class="col-md-6"><if condition="$param[desc]"><pre>{$param.desc}</pre></if></span>
                </div>
                </volist>
            </div>
            <div class="panel-footer">
                <input type="hidden" name="yar" value="{$yar}">
                <input type="hidden" name="func" value="{$func}">
                <input type="submit" id="submit" class="btn btn-primary" value="测试">
                说明：数组内容请转换为JSON字符串进行提交。
            </div>
        </div>
    </form>
    </notempty>
    <present name="error">
        <div class="alert alert-danger">
        {$error}
        </div>
    </present>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">返回值</div>
            <div class="panel-body">
                {$returns|dump}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">返回值(JSON)</div>
            <div class="panel-body">
                <pre>{$returns|json_encode}</pre>
            </div>
        </div>
    </div>
    <div class="col-lg-12"></div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">老接口返回值</div>
            <div class="panel-body">
                {$old_returns|dump}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">老接口返回值(JSON)</div>
            <div class="panel-body">
                <pre>{$old_returns|json_encode}</pre>
            </div>
        </div>
    </div>
</block>

<block name="script">
    <script src="//cdn.bootcss.com/jqBootstrapValidation/1.3.7/jqBootstrapValidation.min.js"></script>
    <script>
        $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
    </script>
</block>