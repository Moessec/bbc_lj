$(document).ready(function(){
    var nice_scroll_row = ['.sav_goods', '.cart_con', '.item_cons', '.history_goods', '.news_contents', '.other_voucher', '.contrast_goods'];

    $.each($.unique(nice_scroll_row), function(index, data)
    {
        $scroll_obj= $(data);

        if ($scroll_obj.length > 0)
        {
            $scroll_obj.niceScroll({
                cursorcolor: "#666",
                cursoropacitymax: 1,
                touchbehavior: false,
                cursorwidth: "3px",
                cursorborder: "0",
                cursorborderradius: "3px",
                autohidemode: false,
                nativeparentscrolling:true
            });
        }
    });

    $(".all_check").click(function(){
    	var isChecked = $(this).prop("checked");
    	$(".cart_contents input").prop("checked", isChecked);
    });
    $(".cart_contents_head input").click(function(){
    	var isChecked1 = $(this).prop("checked");
      	$(this).parent().parent().siblings().find("input").prop("checked", isChecked1);
    })

    //品牌页右侧排行榜效果
    $(".bFt-list li").hover(function(){
        $(this).addClass("bFlil-expand");
    },function(){
         $(this).removeClass("bFlil-expand");
    })

    //遍历banner图背景色
    var arr2=["#5bacf7","#b96fe4","#f2a8a7","#b96fe4"];
    $.each($(".banimg li"),function(i,obj){
         if(i>=4){
            var thisindexs=$(this).index();
          i=thisindexs-Math.floor(thisindexs/5)*5;
        }
        $(this).css("backgroundColor",arr2[i])
       
    })


    $('#site_search').click(function (e){
       $("#form_search").submit();
    });
});


$(document).ready(function()
{
               //获取购物车中的数量
    	$.ajax({
    		type: "POST",
    		url: SITE_URL + "?ctl=Buyer_Cart&met=getCartGoodsNum&typ=json",
    		data: {},
    		dataType: "json",
    		success: function(data){
    			$('.ci-count').html(data.data.cart_count);
    		}
    	});
})

