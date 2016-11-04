<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_CatModel extends Goods_Cat
{
	public $treeRows   = array();
	public $treeAllKey = null;
	public $catListAll = null;

	/**
	 * 读取分页列表
	 *
	 * @param  int $cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatList($cond_row = array(), $order_row = array('cat_displayorder' => 'ASC'), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/**
	 * 查询所有的首页导航分类
	 *
	 * @param  int $cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatListAll()
	{

		//设置cache
		$Cache = Yf_Cache::create('base');

		if ($data = $Cache->get($this->catListAll))
		{

		}
		else
		{
			$data              = $this->getCatTreeData(0, false, 0);
			$Goods_CatNavModel = new Goods_CatNavModel();
			$Goods_BrandModel  = new Goods_BrandModel();
			//循环的到下面的分类导航
			foreach ($data as $key => $value)
			{
				$row     = array("goods_cat_id" => $value['cat_id']);
				$cat_nav = $Goods_CatNavModel->getOneByWhere($row);
				if (!empty($cat_nav['goods_cat_nav_brand']))
				{
					$brand_id_list     = explode(",", $cat_nav['goods_cat_nav_brand']);
					$data[$key]['adv'] = explode(",", $cat_nav['goods_cat_nav_adv']);
					foreach ($brand_id_list as $brand_keys => $brand_values)
					{
						$data[$key]['brand'][] = $Goods_BrandModel->getOne($brand_values);
					}
				}
				$data[$key]['cat_nav'] = $cat_nav;
			}


			$Cache->save($data, $this->catListAll);
		}


		return $data;
	}


	/**
	 * 根据分类父类id读取子类信息,
	 *
	 * @param  int $cat_parent_id 父id
	 * @param  bool $recursive 是否子类信息
	 * @param  int $level 当前层级
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatTree($cat_parent_id = 0, $recursive = true, $level = 0)
	{
		//设置cache
		$Cache = Yf_Cache::create('base');

		if ($data_rows = $Cache->get($this->treeAllKey))
		{

		}
		else
		{
			$data_rows = $this->getCatTreeData($cat_parent_id, $recursive, $level);

			$Cache->save($data_rows, $this->treeAllKey);
		}

		//$this->filterCatTreeData($data_rows);

		$data['items'] = array_values($data_rows);

		return $data;
	}


    /*
     * 根据分类id 获取分组名称
     * @param int $cat_id 分组id
     * @return string $data 分组名称
     */
	public function getNameByCatid($cat_id = null)
	{
		if ($cat_id)
		{

			$name = $this->getOne($cat_id);
			if ($name)
			{
				$data = $name['cat_name'];
			}
			else
			{
				$data = '未分组';
			}
		}
		else
		{
			$data = '未分组';
		}
		return $data;
	}

	/*
	 * 获取分类导航显示数据
	 * @param   int $id 分类Id
	 * @param   array $rows 查询结果
	 * @param   bool  $tag 子分类为空是否显示
	 * @return  array $re  分类导航数据
	 */
	public function getCatDisplayRows($id = 0, $rows = null, $tag = false)
	{
		$Goods_CatModel = new Goods_CatModel();
		$re             = array();
		$data           = $Goods_CatModel->getByWhere(array('cat_parent_id' => $id));
		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$sub          = $this->getCatDisplayRows($value['id'], $rows, $tag);
				$value['sub'] = $sub;
				if (empty($sub) && in_array($value['id'], $rows))
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
				}
				elseif (!empty($sub))
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
					$re[$value['id']]['sub']      = $sub;
				}
				elseif (empty($sub) && $tag)
				{
					$re[$value['id']]['cat_id']   = $value['cat_id'];
					$re[$value['id']]['cat_name'] = $value['cat_name'];
				}
			}
		}
		return $re;
	}

	//新增分类后，获取返回数据
	/*public function getReturnData($cat_id)
	{
		$re             = array();
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatTreeData();
		if (!empty($cat_id) && !empty($data))
		{
			foreach ($cat_id as $k => $v)
			{
				foreach ($data as $key => $value)
				{
					if ($v == $value['id'])
					{
						$re[] = $value;
					}
				}
			}
		}
		return $re;
	}*/

	/*  前台全部商品分类
     * @return array $data_re 分类数据
	 * */
	public function getGoodsCatList()
	{
		$Goods_CatModel = new Goods_CatModel();
		$data_re        = array();
		//取所有一级分类
		$data_cat = $Goods_CatModel->getByWhere(array('cat_parent_id' => 0));
		if (!empty($data_cat))
		{
			foreach ($data_cat as $key => $value)
			{
				$cat_id = $value['cat_id'];
				//一级分类下的热卖商品
				$data_re[$key]['cat_name'] = $value['cat_name'];
				$data_re[$key]['cat_id']   = $value['cat_id'];
				$img                       = $this->getHotByCatId($cat_id);
				$cat                       = $this->getChildCat($cat_id);
				$data_re[$key]['img']      = $img;
				$data_re[$key]['cat']      = $cat;
			}
		}
		return $data_re;
	}
    /*
     * 根据分类id 获取热销商品
     * @param int $cat_id 分类id
     * @return array $re 热销商品
     */
	public function getHotByCatId($cat_id)
	{
		$re                = array();
		$Goods_CommonModel = new Goods_CommonModel();

		$data = $Goods_CommonModel->getCommonList(array('cat_id' => $cat_id,'common_state'=>$Goods_CommonModel::GOODS_STATE_NORMAL), array('common_salenum' => 'desc'), 1, 3);
		$re   = $Goods_CommonModel->getRecommonRow($data);
		return $re;
	}

    /*
     * 获取自分类id
     * @param int $cat_id 商品分类
     * @return array $data_re 查询数据
     */
	public function getChildCat($cat_id)
	{
		$data_re        = array();
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getByWhere(array('cat_parent_id' => $cat_id));

		if (!empty($data))
		{
			foreach ($data as $key => $value)
			{
				$data_re[$key]['cat_id']   = $value['cat_id'];
				$data_re[$key]['cat_name'] = $value['cat_name'];
				$child                     = $Goods_CatModel->getByWhere(array('cat_parent_id' => $value['cat_id']));
				$data_re[$key]['child']    = array_values($child);
			}
		}

		return array_values($data_re);
	}

}

?>