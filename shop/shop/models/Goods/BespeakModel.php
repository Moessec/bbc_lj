<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_BespeakModel extends Goods_Bespeak
{
	const COLOR = 1;

	/**
	 * 读取分页列表
	 *
	 * @param  int $spec_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBespeakList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	// public function getSpecNameById($spec_id)
	// {
	// 	$sepc_name = array();
 //        $GoodsBespeakValueModel = new GoodsBespeakValueModel();
 //        $spec_value = $GoodsBespeakValueModel->getOne($spec_id);
 //        if($spec_value)
 //        {
 //            $id = $spec_value['spec_id'];
 //            $data = $this->getOne($id);
 //            if($data)
 //            {
 //                $sepc_name = $data['spec_name'];
 //            }
 //        }

	// 	return $sepc_name;
	// }
}

?>