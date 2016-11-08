﻿$(function ()
{
    if (getQueryString('key') != '')
    {
        var key = getQueryString('key');
        var username = getQueryString('username');
        addCookie('key', key);
        addCookie('username', username);
    }
    else
    {
        var key = getCookie('key');
    }
    var html = '<div class="nctouch-footer-wrap posr">'
        + '<div class="nav-text">';
    if (key)
    {
        html += '<a href="' + WapSiteUrl + '/tmpl/member/member.html">我的商城</a>'
            + '<a id="logoutbtn" href="javascript:void(0);">注销</a>'
            + '<a href="' + WapSiteUrl + '/tmpl/member/member_feedback.html">反馈</a>';

    }
    else
    {
        /*html += '<a href="' + WapSiteUrl + '/tmpl/member/login.html">登录</a>'*/
        html += '<a id="logbtn"  href="javascript:void(0);">登录</a>'
            + '<a href="' + WapSiteUrl + '/tmpl/member/register.html">注册</a>'
           /* + '<a id="regbtn" href="javascript:void(0);">注册</a>'*/
            + '<a href="' + WapSiteUrl + '/tmpl/member/login.html">反馈</a>';
    }

    if (typeof copyright == 'undefined')
    {
        copyright = '';
    }
    html += '<a href="javascript:void(0);" class="gotop">返回顶部</a>'
        + '</div>'
        + '<div class="nav-pic">'
        //+ '<a href="' + SiteUrl + '" class="app"><span><i></i></span><p>客户端</p></a>'
        //+ '<a href="javascript:void(0);" class="touch"><span><i></i></span><p>触屏版</p></a>'
        //+ '<a href="' + SiteUrl + '" class="pc"><span><i></i></span><p>电脑版</p></a>'
        + '</div>'
        + '<div class="copyright">'
        + copyright
        + '</div>';
    $("#footer").html(html);
    var key = getCookie('key');

    $("#regbtn").click(function(){
        ucenterRegist();
    });

    $("#logbtn").click(function(){
        ucenterLogin();
    });

    $('#logoutbtn').click(function ()
    {
        var username = getCookie('username');
        var key = getCookie('key');
        var client = 'wap';
        $.ajax({
            type: 'get',
            url: ApiUrl + '/index.php?ctl=Login&met=doLoginOut&typ=json',
            data: {username: username, k: key, u: getCookie('id'), client: client},
            success: function (result)
            {

                console.info(result);
                if (result)
                {

                    delCookie('username');
                    delCookie('user_account');
                    delCookie('id');
                    delCookie('key');


                    //ucenter
                    $.ajax({
                        type: "get",
                        url: UCenterApiUrl + "?ctl=Login&met=loginout&typ=json",
                        dataType: "json",
                        success: function(data){

                            location.href = WapSiteUrl;
                        },
                        error: function(){
                            //alert('error!');
                        }
                    });
                }
            }
        });
    });
});