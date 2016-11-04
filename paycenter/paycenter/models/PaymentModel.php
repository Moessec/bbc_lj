<?php
/**
 * 
 * 通过这个类，统一管理支付类。
 * 
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class PaymentModel
{

    /**
     * 构造函数
     *
     * @access    private
     */
    public function __construct()
    {
    }

    /**
     * 得到支付句柄
     *
     * @param array  $channel   使用的支付驱动
     *
     * @return Object   Payment Object
     *
     * @access public
     */
    public static function create($channel,$op)
    {
        $Payment_ChannelModel = new Payment_ChannelModel();
		$config_row = $Payment_ChannelModel->getChannelConfig($channel);

		if (!$config_row)
		{
			throw new Exception(_('支付配置数据错误!'));
		}

        $PaymentModel = null;

        if ('alipay' == $channel)
        {
            if ($op == 'pc')
            {
                $PaymentModel = new Payment_AlipayModel($config_row);
                //$PaymentModel = new Payment_AlipayWapModel($config_row);
            }
            else
            {
                //$PaymentModel = new Payment_AlipayModel($config_row);
                $PaymentModel = new Payment_AlipayWapModel($config_row);
            }
        }
        elseif ('tenpay' == $channel)
        {
            $PaymentModel = new Payment_TenpayModel($config_row);
        }
        elseif ('tenpay_wap' == $channel)
        {
            $PaymentModel = new Payment_TenpayWapModel($config_row);
        }
        elseif ('wx_native' == $channel)
        {
            //微信变量, 不变动程序,修正数据
            !defined('APPID_DEF') && define('APPID_DEF', $config_row['appid']);
            !defined('MCHID_DEF') && define('MCHID_DEF', $config_row['mchid']);
            !defined('KEY_DEF') && define('KEY_DEF', $config_row['key']);
            !defined('APPSECRET_DEF') && define('APPSECRET_DEF', $config_row['appsecret']);

            !defined('SSLCERT_PATH_DEF') && define('SSLCERT_PATH', LIB_PATH . '/Api/wx/cert/apiclient_cert.pem');
            !defined('SSLKEY_PATH_DEF') && define('SSLKEY_PATH', LIB_PATH . '/Api/wx/cert/apiclient_key.pem');

            $PaymentModel = new Payment_WxNativeModel($config_row);
        }
        else
        {
        }

        return $PaymentModel;
    }

    /**
     * 得到支付句柄
     *
     * @param array  $channel   使用的支付驱动
     *
     * @return Object   Payment Object
     *
     * @access public
     */
    public static function get($channel)
    {
        return self::create($channel);
    }
}
?>