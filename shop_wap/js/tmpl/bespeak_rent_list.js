var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=rentBespeak&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                
                checkLogin(e.login);
                console.log(e.data);
                $.each(e.data.temp, function(key, value){
                       tem='<ul><li><dl><div style="width:100%;height:108px;"><a href="'+value.bespeakinfo+'"><img src="'+value.bespeak_img+'" style="margin-left:14px;width:100px;height:100px;float:left"></a>';
                       tem+='<dd style="float: left;margin-left: 15px; width:54%;line-height: 1.6rem;color:#000;font-size:0.8rem">'+value.bespeak_title+'</dd>';
                       tem+='<dd style="float: left;margin-left: 15px; width:54%;line-height: 1rem;">归还时间：'+value.outtime+'</dd>'
                       tem+='<dd style="float: left;margin-left: 15px; width:54%;line-height: 1rem;">价格：'+value.rent_price+'</dd>';
                       tem+='<dd style="float: left;margin-left: 15px; width:54%;line-height: 1rem;">备注：'+value.bespeak_com+'</dd></div></dl></li></ul>';
                    $("#bespeak_list").append(tem);
                })

                if (e.data == null)
                {
                    return false
                }

                $(".delbespeak").click(function ()
                {
                    var bespeak_id = $(this).attr("bespeak_id");
                    $.sDialog({
                        skin: "block", content: "确定取消吗？", okBtn: true, cancelBtn: true, okFn: function ()
                        {
                            del(bespeak_id)
                        }
                    })
                })
            }
        })
    }

    s();
    function del(a)
    {
        $.ajax({
            type: "post", url: ApiUrl + "?ctl=Buyer_Bespeak&met=delBespeak&typ=json", data: {id: a, k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e)
                {
                        location.href = WapSiteUrl + "/tmpl/member/bespeak_rent.html";
                }
            }
        })
    }
});