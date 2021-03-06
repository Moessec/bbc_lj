<?php

if (!defined('ROOT_PATH'))
{
	define('APP_DIR_NAME', 'shop_admin');
	define('ROOT_PATH', substr(str_replace('\\', '/', dirname(__FILE__)), 0, -18));
	define('LIB_PATH', ROOT_PATH . '/libraries');   //ZeroPHP Framework 所在目录
	define('APP_PATH', ROOT_PATH . '/' . APP_DIR_NAME);         //应用程序目录
	define('MOD_PATH', APP_PATH . '/models');       //应用程序模型目录

	$themes_name = 'default';
	$pro_path =  '';

	/**
	 * 风格静态文件文件目录，此处变量名称$themes勿修改
	 *
	 * @var string
	 */
	if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'])
	{
		$pro_path_row = explode($_SERVER['DOCUMENT_ROOT'], ROOT_PATH);

		if (isset($pro_path_row[1]))
		{
			$pro_path = '/' . ltrim($pro_path_row[1], '/');
			$themes = $pro_path . '/' . APP_DIR_NAME . '/static/' . $themes_name;
		}
		else
		{
			$themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
		}
	}
	else
	{
		$themes = '/' . APP_DIR_NAME . '/static/' . $themes_name;
	}

	define('TPL_DEFAULT_PATH', APP_PATH . '/views/default');  //应用程序默认视图目录

	define('TPL_PATH', APP_PATH . '/views/' . $themes_name);
	define('CTL_PATH', APP_PATH . '/controllers');
	define('INI_PATH', APP_PATH . '/configs');


	define('HLP_PATH', APP_PATH . '/helpers');
	define('LOG_PATH', APP_PATH . '/data/logs');
	define('LAN_PATH', APP_PATH . '/data/locales');


	//是否开启runtime，如果为false，则不生成runtime缓存
	define('RUNTIME', false);

	//是否开启debug，如果为true，则不生成runtime缓存
	define('DEBUG', true);

	global $import_file_row;

	if (!isset($import_file_row))
	{
		$import_file_row = array();
	}

	//公用函数库
	require_once LIB_PATH . '/__init__.php';
}
else
{
}
/**
 * main config
 *
 *
 * @category   Config
 * @package    Config
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
define('CODE_TEMPLATE_PATH', ROOT_PATH . '/build_tools/code_template');
define("CONTROLLER_CLASS_NAME", 'Game_'); //控制器class前缀
define("MODEL_CLASS_NAME", ''); //模型class前缀
define('REDIS_AUTO_ID', 'auto_id:');
define('FIGHT_REPORT_PATH', APP_PATH . '/data/fight_report');

if (DEBUG)
{
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else
{
	ini_set('display_errors', 0);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
}

//插件启动
$plugin_rows = array(
	array('name'=>'Perm'),
	array('name'=>'Log'),
	//array('name'=>'Protocal'),
	//array('name'=>'Passprot'),
	//array('name'=>'Xhprof'),
	//array('name'=>'Refresh', 'cli'=>false)
);

//if (1 == mt_rand(1, 10000))
if (true)
{
	//array_push($plugin_rows, array('name'=>'Xhprof'));
}


//$plugin_rows   = get_active_plugins();
$PluginManager = Yf_Plugin_Manager::getInstance($plugin_rows);
$PluginManager->trigger('init', '');

Yf_Registry::set('hook', $PluginManager);

//初始化语言包
if (function_exists('_'))
{
	//init_locale(APP_PATH . '/data/locales/', 'zh_CN', 'HelloWorld');   //初始化，只需要一次即可
	//textdomain('HelloWorld');
}

if ('cli' != SAPI)
{
	set_time_limit(15); //运行时间限制一定要有的。 切记！

	//是否压缩输出
	$gzipcompress = 0;

	if ($gzipcompress && function_exists('ob_gzhandler'))
	{
		ob_start('ob_gzhandler');
	}
	else
	{
		$gzipcompress = 0;
		ob_start();
	}

	Yf_Registry::set('gzipcompress', $gzipcompress);

	//负载控制，理论上，不高于处理器数目*0.7，因为机器高峰负载，可以高一些。
	Yf_Registry::set('loadctrl', 6);

	if (Yf_Registry::isRegistered('loadctrl'))
	{
		if (!function_exists('sys_getloadavg'))
		{
			function sys_getloadavg()
			{
				//$loadavg_file = '/proc/loadavg';
				//if (is_file($loadavg_file)) {
				//return explode(chr(32),file_get_contents($loadavg_file));
				//}

				if ('WIN' != substr(PHP_OS, 0, 3))
				{
					if ($fp = @fopen('/proc/loadavg', 'r'))
					{
						$loadaverage = explode(' ', fread($fp, 6));
						fclose($fp);

						return $loadaverage;
					}
				}
				else
				{
					return array(0, 0, 0);
				}
			}
		}

		$loadaverage = sys_getloadavg();

		if ($loadaverage[0] > Yf_Registry::get('loadctrl'))
		{
			header("HTTP/1.0 503 Too busy, try again later");
			echo 'Server busy, please try again later!';
			exit();
		}
	}
    

	if (function_exists('session_cache_limiter'))
	{
		session_cache_limiter('private, must-revalidate');
	}

	header('Content-type: text/html; charset=UTF-8');

	//强制过期，ajax请求不需要要加随机字符串
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
	header('Pragma: no-cache'); // HTTP/1.0

	header('P3P: CP=CAO PSA OUR');  //ie iframe cookie
	header("Access-Control-Allow-Origin: *");
}

$host = '';
if (isset($_SERVER['HTTP_HOST']))
{
    $host = $_SERVER['HTTP_HOST'];
}

Yf_Registry::set('base_url', 'http://'. $host . $pro_path);
Yf_Registry::set('index_page', 'index.php');
Yf_Registry::set('url', Yf_Registry::get('base_url') . '/' . Yf_Registry::get('index_page'));
Yf_Registry::set('static_url', 'http://'. $host . $themes);




//记录错误提示
ini_set('log_errors', 1);
ini_set('error_log', APP_PATH . '/data/logs/debug.log');

//设置时区
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('Asia/Shanghai');
	//date_default_timezone_set('UTC');
}


/*
应用cache, 如果使用cache，配置格式必须严格按照如下格式
0|false:none
1|true:file/memcache
*/
define('CHE', 1);

if (true)
{
	//cache配置参数
	//require_once INI_PATH . '/cache.ini.php';

	$config_cache['memcache']['base'] = array(
		array(
			'127.0.0.1' => '11211'
		),
	);

	$config_cache['memcache']['data'] = array(
		array(
			'127.0.0.1' => '11211'
		),
	);
}

//设置cache 参数
//cacheType 1:file  2:memcache   3：redis
$config_cache['default'] = array(
	'cacheType' => 1,
	'cacheDir' => APP_PATH . '/data/cache/default_cache/',
	'memoryCaching' => false,
	'automaticSerialization' => true,
	'hashedDirectoryLevel' => 2,
	'lifeTime' => 86400
);

$config_cache['base'] = array(
	'cacheType' => 1,
	'cacheDir' => APP_PATH . '/data/cache/default_cache/',
	'memoryCaching' => false,
	'automaticSerialization' => true,
	'hashedDirectoryLevel' => 2,
	'lifeTime' => 86400
);
Yf_Registry::set('config_cache', $config_cache);



//包含Db配置文件，如果使用DB，配置格式必须严格按照如下格式
//require_once ROOT_PATH . '/../../config/config.inc.php';
define('DB_DRIVE', 'Yf_Db_Pdo');
define('DB_DEBUG', true);

//不同的平台,必须有独立域名,因为用户可能是公用的.
//根据域名,决定访问的平台不同,而不是通过server_id
//md5(域名), server_id
$server_id = md5($host);

Yf_Registry::set('server_id', $server_id);

if (is_file(INI_PATH . '/db_' . $server_id . '.ini.php'))
{
	$db_row = include_once INI_PATH . '/db_' . $server_id . '.ini.php';
}
else
{
	$db_row = include_once INI_PATH . '/db.ini.php';
}

$config['db_cfg_rows'] = array(
	'master' => array(
		'shop_admin' => array(
			$db_row
		)
	)
);

//通过这儿设置默认Db， 目前从主库读取数据，示例如下
$config['db_write_read'] = 'master';

//如果需要从slave库中读取， 需要设置如下：$db_cfg_rows['default'] = $db_cfg_rows['slave'];
Yf_Registry::set('db_cfg', $config);

if (Yf_Registry::get('magic_quotes_gpc'))
{
	$_POST    = unquotes($_POST);
	$_GET     = unquotes($_GET);
	$_REQUEST = unquotes($_REQUEST);
	$_COOKIE  = unquotes($_COOKIE);
	$_FILES   = unquotes($_FILES);
}

//初始化参数
//require_once(INI_PATH . '/init.php');


//判断类型，转换
$int_row = array(
	'int8_t',
	'int16_t',
	'int32_t',
	'int64_t',
	'uint8_t',
	'uint16_t',
	'uint32_t',
	'uint64_t'
);

$float_row = array(
	'float'
);

Yf_Registry::set('int_row', $int_row);
Yf_Registry::set('float_row', $float_row);

if (isset($ccmd_rows))
{
	Yf_Registry::set('ccmd_rows', $ccmd_rows);
}

Yf_Registry::set('error_url', false);



$sms_config = include_once 'sms.ini.php';




//用户中心配置
if (is_file(INI_PATH . '/ucenter_api_' . $server_id . '.ini.php'))
{
	$db_row = include_once INI_PATH . '/ucenter_api_' . $server_id . '.ini.php';
}
else
{
	$db_row = include_once INI_PATH . '/ucenter_api.ini.php';
}

Yf_Registry::set('ucenter_api_key', $ucenter_api_key);
Yf_Registry::set('ucenter_app_id', $ucenter_app_id);
Yf_Registry::set('ucenter_api_url', $ucenter_api_url);
Yf_Registry::set('ucenter_admin_api_url', $ucenter_admin_api_url);

//支付中心配置
if (is_file(INI_PATH . '/paycenter_api_' . $server_id . '.ini.php'))
{
	$db_row = include_once INI_PATH . '/paycenter_api_' . $server_id . '.ini.php';
}
else
{
	$db_row = include_once INI_PATH . '/paycenter_api.ini.php';
}

Yf_Registry::set('paycenter_api_key', $paycenter_api_key);
Yf_Registry::set('paycenter_app_id', $paycenter_app_id);
Yf_Registry::set('paycenter_api_url', $paycenter_api_url);
Yf_Registry::set('paycenter_admin_api_url', $paycenter_admin_api_url);
Yf_Registry::set('paycenter_api_name', @$paycenter_api_name ? $paycenter_api_name : _('网付宝'));


//SHOP配置
if (is_file(INI_PATH . '/shop_api_' . $server_id . '.ini.php'))
{
	include_once INI_PATH . '/shop_api_' . $server_id . '.ini.php';
}
else
{
	include_once INI_PATH . '/shop_api.ini.php';
}


Yf_Registry::set('shop_api_key', $shop_api_key);
Yf_Registry::set('shop_api_url', $shop_api_url);
Yf_Registry::set('shop_app_id', $shop_app_id);
Yf_Registry::set('shop_wap_url', @$shop_wap_url);




//用户中心配置
include_once 'im_api.ini.php';
Yf_Registry::set('im_api_key', $im_api_key);
Yf_Registry::set('im_app_id', $im_app_id);
Yf_Registry::set('im_api_url', $im_api_url);
Yf_Registry::set('im_api_name', $im_api_name);
?>