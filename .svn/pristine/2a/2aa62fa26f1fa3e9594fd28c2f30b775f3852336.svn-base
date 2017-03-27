<extend name="base" />
<block name="body">
	<header>
		<h1 class="text-center">接口列表</h1>
    </header>
    <table class="table">
        <thead>
        <tr>
            <th>#</th><th>接口服务</th><th>接口名称</th><th>测试</th>
        </tr>
        </thead>
        <tbody>
		<volist name="apis" id="api">
            <tr class='success'><td><b>{$i}</b></td><td><b>{$api.api}</b></td><td><b>{$api.title}</b></td><td></td></tr>
			<foreach name="api[methods]" item="method">
                <tr><td></td><td><a href="{:U('apiDetail', array('yar'=>$api[api], 'func'=>$key))}" target='_blank'>{$key}</a></td><td>{$method}</td><td><a href="{:U('apiTest', array('yar'=>$api[api], 'func'=>$key))}" target='_blank'>测试</a></td></tr>
			</foreach>
		</volist>
        </tbody>
    </table>
</block>
