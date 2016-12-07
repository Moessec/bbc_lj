<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_ShoplistCtl extends Yf_AppController
{
	public $Shop_ShoplistCtl = null;

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

		//include $this->view->getView();
		$this->Shop_ShoplistModel = new Shop_BaseModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{

			$data = $this->Shop_ShoplistModel->getBaseList($cond_row, $order_row, '', '');

		$this->data->addBody(-140, $data);
	}

	
}

?>