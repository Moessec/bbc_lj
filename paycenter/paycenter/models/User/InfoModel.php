<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoModel extends User_Info
{
      	public static $user_identity_statu            = array(
		"1" => "待审核",
		"2" => "成功",
		"3" => "失败",
	);
	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfoList($cond_row=array(),$order_row=array(), $page=1, $rows=100, $sort='asc')
	{
                $getInfolist =  $this->listByWhere($cond_row,$order_row,$page,$rows,$sort);
                foreach ($getInfolist['items'] as $key => $value) {
                    $getInfolist['items'][$key]['user_identity_statu_con'] = _(self::$user_identity_statu[$value["user_identity_statu"]]);
                    $getInfolist['items'][$key]['user_identity_card'] = $value['user_identity_card'].'&nbsp;'; //加一个空格转为string,防止数字过大被转义出错
                }
                return $getInfolist;
	}

	public function getUserInfo($user_id = null)
	{
		//先查找paycenter数据库中有没有改用户信息
		$data = $this->getOne($user_id);

		//如果paycenter中没有用户信息就远程
		if(!$data)
		{
			$key      = Yf_Registry::get('ucenter_api_key');
			$url         = Yf_Registry::get('ucenter_api_url');
			$ucenter_app_id = Yf_Registry::get('ucenter_app_id');
			$formvars = array();

			$formvars['app_id']					= $ucenter_app_id;
			$formvars['user_name']     = Perm::$row['user_account'];

			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Login&met=getUserInfoDetail&typ=json',$url), $formvars);
			fb($rs);
			if($rs['status'] == 200)
			{
				$rs_user = current($rs['data']);
				fb($rs_user);
				$add_user_info['user_id'] = $user_id;
				$add_user_info['user_nickname'] = $rs_user['user_name'];
				$add_user_info['user_active_time'] = date('Y-m-d H:i:s');
				$add_user_info['user_realname'] = $rs_user['user_truename'];
				$add_user_info['user_email'] = $rs_user['user_email'];
				$add_user_info['user_mobile'] = $rs_user['user_mobile'];
				$add_user_info['user_qq'] = $rs_user['user_qq'];
				$add_user_info['user_avatar'] = $rs_user['user_avatar'];
				$add_user_info['user_identity_card'] = $rs_user['user_idcard'];

				$this->addInfo($add_user_info);

				$data = $add_user_info;
			}
		}
		return $data;
	}
}
?>