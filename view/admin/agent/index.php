{include file='../public/header.html'}
{literal}
<script language="javascript">
	function addUser(obj){
		location_str = $(obj).attr('location');
		location.href = location_str;
	}
</script>
{/literal}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="11" height="25">代理主机列表　<input onclick="javascript:addUser(this)" location="{spurl r='admin/Agent/add'}" type="button" value="添加代理" /></th>
    </tr>
	<tr>
		<td class="bg_tr" align="left" colspan="11" height="25">
		<form action="{spurl r='admin/Agent/search'}" id="search" method="post">
			<input name='keyword' value="{$keyword}" />
		        <input type="submit" value="搜索" />
		</form>
		</td>
	</tr>
     <tr>
      <td class="bg_tr" align="center" height="25">id</td>
      <td class="bg_tr" align="center" height="25">代理名称</td>
      <td class="bg_tr" align="center" height="25">主机IP/地址</td>
	  {*
      <td class="bg_tr" align="center" height="25">用户名</td>
      <td class="bg_tr" align="center" height="25">密码</td>
	  *}
      <td class="bg_tr" align="center" height="25">脚本地址</td>
	  {*
      <td class="bg_tr" align="center" height="25">创建世界</td>
      <td class="bg_tr" align="center" height="25">类型</td>
      <td class="bg_tr" align="center" height="25">是否被删除</td>
	  *}
      <td class="bg_tr" align="center" height="25">代理方式</td>
      <td class="bg_tr" align="center" height="25">管理</td>
     </tr>
    {foreach $lists.data as $val}
    <tr>
      <td class="td_bg">{$val.id}</td>
      <td class="td_bg">{$val.name}</td>
      <td class="td_bg">{$val.host}</td>
	  {*
      <td class="td_bg">{$val.uname}</td>
      <td class="td_bg">{$val.pwd}</td>
	  *}
      <td class="td_bg">{$val.webdir}</td>
	  {*
      <td class="td_bg">{$val.ctime}</td>
      <td class="td_bg">{$val.type}</td>
      <td class="td_bg">{$val.isdel}</td>
	  *}
      <td class="td_bg">{$syncs[$val.sync]}</td>
      <td class="td_bg"><a href="{spurl r='admin/Agent/edit' id=$val.id}">编辑</a>|<a href="{spurl r='admin/Agent/delete' id=$val.id}" class="_del" >删除</a></td>
    </tr>
    {/foreach}
    {if $lists.html}
    <tr>
      <td colspan="11" class="td_bg" height="23">{$lists.html}</td>
    </tr>
    {/if}
  </tbody>
</table>
{include file='../public/footer.html'}

