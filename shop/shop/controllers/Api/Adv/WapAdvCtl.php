<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WapAdvCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

	}


	public function wap_index_adv(){
		include  $this->view->getView();
		
	}
	public function a(){

		$this->data->addBody(-140, array('a'=>123));	
	}
	public function edit()
	{
		$data = array('aa'=>2);
		// $Web_ConfigModel = new Web_ConfigModel();

		// $config_type_row = request_row('config_type');
		// foreach ($config_type_row as $config_type)
		// {
		// 	$config_value_row = request_row($config_type);

		// 	$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));

		// 	foreach ($config_rows as $config_key => $config_row)
		// 	{
		// 		$edit_row = array();

		// 		if (isset($config_value_row[$config_key]))
		// 		{
		// 			if ('json' == $config_row['config_datatype'])
		// 			{
		// 				$edit_row['config_value'] = json_encode($config_value_row[$config_key]);
		// 			}
		// 			else
		// 			{
		// 				$edit_row['config_value'] = $config_value_row[$config_key];
		// 			}
		// 		}
		// 		else
		// 		{
		// 			if ('number' == $config_row['config_datatype'])
		// 			{
		// 				if ('theme_id' != $config_key)
		// 				{
		// 					//$edit_row['config_value'] = 0;
		// 				}
		// 			}
		// 			else
		// 			{
		// 			}
		// 		}

		// 		if ($edit_row)
		// 		{
		// 			$Web_ConfigModel->editConfig($config_key, $edit_row);
		// 		}
		// 	}
		// }

		// //其它全局变量
		// $config_rows = array();

		// if (true || is_file(INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php'))
		// {
		// 	$file = INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php';
		// }
		// else
		// {
		// 	$file = INI_PATH . '/global.ini.php';
		// }

		// $temp_rows   = $Web_ConfigModel->getConfig(array(
		// 											   'site_name',
		// 											   'time_zone_id',
		// 											   'language_id',
		// 											   'theme_id',
		// 											   'site_status',
		// 											   'closed_reason'
		// 										   ));

		// foreach ($temp_rows as $config_row)
		// {
		// 	$config_rows[$config_row['config_key']] = $config_row['config_value'];
		// }

		// $rs = Yf_Utils_File::generatePhpFile($file, $config_rows);


		$this->data->addBody(-140, $data);
	}

}

?>