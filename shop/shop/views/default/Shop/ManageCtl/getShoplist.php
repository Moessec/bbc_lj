<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/shop_table.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<style>

.ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
.img_flied img{width: 100px; height: 30px;}

</style>
<div style="   overflow: hidden;
    padding: 10px 3% 0;
    text-align: left;" >
    <?php 
    foreach ($data['base'] as $key => $value) {
    if(!empty($value['shop_company_name'])){
    ?>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">公司及联系人信息</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">公司名称：</th>
        <td colspan="20"><?=$value['shop_company_name']?></td>
      </tr>
      <tr>
        <th>公司所在地：</th>
        <td><?=$value['shop_company_address']?></td>
        <th>公司详细地址：</th>
        <td colspan="20"><?=$value['company_address_detail']?></td>
      </tr>
      <tr>
        <th>公司电话：</th>
        <td><?=$value['company_phone']?></td>
        <th>员工总数：</th>
        <td><?=$value['company_employee_count']?>&nbsp;人</td>
        <th>注册资金：</th>
        <td><?=$value['company_registered_capital']?>&nbsp;万元 </td>
      </tr>
      <tr>
        <th>联系人姓名：</th>
        <td><?=$value['contacts_name']?></td>
        <th>联系人电话：</th>
        <td><?=$value['contacts_phone']?></td>
        <th>电子邮箱：</th>
        <td><?=$value['contacts_email']?></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">营业执照信息（副本）</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">营业执照号：</th>
        <td><?=$value['business_id']?></td>
      </tr>
      <tr>
        <th>营业执照所在地：</th>
        <td><?=$value['business_license_location']?></td>
      </tr>
      <tr>
        <th>营业执照有效期：</th>
        <td> <?=$value['business_licence_start']?> - <?=$value['business_licence_end']?></td>
      </tr>
    
      <tr>
        <th>营业执照<br />
          电子版：</th>
        <td colspan="20"> <img src="<?=$value['business_license_electronic']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">组织机构代码证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>组织机构代码：</th>
        <td colspan="20"><?=$value['organization_code']?></td>
      </tr>
      <tr>
        <th>组织机构代码证<br/>
          电子版：</th>
        <td colspan="20"> <img src="<?=$value['organization_code_electronic']?>" alt="" /> </td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">一般纳税人证明：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>一般纳税人证明：</th>
        <td colspan="20"><img src="<?=$value['general_taxpayer']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">开户银行信息：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">银行开户名：</th>
        <td><?=$value['bank_account_name']?></td>
      </tr>
      <tr>
        <th>公司银行账号：</th>
        <td><?=$value['bank_account_number']?></td>
      </tr>
      <tr>
        <th>开户银行支行名称：</th>
        <td><?=$value['bank_name']?></td>
      </tr>
      <tr>
        <th>支行联行号：</th>
        <td><?=$value['bank_code']?></td>
      </tr>
      <tr>
        <th>开户银行所在地：</th>
        <td colspan="20"><?=$value['bank_address']?></td>
      </tr>
      <tr>
        <th>开户银行许可证<br/>
          电子版：</th>
        <td colspan="20"><img src="<?=$value['bank_licence_electronic']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>
<!--  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">结算账号信息：</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">银行开户名：</th>
        <td>银行开户名</td>
      </tr>
      <tr>
        <th>公司银行账号：</th>
        <td>888888888888</td>
      </tr>
      <tr>
        <th>开户银行支行名称：</th>
        <td>天津支行</td>
      </tr>
      <tr>
        <th>支行联行号：</th>
        <td>88888888</td>
      </tr>
      <tr>
        <th>开户银行所在地：</th>
        <td>天津 天津市 红桥区</td>
      </tr>
    </tbody>
  </table>-->
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">税务登记证</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150">税务登记证号：</th>
        <td><?=$value['tax_registration_certificate']?></td>
      </tr>
      <tr>
        <th>纳税人识别号：</th>
        <td><?=$value['taxpayer_id']?></td>
      </tr>
      <tr>
        <th>税务登记证号<br />
          电子版：</th>
        <td><a nctype="nyroModal"  href=""> <img src="<?=$value['tax_registration_certificate_electronic']?>" alt="" /> </a></td>
      </tr>
    </tbody>
  </table>
  <form id="form_store_verify" action="index.php?act=store&op=store_joinin_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="member_id" type="hidden" value="2" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20">店铺经营信息</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150">商家账号：</th>
          <td><?=$value['user_name']?></td>
        </tr>
        <tr>
          <th class="w150">店铺名称：</th>
          <td><?=$value['shop_name']?></td>
        </tr>
        <tr>
          <th>店铺等级：</th>
          <?php if(!empty($value['shop_grade'])){ foreach ($value['shop_grade'] as $keys=>$val){ ?>
          
          <td><?=$val['shop_grade_name']?>（开店费用：<?=$val['shop_grade_fee']?> 元/年）</td>
          
        
        </tr>
        <tr>
          <th class="w150">开店时长：</th>
          <td><?=$value['joinin_year']?> 年</td>
        </tr>
        <tr>
          <th>店铺分类：</th>
           <?php foreach ($value['shop_class'] as $keyss=>$vals){ ?>
          <td><?=$vals['shop_class_name']?>（开店保证金：<?=$vals['shop_class_deposit']?> 元）</td>
  
        </tr>
        <tr>
          <th>应付总金额：</th>
          <td>    <?=$val['shop_grade_fee']*$value['joinin_year']+$vals['shop_class_deposit'] ?> 元
            </td>
        </tr>
          <?php }}} ?>
        <tr>
          <th>经营类目：</th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <thead>
                <tr>
                  <th>分类1</th>
                  <th>分类2</th>
                  <th>分类3</th>
                  <th>分类4</th>
                  <th>比例</th>
                </tr>
              </thead>
              <tbody>
              <?php if(!empty($value["classbind"]['product_parent_name'])){ foreach($value["classbind"]['product_parent_name'] as $keys => $vals){
                  ?>
                  <tr>
                  <?php $i=0; foreach ($vals as $keyss => $valss) { ?>
                        <td><?=$valss['cat_name']?></td>
                   <?php $i++; }?>
                  <?php if($i==1){ ?>
                        <td></td>
                        <td></td>
                        <td></td>
                  <?php }elseif($i==2){?>
                        <td></td>
                        <td></td>
                  <?php }elseif($i==3){ ?>
                         <td></td>
                    <?php }else{}?>  
                  <td><?=$value["classbind"]['commission_rate'][$keys]?>%</td>
                </tr>
              <?php } }?>
                
                </tbody>
              </table>
                
          </td>
        </tr>
                <tr>
          <th>付款凭证：</th>
          <td><img src="<?=$value['payment_voucher']?>" alt="" /></td>
        </tr>
        <tr>
          <th>付款凭证说明：</th>
          <td><?=$value['payment_voucher_explain']?></td>
        </tr>
                      </tbody>
    </table>
      </form>
    
    <?php }}?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>