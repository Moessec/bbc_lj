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
.sum_staff{
  width: 871px;
  height: 40px;
  line-height: 40px;
  background-color:#F08080;
  text-align: center;
  font-size: 16px; 
  color: #fff;
}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
   <div class="sum_staff"><?php if(count($data)==0){?> <?php }else{?>人员总数<span><?php echo count($data);?></span>人<?php }?></div>
	<?php
      // var_dump(count($data));
        foreach ($data as $key => $value) {
      ?>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">活动参与人员</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">参与人姓名：</th>
        <td><?=$value['true_name']?></td>
      </tr>
      <tr>
        <th class="w150">联系方式：</th>
        <td><?=$value['usercontact']?></td>
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