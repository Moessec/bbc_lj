<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_BespeakCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$this->goodsBespeakModel = new Goods_BespeakModel();
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function bespeaklists()
	{

		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeakList();
		foreach ($data['items'] as $k => $v) {
			if($v['bespeak_status']=='1'){
				$data['items'][$k]['bespeak_status']='通过';
			}else{
				$data['items'][$k]['bespeak_status']='待审核';
			}
			if($v['bespeak_place']=='1'){
				$data['items'][$k]['bespeak_place']='室内';
			}else{
				$data['items'][$k]['bespeak_place']='公共区域';
			}
			if($v['bespeak_state']=='0'){
				$data['items'][$k]['bespeak_state']='待处理';
			}else if($v['bespeak_state']=='1'){
				$data['items'][$k]['bespeak_state']='预约正在处理';
			}else if($v['bespeak_state']=='2'){
				$data['items'][$k]['bespeak_state']='预约完成';
			}
			if($v['bespeak_list']!='0'){
				unset($data['items'][$k]);
			}
		}
		$data['items']=array_values($data['items']);
		$this->data->addBody(-140, $data);
	}

	public function bespeakAct()
	{

		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeakList();
		foreach ($data['items'] as $k => $v) {
			if($v['bespeak_status']=='1'){
				$data['items'][$k]['bespeak_status']='通过';
			}else{
				$data['items'][$k]['bespeak_status']='待审核';
			}
			if($v['bespeak_state']=='0'){
				$data['items'][$k]['bespeak_state']='等待进行';
			}else if($v['bespeak_state']=='1'){
				$data['items'][$k]['bespeak_state']='活动正在进行';
			}else if($v['bespeak_state']=='2'){
				$data['items'][$k]['bespeak_state']='活动结束';
			}
			if($v['bespeak_list']!=1 || $v['user_id']!=0){
				unset($data['items'][$k]);
			}
		}

		$data['items']=array_values($data['items']);
		$this->data->addBody(-140, $data);
	}

	public function bespeakActlist()
	{

		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeakList();
		$bespeak_id      = request_string('bespeak_id');
		$one    = $Goods_BespeakModel->getbespeak($bespeak_id);
		foreach ($one as $key => $value) {
			$bespeak_title = $value['bespeak_title'];
		}
		foreach ($data['items'] as $k => $v) {
			if($v['bespeak_title'] != $bespeak_title || $v['user_id']==0){
				unset($data['items'][$k]);
			}
		}

		$data=array_values($data['items']);
		$this->data->addBody(-140, $data);
	}


	public function bespeakRent()
	{

		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeakList();
		foreach ($data['items'] as $k => $v) {
			if($v['bespeak_status']=='1'){
				$data['items'][$k]['bespeak_status']='通过';
			}else{
				$data['items'][$k]['bespeak_status']='待审核';
			}
			if($v['bespeak_state']=='0'){
				$data['items'][$k]['bespeak_state']='无效';
			}else if($v['bespeak_state']=='1'){
				$data['items'][$k]['bespeak_state']='预约正在处理';
			}else if($v['bespeak_state']=='2'){
				$data['items'][$k]['bespeak_state']='预约完成';
			}
			if($v['bespeak_list']!=2 || $v['user_id'] != 0){
				unset($data['items'][$k]);
			}
		}

		$data['items']=array_values($data['items']);
		$this->data->addBody(-140, $data);
	}

		public function bespeakRentlist()
	{

		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeakList();
		$bespeak_id      = request_string('bespeak_id');
		$one    = $Goods_BespeakModel->getbespeak($bespeak_id);
		foreach ($one as $key => $value) {
			$bespeak_title = $value['bespeak_title'];
		}
		foreach ($data['items'] as $k => $v) {
			if($v['bespeak_title'] != $bespeak_title || $v['user_id']==0){
				unset($data['items'][$k]);
			}
		}

		$data=array_values($data['items']);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['bespeak_name']         = request_string('bespeak_name'); // 规格名称
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['bespeak_format']       = request_string('bespeak_format'); // 显示类型
		$data['bespeak_item']         = request_string('bespeak_item'); // 规格值列
		$data['bespeak_displayorder'] = request_string('bespeak_displayorder'); // 排序

		$bespeak_id = $this->goodsBespeakModel->addBespeak($data, true);

		if ($bespeak_id)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['bespeak_id'] = $bespeak_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$bespeak_id = request_int('bespeak_id');

		$flag = $this->goodsBespeakModel->removeBespeak($bespeak_id);

		if ($flag)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['bespeak_id'] = array($bespeak_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['bespeak_name']         = request_string('bespeak_name'); // 规格名称
		$data['type_id']           = request_string('type_id'); // 类型id
		$data['bespeak_format']       = request_string('bespeak_format'); // 显示类型
		$data['bespeak_item']         = request_string('bespeak_item'); // 规格值列
		$data['bespeak_displayorder'] = request_string('bespeak_displayorder'); // 排序

		$bespeak_id = request_int('bespeak_id');
		$data_rs = $data;

		unset($data['bespeak_id']);
		exit();
		$flag = $this->goodsBespeakModel->editBespeak($bespeak_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	public function disable()
	{
		$Goods_BespeakModel = new Goods_BespeakModel();
		$data['bespeak_status']         = request_string('bespeak_status');

		$bespeak_id = request_int('bespeak_id');
		$ones            = $Goods_BespeakModel->getBespeakList($bespeak_id);
		if($ones['0']['bespeak_status']!='1'){
			$data_rs = $data;
			unset($data['bespeak_id']);
			$data['bespeak_status']='1';
			$data['bespeak_state']='1';
			$flag = $this->goodsBespeakModel->editBespeak($bespeak_id, $data);

		}else{
			$data_rs['msg']     = _('failure');
			$status = 250;
		}
		if(!$flag===FALSE){
			$data_rs['msg']    = _('success');
			$status = 200;
		}else{
			$data_rs['msg']     = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140 , $data, $msg, $status);
	}

	public function disablestate()
	{
		$Goods_BespeakModel = new Goods_BespeakModel();
		$data['bespeak_state']         = request_string('bespeak_state');

		$bespeak_id = request_int('bespeak_id');
		$ones            = $Goods_BespeakModel->getBespeakList($bespeak_id);
		if($ones['0']['bespeak_state']!='2'){
			$data_rs = $data;
			unset($data['bespeak_id']);
			$data['bespeak_state']='2';
			$flag = $this->goodsBespeakModel->editBespeak($bespeak_id, $data);

		}else{
			$data_rs['msg']     = _('failure');
			$status = 250;
		}
		if(!$flag===FALSE){
			$data_rs['msg']    = _('success');
			$status = 200;
		}else{
			$data_rs['msg']     = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140 , $data, $msg, $status);
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function removeBespeak()
	{
		$Goods_BespeakModel = new Goods_BespeakModel();

		$bespeak_id     = request_string('bespeak_id');

		if ($bespeak_id)
		{
			$Goods_BespeakModel->sql->startTransactionDb();

			$flag = $Goods_BespeakModel->removeBespeak($bespeak_id);

			if ($flag && $Goods_BespeakModel->sql->commitDb())
			{
				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$Goods_BespeakModel->sql->rollBackDb();
				$m      = $Goods_BespeakModel->msg->getMessages();
				$msg    = $m ? $m[0] : _('failure');
				$status = 250;
			}
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$this->data->addBody(-140, array('id' => $bespeak_id), $msg, $status);
	}

	/*
	 * 获取规格信息
	 */
	function getBespeak()
	{
		$Goods_BespeakModel = new Goods_BespeakModel();
		$data            = $Goods_BespeakModel->getBespeak('*');
		if ($data)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}
		$data = array_values($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 新增预约
	 */
	public function addGoodsBespeak()
	{
		$data                      = array();
		$data['bespeak_title']         = request_string('bespeak_title');
		$data['bespeak_com'] = request_string('bespeak_com');
		$data['opentime'] = request_string('opentime');
		$data['outtime'] = request_string('outtime');
		$data['img'] = request_string('img');
		$data['true_name'] = '管理员';
		$data['user_id'] = 'admin';
		$data['bespeak_list'] = '1';
		$data['usercontact'] =  request_string('usercontact');


		$bespeak_id = $this->goodsBespeakModel->addBespeak($data, true);

		if ($bespeak_id)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
			$data;
		}

		$data['id']      = $bespeak_id;
		$data['bespeak_id'] = $bespeak_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addGoodsBespeak1()
	{
		$data                      = array();
		$data['bespeak_title']         = request_string('bespeak_title');
		$data['bespeak_com'] = request_string('bespeak_com');
		$data['true_name'] = '管理员';
		$data['user_id'] = 'admin';
		$data['bespeak_list'] = '2';
		$data['usercontact'] =  request_string('usercontact');

		$bespeak_id = $this->goodsBespeakModel->addBespeak($data, true);

		if ($bespeak_id)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['id']      = $bespeak_id;
		$data['bespeak_id'] = $bespeak_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}
	/*
	 * 修改规格
	 */
	public function editGoodsBespeak()
	{
		$Goods_BespeakModel = new Goods_BespeakModel();

		$id                        = request_int('bespeak_id');
		$data                      = array();
		$data['bespeak_title']         = request_string('bespeak_title');
		$data['bespeak_com'] = request_string('bespeak_com');
		$data['opentime'] = request_string('opentime');
		$data['outtime'] = request_string('outtime');
		$data['img'] = request_string('img');

		$flag = $Goods_BespeakModel->editBespeak($id, $data);

		if ($flag != false)
		{
			$msg    = _('success');
			$status = 200;
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['id']      = $id;
		$data['bespeak_id'] = $id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getBespeakalllist()
	{
		$bespeak_id = request_int('bespeak_id');
		$Goods_BespeakModel = new Goods_BespeakModel();
		$data    = $Goods_BespeakModel->getbespeak($bespeak_id);
		$this->data->addBody(-140, $data);
	}

	public function upload(){
		if(isset($_FILES["myfile"]))
		{
			$ret = array();
			$uploadDir = 'upload/images'.DIRECTORY_SEPARATOR.date("Ymd").DIRECTORY_SEPARATOR;
			$dir = DATA_PATH.DIRECTORY_SEPARATOR.$uploadDir;
			file_exists($dir) || (mkdir($dir,0777,true) && chmod($dir,0777));
			if(!is_array($_FILES["myfile"]["name"])) //single file
			{
				$fileName = time().uniqid().'.'.pathinfo($_FILES["myfile"]["name"])['extension'];
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$dir.$fileName);
				$ret['file'] = DIRECTORY_SEPARATOR.$uploadDir.$fileName;
			}
			if ($ret != false)
			{
				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}
			$data=$ret;
			$this->data->addBody(-140, $data, $msg, $status);

		}
	}

}

?>