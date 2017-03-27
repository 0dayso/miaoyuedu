<script type="text/javascript">
Do.ready('common', function(){
    $("#txtSkeyWords").keyup(function(event) {
        switch (event.keyCode) {
            case 13:
                goToSearch();
                break;
        }
    });
});

function goToSearch(){
	//这里写入搜索的代码
	var keyword=$("#txtSkeyWords").val();
	if(!keyword){
		/*var keyword=$("#txtSkeyWords").attr("placeholder");*/
        return;
	}
	hg_gotoUrl(parseUrl({keyword:keyword}, 'Channel/search'));
}
function getRedDot(){
    var url="{:url('Userajax/getRedDot',array(),'do')}";
        $.ajax({
            url:url,
            success:function(data){
                if(data.isred == 1){
                   var obj=$('#hongdian');
                   if(obj){
                    $('#hongdian').show();
                   }
                   var obj2=$('#shuzi');
                   if(obj2){
                    $('#shuzi').show();
                   }
                }
            }
        });
    }
</script>