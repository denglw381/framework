<?php
return array(
			#队列配置,每个任务都需要制定一个队列,暂时只支持redis,memcacheqd,gearman队列
			'queue_config' => array(
					'redis_1'=> array(
							'type' => 'redis', //队列类型
							'config' => array(
									'host' => '127.0.0.1:6379',
									'queue_no' => '1',
									), //队列配置
							'process' => array(
									'minsys' => 1, //框架进程数
//									'ci'=>1
									)
							),
							/*

					'memcacheqd_1'=> array(
							'type' => 'memcacheqd', //队列类型
							'config' => array(
									'host' => '127.0.0.1:22201',
									'queue_no' => '1',
							), //队列配置
							'process' => array(
									'minsys' => 1, //框架进程数
									'thinksns' => 1,
									'ci'=>1
							)
					),
					'gearman_1'=> array(
							'type' => 'gearman', //队列类型
							'config' => array(
									'127.0.0.1:4730'
							), //队列配置
							'process' => array(
									'minsys' => 1, //框架进程数
									'ci' => 1, //框架进程数
									'thinksns'=>1
							)
					),
					*/
			),
);	
