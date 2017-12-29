{include file='../public/header.html'}
{literal}
<script languae="javascript">
$(
	function(){
		$("input[type='reset']").bind('click', function(){
			$("input[name='right[]']").removeAttr("checked", false);	
		})
	}
)
</script>
{/literal}
<form method="POST" action="{'admin/user'|spUrl:'saveRight'}">
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25">用户权限管理</th>
    </tr>
    <tr>
	<td class="td_bg" width="17%" height="23" colspan="2">
		<input name="right[]" type="checkbox" value="save" {if $right->is_save eq true}checked{/if} />保存
		<input name="right[]" type="checkbox" value="sync" {if $right->is_sync eq true}checked{/if}  />同步到测试
		<input name="right[]" type="checkbox" value="sync1" {if $right->is_sync1 eq true}checked{/if} />同步到tzh 
		<input name="right[]" type="checkbox" value="syncN" {if $right->is_syncN eq true}checked{/if} />同步到线上 
 		<span class="TableRow2"></span>
	</td>
    </tr>
    <tr>
      <td colspan="2" class="td_bg" height="23">
		<input type="hidden" name="uid" value="{$uid}" />
		<input type="submit" value="保存" />
		<input type="reset" value="重置" />
	<span class="TableRow2"></span></td>
    </tr>
  </tbody>
</table>
</form>
{include file='../public/footer.html'}
