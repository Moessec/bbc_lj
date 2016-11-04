<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<script type="text/javascript" src="<?=$this->view->js?>/cart.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

<div class="wrap">
	<div class="head_cont clearfix">
		<div class="nav_left" style="float:none;">
			<a href="index.php" class="logo"><img src="<?=$this->web['web_logo']?>"/></a>
			<a href="#" class="download iconfont"></a>
		</div>
	</div>
</div>
<div class="wrap wrap_w">
	<div class="shop_cart_head clearfix">
		<div class="cart_head_left">
			<h4><?=_('我的购物车')?></h4>
			<p><?=_('查看购物车商品清单,增加减少商品数量,并勾选想要的商品进入下一步操作。')?></p>
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

	<?php if($data['count'] == 0){?>
		<div class="cart_empty clearfix">
			<div class="cart_log">
				<img src="<?=$this->view->img?>/img_sc_icon2.png"/>
			</div>
			<div class="empty-warn">
				<p><?=_('您的购物车还是空的')?></p>
				<div>
					<a href="<?= Yf_Registry::get('url') ?>"><span class="iconfont icon-mashangqugouwu"></span><?=_('马上去购物')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><span class="iconfont icon-dingdan rel_top2"></span><?=_('查看自己的订单')?></a>
				</div>
			</div>
		</div>
	<?php }else{?>
	<ul class="cart_goods_type clearfix">
		<li><a class="goods_selected bbc_bg"><?=_('全部商品')?><i>(<?=($data['count'])?>)</i></a></li>
		<?php unset($data['count']);?>

	</ul>
	<div class="cart_goods">
		<ul class='cart_goods_head clearfix'>
			<li class="done"><?=_('操作')?></li>
			<li class="price_all"><?=_('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_num"><?=_('数量')?></li>
			<li class="goods_price"><?=_('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_name"><?=_('商品')?></li>
			<li class="cart_goods_all cart-checkbox " style="float:left;"><input class="checkall" type="checkbox"  data-type="all"><div class="select_all"><?=_('全选')?></div></li>
		</ul>
		<form id="form" action="?ctl=Buyer_Cart&met=confirm" method='post'>
		<ul class="cart_goods_list clearfix">
			<?php foreach($data as $key=>$val){?>
				<li class="carts_content">
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<input class="checkshop checkitem" type="checkbox" data-type="all">
							<span><i class="iconfont icon-icoshop"></i><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($key)?>"><?=($val['shop_name'])?></a></span>
						</p>
					</div>
					<table id="table_list" class="table_list">
						<tbody class="rel_good_infor">
						<?php foreach($val['goods'] as $k=>$v){ ?>
							<tr class="row_line">
								<td class="goods_sel cart-checkbox">
									<p>
										<input class="checkitem" type="checkbox" name="product_id[]" value="<?=($v['cart_id'])?>" <?php if($v['IsHaveBuy']){?>disabled="" title="您已达限购数量" <?php }?> >
									</p>
								</td>
								<td class="goods_img"><img src="<?=($v['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name_reset">
									<a  target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($v['goods_base']['goods_id'])?>"><?=($v['goods_base']['goods_name'])?></a>

								<?php if(isset($v['goods_base']['promotion_type'])): ?>
									<p class="sal_price">
										<?php if($v['goods_base']['promotion_type'] == 'groupbuy' && $v['goods_base']['down_price']): ?>
												<?=_('团购,直降：')?><?=format_money($v['goods_base']['down_price'])?>
										<?php endif;?>

										<?php if($v['goods_base']['promotion_type'] == 'xianshi' && $v['goods_base']['down_price']): ?>
												<?=_('限时折扣,直降：')?><?=format_money($v['goods_base']['down_price'])?>
										<?php endif;?>
									</p>
								<?php endif; ?>



								<p>
									<?php if(!empty($v['goods_base']['spec'])){foreach($v['goods_base']['spec'] as $sk => $sv){ ?>
									<?=($sv)?>&nbsp;&nbsp;
									<?php }}?>
								</p>


								</td>

								<td class="goods_price">
									<?php if($v['old_price'] > 0){?><p class="ori_price"><?=($v['old_price'])?></p><?php }?>
									<p class="now_price"><?=($v['now_price'])?></p>
								</td>
								<td class="goods_num">
									<?php
									if($v['buy_limit'] && !$v['IsHaveBuy'])
									{
										$data_max = $v['buy_residue'];
									}
									else
									{
										$data_max = $v['goods_base']['goods_stock'];
									}
									?>
									<a class="<?php if($v['goods_num'] == 1){?>no_<?php }?>reduce" ><?=_('-')?></a><input id="nums" data-id="<?=($v['cart_id'])?>" data-max="<?=($data_max)?>" value="<?=($v['goods_num'])?>"><a class="<?php if($data_max <= 1){?>no_<?php }?>add" ><?=_('+')?></a>
								</td>
								<td class="price_all cell<?=($v['cart_id'])?>">
									<span class="subtotal"><?=($v['sumprice'])?></span>
								</td>
								<td class="done del"><a data-param="{'ctl':'Buyer_Cart','met':'delCartByCid','id':'<?=($v['cart_id'])?>'}"><?=_('删除')?></a></td>
							</tr>
						<?php }?>
						</tbody>
					</table>
				</li>
			<?php }?>
		</ul>
		</form>
	</div>
	<div class="pay_fix wrap3">
		<div class="wrap cart-checkbox">
			<div class="clearfix cart_pad">
				<input class="checkall" type="checkbox" data-type="all">
				<div class="select_all"><?=_('全选')?></div>
				<div  class="delete"><?=_('删除')?></div>
				<a class="submit-btn-disabled submit-btn bbc_btns"><?=_('去付款')?><span class="iconfont icon-iconjiantouyou"></span></a>
				<div class="cart-sum">
					<span><?=_('合计(不含运费)：')?></span>
					<strong class="price"><?=(Web_ConfigModel::value('monetary_unit'))?><em class="subtotal subtotal_all">0.00</em></strong>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="pages">
        <div>
            <span class="goods_prev"><</span>
            <a href="#" class="active1">1</a><a href="#">2</a><a href="#">3</a>
            <span class="goods_next">></span>
        </div>
    </div>-->

	<?php }?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>