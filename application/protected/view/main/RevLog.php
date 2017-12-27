{foreach $msg.key as $k=>$v}
&nbsp;<b>{$v.name}版本保存历史：</b><br />
	{foreach $msg.msg.$k as $log}
	&nbsp;保存时间：{"Y-m-d H:i:s"|date:$log.ctime} 版本号：{$log.rev}<br />
	{/foreach}
{/foreach}
