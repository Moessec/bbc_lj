<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_CommonModel extends Goods_Common
{
	const GOODS_STATE_NORMAL  = 1;  //正常
	const GOODS_STATE_OFFLINE = 0;  //下架下架
	const GOODS_STATE_ILLEGAL = 10; //违规下架-禁售
	const GOODS_STATE_TIMING  = 2;   //定时发布

	const GOODS_VIRTUAL = 1;   //虚拟商品
	const GOODS_NORMAL = 0;   //实物商品

	const GOODS_VIRTUAL_REFUND = 1;   //支持过期退款

	const GOODS_NO_ALARM = 0;  //不需要预警

	const RECOMMEND_TRUE = 2;
	const RECOMMEND_FALSE = 1;

	public static $stateMap = array(
		'0' => '下架',
		'1' => '正常',
		'10' => '违规（禁售）'
	);

	const GOODS_VERIFY_ALLOW   = 1;  //通过
	const GOODS_VERIFY_DENY    = 0;  //未通过
	const GOODS_VERIFY_WAITING = 10; //审核中

	const CONTRACT_USE = 1;
	public static $verifyMap = array(
		'0' => '未通过',
		'1' => '通过',
		'10' => '待审核'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommonList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/**
	 * 读取分页列表
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommonNormal($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getCommonOffline($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['common_state:in'] = array(
			Goods_CommonModel::GOODS_STATE_OFFLINE,
			Goods_CommonModel::GOODS_STATE_TIMING
		);
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	public function getCommonIllegal($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['common_state'] = Goods_CommonModel::GOODS_STATE_ILLEGAL;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getCommonVerifyWaiting($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$cond_row['shop_id'] = Perm::$shopId;
		$cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_WAITING;
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}


	/**
	 * 取得各种下拉框
	 *
	 * @access public
	 */
	public function getStateCombo()
	{
		$data = array();

		foreach (Goods_CommonModel::$stateMap as $id => $name)
		{
			$row                   = array();
			$row['id']             = $id;
			$row['name']           = $name;
			$data['goods_state'][] = $row;
		}


		foreach (Goods_CommonModel::$verifyMap as $id => $name)
		{
			$row         = array();
			$row['id']   = $id;
			$row['name'] = $name;

			$data['goods_verify'][] = $row;
		}

		//goods type
		$Goods_TypeModel = new Goods_TypeModel();
		$goods_type_rows = $Goods_TypeModel->getByWhere();

		if ($goods_type_rows)
		{
			$row = array();
			foreach ($goods_type_rows as $goods_type_row)
			{
				$row         = array();
				$row['id']   = $goods_type_row['type_id'];
				$row['name'] = $goods_type_row['type_name'];

				$data['goods_type'][] = $row;
			}
		}

		return $data;
	}

	/**
	 * 读取分页列表+goods_id
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsIdList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$common_list = $this->getByWhere($cond_row, $order_row, $page, $rows);

		$Goods_BaseModel = new Goods_BaseModel();
		foreach ($common_list as $key => $value)
		{
			//这里随便取一个goods_id 因为多个good_id 对应的都是那个产品
			$goods_cond_row['common_id']        = $value['common_id'];
			$goods_cond_row['shop_id']          = $value['shop_id'];
			$goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
			$goods_list                         = $Goods_BaseModel->getOneByWhere($goods_cond_row);

			if ($goods_list)
			{
				$common_list[$key]["goods_id"] = $goods_list['goods_id'];
			}
			else
			{
				// $common_list['items'][$key]["goods_id"] = 0;
				//若此common_id没有商品则删除此数组
				unset($common_list[$key]);
			}
		}

		$total = ceil_r(count($common_list) / $rows);

		$start = ($page - 1) * $rows;

		$data_rows = array_slice($common_list, $start, $rows, true);

		$arr              = array();
		$arr['page']      = $page;
		$arr['total']     = $total;  //total page
		$arr['totalsize'] = count($common_list);
		$arr['records']   = count($data_rows);
		$arr['items']     = array_values($data_rows);

		return $arr;

	}

	//获取正常状态的商品goods_base 列表
	public function getNormalSateGoodsBase($cond_common_row, $order_row, $page, $rows)
	{
		$cond_common_row['common_state']  = Goods_CommonModel::GOODS_STATE_NORMAL;
		$cond_common_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
		$cond_common_row['shop_status']   = Shop_BaseModel::SHOP_STATUS_OPEN;
		$common_id_rows                   = $this->getKeyByWhere($cond_common_row);

		$Goods_BaseModel                   = new Goods_BaseModel();
		$cond_base_row['common_id:IN']     = $common_id_rows;
		$cond_base_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
		$goods_base_rows                   = $Goods_BaseModel->getBaseList($cond_base_row, $order_row, $page, $rows);

		return $goods_base_rows;
	}
	//状态正常的商品，团购需要
	// Author ye
	public function getNormalStateGoodsCommon($common_id)
	{
		if (is_array($common_id))
		{
			$cond_row['common_id:IN'] = $common_id;
		}
		else
		{
			$cond_row['common_id'] = $common_id;
		}

		$cond_row['common_state']  = Goods_CommonModel::GOODS_STATE_NORMAL;
		$cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
		$cond_row['shop_status']   = Shop_BaseModel::SHOP_STATUS_OPEN;
		$common_rows               = $this->getByWhere($cond_row);

		return $common_rows;
	}

	/**
	 * 根据common_id读取其中一个状态正常的 goods_id
	 *
	 * @param  int $common_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 * Author ye
	 */
	public function getNormalStateGoodsId($common_id)
	{
		$cond_row['common_id']     = $common_id;
		$cond_row['common_state']  = Goods_CommonModel::GOODS_STATE_NORMAL;
		$cond_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;
		$cond_row['shop_status']   = Shop_BaseModel::SHOP_STATUS_OPEN;
		$common_row                = $this->getOneByWhere($cond_row);

		$goods_id = null;

		if ($common_row)
		{
			$Goods_BaseModel = new Goods_BaseModel();

			//这里随便取一个goods_id 因为多个good_id 对应的都是那个产品
			$goods_cond_row['common_id']        = $common_row['common_id'];
			$goods_cond_row['shop_id']          = $common_row['shop_id'];
			$goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;
			$goods_row                          = $Goods_BaseModel->getOneByWhere($goods_cond_row);

			if ($goods_row)
			{
				$goods_id = $goods_row['goods_id'];
			}

		}

		return $goods_id;
	}


	/**
	 * 读取商品,
	 *
	 * @param  int $common_id 主键值
	 * @param  string $type SKU  SPU
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGoodsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 10, $property_value_row = array())
	{
		$type = 'SKU';

		//判断辅助属性, left join
		if ($property_value_row)
		{
			$sql = "
					SELECT
						SQL_CALC_FOUND_ROWS c.*, gp.goods_id
					FROM
						" . TABEL_PREFIX . "goods_common c LEFT OUTER JOIN " . TABEL_PREFIX . "goods_property_index gp ON c.common_id = gp.common_id
					";

			//需要分页如何高效，易扩展
			$offset = $rows * ($page - 1);
			$this->sql->setLimit($offset, $rows);

			if ($cond_row)
			{
				foreach ($cond_row as $k => $v)
				{
					$k_row = explode(':', $k);

					if (count($k_row) > 1)
					{
						$this->sql->setWhere('c.' . $k_row[0], $v, $k_row[1]);
					}
					else
					{
						$this->sql->setWhere('c.' . $k, $v);
					}

				}
			}
			else
			{
			}

			if ($order_row)
			{
				foreach ($order_row as $k => $v)
				{
					$this->sql->setOrder('c.' . $k, $v);
				}
			}

			$limit = $this->sql->getLimit();
			$where = $this->sql->getWhere();
			$where = $where . " AND gp.goods_id IS NOT NULL AND gp.goods_is_shelves AND  gp.property_value_id IN (" . implode(', ', $property_value_row) . ")";

			$order = $this->sql->getOrder();
			$sql   = $sql . $where . $order . $limit;

			$common_rows = $this->sql->getAll($sql);

			//读取影响的函数, 和记录封装到一起.
			$total = $this->getFoundRows();

			$common_data              = array();
			$common_data['page']      = $page;
			$common_data['total']     = ceil_r($total / $rows);  //total page
			$common_data['totalsize'] = $total;
			$common_data['records']   = count($common_rows);

			$common_data['items'] = $common_rows;
		}
		else
		{
			$common_data = $this->listByWhere($cond_row, $order_row, $page, $rows, false);
			$common_rows = $common_data['items'];
		}


		if ('SKU' == $type)
		{
			$common_ids = array_column($common_rows, 'common_id');

			if ($common_ids)
			{

				$Goods_BaseModel = new Goods_BaseModel();

				$goods_cond_row['common_id:IN']     = $common_ids;
				$goods_cond_row['goods_is_shelves'] = Goods_BaseModel::GOODS_UP;

				$goods_rows = $Goods_BaseModel->getByWhere($goods_cond_row);

				//获取当前用户收藏的商品id
				$User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
				if (Perm::checkUserPerm())
				{
					$user_favoritr_row = $User_FavoritesGoodsModel->getByWhere(array("user_id" => Perm::$userId));
					$user_favoritr = array_column($user_favoritr_row,'goods_id');
				}
				else
				{
					$user_favoritr = array();
				}

				foreach ($goods_rows as $key => $goods_row)
				{
					if ($goods_row && isset($common_rows[$goods_row['common_id']]))
					{
						$common_rows[$goods_row['common_id']]["goods_id"] = $goods_row['goods_id'];
						$common_rows[$goods_row['common_id']]["good"][]   = $goods_row;
						//判断该商品是否是自己的商品
						if ($goods_row['shop_id'] == Perm::$shopId)
						{
							$common_rows[$goods_row['common_id']]["shop_owner"] = 1;
						}
						else
						{
							$common_rows[$goods_row['common_id']]["shop_owner"] = 0;
						}

						//判断该商品是否已经收藏过
						if(in_array($goods_row['goods_id'],$user_favoritr))
						{
							$common_rows[$goods_row['common_id']]["is_favorite"] = 1;
						}
						else
						{
							$common_rows[$goods_row['common_id']]["is_favorite"] = 0;
						}
					}
					else
					{
						//错误数据,干掉吧
						//$common_rows[$goods_row['common_id']]["goods_id"] = 0;
					}
				}
			}
		}

		foreach($common_rows as $key => $val)
		{
			if(!isset($val['good']))
			{
				unset($common_rows[$key]);
			}
		}

		$common_data['items'] = array_values($common_rows);

		return $common_data;

	}

	//获取热销
	public function getHotSalle($shop_id = 0, $is_wap = false)
	{
		if ($is_wap)
		{
			$common_num = 8;
		}
		else
		{
			$common_num = 5;
		}
		$cond_row                    = array();
		$order_row                   = array();
		$cond_row['shop_id']         = $shop_id;  //店铺id
		$cond_row['common_state']    = $this::GOODS_STATE_NORMAL;  //正常上架
		$order_row['common_salenum'] = 'desc';
		$data                        = $this->listByWhere($cond_row, $order_row, 0, $common_num);
		return $data;
	}

	//热门收藏
	public function getHotCollect($shop_id = 0)
	{
		$cond_row                    = array();
		$order_row                   = array();
		$cond_row['shop_id']         = $shop_id;  //店铺id
		$cond_row['common_state']    = $this::GOODS_STATE_NORMAL;  //正常上架
		$order_row['common_collect'] = 'desc';
		$data                        = $this->listByWhere($cond_row, $order_row, 0, 5);

		return $data;
	}


	/*
	 * 向映射添加数据
	 * */
	public function createMapRelation($common_id = 0, $common_data = array())
	{}

    /*
     * 根据common_id 获取所有goods_id下面的详细信息
     * @param array $common_id_rows 商品id
     * @return array $re 查询数据
     */
    public function getGoodsDetailRows($common_id_rows)
    {
        $Goods_CommonModel = new Goods_CommonModel();
        $Goods_BaseModel   = new Goods_BaseModel();
        $data = array();
        if(!empty($common_id_rows))
        {
            foreach($common_id_rows as $key=>$value)
            {
                $common_id  = $value;
                $goods_rows = $Goods_BaseModel->getByWhere(array('common_id'=>$common_id));
                if(!empty($goods_rows))
                {
                    $goods_ids = array_column($goods_rows, 'goods_id');
                    foreach($goods_ids as $k=>$v)
                    {
                        $goods_id = $v;
                        $data[$common_id][$v] =  $Goods_BaseModel->getGoodsSpecByGoodId($goods_id);
                    }
                }
            }
        }
        return $data;
    }

	/*
	 * 根据shop 店铺关联的消费者保障服务
	 * @param array $common_data 商品common
	 * @return array $re 查询数据
	 */
	public function getShopContract ( &$common_data )
	{
		$shop_id = Perm::$shopId;

		$Shop_ContractModel = new Shop_ContractModel();
		$condi_con['contract_state']	 = $shop_id;
		$condi_con['contract_state']	 = Shop_ContractModel::CONTRACT_JOIN;
		$condi_con['contract_use_state'] = Shop_ContractModel::CONTRACT_INUSE;

		$contract_list = $Shop_ContractModel->getByWhere( $condi_con );
		if ( !empty($contract_list) )
		{
			foreach ( $contract_list as $contract_id => $contract_data )
			{
				$contract_type_id = $contract_data['contract_type_id'];
				$common_data["common_shop_contract_$contract_type_id"] = Goods_CommonModel::CONTRACT_USE;
			}
		}
	}
}

?>