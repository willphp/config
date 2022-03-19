<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: no-mind <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
//获取：env('database.db_host', '127.0.0.1');
//.env文件示例如下
/*
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
 */
namespace willphp\config\build;
/**
 * 配置处理
 * Class Base
 * @package willphp\config\build
 */
class Base {	
	protected static $items = []; //配置集合
	protected static $env = []; //.env配置集合
	/**
	 * 载入.env配置文件
	 * @param string $file 文件或目录
	 * @return $this
	 */
	public function loadEnv($file = '.env') {	
		if (is_dir($file)) {
			$file = $file.'/.env';
		}		
		if (is_file($file)) {
			$env = parse_ini_file($file, true);
			if ($env) {
				$this->setEnv($env);
			}	 
		}		
		return $this;
	}
	/**
	 * 设置.env配置
	 * @param string|array $env 环境变量
	 * @param mixed $value 值
	 * @return void
	 */
	public function setEnv($env, $value = null) {
		if (is_array($env)) {
			$env = array_change_key_case($env, CASE_UPPER);			
			foreach ($env as $key => $val) {
				if (is_array($val)) {
					foreach ($val as $k => $v) {
						self::$env[$key.'_'.strtoupper($k)] = $v;
					}
				} else {
					self::$env[$key] = $val;
				}
			}
		} else {
			$name = strtoupper(str_replace('.', '_', $env));			
			self::$env[$name] = $value;
		}
	}
	/**
	 * 获取.env配置
	 * @param string $name env配置名称(支持名称.名称)
	 * @param mixed $default 默认值(当配置不存在时返回)
	 * @return array|string
	 */
	public static function getEnv($name = '', $default = '') {	
		if (empty($name)) {
			return self::$env;
		}		
		$name = strtoupper(str_replace('.', '_', $name));	
		$env = isset(self::$env[$name])? self::$env[$name] : '';
		return ('' === $env)? $default : $env;
	}	
	/**
	 * 载入配置
	 * @param array|string  $config 配置数组或文件或目录
	 */
	public function load($config) {
		if (is_array($config)) {
			$this->loadArray($config);
		}
		if (is_file($config)) {
			$this->loadFile($config);
		}
		if (is_dir($config)) {
			$this->loadPath($config);
		}
	}
	/**
	 * 载入配置文件(.php文件)
	 * @param string $file 配置文件
	 */
	public function loadFile($file) {
		if (is_file($file) && substr(strrchr($file, '.'), 1) == 'php') {
			$tmp = include $file;
			$name = basename($file, '.php');
			self::$items[$name] = isset(self::$items[$name])? array_merge(self::$items[$name], $tmp) : $tmp;
		}
	}
	/**
	 * 载入配置目录(目录下所有.php文件)
	 * @param string $path 配置文件目录
	 */
	public function loadPath($path) {
		if (is_dir($path)) {
			foreach (glob($path.'/*.php') as $file) {
				$this->loadFile($file);
			}
		}
	}
	/**
	 * 载入数组
	 * @param array $config 配置数组
	 */
	public function loadArray(array $config) {
		self::$items = array_merge(self::$items, $config);
	}
	/**
	 * 获取所有配置
     * @return array
	 */
	public function all() {
		return self::$items;
	}
	/**
	 * 重置所有配置
	 * @param array $config 新的配置数组
	 * @return array
	 */
	public function reset(array $config = []) {
		return self::$items = $config;
	}
	/**
	 * 设置配置
	 * @param string $name 配置名(支持名称.名称表示数组)
	 * @param mixed $value 配置值
	 * @return bool
	 */
	public function set($name, $value) {
		$tmp = &self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $cn) {
			if (!isset($tmp[$cn])) {
				$tmp[$cn] = [];
			}
			$tmp = &$tmp[$cn];
		}		
		$tmp = $value;		
		return true;
	}
	/**
	 * 批量设置配置(支持名称.名称的键名)
	 * @param array $config 配置数组
	 * @return bool
	 */
	public function batch(array $config) {
		foreach ($config as $k => $v) {
			$this->set($k, $v);
		}		
		return true;
	}
	/**
	 * 获取配置
	 * @param string $name 配置名(支持名称.名称表示数组)
	 * @param mixed $default 默认值(当配置不存在或=''时返回默认值)
	 * @return mixed
	 */
	public function get($name, $default = '') {
		$tmp = self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $cn) {
			if (isset($tmp[$cn])) {
				$tmp = $tmp[$cn];
			} else {
				return $default;
			}
		}
		return ('' === $tmp)? $default : $tmp;
	}
	/**
	 * 检测配置是否存在
	 * @param string $name 配置名(支持名称.名称)
	 * @return mixed
	 */
	public function has($name) {
		$tmp = self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $cn) {
			if (isset($tmp[$cn])) {
				$tmp = $tmp[$cn];
			} else {
				return false;
			}
		}
		return true;
	}
	/**
	 * 排除字段获取配置数据
	 * @param string $name 配置名(支持名称.名称)
	 * @param array  $extName 排除的字段
	 * @return array
	 */
	public function getExtName($name, array $extName) {
		$config = $this->get($name);
		$data = [];
		foreach ((array)$config as $k => $v) {
			if (!in_array($k, $extName)) {
				$data[$k] = $v;
			}
		}
		return $data;
	}
}