var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=advBespeak&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                
                checkLogin(e.login);
                console.log(e.data.temp);
                console.log(e.data.adv);
                $.each(e.data.adv, function(key, value){
                       tem='<ul><li><dl><dt><span class="name">已发布活动：'+value.bespeak_title+'</span><span class="phone" style="margin-left:20px">开始时间：'+value.opentime+'</span></dt><dd><br/>活动详情：'+value.bespeak_com+'</dd></dl><div class="handle">'+value.bespeak_state+'<span><a href="bespeak_opera_adv.html?bespeak_id='+value.bespeak_id+'" onclick="'+value.bespeak_click+'()"><i class="edit"></i>参与</a></div></li></ul>';
                    $("#bespeak_list").append(tem);
                })
                $.each(e.data.temp, function(key, value){
                       tem='<ul><li><dl><dt><span class="name">已参与：'+value.bespeak_title+'</span><span class="phone" style="margin-left:20px">预约时间：'+value.starttime+'</span></dt><dd><br/>活动详情：'+value.bespeak_com+'</dd></dl><div class="handle">'+value.bespeak_state+'<span><a href="javascript:;" bespeak_id="'+value.bespeak_id+'" class="delbespeak"><i class="del"></i>删除</a></span></div></li></ul>';
                    $("#bespeak_adv").append(tem);
                })

                if (e.data == null)
                {
                    return false
                }

                $(".delbespeak").click(function ()
                {
                    var bespeak_id = $(this).attr("bespeak_id");
                    $.sDialog({
                        skin: "block", content: "确认删除吗？", okBtn: true, cancelBtn: true, okFn: function ()
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
                        location.href = WapSiteUrl + "/tmpl/member/bespeak_adv.html";
                }
            }
        })
    }
});