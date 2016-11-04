/**
 * @author     朱羽婷
 */
$(document).ready(function(){

	$("input[type='checkbox']").prop("checked",false);

	window.get = function (e)
	{
		$(e).parent().parent().parent().find(".sale_detail").show();
	}

	window.showVoucher = function(e)
	{
		$(e).parent().parent().parent().find(".voucher_detail").show();
	}
	/*$(".get").click(function(){
		$(this).parent().parent().parent().find(".sale_detail").show();
	})*/
	$(".bk").click(function(){
		$(this).parent().parent().hide();
	})

	//切换用户收货地址，获取物流运费
	$(".receipt_address li").click(function(){
		$(".receipt_address li").removeClass('add_choose');
		$(this).addClass('add_choose');

		getTransport();
	});

	//返回购物车
	$("#back_cart").click(function (){
		location.href = SITE_URL + "?ctl=Buyer_Cart&met=cart";
	});

	function getTransport()
	{
		//var address = $(".add_choose").find('p').html();
		var city = $(".add_choose").find('#user_address_city_id').val();
		var cart_id =[];//定义一个数组
		$("input[name='cart_id']").each(function(){
			cart_id.push($(this).val());//将值添加到数组中
		});

		$.post(SITE_URL  + '?ctl=Seller_Transport&met=getTransportCost&typ=json',{city:city,cart_id:cart_id},function(data)
			{
				console.info(data);
				if(data && 200 == data.status) {
					$.each(data.data ,function(key,val){
						$(".trancon"+key).html(val.con);
						if(val.cost <= 0)
						{
							$(".trancost"+key).html('免运费');
						}
						else
						{
							$(".trancost"+key).html(val.cost.toFixed(2));
						}


						//计算店铺合计
						$(".sprice"+key).html(($(".price"+key).html()*1 + val.cost*1).toFixed(2));
					})

					//计算订单中金额
					var total = 0;
					var rate_amount = 0;
					$(".dian_total i").each(function(){
						total += $(this).html()*1;
						rate_amount += $(this),find(".dian_total_val").val();
					});
					$(".total").html(total.toFixed(2));
					$(".after_total").html((total - rate_amount).toFixed(2));

				}
			}
		);

	}

	var ww=$(document).height()-560;
	$(window).scroll(function (){
		var top=$(window).scrollTop()+$(window).height();
		if(top>=ww){
			$(".pay_fix").css("position","relative");
		}else{
			$(".pay_fix").css("position","fixed");
		}
	});


	//全选的删除按钮
	$('.delete').click(function(){
		//获取所有选中的商品id
		var chk_value =[];//定义一个数组
		$("input[name='product_id[]']:checked").each(function(){
			chk_value.push($(this).val());//将选中的值添加到数组chk_value中
		})

		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_Cart&met=delCartByCid&typ=json',{id:chk_value},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							//$.dialog.alert('删除成功');
							Public.tips.success('删除成功!');
							window.location.reload(); //刷新当前页
						} else {
							//$.dialog.alert('删除失败');
							Public.tips.error('删除失败!');
						}
					}
				);
			}
		})
	});

	//全选
	$('.checkall').click(function(){
		var _self = this;
		$('.checkitem').each(function(){
			if (!this.disabled){
				$(this).prop('checked', _self.checked);

				if(_self.checked)
				{
					$(this).parent().parent().parent().addClass('item-selected');
				}
				else
				{
					$(this).parent().parent().parent().removeClass('item-selected');
				}
			}
		});
		$('.checkall').prop('checked', this.checked);
		count();
	});

	//勾选店铺
	$('.checkshop').click(function(){
		var _self = this;
		if(_self.checked)
		{
			$(this).parents(".carts_content").find(".checkitem").prop('checked', true);
			$(this).parent().parent().parent().find(".row_line").addClass('item-selected');
		}
		else
		{
			$(this).parents(".carts_content").find(".checkitem").prop('checked', false);
			$(this).parent().parent().parent().find(".row_line").removeClass('item-selected');
		}

		count();
	});

	//单度选择商品
	$('.checkitem').click(function(){
		var _self = this;
		if (!this.disabled){
			$(this).prop('checked', _self.checked);

			if(_self.checked)
			{
				$(this).parent().parent().parent().addClass('item-selected');

				//判断该店铺下的商品是否已全选
				if($(this).parents('.table_list').find(".checkitem").not("input:checked").length == 0)
				{
					$(this).parents(".carts_content").find(".checkshop").prop('checked', true);
				}

				//判断是否所有商品都已选择，如果所有商品都选择了就勾选全选
				if($(".checkitem").not("input:checked").length == 0)
				{
					$('.checkall').prop('checked', true);
				}
			}
			else
			{
				$(this).parent().parent().parent().removeClass('item-selected');

				//判断该店铺下的商品是否已全选
				if($(this).parents('.table_list').find(".checkitem").not("input:checked").length != 0)
				{
					$(this).parents(".carts_content").find(".checkshop").prop('checked', false);
				}

				//判断全选是否勾选，如果勾选就去除
				if($(".checkitem").not("input:checked").length != 0)
				{
					$('.checkall').prop('checked', false);
				}
			}
		}
		count();
	});

	function count()
	{
		var count = 0;
		var num = 0;
		$(".cart-checkbox").find("input[name='product_id[]']:checked").each(function(){
			var value = $(this).val();
			var price = $(this).parent().parent().parent().find(".price_all span").html();
			price = price.replace(/,/g, "")
			price = Number(price);
			count = count + price;
			num ++;
		});
		$(".subtotal_all").html(count.toFixed(2));
		//$(".cart-count em").html(num);
		if(num>0)
		{
			$(".submit-btn").removeClass("submit-btn-disabled");
		}
		else
		{
			$(".submit-btn").addClass("submit-btn-disabled");
		}
	}

	var c=$(".goods_num");
	var e=null;
	c.each(function(){
		var g=$(this).find("a");	  //添加减少按钮
		var h=$(this).find("input#nums");  //当前商品数量
		var o=this;
		var f=h.attr("data-max");  //最大值 - 库存
		var i=1;
		var id=h.attr("data-id");  //购物车id
		h.bind("input propertychange",function(){
			var j=this;
			var k=$(j).val();
			e&&clearTimeout(e);
			e=setTimeout(function(){
				var l=Math.max(Math.min(f,k.replace(/\D/gi,"").replace(/(^0*)/,"")||1),i);
				$(j).val(l);
				edit_num(id,l,o);
				if(l==f){
					g.eq(1).attr("class","no_add");
					if(l==i)
						g.eq(0).attr("class","no_reduce")
					else
						g.eq(0).attr("class","reduce")
				}else{
					if(l<=i){
						g.eq(0).attr("class","no_reduce");
						g.eq(1).attr("class","add")
					}else{
						g.eq(0).attr("class","reduce");
						g.eq(1).attr("class","add")
					}
				}
			},50)
		}).trigger("input propertychange").blur(function(){$(this).trigger("input propertychange")}).keydown(function(l){
			if(l.keyCode==38||l.keyCode==40)
			{
				var j=0;
				l.keyCode==40&&(j=1);g.eq(j).trigger("click")
			}
		});
		g.bind("click",function(l){
			if(!$(this).hasClass("no_reduce")){
				var j=parseInt(h.val(),10)||1;
				if($(this).hasClass("add")&&!$(this).hasClass("no_add")){
					$(this).prev().prev().attr("class","reduce");
					if(f>i&&j>=f){
						$(this).attr("class","no_add")
					}
					else
					{
						j++;
						edit_num(id,j,o);
					}
				}else{
					if($(this).hasClass("reduce")&&!$(this).hasClass("no_reduce")){
						j--;
						edit_num(id,j,o);
						$(this).next().next().attr("class","add");
						j<=i&&$(this).attr("class","no_reduce")
					}
				}
				h.val(j)
			}
		})
	})

	function edit_num(id,num,obj){
		var url = "?ctl=Buyer_Cart&met=editCartNum&typ=json";
		var pars = 'cart_id='+id+'&num='+num;
		$.post(url, pars,showResponse);
		function showResponse(originalRequest)
		{
			if(originalRequest.status == 200 )
			{
				$('.cell' + id + ' span').html((Number(originalRequest.data.price).toFixed(2)));
				count();
			}
		}
	}

	$('.del a').click(function(){
		var e = $(this);
		var data_str = e.attr('data-param');
		eval( "data_str = "+data_str);
		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl='+data_str.ctl+'&met='+data_str.met+'&typ=json',{id:data_str.id},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							//$.dialog.alert('删除成功');
							Public.tips.success('删除成功!');
							e.parents('tr').hide('slow', function() {
								var row_count = $('#table_list').find('.row_line:visible').length;
								if(row_count <= 0)
								{
									$('#list_norecord').show();
								}
							});
							window.location.reload(); //刷新当前页
						} else {
							//$.dialog.alert('删除失败');
							Public.tips.error('删除失败!');
						}
					}
				);
			}
		})
	});

	//付款按钮
	$('.submit-btn').click(function(){
		
		//获取所有选中的商品id
		var chk_value =[];//定义一个数组
		$("input[name='product_id[]']:checked").each(function(){
			chk_value.push($(this).val());//将选中的值添加到数组chk_value中
		})

		if(chk_value != "")
		{
			$('#form').submit();
		}
		
	});

	window.addAddress = function(val)
	{
		if(val.user_address_default == 1)
		{
			def = 'add_choose';

			$(".add_choose").removeClass("add_choose");
		}
		else
		{
			def = '';
		}
		str = '<li class=" ' + def + ' " id="addr'+ val.user_address_id + ' "><div class="editbox"><a onclick="edit_address( ' + val.user_address_id + ' )">编辑</a> <a onclick="del_address( ' + val.user_address_id + ' )">删除</a></div><h5> ' + val.user_address_contact +' </h5><p> ' + val.user_address_area + ' ' + val.user_address_address +' </p><div><span class="phone"><i class="iconfont">&#xe64c;</i><span> ' + val.user_address_phone +' </span></span></div></li>';

		$("#address_list").append(str);

	}

	window.editAddress = function(val)
	{
		area = val.user_address_area + ' ' + val.user_address_address;
		$("#addr"+val.user_address_id).find("h5").html(val.user_address_contact);
		$("#addr"+val.user_address_id).find("p").html(area);
		$("#addr"+val.user_address_id).find("phone").find("span").html(val.user_address_phone);

	}

	window.addInvoice = function(state,title,con,id)
	{
		str = state + ' ' + title + ' ' + con;
		$(".mr10").html(str);
		$("input[name='invoice_id']").val(id);
	}

	//删除收货地址
	window.del_address = function(e)
	{
		$.dialog({
			title: '删除',
			content: '您确定要删除吗？',
			height: 100,
			width: 410,
			lock: true,
			drag: false,
			ok: function () {
				$.post(SITE_URL  + '?ctl=Buyer_User&met=delAddress&typ=json',{id:e},function(data)
					{
						console.info(data);
						if(data && 200 == data.status) {
							Public.tips.success('删除成功!');
							//$.dialog.alert('删除成功');
							$("#addr"+e).hide('slow');
						} else {
							Public.tips.error('删除失败!');
							//$.dialog.alert('删除失败');
						}
					}
				);
			}
		})
	}

	//编辑收货地址
	window.edit_addressl = function(e)
	{
		layer.open({
			type:2,
			title:"修改地址",
			maxmin:true,
			shadeClose:false,
			area:["580px","340px"],
			content:SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+e
		});
	}

	window.edit_address = function (e)
	{
		url = SITE_URL + "?ctl=Buyer_Cart&met=resetAddress&id="+e;

		$.dialog({
			title: '修改地址',
			content: 'url: ' + url ,
			height: 340,
			width: 580,
			lock: true,
			drag: false,

		})
	}

	//去付款按钮（生成订单）
	$("#pay_btn").click(function(){
		if($(".total").html() >= 99999999.99 )
		{
			Public.tips.error('订单金额过大，请分批购买！');
			return false;
		}

		//1.获取收货地址
			address_contact = $(".add_choose").find("h5").html();
			address_address = $(".add_choose").find("p").html();
			address_phone   = $(".add_choose").find(".phone").find("span").html();

		if(!address_contact)
		{
			Public.tips.error('请填写收货地址！');
			return false;
		}

		//2.获取发票信息
			invoice = $(".invoice-cont").find(".mr10").html();
			invoice_id = $("input[name='invoice_id']").val();
		//3.获取商品信息（商品id，商品备注）
			var cart_id =[];//定义一个数组
			$("input[name='cart_id']").each(function(){
				cart_id.push($(this).val());//将值添加到数组中
			});

			var remark = [];
			var shop_id = [];
			$("input[name='remarks']").each(function(){
				shop_id.push($(this).attr("id"));
				remark.push($(this).val());//将值添加到数组中
			});

		//加价购的商品
		var increase_goods_id = [];
		$(".increase_list").each(function(){
			if($(this).is('.checked'))
			{
				increase_goods_id.push($(this).find("#redemp_goods_id").val());
			}
		})

		//代金券信息
		var voucher_id = [];
		$(".voucher_list").each(function(){
			if($(this).is(".checked"))
			{
				voucher_id.push($(this).find("#voucher_id").val());
			}
		})

		//获取支付方式
		pay_way_id = $(".pay-selected").attr('pay_id');

		$("body").css("overflow", "hidden");
		$("#mask_box").show();

		$.ajax({
			url: SITE_URL  + '?ctl=Buyer_Order&met=addOrder&typ=json',
			data:{receiver_name:address_contact,receiver_address:address_address,receiver_phone:address_phone,invoice:invoice,invoice_id:invoice_id,cart_id:cart_id,shop_id:shop_id,remark:remark,increase_goods_id:increase_goods_id,voucher_id:voucher_id,pay_way_id:pay_way_id},
			dataType: "json",
			contentType: "application/json;charset=utf-8",
			async:false,
			success:function(a){
				console.info(a);
				if(a.status == 200)
				{

					if(pay_way_id == 1)
					{
						window.location.href = PAYCENTER_URL + "?ctl=Info&met=pay&uorder=" + a.data.uorder +"&from_app_id=" + app_id;
						return false;
					}
					else
					{
						window.location.href = SITE_URL + '?ctl=Buyer_Order&met=physical';
						return false;
					}
				}
				else
				{
					if(a.msg != 'failure')
					{
						Public.tips.error(a.msg);
					}else
					{
						Public.tips.error('订单提交失败！');
					}

					//alert('订单提交失败');
				}
			},
			failure:function(a)
			{
				Public.tips.error('操作失败！');
				//$.dialog.alert("操作失败！");
			}
		});

	});

	window.jiabuy = function(e)
	{
		limit = $(e).parents('.increase').find('#exc_goods_limit').val();
		shop_id = $(e).parents('.increase').find('#shop_id').val();

		if($(e).is('.checked'))
		{
			$(e).removeClass('checked');
			$(e).parents('.increase_list').removeClass('checked');

			good_price = $(e).parents('.increase_list').find(".redemp_price").val();
			good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();

			//商品减价
			goods_price = Number(Number($('.price' + shop_id).html())*1-good_price*1).toFixed(2);
			$('.price' + shop_id).html(goods_price);

			//店铺减价
			shop_price = Number(Number($('.sprice' + shop_id).html())*1-good_price*1).toFixed(2);
			$('.sprice' + shop_id).html(shop_price);

			//总价减价
			total_price = Number(Number($('.total').html())*1-good_price*1).toFixed(2);
			total_rate = Number(Number($('.rate_total').html) - good_price_rate*1).toFixed(2);
			$('.total').html(total_price);
			$('.rate_total').html(total_rate);
			$(".after_total").html((total_price - total_rate).toFixed(2));
		}
		else
		{
			//计算已经选择了加价购商品
			num = $(e).parents('.increase').find('.checked').length;
			if(limit <= 0 || (limit > 0 && num < limit))
			{
				$(e).addClass('checked');
				$(e).parents('.increase_list').addClass('checked');

				good_price = $(e).parents('.increase_list').find(".redemp_price").val();
				good_price_rate = $(e).parents('.increase_list').find(".redemp_price_rate").val();

				//商品加价
				goods_price = Number(Number($('.price' + shop_id).html())*1+good_price*1).toFixed(2);
				$('.price' + shop_id).html(goods_price);

				//店铺加价
				shop_price = Number(Number($('.sprice' + shop_id).html())*1+good_price*1).toFixed(2);
				$('.sprice' + shop_id).html(shop_price);

				//总价加价
				total_price = Number(Number($('.total').html())*1+good_price*1).toFixed(2);
				total_rate = Number(Number($('.rate_total').html) + good_price_rate*1).toFixed(2);
				$('.total').html(total_price);
				$('.rate_total').html(total_rate);
				$(".after_total").html((total_price + total_rate).toFixed(2));


			}


		}


	}

	window.useVoucher = function (e)
	{
		shop_id = $(e).parent().find('#shop_id').val();

		//获取本代金券的价值
		voucher_price = $(e).parent().find("#voucher_price").val();

		if($(e).is('.checked'))
		{
			$(e).removeClass("checked");
			$(e).removeClass("bgred");
			$(e).parents('.voucher_list').removeClass('checked');

			//删除代金券信息
			$(".shop_voucher"+shop_id).html("");

			//店铺加价
			shop_price = Number(Number($('.sprice' + shop_id).html())*1+voucher_price*1).toFixed(2);
			$('.sprice' + shop_id).html(shop_price);

			//总价加价
			total_price = Number(Number($('.total').html())*1+voucher_price*1).toFixed(2);
			rate_total = Number($('.rate_total').val());
			$('.total').html(total_price);
			$(".after_total").html((total_price - rate_total).toFixed(2));

		}else
		{
			$(e).parents(".voucher").find(".checked").removeClass("checked");
			$(e).parents(".voucher").find(".bgred").removeClass("bgred");
			$(e).addClass("checked");
			$(e).addClass("bgred");
			$(e).parents('.voucher_list').addClass('checked');

			//显示代金券信息
			$(".shop_voucher"+shop_id).html("使用" + voucher_price + "代金券");

			//店铺减价
			shop_price = Number(Number($('.sprice' + shop_id).html())*1-voucher_price*1).toFixed(2);
			$('.sprice' + shop_id).html(shop_price);

			//总价减价
			total_price = Number(Number($('.total').html())*1-voucher_price*1).toFixed(2);
			rate_total = Number($('.rate_total').val());
			$('.total').html(total_price);
			$(".after_total").html((total_price - rate_total).toFixed(2));
		}
	}


	//选择支付方式
	$(".pay_way").click(function(){
			$(this).parent().find(".pay-selected").removeClass("pay-selected");
			$(this).addClass("pay-selected");
	 })

})
