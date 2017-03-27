<div class="bk3"><h5>大家都在看</h5>
    <ul id="result">
        </ul>
    </div>


<script type="text/html" id="booklist_tpl">
    {{if list}}
        {{each list as row i}}
        <li>
            <a href="{{row.url}}">
                <img src="{{row.bookface}}" /><br />
                <p>{{row.catename}}</p>
            </a>
        </li>
        {{/each}}
    {{/if}}
</script>
<script>
Do.ready('template', function(){
	$.ajax({
		url:"{:url('getBooks', '', 'do')}",
		success:function(data){
			if(data.hasOwnProperty('status')){
                $('.bk3').remove();
            } else {
                var html = template('booklist_tpl',{list:data});
                $('#result').html(html);
            }
		}
	});
});
</script>