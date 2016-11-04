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
 * @author     charles
 * @copyright  Copyright (c) 2016, 班常乐
 * @version    1.0
 * @todo
 */
class Seller_Controller extends Yf_AppController
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
	{      //共用数据
		parent::__construct($ctl, $met, $typ);
		//头部公用的平台基本配置
		$this->web = $this->webConfig();
		
		//当前用户信息
		$this->user_info   = $this->userInfo();
		$this->title       = '';
		$this->description = '';
		$this->keyword     = '';

		//判断店铺是否开启，和是否需要续费。
		if ($this->ctl != 'Seller_Shop_SettledCtl')
		{
			$this->shopinfo = $this->getShopInfo();
                        
		}
	}


	//默认设置
	public function webConfig()
	{
		$web['web_logo']       = Web_ConfigModel::value("setting_logo");//首页logo
		$web['web_name']       = Web_ConfigModel::value("site_name");//首页名称
		$web['buyer_logo']     = Web_ConfigModel::value("setting_buyer_logo");//会员中心logo
		$web['seller_logo']    = Web_ConfigModel::value("setting_seller_logo");//卖家中心logo
		$web['goods_image']    = Web_ConfigModel::value("photo_goods_logo");//商品图片
		$web['shop_head_logo'] = Web_ConfigModel::value("photo_shop_head_logo");//店铺头像
		$web['shop_logo']      = Web_ConfigModel::value("photo_shop_logo");//店铺标志
		$web['user_logo']      = Web_ConfigModel::value("photo_user_logo");//默认头像
		
		//积分获取的默认设置
		$web['points_reg']      = Web_ConfigModel::value("points_reg");//注册获取积分
		$web['points_login']    = Web_ConfigModel::value("points_login");//登陆获取积分
		$web['points_evaluate'] = Web_ConfigModel::value("points_evaluate");//评论获取积分
		$web['points_recharge'] = Web_ConfigModel::value("points_recharge");//订单每多少获取多少积分
		$web['points_order']    = Web_ConfigModel::value("points_order");//订单每多少获取多少积分
		
		return $web;
	}


	public function userInfo()
	{

		if (Perm::checkUserPerm())
		{
			$user_id       = Perm::$userId;
			$userInfoModel = new User_InfoModel();
			$data          = $userInfoModel->getOne($user_id);
		}
		else
		{
			$data = array();
		}
		return $data;
	}

	public function getShopInfo()
	{
		if (Perm::checkUserPerm())
		{
			$shop['shop_id'] = Perm::$shopId;

			if (!empty($shop['shop_id']))
			{
				$shopBaseModel    = new Shop_BaseModel();
				$shop_base_status = $shopBaseModel->getBaseOneList($shop);
                                
				if ($shop_base_status['shop_status'] == 0)
				{
					$data['shop_status']       = $shop_base_status['shop_status'];
					$data['shop_close_reason'] = $shop_base_status['shop_close_reason'];
					return $data;
				}
				elseif ($shop_base_status['shop_status'] != 0 && $shop_base_status['shop_status'] != 3)
				{
					header("Location:" . Yf_Registry::get('url') . "?ctl=Seller_Shop_Settled&met=index&op=step1");
                                }
//                                }else{
//                                    //推算出他的续签时间（前一个月即可申请）
//                                    $date['shop_end_time'] = date("Y-m-d H:i:s", strtotime("$shop_base_status[shop_end_time] - 1 month"));
//                                    $date['time']       = date("Y-m-d h:i:s", time());
//                                   
//                                }
			}
			else
			{
				header("Location:" . Yf_Registry::get('url') . "?ctl=Seller_Shop_Settled&met=index&op=step1");
			}
		}
		else
		{
			header("Location:" . Yf_Registry::get('url'), "请先登录！");
		}
		
	}

}

?>