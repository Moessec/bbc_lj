function callback(a)
{
    var b = Public.getDefaultPage(),
        c = $('#grid').jqGrid('getGridParam', 'selrow');
    if (c && c.length > 0)
    {
        var d = $('#grid').jqGrid('getRowData', c);
        d.id = c;
        //var e = d.member_realname + ' ' + d.member_cardnum,
		var e = d.member_realname,
            f = parent.THISPAGE.$_customer;
        f.find('input').val(e),
            f.data('contactInfo', d),
        api.data.type && b.SYSTEM[api.data.type].push(d);
        var g = f.data('callback');
        'function' == typeof g && g(d)
    }
}
var urlParam = Public.urlParam(),
    zTree,
    multiselect = urlParam.multiselect || !0,
    defaultPage = Public.getDefaultPage(),
    SYSTEM = defaultPage.SYSTEM,
    taxRequiredCheck = SYSTEM.taxRequiredCheck,
    taxRequiredInput = SYSTEM.taxRequiredInput,
    api = frameElement.api,
    data = api.data || {},
    queryConditions = {
        skey: api.data.skey || '',
        isDelete: data.isDelete || 0
    },
    THISPAGE = {
        init: function (a)
        {
            this.initDom(),
                this.loadGrid(),
                this.addEvent()
        },
        initDom: function ()
        {
            this.$_matchCon = $('#matchCon'),
                this.$_matchCon.placeholder(),
                this.$_catorage = $('#catorage'),
            queryConditions.skey && this.$_matchCon.val(queryConditions.skey);
            var a = 'customertype',
                b = '选择客户类别';
            '10' === urlParam.type && (a = 'supplytype', b = '选择供应商类别'),
                this.catorageCombo = Business.categoryCombo(this.$_catorage, {
                    editable: !1,
                    extraListHtml: '',
                    addOptions: {
                        value: -1,
                        text: b
                    },
                    defaultSelected: 0,
                    trigger: !0,
                    width: 120
                }, a)
        },
        loadGrid: function ()
        {
            var a = './member_select.php?op=index';
            '10' === urlParam.type && (a += '&type=10');
            var b = ($(window).height() - $('.grid-wrap').offset().top - 84, [
                {
                    name: 'member_id',
                    label: '会员ID',
                    index: 'member_id',
                    width: 100,
                    title: !1,
					hidden:true
                },
                {
                    name: 'member_cardnum',
                    label: '会员卡号',
                    index: 'member_cardnum',
					align: 'center',
                    width: 100,
                    title: !1
                },
                {
                    name: 'member_name',
                    label: '会员名',
                    index: 'member_name',
                    width: 120,
                    classes: 'ui-ellipsis',
					hidden:true
                },
                {
                    name: 'member_realname',
                    label: '姓名/昵称',
                    index: 'member_realname',
                    width: 100,
                    align: 'center',
                    classes: 'ui-ellipsis'
                },
                {
                    name: 'member_mobile',
                    label: '手机',
                    index: 'member_mobile',
                    width: 70,
                    align: 'center',
                    title: !1
                },
                {
                    name: 'identification',
                    label: '身份证号',
                    index: 'identification',
                    width: 140,
                    align: 'center',
                    title: !1
                }/* ,
                {
                    name: 'customer_level_id',
                    label: 'customer_level_id',
                    hidden: !0
                } */
            ]);
            $('#grid').jqGrid({
                url: a,
                postData: queryConditions,
                datatype: 'json',
                //autowidth: !0,
				width:560,
                height: 354,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                multiselect: multiselect,
                multiboxonly: multiselect,
                colModel: b,
                pager: '#page',
                viewrecords: !0,
                cmTemplate: {
                    sortable: !1
                },
                rowNum: 50,
                rowList: [
                    50,
                    100,
                    200
                ],
                shrinkToFit: !0,
                jsonReader: {
                    root: 'data.rows',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'id'
                },
                loadComplete: function (a)
                {
                    $('#jqgh_grid_cb').hide()
                },
                loadError: function (a, b, c)
                {
                }
            })
        },
        reloadData: function (a)
        {
            $('#grid').jqGrid('setGridParam', {
                page: 1,
                postData: a
            }).trigger('reloadGrid')
        },
        addEvent: function ()
        {
            var a = this;
            $('.grid-wrap').on('click', '.ui-icon-search', function (a)
            {
                a.preventDefault();
                var b = $(this).parent().data('id');
                Business.forSearch(b, '')
            }),
                $('#search').click(function ()
                {
                    var b = '输入会员卡号/手机号码/身份证号/昵称' === a.$_matchCon.val() ? '' : a.$_matchCon.val();
                    a.reloadData({
                        skey: b
                    })
                }),
                $('#refresh').click(function ()
                {
                    a.reloadData(queryConditions)
                })
        }
    };
THISPAGE.init();
