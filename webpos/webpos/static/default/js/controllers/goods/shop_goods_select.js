/*选择商品弹出框*/

function callbackSp(a) {
    var b = parent.THISPAGE || api.data.page,
        c = b.curID,
        d = (b.newId, 'fix1'),
        e = (api.data.callback, $('#grid').jqGrid('getGridParam', 'selarrrow')),
        f = e.length,
        g = oldRow = parent.curRow,
        h = parent.curCol;
    if (f > 0) {
        parent.$('#fixedGrid').jqGrid('restoreCell', g, h);
        var i = (Public.getDefaultPage(), $('#grid').jqGrid('getRowData', e[0]));
        if (i.id = i.id.split('_') [0], 'string' == typeof i.invSkus && (i.invSkus = $.parseJSON(i.invSkus)), '' === i.spec) var j = i.number + ' ' + i.name;
        else var j = i.number + ' ' + i.name + '_' + i.spec;
        i.isSerNum = 0 == i.isSerNum ? 0 : 1;
        var k = $.extend(!0, {
        }, i);
        if (k.goods = j, k.id = d, c) var l = parent.$('#fixedGrid').jqGrid('setRowData', c, {
        });
        l && parent.$('#' + c).data('goodsInfo', i).data('storageInfo', {
            id: i.locationId,
            name: i.locationName
        }).data('unitInfo', {
            unitId: i.unitId,
            name: i.unitName
        }),
            parent.$('#fixedGrid').jqGrid('setRowData', d, k)
    }
    return e
}

//选择商品后回调
function callback(a) {
    var b = parent.THISPAGE || api.data.page,
        c = b.curID,
        d = b.newId,
        e = api.data.callback,
        f = $('#grid').jqGrid('getGridParam', 'selarrrow'),
        g = f.length,
        h = oldRow = parent.curRow,
        i = parent.curCol;

    if (isSingle) {
        parent.$('#grid').jqGrid('restoreCell', h, i);
        var j = $('#grid').jqGrid('getRowData', $('#grid').jqGrid('getGridParam', 'selrow'));
        if ('string' == typeof j.invSkus && (j.invSkus = $.parseJSON(j.invSkus)), j.id = j.id.split('_') [0], delete j.amount, '' === j.spec) var k = j.number + ' ' + j.name;
        else var k = j.number + ' ' + j.name + '_' + j.goods_spec;
        if (h > 8 && h > oldRow) var l = h;
        else var l = c;
        var m = parent.$('#grid').jqGrid('getRowData', Number(c));
        m = $.extend({
        }, m, {
            id: j.id,
            goods: k,
            invNumber: j.number,
            invName: j.name,
            unitName: j.unitName,
            qty: 1,
            price: j.goods_price,
            spec: j.spec,
            skuId: j.skuId,
            skuName: j.skuName,
            isSerNum: 0 == j.isSerNum ? 0 : 1,
            safeDays: j.safeDays,
            invSkus: j.invSkus
        }),
        e(l, m)
    } 
	else if (g > 0) 
	{
        parent.$('#grid').jqGrid('restoreCell', h, i);

        for (rowid in addList) {
            var j = addList[rowid];
			defaultPage.SYSTEM.goodsInfo =[];
            if (j.goods_id = j.goods_id.split('_') [0], delete j.amount, defaultPage.SYSTEM.goodsInfo.push(j), j.invSkus = j.invSkus, '' === j.spec) var k = j.goods_code + ' ' + j.goods_name;
            else var k = j.goods_code + ' ' + j.goods_name + '_' + j.goods_spec;
            if (c) var l = c;
            else var l = d;
            var n = $.extend(!0, {
            }, j);
            if (n.goods = k, n.goods_id = l, n.qty = n.qty || 1, c) var o = parent.$('#grid').jqGrid('setRowData', Number(c), {
            });
            else {
                var o = parent.$('#grid').jqGrid('addRowData', Number(d), {
                }, 'last');
                d++
            }
            j.isSerNum = 0 == j.isSerNum ? 0 : 1,
            o && parent.$('#' + l).data('goodsInfo', j).data('storageInfo', {
                id: j.shop_id,
                name: j.shop_name
            }).data('unitInfo', {      //商品单位信息
                unitId: j.unitId,
                name: j.unitName
            }),
            parent.$('#grid').jqGrid('setRowData', l, n),
            h++;
            var p = parent.$('#' + c).next();
            c = p.length > 0 ? parent.$('#' + c).next().attr('id')  : ''
        }
        e(d, c, h),
        $('#grid').jqGrid('resetSelection'),
        addList = {}
    }
    return f
}

var $grid = $('#grid'),
    addList = {},
    urlParam = Public.urlParam(),
    zTree,
    defaultPage = Public.getDefaultPage(),
    SYSTEM = defaultPage.SYSTEM,
    taxRequiredCheck = SYSTEM.taxRequiredCheck;
	taxRequiredInput = SYSTEM.taxRequiredInput;
	
	var api = frameElement.api,
    data = api.data || {},
    isSingle = data.isSingle || 0,
    skuMult = data.skuMult,
    //查询条件
    queryConditions = {
        skey: (frameElement.api.data ? frameElement.api.data.skey : '') || '',
        isDelete: data.isDelete || 0
    };
	//如果启用了扫码枪，则多传递一个查询参数
	if($.cookie('BarCodeInsert')){
            queryConditions.searchIndex = 'barcode';
        };
	
    THISPAGE = {
        init: function (a) {
            this.initDom(),
			this.loadGrid(),
			this.initZtree(),
			this.addEvent()
        },
        initDom: function () {
            this.$_matchCon = $('#matchCon').val(queryConditions.skey || '请输入商品编号/名称'),
            this.$_matchCon.placeholder()
        },
        initZtree: function () {
            zTree = Public.zTree.init($('.grid-wrap'), {
			defaultClass: 'ztreeDefault',
			showRoot: !0
            }, 
			{
                callback: {
                    beforeClick: function (a, b) {
                        queryConditions.assistId = b.id,
                        $('#search').trigger('click')
                    }
                }
            })
        },
		
		//载入表格
        loadGrid: function () {
            function a(a, b, c) {
                var d = '<div class="operating" data-id="' + c.goods_id + '"><a class="ui-icon ui-icon-search" title="查询"></a><span class="ui-icon ui-icon-copy" title="商品图片"></span></div>';
                return d
            }
            $(window).height() - $('.grid-wrap').offset().top - 84;
            $('#grid').jqGrid({
				url: SITE_URL + '?ctl=Goods&met=getShopGoodsBaseList&typ=json',
                postData: queryConditions,
                datatype: 'json',
                width: 578,
                height: 354,
                altRows: !0,
                gridview: !0,
                colModel: [
                    {
                        name: 'goods_id',
                        label: 'ID',
                        width: 0,
                        hidden: !0
                    },
                    {
                        name: 'goods_code',
                        label: '商品编号',
                        width: 100,
                        title: !1
                    },
                    {
                        name: 'goods_name',
                        label: '商品名称',
                        width: 200,
                        classes: 'ui-ellipsis'
                    },
					{
                        name: 'goods_stock',
                        label: '库存',
                        width: 60,
                        hidden: !skuMult,
                        formatter: function (a) {
                            return a || '&#160;'
                        }
                    },
					{
                        name: 'goods_price',
                        label: '零售价',
                        width: 100
                    }, 
					{
                        name: 'goods_spec',
                        label: '规格型号',
                        width: 106,
                        title: !1
                    },
					{
                        name: 'shop_id',
                        label: '店铺ID',
                        width: 0,
                        hidden: !0
                    },
                    {
                        name: 'shop_name',
                        label: '店铺名称',
                        width: 120,
						classes: 'ui-ellipsis'
                    },
                    {
                        name: 'skuId',
                        label: 'skuId',
                        width: 0,
                        hidden: !0
                    },
                    {
                        name: 'skuName',
                        label: '属性',
                        width: 0,
                        hidden: !0
                    },
					{
                        name: 'pid',
                        label: 'pid',
                        width: 0,
                        hidden: !0
                    }, 
					{
                        name: 'pic',
                        label: 'pic',
                        width: 0,
                        hidden: !0
                    },                   
                    {
                        name: 'goodsName',
                        label: '商品名称',
                        width: 0,
                        hidden: !0
                    },
                    {
                        name: 'pcatid',
                        label: '商品分类ID',
                        width: 0,
                        hidden: !0
                    },             
                    {
                        name: 'specName',
                        label: '属性名称',
                        width: 0,
                        hidden: !0
                    }
                ],
                cmTemplate: {
                    sortable: !1
                },
                multiselect: isSingle ? !1 : !0,
                page: 1,
                sortname: 'number',
                sortorder: 'desc',
                pager: '#page',
                page: 1,
                rowNum: 100,
                rowList: [
                    50,
                    100,
                    200
                ],
                viewrecords: !0,
                shrinkToFit: !0,
                forceFit: !1,
                jsonReader: {
                    root: 'data.items',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'goods_id'
                },
                loadError: function (a, b, c) {
                },
                ondblClickRow: function (a, b, c, d) {
                    isSingle && (callback(), frameElement.api.close())
                },
                onSelectRow: function (a, b) {
                    if (b) {
                        var c = $grid.jqGrid('getRowData', a);
                        'string' == typeof c.invSkus && (c.invSkus = $.parseJSON(c.invSkus)),
                            addList[a] = c
                    } else addList[a] && delete addList[a]
                },
                onSelectAll: function (a, b) {
                    for (var c = 0, d = a.length; d > c; c++) {
                        var e = a[c];
                        if (b) {
                            var f = $grid.jqGrid('getRowData', e);
                            'string' == typeof f.invSkus && (f.invSkus = $.parseJSON(f.invSkus)),

                                addList[e] = f
                        } else addList[e] && delete addList[e]
                    }
                },
                gridComplete: function () {
                    if (!isSingle) for (_item in addList) {
                        var a = $('#' + addList[_item].id);
                        !a.length && a.find('input:checkbox') [0].checked && $grid.jqGrid('setSelection', _item, !1)
                    }
                }
            })
        },
		
		//重新载入数据
        reloadData: function (a) {
            addList = {},
			$('#grid').jqGrid('setGridParam', {
				url: SITE_URL + '?ctl=Goods&met=getShopGoodsBaseList&typ=json',
				datatype: 'json',
				postData: a
			}).trigger('reloadGrid')
        },
		
		//页面事件
        addEvent: function () {
            var a = this;
            $('.grid-wrap').on('click', '.ui-icon-search', function (a) {
                a.preventDefault();
                var b = $(this).parent().data('id');
                Business.forSearch(b, '')
            }),
			//搜索
			$('#search').click(function () {
				queryConditions.catId = a.catId,
				queryConditions.skey = '请输入商品编号/名称' === a.$_matchCon.val() ? '' : a.$_matchCon.val(),
				a.reloadData(queryConditions)
			}),
			//刷新
			$('#refresh').click(function () {
				a.reloadData(queryConditions)
			})
        }
    };
	
THISPAGE.init();
