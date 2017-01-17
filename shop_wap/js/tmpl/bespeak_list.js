var key = getCookie("key");


$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Bespeak&met=editBespeak&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                
                checkLogin(e.login);
                console.log(e.data);
                $.each(e.data, function(key, value){
                        temp='<ul><li><dl><dd style="margin-top:10px;font-size: 0.6rem;color: #444;">报修项目：'+value.bespeak_place+'</dd><dd style="margin-top:10px;font-size: 0.6rem;color: #444;">报修地址：'+value.bespeak_address+'</dd><dd style="margin-top:10px;font-size: 0.6rem;color: #444;">报修详情：'+value.bespeak_com+'</dd><dd style="margin-top:10px;font-size: 0.6rem;color: #444;">报修时间：'+value.starttime+'</dd><dd style="margin-top:10px;"><img src="http://139.196.51.206/bbc_lj/shop/shop/data'+value.bespeak_img+'" width="100px" height="100px" style="display: block; "></dd></dl><div class="handle" style="'+value.bespeak_css+'">'+value.bespeak_state+'<span><a href="javascript:;" bespeak_id="'+value.bespeak_id+'" class="delbespeak"><i class="del"></i>删除</a></span></div></li></ul>';
                    $("#bespeak_list").append(temp);
                })
                // $("#bespeak_list").empty();

                if (e.data == null)
                {
                    return false
                }
                // var s = e.data;
                // var temp = template.render("sbespeak_list", s);

                $(".delbespeak").click(function ()
                {
                    var e = $(this).attr("bespeak_id");
                    $.sDialog({
                        skin: "block", content: "确认删除吗？", okBtn: true, cancelBtn: true, okFn: function ()
                        {
                            del(e)
                        }
                    })
                })
            }
        })
    }

    s();
    function del(bespeak_id)
    {   
        // alert(bespeak_id);
        $.ajax({
            type: "post", url: ApiUrl + "?ctl=Buyer_Bespeak&met=delBespeak&typ=json", data: {id: bespeak_id, k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e)
                {
                    location.href = WapSiteUrl + "/tmpl/member/bespeak_list.html";
                }
            }
        })
    }
});