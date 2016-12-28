<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     windfnn
 */
class Seller_Trade_OrderCtl extends Seller_Controller
{
	/**
	 * Constructor
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
	 * 实物交易订单
	 *
	 * @access public
	 */
	public function physical()
	{
		$Order_BaseModel = new Order_BaseModel();
		$condition       = array();
		$data            = $Order_BaseModel->getPhysicalList($condition);
		$condition       = $data['condi'];

		fb($data);
		fb("卖家订单");

		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单
	 *
	 * @access public
	 */
	public function virtual()
	{

		$Order_BaseModel = new Order_BaseModel();

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$Order_BaseModel->createSearchCondi($condition);

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		include $this->view->getView();
	}

    /**
     * 门店自提订单
     *
     * @access public
     */
    public function chain()
    {
        $Order_BaseModel = new Order_BaseModel();
        $condition['chain_id:!=']       = 0;
        $data            = $Order_BaseModel->getPhysicalList($condition);
        $condition       = $data['condi'];

        include $this->view->getView();
    }

	/**
	 * 虚拟交易订单--待付款订单
	 *
	 * @access public
	 */
	public function getVirtualNew()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_WAIT_PAY;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--已付款订单
	 *
	 * @access public
	 */
	public function getVirtualPay()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_PAYED;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--交易成功订单
	 *
	 * @access public
	 */
	public function getVirtualSuccess()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_FINISH;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 虚拟交易订单--取消订单列表
	 *
	 * @access public
	 */
	public function getVirtualCancel()
	{
		$Order_BaseModel = new Order_BaseModel();
		$Order_BaseModel->createSearchCondi($condition);

		$condition['shop_id']           = Perm::$shopId;
		$condition['order_is_virtual']  = Order_BaseModel::ORDER_IS_VIRTUAL;
		$condition['order_shop_hidden'] = Order_BaseModel::ORDER_IS_REAL;
		$condition['order_status']      = Order_StateModel::ORDER_CANCEL;

		$order_virtual_list = $Order_BaseModel->getOrderList($condition);  //获取店铺订单列表

		$this->view->setMet('virtual');
		include $this->view->getView();
	}

	/**
	 * 取消订单
	 *
	 * @access public
	 */
	public function orderCancel()
	{
		$typ  = request_string('typ');
		$user = request_string('user');

		if ($typ == 'e')
		{
			if ($user == 'buyer')
			{
				$cancel_row['cancel_identity'] = Order_CancelReasonModel::CANCEL_BUYER;
			}
			else
			{
				$cancel_row['cancel_identity'] = Order_CancelReasonModel::CANCEL_SELLER;
			}

			//获取取消原因
			$Order_CancelReasonModel = new Order_CancelReasonModel;
			$reason                  = array_values($Order_CancelReasonModel->getByWhere($cancel_row));

			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			//开启事物
			$Order_BaseModel->sql->startTransactionDb();

			$order_id   = request_string('order_id');
			$state_info = request_string('state_info');

			if (empty($state_info))
			{
				$state_info = request_string('state_info1');
			}
			//加入取消时间
			$condition['order_status']        = Order_StateModel::ORDER_CANCEL;
			$condition['order_cancel_reason'] = addslashes($state_info);

			if ($user == 'buyer')
			{
				$condition['order_cancel_identity'] = Order_BaseModel::IS_BUYER_CANCEL;
			}
			else
			{
				$condition['order_cancel_identity'] = Order_BaseModel::IS_SELLER_CANCEL;
			}
			$condition['order_cancel_date'] = get_date_time();

			$flag = $Order_BaseModel->editBase($order_id, $condition);

			//修改订单商品表中的订单状态
			$edit_row['order_goods_status'] = Order_StateModel::ORDER_CANCEL;
			$Order_GoodsModel               = new Order_GoodsModel();
			$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

			$Order_GoodsModel->editGoods($order_goods_id, $edit_row);

			//退还订单商品的库存
			$Goods_BaseModel = new Goods_BaseModel();
			$Goods_BaseModel->returnGoodsStock($order_goods_id);

			//将需要取消的订单号远程发送给Paycenter修改订单状态
			//远程修改paycenter中的订单状态
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('paycenter_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$formvars = array();

			$formvars['order_id']    = $order_id;
			$formvars['app_id']        = $shop_app_id;

			fb($formvars);

			$rs = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);



			if ($flag && $Order_BaseModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$Order_BaseModel->sql->rollBackDb();
				$m      = $Order_BaseModel->msg->getMessages();
				$msg    = $m ? $m[0] : _('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 虚拟订单列表详情
	 *
	 * @access public
	 */
	public function virtualInfo()
	{
		$Goods_BaseModel       = new Goods_BaseModel();
		$Order_BaseModel       = new Order_BaseModel();
		$condition['order_id'] = request_string('order_id');

		$order_data = $Order_BaseModel->getOrderList($condition);
		$order_data = pos($order_data['items']);
		$goods_list = pos($order_data['goods_list']);

		//取出虚拟商品有效期 common_base => common_virtual_date
		$goods_id                          = $goods_list['goods_id'];
		$common_data                       = $Goods_BaseModel->getCommonInfo($goods_id);
		$order_data['common_virtual_date'] = $common_data['common_virtual_date'];

		include $this->view->getView();
	}

	/**
	 * 兑换虚拟订单
	 *
	 * @access public
	 */
	public function virtualExchange()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$data            = array();
			$virtual_code_id = request_string('vr_code');

			if (empty($virtual_code_id))
			{
				return $this->data->addBody(-140, $data, _('请输入虚拟码'), 250);
			}

			$orderBaseModel             = new Order_BaseModel();
			$orderGoodsVirtualCodeModel = new Order_GoodsVirtualCodeModel();
			$virtual_base               = $orderGoodsVirtualCodeModel->getCode($virtual_code_id);
			
			if (empty($virtual_base))
			{
				$flag = false;
			}
			else
			{
				$virtual_base = pos($virtual_base);

				if ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::VIRTUAL_CODE_NEW)
				{
					$update['virtual_code_status']  = Order_GoodsVirtualCodeModel::VIRTUAL_CODE_USED;
					$update['virtual_code_usetime'] = date('Y-m-d H:i:s', time());                            //兑换时间
					$flag                           = $orderGoodsVirtualCodeModel->editCode($virtual_code_id, $update);

					$conid['order_id'] = $virtual_base['order_id'];

					$order_data = $orderBaseModel->getOrderList($conid);
					$order_data = pos($order_data['items']);
					$goods_list = pos($order_data['goods_list']);

					$orderBaseModel->editBase($order_data['order_id'], array('order_status' => Order_StateModel::ORDER_FINISH));

					$data['goods_url']  = $goods_list['goods_link'];
					$data['img_240']    = $goods_list['goods_image'];
					$data['img_60']     = $goods_list['goods_image'];
					$data['goods_name'] = $goods_list['goods_name'];
					$data['order_url']  = $goods_list['order_id'];
					$data['order_sn']   = $goods_list['order_id'];
					$data['buyer_msg']  = $order_data['order_message'];
				}
				elseif ($virtual_base['virtual_code_status'] == Order_GoodsVirtualCodeModel::SHOP_STATUS_OPEN)
				{
					$flag = false;
					$msg  = _('已使用或已冻结');
				}
			}

			if ($flag)
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $data, $msg, $status);
		}
	}

	/**
	 * 实物交易订单 ==> 待付款
	 *
	 * @access public
	 */
	public function getPhysicalNew()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已付款
	 *
	 * @access public
	 */
	public function getPhysicalPay()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_PAYED;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 待自提
	 *
	 * @access public
	 */
	public function getPhysicalNotakes()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已发货
	 *
	 * @access public
	 */
	public function getPhysicalSend()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已完成
	 *
	 * @access public
	 */
	public function getPhysicalSuccess()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_FINISH;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 已取消
	 *
	 * @access public
	 */
	public function getPhysicalCancel()
	{
		$Order_BaseModel       = new Order_BaseModel();
		$condi['order_status'] = Order_StateModel::ORDER_CANCEL;
		$data                  = $Order_BaseModel->getPhysicalList($condi);
		$condition             = $data['condi'];

		$this->view->setMet('physical');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 订单详情
	 *
	 * @access public
	 */
	public function physicalInfo()
	{
		$order_id        = request_string('order_id');
		$Order_BaseModel = new Order_BaseModel();
		$data            = $Order_BaseModel->getPhysicalInfoData(array('order_id' => $order_id));

		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 打印发货单
	 *
	 * @access public
	 */
	public function getOrderPrint()
	{
		$Order_BaseModel   = new Order_BaseModel();
		$condi['order_id'] = request_string('order_id');

		$data = $Order_BaseModel->getOrderList($condi);
		$data = pos($data['items']);

		//读取店铺印章等信息
		$shop_id = Perm::$shopId;
		$shop_BaseModel = new Shop_BaseModel();
		$shop_base = $shop_BaseModel->getBase( $shop_id );
		$shop_base = pos($shop_base);
		$shop_print_desc = $shop_base['shop_print_desc'];
		$shop_stamp = $shop_base['shop_stamp'];

		$this->view->setMet('orderPrint');
		include $this->view->getView();
	}

	/**
	 * 实物交易订单 ==> 设置发货
	 *
	 * @access public
	 */
	public function send()
	{
		$typ      = request_string('typ');
		$order_id = request_string('order_id');

		$Order_BaseModel   = new Order_BaseModel(); 
		$Shop_ExpressModel = new Shop_ExpressModel();

		if ($typ == 'e')
		{
			$condi['order_id'] = $order_id;
			$data              = $Order_BaseModel->getOrderList($condi);
			$data              = pos($data['items']);

			//默认物流公司 url
			$default_express_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Deliver&met=express&typ=e';
			//打印运单URL
			$print_tpl_url = Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=printTpl&typ=e&order_id=' . $order_id;

			//默认物流公司
			$express_list = $Shop_ExpressModel->getDefaultShopExpress();
			$express_list = array_values($express_list);

			include $this->view->getView();
		}
		else
		{
			// var_dump(111333);die;
			//设置发货
			$update_data['order_status']              = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
			$update_data['order_shipping_express_id'] = request_int('order_shipping_express_id');
			$update_data['order_shipping_code']       = request_int('order_shipping_code');
			$update_data['order_shipping_message']    = request_string('order_shipping_message');
			$update_data['order_seller_message']      = request_string('order_seller_message');

			//配送时间 收货时间
			$current_time                       = time();
			$confirm_order_time                 = Yf_Registry::get('confirm_order_time');
			$update_data['order_shipping_time'] = date('Y-m-d H:i:s', $current_time);
			$update_data['order_receiver_date'] = date('Y-m-d H:i:s', $current_time + $confirm_order_time);
			
			$flag = $Order_BaseModel->editBase($order_id, $update_data);
			var_dump(3443);
            var_dump($flag);die;
			if ($flag)
			{
				$order_base = $Order_BaseModel->getBase($order_id);
				$order_base = pos($order_base);
				//发送站内信
				$message = new MessageModel();
				$message->sendMessage('ordor_complete_shipping', $order_base['buyer_user_id'], $order_base['buyer_user_name'], $order_id, $order_base['shop_name'], 0, 3);

				//远程修改paycenter中的订单信息
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['order_id']    = $order_id;
				$formvars['app_id']        = $shop_app_id;

				$rs = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=sendOrderGoods&typ=json', $url), $formvars);

				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}
	}

	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function chooseSendAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			$shop_id                   = request_int('shop_id');
			$Shop_ShippingAddressModel = new Shop_ShippingAddressModel();
			$address_list              = $Shop_ShippingAddressModel->getByWhere(array('shop_id' => $shop_id));
			$address_list              = array_values($address_list);
			foreach ($address_list as $key => $val)
			{
				$address_list[$key]['address_info']  = $val['shipping_address_area'] . " " . $val['shipping_address_address'];
				$address_list[$key]['address_value'] = $val['shipping_address_contact'] . "&nbsp" . $val['shipping_address_phone'] . "&nbsp" . $val['shipping_address_area'] . "&nbsp" . $val['shipping_address_address'];
			}

			include $this->view->getView();
		}
		else
		{
			$order_id     = request_string('order_id');
			$send_address = request_row('send_address');

			$Order_BaseModel                     = new Order_BaseModel();
			$update_data['order_seller_name']    = $send_address['order_seller_name'];
			$update_data['order_seller_address'] = $send_address['order_seller_address'];
			$update_data['order_seller_contact'] = $send_address['order_seller_contact'];
			$flag                                = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag)
			{
				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 实物交易订单 ==> 选择发货地址
	 *
	 * @access public
	 */
	public function editBuyerAddress()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			$order_id = request_string('order_id');

			$update_data['order_receiver_name']    = request_string('order_receiver_name');
			$update_data['order_receiver_address'] = request_string('order_receiver_address');
			$update_data['order_receiver_contact'] = request_string('order_receiver_contact');

			$flag = $Order_BaseModel->editBase($order_id, $update_data);

			if ($flag)
			{
				$update_data['receiver_info'] = $update_data['order_receiver_name'] . "&nbsp;" . $update_data['order_receiver_address'] . "&nbsp;" . $update_data['order_receiver_contact'];
				$msg                          = _('success');
				$status                       = 200;
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}

			$this->data->addBody(-140, $update_data, $msg, $status);
		}

	}


	/**
	 * 商家中心首页不同状态订单数目
	 *
	 * @access public
	 */
	public function getOrderNum()
	{
		$order_type = request_int('order_type');

		$orderBaseModel = new Order_BaseModel();

		//待付款订单
		$condi                 = array();
		$condi['shop_id']      = Perm::$shopId;
		$condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
		$wait_pay_data         = $orderBaseModel->getByWhere($condi);

		//待发货订单
		$condi                     = array();
		$condi['shop_id']          = Perm::$shopId;
		$condi['order_status:IN']  = array(
			Order_StateModel::ORDER_PAYED,
			Order_StateModel::ORDER_WAIT_PREPARE_GOODS
		);
		$payed_data                = $orderBaseModel->getByWhere($condi);

		//退款订单
		$condi                        = array();
		$condi['shop_id']             = Perm::$shopId;
		$condi['order_refund_status'] = Order_BaseModel::REFUND_IN;
		$refund_data                  = $orderBaseModel->getByWhere($condi);

		//退货订单
		$condi                        = array();
		$condi['shop_id']             = Perm::$shopId;
		$condi['order_return_status'] = Order_BaseModel::RETURN_SOME;
		$return_data                  = $orderBaseModel->getByWhere($condi);


		$data['wait_pay_num'] = count($wait_pay_data);
		$data['payed_num']    = count($payed_data);
		$data['refund_num']   = count($refund_data);
		$data['return_num']   = count($return_data);

		$this->data->addBody(-140, $data);

	}

    /**
     * 门店自提订单 ==> 待付款
     *
     * @access public
     */
    public function getChainNew()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 待自提
     *
     * @access public
     */
    public function getChainNotakes()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_SELF_PICKUP;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已完成
     *
     * @access public
     */
    public function getChainSuccess()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_FINISH;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 已取消
     *
     * @access public
     */
    public function getChainCancel()
    {
        $Order_BaseModel       = new Order_BaseModel();
        $condi['order_status'] = Order_StateModel::ORDER_CANCEL;
        $condi['chain_id:!=']       = 0;
        $data                  = $Order_BaseModel->getPhysicalList($condi);
        $condition             = $data['condi'];

        $this->view->setMet('chain');
        include $this->view->getView();
    }

    /**
     * 门店自提订单 ==> 订单详情
     *
     * @access public
     */public function chainInfo()
    {
        $order_id        = request_string('order_id');
        $Order_BaseModel = new Order_BaseModel();
        $data            = $Order_BaseModel->getChainInfoData(array('order_id' => $order_id));

        include $this->view->getView();
    }


}

?>