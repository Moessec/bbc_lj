<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<?php if($uorder_base){?>
	<form>
		<div class="recharge2-content-top content-public">
			<table style="width:100%;">
				<tr>
					<th class="recharge_order_num"><?=_('支付单号')?></th>
					<th class="recharge_order_ways"><?=_('支付方式')?></th>
					<th class="recharge_order_all"><?=_('金额')?></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				<tr>
					<td><?=($uorder_base['union_order_id'])?></td>
					<td><?=_('在线支付')?></td>
					<td><?=format_money($uorder_base['trade_payment_amount'])?></td>
					<!--  订单需要支付的金额  -->
					<input type="hidden" name="pay_amount" value="<?=($uorder_base['trade_payment_amount'])?>">
				</tr>
			</table>

			<?php if(($user_resource['user_recharge_card']>0 || $user_resource['user_money']>0) && !$act ){?>

			<p class="yue_pay"><?=_('使用余额支付')?></p>
			<div class="box clearfix">
				<?php if($user_resource['user_recharge_card'] > 0){ ?>
				<div>
					<input type="checkbox" class="pay_yue" name="choice" value="cards"><?=_('使用购物卡支付（可用余额：')?><?=format_money($user_resource['user_recharge_card'])?>）
					<input type="hidden" name="cards" id="cards" value="<?=($user_resource['user_recharge_card'])?>">
					<!--  用购物卡支付的金额  -->
					<input type="hidden" name="cards_pay" id="cards_pay" value="0"/>
				</div>
				<?php }?>

				<?php if($user_resource['user_money'] > 0){ ?>
				<div>
					<input type="checkbox" class="pay_yue" name="choice" value="money"><?=_('使用预存款支付（可用余额：')?><?=format_money($user_resource['user_money'])?>）
					<input type="hidden" name="money" id="money" value="<?=($user_resource['user_money'])?>">
					<!--  用余额支付的金额  -->
					<input type="hidden" name="money_pay" id="money_pay" value="0"/>
				</div>
				<?php }?>
				<p id="pay_password" style="display: none;">
					<span class="spanmt"><?=_('支付密码')?> :&nbsp;</span>
					<input type="password" name="password" onblur="checkPassword()" class="text text-1" />
					<span class="msg-box" style="margin-left:73px;"></span>
					<!--<span class="onright"><a target="_blank" href="./index.php?ctl=Info&met=depositlist&typ=e">充值记录</a></span>-->
				</p>

			</div>

			<?php }?>
			<!--  最后在线支付需要支付的金额  -->
			<input type="hidden" name="online_pay" id="online_pay" autocomplete="off" value="<?=($uorder_base['trade_payment_amount'])?>">

			<div class="online_pay">
				<p class="online_title"><?=_('选择在线支付')?> </p>
				<?php foreach($payment_channel as $key => $val){?>
					<div class="box-public box<?=($key)?>">
						<div class="mallbox-public">
							<img src="<?=($val['payment_channel_image'])?>" alt="<?=($val['payment_channel_name'])?>"/>
							<input type="hidden" name="payway_name" class="payway_name" value="<?=($val['payment_channel_code'])?>">
						</div>
						<span class="sel_icon"></span>
					</div>
				<?php }?>
				<input type="hidden" name="online_payway" class="online_payway" id="online_payway">
				<div class="mg clearfix" style="margin-top:14px;">
					<p class=""><?=_('在线支付：')?><?=_('￥')?><em class="online_money"><?=($uorder_base['trade_payment_amount'])?></em></p>
				</div>
			</div>
		</div>
		<div class="recharge2-content-center content-public wrap">
			<div class="pc_trans_btn"><a id="submit" class="btn_big btn_active submit_disable" style="float:left;"><?=_('确认付款')?></a></div>
			<!--<div id="test">TEst</div>-->
		</div>
		<div class="recharge2-content-bottom content-public">
			<div class="theme" style="margin-top:60px;">
				<span class="title"><?=_('支付遇到问题')?></span>
			</div>
			<div class="content">
				<div class="one">
					<h3><?=_(' 1.我还能用信用卡进行网购么？ ')?></h3>
					<p class="texts"><?=_('答：您在带有信用卡小标识')?><?=_('的店铺购物，可以直接使用信用卡快捷（含卡通）、网银进行信用卡支付，支付限额为您的卡面额度。在没有信用卡标识的店铺购物 时，您可以使用信用卡快捷（含卡通）、网银进行信用卡支付，月累计支付限额不超过500元。')?>
					</p>
				</div>
				<div class="one">
					<h3><?=_('2.没有网上银行，怎么用银行卡充值？')?></h3>
					<p class="texts"><?=_('答：储蓄卡用户，请使用储蓄卡快捷支付充值，开通后只需输入网付宝支付密码，即可完成充值。')?></p>
				</div>
				<div class="one">
					<h3><?=_('3.怎样在网上开通储蓄卡快捷支付(含卡通)？')?> </h3>
					<p class="texts"><?=_('答：已支持国内大部分主流银行在线开通。在支付宝填写信息后，根据页面引导在网上银行完成开通。')?></p>
				</div>
			</div>
		</div>
	</form>
<?php }else{ ?>
	<?=_('订单号无效，请确认后再付款。')?>
<?php } ?>



	<!-- 订单提交遮罩 -->
	<div id="mask_box" style="display:none;">
		<div class='loading-mask'></div>
		<div class="loading">
			<div class="loading-indicator">
				<img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>
				<br/><span class="loading-msg"><?=_('正在付款，请稍后...')?></span>
			</div>
		</div>
	</div>


	<script>
		$(function(){
			$(".box0").click();
		});



		$("input[type='checkbox']").prop('checked', false);
		$(".pay_yue").click(function(){
			var pay_type = $(this).val();
			var _self = this;

			if($("input[class='pay_yue']:checked").length > 0)
			{
				$("#pay_password").show();
			}
			else{
				$("#pay_password").hide();
			}

			//用户余额
			var money = $("input[type='hidden'][name='" + pay_type + "']").val();
			//需要在线支付的金额
			var online = $("input[type='hidden'][name='online_pay']").val();
			//用户余额已支付的金额
			var money_pay = $("input[type='hidden'][name='" + pay_type + "_pay']").val();

			if(_self.checked)
			{
				//使用余额之后，需要在线支付的金额
				var online_money = online - money;

				if(online_money < 0)
				{
					online_money = 0;
					$("#"+ pay_type +"_pay").val(online);

					//如果在线支付金额为0，则取消已选择的在线支付方式
					if($(".online_pay>.pay_method_sel").size() > 0)
					{
						$(".pay_method_sel").click();
					}

				}
				else
				{
					$("#"+ pay_type +"_pay").val(money);
				}
				$(".online_money").html(Number(online_money).toFixed(2));
				$("#online_pay").val(online_money);
			}
			else
			{
				var online_money = online*1 + money_pay*1;
				$(".online_money").html(Number(online_money).toFixed(2));
				$("#online_pay").val(online_money);
			}

		});

		function checkPassword()
		{
			$.post(SITE_URL + "?ctl=Info&met=checkPassword&typ=json",{password:$("input[name='password']").val()},
				function(data){
					console.info(data);
					if(data.status == 250)
					{
						$(".msg-box").html(data.msg);

						$("#submit").addClass("submit_disable");
						$("#submit").removeClass("submit_able");
					}
					else if(data.status == 230)
					{
						$(".msg-box").html("<a style='color:red;' href=' " + SITE_URL + "?ctl=Info&met=passwd'>请设置支付密码</a>");

						$("#submit").addClass("submit_disable");
						$("#submit").removeClass("submit_able");
					}
					else
					{
						$(".msg-box").html("");

						//判断在线支付的金额是否为0，如果不为0判断有没有选择在线支付方式
						online_money = $("#online_pay").val();
						if(online_money <= 0 || (online_money > 0 && $(".online_pay>.pay_method_sel").size() > 0 ))
						{
							$("#submit").addClass("submit_able");
							$("#submit").removeClass("submit_disable");
						}

					}
				}
			);
		}

		$(".box-public").click(function(){

			if($(this).hasClass("pay_method_sel"))
			{
				//取消选择后判断在线支付金额是否为0 ，为0才可支付
				$(this).removeClass("pay_method_sel");
				if($("#online_pay").val() > 0 )
				{
					$("#submit").addClass("submit_disable");
					$("#submit").removeClass("submit_able");
				}

				$("input[type='hidden'][name='online_payway']").val("");
			}
			else
			{
				online_money = $("#online_pay").val();
				if(online_money > 0)
				{
					//选择在线支付方式后，判断是否有支付密码，有的话判断支付密码是否正确
					$(".box-public").parent().find(".pay_method_sel").removeClass("pay_method_sel");
					$(this).addClass("pay_method_sel");

					$("input[type='hidden'][name='online_payway']").val($(this).find("input[type='hidden'][name='payway_name']").val());

					if(document.getElementById("pay_password") && !$("#pay_password").is(":hidden"))
					{
						checkPassword();
					}
					else
					{
						$("#submit").addClass("submit_able");
						$("#submit").removeClass("submit_disable");
					}
				}

			}

		});

		$("#submit").click(function(){

			checkPassword();

			paySubmit($(this));

		});

		function paySubmit(e)
		{
			if(e.hasClass("submit_able"))
			{
				$("body").css("overflow", "hidden");
				$("#mask_box").show();

				var uorder_id = '<?=($uorder)?>';
				data = {trade_id:uorder_id}

				var card_payway = $("input[type='checkbox'][value='cards']").is(':checked');
				var money_payway = $("input[type='checkbox'][value='money']").is(':checked');
				var online_payway = $("input[type='hidden'][name='online_payway']").val();


				//将选用的付款方式保存如数据库
				data = {card_payway:card_payway,money_payway:money_payway,online_payway:online_payway,uorder_id:uorder_id};
				$.post(SITE_URL + "?ctl=Info&met=checkPayWay&typ=json" ,data,
					function(data){
						console.info(data);
						if(data.status == 200)
						{
							//如果选择了在线支付方式
							if(online_payway)
							{
								window.location.href = SITE_URL + "?ctl=Pay&met=" + online_payway + "&trade_id=" + uorder_id + "&op=<?=($op)?>";
							}
							else
							{
								$.post(SITE_URL + "?ctl=Pay&met=money&typ=json" ,{trade_id:uorder_id},function(data){
									console.info(data);
									if(data.status == 200)
									{
										window.location.href = data.data.return_app_url + '?ctl=Buyer_Order&met=physical';
									}
								})
							}
						}
						else
						{
							alert("支付失败");
						}
					}
				);
			}
		}

		$("#test").click(function(){
			data = {test:'22'};
			$.post(SITE_URL + "?ctl=Pay&met=test&typ=json" ,data,
				function(data){
					console.info(data);
				}
			);
		});
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>