<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Goods_GoodsCtl extends Api_Controller
{
	/**
	 * 验证API是否正确
	 *
	 * @access public
	 */
	public function listCommon()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$order_row = array();
		$sidx      = request_string('sidx');
		$sord      = request_string('sord', 'asc');

		if ($sidx)
		{
			$order_row[$sidx] = $sord;
		}

		$cond_row = array();

		if (-1 != request_int('cat_id', -1))
		{
			$cond_row['cat_id'] = request_int('cat_id');
		}

		if (-1 != request_int('brand_id', -1))
		{
			$cond_row['brand_id'] = request_int('brand_id');
		}

		if (-1 != request_int('common_state', -1))
		{
			$cond_row['common_state'] = request_int('common_state');
		}

		if (-1 != request_int('common_verify', -1))
		{
			$cond_row['common_verify'] = request_int('common_verify');
		}

		if (request_int('common_id'))
		{
			$cond_row['common_id'] = request_int('common_id');
		}

		if (request_string('common_name'))
		{
			$cond_row['common_name:LIKE'] = '%' . request_string('common_name') . '%';
		}

		if (request_string('shop_name'))
		{
			$cond_row['shop_name:LIKE'] = '%' . request_string('shop_name') . '%';
		}

		if (-1 != request_int('brand_id', -1))
		{
			$cond_row['brand_id'] = request_int('brand_id');
		}

		$Goods_CommonModel = new Goods_CommonModel();
		$data              = $Goods_CommonModel->getCommonList($cond_row, $order_row, $page, $rows);
		
		if ($data['records'])
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('没有满足条件的结果哦');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 取得商品分类信息
	 *
	 * @access public
	 */
	public function getCatList()
	{
		if (-1 != request_int('cat_id', -1))
		{
			$cond_row['cat_id'] = request_int('cat_id');
		}

		$order_row = array(
			'cat_displayorder',
			'ASC'
		);

		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatList($cond_row['cat_id'], $order_row);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 取得商品分类信息
	 *
	 * @access public
	 */
	public function getBrand()
	{
		$order_row = array(
			'brand_displayorder',
			'ASC'
		);

		$Goods_BrandModel = new Goods_BrandModel();
		$data             = $Goods_BrandModel->getBrandList(array(), $order_row, 1, 10000);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 取得各种下拉框
	 *
	 * @access public
	 */
	public function getStateCombo()
	{
		$Goods_CommonModel = new Goods_CommonModel();
		$data              = $Goods_CommonModel->getStateCombo();


		$this->data->addBody(-140, $data);
	}

	/**
	 * 编辑状态
	 *
	 * @access public
	 */
	public function editCommonState()
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$common_id           = request_int('common_id');
		$common_state        = request_int('common_state');
		$common_state_remark = request_string('common_state_remark');

		if ($common_id && array_key_exists($common_state, Goods_CommonModel::$stateMap))
		{

			$flag = $Goods_CommonModel->editCommon($common_id, array('common_state' => $common_state));

			if ($flag !== false)
			{
				$msg    = _('success');
				$status = 200;

                $shop = $Goods_CommonModel->getOne($common_id);
                $shop_id = $shop['shop_id'];
                $Shp_BaseModel = new Shop_BaseModel();
                $shop_data = $Shp_BaseModel->getOne($shop_id);

                //商品违规被下架
                //[des][common_id]
                $message = new MessageModel();
                $message->sendMessage('Commodity violation is under the shelf',$shop_data['user_id'], $shop_data['user_name'], $order_id = NULL, $shop_name = NULL, 1, 1, $end_time = Null,$common_id=$common_id,$goods_id=NULL,$common_state_remark);
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('请求参数有误');
			$status = 250;

		}

		$this->data->addBody(-140, array(), $msg, $status);
	}


	/**
	 * 编辑状态
	 *
	 * @access public
	 */
	public function editCommonVerify()
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$common_id            = request_int('common_id');
		$common_verify        = request_int('common_verify');
		$common_verify_remark = request_string('common_verify_remark');

		if ($common_id && array_key_exists($common_verify, Goods_CommonModel::$verifyMap))
		{

			$flag = $Goods_CommonModel->editCommon($common_id, array('common_verify' => $common_verify));

			if ($flag !== false)
			{
				$msg    = _('success');
				$status = 200;
                if($common_verify==0){
                    $shop = $Goods_CommonModel->getOne($common_id);
                    $shop_id = $shop['shop_id'];
                    $Shp_BaseModel = new Shop_BaseModel();
                    $shop_data = $Shp_BaseModel->getOne($shop_id);

                    //商品审核失败提醒
                    //[des][common_id]
                    $message = new MessageModel();
                    $message->sendMessage('Commodity audit failed to remind',$shop_data['user_id'], $shop_data['user_name'], $order_id = NULL, $shop_name = NULL, 1, 1, $end_time = Null,$common_id=$common_id,$goods_id=NULL,$common_verify_remark);
                }
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('请求参数有误');
			$status = 250;

		}

		$this->data->addBody(-140, array('common_verify'=>$common_verify), $msg, $status);
	}

	/**
	 * 取得各种下拉框
	 *
	 * @access public
	 */
	public function removeCommon()
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$common_id     = request_string('common_id');
		$common_id_row = explode(',', $common_id);

		$Goods_CommonModel->sql->startTransactionDb();

		$flag = $Goods_CommonModel->removeCommon($common_id_row);

		if ($flag && $Goods_CommonModel->sql->commitDb())
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$Goods_CommonModel->sql->rollBackDb();

			$msg    = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array('id' => $common_id_row), $msg, $status);
	}


	/**
	 * 取得商品SKU信息
	 *
	 * @access public
	 */
	public function getGoodsInfo()
	{
		$Goods_BaseModel = new Goods_BaseModel();

		$common_id = request_int('common_id');
		$data      = $Goods_BaseModel->getBaseListByCommonId($common_id);

		$this->data->addBody(-140, $data);
	}

	public function listGoodsRecommend()
	{
		$page      = request_int('page');
		$rows      = request_int('rows');
		$cond_row  = array();
		$order_row = array();

		$Goods_RecommendModel = new Goods_RecommendModel();
		$Goods_CatModel       = new Goods_CatModel();
		$data                 = $Goods_RecommendModel->getRecommendList($cond_row, $order_row, $page, $rows);
		$items                = $data['items'];
		unset($data['items']);
		if (!empty($items))
		{
			foreach ($items as $key => $value)
			{
				$cat_id                        = $value['goods_cat_id'];
				$items[$key]['goods_cat_name'] = $Goods_CatModel->getNameByCatid($value['goods_cat_id']);
			}
		}
		$data['items'] = $items;
		if ($items)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}
		$this->data->addBody(140, $data, $msg, $status);
	}


	//商品推荐
	public function getCatGoodsList()
	{
		$Goods_CommonModel = new Goods_CommonModel();

		$goods_cat_id = request_int('goods_cat_id');
		$goods_name   = request_string('goods_name');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 24;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row  = array();
		$order_row = array();

		if ($goods_cat_id > 0)
		{
			$cond_row['cat_id'] = $goods_cat_id;
		}

		if ($goods_name)
		{
			$cond_row['common_name:like'] = '%' . $goods_name . '%';
		}

		$data = $Goods_CommonModel->getCommonList($cond_row, $order_row, $page, $rows);

		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->ajaxprompt();

		$this->data->addBody(-140, $data);
	}

	//新增商品推荐
	public function addGoodsRecommend()
	{
		$Goods_RecommendModel      = new Goods_RecommendModel();
		$Goods_CatModel            = new Goods_CatModel();
		$add_data                  = array();
		$add_data['goods_cat_id']  = request_int('goods_cat_id');
		$cat_id                    = request_int('goods_cat_id');
		$goods_id_rows             = request_row('goods_id_list');
		$add_data['common_id']     = $goods_id_rows;
		$add_data['recommend_num'] = count($goods_id_rows);
		//判断分类是否已有推荐
		$data_old = $Goods_RecommendModel->getByWhere(array('goods_cat_id' => $cat_id));
		if (empty($data_old))
		{
			$goods_recommend_id = $Goods_RecommendModel->addRecommend($add_data, true);

			if ($goods_recommend_id)
			{
				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}

			$add_data['goods_cat_name'] = $Goods_CatModel->getNameByCatid($cat_id);

		}
		else
		{
			$msg      = _('该分类已有推荐');
			$status   = 250;
			$add_data = array();
		}

		$this->data->addBody(-140, $add_data, $msg, $status);
	}

	public function removeGoodsRecommend()
	{
		$Goods_RecommendModel = new Goods_RecommendModel();
		$id                   = request_int('id');
		$flag                 = $Goods_RecommendModel->removeRecommend($id);
		if ($flag)
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

	public function getGoodsRecommendById()
	{
		$Goods_RecommendModel = new Goods_RecommendModel();
		$Goods_CommonModel    = new Goods_CommonModel();
		$id                   = request_int('id');
		$re                   = array();

		if ($id)
		{
			$data               = $Goods_RecommendModel->getOne($id);
			$goods_id_rows      = $data['common_id'];
			$cat_id             = $data['goods_cat_id'];
			$re                 = $Goods_CommonModel->getCommonList(array('common_id:in' => $goods_id_rows));
			$re['goods_cat_id'] = $cat_id;
		}
		if (!empty($re))
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $re, $msg, $status);
	}

	public function editGoodsRecommend()
	{
		$Goods_RecommendModel = new Goods_RecommendModel();
		$Goods_CatModel       = new Goods_CatModel();
		$edit_data            = array();

		$id            = request_int('id');
		$goods_cat_id  = request_int('goods_cat_id');
		$goods_id_rows = request_row('goods_id_list');

		if ($goods_cat_id != -1)
		{
			$edit_data['goods_cat_id'] = $goods_cat_id;
		}
		$edit_data['common_id']     = $goods_id_rows;
		$edit_data['recommend_num'] = count($goods_id_rows);

		$flag = $Goods_RecommendModel->editRecommend($id, $edit_data);

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
		$data                       = array();
		$data_new                   = $Goods_RecommendModel->getOne($id);
		$data['goods_cat_name']     = $Goods_CatModel->getNameByCatid($data_new['goods_cat_id']);
		$data['goods_recommend_id'] = $data_new['goods_recommend_id'];
		$data['recommend_num']      = $data_new['recommend_num'];
		$data['id']                 = $data_new['goods_recommend_id'];
		$this->data->addBody(-140, $data, $msg, $status);
	}

//erp同步商品
    public function listGoods()
    {
        $Goods_CommonModel = new Goods_CommonModel();
        $Goods_CommonModel->sql->setWhere('common_verify',1);
        if (request_string('endDate'))
        {
            $Goods_CommonModel->sql->setWhere('common_add_time',request_string('endDate'),'<=');
        }
        if (request_string('beginDate'))
        {
            $Goods_CommonModel->sql->setWhere('common_add_time',request_string('beginDate'),'>=');
        }
        if (request_string('goodsId'))
        {
            $Goods_CommonModel->sql->setWhere('common_id',explode(',',request_string('goodsId')),'IN');
        }
        if (request_string('goodsName'))
        {
            $Goods_CommonModel->sql->setWhere('common_name',explode(',',request_string('goodsName')),'IN');
        }
        if (request_string('goodsNumber'))
        {
            $Goods_CommonModel->sql->setWhere('common_platform_code',explode(',',request_string('goodsNumber')),'IN');
        }
        if (request_string('goodsStatus')==1)
        {
            $Goods_CommonModel->sql->setWhere('common_state',1);
        }else if(request_string('goodsStatus')==2){
            $Goods_CommonModel->sql->setWhere('common_state',0);
        }
        if (-1 !=request_string('sid' , -1))
        {
            $shop_account=request_string('sid');
        }
        $Goods_CommonModel->sql->setLimit(0,999999999);
        $goodscommon  = $Goods_CommonModel->getCommon('*');
        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_BaseModel->sql->setLimit(0,999999999);
        $Goods_Base  = $Goods_BaseModel->getBase('*');
        $Base_DistrictModel = new Base_DistrictModel();
        $Base_DistrictModel->sql->setLimit(0,999999999);
        $Base_District  = $Base_DistrictModel->getDistrict('*');
        $Shop_BaseModel = new Shop_BaseModel();
        $Shop_BaseModel->sql->setLimit(0,999999999);
        $Shop_Base  = $Shop_BaseModel->getBase('*');
$data=array();
        if ($goodscommon)
        {
            $data['count']=count($goodscommon);
            foreach($goodscommon as $key=>$value){
                if(!$shop_account || $shop_account=$Shop_Base[$value['shop_id']]['shop_account']){
                    $data['data'][$key]['productId']=$value['common_id'];
                    $data['data'][$key]['cate']=$value['cat_name'];
                    $data['data'][$key]['imageUrl']=$value['common_image'];
                    $data['data'][$key]['productName']=$value['common_name'];
                    $data['data'][$key]['price']=$value['common_price'];
                    $data['data'][$key]['isShelves']=$value['common_state'];
                    $data['data'][$key]['postPrice']=$value['common_freight'];
                    $data['data'][$key]['area']='';
                    if($value['common_location']){
                        foreach($value['common_location'] as $areaid){
                            $data['data'][$key]['area'].=$Base_District[$areaid]['district_name'];
                        }
                    }
                    $data['data'][$key]['shopId']=$value['shop_id'];
                    $data['data'][$key]['shopName']=$value['shop_name'];
                    $data['data'][$key]['member_name']=$Shop_Base[$value['shop_id']]['shop_account'];
                    $data['data'][$key]['stock']=$value['common_stock'];
                    $data['data'][$key]['code']=$value['common_platform_code'];
                    $sku=array();

                    foreach($Goods_Base as $k=>$v){
                        if($v['common_id']==$value['common_id']){
                            $sku[$k]['id']=$v['goods_id'];
                            $sku[$k]['pid']=$v['common_id'];
                            $sku[$k]['property_value_id']='';
                            $sku[$k]['setmeal']=$value['common_name'];
                            if($v['goods_spec']){
                                foreach($v['goods_spec'] as $goods_spec){
                                    $sku[$k]['property_value_id']=implode(',',array_keys($goods_spec));
                                    $sku[$k]['setmeal']=implode(',',array_values($goods_spec));
                                }
                            }
                            $sku[$k]['spec_id']= '';
                            $sku[$k]['spec_name']= '商品名称';
                            if($value['common_spec_name']){
                                $sku[$k]['spec_id']= implode(',',array_keys($value['common_spec_name']));
                                $sku[$k]['spec_name']= implode(',',array_values($value['common_spec_name']));

                            }

                            $sku[$k]['price']=$v['goods_price'];
                            $sku[$k]['market_price']=$v['goods_market_price'];
                            $sku[$k]['cost_price']=$value['common_cost_price'];
                            $sku[$k]['stock']=$v['goods_stock'];
                            $sku[$k]['sku']=$v['goods_code'];
                        }
                    }
                    $data['data'][$key]['sku']=$sku;
                }
            }
            $status = 200;
            $msg    = _('success');
        }
        else
        {
            $status = 250;
            $msg    = _('没有满足条件的结果哦');
        }
//fb($data);
        $this->data->addBody(-140, $data, $msg, $status);
    }
}

?>