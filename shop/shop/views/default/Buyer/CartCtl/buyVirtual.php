<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<script type="text/javascript" src="<?=$this->view->js?>/virtual.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<div class="wrap">
	<div class="head_cont clearfix">
		<div class="nav_left">
			<a href="index.php" class="logo"><img src="<?=$this->web['web_logo']?>"/></a>
			<a href="#" class="download iconfont"></a>
		</div>
	</div>
</div>
<div class="wrap wrap_w">
	<div class="shop_cart_head clearfix">
		<div class="cart_head_left">
			<h4><?=_('购买兑换码')?></h4>
			<p><?=_('设置购买数量')?></p>
		</div>
		<ul class="cart_process">
			<li class="mycart process_selected1">
				<i class="iconfont icon-wodegouwuche bbc_color"></i>
				<div class="line">
					<p class="bbc_border"></p>
					<span class="bbc_bg bbc_border"></span>
				</div>
				<h4 class="bbc_color"><?=_('我的购物车')?><h4>
			</li>
			<li class="mycart">
				<i class="iconfont icon-iconquerendingdan"></i>
				<div class="line">
					<p></p>
					<span></span>
				</div>
				<h4><?=_('确认订单')?><h4>
			</li>
			<li class="mycart">
				<i class="iconfont icon-icontijiaozhifu"></i>
				<div class="line">
					<p></p>
					<span></span>
				</div>
				<h4><?=_('支付提交')?><h4>
			</li>
			<li class="mycart">
				<i class="iconfont icon-dingdanwancheng"></i>
				<div class="line">
					<p></p>
					<span></span>
				</div>
				<h4><?=_('订单完成')?><h4>
			</li>

		</ul>
	</div>

	<div class="cart_goods">
		<ul class='cart_goods_head clearfix'>
			<li class="done"><?=_('操作')?></li>
			<li class="price_all"><?=_('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_num"><?=_('数量')?></li>
			<li class="goods_price"><?=_('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="cart_goods_all "></li>
			<li class="goods_name"><?=_('商品')?></li>
		</ul>
		<form id="form" action="?ctl=Buyer_Cart&met=confirmVirtual" method='post'>
		<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span><i class="iconfont icon-icoshop"></i><?=($data['shop_base']['shop_name'])?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($data['shop_base']['shop_id'])?>" class="cus_ser"></a>
					</div>
					<table id="table_list">
						<tbody class="rel_good_infor">
							<tr class="row_line">
								<td class="goods_img"><img src="<?=($data['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name" style="width: 536px;">
									<a  target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($data['goods_base']['goods_id'])?>"><?=($data['goods_base']['goods_name'])?></a>

									<?php if(isset($data['goods_base']['goods_base']['promotion_type'])): ?>
										<p class="sal_price">
										<?php if($data['goods_base']['goods_base']['promotion_type'] == 'groupbuy' && $data['goods_base']['goods_base']['down_price']): ?>
											<?=_('团购,直降：')?><?=format_money($data['goods_base']['goods_base']['down_price'])?>
										<?php endif;?>

										<?php if($data['goods_base']['goods_base']['promotion_type'] == 'xianshi' && $data['goods_base']['goods_base']['down_price']): ?>
											<?=_('限时折扣,直降：')?><?=format_money($data['goods_base']['goods_base']['down_price'])?>
										<?php endif;?>
										</p>
									<?php endif; ?>

									<p>
										<?php if(!empty($data['goods_base']['spec'])){foreach($data['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>

									<p>
										<input type="hidden" id="goods_id" name="goods_id" value="<?=($data['goods_base']['goods_id'])?>">
										<input type="hidden" id="goods_price" value="<?=($data['goods_base']['now_price'])?>">
									</p>
								</td>
								<td class="goods_price">
									<?php if($data['goods_base']['old_price'] > 0){?><p class="ori_price"><?=($data['goods_base']['old_price'])?></p><?php }?>
									<p class="now_price"><?=($data['goods_base']['now_price'])?></p>
								</td>
								<td class="goods_num">
									<?php
									if($data['buy_limit'])
									{
										$data_max = $data['buy_residue'];
									}
									else
									{
										$data_max = $data['goods_base']['goods_stock'];
									}
									?>
									<a class="<?php if($data['goods_base']['cart_num'] == 1){?>no_<?php }?>reduce" ><?=_('-')?></a>
									<input id="nums" name="nums" data-id="<?=($data['goods_base']['goods_id'])?>" data-max="<?=($data_max)?>" value="<?=($data['goods_base']['cart_num'])?>">
									<a class="<?php if($data_max <= 1){?>no_<?php }?>add" ><?=_('+')?></a>
								</td>
								<td class="price_all cell<?=($data['goods_base']['goods_id'])?>">
									<span class="subtotal"><?=($data['goods_base']['sumprice'])?></span>
								</td>
								<td class="done del"><a onclick="collectGoods(<?=($data['goods_base']['goods_id'])?>)"><i class="iconfont icon-wenjianjia rel_top2" style="font-size: 20px;"></i><?=_('加入收藏夹')?></a></td>
							</tr>
						</tbody>
					</table>
				</li>
		</ul>
		</form>
	</div>
	<div class="pay_fix wrap3">
		<div class="wrap wrap2 cart-checkbox">
			<a class="submit-btn bbc_btns"><?=_('去付款')?><span class="iconfont icon-iconjiantouyou"></span></a>
			<div class="cart-sum">
				<span><?=_('合计：')?></span>
				<strong class="price"><?=(Web_ConfigModel::value('monetary_unit'))?><em class="subtotal subtotal_all"><?=($data['goods_base']['sumprice'])?></em></strong>
			</div>

		</div>
	</div>

</div>

<script>
	//收藏商品
	window.collectGoods = function(e){
		if (<?=Perm::checkUserPerm()?1:0?>)
		{
			$.post(SITE_URL  + '?ctl=Goods_Goods&met=collectGoods&typ=json',{goods_id:e},function(data)
			{
				if(data.status == 200)
				{
					Public.tips.success(data.data.msg);
					//$(".icon-icoheart").addClass("icon-taoxinshi").removeClass('icon-icoheart');
					//$.dialog.alert(data.data.msg);
				}
				else
				{
					Public.tips.error(data.data.msg);
					//$.dialog.alert(data.data.msg);
				}
			});
		}
		else
		{
			Public.tips.warning('<?=_('请先登录！')?>');
			//$.dialog.alert('请先登录!');
		}

	}
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>