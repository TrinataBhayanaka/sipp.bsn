<?php
class contentHelper extends Database {
	
	var $prefix = "lelang";

	function __construct()
	{
		parent::__construct();
	}

	function getData()
	{
		$sql = "SELECT * FROM code_activity";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function getMessage()
	{
		$sql = "SELECT m.*, um.name,um.email FROM my_message m LEFT JOIN user_member um ON m.receive = um.id ";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function saveMessage($data)
	{
		foreach ($data as $key => $val){
			$tmpfield[] = $key;
			$tmpdata[] = "'$val'";
		}
		// from,to,subject,content,createdate,n_status
		$tmpfield[] = 'fromwho';
		$tmpfield[] = 'createdate';
		$tmpfield[] = 'n_status';
		
		$date = date('Y-m-d H:i:s');
		$tmpdata[] = 0;
		$tmpdata[] = "'{$date}'";
		$tmpdata[] = 1;
		
		$field = implode(',',$tmpfield);
		$data = implode(',',$tmpdata);
		
		$sql = "INSERT INTO my_message ({$field}) 
				VALUES ({$data})";
		// pr($sql);
		// exit;
		$res = $this->query($sql);
		if ($res) return true;
		return false;
	}
	
	function get_user($data)
	{
		$query = "SELECT * FROM user_member WHERE email = '{$data}'";
		
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	

	function getOnlineUser($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"user",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) AND is_online = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	/* renstra */
	
	function getVisi($id=false, $type=5, $cat=0, $parent=0, $debug=false)
	{
		$filter = "";
		if ($id) $filter .= " AND id = {$id}";
		if ($type) $filter .= " AND type = {$type}";
		if ($cat) $filter .= " AND category = {$cat}";
		// if ($parent) $filter .= " AND parent_id = {$parent}";

		$sql = array(
                'table'=>"{$this->prefix}_news_content",
                'field'=>"*",
                'condition' => "n_status = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res){
        	/*foreach ($res as $key => $value) {
        		$sql = array(
		                'table'=>"{$this->prefix}_news_content",
		                'field'=>"*",
		                'condition' => "n_status = 1 AND parent_id = {$value['id']}"
		                );

		        $result = $this->lazyQuery($sql,$debug);
		        if ($result) $res[$key]['child'] = $result;
        	}*/

        	return $res;
        }
		return false;
	}

	function saveData($data)
	{

		$id = $data['id'];

		if ($id){

			$run = $this->save("update", "{$this->prefix}_news_content", $data, "id = {$id}");

		}else{
			$data['createDate'] = date('Y-m-d H:i;s');
			$run = $this->save("insert", "{$this->prefix}_news_content", $data);
	
		}

		if ($run) return true;
		return false;
	}

	
}
?>