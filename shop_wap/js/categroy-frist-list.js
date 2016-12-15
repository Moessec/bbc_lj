
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
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

                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";
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
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

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
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

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
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

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
                    str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].common_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].common_id+"></i></a></span></div>";

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
     $.cookie("community_shopid",shop_id,{expires:7});
        //ctl=Shop_GoodsCat&met=shoplists&typ=json&parent_id=0
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=shoplists&typ=json&parent_id=0&shop_id="+shop_id, function (t)
        {
            // console.info(t);
            var r = t.data;
            r.WapSiteUrl = WapSiteUrl;
            r['status'] = 1;
            var a = template.render("category-one", r);
            // alert(a);
            $("#categroy-cnt").html(a);
            e = new IScroll("#categroy-cnt", {mouseWheel: true, click: true});
            // shop_goodslist2(shop_id);
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
                e = new IScroll("#categroy-cnt", {mouseWheel: true, click: true})
                // shop_goodslist1();
            });
    }
   
    // get_brand_recommend();
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
            new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
            shop_goodslist2(shop_id);
        });
      }else{
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=tree&typ=json", {cat_parent_id: t}, function (e)
        {
            var t = e.data;
            t.WapSiteUrl = WapSiteUrl;
            var r = template.render("category-two", t);
            $("#categroy-rgt").html(r);
            $(".pre-loading").hide();
            new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
             shop_goodslist1();

        });        
      }
     e.scrollToElement(document.querySelector(".categroy-list li:nth-child(" + ($(this).parent().index() + 1) + ")"), 1e3);

}
 

});




    $("#categroy-cnt").on("click", ".brand", function ()
    {
        $(".pre-loading").show();
        get_brand_recommend()
    })


// var key = getCookie('key');

  

});


function get_brand_recommend()
{
    $(".category-item").removeClass("selected");
    $(".brand").parent().addClass("selected");
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Brand&met=lists&typ=json", function (e)
    {
        var t = e.data;
        t.WapSiteUrl = WapSiteUrl;
        var r = template.render("brand-one", t);
        // $("#categroy-rgt").html(r);
        $(".pre-loading").hide();
        // new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
    })
}

$(function(){
  var src;
  var sr2;

 
  src =  $('.active1').find('img')[0].src;
  var sr = src.split('.png');
  var src1 = sr[0]+2+'.png';
  // alert(src1);
  src2 = $('.active1').find('img')[0].src=src1;

})

