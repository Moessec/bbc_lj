<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Consume_RecordModel extends Consume_Record
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $consume_record_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getRecordList($user_id = null,$type = null,$status = null, $page=1, $rows=100, $sort='asc',$user_nickname = null,$trade_type_id=null)
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$consume_record_id_row = array();

		$Consume_TradeModel = new Consume_TradeModel();
              
		if($user_id)
		{
			$this->sql->setWhere('user_id',$user_id);
		}

		if($type)
		{
			$this->sql->setWhere('user_type',$type);
		}
                if($user_nickname)
		{
			$this->sql->setWhere('user_nickname',$user_nickname);
		}
                if($trade_type_id)
		{
			$this->sql->setWhere('trade_type_id',$trade_type_id);
		}
		if($status)
		{
			$order_row = array();
			$data = $Consume_TradeModel->getTradeByState($user_id,$status,$type) ;
			foreach($data as $key=>$val)
			{
				$order_row[] = $val['order_id'];
			}
			$this->sql->setWhere('order_id',$order_row,'IN');
		}
		$this->sql->setOrder('consume_record_id','desc');
		$consume_record_id_row = $this->selectKeyLimit();


		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($consume_record_id_row)
		{
			$this->sql->setOrder('consume_record_id','desc');
			$data_rows = $this->getRecord($consume_record_id_row);
		}

		$RecordStatusModel = new RecordStatusModel();
		$Trade_Type = new Trade_TypeModel();
		$Order_StateModel = new Order_StateModel();
		$Union_Order = new Union_OrderModel();
		foreach($data_rows as $key => $val)
		{
			//如果为购物交易明细，显示交易进度
			if($val['trade_type_id'] == Trade_TypeModel::SHOPPING)
			{
				$order_row = $Consume_TradeModel->getOne($val['order_id']);
				$data_rows[$key]['record_status_con'] = $Order_StateModel->orderState[$order_row['order_state_id']];

				//查找支付单号
				$uorder_row = $Union_Order->getByWhere(array('inorder' => $val['order_id']));
				fb($uorder_row);
				$uorder = current($uorder_row);
				$data_rows[$key]['uorder'] = $uorder['union_order_id'];
				if($order_row['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY  && $val['user_type'] == 2 )
				{
					$data_rows[$key]['act'] = 'pay';
				}
				else
				{
					$data_rows[$key]['act'] = 'info';
				}
			}
			else
			{
				$data_rows[$key]['record_status_con'] = $RecordStatusModel->recordStatus[$val['record_status']];
				$data_rows[$key]['act'] = 'info';
			}

			$data_rows[$key]['user_type_con'] = $RecordStatusModel->userType[$val['user_type']];
			$consume_record = $Consume_TradeModel->getConsumeTradeByOid($val['order_id']);
			$data_rows[$key]['consume_trade'] = $consume_record;
			$data_rows[$key]['trade_type'] = $Trade_Type->trade_type[$val['trade_type_id']];
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
	 public function getRecordList1($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$RecordStatusModel = new RecordStatusModel();
		$Consume_TradeModel = new Consume_TradeModel();
		$Order_StateModel = new Order_StateModel();
		$Union_Order = new Union_Order();
		foreach ($data["items"] as $key => $value)
		{
			//如果为购物交易明细，显示交易进度
			if($value['trade_type_id'] == Trade_TypeModel::SHOPPING)
			{
				$order_row = $Consume_TradeModel->getOne($value['order_id']);
				$data["items"][$key]['record_status_con'] = $Order_StateModel->orderState[$order_row['order_state_id']];

				//查找支付单号
				$uorder_row = $Union_Order->getByWhere(array('inorder' => $value['order_id']));
				fb($uorder_row);
				fb($value['user_type']);
				$uorder = current($uorder_row);
				$data["items"][$key]['uorder'] = $uorder['union_order_id'];
				if($order_row['order_state_id'] == Order_StateModel::ORDER_WAIT_PAY  && $value['user_type'] == 2)
				{
					fb('pay');
					$data["items"][$key]['act'] = 'pay';
				}
				else
				{
					fb('info');
					$data["items"][$key]['act'] = 'info';
				}
			}
			else
			{
				$data["items"][$key]["record_status_con"] = $RecordStatusModel->recordStatus[$value['record_status']];
			}


		}
		return $data;
	}
	public function getRecordListByType($user_id = null,$type = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$consume_record_id_row = array();

		$Consume_TradeModel = new Consume_TradeModel();

		$this->sql->setWhere('user_id',$user_id);

		if($type)
		{
			$this->sql->setWhere('trade_type_id',$type);
		}
		$consume_record_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($consume_record_id_row)
		{
			$data_rows = $this->getRecord($consume_record_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getRecordByOid($order_id = null)
	{
		$this->sql->setWhere('order_id',$order_id);
		$data = $this->getRecord("*");

		return $data;
	}
}
?>