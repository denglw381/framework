{include file="../public/header.html"}
<form method="POST" action="{spurl r='admin/Agent/save'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
     	 <th class="bg_tr" align="left" colspan="2" height="25"><a href="{spurl r='admin/Agent/index'}">返回代理主机列表</a>　&gt;&gt; 添加代理数据
	{if $data.id}<input type='hidden' name='id' value={$data.id} />{/if}
	</th>
    </tr>
        <tr>
    <td class="td_bg">代理名称： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='name' style="width:300px" value='{$data.name}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">主机地址或ip： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='host' style="width:400px" value='{$data.host}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">用户名： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='uname' style="width:400px" value='{$data.uname}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">密码： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="password" style="width:400px"  name='pwd' value='{$data.pwd}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">脚本地址： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input style="width:500px" type="text" name='webdir' value='{$data.webdir}' /><span class="TableRow1"></span></strong></td>
    </tr>
	{*
    <tr>
    <td class="td_bg">类型： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='type' value='{$data.type}' /><span class="TableRow1"></span></strong></td>
    </tr>
         <tr>
    <td class="td_bg">是否被删除： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='isdel' value='{$data.isdel}' /><span class="TableRow1"></span></strong></td>
    </tr>
	*}
         <tr>
    <td class="td_bg">代理方式： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong>{html_options name=sync options=$syncs selected=$data.sync}<span class="TableRow1"></span></strong></td>
    </tr>
       <tr>
      <td colspan="2" class="td_bg" height="23"><input type="submit" value="提交" /><input type="reset" value="重置" /><span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
{include file="../public/footer.html"}

