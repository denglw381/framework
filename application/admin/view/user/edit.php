{include file='../public/header.html'}
{literal}
<script language="javascript">
<!--
	function checkSubmit(){
		$result = checkEmpty("input[name='uname']",'用户名');	
		if($result) $result = checkEmpty("input[name='email']",'邮件');	
        if($("input[name='uid']").val() == 0) 
		if($result) $result = checkEmpty("input[name='passwd']",'密码');	
		if($result) $result = checkEmpty("input[name='role']",'角色');	
		return $result;
	}

	function checkEmpty(lables,message){
		if($.trim($(lables).val())==''){
			errorMsg(message+"不能为空");
			$(lables).focus();
			return false;
		}
		return true;
	}
-->
</script>
{/literal}
<form method="POST" action="{'admin/user'|spUrl:'doSave'}" onsubmit="return checkSubmit()">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25"> <a href="{'admin/user/index'|spUrl}">返回用户列表</a> &gt;&gt; 添加用户</th>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">用户名： <span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='uname' value='{$user.uname}' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">邮箱：　<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='email' value='{$user.email}'/><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">密码：<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg"><strong><input type="text" name='passwd' /><span class="TableRow1"></span></strong></td>
    </tr>
    <tr>
      <td class="td_bg" width="17%" height="23">角色：<span class="TableRow2"></span></td>
      <td width="83%" class="td_bg">
				<input name='role' type='radio' value="2" {if $user.role neq 1}checked{/if}/> 普通用户
				<input name='role' type='radio' value="1" {if $user.role eq 1}checked{/if}/> 管理员
	</td>
    </tr>
    <tr>
      <td colspan="2" class="td_bg" height="23">
		<input type="hidden" name="uid" value="{$user.uid|default:0}" />
		<input type="submit" value="提交" />
		<input type="reset" value="重置" />
	<span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
{include file='../public/footer.html'}
