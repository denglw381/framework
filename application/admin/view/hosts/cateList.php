{include file='../public/header.html'}
{literal}
<script language="javascript">
	function addUser(obj){
		location_str = $(obj).attr('location');
		location.href = location_str;
	}
function Win(){
	var postData = {};
	if(arguments.length > 0) postData ={'pid':arguments[0]};
	if(arguments.length == 2) postData.id = arguments[1];
	$.ajax({
		url:'/index.php?m=admin&c=hosts&a=cateEdit',
		data: postData,
		type:'POST',
		success : function(data){
			ymPrompt.win({message:data,width:300,height:140,title:'添加项目组',handler:'testHd',btn:[['是','yes'],['否','no']]})
		}
	});
}

function testHd(tp){
	if(tp == 'yes'){
		xiangmu_val =$('#xiangmu input').val();
		if(xiangmu_val == '') {
			errorMsg('项目名称不能为空');
		}
		postData = {'name': xiangmu_val};
		postData.pid = $("select[name='pid']").val();
		if($("input[name='id']").val() != undefined)
			postData.id = $("input[name='id']").val();
		$.ajax({
			url:'/index.php?m=admin&c=hosts&a=cateSave',
			type:'POST',
			data:postData,
			success:function(data){
				if(data == 1)
				//	ymPrompt.succeedInfo('添加成功');
				location.reload();
			}
		});
	}
}
function del(id){
	if(confirm('确认删除？')){
		$.ajax({
			url:'/index.php?m=admin&c=hosts&a=cateDel',
			data:{'id':id},
			type:'POST',
			success:function(data){
				if(data == 1){
					alert('操作成功');
					location.reload();
				}else{
					alert('操作失败');
				}
			}
		});
	}
}
</script>
{/literal}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
     	 <th class="bg_tr" align="left" colspan="6" height="25">
		<a href="javascript:;" >项目列表</a>
		<button onclick="Win({$pid|default:0})">添加项目</button>
	 </th>
    </tr>
     <tr>
      <td class="bg_tr" align="center" height="25">id</td>
      <td class="bg_tr" align="center" height="25">项目名</td>
      <td class="bg_tr" align="center" height="25">添加时间</td>
      <td class="bg_tr" align="center" height="25">操作</td>
    </tr>
    {foreach $datas.data as $val}
    <tr>
      <td class="td_bg" width="17%" height="23">{$val.id}<span class="TableRow2"></span></td>
      <td class="td_bg"><a href="{spurl r='admin/hosts/index' id=$val.id}">{$val.name}</a></td>
      <td class="td_bg">{'Y-m-d H:i:s'|date:$val.ctime}</td>
      <td class="td_bg">
	{if $val.location neq 2}<a href="{spurl r='admin/hosts/index' id=$val.id}">查看子主机列表</a>|{/if}<a onclick='Win({$val.pid|default:0}, {$val.id|default:0})' href="javascript:;">编辑</a>|<a href='javascript:;' onclick="del({$val.id})">删除</a></td>
    </tr>
    {/foreach}
    {if $lists.html}
    <tr>
      <td colspan="6" class="td_bg" height="23">{$lists.html}</td>
    </tr>
    {/if}
  </tbody>
</table>
{include file='../public/footer.html'}
