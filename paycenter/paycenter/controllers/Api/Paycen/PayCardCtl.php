<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Paycen_PayCardCtl extends Api_Controller
{
    public $cardInfoModel    = null;
    /**
     * 初始化方法，构造函数
     *
     * @access public
     */
    public function init()
    {
        $this->cardInfoModel    = new Card_InfoModel();

    }

    //获取支付卡列表
    public function getCardBaseList()
    {
        $cardname  = request_string('cardName');   //卡片名称
        $beginDate = request_string('beginDate');
        $endDate   = request_string('endDate');
        $appid     = request_int('appid');

        $page = request_string('page', 1);
        $rows = request_string('rows', 20);

        $Card_BaseModel = new Card_BaseModel();
        $data           = $Card_BaseModel->getBaseList($cardname, $appid, $beginDate, $endDate, $page, $rows);


        $Card_InfoModel = new Card_InfoModel();
        foreach ($data['items'] as $key => $val)
        {
            $card_used_num                        = $Card_InfoModel->getCardusednumBy($val['card_id']);
            $data['items'][$key]['card_used_num'] = $card_used_num;

            $card_new_num                        = $Card_InfoModel->getCardnewnumBy($val['card_id']);
            $data['items'][$key]['card_new_num'] = $card_new_num;
        }

        if ($data)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {
            $msg    = 'failure';
            $status = 250;
        }
        fb($data);
        $this->data->addBody(-140, $data, $msg, $status);
    }
    //添加购物卡
    public function add()
    {
        $Buyer_TestModel           = new Card_BaseModel();
        $data                       = array();
        $data['card_id']         = request_int('card_id');                  //卡id
        $data['card_name']       = request_string('card_name');             //卡名称
        $data['card_num']        = request_string('card_num');              //卡数量
        $data['card_start_time']        = request_string('card_start_time');//卡开始有效时间
        $data['card_end_time']        = request_string('card_end_time');    //卡的有效结束时间card_desc
        $data['card_desc']        = request_string('card_desc');            //卡的描述
        $data_rows =array();
        $data_rows['m'] = request_float('money');
        $data_rows['p'] = request_float('point');
        $data['card_prize'] = json_encode($data_rows);                      //卡的积分和金额
        $flag = $Buyer_TestModel->addBase($data, true);
        if ($flag)
        {
            $msg    = 'failure';
            $status = 250;
        }
        else
        {
            $msg    = 'success';
            $status = 200;
        }
        $data['card_id'] = $flag;
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /*
      * 删除购物卡
      */
    public function remove()
    {
        $Card_BaseModel     = new Card_BaseModel();

        $cat_id = request_int('cat_id');
        if ($cat_id)
        {
            $flag = $Card_BaseModel->removeBase($cat_id);


        }
        if ($flag)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {

            $msg    = 'failure';
            $status = 250;

        }

        $data['cat_id'] = $cat_id;
        $this->data->addBody(-140, $data, $msg, $status);
    }
    //修改购物卡
    public function editBases()
    {
        $Card_BaseModel            = new Card_BaseModel();
        $card_id                   = request_int('card_id');
        $data                      = array();
        $data['card_id']           = request_int('card_id');                  //卡id
        $data['card_name']         = request_string('card_name');             //卡名称
        $data['card_num']          = request_string('card_num');              //卡数量
        $data['card_start_time']   = request_string('card_start_time');       //卡开始有效时间
        $data['card_end_time']     = request_string('card_end_time');         //卡的有效结束时间card_desc
        $data['card_desc']         = request_string('card_desc');             //卡的描述
        $data_rows =array();
        $data_rows['m'] = request_float('money');
        $data_rows['p'] = request_float('point');
        $data['card_prize'] = json_encode($data_rows);                        //卡的积分和金额

        $flag = $Card_BaseModel->editBase($card_id, $data, false);

        if ($flag)
        {
            $msg    = 'success';
            $status = 200;
        }
        else
        {

            $msg    = 'failure';
            $status = 250;

        }
        $this->data->addBody(-140, $data, $msg, $status);

    }

    /**
     * 获取购物卡详情
     *
     * @access public
     */
    public function getCardlist()
    {
        $card_id = request_int('card_id');
        $data    = $this->cardInfoModel->getListCardInfoByCardId($card_id);
//        echo "<pre>";
//        print_r($data);
//        echo "<pre>";
        $this->data->addBody(-140, $data);
    }
    //批量删除
//    public function removeBaseSelected($config_array = array())
//    {
//
//        foreach ($config_array as $key => $value)
//        {
//            $flag = $this->removeBase($value);
//        }
//    }


}

?>