<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_BespeakCtl extends Buyer_Controller
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

		$this->userInfoModel        = new User_InfoModel();
		$this->userGradeModel       = new User_GradeModel();
		$this->userResourceModel    = new User_ResourceModel();
		$this->userBespeakModel     = new User_BespeakModel();
		$this->userPrivacyModel     = new User_PrivacyModel();
		$this->userBaseModel        = new User_BaseModel();
		$this->userTagModel         = new User_TagModel();
		$this->userTagRecModel      = new User_TagRecModel();
		$this->userFriendModel      = new User_FriendModel();
		$this->messageTemplateModel = new Message_TemplateModel();
	}
	/**
	 * 会员信息--paycenter
	 *
	 * @access public
	 */
	public function linkUserInfo()
	{

		$url = Yf_Registry::get('ucenter_api_url') . '?ctl=User&met=getUserInfo';
		location_to($url);
		die();
	}
	/**
	 *获取会员信息
	 *
	 * @access public
	 */
	public function getUserInfo()
	{
		$user_id = Perm::$userId;

		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);
		
		$data = $this->userInfoModel->getInfo($user_id);
		$data = $data[$user_id];
		
		$privacy = $this->userPrivacyModel->getPrivacy($user_id);
		$privacy = $privacy[$user_id];
		
		if ('json' == $this->typ)
		{
			$data['district'] = $district;
			$data['privacy']  = $privacy;

			//$grade_row = $this->userGradeModel->getOne($data['user_grade']);
			//$data['user_grade_name'] = $grade_row['user_grade_name'];

			$User_FavoritesGoodsModel = new User_FavoritesGoodsModel();
			$User_FavoritesShopModel = new User_FavoritesShopModel();

			$data['favorites_goods_num'] = $User_FavoritesGoodsModel->getFavoritesGoodsNum($user_id);
			$data['favorites_shop_num'] = $User_FavoritesShopModel->getFavoritesShopNum($user_id);
			//$data['resource'] = $this->userResourceModel->getOne($user_id);

			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 *获取会员地址信息
	 *
	 * @access public
	 */
	public function editBespeak()
	{
		$user_id = Perm::$userId;
		$user['user_id']=$user_id;
		$USER_BespeakModel = new USER_BespeakModel();
		$data            = $USER_BespeakModel->getBespeakList($user);

		if ("json" == $this->typ)
		{
			foreach ($data as $key => $value) {
					if($value['bespeak_state']=='0'){
						$value['bespeak_state']='无效，审核不通过';
					}elseif ($value['bespeak_state']=='1') {
						$value['bespeak_state']='预约正在处理';
					}elseif ($value['bespeak_state']=='2') {
						$value['bespeak_state']='预约已完成';
					}
					$data[$key]=$value;
			}
			$num=count($data);
			// exit();
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}

	/**
	 *编辑会员地址信息
	 *
	 * @access public
	 */
	public function editBespeakInfo()
	{
		$user_id              = Perm::$userId;
		$id              = request_string('id');
		$usercontact    = request_string('usercontact');
		$true_name    = request_string('true_name');
		$bespeak_area_info = request_string('bespeak_area_info');
		$bespeak_com   = request_string('bespeak_com');
		$bespeak_title = request_string('bespeak_title');
		$bespeak_address = request_string('bespeak_address');
		$bes_address = request_string('bes_address');

		$edit_bespeak_row['true_name']                  = $true_name;
		$edit_bespeak_row['usercontact']                  = $usercontact;
		$edit_bespeak_row['bespeak_area_info']     = $bespeak_area_info;
		$edit_bespeak_row['bes_address'] = $bes_address;
		$edit_bespeak_row['bespeak_address'] = $bespeak_address;
		$edit_bespeak_row['bespeak_com']     = $bespeak_com;
		$edit_bespeak_row['bespeak_title']        = $bespeak_title;
		$edit_bespeak_row['starttime']        = get_date_time();
		$edit_bespeak_row['user_id']        = $user_id;

		//验证用户
		$cond_row = array(
			'user_id' => $user_id,
			'bespeak_id' => $id,
		);
		

		$re = $this->userBespeakModel->getByWhere($cond_row);


		if (!$re)
		{
			$msg    = _('failure');
			$status = 250;
		}
		else
		{
			//开启事物
			$rs_row = array();
			$this->userBespeakModel->sql->startTransactionDb();


			$flag = $this->userBespeakModel->editBespeak($id, $edit_bespeak_row);
			
			check_rs($flag, $rs_row);
			
			$flag = is_ok($rs_row);
			if ($flag !== false && $this->userBespeakModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$this->userBespeakModel->sql->rollBackDb();
				$msg    = _('failure');
				$status = 250;
			}

			$edit_bespeak_row['bespeak_id'] = $bespeak_id;
			$data                                = $edit_bespeak_row;
			$this->data->addBody(-140, $data, $msg, $status);
		}

	}
	
	/**
	 *增加会员地址信息
	 *
	 * @access public
	 */
	public function addBespeakInfo()
	{
		$user_id = Perm::$userId;

		$usercontact    = request_string('usercontact');
		$true_name    = request_string('true_name');
		$bespeak_area_info = request_string('bespeak_area_info');
		$bespeak_com   = request_string('bespeak_com');
		$bespeak_title = request_string('bespeak_title');
		$bespeak_address = request_string('bespeak_address');
		$bes_address = request_string('bes_address');

		$edit_bespeak_row['true_name']                  = $true_name;
		$edit_bespeak_row['usercontact']                  = $usercontact;
		$edit_bespeak_row['bespeak_area_info']     = $bespeak_area_info;
		$edit_bespeak_row['bes_address'] = $bes_address;
		$edit_bespeak_row['bespeak_address']     = $bespeak_address;
		$edit_bespeak_row['bespeak_com']     = $bespeak_com;
		$edit_bespeak_row['bespeak_title']        = $bespeak_title;
		$edit_bespeak_row['starttime']        = get_date_time();
		$edit_bespeak_row['user_id']        = $user_id;
		$edit_bespeak_row['bespeak_list']   = 0;

			//开启事物
		$this->userBespeakModel->sql->startTransactionDb();

		$flag = $this->userBespeakModel->addBespeak($edit_bespeak_row, true);
		$addess_id = $flag;
		fb($flag);
		check_rs($flag, $rs_row);
		$flag = is_ok($rs_row);
		if ($flag !== false && $this->userBespeakModel->sql->commitDb())
		{
			$edit_bespeak_row['bespeak_id'] = $addess_id;
			$status                              = 200;
			$msg                                 = _('success');
		}
		else
		{
			$this->userBespeakModel->sql->rollBackDb();
			
			$status = 250;
			$msg    = _('failure');
		}
		

		$data = $edit_bespeak_row;
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *删除会员地址信息
	 *
	 * @access public
	 */
	public function delBespeak()
	{
		$user_id         = Perm::$row['user_id'];
		$bespeak_id = request_string('bespeak_id');
		// $bespeak_id = request_string('bespeak_id');

		//验证用户
		$cond_row = array(
			'user_id' => $user_id,
			'bespeak_id' => $bespeak_id
		);
		$re       = $this->userBespeakModel->getByWhere($cond_row);

		if ($re)
		{
			$flag = $this->userBespeakModel->removeBespeak($bespeak_id);
		}
		else
		{
			$flag = false;
		}

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
	 *获取预约信息
	 *
	 * @access public
	 */

	public function bespeak()
	{
		$user_id = Perm::$userId;
		$act     = request_string('act');
		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$Base_DistrictModel  = new Base_DistrictModel();
		$district           = $Base_DistrictModel->getDistrictTree($district_parent_id);

		if ($act == 'edit')
		{
			$userId          = Perm::$userId;
			$bespeak_id = request_int('id');

			$data = $this->userBespeakModel->getBespeakInfo($bespeak_id);
		}
		elseif ($act == 'add')
		{
			$userId = Perm::$userId;

			$data = array();
		}
		elseif ($act == 'edit_delivery')
		{
			$userId = Perm::$userId;
			$data   = array();
		}
		else
		{
			$order_row['bespeak_id'] = $bespeak_id;

			$data = $this->userBespeakModel->getBespeakList($order_row);
		}
		$address_info=explode('-', $data['bespeak_area_info']);
		$data['province_id']=$address_info['0'];
		$data['city_id']=$address_info['1'];
		$data['area_id']=$address_info['2'];

		if ("json" == $this->typ)
		{
			// foreach ($data as $k=>$v)
			// {
			// 	var_dump($v);
			// 	$v['address_info'] = sprintf('%s %s %s', @$district_rows[$v['user_bespeak_province_id']]['district_name'], @$district_rows[$v['user_bespeak_city_id']]['district_name'], @$district_rows[$v['user_bespeak_area_id']]['district_name']);
			// 	$data[$k] = $v;
			// }


			$data_rows['address_list'] = $data;

			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}

	}


}


?>