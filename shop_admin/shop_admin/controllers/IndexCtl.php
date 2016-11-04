<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends AdminController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}

	public function index()
	{
		/*
		$Page_LayoutModel = new Page_LayoutModel();

		$id = 1;
		$data = $Page_LayoutModel->getOne($id);

		echo '<pre>';
		print_r($data);
		//$d['layout_structure'] =  $data['layout_structure'];
		//$Page_LayoutModel->edit($id, $d);
		*/
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