{include file="public/header.html"}
<form method="POST" action="{spurl r='admin/main/doChangePwd'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25">修改密码</th>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">请输入当前密码： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="password" name='oldPwd' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">请输入新密码：　<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="password" name='newPwd' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">请再次输入新密码<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="password" name='newPwd1' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td colspan="2" class="td_bg" height="23"><input type="submit" value="提交" /><input type="reset" value="重置" /><span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
{include file="public/footer.html"}
