<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>

<div class="search fn-clear">
    <form id="search_form" method="get">
        <input type="hidden" name="ctl" value="Seller_Analysis_Operation"/>
        <input type="hidden" name="met" value="index"/>
        <a class="button refresh" href="index.php?ctl=Seller_Analysis_Operation&met=index&typ=e"><i class="iconfont">
                &#xe649;</i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont">&#xe603;</i>搜索</a>
        <select name="stype" id="stype">
            <option value="month">按月统计</option>
            <option value="week">按周统计</option>
        </select>
        <select name="year" id="year">
            <?= $option['year'] ?>
        </select>
        <select name="month" id="month">
            <?= $option['month'] ?>
        </select>
        <select name="week" id="week" style="display: none;">
        </select>
    </form>
    <script type="text/javascript">
        $("#month").change(function ()
        {
            var stype = $("#stype").val();
            if (stype == "week")
            {
                var year = $("#year").val();
                var month = $("#month").val();
                $.post(SITE_URL + "?ctl=Seller_Analysis_Goods&met=getWeek", {year: year, month: month}, function (e)
                {
                    $("#week").html(e);
                    $("#week").css("display", "inline");
                })
            }
        });
        $("#stype").change(function ()
        {
            var stype = $(this).val();
            if (stype == "month")
            {
                $("#week").css("display", "none");
            }
        });
        $(".search").on("click", "a.button", function ()
        {
            $("#search_form").submit();
        });
    </script>
</div>
<div class="tabmenu">
    <ul class="tab clearfix">
        <li class="ui-tabs-selected"><a href="javascript:void(0);" data-id="1">下单会员数</a></li>
        <li><a href="javascript:void(0);" data-id="2">下单金额</a></li>
        <li><a href="javascript:void(0);" data-id="3">下单量</a></li>
    </ul>
</div>

<div class="main-content" id="mainContent">
    <div id="container" style="height: 400px;"></div>
    <div class="h30 cb">&nbsp;</div>
</div>

<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/echarts/echarts.js"></script>
<script>
    $(function ()
    {
        require.config({
            paths: {
                echarts: '<?=$this->view->js_com?>/plugins/echarts'
            }
        });

        option =
        {
            "1": {
                title: {
                    text: '下单会员数',
                    subtext: '',
                    x: 'center'
                }
                ,
                tooltip: {
                    trigger: 'item'
                }
                ,
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['下单会员数']
                }
                ,
                dataRange: {
                    min: 0,
                    max: 2500,
                    x: 'left',
                    y: 'bottom',
                    text: ['高', '低'],           // 文本，默认为数值文本
                    calculable: true
                }
                ,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    feature: {
                        mark: {
                            show: true
                        }
                        ,
                        dataView: {
                            show: true, readOnly: false
                        }
                        ,
                        restore: {
                            show: true
                        }
                        ,
                        saveAsImage: {
                            show: true
                        }
                    }
                }
                ,
                roamController: {
                    show: false,
                    x: 'right',
                    mapTypeControl: {
                        'china': true
                    }
                }
                ,
                series: [
                    {
                        name: '下单会员数',
                        type: 'map',
                        mapType: 'china',
                        roam: false,
                        itemStyle: {
                            normal: {label: {show: true}},
                            emphasis: {label: {show: true}}
                        },
                        data:<?=$data_order_user?>
                    }
                ]
            }
            ,
            "2": {
                title: {
                    text: '下单金额',
                    subtext: '',
                    x: 'center'
                }
                ,
                tooltip: {
                    trigger: 'item'
                }
                ,
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['下单金额']
                }
                ,
                dataRange: {
                    min: 0,
                    max: 2500,
                    x: 'left',
                    y: 'bottom',
                    text: ['高', '低'],           // 文本，默认为数值文本
                    calculable: true
                }
                ,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    feature: {
                        mark: {
                            show: true
                        }
                        ,
                        dataView: {
                            show: true, readOnly: false
                        }
                        ,
                        restore: {
                            show: true
                        }
                        ,
                        saveAsImage: {
                            show: true
                        }
                    }
                }
                ,
                roamController: {
                    show: false,
                    x: 'right',
                    mapTypeControl: {
                        'china': true
                    }
                }
                ,
                series: [
                    {
                        name: '下单金额',
                        type: 'map',
                        mapType: 'china',
                        roam: false,
                        itemStyle: {
                            normal: {label: {show: true}},
                            emphasis: {label: {show: true}}
                        },
                        data:<?=$data_order_cash?>
                    }
                ]
            }
            ,
            "3": {
                title: {
                    text: '下单量',
                    subtext: '',
                    x: 'center'
                }
                ,
                tooltip: {
                    trigger: 'item'
                }
                ,
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: ['下单量']
                }
                ,
                dataRange: {
                    min: 0,
                    max: 2500,
                    x: 'left',
                    y: 'bottom',
                    text: ['高', '低'],           // 文本，默认为数值文本
                    calculable: true
                }
                ,
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    feature: {
                        mark: {
                            show: true
                        }
                        ,
                        dataView: {
                            show: true, readOnly: false
                        }
                        ,
                        restore: {
                            show: true
                        }
                        ,
                        saveAsImage: {
                            show: true
                        }
                    }
                }
                ,
                roamController: {
                    show: false,
                    x: 'right',
                    mapTypeControl: {
                        'china': true
                    }
                }
                ,
                series: [
                    {
                        name: '下单量',
                        type: 'map',
                        mapType: 'china',
                        roam: false,
                        itemStyle: {
                            normal: {label: {show: true}},
                            emphasis: {label: {show: true}}
                        },
                        data:<?=$data_order_num?>
                    }
                ]
            }
        }
        require(
            [
                'echarts',
                'echarts/chart/map' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec)
            {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('container'));
                myChart.setOption(option[1]);
            }
        );

        $(".tab li a").click(function ()
        {
            $(".tab li").removeClass("ui-tabs-selected");
            $(this).parent("li").addClass("ui-tabs-selected");
            var id = $(this).attr("data-id");
            require(
                [
                    'echarts',
                    'echarts/chart/map' // 使用柱状图就加载bar模块，按需加载
                ],
                function (ec)
                {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('container'));
                    myChart.setOption(option[id]);
                }
            );
        })
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



