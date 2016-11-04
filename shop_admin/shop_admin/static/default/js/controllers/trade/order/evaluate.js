
function initEvent()
{
    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "评价人", "评分", "评论内容", "晒单图片", "评价时间", "被评商品", "所属商家", "订单编号", "评价人id", "商家id"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: handle.operFmatter
    },
        {name: "member_name", index: "member_name", align: "center",width: 100},
        {name: "scores", index: "scores", width: 200, align:"center"},
        {name: "content", index: "content", width:150, align:"center"},
        {name: "image", formatter:online_imgFmt,index: "image", width:150, align:"center"},
        {name: "create_time", index: "create_time", width:150, align:"center"},
        {name: "goods_name", index: "goods_name", width:300, align:"left"},
        {name: "shop_name", index: "shop_name", width:150, align:"center"},
        {name: "order_id", index: "order_id", width:150, align:"center"},
        {name: "user_id", index: "user_id", width:150, align:"center"},
        {name: "shop_id", index: "shop_id", width:150, align:"center"},
    ];

    $("#grid").jqGrid({
        url: SITE_URL + "?ctl=Trade_Order&met=getEvaluateList&typ=json&isDelete=2",
        datatype: "json",
        height: Public.setGrid().h,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 100,
        rowList: [100, 200, 500],
        shrinkToFit: !1,
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "article_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.article_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有评价数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取评价数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    //操作项格式化，适用于有“修改、删除”操作的表格
    operFmatter: function (val, opt, row)
    {
        var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
        return html_con;
    },
     del: function (t)
    {
        $.dialog.confirm("删除的评价将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Trade_Order&met=removeEvaluate&typ=json", {id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "评价删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "评价删除失败！" + e.msg})
                }
            })
        })
    }
};
function online_imgFmt(val){
    var val = '<img src="'+val+'" style="width:150px;">';
    return val;
}
function online_scro(val){

}
initEvent();
initGrid();