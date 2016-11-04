<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_GoodsCtl extends Seller_Controller
{
	public $shopBaseModel;
	public $goodsTypeModel;
	public $goodsCatModel;
	public $goodsBrandModel;
	public $goodsSpecModel;
	public $goodsPropertyValueModel;
	public $goodsSpecValueModel;
	public $goodsCommonModel;
	public $goodsBaseModel;
	public $goodsCommonDetailModel;
	public $shopGoodsCat;

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

		$this->shopBaseModel           = new Shop_BaseModel();
		$this->goodsTypeModel          = new Goods_TypeModel();
		$this->goodsCatModel           = new Goods_CatModel();
		$this->goodsBrandModel         = new Goods_BrandModel();
		$this->goodsSpecModel          = new Goods_SpecModel();
		$this->goodsPropertyValueModel = new Goods_PropertyValueModel();
		$this->goodsSpecValueModel     = new Goods_SpecValueModel();
		$this->goodsCommonModel        = new Goods_CommonModel();
		$this->goodsBaseModel          = new Goods_BaseModel();
		$this->goodsCommonDetailModel  = new Goods_CommonDetailModel();
		$this->shopGoodsCat            = new Shop_GoodsCat();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function add()
	{
		$cat_id    = request_int('cat_id');
		$action    = request_string('action');
		$common_id = request_int('common_id');

		if ($cat_id)
		{
			if (empty($common_id))
			{
				$data = $this->goodsTypeModel->getTypeInfoByPublishGoods($cat_id);
				$this->view->setMet('goodsInfoManage');
			}
			else
			{
				return $this->editGoods();
			}
		}
		else if (!empty($action) && $action == 'goodsImageManage')
		{
			$common_id = request_int('common_id');

			$data = $this->goodsImageManage($common_id);

			$common_data  = $data['common_data'];
			$common_image = $common_data['common_image'];

			if (!empty($data['color']))
			{
				$color = $data['color'];
			}

			$this->view->setMet('goodsImageManage');
		}
		else
		{
			$Goods_CatModel = new Goods_CatModel();
			$cat_rows       = $Goods_CatModel->getCatTreeData(0, false, 0, true);
		}
		include $this->view->getView();
	}

	public function online()
	{
		$met               = request_string('met');
		$action            = request_string('action');
		$key               = request_string('key');
		$Goods_CommonModel = new Goods_CommonModel();

		$cront_row = array('shop_id' => Perm::$shopId, 'common_state' => Goods_CommonModel::GOODS_STATE_NORMAL);
		if (!empty($key) && isset($key))
		{
			$cront_row['common_name:like'] = '%' . $key . '%';
		}

		$Yf_Page = new Yf_Page();
		$row     = $Yf_Page->listRows;
		$offset  = request_int('firstRow', 0);
		$page    = ceil_r($offset / $row);

		$goods_rows = $Goods_CommonModel->getCommonNormal($cront_row, array('common_id' => 'DESC'), $page, $row);
        $common_id_rows = array_column($goods_rows['items'], 'common_id');
        if(!empty($common_id_rows))
        {
            $goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
        }
		$goods      = $Goods_CommonModel->getRecommonRow($goods_rows);
		if ('e' == $this->typ)
		{
			if ($action == 'edit_goods')
			{
				return $this->editGoods();
			}
			else if ($action == 'edit_image')
			{
				$common_id = request_int('common_id');
				$data      = $this->goodsImageManage($common_id);

				if (!empty($data['color']))
				{
					$color = $data['color'];
				}

				$common_data  = $data['common_data'];
				$common_image = $common_data['common_image'];

				$this->view->setMet('goodsImageManage');
				return include $this->view->getView();
			}
			else
			{
				$Yf_Page->totalRows = $goods_rows['totalsize'];
				$page_nav           = $Yf_Page->prompt();

				include $this->view->getView();
			}
		}
		else
		{
			$this->data->addBody('', $goods_rows);
		}
	}

	public function offline()
	{
		$met               = request_string('met');
		$action            = request_string('action');
		$Goods_CommonModel = new Goods_CommonModel();
		$cront_row         = array('shop_id' => Perm::$shopId);

		$key = request_string('key');
		if (!empty($key) && isset($key))
		{
			$cront_row = array('common_name:like' => '%' . $key . '%');
		}


		$Yf_Page = new Yf_Page();
		$row     = $Yf_Page->listRows;
		$offset  = request_int('firstRow', 0);
		$page    = ceil_r($offset / $row);

		$goods_rows = $Goods_CommonModel->getCommonOffline($cront_row, array('common_id' => 'DESC'), $page, $row);
		$common_id_rows = array_column($goods_rows['items'], 'common_id');
		if(!empty($common_id_rows))
		{
			$goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
		}
		$goods      = $Goods_CommonModel->getRecommonRow($goods_rows);

		if ('e' == $this->typ)
		{
			if ($action == 'edit_goods')
			{
				return $this->editGoods();
			}
			else
			{
				$Yf_Page->totalRows = $goods_rows['totalsize'];
				$page_nav           = $Yf_Page->prompt();

				$this->view->setMet('online');
				include $this->view->getView();
			}
		}
		else
		{
			$this->data->addBody('', $goods_rows);
		}
	}

	public function lockup()
	{
		$met               = request_string('met');
		$action            = request_string('action');
		$Goods_CommonModel = new Goods_CommonModel();

		$cront_row = array('shop_id' => Perm::$shopId);

		$key = request_string('key');
		if (!empty($key) && isset($key))
		{
			$cront_row = array('common_name:like' => '%' . $key . '%');
		}

		$Yf_Page = new Yf_Page();
		$row     = $Yf_Page->listRows;
		$offset  = request_int('firstRow', 0);
		$page    = ceil_r($offset / $row);

		$goods_rows = $Goods_CommonModel->getCommonIllegal($cront_row, array('common_id' => 'DESC'), $page, $row);
		$common_id_rows = array_column($goods_rows['items'], 'common_id');
		if(!empty($common_id_rows))
		{
			$goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
		}
		$goods      = $Goods_CommonModel->getRecommonRow($goods_rows);

		if ('e' == $this->typ)
		{
			if ($action == 'edit_goods')
			{
				return $this->editGoods();
			}
			else
			{
				$Yf_Page->totalRows = $goods_rows['totalsize'];
				$page_nav           = $Yf_Page->prompt();

				$this->view->setMet('online');
				include $this->view->getView();
			}
		}
		else
		{
			$this->data->addBody('', $goods_rows);
		}
	}

	public function verify()
	{
		$met               = request_string('met');
		$action            = request_string('action');
		$Goods_CommonModel = new Goods_CommonModel();

		$cront_row = array('shop_id' => Perm::$shopId);

		$key = request_string('key');
		if (!empty($key) && isset($key))
		{
			$cront_row = array('common_name:like' => '%' . $key . '%');
		}

		$Yf_Page = new Yf_Page();
		$row     = $Yf_Page->listRows;
		$offset  = request_int('firstRow', 0);
		$page    = ceil_r($offset / $row);

		$goods_rows = $Goods_CommonModel->getCommonVerifyWaiting($cront_row, array('common_id' => 'DESC'), $page, $row);
		$common_id_rows = array_column($goods_rows['items'], 'common_id');
		if(!empty($common_id_rows))
		{
			$goods_detail_rows = $Goods_CommonModel->getGoodsDetailRows($common_id_rows);
		}
		$goods      = $Goods_CommonModel->getRecommonRow($goods_rows);

		if ('e' == $this->typ)
		{
			if ($action == 'edit_goods')
			{
				return $this->editGoods();
			}
			else
			{
				$Yf_Page->totalRows = $goods_rows['totalsize'];
				$page_nav           = $Yf_Page->prompt();

				$this->view->setMet('online');
				include $this->view->getView();
			}
		}
		else
		{
			$this->data->addBody('', $goods_rows);
		}
	}

	public function appointment()
	{
		include $this->view->getView();
	}


	public function addOrEditShopGoods()
	{
		$common_id = request_int('common_id');  //区分是修改商品，还是添加商品
		$action    = request_string('action');

		$shop_base = $this->shopBaseModel->getBase(Perm::$shopId);
		$shop_base = current($shop_base);

		$common_data['shop_status'] = $shop_base['shop_status'];  //插入店铺状态

		$goods_cat_base = $this->goodsCatModel->getCat(request_int('cat_id'));
		$goods_cat_base = current($goods_cat_base);

		$shop_cat_id         = ',';
		/*$shop_goods_cat_base = $this->shopGoodsCat->getByWhere(array('shop_id' => $shop_base['shop_id']));*/
		$sgcate_id = request_row('sgcate_id');
		if (empty($sgcate_id))
		{
			$shop_cat_id .= ',';
		}
		else
		{
			foreach ($sgcate_id as $key => $val)
			{
				$shop_cat_id .= $val . ',';
			}
		}
		
		$common_data['shop_id']    	= $shop_base['shop_id'];                        //店铺id
		$common_data['shop_name']   = $shop_base['shop_name'];                        //店铺名称
		$common_data['shop_cat_id'] = $shop_cat_id;                                //店铺分类id
		$common_data['type_id']     = $goods_cat_base['type_id'];                    //类型id

		$common_data['shop_self_support']     = $shop_base['shop_self_support'] == Shop_BaseModel::SELF_SUPPORT_TRUE ? 1 : 0;     //是否自营

		$common_data['cat_id']                = request_string('cat_id');                    //商品分类id
		$common_data['cat_name']              = request_string('cat_name');                    //商品分类
		$common_data['common_name']           = request_string('name');                        //商品名称
		$common_data['brand_id']              = request_int('brand_id');                        //品牌id
		$common_data['brand_name']            = request_string('brand_name');                    //品牌名称
		$common_data['common_promotion_tips'] = request_string('promotion_tips');                //商品广告词

		$common_data['common_image']        = request_string('imagePath');                    //商品主图
		$common_data['common_price']        = request_float('price');                        //商品价格
		$common_data['common_market_price'] = request_float('market_price');                //市场价
		$common_data['common_cost_price']   = request_float('cost_price');                    //成本价

		$common_data['common_stock'] = request_int('stock');                            //商品库存
		$common_data['common_alarm'] = request_int('alarm');                            //库存预警值
		$common_data['common_code']  = request_string('code');                        //商家编号

		$common_data['common_formatid_top']    = request_int('formatid_top');                    //顶部关联板式
		$common_data['common_formatid_bottom'] = request_int('formatid_bottom');                //底部关联板式

		$common_data['common_cubage']    = request_float('cubage');                        //商品重量
		$common_data['common_is_return'] = request_int('is_return');                        //7天无理由退货

		$common_data['common_service']      = request_string('service');                    //售后服务
		$common_data['common_packing_list'] = request_string('packing_list');                //包装清单

		$common_data['common_state']        = request_int('state');                            //
		$common_data['common_is_recommend'] = request_string('is_recommend');                //商品推荐
		$common_data['common_add_time']     = date('Y-m-d H:i:s', time());                    //商品添加时间
		
		$is_limit = request_int('is_limit');
		if ( $is_limit )
		{
			$common_data['common_limit']        = request_int('limit');                       //限购
		}

		$common_data['common_invoices'] = request_int('is_invoice');                    //商品添加时间

		//读取店铺关联的消费者保障服务
		$this->goodsCommonModel->getShopContract($common_data);


		$common_property = request_string('property');
		$spec_name       = request_string('spec_name');

		$province_id = request_string('province_id');
		$city_id     = request_string('city_id');
		if ($province_id)
		{
			$common_location   = array();
			$common_location[] = $province_id;

			if ($city_id)
			{
				$common_location[] = $city_id;
			}

			$common_data['common_location'] = $common_location;                                //商品所在地
		}

		//售卖区域 0=>固定运费 1=>选择售卖区域
		$transport_type_id = request_int('transport_type_id');

		if (empty($transport_type_id))
		{
			$common_data['common_freight'] = request_string('g_freight');                    //运费
		}
		else
		{
			$common_data['transport_type_id']   = request_int('transport_type_id');                //transport_type_id
			$common_data['transport_type_name'] = request_string('transport_type_name');        //transport_type_name
		}

		//本店分类
		$sgcate_id = request_row('sgcate_id');

		if (!empty($sgcate_id))
		{
			$common_data['shop_goods_cat_id'] = $sgcate_id;                                //shop_goods_cat_id
		}

		/* 只有可发布虚拟商品才会显示 S */
		$is_gv = request_int('is_gv');

		if ($is_gv == 1)
		{
			$common_data['common_is_virtual']     = $is_gv;                                            //虚拟商品
			$common_data['common_virtual_date']   = request_string('g_vindate');                        //虚拟商品有效期
			$common_data['common_virtual_refund'] = request_int('g_vinvalidrefund');                    //支持过期退款
		}

		if (!empty($common_property))
		{
			$common_data['common_property'] = $common_property;                    //属性
		}

		if ($common_data['common_state'] == Goods_CommonModel::GOODS_STATE_TIMING)
		{
			$starttime   = request_string('starttime');
			$time_hour   = request_string('hour');
			$time_minute = request_string('minute');
			$sell_time   = "$starttime $time_hour:$time_minute:00";

			$common_data['common_sell_time'] = date('Y-m-d H:i:s', strtotime($sell_time));            //上架时间
		}

		$spec_val                         = request_row('spec_val');

		$diff_spec_array = array_diff_key($spec_name, $spec_val);
		$flag_spec = empty($diff_spec_array);

		if (!empty($spec_name) && $flag_spec)
		{

			$common_data['common_spec_name']  = $spec_name;                        //规格名称
			$common_data['common_spec_value'] = $spec_val;                        //规格名称
		}

		//判断发布的的商品是否需要审核
		if ($common_data['common_state'] == Goods_CommonModel::GOODS_STATE_NORMAL)
		{
			$webConfigModel    = new Web_ConfigModel();
			$goods_verify_flag = $webConfigModel->getConfig('goods_verify_flag');
			$goods_verify_flag = pos($goods_verify_flag);
			if ($goods_verify_flag['config_value'] == 0)    //商品是否需要审核 0 不需要
			{
				$common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
			}
			else
			{
				$common_data['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
			}
		}

		//关联版式
		$formatid_top  = request_int('formatid_top');
		$format_bottom = request_int('formatid_bottom');

		if (!empty($formatid_top))
		{
			$common_data['common_formatid_top'] = $formatid_top;
		}

		if (!empty($format_bottom))
		{
			$common_data['common_formatid_bottom'] = $format_bottom;
		}

		$this->goodsCommonModel->sql->startTransactionDb();

		if ($action == 'edit')
		{
			$data['action'] = 'edit'; // 返回前端数据
			$this->goodsCommonModel->editCommon($common_id, $common_data);
		}
		else
		{
			$common_id = $this->goodsCommonModel->addCommon($common_data, true);
		}
		
		/*********************向映射表添加数据*********************/
//		$this->goodsCommonModel->createMapRelation($common_id, $common_data);

		if ($common_id && $this->goodsCommonModel->sql->commitDb())
		{

			$body      = request_string('body');
			$spec_data = request_string('spec');

			//内容详情
			if (!empty($body))
			{
				$common_detail_data['common_id']   = $common_id;
				$common_detail_data['common_body'] = $body;

				if ($action == 'edit')
				{
					unset($common_detail_data['common_id']);
					$this->goodsCommonDetailModel->editCommonDetail($common_id, $common_detail_data);
				}
				else
				{
					$this->goodsCommonDetailModel->addCommonDetail($common_detail_data);
				}

			}

			//库存配置
			//判断  修改的只修改
			//取出已有的所有goods_id
			$goods_base = $this->goodsBaseModel->getByWhere(array('common_id' => $common_id));
			if (!empty($goods_base))
			{
				$goods_base_ids = array_column($goods_base, 'goods_id');
			}

			$goods_data['cat_id']               = $common_data['cat_id'];                    //商品分类id
			$goods_data['common_id']            = $common_id;                                //商品公共表id
			$goods_data['shop_id']              = $common_data['shop_id'];                    //shop_id
			$goods_data['shop_name']            = $common_data['shop_name'];                //shop_name
			$goods_data['goods_name']           = $common_data['common_name'];                //商品名称
			$goods_data['goods_promotion_tips'] = $common_data['common_promotion_tips'];    //促销提示
			$goods_data['goods_is_recommend']   = $common_data['common_is_recommend'];        //商品推荐
			$goods_data['goods_image']          = $common_data['common_image'];                //商品主图


			//加入goods_id 冗余数据

			$goods_ids = array();
			$color_ids = array();
			$edit_goods_ids = array();
			$retain_flag = false;

			if (!empty($spec_data) && $flag_spec)
			{
				//读取颜色规格值
				$goodsSpecValueModel  = new Goods_SpecValueModel();
				$spec_value_color_ids = $goodsSpecValueModel->getSpecValueByColor();

				//判断前台是否有老数据
				//过滤无用垃圾数据
				$edit_goods_ids = array_column($spec_data, 'goods_id');

				//判断有无修改goods_id 如果没有修改goods_id 则要删除之前goods_id 不符合现在标准
				$edit_goods_ids_string = implode("", $edit_goods_ids);
				if ( empty($edit_goods_ids_string) && $action == 'edit' )
				{
					$retain_flag = true;
					$goods_base_ids = array_values($goods_base_ids);
					$retain_f_goods_id = $goods_base_ids[0];
					unset($goods_base_ids[0]);
				}

				//删除无用垃圾数据
				$remove_goods_ids =  array();
				foreach ( $goods_base_ids as $old_id )
				{
					if ( !in_array($old_id, $edit_goods_ids) )
					{
						$remove_goods_ids[] = $old_id;
					}
				}

				if ( !empty($remove_goods_ids) )
				{
					$this->goodsBaseModel->removeBase($remove_goods_ids);
				}


				foreach ($spec_data as $key => $val)
				{
					$goods_data['goods_price']        = $val['price'];                            //商品价格
					$goods_data['goods_market_price'] = $val['market_price'];                        //市场价
					$goods_data['goods_stock']        = $val['stock'];                            //商品库存
					$goods_data['goods_alarm']        = $val['alarm'];                            //库存预警值
					$goods_data['goods_code']         = $val['sku'];                                //商家编号货号
					$goods_data['goods_spec']         = array($key => $val['sp_value']);        //商品规格-JSON存储

					if ( !empty($val['color']) )
					{
						$goods_data['color_id']       = $val['color'];                                //颜色
					}

					//判断是修改数据还是新增数据
					if ( !empty($val['goods_id']) )
					{
						$goods_id = $val['goods_id'];
						$this->goodsBaseModel->editBase($goods_id, $goods_data, false);
					}
					else
					{
						if ( $retain_flag )
						{
							$goods_id = $this->goodsBaseModel->editBase($retain_f_goods_id, $goods_data, false);
							$retain_flag = false;
						}
						else
						{
							$goods_id = $this->goodsBaseModel->addBase($goods_data, true);
						}
					}

					//color_id 冗余数据
					foreach ($val['sp_value'] as $k => $v)
					{
						if (in_array($k, $spec_value_color_ids) && !in_array($k, $color_ids))
						{
							$color_ids[] = $k;
							$goods_ids[] = array(
								'goods_id' => $goods_id,
								'color_id' => $k
							);
							break;
						}
					}
				}

			}
			else
			{
				$goods_data['goods_price']        = $common_data['common_price'];                //商品价格
				$goods_data['goods_market_price'] = $common_data['common_market_price'];        //市场价
				$goods_data['goods_stock']        = $common_data['common_stock'];                //商品库存
				$goods_data['goods_alarm']        = $common_data['common_alarm'];                //库存预警值
				$goods_data['goods_code']         = $common_data['common_code'];                //商家编号货号

				if ($action == 'edit')
				{
					$goods_id = pos($goods_base_ids);
					$this->goodsBaseModel->editBase($goods_id, $goods_data, false);
				}
				else
				{
					$goods_id = $this->goodsBaseModel->addBase($goods_data, true);
				}
			}


			if (empty($goods_ids))
			{
				$goods_ids = array(
					'goods_id' => $goods_id,
					'color' => 0
				);
			}

			$edit_common_data['goods_id'] = $goods_ids;
			$test_id                      = $this->goodsCommonModel->editCommon($common_id, $edit_common_data);

			$data['common_id'] = $common_id;
			$this->data->addBody(-140, $data, _('success'), 200);
		}
		else
		{
			$this->goodsCommonModel->sql->rollBackDb();
			$this->data->addBody(-140, array(), _('failure'), 250);
		}
	}

	/*
	 * 上传商品图片 -- 第三步
	 * */
	public function goodsImageManage($common_id)
	{
		$data = array();
		
		$common_data         = $this->goodsCommonModel->getCommon($common_id);
		$common_data         = pos($common_data);
		$data['common_data'] = $common_data;

		$readonly_data = $this->goodsSpecModel->getByWhere(array('spec_readonly' => 1));
		$readonly_data = pos($readonly_data);


		//取出颜色
		$Goods_ImagesModel = new Goods_ImagesModel();
		$goods_images      = $Goods_ImagesModel->getByWhere(array('common_id' => $common_id));


		if (!empty($common_data['common_spec_value']))
		{
			//spec_id = 1 => 是系统默认只读属性: 颜色
			if (!empty($common_data['common_spec_value'][$readonly_data['spec_id']]))
			{
				$color         = $common_data['common_spec_value'][$readonly_data['spec_id']];
				$data['color'] = $color;

				foreach ($color as $key => $val)
				{
					foreach ($goods_images as $k => $v)
					{
						if ($key == $v['images_color_id'])
						{
							$color_images[$key][] = $v;
							unset($goods_images[$k]);
						}
					}
				}
			}
		}
		if (empty($color_images))
		{
			foreach ($goods_images as $key => $val)
			{
				if ($val['images_is_default'] == Goods_ImagesModel::IMAGE_DEFAULT)
				{
					$image_default = $goods_images[$key];
					unset($goods_images[$key]);
					array_unshift($goods_images, $image_default);

					break;
				}
			}
			$data['goods_images'] = array_values($goods_images);
		}
		else
		{
			foreach ($color_images as $key => $val)
			{
				foreach ($val as $k => $v)
				{
					if ($v['images_is_default'] == Goods_ImagesModel::IMAGE_DEFAULT)
					{
						$image_default = $color_images[$key][$k];
						unset($color_images[$key][$k]);
						array_unshift($color_images[$key], $image_default);

						break;
					}
				}
			}
			$data['color_images'] = $color_images;
		}
		return $data;
	}

	/*
	 *	保存图片
	 * */
	public function saveGoodsImage()
	{
		$image_list = request_row('image');
		$common_id  = request_int('common_id');
		$is_color   = request_int('is_color');

		if (!empty($image_list))
		{

			$goodsImagesModel = new Goods_ImagesModel();

			$images     = $goodsImagesModel->getByWhere(array('common_id' => $common_id));
			$images_ids = array_column($images, 'id');
			$goodsImagesModel->removeImages($images_ids);

			$image_data['shop_id']   = Perm::$shopId;
			$image_data['common_id'] = $common_id;

			foreach ($image_list as $key => $val)
			{
				foreach ($val as $k => $v)
				{
					if (!empty($v['name']))
					{
						if (!empty($is_color))
						{
							$image_data['images_color_id'] = $key;
						}
						$image_data['images_image']        = $v['name'];
						$image_data['images_displayorder'] = $v['displayorder'];
						$image_data['images_is_default']   = $v['default'];

						$flag    = $goodsImagesModel->addImages($image_data, true);
						$flags[] = $flag;
					}
				}
			}
			header("location: " . Yf_Registry::get('url') . '?ctl=Seller_Goods&met=online&typ=e');
		}

	}

	public function editGoods()
	{
		$common_id = request_int('common_id');

		$common_data = $this->goodsCommonModel->listByWhere( array('shop_id' => Perm::$shopId, 'common_id' => $common_id) );
		if (empty($common_data))
		{
			return;
		}
		$common_data = pos($common_data['items']);

		$common_detail_data = $this->goodsCommonDetailModel->getCommonDetail($common_data['common_id']);
		$common_detail_data = pos($common_detail_data);


		$common_sell_time_d = strtotime($common_data['common_sell_time']);

		if ( $common_sell_time_d && $common_sell_time_d > 0 )
		{
			//读取上架时间
			$common_sell_time[0]             = date('Y-m-d', $common_sell_time_d);
			$common_sell_time[1]             = date('H', $common_sell_time_d);
			$common_sell_time[2]             = date('i', $common_sell_time_d);
			$common_data['common_sell_time'] = $common_sell_time;
		}
		else
		{
			unset($common_data['common_sell_time']);
		}
		$cat_id   = $common_data['cat_id'];
		$cat_base = $this->goodsCatModel->getCat($cat_id);
		if (empty($cat_base))
		{
			include $this->view->getTplPath() . '/' . 'error.php';
		}


		//判断是否修改商品分类
		$action = request_string('action');

		if (!empty($action) && $action == 'edit_goods_cat')
		{
			$cat_id = request_int('cat_id');
		}

		$data            = $this->goodsTypeModel->getTypeInfoByPublishGoods($cat_id); //商品属性、规格等
		$goods_base_data = $this->goodsBaseModel->getByWhere(array('common_id' => $common_data['common_id'])); //取出商品规格值

		$this->view->setMet('goodsInfoManage');
		include $this->view->getView();
	}

	public function editGoodsCommon()
	{

		$shop_id = Perm::$shopId;

		$Goods_CommonModel = new Goods_CommonModel();

		$goods_common_id_rows = request_row('chk');

		foreach ($goods_common_id_rows as $key => $value)
		{
			$goods_common_id = $value;
			$data_goods      = $Goods_CommonModel->getOne($goods_common_id);
			if ($data_goods['shop_id'] == $shop_id)
			{
				if (request_string('act') == 'down')
				{
					$flag = $Goods_CommonModel->editCommon($goods_common_id, array('common_state' => Goods_CommonModel::GOODS_STATE_OFFLINE));
				}
				elseif (request_string('act') == 'up')
				{
					$flag = $Goods_CommonModel->editCommon($goods_common_id, array('common_state' => Goods_CommonModel::GOODS_STATE_NORMAL));
				}
				elseif (request_string('act') == 'del')
				{
					$flag = $Goods_CommonModel->removeCommon($goods_common_id);
				}
			}
		}
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

	public function deleteGoodsCommon()
	{
		$id      = request_int('id');
		$shop_id = Perm::$shopId;
		$data    = $this->goodsCommonModel->getOne($id);
		if ($data['shop_id'] == $shop_id)
		{
			$flag = $this->goodsCommonModel->removeCommon($id);
		}
		else
		{
			$msg    = _('failure1');
			$status = 250;
		}
		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure2');
			$status = 250;
		}
		$data_re['id'] = $id;
		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	public function deleteGoodsCommonRows()
	{
		$id         = request_row('id');
		$shop_id    = Perm::$shopId;
		$data       = $this->goodsCommonModel->getByWhere(array(
															  'common_id:in' => $id,
															  'shop_id' => $shop_id
														  ));
		$common_ids = array_values(array_column($data, 'common_id'));
		$flag       = $this->goodsCommonModel->removeCommon($common_ids);

		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure2');
			$status = 250;
		}
		$data_re['id'] = $id;
		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	//关联板式
	public function format()
	{
		$Goods_FormatModel   = new Goods_FormatModel();
		$cond_row            = array();
		$cond_row['shop_id'] = Perm::$shopId;
        $key = request_string('key');
		if ($key)
		{
			$cond_row['name:like'] = '%' . $key . '%';
		}
//        $Yf_Page = new Yf_Page();
//        $row     = $Yf_Page->listRows;
//        $offset  = request_int('firstRow', 0);
//        $page    = ceil_r($offset / $row);
		$Yf_Page = new Yf_Page();
		//$Yf_Page->listRows = 2;
		$rows   = $Yf_Page->listRows;
		$offset = request_int('firstRow', 0);
		$page   = ceil_r($offset / $rows);

		$format_rows = $Goods_FormatModel->getFormatList($cond_row, array(), $page, $rows);
		$data        = $format_rows['items'];

		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				if ($value['position'] == $Goods_FormatModel::FORMAT_POSITION_TOP)
				{
					$data[$key]['position_name'] = _('顶部');
				}
				elseif ($value['position'] == $Goods_FormatModel::FORMAT_POSITION_BOTTOM)
				{
					$data[$key]['position_name'] = _('底部');
				}
			}
		}
		$Yf_Page->totalRows = $format_rows['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		include $this->view->getView();
	}

	public function addformat()
	{
		$Goods_FormatModel = new Goods_FormatModel();
		$act               = request_string('act');
		if ($act == 'edit')
		{
			$id              = request_int('id');
			$data            = $Goods_FormatModel->getOne($id);
			$data['content'] = addslashes($data['content']);
		}
		include $this->view->getView();
	}

	public function addFormatRow()
	{
		$Goods_FormatModel    = new Goods_FormatModel();
		$add_data             = array();
		$add_data['name']     = request_string('name');
		$add_data['position'] = request_string('position');
		$add_data['content']  = request_string('content');
		$add_data['shop_id']  = Perm::$shopId;
		$id                   = $Goods_FormatModel->addFormat($add_data, true);
		if ($id)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}
		$re       = array();
		$re['id'] = $id;
		$this->data->addBody(-140, $re, $msg, $status);
	}

	public function editFormatRow()
	{
		$Goods_FormatModel     = new Goods_FormatModel();
		$edit_data             = array();
		$id                    = request_int('id');
		$edit_data['name']     = request_string('name');
		$edit_data['position'] = request_string('position');
		$edit_data['content']  = request_string('content');
		$edit_data['shop_id']  = Perm::$shopId;
		$flag                  = $Goods_FormatModel->editFormat($id, $edit_data);
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

	//删除版式
	public function deleteGoodsFormat()
	{
		$id                = request_int('id');
		$Goods_FormatModel = new Goods_FormatModel();
		$flag              = $Goods_FormatModel->removeFormat($id);
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

	public function deleteGoodsFormatRows()
	{
		$ids               = request_row('id');
		$Goods_FormatModel = new Goods_FormatModel();
		$flag              = $Goods_FormatModel->removeFormat($ids);
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

	public function catListManage ()
	{
		include $this->view->getView();
	}
}

?>