<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$title}</title>
<script type="text/javascript" src="/static/js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="/static/js/prompt/ymPrompt.js"></script>
<script type="text/javascript" src="/static/js/backend.js"> </script>
<link rel="stylesheet" id='skin' type="text/css" href="/static/js/prompt/skin/dmm-green/ymPrompt.css" />
<link href="/static/css/top_css.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#03A8F6">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="194" height="60" align="center" background="/static/images/top_logo.jpg"><font style='font-size:25px; font-weight:bold'>后台管理系统</font></td>
    <td align="center" style="background:url(/static/images/top_bg.jpg) no-repeat"><table cellspacing="0" cellpadding="0" border="0" width="100%" height="33">
      <tbody>
        <tr>
          <td width="30" align="left"><img onClick="switchBar(this)" height="15" alt="关闭左边管理菜单" src="/static/images/on-of.gif" width="15" border="0" /></td>
	  <td width="320" align="left">
		<a class="top_link" href="javascript:;" target="main">您好！{$userInfo.uname}</a><span class="top_link">┆</span> 
		<a class="top_link" href="{spurl r='admin/main/changePwd'}" target="main">修改密码</a><span class="top_link">┆</span> 
		<a class="top_link" href="{spurl r='main/index'}" target="_parent">查看前台</a><span class="top_link">┆</span> 
		<a class="top_link" id="loginOut" onclick="return loginout()" href="{spurl r='admin/main/loginOut'}">退出登录</a>
	 </td>
          <td width="80" align="right" nowrap="nowrap" class="topbar">  </td>
          <td class="topbar"><a href="javascript:;" target="_blank"><img title="返回首页" height="23"  src="/static/images/home.gif" width="33" border="0" /></a>&nbsp;</td>
        </tr>
      </tbody>
    </table>
    <table height="26" border="0" align="left" cellpadding="0" cellspacing="0" class="subbg" name="t1">
      <tbody>
        <tr align="middle">
	{foreach $topLanmus as $lanmu}
          <td width="71" height="26" align="center" valign="middle" background="static/images/top_tt_bg.gif"><a href="{spurl r='admin/main/left' id=$lanmu.id}" target="leftFrame" class="STYLE2">{$lanmu.name}</a></td>
	  {if $lanmu@last}<td align="center" class="topbar"><span class="STYLE2"> </span></td>{/if}
	{/foreach}
	</tr>
      </tbody>
    </table>
    </td>
  </tr>
  <tr height="6">
    <td bgcolor="#1F3A65" background="/static/images/top_bg.jpg"></td>
  </tr>
</table>
{literal}
<script language="javascript">
<!--
var displayBar=true;
function switchBar(obj){
	if (displayBar)
	{
		parent.frame.cols="0,*";
		displayBar=false;
		obj.title="打开左边管理菜单";
	}
	else{
		parent.frame.cols="195,*";
		displayBar=true;
		obj.title="关闭左边管理菜单";
	}
}

function loginout(){
	url_val = $('#loginOut').attr('href');
	$.ajax({
		url:url_val,
		success:function(data){
			parent.location.reload();
		}
	});
	return false;
}
-->
</script>
{/literal}
</body>
</html>

