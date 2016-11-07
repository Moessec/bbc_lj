<?php

class Api_UserCtl extends Api_Controller
{
	public function getUserInfo()
	{
		$user_id = request_string('user_id');

		$user_info_row = array();

		if ($user_id)
		{
			$User_InfoModel = new User_InfoModel();
			$user_row = $User_InfoModel->getOne($user_id);

			if ($user_row)
			{
				$User_InfoDetailModel = new User_InfoDetailModel();
				$user_info_row = $User_InfoDetailModel->getOne($user_row['user_name']);
			}
			else
			{
			}
		}

		$this->data->addBody(100, $user_info_row);
	}


	//获取列表信息
	public function listUser()
	{
		$skey = request_string('skey');
		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$asc = $_REQUEST['asc'];
		$userInfoModel = new User_InfoDetailModel();

		$items = array();
		$cond_row = array();
		$order_row = array();

		if($skey)
		{
			$cond_row['user_name:LIKE'] = '%'.$skey.'%';
		}

		$data = $userInfoModel->getInfoDetailList($cond_row, $order_row, $page, $rows);

		if($data){
			$msg = 'success';
			$status = 200;
		}
		else{
			$msg = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}


	function change()
	{
		$user_name = request_string('id');
		$status = $_REQUEST['server_status'];
		$userInfoModel = new User_InfoModel();

		if($user_name)
		{
			if($status==0)
			{
				$data['user_state'] = 3;
			}
			elseif($status==1)
			{
				$data['user_state'] = 0;
			}

			$user_id = $userInfoModel->getUserIdByName($user_name);
			$flag = $userInfoModel->editInfo($user_id, $data);

			if(false !== $flag)
			{
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
		}
		$this->data->addBody(-140,array(),$msg,$status);
	}

	//解除绑定,生成验证码,并且发送验证码
	public function getYzm()
	{

		$type = request_string('type');
		$val  = request_string('val');

		$cond_row['code'] = 'Lift verification';

		$de = $this->messageTemplateModel->getTemplateDetail($cond_row);

		fb($de);
		if ($type == 'mobile')
		{
			$me = $de['content_phone'];

			$code_key = $val;
			$code     = VerifyCode::getCode($code_key);
			$me       = str_replace("[weburl_name]", $this->web['web_name'], $me);
			$me       = str_replace("[yzm]", $code, $me);

			$str = Sms::send($val, $me);
		}
		else
		{
			$me    = $de['content_email'];
			$title = $de['title'];

			$code_key = $val;
			$code     = VerifyCode::getCode($code_key);
			$me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
			$me       = str_replace("[yzm]", $code, $me);
			$title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

			$str = Email::send($val, Perm::$row['user_account'], $title, $me);
		}
		$status = 200;
		$data   = array($code);
		$msg    = "success";
		$this->data->addBody(-140, $data, $msg, $status);

	}


	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id   = request_int('user_id');
		$user_name = request_string('user_name');

		$edit_user_row['user_gender']     = request_int('user_gender');
		$edit_user_row['user_avatar']   = request_string('user_logo');
		$user_delete = request_int('user_delete');

		//开启事物
		$User_InfoDetailModel  = new User_InfoDetailModel();
		$rs_row = array();
		$User_InfoDetailModel->sql->startTransactionDb();

		$User_InfoModel = new User_InfoModel();
		$user_row = $User_InfoModel->getOne($user_id);
		if($user_delete)
		{
			$edit_user['user_state'] = 3;
			$flagState =$User_InfoModel->editInfo($user_id,$edit_user);
			check_rs($flagState, $rs_row);
		}
		else
		{
			if($user_row['user_state'] == 3)
			{
				$edit_user['user_state'] = 0;  //解禁后用户状态恢复到未激活
				$flagState =$User_InfoModel->editInfo($user_id,$edit_user);
				check_rs($flagState, $rs_row);
			}
		}

		$flag = $User_InfoDetailModel->editInfoDetail($user_name, $edit_user_row);
		check_rs($flag, $rs_row);

		$flag = is_ok($rs_row);

		if ($flag && $User_InfoDetailModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');


			$User_InfoDetailModel->sync($user_id);
		}
		else
		{
			$User_InfoDetailModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');

		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>