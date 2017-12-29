{include file='../public/header.html'}
{literal}
<SCRIPT language=javascript>
	function selectSons(id, obj){
		if($(obj).attr('checked') == false){
			$('#'+id+' input').attr('checked', false);
		}else{
			$('#'+id+' input').attr('checked', true);
		}
	}
</SCRIPT>
{/literal}
<table id="{$data.id}" class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25">为管理员 "{$userInfo.uname}" 设置权限</th>
    </tr>
  </tbody>
</table>

<form action="{'admin/Lanmu'|spUrl:'saveRight'}" method="post">
{foreach $datas as $data}
<table id="{$data.id}" class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25"><input type='checkbox' onclick='selectSons({$data.id}, this)' />{$data.name}</th>
    </tr>
	{foreach $data.llms as $lm}
   	 <tr id='{$lm.id}'>
     		 <td class="td_bg" width="17%" height="23"><input onclick='selectSons({$lm.id}, this)' type='checkbox' />{$lm.name}<span class="TableRow2"></span></td>
		 <td width="83%" class="td_bg">
			{foreach $lm.sons as $son}
			<input name="right[]" type='checkbox' value='{$son.tid},{$son.lid},{$son.id}' {if $son.hasRight} checked {/if}/>{$son.name}<span class="TableRow1"></span>
			{/foreach}
		</td>
   	 </tr>
	{/foreach}
  </tbody>
</table>
{/foreach}
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
   <tr>
      <td colspan="2" class="td_bg" height="23">
		<input type="hidden" name="uid" value="{$uid}" />
		<input type="submit" value="保存权限设置" />
		<input type="reset" value="清空" /><span class="TableRow2"></span>
	</td>
    </tr>
  </tbody>
</table>
</form>
{include file='../public/footer.html'}

