
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>

<div class="wrap" style="">
	<!--  右登陆 -->
	


	<div class="bbc-main-layout">
        <!--积分礼品-->
			

       <!-- 代金券-->
		<div class="bbc-main-layout">
			<div class="title">
				<h3>
                    <span class="iconfont icon-daijinquan bbc_color"></span><?=_('热门代金券')?>
				    <span class="more"> <a href="<?=Yf_Registry::get('url')?>?ctl=Voucher&met=vList"><?=_('更多')?><i class="iconfont icon-iconjiantouyou rel_top2"></i></a></span>
                </h3>
            </div>
			<div style="margin-top:10px;" class="clearfix">
				<?php
                if($data['voucher'])
                {
					foreach($data['voucher'] as $key=>$value)
					{
				?>
				<div class="picture_<?=($key%3)+1?> imgs" data-id="<?=$value['voucher_t_id']?>">
					<div class="picture_1_1"> <?=$value['shop_name']?> </div>
					<div class="picture_1_2" style="margin-top:10px; ">
						<p class="bbc_color f18"><?=format_money($value['voucher_t_price'])?></p>
						<p> (<?=_('满')?><span class="bbc_color"><?=format_money($value['voucher_t_limit'])?></span><?=_('可使用')?>)</p>
						<p style="color:#000;width: 231px;"><?=_('有效期')?>：<?=date('Y-m-d',strtotime($value['voucher_t_start_date']))?> -- <?=date('Y-m-d',strtotime($value['voucher_t_end_date']))?> </p>
					</div>
					<div class="picture_1_3 point">
						<p style="color:red; font-size:16px"><?=_('需')?><?=$value['voucher_t_points']?><?=_('积分')?></p>
						<p><em class="giveout"><?=$value['voucher_t_giveout']?></em><?=_('人已经兑换')?></p>
					</div>
					<div class="picture_1_4"> 
						<a style="display:block" href="javascript:void(0);" op_type="exchangebtn" data-param='{"vid":"<?=$value['voucher_t_id']?>"}'>
							<div class="divxz divxzye bbc_btns"><p><?=_('立即兑换')?></p></div>
						</a> 
					</div>
				</div>
				<?php  
					}
                }
				?>
				
			</div>
		</div>
	</div>
</div>

<link href="<?=$this->view->css?>/tips.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/home.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>