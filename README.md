# 配置处理

mvc框架配置处理组件，比ThinkPHP更易用：无需在配置中使用Env::get获取.env配置。

#开始使用

####安装组件

使用 composer 命令进行安装或下载源代码使用。

    composer require willphp/config

> WillPHP框架已经内置此组件，无需再安装。

####调用方式

    \willphp\config\Config::get('app.name'); //获取$app['name']

####使用示例

	Config::load(ROOT.'/config'); //载入公共配置 db_pwd=123
	Config::load(APP_PATH.'/config'); //载入应用配置db_pwd=456
	Config::load(ROOT.'/.env'); //载入本地配置 .env db_pwd=789	
	$pwd = Config::get('database.default.db_pwd'); //获得最后载入db_pwd=789	

####载入目录

    Config::load(__DIR__.'/config'); //加载config/*.php

####载入文件

    Config::load(__DIR__.'/config/app.php'); //加载app.php

app.php文件配置示例(所有键名载入后自动转为小写)：

    <?php
    return [
	  'debug' => true,
	  'name' => 'home', 
    ];

####载入.env

     Config::load(__DIR__.'/.env');

.env文件配置示例(所有键名载入后自动转为小写)：

	[APP]
	DEBUG = true
	TRACE = true
	
	[DATABASE]
	DEFAULT[DB_TYPE] = mysqli
	DEFAULT[DB_HOST] = localhost
	DEFAULT[DB_NAME] = myapp01db
	DEFAULT[DB_USER]= root
	DEFAULT[DB_PWD] = 
	DEFAULT[DB_PORT] = 3306
	DEFAULT[DB_CHARSET] = utf8
	DEFAULT[TABLE_PRE] = wp_

####设置配置

    Config::set('app.debug', false); 
    Config::reset([]); //重置

####获取所有

    Config::all(); 

####检测配置

    Config::has('app.key'); 

####获取配置

    Config::get('database.default.db_host', '127.0.0.1'); //获取，不存在或=''返回localhost
    Config::getExtName('database', ['read']); //排除获取

#助手函数

已去除内置，请自行设置此函数。
	
	/**
	 * 获取或设置配置
	 * @param string $name 参数名
	 * @param mixed $value 参数值
	 * @return mixed
	 */
	function config($name = '', $value = '') {
		if (!$name) {
			return \willphp\config\Config::all();
		}
		if ('' === $value) {
			return (0 === strpos($name, '?'))? \willphp\config\Config::has(substr($name, 1)) : \willphp\config\Config::get($name);
		}
		return \willphp\config\Config::set($name, $value);
	}
	

####获取全部

    $config = config();

####设置

    config('app.site_name', 'willphp');

####获取
	
    $name= config('app.site_name');

####检测

    $bool= config('?app.site_name');





