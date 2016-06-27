<?php
class contentHelper extends Database {
	
	var $prefix = "bsn";

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
	
	function getVisi($id=false, $type=5, $cat=0, $parent=0, $other=false, $debug=false)
	{
		$filter = "";
		if ($id) $filter .= " AND id = {$id}";
		if ($type) $filter .= " AND type = {$type}";
		if ($cat) $filter .= " AND category = {$cat}";
		if ($parent) $filter .= " AND parent_id = {$parent}";
		if ($other) $filter .= " AND {$parent}";

		$getSetting = $this->getSetting();
		$year = $getSetting[0]['kode'];
		if ($year) $filter .= " AND year = '{$year}'";

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
		// pr($data);
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
		// pr($data);
		// pr($run);
		// exit;
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

  	function fetchData($data=array(),$debug=false)
    {

    	$getSetting = $this->getSetting();
		$year = $getSetting[0]['kode'];

		if ($data['table'] == "{$this->prefix}_news_content") $data['condition']['year'] = $year;
		
        $table = $data['table'];
        $condition = $data['condition'];
        $oderby = $data['oderby'];
        $additional = $data;

        $fetch = $this->fetchSingleTable($table, $condition, $oderby, $additional, $debug);
        if ($fetch) return $fetch;
        return false;
    }

    function getStructure($table){
    	$sql = "select * from information_schema.columns where table_schema = '{$table}' order by table_name,ordinal_position";
    	
    	$result = $this->fetch($sql,1);

    	return $result;
    }

    function gantiTabel()
    {
    	$sql = "ALTER TABLE dt_fileupload_keu MODIFY tgl_upload datetime";
    	$result = $this->query($sql);
    	$sql = "INSERT INTO dt_fileupload_keu (kdfile,nama_file,user_upload,type,keterangan,KDSATKER,tgl_upload) VALUES ('M_SPMIND','m_spmind.dbf','1','sakpa','File Realisasi Total','840000','2016-04-26 12:30:59')";
    	$result = $this->query($sql);
    	// $sql = "ALTER TABLE m_spmmak MODIFY NOSP2D varchar(100)";
    	// $result = $this->query($sql);

    	// $sql = "SET SQL_MODE = 'NO_ENGINE_SUBSTITUTION'";
    	// $res = $this->query($sql);

    	$sql = "SELECT @@SQL_MODE";
    	$result = $this->fetch($sql,1);
    	db($result);
    	return 1;
    }

    function getDesc($table)
    {
    	$sql = "desc {$table}";
    	$result = $this->fetch($sql,1);
    	db($result);
    	return 1;	
    }
	
	
	function getdatadebug($table,$where){
		$sql = "select * from {$table} {$where}";
		pr($sql);
    	$result = $this->fetch($sql,1);
		return $result;	
	}
	
	function deltable($table,$where){
		$sql = "DELETE FROM {$table} {$where}";
		pr($sql);
		// exit;
		$result = $this->query($sql);
		// return $result;	
	}
	
}
?>