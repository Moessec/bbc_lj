<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
 
    <form  method="post" id="form" >
        <input type='hidden' name='shop_id' value="<?=$re['shop_id']?>">
    <div class="form-style">
        <dl>
            <dt><?=_('店铺名称：')?></dt>
            <dd><?=$re['shop_name']?></dd>
        </dl>
<!--        <dl>
            <dt>店铺分类：</dt>
            <dd></dd>
        </dl>-->
        <dl>
            <dt><?=_('店铺等级：')?></dt>
            <dd><?=$re['shop_grade']?></dd>
        </dl>
<!--        <dl>
            <dt>主营商品：</dt>
            <dd>
                <textarea style="width:300px;height:100px;"></textarea>
              <p class="hint">此处理店铺页logo；<br />建议使用宽200像素-高60像素内的GIF或PNG透明图片；点击下方"确认提交"按钮后生效。</p>
            </dd>
        </dl>-->
        <dl>
            <dt><?=_('店铺logo：')?></dt>
            <dd>
                <p class="pic" style="width:180px;height:80px;"><img id="logo_img" src="<?php if(!empty($re['shop_logo'])){ echo $re['shop_logo'];}?>" height="80" width="180" /></p>
                <p class="upload-button"><input type="hidden" id="logo" name="shop[shop_logo]" value="<?=$re['shop_logo']?>" /><div  id='logo_upload' class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan" ></i><?=_('图片上传')?></div></p>                
                <p class="hint"><?=_('此处为店铺页logo；')?><br /><?=_('建议使用宽180像素*高80像素内的GIF或PNG透明图片；点击下方"确认提交"按钮后生效。')?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=_('wap_首页店铺logo：')?></dt>
            <dd>
                <p class="pic" style="width:100px;height:100px;"><img id="logo_img1" src="<?php if(!empty($re['shop_logo1'])){ echo $re['shop_logo1'];}?>" height="100" width="100" /></p>
                <p class="upload-button"><input type="hidden" id="logo1" name="shop[shop_logo1]" value="<?=$re['shop_logo1']?>" /><div  id='logo_upload1' class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan" ></i><?=_('图片上传')?></div></p>                
                <p class="hint"><?=_('此处为手机店铺页logo；')?><br /><?=_('建议使用宽100像素*高100像素内的GIF或PNG透明图片；点击下方"确认提交"按钮后生效。')?></p>
            </dd>
        </dl>
<!--         <dl>
            <dt>店铺头像：</dt>
            <dd>
           		<p class="pic" style="width:200px;height:60px;"><img id="logo_img" src="<{if $de.logo}><{$de.logo}><{else}>image/default/seller/default_logo.png<{/if}>" height="60" width="200" /></p>
                <p class="upload-button"><input type="hidden" id="logo" name="shop[banner]" value="<{$de.logo}>" /><a class="button button_black" href="javascript:uploadfile('图片上传','logo',200,60,'shop');"><i class="iconfont icon-upload-alt"></i>图片上传</a></p>                
                <p class="hint">此处为店铺方形头像；<br/> 建议使用宽100像素*高100像素内的方型图片；点击下方"确认提交"按钮后生效。</p>
            </dd>
        </dl>-->
        <dl>
            <dt><?=_('店铺条幅：')?></dt>
            <dd>
                <p class="pic" style="width:600px;height:150px;"><img id="banner_img" src="<?php if(!empty($re['shop_banner'])){ echo $re['shop_banner'];} ?>" height="150" width="600" /></p>
                <p class="upload-button"><input type="hidden" id="banner" name="shop[shop_banner]" value="<?=$re['shop_banner']?>" /><div  id='banner_upload' class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan"></i><?=_('图片上传')?></div></p>
                <p class="hint"><?=_('此处为店铺条幅：')?><br /><?=_('建议使用宽1200像素*高150像素的图片；点击下方"确认提交"按钮后生效。')?></p>
            </dd>
        </dl>
        <?php if($shop_domain['shop_domain']['config_value']){  ?>
        <dl>
            <dt><?=_('二级域名：')?></dt>
            <dd>
                <input type="text" class="text" name="shop[shop_domain]" value="<?=$re['shop_domain']?>" <?php if($domain_list['shop_edit_domain']==0){?>readonly="readonly"<?php }?> />
                <?php if($shop_domain['is_modify']['config_value']){ ?><p class="hint"><?php if($domain_list['shop_edit_domain']){?><?=_('可留空，域名长度应为:')?><?= $shop_domain['domain_length']['config_value']?>  <?=_('还可以修改')?><?=$domain_list['shop_edit_domain']?><?=_('次')?> <?php }else{?><?=_('修改次数已达上线')?><?php }?></p>
                <?php }else{ ?>
                <p class="hint"><?=_('不可修改')?></p>
                <?php }?>
            </dd>
        </dl>
        <?php }?>
        <dl>
            <dt><?=_('QQ：')?></dt>
            <dd><input type="text" class="text" name="shop[shop_qq]" value="<?=$re['shop_qq']?>" /></dd>
        </dl>
        <dl>
            <dt><?=_('旺旺：')?></dt>
            <dd><input type="text" class="text" name="shop[shop_ww]" value="<?=$re['shop_ww']?>" /></dd>
        </dl> 
        <dl>
            <dt><?=_('电话：')?></dt>
              <dd><input type="text" class="text" name="shop[shop_tel]" value="<?=$re['shop_tel']?>" /></dd>
        </dl>
       
        <dl>
            <dt></dt>
            <dd>
            <input type="hidden" name="op" value="edit" />
            <input type="submit" class="button bbc_seller_submit_btns" value="<?=_('确认提交')?>" />
            </dd>
        </dl>
    </div>
    </form>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">

<script>
    $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Setshop&met=editShop&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                 qq: [/^\d{5,10}$/, '<?=_('请输入正确qq')?>'],
                 tel:[/^[1][358][0-9]{9}$/,'<?=_('请输入正确的手机号码')?>'],
            },

            fields: {
                'shop[shop_qq]': 'qq',
                'shop[shop_tel]':'tel',
            },
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           Public.tips.success('操作成功！');
                          // setTimeout(' location.href="./index.php?ctl=Seller_Shop_Setshop&met=index&typ=e"',3000); //成功后跳转

                        }
                        else
                        {
                            Public.tips.error('操作失败！');
                        }
                    }
                });
            }

        });
    });




</script>
 <script>
    //图片上传
    $(function(){

        /*logo_uploadss = new UploadImage({
            thumbnailWidth: 200,
            thumbnailHeight: 60,
            imageContainer: '#logo_img',
            uploadButton: '#logo_upload',
            inputHidden: '#logo'
        });*/

//        banner_upload = new UploadImage({
//            thumbnailWidth: 1200,
//            thumbnailHeight: 150,
//            imageContainer: '#banner_img',
//            uploadButton: '#banner_upload',
//            inputHidden: '#banner'
//        });


      
        
        
        var $imagePreview, $imageInput, imageWidth, imageHeight,shopWidth;

        $('#banner_upload, #logo_upload,#logo_upload1').on('click', function () {

            if ( this.id == 'banner_upload' ) {
                $imagePreview = $('#banner_img');
                $imageInput = $('#banner');
                imageWidth = 1200, imageHeight = 150,shopWidth = 1200;
            } else if ( this.id == 'logo_upload' ) {
                $imagePreview = $('#logo_img');
                $imageInput = $('#logo');
                imageWidth = 200, imageHeight = 60,shopWidth = 800;
            }else if(this.id == 'logo_upload1') {
                $imagePreview = $('#logo_img1');
                $imageInput = $('#logo1');
                imageWidth = 100, imageHeight = 100,shopWidth = 800;                
            }
            $.dialog({
                title: '图片裁剪',
                content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
                data: { width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                width: shopWidth,
                lock: true
            })
        });

        function callback ( respone , api ) {
            $imagePreview.attr('src', respone.url);
            $imageInput.attr('value', respone.url);
            api.close();
        }

    })
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

