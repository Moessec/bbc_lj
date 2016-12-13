function initField()
{
    if (rowData.bespeak_id)
    {
        $("#bespeak_title").val(rowData.bespeak_title);
        $("#bespeak_com").val(rowData.bespeak_com);
        $("#opentime").val(rowData.opentime);
        $("#bes_img").val(rowData.bespeak_img);
        $("#true_name").val(rowData.true_name);
        $("#usercontact").val(rowData.usercontact);
        $("#bespeak_img").attr('src',rowData.bespeak_img);
        $("#some_class_1").val(rowData.opentime);
        $("#some_class_2").val(rowData.outtime);
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.bespeak_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
    var bespeak_title = $.trim($("#bespeak_title").val()),
        bespeak_com = $.trim($("#bespeak_com").val()),
        outtime = $.trim($("#some_class_2").val()),
        bes_img = $.trim($("#bes_img").val()),
        true_name = $.trim($("#true_name").val()),
        usercontact = $.trim($("#usercontact").val()),
        opentime = $.trim($("#some_class_1").val()),
        n = "add" == t ? "新增预约" : "修改预约";

    params = rowData.bespeak_id ? {
        bespeak_id: e,
        bespeak_title: bespeak_title,
        bespeak_com : bespeak_com,
        opentime : opentime,
        true_name : true_name,
        usercontact : usercontact,
        outtime : outtime,
        bes_img : bes_img
    } : {
        bespeak_title: bespeak_title,
        true_name : true_name,
        bespeak_com : bespeak_com,
        bes_img : bes_img,
        outtime : outtime,
        usercontact : usercontact,
        opentime : opentime,
    };
    console.log(params);
    Public.ajaxPost(SITE_URL +"?ctl=Goods_Bespeak&typ=json&met=" + ("add" == t ? "addGoodsbespeak" : "editGoodsbespeak"), params, function (e)
    {
        if (200 == e.status)
        {
            parent.parent.Public.tips({content: n + "成功！"});
            callback && "function" == typeof callback && callback(e.data, t, window)
        }
        else
        {
            parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
        }
    })
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
areaSelected: function(options) {
            var defaults = {
                    success : function(data){}
                }
            var options = $.extend({}, defaults, options);
            var ASID = 0;
            var ASID_1 = 0;
            var ASID_2 = 0;
            var ASID_3 = 0;
            var ASNAME = '';
            var ASINFO = '';
            var ASDEEP = 1;
            var ASINIT = true;
            function _init() {
                if ($('#areaSelected').length > 0) {
                    $('#areaSelected').remove();
                }
                var html = '<div id="areaSelected">'
                    + '<div class="nctouch-full-mask left">'
                    + '<div class="nctouch-full-mask-bg"></div>'
                    + '<div class="nctouch-full-mask-block">'
                    + '<div class="header">'
                    + '<div class="header-wrap">'
                    + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>'
                    + '<div class="header-title">'
                    + '<h1>选择地区</h1>'
                    + '</div>'
                    + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>'
                    + '</div>'
                    + '</div>'
                    + '<div class="nctouch-main-layout">'
                    + '<div class="nctouch-single-nav">'
                    + '<ul id="filtrate_ul" class="area">'
                    + '<li class="selected"><a href="javascript:void(0);">一级地区</a></li>'
                    + '<li><a href="javascript:void(0);" >二级地区</a></li>'
                    + '<li><a href="javascript:void(0);" >三级地区</a></li>'
                    + '</ul>'
                    + '</div>'
                    + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>';
                $('body').append(html);
                _getAreaList();
                _bindEvent();
                _close();
            }

            function _getAreaList() {
                $.ajax({//获取区域列表
                    type:'get',
                    url:ApiUrl+'/index.php?ctl=Base_District&met=district&typ=json',
                    data:{pid:ASID},
                    dataType:'json',
                    async:false,
                    success:function(result){
                        if (result.data.items.length == 0) {
                            _finish();
                            return false;
                        }
                        if (ASINIT) {
                            ASINIT = false
                        } else {
                            ASDEEP++;
                        }
                        $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP-1).addClass('selected').siblings().removeClass('selected');
                        checkLogin(result.login);
                        var data = result.data;
                        var area_li = '';
                        for(var i=0;i<data.items.length;i++){
                            area_li += '<li><a href="javascript:void(0);" data-id="' + data.items[i].district_id + '" data-name="' + data.items[i].district_name + '"><h4>' + data.items[i].district_name + '</h4><span class="arrow-r"></span> </a></li>';
                        }
                        $('#areaSelected').find(".nctouch-default-list").html(area_li);
                        if (typeof(myScrollArea) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                  });
                            }
                            myScrollArea = new IScroll('#areaSelected .nctouch-main-layout-a', { mouseWheel: true, click: true });
                        } else {
                            myScrollArea.destroy();
                            myScrollArea = new IScroll('#areaSelected .nctouch-main-layout-a', { mouseWheel: true, click: true });
                        }
                    }
                });
                return false;
            }
            
            function _bindEvent() {
                $('#areaSelected').find('.nctouch-default-list').off('click', 'li > a');
                $('#areaSelected').find('.nctouch-default-list').on('click', 'li > a', function(){
                    ASID = $(this).attr('data-id');
                    eval("ASID_"+ASDEEP+"=$(this).attr('data-id')");
                    ASNAME = $(this).attr('data-name');
                    ASINFO += ASNAME + ' ';
                    var _li = $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP);
                    _li.prev().find('a').attr({'data-id':ASID, 'data-name':ASNAME}).html(ASNAME);
                    if (ASDEEP == 3) {
                        _finish();
                        return false;
                    }
                    _getAreaList();
                });
                $('#areaSelected').find('#filtrate_ul').off('click', 'li > a');
                $('#areaSelected').find('#filtrate_ul').on('click', 'li > a', function(){
                    if ($(this).parent().index() >= $('#areaSelected').find('#filtrate_ul').find('.selected').index()) {
                        return false;
                    }
                    ASID = $(this).parent().prev().find('a').attr('data-id');
                    ASNAME = $(this).parent().prev().find('a').attr('data-name');
                    ASDEEP = $(this).parent().index();
                    ASINFO = '';
                    for (var i=0; i<$('#areaSelected').find('#filtrate_ul').find('a').length; i++) {
                        if (i < ASDEEP) {
                            ASINFO += $('#areaSelected').find('#filtrate_ul').find('a').eq(i).attr('data-name') + ' ';
                        } else {
                            var text = '';
                            switch (i) {
                            case 0:
                                text = '一级地区'
                                break;
                            case 1:
                                text = '二级地区'
                                break;
                            case 2:
                                text = '三级地区';
                                break;
                            }
                            $('#areaSelected').find('#filtrate_ul').find('a').eq(i).html(text);
                        }
                    }
                    _getAreaList();
                });
            }
            
            function _finish() {
                var data = {area_id:ASID,area_id_1:ASID_1,area_id_2:ASID_2,area_id_3:ASID_3,area_name:ASNAME,area_info:ASINFO};
                options.success.call('success', data);
                if (!ASINIT) {
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                }
                return false;
            }
            
            function _close() {
                $('#areaSelected').find('.header-l').off('click', 'a');
                $('#areaSelected').find('.header-l').on('click', 'a',function(){
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                });
                return false;
            }
            
            return this.each(function() {
                return _init();
            })();
        },
        


        /**
         * 从右到左动态显示隐藏内容
         * 
         */
        animationLeft: function(options) {
            var defaults = {
                    valve : '.animation-left',          // 动作触发
                    wrapper : '.nctouch-full-mask',    // 动作块
                    scroll : ''     // 滚动块，为空不触发滚动
            }
            var options = $.extend({}, defaults, options);
            function _init() {
                $(options.valve).click(function(){
                    $(options.wrapper).removeClass('hide').removeClass('right').addClass('left');

                    if (options.scroll != '') {
                        if (typeof(myScrollAnimationLeft) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                });
                            }
                            myScrollAnimationLeft = new IScroll(options.scroll, { mouseWheel: true, click: true });
                        } else {
                            myScrollAnimationLeft.refresh();
                        }
                    }
                });
                $(options.wrapper).on('click', '.header-l > a', function(){
                    $(options.wrapper).addClass('right').removeClass('left');
                });
                
            }
            return this.each(function() {
                _init();
            })();
        },

        /**
         * 从下到上动态显示隐藏内容
         * 
         */
        animationUp: function(options) {
            var defaults = {
                    valve : '.animation-up',          // 动作触发，为空直接触发
                    wrapper : '.nctouch-bottom-mask',    // 动作块
                    scroll : '.nctouch-bottom-mask-rolling',     // 滚动块，为空不触发滚动
                    start : function(){},       // 开始动作触发事件
                    close : function(){}        // 关闭动作触发事件
            }
            var options = $.extend({}, defaults, options);
            function _animationUpRun() {
                options.start.call('start');
                $(options.wrapper).removeClass('down').addClass('up');

                if (options.scroll != '') {
                    if (typeof(myScrollAnimationUp) == 'undefined') {
                        if (typeof(IScroll) == 'undefined') {
                            $.ajax({
                                url: WapSiteUrl+'/js/iscroll.js',
                                dataType: "script",
                                async: false
                              });
                        }
                        myScrollAnimationUp = new IScroll(options.scroll, { mouseWheel: true, click: true });
                    } else {
                        myScrollAnimationUp.refresh();
                    }
                }
            }
            return this.each(function() {
                if (options.valve != '') {
                    $(options.valve).on('click', function(){
                        _animationUpRun();
                    });
                } else {
                    _animationUpRun();
                }
                $(options.wrapper).on('click', '.nctouch-bottom-mask-bg,.nctouch-bottom-mask-close', function(){
                    $(options.wrapper).addClass('down').removeClass('up');
                    options.close.call('close');
                });
            })();
        }
function resetForm(t)
{
    $("#manage-form").validate().resetForm();
    $("#bespeak_title").val("");
    $("#bespeak_com").val("");
    $("#some_class_1").val("");
    $("#bes_img").val("");
    $("#true_name").val("");
    $("#some_class_2").val("");
    $("#usercontact").val("");
}
$("#area_info").on("click", function ()
{
    $.areaSelected({
        success: function (a)
        {
            $("#area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
        }
    })
})
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;

initPopBtns();
initField();