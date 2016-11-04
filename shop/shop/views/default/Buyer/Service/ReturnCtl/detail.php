<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>

<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<div class="aright">
    <div class="member_infor_content">
        <div class="div_head  tabmenu clearfix">
            <ul class="tab pngFix clearfix">
                <li class="active">
                    <a><?=$data['text']?><?=_('管理')?></a>
                </li>
            </ul>
        </div>
    <div class="ncm-flow-layout" id="ncmComplainFlow">
        <div class="ncm-flow-container">

            <div class="ncm-flow-step" style="text-align: center;">
                <dl id="state_new" class="step-first current1">
                    <dt><?=_('买家申请')?><?=$data['text']?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl id="state_appeal" <?php if ($data['return_state'] >= 1 ||$data['return_state'] == 3)
                {
                    echo 'class="current1"';
                } ?>>
                    <dt><?=_('商家处理')?><?=$data['text']?><?=_('申请')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <?php if($data['return_goods_return']){?>
                <dl id="state_talk" <?php if ($data['return_state'] >= 4 && $data['return_state'] != 3)
                {
                    echo 'class="current1"';
                } ?>>
                    <dt><?=_('买家')?><?=$data['text']?><?=_('给商家')?></dt>
                    <dd class="bg"></dd>
                </dl>
                 <?php } ?>
                <dl id="state_handle" <?php if ($data['return_state'] >= 5 && $data['return_state'] != 3)
                {
                    echo 'class="current1"';
                } ?>>
                    <dt><?php if($data['return_goods_return']){echo _("确认收货，");}?><?=_('平台审核')?></dt>
                    <dd class="bg"></dd>
                </dl>
            </div>
            <div class="ncm-default-form">
                <h3><?=_('买家')?><?=$data['text']?><?=_('申请')?></h3>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=_('编号：')?></dt>
                    <dd><?= $data['return_code'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=_('申请人（买家）：')?></dt>
                    <dd><?= $data['buyer_user_account'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=_('原因：')?></dt>
                    <dd><?= $data['return_message'] ?></dd>
                    </dl>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=_('金额：')?></dt>
                    <dd><?= format_money($data['return_cash']) ?></dd>
<?php if ($data['order_goods_id'])
{ ?>
    </dl>
                <dl class="return_dl">
                    <dt><?=$data['text']?><?=_('数量：')?></dt>
                    <dd><?= $data['order_goods_num'] ?></dd>
<?php } ?>
                </dl>

                <?php if ($data['return_state_etext'] == "seller_pass")
                { ?>
                    <h3><?=_('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=_('处理状态：')?></dt>
                        <dd><?=_('卖家已同意')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=_('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                    <?php if ($data['return_state_etext'] == "plat_pass")
                { ?>
                    <h3><?=_('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=_('处理状态：')?></dt>
                        <dd><?=$data['text']?><?=_('成功')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=_('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                <?php } ?>
                <?php }
                elseif ($data['return_state_etext'] == "seller_unpass")
                { ?>
                    <h3><?=_('处理结果')?></h3>
                    <dl class="return_dl">
                        <dt><?=_('处理状态：')?></dt>
                        <dd><?=_('卖家不同意')?></dd>
                        </dl>
                    <dl class="return_dl">
                        <dt><?=_('商家备注：')?></dt>
                        <dd><?= $data['return_shop_message'] ?></dd>
                    </dl>
                <?php }?>
            </div>
        </div>
        <div class="ncm-flow-item">
            <div class="title"><?=_('相关商品交易')?></div>
            <?php if ($data['order_goods_id'])
            { ?>
                <div class="item-goods">
                    <dl>
                        <dt>
                        <div class="ncm-goods-thumb-mini"><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?= $data['goods']['goods_id'] ?>"> <img
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
                    <dd><strong><?= format_money($data['order_amount']) ?></strong> (<?=_('退款：')?><?= format_money($data['return_limit']) ?>) </dd>
                </dl>
                <dl class="line">
                    <dt><?=_('订单编号：')?></dt>
                    <dd> <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order_number'] ?>" target="_blank"><?= $data['order_number'] ?> </a><a href="javascript:void(0);" class="a"><?=_('更多')?><i class="iconfont icon-jiantouxiangxia"></i>
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
                    <dd><?= $data['order']['order_receiver_name'] ?><a href="javascript:void(0);" class="a"><?=_('更多')?><i class="iconfont icon-jiantouxiangxia"></i>
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
</div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>