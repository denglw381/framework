<?php
return array(
    'auto_model' => false,
    'db_driver_path' => SP_PATH.'/Drivers/pdo.php',
    'static_url' => '//ms.wantme.cn', 
    'upload_path' => __DIR__.'/../webroot/static/upload',
    'upload_url' => 'http://w1.wantme.cn/static/upload',
    'user' => [
          'max_login_wrong_times'=>20, 
          'login_wrong_forbid_time'=>3600
    ]
);
