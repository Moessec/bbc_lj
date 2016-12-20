 var key = getCookie('key');
function get_brand_recommend()
{

    // $(".category-item").removeClass("selected");
    setTimeout(function(){

    $(".category-item:first").addClass("selected");
  $("#categroy-cnt .category:first").click();
    // $(".category-item:first").trigger("click");
    },150);

}

// ========================钓起商品数据===================================
function shop_goodslist1()
{


     $('.goods_cont').eq(0).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
        // alert(cat_id);
        // shop_cat_goods
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(0).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(1).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(1).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(2).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(2).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(3).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(3).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(4).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(4).find('.outer').html(str); 
        });
        });

}
function shop_goodslist2(shop_id){

     $('.goods_cont').eq(0).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
        // alert(cat_id);
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+shop_id+"&shop_goods_cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";
                    }

                       
                 }   
      
        $('.goods_cont').eq(0).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(1).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+shop_id+"&shop_goods_cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

                    }

                       
                 }   
      
        $('.goods_cont').eq(1).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(2).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+shop_id+"&shop_goods_cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

                    }

                       
                 }   
      
        $('.goods_cont').eq(2).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(3).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+shop_id+"&shop_goods_cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

                    }

                       
                 }   
      
        $('.goods_cont').eq(3).find('.outer').html(str); 
        });
        });
     $('.goods_cont').eq(4).each(function(e){
        var cat_id = $(this).find('.cat_id').val();
        var str='';
        var r = '';
       $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+shop_id+"&shop_goods_cat_id="+cat_id, function (t)
        {

            r = t.data.items; 
                for (var i in r)
                {
                    if(i<4)
                    {
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p class='demo1 text-overflow1'><span> "+r[i].common_name+"</span></p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

                    }

                       
                 }   
      
        $('.goods_cont').eq(4).find('.outer').html(str); 
        });
        });
}

//===========================钓起商品数据结束==============================
//*************************************************************************
//*************************************************************************
//*************************************************************************
//=========================================================================
//===========================商品加入购物车函数============================

 // alert(key);
  var unixTimeToDateString = function(ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function(a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

  function contains(arr, str) {//检测goods_id是否存入
        var i = arr.length;
        while (i--) {
           if (arr[i] === str) {
               return true;
           }
        }
        return false;
    }


     // 购物车中商品数量
     if (getCookie('cart_count')) {
       if (getCookie('cart_count') > 0) {
            $('#cart_count').html('<sup>'+getCookie('cart_count')+'</sup>');
           }
       }



/////////////////////////加入购物车/////////////////////////////////////////
function addcart()
{
    setTimeout(function(){

            $('.add').click(function(){
             // alert(1);
                var goods_id = $(this).find('input').val();
                // alert(goods_id);
                    get_detail(goods_id);
              function get_detail(goods_id) {
                  //渲染页面
                  $.ajax({
                     url:ApiUrl+"/index.php?ctl=Goods_Goods&met=goods&typ=json",
                     type:"get",
                     data:{goods_id:goods_id,k:key,u:getCookie('id')},
                     dataType:"json",
                     success:function(result){
                        var data = result.data;
                         console.info(data);
                        if(result.status == 200){
                          //商品图片格式化数据
                          if(data.goods_image){
                            var goods_image = data.goods_image.split(",");
                            data.goods_image = goods_image;
                          }else{
                             data.goods_image = [];
                          }

                        if(data.goods_info)
                        {
                            //商品规格格式化数据
                            if(data.goods_info.common_spec_name){
                                var goods_map_spec = $.map(data.goods_info.common_spec_name,function (v,i){
                                    var goods_specs = {};
                                    goods_specs["goods_spec_id"] = i;
                                    goods_specs['goods_spec_name']=v;
                                    if(data.goods_info.common_spec_value_c){
                                        $.map(data.goods_info.common_spec_value_c,function(vv,vi){
                                            if(i == vi){
                                                goods_specs['goods_spec_value'] = $.map(vv,function (vvv,vvi){
                                                    var specs_value = {};
                                                    specs_value["specs_value_id"] = vvi;
                                                    specs_value["specs_value_name"] = vvv;
                                                    return specs_value;
                                                });
                                            }
                                        });
                                        return goods_specs;
                                    }else{
                                        data.goods_info.common_spec_value = [];
                                    }
                                });
                                data.goods_map_spec = goods_map_spec;
                            }else {
                                data.goods_map_spec = [];
                            }

                            // 虚拟商品限购时间和数量
                            if (data.goods_info.common_is_virtual == '1') {
                                data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);
                                data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);
                            }


                            // 购物车中商品数量
                            if (getCookie('cart_count')) {
                                if (getCookie('cart_count') > 0) {
                                    $('#cart_count').html('<sup>'+getCookie('cart_count')+'</sup>');
                                }
                            }

             
                            //加入购物车
                     
                                var key = getCookie('key');//登录标记
                                // var quantity = parseInt($(".buy-num").val());
                                var quantity = 1;
                                
                                if(!key){
                                    var goods_info = decodeURIComponent(getCookie('goods_cart'));
                                    if (goods_info == null) {
                                        goods_info = '';
                                    }
                                    if(goods_id<1){
                                        // show_tip();
                                        return false;
                                    }
                                    var cart_count = 0;
                                    if(!goods_info){
                                        goods_info = goods_id+','+quantity;
                                        cart_count = 1;
                                    }else{
                                        var goodsarr = goods_info.split('|');
                                        for (var i=0; i<goodsarr.length; i++) {
                                            var arr = goodsarr[i].split(',');
                                            if(contains(arr,goods_id)){
                                                // show_tip();
                                                return false;
                                            }
                                        }
                                        goods_info+='|'+goods_id+','+quantity;
                                        cart_count = goodsarr.length;
                                    }
                                    // 加入cookie
                                    addCookie('goods_cart',goods_info);
                                    // 更新cookie中商品数量
                                    addCookie('cart_count',cart_count);
                                    // show_tip();
                                     alert('加入成功');
                                    getCartCount();
                                    // alert('商品加入购物车成功');
                                    $('#cart_count').html('<sup>'+cart_count+'</sup>');
                                    return false;
                                }else{
                                    if(data.shop_owner)
                                    {
                                        alert('不能购买自己的商品');
                                        return;
                                    }
                                    if(data.isBuyHave)
                                    {
                                     
                                            alert('您已达购买上限！');
                                     
                                        return;
                                    }
                                    $.ajax({
                                        url:ApiUrl+"/index.php?ctl=Buyer_Cart&met=addCart&typ=json",
                                        data:{k:key,u:getCookie('id'),goods_id:goods_id,goods_num:quantity},
                                        type:"post",
                                        success:function (result){
                                            /*var rData = $.parseJSON(result);*/
                                            if(checkLogin(result.login)){
                                                if(result.status == 200){
                                                    // show_tip();
                                                    // 更新购物车中商品数量
                                                    delCookie('cart_count');
                                                     alert('加入成功');
                                                    getCartCount();
                                                    $('#cart_count').html('<sup>'+getCookie('cart_count')+'</sup>');
                                                }else{
                                           
                                                       alert(result.msg);
                                                }
                                            }
                                        }
                                    })
                                }
                    


                        }
                        else
                        {
                            $.sDialog({
                                content: '该商品已下架或该店铺已关闭！<br>请返回上一页继续操作…',
                                okBtn:false,
                                cancelBtnText:'返回',
                                cancelFn: function() { history.back(); }
                            });
                        }


                        }else {

                          $.sDialog({
                              content: data.error + '！<br>请返回上一页继续操作…',
                              okBtn:false,
                              cancelBtnText:'返回',
                              cancelFn: function() { history.back(); }
                          });
                        }





                     }
                  });
              }
          

             });        
    },1000);        
       
}
////////////////////主列表数据/////////////////////////////////////////////
$(function ()
{

    var e;
    $("#header").on("click", ".header-inp", function ()
    {
        location.href = WapSiteUrl + "/tmpl/search.html"
    });
   var shop_id = getQueryString('shop_id');

 
   if (shop_id!='')
   { 
        // addCookie('community_shopid',shop_id);
        document.cookie="community_shopid="+shop_id+"; path=/"; 
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=shoplists&typ=json&parent_id=0&shop_id="+shop_id, function (t)
        {
            // console.info(t);
            var r = t.data;
            r.WapSiteUrl = WapSiteUrl;
            r['status'] = 1;
            var a = template.render("category-one", r);
            $("#categroy-cnt").html(a);

        });    
    }else{
         //ctl=Shop_GoodsCat&met=shoplists&typ=json&parent_id=0
            $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=cat&typ=json&cat_parent_id=0", function (t)
            {
                console.info(t);
                var r = t.data;
                r.WapSiteUrl = WapSiteUrl;

                var a = template.render("category-one", r);
                $("#categroy-cnt").html(a);
                // e = new IScroll("#categroy-cnt", {mouseWheel: true, click: true})
                // shop_goodslist1();
            });
    }
   
    get_brand_recommend();
    $("#categroy-cnt").on("click", ".category", function ()
    {
        $(".pre-loading").show();
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        var t = $(this).attr("date-id");
       if (shop_id!='')
       {
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=treelist&typ=json", {shop_id:shop_id,parent_id: t}, function (e)
        {
            var t = e.data;
            // console.log(t);
            e.data['status'] = 1;
            t.WapSiteUrl = WapSiteUrl;
            var r = template.render("category-two", t);
            $("#categroy-rgt").html(r);
            $(".pre-loading").hide();
            // new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
            shop_goodslist2(shop_id);
            addcart();
        });
      }else{
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=tree&typ=json", {cat_parent_id: t}, function (e)
        {
            var t = e.data;
            t.WapSiteUrl = WapSiteUrl;
            var r = template.render("category-two", t);
            $("#categroy-rgt").html(r);
            $(".pre-loading").hide();
            shop_goodslist1();
             addcart();

        });        
      }
    

});




$("#categroy-cnt").on("click", ".brand", function ()
{
    $(".pre-loading").show();
    get_brand_recommend()
})



});




$(function(){

  var src;
  var sr2;

 
  src =  $('.active1').find('img')[0].src;
  var sr = src.split('.png');
  var src1 = sr[0]+2+'.png';
  // alert(src1);
  src2 = $('.active1').find('img')[0].src=src1;

});




