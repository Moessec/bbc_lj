<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="renderer" content="webkit">
	
	<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
	<link href="<?=$this->view->css?>/common.css" rel="stylesheet">
	<link href="<?=$this->view->css?>/ui.min.css" rel="stylesheet">
	<link href="<?=$this->view->css?>/report.css" rel="stylesheet" />
	<script src="<?=$this->view->js?>/models/sea.js?ver=20150529" id="seajsnode"></script>
	<script src="<?=$this->view->js?>/libs/jquery/jquery-1.10.2.min.js"></script>
</head>

<style type="text/css">
	#filter-menu .con{ width:355px; }
	#filter-menu label.tit{ width:80px; }
	/*.ui-jqgrid tr.jqgrow td {white-space: normal !important;}*/
</style>
<body>

<div class="mod-report">
	<div class="search-wrap" id="report-search">
		<div class="s-inner cf">
			<div class="fl"> 
				<strong class="tit mrb fl">选择查询条件：</strong>
				<div class="ui-btn-menu fl" id="filter-menu"> 
					<span class="ui-btn menu-btn"> <strong id="selected-period">请选择查询条件</strong><b></b> </span>
					<div class="con">
						<ul class="filter-list">
							<li>
								<label class="tit">订单日期:</label>
								<input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate" maxlength="10" />
								<span>至</span>
								<input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate" maxlength="10" />
							</li>
						</ul>
						<div class="btns"> 
							<a href="#" id="conditions-trigger" class="conditions-trigger" tabindex="-1">更多条件<b></b></a> 
							<a class="ui-btn ui-btn-sp" id="filter-submit" href="#">确定</a> 
							<a class="ui-btn" id="filter-reset" href="#" tabindex="-1">重置</a> 
						</div>
					</div>
				</div>
				<a id="refresh" class="ui-btn ui-btn-refresh fl mrb"><b></b></a> 
				<span class="txt fl" id="cur-search-tip"></span> 
			</div>
			
			<div class="fr">
				<a href="#" class="ui-btn ui-btn-sp mrb fl" id="btn-print">打印</a><!-- <a href="#" class="ui-btn fl" id="btn-export">导出</a> -->
			</div>
		</div>
	</div>
  
	<div class="ui-print">
		<span id="config" class="ui-icon ui-state-default ui-icon-config"></span>
		<div class="grid-wrap" id="grid-wrap">
			<div class="grid-title">销售订单表</div>
			<div class="grid-subtitle"></div>
			<table id="grid"></table>
		</div>
	</div>
	<div class="no-query"></div>
</div>
<script>
seajs.use("salesOrderTracking");

<?php if($data['begin']){ ?> parent.SYSTEM.beginDate='<?=$data['begin']?>';<?php } ?>

</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>