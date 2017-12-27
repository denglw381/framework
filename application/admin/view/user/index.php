{include file='../public/header.html'}
{literal}
<script language="javascript">
function addUser(obj){
		location_str = $(obj).attr('location');
		location.href = location_str;
}

function del(id){
	if(confirm('确认删除？')){
		$.ajax({
			url:'/index.php?m=admin&c=user&a=delete',
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
      <th class="bg_tr" align="left" colspan="6" height="25">用户列表　<input onclick="javascript:addUser(this)" location="{'admin/user'|spUrl:'add'}" type="button" value="添加用户" /></th>
    </tr>
     <tr>
      <td class="bg_tr" align="center" height="25">id</td>
      <td class="bg_tr" align="center" height="25">用户名</td>
      <td class="bg_tr" align="center" height="25">邮箱</td>
      <td class="bg_tr" align="center" height="25">角色</td>
      <td class="bg_tr" align="center" height="25">添加时间</td>
      <td class="bg_tr" align="center" height="25">操作</td>
    </tr>
    {foreach $lists.data as $val}
    <tr>
      <td class="td_bg" width="17%" height="23">{$val.uid}<span class="TableRow2"></span></td>
      <td class="td_bg">{$val.uname}</td>
      <td class="td_bg">{$val.email}</td>
      <td class="td_bg" align="center">{if $val.role eq 1}管理员{else}普通用户{/if}</td>
      <td class="td_bg">{'Y-m-d H:i:s'|date:$val.ctime}</td>
      <td class="td_bg">
	  <!--
	  {if $val.role eq 1}<a href="{'admin/lanmu'|spUrl:'right'}&uid={$val.uid}">后台权限管理</a>|{/if}
	  -->
	  <a href="{'admin/user'|spUrl:'right'}&id={$val.uid}">用户权限管理</a>|<a href="{spurl r='admin/user/project' id=$val.uid}">项目管理</a>|<a href="{'admin/user'|spUrl:'edit'}&id={$val.uid}">编辑</a>{if $val.uid neq 1}|<a href="javascript:;" onclick="del({$val.uid})">删除</a>{/if}</td>
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

