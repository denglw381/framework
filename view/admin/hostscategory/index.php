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
      <th class="bg_tr" align="left" colspan="6" height="25">列表　<input onclick="javascript:addUser(this)" location="{spurl r='admin/HostsCategory/add'}" type="button" value="添加数据" /></th>
    </tr>
	<tr>
		<td class="bg_tr" align="left" colspan="6" height="25">
		<form action="{spurl r='admin/HostsCategory/search'}" id="search" method="post">
			<input name='keyword' value="{$keyword}" />
		        <input type="submit" value="搜索" />
		</form>
		</td>
	</tr>
     <tr>
      <td class="bg_tr" align="center" height="25">id</td>
	  {*
      <td class="bg_tr" align="center" height="25">父类id</td>
	  *}
      <td class="bg_tr" align="center" height="25">项目名称</td>
	  {*
      <td class="bg_tr" align="center" height="25">项目状态(1,正常; 2,删除)</td>
      <td class="bg_tr" align="center" height="25">ctime</td>
	  *}
      <td class="bg_tr" align="center" height="25">管理</td>
     </tr>
    {foreach $lists.data as $val}
    <tr>
      <td class="td_bg">{$val.id}</td>
	  {*
      <td class="td_bg">{$val.pid}</td>
	  *}
      <td class="td_bg"><a href="{spurl r='admin/Hosts/cateList' pid=$val.id}">{$val.name}</a></td>
	  {*
      <td class="td_bg">{$val.status}</td>
      <td class="td_bg">{$val.ctime}</td>
	  *}
      <td class="td_bg">
	  	<a href="{spurl r='admin/Hosts/cateList' pid=$val.id}">项目列表</a>
		| <a href="{spurl r='admin/HostsCategory/edit' id=$val.id}">编辑</a>
		{*
		|<a href="{spurl r='admin/HostsCategory/delete' id=$val.id}" class="_del" >删除</a>
		*}
	  </td>
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

