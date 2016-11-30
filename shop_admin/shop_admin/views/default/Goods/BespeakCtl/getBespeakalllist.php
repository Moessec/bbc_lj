<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.store-joinin thead th {
    font-weight: 600;
    color: #FFF;
    background-color: #CCC;
    height: 20px;
    padding: 8px 5px;
    border-style: solid;
    border-width: 1px 1px 0 0;
    border-color: #CCC #CCC transparent transparent;
}
th{
	padding-left:10px;
	line-height: 30px;
}
.store-joinin {
    width: 871px;
    line-height: 20px;
    border-style: solid;
    border-width: 0 0 1px 1px;
    border-color: transparent transparent #CCC #CCC;
    margin: 10px 0 20px 0;
    box-shadow: 2px 2px 2px rgba(204,204,204,0.25);
}
.manage-wrap{
	    overflow: hidden;
    padding: 10px 3% 0;
    text-align: left;
}
input, select, textarea {
    font: 12px/1.5 '微软雅黑', 'Microsoft Yahei', \5b8b\4f53;
    color: #555;
}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<?php
	foreach ($data as $key => $value) {

	?>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">报修信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">报修人姓名：</th>
        <td><?=$value['true_name']?></td>
      </tr>
      <tr>
        <th class="w150">报修情况:</th>
        <td><?=$value['bespeak_title']?></td>
      </tr>
      <tr>
        <th>报修人联系方式：</th>
        <td><?=$value['usercontact']?></td>
      </tr>
      <tr>
        <th>所在地区</th>
        <td> <?=$value['bes_address']?></td>
      </tr>
    
      <tr>
        <th>详细地址：</th>
        <td> <?=$value['bespeak_address']?></td>
      </tr>
      <tr>
        <th>详细状况：</th>
        <td> <?=$value['bespeak_com']?></td>
      </tr>
      <tr>
        <th>相关图片：</th>
        <td>
       <?php
          $img=explode('--', $value['bespeak_img']);
          $img=array_filter($img);
          foreach ($img as $k => $v) {
          ?>
          <img src="http://139.196.51.206/bbc_lj/\shop\shop\data\<?=$v?>" style="width:200px;height:200px; float:left; marrgin-left:20px">
          <?}?>
          </td>
      </tr>
    </tbody>
  </table>
  <?
  	}
	?>
</div>


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>