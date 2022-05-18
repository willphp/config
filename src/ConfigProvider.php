<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: 无念 <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\config;
use willphp\framework\build\Provider;
class ConfigProvider extends Provider {
	public $defer = false; //延迟加载
	public function boot() {		
		Config::load(WIPHP_URI.'/config'); //加载公共配置
		Config::load(APP_PATH.'/config'); //加载应用配置
		Config::load(WIPHP_URI.'/.env'); //加载.env配置
	}
	public function register() {
		$this->app->single('Config', function () {
			return Config::single();
		});
	}
}