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
                console.info(e.data.temp);
                $.each(e.data.temp, function(key, value){
                       tem='<ul><li><dl><dt><span class="name"><a href="'+value.bespeakinfo+'" style="list-style: none;">'+value.bespeak_title+'</a></span></dt><div style="width:100%;height:70px;"><a href="'+value.bespeakinfo+'"><img src="'+value.bespeak_img+'" style="width:40px;height:40px;float:left"></a><dd style="float: left;margin-left: 10px; width:80%;line-height: 0.9rem;">活动时间：'+value.opentime.substr(0,10)+'--'+value.outtime.substr(0,10)+'</dd><dd style="float: left;margin-left: 10px; width:80%;line-height: 0.9rem;overflow:hidden;height:22px;">联系人：'+value.true_name+'</dd><dd style="float: left;margin-left: 51px; width:80%";line-height: 0.9rem;">活动地点：'+value.bes_address+'</dd></div></dl><div class="handle">'+value.bespeak_state+'<span><a href="'+value.bespeak_id+'"><i class="edit"></i>'+value.bespeak_click+'</a></div></li></ul>';
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