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
}

?>