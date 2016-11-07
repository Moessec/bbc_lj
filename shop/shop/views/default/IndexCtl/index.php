<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<div style="height:500px;">
	<div class="banner swiper-container">
		<ul class="banimg swiper-wrapper">
			<li class="swiper-slide">
				<a href="<?=Web_ConfigModel::value('index_live_link1')?>"><img src="<?=Web_ConfigModel::value('index_slider1_image')?>"/></a>
			</li>
			<li class="swiper-slide">
				<a href="<?=Web_ConfigModel::value('index_live_link2')?>"><img src="<?=Web_ConfigModel::value('index_slider2_image')?>"/></a>
			</li>
                        <li class="swiper-slide">
				<a href="<?=Web_ConfigModel::value('index_live_link3')?>"><img src="<?=Web_ConfigModel::value('index_slider3_image')?>"/></a>
			</li>
                        <li class="swiper-slide">
				<a href="<?=Web_ConfigModel::value('index_live_link4')?>"><img src="<?=Web_ConfigModel::value('index_slider4_image')?>"/></a>
			</li>
                        <li class="swiper-slide">
				<a href="<?=Web_ConfigModel::value('index_live_link5')?>"><img src="<?=Web_ConfigModel::value('index_slider5_image')?>"/></a>
			</li>
			
		</ul>
		 <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <script type="text/javascript">
			$(document).ready(function () {
				var swiper = new Swiper('.swiper-container', {
					pagination: '.swiper-pagination',
					paginationClickable: true,
					autoplayDisableOnInteraction: false,
					autoplay: 3000,
					speed: 300,
					loop: true, 
					grabCursor: true,
					paginationClickable: true,
					lazyLoading: true,
				});
			});
		</script>
		<div class="wrap t_cont clearfix">
			<ul class="tcenter">
				<li><a href="<?=Web_ConfigModel::value('index_liandong_url1')?>"><img src="<?=Web_ConfigModel::value('index_liandong1_image')?>"></a></li>
				<li><a href="<?=Web_ConfigModel::value('index_liandong_url2')?>"><img src="<?=Web_ConfigModel::value('index_liandong2_image')?>"></a></li>

			</ul>
			<div class="tright" id="login_tright">
			</div>
		</div>
	</div>
	</div>
	<div class="wrap">

		<!-- 团购风暴 -->
            <?php if(Web_ConfigModel::value('groupbuy_allow')){ ?>
		<div class="section">
			<h3>
				<img src="<?= $this->view->img ?>/gpad.png"/>
				<a href="index.php?ctl=GroupBuy&met=index"><?=_('更多')?><span class="iconfont icon-btnrightarrow"></span></a>
			</h3>
			<div class="wrap2 h_goods_cont">
				 <a class="lrwh btn1 iconfont icon-btnreturnarrow" data-numb="0"></a>
                                    
                                <ul class="goodsUl clearfix">
				
                                     <?php if(!empty($gb_goods_list['items'])){
                                                    foreach ($gb_goods_list['items'] as $key => $value) {
                                                        ?>
					<li>
						<a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=detail&id=<?=$value['groupbuy_id']?>" target="_blank"><img style="max-width: 200px;max-height: 150px;" src="<?= $value['groupbuy_image'] ?>"/></a>
						<p class="goods_pri"><?=format_money($value['groupbuy_price']) ?></p>
						<h5><a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=detail&id=<?=$value['groupbuy_id']?>" target="_blank"><?= $value['groupbuy_name'] ?></a></h5>
						<p class="rest">
							<span class="iconfont icon-shijian2"></span>
							<strong class="fnTimeCountDown" data-end="<?=$value['groupbuy_endtime']?>"> 
                                                            <span class="day" >00</span><strong><?=_('天')?></strong>
                                                            <span class="hour">00</span><strong><?=_('小时')?></strong>
                                                            <span class="mini">00</span><strong><?=_('分')?></strong>
                                                            <span class="sec" >00</span><strong><?=_('秒')?></strong>
                                                        </strong>
						</p>
						<div class="buygo"><a href="<?=Yf_Registry::get('url')?>?ctl=GroupBuy&met=detail&id=<?=$value['groupbuy_id']?>" target="_blank"><?=_('立即去团')?></a></div>
					</li>
                                            <?php } }?>
					
				</ul>
				  <a class="lrwh btn2 iconfont icon-btnrightarrow " data-num="0"></a>
			</div>
		</div>
                <?php } ?>
<!--		<div class="ban2">
			<a href="#"><img src="<--?= $this->view->img ?>/gpad6.png"/></a>
		</div>-->
               <div class="wrap floor fn-clear">
                <?php if(!empty($adv_list['items'])){
                    foreach ($adv_list['items'] as $key => $value) {

                    
                
?>
                
                    <?=$value['page_html']?>
               
                 <?php } }?>
                     </div>
	

		</div>
	</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>