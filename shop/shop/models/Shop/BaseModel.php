<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Shop_BaseModel extends Shop_Base
{
	const SHOP_STATUS_OPEN = 3;  //开启

	const SELF_SUPPORT_TRUE  = 'true';
	const SELF_SUPPORT_FALSE = 'false';

	const SHOP_ALL_CLASS_TRUE = 1;

	const ADMIN_SHOP_ID = 0; //admin上传图片
	const ADMIN_USER_ID = 0; //admin上传图片

	public static $shop_status            = array(
		"0" => "关闭",
		"1" => "待审核信息",
		"2" => "待审核付款",
		"开店成功"
	);
	public static $shop_all_class         = array(
		"0" => "否",
		"1" => "是"
	);
	public static $shop_class_bind_enable = array(
		"1" => "不启用",
		"2" => "启用"
	);
	public static $shop_grade_name        = '自营店铺';
	public static $shop_payment           = array(
		"0" => "未付款",
		"1" => "已付款"
	);


	/**
	 * 读取店铺列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data           = $this->listByWhere($cond_row, $order_row, $page, $rows);
		$shopClassModel = new Shop_ClassModel();
		$shopGradeModel = new Shop_GradeModel();
                $shopCompanyModel   = new Shop_CompanyModel();
		//把数据库的状态以及分类id 和等级id全部变成中文。
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["shop_status_cha"]    = _(self::$shop_status[$value["shop_status"]]);
			$data["items"][$key]["shop_all_class_cha"] = _(self::$shop_all_class[$value["shop_all_class"]]);
			$data["items"][$key]["shop_payment_cha"]   = _(self::$shop_payment[$value["shop_payment"]]);
			if ($value['shop_class_id'])
			{
				//获取分类名
				$shop_class_id                     = $value['shop_class_id'];
				$data["items"][$key]["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
			}
			if ($value['shop_grade_id'])
			{
				//获取等级名
				$shop_grade_id                     = $value['shop_grade_id'];
				$data["items"][$key]["shop_grade"] = $shopGradeModel->getGradeName("$shop_grade_id");
			}
                        
                        $company            = $shopCompanyModel->getOne($value['shop_id']);
                        $data['items'][$key] = array_merge($data['items'][$key], $company);

		}
		return $data;
	}

	/**
	 * 根据店铺id 获取该店铺的详细信息
	 *
	 * Zhuyt
	 */
	public function getShopDetail($shop_id)
	{
		$data = $this->getOne($shop_id);

		if ($data)
		{
			$data["shop_status_cha"]    = _(self::$shop_status[$data["shop_status"]]);
			$data["shop_all_class_cha"] = _(self::$shop_all_class[$data["shop_all_class"]]);

			if (!$data['shop_self_support'])
			{
				if ($data['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $data['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
				}
			}

			if ($data['shop_grade_id'])
			{
				//获取等级名
				$shopGradeModel     = new Shop_GradeModel();
				$shop_grade_id      = $data['shop_grade_id'];
				$data["shop_grade"] = $shopGradeModel->getGradeName("$shop_grade_id");
			}
			else
			{
				$data["shop_grade"] = _(self::$shop_grade_name);
			}

		}

		//计算店铺动态评分
		$Shop_EvaluationModel = new Shop_EvaluationModel();
		$shop_evaluation      = $Shop_EvaluationModel->getByWhere(array('shop_id' => $shop_id));
		$evaluation_num       = count($shop_evaluation);

		$desc_scores    = 0;    //描述相符评分
		$service_scores = 0;    //服务态度评分
		$send_scores    = 0;   //发货速度评分
		foreach ($shop_evaluation as $key => $val)
		{
			$desc_scores += $val['evaluation_desccredit'];
			$service_scores += $val['evaluation_servicecredit'];
			$send_scores += $val['evaluation_deliverycredit'];
		}
		if ($evaluation_num)
		{
			$shop_desc_scores    = round($desc_scores / $evaluation_num, 2);
			$shop_service_scores = round($service_scores / $evaluation_num, 2);
			$shop_send_scores    = round($send_scores / $evaluation_num, 2);
		}
		else
		{
			$shop_desc_scores    = 5;
			$shop_service_scores = 5;
			$shop_send_scores    = 5;
		}


		$all_shop_eval      = $Shop_EvaluationModel->getByWhere();
		$all_eval_num       = count($all_shop_eval);
		$all_desc_scores    = 0;    //描述相符评分
		$all_service_scores = 0;    //服务态度评分
		$all_send_scores    = 0;   //发货速度评分
		foreach ($all_shop_eval as $key => $val)
		{
			$all_desc_scores += $val['evaluation_desccredit'];
			$all_service_scores += $val['evaluation_servicecredit'];
			$all_send_scores += $val['evaluation_deliverycredit'];
		}


		$data['shop_desc_scores']    = $shop_desc_scores > 0 ? $shop_desc_scores : 5;
		$data['shop_service_scores'] = $shop_service_scores > 0 ? $shop_service_scores : 5;
		$data['shop_send_scores']    = $shop_send_scores > 0 ? $shop_send_scores : 5;

		if ($all_eval_num == 0)
		{
			$data['com_desc_scores']    = 100;
			$data['com_service_scores'] = 100;
			$data['com_send_scores']    = 100;
		}
		else
		{
			$avg_desc_scores    = round($all_desc_scores / $all_eval_num, 2);
			$avg_service_scores = round($all_service_scores / $all_eval_num, 2);
			$avg_send_scores    = round($all_send_scores / $all_eval_num, 2);

			$data['com_desc_scores']    = round(($data['shop_desc_scores'] - $avg_desc_scores) / $avg_desc_scores * 100, 2);
			$data['com_service_scores'] = round(($data['shop_service_scores'] - $avg_service_scores) / $avg_service_scores * 100, 2);
			$data['com_send_scores']    = round(($data['shop_send_scores'] - $avg_send_scores) / $avg_send_scores * 100, 2);
		}

		//获取店铺支持的消费者保障服务
		$Shop_ContractModel = new Shop_ContractModel();
		$contract = $Shop_ContractModel->getByWhere(array('shop_id'=>$shop_id));

		$Shop_ContractTypeModel = new Shop_ContractTypeModel();

		if($contract)
		{
			foreach($contract as $ckey => $cval)
			{
				$contract_type =  $Shop_ContractTypeModel->getOne($cval['contract_type_id']);

				$contract[$ckey]['contract_type_logo'] = $contract_type['contract_type_logo'];
				$contract[$ckey]['contract_type_url'] = $contract_type['contract_type_url'];
			}
		}

		$data['contract'] = $contract;

		return $data;
	}

	/**
	 * 读取单个店铺
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseOneList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->getOneByWhere($cond_row, $order_row, $page, $rows);

		//把数据库的状态以及分类id 和等级id全部变成中文。
		
		if ($data)
		{
			$data["shop_status_cha"]    = _(self::$shop_status[$data["shop_status"]]);
			$data["shop_all_class_cha"] = _(self::$shop_all_class[$data["shop_all_class"]]);

			if (!$data['shop_self_support'])
			{
				if ($data['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $data['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClassName("$shop_class_id");
				}
			}

			if ($data['shop_grade_id'])
			{
				//获取等级名
				$shopGradeModel         = new Shop_GradeModel();
				$shop_grade_id          = $data['shop_grade_id'];
				$data["shop_grade"]     = $shopGradeModel->getGradeName("$shop_grade_id");
				$data["shop_grade_row"] = $shopGradeModel->getOne($shop_grade_id);
			}
			else
			{
				$data["shop_grade"] = _(self::$shop_grade_name);
			}

		}

		return $data;
	}


	//多条件获取主键
	public function getShopId($cond_row = array(), $order_row = array())
	{

		return $this->getKeyByMultiCond($cond_row, $order_row);

	}

	/**
	 * 读取添加成功的数据
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseWhere($cond_row = array(), $order_row = array())
	{

		$datas = $this->getByWhere($cond_row, $order_row);
		foreach ($datas as $key => $value)
		{

			$data["shop_status_cha"]    = _(self::$shop_status[$value["shop_status"]]);
			$data["shop_all_class_cha"] = _(self::$shop_all_class[$value["shop_all_class"]]);
			$data['shop_id']            = $value['shop_id'];
			$data['shop_name']          = $value['shop_name'];
		}
		return $data;

	}

	/**
	 * 获取店铺所有的开店信息
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getbaseAllList($shop_id = null)
	{

		$shopCompanyModel   = new Shop_CompanyModel();
		$shopClassBindModel = new Shop_ClassBindModel();
		$shopClassModel     = new Shop_ClassModel();
		$shopGradeModel     = new Shop_GradeModel();
		$company            = $shopCompanyModel->getCompanyrow($shop_id);

		$data['base'] = $this->getBase($shop_id);
		//把两个数组拼成一个数组
		foreach ($data['base'] as $key => $value)
		{
			if ($company)
			{
				$data['base'][$key] = array_merge($data['base'][$key], $company[$key]);

				if ($value['shop_class_id'])
				{
					//获取分类名
					$shop_class_id                    = $value['shop_class_id'];
					$data['base'][$key]["shop_class"] = $shopClassModel->getClass("$shop_class_id");
				}
				if ($value['shop_grade_id'])
				{
					//获取等级名
					$shop_grade_id                    = $value['shop_grade_id'];
					$data['base'][$key]["shop_grade"] = $shopGradeModel->getGrade("$shop_grade_id");
				}

				$data['base'][$key]['classbind'] = $shopClassBindModel->getClassBindlist(array("shop_id" => $shop_id));
			}
		}
		return $data;

	}


	/**
	 * 获取店铺所有的开店信息
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getbaseCompanyList($shop_id = null)
	{

		$shopCompanyModel   = new Shop_CompanyModel();
		$shopClassBindModel = new Shop_ClassBindModel();
		$company            = $shopCompanyModel->getCompanyrow($shop_id);

		$shop_base = $this->getBase($shop_id);
		//把两个数组拼成一个数组
		foreach ($shop_base as $key => $value)
		{
			if ($company)
			{
				$data = array_merge($shop_base[$key], $company[$key]);

				if ($value['shop_class_id'])
				{
					//获取分类名
					$shopClassModel     = new Shop_ClassModel();
					$shop_class_id      = $value['shop_class_id'];
					$data["shop_class"] = $shopClassModel->getClass("$shop_class_id");
				}
				if ($value['shop_grade_id'])
				{
					//获取等级名
					$shopGradeModel     = new Shop_GradeModel();
					$shop_grade_id      = $value['shop_grade_id'];
					$data["shop_grade"] = $shopGradeModel->getGrade("$shop_grade_id");
				}

				$data['classbind'] = $shopClassBindModel->getClassBindlist(array("shop_id" => $shop_id));
			}
		}
		return $data;

	}

	/**
	 * 获取店铺经营类目
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCategorylist($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		//$data  = array();
		$cat_num            = array();
		$shopClassBindModel = new Shop_ClassBindModel();
		$data               = $shopClassBindModel->listClassBindWhere($cond_row, $order_row, $page, $rows);
		//循环查询出店铺名字，以及类目名
		foreach ($data['items'] as $key => $value)
		{
			$data['items'][$key]['shop_class_bind_enablecha'] = _(self::$shop_class_bind_enable[$value['shop_class_bind_enable']]);
			$data['items'][$key]['shop_name']                 = $this->getshopName($value['shop_id']);
			$data['items'][$key]['user_name']                 = $this->getuserName($value['shop_id']);
			$product_class_id                                 = $value['product_class_id'];

			$cat_num                            = $this->catNameNum($product_class_id);
			$data['items'][$key]['cat_namenum'] = implode(" --> ", $cat_num);

		}

		return $data;
	}

	/**
	 * 根据分类id 获取所有的经营类目名称
	 *
	 * @param  array $cond_row
	 * @return array $rows 信息
	 * @access public
	 */
	public function catNameNum($product_class_id = null, $level = 100)
	{
		$product_name = array();
		// $product_cat= array();
		$CatModel    = new Goods_CatModel();
		$product_cat = $CatModel->getOne($product_class_id);
		//循环父类经营类目把子类插进去
		if ($product_cat)
		{
			$product_name[$level] = $product_cat['cat_name'];
			$cat_id               = $product_cat['cat_parent_id'];
			if ($cat_id)
			{
				$level--;
				$rs           = call_user_func_array(array(
														 $this,
														 'catNameNum'
													 ), array(
														 $cat_id,
														 $level
													 ));
				$product_name = $product_name + $rs;
			}
		}
		//数组颠倒
		ksort($product_name);
		return $product_name;
	}


	/**
	 * 获取店铺结算周期
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */

	public function getSettlementCycle($shop_id = null)
	{
		$cond_row = array();
		if ($shop_id)
		{
			$cond_row = array('shop_id' => $shop_id);
		}

		$shop_info = $this->getByWhere($cond_row);

		$data = array();
		foreach ($shop_info as $key => $val)
		{
			$data[$key]['shop_id']               = $val['shop_id'];
			$data[$key]['shop_name']             = $val['shop_name'];
			$data[$key]['shop_settlement_cycle'] = $val['shop_settlement_cycle'];
			$data[$key]['shop_create_time']      = $val['shop_create_time'];
			$data[$key]['user_id']				   = $val['user_id'];
			$data[$key]['user_name']			   = $val['user_name'];
		}

		return $data;
	}

	/**
	 * 更改店铺免运费额度
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function editFreightAmount($shop_id)
	{
		$update_flag = $this->edit($shop_id, array('shop_free_shipping' => request_int('free_shipping')));
		return $update_flag;
	}

	/**
	 * 获取店铺免运费额度
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getShopBaseInfo($shop_id)
	{
		$data = $this->getOne($shop_id);
		return $data;
	}

	/*
	 * 发货单打印设置
	 */
	public function setPrint($shop_id, $field_row)
	{
		$update_flag = $this->edit($shop_id, $field_row);
		return $update_flag;
	}
	
	/*
	 * 查店铺详情
	 */
	public function getShopListByGoodId($shop_id)
	{
		if (is_array($shop_id))
		{
			$cond_row = array('shop_id:in' => $shop_id);

		}
		else
		{
			$cond_row = array('shop_id' => $shop_id);

		}

		return $this->getByWhere($cond_row);
	}
}

?>