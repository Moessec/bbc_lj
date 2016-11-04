<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_Analysis_GeneralCtl extends Seller_Controller
{
	public $Analysis_ShopGeneralModel   = null;
	public $Analysis_ShopGoodsModel     = null;
	public $Analysis_PlatformTotalModel = null;

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
		$this->Analysis_ShopGeneralModel   = new Analysis_ShopGeneralModel();
		$this->Analysis_ShopGoodsModel     = new Analysis_ShopGoodsModel();
		$this->Analysis_PlatformTotalModel = new Analysis_PlatformTotalModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{

		$start_date                  = date("Y-m-d", strtotime("-30 days"));
		$end_date                    = date("Y-m-d");
		$cond_row['general_date:>='] = $start_date;
		$cond_row['general_date:<='] = $end_date;
		$cond_row['shop_id']         = Perm::$shopId;

		$order                    = $this->Analysis_ShopGeneralModel->getByWhere($cond_row);
		$total['order_cash']      = 0;
		$total['order_goods_num'] = 0;
		$total['order_num']       = 0;
		$total['order_user_num']  = 0;
		$total['goods_favor_num'] = 0;
		$total['shop_favor_num']  = 0;
		foreach ($order as $v)
		{
			$data['line'][] = date("m-d", strtotime($v['general_date']));
			$data['num'][]  = $v['order_cash'];
			$total['order_cash'] += $v['order_cash'];
			$total['order_goods_num'] += $v['order_goods_num'];
			$total['order_num'] += $v['order_num'];
			$total['order_user_num'] += $v['order_user_num'];
			$total['goods_favor_num'] += $v['goods_favor_num'];
			$total['shop_favor_num'] += $v['shop_favor_num'];
		}

		$total['order_num'] ? $total['general_cash']      = round($total['order_cash'] / $total['order_num'], 2) : "";
		$total['order_user_num'] ? $total['general_user_cash'] = round($total['order_cash'] / $total['order_user_num'], 2) : '';

		isset($data['line']) ?  $data['line'] = json_encode($data['line']) : '';
		isset($data['num']) ?  $data['num']  = json_encode($data['num']) : '';

		$total_data = $this->Analysis_PlatformTotalModel->getOne(Perm::$shopId);
		if (empty($total_data))
		{
			$total['goods_num'] = 0;
		}
		else
		{
			$total['goods_num'] = $total_data['goods_num'];
		}

		$cond_row2['goods_date:>='] = $start_date;
		$cond_row2['goods_date:<='] = $end_date;
		$cond_row2['shop_id']       = Perm::$shopId;

		$goods = $this->Analysis_ShopGoodsModel->listByWhere($cond_row2, array('order_num' => 'DESC'), 1, 10);

		$goods_list = $goods['items'];
		include $this->view->getView();
	}

	
}

?>