<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_GoodsModel extends Order_Goods
{
	const EVALUATION_YES = 1;        //已评价
	const EVALUATION_NO  = 0;        //未评价
	const REFUND_NO      = 0;
	const REFUND_IN      = 1;
	const REFUND_COM     = 2;

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data            = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$Order_BaseModel = new Order_BaseModel();
		if ($data['items'])
		{
			foreach ($data['items'] as $key => $val)
			{
				$order_base                                 = $Order_BaseModel->getOne($val['order_id']);
				$data['items'][$key]['buyer_user_name']     = $order_base['buyer_user_name'];
				$data['items'][$key]['order_finished_time'] = $order_base['order_finished_time'];
			}
		}
		return $data;
	}

	/**
	 * 商品销售列表
	 *
	 * @author Zhuyt
	 */
	public function getGoodSaleList($cond_row = array(), $order_row = array(), $page, $rows)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		$Order_BaseModel = new Order_BaseModel();
		if ($data['items'])
		{
			foreach ($data['items'] as $key => $val)
			{
				$order = $Order_BaseModel->getOne($val['order_id']);

				$data['items'][$key]['order'] = $order;
			}
		}

		fb($data);
		return $data;
	}

	/**
	 * 商品销售数量
	 *
	 * @author Zhuyt
	 */
	public function getGoodsSaleNum($goods_id = null)
	{
		$data = $this->listByWhere(array('goods_id' => $goods_id));

		$count = count($data['items']);

		return $count;
	}

	/**
	 * 获取订单产品列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsListByOrderId($order_id, $order_row = array(), $page = 1, $rows = 100)
	{
		if (is_array($order_id))
		{
			$cond_row = array('order_id:IN' => $order_id);
		}
		else
		{
			$cond_row = array('order_id' => $order_id);
		}

		return $this->listByWhere($cond_row);
	}

	/**
	 * 获取订单产品详情
	 *
	 * @param  int $order_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsDetail($cond_row)
	{

		return $this->getOneByWhere($cond_row);
	}

}

?>