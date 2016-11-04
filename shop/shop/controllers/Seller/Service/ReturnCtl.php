<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Service_ReturnCtl extends Seller_Controller
{
	public $orderReturnModel = null;
	public $orderBaseModel   = null;
	public $orderGoodsModel  = null;

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
		$this->orderReturnModel = new Order_ReturnModel();
		$this->orderBaseModel   = new Order_BaseModel();
		$this->orderGoodsModel  = new Order_GoodsModel();

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function listReturn($type)
	{
		$Yf_Page                    = new Yf_Page();
		$Yf_Page->listRows          = 10;
		$rows                       = $Yf_Page->listRows;
		$offset                     = request_int('firstRow', 0);
		$page                       = ceil_r($offset / $rows);
		$cond_row['seller_user_id'] = Perm::$shopId;         //店铺ID
		$keyword                    = request_string("keys");
		$start_time                 = request_string("start_date");
		$end_time                   = request_string("end_date");
		$state                      = request_int("status");

		if ($keyword)
		{
			if ($type == Order_ReturnModel::RETURN_TYPE_GOODS)
			{
				$cond_row['order_goods_name:LIKE'] = "%" . $keyword . "%";
			}
			else
			{
				$cond_row['order_number'] = $keyword;
			}
		}
		if ($state)
		{
			$cond_row['return_state'] = $state;
		}
		if ($type == Order_ReturnModel::RETURN_TYPE_GOODS)
		{
			$cond_row['return_type'] = Order_ReturnModel::RETURN_TYPE_GOODS;
		}
		else
		{
			$cond_row['return_type:!='] = Order_ReturnModel::RETURN_TYPE_GOODS;
		}
		if ($start_time)
		{
			$cond_row['return_add_time:>='] = $start_time;
		}
		if ($end_time)
		{
			$cond_row['return_add_time:<='] = $end_time;
		}

		$data = $this->orderReturnModel->getReturnList($cond_row, array('return_add_time' => 'DESC'), $page, $rows);

		$goods_ids = array_column($data['items'],"order_goods_id");

		if($goods_ids)
		{
			$goods = $this->orderGoodsModel->getByWhere(array("order_goods_id:IN" => $goods_ids));
			foreach ($data['items'] as $k => $v)
			{
				if($v['order_goods_id'])
				{
					$data['items'][$k]['good'] = $goods[$v['order_goods_id']];
				}
			}
		}

		$Yf_Page->totalRows = $data['totalsize'];
		$data['page']       = $Yf_Page->prompt();
		$data['keys']       = $keyword;
		$data['state']      = $state;
		$data['start_date'] = $start_time;
		$data['end_date']   = $end_time;
		return $data;
	}

	public function orderReturn()
	{
		$act = request_string('act');

		if ($act == "detail")
		{
			$data = $this->detail();
			$this->view->setMet('detail');
		}
		else
		{
			$data = $this->listReturn(Order_ReturnModel::RETURN_TYPE_ORDER);
		}
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function goodsReturn()
	{
		$act = request_string('act');

		if ($act == "detail")
		{
			$data = $this->detail();
			$this->view->setMet('detail');
		}
		else
		{
			$data = $this->listReturn(Order_ReturnModel::RETURN_TYPE_GOODS);
		}
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $data);
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
		$cond_row['seller_user_id']  = Perm::$shopId;

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

	public function agreeReturn()
	{
		$Order_StateModel    = new Order_StateModel();
		$order_return_id     = request_int("order_return_id");
		$return_shop_message = request_string("return_shop_message");
		$return              = $this->orderReturnModel->getOne($order_return_id);

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($return_shop_message, $matche_row))
		{
			fb($matche_row);
			$msg    = _('含有违禁词');
			fb($msg);
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		if ($return['seller_user_id'] == Perm::$shopId)
		{
			$data['return_shop_message'] = $return_shop_message;
			if ($return['return_goods_return'] == Order_ReturnModel::RETURN_GOODS_RETURN)
			{
				$data['return_state'] = Order_ReturnModel::RETURN_SELLER_PASS;
			}
			else
			{
				$data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
			}
			$data['return_shop_time'] = get_date_time();
			$flag                     = $this->orderReturnModel->editReturn($order_return_id, $data);

			if ($flag)
			{
				$status = 200;
				$msg    = _('success');
				//退款退货提醒
				$message = new MessageModel();
				$message->sendMessage('Refund return reminder', $return['buyer_user_id'], $return['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, 1);
			}
			else
			{
				$status = 250;
				$msg    = _('failure2');
			}
		}
		else
		{
			$status = 250;
			$msg    = _('failure3');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function agreeGoods()
	{
		$order_return_id = request_int("order_return_id");
		$return          = $this->orderReturnModel->getOne($order_return_id);

		if ($return['seller_user_id'] == Perm::$shopId)
		{
			$data['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
			$flag                 = $this->orderReturnModel->editReturn($order_return_id, $data);

			if ($flag)
			{
				//退款退货提醒
				$message = new MessageModel();
				$message->sendMessage('Refund return reminder', $return['buyer_user_id'], $return['buyer_user_account'], $order_id = NULL, $shop_name = NULL, 0, 1);
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$status = 250;
				$msg    = _('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function closeReturn()
	{
		$Order_StateModel    = new Order_StateModel();
		$order_return_id     = request_int("order_return_id");
		$return_shop_message = request_string("return_shop_message");

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($return_shop_message, $matche_row))
		{
			$data   = array();
			$msg    = _('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}
		$return = $this->orderReturnModel->getOne($order_return_id);
		if ($return['seller_user_id'] == Perm::$shopId)
		{
			$data['return_shop_message'] = $return_shop_message;
			$data['return_state']        = Order_ReturnModel::RETURN_SELLER_UNPASS;

			$rs_row = array();
			$this->orderReturnModel->sql->startTransactionDb();
			$edit_flag = $this->orderReturnModel->editReturn($order_return_id, $data);
			check_rs($edit_flag, $rs_row);

			if ($return['order_goods_id'])
			{
				$order                              = $this->orderBaseModel->getOne($return['order_number']);
				$goods_field['goods_refund_status'] = Order_GoodsModel::REFUND_NO;
				$edit_flag                          = $this->orderGoodsModel->editGoods($return['order_goods_id'], $goods_field);
				check_rs($edit_flag, $rs_row);
			}
			else
			{
				$order_field['order_refund_status'] = Order_BaseModel::REFUND_NO;
				$edit_flag                          = $this->orderBaseModel->editBase($return['order_number'], $order_field);
				check_rs($edit_flag, $rs_row);
			}
			$flag = is_ok($rs_row);
			if ($flag && $this->orderReturnModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$this->orderReturnModel->sql->rollBackDb();
				$status = 250;
				$msg    = _('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
}

?>