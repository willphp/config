<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: no-mind <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\config;
use willphp\framework\build\Provider;
class ConfigProvider extends Provider {
	public $defer = false; //延迟加载
	public function boot() {
		Config::loadEnv(WIPHP_URI.'/.env'); //加载.env配置
		Config::loadPath(WIPHP_URI.'/config'); //加载框架公共配置
		Config::loadPath(WIPHP_URI.'/app/'.APP_NAME.'/config'); //加载框架应用配置
	}
	public function register() {
		$this->app->single('Config', function () {
			return Config::single();
		});
	}
}