<div class="footer">
	<div class="wrapper">
            <?php if(!$this->ctl =="Seller_Shop_Settled"){ ?>
		<div class="promise">
                    <div ><span  class="iconfont icon-qitiantuihuan bbc_color"></span><strong class="bbc_color">七天退货</strong></div>
                    <div><span class="iconfont icon-iconzhengping bbc_color"></span><strong class="bbc_color">正品保障</strong></div>
                    <div><span class="iconfont icon-iconshandian bbc_color"></span><strong class="bbc_color">闪电发货</strong></div>
                    <div><span class="iconfont icon-iconbaoyou bbc_color"></span><strong class="bbc_color">满额免邮</strong></div>
		</div>
                <?php } ?>
		<ul class="services clearfix">
			<?php if (!empty($this->foot)):
                                $i = 1;
				foreach ($this->foot as $key => $value):
					?>
					<li>
						<h5><i class="iconfont icon-weibu<?=$i?>"></i><span><?= $value['group_name'] ?></span></h5>
						<?php
						if (!empty($value['article'])):
							foreach ($value['article'] as $k => $v):
								?>
                                <?php if(!empty($v['article_url'])){ ?>
                                    <p>
                                        <a href="<?= $v['article_url'] ?>">&bull;&nbsp;<?= $v['article_title'] ?></a>
                                    </p>
                                <?php }else{ ?>
                                    <p>
                                        <a href="index.php?ctl=Article_Base&article_id=<?= $v['article_id'] ?>">&bull;&nbsp;<?= $v['article_title'] ?></a>
                                    </p>
                                <?php } ?>
                                <?php  ?>
								<?php
							endforeach;
						endif;
						?>
					</li>
					<?php
                                    $i++;
				endforeach;
			endif; ?>
		</ul>
		<p class="about">
            <?php if($this->bnav){
                foreach ($this->bnav['items'] as $key => $nav) {
                    if($key<10){
                    ?>
                    <a href="<?=$nav['nav_url']?>" <?php if($nav['nav_new_open']==1){?>target="_blank"<?php } ?>><?=$nav['nav_title']?></a>
                <?php }else{
                        return;
                    }}} ?>
		</p>

		<p class="copyright"><?=Web_ConfigModel::value('copyright')?></p>
		<p class="statistics_code"><?php echo Web_ConfigModel::value('icp_number') ?></p>
	</div>
	</div>
</div>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.ui.js"></script>
<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/respond.js"></script>

<p class="statistics_code"><?php echo Web_ConfigModel::value('statistics_code') ?></p>

</body>
</html>