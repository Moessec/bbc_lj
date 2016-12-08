$(function ()
{

    var e;
    $("#header").on("click", ".header-inp", function ()
    {
        location.href = WapSiteUrl + "/tmpl/search.html"
    });
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=cat&typ=json&cat_parent_id=0", function (t)
    {
        console.info(t);
        var r = t.data;
        r.WapSiteUrl = WapSiteUrl;
        var a = template.render("category-one", r);
        $("#categroy-cnt").html(a);
        e = new IScroll("#categroy-cnt", {mouseWheel: true, click: true})
    });
    get_brand_recommend();
    $("#categroy-cnt").on("click", ".category", function ()
    {
        $(".pre-loading").show();
        $(this).parent().addClass("selected").siblings().removeClass("selected");
        var t = $(this).attr("date-id");
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Cat&met=tree&typ=json", {cat_parent_id: t}, function (e)
        {
            var t = e.data;
            t.WapSiteUrl = WapSiteUrl;
            var r = template.render("category-two", t);
            $("#categroy-rgt").html(r);
            $(".pre-loading").hide();
            new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
        });
        e.scrollToElement(document.querySelector(".categroy-list li:nth-child(" + ($(this).parent().index() + 1) + ")"), 1e3);
 
 setTimeout(function(){
 $('.goods_cont').each(function(e){
    var cat_id = $(this).find('.cat_id').val();
    var str='';
    var r = '';
   $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json&cat_id="+cat_id, function (t)
    {

        r = t.data.items;
  
    
     
    console.log(str); 
    });
    for (var i=0; i<r.length ;i++)
    {
        if(i<4)
        {

        str+="<div class='inter'><div class='clear'><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><image src="+r[i].common_image+" /></a></div><a href='../tmpl/product_detail.html?goods_id="+r[i].goods_id+"'><p> "+r[i].common_name+"</p></a><span class='add1'>￥"+r[i].common_price+"</a><a  href='javascript:void(0)'><i class='add'><input type='hidden' value="+r[i].goods_id+"></i></a></span></div>";
        }

           
     }   
   alert(str);
    //展示模版
    // $(this).find('.outer').html(str); 
    $(this).eq(e).find('.outer').html(str); 

    });
},100)

    });
    $("#categroy-cnt").on("click", ".brand", function ()
    {
        $(".pre-loading").show();
        get_brand_recommend()
    })

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
        $("#categroy-rgt").html(r);
        $(".pre-loading").hide();
        new IScroll("#categroy-rgt", {mouseWheel: true, click: true})
    })
}