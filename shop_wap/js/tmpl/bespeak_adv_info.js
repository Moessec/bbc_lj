var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=getbespeaklist&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                
                checkLogin(e.login);
                console.log(e.data);
                $.each(e.data.adv, function(key, value){
                       tem='<ul><li><dl><dt><span class="name"><a href="'+value.bespeakinfo+'">'+value.bespeak_title+'</a></span></dt><div style="width:100%;height:70px;"><a href="'+value.bespeakinfo+'"><img src="'+value.bespeak_img+'" style="width:50px;height:50px;float:left"></a><dd style="float: left;margin-left: 10px;">活动时间：'+value.opentime+'--"'+value.outtime+'"</dd><dd style="float: left;margin-left: 10px;"><br/>联系人：'+value.true_name+'</dd></div></dl><div class="handle">'+value.bespeak_state+'<span><a href="'+value.bespeak_id+'"><i class="edit"></i>'+value.bespeak_click+'</a></div></li></ul>';
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
                        location.href = WapSiteUrl + "/tmpl/member/bespeak_adv.html";
                }
            }
        })
    }

});