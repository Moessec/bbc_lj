<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <dl class="bill-hd fn-clear">
        <dt><?=_('本期结算')?></dt>
        <?php if($data['os_state']==1):?>
		<dt><?=_('一旦确认将无法恢复，系统将自动进入结算环节，请确认系统出账单位计算无误。')?></dt>
        <dd>
        	<!--<a class="button button_red" href="javaScript:void(0);">本期结算无误，我要确认</a>-->
        	<span class="con"><a data-param="{'ctl':'Seller_Order_Settlement','met':'confirmSettlement','id':'<?=$data['os_id']; ?>'}" class="button button_red" href="javascript:void(0)"><?=_('本期结算无误，我要确认')?></a></span>
        </dd>
        <?php endif;?>
        <dd><?=_('结算单号')?><?=($data['os_id'])?></dd>
        <dd><?=_('起止时间')?><?=($data['os_start_date'])?>  <?=_('至')?>  <?=($data['os_end_date'])?></dd>
        <dd><?=_('出账日期')?><?=($data['os_datetime'])?></dd>
        <dd><?=_('平台应付金额')?><?=($data['os_amount'])?> = <?=($data['os_order_amount'])?> <?=_('(订单金额) - ')?><?=($data['os_commis_amount'])?> <?=_('(佣金金额) - ')?><?=($data['os_order_return_amount'])?> <?=_('(退单金额) + ')?><?=($data['os_commis_return_amount'])?> <?=_('(退还佣金) - ')?><?=($data['os_shop_cost_amount'])?> <?=_('(店铺消费)')?></dd>
        <dd><?=_('结算状态：')?><?=($data['os_state_text'])?></dd>
        <?php if($data['os_pay_content']):?>
        <dd><?=_('备注：')?><?=($data['os_pay_content'])?></dd>
        <?php endif;?>
    </dl>
	<div class="tabmenu">
		<ul>
        	<li class="<?php if($type == 'active'): ?>active<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=active"><?=_('订单列表')?></a></li>
        	<li class="<?php if($type == 'refund'): ?>active<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=refund"><?=_('退款订单')?></a></li>
        	<li class="<?php if($type == 'cost'): ?>active<?php endif;?>"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=normal&op=show&id=<?=($id)?>&type=cost"><?=_('促销活动')?></a></li>
        </ul>
    </div>
	<?php if($type == 'returd'):?>
	<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<th width="120"><?=_('订单编号')?></th>
            <th width="10"></th>
            <th width="160"><?=_('订单总额')?></th>
            <th width="160"><?=_('运费')?></th>
            <th width="160"><?=_('佣金')?></th>
            <th width="200"><?=_('下单时间')?></th>
            <th width="200"><?=_('成交时间')?></th>
            <th width="200"><?=_('买家')?></th>
            <th width="100"><?=_('操作')?></th>
        </tr>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key=> $val): ?>
        <tr>
        	<td><?=($val['order_id'])?></td>
            <td></td>
            <td><?=($val['order_payment_amount'])?></td>
            <td><?=($val['order_shipping_fee'])?></td>
            <td><?=($val['order_commission_fee'])?></td>
            <td><?=($val['order_create_time'])?></td>
            <td><?=($val['order_finished_time'])?></td>
            <td><?=($val['buyer_user_name'])?></td>
            <td><span><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=<?=$val['order_id'] ?>"><i class="iconfont icon-btnclassify2"></i><?=_('查看')?></a></span></td>
        </tr>
        <?php endforeach; ?>
		<?php else: ?>
        <tr>
            <td colspan="99">
                <img src="<?= $this->view->img ?>/ico_none.png"/>
                <div class="nocont"><?=_('暂无符合条件的数据记录')?></div>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($page_nav):?>
        <tr>
            <td class="toolBar" colspan="99">
            <div class="page"><?=($page_nav)?></div>
            </td>
        </tr>
        <?php endif;?>
    </table>

    <?php elseif($type == 'cost'): ?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th width="120" class="tl"><?=_('消费金额')?></th>
            <th class="tl"><?=_('消费内容')?></th>
            <th width="200"><?=_('时间')?></th>
        </tr>
        </thead>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key => $val): ?>
        <tr>
            <td class="tl"><?=($val['cost_price'])?></td>
            <td class="tl"><?=($val['cost_desc'])?></td>
            <td><?=($val['cost_time'])?></td>
        </tr>
        <?php endforeach; ?>
		<?php else : ?>
        <tr>
            <td colspan="99">
                <img src="<?= $this->view->img ?>/ico_none.png"/>
                <div class="nocont"><?=_('没有符合条件的记录')?></div>
            </td>
        </tr>
		<?php endif; ?>
        </tbody>
        <?php if($page_nav): ?>
        <tfoot>
        <tr>
            <td colspan="99"><div class="page"><?=($page_nav)?></div></td>
        </tr>
        </tfoot>
		<?php endif; ?>
        
    </table>
    <?php else:?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<th width="120"><?=_('订单编号')?></th>
            <th width="10"></th>
            <th width="160"><?=_('订单总额')?></th>
            <th width="160"><?=_('运费')?></th>
            <th width="160"><?=_('佣金')?></th>
            <th width="200"><?=_('下单时间')?></th>
            <th width="200"><?=_('成交时间')?></th>
            <th width="200"><?=_('买家')?></th>
            <th width="100"><?=_('操作')?></th>
        </tr>
		<?php if($list['items']): ?>
        <?php foreach($list['items'] as $key=> $val): ?>
        <tr>
        	<td><?=($val['order_id'])?></td>
            <td></td>
            <td><?=($val['order_payment_amount'])?></td>
            <td><?=($val['order_shipping_fee'])?></td>
            <td><?=($val['order_commission_fee'])?></td>
            <td><?=($val['order_create_time'])?></td>
            <td><?=($val['order_finished_time'])?></td>
            <td><?=($val['buyer_user_name'])?></td>
            <td><span><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Trade_Order&met=physicalInfo&o&typ=e&order_id=<?=$val['order_id'] ?>"><i class="iconfont icon-btnclassify2"></i><?=_('查看')?></a></span></td>
        </tr>
        <?php endforeach; ?>
		<?php else: ?>
        <tr>
            <td colspan="99">
                <img src="<?= $this->view->img ?>/ico_none.png"/>
                <div class="nocont"><?=_('暂无符合条件的数据记录')?></div>
            </td>
        </tr>
        <?php endif; ?>
        <?php if($page_nav):?>
        <tr>
            <td class="toolBar" colspan="99">
            <div class="page"><?=($page_nav)?></div>
            </td>
        </tr>
        <?php endif;?>
    </table>
    <?php endif; ?>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>