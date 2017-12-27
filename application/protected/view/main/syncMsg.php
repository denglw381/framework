{if $syncMsg}
{foreach from=$syncMsg.key key="key" item="log"}
&nbsp;{$log}&nbsp;<br />
	{foreach $syncMsg.msg.$key as $val}
		&nbsp;{$val}&nbsp;<br />
	{/foreach}
{/foreach}
{else}
<div class="noresult">哎，没有同步纪录，请检查网络与服务器配置是否正常!</div>
{/if}
