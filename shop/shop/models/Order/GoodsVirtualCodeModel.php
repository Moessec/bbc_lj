<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Order_GoodsVirtualCodeModel extends Order_GoodsVirtualCode
{
	const SHOP_STATUS_OPEN  = 3;  //О©╫О©╫О©╫О©╫
	const VIRTUAL_CODE_NEW  = 0;    //О©╫О©╫О©╫О©╫р╩О©╫О©╫О©╫н╢й╧О©╫О©?
	const VIRTUAL_CODE_USED = 1;    //О©╫О©╫О©╫О©╫р╩О©╫О©╫О©╫О©╫О©╫й╧О©╫О©?

	public function __construct()
	{
		parent::__construct();
		$this->codeUse = array(
			'0' => _('ряй╧сц'),
			'1' => _('н╢й╧сц'),
		);
	}


	public function getVirtualCode($cond_row = array())
	{
		$data = $this->getByWhere($cond_row);

		foreach ($data as $key => $val)
		{
			$data[$key]['code_status'] = $this->codeUse[$val['virtual_code_status']];
		}

		return $data;
	}

}

?>