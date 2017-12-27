{include file="../public/header.html"}
<script language="javascript">
{literal}
function delHosts(hostid){
	if(confirm("确认删除？")){
		$.ajax({
			url:'/index.php?m=admin&c=hosts&a=delete',
			data:{'id':hostid},
			success:function(data){
				if(data == '1'){
					alert("删除成功");
					location.reload();
				}else{
					alert("删除失败");
				}	
			}
		})
	}
}

function addHost(category_id){
	location.href = '/index.php?m=admin&c=hosts&a=edit&category_id='+category_id;
}
{/literal}
</script>
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25"><a href="{'admin/hosts'|spUrl:'cateList'}">返回项目列表</a> &gt;&gt; {$cat.name} &nbsp;&nbsp;&nbsp;<button onclick='addHost({$category_id})'>添加主机</button></th>
    </tr>
  </tbody>
</table>
{if $devBranch}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">svn开发分支</th>
    </tr>
    <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="75%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
      <td class="td_bg" width="20%">所在文件夹<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$devBranch item=item}
    <tr>
      <td class="td_bg" >{$item.id}</td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
      <td class="td_bg" >{$item.uname}</td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
      <td class="td_bg" >{$item.webdir}</td>
	  *}
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a  href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
    </tbody>
</table>
{/if}

{if $onlineBranch}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">svn tzh分支</th>
    </tr>
    <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="75%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
      <td class="td_bg" width="20%">所在文件夹<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$onlineBranch item=item}
    <tr>
      <td class="td_bg" >{$item.id}</td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
      <td class="td_bg" >{$item.uname}</td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
      <td class="td_bg" >{$item.webdir}</td>
	  *}
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a  href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
    </tbody>
</table>
{/if}


{if $trunk}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">svn主分支</th>
    </tr>
    <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="75%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
      <td class="td_bg" width="20%">所在文件夹<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$trunk item=item}
    <tr>
      <td class="td_bg" >{$item.id}</td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
      <td class="td_bg" >{$item.uname}</td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
      <td class="td_bg" >{$item.webdir}</td>
	  *}
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
  </tbody>
</table>
{/if}
{if $test}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">测试主机 </th>
    </tr>
     <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="45%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="30%">所在文件夹<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$test item=item}
    <tr>
      <td class="td_bg" >{$item.id}</td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
	  *}
      <td class="td_bg" >{$item.webdir}</td>
      <td class="td_bg" >{$item.uname}</td>
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
  </tbody>
</table>
{/if}
{if $online1}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">tzh主机 </th>
    </tr>
     <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="45%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="30%">所在文件夹<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$online1 item=item}
    <tr>
      <td class="td_bg" >{$item.id}</td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
	  *}
      <td class="td_bg" >{$item.webdir}</td>
      <td class="td_bg" >{$item.uname}</td>
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
  </tbody>
</table>
{/if}
{if $online}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="6" height="25">线上主机 </th>
    </tr>
     <tr>
      <td class="td_bg" width="5%">id<span class="TableRow1"></span></td>
      <td class="td_bg" width="45%" height="23">地址/ip<span class="TableRow2">(IP:)</span></td>
	  {*
      <td class="td_bg" width="10%">密码<span class="TableRow1"></span></td>
	  *}
      <td class="td_bg" width="30%">所在文件夹<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">用户名<span class="TableRow1"></span></td>
      <td class="td_bg" width="10%">操　作<span class="TableRow1"></span></td>
    </tr>
     {foreach from=$online item=item}
    <tr>
      <td class="td_bg" >{$item.id}<span class="TableRow1"></span></td>
      <td class="td_bg"  height="23">{$item.host}<span class="TableRow2"></span></td>
	  {*
      <td class="td_bg" >{$item.pwd}</td>
	  *}
      <td class="td_bg" >{$item.webdir}</td>
      <td class="td_bg" >{$item.uname}</td>
      <td class="td_bg" ><a href="/index.php?m=admin&c=hosts&a=edit&id={$item.id}">[修改]</a><a href="javascript:;" onclick="delHosts({$item.id})">[删除]</a></td>
    </tr>
     {/foreach}
  </tbody>
</table>
{/if}
{include file="../public/footer.html"}

