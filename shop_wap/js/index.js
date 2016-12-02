var map_list = [];
var map_index_id = '';
var shop_id;

$(function() {

    if(getCookie('lat')&&getCookie('lng'))
    {
         $.ajax({
                url: ApiUrl + "/index.php?ctl=Shop_Settled&met=getShopInfo&typ=json",
                type: 'get',
                dataType: 'json',
                success: function(result) {
                    var da = result.data;
                    var info = da.shop_address;
                  var map = new BMap.Map("container");
                  var localSearch = new BMap.LocalSearch(map);

                    function searchByStationName(info) {
                        map.clearOverlays();//清空原来的标注
                        var keyword = info;

                        localSearch.setSearchCompleteCallback(function (searchResult) {
                            var poi = searchResult.getPoi(0);

                            map.centerAndZoom(poi.point, 13);
                            var marker = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度
                            map.addOverlay(marker);
                            // var content = document.getElementById("text_").value + "<br/><br/>经度：" + poi.point.lng + "<br/>纬度：" + poi.point.lat;
                            // alert(poi.point.lng);
                            // alert(poi.point.lat);
                           
                            $.post('ajax_back_end.php', { shoplng:poi.point.lng,shoplat:poi.point.lat }, function (distance, status) { da.shop_stamp=distance;
                                console.log(da);
                             $("#shopinfo").html(template.render('shop_info', da));   
                             });

                        });
                        localSearch.search(keyword);
                    } 
                    searchByStationName(info);                 

                }
            });
    }
 var key = getCookie('key');
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

if(!key)
{
    delCookie('cart_count');
}else{
     // 购物车中商品数量
     if (getCookie('cart_count')) {
       if (getCookie('cart_count') > 0) {
           $('#cart_count').html('<sup>'+getCookie('cart_count')+'</sup>');
           }
       }

}
 //**********************调用商品数据****************************      
 $.ajax({
        url: ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json",
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.data;
            var html = '';

            // $.each(data.items,function(k,v){
            //     console.log(v);
            // })
         
                
           
            $("#product-contain").html(template.render('goods', data));
            $("#product-contain1").html(template.render('goods1', data));
            $("#product-contain2").html(template.render('goods2', data));
        }
    });
//*******************加入购物车功能*********************************

setTimeout(function(){
  if(key)
  {
    $('.add').each(function(){

    $(this).click(function(){
     // alert(1);
        var goods_id = $(this).find('input').val();
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
                        getCartCount();
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
    });
}
},2000)







});
