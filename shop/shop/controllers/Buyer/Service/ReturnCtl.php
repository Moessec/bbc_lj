<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_Service_ReturnCtl extends Buyer_Controller
{
	public $orderReturnModel       = null;
	public $orderBaseModel         = null;
	public $orderGoodsModel        = null;
	public $orderReturnReasonModel = null;

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
		$this->orderReturnModel       = new Order_ReturnModel();
		$this->orderBaseModel         = new Order_BaseModel();
		$this->orderGoodsModel        = new Order_GoodsModel();
		$this->orderReturnReasonModel = new Order_ReturnReasonModel();

		$this->Order_BaseModel         = new Order_BaseModel();
		$this->Order_ReturnModel       = new Order_ReturnModel();
		$this->Order_ReturnReasonModel = new Order_ReturnReasonModel();
		$this->Order_GoodsModel        = new Order_GoodsModel();

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$act = request_string('act');

		if ($act == "detail")
		{
			$data = $this->detail();/*var_dump($data);die;*/
			$this->view->setMet('detail');
			$d = $data;
		}
		elseif ($act == "add")
		{
			$data = $this->add();

			if ($data == -3)
			{
				$this->view->setMet('error3');
			}
			elseif ($data == -1)
			{
				$this->view->setMet('error2');
			}
			elseif ($data == 0)
			{
				$this->view->setMet('error');
			}
			else
			{
				$this->view->setMet('add');
			}
			$d = $data;
		}
		else
		{
			$Yf_Page                   = new Yf_Page();
			$Yf_Page->listRows         = 10;
			$rows                      = $Yf_Page->listRows;
			$offset                    = request_int('firstRow', 0);
			$page                      = ceil_r($offset / $rows);
			$start_time                = request_string("start_time");
			$end_time                  = request_string("end_time");
			$order_id                  = request_string("order_id");
			$state                     = request_int("state", 1);
			$cond_row['buyer_user_id'] = Perm::$userId;         //店铺ID
			if ($start_time)
			{
				$cond_row['return_add_time:>='] = $start_time;
			}
			if ($end_time)
			{
				$cond_row['return_add_time:<='] = $end_time;
			}
			if ($order_id)
			{
				$cond_row['order_number'] = $order_id;
			}
			if ($state)
			{
				$cond_row['return_type'] = $state;
			}
			$data               = $this->orderReturnModel->getReturnList($cond_row, array('return_add_time' => 'DESC'), $page, $rows);
			$data['state']      = $state;
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			$d                  = $data;

		}
		if ($this->typ == "json")
		{
			$d['goods_list'] = array_values($d['goods']);
			$this->data->addBody(-140, $d);
		}
		else
		{
			include $this->view->getView();
		}
	}


	public function detail()
	{
		$return_id                   = request_int("id");
		$cond_row['order_return_id'] = $return_id;
		$cond_row['buyer_user_id']   = Perm::$userId;

		$data = $this->orderReturnModel->getReturn($cond_row);
		if ($data['order_goods_id'])
		{
			$data['goods'] = $this->orderGoodsModel->getOne($data['order_goods_id']);
			$data['text']  = _("退货");
		}
		else
		{
			$data['text'] = _("退款");
		}
		$data['order'] = $this->orderBaseModel->getOne($data['order_number']);
		$return_limit  = $this->orderReturnModel->getByWhere(array(
																 'order_number' => $data['order']['order_id'],
																 'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
															 ));
		$cash          = 0;
		foreach ($return_limit as $v)
		{
			$cash += $v['return_cash'];
		}
		$data['return_limit'] = $cash;
		return $data;
	}

	public function add()
	{
		$Order_StateModel = new Order_StateModel();
		$order_id         = request_string("oid");
		$goods_id         = request_int("gid");
		if ($goods_id)
		{
			$goods               = $this->orderGoodsModel->getOne($goods_id);
			$data['order']       = $this->orderBaseModel->getOne($goods['order_id']);
			$data['goods'][]     = $goods;
			$data['return_cash'] = $goods['order_goods_num'] * $goods['goods_price'];
			if ($data['order']['order_status'] < Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
			{
				return -1;
			}
			else
			{
				$data['return_goods'] = 1;
			}
			$data['text'] = _("退货");
			$return       = $this->orderReturnModel->getByWhere(array(
																	'order_goods_id' => $goods_id,
																	'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
																));
																	
		}
		else
		{
			$data['order']        = $this->orderBaseModel->getOne($order_id);
			$cond_row['order_id'] = $order_id;
			$data['goods']        = $this->orderGoodsModel->getByWhere($cond_row);
			$data['return_cash']  = $data['order']['order_goods_amount'];
			$data['return_goods'] = 0;
			$data['text']         = _("退款");
			if ($data['order']['order_status'] < Order_StateModel::ORDER_PAYED)
			{
				return -1;
			}
			$return = $this->orderReturnModel->getByWhere(array(
															  'order_number' => $order_id,
															  'order_goods_id' => 0,
															  'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
														  ));
														  
		}
		$data['order_id'] = $order_id;
		$data['goods_id'] = $goods_id;
		$data['reason']   = $this->orderReturnReasonModel->getByWhere(array(), array('order_return_reason_sort' => 'ASC'));

		$return_limit = $this->orderReturnModel->getByWhere(array(
																'order_number' => $data['order']['order_id'],
																'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
															));
		$cash         = 0;
		$nums         = 0;
		$goods_ids    = array_column($return_limit, "order_goods_id");
		$return_goods = array();
		if ($goods_ids)
		{
			$return_goods = $this->orderGoodsModel->getByWhere(array(
																   "order_goods_id:not in" => $goods_ids,
																   "order_id" => $data['order']['order_id']
															   ));
		}
		else
		{
			$return_goods = $this->orderGoodsModel->getByWhere(array("order_id" => $data['order']['order_id']));
		}
		$price = 0;
		$price = array_sum(array_column($return_goods, 'order_goods_amount'));

		$cash       = array_sum(array_column($return_limit, 'return_cash'));
		$nums       = array_sum(array_column($return_limit, 'order_goods_num'));
		$goods_nums = $this->orderGoodsModel->getByWhere(array("order_id" => $data['order']['order_id']));
		$nums2      = 0;
		$nums2      = array_sum(array_column($goods_nums, 'order_goods_num'));

		$data['nums']         = $nums2 - $nums;
		$data['return_limit'] = $cash;
		$data['cash_limit']   = $data['order']['order_payment_amount'] - $cash - $data['order']['order_shipping_fee'];
		if ($goods_id)
		{
			if ($price == 0)
			{
				return -3;
			}
			$data['goods'][0]['goods_price'] = floor($data['goods'][0]['goods_price'] * (($data['order']['order_payment_amount'] - $data['order']['order_shipping_fee'] - $cash) / $price) * 100) / 100;
			$data['return_cash']             = floor($data['goods'][0]['goods_price'] * $data['goods'][0]['order_goods_num'] * 100) / 100;
			if ($data['nums'] == $data['goods'][0]['order_goods_num'])
			{
				$data['return_cash'] = $data['cash_limit'];
			}
		}
		if (!empty($return))
		{
			return 0;
		}
		else
		{
			return $data;
		}

	}

	public function addReturn()
	{
		$Order_StateModel = new Order_StateModel();
		$order_id         = request_string("order_id");
		$goods_id         = request_int("goods_id");
		$flag2            = true;
		$Number_SeqModel  = new Number_SeqModel();
		$prefix           = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('Ymd'));
		$return_number    = $Number_SeqModel->createSeq($prefix);
		$return_id        = sprintf('%s-%s-%s-%s', 'TD', Perm::$userId, 0, $return_number);

		$field['return_cash']      = request_float("return_cash");
		$field['return_message']   = request_string("return_message");
		$field['return_code']      = $return_id;
		$field['return_reason_id'] = request_int("return_reason_id");
		$reason                    = $this->orderReturnReasonModel->getOne($field['return_reason_id']);
		$field['return_reason']    = $reason['order_return_reason_content'];
		if ($order_id)
		{
			$field['order_number'] = $order_id;
			$order                 = $this->orderBaseModel->getOne($order_id);

			if ($order['order_status'] >= Order_StateModel::ORDER_PAYED)
			{
				if ($order['order_is_virtual'])
				{
					$field['return_type'] = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
				}
				else
				{
					$field['return_type'] = Order_ReturnModel::RETURN_TYPE_ORDER;
				}
			}
			else
			{
				$flag2 = false;
			}
			$field['return_goods_return'] = 0;
			$data['text']                 = _("退货");
			$return                       = $this->orderReturnModel->getByWhere(array(
																					'order_number' => $order_id,
																					'order_goods_id' => 0,
																					'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
																				));
		}

		if ($goods_id)
		{
			$nums     = request_int("nums");
			$goods    = $this->orderGoodsModel->getOne($goods_id);
			$order    = $this->orderBaseModel->getOne($goods['order_id']);
			$order_id = $goods['order_id'];
			if ($order['order_status'] >= Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
			{
				$field['order_number']      = $goods['order_id'];
				$field['order_goods_id']    = $goods_id;
				$field['order_goods_name']  = $goods['goods_name'];
				$field['order_goods_price'] = $goods['goods_price'];
				$field['order_goods_num']   = $nums;
				$field['order_goods_pic']   = $goods['goods_image'];
				$field['return_type']       = Order_ReturnModel::RETURN_TYPE_GOODS;
			}
			else
			{
				$flag2 = false;
			}
			if ($order['order_status'] < Order_StateModel::ORDER_WAIT_CONFIRM_GOODS)
			{
				$field['return_goods_return'] = 0;
			}
			else
			{
				$field['return_goods_return'] = 1;
			}
			$data['text'] = _("退款");
			$return       = $this->orderReturnModel->getByWhere(array(
																	'order_goods_id' => $goods_id,
																	'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
																));
		}
		$field['order_amount']        = $order['order_payment_amount'];
		$field['seller_user_id']      = $order['shop_id'];
		$field['seller_user_account'] = $order['shop_name'];
		$field['buyer_user_id']       = $order['buyer_user_id'];
		$field['buyer_user_account']  = $order['buyer_user_name'];
		$field['return_add_time']     = get_date_time();
		$field['order_is_virtual']    = $order['order_is_virtual'];


		$return_limit = $this->orderReturnModel->getByWhere(array(
																'order_number' => $order_id,
																'return_state:!=' => Order_ReturnModel::RETURN_SELLER_UNPASS
															));
		$goods_ids    = array_column($return_limit, "order_goods_id");
		$return_goods = array();
		if ($goods_ids)
		{
			$return_goods = $this->orderGoodsModel->getByWhere(array(
																   "order_goods_id:not in" => $goods_ids,
																   "order_id" => $order['order_id']
															   ));
		}
		else
		{
			$return_goods = $this->orderGoodsModel->getByWhere(array("order_id" => $order['order_id']));
		}
		$price = 0;

		$price = array_sum(array_column($return_goods, 'order_goods_amount'));

		$cash            = 0;
		$nums2           = 0;
		$commission_cash = 0;

		$cash            = array_sum(array_column($return_limit, 'return_cash'));
		$nums2           = array_sum(array_column($return_limit, 'order_goods_num'));
		$commission_cash = array_sum(array_column($return_limit, 'return_commision_fee'));

		$goods_nums = $this->orderGoodsModel->getByWhere(array("order_id" => $order['order_id']));
		$nums4      = 0;

		$nums4 = array_sum(array_column($goods_nums, 'order_goods_num'));

		$nums3       = $nums4 - $nums2;
		$cash_limit  = $order['order_payment_amount'] - $cash - $order['order_shipping_fee'];
		$return_flag = true;
		if ($goods_id)
		{
			if ($price == 0)
			{
				return 0;
			}
			$goods['goods_price'] = floor($goods['goods_price'] * (($order['order_payment_amount'] - $order['order_shipping_fee'] - $cash) / $price) * 100) / 100;
			$return_cash          = floor($nums * $goods['goods_price'] * 100) / 100;
			if ($return_cash != $field['return_cash'])
			{
				$return_flag = false;
			}
			$e = 0.00001;
			if (($nums3 == $nums) && (abs($cash_limit == $field['return_cash']) < $e))
			{
				$return_flag = true;
			}
			if ($order['order_commission_fee'] && $goods['order_goods_commission'])
			{
				/*echo $field['return_cash'] . '---' ;//47.88
				echo $price .'----';//58
				echo $order['order_commission_fee'] .'---';//0.00
				echo $commission_cash.'-----';//0
				echo $order['order_commission_fee'].'-----';//0.00
				echo $goods['order_goods_commission'].'----';//0.00*/
				fb($field['return_cash']);
				fb($price);
				fb($order['order_commission_fee']);
				fb($commission_cash);


				if($order['order_commission_fee'] > 0 )
				{
					$field['return_commision_fee'] = floor(($field['return_cash'] / $price) * (($order['order_commission_fee'] - $commission_cash) / $order['order_commission_fee']) * $goods['order_goods_commission'] * 100) / 100;
				}
				else
				{
					$field['return_commision_fee'] = floor(($field['return_cash'] / $price) * (($order['order_commission_fee'] - $commission_cash) / 1) * $goods['order_goods_commission'] * 100) / 100;
				}

				fb($field['return_commision_fee']);

			}
		}
		else
		{
			if ($order['order_commission_fee'])
			{
				$field['return_commision_fee'] = floor(($field['return_cash'] / $order['order_payment_amount']) * $order['order_commission_fee'] * 100) / 100;
			}
		}

		if (empty($return) && (bccomp($cash_limit,$field['return_cash'] < 0)) && ($field['return_cash'] > 0) && $return_flag)
		{
			if ($order['buyer_user_id'] == Perm::$userId && $flag2)
			{
				$rs_row = array();
				$this->orderReturnModel->sql->startTransactionDb();
				$add_flag = $this->orderReturnModel->addReturn($field, true);
				check_rs($add_flag, $rs_row);

				//paycenter中添加退款的交易记录
				/*$key      = Yf_Registry::get('paycenter_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$paycenter_app_id = Yf_Registry::get('paycenter_app_id');
				$formvars = array();

				$formvars['buyer_user_id']    = $order['buyer_user_id']; //买家id
				$formvars['buyer_user_name'] = $order['buyer_user_name']; //买家名称
				$formvars['seller_user_id'] = $order['seller_user_id'];
				$formvars['seller_user_name'] = $order['seller_user_name'];
				$formvars['amount'] = $field['return_cash'];

				$formvars['app_id']        = $paycenter_app_id;

				fb($formvars);

				$rs = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=returnMoney&typ=json', $url), $formvars);*/

				fb($goods_id);
				if ($goods_id)
				{
					$goods_field['goods_refund_status'] = Order_GoodsModel::REFUND_IN;
					$edit_flag                          = $this->orderGoodsModel->editGoods($goods_id, $goods_field);
					check_rs($edit_flag, $rs_row);
				}
				else
				{
					$order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
					$edit_flag                          = $this->orderBaseModel->editBase($order_id, $order_field);
					check_rs($edit_flag, $rs_row);
				}
				$flag = is_ok($rs_row);
				if ($flag && $this->orderReturnModel->sql->commitDb())
				{
					$msg    = _('success');
					$status = 200;
					$shopBase = new Shop_BaseModel();
					$shop_detail = $shopBase->getOne($field['seller_user_id']);
					$message = new MessageModel();
					if ($goods_id)
					{
						//退款提醒
						//$order_id
						$message->sendMessage('Refund reminder',$shop_detail['user_id'], $shop_detail['user_name'], $order_id, $shop_name = NULL, 1, 1);
					}else{
						
						//退货提醒
						//$order_id
						$message->sendMessage('Return reminder',$shop_detail['user_id'], $shop_detail['user_name'], $order_id, $shop_name = NULL, 1, 1);
					}
				}
				else
				{
					$this->orderReturnModel->sql->rollBackDb();
					$msg    = _('failure');
					$status = 250;
				}
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function virtualReturn($order_id)
	{
		$Order_StateModel = new Order_StateModel();
		$flag2            = true;
		$Number_SeqModel  = new Number_SeqModel();
		$prefix           = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('Ymd'));
		$return_number    = $Number_SeqModel->createSeq($prefix);
		$return_id        = sprintf('%s-%s-%s-%s', 'TD', Perm::$userId, 0, $return_number);

		$field['return_message']       = _('虚拟商品过期自动退款');
		$field['return_code']          = $return_id;
		$field['return_reason_id']     = 0;
		$field['return_reason']        = "";
		$field['order_number']         = $order_id;
		$order                         = $this->orderBaseModel->getOne($order_id);
		$field['return_type']          = Order_ReturnModel::RETURN_TYPE_VIRTUAL;
		$field['return_goods_return']  = 0;
		$field['return_cash']          = $order['order_payment_amount'];
		$field['order_amount']         = $order['order_payment_amount'];
		$field['seller_user_id']       = $order['shop_id'];
		$field['seller_user_account']  = $order['shop_name'];
		$field['buyer_user_id']        = $order['buyer_user_id'];
		$field['buyer_user_account']   = $order['buyer_user_name'];
		$field['return_add_time']      = get_date_time();
		$field['return_commision_fee'] = $order['order_commission_fee'];
		$field['return_state']         = Order_ReturnModel::RETURN_PLAT_PASS;
		$field['return_finish_time']   = get_date_time();

		$rs_row = array();
		$this->orderReturnModel->sql->startTransactionDb();

		$add_flag = $this->orderReturnModel->addReturn($field, true);
		check_rs($add_flag, $rs_row);

		$order_field['order_refund_status'] = Order_BaseModel::REFUND_IN;
		$order_field['order_refund_status'] = Order_BaseModel::REFUND_COM;
		$edit_flag                          = $this->orderBaseModel->editBase($order_id, $order_field);
		check_rs($edit_flag, $rs_row);

		$sum_data['order_refund_amount']         = $order['order_payment_amount'];
		$sum_data['order_commission_return_fee'] = $order['order_commission_fee'];
		$edit_flag                               = $this->orderBaseModel->editBase($order_id, $sum_data, true);
		check_rs($edit_flag, $rs_row);

		$key      = Yf_Registry::get('shop_api_key');
		$url         = Yf_Registry::get('paycenter_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		$formvars             = array();
		$formvars['app_id']        = $shop_app_id;
		$formvars['user_id']  = $order['buyer_user_id'];
		$formvars['user_account'] = $order['buyer_user_name'];
		$formvars['seller_id'] = $order['seller_user_id'];
		$formvars['seller_account'] = $order['seller_user_name'];
		$formvars['amount']   = $order['order_payment_amount'];
		$formvars['order_id'] = $order_id;
		//$formvars['goods_id'] = $return['order_goods_id'];
		$formvars['uorder_id'] = $order['payment_number'];


		$rs                   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Api_Pay_Pay&met=refundTransfer&typ=json', $url), $formvars);

		if ($rs['status'] == 200)
		{
			check_rs(true, $rs_row);
		}
		else
		{
			check_rs(false, $rs_row);
		}

		$flag = is_ok($rs_row);
		if ($flag && $this->orderReturnModel->sql->commitDb())
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$this->orderReturnModel->sql->rollBackDb();
			$msg    = _('failure');
			$status = 250;
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}


	public function agree()
	{
		$Order_StateModel        = new Order_StateModel();
		$order_return_id         = request_int("order_return_id");
		$return_platform_message = request_string("return_platform_message");
		$return                  = $this->Order_ReturnModel->getOne($order_return_id);

		//根据order_id查找订单信息
		$order_base = $this->Order_BaseModel->getOne($return['order_id']);

		$data['return_platform_message'] = $return_platform_message;
		$data['return_state']            = Order_ReturnModel::RETURN_PLAT_PASS;
		$data['return_finish_time']      = get_date_time();
		$rs_row                          = array();
		$this->Order_ReturnModel->sql->startTransactionDb();
		$edit_flag = $this->Order_ReturnModel->editReturn($order_return_id, $data);
		check_rs($edit_flag, $rs_row);

		if ($return['order_goods_id'])
		{
			$goods_data['goods_refund_status'] = Order_GoodsModel::REFUND_COM;
			$edit_flag                         = $this->Order_GoodsModel->editGoods($return['order_goods_id'], $goods_data);
			check_rs($edit_flag, $rs_row);
		}
		else
		{
			$order_data['order_refund_status'] = Order_BaseModel::REFUND_COM;
			$edit_flag                         = $this->Order_BaseModel->editBase($return['order_number'], $order_data);
			check_rs($edit_flag, $rs_row);
		}
		$sum_data['order_refund_amount']         = $return['return_cash'];
		$sum_data['order_commission_return_fee'] = $edit_flag = $this->Order_BaseModel->editBase($return['order_number'], $sum_data, true);
		check_rs($edit_flag, $rs_row);

		$key                  = Yf_Registry::get('paycenter_api_key');
		$formvars             = array();
		$formvars['user_id']  = $return['buyer_user_id'];
		$formvars['user_account'] = $return['buyer_user_account'];
		$formvars['seller_id'] = $return['seller_user_id'];
		$formvars['seller_account'] = $return['seller_user_account'];
		$formvars['amount']   = $return['return_cash'];
		$formvars['order_id'] = $return['order_number'];
		$formvars['goods_id'] = $return['order_goods_id'];
		$formvars['uorder_id'] = $order_base['payment_other_number'];
		$rs                   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=refundTransfer&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);

		if ($rs['status'] == 200)
		{
			check_rs(true, $rs_row);
		}
		else
		{
			check_rs(false, $rs_row);
		}
		$flag = is_ok($rs_row);
		if ($edit_flag && $this->Order_ReturnModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->Order_ReturnModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');
		}
		$this->data->addBody(-140, $data, $msg, $status);

	}

}

?>