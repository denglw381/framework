<?php
return array(
		'auto_model'=>false, //自动生成model;
		'recodeslowsql'=>TRUE, //记录慢的sql语句
		'showsqlinpage'=>FALSE,//在页面显示sql语句和运行时间

		'mode' => 'debug', // 应用程序模式，默认为调试模式
		'sp_core_path' => SP_PATH.'/Core', // 框架MVC核心目录
		'sp_drivers_path' => SP_PATH.'/Drivers', // 框架各类驱动文件目录
		'sp_include_path' => array( SP_PATH.'/Extensions' ), // 框架扩展功能载入路径

        'launch' => array( 
                'router_prefilter' => array( 
                        array('spUrlRewrite', 'setReWrite'), 
                        ),
                'function_url' => array(
                        array("spUrlRewrite", "getReWrite"),
                        ),
        ),

		'auto_load_controller' => array('spArgs'), // 控制器自动加载的扩展类名
		'auto_load_model' => array('spPager','spVerifier','spCache','spLinker'), // 模型自动加载的扩展类名

		'sp_error_show_source' => 5, // spError显示代码的行数
		'sp_error_throw_exception' => FALSE, // 是否抛出异常
		'allow_trace_onrelease' => FALSE, // 是否允许在部署模式下输出调试信息
		'sp_notice_php' => SP_PATH."/Misc/notice.php", // 框架默认的错误提示程序

		'inst_class' => array(), // 已实例化的类名称
		'import_file' => array(), // 已经载入的文件
		'sp_access_store' => array(), // 使用spAccess保存到内存的变量
		'view_registered_functions' => array(), // 视图内注册的函数记录

		'auto_session' => TRUE, // 是否自动开启SESSION支持
		'dispatcher_error' => "spError('路由错误，请检查控制器目录下是否存在该控制器/动作。');", // 定义处理路由错误的函数
		'auto_sp_run' => FALSE, // 是否自动执行spRun函数

		'sp_cache' => APP_PATH.'/tmp', // 框架临时文件夹目录
		'sp_app_id' => '',  // 框架识别ID
		'controller_path' => APP_PATH.'/controller', // 用户控制器程序的路径定义
		'model_path' => PROTECTED_PATH.DS.'model'.DS, // 用户模型程序的路径定义
		'service_path' => PROTECTED_PATH.DS.'service'.DS, // 用户模型程序的路径定义
		'plugin_path' => PROTECTED_PATH.DS.'plugin'.DS, // 用户模型程序的路径定义
		'library_path' => SP_PATH.DS.'library'.DS, // 用户模型程序的路径定义
		'utile_path' => SP_PATH.DS.'utile'.DS, // 用户模型程序的路径定义


		'url' => array( // URL设置
				'url_path_info' => FALSE, // 是否使用path_info方式的URL
				//	'url_path_info' => true, // 是否使用path_info方式的URL
				'url_path_base' => '', // URL的根目录访问地址，默认为空则是入口文件index.php
				),

		'db' => array(  // 数据库连接配置
				'driver' => 'mysql',   // 驱动类型
				'host' => 'localhost', // 数据库地址
				'port' => 3306,        // 端口
				'login' => 'root',     // 用户名
				'password' => '',      // 密码
				'database' => '',      // 库名称
				'prefix' => '',           // 表前缀
				'persistent' => FALSE,    // 是否使用长链接
				'charset' => 'UTF8',
	    ),

		'db_driver_path' => '', // 自定义数据库驱动文件地址
		'db_spdb_full_tblname' => TRUE, // spDB是否使用表全名

		'view' => array( // 视图配置
				'enabled' => TRUE, // 开启视图
				'config' =>array(
					'template_dir' => APP_PATH.DS.'view'.DS, // 模板目录
					'compile_dir' => RUNTIME_PATH.DS.'templates_c'.DS, // 编译目录
					'cache_dir' => RUNTIME_PATH.DS.'cache'.DS, // 缓存目录
					'left_delimiter' => '{',  // smarty左限定符
					'right_delimiter' => '}', // smarty右限定符
					'auto_literal' => TRUE, // Smarty3新特性
					),
				'debugging' => FALSE, // 是否开启视图调试功能，在部署模式下无法开启视图调试功能
				'engine_name' => 'Smarty', // 模板引擎的类名称，默认为Smarty
				'cache'=>FALSE,//开启smarty缓存机制
				'engine_path' => SP_PATH.'/Drivers/Smarty/Smarty.class.php', // 模板引擎主类路径
				'auto_ob_start' => TRUE, // 是否自动开启缓存输出控制
				'auto_display' => FALSE, // 是否使用自动输出模板功能
				'auto_display_sep' => '/', // 自动输出模板的拼装模式，/为按目录方式拼装，_为按下划线方式，以此类推
				'auto_display_suffix' => '.html', // 自动输出模板的后缀名
				),

		'html' => array( 
				'enabled' => FALSE, // 是否开启真实静态HTML文件生成器
				'file_root_name' => 'topic', // 静态文件生成的根目录名称，设置为空则是直接在入口文件的同级目录生成
				'safe_check_file_exists' => FALSE, // 获取URL时，检查物理HTML文件是否存在，如文件不存在，则返回安全的动态地址
				),

		'lang' => array(), // 多语言设置，键是每种语言的名称，而值可以是default（默认语言），语言文件地址或者是翻译函数
		// 同时请注意，在使用语言文件并且文件中存在中文等时，请将文件设置成UTF8编码

		'ext' => array( // 扩展使用的配置根目录
                        'spUrlRewrite' => array(
                                'suffix' => '.html', // 生成地址的结尾符，网址后缀
                                'sep' => '/', // 网址参数分隔符，建议是“-_/”之一
                                'map' => array( // 网址映射，比如 'search' => 'main@search'，
                                        // 将使得 http://www.example.com/search.html 转向控制器main/动作serach执行
                                        // 特例 '@' => 'main@no' 如果映射是@，将使得符合以下条件的网址转向到 控制器main/动作no执行：
                                        // 1. 在map中无法找到其他映射，2. 网址第一个参数并非控制器名称。			
                                        ),
                                'args' => array( // 网址映射附加的隐藏参数，如果针对某个网址映射设置了隐藏参数，则在网址中仅会存在参数值，而参数名称被隐藏。
                                        // 比如 'search' => array('q','page'), 那么生成的网址将会是：
                                        // http://www.example.com/search-thekey-2.html
                                        // 配合map映射'search' => 'main@search'，这个网址将会执行 控制器main/动作serach，
                                        // 而参数q将等于thekey，参数page将等于2
                                        ),
                                ),
         ),


		'include_path' => array(
				APP_PATH.'/include',
	    ), // 用户程序扩展类载入路径

		'log_type' => 3,
		'log_file_size' => 2*1024*1024*1024,
		'log_level' => '',
		'log_dest' => '',
		'log_extra' => '',
		'log_path' => APP_APPLICATION_PATH.DS.'..'.DS.'logs'.DS,
        'var_page'=> 'p',
        'cookie' => ['expire_time'=>24*3600]
	);
