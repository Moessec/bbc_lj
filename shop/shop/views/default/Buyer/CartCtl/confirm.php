<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
	<script type="text/javascript" src="<?=$this->view->js?>/cart.js"></script>
	<script type="text/javascript" src="<?=$this->view->js?>/alert.js"></script>
	<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
	<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
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
	<div class="wrap">
		<div class="shop_cart_head clearfix">
			<div class="cart_head_left">
				<h4><?=('确认订单')?></h4>
				<p><?=('请仔细核对收货,发货等信息,以确保物流快递能准确投递')?>.</p>
			</div>
			<ul class="cart_process">
				<li class="mycart">
					<i class="iconfont icon-wodegouwuche"></i>
					<div class="line">
						<p></p>
						<span></span>
					</div>
					<h4><?=('我的购物车')?><h4>
				</li>
				<li class="mycart process_selected1">
					<i class="iconfont icon-iconquerendingdan bbc_color"></i>
					<div class="line">
						<p class="bbc_border"></p>
						<span class="bbc_bg bbc_border"></span>
					</div>
					<h4 class="bbc_color"><?=('确认订单')?><h4>
				</li>
				<li class="mycart">
					<i class="iconfont icon-icontijiaozhifu"></i>
					<div class="line">
						<p></p>
						<span></span>
					</div>
					<h4><?=('支付提交')?><h4>
				</li>
				<li class="mycart">
					<i class="iconfont icon-dingdanwancheng"></i>
					<div class="line">
						<p></p>
						<span></span>
					</div>
					<h4><?=('订单完成')?><h4>
				</li>
			</ul>
		</div>
		<ul class="receipt_address clearfix">
		<div id="address_list">
		<?php if(isset($data['address'])){$total = 0; $total_dian_rate = 0; foreach ($data['address'] as $key => $value) {
		?>
			<li class="<?php if($value['user_address_default'] == 1){?>add_choose<?php }?>" id="addr<?=($value['user_address_id'])?>">
				<input type="hidden" id="user_address_province_id" value="<?=($value['user_address_province_id'])?>">
				<input type="hidden" id="user_address_city_id" value="<?=($value['user_address_city_id'])?>">
				<input type="hidden" id="user_address_area_id" value="<?=($value['user_address_area_id'])?>">
				<div class="editbox">
					<a onclick="edit_address(<?=($value['user_address_id'])?>)"><?=('编辑')?></a>
					<a onclick="del_address(<?=($value['user_address_id'])?>)"><?=('删除')?></a>
				</div>
				<h5><?=($value['user_address_contact'])?></h5>
				<p><?=($value['user_address_area'])?> <?=($value['user_address_address'])?></p>
				<div><span class="phone"><i class="iconfont icon-shouji"></i><span><?=($value['user_address_phone'])?></span></span></div>
			</li>
			<?php }}?>
		</div>
			<div class="add_address">
				<a><?=_('+')?></a>
			</div>
		</ul>

		<h4 class="confirm"><?=('支付方式')?></h4>
			<div class="pay_way pay-selected" pay_id="1">
				<i></i><?=_('在线支付')?>
			</div>
			<div class="pay_way" pay_id="2">
				<i></i><?=_('货到付款')?>
			</div>

		<h4 class="confirm"><?=('确认商品信息')?></h4>
		<div class="cart_goods">
			<ul class='cart_goods_head clearfix'>
				<li class="price_all"><?=('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_num"><?=('数量')?></li>
				<li class="confirm_sale"><?=('优惠')?></li>
				<li class="goods_price"><?=('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_name"><?=('商品')?></li>
				<li class="cart_goods_all"></li>





			</ul>
			<?php unset($data['glist']['count']); foreach($data['glist'] as $key=>$val){?>
			<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span>
								<i class="iconfont icon-icoshop"></i>
								<a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($key)?>"><?=($val['shop_name'])?></a>
							</span>
							<?php if($val['voucher_base']){?>
								<div style="position:relative; margin-left: 14px;margin-top: 20px;float:left;">
									<a onclick="showVoucher(this)">
										<i class="iconfont  icon-daijinquan"></i><?=('可用代金券')?>
										<i class="iconfont  icon-iconjiantouxia"></i>
									</a>
									<?php if($val['voucher_base']){?>
										<div class="voucher_detail box_list">
											<ul class="voucher clearfix">
												<?php foreach($val['voucher_base'] as $voukey => $vouval){?>
													<li class="voucher_list">
														<div class="quan_num"><?=(Web_ConfigModel::value('monetary_unit'))?><?=($vouval['voucher_price'])?></div>
														<div class="quan_condition">
															<span><?=($vouval['voucher_title'])?></span>
															<span><?=('有效期:')?> <time><?=($vouval['voucher_start_date'])?></time> <?=_(-'')?> <time><?=($vouval['voucher_end_date'])?></time></span>
															<span><?=($vouval['voucher_desc'])?></span>
														</div>
														<div>
															<input type="hidden" name="shop_id" id="shop_id" value="<?=($vouval['voucher_shop_id'])?>">
															<input type="hidden" name="voucher_id" id="voucher_id" value="<?=($voukey)?>">
															<input type="hidden" name="voucher_price" id="voucher_price" value="<?=($vouval['voucher_price'])?>">
															<input type="hidden" name="voucher_code" id="voucher_code" value="<?=($vouval['voucher_code'])?>">
															<a onclick="useVoucher(this)" class="quan_get"><?=_('使用')?></a>
														</div>
													</li>
												<?php }?>
												<div class="bk"><i class="iconfont icon-cuowu"></i></div>

											</ul>

										</div>
									<?php }?>
								</div>
							<?php }?>
							<?php if($val['increase_info']){?>
							<?php foreach($val['increase_info'] as $inckey => $incval){?>
							<p class="bus_sale">
								<span>&bull;<?=_('购物满')?> <?=format_money($incval['rule_info']['rule_price'])?> </span><?=_('即可加价购买')?><?php if($incval['exc_goods_limit']){echo $incval['exc_goods_limit'].'样'; }?><?=_('商品，')?><a><?=_('加价购商品：')?></a>
								<i class="get" onclick="get(this)"><?=_('展示')?></i>
							</p>
						<?php } }?>
						</p>

					</div>
					<table>
						<tbody class="rel_good_infor rel_good_infor2">
						<?php foreach($val['goods'] as $k=>$v){ ?>
							<tr>
								<td class="goods_sel">
									<p>
										<input type="hidden" name="cart_id" value="<?=($v['cart_id'])?>">
									</p>
								</td>
								<td class="goods_img"><img src="<?=($v['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name_reset">
									<a  target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($v['goods_base']['goods_id'])?>"><?=($v['goods_base']['goods_name'])?></a>
									<p>
										<?php if(!empty($v['goods_base']['spec'])){foreach($v['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
								</td>

								<td class="goods_price">
									<?php if($v['old_price'] > 0){?><p class="ori_price"><?=($v['old_price'])?></p><?php }?>
									<p class="now_price"><?=($v['now_price'])?></p>
									
								</td>
								<td class="confirm_sale">
									<?php if(isset($v['goods_base']['promotion_type'])): ?>
										<?php if($v['goods_base']['promotion_type'] == 'groupbuy'): ?>
											<p class="sal_price"><?=_('团购')?></p>
											<?php if($v['goods_base']['down_price']): ?><p><?=_('直降')?><?=format_money($v['goods_base']['down_price'])?></p><?php endif; ?>
										<?php endif;?>
										<?php if($v['goods_base']['promotion_type'] == 'xianshi'): ?>
											<p class="sal_price"><?=_('限时折扣')?></p>
											<?php if($v['goods_base']['down_price']): ?><p><?=_('直降')?><?=format_money($v['goods_base']['down_price'])?></p><?php endif; ?>
										<?php endif;?>
									<?php endif; ?>
								</td>
								<td class="goods_num">
									<span><?=($v['goods_num'])?></span>
								</td>
								<td class="price_all">
									<span class="subtotal"><?=($v['sumprice'])?></span>
								</td>
							</tr>
							<?php }?>
						</tbody>
					</table>
					<?php if($val['increase_info']){?>
						<?php foreach($val['increase_info'] as $incgkey => $incgval){?>
							<div class="sale_detail <?=($incgkey)?> box_list">
								<ul class="increase clearfix">
									<input type="hidden" name="increase_id" value="<?=($incgkey)?>">
									<input type="hidden" name="exc_goods_limit" id="exc_goods_limit" value="<?=($incgval['exc_goods_limit'])?>">
									<input type="hidden" name="shop_id" id="shop_id" value="<?=($incgval['shop_id'])?>">
									<?php foreach($incgval['exc_goods'] as $excgkey => $excgval){?>
										<li class="increase_list">
											<input type="hidden" name="redemp_goods_id" id="redemp_goods_id" value="<?=($excgval['redemp_goods_id'])?>">
											<a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($excgval['goods_id'])?>">
												<div class="quan_num"><img alt="<?=($excgval['goods_name'])?>" src="<?=image_thumb
($excgval['goods_image'],60,60)?>"></div>
											</a>
											<div class="quan_condition">
												<span><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($excgval['goods_id'])?>"><?=($excgval['goods_name'])?></a></span>
												<span><?=_('加购价')?> <?=format_money($excgval['redemp_price'])?></span>
												<input type="hidden" class="redemp_price" value="<?=($excgval['redemp_price'])?>">
												<input type="hidden" class="redemp_price_rate" value="<?=($excgval['redemp_price']*(100-$user_rate)/100)?>">
											</div>
											<div><a onclick="jiabuy(this)" class="quan_get"><?=_('购买')?></a></div>
										</li>
									<?php }?>
									<div class="bk"><i class="iconfont icon-cuowu"></i></div>

								</ul>

							</div>
						<?php }?>
					<?php }?>
				</li>
			</ul>
			<?php if($val['mansong_info']){?>
					<?php if($val['mansong_info']['rule_discount']){?>
					<span><?=_('店铺优惠')?><?=($val['mansong_info']['rule_discount'])?></span>
					<?php }?>
					<?php if($val['mansong_info']['gift_goods_id']){?>
						<img title="<?=($val['mansong_info']['goods_name'])?>" alt="<?=($val['mansong_info']['goods_name'])?>" src="<?=image_thumb($val['mansong_info']['goods_image'],60,60)?>">
						<?=($val['mansong_info']['goods_name'])?>
					<?php }?>
			<?php }?>
			<div class="goods_remark clearfix">
				<p class="remarks"><span><?=_('备注：')?></span><input type="text" class="remarks_content" name="remarks" id="<?=($key)?>" placeholder="<?=_('限45个字（定制类商品，请将购买需求在备注中做详细说明）')?>"><?=_('提示：请勿填写有关支付、收货、发票方面的信息')?></p>
				<div class="order_total">
					<p class="clearfix">
						<span><?=_('商品金额')?></span>
						<i class="price<?=($key)?>"><?=(number_format($val['sprice'],2,'.',''))?></i>
					</p>
					<p class="clearfix trans<?=($key)?>">
						<span><?=_('物流运费')?></span>
						<strong class="trancon<?=($key)?>"><?=($data['cost'][$key]['con'])?></strong>
						<i class="trancost<?=($key)?>">
							<?php if($data['cost'][$key]['cost'] > 0){?>
							<?=(number_format($data['cost'][$key]['cost'],2))?>
							<?php }else{ ?>
								<?=_('免运费')?>
							<?php }?>
						</i>
					</p>
					<p class="dian_total clearfix">
						<span class=""><?=_('本店合计')?></span>
						<em></em>
						<i class="sprice<?=($key)?>">
							<?php
							echo number_format($data['cost'][$key]['cost']+$val['sprice'],2,'.','');

							$dian_rate = $val['sprice']*(100-$user_rate)/100;
							?>
							<input type="hidden" name="dian_total_val" class="dian_total_val" value="<?=number_format($dian_rate,2,'.','')?>">
						</i>
						</input>
					</p>
					<p class="shop_voucher<?=($key)?>"></p>
				</div>
			</div>
			<?php
				$total += $data['cost'][$key]['cost']+$val['sprice'];
				$total_dian_rate += $dian_rate;
			}?>
			<div class="frank clearfix">
				<div class="invoice">
					<h3><?=_('发票信息')?></h3>
					<div class="invoice-cont">
						<input type="hidden" name="invoice_id" value="">
						<span class="mr10"> <?=_('不开发票')?> </span><a class="invoice-edit"><?=_('修改')?></a>
					</div>
				</div>
				<p class="back_cart"><a id="back_cart"><i class="iconfont icon-iconjiantouzuo rel_top2"></i><?=_('返回我的购物车')?></a></p>

				<p class="submit" style="text-align: center;">
					<span>
						<?=_('订单金额：')?>
                            <strong>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="total"><?=(number_format($total,2,'.','') )?></i>
                            </strong>
					</span>

					<?php if($user_rate > 0){?>
						<span>
							<?=_('会员折扣：')?>
							<strong>
								-<?=(Web_ConfigModel::value('monetary_unit'))?><i class="rate_total"><?=number_format($total_dian_rate,2,'.','')?></i>
							</strong>
						</span>
					<?php }else{$user_rate = 100;}?>

					<span>
						<?php $after_total = number_format($total-$total_dian_rate,2,'.','');?>
						<?=_('支付金额：')?>
						<strong>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="after_total bbc_color"><?=(number_format($after_total,2,'.','') )?></i>
						</strong>
					</span>

					<a id="pay_btn" class="bbc_btns"><?=_('提交订单')?></a>
				</p>

			</div>
		</div>
	</div>

	<!-- 订单提交遮罩 -->
	<div id="mask_box" style="display:none;">
		<div class='loading-mask'></div>
		<div class="loading">
			<div class="loading-indicator">
				<img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>
				<br/><span class="loading-msg"><?=_('正在提交订单，请稍后...')?></span>
			</div>
		</div>
	</div>

<script>
	var app_id = <?=(Yf_Registry::get('shop_app_id'))?>;
	if(<?=($user_rate)?>)
	{
		rate = <?=($user_rate)?>;
	}
	else
	{
		rate = 100;
	}

	$(function(){
		$(".remarks_content").val("");
		$(".remarks_content").keyup(function(){
			var len = $(this).val().length;
			if(len > 45){
				$(this).val($(this).val().substring(0,45));
			}
		});
	});
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>