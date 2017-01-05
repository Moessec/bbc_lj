<?php

class LoginCtl extends WebPosController
{
	public function index()
	{
		include $this->view->getView();
	}
	
	/**
	 * 用户登录
	 *
	 * @access public
	 */
	public function login()
	{
		session_start();
        if (strtolower($_SESSION['auth']) != strtolower($_REQUEST['yzm']))
        {
            location_go_back('验证码错误');
        }
        
        $user_account = $_REQUEST['user_account'];
        //本地读取远程信息
        $key = Yf_Registry::get('ucenter_api_key');;
        $url                       = Yf_Registry::get('ucenter_api_url');
        $ucenter_app_id            = Yf_Registry::get('ucenter_app_id');
        $formvars                  = array();
        $formvars['user_account']  = $_REQUEST['user_account'];
        $formvars['user_password'] = $_REQUEST['user_password'];
        $formvars['app_id']        = $ucenter_app_id;
        
        $formvars['ctl'] = 'Api';
        $formvars['met'] = 'login';
        $formvars['typ'] = 'json';
        $init_rs         = get_url_with_encrypt($key, $url, $formvars);

        if (200 == $init_rs['status'])
        {
            //远程获取店铺信息
            $shop_api_key                   = Yf_Registry::get('shop_api_key');;
            $shop_api_url                   = Yf_Registry::get('shop_api_url');
            $shop_app_id                    = Yf_Registry::get('shop_app_id');
            $formvars_shop                  = array();
            $formvars_shop['user_id']   = $init_rs['data']['user_id'];
            $formvars_shop['app_id']       = $shop_app_id;
            $init_rs_shop         = get_url_with_encrypt($shop_api_key, sprintf('%s?ctl=WebPosApi_%s&met=%s&typ=json', $shop_api_url, 'Shop', 'getShopInfo'), $formvars_shop);
             

             // var_dump($shop_api_key,$shop_api_url,$shop_app_id);die;
             // var_dump($formvars_shop);die;


            if (200 == $init_rs_shop['status'])
            {

                //读取服务列表
                $shop_info_row = $init_rs_shop['data'];  //店铺信息
                $user_id   = $init_rs['data']['user_id'];
                $user_name = $init_rs['data']['user_name'];


                $User_BaseModel  = new User_BaseModel();
                $User_InfoModel  = new User_InfoModel();

                //本地数据校验登录
                $user_row = $User_BaseModel->getOne($user_id);

                if ($user_row)
                {
                    //判断状态是否开启
                    if ($user_row['user_delete'] == 1)
                    {
                        $msg = _('该账户未启用，请启用后登录！');
                        if ('e' == $this->typ)
                        {
                            location_go_back(_('初始化用户出错!'));
                        }
                        else
                        {
                            return $this->data->setError($msg, array());
                        }
                    }
                }
                else
                {
                    //添加用户
                    $data['user_id']        = $init_rs['data']['user_id']; // 用户id
                    $data['user_account']  = $init_rs['data']['user_name']; // 用户帐号
                    $data['shop_id']        = $shop_info_row['shop_id']; // 用户帐号
                    $data['user_account']  = $shop_info_row['shop_name']; // 用户帐号
                    $data['user_key']  = 'ttt'; // 用户帐号
                    $data['user_delete'] = 0; // 用户状态

                    $flag             = $User_BaseModel->addBase($data, true);
                    //判断状态是否开启
                    if (!$flag)
                    {
                        $msg = _('初始化用户出错!');
                        if ('e' == $this->typ)
                        {
                            location_go_back(_('初始化用户出错!'));
                        }
                        else
                        {
                            return $this->data->setError($msg, array());
                        }
                    }
                    else
                    {
                        //初始化用户信息
                        $user_info_row                  = array();
                        $user_info_row['user_id']       = $user_id;
                        $user_info_row['user_nickname'] = @$init_rs['data']['user_name'];
                        $User_InfoModel                 = new User_InfoModel();
                        $info_flag                      = $User_InfoModel->addInfo($user_info_row);
                    }

                    $user_row = $data;
                }

                if($user_row)
                {
                    $data            	= array();
                    $data['user_id'] 	= $user_row['user_id'];
                    $data['user_name'] = $user_row['user_account'];
                    $data['shop_id'] 	= $user_row['shop_id'];
                    $data['shop_name'] = $user_row['shop_name'];
                    srand((double)microtime() * 1000000);
                    $user_key = 'ttt';
                    Yf_Hash::setKey($user_key);
                    Perm::encryptUserInfo($data);

                    location_to(Yf_Registry::get('base_url'));
                }
            }
            else
            {
                location_go_back(isset($init_rs['msg']) ? '登录失败,店铺信息不存在!' . $init_rs['msg'] : '登录失败,店铺信息不存在!');
            }
		}
		else
		{
			location_go_back(isset($init_rs['msg']) ? '登录失败,请重新登录!' . $init_rs['msg'] : '登录失败,请重新登录!');
		}
	}

	/*
	 * 用户退出
	 *
	 *
	 */
	public function loginout()
	{
		if ($_REQUEST['met'] == 'loginout')
		{
			if (isset($_COOKIE['key']) || isset($_COOKIE['id']))
			{
				setcookie("key", null, time() - 3600 * 24 * 365);
				setcookie("id", null, time() - 3600 * 24 * 365);
				echo "<script>parent.location.href='" . Yf_Registry::get('base_url') . "';</script>";
			}
			else
			{
				echo "<script>parent.location.href='" . Yf_Registry::get('base_url') . "';</script>";
			}
		}
	}

	public function getCheckCode()
	{
		session_start();
		//===============================
		$width  = $_GET['w'] ? $_GET['w'] : "80";
		$height = $_GET['h'] ? $_GET['h'] : "33";
		$image  = new ValidationCode($width, $height, '4');
		$image->outImg();
		$_SESSION["auth"] = $image->checkcode;
	}
}

?>


