<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<style>
    .w30 {
        width: 30px;
    }

    .w180 {
        width: 180px;
    }

    .w80 {
        width: 80px !important;
    }

    .w200 {
        width: 200px !important;
    }

    .nscs-table-handle span a {
        color: #777;
        background-color: #FFF;
        display: block;
        padding: 3px 7px;
        margin: 1px;
    }

    .waybill-img-thumb {
        background-color: #fff;
        border: 1px solid #e6e6e6;
        display: inline-block;
        height: 45px;
        padding: 1px;
        vertical-align: top;
        width: 70px;
    }

    .waybill-img-thumb a {
        display: table-cell;
        height: 45px;
        line-height: 0;
        overflow: hidden;
        text-align: center;
        vertical-align: middle;
        width: 70px;
    }

    .waybill-img-thumb a img {
        max-height: 45px;
        max-width: 70px;
    }

    .waybill-img-size {
        color: #777;
        display: inline-block;
        line-height: 20px;
        margin-left: 10px;
        vertical-align: top;
    }

    .table-list-style th {
        padding: 8px 0;
    }

    .table-list-style tbody td {
        color: #999;
        background-color: #FFF;
        text-align: center;
        padding: 10px 0;
    }

    .tabmenu a.ncbtn {
        position: absolute;
        z-index: 1;
        top: -2px;
        right: 0px;
    }
    
    a.ncbtn {
        height: 20px;
        padding: 5px 10px;
        border-radius: 3px;
        color: #FFF;
        font: normal 12px/20px "microsoft yahei", arial;
    }
</style>
<div class = "deliverSetting">
    <div class = "alert">
        <h4>操作提示：</h4>
        <ul>
            <li>1、商家已经建立的自配送模板列表</li>
            <li>2、点击右上角的添加模板按钮可以建立商家自己的自配送模板</li>
            <li>3、自配送模板需由商家设定配送范围，超过范围将启用物流配送</li>
            <li>4、设计完成后在编辑中修改模板状态为启用后，商家就可以启用该配送模板</li>
            <li>5、点击删除按钮可以删除现有模板，删除后该模板将自动解除绑定，请慎重操作</li>
        </ul>
    </div>

    <form method = "post" id = "form">
        <table class = "table-list-style" width = "100%" cellpadding = "0" cellspacing = "0">
            <thead>
            <tr>
               
                <th >模板名称</th>
                <th >配送范围</th>
                <th >配送费用</th>
                <th >启用</th>
                <th >操作</th>
            </tr>
            </thead>

            <tbody>
          
            <?php foreach ($data['items'] as $key => $val) { ?>
            <tr class = "bd-line">
                <td><?php echo $val['zps_tpl_name']; ?></td>
                <td><?php echo $val['zps_range']; ?>km以内</td>
                <td><?php echo $val['zps_cost']; ?>元</td>
                <td><?php if ($val['zps_enable']==1){?>
                是
				<?php }else{?>
				否
				<?php }?>
                </td>

                <td class="nscs-table-handle"><span>
                  
                    </a></span><span><a href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=editZps&typ=e&zps_tpl_id=' . $val['zps_tpl_id']; ?>"
                                        class="btn-bluejeans"><i class="iconfont icon-zhifutijiao"></i>
                    <p>编辑</p>
                    </a></span><span><a href="javascript:;"
                                        nctype="btn_del"
                                        data-zps_tpl_id="<?php echo $val['zps_tpl_id']; ?>"
                                        class="btn-grapefruit"><i class="iconfont icon-lajitong"></i>
                    <p>删除</p>
                    </a></span></td>
               
                
            </tr>
             <?php } ?>
            </tbody>
        </table>
        <?php if ( empty($data['items']) ) { ?>
            <div class="no_account">
                <img src="http://127.0.0.1/yf_shop/shop/static/default/images/ico_none.png">
                <p>暂无符合条件的数据记录</p>
            </div>
        <?php } ?>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
<script>
    
    $('.tabmenu').children().children('li:gt(1)').hide();

    $(function () {

        //添加标签
        
           $('.tabmenu').append('<a title="建立自配送模板" class="ncbtn ncbtn-mint bbc_seller_btns" style="" href="' + SITE_URL + '?ctl=Seller_Trade_Waybill&met=zpsAdd&typ=e' + '">新建自配送模板</a>');

        $('a[nctype="btn_del"]').on('click', function () {

            var _this = $(this),
                zps_tpl_id = _this.data('zps_tpl_id');

            $.post(SITE_URL + '?ctl=Seller_Trade_Waybill&met=removeZps&typ=json', {zps_tpl_id: zps_tpl_id}, function (data) {
                if ( data.status == 200 ) {
                    Public.tips( { content:data.msg, type:3 } );
                    _this.parents('tr').remove();
                } else {
                    Public.tips( { content:data.msg, type:1 } );
                }
            })
        })
    })
</script>
