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
		if ($parent) $filter .= " AND parent_id = {$parent}";

		$sql = array(
                'table'=>"{$this->prefix}_news_content",
                'field'=>"*",
                'condition' => "n_status = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res)return $res;
        return false;
	}

	function getContent($data=array(), $table="_news_content", $debug=false)
	{
		$filter = "";
		$data['n_status'] = 1;
		foreach ($data as $key => $value) {
			$field[] = "{$key} = '{$value}'";
		}

		$filter .= implode(' AND ', $field);

		$sql = array(
                'table'=>"{$this->prefix}{$table}",
                'field'=>"*",
                'condition' => "{$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res)return $res;
        return false;
	}

	function saveData($data=array(), $table="_news_content", $debug=0)
	{
		
		if ($table == "_news_content"){
			$getSetting = $this->getSetting();
			$data['year'] = $getSetting[0]['kode'];

		}
		
		$id = $data['id'];

		if ($id){

			$run = $this->save("update", "{$this->prefix}{$table}", $data, "id = {$id}", $debug);

		}else{
			$data['createDate'] = date('Y-m-d H:i;s');
			$run = $this->save("insert", "{$this->prefix}{$table}", $data, false, $debug);
	
		}

		if ($run) return true;
		return false;
	}

	function getStruktur($data=array())
	{

		$data['n_status'] = 1;

		$getData = $this->fetchSingleTable("{$this->prefix}_struktur", $data);
		if ($getData) return $getData;
		return false;
	}

	function getSetting($activity="tahun_sistem")
	{
		$data['n_status'] = 1;
		$data['desc'] = $activity;

		$getData = $this->fetchSingleTable("{$this->prefix}_sistem_setting", $data);
		if ($getData) return $getData;
		return false;
	}

	function getDocument($id=false, $type=1, $debug=false)
	{
		$filter = "";
		$sql = array(
                'table'=>"{$this->prefix}_news_content",
                'field'=>"*",
                'condition' => "type = {$type} AND n_status = 1 {$filter} ORDER BY id DESC",
                'limit'=>1
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res)return $res;
        return false;
	}

	function getDataTable($table, $data)
	{
		$data['n_status'] = 1;
		
		$getData = $this->fetchSingleTable("{$table}", $data);
		if ($getData) return $getData;
		return false;
	}


}
?>