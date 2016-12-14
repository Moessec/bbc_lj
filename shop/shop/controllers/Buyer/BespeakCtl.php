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
		$user['bespeak_list']=0;
		$USER_BespeakModel = new USER_BespeakModel();
		$data            = $USER_BespeakModel->getBespeakList($user);

		if ("json" == $this->typ)
		{
			foreach ($data as $key => $value) {
					if($value['bespeak_state']=='0'){
						$value['bespeak_state']='无效，审核不通过';
					}elseif ($value['bespeak_state']=='1') {
						$value['bespeak_state']='处理中';
					}elseif ($value['bespeak_state']=='2') {
						$value['bespeak_state']='已处理';
					}
					if($value['bespeak_place']=='0'){
						$value['bespeak_place']='公共区域';
					}else{
						$value['bespeak_place']='室内';
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

	public function advBespeak()
	{
		$user_id = Perm::$userId;
		$user['user_id']=$user_id;
		$user['bespeak_list']=1;
		$adv['bespeak_list']=1;
		$adv['user_id']=0;
		$adv['bespeak_status']=1;
		$adv['bespeak_state']=1;
		$USER_BespeakModel = new USER_BespeakModel();
		$data['temp']            = $USER_BespeakModel->getBespeakList($user);
		$data['adv']            = $USER_BespeakModel->getBespeakList($adv);

		if ("json" == $this->typ)
		{
			foreach ($data['temp'] as $key => $value) {
				if ($value['bespeak_state']=='1') {
					$value['bespeak_state']='已参与活动';
				}elseif ($value['bespeak_state']=='2') {
					$value['bespeak_state']='活动结束';
				}
				$value['outtime']=mb_substr($value['outtime'],0,10);
				$value['opentime']=mb_substr($value['opentime'],0,10);
				$data['temp'][$key]=$value;
			}
			foreach ($data['adv'] as $key => $value) {
				if ($value['bespeak_state']=='1') {
					$value['bespeak_state']='活动正在进行';
				}elseif ($value['bespeak_state']=='2') {
					$value['bespeak_state']='活动已经结束';
				}
				$value['bespeakinfo']='bespeak_adv_info.html?bespeak_id='.$value['bespeak_id'];
				if(!empty($data['temp'])){
					foreach ($data['temp'] as $k1 => $v1) {
						if($v1['bespeak_title']==$value['bespeak_title']){
							$value['bespeak_click']='已参与';
							$value['bespeak_id']='#';
						}else{
							$value['bespeak_click']='参与';
							$id = $value['bespeak_id'];
							$value['bespeak_id']='bespeak_opera_adv.html?bespeak_id='.$id;
						}
					}
				}else{
					$value['bespeak_click']='参与';
					$id = $value['bespeak_id'];
					$value['bespeak_id']='bespeak_opera_adv.html?bespeak_id='.$id;
				}
				$data['adv'][$key]=$value;

			}
			$num=count($data);
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function rentBespeak()
	{
		$user_id = Perm::$userId;
		$user['user_id']=$user_id;
		$user['bespeak_list']=2;
		$rent['bespeak_list']=2;
		$rent['user_id']=0;
		$rent['bespeak_status']=1;
		$rent['bespeak_state']=1;
		$USER_BespeakModel = new USER_BespeakModel();
		$data['temp']            = $USER_BespeakModel->getBespeakList($user);
		$data['rent']            = $USER_BespeakModel->getBespeakList($rent);
		// var_dump($data);
		// exit();
		if ("json" == $this->typ)
		{
			foreach ($data['temp'] as $key => $value) {
					if($value['bespeak_state']=='0'){
						$value['bespeak_state']='无效，审核不通过';
					}elseif ($value['bespeak_state']=='1') {
						$value['bespeak_state']='预约正在处理';
					}elseif ($value['bespeak_state']=='2') {
						$value['bespeak_state']='预约已完成';
					}
					$data['temp'][$key]=$value;
			}
			foreach ($data['rent'] as $key => $value) {
					if($value['bespeak_state']=='0'){
						$value['bespeak_state']='无效，审核不通过';
					}elseif ($value['bespeak_state']=='1') {
						$value['bespeak_state']='租赁中';
					}elseif ($value['bespeak_state']=='2') {
						$value['bespeak_state']='租赁已经结束';
					}
				$value['bespeakinfo']='bespeak_rent_info.html?bespeak_id='.$value['bespeak_id'];

					if(!empty($data['temp'])){
						foreach ($data['temp'] as $k1 => $v1) {
							if($v1['bespeak_title']==$value['bespeak_title']){
								$value['bespeak_click']='<a onclick="show()">已预约</a>';
								$value['bespeak_id']='#';
							}else{
								$value['bespeak_click']='预约';
								$id = $value['bespeak_id'];
								$value['bespeak_id']='bespeak_opera_rent.html?bespeak_id='.$id;
							}
						}
					}else{
						$value['bespeak_click']='预约';
						$id = $value['bespeak_id'];
						$value['bespeak_id']='bespeak_opera_rent.html?bespeak_id='.$id;
					}
					$data['rent'][$key]=$value;
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
		$img = request_string('img');

		$edit_bespeak_row['true_name']                  = $true_name;
		$edit_bespeak_row['bespeak_img']                  = $img;
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
		$starttime   = request_string('starttime');
		$bespeak_place = request_string('bespeak_place');
		$bespeak_address = request_string('bespeak_address');
		$bes_address = request_string('bes_address');
		$img = request_string('img');

		$edit_bespeak_row['true_name']                  = $true_name;
		$edit_bespeak_row['bespeak_img']                  = $img;
		$edit_bespeak_row['usercontact']                  = $usercontact;
		$edit_bespeak_row['bespeak_area_info']     = $bespeak_area_info;
		$edit_bespeak_row['bes_address'] = $bes_address;
		$edit_bespeak_row['bespeak_address']     = $bespeak_address;
		$edit_bespeak_row['starttime']     = date('Y-m-d ',time()).$starttime;
		$edit_bespeak_row['bespeak_com']     = $bespeak_com;
		$edit_bespeak_row['bespeak_place']        = $bespeak_place;
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
			echo json_encode($ret);
			exit();
		}
	}

	public function addAdvBespeak()
	{
		$user_id = Perm::$userId;

		$true_name    = request_string('true_name');
		$usercontact    = request_string('usercontact');
		$bespeak_title = request_string('bespeak_title');
		$bespeak_com = request_string('bespeak_com');

		$edit_bespeak_row['true_name']        = $true_name;
		$edit_bespeak_row['usercontact']        = $usercontact;
		$edit_bespeak_row['bespeak_com']        = $bespeak_com;
		$edit_bespeak_row['bespeak_title']        = $bespeak_title;
		$edit_bespeak_row['starttime']        = get_date_time();
		$edit_bespeak_row['user_id']        = $user_id;
		$edit_bespeak_row['bespeak_list']   = 1;
		// var_dump($edit_bespeak_row);exit();
		if (empty($usercontact)) {
			$bespeak_id = request_int('id');
			$data = $this->userBespeakModel->getBespeakInfo($bespeak_id);
			if(!empty($data)){
				$status                              = 200;
				$msg                                 = _('success');
			}else
			{
				$this->userBespeakModel->sql->rollBackDb();
				
				$status = 250;
				$msg    = _('failure');
			}
		}else{
			$bespeak['user_id']=Perm::$userId;
			$bespeak['bespeak_title']=$bespeak_title;
			$ones = $this->userBespeakModel->getBespeakList($bespeak);
			foreach ($ones as $k => $v) {
				$besid=$v['bespeak_id'];
			}
			if(empty($besid)){
				$this->userBespeakModel->sql->startTransactionDb();
			// var_dump($edit_bespeak_row);exit();
				$flag = $this->userBespeakModel->addBespeak($edit_bespeak_row, true);
				
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
			}else
				{
					$this->userBespeakModel->sql->rollBackDb();
					
					$status = 250;
					$msg    = _('failure');
				}			

			$data = $edit_bespeak_row;
		}
		$this->data->addBody(-140, $data, $msg, $status);

	}


	public function addRentBespeak()
	{
		$user_id = Perm::$userId;

		$true_name    = request_string('true_name');
		$usercontact    = request_string('usercontact');
		$bespeak_title = request_string('bespeak_title');
		$bespeak_com = request_string('bespeak_com');
		$starttime = request_string('starttime');

		$edit_bespeak_row['true_name']        = $true_name;
		$edit_bespeak_row['usercontact']        = $usercontact;
		$edit_bespeak_row['starttime']        = $starttime;
		$edit_bespeak_row['bespeak_com']        = $bespeak_com;
		$edit_bespeak_row['bespeak_title']        = $bespeak_title;
		$edit_bespeak_row['user_id']        = $user_id;
		$edit_bespeak_row['bespeak_list']   = 2;
		// var_dump($edit_bespeak_row);exit();
		if (empty($usercontact)) {
			$bespeak_id = request_int('id');
			$data = $this->userBespeakModel->getBespeakInfo($bespeak_id);
			if(!empty($data)){
				$status                              = 200;
				$msg                                 = _('success');
			}else
			{
				$this->userBespeakModel->sql->rollBackDb();
				
				$status = 250;
				$msg    = _('failure');
			}
		}else{
			$bespeak['user_id']=Perm::$userId;
			$bespeak['bespeak_title']=$bespeak_title;
			$ones = $this->userBespeakModel->getBespeakList($bespeak);

			foreach ($ones as $k => $v) {
				$besid=$v['bespeak_id'];
			}
			if(empty($besid)){
				$this->userBespeakModel->sql->startTransactionDb();
			// var_dump($edit_bespeak_row);exit();
				$flag = $this->userBespeakModel->addBespeak($edit_bespeak_row, true);
				
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
			}else
				{
					$this->userBespeakModel->sql->rollBackDb();
					
					$status = 250;
					$msg    = _('failure');
				}			

			$data = $edit_bespeak_row;
		}
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
		$bespeak_id = request_string('id');
		
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

	public function getbespeaklist()
	{
		$user_id = Perm::$userId;

		$bespeak_id['bespeak_id'] = request_int('id');
		$USER_BespeakModel = new USER_BespeakModel();
		$data    = $USER_BespeakModel->getBespeakList($bespeak_id);
		foreach ($data as $key => $value) {
			$bespeak['bespeak_title']=$value['bespeak_title'];
			$bespeak['user_id']=$user_id;
			$one    = $USER_BespeakModel->getBespeakList($bespeak);

			if(empty($one)){
				$value['bespeak_id']='bespeak_opera_rent.html?bespeak_id='.$value['bespeak_id'];
				$value['bespeaka']='申请租赁';
			}else{
				$value['bespeak_id']=' ';
				$value['bespeaka']='已租赁';
			}
			$data[$key]=$value;
		}
		$this->data->addBody(-140, $data);
	}


	public function getbespeaklist1()
	{
		$user_id = Perm::$userId;

		$bespeak_id['bespeak_id'] = request_int('id');
		$USER_BespeakModel = new USER_BespeakModel();
		$data    = $USER_BespeakModel->getBespeakList($bespeak_id);
		foreach ($data as $key => $value) {
			$bespeak['bespeak_title']=$value['bespeak_title'];
			$bespeak['user_id']=$user_id;
			$one    = $USER_BespeakModel->getBespeakList($bespeak);

			if(empty($one)){
				$value['bespeak_id']='bespeak_opera_adv.html?bespeak_id='.$value['bespeak_id'];
				$value['bespeaka']='申请预约';
			}else{
				$value['bespeak_id']=' ';
				$value['bespeaka']='已预约';
			}
			$data[$key]=$value;
		}
		$this->data->addBody(-140, $data);
	}

	public function getplace($one,$two){
		$one = request_string('one');
		$two = request_string('two');
		$oneti = $this->addr_to_location($one);
		$twoti = $this->addr_to_location($two);
		$distance = $this->getDistance($oneti['lng'],$oneti['lat'],$twoti['lng'],$twoti['lat']);
		$data = array();
		$data['dis']=$distance/1000;

		$this->data->addBody(-140, $data);
	}

	//计算距离
	public function getDistance($lat1, $lng1, $lat2, $lng2){      
          $earthRadius = 6378138; //近似地球半径米
          // 转换为弧度
          $lat1 = ($lat1 * pi()) / 180;
          $lng1 = ($lng1 * pi()) / 180;
          $lat2 = ($lat2 * pi()) / 180;
          $lng2 = ($lng2 * pi()) / 180;
          // 使用半正矢公式  用尺规来计算
        $calcLongitude = $lng2 - $lng1;
          $calcLatitude = $lat2 - $lat1;
          $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  
       $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
          $calculatedDistance = $earthRadius * $stepTwo;
          return round($calculatedDistance);
   }
	//地址转经纬度
	public function addr_to_location($addr)
	{	$location=array();
		//去空格
		$addr=str_replace(' ','',$addr);
		//查出经纬度
		$location_json = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address=$addr&output=json&ak=CvQKTKQ3upsNAL7sLLFTvDqHc4g8nChG");
		//解析出经度
		$location_json=(string)$location_json;
		$lng_pos=strpos($location_json,'lng"');
		$lng_pos=$lng_pos+5;
		$lat_pos=strpos($location_json,',"lat"');
		$sub_len=(int)$lat_pos-(int)$lng_pos;
		$lng=substr($location_json,$lng_pos,$sub_len);
		//解析出纬度
		$lat_pos=strpos($location_json,'lat"');
		$lat_pos=$lat_pos+5;
		$end_pos=strpos($location_json,'},"pre');
		$sub_len=(int)$end_pos-(int)$lat_pos;
		$lat=substr($location_json,$lat_pos,$sub_len);
		$location['lat']=$lat;
		$location['lng']=$lng;
		return $location;
	}


}


?>