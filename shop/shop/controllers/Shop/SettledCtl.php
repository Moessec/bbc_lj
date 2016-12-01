<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Shop_SettledCtl extends AdminController
{

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}


	public function getShopInfo(){

		$shop_id = request_int('shop_id');
		$shop_id = 1;
		$Shop_BaseModel = new Shop_BaseModel();
		$data = $Shop_BaseModel ->getOneByWhere( array('shop_id'=>$shop_id) );
		if( $data )
		{
			$status = 200;
			$msg = _('sucess');
		}
		else
		{
			$status = 250;
			$msg = _('failure');
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}


}

?>