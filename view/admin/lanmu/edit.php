<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/static/css/css.css" rel="stylesheet" type="text/css">
</head>
<body>
<form method="POST" action="{'admin/Lanmu'|spUrl:'save'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
     	 <th class="bg_tr" align="left" colspan="2" height="25">添加栏目
	{if $data.id}<input type='hidden' name='id' value={$data.id} />{/if}
	{if $pid}<input type='hidden' name='pid' value={$pid} />{/if}
	</th>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">所属栏目： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong>顶级栏目<input type="hidden" name='location' value='{$data.location|default:0}' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">栏目名： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='name' value='{$data.name}' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">controller：<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='controller' value='{$data.controller}'/><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">action：<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='action' value='{$data.action}' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td colspan="2" class="td_bg" height="23"><input type="submit" value="提交" /><input type="reset" value="重置" /><span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
</body>
</html>

