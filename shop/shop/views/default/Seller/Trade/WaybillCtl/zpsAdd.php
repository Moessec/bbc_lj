<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    .ncsc-form-radio-list li, .ncsc-form-checkbox-list li {
        font-size: 12px;
        vertical-align: top;
        letter-spacing: normal;
        word-spacing: normal;
        display: inline-block;
        margin-right: 30px;
    }

    .webuploader-pick {
        padding: 0px;
    }

    select, .select {
        color: #777;
        background-color: #FFF;
        height: 30px;
        vertical-align: middle;
        padding: 0 4px;
        border: solid 1px #E6E9EE;
    }
</style>

<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>

<div class="form-style">
    <form method="post" id="form" class="nice-validator n-yellow" novalidate="novalidate">
        <dl>
            <dt><i>*</i>模板名称：</dt>
            <dd>
                <input type="text" autocomplete="off" name="zps_tpl_name" id="zps_tpl_name" maxlength="10" class="text w100" aria-required="true">
                <p class="hint">运单模板名称，最多10个字</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>配送范围：</dt>
            <dd>
                <input type="text" autocomplete="off" name="zps_range" id="zps_range" maxlength="10" class="text w100" aria-required="true"> km
                <p class="hint">填写为N时代表与商家所在地距离在N km范围内时调用该配送模板</p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>配送金额：</dt>
            <dd>
                <input type="text" autocomplete="off" name="zps_cost" id="zps_cost" maxlength="10" class="text w100" aria-required="true"> 元（人民币）
               
            </dd>
        </dl>
        <dl>
            <dt><i>*</i>启用：</dt>
            <dd>
                <ul class="ncsc-form-radio-list">
                    <li>
                        <label for="waybill_usable_1"><input id="zps_enable_1" type="radio" name="zps_enable" value="1"> 是</label>
                    </li>
                    <li>
                        <label for="waybill_usable_0"><input id="zps_enable_0" type="radio" name="zps_enable" value="0" checked=""> 否</label>
                    </li>
                </ul>
             
            </dd>
        </dl>

        <dl>
            <dt></dt>
            <dd>
                <input type="submit" class="button button_blue bbc_seller_submit_btns" value="提交">
                <input type="hidden" name="zps_tpl_id" id="zps_tpl_id" value="" />
            </dd>
        </dl>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>

    $('.tabmenu').children().children('li:gt(1) ').hide();
    $('.tabmenu').children().children('.active').show();

    $(function () {

        var met = 'addZps';

        upload_image = new UploadImage({
            thumbnailWidth: 500,
            thumbnailHeight: 281,
            uploadButton: '#btn_upload_img',
            inputHidden: '#waybill_image',
            imageContainer: '#img_show',
            callback: function (response) {
                Public.tips({ content: '上传成功', type: 3});
            }
        })

        //验证
        $('#form').validator({
            theme: 'yellow_right',
            timely: true,

            rules: {

            },

            fields: {
                'zps_tpl_name': 'required;length[~10]',
                'zps_range':'required;',
                'zps_cost': 'required;',
               
            },

            valid: function(form){
                //表单验证通过，提交表单到服务器
                $.post( SITE_URL + "?ctl=Seller_Trade_Waybill&typ=json&met=" + met , $('#form').serialize(), function(data) {

                    if( data.status == 200 ) {
                        Public.tips({ content: '保存成功！', type: 3 });
                        setTimeout(function () {
                            window.location.href = SITE_URL + '?ctl=Seller_Trade_Waybill&met=zpsIndex&typ=e';
                        }, 1000);
                    } else {
                        Public.tips({ content: '保存失败！', type: 1 });
                    }
                })
            }
        })

        //初始化
        <?php if ( !empty($zps_data) ) { ?>
            met = 'editZps';
            $('#zps_tpl_id').val(<?php echo $zps_data['zps_tpl_id']; ?>);
            $('#zps_tpl_name').val(<?php echo $zps_data['zps_tpl_name']; ?>);
            $('#zps_range').val(<?php echo $zps_data['zps_range']; ?>);
            $('#zps_cost').val(<?php echo $zps_data['zps_cost']; ?>);
            $('#zps_enable_<?= $zps_data['zps_enable'] ?>').prop('checked', 'checked');
        <?php } ?>
    })
</script>