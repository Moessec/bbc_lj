/*获取商城用户列表*/

//回调函数，选择完客户后，将客户信息填充的查询输入框
function callback(a)
{
    var b = Public.getDefaultPage(),
        c = $('#grid').jqGrid('getGridParam', 'selrow');
    if (c && c.length > 0)
    {
        var d = $('#grid').jqGrid('getRowData', c);
        d.id = c;
		var e = d.user_name,
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
	
	//初始化信息
    THISPAGE = {
        init: function (a)
        {
            this.initDom(),  //初始化DOM
			this.loadGrid(), //载入表格数据
			this.addEvent()  //初始化事件
        },
        initDom: function ()
        {
            this.$_matchCon = $('#matchCon'),
			this.$_matchCon.placeholder(),
			this.$_catorage = $('#catorage'),
            queryConditions.skey && this.$_matchCon.val(queryConditions.skey);
        },
		
        loadGrid: function ()
        {
            var b = ($(window).height() - $('.grid-wrap').offset().top - 84, [
                {
                    name: 'user_id',
                    label: '会员ID',
                    index: 'user_id',
                    width: 100,
                    title: !1,
					hidden:true
                },
                {
                    name: 'user_cardnum',
                    label: '会员卡号',
                    index: 'user_cardnum',
					align: 'center',
                    width: 100,
                    title: !1
                },
                {
                    name: 'user_name',
                    label: '会员名',
                    index: 'user_name',
                    width: 120,
                    classes: 'ui-ellipsis'
                },
                {
                    name: 'user_realname',
                    label: '姓名/昵称',
                    index: 'user_realname',
                    width: 100,
                    align: 'center',
                    classes: 'ui-ellipsis'
                },
                {
                    name: 'user_mobile',
                    label: '手机',
                    index: 'user_mobile',
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
                }
            ]);
            $('#grid').jqGrid({
                url: SITE_URL + '?ctl=User&met=getBuyerList&typ=json',
                postData: queryConditions,
                datatype: 'json',
                //autowidth: !0,
				width:700,
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
                    root: 'data.items',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'user_id'
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
		
        reloadData: function (a) //重新载入数据
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
			
			//查询
			$('#search').click(function () 
			{
				var b = '输入会员卡号/手机号码/身份证号/昵称' === a.$_matchCon.val() ? '' : a.$_matchCon.val();
				a.reloadData({
					skey: b
				})
			}),
			
			//刷新
			$('#btn-refresh').click(function ()
			{
				a.reloadData(queryConditions)
			})
        }
    };
	
THISPAGE.init();
