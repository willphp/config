# 配置管理
config组件用于网站配置管理

#开始使用

####安装组件
使用 composer 命令进行安装或下载源代码使用。

    composer require willphp/config

> WillPHP 框架已经内置此组件，无需再安装。

####调用方式

    \willphp\config\Config::get('app'); //直接调用或使用config函数

####载入env配置

    Config::loadEnv(WIPHP_URI.'/.env'); //加载目录(.env所在目录)或.env文件

.env文件示例如下：

     [APP]
     DEBUG = true
     
     [DATABASE]
     DB_TYPE = mysql
     DB_HOST = localhost
     DB_NAME = test
     DB_USER = root
     DB_PWD =
     DB_PORT = 3306
     DB_CHARSET = utf8
     TABLE_PRE = wp_

####获取env配置

    env('database.db_host', '127.0.0.1'); //读取.env文件中的[DATABASE]DB_HOST配置，默认127.0.0.1

####载入配置

    Config::load($config); //载入配置数组或文件或目录
    Config::loadFile($file); //载入配置文件(.php文件)	
    Config::loadPath($path); //载入配置目录(目录下所有.php文件)
    Config::loadArray($array); //载入数组配置	

####设置配置

    Config::set('app.debug', true); //配置名支持名称.名称
    Config::batch(['app.debug'=>true,'database.db_pwd','123']); //批量设置
    Config::reset([]); //重置所有配置

####检测配置

    Config::has('app.key'); //是否存在配置

####获取配置

    Config::get('database.db_host', 'localhost'); //获取配置(支持名称.名称)，不存在或=''返回默认值
    Config::all(); //获取所有配置
    Config::getExtName('database', ['write', 'read']); //排除键名获取

#config函数

####获取全部

    $config = config();

####获取
	
    $name= config('app.site_name');

####设置

    config('app.site_name', 'willphp');

####检测

    $bool= config('?app.site_name');





