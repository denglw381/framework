function errorMsg(message){
	ymPrompt.errorInfo({message:message,title:'错误提示'});	
}

function successMsg(message){
	ymPrompt.succeedInfo({message:message,title:'操作成功提示'});	
}





function doSelectHostType(_type){
	if(0 == _type || 2 == _type || 1 == _type){
		$("input[name='webdir']").parent().parent().hide();
		$("select[name='sync']").parent().parent().hide();
		$("input[name='agent']").parent().parent().hide();
	}else{
		$("input[name='webdir']").parent().parent().show();
		$("select[name='sync']").parent().parent().show();
		$("input[name='agent']").parent().parent().show();
	}
}

$("document").ready(function(){
		doSelectHostType($("select[name='type']").val());
		$("select[name='type']").bind('change', function(){
			doSelectHostType($("select[name='type']").val());
		})
})
