<span><?=_('欢迎来')?><?=Web_ConfigModel::value("site_name") ?><?=_('欢迎您！')?> </span>
<?php echo empty($this->userInfo) ? '<a href="' . Yf_Registry::get('url') . '?ctl=Login&met=login"> 请登录 </a> <a href="' . Yf_Registry::get('url') . '?ctl=Login&met=reg">免费注册 </a> ' : ' <a href="./index.php?ctl=Buyer_Index&met=index"> ' . $this->userInfo['user_name'] . ' </a> <a href="' . Yf_Registry::get('url') . '?ctl=Login&met=loginout"> [退出]</a>' ?>

<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;
?>

<div class="tright_content">
    <p class="user_head"><img src="<?php if(empty($this->userInfo['user_logo'])){  ?><?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?= @Perm::$userId ?> <?php }else{ ?><?php echo $this->userInfo['user_logo'];}?>"/></p>
	<p class="hi"><span><?=_('Hi~你好！')?></span></p>
	<?php echo empty($this->userInfo) ? '<p><a href="' . Yf_Registry::get('url') . '?ctl=Login&met=login" class="login">
	<span class="iconfont icon-icondenglu"></span>请登录</a></p><p><a class="register" href="' . Yf_Registry::get('url') . '?ctl=Login&met=reg"><i class="iconfont icon-icoedit"></i>免费注册</a></p>' : '<p style="overflow:hidden;"><a href="./index.php?ctl=Buyer_Index&met=index">' . $this->userInfo['user_name'] . '</a></p>' ?>

	<div class="prom">
		<p><span class="iconfont icon-tuihuobaozhang"></span><a href=""><?=_('退货保障')?></a></p>
		<p><span class="iconfont icon-shandiantuikuan"></span><a href=""><?=_('极速退款')?></a></p>
	</div>
	<div class="cooperation">
		<h3><?=_('招商入驻')?></h3>
		<p><img src="<?= $this->view->img ?>/icon_ruzhu.png"/></p>
		<p><a href="index.php?ctl=Seller_Shop_Settled&met=index" class="apply"><?=_('申请商家入驻')?></a></p>
	</div>
</div>
<?php
$d = ob_get_contents();
ob_end_clean();
ob_start();

$data[] = $d;
?>
