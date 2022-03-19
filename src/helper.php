<?php
if (!function_exists('config')) {
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
}
if (!function_exists('env')) {
	/**
	 * 根据.env配置获取配置
	 * @param $name  配置名称(支持名称.名称)
	 * @param $default 为空时返回默认值
	 * @return mixed
	 */
	function env($name = '', $default = '') {
		return \willphp\config\Config::getEnv($name, $default);
	}
}