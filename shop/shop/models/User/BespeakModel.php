<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_BespeakModel extends User_Bespeak
{
	const DEFAULT_BESPEAK = 1;

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getBespeakList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getBespeakInfo($bespeak_id = null)
	{
		$data = $this->getOne($bespeak_id);

		return $data;
	}

	/**
	 * 设置为默认
	 *
	 * @param  array $de 查询条件
	 * @return array $update_flag 返回的设置的状态
	 * @access public
	 */
	public function editBespeakInfo($de)
	{
		$bespeak_ids = array_column($de, 'bespeak_id');
		
		$update_flag = $this->editBespeak($bespeak_ids, array('bespeak_status' => '2'));

		return $update_flag;
	}

	/**
	 * 读数量
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}
}

?>