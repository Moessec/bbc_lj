var key = getCookie('key');
// buy_stop2使用变量
var ifcart = getQueryString('ifcart');
if(ifcart==1){
    var cart_id = getQueryString('cart_id');
    cart_id = cart_id.split(',');
}else{
    var cart_id = getQueryString("goods_id")+'|'+getQueryString("buynum");
}
var pay_name = 'online';
var invoice_id = 0;
var address_id,vat_hash,offpay_hash,offpay_hash_batch,voucher,pd_pay,password,fcode='',rcb_pay,rpt,payment_code;
var message = {};
// change_address 使用变量
var freight_hash,city_id,area_id,province_id
// 其他变量
var area_info;
var goods_id;


function isEmptyObject(e) {
    var t;
    for (t in e)
        return !1;
    return !0
}


$(function() {
    // 地址列表
    $('#list-address-valve').click(function(){
        var address_id = $(this).find("#address_id").val();
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?ctl=Buyer_Cart&met=confirm&typ=json",
            data:{k:key, u:getCookie('id'),product_id:cart_id},
            dataType:'json',
            async:false,
            success:function(result){
                checkLogin(result.login);
                if(result.data.address==null){
                    return false;
                }
                
                var data = result.data;
                console.info(data);
                data.address_id = address_id;
                var html = template.render('list-address-add-list-script', data);
                $("#list-address-add-list-ul").html(html);
               
            }
        });
    });
 

      
    
    $.animationLeft({
        valve : '#list-address-valve',
        wrapper : '#list-address-wrapper',
        scroll : '#list-address-scroll'
    });

    // 地区选择
    $('#list-address-add-list-ul').on('click', 'li', function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        eval('address_info = ' + $(this).attr('data-param'));
        _init(address_info.user_address_id);
        //console.info(address_info);
        $('#true_name').html(address_info.user_address_contact);
        $('#mob_phone').html(address_info.user_address_phone);
        $('#address').html(address_info.user_address_area + address_info.user_address_address);
        $("#address_id").val(address_info.user_address_id);
        $('#list-address-wrapper').find('.header-l > a').click();
    });











    
  
    //
    //
    //
    //
    // 地址新增
    $.animationLeft({
        valve : '#new-address-valve',
        wrapper : '#new-address-wrapper',
        scroll : ''
    });
    // 支付方式
    $.animationLeft({
        valve : '#select-payment-valve',
        wrapper : '#select-payment-wrapper',
        scroll : ''
    });
       $.animationLeft({
        valve : '#select-ps-valve',
        wrapper : '#select-ps-wrapper',
        scroll : ''
    });
    // 地区选择
    $('#new-address-wrapper').on('click', '#varea_info', function(){

        $.areaSelected({
            success : function(data){
                //console.info(data);
                province_id = data.area_id_1;
                city_id = data.area_id_2;
                area_id = data.area_id_3;
                area_info = data.area_info;
                $('#varea_info').val(data.area_info);
            }
        });
    });

    //增值税发票中的地区选择
    $('#invoice-list').on('click', '#invoice_area_info', function(){
        $.areaSelected({
            success: function (a)
            {
                $("#invoice_area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
            }
        });
    });

    // 发票
    $.animationLeft({
        valve : '#invoice-valve',
        wrapper : '#invoice-wrapper',
        scroll : ''
    });


    template.helper('isEmpty', function(o) {
        var b = true;
        $.each(o, function(k, v) {
            b = false;
            return false;
        });
        return b;
    });

    template.helper('pf', function(o) {
        return parseFloat(o) || 0;
    });

    template.helper('p2f', function(o) {
        return (parseFloat(o) || 0).toFixed(2);
    });

    var _init = function (address_id) {
        var totals = 0;
        var gptotl = 0;
        var cptotal = 0;
        var vototal = 0;
        // 购买第一步 提交

        $.ajax({//提交订单信息

            type:'post',
            url:ApiUrl+'/index.php?ctl=Buyer_Cart&met=confirm&typ=json',
            dataType:'json',
            data:{k:key, u:getCookie('id'),product_id:cart_id,ifcart:ifcart,address_id:address_id},
            success:function(result){
               
                checkLogin(result.login);
                if (result.status == 250) {
                    $.sDialog({
                        skin:"red",
                        content:result.data.msg,
                        okBtn:false,
                        cancelBtn:false
                    });
                    return false;
                }
                // 商品数据
                result.data.address_id = address_id;
                console.info(result.data);
                result.data.WapSiteUrl = WapSiteUrl;
                delete result.data.glist.count

                var html = template.render('goods_list', result.data);
                $("#deposit").html(html);
                


                for (var i=0; i<result.data.glist.length; i++) {
                    $.animationUp({
                        valve : '.animation-up' + i,          // 动作触发，为空直接触发
                        wrapper : '.nctouch-bottom-mask' + i,    // 动作块
                        scroll : '.nctouch-bottom-mask-rolling' + i,     // 滚动块，为空不触发滚动
                    });
                }



                /*if (fcode == '') {
                    // F码商品
                    for (var k in result.datas.store_cart_list) {
                        if (result.datas.store_cart_list[k].goods_list[0].is_fcode == '1') {
                            $('#container-fcode').removeClass('hide');
                            goods_id = result.datas.store_cart_list[k].goods_list[0].goods_id;
                        }
                        break;
                    }
                }
                // 验证F码
                $('#container-fcode').find('.submit').click(function(){
                    fcode = $('#fcode').val();
                    if (fcode == '') {
                        $.sDialog({
                            skin:"red",
                            content:'请填写F码',
                            okBtn:false,
                            cancelBtn:false
                        });
                        return false;
                    }
                    $.ajax({//提交订单信息
                        type:'post',
                        url:ApiUrl+'/index.php?act=member_buy&op=check_fcode',
                        dataType:'json',
                        data:{key:key,goods_id:goods_id,fcode:fcode},
                        success:function(result){
                            if (result.datas.error) {
                                $.sDialog({
                                    skin:"red",
                                    content:result.datas.error,
                                    okBtn:false,
                                    cancelBtn:false
                                });
                                return false;
                            }

                            $.sDialog({
                                autoTime:'500',
                                skin:"green",
                                content:'验证成功',
                                okBtn:false,
                                cancelBtn:false
                            });
                            $('#container-fcode').addClass('hide');
                        }
                    });
                });*/

                // 默认地区相关
                if ($.isEmptyObject(result.data.address)) {
                    $.sDialog({
                        skin:"block",
                        content:'请添加地址',
                        okFn: function() {
                            $('#new-address-valve').click();
                        },
                        cancelFn: function() {
                            history.go(-1);
                        }
                    });
                    return false;
                }
                /*if (typeof(result.datas.inv_info.inv_id) != 'undefined') {
                    invoice_id = result.datas.inv_info.inv_id;
                }
                // 发票
                $('#invContent').html(result.datas.inv_info.content);
                vat_hash = result.datas.vat_hash;

                freight_hash = result.datas.freight_hash;*/
                // 输入地址数据

                //console.info(result.data.address);

                insertHtmlAddress(result.data.address, address_id);

                // 代金券
                voucher = '';
                voucher_temp = [];
                for (var k in result.data.glist.voucher_base) {
                    voucher_temp.push([result.data.glist.voucher_base[k].voucher_t_id + '|' + k + '|' + result.data.glist.voucher_base[k].voucher_price]);
                }
                voucher = voucher_temp.join(',');
                
                for (var k in result.data.glist) {
                    var voucher_price = 0;
                    var voucher_id = 0;
                    if(result.data.glist[k].voucher_base)
                    {
                        for (var kv in result.data.glist[k].voucher_base) {
                            if(result.data.glist[k].voucher_base[kv].voucher_price > voucher_price)
                            {
                                voucher_price = result.data.glist[k].voucher_base[kv].voucher_price;
                                voucher_id = result.data.glist[k].voucher_base[kv].voucher_id;
                            }
                        }
                    }

                    // 总价
                    allprice = result.data.glist[k].sprice + result.data.cost[k].cost - voucher_price;
                    if(allprice < 0)
                    {
                        allprice = 0
                    }


                    if(voucher_price > 0)
                    {
                        $('#vourchPrice' + k).html(voucher_price+'元');
                        $('#voucher' + k).show();
                        $('#vourch_id' + k).val(voucher_id);
                    }

                    $('#storeTotal' + k).html(allprice.toFixed(2));
                    $('#storeFreight' + k).html(result.data.cost[k].cost.toFixed(2));

                    totals += parseFloat(result.data.glist[k].sprice + result.data.cost[k].cost - voucher_price);
                    gptotl +=parseFloat(result.data.glist[k].sprice);
                    cptotal +=parseFloat(result.data.cost[k].cost);
                    vototal += voucher_price;
                    // 留言
                    message[k] = '';
                    $('#storeMessage' + k).on('change', function(){
                        message[k] = $(this).val();
                    });
                }

                // 红包
                // rcb_pay = 0;
                // rpt = '';
                // var rptPrice = 0;
                // if (!$.isEmptyObject(result.datas.rpt_info)) {
                //     $('#rptVessel').show();
                //     var rpt_info = ((parseFloat(result.datas.rpt_info.rpacket_limit) > 0) ? '满' + parseFloat(result.datas.rpt_info.rpacket_limit).toFixed(2) + '元，': '') + '优惠' + parseFloat(result.datas.rpt_info.rpacket_price).toFixed(2) + '元'
                //     $('#rptInfo').html(rpt_info);
                //     rcb_pay = 1;
                // } else {
                //     $('#rptVessel').hide();
                // }



                password = '';

                // $('#useRPT').click(function(){
                //     if ($(this).prop('checked')) {
                //         rpt = result.datas.rpt_info.rpacket_t_id+ '|' +parseFloat(result.datas.rpt_info.rpacket_price);
                //         rptPrice = parseFloat(result.datas.rpt_info.rpacket_price);
                //         var total_price = totals - rptPrice;
                //     } else {
                //         rpt = '';
                //         var total_price = totals;
                //     }
                //     if (total_price <= 0) {
                //         total_price = 0;
                //     }
                //     $('#totalPrice,#onlineTotal').html(total_price.toFixed(2));
                // });

                // 计算总价
                var total_price = totals;
                if (total_price <= 0) {
                    total_price = 0;
                }
                if(cptotal <= 0)
                {
                    cptotal = 0;
                }
                if(gptotl <= 0)
                {
                    gptotl = 0;
                }
                $('#totalPrice,#onlineTotal').html(total_price.toFixed(2));
                var total_rate_price = cptotal + ((gptotl * result.data.user_rate)/100) - vototal;
                if(total_rate_price <= 0)
                {
                    total_rate_price = 0
                }
                $('#totalPayPrice').html(total_rate_price.toFixed(2));

                if(result.data.user_rate != 100)
                {
                    var rate_price = gptotl * (100 - result.data.user_rate)/100;
                    $("#ratePrice").html(rate_price.toFixed(2));

                    $(".rate-money").show();
                }

            }
        });
    }

    rcb_pay = 0;
    pd_pay = 0;
    // 初始化
    _init();

    // 插入地址数据到html
    var insertHtmlAddress = function (address, address_id) {

        console.info(address);
        var address_info = {};

        for ( var i=0; i<address.length; i++ ) {

            if(address_id != undefined )
            {
                if ( address[i].user_address_id == address_id ) {
                    //address_info.address_id = address[i].user_address_area_id;
                    address_info.address_id = address[i].user_address_id;
                    address_info.user_address_contact = address[i].user_address_contact;
                    address_info.provice_id = address[i].user_address_provice_id;
                    address_info.city_id = address[i].user_address_city_id;
                    address_info.area_id = address[i].user_address_area_id;
                    address_info.user_address_phone = address[i].user_address_phone;
                    address_info.user_address_area = address[i].user_address_area;
                    address_info.user_address_address = address[i].user_address_address;
                }
            }
            else
            {
                if (address[i].user_address_default) {
                    //address_info.address_id = address[i].user_address_area_id;
                    address_info.address_id = address[i].user_address_id;
                    address_info.user_address_contact = address[i].user_address_contact;
                    address_info.provice_id = address[i].user_address_provice_id;
                    address_info.city_id = address[i].user_address_city_id;
                    address_info.area_id = address[i].user_address_area_id;
                    address_info.user_address_phone = address[i].user_address_phone;
                    address_info.user_address_area = address[i].user_address_area;
                    address_info.user_address_address = address[i].user_address_address;

                }
            }

        }

        if(!isEmptyObject(address_info))
        {
            address_id = address_info.address_id;
            $('#true_name').html(address_info.user_address_contact);
            $('#mob_phone').html(address_info.user_address_phone);
            $('#address').html(address_info.user_address_area + address_info.user_address_address);
        }
        else
        {
            $('#address').html('未选择收货地址');
        }

        $("#address_id").val(address_id);
        area_id = address_info.area_id;
        city_id = address_info.city_id;
        province_id = address_info.provice_id;

        /*if (address_api.content) {
            for (var k in address_api.content) {
                $('#storeFreight' + k).html(parseFloat(address_api.content[k]).toFixed(2));
            }
        }
        offpay_hash = address_api.offpay_hash;
        offpay_hash_batch = address_api.offpay_hash_batch;
        if (address_api.allow_offpay == 1) {
            $('#payment-offline').show();
        }
        if (!$.isEmptyObject(address_api.no_send_tpl_ids)) {
            $('#ToBuyStep2').parent().removeClass('ok');
            for (var i=0; i<address_api.no_send_tpl_ids.length; i++) {
                $('.transportId' + address_api.no_send_tpl_ids[i]).show();
            }
        } else {

        }*/
        $('#ToBuyStep2').parent().addClass('ok');
    }

    // 支付方式选择
    // 在线支付
    $('#payment-online').click(function(){
        pay_name = 'online';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('在线支付');
        $("#pay-selected").val('1');
        $(this).addClass('sel').siblings().removeClass('sel');
    })
    // 货到付款
    $('#payment-offline').click(function(){
        pay_name = 'offline';
        $('#select-payment-wrapper').find('.header-l > a').click();
        $('#select-payment-valve').find('.current-con').html('货到付款');
        $("#pay-selected").val('2');
        $(this).addClass('sel').siblings().removeClass('sel');
    })


    //PS···························
      $('#ps-online').click(function(){
        ps_name = 'online';
        $('#select-ps-wrapper').find('.header-l > a').click();
        $('#select-ps-valve').find('.current-con').html('配送到家');
        $("#ps-selected").val('1');
        $(this).addClass('sel').siblings().removeClass('sel');
    })
    // 上门自提
    $('#ps-offline').click(function(){
       ps_name = 'offline';
        $('#select-ps-wrapper').find('.header-l > a').click();
        $('#select-ps-valve').find('.current-con').html('上门自提');
        // $("em[name='trans_cost']").html('0');
        $("#ps-selected").val('2');
         ps_type=$("#ps-selected").val();
          $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?ctl=Buyer_Cart&met=confirm&typ=json",

            data:{k:key, u:getCookie('id'),product_id:cart_id,ps_type:ps_type},
            dataType:'json',
            async:false,
            success:function(result){
                checkLogin(result.login);
                
                if(result.data.address==null){
                    return false;
                }
               
                var data = result.data;
                data.address_id = address_id;
                var html = template.render('list-address-add-list-script', data);
                $("#list-address-add-list-ul").html(html);
            }
        });
        $("#ps-selected").val('2');
        $(this).addClass('sel').siblings().removeClass('sel');
    })


    // 地址保存
    $.sValid.init({
        rules:{
            vtrue_name:"required",
            vmob_phone:"required",
            varea_info:"required",
            vaddress:"required"
        },
        messages:{
            vtrue_name:"姓名必填！",
            vmob_phone:"手机号必填！",
            varea_info:"地区必填！",
            vaddress:"街道必填！"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                errorTipsShow(errorHtml);
            }else{
                errorTipsHide();
            }
        }
    });
    $('#add_address_form').find('.btn').click(function(){
        if($.sValid()){
            var param = {};
            param.k = key;
            param.user_address_contact = $('#vtrue_name').val();
            param.user_address_phone = $('#vmob_phone').val();
            param.user_address_address = $('#vaddress').val();
            param.address_area = $('#varea_info').val();
            param.province_id = province_id;
            param.city_id = city_id;
            param.area_id = area_id;

            param.user_address_default = 0;

            param.u = getCookie('id');

            $.ajax({
                type:'post',
                url:ApiUrl+"/index.php?ctl=Buyer_User&met=addAddressInfo&typ=json",
                data:param,
                dataType:'json',
                success:function(result){
                    //console.info(result);
                    if (result.status == 200) {
                        //param.address_id = result.data.address_id;
                        _init(result.data.user_address_id);
                        $('#true_name').html(result.data.user_address_contact);
                        $('#mob_phone').html(result.data.user_address_phone);
                        $('#address').html(result.data.user_address_area + result.data.user_address_address);
                        $("#address_id").val(result.data.user_address_id);
                        $('#new-address-wrapper,#list-address-wrapper').find('.header-l > a').click();
                    }
                }
            });
        }
    });
    // 发票选择
    $('#invoice-noneed').click(function(){
        $(this).addClass('sel').siblings().removeClass('sel');
        $('#invoice_add,#invoice-list').hide();
        invoice_id = 0;
    });
    $('#invoice-need').click(function(){
        $(this).addClass('sel').siblings().removeClass('sel');
        $('#invoice_add').show();

        /*$.ajax({//获取发票内容(下拉框内容)
            type:'post',
            url:ApiUrl+'/index.php?ctl=Buyer_Cart&met=piao',
            data:{key:key},
            dataType:'json',
            success:function(result){
                checkLogin(result.login);
                var data = result.datas;
                var html = '';
                $.each(data.invoice_content_list,function(k,v){
                    html+='<option value="'+v+'">'+v+'</option>';
                });
                $('#inc_content').append(html);
            }
        });*/
        html = '<option value="明细">明细</option><option value="办公用品">办公用品</option><option value="电脑配件">电脑配件</option><option value="耗材">耗材</option>';
        $('#inc_content').append(html);
        //获取发票列表
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?ctl=Buyer_Cart&met=piao&typ=json',
            data:{k:key, u:getCookie('id')},
            dataType:'json',
            success:function(result){
                checkLogin(result.login);
                //console.info(result);
                //console.info(result.data);
                var html = template.render('invoice-list-script', result.data);
                $('#invoice-list').html(html)
                if (result.data.normal.length > 0) {
                    invoice_id = result.data.normal[0].invoice_id;
                }
            }
        });
    })
    // 发票类型选择
    $('input[name="inv_title_select"]').click(function(){
        //增值税发票
        if ($(this).val() == 'increment') {
            $('#invoice-list>#addtax').show();
            $('#invoice-list>#electron').hide();
            $('#invoice-list>#normal').hide();

        } //电子发票
        else if($(this).val() == 'electronics') {
            $('#invoice-list>#electron').show();
            $('#invoice-list>#normal').hide();
            $('#invoice-list>#addtax').hide();
        }//普通发票
        else
        {
            $('#invoice-list>#normal').show();
            $('#invoice-list>#electron').hide();
            $('#invoice-list>#addtax').hide();
        }
    });
    $('#invoice-div').on('click', '#invoiceNew', function(){
        invoice_id = 0;
        $('#invoice_normal_add').show();
    });
    $('#invoice-list').on('click', 'label', function(){
        invoice_id = $(this).find('input').val();
    });

    var add_invoice = function(e)
    {
        var result = "";
        $.ajax({
            type:'post',
            url: ApiUrl+"?ctl=Buyer_Invoice&met=addInvoice&typ=json",
            data:e,
            dataType: "json",
            async:false,
            success:function(a){
                result = a;
            }
        });
        return result;
    }
    // 发票添加
    $('#invoice-div').find('.btn-l').click(function(){
        //选择需要发表按钮
        if ($('#invoice-need').hasClass('sel')) {
            //判断选择的发票类型
            invoice_type = $('#invoice_type').find(".checked").find("input[name='inv_title_select']").attr('id');
            //普通发票
            if(invoice_type == 'norm')
            {
                //判断有没有新增的发票抬头
                invoice_state = 1;
                type = "普通发票";
                if($('#invoiceNew').hasClass('checked'))
                {
                    title = $("#invoice_normal_add").find("input[name='inv_normal_add_title']").val();
                    cont  = $("#invoice_normal_add").find("#inv_normal_add_content").val();

                    var data = {invoice_state:invoice_state,
                                invoice_title:title,
                                k:key, u:getCookie('id')};

                    flag = add_invoice(data);
                }
                else
                {
                    title = $("#normal").find("#inc_normal_title").val();
                    cont = $("#normal").find("#inc_normal_content").val();
                    flag = {status:200,data:{invoice_id:''}}
                }
            }

            //电子发票
            if(invoice_type == 'electronics')
            {
                //将电子发票保存到数据库
                type  = '电子发票';
                title = $("#electron").find('.checked').find("input[name='inv_ele_title']").val();
                phone = $("#electron").find("input[name='inv_ele_phone']").val();
                email = $("#electron").find("input[name='inv_ele_email']").val();
                cont  = $("#electron").find("#inc_content").val();
                var data = {invoice_state:'2',
                    invoice_title:title,
                    invoice_rec_phone:phone,
                    invoice_rec_email:email,
                    k:key, u:getCookie('id')};

                flag = add_invoice(data);
            }

            //增值税发票
            if(invoice_type == 'increment')
            {
                //将增值税发票保存到数库中
                type = '增值税发票';
                title = $("#addtax").find("input[name='inv_tax_title']").val();
                company = $("#addtax").find("input[name='inv_tax_title']").val();
                code	= $("#addtax").find("input[name='inv_tax_code']").val();
                addr = $("#addtax").find("input[name='inv_tax_address']").val();
                phone = $("#addtax").find("input[name='inv_tax_phone']").val();;
                bname = $("#addtax").find("input[name='inv_tax_bank']").val();
                bcount = $("#addtax").find("input[name='inv_tax_bankaccount']").val();
                cname = $("#addtax").find("input[name='inv_tax_recname']").val();
                cphone = $("#addtax").find("input[name='inv_tax_recphone']").val();
                province = $("#addtax").find("input[name='invoice_tax_rec_province']").val();
                caddr = $("#addtax").find("input[name='inv_tax_rec_addr']").val();

                province_id = $("#addtax").find("input[name='invoice_tax_rec_province']").attr('data-areaid1');
                city_id = $("#addtax").find("input[name='invoice_tax_rec_province']").attr('data-areaid2');
                area_id = $("#addtax").find("input[name='invoice_tax_rec_province']").attr('data-areaid3');


                cont = $("#addtax").find("#inc_tax_content").val();
                var data = {invoice_state:'3',
                    invoice_title:title,
                    invoice_company:company,
                    invoice_code:code,
                    invoice_reg_addr:addr,
                    invoice_reg_phone:phone,
                    invoice_reg_bname:bname,
                    invoice_reg_baccount:bcount,
                    invoice_rec_name:cname,
                    invoice_rec_phone:cphone,
                    invoice_rec_province:province,
                    invoice_province_id:province_id,
                    invoice_city_id:city_id,
                    invoice_area_id:area_id,
                    invoice_goto_addr:caddr,
                    k:key, u:getCookie('id')};

                //console.info(data);

                flag = add_invoice(data);
            }

            if(flag.status == 200)
            {
                $('#invContent').html(type + ' ' + title + ' ' + cont);
                $("input[name='invoice_id']").val(flag.data.invoice_id);
            }
            else
            {
                $.sDialog({
                    content: '操作失败',
                    okBtn:false,
                    cancelBtnText:'返回',
                    cancelFn: function() { }
                });
            }
        } else {
            $('#invContent').html('不需要发票');
        }
        $('#invoice-wrapper').find('.header-l > a').click();
    });


    // 支付
    $('#ToBuyStep2').click(function(){

        if($("#totalPayPrice").html() >= 99999999.99)
        {
            $.sDialog({
                content: '订单金额过大，请分批购买！',
                okBtn:false,
                cancelBtnText:'返回',
                cancelFn: function() { history.back(); }
            });
        }

        //1.获取收货地址
        address_contact = $("#true_name").html();
        address_address = $("#address").html();
        address_phone   = $("#mob_phone").html();
        address_id = $("#address_id").val();

        if(address_id == 'undefined')
        {
            $.sDialog({
                skin:"red",
                content:'请选择收货地址！',
                okBtn:false,
                cancelBtn:false
            });

            return false;
        }
        //获取配送方式信息
        ps_type=[];
        $("input[class='ps_type']:checked").each(function(){
            ps_type.push($(this).val());//将值添加到数组中
        });
       
       
        // for(var i = 0 ;i<ps_type.length;i++)
        //  {
        //              if(ps_type[i] == "" || typeof(ps_type[i]) == "undefined")
        //              {
        //                       ps_type.splice(i,1);
        //                       i= i-1;
                          
        //              }
                      
        //  }
      
        //2.获取发票信息
        invoice = $("#invContent").html();
        invoice_id = $("input[name='invoice_id']").val();

        //3.获取商品信息（商品id，商品备注）
        var cart_id =[];//定义一个数组
        $("input[name='cart_id']").each(function(){
            cart_id.push($(this).val());//将值添加到数组中
        });

        var remark = [];
        var shop_id = [];
        $("input[name='remarks']").each(function(){
            shop_id.push($(this).attr("rel"));
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
            /*if($(this).is(".checked"))
            {
                voucher_id.push($(this).find("#voucher_id").val());
            }*/

            if($(this).val() > 0)
            {
                voucher_id.push($(this).val());
            }
        })

        

        //获取支付方式
        pay_way_id = $("#pay-selected").val();
        

        $.ajax({
            type:'post',
            url: ApiUrl  + '?ctl=Buyer_Order&met=addOrder&typ=json',
            data:{receiver_name:address_contact,receiver_address:address_address,receiver_phone:address_phone,invoice:invoice,invoice_id:invoice_id,cart_id:cart_id,shop_id:shop_id,remark:remark,increase_goods_id:increase_goods_id,voucher_id:voucher_id,pay_way_id:pay_way_id,ps_type:ps_type,address_id:address_id,k:key, u:getCookie('id')},
            dataType: "json",
            success:function(a){
                console.info(a);
                if(a.status == 200)
                {
                    delCookie('cart_count');
                    //重新计算购物车的数量
                    getCartCount();
                    //alert(PayCenterWapUrl + "?ctl=Info&met=pay&uorder=" + a.data.uorder);
                    if(pay_way_id == 1)
                    {
                        window.location.href = PayCenterWapUrl + "?ctl=Info&met=pay&uorder=" + a.data.uorder;
                        return false;
                    }
                    else
                    {
                        window.location.href = WapSiteUrl + '/tmpl/member/order_list.html';
                        return false;
                    }

                }
                else
                {
                    if(a.msg != 'failure')
                    {
                       /* Public.tips.error(a.msg);*/
                        $.sDialog({
                            content: a.msg,
                            okBtn:false,
                            cancelBtnText:'返回',
                            cancelFn: function() { /*history.back();*/ }
                        });
                    }else
                    {
                        /*Public.tips.error('订单提交失败！');*/
                        $.sDialog({
                            content: '订单提交失败！',
                            okBtn:false,
                            cancelBtnText:'返回',
                            cancelFn: function() { /*history.back();*/ }
                        });
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




        // var msg = '';
        // for (var k in message) {
        //     msg += k + '|' + message[k] + ',';
        // }
        // $.ajax({
        //     type:'post',
        //     url:ApiUrl+'/index.php?act=member_buy&op=buy_step2',
        //     data:{
        //         key:key,
        //         ifcart:ifcart,
        //         cart_id:cart_id,
        //         address_id:address_id,
        //         vat_hash:vat_hash,
        //         offpay_hash:offpay_hash,
        //         offpay_hash_batch:offpay_hash_batch,
        //         pay_name:pay_name,
        //         invoice_id:invoice_id,
        //         voucher:voucher,
        //         pd_pay:pd_pay,
        //         password:password,
        //         fcode:fcode,
        //         rcb_pay:rcb_pay,
        //         rpt:rpt,
        //         pay_message:msg
        //     },
        //     dataType:'json',
        //     success: function(result){
        //         checkLogin(result.login);
        //         if (result.datas.error) {
        //             $.sDialog({
        //                 skin:"red",
        //                 content:result.datas.error,
        //                 okBtn:false,
        //                 cancelBtn:false
        //             });
        //             return false;
        //         }
        //         if (result.datas.payment_code == 'offline') {
        //             window.location.href = WapSiteUrl + '/tmpl/member/order_list.html';
        //         } else {
        //             delCookie('cart_count');
        //             toPay(result.datas.pay_sn,'member_buy','pay');
        //         }
        //     }
        // });
    });
});