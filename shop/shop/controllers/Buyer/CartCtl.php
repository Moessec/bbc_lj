<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Zhuyt
 */
class Buyer_CartCtl extends Controller
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

		$this->title       = '';
		$this->description = '';
		$this->keyword     = '';
		$this->web         = $this->webConfig();
		$this->bnav		 = 		$this->bnavIndex();

		$this->cartModel = new CartModel();
	}

	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 首页
	 *
	 * @author Zhuyt
	 */
	public function cart()
	{
		$data = $this->getCart();

		$this->data->addBody(-140, $data);

		if ($this->typ == 'json')
		{
			$new_data = array();
			$sum = 0;

			$count = $data['count'];
			unset($data['count']);
			$new_data['count'] = $count;

			$cart_list = array_values($data);
			$new_data['cart_list'] = $cart_list;

			if ( !empty($cart_list) )
			{
				foreach ($cart_list as $key => $val)
				{
					foreach ($val['goods'] as $k => $v)
					{
						$sum += $v['goods_num'] * $v['now_price'];
					}
				}
			}

			$new_data['sum'] = $sum;
			$this->data->addBody(-140, $new_data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 获取用户的收货地址
	 *
	 * @author Zhuyt
	 */
	public function resetAddress()
	{
		$user_id         = Perm::$row['user_id'];
		$user_address_id = request_int('id');

		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

		if ($user_address_id)
		{
			$cond_row          = array();
			$cond_row          = array(
				'user_id' => $user_id,
				'user_address_id' => $user_address_id
			);
			$User_AddressModel = new User_AddressModel();
			$data              = $User_AddressModel->getOneByWhere($cond_row);
		}
		/*fb($data);
		fb("用户地址");*/

		include $this->view->getView();
	}

	/**
	 * 获取用户的发票信息
	 *
	 * @author Zhuyt
	 */
	public function piao()
	{
		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

		//获取用户的发票信息
		$user_id      = Perm::$row['user_id'];
		$InvoiceModel = new InvoiceModel();
		$data         = $InvoiceModel->getInvoiceByUser($user_id);

		if($this->typ == 'json')
		{
			if($data['normal'])
			{
				$da['normal'] = $data['normal'];
			}
			else
			{
				$da['normal'] = array();
			}

			if($data['electron'])
			{
				$da['electron'] = $data['electron'];
			}
			else
			{
				$da['electron'] = array();
			}

			if($data['addtax'])
			{
				$da['addtax'] = $data['addtax'];
			}
			else
			{
				$da['addtax'] = array();
			}
			$this->data->addBody(-140, $da);
		}
		else
		{
			include $this->view->getView();
		}


	}

	/**
	 * 确认订单信息后生成订单
	 *
	 * @author Zhuyt
	 */
	public function confirm()
	{
		$user_id = Perm::$row['user_id'];
		$address_id = request_int('address_id');


		//获取用户的折扣信息
		$User_InfoMode = new User_InfoModel();
		$user_info     = $User_InfoMode->getOne($user_id);

		$User_GradeMode = new User_GradeModel();
		$user_grade     = $User_GradeMode->getOne($user_info['user_grade']);
		if (!$user_grade)
		{
			$user_rate = 0;
		}
		else
		{
			$user_rate = $user_grade['user_grade_rate'];
		}
		$data['user_rate'] = $user_rate;

		$cart_id = request_row('product_id');

		$cord_row  = array();
		$order_row = array();

		$cond_row['cart_id:IN'] = $cart_id;
		$cond_row['user_id']    = $user_id;
		//购物车中的商品信息
		$data['glist'] = $this->cartModel->getCardList($cond_row, $order_row);
		
		//获取收货地址
		$User_AddressModel = new User_AddressModel();

		$cond_address    = array('user_id' => $user_id);
		$address         = array_values($User_AddressModel->getAddressList($cond_address, array('user_address_default' => 'DESC')));
		$data['address'] = $address;

		fb($data['address']);
		fb("用户地址");

		if($address_id)
		{
			//如果传递了address_id,根据address_id获取运费信息
			$address_row = $User_AddressModel->getOne($address_id);

			$Transport_TypeModel = new Transport_TypeModel();
			if ($address_row)
			{
				$city_id             = $address_row['user_address_city_id'];

				$transport_cost      = $Transport_TypeModel->countTransportCost($city_id, $cart_id);

				$data['cost'] = $transport_cost;
			}
			else
			{
				$transport_cost      = $Transport_TypeModel->countTransportCost(0, $cart_id);
				$data['cost'] = $transport_cost;
			}

		}
		else
		{
			//根据默认收货地址计算运费
			$cond_address['user_address_default'] = User_AddressModel::DEFAULT_ADDRESS;
			$default_address_row                  = $User_AddressModel->getOneByWhere($cond_address);

			fb($default_address_row);

			$Transport_TypeModel = new Transport_TypeModel();
			if ($default_address_row)
			{
				//$default_address = $default_address_row['user_address_area'];
				$city_id             = $default_address_row['user_address_city_id'];

				$transport_cost      = $Transport_TypeModel->countTransportCost($city_id, $cart_id);

				$data['cost'] = $transport_cost;
			}
			else
			{
				$transport_cost      = $Transport_TypeModel->countTransportCost(0, $cart_id);
				$data['cost'] = $transport_cost;
			}
		}

		
		fb($data);
		fb("购物车列表confirm");
		if ( $this->typ == 'json' )
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	//根据收货地址与商品id计算出物流运费
	public function getTransportCost()
	{
		$transportTypeModel = new Transport_TypeModel();

		$city = request_string('city');

		$cart_id = request_string('cart_id');

		$data = $transportTypeModel->countTransportCost($city, $cart_id);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 确认订单信息后生成订单(虚拟商品)
	 *
	 * @author Zhuyt
	 */
	public function confirmVirtual()
	{
		$nums     = request_int("nums");
		$goods_id = request_int('goods_id');

		$user_id = Perm::$userId;
		//获取用户的折扣信息
		$User_InfoMidel = new User_InfoModel();
		$user_info      = $User_InfoMidel->getOne($user_id);

		$User_GradeModel = new User_GradeModel();
		$user_grade      = $User_GradeModel->getOne($user_info['user_grade']);
		$user_rate       = $user_grade['user_grade_rate'];

		//获取虚拟商品的信息
		$data = $this->cartModel->getVirtualCart($goods_id, $nums);

		fb($data);
		fb('虚拟确认订单');
		if ( $this->typ == 'json' )
		{
			$data['user_rate'] = $user_rate;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 获取购物车列表
	 *
	 * @author Zhuyt
	 */
	public function getCart()
	{
		$user_id = Perm::$row['user_id'];

		$cord_row  = array();
		$order_row = array();

		$cond_row = array('user_id' => $user_id);
		$data     = $this->cartModel->getCardList($cond_row, $order_row);

		if ($data)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}


		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}

	/**
	 * 修改购物车数量
	 *
	 * @author Zhuyt
	 */
	public function editCartNum()
	{
		$cart_id = request_int('cart_id');
		$num     = request_int('num');
		$user_id = Perm::$userId;

		$edit_row = array('goods_num' => $num);


		//获取商品信息
		$cart_base = $this->cartModel->getOne($cart_id);
		$goods_id = $cart_base['goods_id'];

		$Goods_BaseModel = new Goods_BaseModel();
		$goods_base = $Goods_BaseModel->getOne($goods_id);


		//查询该用户是否已购买过该商品
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_cond['common_id']             = $goods_base['common_id'];
		$order_goods_cond['buyer_user_id']         = $user_id;
		$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
		$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

		$order_goods_count         = count($order_list);

		//如果有限购数量就计算还剩多少可购买的商品数量
		if($goods_base['goods_max_sale'])
		{
			$limit_num = $goods_base['goods_max_sale'] - $order_goods_count;

			$limit_num = $limit_num < 0 ? 0:$limit_num;

			if($limit_num < $num)
			{
				$num = $limit_num;
			}
		}



		//判断加入购物车的数量和库存
		if($num <= $goods_base['goods_stock'])
		{
			$flag = $this->cartModel->editCartNum($cart_id, $edit_row);
		}
		else
		{
			$flag = false;
		}

		$data = array();
		if ($flag)
		{
			//获取此商品的总价
			$data['price'] = $this->cartModel->getCartGoodPrice($cart_id);

			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除购物车中的商品
	 *
	 * @author Zhuyt
	 */
	public function delCartByCid()
	{
		$cart_id = request_string('id');

		$flag = $this->cartModel->removeCart($cart_id);
		//$flag = true;
		if ($flag)
		{

			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}
		$data = array($cart_id);
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 立即购买虚拟产品
	 *
	 * @author Zhuyt
	 */
	public function buyVirtual()
	{
		$user_id   = Perm::$row['user_id'];
		$goods_id  = request_int('goods_id');
		$goods_num = request_int('goods_num');

		//获取虚拟商品的信息
		$Goods_BaseModel = new Goods_BaseModel();
		$data            = $Goods_BaseModel->getGoodsInfo($goods_id);

		fb($data);
		//计算商品价格
		if (isset($data['goods_base']['promotion_price']) && !empty($data['goods_base']['promotion_price']) && $data['goods_base']['promotion_price'] < $data['goods_base']['goods_price'])
		{
			$data['goods_base']['old_price']  = $data['goods_base']['goods_price'];
			$data['goods_base']['now_price']  = $data['goods_base']['promotion_price'];
			$data['goods_base']['down_price'] = $data['goods_base']['down_price'];
		}
		else
		{
			$data['goods_base']['old_price']  = 0;
			$data['goods_base']['now_price']  = $data['goods_base']['goods_price'];
			$data['goods_base']['down_price'] = 0;
		}

		$data['goods_base']['cart_num'] = $goods_num;
		$data['goods_base']['sumprice'] = $goods_num * $data['goods_base']['now_price'];

		$Order_GoodsModel = new Order_GoodsModel();
		if ($user_id)
		{
			//团购商品是否已经开始
			//查询该用户是否已购买过该商品
			$order_goods_cond['common_id']             = $data['goods_base']['common_id'];
			$order_goods_cond['buyer_user_id']         = $user_id;
			$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
			$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

			$order_goods_count         = count($order_list);
			$data['order_goods_count'] = $order_goods_count;

		}

		//计算商品购买数量
		//计算限购数量
		if (isset($data['goods_base']['upper_limit']))
		{
			if ($data['goods_base']['upper_limit'] && $data['common_base']['common_limit'])
			{
				if ($data['goods_base']['upper_limit'] >= $data['common_base']['common_limit'])
				{
					$data['buy_limit'] = $data['common_base']['common_limit'];
				}
				else
				{
					$data['buy_limit'] = $data['goods_base']['upper_limit'];
				}
			}
			elseif ($data['goods_base']['upper_limit'] && !$data['common_base']['common_limit'])
			{
				$data['buy_limit'] = $data['goods_base']['upper_limit'];
			}
			elseif (!$data['goods_base']['upper_limit'] && $data['common_base']['common_limit'])
			{
				$data['buy_limit'] = $data['common_base']['common_limit'];
			}
			else
			{
				$data['buy_limit'] = 0;
			}
		}
		else
		{
			$data['buy_limit'] = $data['common_base']['common_limit'];
		}

		//有限购数量且仍可以购买，计算还可购买的数量
		if ($data['buy_limit'])
		{
			$data['buy_residue'] = $data['buy_limit'] - $order_goods_count;
		}

		fb($data);
		fb("虚拟购物车");

		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}else
		{
			include $this->view->getView();
		}

	}

	/**
	 * 加入购物车
	 *
	 * @author Zhuyt
	 */
	public function add()
	{
		include $this->view->getView();
	}

	public function addCartRow()
	{
		$cart_list = request_string('cartlist');
		$user_id = request_int('u');

		$cart_list = explode('|',$cart_list);


		foreach($cart_list as $key => $val)
		{
			$val = explode(',',$val);
			if(count($val) > 1)
			{
				//将商品id与数量添加到购物车表中
				$this->cartModel->updateCart($user_id,$val[0],$val[1]);
			}
		}

		$this->data->addBody(-140, array());
	}

	public function addCart()
	{
		$user_id   = Perm::$row['user_id'];
		$goods_id  = request_int('goods_id');
		$goods_num = request_int('goods_num');

		if ($goods_id)
		{
			//查找商品的shop_id
			$Goods_BaseModel = new Goods_BaseModel();
			$goods_base      = $Goods_BaseModel->getOne($goods_id);

			//判断该件商品是否为虚拟商品，若为虚拟商品则加入购物车失败
			$Goods_CommonModel = new Goods_CommonModel();
			$common_base = $Goods_CommonModel->getOne($goods_base['common_id']);
			if($common_base['common_is_virtual'] != $Goods_CommonModel::GOODS_VIRTUAL)
			{
				$shop_id = $goods_base['shop_id'];

				//判断购物车中是否存在该商品
				$cart_cond             = array();
				$cart_cond['user_id']  = $user_id;
				$cart_cond['shop_id']  = $shop_id;
				$cart_cond['goods_id'] = $goods_id;
				$cart_row              = current($this->cartModel->getByWhere($cart_cond));
				$msg                   = '';


				//查询该用户是否已购买过该商品
				$Order_GoodsModel = new Order_GoodsModel();
				$order_goods_cond['common_id']             = $goods_base['common_id'];
				$order_goods_cond['buyer_user_id']         = $user_id;
				$order_goods_cond['order_goods_status:!='] = Order_StateModel::ORDER_REFUND_FINISH;
				$order_list                                = $Order_GoodsModel->getByWhere($order_goods_cond);

				$order_goods_count         = count($order_list);

				//如果有限购数量就计算还剩多少可购买的商品数量

				$limit_num = $goods_base['goods_max_sale'];
				if($goods_base['goods_max_sale'])
				{
					$limit_num = $goods_base['goods_max_sale'] - $order_goods_count;
					$limit_num = $limit_num < 0 ? 0:$limit_num;
				}

				if ($cart_row)
				{
					//判断商品的个人购买数
					//if (($cart_row['goods_num'] >= $goods_base['goods_max_sale'] || $cart_row['goods_num'] + $goods_num > $goods_base['goods_max_sale']) && $goods_base['goods_max_sale'] != 0)
					if (($cart_row['goods_num'] >= $limit_num || $cart_row['goods_num'] + $goods_num > $limit_num) && $goods_base['goods_max_sale'] != 0)
					{
						$flag = false;
						$msg  = sprintf(_('每人最多可购买%s件该商品'), $goods_base['goods_max_sale']);
					}
					else
					{
						$edit_row = array('goods_num' => $goods_num);
						$flag     = $this->cartModel->editCart($cart_row['cart_id'], $edit_row, true);
					}
					$cart_id = $cart_row['cart_id'];
				}
				else
				{
					$add_row              = array();
					$add_row['user_id']   = $user_id;
					$add_row['shop_id']   = $shop_id;
					$add_row['goods_id']  = $goods_id;
					$add_row['goods_num'] = $goods_num;

					$flag    = $this->cartModel->addCart($add_row, true);
					$cart_id = $flag;
				}
			}
			else
			{
				$flag = false;
				$cart_id = $flag;
			}


		}
		else
		{
			$flag = false;
		}


		if ($flag)
		{
			$status = 200;
			$msg    = $msg ? $msg : _('success');
		}
		else
		{
			$status = 250;
			$msg    = $msg ? $msg : _('failure');
		}
		$data = array(
			'flag' => $flag,
			'msg' => $msg,
			'cart_id' => $cart_id
		);
		$this->data->addBody(-140, $data, $msg, $status);
		return $data;
	}

	//获取购物车中的数量
	public function getCartGoodsNum()
	{
		$user_id = Perm::$row['user_id'];

		$cord_row  = array();
		$order_row = array();

		$cond_row = array('user_id' => $user_id);

		$CartModel = new CartModel();

		$count  = $CartModel->getCartGoodsNum($cond_row, $order_row);
		$data[] = $count;
		$data['cart_count'] = $count;

		$this->data->addBody(-140, $data);
	}


}

?>