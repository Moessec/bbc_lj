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
class Api_Paycen_PayRecordCtl extends Api_Controller
{
    /**
     *交易流水
     *
     * @access public
     */
    
    function getRecordList() {
        $username  = request_string('userName');   //用户名称
        $trade_type_id = request_int('trade_type_id');
        
        $cond_row = array();
        $Consume_RecordModel = new Consume_RecordModel();
        $data           = $Consume_RecordModel->getRecordList(null,null,null,1,100,'asc',$username,$trade_type_id);
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
    

}

?>