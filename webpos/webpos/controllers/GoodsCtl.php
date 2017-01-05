<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}


class GoodsCtl extends WebPosController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

}

?>