<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_BaseModel extends Goods_Base
{

	const GOODS_UP   = 1;//上架
	const GOODS_DOWN = 2;//下架

	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/**
	 * 读取分页列表
	 *
	 * @param  int $goods_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseListByCommonId($common_id, $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row = array('common_id' => $common_id);

		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	public function getBaseByCommonId($common_id, $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row = array('common_id' => $common_id);

		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getBaseSpecByCommonId($common_id)
	{
		$cond_row = array('common_id' => $common_id);
		$res      = $this->getByWhere($cond_row);
		foreach ($res as $key => $val)
		{
			$data[$key] = current($val['goods_spec']);
		}

		return $data;

	}

	public function getGoodsIdByCommonId($common_id)
	{
		if (is_array($common_id))
		{
			$cond_row = array('common_id:in' => $common_id);

		}
		else
		{
			$cond_row = array('common_id' => $common_id);

		}

		return $this->getKeyByWhere($cond_row);
	}
	
	public function getGoodsListByGoodId($goods_id)
	{
		if (is_array($goods_id))
		{
			$cond_row = array('goods_id:in' => $goods_id);

		}
		else
		{
			$cond_row = array('goods_id' => $goods_id);

		}

		return $this->getByWhere($cond_row);
	}
	
	public function getGoodsDetail($cond_row)
	{
		return $this->getOneByWhere($cond_row);
	}

	public function getGoodsDetailss($cond_row)
	{
		return $this->getByWhere($cond_row);
	}

	public function getGoodsDetailByGoodId($goods_id)
	{
		return $this->getOne($goods_id);
	}

	/**
	 * 店铺查商品
	 *
	 * @author Zhuyt
	 */
	public function getGoodsListByShopId($shop_id, $order_row, $page, $rows)
	{
		
		if (is_array($shop_id))
		{
			$cond_row = array('shop_id:in' => $shop_id);

		}
		else
		{
			$cond_row = array('shop_id' => $shop_id);

		}

		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 删除商品库存
	 *
	 * @author Zhuyt
	 */
	public function delStock($goods_id, $num)
	{
		$goods_base    = $this->getOne($goods_id);
		$edit_base_num = $goods_base['goods_stock'] - $num;

		if($edit_base_num < 0)
		{
			return 'no_stock' ;
		}
		$edit_base_row = array('goods_stock' => $edit_base_num);
		$flag          = $this->editBase($goods_id, $edit_base_row, false);

		if ($flag)
		{
			$Goods_CommonModel = new Goods_CommonModel();
			$common_base       = $Goods_CommonModel->getOne($goods_base['common_id']);

			//查询此件商品是否为团购商品
			$GroupBuy_BaseModel = new GroupBuy_BaseModel();
			$group_buy_row = array(
				'common_id' => $goods_base['common_id'],
				'groupbuy_state'=>GroupBuy_BaseModel::NORMAL,
				'groupbuy_starttime:<=' => get_date_time(),
				'groupbuy_endtime:>=' => get_date_time(),
			);
			$gruop = $GroupBuy_BaseModel->getOneByWhere($group_buy_row);
			if($gruop)
			{
				$edit_gruop_row['groupbuy_buyer_count'] = 1;
				$edit_gruop_row['groupbuy_buy_quantity'] = $num;
				$edit_gruop_row['groupbuy_virtual_quantity'] = $num;
				$GroupBuy_BaseModel->editGroupBuy($gruop['groupbuy_id'],$edit_gruop_row,true);
			}

			$edit_common_num   = $common_base['common_stock'] - $num;
			$edit_common_row   = array('common_stock' => $edit_common_num);
			$res               = $Goods_CommonModel->editCommon($goods_base['common_id'], $edit_common_row, false);
			fb($edit_common_row);

			if($goods_base['goods_alarm'] >= $edit_base_num)
			{
				//查找店铺信息
				$Shop_BaseModel = new Shop_BaseModel();
				$shop_base = $Shop_BaseModel->getOne($common_base['shop_id']);
				$message = new MessageModel();
				$message->sendMessage('goods are not in stock',$shop_base['user_id'], $shop_base['user_name'], $order_id = NULL, $shop_name = NULL, 1, 1, $end_time = Null,$goods_base['common_id'],$goods_id);
			}

		}
		else
		{
			$res = false;
		}


		return $res;

	}

	/**
	 * 返回商品库存(取消订单后根据订单商品id返回商品库存)
	 *
	 * @author Zhuyt
	 */
	public function returnGoodsStock($order_goods_id)
	{
		$Order_GoodsModel  = new Order_GoodsModel();
		$Goods_CommonModel = new Goods_CommonModel();
		if (is_array($order_goods_id))
		{
			foreach ($order_goods_id as $key => $val)
			{
				$order_goods_base = $Order_GoodsModel->getOne($val);
				$goods_id         = $order_goods_base['goods_id'];
				$num              = $order_goods_base['order_goods_num'];

				$edit_base_row = array('goods_stock' => $num);
				$this->editBase($goods_id, $edit_base_row);

				$edit_common_row = array('common_stock' => $num);
				$Goods_CommonModel->editCommonTrue($order_goods_base['common_id'], $edit_common_row);

			}
		}
		else
		{
			$order_goods_base = $Order_GoodsModel->getOne($order_goods_id);
			$goods_id         = $order_goods_base['goods_id'];
			$num              = $order_goods_base['order_goods_num'];

			$edit_base_row = array('goods_stock' => $num);
			$flag          = $this->editBase($goods_id, $edit_base_row);

			$edit_common_row = array('common_stock' => $num);
			$Goods_CommonModel->editCommonTrue($order_goods_base['common_id'], $edit_common_row);

		}

	}

	/**
	 * 检测商品状态
	 * 1.商品id是否存在
	 * 2.商品base信息是否存在
	 * 3.商品是否上架，商品是否有库存，商品是否存在店铺id
	 * 4.商品common信息是否存在
	 * 5.common是否正常，common审核是否通过，店铺是否正常开启
	 * @author Zhuyt
	 */
	public function checkGoods($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getOne($goods_id);

		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || $goods_base['goods_stock'] <= 0 || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态与店铺状态
		if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}


		$data['goods_base'] = $goods_base;
		return $data;
	}

	/**
	 * 检测商品状态(检测是否存在店铺的信息)
	 *
	 * @author Zhuyt
	 */
	public function checkGoodsI($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || $goods_base['goods_stock'] <= 0 || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态
		if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL)
		{
			return null;
		}

		//店铺信息
		$Shop_BaseModel = new Shop_BaseModel();
		$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

		if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}


		$data['goods_base']  = $goods_base;
		$data['common_base'] = $goods_common;
		$data['shop_base']   = $shop_base;

		return $data;
	}

	/**
	 * 检测商品状态
	 * 1.商品id是否存在
	 * 2.商品base信息是否存在
	 * 3.商品是否上架，商品是否有库存，商品是否存在店铺id
	 * 4.商品common信息是否存在
	 * 5.common是否正常，common审核是否通过，店铺是否正常开启
	 * @author Zhuyt
	 */
	public function checkGoodsII($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getOne($goods_id);
		if (empty($goods_base))
		{
			return null;
		}

		//商品状态
		if ($goods_base['goods_is_shelves'] != Goods_BaseModel::GOODS_UP || !$goods_base['shop_id'])
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//common状态与店铺状态
		if ($goods_common['common_state'] != Goods_CommonModel::GOODS_STATE_NORMAL || $goods_common['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
		{
			return null;
		}


		$data['goods_base'] = $goods_base;
		return $data;
	}

	/**
	 * 获取商品信息(购物车中获取商品信息，此处获取的商品信息并不全面)
	 *
	 * @author Zhuyt
	 */
	public function getGoodsInfo($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		fb($goods_base);
		fb("商品信息goods_base");
		if (empty($goods_base))
		{
			return null;
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		//商品规格信息
		$spec_name  = $goods_common['common_spec_name'];
		$spec_value = $goods_common['common_spec_value'];

		if (is_array($spec_name) && $spec_name && $goods_base['goods_spec'])
		{
			$goods_spec = current($goods_base['goods_spec']);

			foreach ($goods_spec as $gpk => $gbv)
			{
				foreach ($spec_value as $svk => $svv)
				{
					$pk = array_search($gbv, $svv);

					if ($pk)
					{
						$goods_base['spec'][] = $spec_name[$svk] . ":" . $gbv;
					}
				}
			}

		}
		else
		{
			$goods_base['spec'] = array();
		}

		$goods_base['spec_str'] = '';
		if($goods_base['spec'])
		{
			foreach($goods_base['spec'] as $spk=>$spv)
			{
				$goods_base['spec_str'] = $goods_base['spec_str'] . $spv .'  ';
			}
		}

		fb($goods_base['groupbuy_info']);
		fb('团购');
		//团购
		if (!empty($goods_base['groupbuy_info']) && $goods_base['groupbuy_info']['groupbuy_price'] < $goods_base['goods_price'])
		{
			$goods_base['promotion_type']  = 'groupbuy';
			$goods_base['title']           = $goods_base['groupbuy_info']['groupbuy_name'];
			$goods_base['remark']          = $goods_base['groupbuy_info']['groupbuy_remark'];
			$goods_base['promotion_price'] = $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['upper_limit']     = $goods_base['groupbuy_info']['groupbuy_upper_limit'];

			unset($goods_base['groupbuy_info']);
		}

		//限时折扣
		if (!empty($goods_base['xianshi_info']) && $goods_base['xianshi_info']['discount_price'] < $goods_base['goods_price'])
		{
			if ($goods_base['goods_price'] > $goods_base['xianshi_info']['discount_price'])
			{
				$goods_base['promotion_type']  = 'xianshi';
				$goods_base['title']           = $goods_base['xianshi_info']['discount_name'];
				$goods_base['remark']          = $goods_base['xianshi_info']['discount_title'];
				$goods_base['promotion_price'] = $goods_base['xianshi_info']['discount_price'];
				$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['xianshi_info']['discount_price'];
				$goods_base['lower_limit']     = $goods_base['xianshi_info']['goods_lower_limit'];
				$goods_base['explain']         = $goods_base['xianshi_info']['discount_explain'];
			}

			unset($goods_base['xianshi_info']);
		}

		//验证是否赠送商品
		if (true)
		{
			$gift_array = array();
			if (!empty($gift_array))
			{
				$goods_base['have_gift'] = 'gift';
			}
			else
			{
				$goods_base['have_gift'] = '';
			}
		}

		//店铺信息
		//店铺信息
		if ($goods_base['shop_id'])
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

			if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
			{
				return null;
			}

		}
		else
		{
			return null;
		}

		$data['goods_base']  = $goods_base;
		$data['common_base'] = $goods_common;
		$data['shop_base']   = $shop_base;

		return $data;
	}

	/**
	 * 获取商品详细信息(商品详情中获取商品信息，此处获取的商品信息全面)
	 *
	 * @author Zhuyt
	 */
	public function getGoodsDetailInfoByGoodId($goods_id)
	{
		if ($goods_id <= 0)
		{
			return null;
		}

		//检测商品是否属于正常状态
		$goods_status = $this->checkGoodsII($goods_id);
		if (!$goods_status)
		{
			return null;
		}


		//获取商品信息及活动信息
		$goods_base = $this->getGoodsInfoAndPromotionById($goods_id);
		if (empty($goods_base))
		{
			return null;
		}
		//商品规格信息
		if (is_array($goods_base['goods_spec']))
		{
			$goods_base['goods_spec'] = current($goods_base['goods_spec']);
		}

		//获取商品Common信息
		$Goods_CommonModel = new Goods_CommonModel();
		$goods_common      = $Goods_CommonModel->getOne($goods_base['common_id']);
		if (empty($goods_common))
		{
			return null;
		}

		$spec_name  = $goods_common['common_spec_name'];
		$spec_value = $goods_common['common_spec_value'];

		//商品详情
		$Goods_CommonDetailModel = new Goods_CommonDetailModel();
		$goods_detail            = $Goods_CommonDetailModel->getOne($goods_base['common_id']);
		if ($goods_detail)
		{
			$goods_common['common_detail'] = $goods_detail['common_body'];
		}
		else
		{
			$goods_common['common_detail'] = '';
		}

		//商品common图片
		$image_common_cond                      = array();
		$image_common_cond['common_id']         = $goods_common['common_id'];
		$image_common_cond['images_is_default'] = Goods_ImagesModel::IMAGE_DEFAULT;
		$Goods_ImagesModel                      = new Goods_ImagesModel();

		$goods_common['common_spec_value_c'] = $goods_common['common_spec_value'];
		if (is_array($goods_common['common_spec_value']))
		{
			foreach ($goods_common['common_spec_value'] as $comvk => $comvv)
			{
				foreach ($comvv as $cvk => $cvv)
				{
					$image_common_cond['images_color_id'] = $cvk;
					$image_common_row                     = current($Goods_ImagesModel->getGoodsImage($image_common_cond));
					if ($image_common_row)
					{
						$goods_common['common_spec_value'][$comvk][$cvk] = sprintf('<img src="%s" title="%s" alt="%s"/>', image_thumb($image_common_row['images_image'],42,42),$cvv,$cvv);
						$goods_common['common_spec_value_color'][$cvk] = image_thumb($image_common_row['images_image'], 360, 360);
					}
				}

			}
		}

		//商品详细图片
		$image_cond                         = array();
		$image_cond['common_id']            = $goods_common['common_id'];
		$image_cond['images_color_id']      = $goods_base['color_id'];
		$image_order                        = array();
		$image_order['images_displayorder'] = 'ASC';
		$image_order['images_is_default'] = 'DESC';
		$image_row                          = array_values($Goods_ImagesModel->getGoodsImage($image_cond, $image_order));

		$goods_base['image_row'] = $image_row;

		//商品评论数
		$Goods_EvaluationModel   = new Goods_EvaluationModel();
		$countall                = $Goods_EvaluationModel->countEvaluation($goods_id);
		$goods_base['evalcount'] = $countall;

		//商品销售记录
		$Order_GoodsModel        = new Order_GoodsModel();
		$sale                    = $Order_GoodsModel->getGoodsSaleNum($goods_id);
		$goods_base['salecount'] = $sale;

		fb($goods_base['groupbuy_info']);
		fb('团购');
		//团购
		if (!empty($goods_base['groupbuy_info']) && $goods_base['groupbuy_info']['groupbuy_price'] < $goods_base['goods_price'])
		{
			$goods_base['promotion_type']  = 'groupbuy';
			$goods_base['title']           = $goods_base['groupbuy_info']['groupbuy_name'];
			$goods_base['remark']          = $goods_base['groupbuy_info']['groupbuy_remark'];
			$goods_base['promotion_price'] = $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['groupbuy_info']['groupbuy_price'];
			$goods_base['upper_limit']     = $goods_base['groupbuy_info']['groupbuy_upper_limit'];

			unset($goods_base['groupbuy_info']);
		}

		//限时折扣
		if (!empty($goods_base['xianshi_info']) && $goods_base['xianshi_info']['discount_price'] < $goods_base['goods_price'])
		{
			if ($goods_base['goods_price'] > $goods_base['xianshi_info']['discount_price'])
			{
				$goods_base['promotion_type']  = 'xianshi';
				$goods_base['title']           = $goods_base['xianshi_info']['discount_name'];
				$goods_base['remark']          = $goods_base['xianshi_info']['discount_title'];
				$goods_base['promotion_price'] = $goods_base['xianshi_info']['discount_price'];
				$goods_base['down_price']      = $goods_base['goods_price'] - $goods_base['xianshi_info']['discount_price'];
				$goods_base['lower_limit']     = $goods_base['xianshi_info']['goods_lower_limit'];
				$goods_base['explain']         = $goods_base['xianshi_info']['discount_explain'];
			}

			unset($goods_base['xianshi_info']);
		}

		//验证是否赠送商品
		if (true)
		{
			$gift_array = array();
			if (!empty($gift_array))
			{
				$goods_base['have_gift'] = 'gift';
			}
			else
			{
				$goods_base['have_gift'] = '';
			}
		}

		//加入购物车按钮
		$goods_base['cart'] = true;

		//虚拟、F码、预售不显示加入购物车
		if ($goods_common['common_is_virtual'] == 1)
		{
			$goods_base['cart'] = false;
		}

		//店铺满送活动
		$Promotion    = new Promotion();
		$mansong_info = $Promotion->getShopGiftInfo($goods_base['shop_id']);
		fb($mansong_info);
		fb('满送');


		//店铺信息
		if ($goods_base['shop_id'])
		{
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base      = $Shop_BaseModel->getOne($goods_base['shop_id']);

			if (!$shop_base || $shop_base['shop_status'] != Shop_BaseModel::SHOP_STATUS_OPEN)
			{
				return null;
			}

			//商品运费信息（查找是否是包邮产品，或者满多少包邮）
			if ($shop_base['shop_free_shipping'] > 0)
			{
				$shop_base['shipping'] = sprintf("满%s免运费", ceil($shop_base['shop_free_shipping']));
			}
			else
			{
				$shop_base['shipping'] = '';
			}

		}
		else
		{
			return null;
		}

		$data                 = array();
		$data['goods_base']   = $goods_base;
		$data['common_base']  = $goods_common;
		$data['shop_base']    = $shop_base;
		$data['mansong_info'] = $mansong_info;
		$data['gift_array']   = $gift_array;

		return $data;
	}

	/**
	 * 查询商品详细信息及其促销信息
	 * @param int $goods_id
	 * @return array
	 */
	public function getGoodsInfoAndPromotionById($goods_id = null)
	{
		$goods_info = $this->getOne($goods_id);

		if (empty($goods_info))
		{
			return array();
		}

		$Promotion = new Promotion();

		//团购
		$goods_info['groupbuy_info'] = $Promotion->getGroupBuyInfoByGoodsCommonID($goods_info['common_id']);

		//限时折扣
		if (empty($goods_info['groupbuy_info']))
		{
			$goods_info['xianshi_info'] = $Promotion->getXianshiGoodsInfoByGoodsID($goods_info['goods_id']);
		}

		//加价购
		$goods_info['increase_info'] = $Promotion->getIncreaseDetailByGoodsId($goods_info['goods_id']);

		return $goods_info;
	}

	public function getCommonInfo($goods_id = 0)
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$goods_base = $this->getBase($goods_id);
		if ( empty($goods_base) )
		{
			return array();
		}
		else
		{
			$goods_base = pos($goods_base);

			$common_id   = $goods_base['common_id'];
			$common_data = $Goods_CommonModel->getCommon($common_id);
			$common_data = pos($common_data);

			return $common_data;
		}

	}

	//获取商品规格名称
	public function getGoodsSpecName($goods_id = 0)
	{
		$spec_name            = null;
		$Goods_SpecModel      = new Goods_SpecModel();
		$Goods_SpecValueModel = new Goods_SpecValueModel();

		$goods_base = $this->getBase($goods_id);
		$goods_base = pos($goods_base);

		if (!empty($goods_base['goods_spec']))
		{
			$goods_spec = pos($goods_base['goods_spec']);

			if (!empty($goods_spec))
			{
				foreach ($goods_spec as $key => $val)
				{
					$spec_value = $Goods_SpecValueModel->getSpecValue($key);
					$spec_value = pos($spec_value);

					$spec = $Goods_SpecModel->getSpec($spec_value['spec_id']);
					$spec = pos($spec);

					$spec_base_name  = $spec['spec_name'];
					$spec_value_name = $spec_value['spec_value_name'];
					$spec_name .= "$spec_base_name:&nbsp$spec_value_name&nbsp&nbsp";

				}
			}
		}

		return $spec_name;
	}

	//修改商品的销量（增加）
	public function editGoodsSale($order_goods_id = null)
	{
		//查找出订单商品的信息
		$Order_GoodsModel = new Order_GoodsModel();
		$order_goods_row  = $Order_GoodsModel->getByWhere(array('order_goods_id:IN' => $order_goods_id));

		$Goods_CommonModel = new Goods_CommonModel();

		foreach ($order_goods_row as $key => $val)
		{
			//修改common的销售数量
			$edit_common_row = array('common_salenum' => $val['order_goods_num']);
			$Goods_CommonModel->editCommonTrue($val['common_id'], $edit_common_row);

			//修改商品的销售数量
			$edit_goods_row = array('goods_salenum' => $val['order_goods_num']);
			$this->editBase($val['goods_id'], $edit_goods_row);

		}
	}

	public function getGoodsSpecByGoodId($goods_id)
	{
		$Goods_BaseModel = new Goods_BaseModel();
		$Goods_SpecModel = new Goods_SpecModel();
		$data = array();
		if($goods_id)
		{
			$data = $Goods_BaseModel->getOne($goods_id);

			if(is_array($data['goods_spec']))
			{
				$spec = pos($data['goods_spec']);
				if(!empty($spec))
				{
					$spec_data = array();
					foreach($spec as $key=>$value)
					{
						$spec_id = $key;
						$spec_value = $value;
						if($spec_id)
						{
							$spec_name = $Goods_SpecModel->getSpecNameById($spec_id);
							if($spec_name)
							{
								$spec_data[$spec_name] = $spec_value;
							}
						}
					}
					$data['spec'] = $spec_data;
				}
			}
		}
		return $data;
	}

	public function createSGIdByWap( $common_id = 0 )
	{
		$spec_goods_ids = array();

		$goods_base = $this->getBaseByCommonId($common_id);

		if ( !empty($goods_base) )
		{
			foreach ($goods_base as $goods_id => $goods_data)
			{
				if ( !empty($goods_data['goods_spec']) )
				{
					foreach ($goods_data['goods_spec'] as $k => $spec_data)
					{
						$spec_ids = array();
						$spec_ids = array_keys($spec_data);
						sort($spec_ids);
						$spec_ids = implode("|", $spec_ids);
						$spec_goods_ids[$spec_ids] = $goods_id;
					}
				}
			}
		}
		return $spec_goods_ids;
	}

	public function getTransportInfo ($area_id , $common_id)
	{
		//获取common的transport
		$Goods_CommonModel = new Goods_CommonModel();
		$common_base = $Goods_CommonModel->getOne($common_id);

		$Transport_ItemModel = new Transport_ItemModel();
		$transport = $Transport_ItemModel->getOne($common_base['transport_type_id']);

		fb($transport);

		if($transport['transport_item_default_price'])
		{
			$transport_str = sprintf('首重%sKg,默认运费：%s',$transport['transport_item_default_num'],format_money($transport['transport_item_default_price']));

			if($transport['transport_item_add_price'] > 0)
			{
				$transport_str .= sprintf('每续重%sKg,增加运费：%s',$transport['transport_item_add_num'],format_money($transport['transport_item_add_price']));
			}
		}
		else
		{
			$transport_str = _('免运费');
		}

		$transport_row = explode(',',$transport['transport_item_city']);

		if(in_array($area_id,$transport_row) || $transport['transport_item_city'] == 'default')
		{
			$flag = true;
		}
		else
		{
			$flag = false;
		}

		if ($flag)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    =  _('failure');
			$status = 250;
		}
		$data['transport_row'] = $transport_row;
		$data['transport_str'] = $transport_str;

		$result = array();
		$result['data'] 	= $data;
		$result['status'] 	= $status;
		$result['msg'] 		= $msg;

		return $result;
	}
}

?>