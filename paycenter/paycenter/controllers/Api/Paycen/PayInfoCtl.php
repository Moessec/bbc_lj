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
 * @author     banchangle <1427825015@qq.com>
 * @copyright  Copyright (c) 2016, 班常乐
 * @version    1.0
 * @todo
 */
class Api_Paycen_PayInfoCtl extends Api_Controller
{
    /**
     *交易流水
     *
     * @access public
     */
    //获取卡片列表
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
//    展示info表中的数据
    function getInfoList() {
        $page = request_string('page', 1);
        $rows = request_string('rows', 20);
        $card_id  = request_string('cardName');   //卡片名称
        $beginDate = request_string('beginDate'); //卡片生成时间
        $User_InfoModel = new  Card_InfoModel();
        $data      = $User_InfoModel->getInfoList($card_id,$beginDate,$page,$rows);
//从paycard分配数据到info表中************
        $Card_BaseModel = new Card_BaseModel();
        $datas          = $Card_BaseModel->getBaseList();

        foreach($datas['items'] as $key=>$val){
            $paydata[]=$val['card_id'];
        }
        $pdata=json_encode($paydata);
        $data['card_id']=$pdata;

//        echo "<pre>";
//        print_r($data['card_id']);
//        echo "</pre>";
//        exit();
//*************************************
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
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //添加info表中的数据生成卡片
    public function add()
    {
        $Buyer_TestModel           = new Card_InfoModel();
        $data                      = array();
        $data['card_id']           = request_int('card_id');                  //卡id
        $length                  = request_string('card_sum');                //生成卡的数量
        for ($i=1; $i<=$length;$i++){
            $data['card_code']=$data['card_id'].Text_Password::create(4,unpronounceable,1234567890);
            $flag = $Buyer_TestModel->addInfo($data, true);

        }
        var_dump($data['card_code']);die;
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
//        ************************************************
//        $Card_BaseModel = new Card_BaseModel();
//        $data['cart_id']        = $Card_BaseModel->getByWhere();
//        foreach($data['cart_id'] as $key=>$val){
//            $item = array();
//            $item['id'] = $val['card_id'];
//            $item['name'] = $val['card_id'];
//            $paydata[]= $item;
//        }
//        $b=json_encode($paydata);
//        $data['cart_id']=$b;
//        echo "<pre>";
//        print_r($data['cart_id']);
//        echo "</pre>";
//        exit();
//        ************************************************
        $this->data->addBody(-140, $data, $msg, $status);

    }
    /*
     * 删除购物卡
     */
    public function remove()
    {
        $Card_InfoModel     = new Card_InfoModel();

        $card_code = request_int('card_code');
        if ($card_code)
        {
            $flag = $Card_InfoModel->delCardByCid($card_code);


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

        $data['card_code'] = $card_code;
        $this->data->addBody(-140, $data, $msg, $status);
    }
    function getEditInfo(){
        $user_id = request_int("user_id");
          $User_InfoModel = new User_InfoModel();
          $data           = $User_InfoModel->getOne($user_id);
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
        $this->data->addBody(-140, $data, $msg, $status);
    }
    
    function editInfoRow(){
            $user_id = request_int("user_id");
            $user_identity_statu = request_int("user_identity_statu");
            $User_InfoModel = new User_InfoModel();
            $flag           = $User_InfoModel->editInfo($user_id,array("user_identity_statu"=>$user_identity_statu));
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
            $data =array();
            $this->data->addBody(-140, $data, $msg, $status);
    }
    //修改充值卡
    public function editBases()
    {
        $Card_InfoModel     = new Card_InfoModel();
        $card_code                 = request_int('card_code');
        $data                      = array();
        $data['card_id']           = request_int('card_id');                  //卡id
        $data['user_id']           = request_string('user_id');
        $data['card_code']         = request_string('card_code');
        $data['card_password']     = request_string('card_password');
        $data['card_fetch_time']   = request_string('card_fetch_time');
        $data['card_media_id']     = request_string('card_media_id');
        $data['server_id']         = request_string('server_id');
        $data['user_account']      = request_string('user_account');
        $data['card_time']         = request_string('card_time');
        $data['card_money']        = request_string('card_money');
        $data['card_froze_money']  = request_string('card_froze_money');
        $flag = $Card_InfoModel->editInfo($card_code, $data, false);

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

}

?>