<table class="table" cellspacing="1" cellpadding="2" width="99%" align="center" 
border="0" id="xiangmu">
  <tbody>
   <tr>
      <td class="td_bg"  height="23">项目组：<span class="TableRow2"> </span></td>
      <td class="td_bg" >
	  {html_options options=$lists name=pid selected=$data.pid}
	  </td>
   </tr>
   <tr>
      <td class="td_bg"  height="23">项目名称：<span class="TableRow2"> </span></td>
      <td class="td_bg" ><input name="name" value="{$data.name}" /> <span class="TableRow1"></span></td>
    </tr>
  </tbody>
</table>
{if $data}<input type="hidden" name="id"  value="{$data.id}" />{/if}

