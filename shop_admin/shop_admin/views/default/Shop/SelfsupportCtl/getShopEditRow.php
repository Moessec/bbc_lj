<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>

<body>

        <form method="post" enctype="multipart/form-data" id="shop_edit_class" name="form1">
          <input  name="shop_id" id="shop_id" value="<?=$data['shop_id']?>"  type="hidden"/>
        <div class="ncap-form-default">
              <dl class="row">
                <dt class="tit">
                    <label for="shop_name"> *店铺名称</label>
                </dt>
                <dd class="opt">
                    <input id="shop_name" name="shop_name" value="<?=$data['shop_name']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="shop_address"> *店铺地址</label>
                </dt>
                <dd class="opt">
                    <input id="shop_address" name="shop_address" value="<?=$data['shop_address']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="shop_opening"> *营业时间</label>
                </dt>
                <dd class="opt">
                    <input id="shop_opening" name="shop_opening" value="<?=$data['shop_opening']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="shop_tel"> *店铺电话</label>
                </dt>
                <dd class="opt">
                    <input id="shop_tel" name="shop_tel" value="<?=$data['shop_tel']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="shop_longitude"> *经度</label>
                </dt>
                <dd class="opt">
                    <input id="shop_longitude" name="shop_longitude" value="<?=$data['shop_longitude']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="shop_latitude"> *纬度</label>
                </dt>
                <dd class="opt">
                    <input id="shop_latitude" name="shop_latitude" value="<?=$data['shop_latitude']?>" class="ui-input w200" type="text"/>
                </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_create_time"> *开店时间</label>
                </dt>
                <dd class="opt">
                   <?=$data['shop_create_time']?>
                </dd>
              </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="shop_all_class">绑定所有类目</label>
                </dt>
                <dd class="opt">
                <div class="onoff">
                        <label for="shop_all_class1" class="cb-enable <?=($data['shop_all_class'] ? 'selected' : '')?> ">开启</label>
                        <label for="shop_all_class0" class="cb-disable <?=(!$data['shop_all_class'] ? 'selected' : '')?>">关闭</label>
                        <input id="shop_all_class1"  name ="shop_all_class" <?=($data['shop_all_class'] ? 'checked' : '')?>  value="1" type="radio">
                        <input id="shop_all_class0"  name ="shop_all_class"  <?=(!$data['shop_all_class'] ? 'checked' : '')?>   value="0" type="radio">
                       
                    </div>
                 </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="shop_status">状态</label>
                </dt>
                <dd class="opt">
                <div class="onoff">
                        <label for="shop_status1" class="cb-enable <?=($data['shop_status'] ? 'selected' : '')?> ">开启</label>
                        <label for="shop_status0" class="cb-disable <?=(!$data['shop_status'] ? 'selected' : '')?>">关闭</label>
                        <input id="shop_status1"  name ="shop_status" <?=($data['shop_status'] ? 'checked' : '')?>  value="3" type="radio">
                        <input id="shop_status0"  name ="shop_status"  <?=(!$data['shop_status'] ? 'checked' : '')?>   value="0" type="radio">
                       
                    </div>
                 </dd>
              </dl>
        
          
          
        </div>
    </form>

    <script>

function initPopBtns()
{
    var t = "Add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            console.log(rowData);
            postData(oper, rowData.shop_id);
           return cancleGridEdit(),$("#shop_edit_class").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
 
	$_form.validator({
               messages: {
                    required: "请填写该字段",
           },
            fields: {
                'shop_name':'required;' ,
                'shop_status':'required;',
                'shop_latitude':'required;',
                'shop_longitude':'required;',
                'shop_address':'required;',
                'shop_tel':'required;',
                'shop_opening':'required;',


            },

        valid: function (form)
        {
            var 
              shop_id = $.trim($("#shop_id").val()), 
              shop_name = $.trim($("#shop_name").val()), 
              shop_address = $.trim($("#shop_address").val()), 
              shop_tel = $.trim($("#shop_tel").val()), 
              shop_opening = $.trim($("#shop_opening").val()), 
              shop_longitude = $.trim($("#shop_longitude").val()), 
              shop_latitude = $.trim($("#shop_latitude").val()), 
              shop_all_class = $.trim($("input[name='shop_all_class']:checked").val()),
              shop_status = $.trim($("input[name='shop_status']:checked").val()),

			n = "Add" == t ? "新增店铺" : "修改店铺";
			params =  {
				shop_id: shop_id, 
        shop_name: shop_name, 
        shop_address: shop_address, 
				shop_tel: shop_tel, 
				shop_all_class: shop_all_class,
        shop_status:shop_status,
        shop_opening:shop_opening,
        shop_longitude:shop_longitude,
        shop_latitude:shop_latitude,
                               
			};
			Public.ajaxPost(SITE_URL +"?ctl=Shop_Selfsupport&met=" + ("Add" == t ? "Add" : "Edit")+ "ShopBase&typ=json", params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: n + "成功！"});
					callback && "function" == typeof callback && callback(e.data, t, window)
				}
				else
				{
					parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
				}
			})
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    });
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
function resetForm(t)
{
    $_form.validate().resetForm();
    $("#shop_name").val("");
    $("#shop_address").val("");
    $("#shop_tel").val("");
    $("#shop_longitude").val("");
    $("#shop_latitude").val("");
    $("input[name='shop_all_class']:checked").val("");
    $("input[name='shop_status']:checked").val("");

}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#shop_edit_class"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();

    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
