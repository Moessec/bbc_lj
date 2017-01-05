<?php
class Common{
	var $db;
	function getData($sql,$scl='',$limit='',$rows)
	{
		global $db;
		$this ->db= & $db;
		$this->db->query($sql);		
		$total = $this->db->num_rows();
			
		$sql = $sql.$limit;
		$this->db->query($sql);
		$res = $this->db->getRows();
			
		$body_data_rows = array();
		$body_data_rows['status'] = 200;
		$body_data_rows['msg'] = 'success';
		$body_data_rows['data']['rows']   = $res;
		$body_data_rows['data']['total'] = ceil($total/$rows);
		$body_data_rows['data']['records'] =$total;
		$pro_data_rows = array('cmd_id'=>1) + $body_data_rows;
		header('Content-type: application/json');
		return json_encode($pro_data_rows);
	}
	
	
	function getMember($member_id)
	{	
		global $db;
		$this ->db= & $db;
		$sql="
			SELECT
				a.userid AS member_id,
				a.userid AS id,
				a.`user` AS member_name,
				a.sex AS member_sex,
				a.birth AS member_birth,
				a.mobile AS member_mobile,
				a.identification,
				a.qq AS member_qq,
				a.ww As member_ww,
				a.`real_name` AS member_realname,
				a.card_num AS member_cardnum,
				a.email AS member_email,
				a.operator,
				b.points AS member_points
			FROM
				mallbuilder_member a
			LEFT JOIN mallbuilder_member_info b ON a.userid = b.member_id
			WHERE
				a.userid = $member_id
		";
		$this->db->query($sql);
		$res = $this->db->fetchRow();
		$result['status']=200;
		$result['msg']='success';
		$result['data']=$res;
		header('Content-type: application/json');
		return json_encode($result);
	}
}
?>