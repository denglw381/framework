{foreach from=$logs item=log}
&nbsp;{$log.operate}&nbsp;{"Y-m-d H:i:s"|date:$log.operate_ctime}<br />
{/foreach}
