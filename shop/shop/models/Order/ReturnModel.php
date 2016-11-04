<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_ReturnModel extends Order_Return
{

	const RETURN_WAIT_PASS      = 1;
	const RETURN_SELLER_PASS    = 2;
	const RETURN_SELLER_UNPASS  = 3;
	const RETURN_SELLER_GOODS   = 4;
	const RETURN_PLAT_PASS      = 5;
	const RETURN_TYPE_ORDER     = 1;
	const RETURN_TYPE_GOODS     = 2;
	const RETURN_TYPE_VIRTUAL   = 3;
	const RETURN_GOODS_ISRETURN = 0;
	const RETURN_GOODS_RETURN   = 1;

	public static $state = array(
		'1' => 'wait_pass',
		'2' => 'seller_pass',
		'3' => 'seller_unpass',
		'4' => 'seller_goods',
		'5' => 'plat_pass',
	);

	public $return_state;
	public $return_type;

	public function __construct()
	{
		parent::__construct();
		$this->return_state = array(
			'1' => _("等待卖家审核"),
			'2' => _("卖家审核通过"),
			'3' => _("卖家审核未通过"),
			'4' => _("等待平台审核"),
			'5' => _("退款/货完成"),
		);
		$this->return_type  = array(
			'1' => _("退款"),
			'2' => _("退货"),
			'3' => _("虚拟订单退款")
		);
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getReturnList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($data['items'] as $k => $v)
		{
			$data['items'][$k]['return_state_text'] = $this->return_state[$v['return_state']];
			$data['items'][$k]['return_type_text']  = $this->return_state[$v['return_type']];
		}

		return $data;
	}

	public function getReturnExcel($cond_row = array(), $order_row = array())
	{
		$data = $this->getByWhere($cond_row, $order_row);

		foreach ($data as $k => $v)
		{
			$data[$k]['order_number'] = " " . $v['order_number'] . " ";
			$data[$k]['return_code']  = " " . $v['return_code'] . " ";
		}

		return array_values($data);
	}

	public function getReturn($cond_row = array(), $order_row = array())
	{
		$data = $this->getOneByWhere($cond_row, $order_row);

		$data['return_state_etext'] = self::$state[$data['return_state']];
		$data['return_state_text']  = $this->return_state[$data['return_state']];

		return $data;
	}

	public function getReturnBase($id)
	{
		$data                       = $this->getOne($id);
		$data['return_state_etext'] = self::$state[$data['return_state']];
		$data['return_state_text']  = $this->return_state[$data['return_state']];

		return $data;
	}


}

?>