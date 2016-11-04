<?php if($data['items']){ ?>
<ul class="fn-clear">
    <?php
        foreach($data['items'] as $key=>$goods)
        {
            ?>
            <li>
                <div class="goods-image"><img src="<?=image_thumb($goods['goods_image'],140,140)?>" /></div>
                <div class="goods-name"><?=$goods['goods_name']?></div>
                <div class="goods-price"><?=_('销售价')?>：<span><?=format_money($goods['goods_price'])?></span></div>
                <div class="goods-btn">
                    <div data-type="btn_add_goods" class="button" data-storage="<?=@$goods['goods_stock']?>" data-goods-id="<?=$goods['goods_id']?>" data-common-id="<?=$goods['common_id']?>" data-goods-name="<?=$goods['goods_name']?>" data-goods-img="<?=$goods['goods_image']?>" data-goods-price="<?=$goods['goods_price']?>" data-goods-price-format ="<?=format_money($goods['goods_price'])?>" href="javascript:void(0);" class="ncbtn-mini"><i class="iconfont icon-jia"></i><?=_('选择商品')?></div>
                </div>
            </li>
        <?php 	}	?>
</ul>
<?php }else{ ?>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p>暂无符合条件的数据记录</p>
    </div>
    <?php
}
?>
<?php if($page_nav){ ?>
    <div class="goods-page fn-clear">
        <div class="mm">
            <div class="page"><?=$page_nav?></div>
        </div>
    </div>
<?php } ?>



<div id="dialog_add_discount_goods" style="display:none;">
    <input id="dialog_goods_id" type="hidden">
    <input id="dialog_common_id" type="hidden">
    <input id="dialog_input_goods_price" type="hidden">
    <input id="dialog_input_goods_price_format" type="hidden">
    <div class="eject_con">
        <div id="dialog_add_discount_goods_error" class="alert alert-error">
            <label for="dialog_xianshi_price" class="error" >
                <i class='iconfont icon-exclamation-sign'></i><?=_('折扣价格不能为空，且必须小于商品价格')?>
            </label>
        </div>
        <div class="selected-goods-info">
            <div class="goods-thumb"><img id="dialog_goods_img" src="" alt=""></div>
            <dl class="goods-info">
                <dt id="dialog_goods_name"></dt>
                <dd><?=_('销售价格')?>：<?=Web_ConfigModel::value('monetary_unit')?><strong class="red"><font id="dialog_goods_price"></font></strong></dd>
                <dd><?=_('库存')?>：<span id="dialog_goods_storage"></span> <?=_('件')?></dd>
            </dl>
        </div>
        <dl>
            <dt><?=_('限时折扣价格')?>：</dt>
            <dd>
                <input id="dialog_discount_price" type="text" class="text w70"><em class="add-on"><i class="iconfont icon-iconyouhuiquan"></i></em>
                <p class="hint"><?=_('限时折扣价应低于正常商品售价，活动开始时，系统将自动转换销售价为促销价')?>。</p>
            </dd>
        </dl>
        <div class="eject_con">
            <div class="bottom">
                <label class="submit-border"><a id="btn_submit" class="submit bbc_seller_submit_btns" href="javascript:void(0);"><?=_('提交')?></a></label>
            </div>
        </div>
    </div>
</div>
