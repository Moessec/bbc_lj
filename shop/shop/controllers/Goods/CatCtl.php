<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_CatCtl extends Controller
{
	public $goodsCatModel = null;

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
		$this->initData();
		$this->web = $this->webConfig();
		$this->nav = $this->navIndex();
		$this->cat = $this->catIndex();
		//include $this->view->getView();
		$this->goodsCatModel = new Goods_CatModel();
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function cat()
	{
		$Goods_CatModel = new Goods_CatModel();

		if (isset($_REQUEST['cat_parent_id']))
		{
			$cat_parent_id = request_int('cat_parent_id', 0);

			$data_rows     = $Goods_CatModel->getCatTreeData($cat_parent_id, false, 1);
			$data['items'] = array_values($data_rows);
		}
		else
		{
			$data = $Goods_CatModel->getCatTree();
			
			if ( request_string('filter') )
			{
				$Goods_CatModel->filterCatTreeData( $data['items'] );
				$data['items'] = array_values($data['items']);
			}
		}


		$this->data->addBody(-140, $data);
	}
	public function catgoodlist()
	{
		if('json' == $this->typ)
		{
		$Goods_CatModel = new Goods_CatModel();

		if (isset($_REQUEST['cat_parent_id']))
		{
			$cat_parent_id = request_int('cat_parent_id', 0);

			$data_rows     = $Goods_CatModel->getCatTreeData($cat_parent_id, false, 1);
			$data['items'] = array_values($data_rows);
		}
		else
		{
			$data = $Goods_CatModel->getCatTree();
			
			if ( request_string('filter') )
			{
				$Goods_CatModel->filterCatTreeData( $data['items'] );
				$data['items'] = array_values($data['items']);
			}
		}
         foreach ($data['items'] as $key => $value) {
         	foreach ($value as $k=>$v) {
         		if($k=='cat_id')
         		{
         			$Goods_CatModel->getReturnData($v);
         		}

         	}
         }

		$this->data->addBody(-140, $data);
	}
	}

	public function tree()
	{
		$Goods_CatModel = new Goods_CatModel();

		$cat_parent_id = request_int('cat_parent_id', 0);

		$data['items'] = $Goods_CatModel->getChildCat($cat_parent_id);

		$this->data->addBody(-140, $data);
	}


	public function goodsCatList()
	{
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getGoodsCatList();

		//最近浏览
		$user_id             = Perm::$userId;
		$User_FootprintModel = new User_FootprintModel();
		$data_foot           = $User_FootprintModel->getByWhere(array('user_id' => $user_id), array('footprint_time' => 'desc'));
		$common_id_rows      = array_column($data_foot, 'common_id');
		$common_id_rows      = array_unique($common_id_rows);
		$common_id_rows      = array_slice($common_id_rows, 0, 4);
		$Goods_CommonModel   = new Goods_CommonModel();
		$data_recommon       = $Goods_CommonModel->listByWhere(array('common_id:in' => $common_id_rows), array('common_sell_time' => 'desc'), 0, 4);
		$data_recommon_goods = $Goods_CommonModel->getRecommonRow($data_recommon);

		include $this->view->getView();
	}
	public function shoplists()
	{
		$shopGoodsCatModel = new Shop_GoodsCatModel();
		// var_dump(1);die;
		$user_id = Perm::$userId;
		$parent_id = request_int('parent_id');
		$shop_id = request_int('shop_id');
		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');
		$cond_row  = array('shop_id'=>$shop_id,'parent_id'=>$parent_id);
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $shopGoodsCatModel->getGoodsCatList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $shopGoodsCatModel->getGoodsCatList($cond_row, $order_row, $page, $rows);
		}

// var_dump($data);die;

		$this->data->addBody(-140, $data);
	}
	public function tree()
	{

		// var_dump(1);die;
		$parent_id = request_int('parent_id');
		$shop_id = request_int('shop_id');
		var_dump($shop_id,$parent_id);die;
		$Shop_GoodsCatModel = new Shop_GoodCatModel();
		$data['items'] = $Shop_GoodsCatModel->getChildCat($shop_id,$parent_id);

		$this->data->addBody(-140, $data);
	}			

}

?>