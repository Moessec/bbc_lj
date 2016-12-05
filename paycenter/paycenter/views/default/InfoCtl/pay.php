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

			
			<!--  最后在线支付需要支付的金额  -->
			<input type="hidden" name="online_pay" id="online_pay" value="<?=($uorder_base['trade_payment_amount'])?>">

			<div class="online_pay">
				<p class="online_title"><?=_('选择在线支付')?> </p>
				<?php foreach($payment_channel as $key => $val){?>
					<div class="box-public box<?=($key)?>" style="width:25%;height: 43px;padding-left:3px" >
						<div class="mallbox-public" >
							<img src="<?=($val['payment_channel_image'])?>" alt="<?=($val['payment_channel_name'])?>" width="50%" />
							<input type="hidden" name="payway_name" class="payway_name" value="<?=($val['payment_channel_code'])?>">
						</div>
						<span class="sel_icon"></span>
					</div>
				<?php }?>
				<input type="hidden" name="online_payway" class="online_payway" id="online_payway">
				
			</div>
		</div>
		<div class="recharge2-content-center content-public wrap">
			<div class="pc_trans_btn"><a id="submit" class="btn_big btn_active submit_disable" style="width:100%"><?=_('确认付款')?></a></div>
			<!--<div id="test">TEst</div>-->
		</div>
		
	</form>
<?php }else{ ?>
	<?=_('订单号无效，请确认后再付款。')?>
<?php } ?>

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