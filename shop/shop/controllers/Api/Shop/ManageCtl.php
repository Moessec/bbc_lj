<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Shop_ManageCtl extends Api_Controller
{
	public $messageModel     = null;
	public $shopBaseModel    = null;
	public $shopClassModel   = null;
	public $shopGradeModel   = null;
	public $shopRenewalModel = null;
        public $goodsCommonModel = null;

        /**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->messageModel     = new MessageModel();
		$this->shopBaseModel    = new Shop_BaseModel();
		$this->shopClassModel   = new Shop_ClassModel();
		$this->shopGradeModel   = new Shop_GradeModel();
		$this->shopRenewalModel = new Shop_RenewalModel();
                $this->goodsCommonModel = new Goods_CommonModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */

	public function shopIndex()
	{
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array(
			"shop_self_support" => "false"
		);
                $cond_row["shop_status:in"]=  array("0","3");
		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'user_name:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}

		$data = $this->shopBaseModel->getBaseList($cond_row);


		$this->data->addBody(-140, $data);
	}

	/**
	 * 获取店铺详情
	 *
	 * @access public
	 */
	public function getShoplist()
	{
		$shop_id = request_int('shop_id');
		$data    = $this->shopBaseModel->getbaseAllList($shop_id);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 店铺信息主页
	 *
	 * @access public
	 */
	public function getinformationrow()
	{
		$id            = request_int('shop_id');
		$data          = $this->shopBaseModel->getOne($id);
		$data['class'] = $this->shopClassModel->getClassWhere();
		$data['grade'] = $this->shopGradeModel->getGradeWhere();
		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改店铺信息主页
	 *
	 * @access public
	 */
	public function editShopinformation()
	{
		$edit_shop_row['shop_name'] = request_string("shop_name");
                $edit_shop_row['shop_class_id'] = request_int("shop_class_id");
                $edit_shop_row['shop_grade_id'] = request_int("shop_grade_id");
                $edit_shop_row['shop_status'] = request_int("shop_status");
                $edit_shop_row['shop_opening'] = request_string("shop_opening");
                $edit_shop_row['shop_latitude'] = request_float("shop_latitude");
                $edit_shop_row['shop_longitude'] = request_float("shop_longitude");
                

		$shop_id       = request_int('shop_id');
		$flag          = $this->shopBaseModel->editBase($shop_id, $edit_shop_row);
		if ($flag === FALSE)
		{
			$status = 250;
			$msg    = _('failure');
		}
		else
		{       $status = 200;
			$msg    = _('success');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 开店申请首页
	 *
	 * @access public
	 */
	public function shopJoin()
	{
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array(
			"shop_status" => "1",
			"shop_self_support" => "false"
		);

		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'user_name:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}
		$data = $this->shopBaseModel->getBaseList($cond_row);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 经营类目申请
	 *
	 * @access public
	 */

	public function getCategory()
	{
		// $data = array();
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');
                $shop_class_bind_enable = request_string('status');
		$cond_row = array();
                
                if($shop_class_bind_enable){
                    $type            = 'shop_class_bind_enable';
                    $cond_row[$type] = $shop_class_bind_enable;
                }
		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type=="1")
			{
				$type            = 'commission_rate:LIKE';
				$cond_row[$type] = $shop_account . '%';
			}
			elseif($shop_type=="2")
			{
                                
                                $shop_base = $this->shopBaseModel->getByWhere(array('shop_name:LIKE'=>$shop_account.'%'));
                                $shop_id = array_column($shop_base, 'shop_id'); 
                                $cond_row['shop_id:IN'] = $shop_id;
                        }else{
                                $shop_base = $this->shopBaseModel->getByWhere(array('user_name:LIKE'=>$shop_account.'%'));
                                $user_id = array_column($shop_base, 'shop_id'); 
                                $cond_row['shop_id:IN'] = $user_id;
                        }
			
		}
		$order = array('shop_id' => 'desc');
		$data  = $this->shopBaseModel->getCategorylist($cond_row, $order);
		$this->data->addBody(-140, $data);
	}


	/**
	 * 修改店铺经营类目
	 *
	 * @access public
	 */
	public function editShopCategory()
	{
		$shop_class_bind_id  = request_int('shop_class_bind_id');
		$shopClassBindModel  = new Shop_ClassBindModel();
		$shop_class_bind_row = $shopClassBindModel->getOne($shop_class_bind_id);
		$this->data->addBody(-140, $shop_class_bind_row);
	}

	/**
	 * 添加店铺经营类目
	 */

	public function editShopCategoryRow()
	{
		$shop_class_bind_id            = request_int('shop_class_bind_id');
		$class_list["commission_rate"] = request_string("commission_rate");
		$shopClassBindModel            = new Shop_ClassBindModel();
		$flag                          = $shopClassBindModel->editClassBind($shop_class_bind_id, $class_list);
		if ($flag !== false)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 添加店铺经营类目
	 */

	public function addShopCategory()
	{
		$data["shop_id"] = request_int('shop_id');
		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加店铺经营类目
	 */

	public function addShopCategoryRow()
	{
		$class_list["product_class_id"]       = request_int('product_class_id');
		$class_list["shop_id"]                = request_int('shop_id');
		$class_list["commission_rate"]        = request_string("commission_rate");
		$class_list["shop_class_bind_enable"] = 2;
		$shopClassBindModel                   = new Shop_ClassBindModel();
		$flag                                 = $shopClassBindModel->addClassBind($class_list, true);
		if ($flag)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 删除经营类目
	 *
	 * @access public
	 */

	public function delCategory()
	{

		$shop_class_bind_id = request_int('shop_class_bind_id');

		if ($shop_class_bind_id)
		{

			$shopClassBindModel = new Shop_ClassBindModel();
			$flag               = $shopClassBindModel->removeClassBind($shop_class_bind_id);
			if ($flag)
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$status = 250;
				$msg    = _('failure');
			}
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function categoryStatus()
	{

		$shop_class_bind_id = request_int('shop_class_bind_id');

		if ($shop_class_bind_id)
		{

			$shopClassBindModel                     = new Shop_ClassBindModel();
			$class_status['shop_class_bind_enable'] = 2;
			$flag                                   = $shopClassBindModel->editClassBind($shop_class_bind_id, $class_status);
			if ($flag)
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$status = 250;
				$msg    = _('failure');
			}
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 结算周期首页
	 *
	 * @access public
	 */

	public function getSettlement()
	{
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array();

		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'user_name:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}
		$order = array('shop_id' => 'asc');
		$data  = $this->shopBaseModel->getBaseList($cond_row, $order);
		$this->data->addBody(-140, $data);
	}


	/**
	 * 结算周期修改页面
	 *   查询一条记录
	 * @access public
	 */

	public function getSettlementRow()
	{
		$shop_id = request_int('shop_id');
		$data    = $this->shopBaseModel->getOne($shop_id);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改周期
	 *
	 * @access public
	 */
	public function editSettlementRow()
	{
		$shop_id                                        = request_int('shop_id');
		$shop_settlement_cycle['shop_settlement_cycle'] = request_string('shop_settlement_cycle');
		$flag                                           = $this->shopBaseModel->editBase($shop_id, $shop_settlement_cycle);
                
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
		$this->data->addBody(-140, array(), $msg, $status);
	}

	public function reopenlist()
	{
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array();

		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'shop_id:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}
		$data = $this->shopRenewalModel->getRenewalList($cond_row);
		$this->data->addBody(-140, $data);
	}

	public function delReopen()
	{

		$shop_reopen_id = request_int('id');

		if ($shop_reopen_id)
		{
			$flag = $this->shopRenewalModel->removeRenewal($shop_reopen_id);
			if ($flag)
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$status = 250;
				$msg    = _('failure');
			}
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//审核续签
	public function examineReopen()
	{

		$shop_reopen_id = request_int('id');
		//开启事物
		$this->messageModel->sql->startTransactionDb();
		if ($shop_reopen_id)
		{
			$status['status'] = 1;
			//更改续签的状态
			$flag = $this->shopRenewalModel->editRenewal($shop_reopen_id, $status);
			//更改店铺的结束时间
			$flag1 = $this->shopRenewalModel->editEndTime($shop_reopen_id);
			//判断事物有没有成功
			if ($flag && $flag1 && $this->messageModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$this->messageModel->sql->rollBackDb();
				$status = 250;
				$msg    = _('failure');
			}

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editCategory()
	{

		$cond_row['shop_id'] = request_int('shop_id');
		$data                = $this->shopBaseModel->getCategorylist($cond_row);
		$this->data->addBody(-140, $data);

	}

	//审核店铺 状态为1，审核信息。状态为2，审核有没有付款。
	public function editShopStatus()
	{
		$shop_id  = request_int('shop_id');
		$shop_row = $this->shopBaseModel->getOne($shop_id);
		if ($shop_row['shop_status'] == 1)
		{
			$edit_status['shop_status'] = 2;
			$flag                       = $this->shopBaseModel->editBase($shop_id, $edit_status);

		}
		elseif ($shop_row['shop_status'] == 2)
		{
			$edit_status['shop_status'] = 3;
			$flag                       = $this->shopBaseModel->editBase($shop_id, $edit_status);
		}
		if (!$flag === FALSE)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//审核付款
	public function shopPay()
	{
		$shop_type    = request_string('user_type');
		$shop_account = request_string('search_name');

		$cond_row = array(
			"shop_status" => "2",
			"shop_self_support" => "false"
		);

		//按照店主账号与店主名称查询
		if ($shop_account)
		{
			if ($shop_type)
			{
				$type = 'shop_name:LIKE';
			}
			else
			{
				$type = 'user_name:LIKE';
			}
			$cond_row[$type] = '%' . $shop_account . '%';
		}
		$data = $this->shopBaseModel->getBaseList($cond_row);
		$this->data->addBody(-140, $data);
	}

}

?>