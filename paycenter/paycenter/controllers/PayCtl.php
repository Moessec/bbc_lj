<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
/**
 * 支付入口
 * @author     Cbin
 */
class PayCtl extends Controller
{

	/**
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	/**
	 * 微信二维码支付
	 * 构造 url
	 * @param product_id产品ID
	 */
	public function structWXurl()
	{
		// 第一步 参数过滤
		$product_id  = trim($_REQUEST['product_id']);
		if (!$product_id || is_int($product_id))
		{
			$this->data->setError('参数错误');
			$this->data->printJSON();
			die;
		}

		// 第二步  调用url生成类
		$pw = new Payment_WxQrcodeModel();
		$url = $pw->url($product_id);
		include $this->view->getView();
	}

	/**
	 * 微信二维码支付
	 * 生成二维码
	 */
	public function structWXcode()
	{
		require_once MOD_PATH.'/Payment/phpqrcode/phpqrcode.php';
		$url = urldecode($_REQUEST["data"]);
		QRcode::png($url);
	}

	/**
	 * 微信二维码支付
	 * 微信回调
	 */
	public function WXnotify()
	{
		// 确定支付
		$pw = new Payment_WxQrcodeModel();
		$pw->notify();

		// 支付金额写入数据库
		// code
	}

	/**
	 * 使用余额支付
	 *
	 */
	public function money()
	{
		$trade_id = request_string('trade_id');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();

		//开启事物
		$Consume_DepositModel = new Consume_DepositModel();

		$uorder = $Union_OrderModel->getOne($trade_id);
		//修改订单表中的各种状态

		$flag = $Consume_DepositModel->notifyShop($trade_id,$uorder['buyer_id']);
		$data = array();
		if ($flag['status'] == 200)
		{
			//查找回调地址
			$User_AppModel = new User_AppModel();
			$user_app = $User_AppModel->getOne($uorder['app_id']);
			$return_app_url = $user_app['app_url'];

			$data['return_app_url'] = $return_app_url;

			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;

		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 使用充值卡支付
	 *
	 */
	public function cards()
	{
		$trade_id = request_string('trade_id');
		$card_code = request_string('card_code');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();

		//开启事物
		$Union_OrderModel->sql->startTransactionDb();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		fb($trade_row);
		$user_id = Perm::$userId;

		if($trade_row)
		{
			//1.用户资源中订单金额冻结
			$User_ResourceModel = new User_ResourceModel();
			$flag = $User_ResourceModel->frozenUserCards($user_id,$card_code,$trade_row['trade_payment_amount']);
			fb($flag);

			if($flag)
			{
				//修改订单表中的支付方式
				$Consume_TradeModel = new Consume_TradeModel();
				$Consume_TradeModel->editConsumeTrade($trade_id,Payment_ChannelModel::CARDS);

				//修改订单表中的各种状态
				$Consume_DepositModel = new Consume_DepositModel();
				$data                 = $Consume_DepositModel->notifyShop($trade_id);
			}



		}
		else
		{
			$flag = false;
		}

		if ($flag && $Union_OrderModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Union_OrderModel->sql->rollBackDb();
			$m      = $Union_OrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 使用支付宝支付
	 *
	 */
	public function alipay()
	{
		$trade_id = request_string('trade_id');
		$op = request_string('op');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		if ($trade_row)
		{
			$Payment = PaymentModel::create('alipay');
			$Payment->pay($trade_row);
		}
		else
		{

		}
	}

	/**
	 * 使用微信支付
	 *
	 */
	public function wx_native()
	{
		$trade_id = request_string('trade_id');

		//如果订单号为合并订单号，则获取合并订单号的信息
		$Union_OrderModel = new Union_OrderModel();
		$trade_row        = $Union_OrderModel->getOne($trade_id);

		if ($trade_row)
		{
			$Payment = PaymentModel::create('wx_native');
			$Payment->pay($trade_row);
		}
		else
		{

		}
	}

	public function test()
	{
		$test = request_string('test');

		fb($test);
		$key = Yf_Registry::get('shop_api_key');
		$url = Yf_Registry::get('shop_api_url');
		$paycenter_app_id = Yf_Registry::get('shop_app_id');

		$formvars = array();
		$formvars['app_id'] = $paycenter_app_id;
		$formvars['test'] = $test;


		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Trade_Order&met=test&typ=json', $url), $formvars);

		return $rs;
	}



}