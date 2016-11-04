<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_FavoritesCtl extends Buyer_Controller
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
		
		$this->userFavoritesGoodsModel = new User_FavoritesGoodsModel();
		$this->goodsBaseModel          = new Goods_BaseModel();
		$this->goodsCommonModel        = new Goods_CommonModel();
		$this->shopBaseModel           = new Shop_BaseModel();
		$this->goodsCatModel           = new Goods_CatModel();
		$this->userFavoritesShopModel  = new User_FavoritesShopModel();
		$this->userFootprintModel      = new User_FootprintModel();
	}
	
	/**
	 *收藏商品信息
	 *
	 * @access public
	 */
	public function favoritesGoods()
	{
		
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):18;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
		$page_nav          = '';
		$user_id           = Perm::$userId;

		$cond_row['user_id'] = $user_id;
		
		$data = $this->userFavoritesGoodsModel->getFavoritesGoodsDetail($cond_row, array('favorites_goods_time' => 'DESC'), $page, $rows);
		
		if ($data)
		{
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
		}
		if ('json' == $this->typ)
		{
			$data['items'] = array_values($data['items']);
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	/**
	 *删除收藏商品信息
	 *
	 * @access public
	 */
	public function delFavoritesGoods()
	{
		$userId   = Perm::$userId;
		$goods_id = request_int('id');
		
		$order_row['user_id']  = $userId;
		$order_row['goods_id'] = $goods_id;
		
		$de = $this->userFavoritesGoodsModel->getFavoritesGoods($order_row);

		$favorites_goods_id = $de['favorites_goods_id'];

		$flag = $this->userFavoritesGoodsModel->removeGoods($favorites_goods_id);

		if ($flag === false)
		{
			$status = 250;
			$msg    = _('failure');
		}
		else
		{
			$status = 200;
			$msg    = _('success');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
	
	/**
	 *收藏店铺信息
	 *
	 * @access public
	 */
	public function favoritesShop()
	{
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):5;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
		
		$user_id             = Perm::$userId;
		$cond_row['user_id'] = $user_id;
		
		$data = $this->userFavoritesShopModel->getFavoritesShops($cond_row, array('favorites_shop_time' => 'DESC'), $page, $rows);
		
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		
		if ('json' == $this->typ)
		{
			/*
			$shop_id_row = array_column($data['items'], 'shop_id');

			//获取单个店铺数据
			$Goods_CommonModel = new Goods_CommonModel();
			$goods_num   = $Goods_CommonModel->getCommonStateNum($data['items']['shop_id'], -1);

			$data['items']['shop_collect'] = $goods_num;
			*/

			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	/**
	 *删除收藏店铺信息
	 *
	 * @access public
	 */
	public function delFavoritesShop()
	{
		$userId  = Perm::$userId;
		$shop_id = request_int('id');
		
		$cond_row['user_id'] = $userId;
		$cond_row['shop_id'] = $shop_id;
		
		$de = $this->userFavoritesShopModel->getFavoritesShop($cond_row);

		$favorites_shop_id = $de['favorites_shop_id'];

		$flag = $this->userFavoritesShopModel->removeShop($favorites_shop_id);

		if ($flag === false)
		{
			$status = 250;
			$msg    = _('failure');
		}
		else
		{
			$status = 200;
			$msg    = _('success');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *个人足迹信息
	 *
	 * @access public
	 */
	public function footprint()
	{
		
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):2;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
		
		$user_id              = Perm::$userId;
		$order_row['user_id'] = $user_id;
		$classid              = request_int('classid');

		$data = $this->userFootprintModel->getFootprintList($order_row, array('footprint_time' => 'DESC'));

		$page_nav = '';
		$arr      = array();
		$cat      = array();
		if ($data['items'])
		{
			//查出有详情的
			$goodsid                 = array();
			$goodsid['common_id:in'] = array_column($data['items'], 'common_id');

			$goods_cat = $this->goodsCommonModel->getGoodsList($goodsid);
			//分类名搜索
			if ($classid)
			{
				$goodsid['cat_id'] = $classid;
			}

			$goodsall = $this->goodsCommonModel->getGoodsList($goodsid);
			
			//删除数组中的good，即common里面的商品详情
			/* foreach( $goodsall['items'] as  $key=>$data) {
				if(in_array('good',array_keys($data))){ 
					 unset($goodsall['items'][$key]['good']);
				}   
			} */
			
			$goodsallid                   = array();
			$goodsallid                   = array_column($goodsall['items'], 'common_id');
			$class = '';
			if($goodsallid){
				$class = implode(',',$goodsallid);
			}
			$goodsall_row['user_id']      = $user_id;
			$goodsall_row['common_id:in'] = $goodsallid;

			$data = array();
			$data = $this->userFootprintModel->getFootprintList($goodsall_row, array('footprint_time' => 'DESC'));

			//获取时间
			$re = array();
			$re = array_column($data['items'], 'footprint_time');
			
			$re = array_unique($re);

			$footprint_row['user_id']           = $user_id;
			$footprint_row['footprint_time:in'] = $re;
			$footprint_row['common_id:in'] = $goodsallid;
			//获取所有有详情的足迹
			$foot = $this->userFootprintModel->getFootprintAll($footprint_row);
			//以时间为分类分出足迹
			$ce = array();
			foreach ($foot as $k => $v)
			{
				$ce[$v['footprint_time']][$k] = $v;
			}
			$data = array();
			foreach ($ce as $k => $v)
			{
				$data[][$k] = $v;
			}

			$goods_id = array();
			$goods_id = array_column($goodsall['items'], 'common_id');
			
			//以common_id为下标
			$commonAll = array();
			foreach ($goodsall['items'] as $k => $v)
			{
				$commonAll[$v['common_id']] = $v;
			}
			
			foreach ($data as $kk => $vv)
			{
				foreach ($vv as $ke => $ve)
				{
					foreach ($ve as $k => $v)
					{
						if (in_array($v['common_id'], $goods_id))
						{
							$data[$kk][$ke][$k]['detail'] = $commonAll[$v['common_id']];
						}
					}

				}
			}
			
			$total     = ceil_r(count($data) / $rows);
			$start     = ($page - 1) * $rows;
			$data_rows = array_slice($data, $start, $rows);

			$arr              = array();
			$arr['page']      = $page;
			$arr['total']     = $total;  //total page
			$arr['totalsize'] = count($data);
			$arr['records']   = count($data_rows);
			$arr['items']     = array_values($data_rows);

			if (!empty($goods_cat['items']))
			{
				
				$cat_id = array_column($goods_cat['items'], 'cat_id');
				
				$cat_id = array_unique($cat_id);

				foreach ($cat_id as $k => $v)
				{
					$cat_name = $this->goodsCatModel->getNameByCatid($v);
					if ($cat_name != '未分组')
					{
						$cat[$k]['cat_name'] = $cat_name;
						$cat[$k]['cat_id']   = $v;
					}
				}
			}

			$Yf_Page->totalRows = $arr['totalsize'];
			$page_nav           = $Yf_Page->prompt();

		}

		if ('json' == $this->typ)
		{
			$data        = array();
			$data['cat'] = $cat;
			$data['arr'] = $arr;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}
	
	/**
	 *删除足迹信息
	 *
	 * @access public
	 */
	public function delFootprint()
	{
		$userId         = Perm::$userId;
		$footprint_time = request_string('time');
		$common_id = request_string('id');
		
		$order_row['user_id']        = $userId;

		if ($footprint_time)
		{
			$order_row['footprint_time'] = $footprint_time;
		}
		if ($common_id)
		{
			$order_row['common_id:in'] = $common_id;
		}
		
		$de = $this->userFootprintModel->getFootprintAll($order_row);

		//开启事物
		$rs_row = array();
		$this->userFootprintModel->sql->startTransactionDb();
		
		$footprint_ids = array_column($de, 'footprint_id');
		
		$flag = $this->userFootprintModel->removeFootprint($footprint_ids);
		
		check_rs($flag, $rs_row);
		
		$flag = is_ok($rs_row);
		if ($flag !== false && $this->userFootprintModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userFootprintModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *
	 * 判断商品是否被收藏 wap
	 */
	public function getGoodsFI ()
	{
		$goods_id = request_int('fav_id');
		$user_id = Perm::$userId;

		$fav_result = $this->userFavoritesGoodsModel->getByWhere(array('user_id' => $user_id, 'goods_id' => $goods_id));

		$data = array();

		if (!empty($fav_result))
		{
			$data['favorites_info'] = pos($fav_result);
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
	
}

?>