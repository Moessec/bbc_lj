$(function(){
    ucenterLogin();
});
$(function(){
    if (getQueryString('key') != '') {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
        updateCookieCart(key);
    } else {
        var key = getCookie('key');
    }

	if(key){
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?ctl=Buyer_User&met=getUserInfo&typ=json",
            data:{k:key,u:getCookie('id')},
            dataType:'json',
            //jsonp:'callback',
            success:function(result){
                checkLogin(result.login);

                var html = '<div class="member-info">'
                    + '<div class="user-avatar"> <img src="' + result.data.user_logo + '"/> </div>'
                    + '<div class="user-name"> <span>'+result.data.user_name+'<sup>V' + result.data.user_grade + '</sup></span> </div>'
                    + '</div>'
                    + '<div class="member-collect"><span><a href="favorites.html"><em>' + result.data.favorites_goods_num + '</em>'
                    + '<p>商品收藏</p>'
                    + '</a> </span><span><a href="favorites_store.html"><em>' +result.data.favorites_shop_num + '</em>'
                    + '<p>店铺收藏</p>'
                    + '</a> </span><span><a href="views_list.html"><i class="goods-browse"></i>'
                    + '<p>我的足迹</p>'
                    + '</a> </span></div>';
                //渲染页面
                
                $(".member-top").html(html);
                
                var html = '<li><a href="order_list.html?data-state=wait_pay">'+ (result.data.order_nopay_count > 0 ? '<em></em>' : '') +'<i class="cc-01"></i><p>待付款</p></a></li>'
                    + '<li><a href="order_list.html?data-state=order_payed">' + (result.data.order_notakes_count > 0 ? '<em></em>' : '') + '<i class="cc-02"></i><p>已付款</p></a></li>'
                    + '<li><a href="order_list.html?data-state=wait_confirm_goods">' + (result.data.order_noreceipt_count > 0 ? '<em></em>' : '') + '<i class="cc-03"></i><p>待收货</p></a></li>'
                    + '<li><a href="order_list.html?data-state=finish">' + (result.data.order_noeval_count > 0 ? '<em></em>' : '') + '<i class="cc-04"></i><p>待评价</p></a></li>'
                    + '<li><a href="member_refund.html">' + (result.data.return > 0 ? '<em></em>' : '') + '<i class="cc-05"></i><p>退款/退货</p></a></li>';
                //渲染页面
                
                $("#order_ul").html(html);
                
                var html = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>预存款</p></a></li>'
                    + '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值卡</p></a></li>'
                    + '<li><a href="voucher_list.html"><i class="cc-08"></i><p>代金券</p></a></li>'
                    + '<li><a href="redpacket_list.html"><i class="cc-09"></i><p>红包</p></a></li>'
                    + '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>积分</p></a></li>';
                $('#asset_ul').html(html);
                return false;
            }
        });
	} else {
	    var html = '<div class="member-info">'
	        + '<a href="login.html" class="default-avatar" style="display:block;"></a>'
	        + '<a href="login.html" class="to-login">点击登录</a>'
	        + '</div>'
	        + '<div class="member-collect"><span><a href="login.html"><i class="favorite-goods"></i>'
	        + '<p>商品收藏</p>'
	        + '</a> </span><span><a href="login.html"><i class="favorite-store"></i>'
	        + '<p>店铺收藏</p>'
	        + '</a> </span><span><a href="login.html"><i class="goods-browse"></i>'
	        + '<p>我的足迹</p>'
	        + '</a> </span></div>';
	    //渲染页面
	    $(".member-top").html(html);
	    
        var html = '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>'
        + '<li><a href="login.html"><i class="cc-02"></i><p>待收货</p></a></li>'
        + '<li><a href="login.html"><i class="cc-03"></i><p>待自提</p></a></li>'
        + '<li><a href="login.html"><i class="cc-04"></i><p>待评价</p></a></li>'
        + '<li><a href="login.html"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        //渲染页面
        $("#order_ul").html(html);
        return false;           
	}

	  //滚动header固定到顶部
	  $.scrollTransparent();

      
      $("#paycenter").click(function(){
        
          window.location.href = PayCenterWapUrl;
      });

 // var key = getCookie('key');

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
     
      
});
$(function(){
  var src;
  var sr2;

 
  src =  $('.active1').find('img')[0].src;
  var sr = src.split('.png');
  var src1 = sr[0]+2+'.png';
  // ale rt(src1); 
  src2 = $('.active1').find('img')[0].src=src1;

})