<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<script src="<?=$this->view->js_com?>/plugins/jquery.timeCountDown.js" ></script>
<script>
    $(function(){
        var _TimeCountDown = $(".fnTimeCountDown");
        _TimeCountDown.fnTimeCountDown();
    })
</script>
    </div>
      <div class="order_content">
          <div class="order_content_title clearfix">
          <form method="get" id="search_form" action="index.php" >
            <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
            <input type="hidden" name="met" value="<?=$_GET['met']?>">
            <p class="order_types">
				<a <?php if($status == '' &&  !$recycle):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><?=_('全部订单')?></a>
				<a <?php if($status == 'wait_pay'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_pay"><?=_('待付款')?></a>
				<a <?php if($status == 'wait_confirm_goods'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_confirm_goods"><?=_('待收货')?></a>
				<a <?php if($status == 'finish'):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=finish"><?=_('已完成')?></a>
			</p>

            <p class="order_time">
                <span><?=_('下单时间')?></span>
                <input type="text" autocomplete="off" placeholder="开始时间" name="start_date" id="start_date" class="text w70" value="<?=@$_GET['start_date']?>">
                 <label class="add-on">
                    <i class="iconfont icon-rili"></i>
                </label>
                <em style="margin-top: 3px;">&nbsp;– &nbsp;</em>
                <input type="text" placeholder="结束时间" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=@$_GET['end_date']?>">
                 <label class="add-on">
                    <i class="iconfont icon-rili"></i>
                </label>

            </p>
            <p class="ser_p" style="margin-left: 10px;">
                <input type="text" name="orderkey" placeholder="<?=_('订单号')?>" value="<?=@$_GET['orderkey']?>">
                <a class="btn_search_goods" href="javascript:void(0);" style="padding-left: 2px;"><i class="iconfont icon-icosearch icon_size18" style="margin-right:-2px; "></i><?=_('搜索')?></a>
            </p>

            <p class="order_types serc_p">
                <a <?php if($recycle):?>class="currect"<?php endif;?> href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&recycle=1"><i class="iconfont icon-lajitong icon_size20"></i><?=_('订单回收站')?></a>
            </p>

            <script type="text/javascript">
            $("a.btn_search_goods").on("click",function(){
                $("#search_form").submit();
            });
            </script>
          </form>
          </div>
          <table>
              <tbody class="tbpad">
                <tr class="order_tit">
                  <th class="order_goods"><?=_('商品')?></th>
                  <th class="widt1"><?=_('单价')?></th>
                  <th class="widt2"><?=_('数量')?></th>
                  <th class="widt4"><?=_('售后维权')?></th>
                  <th class="widt5"><?=_('订单金额')?></th>
                  <th class="widt6"><?=_('交易状态')?></th>
                  <th class="widt7"><?=_('交易操作')?></th>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
                </tr>
              </tbody>
              <?php if($data['items']){?>
              <?php foreach($data['items'] as $key => $val):?>
              <tbody class="tboy">

              <!-- 下单时间，订单号，店铺名称    -->
                <tr class="tr_title">
                  <th colspan="8" class="order_mess clearfix">
                      <p class="order_mess_one">
                        <time><?=_('下单时间：')?><?=($val['order_create_time'])?></time>
                        <span><?=_('订单号：')?><strong><?=($val['order_id'])?></strong></span>
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($val['shop_id'])?>"><i class="iconfont icon-icoshop"></i><?=($val['shop_name'])?></a>
                      </p>
                  </th>
                </tr>

				<tr>
				    <td colspan="4"  class="td_rborder">
				        <!--S  循环订单中的商品  -->
                        <table>
                        <?php foreach($val['goods_list'] as $ogkey=> $ogval):?>
                            <tr class="tr_con">
                                <td class="order_goods">
                                    <img src="<?=image_thumb($ogval['goods_image'],50,50)?>"/>
                                    <a target="_blank"  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&gid=<?=($ogval['goods_id'])?>"><?=($ogval['goods_name'])?></a>

                                    <?php if($ogval['order_goods_benefit']){?><em class="td_sale bbc_btns small_details"><?=($ogval['order_goods_benefit'])?></em><?php }?>
                                </td>
                                <td class="td_color widt1"><?=format_money($ogval['goods_price'])?></td>
                                <td class="td_color widt2"><i class="iconfont icon-cuowu" style="position:relative;font-size: 12px;"></i> <?=($ogval['order_goods_num'])?></td>
                                <td class="td_color widt4">
                                    <?php if($val['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $val['order_status'] != Order_StateModel::ORDER_PAYED  && $val['order_status'] != Order_StateModel::ORDER_CANCEL){?>
                                        <?php if($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_NO ){?>
                                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>"><?=_('退货')?></a>
                                        <?php }?>

                                            <?php if($ogval['goods_refund_status'] != Order_StateModel::ORDER_GOODS_RETURN_NO ){?>
                                                 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($ogval['order_return_id'])?>"><?=_('退货进度')?></a>
                                            <?php }?>

<!--                                        --><?php //if($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_IN ){?>
<!--                                            --><?//=_('退货中')?>
<!--                                        --><?php //}?>
<!--                                        --><?php //if($ogval['goods_refund_status'] == Order_StateModel::ORDER_GOODS_RETURN_END   ){?>
<!--                                            --><?//=_('退货完成')?>
<!--                                        --><?php //}?>

                                     <?php }?>
                                    <p>
                                        <?php if(($val['order_status'] == Order_StateModel::ORDER_FINISH && $val['complain_status']) || ($val['order_status'] != Order_StateModel::ORDER_CANCEL && $val['order_status'] != Order_StateModel::ORDER_WAIT_PAY)){?>
                                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Complain&met=index&act=add&gid=<?=($ogval['order_goods_id'])?>">
                                                <?=_('交易投诉')?>
                                            </a>
                                        <?php }?>
                                    </p>
                                </td>

                            </tr>
                        <?php endforeach;?>
                        </table>
                        <!--E  循环订单中的商品   -->
                </td>

                <!--S  订单金额 -->
                <td class="td_rborder widt5">
				     <span>
				        <?=_('订单总额：')?><strong><?=format_money($val['order_goods_amount'])?></strong><!--<br/>--><?/*=($val['payment_name'])*/?>
				     </span>
				     <br/>
				     <span>
				        <?=_('运费：')?><?php if($val['order_shipping_fee'] > 0):?><?=format_money($val['order_shipping_fee'])?><?php else:?><?=_('免运费')?><?php endif;?>
                    </span>
				     <br/>
				     <span>
				        <?=_('应付：')?><strong><?=format_money($val['order_payment_amount'])?></strong>
				     </span>
				     <?php if($val['order_shop_benefit']){?><span class="td_sale bbc_btns"><?=($val['order_shop_benefit'])?></span><?php }?>
                </td>
                <!--E 订单金额 -->

				<td class="td_rborder">
                   <p class="getit <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY ){?>bbc_color<?php }?>"><?=($val['order_state_con'])?></p>
                   <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY  && $val['payment_id'] == PaymentChannlModel::PAY_CONFIRM ){?>
                        <p class="getit bbc_color"><?=_('货到付款')?></p>
                   <?php }?>

                   <!-- 如果是待收货的订单就显示物流信息 -->
                   <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ){ ?>
                        <a style="position:relative;" onmouseover="show_logistic('<?=($val['order_id'])?>','<?=($val['order_shipping_express_id'])?>','<?=($val['order_shipping_code'])?>')" onmouseout="hide_logistic('<?=($val['order_id'])?>')">
                        <i class="iconfont icon-icowaitproduct rel_top2"></i><?=_('跟踪')?>
                        <div style="display: none;" id="info_<?=($val['order_id'])?>" class="prompt-01"> </div>
                        </a>
                   <?php }?>
                   <p>
                      <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?=($val['order_id'])?>"><?=_('订单详情')?>
                      </a>
                   </p>


                    <!-- S 订单详情  -->
                    <!-- 订单退款状态：当订单不为取消状态和待付款状态时显示订单退款状态 -->
				    <?php if($val['order_status'] != Order_StateModel::ORDER_CANCEL && $val['order_status'] != Order_StateModel::ORDER_WAIT_PAY ){?>
                    <p>
                                <?php if($val['order_refund_status'] != Order_StateModel::ORDER_REFUND_NO ){?>
                                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=detail&id=<?=($val['order_return_id'])?>"><?=_('退款进度')?></a>
                                <?php }?>
                    </p>
                    <?php }?>
                    <!--E  订单详情  -->
                </td>


                <!--S 订单操作  -->
				<td class="td_rborder">
				    <?php if(($val['order_status'] == Order_StateModel::ORDER_CANCEL || $val['order_status'] == Order_StateModel::ORDER_FINISH) && $recycle != 1):?>
                      <p>
                        <a onclick="hideOrder('<?=$val['order_id']?>')"><i class="iconfont icon-lajitong icon_size22"></i><?=_('删除订单')?></a>
                      </p>
                  <?php endif; ?>

				<!--S  未付款订单 -->
				    <?php if($val['order_status'] == Order_StateModel::ORDER_WAIT_PAY):?>
				        <p class="rest">
							<span class="iconfont icon-shijian2"></span>
							<span class="fnTimeCountDown" data-end="<?=$val['cancel_time']?>">
							    <span><?=_("剩余")?></span>
                                <!--<span class="day" >00</span><strong><?/*=_('天')*/?></strong>-->
                                <span class="hour">00</span><span><?=_('时')?></span>
                                <span class="mini">00</span><span><?=_('分')?></span>
                                <!--<span class="sec" >00</span><strong><?/*=_('秒')*/?></strong>-->
                            </span>
						</p>
						<?php if($val['payment_id'] != PaymentChannlModel::PAY_CONFIRM): ?>
                        <p>
                            <a target="_blank" onclick="payOrder('<?=$val['payment_number']?>','<?=$val['order_id']?>')" class="to_views "><i class="iconfont icon-icoaccountbalance pay-botton" ></i><?=_('订单支付')?></a>
                        </p>
                        <?php endif; ?>
                          <a onclick="cancelOrder('<?=$val['order_id']?>')" class="to_views"><i class="iconfont icon-quxiaodingdan"></i><?=_('取消订单')?></a>
                    <?php endif;?>
                <!--E  未付款订单 -->
                <?php if($val['order_status'] != Order_StateModel::ORDER_WAIT_PAY && $val['order_status'] != Order_StateModel::ORDER_CANCEL){?>
                    <?php if($val['order_refund_status'] == Order_StateModel::ORDER_REFUND_NO ){?>
                         <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index&act=add&oid=<?=($val['order_id'])?>" class="to_views"><i class="iconfont icon-dingdanwancheng icon_size22"></i><?=_('申请退款')?></a>
                    <?php }?>
                <?php }?>

                    <?php if($val['order_refund_status'] !== Order_StateModel::ORDER_REFUND_IN && $val['order_return_status'] !== Order_StateModel::ORDER_GOODS_RETURN_IN  &&  $val['order_status'] == Order_StateModel::ORDER_WAIT_CONFIRM_GOODS ): ?>
                        <p class="rest">
							<span class="iconfont icon-shijian2"></span>
							<span class="fnTimeCountDown" data-end="<?=$val['order_receiver_date']?>">
							    <span><?=_("剩余")?></span>
                                <span class="day" >00</span><span><?=_('天')?></span>
                                <span class="hour">00</span><span><?=_('时')?></span>
                                <!--<span class="mini">00</span><strong><?/*=_('分')*/?></strong>-->
                                <!--<span class="sec" >00</span><strong><?/*=_('秒')*/?></strong>-->
                            </span>
						</p>
                        <a onclick="confirmOrder('<?=$val['order_id']?>')" class="to_views "><i class="iconfont icon-duigou1 icon_size22"></i><?=_('确认收货')?></a>
                   <?php endif;?>

                    <?php if($val['order_status'] == Order_StateModel::ORDER_FINISH ): ?>
                            <?php if(!$val['order_buyer_evaluation_status']): ?>
                                    <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation&act=add&order_id=<?=($val['order_id'])?>" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=_('我要评价')?></a>
                            <?php endif;?>
                        <?php if($val['order_buyer_evaluation_status']): ?>
                            <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation" class="to_views"><i class="iconfont icon-woyaopingjia icon_size22"></i><?=_('追加评价')?></a>
                        <?php endif;?>
                    <?php endif;?>

                    <?php if($recycle): ?>

                        <a onclick="restoreOrder('<?=$val['order_id']?>')"><i class="iconfont icon-huanyipi"></i><?=_('还原订单')?></a>

                        <a onclick="delOrder('<?=$val['order_id']?>')" class="to_views"><i class="iconfont icon-lajitong icon_size22"></i><?=_('彻底删除')?></a>

                    <?php endif;?>
                </td>
                <!--E 订单操作   -->
		    </tr>
            </tbody>

              <tbody>
                <tr>
                  <th class="tr_margin" style="height:16px;background:#fff;" colspan="8"></th>
                </tr>
              </tbody>
              <?php endforeach;?>
              <?php }
            else
            {
                ?>
                <tr>
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png"/>
                            <p><?= _('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
          </table>
          <div class="flip page clearfix">
            <p><!--<a href="#" class="page_first">首页</a><a href="#" class="page_prev">上一页</a><a href="#" class="numla cred">1</a><a href="#" class="page_next">下一页</a><a href="#" class="page_last">末页</a>-->
            <?=$page_nav?>
            </p>
          </div>
        </div>
      </div>
</div>
  </div>
</div>
</div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('#start_date').datetimepicker({
        controlType: 'select',
        timepicker:false,
        format:'Y-m-d'
    });

    $('#end_date').datetimepicker({
    controlType: 'select',
    timepicker:false,
    format:'Y-m-d'
    });


    window.hide_logistic = function (order_id)
    {
        $("#info_"+order_id).hide();
        $("#info_"+order_id).html("");
    }

    window.show_logistic = function (order_id,express_id,shipping_code)
    {
     $("#info_"+order_id).show();
        $.post(BASE_URL + "/shop/api/logistic.php",{"order_id":order_id,"express_id":express_id,"shipping_code":shipping_code} ,function(da) {

                if(da)
                {
                    var data = eval('('+da+')');

                    if(data.status == 1)
                    {
                        var info_div = $("#info_"+order_id);

                        var content_div = '<div class="pc"><div class="p-tit"><?=_('运单号：')?>' + order_id + '</div><div class="logistics-cont"><ul>';

                        for (var i in data.data) {
                            var time = data.data[i].time;
                            var context = data.data[i].context;

                            var class_name = "";
                            if(i == 0)
                            {
                                class_name = "first";
                            }

                            content_div = content_div + '<li class='+ class_name + '><i class="node-icon bbc_bg"></i><a> ' + context + ' </a><div class="ftx-13"> ' + time + '</div></li>';

                        }

                        content_div = content_div + '</ul></div></div><div class="p-arrow p-arrow-left" style="top: 242px;"></div>';

                        $("#info_"+order_id).html(content_div);
                    }

                    if(data.status == 0)
                    {
                        $("#info_"+order_id).html('<div class="error_msg"><?=_('物流单暂无结果')?></div>');
                    }

                    if(data.status == 2 || !data)
                    {

                    }
                }
                else
                {
                    $("#info_"+order_id).html('<div class="error_msg"><?=_('接口出现异常')?></div>');
                }


        })
    }
});
</script>

 <!-- 尾部 -->
 <?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>