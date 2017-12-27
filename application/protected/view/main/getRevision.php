{if $result}
{section name=file loop=$result}
    <input name="files[]" value="{$result[file]._input}" checked="true" type="checkbox" />
	版本号:{$result[file].rev};
	文件:{$svnHost}{$result[file].path};
    操作:{$result[file].action};
	作者:{$result[file].author};
	上传时间:{"Y-m-d H:i:s"|date:$result[file].date}
	<br />
{/section}
{else}
<div class="noresult">对不起，没发现相关版本文件,请输入正确的版本号!</div>
{/if}
