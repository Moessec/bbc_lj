<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Trade_OrderCtl extends Api_Controller
{
	
	public $Order_BaseModel = null;

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
		$this->Order_BaseModel = new Order_BaseModel();

		$this->tradeOrderModel = new Order_BaseModel();
		
	}

	/*
	 * 获取商品订单列表
	 * */
	public function getOrderList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$order_row = array();
		$sidx      = request_string('sidx');
		$sord      = request_string('sord', 'asc');
		$action    = request_string('action');

		if ($sidx)
		{
			$order_row[$sidx] = $sord;
		}
		
		if (request_string('order_id'))
		{
			$cond_row['order_id:LIKE'] = request_string('order_id') . '%';
		}
		if (request_string('buyer_name'))
		{
			$cond_row['buyer_name:LIKE'] = request_string('buyer_name') . '%';
		}
		if (request_string('shop_name'))
		{
			$cond_row['shop_name:LIKE'] = request_string('shop_name') . '%';
		}
		if (request_string('payment_id'))
		{
			$cond_row['payment_id'] = request_string('payment_id') . '%';
		}
		if (!empty($action) && $action == 'virtual')
		{
			$cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_VIRTUAL;
		}
		if (request_string('payment_date_f'))
		{
			$cond_row['payment_time:>='] = request_string('payment_date_f');
		}
		if (request_string('payment_date_t'))
		{
			$cond_row['payment_time:<='] = request_string('payment_date_t');
		}

		$data = $this->Order_BaseModel->getPlatOrderList($cond_row, array(), $page, $rows);
		$this->data->addBody(-140, $data);
	}

	/*
	 * 取消订单
	 * */
	public function cancelOrder()
	{
		$order_id = request_string('order_id');

		$data['order_status']          = Order_StateModel::ORDER_CANCEL;
		$data['order_cancel_identity'] = Order_BaseModel::CANCEL_USER_SYSTEM;

		$flag = $this->Order_BaseModel->editBase($order_id, $data);

		if ($flag != false)
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

	/**
	 * 获取订单详细信息
	 */
	public function getOrderInfo()
	{
		$order_id = request_string('order_id');

		$data = $this->Order_BaseModel->getPhysicalInfoData(array('order_id' => $order_id));
		
		if ($data)
		{
			$msg    = _('success');
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
	 * 收到货款
	 */
	public function receivePay()
	{
		$order_id                     = request_string('order_id');
		$data['payment_number']       = request_string('payment_number');
		$data['payment_time']         = request_string('payment_date');
		$data['payment_name']         = request_string('payment_name');
		$data['payment_other_number'] = request_string('payment_other_number');
		$data['order_status']         = Order_StateModel::ORDER_PAYED;

		$flag = $this->Order_BaseModel->editBase($order_id, $data);

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

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getPaymentNum()
	{
		$data['payment_number'] = $this->Order_BaseModel->createPaymentNum();

		$msg    = _('success');
		$status = 200;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//修改订单状态(数组支付成功)
	public function editOrderRowSatus()
	{
		$order_id = request_row('order_id');
		$uorder_id = request_string('uorder_id');

		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();
       
		if (is_array($order_id))
		{
			$order_id = array_filter($order_id);

			$order_id_str = implode(',',$order_id);

			foreach ($order_id as $key => $val)
			{
				$flag = $this->tradeOrderModel->editOrderStatusAferPay($val,$uorder_id);

				$order_base = $this->tradeOrderModel->getOne($val);

				if ($flag && !$order_base['order_is_virtual'])
				{
					$message           = new MessageModel();
					$code              = 'place_your_order';
					$message_user_id   = $order_base['seller_user_id'];
					$message_user_name = $order_base['seller_user_name'];
					$shop_name         = $order_base['shop_name'];
					$message->sendMessage($code, $message_user_id, $message_user_name, $val, $shop_name, 1, 1);

				}

				$buyer_user_id = $order_base['buyer_user_id'];
				$buyer_user_name = $order_base['buyer_user_name'];
			}
		}
		else
		{
			$order_id_str = $order_id;

			$flag = $this->tradeOrderModel->editOrderStatusAferPay($order_id);

			$order_base = $this->tradeOrderModel->getOne($order_id);

			if ($flag)
			{
				$message           = new MessageModel();
				$code              = 'place_your_order';
				$message_user_id   = $order_base['seller_user_id'];
				$message_user_name = $order_base['seller_user_name'];
				$shop_name         = $order_base['shop_name'];
				$message->sendMessage($code, $message_user_id, $message_user_name, $order_id_str, $shop_name, 1, 1);
			}

			$buyer_user_id = $order_base['buyer_user_id'];
			$buyer_user_name = $order_base['buyer_user_name'];

		}


		if ($flag && $this->tradeOrderModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');

			//付款成功提醒
			//$order_id
			$message = new MessageModel();
			$message->sendMessage('Payment reminder', $buyer_user_id, $buyer_user_name, $order_id = $order_id_str, $shop_name = NULL, 0, 1);
		}
		else
		{
			$this->tradeOrderModel->sql->rollBackDb();
			$m      = $this->tradeOrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

    //后台显示数据查询
    public function getEvaluateList()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $data = $Goods_EvaluationModel->listByWhere(array(), array(), $page, $rows);

        if($data)
        {
            $msg = _('success');
            $status = 200;
        }
        else
        {
            $msg = _('failure');
            $status = 250;
        }

        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function removeEvaluate()
    {
        $id = request_int('id');
        $Goods_EvaluationModel = new Goods_EvaluationModel();
        $flag = $Goods_EvaluationModel->removeEvalution($id);

        if($flag)
        {
            $msg = _('success');
            $status = 200;
        }
        else
        {
            $msg = _('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

    public function getShopEvaluateList()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $Shop_BaseModel       = new Shop_BaseModel();
        $User_BaseModel       = new User_BaseModel();
        $data = $Shop_EvaluationModel->listByWhere(array(), array(), $page, $rows);
        $items = $data['items'];
        unset($data['items']);
        if(!empty($items))
        {
            foreach($items as $key=>$value)
            {
                $shop_id = $value['shop_id'];
                $user_id = $value['user_id'];
                if($shop_id)
                {
                    $data_shop = $Shop_BaseModel->getOne($shop_id);
                    if($data_shop)
                        $items[$key]['shop_name'] = $data_shop['shop_name'];
                    else
                        $items[$key]['shop_name'] = '';
                }
                if($user_id)
                {
                    $data_user = $User_BaseModel->getOne($user_id);
                    if($data_user)
                        $items[$key]['user_name'] = $data_user['user_account'];
                    else
                        $items[$key]['user_name'] = '';
                }
            }
        }
        $data['items'] = $items;

        if($items)
        {
            $msg = _('success');
            $status = 200;
        }
        else
        {
            $msg = _('failure');
            $status = 250;
        }

        $this->data->addBody(-140,$data,$msg,$status);
    }

    public function removeShopEvaluate()
    {
        $id = request_int('id');
        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $flag = $Shop_EvaluationModel->removeEvalution($id);

        if($flag)
        {
            $msg = _('success');
            $status = 200;
        }
        else
        {
            $msg = _('failure');
            $status = 250;
        }

        $this->data->addBody(-140, array(), $msg, $status);
    }

	public function test()
	{
		$id = request_int('test');

		$this->data->addBody(-140, array('re'=>$id.'1111'));
	}
    //erp下载订单
    public function downOrder()
    {

//        if (!empty($action) && $action == 'virtual')
//        {
//            $cond_row['order_is_virtual'] = Order_BaseModel::ORDER_IS_VIRTUAL;
//        }
        if (request_string('end_created'))
        {
            $this->Order_BaseModel->sql->setWhere('order_create_time',request_string('end_created'),'<=');
        }
        if (request_string('start_created'))
        {
            $this->Order_BaseModel->sql->setWhere('order_create_time',request_string('start_created'),'>=');
        }
        $this->Order_BaseModel->sql->setWhere('order_status',array(8,9),'NOT IN');
        $this->Order_BaseModel->sql->setLimit(0,999999999);
        $Order_Base = $this->Order_BaseModel->getBase('*');
        $Order_GoodsModel=new Order_GoodsModel();
        $Order_GoodsModel->sql->setLimit(0,999999999);
        $Order_Goods = $Order_GoodsModel->getGoods('*');
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base  = $Shop_BaseModel->getBase('*');
        $User_InfoModel = new User_InfoModel();
        $User_InfoModel->sql->setLimit(0,999999999);
        $User_Info  = $User_InfoModel->getInfo('*');
        $data=array();
        if($Order_Base){
            foreach($Order_Base as $key=>$value){
                $data['items'][$key]['order_id']=$value['order_id'];
                $data['items'][$key]['shop_id']=$value['shop_id'];
                $data['items'][$key]['store_account']=$Shop_Base[$value['shop_id']]['shop_account'];
                $data['items'][$key]['shop_name']=$value['shop_name'];
                $data['items'][$key]['shop_mobile']=$Shop_Base[$value['shop_id']]['shop_tel'];
                $data['items'][$key]['user_id']=$value['buyer_user_id'];
                $data['items'][$key]['user_account']=$value['buyer_user_name'];
                $data['items'][$key]['user_sex']=$User_Info[$value['buyer_user_id']]['user_sex'];
                $data['items'][$key]['user_mobile']=$User_Info[$value['buyer_user_id']]['user_mobile'];
                $data['items'][$key]['user_email']=$User_Info[$value['buyer_user_id']]['user_email'];
                $data['items'][$key]['user_qq']=$User_Info[$value['buyer_user_id']]['user_qq'];
                $data['items'][$key]['user_ww']=$User_Info[$value['buyer_user_id']]['user_ww'];
                $data['items'][$key]['create_time']=strtotime($value['order_create_time']);
                $data['items'][$key]['consignee_mobile']=$value['order_receiver_contact'];
                $data['items'][$key]['consignee_tel']='';
                $data['items'][$key]['consignee']=$value['order_receiver_name'];
                $data['items'][$key]['order_delivery_address_province']='';
                $data['items'][$key]['order_delivery_address_city']='';
                $data['items'][$key]['order_delivery_address_county']='';
                $data['items'][$key]['order_delivery_address_address']='';
                if($value['order_receiver_address']){
                    $order_delivery_address=explode(' ',$value['order_receiver_address']);
                    if($order_delivery_address[0]=='北京' ||$order_delivery_address[0]=='天津' ||$order_delivery_address[0]=='上海' ||$order_delivery_address[0]=='重庆'){
                        $data['items'][$key]['order_delivery_address_province']=$order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_city']=$order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_county']=$order_delivery_address[1];
                        for($i=2;$i<count($order_delivery_address);$i++){
                            $data['items'][$key]['order_delivery_address_address'].=$order_delivery_address[$i];
                        }
                    }else{
                        $data['items'][$key]['order_delivery_address_province']=$order_delivery_address[0];
                        $data['items'][$key]['order_delivery_address_city']=$order_delivery_address[1];
                        $data['items'][$key]['order_delivery_address_county']=$order_delivery_address[2];
                        for($i=3;$i<count($order_delivery_address);$i++){
                            $data['items'][$key]['order_delivery_address_address'].=$order_delivery_address[$i];
                        }
                    }
                }
                $data['items'][$key]['des']=$value['order_message'];
                $data['items'][$key]['payment_id']=$value['payment_id'];
                $data['items'][$key]['payment_name']=$value['payment_name'];
                $data['items'][$key]['order_goods_amount']=$value['order_goods_amount'];
                $data['items'][$key]['order_discount_amount']=$value['order_discount_fee'];
                $data['items'][$key]['order_payment']=$value['order_payment_amount'];
                $data['items'][$key]['order_shipping_fee_amount']=$value['order_shipping_fee'];
                $data['items'][$key]['order_shipping_fee']=$value['order_shipping_fee'];
                $data['items'][$key]['voucher_id']=$value['voucher_id'];
                $data['items'][$key]['voucher_number']=$value['voucher_code'];
                $data['items'][$key]['voucher_price']=$value['voucher_price'];
                $data['items'][$key]['order_point_add']=$value['order_points_add'];
                $data['items'][$key]['payment_time']=strtotime($value['payment_time']);
                if($value['order_status']==1){
                    $data['items'][$key]['status']=1;
                }else if($value['order_status']==2){
                    $data['items'][$key]['status']=2;
                }else if($value['order_status']==3){
                    $data['items'][$key]['status']=2;
                }else if($value['order_status']==4){
                    $data['items'][$key]['status']=3;
                }else if($value['order_status']==5){
                    $data['items'][$key]['status']=4;
                }else if($value['order_status']==6){
                    $data['items'][$key]['status']=4;
                }else if($value['order_status']==7){
                    $data['items'][$key]['status']=0;
                }
                $data['items'][$key]['order_finished_time']=strtotime($value['order_finished_time']);
                $data['items'][$key]['discounts']=$value['order_discount_fee'];
                $data['items'][$key]['order_type']=$value['order_is_virtual'];
                $goods_msg=array();
                foreach($Order_Goods as $k=>$v){
                    if($v['order_id']==$value['order_id']){
                        $goods_msg[$k]['id']=$v['order_goods_id'];
                        $goods_msg[$k]['order_id']=$v['order_id'];
                        $goods_msg[$k]['setmeal']=$v['spec_id'];
                        $goods_msg[$k]['pid']=$v['goods_id'];
                        $goods_msg[$k]['name']=$v['goods_name'];
                        $goods_msg[$k]['pcatid']=$v['goods_class_id'];
                        $goods_msg[$k]['price']=$v['goods_price'];
                        $goods_msg[$k]['num']=$v['order_goods_num'];
                        $goods_msg[$k]['pic']=$v['goods_image'];
                        $goods_msg[$k]['status']=$data['items'][$key]['status'];
                    }
                }
                $data['items'][$key]['goods_msg']=$goods_msg;
            }
        }
        $this->data->addBody(-140, $data);
    }
}

?>