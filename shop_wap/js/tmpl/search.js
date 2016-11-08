$(function ()
{
    Array.prototype.unique = function ()
    {
        var e = [];
        for (var t = 0; t < this.length; t++)
        {
            if (e.indexOf(this[t]) == -1)
            {
                e.push(this[t])
            }
        }
        return e
    };
    var e = decodeURIComponent(getQueryString("keyword"));
    if (e)
    {
        $("#keyword").val(e);
        writeClear($("#keyword"))

        if(window.localStorage){

            if (('undefined' != typeof window.localStorage['his_list']))
            {
                var td = window.localStorage['his_list'].split(',');
            }
            else
            {
                var td = [];
            }


            if (-1 == $.inArray(e, td))
            {
                td.push(e);
            }

            window.localStorage['his_list'] = td;
        }else{
        }

    }
    $("#keyword").on("input", function ()
    {
        var e = $.trim($("#keyword").val());
        if (e == "")
        {
            $("#search_tip_list_container").hide()
        }
        else
        {
            $.getJSON(ApiUrl + "/index.php?act=goods&op=auto_complete", {term: $("#keyword").val()}, function (e)
            {
                if (!e.data.error)
                {
                    var t = e.data;
                    t.WapSiteUrl = WapSiteUrl;
                    if (t.list.length > 0)
                    {
                        $("#search_tip_list_container").html(template.render("search_tip_list_script", t)).show()
                    }
                    else
                    {
                        $("#search_tip_list_container").hide()
                    }
                }
            })
        }
    } );
    $(".input-del").click(function ()
    {
        $(this).parent().removeClass("write").find("input").val("")
    });
    template.helper("$buildUrl", buildUrl);
    $.getJSON(ApiUrl + "/index.php?ctl=Index&met=getSearchKeyList&typ=json", function (e)
    {
        var t = e.data;
        t.WapSiteUrl = WapSiteUrl;

        if(window.localStorage && ('undefined' != typeof window.localStorage['his_list'])){
            t['his_list'] = window.localStorage['his_list'].split(',');
        }else{
        }


        $("#hot_list_container").html(template.render("hot_list", t));
        $("#search_his_list_container").html(template.render("search_his_list", t))
    });
    $("#header-nav").click(function ()
    {
        if ($("#keyword").val() == "")
        {
            window.location.href = buildUrl("keyword", getCookie("deft_key_value") ? getCookie("deft_key_value") : "")
        }
        else
        {
            window.location.href = buildUrl("keyword", $("#keyword").val())
        }
    })
});