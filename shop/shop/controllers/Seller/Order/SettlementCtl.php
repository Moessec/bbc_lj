<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Order_SettlementCtl extends Seller_Controller
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
	 * 首页
	 *
	 * @access public
	 */
	public function addBill()
	{
		$Order_SettlementModel = new Order_SettlementModel();
		//开启事物
		$Order_SettlementModel->sql->startTransactionDb();
		//调用封装好的结算功能
		//1.实物订单结算
		$Order_SettlementModel->settleNormalOrder();

		//2.虚拟订单结算
		$Order_SettlementModel->settleVirtualOrder();

		if ($Order_SettlementModel->sql->commitDb())
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$Order_SettlementModel->sql->rollBackDb();
			$m      = $Order_SettlementModel->msg->getMessages();
			$msg    = $m ? $m[0] : _('failure');
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);


	}

	//获取虚拟结算
	public function virtual()
	{
		$op   = request_string('op');
		$id   = request_int('id');
		$type = request_string('type', 'active');
		
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
		
		
		if ($op == 'show')
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$data = $Order_SettlementModel->getOneSettle($id);

			//订单列表
			if ($type == 'active')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];

				$Order_BaseModel = new Order_BaseModel;
				$list            = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);
			}
			//退款订单
			if ($type == 'refund')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];
				$order_cond_row['order_refund_status:!='] = Order_BaseModel::REFUND_NO;
				$Order_BaseModel                          = new Order_BaseModel;
				$list                                     = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);

			}
			//店铺费用
			if ($type == 'cost')
			{
				$shop_cost_row                 = array();
				$shop_cost_row['cost_time:>='] = $data['os_start_date'];
				$shop_cost_row['cost_time:<='] = $data['os_end_date'];
				$shop_cost_row['cost_status']  = Shop_CostModel::SETTLED;

				$Shop_CostModel = new Shop_CostModel();
				$list           = $Shop_CostModel->listByWhere($shop_cost_row, array(), $page, $rows);
			}
			
			$this->view->setMet('showVirtual');
		}
		else
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$shop_id = Perm::$shopId;
			//$shop_id   = 1;
			$cond_row  = array();
			$order_row = array();

			$cond_row = array(
				'shop_id' => $shop_id,
				'os_order_type' => Order_SettlementModel::SETTLEMENT_VIRTUAL_ORDER
			);

			$list = $Order_SettlementModel->getSettlementList($cond_row, $order_row, $page, $rows);
		}
		
		
		$Yf_Page->totalRows = $list['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		include $this->view->getView();
	}

	//获取虚拟结算列表
	public function getVirtualList()
	{
		$Order_SettlementModel = new Order_SettlementModel();

		$shop_id = Perm::$shopId;
		//$shop_id   = 1;
		$cond_row  = array();
		$order_row = array();

		$cond_row = array(
			'shop_id' => $shop_id,
			'os_order_type' => Order_SettlementModel::SETTLEMENT_VIRTUAL_ORDER
		);

		$data = $Order_SettlementModel->getSettlementList($cond_row, $order_row);

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

	//获取实物结算列表
	public function getNormalList()
	{
		$Order_SettlementModel = new Order_SettlementModel();

		$shop_id = Perm::$shopId;
		//$shop_id   = 1;
		$cond_row  = array();
		$order_row = array();

		$cond_row = array(
			'shop_id' => $shop_id,
			'os_order_type' => Order_SettlementModel::SETTLEMENT_NORMAL_ORDER
		);

		$data = $Order_SettlementModel->getSettlementList($cond_row, $order_row);

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


	//获取实物结算
	public function normal()
	{
		$op   = request_string('op');
		$id   = request_int('id');
		$type = request_string('type', 'active');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);


		if ($op == 'show')
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$data = $Order_SettlementModel->getOneSettle($id);

			//订单列表
			if ($type == 'active')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];

				$Order_BaseModel = new Order_BaseModel;
				$list            = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);
			}
			//退款订单
			if ($type == 'refund')
			{
				//查找结算订单表中的订单列表
				$order_cond_row                           = array();
				$order_cond_row['order_finished_time:>='] = $data['os_start_date'];
				$order_cond_row['order_finished_time:<='] = $data['os_end_date'];
				$order_cond_row['order_refund_status:!='] = Order_BaseModel::REFUND_NO;
				$Order_BaseModel                          = new Order_BaseModel;
				$list                                     = $Order_BaseModel->listByWhere($order_cond_row, array(), $page, $rows);

			}
			//店铺费用
			if ($type == 'cost')
			{
				$shop_cost_row                 = array();
				$shop_cost_row['cost_time:>='] = $data['os_start_date'];
				$shop_cost_row['cost_time:<='] = $data['os_end_date'];
				$shop_cost_row['cost_status']  = Shop_CostModel::SETTLED;

				$Shop_CostModel = new Shop_CostModel();
				$list           = $Shop_CostModel->listByWhere($shop_cost_row, array(), $page, $rows);
			}

			$this->view->setMet('showNormal');
		}
		else
		{
			$Order_SettlementModel = new Order_SettlementModel();

			$shop_id = Perm::$shopId;
			//$shop_id   = 1;
			$cond_row  = array();
			$order_row = array();

			$cond_row = array(
				'shop_id' => $shop_id,
				'os_order_type' => Order_SettlementModel::SETTLEMENT_NORMAL_ORDER
			);

			$list = $Order_SettlementModel->getSettlementList($cond_row, $order_row, $page, $rows);
		}


		$Yf_Page->totalRows = $list['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		include $this->view->getView();
	}

	//结算无误，确认结算
	public function confirmSettlement()
	{
		$id                    = request_int('id');
		$Order_SettlementModel = new Order_SettlementModel();
		$edit['os_state']      = $Order_SettlementModel::SETTLEMENT_SELLER_COMFIRMED;
		$flag                  = $Order_SettlementModel->editSettlement($id, $edit);
		if ($flag !== false)
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

?>