<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_OrderCtl extends Buyer_Controller
{
	public $tradeOrderModel = null;

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

		$this->tradeOrderModel = new Order_BaseModel();
	}

	/**
	 * 实物交易订单
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}

	public function physical()
	{
		$act      = request_string('act');
		$order_id = request_string('order_id');

		//订单详情页
		if ($act == 'details')
		{
			$data = $this->tradeOrderModel->getOrderDetail($order_id);
			$this->view->setMet('details');
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);


			$status  = request_string('status');
			$recycle = request_int('recycle');
			//待付款
			if ($status == 'wait_pay')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
			}
			//待发货 -> 只可退款
			if ($status == 'wait_perpare_goods')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
			}
			//已付款
			if ($status == 'order_payed')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_PAYED;
			}
			//待收货、已发货 -> 退款退货
			if ($status == 'wait_confirm_goods')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
			}
			//已完成 -> 订单评价
			if ($status == 'finish')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_FINISH;
			}
			//已取消
			if ($status == 'cancel')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_CANCEL;
			}
			//订单回收站
			if ($recycle)
			{
				$order_row['order_buyer_hidden'] = Order_BaseModel::IS_BUYER_HIDDEN;
			}
			else
			{
				$order_row['order_buyer_hidden:!='] = Order_BaseModel::IS_BUYER_HIDDEN;
			}

			if (request_string('start_date'))
			{
				$order_row['order_create_time:>'] = request_string('start_date');
			}
			if (request_string('end_date'))
			{
				$order_row['order_create_time:<'] = request_string('end_date');
			}
			if (request_string('orderkey'))
			{
				$order_row['order_id:LIKE'] = '%' . request_string('orderkey') . '%';
			}


			$user_id                           = Perm::$row['user_id'];
			$order_row['buyer_user_id']        = $user_id;
			$order_row['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
			$order_row['order_is_virtual']     = Order_BaseModel::ORDER_IS_REAL; //实物订单

			$data = $this->tradeOrderModel->getBaseList($order_row, array('order_create_time' => 'DESC'), $page, $rows);
			fb($data);
			fb("订单列表");
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
		}

		fb($data);
		
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}









	//计算距离
	public function getDistance($lat1, $lng1, $lat2, $lng2){      
          $earthRadius = 6378138; //近似地球半径米
          // 转换为弧度
          $lat1 = ($lat1 * pi()) / 180;
          $lng1 = ($lng1 * pi()) / 180;
          $lat2 = ($lat2 * pi()) / 180;
          $lng2 = ($lng2 * pi()) / 180;
          // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
          $calcLatitude = $lat2 - $lat1;
          $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  
       $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
          $calculatedDistance = $earthRadius * $stepTwo;
          return round($calculatedDistance);
   }
	//地址转经纬度
	public function addr_to_location($addr)
	{	$location=array();
		//去空格
		$addr=str_replace(' ','',$addr);
		//查出经纬度
		$location_json = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address=$addr&output=json&ak=CvQKTKQ3upsNAL7sLLFTvDqHc4g8nChG");
		//解析出经度
		$location_json=(string)$location_json;
		$lng_pos=strpos($location_json,'lng"');
		$lng_pos=$lng_pos+5;
		$lat_pos=strpos($location_json,',"lat"');
		$sub_len=(int)$lat_pos-(int)$lng_pos;
		$lng=substr($location_json,$lng_pos,$sub_len);
		//解析出纬度
		$lat_pos=strpos($location_json,'lat"');
		$lat_pos=$lat_pos+5;
		$end_pos=strpos($location_json,'},"pre');
		$sub_len=(int)$end_pos-(int)$lat_pos;
		$lat=substr($location_json,$lat_pos,$sub_len);
		$location['lat']=$lat;
		$location['lng']=$lng;
		return $location;
	}
	/**
	 * 确认收货
	 *
	 * @author     Zhuyt
	 */
	public function confirmOrder()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			include $this->view->getView();
		}
		else
		{
			$Order_BaseModel = new Order_BaseModel();

			//开启事物
			$Order_BaseModel->sql->startTransactionDb();

			$order_id = request_string('order_id');
			
			$order_base           = $Order_BaseModel->getOne($order_id);
			$order_payment_amount = $order_base['order_payment_amount'];

			$condition['order_status'] = Order_StateModel::ORDER_FINISH;

			$condition['order_finished_time'] = get_date_time();

			$flag = $Order_BaseModel->editBase($order_id, $condition);

			//修改订单商品表中的订单状态
			$edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;
			$Order_GoodsModel               = new Order_GoodsModel();
			$order_goods_id                 = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

			$Order_GoodsModel->editGoods($order_goods_id, $edit_row);

			//将需要确认的订单号远程发送给Paycenter修改订单状态
			//远程修改paycenter中的订单状态
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('paycenter_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$formvars = array();

			$formvars['order_id']    = $order_id;
			$formvars['app_id']        = $shop_app_id;
			$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');

			fb($formvars);

			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=confirmOrder&typ=json', $url), $formvars);


			/*
			*  经验与成长值
			*/
			$user_points        = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
			$user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

			if ($order_payment_amount / $user_points < $user_points_amount)
			{
				$user_points = floor($order_payment_amount / $user_points);
			}
			else
			{
				$user_points = $user_points_amount;
			}

			$user_grade        = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
			$user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值

			if ($order_payment_amount / $user_grade > $user_grade_amount)
			{
				$user_grade = floor($order_payment_amount / $user_grade);
			}
			else
			{
				$user_grade = $user_grade_amount;
			}

			$User_ResourceModel = new User_ResourceModel();
			//获取积分经验值
			$ce = $User_ResourceModel->getResource(Perm::$userId);

			$resource_row['user_points'] = $ce[Perm::$userId]['user_points'] * 1 + $user_points * 1;
			$resource_row['user_growth'] = $ce[Perm::$userId]['user_growth'] * 1 + $user_grade * 1;

			$res_flag = $User_ResourceModel->editResource(Perm::$userId, $resource_row);

			$User_GradeModel = new User_GradeModel;
			//升级判断
			$res_flag = $User_GradeModel->upGrade(Perm::$userId, $resource_row['user_growth']);
			//积分
			$points_row['user_id']           = Perm::$userId;
			$points_row['user_name']         = Perm::$row['user_account'];
			$points_row['class_id']          = Points_LogModel::ONBUY;
			$points_row['points_log_points'] = $user_points;
			$points_row['points_log_time']   = get_date_time();
			$points_row['points_log_desc']   = '确认收货';
			$points_row['points_log_flag']   = 'confirmorder';

			$Points_LogModel = new Points_LogModel();

			$Points_LogModel->addLog($points_row);

			//成长值
			$grade_row['user_id']         = Perm::$userId;
			$grade_row['user_name']       = Perm::$row['user_account'];
			$grade_row['class_id']        = Grade_LogModel::ONBUY;
			$grade_row['grade_log_grade'] = $user_grade;
			$grade_row['grade_log_time']  = get_date_time();
			$grade_row['grade_log_desc']  = '确认收货';
			$grade_row['grade_log_flag']  = 'confirmorder';

			$Grade_LogModel = new Grade_LogModel;
			$Grade_LogModel->addLog($grade_row);
			
			
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
	 * 删除订单
	 *
	 * @author     Zhuyt
	 */
	public function hideOrder()
	{
		$order_id = request_string('order_id');
		$user     = request_string('user');
		$op       = request_string('op');

		$edit_row = array();

		$Order_BaseModel = new Order_BaseModel();
		//买家删除订单
		if ($user == 'buyer')
		{
			if ($op == 'del')
			{
				$edit_row['order_buyer_hidden'] = Order_BaseModel::IS_BUYER_REMOVE;
			}
			else
			{
				$edit_row['order_buyer_hidden'] = Order_BaseModel::IS_BUYER_HIDDEN;
			}

		}
		if ($user == 'seller')
		{
			if ($op == 'del')
			{
				$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_REMOVE;
			}
			else
			{
				$edit_row['order_shop_hidden'] = Order_BaseModel::IS_SELLER_HIDDEN;
			}

		}

		$flag = $Order_BaseModel->editBase($order_id, $edit_row);

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

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * 还原回收站中的订单
	 *
	 * @author     Zhuyt
	 */
	public function restoreOrder()
	{
		$order_id = request_string('order_id');
		$user     = request_string('user');

		$edit_row = array();

		$Order_BaseModel = new Order_BaseModel();
		//还原买家隐藏订单
		if ($user == 'buyer')
		{
			$edit_row['order_buyer_hidden'] = Order_BaseModel::NO_BUYER_HIDDEN;
		}
		if ($user == 'seller')
		{
			$edit_row['order_shop_hidden'] = Order_BaseModel::NO_SELLER_HIDDEN;
		}

		$flag = $Order_BaseModel->editBase($order_id, $edit_row);

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

		$this->data->addBody(-140, array(), $msg, $status);

	}

	/**
	 * 虚拟兑换订单
	 *
	 * @author     Zhuyt
	 */
	public function virtual()
	{
		$act      = request_string('act');
		$order_id = request_string('order_id');

		//订单详情页
		if ($act == 'detail')
		{
			$data = $this->tradeOrderModel->getOrderDetail($order_id);
			$this->view->setMet('detail');
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);


			$status  = request_string('status');
			$recycle = request_int('recycle');
			//待付款
			if ($status == 'wait_pay')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_PAY;
			}
			//待发货 -> 只可退款
			if ($status == 'wait_perpare_goods')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;
			}
			//待收货、已发货 -> 退款退货
			if ($status == 'wait_confirm_goods')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
			}
			//已完成 -> 订单评价
			if ($status == 'finish')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_FINISH;
			}
			//已取消
			if ($status == 'cancel')
			{
				$order_row['order_status'] = Order_StateModel::ORDER_CANCEL;
			}
			//订单回收站
			if ($recycle)
			{
				$order_row['order_buyer_hidden'] = Order_BaseModel::IS_BUYER_CANCEL;
			}
			else
			{
				$order_row['order_buyer_hidden:!='] = Order_BaseModel::IS_BUYER_HIDDEN;
			}

			if (request_string('start_date'))
			{
				$order_row['order_create_time:>'] = request_string('start_date');
			}
			if (request_string('end_date'))
			{
				$order_row['order_create_time:<'] = request_string('end_date');
			}
			if (request_string('orderkey'))
			{
				$order_row['order_id:LIKE'] = '%' . request_string('key') . '%';
			}


			$user_id                            = Perm::$row['user_id'];
			$order_row['buyer_user_id']         = $user_id;
			$order_row['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_REMOVE;
			$order_row['order_is_virtual']      = Order_BaseModel::ORDER_IS_VIRTUAL; //虚拟订单
			$data                               = $this->tradeOrderModel->getBaseList($order_row, array('order_create_time' => 'DESC'), $page, $rows);

			fb($data);
			fb("订单列表");
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
		}
		
		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 评价订单/晒单
	 *
	 * @author     Zhuyt
	 */
	public function evaluation()
	{
		$order_id = request_string('order_id');

		$act = request_string('act');
		if ($act == 'again')
		{
			$evaluation_goods_id = request_int("oge_id");

			//获取已评价信息
			$Goods_EvaluationModel = new Goods_EvaluationModel();
			$data = $Goods_EvaluationModel->getOne($evaluation_goods_id);
			if($data['image'])
			{
				$data['image_row'] = explode(',',$data['image']);
				$data['image_row'] = array_filter($data['image_row']);
			}

			//商品信息
			$Order_GoodsModel = new Order_GoodsModel();
			$data['goods_base'] = current($Order_GoodsModel->getByWhere(array('goods_id'=>$data['goods_id'],'order_id'=>$data['order_id'])));

			//订单信息
			$Order_BaseModel    = new Order_BaseModel();
			$data['order_base'] = $Order_BaseModel->getOne($data['order_id']);

			//评价用户的信息
			$User_InfoModel = new User_InfoModel();
			$data['user_info'] = $User_InfoModel->getOne($data['order_base']['buyer_user_id']);

			fb($data);

			$this->view->setMet('evalagain');
		}
		elseif ($act == 'add')
		{
			//订单信息
			$Order_BaseModel    = new Order_BaseModel();
			$data['order_base'] = $Order_BaseModel->getOne($order_id);

			//评价用户的信息
			$User_InfoModel = new User_InfoModel();
			$data['user_info'] = $User_InfoModel->getOne($data['order_base']['buyer_user_id']);

			//店铺信息
			$Shop_BaseModel    = new Shop_BaseModel();
			$data['shop_base'] = $Shop_BaseModel->getOne($data['order_base']['shop_id']);

			//查找出订单中的商品
			$Order_GoodsModel   = new Order_GoodsModel();
			$order_goods_id_row = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

			//商品信息
			foreach ($order_goods_id_row as $ogkey => $order_good_id)
			{
				$data['order_goods'][] = $Order_GoodsModel->getOne($order_good_id);
			}
			fb($data);

			if ('json' == $this->typ)
			{
				$this->data->addBody(-140, $data);
			}
			else
			{
				$this->view->setMet('evaladd');
			}
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);


			//获取买家的所有评论
			$user_id = Perm::$userId;

			$Goods_EvaluationModel = new Goods_EvaluationModel();

			$goods_evaluation_row            = array();
			$goods_evaluation_row['user_id'] = $user_id;

			$data = $Goods_EvaluationModel->getEvaluationByUser($goods_evaluation_row, array(), $page, $rows);
			fb($data);
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();

		}

		include $this->view->getView();
	}


	/**
	 * 生成实物订单
	 *
	 * @author     Zhuyt
	 */
	public function addOrder()
	{
		$user_id      = Perm::$row['user_id'];

		$user_account = Perm::$row['user_account'];

		$flag         = true;

		$receiver_name     = request_string('receiver_name');//收货人

		$receiver_address  = request_string('receiver_address');//收货地址
		
		$receiver_phone    = request_string('receiver_phone');//收货人联系方式

		$invoice           = request_string('invoice');//是否需要发票

		$cart_id           = request_row("cart_id");//购物车ID
		
		$shop_id           = request_row("shop_id");//店铺ID

		$remark            = request_row("remark");//留言备注数组
		
		$increase_goods_id = request_row("increase_goods_id");//加价购产品ID

		$voucher_id        = request_row('voucher_id');//代金券

		$pay_way_id		   = request_int('pay_way_id');//支付方式1在线支付   2 货到付款

		// $ps_way_id		   = request_int('ps_way_id');//PS方式
		$invoice_id		   = request_int('invoice_id');//发票

		$address_id        = request_int('address_id');//地址ID
		$cost_void_array   = request_row('ps_type');//免运费商家
		
		//判断支付方式为在线支付还是货到付款,如果是货到付款则订单状态直接为待发货状态，如果是在线支付则订单状态为待付款
		
		if($pay_way_id == PaymentChannlModel::PAY_ONLINE)//1
		{	
			$order_status = Order_StateModel::ORDER_WAIT_PAY;//等待付款，状态为1
		}
		
		if($pay_way_id == PaymentChannlModel::PAY_CONFIRM)//2 
		{	
			$order_status = Order_StateModel::ORDER_WAIT_PREPARE_GOODS;// 等待发货  状态为3
		}


		$shop_remark = array_combine($shop_id, $remark);//留言数组
		
		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();

		//获取用户的折扣信息
		$user_id        = Perm::$row['user_id'];
		$User_InfoModel = new User_InfoModel();
		$user_info      = $User_InfoModel->getOne($user_id);
		$User_GradeMode = new User_GradeModel();
		$user_grade     = $User_GradeMode->getOne($user_info['user_grade']);
		$user_rate      = $user_grade['user_grade_rate'];

		if ($user_rate <= 0)
		{
			$user_rate = 100;
		}

		//重组加价购商品
		//活动下的所有规则下的换购商品信息
		
		if ($increase_goods_id)
		{
			$Increase_RedempGoodsModel          = new Increase_RedempGoodsModel();
			$Goods_BaseModel                    = new Goods_BaseModel();
			$Goods_CatModel                     = new Goods_CatModel();
			$cond_row_exc['redemp_goods_id:IN'] = $increase_goods_id;
			$redemp_goods_rows                  = $Increase_RedempGoodsModel->getIncreaseRedempGoodsByWhere($cond_row_exc);

			$increase_shop_row = array();
			foreach ($redemp_goods_rows as $key => $val)
			{
				fb($val['goods_id']);
				//获取加价购商品的信息
				$goods_base         = $Goods_BaseModel->getOne($val['goods_id']);
				$val['goods_name']  = $goods_base['goods_name'];
				$val['goods_image'] = $goods_base['goods_image'];
				$val['cat_id']      = $goods_base['cat_id'];
				$val['common_id']   = $goods_base['common_id'];
				$val['shop_id']	 = $goods_base['shop_id'];
				//获取分类佣金
				$cat_base = $Goods_CatModel->getOne($val['cat_id']);
				if ($cat_base)
				{
					$cat_commission = $cat_base['cat_commission'];
				}
				else
				{
					$cat_commission = 0;
				}

				$val['commission'] = number_format(($val['redemp_price'] * $cat_commission / 100), 2, '.', '');

				if (in_array($val['shop_id'], $increase_shop_row))
				{
					$increase_shop_row[$val['shop_id']][] = $val;
					$increase_shop_row[$val['shop_id']]['price'] += $val['redemp_price'];
					$increase_shop_row[$val['shop_id']]['commission'] += $val['commission'];
				}
				else
				{
					$increase_shop_row[$val['shop_id']][]             = $val;
					$increase_shop_row[$val['shop_id']]['price']      = $val['redemp_price'];
					$increase_shop_row[$val['shop_id']]['commission'] = $val['commission'];
				}
			}
			fb($increase_shop_row);
			fb($redemp_goods_rows);
			fb("加价购商品信息");
		}

		//重组代金券信息
		if ($voucher_id)
		{
			//查找代金券的信息
			$Voucher_BaseModel = new Voucher_BaseModel();

			$voucher_cond_row['voucher_id:IN'] = $voucher_id;
			$voucher_row                       = $Voucher_BaseModel->getByWhere($voucher_cond_row);

			$shop_voucher_row = array();
			foreach ($voucher_row as $voukey => $vouval)
			{
				$shop_voucher_row[$vouval['voucher_shop_id']] = $vouval;
			}
			fb($shop_voucher_row);
		}

		$cond_row  = array('cart_id:IN' => $cart_id);

		$order_row = array();
		//购物车中的商品信息
		$CartModel = new CartModel();
		$data      = $CartModel->getCardList($cond_row, $order_row);

		fb($data);
		fb("购物车中的商品信息");


		if(!$data['count'])
		{
           $flag = false;
		}


		//查找收货地址
		$User_AddressModel = new User_AddressModel();
		$city_id = 0;

		if($address_id)
		{
			$user_address = $User_AddressModel->getOne($address_id);

			$city_id = $user_address['user_address_city_id'];
		}
		//运费
		$Transport_TypeModel = new Transport_TypeModel();
		$transport_cost      = $Transport_TypeModel->countTransportCost($city_id, $cart_id);
		unset($data['count']);
		// var_dump($data);exit;	













		

			foreach($shop_id as $k =>$v)
		{	
				//获取商家店址
			$this->shopShippingAddressModel = new Shop_ShippingAddressModel();

			$seller_address= $this->shopShippingAddressModel->getBaseList(array(
				'shop_id'=>(int)$v,
				'shipping_address_default'=>1
				), array('shipping_address_time' => 'desc'), $page, 10);
			
			$seller_address=$seller_address['items'][0]['shipping_address_area'].$seller_address['items'][0]['shipping_address_address'];
			
			$data[$v]['shop_address']=$seller_address;
		}

		//查询有自配送模板的商家数组
		$has_zps=array();
		
		foreach($data as $k=>$v)
		{	
			//根据商家ID查询模板
			$logisticsZpsModel=null;
			$this->logisticsZpsModel = new Waybill_ZpsModel();
			$zps_model = $this->logisticsZpsModel->getZpsList(array(
				'shop_id'=>$k

				));
			
			if(!empty($zps_model['items']))
			{//将配送模板存入数组 店铺ID=>array('距离'=>价格)
				$shop_model=array();
				foreach($zps_model['items'] as $k1=>$v1)
				{
					$shop_model[$v1['zps_range']]=$v1['zps_cost'];
				}
				
				$has_zps[$k]=$shop_model;

			}
		
			
		}
		
		//shop_address
		foreach($has_zps as $k=> $v)
		{	
			$shop_address=$data[$k]['shop_address'];
			//有默认地址
			if($shop_address)
			{	
				//商家经纬度
				$shop_location_array=$this->addr_to_location($shop_address);
				//买家所选地址
				// foreach($data['address'] as $k1 =>$v1)
				// {
				// 	if($v1['user_address_id']==$address_id)
				// 	{
				// 		$buyer_address=$v1['user_address_area'].$v1['user_address_address'];
				// 	}

				// }
				//买家经纬度
				$buyer_location_array=$this->addr_to_location($receiver_address);

				//经纬度计算距离
				$distance=$this->getDistance($shop_location_array['lng'],$shop_location_array['lat'],$buyer_location_array['lng'],$buyer_location_array['lat']);
				$km_distance=$distance/1000;//千米距离
				

			}
			
			
				//查找商家的配送距离区间,算出运费
			
			$seller_send_array=array_keys($v);
			
			

			$shop_need_change=array();
			
			for($i=0;$i<=count($seller_send_array)-1;$i++)
			{	
				if($seller_send_array[$i]<$km_distance&&$km_distance<$seller_send_array[$i+1])
				{  
					$shop_need_change[$k]=$v[$seller_send_array[$i+1]];
				}
			}
			
		}

		// 运费替换
		foreach($transport_cost as $key2 =>$val)
		{	
			foreach($shop_need_change as $key1 => $val1)
			{
				 if($key2 == $key1)
				 {
				 	$transport_cost[$key2]['cost']=(float)$val1;
				 }
			}

		}

		























		foreach($transport_cost as $k =>$v)
		{
			if(in_array($k,$cost_void_array))
			 {
			 	$transport_cost[$k]['cost']=0;
			 }
		}
		
		
		

		$Number_SeqModel = new Number_SeqModel();

		$Order_BaseModel = new Order_BaseModel();

		$Order_GoodsModel = new Order_GoodsModel();

		$Goods_BaseModel = new Goods_BaseModel();

		$PaymentChannlModel = new PaymentChannlModel();

		$Order_GoodsSnapshot = new Order_GoodsSnapshot();
		//合并支付订单的价格
			
		$uprice  = 0;
		$inorder = '';
		$utrade_title = '';	//商品名称 - 标题
		
		foreach ($data as $key_1=> $val_1)
			
		{		
			$trade_title = '';
			//生成店铺订单

			//计算加价购商品的价格
			if (isset($increase_shop_row[$key_1]))
			{
				$increase_price      = $increase_shop_row[$key_1]['price'];
				$increase_commission = $increase_shop_row[$key_1]['commission'];
			}
			else
			{
				$increase_price      = 0;
				$increase_commission = 0;
			}

			//总结店铺的优惠活动
			$order_shop_benefit = '';
			if ($val_1['mansong_info'])
			{
				$order_shop_benefit = $order_shop_benefit . '店铺满送活动:';
				if ($val_1['mansong_info']['rule_discount'])
				{
					$order_shop_benefit = $order_shop_benefit . ' 优惠' . format_money($val_1['mansong_info']['rule_discount']) . ' ';
				}
			}
			if ($user_rate < 100)
			{
				$order_shop_benefit = $order_shop_benefit . ' 会员折扣:' . $user_rate . '% ';
			}


			//计算店铺的代金券
			if (isset($shop_voucher_row[$key_1]))
			{
				$voucher_price = $shop_voucher_row[$key_1]['voucher_price'];
				$voucher_id    = $shop_voucher_row[$key_1]['voucher_id'];
				$voucher_code  = $shop_voucher_row[$key_1]['voucher_code'];

				$order_shop_benefit = $order_shop_benefit . ' 代金券:' . format_money($shop_voucher_row[$key_1]['voucher_price']) . ' ';
			}
			else
			{
				$voucher_price = 0;
				$voucher_id    = 0;
				$voucher_code  = 0;
			}

			//计算订单价格（未打会员折扣前的价格）
			
			$order_price = $val_1['sprice'] + $increase_price;

			//计算店铺的交易佣金
			$commission = $val_1['commission'] + $increase_commission;

			$prefix       = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('Ymd'));
			$order_number = $Number_SeqModel->createSeq($prefix);

			$order_id = sprintf('%s-%s-%s-%s', 'DD', $val_1['shop_user_id'], $key_1, $order_number);

			$order_row                           = array();
			$order_row['order_id']               = $order_id;
			$order_row['shop_id']                = $key_1;
			$order_row['shop_name']              = $val_1['shop_name'];
			$order_row['buyer_user_id']          = $user_id;
			$order_row['buyer_user_name']        = $user_account;

			$order_row['seller_user_id']         = $val_1['shop_user_id'];
			$order_row['seller_user_name']       = $val_1['shop_user_name'];
			$order_row['order_date']             = date('Y-m-d');
			$order_row['order_create_time']      = get_date_time();
			$order_row['order_receiver_name']    = $receiver_name;
			$order_row['order_receiver_address'] = $receiver_address;
			$order_row['order_receiver_contact'] = $receiver_phone;
			$order_row['order_invoice']          = $invoice;
			$order_row['order_invoice_id']	   = $invoice_id;
			$order_row['order_goods_amount']     = $val_1['sprice'] + $increase_price;
			$order_row['order_payment_amount']   = ($order_price * $user_rate) / 100 + $transport_cost[$key_1]['cost'] - $voucher_price;// 店铺商品价格 + 运费价格 + 加价购商品价格   - 代金券价格
		
			$order_row['order_discount_fee']     = ($order_price * (100 - $user_rate)) / 100 + $voucher_price;   //折扣金额  店铺优惠价格 + 会员折扣价格  +  代金券价格
			$order_row['order_point_fee']        = 0;    //买家使用积分
			$order_row['order_shipping_fee']     = $transport_cost[$key_1]['cost'];
			$order_row['order_message']          = $shop_remark[$key_1];
			$order_row['order_status']           = $order_status;
			$order_row['order_points_add']       = 0;    //订单赠送的积分
			$order_row['voucher_id']             = $voucher_id;    //代金券id
			$order_row['voucher_price']          = $voucher_price;    //代金券面额
			$order_row['voucher_code']           = $voucher_code;    //代金券编码
			$order_row['order_commission_fee']   = $commission;
			$order_row['order_is_virtual']       = 0;    //1-虚拟订单 0-实物订单
			$order_row['order_shop_benefit']     = $order_shop_benefit;  //店铺优惠
			$order_row['payment_id']			   = $pay_way_id;
			$order_row['payment_name']			   = $PaymentChannlModel->payWay[$pay_way_id];

			if(in_array($order_row['shop_id'],$cost_void_array))
			{	//免运费的商品
				
				$order_row['order_ps_type']='2';
			}

			$flag1 = $this->tradeOrderModel->addBase($order_row);


			/*fb("====order_base===");
			fb($flag1);*/
			$flag = $flag && $flag1;

			foreach ($val_1['goods'] as $k => $v)
			{

				//计算商品的优惠
				$order_goods_benefit = '';
				if (isset($v['goods_base']['promotion_type']))
				{
					if ($v['goods_base']['promotion_type'] == 'groupbuy')
					{
						$order_goods_benefit = $order_goods_benefit . '团购';

						if ($v['goods_base']['down_price'])
						{
							$order_goods_benefit = $order_goods_benefit . ':直降' . format_money($v['goods_base']['down_price']) . ' ';
						}
					}

					if ($v['goods_base']['promotion_type'] == 'xianshi')
					{
						$order_goods_benefit = $order_goods_benefit . '限时折扣';

						if ($v['goods_base']['down_price'])
						{
							$order_goods_benefit = $order_goods_benefit . ':直降' . format_money($v['goods_base']['down_price']) . ' ';
						}
					}

				}

				$order_goods_row                                  = array();
				$order_goods_row['order_id']                      = $order_id;
				$order_goods_row['goods_id']                      = $v['goods_base']['goods_id'];
				$order_goods_row['common_id']                     = $v['goods_base']['common_id'];
				$order_goods_row['buyer_user_id']                 = $user_id;
				$order_goods_row['goods_name']                    = $v['goods_base']['goods_name'];
				$order_goods_row['goods_class_id']                = $v['goods_base']['cat_id'];
				$order_goods_row['order_spec_info']               = $v['goods_base']['spec'];
				$order_goods_row['goods_price']                   = $v['now_price'];
				$order_goods_row['order_goods_num']               = $v['goods_num'];
				$order_goods_row['goods_image']                   = $v['goods_base']['goods_image'];
				$order_goods_row['order_goods_amount']            = $v['now_price'] * $v['goods_num'];
				$order_goods_row['order_goods_discount_fee']      = (abs($v['old_price'] - $v['now_price']) * $v['goods_num'] * (100 - $user_rate)) / 100;        //优惠价格
				$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
				$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
				$order_goods_row['order_goods_commission']        = $v['commission'];    //商品佣金
				$order_goods_row['shop_id']                       = $key_1;
				$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
				$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
				$order_goods_row['order_goods_benefit']           = $order_goods_benefit;
				$order_goods_row['order_goods_time']              = get_date_time();

				$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

				//加入交易快照表
				$order_goods_snapshot_add_row = array();
				$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
				$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
				$order_goods_snapshot_add_row['shop_id'] 		=	$v['goods_base']['shop_id'];
				$order_goods_snapshot_add_row['common_id'] 	=	$v['goods_base']['common_id'];
				$order_goods_snapshot_add_row['goods_id'] 		=	$v['goods_base']['goods_id'];
				$order_goods_snapshot_add_row['goods_name'] 	=	$v['goods_base']['goods_name'];
				$order_goods_snapshot_add_row['goods_image'] 	=	$v['goods_base']['goods_image'];
				$order_goods_snapshot_add_row['goods_price'] 	=	$v['now_price'];
				$order_goods_snapshot_add_row['freight'] 		=	$transport_cost[$key_1]['cost'];   //运费
				$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
				$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
				$order_goods_snapshot_add_row['snapshot_detail'] = $order_goods_benefit;

				$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);
				/*fb("====order_goods====");
				fb($flag2);*/
				$flag = $flag && $flag2;

				//删除商品库存
				$flag3 = $Goods_BaseModel->delStock($v['goods_id'], $v['goods_num']);

				$trade_title = $v['goods_base']['goods_name'];

				/*if($flag3 == 'no_stock')
				{
					$msg = '商品库存不足';
					fb($msg);
					$status = 250;
					$data = array();
					$this->data->addBody(-140, $data, $msg, $status);

					return false;
				}*/
				/*	fb("====flag3===");
					fb($flag3);*/
				$flag = $flag && $flag3;

				//从购物车中删除商品
				$flag4 = $CartModel->removeCart($v['cart_id']);
				/*fb("====flag4====");
				fb($flag4);*/
				$flag = $flag && $flag4;
				
			}

			//加价购商品
			if (isset($increase_shop_row[$key_1]))
			{
				unset($increase_shop_row[$key_1]['price']);
				unset($increase_shop_row[$key_1]['commission']);
				foreach ($increase_shop_row[$key_1] as $k => $v)
				{
					//判断加价购的商品库存
					fb($v);
					fb("加价购加入订单信息");
					$order_goods_row                                  = array();
					$order_goods_row['order_id']                      = $order_id;
					$order_goods_row['goods_id']                      = $v['goods_id'];
					$order_goods_row['common_id']                     = $v['common_id'];
					$order_goods_row['buyer_user_id']                 = $user_id;
					$order_goods_row['goods_name']                    = $v['goods_name'];
					$order_goods_row['goods_class_id']                = $v['cat_id'];
					$order_goods_row['goods_price']                   = $v['redemp_price'];
					$order_goods_row['order_goods_num']               = 1;
					$order_goods_row['goods_image']                   = $v['goods_image'];
					$order_goods_row['order_goods_amount']            = $v['redemp_price'];
					$order_goods_row['order_goods_discount_fee']      = ($v['redemp_price'] * (100 - $user_rate)) / 100;        //优惠价格
					$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
					$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
					$order_goods_row['order_goods_commission']        = $v['commission'];        //商品佣金
					$order_goods_row['shop_id']                       = $key_1;
					$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
					$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
					$order_goods_row['order_goods_benefit']           = '加价购商品';
					$order_goods_row['order_goods_time']              = get_date_time();

					$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

					//加入交易快照表(加价购商品)
					$order_goods_snapshot_add_row = array();
					$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
					$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
					$order_goods_snapshot_add_row['shop_id'] 		=	$v['shop_id'];
					$order_goods_snapshot_add_row['common_id'] 	=	$v['common_id'];
					$order_goods_snapshot_add_row['goods_id'] 		=	$v['goods_id'];
					$order_goods_snapshot_add_row['goods_name'] 	=	$v['goods_name'];
					$order_goods_snapshot_add_row['goods_image'] 	=	$v['goods_image'];
					$order_goods_snapshot_add_row['goods_price'] 	=	$v['redemp_price'];
					$order_goods_snapshot_add_row['freight'] 		=	$transport_cost[$key]['cost'];   //运费
					$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
					$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
					$order_goods_snapshot_add_row['snapshot_detail'] = '加价购商品';

					$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);

					/*fb("====order_goods====");
                    fb($flag2);*/
					$flag = $flag && $flag2;

					//删除商品库存
					$flag3 = $Goods_BaseModel->delStock($v['goods_id'], 1);
					/*	fb("====flag3===");
                        fb($flag3);*/
					$flag = $flag && $flag3;

				}
			}

			//店铺满赠商品
			if ($val_1['mansong_info'] && $val_1['mansong_info']['gift_goods_id'])
			{
				$order_goods_row                                  = array();
				$order_goods_row['order_id']                      = $order_id;
				$order_goods_row['goods_id']                      = $val_1['mansong_info']['gift_goods_id'];
				$order_goods_row['common_id']                     = $val_1['mansong_info']['common_id'];
				$order_goods_row['buyer_user_id']                 = $user_id;
				$order_goods_row['goods_name']                    = $val_1['mansong_info']['goods_name'];
				$order_goods_row['goods_class_id']                = 0;
				$order_goods_row['goods_price']                   = 0;
				$order_goods_row['order_goods_num']               = 1;
				$order_goods_row['goods_image']                   = $val_1['mansong_info']['goods_image'];
				$order_goods_row['order_goods_amount']            = 0;
				$order_goods_row['order_goods_discount_fee']      = 0;        //优惠价格
				$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
				$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
				$order_goods_row['order_goods_commission']        = 0;    //商品佣金
				$order_goods_row['shop_id']                       = $key_1;
				$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
				$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
				$order_goods_row['order_goods_benefit']           = '店铺满赠商品';
				$order_goods_row['order_goods_time']              = get_date_time();

				$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

				//加入交易快照表(满赠商品)
				$order_goods_snapshot_add_row = array();
				$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
				$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
				$order_goods_snapshot_add_row['shop_id'] 		=	$key_1;
				$order_goods_snapshot_add_row['common_id'] 	=	$val_1['mansong_info']['common_id'];
				$order_goods_snapshot_add_row['goods_id'] 		=	$val_1['mansong_info']['gift_goods_id'];
				$order_goods_snapshot_add_row['goods_name'] 	=	$val_1['mansong_info']['goods_name'];
				$order_goods_snapshot_add_row['goods_image'] 	=	$val_1['mansong_info']['goods_image'];
				$order_goods_snapshot_add_row['goods_price'] 	=	0;
				$order_goods_snapshot_add_row['freight'] 		=	$transport_cost[$key_1]['cost'];   //运费
				$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
				$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
				$order_goods_snapshot_add_row['snapshot_detail'] = '满赠商品';

				$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);

				/*fb("====order_goods====");
				fb($flag2);*/
				$flag = $flag && $flag2;

				//删除商品库存
				$flag3 = $Goods_BaseModel->delStock($val_1['mansong_info']['gift_goods_id'], 1);
				/*	fb("====flag3===");
					fb($flag3);*/
				$flag = $flag && $flag3;

			}


			//支付中心生成订单
			$key_1      = Yf_Registry::get('shop_api_key');//支付key

			$url         = Yf_Registry::get('paycenter_api_url');//支付url
			
			$shop_app_id = Yf_Registry::get('shop_app_id');
			
			$formvars = array();

			$formvars['app_id']					= $shop_app_id;
			$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
			$formvars['consume_trade_id']     = $order_row['order_id'];
			$formvars['order_id']             = $order_row['order_id'];
			$formvars['buy_id']               = Perm::$userId;
			$formvars['buyer_name'] 		   = Perm::$row['user_account'];
			$formvars['seller_id']            = $order_row['seller_user_id'];
			$formvars['seller_name']		   = $order_row['seller_user_name'];
			$formvars['order_state_id']       = $order_row['order_status'];
			$formvars['order_payment_amount'] = $order_row['order_payment_amount'];
			$formvars['order_commission_fee']  = $order_row['order_commission_fee'];
			$formvars['trade_remark']         = $order_row['order_message'];
			$formvars['trade_create_time']    = $order_row['order_create_time'];
			$formvars['trade_title']			= $trade_title;		//商品名称 - 标题
			fb($formvars);
			
			$rs = get_url_with_encrypt($key_1, sprintf('%s?ctl=Api_Pay_Pay&met=addConsumeTrade&typ=json',$url), $formvars);
			
			fb("合并支付返回的结果");
			//将合并支付单号插入数据库
			if($rs['status'] == 200)
			{
				$Order_BaseModel->editBase($order_id,array('payment_number' => $rs['data']['union_order']));

				$flag = $flag && true;
			}
			else
			{
				$flag = $flag && false;
			}

			$uprice += $order_row['order_payment_amount'];
			
			$inorder .= $order_id . ',';

			/*if(substr($inorder, -1) == ",")
			{
				$inorder=substr($inorder,0,-1);
			}*/
			$utrade_title .=$trade_title;
		}
		
		//生成合并支付订单
		$key      = Yf_Registry::get('shop_api_key');
		$url         = Yf_Registry::get('paycenter_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');
		$formvars = array();

		$formvars['inorder']    = $inorder;
		$formvars['uprice']     = $uprice;
		
		$formvars['buyer']      = Perm::$userId;
		$formvars['trade_title'] = $utrade_title;
		$formvars['buyer_name'] = Perm::$row['user_account'];
		$formvars['app_id']        = $shop_app_id;
		$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');

		fb($formvars);

		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addUnionOrder&typ=json', $url), $formvars);
		fb($rs);

		if ($rs['status'] == 200)
		{
			$uorder = $rs['data']['uorder'];

			$flag = $flag && true;
		}
		else
		{
			$uorder = '';

			$flag = $flag && false;
		}
		var_dump($flag,$this->tradeOrderModel->sql->commitDb());exit;
		if ($flag && $this->tradeOrderModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');

			$data = array('uorder' => $uorder);
		}
		else
		{
			$this->tradeOrderModel->sql->rollBackDb();
			$m      = $this->tradeOrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;

			//订单提交失败，将paycenter中生成的订单删除
			if($uorder)
			{
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['uorder']    = $uorder;
				$formvars['app_id']        = $shop_app_id;

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=delUnionOrder&typ=json', $url), $formvars);
			}

			$data = array();
		}

		fb($flag);
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function addUorder()
	{
		$order_id = request_string('order_id');

		$key      = Yf_Registry::get('shop_api_key');
		$url         = Yf_Registry::get('paycenter_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');

		//查找paycenter中是否已经生成改订单
		$formvars = array();
		$formvars['app_id'] = $shop_app_id;
		$formvars['order_id'] = $order_id;
		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=getOrderInfo&typ=json', $url), $formvars);
		fb($rs);

		$Order_BaseModel = new Order_BaseModel();
		//开启事物
		$Order_BaseModel->sql->startTransactionDb();

		if($rs['status'] == 200 )
		{
			//此订单在paycenter中存在支付单号
			if($rs['data'])
			{
				$uorder = current($rs['data']);

				//将支付单号写入订单信息
				$edit_row['payment_number'] = $uorder['union_order_id'];
				$flag = $Order_BaseModel->editBase($order_id,$edit_row);

				$uorder_id = $uorder['union_order_id'];
			}
			else
			{
				$order_row = $Order_BaseModel->getOne($order_id);
				$Order_GoodsModel = new Order_GoodsModel();
				$goods_row = $Order_GoodsModel->getByWhere(array('order_id' => $order_id));
				$goods = current($goods_row);
				fb($goods);
				//此订单在paycenter中不存在支付单号，现在生成支付单号
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['app_id']					= $shop_app_id;
				$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
				$formvars['consume_trade_id']     = $order_row['order_id'];
				$formvars['order_id']             = $order_row['order_id'];
				$formvars['buy_id']               = Perm::$userId;
				$formvars['buyer_name'] 		   = Perm::$row['user_account'];
				$formvars['seller_id']            = $order_row['seller_user_id'];
				$formvars['seller_name']		   = $order_row['seller_user_name'];
				$formvars['order_state_id']       = $order_row['order_status'];
				$formvars['order_payment_amount'] = $order_row['order_payment_amount'];
				$formvars['trade_remark']         = $order_row['order_message'];
				$formvars['trade_create_time']    = $order_row['order_create_time'];
				$formvars['trade_title']			= $goods['goods_name'];		//商品名称 - 标题

				$rss = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addConsumeTrade&typ=json',$url), $formvars);

				if($rss['status'] == 200)
				{
					$edit_order_row['payment_number'] = $rss['data']['union_order'];
					$flag = $Order_BaseModel->editBase($order_id,$edit_order_row);

					$uorder_id = $rss['data']['union_order'];
				}
				else
				{
					$flag = false;
				}
			}

		}
		else
		{
			$flag = false;
		}

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
		$data = array('uorder' => $uorder_id);
		$this->data->addBody(-140, $data, $msg, $status);

	}

	//测试接口
	public function addtest()
	{
		$test = request_string('test');
		//生成合并支付订单
		$key      = Yf_Registry::get('shop_api_key');
		$url         = Yf_Registry::get('paycenter_api_url');
		$shop_app_id = Yf_Registry::get('shop_app_id');
		$formvars = array();

		$formvars['test'] = $test;
		$formvars['app_id']        = $shop_app_id;

		fb($formvars);

		$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addTest&typ=json', $url), $formvars);
		fb($rs);

		if($rs['status'] == 200)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}
		$this->data->addBody(-140, $rs, $msg, $status);
	}


	/**
	 * 生成虚拟订单
	 *
	 * @author     Zhuyt
	 */
	public function addVirtualOrder()
	{
		$user_id      = Perm::$row['user_id'];
		$user_account = Perm::$row['user_account'];
		$flag         = true;

		$goods_id          = request_int('goods_id');
		$goods_num         = request_int('goods_num');
		$buyer_phone       = request_string('buyer_phone');
		$remarks           = request_string('remarks');
		$increase_goods_id = request_row("increase_goods_id");
		$voucher_id        = request_row('voucher_id');
		$pay_way_id	   = request_int('pay_way_id');

		//获取商品信息
		$Goods_BaseModel = new Goods_BaseModel();
		//$data = $Goods_BaseModel->getGoodsInfo($goods_id);
		$CartModel = new CartModel();
		$data      = $CartModel->getVirtualCart($goods_id, $goods_num);
		fb($data);
		fb("虚拟订单商品信息");

		//$data['goods_base']['sumprice'] = number_format($goods_num * $data['goods_base']['now_price'],2,',','');

		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();

		//获取用户的折扣信息
		$user_id = Perm::$row['user_id'];

		$User_InfoModel = new User_InfoModel();
		$user_info      = $User_InfoModel->getOne($user_id);

		$User_GradeMode = new User_GradeModel();
		$user_grade     = $User_GradeMode->getOne($user_info['user_grade']);
		$user_rate      = $user_grade['user_grade_rate'];

		if ($user_rate <= 0)
		{
			$user_rate = 100;
		}

		//重组加价购商品
		//活动下的所有规则下的换购商品信息
		$increase_price      = 0;
		$increase_commission = 0;
		if ($increase_goods_id)
		{
			$Increase_RedempGoodsModel = new Increase_RedempGoodsModel();
			$Goods_BaseModel           = new Goods_BaseModel();
			$Goods_CatModel            = new Goods_CatModel();

			$cond_row_exc['redemp_goods_id:IN'] = $increase_goods_id;
			$redemp_goods_rows                  = $Increase_RedempGoodsModel->getIncreaseRedempGoodsByWhere($cond_row_exc);


			foreach ($redemp_goods_rows as $key => $val)
			{
				//获取加价购商品的信息
				$goods_base                             = $Goods_BaseModel->getOne($val['goods_id']);
				$redemp_goods_rows[$key]['goods_name']  = $goods_base['goods_name'];
				$redemp_goods_rows[$key]['goods_image'] = $goods_base['goods_image'];
				$redemp_goods_rows[$key]['cat_id']      = $goods_base['cat_id'];
				$redemp_goods_rows[$key]['common_id']   = $goods_base['common_id'];
				$redemp_goods_rows[$key]['shop_id']	 = $goods_base['shop_id'];

				$cat_base = $Goods_CatModel->getOne($redemp_goods_rows[$key]['cat_id']);
				if ($cat_base)
				{
					$cat_commission = $cat_base['cat_commission'];
				}
				else
				{
					$cat_commission = 0;
				}

				$redemp_goods_rows[$key]['commission'] = number_format(($val['redemp_price'] * $cat_commission / 100), 2, '.', '');
				$increase_commission += number_format(($val['redemp_price'] * $cat_commission / 100), 2, '.', '');


				$increase_price += $val['redemp_price'];
			}

			fb($redemp_goods_rows);
			fb("加价购商品信息");
		}


		//查找代金券的信息
		$Voucher_BaseModel = new Voucher_BaseModel();
		if ($voucher_id)
		{
			$voucher_base = $Voucher_BaseModel->getOne($voucher_id);

			$voucher_id    = $voucher_base['voucher_id'];
			$voucher_price = $voucher_base['voucher_price'];
			$voucher_code  = $voucher_base['voucher_code'];
		}
		else
		{
			$voucher_id    = 0;
			$voucher_price = 0;
			$voucher_code  = 0;
		}
		fb($voucher_base);
		fb("代金券");

		$Number_SeqModel = new Number_SeqModel();

		$Order_BaseModel = new Order_BaseModel();

		$Order_GoodsModel = new Order_GoodsModel();

		$Goods_BaseModel = new Goods_BaseModel();

		$PaymentChannlModel = new PaymentChannlModel();

		$Order_GoodsSnapshot = new Order_GoodsSnapshot();


		//生成店铺订单

		//总结店铺的优惠活动
		$order_shop_benefit = '';
		if ($data['mansong_info'])
		{
			$order_shop_benefit = $order_shop_benefit . '店铺满送活动:';
			if ($data['mansong_info']['rule_discount'])
			{
				$order_shop_benefit = $order_shop_benefit . ' 优惠' . format_money($data['mansong_info']['rule_discount']) . ' ';
			}
		}
		if ($user_rate < 100)
		{
			$order_shop_benefit = $order_shop_benefit . ' 会员折扣:' . $user_rate . '% ';
		}

		if($voucher_price)
		{
			$order_shop_benefit = $order_shop_benefit . ' 代金券:' . format_money($voucher_base['voucher_price']) . ' ';
		}

		$prefix       = sprintf('%s-%s-', Yf_Registry::get('shop_app_id'), date('Ymd'));
		$order_number = $Number_SeqModel->createSeq($prefix);

		$order_price = $data['goods_base']['sumprice'] + $increase_price;
		$commission  = $data['goods_base']['commission'] + $increase_commission;

		$order_id = sprintf('%s-%s-%s-%s', 'DD', $data['shop_base']['user_id'], $data['shop_base']['shop_id'], $order_number);

		$order_row                           = array();
		$order_row['order_id']               = $order_id;
		$order_row['shop_id']                = $data['shop_base']['shop_id'];
		$order_row['shop_name']              = $data['shop_base']['shop_name'];
		$order_row['buyer_user_id']          = $user_id;
		$order_row['buyer_user_name']        = $user_account;
		$order_row['seller_user_id']         = $data['shop_base']['user_id'];
		$order_row['seller_user_name']       = $data['shop_base']['user_name'];
		$order_row['order_date']             = date('Y-m-d');
		$order_row['order_create_time']      = get_date_time();
		$order_row['order_receiver_name']    = $user_account;
		$order_row['order_receiver_contact'] = $buyer_phone;
		$order_row['order_goods_amount']     = $order_price;
		$order_row['order_payment_amount']   = ($order_price * $user_rate) / 100 - $voucher_price;//$data['sprice'];

		$order_row['order_discount_fee']     = ($order_price * (100 - $user_rate)) / 100 + $voucher_price;   //折扣金额
		$order_row['order_point_fee']        = 0;    //买家使用积分
		$order_row['order_message']          = $remarks;
		$order_row['order_status']           = Order_StateModel::ORDER_WAIT_PAY;
		$order_row['order_points_add']       = 0;    //订单赠送的积分
		$order_row['voucher_id']             = $voucher_id;    //代金券id
		$order_row['voucher_price']          = $voucher_price;    //代金券面额
		$order_row['voucher_code']           = $voucher_code;    //代金券编码
		$order_row['order_commission_fee']   = $commission;  //交易佣金
		$order_row['order_is_virtual']       = 1;    //1-虚拟订单 0-实物订单
		$order_row['order_shop_benefit']     = $order_shop_benefit;  //店铺优惠
		$order_row['payment_id']			   = $pay_way_id;
		$order_row['payment_name']			   = $PaymentChannlModel->payWay[$pay_way_id];

		fb($order_row);

		$flag1 = $this->tradeOrderModel->addBase($order_row);

		$flag = $flag && $flag1;


		//计算商品的优惠
		$order_goods_benefit = '';
		if (isset($data['goods_base']['promotion_type']))
		{
			if ($data['goods_base']['promotion_type'] == 'groupbuy')
			{
				$order_goods_benefit = $order_goods_benefit . '团购';

				if ($data['goods_base']['down_price'])
				{
					$order_goods_benefit = $order_goods_benefit . ':直降' . format_money($data['goods_base']['down_price']) . ' ';
				}
			}

			if ($data['goods_base']['promotion_type'] == 'xianshi')
			{
				$order_goods_benefit = $order_goods_benefit . '限时折扣';

				if ($data['goods_base']['down_price'])
				{
					$order_goods_benefit = $order_goods_benefit . ':直降' . format_money($data['goods_base']['down_price']) . ' ';
				}
			}

		}

		$trade_title = '';

		//插入订单商品表
		$order_goods_row                                  = array();
		$order_goods_row['order_id']                      = $order_id;
		$order_goods_row['goods_id']                      = $data['goods_base']['goods_id'];
		$order_goods_row['common_id']                     = $data['goods_base']['common_id'];
		$order_goods_row['buyer_user_id']                 = $user_id;
		$order_goods_row['goods_name']                    = $data['goods_base']['goods_name'];
		$order_goods_row['goods_class_id']                = $data['goods_base']['cat_id'];
		$order_goods_row['order_spec_info']               = $data['goods_base']['spec'];
		$order_goods_row['goods_price']                   = $data['goods_base']['now_price'];
		$order_goods_row['order_goods_num']               = $goods_num;
		$order_goods_row['goods_image']                   = $data['goods_base']['goods_image'];
		$order_goods_row['order_goods_amount']            = $data['goods_base']['sumprice'];
		$order_goods_row['order_goods_discount_fee']      = ($data['goods_base']['sumprice'] * (100 - $user_rate)) / 100;        //优惠价格
		$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
		$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
		$order_goods_row['order_goods_commission']        = $data['goods_base']['commission'];   //商品佣金
		$order_goods_row['shop_id']                       = $data['goods_base']['shop_id'];
		$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
		$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
		$order_goods_row['order_goods_benefit']           = $order_goods_benefit;
		$order_goods_row['order_goods_time']              = get_date_time();

		$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

		$trade_title .= $data['goods_base']['goods_name'].',';

		//加入交易快照表
		$order_goods_snapshot_add_row = array();
		$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
		$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
		$order_goods_snapshot_add_row['shop_id'] 		=	$data['goods_base']['shop_id'];
		$order_goods_snapshot_add_row['common_id'] 	=	$data['goods_base']['common_id'];
		$order_goods_snapshot_add_row['goods_id'] 		=	$data['goods_base']['goods_id'];
		$order_goods_snapshot_add_row['goods_name'] 	=	$data['goods_base']['goods_name'];
		$order_goods_snapshot_add_row['goods_image'] 	=	$data['goods_base']['goods_image'];
		$order_goods_snapshot_add_row['goods_price'] 	=	$data['now_price'];
		$order_goods_snapshot_add_row['freight'] 		=	0;   //运费
		$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
		$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
		$order_goods_snapshot_add_row['snapshot_detail'] = $order_goods_benefit;

		$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);

		$flag  = $flag && $flag2;

		//删除商品库存
		$flag3 = $Goods_BaseModel->delStock($goods_id, $goods_num);
		$flag  = $flag && $flag3;

		if (isset($redemp_goods_rows))
		{
			foreach ($redemp_goods_rows as $k => $v)
			{
				$order_goods_row                                  = array();
				$order_goods_row['order_id']                      = $order_id;
				$order_goods_row['goods_id']                      = $v['goods_id'];
				$order_goods_row['common_id']                     = $v['common_id'];
				$order_goods_row['buyer_user_id']                 = $user_id;
				$order_goods_row['goods_name']                    = $v['goods_name'];
				$order_goods_row['goods_class_id']                = $v['cat_id'];
				$order_goods_row['goods_price']                   = $v['redemp_price'];
				$order_goods_row['order_goods_num']               = 1;
				$order_goods_row['goods_image']                   = $v['goods_image'];
				$order_goods_row['order_goods_amount']            = $v['redemp_price'];
				$order_goods_row['order_goods_discount_fee']      = ($v['redemp_price'] * (100 - $user_rate)) / 100;        //优惠价格
				$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
				$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
				$order_goods_row['order_goods_commission']        = $v['commission'];  //商品佣金
				$order_goods_row['shop_id']                       = $data['goods_base']['shop_id'];
				$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
				$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
				$order_goods_row['order_goods_benefit']           = '加价购商品';
				$order_goods_row['order_goods_time']              = get_date_time();

				$trade_title .= $v['goods_name'].',';

				$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

				//加入交易快照表(加价购商品)
				$order_goods_snapshot_add_row = array();
				$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
				$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
				$order_goods_snapshot_add_row['shop_id'] 		=	$v['shop_id'];
				$order_goods_snapshot_add_row['common_id'] 	=	$v['common_id'];
				$order_goods_snapshot_add_row['goods_id'] 		=	$v['goods_id'];
				$order_goods_snapshot_add_row['goods_name'] 	=	$v['goods_name'];
				$order_goods_snapshot_add_row['goods_image'] 	=	$v['goods_image'];
				$order_goods_snapshot_add_row['goods_price'] 	=	$v['redemp_price'];
				$order_goods_snapshot_add_row['freight'] 		=	0;   //运费
				$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
				$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
				$order_goods_snapshot_add_row['snapshot_detail'] = '加价购商品';

				$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);

				/*fb("====order_goods====");
                fb($flag2);*/
				$flag = $flag && $flag2;

				//删除商品库存
				$flag3 = $Goods_BaseModel->delStock($v['goods_id'], 1);
				/*	fb("====flag3===");
                    fb($flag3);*/
				$flag = $flag && $flag3;

			}
		}

		//店铺满赠商品
		if ($data['mansong_info'] && $data['mansong_info']['gift_goods_id'])
		{
			$order_goods_row                                  = array();
			$order_goods_row['order_id']                      = $order_id;
			$order_goods_row['goods_id']                      = $data['mansong_info']['gift_goods_id'];
			$order_goods_row['common_id']                     = $data['mansong_info']['common_id'];
			$order_goods_row['buyer_user_id']                 = $user_id;
			$order_goods_row['goods_name']                    = $data['mansong_info']['goods_name'];
			$order_goods_row['goods_class_id']                = 0;
			$order_goods_row['goods_price']                   = 0;
			$order_goods_row['order_goods_num']               = 1;
			$order_goods_row['goods_image']                   = $data['mansong_info']['goods_image'];
			$order_goods_row['order_goods_amount']            = 0;
			$order_goods_row['order_goods_discount_fee']      = 0;        //优惠价格
			$order_goods_row['order_goods_adjust_fee']        = 0;    //手工调整金额
			$order_goods_row['order_goods_point_fee']         = 0;    //积分费用
			$order_goods_row['order_goods_commission']        = 0;    //商品佣金
			$order_goods_row['shop_id']                       = $data['goods_base']['shop_id'];
			$order_goods_row['order_goods_status']            = Order_StateModel::ORDER_WAIT_PAY;
			$order_goods_row['order_goods_evaluation_status'] = 0;  //0未评价 1已评价
			$order_goods_row['order_goods_benefit']           = '店铺满赠商品';
			$order_goods_row['order_goods_time']              = get_date_time();

			$trade_title .= $data['mansong_info']['goods_name'].',';

			$flag2 = $Order_GoodsModel->addGoods($order_goods_row);

			//加入交易快照表(满赠商品)
			$order_goods_snapshot_add_row = array();
			$order_goods_snapshot_add_row['order_id'] 		=	$order_id;
			$order_goods_snapshot_add_row['user_id'] 		=	$user_id;
			$order_goods_snapshot_add_row['shop_id'] 		=	$data['shop_base']['shop_id'];
			$order_goods_snapshot_add_row['common_id'] 	=	$data['mansong_info']['common_id'];
			$order_goods_snapshot_add_row['goods_id'] 		=	$data['mansong_info']['gift_goods_id'];
			$order_goods_snapshot_add_row['goods_name'] 	=	$data['mansong_info']['goods_name'];
			$order_goods_snapshot_add_row['goods_image'] 	=	$data['mansong_info']['goods_image'];
			$order_goods_snapshot_add_row['goods_price'] 	=	0;
			$order_goods_snapshot_add_row['freight'] 		=	0;   //运费
			$order_goods_snapshot_add_row['snapshot_create_time'] =	get_date_time();
			$order_goods_snapshot_add_row['snapshot_uptime'] =		get_date_time();
			$order_goods_snapshot_add_row['snapshot_detail'] = '店铺满赠商品';

			$Order_GoodsSnapshot->addSnapshot($order_goods_snapshot_add_row);

			/*fb("====order_goods====");
            fb($flag2);*/
			$flag = $flag && $flag2;

			//删除商品库存
			$flag3 = $Goods_BaseModel->delStock($data['mansong_info']['gift_goods_id'], 1);
			/*	fb("====flag3===");
                fb($flag3);*/
			$flag = $flag && $flag3;

		}

		if ($flag && $this->tradeOrderModel->sql->commitDb())
		{
			//支付中心生成订单
			$key      = Yf_Registry::get('shop_api_key');
			$url         = Yf_Registry::get('paycenter_api_url');
			$shop_app_id = Yf_Registry::get('shop_app_id');
			$formvars = array();

			$formvars['app_id']					= $shop_app_id;
			$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');
			$formvars['consume_trade_id']     = $order_row['order_id'];
			$formvars['order_id']             = $order_row['order_id'];
			$formvars['buy_id']               = Perm::$userId;
			$formvars['buyer_name'] 		   = Perm::$row['user_account'];
			$formvars['seller_id']            = $order_row['seller_user_id'];
			$formvars['seller_name']		   = $order_row['seller_user_name'];
			$formvars['order_state_id']       = $order_row['order_status'];
			$formvars['order_payment_amount'] = $order_row['order_payment_amount'];
			$formvars['trade_remark']         = $order_row['order_message'];
			$formvars['trade_create_time']    = $order_row['order_create_time'];
			$formvars['trade_title']			= $trade_title;		//商品名称 - 标题

			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addConsumeTrade&typ=json',$url), $formvars);

			fb($rs);

			if ($rs['status'] == 200)
			{
				$Order_BaseModel->editBase($order_row['order_id'],array('payment_number' => $rs['data']['union_order']));

				//生成合并支付订单
				$key      = Yf_Registry::get('shop_api_key');
				$url         = Yf_Registry::get('paycenter_api_url');
				$shop_app_id = Yf_Registry::get('shop_app_id');
				$formvars = array();

				$formvars['inorder']    = $order_id . ',';
				$formvars['uprice']     = $order_row['order_payment_amount'];
				$formvars['buyer']      = Perm::$userId;
				$formvars['trade_title'] = $trade_title;
				$formvars['buyer_name'] = Perm::$row['user_account'];
				$formvars['app_id']     = $shop_app_id;
				$formvars['from_app_id'] = Yf_Registry::get('shop_app_id');

				fb($formvars);

				$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=addUnionOrder&typ=json', $url), $formvars);

				fb($rs);

				if ($rs['status'] == 200)
				{
					$uorder = $rs['data']['uorder'];
				}
				else
				{
					$uorder = '';
				}


			}
			$status = 200;
			$msg    = _('success');
			$data   = $rs['data'];
		}
		else
		{
			$this->tradeOrderModel->sql->rollBackDb();
			$m      = $this->tradeOrderModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;
			$data   = array();
		}
		//$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}


	//修改订单状态(支付成功)
	public function editOrderStatusFinish()
	{
		$order_id = request_string('order_id');

		$Order_GoodsModel = new Order_GoodsModel();
		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();


		$order_base               = $this->tradeOrderModel->getOne($order_id);
		$edit_row                 = array('order_status' => Order_StateModel::ORDER_PAYED);
		$edit_row['payment_time'] = get_date_time();
		//修改订单状态

		$this->tradeOrderModel->editBase($order_id, $edit_row);

		//修改订单商品状态
		$edit_goods_row = array('order_goods_status' => Order_StateModel::ORDER_PAYED);
		$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));
		$flag           = $Order_GoodsModel->editGoods($order_goods_id, $edit_goods_row);


		if ($flag && $this->tradeOrderModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');

			$message           = new MessageModel();
			$code              = 'place_your_order';
			$message_user_id   = $order_base['seller_user_id'];
			$message_user_name = $order_base['seller_user_name'];
			$shop_name         = $order_base['shop_name'];
			$message->sendMessage($code, $message_user_id, $message_user_name, $order_id, $shop_name, 1, 1);
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

	//修改订单状态(数组支付成功)
	public function editOrderRowSatus()
	{
		$order_id = request_row('order_id');

		$order_id = array_filter($order_id);
		
		//开启事物
		$this->tradeOrderModel->sql->startTransactionDb();

		foreach ($order_id as $key => $val)
		{
			$flag = $this->tradeOrderModel->editOrderStatusAferPay($val);

			$order_base = $this->tradeOrderModel->getOne($val);

			if ($flag)
			{
				$message           = new MessageModel();
				$code              = 'place_your_order';
				$message_user_id   = $order_base['seller_user_id'];
				$message_user_name = $order_base['seller_user_name'];
				$shop_name         = $order_base['shop_name'];
				$message->sendMessage($code, $message_user_id, $message_user_name, $val, $shop_name, 1, 1);
			}
		}

		if ($flag && $this->tradeOrderModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
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


	//自动收货 - 定时计划任务
	public function confirmOrderAuto()
	{
		$Order_BaseModel  = new Order_BaseModel();
		$Order_GoodsModel = new Order_GoodsModel();

		//开启事物
		$Order_BaseModel->sql->startTransactionDb();

		//查找出所有待收货状态的商品
		$cond_row                           = array();
		$cond_row['order_status']           = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;
		$cond_row['order_receiver_date:<='] = get_date_time();
		$order_list                         = $Order_BaseModel->getKeyByWhere($cond_row);
		fb($order_list);

		if($order_list)
		{
			foreach ($order_list as $key => $val)
			{

				$order_id = $val;

				$order_base           = $Order_BaseModel->getOne($order_id);
				$order_payment_amount = $order_base['order_payment_amount'];

				$condition['order_status'] = Order_StateModel::ORDER_FINISH;

				$condition['order_finished_time'] = get_date_time();

				$flag = $Order_BaseModel->editBase($order_id, $condition);

				//修改订单商品表中的订单状态
				$edit_row['order_goods_status'] = Order_StateModel::ORDER_FINISH;

				$order_goods_id = $Order_GoodsModel->getKeyByWhere(array('order_id' => $order_id));

				$Order_GoodsModel->editGoods($order_goods_id, $edit_row);


				/*
				*  经验与成长值
				*/
				$user_points        = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
				$user_points_amount = Web_ConfigModel::value("points_order");//订单每多少获取多少积分

				if ($order_payment_amount / $user_points < $user_points_amount)
				{
					$user_points = floor($order_payment_amount / $user_points);
				}
				else
				{
					$user_points = $user_points_amount;
				}

				$user_grade        = Web_ConfigModel::value("grade_recharge");//订单每多少获取多少积分
				$user_grade_amount = Web_ConfigModel::value("grade_order");//订单每多少获取多少成长值

				if ($order_payment_amount / $user_grade > $user_grade_amount)
				{
					$user_grade = floor($order_payment_amount / $user_grade);
				}
				else
				{
					$user_grade = $user_grade_amount;
				}

				$User_ResourceModel = new User_ResourceModel();
				//获取积分经验值
				$ce = $User_ResourceModel->getResource($order_base['buyer_user_id']);

				$resource_row['user_points'] = $ce[$order_base['buyer_user_id']]['user_points'] * 1 + $user_points * 1;
				$resource_row['user_growth'] = $ce[$order_base['buyer_user_id']]['user_growth'] * 1 + $user_grade * 1;

				$res_flag = $User_ResourceModel->editResource($order_base['buyer_user_id'], $resource_row);

				$User_GradeModel = new User_GradeModel;
				//升级判断
				$res_flag = $User_GradeModel->upGrade($order_base['buyer_user_id'], $resource_row['user_growth']);
				//积分
				$points_row['user_id']           = $order_base['buyer_user_id'];
				$points_row['user_name']         = $order_base['buyer_user_name'];
				$points_row['class_id']          = Points_LogModel::ONBUY;
				$points_row['points_log_points'] = $user_points;
				$points_row['points_log_time']   = get_date_time();
				$points_row['points_log_desc']   = '确认收货';
				$points_row['points_log_flag']   = 'confirmorder';

				$Points_LogModel = new Points_LogModel();

				$Points_LogModel->addLog($points_row);

				//成长值
				$grade_row['user_id']         = $order_base['buyer_user_id'];
				$grade_row['user_name']       = $order_base['buyer_user_name'];
				$grade_row['class_id']        = Grade_LogModel::ONBUY;
				$grade_row['grade_log_grade'] = $user_grade;
				$grade_row['grade_log_time']  = get_date_time();
				$grade_row['grade_log_desc']  = '确认收货';
				$grade_row['grade_log_flag']  = 'confirmorder';

				$Grade_LogModel = new Grade_LogModel;
				$Grade_LogModel->addLog($grade_row);
			}
		}
		else
		{
			$flag = true;
		}


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
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//如果为虚拟订单读取实体店铺的地址
	public function getEntityList()
	{
		$shop_id 			   = request_int('shop_id');

		$data 				   = array();
		$addr_list 			   = array();
		$Shop_EntityModel      = new Shop_EntityModel();

		$shop_entity_list = $Shop_EntityModel->getByWhere( array('shop_id' => $shop_id) );

		if ( !empty($shop_entity_list) )
		{

			foreach ( $shop_entity_list as $entity_id => $entity_val )
			{
				$addr_list[$entity_id]['name_info'] 	= $entity_val['entity_name'];
				$addr_list[$entity_id]['address_info'] 	= $entity_val['entity_xxaddr'];
				$addr_list[$entity_id]['phone_info'] 	= $entity_val['entity_tel'];
			}

			$data['addr_list'] = $addr_list;
		}
		else
		{
			$data['addr_list'] = $addr_list;
		}

		$this->data->addBody(-140, $data);
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

			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Pay_Pay&met=cancelOrder&typ=json', $url), $formvars);



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
}

?>