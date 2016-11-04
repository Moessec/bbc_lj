<?php

/**
 * @author     charles
 */
class ShopCtl extends Controller
{

	public $shopBaseModel       = null;
	public $shopGoodCatModel    = null;
	public $shopNavModel        = null;
	public $goodsCommonModel    = null;
	public $shopDecorationModel = null;

	// public $shopDecorationBlockModel = null;


	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->shopBaseModel       = new Shop_BaseModel();
		$this->shopGoodCatModel    = new Shop_GoodCatModel();
		$this->shopNavModel        = new Shop_NavModel();
		$this->goodsCommonModel    = new Goods_CommonModel();
		$this->shopDecorationModel = new Shop_DecorationModel();
		// $this->shopDecorationBlockModel = new Shop_DecorationBlockModel();
		//调用这个方法查询出当下店铺是否开启自定义店铺，如果开启自定义店铺只能用店铺默认的模板，如果不是自定义店铺则需要分配那个模板
		$this->setTemp();
		$this->initData();
	}

	public function setTemp()
	{
		$shop_id = request_int('id');

		if ($shop_id)
		{
			//根据店铺id查询出是否开启自定义店铺
			$renovation_list = $this->shopBaseModel->getOne($shop_id);
			if (!empty($renovation_list['is_renovation']))
			{       
                                
				//店铺装修
				$this->view->setMet(null, "default");
			}
			else
			{
				if ($renovation_list)
				{
					//分配模板
					$shop_template = $renovation_list['shop_template'];
					$this->view->setMet(null, $shop_template);
				}
				else
				{
					$this->view->setMet('404');
				}
			}
		}
		else
		{
			$this->view->setMet('404');

		}
	}

	public function index()
	{
                $shop_id = request_int('id');
                if($shop_id){
			$this->shopCustomServiceModel = new Shop_CustomServiceModel;
			
			$cond_row['shop_id'] = $shop_id;
			
			$service = $this->shopCustomServiceModel->getServiceList($cond_row);
			if($service['items']){
				foreach($service['items'] as $key => $val)
				{
					$service[$key]["tool"] = $val["tool"] == 2 ? "<a target='_blank' href='http://www.taobao.com/webww/ww.php?ver=3&amp;touid=".$val['number']."&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8' ><img border='0' src='http://amos.alicdn.com/online.aw?v=2&amp;uid=".$val['number']."&amp;site=cntaobao&s=1&amp;charset=utf-8' alt='点击这里' /></a>" : "<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=".$val['number']."&site=qq&menu=yes'><img border='0' src='http://wpa.qq.com/pa?p=2:".$val['number'].":41 &amp;r=0.22914223582483828' alt='点击这里'></a>";
					//$service[$key]["tool"] = $val['tool'];
					$service[$key]["number"] = $val['number'];
					$service[$key]["name"] = $val['name'];
					$service[$key]["id"] = $val['id'];

					if($val['type']==1)
					{
						$de['after'][] = $service[$key];	
					}
					else
					{
						$de['pre'][] = $service[$key];
					}
				}
				$service = array();
				$service = $de;
			}
		}
		$GroupBuy_BaseModel = new GroupBuy_BaseModel();
		$data_hot_groupbuy  = $GroupBuy_BaseModel->getGroupBuyGoodsList(array("shop_id"=>$shop_id), array('groupbuy_buy_quantity' => 'desc'), 0, 5);

		if (!empty($data_hot_groupbuy['items']))
		{
			$hot_groupbuy_data = $data_hot_groupbuy['items'];
		}

		

		//店铺信息
		$shop_base = $this->shopBaseModel->getOne($shop_id);
                //2.评分信息
		$shop_detail = $this->shopBaseModel->getShopDetail($shop_id);
                $shop_scores_num = ($shop_detail['shop_desc_scores']+$shop_detail['shop_service_scores']+$shop_detail['shop_send_scores'])/3;
                $shop_scores_count = sprintf("%.2f", $shop_scores_num); 
                $shop_scores_percentage = $shop_scores_count * 20;
                
                if($shop_base['shop_self_support']=='false'){
                    $shop_all_base = $this->shopBaseModel->getbaseCompanyList($shop_id);
                }
		if (!empty($shop_base) && $shop_base['shop_status'] == 3)
		{
			//店铺幻灯和幻灯对应的连接
			$shop_slide     = explode(",", $shop_base['shop_slide']);
			$shop_slide_url = explode(",", $shop_base['shop_slideurl']);

			//用来判断是不是开启了店铺装潢
			// $renovation_list = $this->shopRenovationModel->getOne($shop_id);
			//查询数据的条件
			$nav_cond_row  = array(
				"shop_id" => $shop_id,
				"status" => 1
			);
			$nav_order_row = array("displayorder" => "asc");
			//店铺导航
			$shop_nav = $this->shopNavModel->listByWhere($nav_cond_row, $nav_order_row);
			if (($shop_base['is_renovation'] && $shop_base['is_only_renovation'] == "0") || !$shop_base['is_renovation'])
			{
				//店铺分类
				$cat_row['shop_id'] = $shop_id;
				$shop_cat           = $this->shopGoodCatModel->getGoodCatList($cat_row);

				//店铺下面的产品 新品 推荐 热销排行 收藏排行
				$goods_new_list   = $this->goodsCommonModel->getGoodsList(array(
																			  "shop_id" => $shop_id,
																			  "common_state" => 1
																		  ), array("common_add_time" => "desc"), 1, 12);
				$goods_recom_list = $this->goodsCommonModel->getGoodsList(array(
																			  "shop_id" => $shop_id,
																			  "common_is_recommend" => 2,
																			  "common_state" => 1
																		  ), array(), 1, 12);

				//ajax 读取
				$goods_selling_list = $this->goodsCommonModel->getGoodsList(array(
																				"shop_id" => $shop_id,
																				"common_state" => 1
																			), array("common_salenum" => "desc"), 1, 5);


				$goods_collec_list  = $this->goodsCommonModel->getGoodsList(array(
																				"shop_id" => $shop_id,
																				"common_state" => 1
																			), array("common_collect" => "desc"), 1, 5);
			}

			if ($shop_base['is_renovation'])
			{

				//根据店铺id，查询出装修编号
				$cat_row['shop_id'] = $shop_id;
				$decoration_row     = $this->shopDecorationModel->getOneByWhere($cat_row);

				//店铺装潢
				$decoration_detail = $this->shopDecorationModel->outputStoreDecoration($decoration_row['decoration_id'], $shop_id);
			}
			$title             = Web_ConfigModel::value("shop_title");//首页名;
			$this->keyword     = Web_ConfigModel::value("shop_keyword");//关键字;
			$this->description = Web_ConfigModel::value("shop_description");//描述;
			$this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
			$this->title       = str_replace("{shopname}", $shop_base['shop_name'], $this->title);
			$this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
			$this->keyword       = str_replace("{shopname}", $shop_base['shop_name'], $this->keyword);
			$this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
			$this->description       = str_replace("{shopname}", $shop_base['shop_name'], $this->description);
		}
		else
		{
			$this->view->setMet('404');
		}


		//传递数据
		if ('json' == $this->typ)
		{
			$data['shop_base']          = empty($shop_base) ? array() : $shop_base;
			$data['shop_nav']           = empty($shop_nav) ? array() : $shop_nav;
			$data['shop_cat']           = empty($shop_cat) ? array() : $shop_cat;
			$data['goods_new_list']     = empty($goods_new_list) ? array() : $goods_new_list;
			$data['goods_recom_list']   = empty($goods_recom_list) ? array() : $goods_recom_list;
			$data['goods_selling_list'] = empty($goods_selling_list) ? array() : $goods_selling_list;
			$data['goods_collec_list']  = empty($goods_collec_list) ? array() : $goods_collec_list;
			$this->data->addBody(-140, $data);

		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 收藏店铺
	 *
	 * @author     Zhuyt
	 */
	public function addCollectShop()
	{
		$shop_id = request_int('shop_id');

		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;
			//用户登录情况下,插入用户收藏商品表
			$add_row            = array();
			$add_row['user_id'] = $user_id;
			$add_row['shop_id'] = $shop_id;

			$User_FavoritesShopModel = new User_FavoritesShopModel();
			//开启事物
			$User_FavoritesShopModel->sql->startTransactionDb();

			$res = $User_FavoritesShopModel->getByWhere($add_row);

			if ($res)
			{
				$flag        = false;
				$data['msg'] = _("您已收藏过该店铺！");

			}
			else
			{
				$Shop_BaseModel = new Shop_BaseModel();
				$shop_base      = $Shop_BaseModel->getOne($shop_id);

				$add_row['shop_name']           = $shop_base['shop_name'];
				$add_row['shop_logo']           = $shop_base['shop_logo'];
				$add_row['favorites_shop_time'] = get_date_time();


				$User_FavoritesShopModel->addShop($add_row);

				//店铺详情中收藏数量增加
				$edit_row                 = array();
				$edit_row['shop_collect'] = '1';
				$flag                     = $Shop_BaseModel->editBaseCollectNum($shop_id, $edit_row, true);
				fb($flag);
				fb($shop_id);
			}


		}
		else
		{
			$flag = false;
		}

		if ($flag && $User_FavoritesShopModel->sql->commitDb())
		{
			$status      = 200;
			$msg         = _('success');
			$data['msg'] = $data['msg'] ? $data['msg'] : _("收藏成功！");
		}
		else
		{
			$User_FavoritesShopModel->sql->rollBackDb();
			$m           = $User_FavoritesShopModel->msg->getMessages();
			$msg         = $m ? $m[0] : _('failure');
			$status      = 250;
			$data['msg'] = $data['msg'] ? $data['msg'] : _("收藏失败！");
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function goodsList()
	{
		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 20;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$wap_page = request_int('page');
		$wap_curpage = request_int('curpage');
		if ( !empty($wap_page) )
		{
			$rows = $wap_page;
		}

		if ( !empty($wap_curpage) )
		{
			$page = $wap_curpage;
		}

		$shop_id = request_int('id');
		$sort    = request_string('sort');
		if($shop_id){
			$this->shopCustomServiceModel = new Shop_CustomServiceModel;
			
			$cond_row['shop_id'] = $shop_id;
			
			$service = $this->shopCustomServiceModel->getServiceList($cond_row);
			if($service['items']){
				foreach($service['items'] as $key => $val)
				{
					$service[$key]["tool"] = $val["tool"] == 2 ? "<a target='_blank' href='http://www.taobao.com/webww/ww.php?ver=3&amp;touid=".$val['number']."&amp;siteid=cntaobao&amp;status=1&amp;charset=utf-8' ><img border='0' src='http://amos.alicdn.com/online.aw?v=2&amp;uid=".$val['number']."&amp;site=cntaobao&s=1&amp;charset=utf-8' alt='点击这里' /></a>" : "<a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=".$val['number']."&site=qq&menu=yes'><img border='0' src='http://wpa.qq.com/pa?p=2:".$val['number'].":41 &amp;r=0.22914223582483828' alt='点击这里'></a>";
					//$service[$key]["tool"] = $val['tool'];
					$service[$key]["number"] = $val['number'];
					$service[$key]["name"] = $val['name'];
					$service[$key]["id"] = $val['id'];

					if($val['type']==1)
					{
						$de['after'][] = $service[$key];	
					}
					else
					{
						$de['pre'][] = $service[$key];
					}
				}
				$service = array();
				$service = $de;
			}
		}
                    //店铺信息
                    $shop_base = $this->shopBaseModel->getOne($shop_id);

                     //2.评分信息
                    $shop_detail = $this->shopBaseModel->getShopDetail($shop_id);
                    $shop_scores_num = ($shop_detail['shop_desc_scores']+$shop_detail['shop_service_scores']+$shop_detail['shop_send_scores'])/3;
                    $shop_scores_count = sprintf("%.2f", $shop_scores_num); 
                    $shop_scores_percentage = $shop_scores_count * 20;

                    if($shop_base['shop_self_support']=='false'){
                        $shop_all_base = $this->shopBaseModel->getbaseCompanyList($shop_id);
                    }
		if (!empty($shop_base) && $shop_base['shop_status'] == 3)
		{
			//店铺幻灯和幻灯对应的连接
			$shop_slide     = explode(",", $shop_base['shop_slide']);
			$shop_slide_url = explode(",", $shop_base['shop_slideurl']);
			//店铺导航
			$nav_cond_row  = array(
				"shop_id" => $shop_id,
				"status" => 1
			);
			$nav_order_row = array("displayorder" => "asc");

			$shop_nav = $this->shopNavModel->listByWhere($nav_cond_row, $nav_order_row);

			if ($sort == 'desc')
			{
				$new_sort = 'asc';
			}
			else
			{
				$new_sort = 'desc';
			}


			$order_row           = array();
			$cond_row            = array();
			$search              = request_string('search');
			$order               = request_string('order');
			$shop_cat_id         = request_int('shop_cat_id');
			$cond_row['shop_id'] = $shop_id;
			$Goods_CommonModel   = new Goods_CommonModel();

            if ($search)
			{
				$cond_row['common_name:like'] = '%' . $search . '%';
			}

			if ($shop_cat_id)
			{
                $cond_row['shop_cat_id:like'] = '%'.','.$shop_cat_id.','.'%';
			}

			if ($order)
			{
				$order_row = array($order => $sort);
			}

			$datas              = $Goods_CommonModel->getGoodsList($cond_row, $order_row, $page, $rows);
			$Yf_Page->totalRows = $datas['totalsize'];
			$page_nav           = $Yf_Page->prompt();
			$data               = $datas['items'];
		}
		else
		{
			$this->view->setMet('404');
		}

		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, $datas);

		}
		else
		{
			include $this->view->getView();
		}

	}

	/**
	 *
	 * 获取店铺信息和推荐商品 wap
	 */
	public function getStoreInfo()
	{
		$data 			= array();
		$store_info 	= array();
		$rec_goods_list = array();
		$shop_id = request_int('shop_id');


		$condi_rec_goods['shop_id'] 			= $shop_id;
		$condi_rec_goods['common_state'] 		= Goods_CommonModel::GOODS_STATE_NORMAL;

		$goods_common_list = $this->goodsCommonModel->getbywhere( $condi_rec_goods );

		//读取推荐商品
		$order_order_id['order_id']				= 'DESC';
		$condi_rec_goods['common_is_recommend'] = Goods_CommonModel::RECOMMEND_TRUE;
		$rec_goods_list = $this->goodsCommonModel->getGoodsList($condi_rec_goods);
		$rec_goods_list = $rec_goods_list['items'];

		//读取店铺详情
		$shop_base = $this->shopBaseModel->getShopDetail($shop_id);

		//判断当前店铺是否为用户所收藏
		$condi_u_f = array();
		$condi_u_f['user_id'] = Perm::$userId;
		$condi_u_f['shop_id'] = $shop_id;
		$userFavoritesShopModel = new User_FavoritesShopModel();
		$user_f_base = $userFavoritesShopModel->getByWhere($condi_u_f);
		if ( empty($user_f_base) )
		{
			$u_f_shop = false;
		}
		else
		{
			$u_f_shop = true;
		}

		//店铺幻灯片
		$shop_slide     = explode(",", $shop_base['shop_slide']);
		$shop_slide_url = explode(",", $shop_base['shop_slideurl']);

		$mb_sliders = array();

		if ( !empty($shop_slide) )
		{
			foreach ($shop_slide as $key => $silde_img)
			{
				$sliders['link'] = $shop_slide_url[$key];
				$sliders['imgUrl'] = $silde_img;

				array_push($mb_sliders, $sliders);
			}
		}

		$store_info['goods_count'] 			= count($goods_common_list);
		$store_info['is_favorate'] 			= $u_f_shop;
		$store_info['is_own_shop']			= $shop_base['shop_self_support'];
		$store_info['mb_sliders'] 			= $mb_sliders;
		$store_info['mb_title_img'] 		= $shop_base['shop_banner'];
		$store_info['member_id'] 			= Perm::$userId;
		$store_info['store_avatar'] 		= $shop_base['shop_logo'];
		$store_info['store_collect'] 		= $shop_base['shop_collect'];
		$store_info['store_credit_text'] 	= sprintf('描述: %.2f, 服务: %.2f, 物流: %.2f', $shop_base['shop_desc_scores'], $shop_base['com_service_scores'], $shop_base['shop_send_scores'])  ;		//描述: 5.0, 服务: 5.0, 物流: 5.0
		$store_info['shop_id'] 				= $shop_base['shop_id'];
		$store_info['store_name'] 			= $shop_base['shop_name'];
		$store_info['user_id'] 				= $shop_base['user_id'];


		$data['rec_goods_list'] 		= $rec_goods_list;
		$data['rec_goods_list_count'] 	= count($rec_goods_list);
		$data['store_info'] 			= $store_info;

		$this->data->addBody(-140, $data);

	}

	/**
	 *
	 * wap 获取店铺满送 限时
	 */

	public function getShopPromotion()
	{
		$mansong 	= array();
		$xianshi 	= array();
		$promotion  = array();

		$discountBaseModel = new Discount_BaseModel();
		$manSongBaseModel  = new ManSong_BaseModel();


		$shop_id = request_int('shop_id');

		//限时
		$discount_list = $discountBaseModel->getDiscountActList( array('discount_state' => Discount_BaseModel::NORMAL, 'shop_id' => $shop_id) );
		$xianshi = pos($discount_list['items']);

		//满送
		$mansong_list = $manSongBaseModel->getManSongActList( array( 'mansong_state' => ManSong_BaseModel::NORMAL, 'shop_id' => $shop_id ) );
		$mansong_list_f = pos( $mansong_list['items'] );

		if($mansong_list_f)
		{
			$mansong = $manSongBaseModel->getManSongActItem( array('shop_id' => $shop_id, 'mansong_id' => $mansong_list_f['mansong_id']) );
		}
		else
		{
			$mansong = $mansong_list_f;
		}

		$promotion['mansong'] = $mansong;
		$promotion['xianshi'] = $xianshi;

		$data['promotion'] = $promotion;

		$this->data->addBody(-140, $data);
	}

	/**
	 * 店铺详细信息
	 */
	public function getStoreIntro()
	{
		$data = array();
		$shop_id = request_int('shop_id');
		$shop_base = $this->shopBaseModel->getShopDetail($shop_id);

		$data['store_info'] = $shop_base;
		$this->data->addBody(-140, $data);
	}

}