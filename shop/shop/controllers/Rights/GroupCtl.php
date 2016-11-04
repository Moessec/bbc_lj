<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Rights_GroupCtl extends AdminController
{
	public $baseRightsGroupModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		//include $this->view->getView();
		$this->baseRightsGroupModel = new Rights_GroupModel();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function rightsGroupList()
	{
		$user_id = Perm::$userId;

		$page = request_int('page', 1);
		$rows = request_int('rows', 100);
		$sort = request_string('sord');


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->baseRightsGroupModel->getRightsGroupList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->baseRightsGroupModel->getRightsGroupList('*', $page, $rows, $sort);
		}


		$this->data->addBody(-140, $data);
	}

	/*
	 * 2016-5-17
	 */
	public function index()
	{
		include $this->view->getView();
	}

	public function manage()
	{
		include $this->view->getView();
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$rights_group_id = $_REQUEST['rights_group_id'];
		$rows            = $this->baseRightsGroupModel->getRightsGroup($rights_group_id);
		$data            = array();

		//读取权限详情
		if (isset($_REQUEST['action']))
		{
			//得到权限
			$baseRightsModel = new Rights_Base();
			$baseRightsModel->sql->setLimit(0, 1000);
			$rights_rows = $baseRightsModel->getRights('*');

			//独立权限
			$group_rights_id_row = $rows[$rights_group_id]['rights_group_rights_ids'];
			foreach ($rights_rows as $key => $rights_row)
			{
				if (in_array($rights_row['frightid'], $group_rights_id_row))
				{
					$rights_rows[$key]['fright']   = 1;
					$rights_rows[$key]['readonly'] = false;
				}
			}

			$data['items'] = $rights_rows;
		}
		else
		{
			if ($rows)
			{
				$data = array_pop($rows);
			}
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$rightid_info = decode_json($_REQUEST['rightid']);

		$data['rights_group_id']         = $_REQUEST['rights_group_id']; // 权限组id
		$data['rights_group_name']       = $_REQUEST['rights_group_name']; // 权限组名称
		$data['rights_group_rights_ids'] = $rightid_info['rightids']; // 权限列表
		//$data['rights_group_add_time']  = $_REQUEST['rights_group_add_time']; // 创建时间

		$rights_group_id = $_REQUEST['rights_group_id'];
		$data_rs         = $data;

		unset($data['rights_group_id']);

		$flag = $this->baseRightsGroupModel->editGroup($rights_group_id, $data);

		$this->data->addBody(-140, $data_rs);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$rightid_info = decode_json($_REQUEST['rightid']);

		$data['rights_group_name']       = $_REQUEST['rights_group_name']; // 权限组名称
		$data['rights_group_rights_ids'] = $rightid_info['rightids']; // 权限列表


		$rights_group_id = $this->baseRightsGroupModel->addGroup($data, true);

		if ($rights_group_id)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$data['rights_group_id'] = $rights_group_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>