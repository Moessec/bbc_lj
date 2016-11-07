
<?php
include $this->view->getTplPath() . '/' . 'join_header.php';
?>


<script type="text/javascript" src="<?=$this->view->js?>/Touch.js"></script>


<div class="banner">
  <div class="user-box">
    <div class="user-joinin">
      <h3><?=_('亲爱的：')?><?=$this->user_info['user_name'] ?></h3>
      <dl>
        <dt><?=_('欢迎来到bbcbuilder')?></dt>
        <dd> <?=_('若您还没有填写入驻申请资料')?> <br>
          请点击“ <a href="" target="_blank"> <?=_('我要入驻')?></a><?=_('”进行入驻资料填写')?> </dd>
        <dd>  <?=_('若您的店铺还未开通')?> <br>
          <?=_('请通过“')?>  <a href="index.php?ctl=Seller_Shop_Settled&met=index&op=step2" target="_blank"> <?=_('查看入驻进度')?></a>  <?=_('”了解店铺开通的最新状况')?> </dd>
      </dl>
      <div class="bottom"> <a href="index.php?ctl=Seller_Shop_Settled&met=index&op=step1" target="_blank"> <?=_('我要入驻')?></a>  </div>
    </div>
  </div>
<!--  <ul id="fullScreenSlides" class="full-screen-slides">
    <li style="z-index: 800; display: list-item; background-image: url(&quot;http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/store_joinin_1.jpg&quot;); background-color: rgb(241, 165, 149);"></li>
    <li style="display: none; z-index: 900; background-image: url(&quot;http://b2b2c.bbc-builder.com/tesa/data/upload/shop/common/store_joinin_2.jpg&quot;); background-color: rgb(241, 165, 149);"></li>
  </ul>
  <ul class="full-screen-slides-pagination">
    <li class="current"> <a href="javascript:void(0)">1</a> </li>
    <li class=""> <a href="javascript:void(0)">2</a> </li>
  </ul>-->
      
        <div class="swiper-container">
          <ul class="clearfix swiper-wrapper">
              <a href="<?=Web_ConfigModel::value('join_live_link1')?>" class="swiper-slide">
              <li>
                  <img src="<?=Web_ConfigModel::value('join_slider1_image')?>">
              </li>
              </a>
              <a href="<?=Web_ConfigModel::value('join_live_link2')?>" class="swiper-slide">
              <li>
                  <img src="<?=Web_ConfigModel::value('join_slider2_image')?>">
              </li>
              </a>
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
                grabCursor: true,
                paginationClickable: true,
                lazyLoading: true,
              });
            });
          </script>
      </div>
</div>
<div class="indextip">
  <div class="container"> <span class="title"> <i class="iconfont icon-laba"></i>
    <h3> <?=_('贴心提示')?></h3>
    </span>
    <span class="content"> <?=Web_ConfigModel::value('join_tip')?></span> 
    </div>
</div>
<div class="main mt30">
  <h2 class="index-title"> <?=_('入驻流程')?></h2>
  <div class="joinin-index-step"> <span class="step"> <i class="iconfont icon-shangjiaruzhushenqing"></i>  <?=_('签署入驻协议')?> </span> <span class="arrow"></span> <span class="step"> <i class="iconfont icon-xinxitijiao"></i>  <?=_('商家信息提交')?> </span> <span class="arrow"></span> <span class="step"> <i class="iconfont icon-pingtaishenhe"></i>  <?=_('平台审核资质')?> </span> <span class="arrow"></span> <span class="step"> <i class="iconfont icon-jiaonafeiyong"></i>  <?=_('商家缴纳费用')?> </span> <span class="arrow"></span> <span class="step"> <i class="iconfont icon-dianpu2"></i>  <?=_('店铺开通')?> </span> </div>
  <h2 class="index-title"> <?=_('入驻指南')?></h2>
  <div class="joinin-info">
    <ul class="tabs-nav">
       <?php foreach ($shop_help as $key => $value) {
            
        ?>
      <li class="<?php if($key==96){echo "tabs-selected";}?>">
        <h3><?=$value['help_title']?></h3>
      </li>
     
       <?php }?>
    </ul>
    <?php foreach ($shop_help as $key => $value) {
            
        ?>
    <div class="tabs-panel <?php if($key!=96){?>tabs-hide<?php }?>">
        <?=$value['help_info']?>
    </div>
    <?php }?>
  </div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
</body>
</html>