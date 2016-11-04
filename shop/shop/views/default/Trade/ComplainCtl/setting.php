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
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>投诉管理</h3>
                <h5>商城对商品交易投诉管理及仲裁</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a id="1" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=1"><span>新投诉</span></a></li>
                <li><a id="2" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=2"><span>待申诉</span></a></li>
                <li><a id="3" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=3"><span>对话中</span></a></li>
                <li><a id="4" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=4"><span>待仲裁</span></a></li>
                <li><a id="5" href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=complain&state=5"><span>已关闭</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Trade_Complain&met=subject"><span>主题设置</span></a></li>
                <li><a class="current"><span>时效设置</span></a></li>
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>在投诉时效内，买家可对订单进行投诉，投诉主题由管理员在后台统一设置</li>
            <li>投诉时效可在系统设置处进行设置</li>
            <li>点击详细，可进行投诉审核。审核完成后，被投诉店铺可进行申诉。申诉成功后，投诉双方进行对话，最后由后台管理员进行仲裁操作</li>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="setting-form" name="form1">
        <input type="hidden" name="form_submit" value="ok"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">投诉时效</label>
                </dt>
                <dd class="opt">
                    <input id="complain_datetime" name="complain_datetime" value="<?=($data[0])?>" class="ui-input" type="text"  />

                    <p class="notic">单位为天，订单完成后开始计算，多少天内可以发起投诉</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function ()
    {
        if ($('#setting-form').length > 0)
        {
            $('#setting-form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
            fields: {
                'complain_datetime': 'required;integer[+];range[0~30];'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=editComplainDatetime&typ=json', $('#setting-form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
                            }
                            else
                            {
                                parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                            }
                        });
                    },
                    function ()
                    {
                    });
            },
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    }
});
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>