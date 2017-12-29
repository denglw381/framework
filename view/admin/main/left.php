<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACT内容管理系统</title>
<link href="/static/css/left_css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/static/js/jquery-1.4.2.js"></script>
</head>
<SCRIPT language=JavaScript>
function showsubmenu(sid)
{
	whichEl = eval("submenu" + sid);
	if (whichEl.style.display == "none")
	{
		eval("submenu" + sid + ".style.display=\"\";");
	}
	else
	{
		eval("submenu" + sid + ".style.display=\"none\";");
	}
}
</SCRIPT>
<body bgcolor="16ACFF">
<table width="98%" border="0" cellpadding="0" cellspacing="0" background="/static/images/tablemde.jpg">
  <tr>
    <td height="5" background="/static/images/tableline_top.jpg" bgcolor="#16ACFF"></td>
  </tr>
  <tr>
    <td><TABLE width="97%" 
border=0 align=right cellPadding=0 cellSpacing=0 class=leftframetable>
      <TBODY>
	<TR>
	  <TD height="25" style="background:url(/static/images/left_tt.gif) no-repeat">
	    <table width="100%" border="0" cellspacing="0" cellpadding="0">
	      <tr>
		<TD width="20"></TD>
	  <TD class="STYLE1" style="CURSOR: hand" onclick="showsubmenu(1);" height="25">主机设置</TD>
	      </tr>
	    </table>           
		 </TD>
	  </TR>
        <TR>
          <TD><TABLE id="submenu1" cellSpacing="0" cellPadding="0" width="100%" border="0">
              <TBODY>
                <TR>
                  <TD width="2%"><IMG src="/static/images/closed.gif"></TD>
                  <TD height="23"><A href="{spurl r='admin/hosts/cateList'}"   target="main">项目列表</A></TD>
                </TR>
{*
                <TR>
                  <TD width="2%"><IMG src="/static/images/closed.gif"></TD>
                  <TD height="23"><A href="{spurl r='admin/hosts/index'}"   target="main">主机列表</A></TD>
                </TR>
*}
              </TBODY>
          </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_bottom.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_top.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td><table class="leftframetable" cellspacing="0" cellpadding="0" width="97%" align="right" 
border="0">
      <tbody>
        <tr>
          <td height="25" style="background:url(/static/images/left_tt.gif) no-repeat"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20"></td>
          <td height="25" style="CURSOR: hand" onClick="showsubmenu(2);"><span class="STYLE1">用户管理</span></td>
              </tr>
            </table></td>
          </tr>
        <tr>
          <td><table id="submenu2" cellspacing="0" cellpadding="0" width="100%" border="0">
              <tbody>
                <tr>
                  <td width="2%"><img src="/static/images/closed.gif" /></td>
		  <td height="23"><a href="{spurl r='admin/user/index'}" target="main">用户列表</a></td>
                </tr>
                <tr>
                  <td width="2%"><img src="/static/images/closed.gif" /></td>
		  <td height="23"><a href="{spurl r='admin/user/add'}" target="main">添加用户</a></td>
                </tr>
{*
                <tr>
                  <td><img src="/static/images/closed.gif" /></td>
		  <td height="23">
			<a href="{spurl r='admin/user/right'}" target="main">权限管理</a>
		 </td>
                </tr>
*}
	      </tbody>
          </table></td>
        </tr>
      </tbody>
    </table></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_bottom.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_top.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td><TABLE class=leftframetable cellSpacing=0 cellPadding=0 width="97%" align=right border=0>
      <TBODY>
        <TR>
          <TD height="25" style="background:url(/static/images/left_tt.gif) no-repeat"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <TD width="20"></TD>
          <TD class=STYLE1 style="CURSOR: hand" onclick=showsubmenu(5); height=25>后台栏目管理</TD>
            </tr>
          </table></TD>
          </TR>
        <TR>
          <TD><TABLE id=submenu5 cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
                <TR>
                  <TD width="2%"><IMG src="/static/images/closed.gif"></TD>
                  <TD height=23><A href="{spurl r='admin/lanmu/index'}" target=main>后台栏目管理</A> </TD>
             </TR>
{*
                <TR>
                  <TD><IMG src="/static/images/closed.gif"></TD>
                  <TD height=23><A   href="{spurl r='admin/lanmu/right'}"  target=main>后台栏目权限设置 </A></TD>
                </TR>
*}
              </TBODY>
          </TABLE></TD>
        </TR>
      </TBODY>
    </TABLE></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_bottom.jpg" bgcolor="#9BC2ED"></td>
  </tr>
{foreach $datas as $data}
  <tr>
    <td height="5" background="/static/images/tableline_top.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td><TABLE class=leftframetable cellSpacing=0 cellPadding=0 width="97%" align=right border=0>
      <TBODY>
        <TR>
          <TD height="25" style="background:url(/static/images/left_tt.gif) no-repeat"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <TD width="20"></TD>
          <TD class=STYLE1 style="CURSOR: hand" onclick=showsubmenu({$data@index}); height=25>{$data.name}</TD>
            </tr>
          </table></TD>
          </TR>
	{foreach $data.sons as $son}
        <TR>
          <TD><TABLE id=submenu9 cellSpacing=0 cellPadding=0 width="100%" border=0>
              <TBODY>
                <TR>
                  <TD width="2%"><IMG src="/static/images/closed.gif"></TD>
                  <TD height=23><a href="{spurl r=$son.controller/$son.action}" target=main>{$son.name}</A> </TD>
                </TR>
              </TBODY>
          </TABLE></TD>
	{/foreach}
        </TR>
      </TBODY>
    </TABLE></td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_bottom.jpg" bgcolor="#9BC2ED"></td>
  </tr>
{/foreach}
  <tr>
    <td height="5" background="/static/images/tableline_top.jpg" bgcolor="#9BC2ED"></td>
  </tr>
  <tr>
    <td height="8">
<TABLE class=leftframetable cellSpacing=1 cellPadding=1 width="97%" align=right 
border=0>
      <TBODY>
        <TR>
          <TD height="25" style="background:url(/static/images/left_tt.gif) no-repeat"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <TD width="20"></TD>
          <TD class=STYLE1 height=25>系统信息</TD>
            </tr>
          </table></TD>
          </TR>
        <TR>
          <TD 
      height=105><span class="STYLE2"><IMG src="/static/images/closed.gif">版权所有：邓联文<br>
              <IMG src="/static/images/closed.gif">技术支持：<a href="mailto:nenu_1@126.com" target="_blank">nenu_1@126.com</a><br>
              <IMG src="/static/images/closed.gif">帮助中心：<a href="mailto:nenu_1@126.com" target="_blank">nenu_1@126.com</a><br>
            <IMG src="/static/images/closed.gif">系统版本：1.0</span></TD>
        </TR>
      </TBODY>
    </TABLE>	</td>
  </tr>
  <tr>
    <td height="5" background="/static/images/tableline_bottom.jpg"></td>
  </tr>
</table>
</body></html>

