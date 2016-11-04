<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>
	
	<!---  BEGIN 默认物流公司 --->
	<form method="post" id="form" action="<?=Yf_Registry::get('url')?>?ctl=Seller_Trade_Deliver&met=express&op=save&typ=e">
		
		<input type="hidden" name="act" value="save">
		<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
			<tbody>
				
				<tr><th colspan="4"><?=_('物流公司')?></th></tr>

                <tr>
				<?php foreach($data['items'] as $key=>$val){ ?>

					<td class="tl">
						<label class="checkbox"><input type="checkbox" <?php if($val['checked']==1) echo "checked"; ?> name="id[]" value="<?=$val['express_id']?>"></label><?=_($val['express_name'])?>
					</td>
				    <?php if(($key+1)%4==0){ ?></tr><tr><?php } ?>

				<?php } ?>

				<tr>
					<td colspan="4"><input class="button button_red bbc_seller_submit_btns" value="<?=_('保存')?>" type="submit"></td>
				</tr>
			
			</tbody>
		</table>
 
    </form>
	<!---  END 默认物流公司 --->

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>