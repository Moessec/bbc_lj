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
class Api_Paycen_PayWithdrawCtl extends Api_Controller
{
    /**
     *支付会员
     *
     * @access public
     */
    
    function getPayWithdrawList() {
//          $username  = request_string('userName');   //用户名称
          $cond_row = array();
//          if($username){
//                $cond_row = array( "user_account" => $username);
//          }
          $Consume_WithdrawModel = new Consume_WithdrawModel();
            $data = array();
          $data           = $Consume_WithdrawModel->getWithdrawList($cond_row);
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
    
    function getEditWithdraw() {
        $id = request_int("id");
        $Consume_WithdrawModel = new Consume_WithdrawModel();
        $data = $Consume_WithdrawModel->getOne($id);
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
    
    function editWithdrawRow() {
            $id = request_int("id");
            $data['is_succeed'] = request_int("is_succeed");
            $Consume_WithdrawModel = new Consume_WithdrawModel();
            $Withdrawlist = $Consume_WithdrawModel->getOne($id);
            $flag = $Consume_WithdrawModel->editWithdraw($id,$data);
            if ($flag!==false)
            {
                 if( $data['is_succeed'] == 3){
                
                    //实例化流水表
                 $Consume_RecordModel = new Consume_RecordModel(); 
                 //用充值的订单id查询出流水表信息
                 $cond_row['order_id'] = $Withdrawlist['orderid'];
                 $record_list = $Consume_RecordModel->getOneByWhere($cond_row);
                 
                 //更改流水表的信息
                 $flag1 = $Consume_RecordModel->editRecord($record_list['consume_record_id'],array("record_status"=>2));
                 if($flag1!==false)
                 {
                    $msg    = 'success';
                    $status = 200; 
                 }
                 else
                 {
                     $msg    = 'failure';
                     $status = 250; 
                 }
                }elseif($data['is_succeed'] == 4){
                    
              
                    
                    
                }
                 
            }
            else
            {
                $msg    = 'failure';
                $status = 250;
            }
            $data['id'] =$id ;
            
            $this->data->addBody(-140, $data, $msg, $status);
    }
 
}
?>