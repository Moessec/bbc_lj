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
class Seller_TransportCtl extends Seller_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->transportTypeModel = new Transport_TypeModel();

		$this->transportItemModel = new Transport_ItemModel();
	}


	//显示物流工具列表页
	public function transport()
	{
		$act     = request_string('act');
		$shop_id = Perm::$shopId;

		//获取该店铺已经设置的售卖区域
		$shop_transport = $this->transportTypeModel->getShopDistrict($shop_id);


		//获取地区数组
		$Base_DistrictModel = new Base_DistrictModel();
		$province           = $Base_DistrictModel->getAllDistrict();

		$type_city = array();
		if ($act == 'edit')
		{
			$this->view->setMet('message');

			$transport_type_id = request_int('transport_type_id');

			$cond_row['transport_type_id'] = $transport_type_id;

			$data = $this->transportTypeModel->getTransportInfo($transport_type_id);

			//获取该模板的售卖区域
			$type_city = explode(',', $data['transport_item']['transport_item_city']);

			//在店铺的已售区域中去除该模板的售卖区域
			$shop_transport = array_diff($shop_transport, $type_city);


			fb($shop_transport);
			fb("该模板的售卖区域");
		}
		elseif ($act == 'add')
		{
			$this->view->setMet('message');
			$data = array();
		}
		else
		{
			$data = $this->getTransportList();
		}


		$this->data->addBody(-140, $data);
		include $this->view->getView();
	}

	/**
	 * 获取评价列表
	 *
	 * @access public
	 */
	public function getTransportList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$shop_id = Perm::$shopId;

		$cond_row  = array();
		$order_row = array();


		$cond_row['shop_id'] = $shop_id;


		$data = $this->transportTypeModel->getTransportList($cond_row, $order_row, $page, $rows);


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

	public function addTransport()
	{
		//开启事物
		$this->transportTypeModel->sql->startTransactionDb();

		$flag         = false;
		$add_type_row = array(
			'transport_type_name' => request_string('type_name'),
			'shop_id' => Perm::$shopId,
			'transport_type_time' => date('Y-m-d H:i:s'),
		);

		$add_id = $this->transportTypeModel->addType($add_type_row, true);

		$city_row = request_row('city');
		$city     = implode(',', $city_row);
		fb($city);
		if ($add_id)
		{
			$add_item_row = array(
				'transport_type_id' => $add_id,
				'transport_item_default_num' => request_int('default_num'),
				'transport_item_default_price' => request_float('default_price'),
				'transport_item_add_num' => request_int('add_num'),
				'transport_item_add_price' => request_float('add_price'),
				'transport_item_city' => $city,
			);

			$this->transportItemModel->addItem($add_item_row, true);
			$flag = true;
		}

		if ($flag && $this->transportTypeModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->transportTypeModel->sql->rollBackDb();
			$m      = $this->transportTypeModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function editTransport()
	{
		$transport_type_id = request_int('transport_type_id');
		$transport_item_id = request_int('transport_item_id');
		$default = request_string('default');
		$city_row          = request_row('city');
		$city              = implode(',', $city_row);

		$edit_type_row = array('transport_type_name' => request_string('type_name'),);

		//开启事物
		$this->transportTypeModel->sql->startTransactionDb();
		$this->transportTypeModel->editType($transport_type_id, $edit_type_row);

		if($default == 'default')
		{
			$city = 'default';
		}

		$edit_item_row = array(
			'transport_item_default_num' => request_float('default_num'),
			'transport_item_default_price' => request_int('default_price'),
			'transport_item_add_num' => request_float('add_num'),
			'transport_item_add_price' => request_int('add_price'),
			'transport_item_city' => $city,
		);
		fb($default);
		fb($transport_item_id);
		fb($edit_item_row);
		$flag          = $this->transportItemModel->editItem($transport_item_id, $edit_item_row);
		fb($flag);
		fb("修改");
		if ($flag && $this->transportTypeModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->transportTypeModel->sql->rollBackDb();
			$m      = $this->transportTypeModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function  delTransportRow()
	{
		$transport_type_id = request_row('id');

		fb($transport_type_id);
		//开启事物
		$this->transportTypeModel->sql->startTransactionDb();

		foreach ($transport_type_id as $key => $val)
		{
			$flag = $this->transportTypeModel->delType($val);
		}

		if ($flag && $this->transportTypeModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->transportTypeModel->sql->rollBackDb();
			$m      = $this->transportTypeModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function delTransport()
	{
		$transport_type_id = request_int('id');

		//开启事物
		$this->transportTypeModel->sql->startTransactionDb();

		$flag = $this->transportTypeModel->delType($transport_type_id);

		if ($flag && $this->transportTypeModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->transportTypeModel->sql->rollBackDb();
			$m      = $this->transportTypeModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//根据收货地址与商品id计算出物流运费
	public function getTransportCost()
	{
		$city = request_string('city');

		$cart_id = request_string('cart_id');

		$data = $this->transportTypeModel->countTransportCost($city, $cart_id);

		$this->data->addBody(-140, $data);
	}

	//发布商品选择售卖地区
	public function chooseTranDialog()
	{
		$transport_list = $this->getTransportList();
		$transport_list = $transport_list['items'];
		include $this->view->getView();
	}
}

?>