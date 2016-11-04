<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <link rel="stylesheet" type="text/css" media="screen and (min-width: 1200px)" href="<?= $this->view->css ?>/exchange-max.css" />
    <link rel="stylesheet" type="text/css" media="screen and (max-width: 1200px)" href="<?= $this->view->css ?>/exchange-min.css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css" />
    <div class="tabmenu">
        <ul>
            <li class="active bbc_seller_bg"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Return&met=<?php if ($data['order_goods_id'])
                {
                    echo "goodsReturn\">"._('退货管理');
                }
                else
                {
                    echo "orderReturn\">"._('退货管理');
                } ?></a></li>
            <li class=" active"><a href="javascript:void(0);"><?=_('查看详情')?></a></li>
        </ul>

    </div>


    <div class="ncsc-flow-layout">
        <div class="ncsc-flow-container">
            <div class="title">
                <h3><?=$data['text']?><?=_('服务')?></h3>
            </div>
            <div id="saleRefundReturn">
                <div class="ncsc-flow-step">
                    <dl class="step-first current">
                        <dt><?=_('买家申请')?><?=$data['text']?></dt>
                        <dd class="bg"></dd>
                    </dl>
                    <dl <?php if ($data['return_state'] >= 1 ||$data['return_state'] == 3)
                    {
                        echo 'class="current"';
                    } ?>>
                        <dt><?=_('商家处理')?><?=$data['text']?><?=_('申请')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                    <?php if($data['return_goods_return']){?>
                    <dl <?php if ($data['return_state'] >= 4 && $data['return_state'] != 3)
                    {
                        echo 'class="current"';
                    } ?>>
                        <dt><?=_('买家')?><?=$data['text']?><?=_('给商家')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                    <?php } ?>
                    <dl <?php if ($data['return_state'] >= 5 && $data['return_state'] != 3)
                    {
                        echo 'class="current"';
                    } ?>>
                        <dt><?php if($data['return_goods_return']){echo _("确认收货，");}?><?=_('平台审核')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                </div>
                <div class="ncsc-form-default">
                    <h3><?=_('买家')?><?=$data['text']?><?=_('申请')?></h3>
                    <dl>
                        <dt><?=$data['text']?><?=_('编号：')?></dt>
                        <dd><?= $data['return_code'] ?></dd>
                    </dl>
                    <dl>
                        <dt><?=_('申请人（买家）：')?></dt>
                        <dd><?= $data['buyer_user_account'] ?></dd>
                    </dl>
                    <dl>
                        <dt><?=$data['text']?><?=_('原因：')?></dt>
                        <dd><?= $data['return_message'] ?></dd>
                    </dl>
                    <dl>
                        <dt><?=$data['text']?><?=_('金额：')?></dt>
                        <dd><?= format_money($data['return_cash']) ?></dd>
                    </dl>
                    <dl>
                        <dt><?=$data['text']?><?=_('佣金金额：')?></dt>
                        <dd><?= format_money($data['return_commision_fee']) ?></dd>
                    </dl>
                    <?php if ($data['order_goods_id'])
                    { ?>
                    <dl>
                        <dt><?=$data['text']?><?=_('数量：')?></dt>
                        <dd><?= $data['order_goods_num'] ?></dd>
                    </dl>
                    <?php } ?>
                    <?php if ($data['return_state_etext'] == "seller_pass")
                    { ?>
                        <h3><?=_('处理结果')?></h3>
                        <dl>
                            <dt><?=_('处理状态：')?></dt>
                            <dd><?=_('卖家已同意')?></dd>
                        </dl>
                        <dl>
                            <dt><?=_('商家备注：')?></dt>
                            <dd><?= $data['return_shop_message'] ?></dd>
                        </dl>
                        <h3><?=_('确认收货')?></h3>
                        <dl>
                            <form id="form2" action="#" method="post">
                                <input type="hidden" name="order_return_id" id="order_return_id"
                                       value="<?= $data['order_return_id'] ?>">
                                <dl class="foot">
                                    <dt></dt>
                                    <dd>
                                        <input id="handle_goods" type="button" class="button button_red bbc_seller_submit_btns" value="<?=_('已收到货')?>">
                                    </dd>
                                </dl>
                            </form>
                        </dl>
                        <?php }
                        elseif ($data['return_state_etext'] == "seller_goods")
                        { ?>
                        <h3><?=_('处理结果')?></h3>
                        <dl>
                            <dt><?=_('处理状态：')?></dt>
                            <dd><?=_('卖家已同意')?></dd>
                        </dl>
                        <dl>
                            <dt><?=_('商家备注：')?></dt>
                            <dd><?= $data['return_shop_message'] ?></dd>
                        </dl>
                    <?php } ?>
                        <?php if ($data['return_state_etext'] == "plat_pass")
                    { ?>
                        <h3><?=_('处理结果')?></h3>
                        <dl>
                            <dt><?=_('处理状态：')?></dt>
                            <dd><?=$data['text']?><?=_('成功')?></dd>
                        </dl>
                        <dl>
                            <dt><?=_('商家备注：')?></dt>
                            <dd><?= $data['return_shop_message'] ?></dd>
                        </dl>
                    <?php }
                    elseif ($data['return_state_etext'] == "seller_unpass")
                    { ?>
                        <h3><?=_('处理结果')?></h3>
                        <dl>
                            <dt><?=_('处理状态：')?></dt>
                            <dd><?=_('卖家不同意')?></dd>
                        </dl>
                        <dl>
                            <dt><?=_('商家备注：')?></dt>
                            <dd><?= $data['return_shop_message'] ?></dd>
                        </dl>
                    <?php }
                    elseif ($data['return_state_etext'] == "wait_pass")
                    { ?>
                        <h3><?=_('处理结果')?></h3>
                        <dl>
                            <form id="form" action="#" method="post">
                                <input type="hidden" name="order_return_id" id="order_return_id"
                                       value="<?= $data['order_return_id'] ?>">
                                <dl>
                                    <dt><?=_('处理')?></dt>
                                    <dd>
                                        <textarea name="return_shop_message" id="return_shop_message" class="textarea_text"></textarea>
                                    </dd>
                                </dl>
                                <dl class="foot">
                                    <dt></dt>
                                    <dd>
                                        <input id="handle_submit" type="button" class="button bbc_seller_submit_btns mr10" value="<?=_('同意')?>">
                                        <input id="handle_close" type="button" class="button bbc_seller_submit_btns" value="<?=_('不同意')?>"/>
                                    </dd>
                                </dl>
                            </form>
                        </dl>
                    <?php }?>
                </div>
            </div>
        </div>

        <div class="ncsc-flow-item">
            <div class="title"><?=_('相关商品交易信息')?></div>

            <?php if ($data['order_goods_id'])
            { ?>
                <div class="item-goods">
                    <dl>
                        <dt>
                        <div class="ncsc-goods-thumb-mini"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= $data['goods']['goods_id'] ?>"> <img
                                    src="<?= $data['order_goods_pic'] ?>"></a></div>
                        </dt>
                        <dd><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= $data['goods']['goods_id'] ?>"><?= $data['order_goods_name'] ?></a>
                            <?= format_money($data['order_goods_price']) ?> * <?= $data['order_goods_num'] ?> <font
                                color="#AAA">(<?=_('数量')?>)</font> <span></span></dd>
                    </dl>
                </div>
            <?php } ?>
            <div class="item-order">
                <dl>
                    <dt><?=_('订单总额：')?></dt>
                    <dd><strong><?= format_money($data['order_amount']) ?> (<?=_('退款：')?><?= format_money($data['return_limit']) ?>) </strong></dd>
                </dl>
                <dl class="line">
                    <dt><?=_('订单编号：')?></dt>
                    <dd><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order_number'] ?>"><?= $data['order_number'] ?></a>
                        <a href="javascript:void(0);" class="a"><?=_('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                            <div class="more"><span class="arrow"></span>
                                <ul>
                                    <li><?=_('付款单号：')?><span><?= $data['order']['payment_number'] ?></span></li>
                                    <li><?=_('支付方式：')?><span><?= $data['order']['payment_name'] ?></span></li>
                                    <li><?=_('下单时间：')?><span><?= $data['order']['order_create_time'] ?></span></li>
                                    <li><?=_('付款时间：')?><span><?= $data['order']['payment_time'] ?></span></li>
                                </ul>
                            </div>
                        </a></dd>
                </dl>
                <dl class="line">
                    <dt><?=_('收货人：')?></dt>
                    <dd><?= $data['order']['order_receiver_name'] ?><a href="javascript:void(0);" class="a"><?=_('更多')?><i class="iconfont icon-iconjiantouxia"></i>
                        <div class="more"><span class="arrow"></span>
                            <ul>
                                <li><?=_('收货地址：')?><span><?= $data['order']['order_receiver_address'] ?></span></li>
                                <li><?=_('联系电话：')?><span><?= $data['order']['order_receiver_contact'] ?></span></li>
                            </ul>
                        </div>
                        </a>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    <link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script>
        var order_return_id = $('#order_return_id').val();
        $(document).ready(function ()
        {
            var ajax_url;
            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                fields: {return_shop_message: "required"},
                valid: function (form)
                {
                    //表单验证通过，提交表单
                    $.ajax({
                        url: ajax_url,
                        data: $("#form").serialize(),
                        success: function (a)
                        {
                            if (a.status == 200)
                            {
                                location.href = "./index.php?ctl=Seller_Service_Return&met=<?php if ($data['order_goods_id'])
                                    {
                                        echo "goodsReturn";
                                    }
                                    else
                                    {
                                        echo "orderReturn";
                                    }?>&act=detail&id=" + order_return_id;
                            }
                            else
                            {
                                Public.tips.error('<?=_('操作失败！')?>');
                            }
                        }
                    });
                }

            }).on("click", "#handle_submit", function (e)
            {
                ajax_url = SITE_URL + '?ctl=Seller_Service_Return&met=agreeReturn&typ=json';
                $(e.delegateTarget).trigger("validate");
            }).on("click", "#handle_close", function (e)
            {
                ajax_url = SITE_URL + '?ctl=Seller_Service_Return&met=closeReturn&typ=json';
                $(e.delegateTarget).trigger("validate");
            });

            $('#form2').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                fields: {},
                valid: function (form)
                {
                    //表单验证通过，提交表单
                    $.ajax({
                        url: SITE_URL + '?ctl=Seller_Service_Return&met=agreeGoods&typ=json',
                        data: $("#form2").serialize(),
                        success: function (a)
                        {
                            if (a.status == 200)
                            {
                                location.href = "./index.php?ctl=Seller_Service_Return&met=<?php if ($data['order_goods_id'])
                                    {
                                        echo "goodsReturn";
                                    }
                                    else
                                    {
                                        echo "orderReturn";
                                    }?>&act=detail&id=" + order_return_id;
                            }
                            else
                            {
                                Public.tips.error('<?=_('操作失败！')?>');
                            }
                        }
                    });
                }

            }).on("click", "#handle_goods", function (e)
            {
                $(e.delegateTarget).trigger("validate");
            });
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>