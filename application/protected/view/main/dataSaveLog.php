<tr>
      <td  colspan="2" class="td_bg" height="23" id="dataSaveLog">
 {if count($result) eq 0}
<div class="noresult">暂无可上线文件！</div>
{else}
<ul>
{section name=file loop=$result}
	<li {if $result[file].sync_revision lt $result[file].trunk_revision} style="color:red" {/if}>
	<span>
	<input name="main_log_id[]" value="{$result[file].id}"  type="checkbox" />
	项目:{$result[file].category_id|getCategoryName};
	版本号:{$result[file].sync_revision};	
	</span>
	{if $result[file].dev_rev neq 0 and $result[file].dev_rev gt $result[file].sync_revision}
		<span style="color:orange">
		[最新版本：{$result[file].dev_rev}]
		</span>
	{/if}
	<span>
	{if $result[file].trunk_revision neq 0}
	[trunk版本：{$result[file].trunk_revision}]
	{/if}
	文件:{$result[file].sync_path};
	操作:{$result[file].sync_action};
	作者:{$result[file].sync_author};
	提交时间:{"Y-m-d H:i:s"|date:$result[file].sync_date}
	</span>
	</li>
{/section}
</ul>
{/if}
     </td>
</tr>
{if $right->is_save}
    <tr {if $mainLogCount}style="" {else} style="display:none"{/if}>
      <td  colspan="2" class="td_bg" id="main_log_operator">
		<span style="cursor:pointer" onclick="checkAll('main_log_id[]')">全选</span>　
		<span style="cursor:pointer" onclick="choiceOthers('main_log_id[]')">反选</span>　
		<span style="cursor:pointer" onclick="canselAll('main_log_id[]')">取消</span>　
		<span><input type="button" id="deleteLog" value="删除选中" /></span> 
		<span> 总文件数:{$mainLogCount}</span>
      </td>
    </tr>
{/if}
