{include file='../public/header.html'}
<form method="POST" action="{spurl r='admin/HostsCategory/save'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
     	 <th class="bg_tr" align="left" colspan="2" height="25"><a href="{spurl r='admin/HostsCategory/index'}">返回项目组列表</a>　&gt;&gt; 添加项目组
	{if $data.id}<input type='hidden' name='id' value={$data.id} />{/if}
	</th>
    </tr>
        <tr style="display:none">
    <td class="td_bg">父类id： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='pid' value='{$data.pid}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">项目名称： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='name' value='{$data.name}' /><span class="TableRow1"></span></strong></td>
    </tr>
     <tr style="display:none">
    <td class="td_bg">项目状态(1,正常; 2,删除)： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='status' value='{$data.status}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
      <td colspan="2" class="td_bg" height="23"><input type="submit" value="提交" /><input type="reset" value="重置" /><span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
{include file='../public/footer.html'}

