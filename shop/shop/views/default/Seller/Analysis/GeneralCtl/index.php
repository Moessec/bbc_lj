<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>

<div id="mainContent">
    <div class="alert1 alert mt10" style="clear:both;">
        <ul class="mt5">
            <li>1、符合以下任何一种条件的订单即为有效订单：1）采用在线支付方式支付并且已付款；2）采用货到付款方式支付并且交易已完成</li>
            <li>2、以下关于订单和订单商品近30天统计数据的依据为：从昨天开始最近30天的有效订单</li>
        </ul>
    </div>
    <div class="alert alert-info mt10" style="clear:both;">
        <ul class="mt5">
            <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺从昨天开始最近30天有效订单的总金额" class="tip icon-question-sign"></i>
    		近30天下单金额：<strong><?= format_money($total['order_cash']) ?></strong>
    	</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺从昨天开始最近30天有效订单的会员总数" class="tip icon-question-sign"></i>
			近30天下单会员数：<strong><?= $total['order_user_num'] ?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺从昨天开始最近30天有效订单的总订单数" class="tip icon-question-sign"></i>
			近30天下单量：<strong><?= $total['order_num'] ?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺从昨天开始最近30天有效订单的总商品数量" class="tip icon-question-sign"></i>
			近30天下单商品数：<strong><?= $total['order_goods_num'] ?></strong>
		</span>
            </li>
            <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺从昨天开始最近30天有效订单的平均每个订单的交易金额" class="tip icon-question-sign"></i>
    		平均客单价：<strong><?= format_money(@$total['general_user_cash']) ?></strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺从昨天开始最近30天有效订单商品的平均每个商品的成交价格" class="tip icon-question-sign"></i>
    		平均价格：<strong><?= format_money(@$total['general_cash']) ?></strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺所有商品的总收藏次数" class="tip icon-question-sign"></i>
    		商品收藏量：<strong><?= $total['goods_favor_num'] ?></strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺拥有商品的总数（仅计算商品种类，不统计库存）" class="tip icon-question-sign"></i>
    		商品总数：<strong><?= $total['goods_num'] ?></strong>
    	</span>
            </li>
            <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺总收藏次数" class="tip icon-question-sign"></i>
    		店铺收藏量：<strong><?= $total['shop_favor_num'] ?></strong>
    	</span>
            </li>
        </ul>
        <div style="clear:both;"></div>
    </div>

    <div id="container" style="height: 400px;"></div>

    <div class="fl mr50" style="width: 100%;">
        <div class="alert alert-info" style="margin-bottom:0px;"><strong>建议推广商品</strong>
            &nbsp;<i title="统计店铺从昨天开始7日内热销商品前30名，建议推广以下商品，提升推广回报率" class="tip icon-question-sign"></i>
        </div>
        <table class="ncsc-default-table">
            <thead>
            <tr class="sortbar-array">
                <th class="align-center">序号</th>
                <th class="align-center">商品名称</th>
                <th class="align-center">销量</th>
            </tr>
            </thead>
            <tbody id="datatable">
            <?php if (empty($goods_list))
            { ?>
                <tr>
                    <td colspan="20" class="norecord">
                        <div class="warning-option"><i class="icon-warning-sign"></i><span>暂无符合条件的数据记录</span></div>
                    </td>
                </tr>
            <?php }
            else
            {
                foreach ($goods_list as $k => $v)
                {
                    ?>
                    <tr>
                        <td class="align-center"><?= ($k + 1) ?></td>
                        <td class="align-center"><?= $v['goods_name'] ?></td>
                        <td class="align-center"><?= $v['order_num'] ?></td>
                    </tr>
                <?php }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="h30 cb">&nbsp;</div>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/echarts/echarts.js"></script>
    <script>
        $(function ()
        {
            require.config({
                paths: {
                    echarts: '<?=$this->view->js_com?>/plugins/echarts'
                }
            });
            require(
                [
                    'echarts',
                    'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
                ],
                function (ec) {
                    // 基于准备好的dom，初始化echarts图表
                    var myChart = ec.init(document.getElementById('container'));

                    var option = {
                        tooltip: {
                            show: true
                        },
                        legend: {
                            data:['下单金额']
                        },
                        xAxis : [
                            {
                                type : 'category',
                                data : <?=$data['line']?>
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                "name":"下单金额",
                                "type":"line",
                                "data":<?=$data['num']?>
                            }
                        ]
                    };

                    // 为echarts对象加载数据
                    myChart.setOption(option);
                }
            );
        });
    </script>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>



