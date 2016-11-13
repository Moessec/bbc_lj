<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Waybill_ZpsModel extends Waybill_Ps
{		
	// public $_tableName       = 'waybill_zps';

	public static $waybillTplEnable = array(
		"0" => '否',
		"1" => '是'
	);

	const ENABLE_TRUE = 1;
	public $jsonKey = array('waybill_tpl_item');
	
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
	public function getZpsList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{	
		
			if ( empty($cond_row['shop_id:IN']) )
		{
			$cond_row['shop_id'] = Perm::$shopId;
		}
		$data                = $this->listByWhere();
		// var_dump($data["items"]);exit;
		return $data;
	}









	
	
	


	
}

?>