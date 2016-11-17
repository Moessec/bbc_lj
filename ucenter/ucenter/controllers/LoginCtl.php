<?php

class LoginCtl extends Yf_AppController
{

	public function callback()
	{
		echo 'callback 地址';
	}
	public function select()
	{
		include $this->view->getView();
	}
	public function index()
	{	echo '<br>'233;exit;
		//如果已经登录,则直接跳转
		if (Perm::checkUserPerm())
		{
			$data = Perm::$row;

			$k = $_COOKIE[Perm::$cookieName];
			$u = $_COOKIE[Perm::$cookieId];

			if (isset($_REQUEST['callback']))
			{
				$url = $_REQUEST['callback'] . '&us=' . $u . '&ks=' . urlencode($k);

			}
			else
			{
				$url = './index.php';
			}

			header('location:' . $url);
		}
		else
		{

			include $this->view->getView();
		}
	}

	public function regist()
	{
		//如果已经登录,则直接跳转
		if (Perm::checkUserPerm())
		{
			$data = Perm::$row;

			$k = $_COOKIE[Perm::$cookieName];
			$u = $_COOKIE[Perm::$cookieId];

			if (isset($_REQUEST['callback']))
			{
				$url = $_REQUEST['callback'] . '&us=' . $u . '&ks=' . urlencode($k);

			}
			else
			{
				$url = './index.php';
			}

			header('location:' . $url);
		}
		else
		{
			$url = './index.php?ctl=Login&act=reg';
			header('location:' . $url);
		}

	}

	public function findpwd()
	{
		//include $this->view->getView();
		$url = './index.php?ctl=Login&act=reset';
		header('location:' . $url);
	}
//localhost/pcenter/index.php?ctl=Login&from=im&callback=http://RYTUY
	/**
	 * 手机获取注册码
	 *
	 * @access public
	 */
	public function regCode()
	{
		$mobile = request_string('mobile');

		//判断手机号是否已经注册过
		$User_InfoDetail = new User_InfoDetailModel();


		$checkmobile = $User_InfoDetail->checkMobile($mobile);

		//fb($checkmobile);

		if($checkmobile)
		{
			//$data   = array();
			//$msg    = 'success';
			//$status = 200;
			//$this->data->addBody(-140, $data, $msg, $status);
			$this->data->addBody('该手机号已注册');
			die();
		}

		$data = array();

		$data['user_code'] = rand(1000, 9999);

		$config_cache = Yf_Registry::get('config_cache');

		if (!file_exists($config_cache['default']['cacheDir']))
		{
			fb($config_cache['default']['cacheDir']);
			mkdir($config_cache['default']['cacheDir']);
		}
		$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

		$Cache_Lite->save($data['user_code'], $mobile);

		//发送短消息
		$contents = '您的验证码是：' . $data['user_code'] . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';

		$result = Sms::send($mobile, $contents);
		/*
				$contents = array($data['user_code'], 2);
				$tpl_id = 63463;
				$result = Sms::send($mobile, $contents, $tpl_id);
		*/
		{
			if (true)
			{
				$msg    = 'success';
				$status = 200;
			}
			else
			{
				$msg    = '失败';
				$status = 250;
			}

		}


		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 手机获取找回密码验证码
	 *
	 * @access public
	 */
	public function findPasswdCode()
	{
		$mobile = request_string('mobile');

		/*$user_name = request_string('user_name');
		//验证手机号是否是用户手机号
		$User_InfoDetail = new User_InfoDetailModel();
		$mobile_info = $User_InfoDetail->getInfoDetail($user_name);
		$mobile_info = array_values($mobile_info);
		$user_mobile = $mobile_info[0]['user_mobile'];
		
		if($mobile != $user_mobile)
		{
			$this->data->setError('请填写注册手机号');
			return false;
		}*/


		//判断用户是否存在  $mobile
		if (true)
		{
			$data = array();

			$data['user_code'] = rand(1000, 9999);

			$config_cache = Yf_Registry::get('config_cache');

			if (!file_exists($config_cache['default']['cacheDir']))
			{
				mkdir($config_cache['default']['cacheDir']);
			}

			$Cache_Lite = new Cache_Lite_Output($config_cache['default']);

			$Cache_Lite->save($data['user_code'], $mobile);

			//发送短消息
			$contents = '您的验证码是：' . $data['user_code'] . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';

			$result = Sms::send($mobile, $contents);

			{
				if (true)
				{
					$msg    = 'success';
					$status = 200;
				}
				else
				{
					$msg    = '失败';
					$status = 250;
				}

			}
		}
		else
		{
			$msg    = '用户账号不存在';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function resetPasswd()
	{
		//
		$user_code = request_string('user_code');

		$from = request_string('from');

		$data           = array();
		
		$data['mobile'] = request_string('mobile');
		$data['password'] = md5(request_string('user_password'));
		$data['passworderp'] = request_string('user_password');
		//为erp做的修改密码
		if($from == 'erp')
		{
			$data['user']   = request_string('user_account');
			$User_InfoModel = new User_InfoModel();

			//检测登录状态
			$user_id_row = $User_InfoModel->getInfoByName($data['user']);

			if ($user_id_row)
			{
				//重置密码
				$user_id          = $user_id_row['user_id'];
				$reset_passwd_row = array();

				$reset_passwd_row['password'] = $data['passworderp'];

				$flag = $User_InfoModel->editInfo($user_id, $reset_passwd_row);

				if ($flag)
				{
					$msg    = '重置密码成功';
					$status = 200;

				}
				else
				{
					$msg    = '重置密码失败';
					$status = 250;
				}
			}
			else
			{
				$msg    = '用户不存在';
				$status = 250;
			}

			unset($data['password']);
		}else
{

		
		

		if ($user_code)
		{
			if (!$data['mobile'])
			{
				$this->data->setError('手机号不能为空');
				return false;
			}

			if (request_string('user_password'))
			{

				$config_cache = Yf_Registry::get('config_cache');
				$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

				$user_code_pre = $Cache_Lite->get($data['mobile']);
				fb($user_code_pre);

				if ($user_code == $user_code_pre)
				{
					$User_InfoModel = new User_InfoModel();
					$User_InfoDetailModel = new User_InfoDetailModel();

					//检测登录状态
					fb($data['mobile']);
					$data['user'] = $User_InfoDetailModel->getUserByMobile($data['mobile']);
					
					$user_id_row = $User_InfoModel->getInfoByName($data['user']);

					if ($user_id_row)
					{
						//重置密码
						$user_id          = $user_id_row['user_id'];
						$reset_passwd_row = array();

						$reset_passwd_row['password'] = $data['password'];

						$flag = $User_InfoModel->editInfo($user_id, $reset_passwd_row);

						if ($flag === 'false')
						{
							$msg    = '网路故障，请稍后重试';
							$status = 250;
						}
						else
						{
							$msg    = '重置密码成功';
							$status = 200;

							$Cache_Lite->remove($data['mobile']);
						}
					}
					else
					{
						$msg    = '用户不存在';
						$status = 250;
					}
				}
				else
				{
					$msg    = '验证码错误';
					$status = 250;
				}

			}
			else
			{
				$msg    = '密码不能为空';
				$status = 250;
			}
		}
		else
		{
			$msg    = '验证码不能为空';
			$status = 250;
		}

		unset($data['password']);

}

		$this->data->addBody(-140, $data, $msg, $status);
	}


	public function register()
	{
		$token = request_string('t');

		$app_id = request_int('app_id');

		$user_name = request_string('user_account', null);
		$password  = request_string('user_password', null);


		$user_code = request_string('user_code');
		$mobile    = request_string('mobile');

		$server_id = 0;

		if (!$user_name)
		{
			$this->data->setError('请输入账号');
			return false;
		}

		if (!$password)
		{
			$this->data->setError('请输入密码');
			return false;
		}
		if (!$user_code)
		{
			$this->data->setError('请输入验证码');
			return false;
		}
		if (!$mobile)
		{
			$this->data->setError('请输入手机号');
			return false;
		}

		$config_cache = Yf_Registry::get('config_cache');
		$Cache_Lite   = new Cache_Lite_Output($config_cache['default']);

		$user_code_pre = $Cache_Lite->get($mobile);

		if ($user_code == $user_code_pre)
		{
			$rs_row = array();

			//用户是否存在
			$User_InfoModel  = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetailModel();

			$user_rows = $User_InfoModel->getInfoByName($user_name);

			if ($user_rows)
			{
				$this->data->setError('用户已经存在,请更换用户名!');
				return false;
			}
			else
			{

				$User_InfoModel->sql->startTransaction();

				$Db       = Yf_Db::get('ucenter');
				$seq_name = 'user_id';
				$user_id  = $Db->nextId($seq_name);
				
				//$User_InfoModel->check_input($user_name, $password, $user_mobile);

				$now_time = time();
				$ip       = get_ip();

				$session_id                         = uniqid();
				$arr_field_user_info                = array();
				$arr_field_user_info['user_id']     = $user_id;
				$arr_field_user_info['user_name']   = $user_name;
				$arr_field_user_info['password']    = md5($password);
				$arr_field_user_info['action_time'] = $now_time;
				$arr_field_user_info['action_ip']   = $ip;
				$arr_field_user_info['session_id']  = $session_id;

				$flag = $User_InfoModel->addInfo($arr_field_user_info);
				array_push($rs_row, $flag);

				$arr_field_user_info_detail                        = array();

				{
					//添加mobile绑定.
					//绑定标记：mobile/email/openid  绑定类型+openid
					$bind_id = sprintf('mobile_%s', $mobile);


					//查找bind绑定表
					$User_BindConnectModel = new User_BindConnectModel();
					$bind_info = $User_BindConnectModel->getOne($bind_id);

					if (!$bind_info)
					{
						$time = date('Y-m-d H:i:s',time());

						//插入绑定表
						$bind_array = array(
							'bind_id'=>$bind_id,
							'user_id'=>$user_id,
							'bind_type'=>$User_BindConnectModel::MOBILE,
							'bind_time'=>$time
						);

						$flag = $User_BindConnectModel->addBindConnect($bind_array);
						array_push($rs_row, $flag);


						//手机绑定关系
						$arr_field_user_info_detail['user_mobile']         = $mobile;
						$arr_field_user_info_detail['user_mobile_verify']         = 1;
					}
				}


				$arr_field_user_info_detail['user_name']           = $user_name;
				//$arr_field_user_info_detail['user_mobile']         = $mobile;
				//$arr_field_user_info_detail['user_mobile_verify']         = 1;
				$arr_field_user_info_detail['user_reg_time']       = $now_time;
				$arr_field_user_info_detail['user_count_login']    = 1;
				$arr_field_user_info_detail['user_lastlogin_time'] = $now_time;
				$arr_field_user_info_detail['user_lastlogin_ip']   = $ip;
				$arr_field_user_info_detail['user_reg_ip']         = $ip;

				$flag = $User_InfoDetail->addInfoDetail($arr_field_user_info_detail);
				array_push($rs_row, $flag);

			}

			$app_id   = isset($_REQUEST['app_id']) ? $_REQUEST['app_id'] : 0;
			$Base_App = new Base_AppModel();

			if ($app_id && !($base_app_rows = $Base_App->getApp($app_id)))
			{
				/*
				$base_app_row = array_pop($base_app_rows);

				$arr_field_user_app = array();
				$arr_field_user_app['user_name'] = $user_name;
				$arr_field_user_app['app_id'] = $app_id;
				$arr_field_user_app['active_time'] = time();

				$User_App = new User_AppModel();

				//是否存在
				$user_app_row = $User_App->getAppByNameAndAppId($user_name, $app_id);

				if ($user_app_row)
				{
					// update app_quantity
					$app_quantity_row = array();
					$app_quantity_row['app_quantity'] = $user_app_row['app_quantity'] + 1;
					$flag = $User_App->editApp($user_name, $app_quantity_row);
					array_push($rs_row, $flag);
				}
				else
				{

					$flag = $User_App->addApp($arr_field_user_app);
					array_push($rs_row, $flag);

				}

				$User_AppServerModel = new User_AppServerModel();

				$user_app_server_row = array();
				$user_app_server_row['user_name'] = $user_name;
				$user_app_server_row['app_id'] = $app_id;
				$user_app_server_row['server_id'] = $server_id;
				$user_app_server_row['active_time'] = time();

				$flag = $User_AppServerModel->addAppServer($user_app_server_row);
				*/
			}
			else
			{
			}

			if (is_ok($rs_row) && $User_InfoDetail->sql->commit())
			{
				$d            = array();
				$d['user_id'] = $user_id;

				$encrypt_str = Perm::encryptUserInfo($d, $session_id);

				$arr_body = array(
					"user_name" => $user_name,
					"server_id" => $server_id,
					"k" => $encrypt_str,
					"user_id" => $user_id
				);

				if($token)
				{
					//查找bind绑定表
					$User_BindConnectModel = new User_BindConnectModel();
					$bind_info = $User_BindConnectModel->getBindConnectByToken($token);
					
					$bind_info = $bind_info[0];

					//获取qq缩略头像
					$qq_logo = substr($bind_info['bind_avator'], 0,strrpos($bind_info['bind_avator'],'/'));
					$qq_logo = $qq_logo.'/40';
					//更新用户详情表
					if($bind_info['bind_gender'] == 1)
					{
						$gender = 1;
					}else
					{
						$gender = 0;
					}
					$user_info_detail = array(
								'nickname'         => $bind_info['bind_nickname'],
								'user_avatar'      => $bind_info['bind_avator'],
								'user_gender'      => $gender,
								'user_avatar_thumb'=> $qq_logo,
						);
					

					$User_InfoDetail->editInfoDetail($user_name,$user_info_detail);

					$time = date('Y-m-d H:i:s',time());

					//插入绑定表
					$bind_array = array(
									'user_id'=>$user_id,
									'bind_time'=>$time,
									'bind_token'=>$token,
									);
					$User_BindConnectModel->editBindConnect($bind_info['bind_id'],$bind_array);
				}
				$this->data->addBody(100, $arr_body);


			}
			else
			{
				$Base_App->sql->rollBack();
				$this->data->setError('创建用户信息失败');
			}
		}
		else
		{
			$msg    = '验证码错误';
			$status = 250;
			$this->data->addBody(-1, array(), $msg, $status);
		}
	}

	public function loginex()
	{
		$token = request_string('t');
		fb($token);
		$user_name = strtolower($_REQUEST['user_account']);

		if (!$user_name)
		{
			$user_name = strtolower($_REQUEST['user_name']);
		}

		$password = $_REQUEST['user_password'];

		$md5_password = $_REQUEST['md5_password'];

		if (!$password)
		{
			$password = $_REQUEST['password'];
		}

		if (!strlen($user_name))
		{
			$this->data->setError('请输入账号');
			return false;
		}
		

		if (!strlen($password)  && !strlen($md5_password))
		{
			$this->data->setError('请输入密码');
		}
		else
		{
			$User_InfoModel  = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetailModel();
			$user_info_row   = $User_InfoModel->getInfoByName($user_name);

			if (!$user_info_row)
			{
				$this->data->setError('账号不存在');
				return false;
			}

			if($password)
			{
				$pswd =  md5($password);
			}
			if($md5_password)
			{
				$pswd = $md5_password;
			}
			if ($pswd != $user_info_row['password'])
			{
				$this->data->setError('密码错误');
			}
			else
			{
				//$session_id = uniqid();
				$session_id = $user_info_row['session_id'];

				$arr_field               = array();
				$arr_field['session_id'] = $session_id;

				//if ($User_InfoModel->editInfo($user_info_row['user_id'], $arr_field) > 0)
				if (true)
				{
					//$arr_body = array("result"=>1,"user_name"=>$user_info_row['user_name'],"session_id"=>$session_id);
					$arr_body           = $user_info_row;
					$arr_body['result'] = 1;
					//$arr_body['session_id'] = $session_id;

					$data            = array();
					$data['user_id'] = $user_info_row['user_id'];
					
					//$data['session_id'] = $session_id;
					$encrypt_str = Perm::encryptUserInfo($data, $session_id);

					$arr_body['k'] = $encrypt_str;

					//插入绑定表
					if($token)
					{
						//查找bind绑定表
						$User_BindConnectModel = new User_BindConnectModel();
						$bind_info = $User_BindConnectModel->getBindConnectByToken($token);
						
						$bind_info = $bind_info[0];

						//插入绑定表
						$time = date('Y-m-d H:i:s',time());
						$User_BindConnectModel = new User_BindConnectModel();
						$bind_array = array(
										'user_id'	=>$user_info_row['user_id'],
										'bind_time'	=>$time,
										'bind_token'=>$token,
										);
						$User_BindConnectModel->editBindConnect($bind_info['bind_id'],$bind_array);
						
					}

					$this->data->addBody(100, $arr_body);
					
				}
				else
				{
					$this->data->setError('登录失败');
				}
			}

		}


	}
	
	public function login()
	{
		$token = request_string('t');
		$type = request_int('type');

		$user_name = strtolower($_REQUEST['user_account']);

		//查找bind绑定表
		$User_BindConnectModel = new User_BindConnectModel();
		
		if (!$user_name)
		{
			$user_name = strtolower($_REQUEST['user_name']);
		}

		$password = $_REQUEST['user_password'];

		$md5_password = request_string('md5_password');

		if (!$password)
		{
			$password = $_REQUEST['password'];
		}

		if (!strlen($user_name))
		{
			$this->data->setError('请输入账号');
			return false;
		}
		

		if (!strlen($password)  && !strlen($md5_password))
		{
			$this->data->setError('请输入密码');
		}
		else
		{
			$User_InfoModel  = new User_InfoModel();
			$User_InfoDetail = new User_InfoDetailModel();

			$bind_id = '';
			//添加mobile绑定.
			//绑定标记：mobile/email/openid  绑定类型+openid
			{
				if (filter_var($user_name, FILTER_VALIDATE_EMAIL))
				{
					//邮件登录
					$bind_id = sprintf('email_%s', $user_name);
				}
				elseif (Yf_Utils_String::isMobile($user_name))
				{
					//手机号登录
					$bind_id = sprintf('mobile_%s', $user_name);
				}

				if ($bind_id)
				{
					//查找bind绑定表
					$User_BindConnectModel = new User_BindConnectModel();
					$bind_info = $User_BindConnectModel->getOne($bind_id);

					if ($bind_info)
					{
						//用户名登录
						$user_info_row   = $User_InfoModel->getOne($bind_info['user_id']);
					}
				}


				if ($user_info_row)
				{
				}
				else
				{
					//用户名登录
					$user_info_row   = $User_InfoModel->getInfoByName($user_name);
				}

			}

			if (!$user_info_row)
			{
				$this->data->setError('账号不存在');
				return false;
			}

			if($password)
			{
				$pswd =  md5($password);
			}
			if($md5_password)
			{
				$pswd = $md5_password;
			}
			if ($pswd != $user_info_row['password'])
			{
				$this->data->setError('密码错误');
			}
			else
			{
				if (3 == $user_info_row['user_state'])
				{
					$this->data->setError('用户已经锁定,禁止登录!');
					return false;
				}

				//$session_id = uniqid();
				$session_id = $user_info_row['session_id'];

				$arr_field               = array();
				$arr_field['session_id'] = $session_id;

				//if ($User_InfoModel->editInfo($user_info_row['user_id'], $arr_field) > 0)
				if($user_info_row['user_id'] != 0 && $token)
				{
					$bind_id_row = $User_BindConnectModel->getBindConnectByuserid($user_info_row['user_id'],$type);
					if($bind_id_row)
					{
						$this->data->setError('账号已绑定');
						return false;
					}
				}

				if (true)
				{
					//$arr_body = array("result"=>1,"user_name"=>$user_info_row['user_name'],"session_id"=>$session_id);
					$arr_body           = $user_info_row;
					$arr_body['result'] = 1;
					//$arr_body['session_id'] = $session_id;

					$data            = array();
					$data['user_id'] = $user_info_row['user_id'];
					
					//$data['session_id'] = $session_id;
					$encrypt_str = Perm::encryptUserInfo($data, $session_id);

					$arr_body['k'] = $encrypt_str;

					//插入绑定表
					if($token)
					{
						
						$bind_info = $User_BindConnectModel->getBindConnectByToken($token);
						
						$bind_info = $bind_info[0];

						//插入绑定表
						$time = date('Y-m-d H:i:s',time());
						$User_BindConnectModel = new User_BindConnectModel();
						$bind_array = array(
										'user_id'	=>$user_info_row['user_id'],
										'bind_time'	=>$time,
										'bind_token'=>$token,
										);
						$User_BindConnectModel->editBindConnect($bind_info['bind_id'],$bind_array);
						
					}

					$this->data->addBody(100, $arr_body);
					
				}
				else
				{
					$this->data->setError('登录失败');
				}
			}

		}

		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}


	/*
	 * 用户退出
	 *
	 *
	 */
	public function loginout()
	{
		if (isset($_COOKIE['key']) || isset($_COOKIE['id']))
		{
			setcookie("key", null, time() - 3600 * 24 * 365);
			setcookie("id", null, time() - 3600 * 24 * 365);
		}

		//如果已经登录,则直接跳转
		if (isset($_REQUEST['callback']))
		{
			$url = urldecode($_REQUEST['callback']);
		}
		else
		{
			$url = Yf_Registry::get('url');
		}


		if ('e' == $this->typ)
		{
			header('location:' . $url);
			die();
		}
		else
		{
			$this->data->addBody(-1, array());

			if ($jsonp_callback = request_string('jsonp_callback'))
			{
				exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
			}
		}
	}

	public function logout()
	{
		$this->loginout();
	}

	/*
	 * 检测用户登录
	 */
	public function checkLogin()
	{
		if (Perm::checkUserPerm())
		{
			$msg = '数据正确';
			$status = 200;
			$data = Perm::$row;

			//user detail


			$User_InfoDetailModel = new User_InfoDetailModel();
			$data_info = $User_InfoDetailModel->getOne($data['user_name']);

			$data = array_merge($data, $data_info);

			if (!$data['user_avatar'])
			{
				$data['user_avatar'] =  Web_ConfigModel::value('user_default_avatar', Yf_Registry::get('static_url') . '/images/default_user_portrait.png');
			}

			unset($data['password']);
			//unset($data['session_id']);
		}
		else
		{
			$msg = '权限错误';
			$status = 250;
			$data = array();
			$data['k'] = $_COOKIE['key'];
			$data['u'] = $_COOKIE['id'];
		}

		$this->data->addBody(100, $data, $msg, $status);
	}

	public function getUserByName()
	{
		$user_name = request_string('user_name');

		$User_InfoModel = new User_InfoModel();

		$user_id_row = $User_InfoModel->getInfoByName($user_name);

		$data = array();
		if($user_id_row)
		{
			$data = $user_id_row;
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = '用户不存在';
			$status = 250;
		}
		$this->data->addBody(-1, $data, $msg, $status);

	}

	public function getUserInfoDetail()
	{
		$user_name = request_string('user_name');

		$User_InfoDetailModel = new User_InfoDetailModel();

		$user_info = $User_InfoDetailModel->getInfoDetail($user_name);
		
		$this->data->addBody(100, $user_info);

	}

	public function checkStatus()
	{
		$data = array();

		//如果已经登录,则直接跳转
		if (Perm::checkUserPerm())
		{
			$data = Perm::$row;

			$k = $_COOKIE[Perm::$cookieName];
			$u = $_COOKIE[Perm::$cookieId];


			$data['ks'] = $k;
			$data['us'] = $u;


			$msg    = '已登录';
			$status = 200;

		}
		else
		{
			$msg    = '未登录';
			$status = 250;
		}

		$this->data->addBody(-1, $data, $msg, $status);

		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}
}

?>