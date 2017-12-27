var loadding='<center><br /><br /><br /><img src="/static/images/loading.gif" /><br /><br /><br /></center>';
ymPrompt.setDefaultCfg({maskAlpha:'0.5',maskAlphaColor:'#000'})

/** 
 * 
 * @return 
 */
function showDataSaveLog(){
	$("#dataSaveLog").html(loadding);
	$.ajax({
	   url:"/index.php",
	   data:{c:"main",a:"dataSaveLog"},	
	   type:"POST",
	   dataType:'html',
	   success:function(data){
		$("#dataSaveLog").html($(data).find('#dataSaveLog').eq(0).html());
		if(undefined != $(data).find('#main_log_operator'))
			$("#main_log_operator").html($(data).find('#main_log_operator').eq(0).html());
	   }
	});
}

$("document").ready(function(){
	$("#query").bind("click",function(){
		if(false == /^[1-9][0-9]*$/.test($("#version").val())){
			alert('版本号必须为正整数'); 
			$('#version').focus();
			return false;
		}
		$(this).attr("disabled",true); 
		$("#allFiles > td").html(loadding);
		$.ajax({
			url:'/index.php?c=main&a=getRevision',
			type:'POST',
			data:"rid="+$("#version").val()+"&category_id="+$('#category_id').val(),
			success:function(data){
				$("td","#allFiles").eq(0).html(data);
				$("#query").removeAttr("disabled");
				if($("td>input", "#allFiles").length < 1){
					$("#operateRevFile").hide();		
				}else{
					$("#operateRevFile").show();		
				}
			}
		});
		$("#allFiles").show(); 
	});

	showDataSaveLog();
	getOperateLog();

	$("#querySync").bind("click",function(){
		syncIdValue = $("#syncId").val();
		if(syncIdValue=='') {alert("上线单号不能为空！");$("#syncId").focus(); return false;}
		if(false == /^[1-9][0-9]*$/.test(syncIdValue)){
			alert('上线单号必须为正整数'); 
			$('#syncId').focus();
			return false;
		}
		$.post("/index.php?c=main&a=setSyncId",{"syncId":syncIdValue},function(data){ 
			if(data == syncIdValue){
				window.location.reload();
			}
	        }	  
	       );
	})

	$("#createSync").bind("click",function(){
		syncIdValue = $("#syncId").val();
		if(false == /^[1-9][0-9]*$/.test(syncIdValue)){
			alert('上线单号必须为正整数'); 
			$('#syncId').focus();
			return false;
		}
		if(syncIdValue=='') {alert("上线单号不能为空！");$("#syncId").focus(); return false;}
		$.post("/index.php?c=main&a=setSyncId",{"syncId":syncIdValue},function(data){ 
			alert(data);
			if(data == syncIdValue){
				showDataSaveLog();
			}
	        }	  
	       );
	})

	$("#sync").bind("click",function(){
		__str = $("#sync").val();
		$("#sync").val("正在同步...");
		$("#sync").attr("disabled",true);
		$("#syncBackInfoTable").show();
		$("#syncBackInfo").html(loadding);
		$.ajax({
			url:"/index.php?c=main&a=sync",
			success:function(data){
				$("#syncBackInfo").html(data);
                         	$("#sync").val(__str);
	                	$("#sync").attr("disabled",false);
				getOperateLog();
				enableNext('sync');
			}
		});
	});

	$("#main_log_operator").delegate('#deleteLog',"click",function(){
	    if(confirm("确认删除？")){
		items=$("input[name='main_log_id[]']");
		log_Ids=new Array();
		j=0;
		for(i=0;i<items.length;i++){
			if(items.eq(i).attr("checked")==true){
				log_Ids[j]=items.eq(i).val();
				j++;
			}
		}
		if(log_Ids.length<=0){ 
			ymPrompt.errorInfo('对不起，您没有选中任何数据!');
			return;
		}
		main_log_ids=log_Ids.join(',');
		$("#deleteLog").val("正在删除...");
		$("#deleteLog").attr("disabled",true);
		$.ajax({
			url:"/index.php?c=main&a=delete_mainlog",
			data:{'ids':main_log_ids},
			success:function(data){
				alert(data);
				$("#deleteLog").val("删除选中");
				$("#deleteLog").attr("disabled",false);
				showDataSaveLog();
				showMainLogOperatorByCounts();
			}
		});
	    }
	});

	$("#sync1").bind("click",function(){
		__str = $("#sync1").val();
		$("#sync1").val("正在同步...");
		$("#sync1").attr("disabled",true);
		$("#syncBackInfoTable").show();
		$("#syncBackInfo").html(loadding);
		$.ajax({
			url:"/index.php?c=main&a=sync1",
			success:function(data){
				$("#syncBackInfo").html(data);
				getOperateLog();
		  		$("#sync1").val(__str);
				$("#sync1").attr("disabled",false);
				enableNext('sync1');
			}
		} );

	});
	$("#syncN").bind("click",function(){
		__str = $("#syncN").val();
		$("#syncN").val("正在同步...");
		$("#syncN").attr("disabled",true);
		$("#syncBackInfoTable").show();
		$("#syncBackInfo").html(loadding);
		$.ajax({
			url:"/index.php?c=main&a=syncN",
			success:function(data){
				$("#syncBackInfo").html(data);
				getOperateLog();
		  		$("#syncN").val(__str);
				$("#syncN").attr("disabled",false);
			}
		} );

	});
	$(".main_title ul li").bind('click',function(){
		$(this).parent().children('li').removeClass('on');
		$(this).addClass('on');
		$("#operateLog").toggle();
		$("#revLog").toggle();
	});

	$("#tippwd").bind('click',function(){
		$(this).hide();
		$("#newpwd").show();
		$("#newpwd").focus();
	});

	$('#changePwd').bind('click',function(){
		__passwd = $("#newpwd").val();
		if($.trim(__passwd) == ''){
			ymPrompt.errorInfo({title:'错误提示！',message:'密码不能为空'});
			return;
		}
		if(__passwd.length < 6){
			ymPrompt.errorInfo({title:'错误提示！',message:'密码长度不能小于6位'});
			return;
		}

		ymPrompt.confirmInfo({message:'确认密码修改为'+__passwd,title:'密码修改提示！',handler:changePwd})
	});

	$("#hosts_pid").bind('change', function(){
				_id = $(this).val();
				$.ajax(
					{
						url:'/index.php?c=HostsCategory&a=getMySonCat&pid='+_id,
						type:'GET',
						dataType:'json',
						success:function(data){
							str = '';
							for(var i in data){
								str +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
								$("select[name='category_id']").html(str);
							}
						}
					}
				)
		}
	)
});

function changePwd(tp){
	if(tp == 'ok')
	$.ajax(
	{
		url:'/index.php?c=main&a=changePwd',
		data:{passwd:$("input[name='password']").val()},
		dataType:'json',
		success:function(data){
			if(data.code)
			ymPrompt.succeedInfo({message:data.msg, title:'密码修改提示'});
			else
			ymPrompt.succeedInfo({message:'密码修改成功！', title:'密码修改提示'});
		}
	}
	);
}

function enableNext(_id){
	$('#'+_id).next().removeAttr('disabled');
}

function checkAll(item_name){
    $("input[name='"+item_name+"']").attr("checked",true);
}

function canselAll(item_name){
    $("input[name='"+item_name+"']").removeAttr("checked");
}

function choiceOthers(item_name){
    items=$("input[name='"+item_name+"']");	
    for(i=0;i<items.length;i++){
        if(items.eq(i).attr("checked")==true){
		items.eq(i).removeAttr("checked");
	}else{
		items.eq(i).attr("checked",true);
	}
    }
}

function saveFiles(obj){
	items=$("input[name='files[]']");	
	postval=new Array();
	for(i=0;i<items.length;i++){
		if(items.eq(i).attr("checked")==true){
			postval[i]="files[]="+items.eq(i).val();
		}
	}
	postval=postval.join("&");
	$.ajax({
		url:'/index.php?c=main&a=saveFiles',			
		type:'POST',
		data:postval,
		success:function(data){       
			alert(data);
			showDataSaveLog();
			getOperateLog();
			toBottom();
			showMainLogOperatorByCounts();
		}
	});
}

function toBottom(){
	  window.scrollTo(0,document.body.scrollHeight);
}

function getOperateLog(){
	$("#operateLog").html(loadding);
	$.ajax({
		url:"/index.php?c=main&a=getOperateLog",
		sync:false,
		success:function(data){
			if(data == '0'){
				data = noResult("暂无操作记录！");
			}
			$("#operateLog").html(data);
		}

	});
	getRevLog();
}

function noResult(str){
	return '<div class="noresult">'+str+'</div>';
}

function getRevLog(){
	$("#revLog").html(loadding);
	$.ajax({
		url:"/index.php?c=main&a=getRevLog",
		sync:false,
		success:function(data){
			if(data == '0'){
				data = noResult("暂无操作记录！");
			}
			$("#revLog").html(data);
		}

	});
}


function showMainLogOperatorByCounts(){
	$.ajax({
	url:"/index.php?c=main&a=showMainLogCount",
	success:function(data){
		if(data>0){
			$("#main_log_operator").show();
			$("#sync_operator").show();
		}else{
			$("#main_log_operator").hide();
			$("#sync_operator").hide();
		}
	}
	})
}
