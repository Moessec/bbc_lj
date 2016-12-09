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
class Api_ConfigCtl extends Api_Controller
{
	/**
	 * Constructor
	 * @param string $args 参数
	 * @return void
	 */
	public function __call($method, $args)
	{
		$config_type = $this->met;

		$config_type_row = request_row('config_type');

		if (!$config_type_row)
		{
			$config_type_row = array($config_type);
		}

		$Web_ConfigModel = new Web_ConfigModel();

		$data = array();
		foreach ($config_type_row as $config_type)
		{
			$data_tmp = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));
			$data     = $data + $data_tmp;

			//系统环境上传变量
			if ('upload' == $config_type)
			{
				$sys_max_upload_file_size         = min(Yf_Utils_File::getByteSize(ini_get('upload_max_filesize')), Yf_Utils_File::getByteSize(ini_get('memory_limit')), Yf_Utils_File::getByteSize(ini_get('post_max_size'))) / 1024;
				$data['sys_max_upload_file_size'] = $sys_max_upload_file_size;
			}

			//站点设置
			if ('site' == $config_type)
			{
				//系统可选语言包
				$file_row = scandir(LAN_PATH);

				$language_row = array();

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_dir(LAN_PATH . '/' . $file))
					{
						$language_row[] = array(
							'id' => $file,
							'name' => $file
						);
					}
				}

				$data['language_row'] = $language_row;

				//系统可选风格
				$data['theme_row'] = array();
				$theme_dir         = APP_PATH . '/views/';
				$file_row          = scandir($theme_dir);

				$theme_row = array();

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_dir($theme_dir . '/' . $file))
					{
						$theme_row[] = array(
							'id' => $file,
							'name' => $file
						);
					}
				}

				$data['theme_row'] = $theme_row;
			}

			//插件设置
			if ('plugin' == $config_type)
			{
				$plugin_rows = array();
				//用户自定义
				$plugin_user_dir = APP_PATH . '/controllers/Plugin/';

				$file_row = scandir($plugin_user_dir);

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_file($plugin_user_dir . '/' . $file))
					{
						$ext_row     = pathinfo($file);
						$plugin_name = 'Plugin_' . $ext_row['filename'];

						if ('Plugin_Perm' == $plugin_name)
						{
							continue;
						}
						try
						{
							if (class_exists($plugin_name))
							{
								$plugin_desc   = $plugin_name::desc();
								$plugin_rows[] = array(
									'plugin_id' => $plugin_name,
									'plugin_name' => $plugin_name,
									'plugin_desc' => $plugin_desc
								);
							}
						}
						catch (Exception $e)
						{

						}
					}
				}

				$data['plugin_rows'] = $plugin_rows;
			}


			//插件设置
			if ('sphinx' == $config_type)
			{
				if (extension_loaded("sphinx"))
				{
					$data['sphinx_ext'] = 1;
				}
				else
				{
					$data['sphinx_ext'] = 0;
				}

				if (extension_loaded("scws"))
				{
					$data['scws_ext'] = 1;
				}
				else
				{
					$data['scws_ext'] = 0;
				}
			}

			//
			//证书
			if ('licence' == $config_type)
			{
				//授权证书
				$licence_file = APP_PATH . '/data/licence/licence.lic';

				//本地检测, 为正常企业使用
				if (is_file($licence_file))
				{
					$lic  = new Yf_Licence_Maker();
					$licence_row = $lic->getData(file_get_contents($licence_file), file_get_contents(APP_PATH . '/data/licence/public.pem'));


					$licence_row['company_name'] = $licence_row['licensee'];
					$licence_row['licence_effective_enddate'] = date('Y-m-d', $licence_row['expires']);
					$licence_row['licence_domain'] = $licence_row['domain'];
					$licence_row['licence_key'] = file_get_contents($licence_file);

					$data['licence'] = $licence_row;

				}
				else
				{

					$licence_row['company_name'] = _('无');
					$licence_row['licence_effective_enddate'] = _('无');
					$licence_row['licence_domain'] = _('无');
					$licence_row['licence_key'] = '';

					$data['licence'] = $licence_row;
				}
			}
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 清除缓存
	 *
	 * @access public
	 */
	public function cache()
	{
		$error_row = array();
		$data_row  = array();

		$config_cache = Yf_Registry::get('config_cache');

		foreach ($config_cache as $name => $item)
		{
			if (isset($item['cacheDir']))
			{
				if (clean_cache($item['cacheDir']))
				{
					$data_row[] = $item['cacheDir'];
				}
				else
				{
					$error_row[] = $item['cacheDir'];
				}

				$Cache = Yf_Cache::create($name);

				$data_row[] = json_encode($config_cache['memcache'][$name]);

				if (method_exists($Cache, 'flush') && !$Cache->flush())
				{
					$error_row[] = 'memcache-' . $name;
				}
			}
			else
			{

			}
		}

		if (true)
		{
			$msg    = _('sucess');
			$status = 200;
		}
		else
		{
			$msg    = _('清除cache失败');
			$status = 250;
		}

		$this->data->addBody(-140, $data_row);
	}

	/**
	 * 验证API是否正确
	 *
	 * @access public
	 */
	public function checkApi()
	{
		$this->data->addBody(-140, array());
	}


	/**
	 * 生成API是否正确
	 *
	 * @access public
	 */
	public function editApi()
	{
		$data                 = array();
		$data['shop_api_key'] = request_string('shop_api_key_new');
		$data['shop_api_url'] = request_string('shop_api_url_new');
		$data['shop_app_id']  = request_string('shop_app_id_new');
		$data['shop_wap_url'] = request_string('shop_wap_url');
		
		$server_id = Yf_Registry::get('server_id');

		if (is_file(INI_PATH . '/shop_api_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/shop_api_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/shop_api.ini.php';
		}

		if (!Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 250;
			$msg    = _('生成配置文件错误!');
		}
		else
		{
			$status = 200;
			$msg    = _('success!');
		}


		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * 防止灌水
	 *
	 * @access public
	 */
	/*
	public function dump()
	{
		$Web_ConfigModel = new Web_ConfigModel();
		$data = $Web_ConfigModel->getByWhere(array('config_type'=>'dump'));

		$this->data->addBody(-140, $data);
	}
	*/

	/**
	 * 站点设置
	 *
	 * @access public
	 */
	/*
	public function shop()
	{
		$Web_ConfigModel = new Web_ConfigModel();
		$data = $Web_ConfigModel->getByWhere(array('config_type'=>'site'));

		$this->data->addBody(-140, $data);
	}
	*/

	/**
	 * 短信邮件账号设置
	 *
	 * @access public
	 */
	/*
	public function getEmailAndMsg()
	{
		$Web_ConfigModel = new Web_ConfigModel();
		$data = $Web_ConfigModel->getByWhere(array('config_type'=>'email'));

		$this->data->addBody(-140, $data);
	}
	*/

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function edit()
	{
		echo 1;die;
		$Web_ConfigModel = new Web_ConfigModel();

		$config_type_row = request_row('config_type');
		foreach ($config_type_row as $config_type)
		{
			$config_value_row = request_row($config_type);

			$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));

			foreach ($config_rows as $config_key => $config_row)
			{
				$edit_row = array();

				if (isset($config_value_row[$config_key]))
				{
					if ('json' == $config_row['config_datatype'])
					{
						$edit_row['config_value'] = json_encode($config_value_row[$config_key]);
					}
					else
					{
						$edit_row['config_value'] = $config_value_row[$config_key];
					}
				}
				else
				{
					if ('number' == $config_row['config_datatype'])
					{
						if ('theme_id' != $config_key)
						{
							//$edit_row['config_value'] = 0;
						}
					}
					else
					{
					}
				}

				if ($edit_row)
				{
					$Web_ConfigModel->editConfig($config_key, $edit_row);
				}
			}
		}

		//其它全局变量
		$config_rows = array();

		if (true || is_file(INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/global.ini.php';
		}

		$temp_rows   = $Web_ConfigModel->getConfig(array(
													   'site_name',
													   'time_zone_id',
													   'language_id',
													   'theme_id',
													   'site_status',
													   'closed_reason'
												   ));

		foreach ($temp_rows as $config_row)
		{
			$config_rows[$config_row['config_key']] = $config_row['config_value'];
		}

		$rs = Yf_Utils_File::generatePhpFile($file, $config_rows);


		$this->data->addBody(-140, array());
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function setPluginState()
	{
		$Web_ConfigModel = new Web_ConfigModel();

		$flag = false;

		$config_key = request_string('plugin_id');

		if ($config_key)
		{
			$plugin_rows = array();

			$config_value = request_int('enable');

			$edit_row                 = array();
			$edit_row['config_value'] = $config_value;

			//添加
			if (false === $Web_ConfigModel->getConfigValue($config_key))
			{
				$edit_row['config_key']  = $config_key;
				$edit_row['config_type'] = 'plugin';

				$flag = $Web_ConfigModel->addConfig($edit_row);
			}
			else
			{
				$flag = $Web_ConfigModel->editConfig($config_key, $edit_row);
			}

			//重新生成配置文件
			$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => 'plugin'));

			$file = INI_PATH . '/plugin.ini.php';

			foreach ($config_rows as $config_row)
			{
				if ($config_row['config_value'])
				{
					$plugin_rows[$config_row['config_key']] = array('name' => $config_row['config_key']);
				}
			}

			$rs = Yf_Utils_File::generatePhpFile($file, array('plugin_rows' => $plugin_rows));
		}

		if ($flag !== false)
		{
			$msg    = 'sucess';
			$status = 200;
		}
		else
		{
			$msg    = '修改失败';
			$status = 250;
		}

		$this->data->addBody(-140, array());
	}


	//修改交易投诉的时效
	public function editComplainDatetime()
	{
		$complain_datetime = request_int('complain_datetime');

		$edit_row        = array('config_value' => $complain_datetime);
		$Web_ConfigModel = new Web_ConfigModel();
		$flag            = $Web_ConfigModel->editConfig('complain_datetime', $edit_row);

		if ($flag !== false)
		{
			$msg    = _('sucess');
			$status = 200;
		}
		else
		{
			$msg    = _('修改失败');
			$status = 250;
		}

		$this->data->addBody(-140, array());
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function validator()
	{
		$data = Yf_Utils_File::getPhpFile(ROOT_PATH);

		foreach ($data as $key => $file)
		{
			$k                = md5($file . md5_file($file));
			$data_now_row[$k] = $file;
		}

		$validator_standard_file = APP_PATH . '/data/php_standard_file';

		$file_str     = file_get_contents($validator_standard_file);
		$data_ori_row = decode_json($file_str);

		$data_rs = array();

		//判断文件名称的增减
		$diff_name_row = array_unique(array_diff($data_ori_row, $data_now_row) + array_diff($data_now_row, $data_ori_row));
		
		foreach ($diff_name_row as $k => $v)
		{
			if (in_array($v, $data_ori_row))
			{
				$data_rs['name']['decrease'][$k] = $v;

			}
			else
			{
				$data_rs['name']['increase'][$k] = $v;
			}
		}

		//判断内容异同
		$diff_file_row = array_unique(array_diff_key($data_now_row, $data_ori_row));
		foreach ($diff_file_row as $k => $v)
		{
			if (in_array($v, $data_ori_row))
			{
				$data_rs['file']['modify'][$k] = $v;

			}
			else
			{
				$data_rs['file']['increase'][$k] = $v;
			}
		}

		$this->data->addBody(-140, $data_rs);
	}


	/**
	 * setStandard1
	 *
	 * @access public
	 */
	public function setStandard()
	{
		$data = Yf_Utils_File::getPhpFile(ROOT_PATH);

		foreach ($data as $key => $file)
		{
			$k            = md5($file . md5_file($file));
			$data_row[$k] = $file;
		}

		$validator_standard_file = APP_PATH . '/data/php_standard_file';

		$flag = file_put_contents($validator_standard_file, json_encode($data_row));

		if ($flag)
		{
			$msg    = _('sucess');
			$status = 200;
		}
		else
		{
			$msg    = _('失败');
			$status = 250;
		}

		$this->data->addBody(-140, $data_row, $msg, $status);
	}


	/**
	 * setStandard1
	 *
	 * @access public
	 */
	public function editUcenterApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/ucenter_api.ini.php';


		$ucenter_api_row = request_row('ucenter_api');

		$ucenter_api_key = $ucenter_api_row['ucenter_api_key'];
		$ucenter_api_url = $ucenter_api_row['ucenter_api_url'];
		$ucenter_app_id  = 103;

		$data                    = array();
		$data['ucenter_api_key'] = $ucenter_api_key;
		$data['ucenter_api_url'] = $ucenter_api_url;
		$data['ucenter_app_id']  = $ucenter_app_id;

		if (is_file(INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/ucenter_api.ini.php';
		}

		if (!Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 250;
			$msg    = _('生成配置文件错误!');
		}
		else
		{
			$msg    = _('生成配置文件成功!');;
			$status = 200;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}


	/**
	 * setStandard1
	 *
	 * @access public
	 */
	public function
	editPaycenterApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/paycenter_api.ini.php';


		$paycenter_api_row = request_row('paycenter_api');

		$paycenter_api_key = $paycenter_api_row['paycenter_api_key'];
		$paycenter_api_url = $paycenter_api_row['paycenter_api_url'];
		$paycenter_app_id  = 105;
		$paycenter_api_name  = $paycenter_api_row['paycenter_api_name'];

		$data                    = array();
		$data['paycenter_api_key'] = $paycenter_api_key;
		$data['paycenter_api_url'] = $paycenter_api_url;
		$data['paycenter_app_id']  = $paycenter_app_id;
		$data['paycenter_api_name'] = $paycenter_api_name;

		if (is_file(INI_PATH . '/paycenter_api_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/paycenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/paycenter_api.ini.php';
		}

		if (!Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 250;
			$msg    = _('生成配置文件错误!');
		}
		else
		{
			$msg    = _('生成配置文件成功!');;
			$status = 200;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * testEmail
	 *
	 * @access public
	 */
	public function testEmail()
	{
		//其它全局变量
		$email_row = request_row('email');

		$title    = '测试邮件';
		$name     = 'test';
		$email_to = $email_row['email_test'];
		$con      = '测试邮件';
		$reply    = $email_row['email_test'];  //收件人

		$email_host = $email_row['email_host'];
		$email_addr = $email_row['email_addr'];
		$email_pass = $email_row['email_pass'];
		$email_id   = $email_row['email_id'];


		include_once(LIB_PATH . "/phpmailer/class.phpmailer.php");


		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet  = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port     = 25;
			$mail->Host     = $email_host;
			$mail->Username = $email_addr;
			$mail->Password = $email_pass;
//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
			$mail->AddReplyTo($email_addr, $email_id);//回复地址
			$mail->From     = $email_addr;
			$mail->FromName = $email_id;

			$mail->AddAddress($email_to);
			$mail->Subject = "邮件测试标题";
			$mail->Body    = "测试邮件内容";
			//$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
//$mail->AddAttachment("f:/test.png"); //可以添加附件
			$mail->IsHTML(true);
			$re = $mail->Send();
		}
		catch (phpmailerException $e)
		{
			$re  = false;
			$msg = "邮件发送失败：" . $e->errorMessage();
		}


		if ($re)
		{
			$status = 250;
			$msg    = _('测试邮件已经发送!');
		}
		else
		{
			$msg    = _('测试失败') . $msg;
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * testEmail
	 *
	 * @access public
	 */
	public function testSms()
	{
		//其它全局变量
		$email_row = request_row('sms');


		$sms_account = $email_row['sms_account'];
		$sms_pass    = $email_row['sms_pass'];

		if ($re)
		{
			$status = 250;
			$msg    = _('测试邮件已经发送!');
		}
		else
		{
			$msg    = _('测试失败');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}


	/**
	 * 初始化db
	 *
	 * @access public
	 */
	public function initDb()
	{

		$table_sys_row = array(
			'adv_page_layout',
			'base_district',
			'express',
			'message_template',
			'order_cancel_reason',
			'order_return_reason',
			'order_state',
			'consult_type',
			'report_type',
			'user_grade',
			'shop_contract_type',
			'shop_template',
			'shop_help',
			'web_config',
		);


		$table_data_row = array(
			'adv_page_layout',
			'adv_page_settings',
			'adv_page_statistics_area',
			'adv_page_statistics_day',
			'adv_widget_base',
			'adv_widget_cat',
			'adv_widget_item',
			'adv_widget_nav',
			'adv_widget_statistics_area',
			'adv_widget_statistics_day',
			'advertisement',
			'analysis_platform_area',
			'analysis_platform_class',
			'analysis_platform_general',
			'analysis_platform_goods',
			'analysis_platform_return',
			'analysis_platform_total',
			'analysis_platform_user',
			'analysis_shop_area',
			'analysis_shop_general',
			'analysis_shop_goods',
			'analysis_shop_user',
			'announcement',
			'article_base',
			'article_group',
			'article_reply',
			'base_cron',
			'base_district',
			'base_filter_keyword',
			'base_menu',
			'card_base',
			'cart',
			'complain_base',
			'complain_goods',
			'complain_subject',
			'complain_talk',
			'consult_base',
			'consult_reply',
			'consult_type',
			'delivery_base',
			'discount_base',
			'discount_combo',
			'discount_goods',
			'express',
			'feed_base',
			'feed_group',
			'goods_base',
			'goods_brand',
			'goods_cat',
			'goods_cat_nav',
			'goods_common',
			'goods_common_detail',
			'goods_evaluation',
			'goods_format',
			'goods_images',
			'goods_property',
			'goods_property_index',
			'goods_property_value',
			'goods_recommend',
			'goods_service',
			'goods_spec',
			'goods_spec_value',
			'goods_state',
			'goods_type',
			'goods_type_brand',
			'goods_type_spec',
			'grade_log',
			'groupbuy_area',
			'groupbuy_base',
			'groupbuy_cat',
			'groupbuy_combo',
			'groupbuy_price_range',
			'increase_base',
			'increase_combo',
			'increase_goods',
			'increase_redemp_goods',
			'increase_rule',
			'invoice',
			'log_action',
			'mansong_base',
			'mansong_combo',
			'mansong_rule',
			'mb_cat_image',
			'mb_tpl_layout',
			'member_agreement',
			'member_consume_log',
			'message',
			'message_setting',
			'message_template',
			'number_seq',
			'order_base',
			'order_base1',
			'order_cancel_reason',
			'order_delivery',
			'order_goods',
			'order_goods_snapshot',
			'order_goods_virtual_code',
			'order_log',
			'order_payment',
			'order_return',
			'order_return_reason',
			'order_settlement',
			'order_settlement_stat',
			'order_state',
			'payment_channel',
			'platform_custom_service',
			'platform_custom_service_type',
			'platform_nav',
			'platform_report',
			'platform_report_subject',
			'platform_report_subject_type',
			'points_cart',
			'points_goods',
			'points_log',
			'points_order',
			'points_orderaddress',
			'points_ordergoods',
			'rec_position',
			'report_base',
			'report_subject',
			'report_type',
			'search_word',
			'seller_base',
			'seller_log',
			'seller_rights_base',
			'seller_rights_group',
			'service',
			'shared_bindings',
			'shop_base',
			'shop_class',
			'shop_class_bind',
			'shop_company',
			'shop_contract',
			'shop_contract_log',
			'shop_contract_type',
			'shop_cost',
			'shop_custom_service',
			'shop_decoration',
			'shop_decoration_album',
			'shop_decoration_block',
			'shop_domain',
			'shop_entity',
			'shop_evaluation',
			'shop_express',
			'shop_extend',
			'shop_goods_cat',
			'shop_grade',
			'shop_help',
			'shop_nav',
			'shop_points_log',
			'shop_renewal',
			'shop_shipping_address',
			'shop_supplier',
			'shop_template',
			'sub_site',
			'transport_item',
			'transport_offpay_area',
			'transport_type',
			'upload_album',
			'upload_base',
			'user_address',
			'user_base',
			'user_buy',
			'user_extend',
			'user_favorites_brand',
			'user_favorites_goods',
			'user_favorites_shop',
			'user_footprint',
			'user_friend',
			'user_grade',
			'user_info',
			'user_message',
			'user_privacy',
			'user_resource',
			'user_tag',
			'user_tag_rec',
			'user_type',
			'voucher_base',
			'voucher_combo',
			'voucher_price',
			'voucher_template',
			'waybill_tpl',
			'web_config'
		);


		foreach ($table_data_row as $table_name)
		{
			if (!in_array($table_name, $table_sys_row))
			{
				$table = TABEL_PREFIX . $table_name;
				$sql   = sprintf('truncate table `%s`;', $table);

				echo $sql;
				echo "\n";
				$Web_ConfigModel = new Web_ConfigModel();
				//$Web_ConfigModel->sql->exec($sql);
			}
		}

		/*
		truncate table `yf_adv_page_settings`;
		truncate table `yf_adv_page_statistics_area`;
		truncate table `yf_adv_page_statistics_day`;
		truncate table `yf_adv_widget_base`;
		truncate table `yf_adv_widget_cat`;
		truncate table `yf_adv_widget_item`;
		truncate table `yf_adv_widget_nav`;
		truncate table `yf_adv_widget_statistics_area`;
		truncate table `yf_adv_widget_statistics_day`;
		truncate table `yf_advertisement`;
		truncate table `yf_analysis_platform_area`;
		truncate table `yf_analysis_platform_class`;
		truncate table `yf_analysis_platform_general`;
		truncate table `yf_analysis_platform_goods`;
		truncate table `yf_analysis_platform_return`;
		truncate table `yf_analysis_platform_total`;
		truncate table `yf_analysis_platform_user`;
		truncate table `yf_analysis_shop_area`;
		truncate table `yf_analysis_shop_general`;
		truncate table `yf_analysis_shop_goods`;
		truncate table `yf_analysis_shop_user`;
		truncate table `yf_announcement`;
		truncate table `yf_article_base`;
		truncate table `yf_article_group`;
		truncate table `yf_article_reply`;
		truncate table `yf_base_cron`;
		truncate table `yf_base_filter_keyword`;
		truncate table `yf_base_menu`;
		truncate table `yf_card_base`;
		truncate table `yf_cart`;
		truncate table `yf_complain_base`;
		truncate table `yf_complain_goods`;
		truncate table `yf_complain_subject`;
		truncate table `yf_complain_talk`;
		truncate table `yf_consult_base`;
		truncate table `yf_consult_reply`;
		truncate table `yf_delivery_base`;
		truncate table `yf_discount_base`;
		truncate table `yf_discount_combo`;
		truncate table `yf_discount_goods`;
		truncate table `yf_feed_base`;
		truncate table `yf_feed_group`;
		truncate table `yf_goods_base`;
		truncate table `yf_goods_brand`;
		truncate table `yf_goods_cat`;
		truncate table `yf_goods_cat_nav`;
		truncate table `yf_goods_common`;
		truncate table `yf_goods_common_detail`;
		truncate table `yf_goods_evaluation`;
		truncate table `yf_goods_format`;
		truncate table `yf_goods_images`;
		truncate table `yf_goods_property`;
		truncate table `yf_goods_property_index`;
		truncate table `yf_goods_property_value`;
		truncate table `yf_goods_recommend`;
		truncate table `yf_goods_service`;
		truncate table `yf_goods_spec`;
		truncate table `yf_goods_spec_value`;
		truncate table `yf_goods_state`;
		truncate table `yf_goods_type`;
		truncate table `yf_goods_type_brand`;
		truncate table `yf_goods_type_spec`;
		truncate table `yf_grade_log`;
		truncate table `yf_groupbuy_area`;
		truncate table `yf_groupbuy_base`;
		truncate table `yf_groupbuy_cat`;
		truncate table `yf_groupbuy_combo`;
		truncate table `yf_groupbuy_price_range`;
		truncate table `yf_increase_base`;
		truncate table `yf_increase_combo`;
		truncate table `yf_increase_goods`;
		truncate table `yf_increase_redemp_goods`;
		truncate table `yf_increase_rule`;
		truncate table `yf_invoice`;
		truncate table `yf_log_action`;
		truncate table `yf_mansong_base`;
		truncate table `yf_mansong_combo`;
		truncate table `yf_mansong_rule`;
		truncate table `yf_mb_cat_image`;
		truncate table `yf_mb_tpl_layout`;
		truncate table `yf_member_agreement`;
		truncate table `yf_member_consume_log`;
		truncate table `yf_message`;
		truncate table `yf_message_setting`;
		truncate table `yf_number_seq`;
		truncate table `yf_order_base`;
		truncate table `yf_order_base1`;
		truncate table `yf_order_delivery`;
		truncate table `yf_order_goods`;
		truncate table `yf_order_goods_snapshot`;
		truncate table `yf_order_goods_virtual_code`;
		truncate table `yf_order_log`;
		truncate table `yf_order_payment`;
		truncate table `yf_order_return`;
		truncate table `yf_order_settlement`;
		truncate table `yf_order_settlement_stat`;
		truncate table `yf_payment_channel`;
		truncate table `yf_platform_custom_service`;
		truncate table `yf_platform_custom_service_type`;
		truncate table `yf_platform_nav`;
		truncate table `yf_platform_report`;
		truncate table `yf_platform_report_subject`;
		truncate table `yf_platform_report_subject_type`;
		truncate table `yf_points_cart`;
		truncate table `yf_points_goods`;
		truncate table `yf_points_log`;
		truncate table `yf_points_order`;
		truncate table `yf_points_orderaddress`;
		truncate table `yf_points_ordergoods`;
		truncate table `yf_rec_position`;
		truncate table `yf_report_base`;
		truncate table `yf_report_subject`;
		truncate table `yf_search_word`;
		truncate table `yf_seller_base`;
		truncate table `yf_seller_log`;
		truncate table `yf_seller_rights_base`;
		truncate table `yf_seller_rights_group`;
		truncate table `yf_service`;
		truncate table `yf_shared_bindings`;
		truncate table `yf_shop_base`;
		truncate table `yf_shop_class`;
		truncate table `yf_shop_class_bind`;
		truncate table `yf_shop_company`;
		truncate table `yf_shop_contract`;
		truncate table `yf_shop_contract_log`;
		truncate table `yf_shop_cost`;
		truncate table `yf_shop_custom_service`;
		truncate table `yf_shop_decoration`;
		truncate table `yf_shop_decoration_album`;
		truncate table `yf_shop_decoration_block`;
		truncate table `yf_shop_domain`;
		truncate table `yf_shop_entity`;
		truncate table `yf_shop_evaluation`;
		truncate table `yf_shop_express`;
		truncate table `yf_shop_extend`;
		truncate table `yf_shop_goods_cat`;
		truncate table `yf_shop_grade`;
		truncate table `yf_shop_nav`;
		truncate table `yf_shop_points_log`;
		truncate table `yf_shop_renewal`;
		truncate table `yf_shop_shipping_address`;
		truncate table `yf_shop_supplier`;
		truncate table `yf_sub_site`;
		truncate table `yf_transport_item`;
		truncate table `yf_transport_offpay_area`;
		truncate table `yf_transport_type`;
		truncate table `yf_upload_album`;
		truncate table `yf_upload_base`;
		truncate table `yf_user_address`;
		truncate table `yf_user_base`;
		truncate table `yf_user_buy`;
		truncate table `yf_user_extend`;
		truncate table `yf_user_favorites_brand`;
		truncate table `yf_user_favorites_goods`;
		truncate table `yf_user_favorites_shop`;
		truncate table `yf_user_footprint`;
		truncate table `yf_user_friend`;
		truncate table `yf_user_info`;
		truncate table `yf_user_message`;
		truncate table `yf_user_privacy`;
		truncate table `yf_user_resource`;
		truncate table `yf_user_tag`;
		truncate table `yf_user_tag_rec`;
		truncate table `yf_user_type`;
		truncate table `yf_voucher_base`;
		truncate table `yf_voucher_combo`;
		truncate table `yf_voucher_price`;
		truncate table `yf_voucher_template`;
		truncate table `yf_waybill_tpl`;
	 	*/

		$this->data->addBody(-140, array());
	}


	/**
	 * 编辑授权证书
	 *
	 * @access public
	 */
	public function editLicence()
	{
		$licence = request_row('licence');

		//授权证书
		$licence_file = APP_PATH . '/data/licence/licence.lic';

		if (!file_put_contents($licence_file, $licence['licence_key']))
		{
			$status = 250;
			$msg    = _('生成证书错误!');
		}
		else
		{
			$status = 200;
			$msg    = _('success!');
		}


		$this->data->addBody(-140, array(), $msg, $status);
	}

}

?>