<script type="text/html" id="reply_tpl">
    {{each list as row i}}
        {{if row.forbidden_flag == 1}}
		    <li>
		        <p class="comment shenhe"><span class="cpink">{{row.nickname}}:</span>内容审核中...</p>
		        <p class="comment_xinxi"><span class="comment_date">{{row.time}}</span><span >{{row.floor}}楼</span></p>
		    </li>
		{{else}}
			<li>
		        <p class="comment"><span class="cpink">{{row.nickname}}:</span>{{#row.content}}</p>
		        <p class="comment_xinxi"><span class="comment_date">{{row.time}}</span><span >{{row.floor}}楼</span></p>
		    </li>
		{{/if}}
	{{/each}}
</script>