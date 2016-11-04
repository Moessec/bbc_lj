<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>


    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th class="tl"><?=_('结算单号')?></th>
        <th width="200"><?=_('起止时间')?></th>
        <th width="150"><?=_('本期应收')?></th>
        <th width="120"><?=_('结算状态')?></th>
        <th width="120"><?=_('付款日期')?></th>
        <th width="120"><?=_('操作')?></th>
    </tr>
    <?php if($list['items']){
        foreach ($list['items'] as $key => $val) {?>
    <tr>
        <td class="tl"><?=($val['os_id'])?></td>
        <td><p><?=($val['os_start_date'])?></p> | <p><?=($val['os_end_date'])?></p></td>
        <td><?=($val['os_amount'])?></td>
        <td><?=($val['os_state_text'])?></td>
        <td><?=($val['os_pay_date'])?></td>
        <td class="operate">
        <span>
        <a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Order_Settlement&met=virtual&op=show&id=<?=($val['os_id'])?>"><i class="iconfont icon-btnclassify2"></i><?=_('查看')?></a>
        </span>
        </td>
    </tr>
    <?php }}else{?>
    <tr>
        <td colspan="99">
            <img src="<?= $this->view->img ?>/ico_none.png"/>
            <div class="nocont"><?=_('暂无符合条件的数据记录')?></div>
        </td>
    </tr>
    <?php }?>
</table>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>