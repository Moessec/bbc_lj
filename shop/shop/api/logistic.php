<?php
require_once '../configs/config.ini.php';

//Web_ConfigModel::value('kuaidi100_status');
$channel = Web_ConfigModel::value('logistics_channel');


$order_id   = request_string('order_id');
$express_id = request_string('express_id');
$nu         = request_string('shipping_code');



$ExpressModel = new ExpressModel();

if ('kuaidi100' == $channel)
{
	$api_id = Web_ConfigModel::value('kuaidi100_app_id');
	$api_sceret = Web_ConfigModel::value('kuaidi100_app_key');


	if($express_id && $nu)
	{
		$express_row = $ExpressModel->getOne($express_id);

		if ($express_row)
		{
			$express_pinyin = $express_row['express_pinyin'];

			//echo $str = lookorder($express_pinyin, $nu, $api_id, $api_sceret);

			$str = '{"message":"ok","status":"1","state":"3","data": [{"time":"2012-07-07 13:35:14","context":"客户已签收"}, {"time":"2012-07-07 09:10:10","context":"离开 [北京石景山营业厅] 派送中，递送员[温]，电话[]"}, {"time":"2012-07-06 19:46:38","context":"到达 [北京石景山营业厅]"}, {"time":"2012-07-06 15:22:32","context":"离开 [北京石景山营业厅] 派送中，递送员[温]，电话[]"}, {"time":"2012-07-06 15:05:00","context":"到达 [北京石景山营业厅]"}, {"time":"2012-07-06 13:37:52","context":"离开 [北京_同城中转站] 发往 [北京石景山营业厅]"}, {"time":"2012-07-06 12:54:41","context":"到达 [北京_同城中转站]"}, {"time":"2012-07-06 11:11:03","context":"离开 [北京运转中心驻站班组] 发往 [北京_同城中转站]"}, {"time":"2012-07-06 10:43:21","context":"到达 [北京运转中心驻站班组]"}, {"time":"2012-07-05 21:18:53","context":"离开 [福建_厦门支公司] 发往 [北京运转中心_航空]"}, {"time":"2012-07-05 20:07:27","context":"已取件，到达 [福建_厦门支公司]"} ]}';

			echo  $str;

			/*$str = json_decode($str,true);

			$msg = '';
			if($str['status'] == 1)
			{
				$msg = '<ul>';
				foreach($str['data'] as $key => $val)
				{
					$msg .='<li>'.$val['time'].$val['context'].'</li>';
				}
				$msg .='</ul>';
			}

			return $msg;*/
		}
		else
		{
			echo "document.write('暂时没有物流信息！');";
		}
	}
}
elseif ('kuaidiniao' == $channel)
{
	Web_ConfigModel::value('kuaidiniao_status');
	$kuaidiniao_express = decode_json(Web_ConfigModel::value('kuaidiniao_express'));

	$e_business_id = Web_ConfigModel::value('kuaidiniao_e_business_id');
	$app_key = Web_ConfigModel::value('kuaidiniao_app_key');


	$express_code = $express_row['express_pinyin'];

	/*
	if ($kuaidiniao_express)
	{
		$express_row = $ExpressModel->getOne($express_id);


		foreach ($kuaidiniao_express as $key=>$item)
		{
			if ($item == $express_code)
			{
				$shipper_code = $express_code;
				break;
			}
		}

		$shipper_code = $express_code;
	}
	*/

	$api = new Api_KdNiao($e_business_id, $app_key);

	$request_rows =
		array (
			'OrderCode' =>   $order_id,  //订单编号
			'ShipperCode' => $shipper_code, //物流公司编码
			'LogisticCode' => $nu            //物流单号
		);

	$rs_str =  $api->getOrderTracesByJson($request_rows);
	$rs_row = array();

	if ($rs_str)
	{
		$rs_row = json_decode($rs_str, true);
	}
	if (isset($rs_row['Success']) && $rs_row['Success'])
	{
		if ($rs_row['Traces'])
		{
			foreach ($rs_row['Traces'] as $trace)
			{
				//$trace['Remark']

				$msg = sprintf('<p>%s - %s</p>', $trace['AcceptTime'], $trace['AcceptStation']);
				echo "document.write('" . addslashes($msg) . "');";
			}

		}
		else
		{
			$msg = sprintf('<font color="red">%s</font>', $rs_row['Reason']);
			echo "document.write('" . addslashes($msg) . "');";
		}
	}
	else
	{
		if (isset($rs_row["Reason"]))
		{
			$msg = $rs_row["Reason"];
		}
		else
		{
			$msg = $rs_row["Message"];
		}

		$msg = sprintf('<font color="red">%s</font>', $msg);

		echo "document.write('" . addslashes($msg) . "');";
	}
}


//http://api.ickd.cn/?id=[]&secret=[]&com=[]&nu=[]&type=[]&encode=[]&ord=[]&lang=[]
/*com	必须	快递公司代码（英文），所支持快递公司见如下列表
nu	必须	快递单号，长度必须大于5位
id	必须	授权KEY，申请请点击快递查询API申请方式
在新版中ID为一个纯数字型，此时必须添加参数secret（secret为一个小写的字符串）
secret	必选(新增)	该参数为新增加，老用户可以使用申请时填写的邮箱和接收到的KEY值登录http://api.ickd.cn/users/查看对应secret值
type	可选	返回结果类型，值分别为 html | json（默认） | text | xml
encode	可选	gbk（默认）| utf8
ord	可选	asc（默认）|desc，返回结果排序
lang	可选	en返回英文结果，目前仅支持部分快递（EMS、顺丰、DHL）*/
function lookorder($com, $nu, $api_id, $api_sceret)
{
	//爱查快递
	$url2="http://api.ickd.cn/?com=".$com."&nu=".$nu."&id=".$api_id."&secret=".$api_sceret."&type=html&encode=utf8";

	//快递100  show=[0|1|2|3]

	$url2="http://api.kuaidi100.com/api?id=$api_id&com=$com&nu=$nu&valicode=[]&show=2&muti=1&order=desc";

	$con = file_get_contents($url2);
	return 'document.write("'.$con.'");';
}
?>