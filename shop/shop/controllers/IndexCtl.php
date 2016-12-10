<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Controller
{
	public function __construct(&$ctl, $met, $typ)
	{	
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{	
		if ('json' == $this->typ)
		{
			$goods_CommonModel = new Goods_CommonModel();
			$mbTplLayoutModel = new Mb_TplLayoutModel();
			$layout_list = $mbTplLayoutModel->getByWhere(array('mb_tpl_layout_enable'=>Mb_TplLayoutModel::USABLE), array('mb_tpl_layout_order'=>'ASC'));

			$data = array();
			$data[] = array();

			if ( !empty($layout_list) )
			{
				foreach($layout_list as $mb_tpl_layout_id => $layout_data_val)
				{
					if ($layout_data_val['mb_tpl_layout_type'] == 'adv_list')
					{
						$adv_list = $layout_data_val;
					}

					if ($layout_data_val['mb_tpl_layout_type'] == 'home1')
					{
						$hom1 = array();
						$mb_tpl_layout_data = $layout_data_val['mb_tpl_layout_data'];

						$hom1['title'] = $layout_data_val['mb_tpl_layout_title'];
						$hom1['image'] = $mb_tpl_layout_data['image'];
						$hom1['type']  = $mb_tpl_layout_data['image_type'];
						$hom1['data']  = $mb_tpl_layout_data['image_data'];

						$data[$mb_tpl_layout_id+1]['home1'] = $hom1;
					}

					if ($layout_data_val['mb_tpl_layout_type'] == 'home2' || $layout_data_val['mb_tpl_layout_type'] == 'home4')
					{
						$home2_4 = array();
						$mb_tpl_layout_data = $layout_data_val['mb_tpl_layout_data'];

						$home2_4['title'] = $layout_data_val['mb_tpl_layout_title'];

						$home2_4['rectangle1_image'] = $mb_tpl_layout_data['rectangle1']['image'];
						$home2_4['rectangle1_type']  = $mb_tpl_layout_data['rectangle1']['image_type'];
						$home2_4['rectangle1_data']  = $mb_tpl_layout_data['rectangle1']['image_data'];

						$home2_4['rectangle2_image'] = $mb_tpl_layout_data['rectangle2']['image'];
						$home2_4['rectangle2_type']  = $mb_tpl_layout_data['rectangle2']['image_type'];
						$home2_4['rectangle2_data']  = $mb_tpl_layout_data['rectangle2']['image_data'];

						$home2_4['square_image'] = $mb_tpl_layout_data['square']['image'];
						$home2_4['square_type']  = $mb_tpl_layout_data['square']['image_type'];
						$home2_4['square_data']  = $mb_tpl_layout_data['square']['image_data'];

						$data[$mb_tpl_layout_id+1][$layout_data_val['mb_tpl_layout_type']] = $home2_4;
					}

					if ($layout_data_val['mb_tpl_layout_type'] == 'home3')
					{
						$home3 = array();
						$item = array();
						$mb_tpl_layout_data = $layout_data_val['mb_tpl_layout_data'];

						foreach ($mb_tpl_layout_data as $key => $layout_data)
						{
							$item[$key]['image'] = $layout_data['image'];
							$item[$key]['type']  = $layout_data['image_type'];
							$item[$key]['data']  = $layout_data['image_data'];
						}

						$home3['item'] = $item;
						$home3['title'] = $layout_data_val['mb_tpl_layout_title'];

						$data[$mb_tpl_layout_id+1]['home3'] = $home3;
					}

					if ($layout_data_val['mb_tpl_layout_type'] == 'goods')
					{
						$goods = array();
						$item = array();
						$mb_tpl_layout_data = $layout_data_val['mb_tpl_layout_data'];

						$common_list = $goods_CommonModel->getByWhere( array('common_id:IN'=>$mb_tpl_layout_data) );
						if ( $common_list )
						{
							foreach($common_list as $common_id => $common_data)
							{
								$goods_id = pos($common_data['goods_id']);
								if ( is_array($goods_id) )
								{
									$goods_id = pos($goods_id);
								}
								$item[$common_id]['goods_id'] 			   = $goods_id;
								$item[$common_id]['goods_name'] 		   = $common_data['common_name'];
								$item[$common_id]['goods_promotion_price'] = $common_data['common_price'];
								$item[$common_id]['goods_image'] 		   = sprintf('%s!360x360', $common_data['common_image']);
							}
							$goods['item'] = array_values($item);
							$goods['title'] = $layout_data_val['mb_tpl_layout_title'];
							$data[$mb_tpl_layout_id+1]['goods'] = $goods;
						}
					}
				}
			}


			// $data = array(
			// 	1 => array(
			// 		'home1' => array(
			// 			'title' => '',
			// 			'image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'type' => 'special',
			// 			'data' => '3',
			// 		),
			// 	),
			// 	2 => array(
			// 		'home4' => array(
			// 			'title' => '',
			// 			'rectangle1_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'rectangle1_type' => 'goods',
			// 			'rectangle1_data' => '100456',
			// 			'rectangle2_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'rectangle2_type' => 'goods',
			// 			'rectangle2_data' => '100378',
			// 			'square_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'square_type' => 'keyword',
			// 			'square_data' => '收纳',
			// 		),
			// 	),
			// 	3 => array(
			// 		'home2' => array(
			// 			'title' => '',
			// 			'square_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'square_type' => 'keyword',
			// 			'square_data' => '水果',
			// 			'rectangle1_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'rectangle1_type' => 'goods',
			// 			'rectangle1_data' => '100374',
			// 			'rectangle2_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'rectangle2_type' => 'goods',
			// 			'rectangle2_data' => '100028',
			// 		),
			// 	),
			// 	4 => array(
			// 		'home1' => array(
			// 			'title' => '',
			// 			'image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'type' => 'special',
			// 			'data' => '4',
			// 		),
			// 	),
			// 	5 => array(
			// 		'home3' => array(
			// 			'title' => '',
			// 			'item' => array(
			// 				0 => array(
			// 					'image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 					'type' => 'goods',
			// 					'data' => '100517',
			// 				),
			// 				1 => array(
			// 					'image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 					'type' => 'goods',
			// 					'data' => '100520',
			// 				)
			// 			),
			// 		),
			// 	),
			// 	6 => array(
			// 		'home1' => array(
			// 			'title' => '',
			// 			'image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 			'type' => 'special',
			// 			'data' => '5',
			// 		),
			// 	),
			// 	7 => array(
			// 		'goods' => array(
			// 			'title' => '',
			// 			'item' => array(
			// 				0 => array(
			// 					'goods_id' => '100385',
			// 					'goods_name' => '【全国通用】500元面值海底捞火锅现金储值卡',
			// 					'goods_promotion_price' => '428.00',
			// 					'goods_price' => '528.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg',
			// 				),
			// 				1 => array(
			// 					'goods_id' => '100383',
			// 					'goods_name' => '12省通用】必胜客100面值现金券1张',
			// 					'goods_promotion_price' => '87.90',
			// 					'goods_price' => '428.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				)
			// 			),
			// 		),
			// 	),
			// 	8 => array(
			// 		'goods' => array(
			// 			'title' => '热卖单品',
			// 			'item' => array(
			// 				0 => array(
			// 					'goods_id' => '100601',
			// 					'goods_name' => '进口德国凯驰HD5/11C高压清洗机',
			// 					'goods_promotion_price' => '7800.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				),
			// 				1 => array(
			// 					'goods_id' => '100367',
			// 					'goods_name' => '乐视TV Letv Max70乐视tv液晶电视',
			// 					'goods_promotion_price' => '9979.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				),
			// 				2 => array(
			// 					'goods_id' => '100367',
			// 					'goods_name' => '乐视TV Letv Max70乐视tv液晶电视3d网络 ',
			// 					'goods_promotion_price' => '19979.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				)

			// 			),
			// 		),
			// 	),
			// 	9 => array(
			// 		'goods' => array(
			// 			'title' => '热卖单品',
			// 			'item' => array(
			// 				0 => array(
			// 					'goods_id' => '100601',
			// 					'goods_name' => '进口德国凯驰HD5/11C高压清洗机',
			// 					'goods_promotion_price' => '7800.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				),
			// 				1 => array(
			// 					'goods_id' => '100367',
			// 					'goods_name' => '乐视TV Letv Max70乐视tv液晶电视',
			// 					'goods_promotion_price' => '9979.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				),
			// 				2 => array(
			// 					'goods_id' => '100367',
			// 					'goods_name' => '乐视TV Letv Max70乐视tv液晶电视3d网络 ',
			// 					'goods_promotion_price' => '19979.00',
			// 					'goods_image' => 'http://shop.bbc-builder.com/image.php/shop/data/upload/media/plantform/image/20160721/1469094838911002.jpg!360x360.jpg',
			// 				)

			// 			),
			// 		),
			// 	),
			// );


			//头部滚动条
			$slide_row = $adv_list['mb_tpl_layout_data'];

			foreach ($slide_row as $s_k => $s_v)
			{
				$item          = array();
				$item['image'] = $s_v['image'];
				$item['type']  = $s_v['image_type'];
				$item['data']  = $s_v['image_data'];
//				$item['link']  = $s_v['image_data'];
				$slide_row[]   = $item;
			}

			//$data = array();
			$data[0]['slider_list']['item'] = $slide_row;

			$data = array_values($data);
			$this->data->addBody(-140, $data);

		}
		else
		{
			$Cache = Yf_Cache::create('default');

			$site_index_key = sprintf('%s|%s|', Yf_Registry::get('server_id'), 'site_index');

			if (!$Cache->start($site_index_key))
			{
				$this->initData();

				//团购风暴
				$GroupBuy_BaseModel = new GroupBuy_BaseModel;
				$cond_row           = array(
					"groupbuy_starttime:<=" => get_date_time(),
					"groupbuy_endtime :>=" => get_date_time(),
					"groupbuy_state" => GroupBuy_BaseModel::NORMAL,
				);
				$order_row          = array("groupbuy_recommend" => "desc");

				$gb_goods_list = $GroupBuy_BaseModel->getGroupBuyGoodsList($cond_row, $order_row, 1, 15);

				//楼层设置
				$Adv_PageSettingsModel = new Adv_PageSettingsModel();
				$cond_adv_row          = array("page_status" => 1);
				$order_adv_row         = array("page_order" => "asc");
				$adv_list              = $Adv_PageSettingsModel->listByWhere($cond_adv_row, $order_adv_row);

				//首页标题关键字
				$title             = Web_ConfigModel::value("title");//首页名;
				$this->keyword     = Web_ConfigModel::value("keyword");//关键字;
				$this->description = Web_ConfigModel::value("description");//描述;
				$this->title       = str_replace("{sitename}", $this->web['web_name'], $title);
				$this->keyword       = str_replace("{sitename}", $this->web['web_name'], $this->keyword);
				$this->description       = str_replace("{sitename}", $this->web['web_name'], $this->description);

				include $this->view->getView();

				$Cache->_id = $site_index_key;
				$Cache->end($site_index_key);
			}

		}

	}

	public function main()
	{
		//include $this->view->getView();
	}

	public function getUserLoginInfo()
	{
		if (Perm::checkUserPerm())
		{
			$user_id       = Perm::$userId;
			$userInfoModel = new User_InfoModel();
			$this->userInfo          = $userInfoModel->getOne($user_id);
		}

		include $this->view->getView();

		$this->data->addBody(-140, $data);

	}

	public function getSearchWords()
	{
		$search_words              = explode(',', Web_ConfigModel::value('search_words'));
		$data['hot_info']["name"]  = $search_words[0];
		$data['hot_info']["value"] = $search_words[0];

		$this->data->addBody(-140, $data);
	}

	public function getSearchKeyList()
	{
		$search_words     = explode(',', Web_ConfigModel::value('search_words'));
		$data['list']     = $search_words;
		$data['his_list'] = array($search_words[1]);

		$this->data->addBody(-140, $data);
	}


	public function test()
	{
		include $this->view->getView();
	}

	//获取侧边栏的信息
	public function toolbar()
	{
		$this->initData();
		//$this->user_info = $this->userInfo();

		//公告

		$this->articleBaseModel = new Article_BaseModel();

		$Announcement_row['article_type']   = 1;
		$Announcement_row['article_status'] = 1;

		$Announcement = $this->articleBaseModel->getBaseAllList($Announcement_row, array('article_add_time' => 'DESC'), 1, 20);

		//用户登录情况下获取信息
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;

			$cord_row = array();
			$cond_row = array('user_id' => $user_id);

			$userResourceModel = new User_ResourceModel();

			$user_list = $userResourceModel->getUserResource($cond_row);

		}

		//用户登录情况下获取购物车信息
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;

			$cord_row  = array();
			$order_row = array();

			$cond_row  = array('user_id' => $user_id);
			$CartModel = new CartModel();
			$cart_list = $CartModel->getCardList($cond_row, $order_row);
		}
		//用户登录情况下获取关注店铺信息
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;

			$userFavoritesShopModel = new User_FavoritesShopModel();
			$goodsCommonModel       = new Goods_CommonModel();

			$shop_list = $userFavoritesShopModel->getFavoritesShopDetail($user_id, 1, 4);

			if ($shop_list['items'])
			{
				foreach ($shop_list['items'] as $key => $val)
				{

					$cond_row            = array();
					$cond_row['shop_id'] = $val['shop_id'];
					$goods               = $goodsCommonModel->getGoodsList($cond_row, array(), 1, 2);

					if ($goods)
					{
						$shop_list['items'][$key]['detail'] = $goods;
					}

				}
			}

		}

		//用户登录情况下获取收藏商品信息
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;

			$userFavoritesGoodsModel = new User_FavoritesGoodsModel();

			$favorites_row['user_id'] = $user_id;

			$goods_list = $userFavoritesGoodsModel->getFavoritesGoodsDetail($favorites_row, array('favorites_goods_time' => 'DESC'), 1, 20);


		}
		//用户登录情况下获取足迹信息
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;

			$cord_row  = array();
			$order_row = array();

			$cond_row = array('user_id' => $user_id);

			$userFootprintModel = new User_FootprintModel();

			$footprint_list = $userFootprintModel->getFootprintList($cond_row, array('footprint_time' => 'DESC'), 1, 30);
			if ($footprint_list['items'])
			{
				$goods_id_row                 = array();
				$goods_id_row['common_id:in'] = array_column($footprint_list['items'], 'common_id');
				$goods_id_row                 = array_unique($goods_id_row);

				$goodsCommonModel = new Goods_CommonModel();
				$goods            = $goodsCommonModel->getGoodsList($goods_id_row);

				$goods_id = array_column($goods['items'], 'common_id');
				//以common_id为下表
				$commonAll = array();
				foreach ($goods['items'] as $k => $v)
				{
					$commonAll[$v['common_id']] = $v;
				}
				foreach ($footprint_list['items'] as $key => $val)
				{
					if (in_array($val['common_id'], $goods_id))
					{
						$footprint_list['items'][$key]['detail'] = $commonAll[$val['common_id']];
					}

				}
			}
		}

		include $this->view->getView();
	}

	/**
	 *
	 * 取出地区（一级） 店铺保障
	 */
	public function getSearchAdv()
	{
		$data = array();
		$area_list = array();
		$contract_list = array();
		$baseDistrictModel = new Base_DistrictModel();
		$shopContractTypeModel = new Shop_ContractTypeModel();

		$district_list = $baseDistrictModel->getDistrictTree(0, false);
		$contract_type_list = $shopContractTypeModel->getByWhere( array( 'contract_type_state'=> Shop_ContractTypeModel::CONTRACT_OPEN, 'contract_type_name:<>' => '') );

		$district_list = pos($district_list);
		foreach ( $district_list as $key => $district_data)
		{
			$area_list[$key]['area_id'] = $district_data['district_id'];
			$area_list[$key]['area_name'] = $district_data['district_name'];
		}

		$contract_type_list = array_values($contract_type_list);
		foreach ($contract_type_list as $key => $type_data)
		{
			$contract_list[$key]['id'] = $type_data['contract_type_id'];
			$contract_list[$key]['name'] = $type_data['contract_type_name'];
		}

		$data['area_list'] = $area_list;
		$data['contract_list'] = $contract_list;

		$this->data->addBody(-140, $data);
	}
	// public function getCatpro()
	// {
	// 	$data = array();
	// 	$goodscat = new Goods_GoodsCtl();
	// 	$goodscatlist=$goodscat->cat();
	// 	foreach ($goodscatlist as $key => $cat_data) {

	// 		$datalist[$key][list] =$this->goodscat->goodsCatList();
	// 	}
	// 	$this->data->addBody(-140, $data);
	// }

}

?>