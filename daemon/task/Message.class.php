<?php
class Message{
	function run($data){
		return DM_TASK_RET_RETRY; //重新处理
		return DM_TASK_RET_FAILED; //处理失败
		return DM_TASK_RET_SUCCESS; //成功处理
	}	
}
