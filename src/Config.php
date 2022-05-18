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
class Config {
	protected static $link;	
	public static function single()	{
		if (!self::$link) {
			self::$link = new ConfigBuilder();
		}
		return self::$link;
	}	
	public function __call($method, $params) {
		return call_user_func_array([self::single(), $method], $params);
	}
	public static function __callStatic($name, $arguments) {
		return call_user_func_array([self::single(), $name], $arguments);
	}
}
class ConfigBuilder {
	protected static $items = []; //配置集合
	/**
	 * 载入配置：可加载目录(*.php)，文件(.php和.env)，数组
	 * @param string|array $config 配置
	 */
	public function load($config) {
		if (is_dir($config)) {
			foreach (glob($config.'/*.php') as $file) {
				$name = strtolower(basename($file, '.php'));
				$data = include $file;
				$this->array_change_key_case_recursive($data);
				self::$items[$name] = isset(self::$items[$name])? array_replace_recursive(self::$items[$name], $data) : $data;
			}
		} elseif (is_file($config)) {
			$suffix = substr(strrchr($config, '.'), 1);
			if ($suffix == 'php') {
				$name = strtolower(basename($config, '.php'));
				$data = include $config;
				$this->array_change_key_case_recursive($data);
				self::$items[$name] = isset(self::$items[$name])? array_replace_recursive(self::$items[$name], $data) : $data;
			} elseif ($suffix == 'env') {
				$env = parse_ini_file($config, true);
				if ($env) {
					$this->array_change_key_case_recursive($env);
					self::$items = array_replace_recursive(self::$items, $env);
				}
			}
		} elseif (is_array($config)) {
			self::$items = array_replace_recursive(self::$items, $config);
		}
	}
	/**
	 * 转换键值大小写
	 * @param array $array
	 * @return array
	 */
	protected function array_change_key_case_recursive(&$array, $case = CASE_LOWER) {
		$array = array_change_key_case($array, $case);
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->array_change_key_case_recursive($array[$key], $case);
			}
		}
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
	 * 获取配置
	 * @param string $name 名称(支持名称.名称)
	 * @param mixed $default 默认值
	 * @return mixed
	 */
	public function get($name = '', $default = '') {
		if (empty($name)) {
			return self::$items;
		}
		$tmp = self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $na) {
			if (isset($tmp[$na])) {
				$tmp = $tmp[$na];
			} else {
				return $default;
			}
		}
		return ('' === $tmp)? $default : $tmp;
	}
	/**
	 * 设置配置
	 * @param string $name 名称(支持名称.名称)
	 * @param mixed $value 值
	 * @return $value
	 */
	public function set($name, $value = '') {
		$tmp = &self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $na) {
			if (!isset($tmp[$na])) {
				$tmp[$na] = [];
			}
			$tmp = &$tmp[$na];
		}
		$tmp = $value;
		return $value;
	}
	/**
	 * 检测配置
	 * @param string $name 名称(支持名称.名称)
	 * @return boolean
	 */
	public function has($name) {
		$tmp = self::$items;
		$name = explode('.', $name);
		foreach ((array)$name as $na) {
			if (isset($tmp[$na])) {
				$tmp = $tmp[$na];
			} else {
				return false;
			}
		}
		return true;
	}
	/**
	 * 排除字段获取
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