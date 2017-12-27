{include file="../public/header.html"}
<form method="POST" action="{'admin/hosts'|spUrl:'doedit'}" onsubmit="return checkSubmit();" >
<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0">
  <tbody>
    <tr>
      <th class="bg_tr" align="left" colspan="2" height="25"><a href="{'admin/hosts'|spUrl:index}">返回项目列表</a> &gt;&gt; <a href="{'admin/hosts'|spUrl:index}&id={$categorys_info.id}">{$categorys_info.name}</a> &gt;&gt; {if $hostDetail.id}修改{else}添加{/if}主机
     </th>
    </tr>
    <tr>
      <td class="td_bg"  height="23">选择项目组：<span class="TableRow2"> </span></td>
      	<td class="td_bg" >
		{html_options name=top_category options=$top_categorys selected=$categorys_info['pid'] style='width:150px'}
		<span class="TableRow1"></span>
	</td>
    </tr>

	<script language="javascript">
	$("select[name='top_category']").bind('change', function(){
				_id = $(this).val();
				$.ajax(
					{
						url:'/index.php?c=HostsCategory&a=get&id='+_id,
						type:'GET',
						dataType:'json',
						success:function(data){
							str = '';
							for(var i in data){
								str +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
								$("select[name='category_id']").html(str);
							}
						}
					}
				)
		}
	)
	</script>

    <tr>
      <td class="td_bg"  height="23">选择项目：<span class="TableRow2"> </span></td>
      	<td class="td_bg" >
		{html_options name=category_id options=$categorys selected=$category_id style='width:150px'}
		<span class="TableRow1"></span>
	</td>
    </tr>
	<tr>
      <td class="td_bg"  height="23">主机类型：<span class="TableRow2"> </span></td>
      <td class="td_bg" >
		{html_options name=type options=$remote_host selected=$hostDetail['type'] style='width:150px'}
        <span class="TableRow1"></span>

      </td>
    </tr>
    <tr>
      <td class="td_bg" width="10%" height="23">服务器ip或地址：</td>
      <td class="td_bg" width="90%"><input type="text" name="host" style="width:500px" value="{$hostDetail.host}" /> <span class="TableRow1"></span></td>
    </tr>
    <tr>
      <td class="td_bg" height="23">用户名：<span class="TableRow2"></span></td>
      <td class="td_bg"><input type="text" name="uname" value="{$hostDetail.uname}" /> <span class="TableRow1"></span></td>
    </tr>
    <tr>
      <td class="td_bg"  height="23">密　码：<span class="TableRow2"> </span></td>
      <td class="td_bg" ><input type="password" name="pwd" value="{$hostDetail.pwd|htmlentities}" /> <span class="TableRow1"></span></td>
    </tr>
    <tr>
      <td class="td_bg"  height="23">文件路径：<span class="TableRow2"> </span></td>
      <td class="td_bg" ><input type="text" style="width:500px" name="webdir" value="{$hostDetail.webdir}" /> <span class="TableRow1"></span></td>
    </tr>
    <tr>
      <td class="td_bg"  height="23">上传工具：<span class="TableRow2"> </span></td>
      <td class="td_bg" >
	  			{html_options name=sync options=$syncMethod selected=$hostDetail.sync}
                <span class="TableRow1"></span>
      </td>
    </tr>
    <tr>
      <td class="td_bg"  height="23">代理服务器：<span class="TableRow2"> </span></td>
      <td class="td_bg" >{html_options name=agent options=$agents selected=$hostDetail.agent}
	  {*
	  <input name="agent" style="width:500px" value="{$hostDetail.agent}" /> 
	  *}
	  <span class="TableRow1"></span></td>
    </tr>
    <tr>
      <td class="td_bg" colspan="2"  height="23"><input type="submit" value="保存" /> <span class="TableRow2"> </span></td>
    </tr>
  </tbody>
</table>
{if $hostDetail}<input type="hidden" name="id" value="{$hostDetail.id}" />{/if}
</form>
{include file="../public/footer.html"}
