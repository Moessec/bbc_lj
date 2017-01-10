<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_InfoCtl extends Yf_AppController
{
	public $userInfoModel     = null;
	public $userBaseModel     = null;

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
		
		$this->userInfoModel     = new User_InfoModel();
		$this->userBaseModel     = new User_BaseModel();

	}

	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id = request_int('user_id');

		/*
		'app_id' => '105',
		'rtime' => 1471925935,
		'user_area' => '河北 唐山市 丰润区',
		'user_areaid' => '1150',
		'user_avatar' => 'http://127.0.0.1/pcenter/trunk/image.php/ucenter/data/upload/media/plantform/image/20160813/1471057867864788.jpg!120x120.jpg',
		'user_birthday' => '1989-10-03',
		'user_cityid' => '74',
		'user_delete' => 0,
		'user_email' => '323@fdsfa.com',
		'user_mobile' => '',
		'user_provinceid' => '3',
		'user_qq' => '15524721181',
		'user_realname' => 'zsd12111',
		'user_sex' => '0',
		'key' => 'HANZaFR0Aw08PV1U02RzCW114UWXa26AUiIO',
		*/
		$user_email    = request_string('user_email');
		$user_mobile    = request_string('user_mobile');

		$user_realname = request_string('user_realname');
		$user_sex      = request_int('user_sex');
		$user_qq       = request_string('user_qq');
		$user_logo     = request_string('user_avatar');

		$user_delete   = request_int('user_delete');



		//$cond_row['user_passwd'] = md5($user_passwd);
		$edit_user_row['user_mobile']     = $user_mobile;
		$edit_user_row['user_email']    = $user_email;

		//$edit_user_row['user_sex']      = $user_sex;
		$edit_user_row['user_realname'] = $user_realname;
		$edit_user_row['user_qq']       = $user_qq;
		$edit_user_row['user_avatar']     = $user_logo;


		/*
		$edit_user_row['user_provinceid']     = $user_logo;
		$edit_user_row['user_cityid']     = $user_logo;
		$edit_user_row['user_areaid']     = $user_logo;
		$edit_user_row['user_area']     = $user_logo;
		$edit_user_row['user_birthday']     = $user_logo;
		*/

		$edit_base_row = array();
		isset($_REQUEST['user_delete']) ? $edit_base_row['user_delete'] = $user_delete : '';

		//开启事物
		$rs_row = array();
		$this->userInfoModel->sql->startTransactionDb();
		

		if ($edit_base_row)
		{
			$update_flag = $this->userBaseModel->editBase($user_id, $edit_base_row);
			check_rs($update_flag, $rs_row);
		}

		if ($edit_user_row)
		{
			$flag = $this->userInfoModel->editInfo($user_id, $edit_user_row);

			check_rs($flag, $rs_row);
		}


		$flag = is_ok($rs_row);

		if ($flag !== false && $this->userInfoModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userInfoModel->sql->rollBackDb();

			$status = 250;
			$msg    = _('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//获取用户资源信息
	public function getUserResourceInfo()
	{
		$user_id = request_int('user_id');


		$User_ResourceModel = new User_ResourceModel();

		$data = $User_ResourceModel->getOne($user_id);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);

	}


	//修改用户资源信息
	public function editUserResourceInfo()
	{
		$user_id = request_int('user_id');

		$money = request_float('money');
		$pay_type = request_string('pay_type');


		$edit_row = array();
		//修改现金账户
		if($pay_type == 'cash')
		{
			$edit_row['user_money'] = $money;
		}
		if($pay_type == 'frozen_cash')
		{
			$edit_row['user_money_frozen'] = $money;
		}

		$User_ResourceModel = new User_ResourceModel();

		$data = $User_ResourceModel->editResource($user_id,$edit_row,true);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/* 获取用户支付账户信息
	 * webpos 请求该接口
	*/
	public function getPayUserInfo()
	{
		$cond_row   = array();
		$data       = array();
		$user_id    = request_int('user_id');
		$pay_money  = request_float('money');

		$cond_row['user_id'] = $user_id;
		$cond_row['user_pay_passwd'] = request_string('user_pay_passwd');

		$User_BaseModel     = new User_BaseModel();
		$user_base_row = $User_BaseModel->getOneByWhere($cond_row);
		$flag  = true;

		if(!$user_base_row)
		{
			$flag = false;
			$msg  = _('支付账号不存在或密码有误！');
		}
		else
		{
			$User_ResourceModel = new User_ResourceModel();
			$user_resource_row = $User_ResourceModel->getOne($user_id);
			if($pay_money > $user_resource_row['user_money'])
			{
				$flag = false;
				$msg  = _('账户余额不足！');
			}
		}

		if($flag)
		{
			$status = 200;
			$msg = $msg?$msg:_('success');
		}
		else
		{
			$status = 250;
			$msg = $msg?$msg:_('fail');
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}


}

?>