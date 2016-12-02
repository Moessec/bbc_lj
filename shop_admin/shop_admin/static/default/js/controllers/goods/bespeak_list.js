$(function() {
    var searchFlag = false;
    var filterClassCombo, catorageCombo;
    var defaultPage = Public.getDefaultPage();
    var handle = {

        //删除
        del: function(row_ids) {
            $.dialog.confirm(_('删除的将不能恢复，请确认是否删除？'), function() {
                Public.ajaxPost(SITE_URL + '?ctl=Goods_Bespeak&met=remove&typ=json', {
                    bespeak_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.bespeak_id || [];
                        if (row_ids.split(',').length === id_arr.length) {
                            parent.Public.tips({
                                content: sprintf(_('成功删除 %d 个！'), id_arr.length)
                            });
                        } else {
                            parent.Public.tips({
                                type: 2,
                                content: data.data.msg
                            });
                        }
                        for (var i = 0, len = id_arr.length; i < len; i++) {
                            $('#grid').jqGrid('setSelection', id_arr[i]);
                            $('#grid').jqGrid('delRowData', id_arr[i]);
                        };
                    } else {
                        parent.Public.tips({
                            type: 1,
                            content: _('删除失败！') + data.msg
                        });
                    }
                });
            });
        },
        //修改状态
        setStatus: function(id, is_enable) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Bespeak&met=disable&typ=json', {
                bespeak_id: id,
                disable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'enable', is_enable);
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: _('状态修改失败！') + data.msg
                    });
                }
            });
        },

        setState: function(id, is_enable) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Bespeak&met=disablestate&typ=json', {
                bespeak_id: id,
                disable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: _('状态修改成功！')
                    });
                    $('#grid').jqGrid('setCell', id, 'enable', is_enable);
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: _('状态修改失败！') + data.msg
                    });
                }
            });
        },

        callback: function(data, oper, dialogWin) {
            var gridData = $("#grid").data('gridData');
            if (!gridData) {
                gridData = {};
                $("#grid").data('gridData', gridData);
            }

            //计算期初余额字段difMoney
            //data.difMoney = data.amount - data.periodMoney;

            gridData[data.bespeak_id] = data;

            if (oper == "edit") {
                $("#grid").jqGrid('setRowData', data.bespeak_id, data);
                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.bespeak_id, data, 'first');
                dialogWin && dialogWin.api.close();
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFormatter: function(val, opt, row) {


            if (row.bespeak_state == '待处理')
            {
                var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="预约处理"></span><span class="ui-icon ui-icon-search" title="查看详情"></span></div>';
            }
            else if(row.bespeak_state == '预约正在处理')
            {
                var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon set-status" title="处理完成"></span><span class="ui-icon ui-icon-trash ui-icon-disabled" title="删除"></span></div>';
            }


            return html_con;
        },

        statusFormatter: function(val, opt, row) {
            var text = val == 0 ? _('已禁用') : _('已启用');
            var cls = val == 0 ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },

        catNameFormatter:function(val, opt, row) {
            for (i in defaultPage.SYSTEM.goodsCatInfo)
            {
                if (val == defaultPage.SYSTEM.goodsCatInfo[i].id)
                {
                    return defaultPage.SYSTEM.goodsCatInfo[i].cat_name;
                }

            }

            return '';

        }
    };

    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};
        catorageCombo = Business.categoryCombo($('#catorage'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: _('选择类别')
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'customertype');
    };

    function initGrid() {
        var grid_row = Public.setGrid();
        var colModel = [{
            "name": "operate",
            "label": "操作",
            "width": 80,
            "sortable": false,
            "search": false,
            "resizable": false,
            "fixed": true,
            "align": "center",
            "title": false,
            "formatter": handle.operFormatter
        },{
            "name": "bespeak_id",
            "index": "bespeak_id",
            "label": "预约编号",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60
        }, {
            "name": "true_name",
            "index": "true_name",
            "label": "预约会员",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 60
        }, {
            "name": "bespeak_title",
            "index": "bespeak_title",
            "label": "预约事务",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": true,
            "fixed": true,
            "width":100
        },  {
            "name": "bespeak_state",
            "index": "bespeak_state",
            "label": "预约状态",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": true,
            "width": 30,
        }, {
            "name": "usercontact",
            "index": "usercontact",
            "label": "联系方式",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width":100,
        }];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Goods_Bespeak&met=bespeaklists&typ=json',
            datatype: 'json',
            autowidth: true,
            shrinkToFit: true,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false, //多选
            colModel: colModel,
            pager: '#grid-pager',
            viewrecords: true,
            cmTemplate: {
                sortable: true
            },
            rowNum: 100,
            rowList: [100, 200, 500],
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "bespeak_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.bespeak_id;
                        gridData[item.bespeak_id] = item;
                    }

                    $("#grid").data('gridData', gridData);
                } else {
                    var msg = response.status === 250 ? (searchFlag ? _('没有满足条件的结果哦！') : _('没有数据哦！')) : response.msg;
                    parent.Public.tips({
                        type: 2,
                        content: msg
                    });
                }
            },
            loadError: function(xhr, status, error) {
                parent.Public.tips({
                    type: 1,
                    content: _('操作失败了哦，请检查您的网络链接！')
                });
            },
            resizeStop: function(newwidth, index) {
                //mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        });
    }

    function initEvent() {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function(e) {
            e.preventDefault();
            var skey = match_con.val() === '输入客户编号/ 名称/ 联系人/ 电话查询' ? '' : $.trim(match_con.val());
            var category_id = catorageCombo ? catorageCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: skey,
                    category_id: category_id
                }
            }).trigger("reloadGrid");

        });

        //新增
        $('#btn-add').on('click', function(e) {
            e.preventDefault();
            handle.operate('add');
        });

        //导入
        $('#btn-import').on('click', function(e) {
            e.preventDefault();
            parent.$.dialog({
                width: 560,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });

        $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var bespeak_id = $(this).parent().data("id");
              $.dialog({
                title: "查看详情",
                content: "url:"+ SITE_URL + '?ctl=Goods_Bespeak&met=getBespeakalllist&bespeak_id=' + bespeak_id,
                width: 950,
                height: $(window).height() * 0.9,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
        
        });

        //修改
        $('#grid').on('click', '.operating .ui-icon-pencil', function(e) {
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.setStatus(id);
        });

        //删除
        $('#grid').on('click', '.operating .ui-icon-trash', function(e) {
            e.preventDefault();

            if (!$(e.target).hasClass('ui-icon-disabled'))
            {
                var id = $(this).parent().data('id');
                handle.del(id + '');
            }
        });

        //设置状态
        $('#grid').on('click', '.set-status', function(e) {
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.setState(id);
        });

        //刷新,可全局
        $('#btn-refresh').click(function(e) {
            e.preventDefault();
            $("#grid").trigger("reloadGrid")
        });
    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
});