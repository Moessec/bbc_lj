<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends WebPosController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{
		include $this->view->getView();
	}


	public function main()
	{
		include $this->view->getView();
	}

	public function upload()
	{
		include $this->view->getView();
	}

	public function cropperImage()
	{
		include $this->view->getView();
	}

	public function image()
	{
		include $this->view->getView();
	}
}

?>