<?php
return	array(
		//队列处理类名
			'queue_handle' => array(
				'redis' 	 => 'DmRedis',	
				'gearman' 	 => 'DmGearMan',
				'memcacheqd' => 'DmMemcacheqd', //开源的memcacheq
				'memcacheqd_xp' => 'DmMemcacheqdXp', //祥鹏版本的memcacheq
			),
		//日志路径
			'log'=>array(
				'path' => dirname(__FILE__).'/../log/',
			),  
			'daemon' => true, //是否作为守护进行运行
			'max_retry_num' => 6, //任务的最大重试次数
			'task_path' => DM_PATH.'/task', //任务路径
			'puid' => '0' //以什么身份运行,只有在以root身份运行时才有效
);
