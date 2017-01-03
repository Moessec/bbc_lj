var page = pagesize;
var curpage = 1;
var firstRow = 0;
var hasmore = true;
var footer = false;
var keyword = decodeURIComponent(getQueryString("keyword"));
var cat_id = getQueryString("cat_id");
var shop_goods_cat_id = getQueryString("shop_goods_cat_id");
var brand_id = getQueryString("brand_id");
var key = getQueryString("key");
var order = getQueryString("order");
var area_id = getQueryString("area_id");
var price_from = getQueryString("price_from");
var price_to = getQueryString("price_to");
var own_shop = getQueryString("own_shop");
var gift = getQueryString("gift");
var actgoods = getQueryString("actgoods");
var virtual = getQueryString("virtual");
var ci = getQueryString("ci");
var myDate = new Date;
var searchTimes = myDate.getTime();
var seller = getQueryString("ctl");
$(function ()
{
    // alert(1);
    if(seller=='Seller_Promotion_Discount')
    {
        $("#nav_ul").css('display','none');
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=index222&op=manage&typ=json&id=1" , function (e)
        {
           var data = e.data;
            console.log(e);
            if(data){var d = template.render("home_body1", data);}
            
            $("#product_list .goods-secrch-list").append(d);
       
        });        
    }else if(seller=='Promotion_ActIncrease'){
        // alert(1);
        $("#nav_ul").css('display','none');
        $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=getShopGoods121&op=manage&typ=json&id=1" , function (e)
        {
           var data = e.data;
           console.log(e);
            if(data){var d = template.render("home_body2", data);}
            
            $("#product_list .goods-secrch-list").append(d);
       
        });         
    }
    $.animationLeft({valve: "#search_adv", wrapper: ".nctouch-full-mask", scroll: "#list-items-scroll"});
    $("#header").on("click", ".header-inp", function ()
    {
        location.href = WapSiteUrl + "/tmpl/search.html?keyword=" + keyword
    });
    if (keyword != "")
    {
        $("#keyword").html(keyword)
    }
    $("#show_style").click(function ()
    {
        if ($("#product_list").hasClass("grid"))
        {
            $(this).find("span").removeClass("browse-grid").addClass("browse-list");
            $("#product_list").removeClass("grid").addClass("list")
        }
        else
        {
            $(this).find("span").addClass("browse-grid").removeClass("browse-list");
            $("#product_list").addClass("grid").removeClass("list")
        }
    });
    $("#sort_default").click(function ()
    {
        if ($("#sort_inner").hasClass("hide"))
        {
            $("#sort_inner").removeClass("hide")
        }
        else
        {
            $("#sort_inner").addClass("hide")
        }
    });
    $("#nav_ul").find("a").click(function ()
    {
        $(this).addClass("current").parent().siblings().find("a").removeClass("current");
        if (!$("#sort_inner").hasClass("hide") && $(this).parent().index() > 0)
        {
            $("#sort_inner").addClass("hide")
        }
    });
    $("#sort_inner").find("a").click(function ()
    {
        $("#sort_inner").addClass("hide").find("a").removeClass("cur");
        var e = $(this).addClass("cur").text();
        $("#sort_default").html(e + "<i></i>")
    });
    $("#product_list").on("click", ".goods-store a", function ()
    {
        var e = $(this);
        var r = $(this).attr("data-id");
        var i = $(this).text();
        $.getJSON(ApiUrl + "/index.php?act=store&op=store_credit", {shop_id: r}, function (t)
        {
            var a = "<dl>" + '<dt><a href="store.html?shop_id=' + r + '">' + i + '<span class="arrow-r"></span></a></dt>' + '<dd class="' + t.datas.store_credit.store_desccredit.percent_class + '">描述相符：<em>' + t.datas.store_credit.store_desccredit.credit + "</em><i></i></dd>" + '<dd class="' + t.datas.store_credit.store_servicecredit.percent_class + '">服务态度：<em>' + t.datas.store_credit.store_servicecredit.credit + "</em><i></i></dd>" + '<dd class="' + t.datas.store_credit.store_deliverycredit.percent_class + '">发货速度：<em>' + t.datas.store_credit.store_deliverycredit.credit + "</em><i></i></dd>" + "</dl>";
            e.next().html(a).show()
        })
    }).on("click", ".sotre-creidt-layout", function ()
    {
        $(this).hide()
    });
    get_list();
    $(window).scroll(function ()
    {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        {
            get_list()
        }
    });
    search_adv()
});
function get_list()
{
    $(".loading").remove();
    if (!hasmore)
    {
        return false
    }
    hasmore = false;
    param = {};
    param.rows = page;
    param.page = curpage;
    param.firstRow = firstRow;
    if (cat_id != "")
    {
        param.cat_id = cat_id
    }
    else if (keyword != "")
    {
        param.keywords = keyword
    }
    else if (brand_id != "")
    {
        param.brand_id = brand_id
    }else if(shop_goods_cat_id != '')
    {
        param.shop_goods_cat_id = shop_goods_cat_id
    }
    if (key != "")
    {
        param.actorder = key
    }
    if (order != "")
    {
        param.act = order
    }
    if(price_from != "")
    {
        param.price_from = price_from
    }
    if(price_to != "")
    {
        param.price_to = price_to
    }
    if(own_shop != "")
    {
        param.op3 = 'ziying'
    }
    if(actgoods != '')
    {
        param.op2 = 'active'
    }
    if(virtual != '')
    {
        param.isvirtual = virtual
    }
    // alert(1);
if(shop_goods_cat_id)
{
    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=shop_cat_goods&typ=json&shop_id="+getCookie('community_shopid')+"&shop_goods_cat_id="+shop_goods_cat_id, function (e)
    {
        if (!e)
        {
            e = [];
            e.datas = [];
            e.data.goods_list = []
        }
        $(".loading").remove();
        curpage++;
        console.info(e);
        e.data['status']= 1;
        var r = template.render("home_body", e);
        $("#product_list .goods-secrch-list").append(r);
        //hasmore = e.hasmore
       if(e.data.page < e.data.total)
       {
           firstRow = e.data.records;
           hasmore = true;
       }
        else
       {
           hasmore = false;
       }
    })

}else{


    $.getJSON(ApiUrl + "/index.php?ctl=Goods_Goods&met=goodslist&typ=json" + window.location.search.replace("?", "&"), param, function (e)
    {
        if (!e)
        {
            e = [];
            e.datas = [];
            e.data.goods_list = []
        }
        $(".loading").remove();
        curpage++;
        console.info(e);
        var r = template.render("home_body", e);
        $("#product_list .goods-secrch-list").append(r);
        //hasmore = e.hasmore
       if(e.data.page < e.data.total)
       {
           firstRow = e.data.records;
           hasmore = true;
       }
        else
       {
           hasmore = false;
       }
    })
 }   
}
function search_adv()
{
    $.getJSON(ApiUrl + "/index.php?ctl=Index&met=getSearchAdv&typ=json", function (e)
    {
        var r = e.data;
        $("#list-items-scroll").html(template.render("search_items", r));
        if (area_id)
        {
            $("#area_id").val(area_id)
        }
        if (price_from)
        {
            $("#price_from").val(price_from)
        }
        if (price_to)
        {
            $("#price_to").val(price_to)
        }
        if (own_shop)
        {
            $("#own_shop").addClass("current")
        }
        if (actgoods)
        {
            $("#actgoods").addClass("current")
        }
        if (virtual)
        {
            $("#virtual").addClass("current")
        }
        if (ci)
        {
            var i = ci.split("_");
            for (var t in i)
            {
                $('a[name="ci"]').each(function ()
                {
                    if ($(this).attr("value") == i[t])
                    {
                        $(this).addClass("current")
                    }
                })
            }
        }
        $("#search_submit").click(function ()
        {
            var e = "?keyword=" + keyword, r = "";
            e += "&transport_id=" + $("#area_id").val();
            if ($("#price_from").val() != "")
            {
                e += "&price_from=" + $("#price_from").val()
            }
            if ($("#price_to").val() != "")
            {
                e += "&price_to=" + $("#price_to").val()
            }
            if ($("#own_shop")[0].className == "current")
            {
                e += "&own_shop=1"
            }
            if ($("#actgoods")[0].className == "current")
            {
                e += "&actgoods=1"
            }
            if ($("#virtual")[0].className == "current")
            {
                e += "&virtual=1"
            }
            $('a[name="ci"]').each(function ()
            {
                if ($(this)[0].className == "current")
                {
                    r += $(this).attr("value") + "_"
                }
            });
            if (r != "")
            {
                e += "&ci=" + r
            }
            window.location.href = WapSiteUrl + "/tmpl/product_list.html" + e
        });
        $('a[nctype="items"]').click(function ()
        {
            var e = new Date;
            if (e.getTime() - searchTimes > 300)
            {
                $(this).toggleClass("current");
                searchTimes = e.getTime()
            }
        });
        $('input[nctype="price"]').on("blur", function ()
        {
            if ($(this).val() != "" && !/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test($(this).val()))
            {
                $(this).val("")
            }
        });
        $("#reset").click(function ()
        {
            $('a[nctype="items"]').removeClass("current");
            $('input[nctype="price"]').val("");
            $("#area_id").val("")
        })
    })
}
function init_get_list(e, r)
{
    order = e;
    key = r;
    if(shop_goods_cat_id){
        order = 'price';
        alert(1221);
    } 
    curpage = 1;
    firstRow = 0;
    hasmore = true;
    $("#product_list .goods-secrch-list").html("");
    $("#footer").removeClass("posa");
    get_list();
}
$('#list-items-scroll').on('click', '#area_info', function(){
    alert('sss');
    $.areaSelected({
        success: function (a)
        {
            $("#area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
        }
    });
});