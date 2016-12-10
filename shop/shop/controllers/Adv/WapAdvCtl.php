<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Adv_WapAdvCtl extends Yf_AppController
{
	public $advWapAdvModel = null;

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

		//include $this->view->getView();
		$this->advWapAdvModel = new Web_ConfigModel();
	}


 public function getwap_adv()
 {
 	$config_type = 'wap_index_adv';

 	//$rows = $this->advWapAdvModel->getConfigValue($config_type);
 	$Web_ConfigModel = new Web_ConfigModel();
 	$rows = $Web_ConfigModel->getConfigList(array('config_type'=>$config_type));
 	// var_dump( $rows );die;
		$data = array();
		if ($rows)
		{
			foreach ($rows as $key => $value) {
				$data[$key]=$value['config_value'];
			}
		}
       // var_dump($data);die;
		$this->data->addBody(-140, $data);

 }


	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$widget_id = request_int('widget_id');
		$rows      = $this->advWidgetBaseModel->getWidgetBase($widget_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	
}

?>