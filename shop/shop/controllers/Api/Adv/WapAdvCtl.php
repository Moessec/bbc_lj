<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Adv_WapAdvCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

	}
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


	public function wap_index_adv(){
		include  $this->view->getView();
		
	}
	public function edit()
	{
		$Web_ConfigModel = new Web_ConfigModel();

		$config_type_row = request_row('config_type');
		foreach ($config_type_row as $config_type)
		{
			var_dump($config_type);
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

}

?>