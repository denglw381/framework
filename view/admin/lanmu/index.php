{include file='../public/header.html'}
{literal}
<script language="javascript">
	function addUser(obj){
		location_str = $(obj).attr('location');
		location.href = location_str;
	}
</script>
{/literal}
<form method="POST" action="{spurl r='admin/Lanmu/save'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
     	 <th class="bg_tr" align="left" colspan="6" height="25">
		<a href="{spurl r='admin/lanmu/index'}" >顶级栏目列表</a>
		{if $location.tid}
		&gt;&gt; <a href="{spurl r='admin/lanmu/index id=$location.tid}" >子级栏目列表</a>
			{if $location.lid}
			&gt;&gt; <a href="{sprul r='admin/lanmu/index' id=$location.lid}" >二级栏目列表</a>
			{/if}
		{/if}
	　	<input onclick="javascript:addUser(this)" location="{spurl r='admin/lanmu/add' pid=$id}" type="button" value="添加栏目" />
	</th>
    </tr>
     <tr>
      <td class="bg_tr" align="center" height="25">id</td>
      <td class="bg_tr" align="center" height="25">栏目名</td>
      <td class="bg_tr" align="center" height="25">添加时间</td>
      <td class="bg_tr" align="center" height="25">操作</td>
    </tr>
    {foreach $lists.data as $val}
    <tr>
      <td class="td_bg" width="17%" height="23">{$val.id}<span class="TableRow2"></span></td>
      <td class="td_bg">{$val.name}</td>
      <td class="td_bg">{'Y-m-d H:i:s'|date:$val.ctime}</td>
      <td class="td_bg">
	{if $val.location neq 2}<a href="{'spurl admin/lanmu/index'} id=$val.id location=$val.location}">查看子一级栏目列表</a>|{/if}<a href="{spurl r='admin/lanmu/edit' id=$val.id}">编辑</a>|<a href="">删除</a></td>
    </tr>
    {/foreach}
    {if $lists.html}
    <tr>
      <td colspan="6" class="td_bg" height="23">{$lists.html}</td>
    </tr>
    {/if}
  </tbody>
</table>
</form>
{include file='../public/footer.html'}
