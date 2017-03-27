var pagelist='';
var prenum = parseInt(data.pagenum)-1;
var nextnum= parseInt(data.pagenum)+1;
if(data.pagenum==1){
    pagelist += '<a href="javascript:void(0);" class="radius4 disable" >|<</a><a href="javascript:void(0);" class="radius4 disable" ><</a>';
}else{
    pagelist += '<a href="javascript:getlist(1);" class="radius4" >|<</a><a href="javascript:getlist('+prenum+');" class="radius4" ><</a>';
}
var start=parseInt(data.pageliststart);
for(var i=0;i<10&&(i+start)<=data.totalpage;i++){
    if((i+start)==data.pagenum){
        pagelist+='<a href="javascript:void(0);" class="radius4 active">'+(i+start)+'</a>'
    }else{
        pagelist+='<a href="javascript:getlist('+(i+start)+');" class="radius4">'+(i+start)+'</a>'
    }
    
}
if(data.totalpage==data.pagenum){
    pagelist+='<a href="javascript:void(0);" class="radius4 disable">></a><a href="javascript:void(0);" class="radius4 disable">>|</a>'
}else{
    pagelist+='<a href="javascript:getlist('+nextnum+');" class="radius4">></a><a href="javascript:getlist('+data.totalpage+');" class="radius4">>|</a>'
}
$('#pagelist').html(pagelist);