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
		//pr($sql);
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
		//pr($sql);
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
    	pr($sql);
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
	
	function altertabel(){
		$sql_1 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_1` `target_1` DECIMAL(10,2) NULL DEFAULT '0.00'";
	
		$sql_2 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_2` `target_2` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_3 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_3` `target_3` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_4 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_4` `target_4` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_5 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_5` `target_5` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_6 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_6` `target_6` DECIMAL(10,2) NULL DEFAULT '0.00'";
	
		$sql_7 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_7` `target_7` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_8 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_8` `target_8` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_9 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_9` `target_9` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_10 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_10` `target_10` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_11 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_11` `target_11` DECIMAL(10,2) NULL DEFAULT '0.00'";
		
		$sql_12 = "ALTER TABLE `thbp_kak_output_tahapan` CHANGE `target_12` `target_12` DECIMAL(10,2) NULL DEFAULT '0.00'";

		$all_sql = array($sql_1,$sql_2,$sql_3,$sql_4,$sql_5,$sql_6,
						$sql_7,$sql_8,$sql_9,$sql_10,$sql_11,$sql_12);

		for ($i=0; $i<count($all_sql);$i++){
			//pr($all_sql[$i]);
			//exit;
			$result = $this->query($all_sql[$i]);
		}
	
	}

	function refOutput($table,$loop=0,$where=false,$order=false,$limit=false)
    {
    	if($where) $where = "WHERE ".$where;
        if($order) $order = "ORDER BY ".$order;
        if($limit) $limit = "LIMIT ".$limit;
        $sql = "SELECT * FROM {$table} {$where} {$order} {$limit}";
		$res = $this->fetch($sql,$loop);

        return $res;
    }

    function altertabelmonev(){
		$sql = "ALTER TABLE monev_bulanan
    			  ENGINE=InnoDB
    			  ROW_FORMAT=COMPRESSED
    			  KEY_BLOCK_SIZE=8;";

		$result = $this->query($sql);
		return $result;
	}

	function altertabelrpk(){
		$sql = "ALTER TABLE thbp_kak_output
    			  ENGINE=InnoDB
    			  ROW_FORMAT=COMPRESSED
    			  KEY_BLOCK_SIZE=8;";
    	pr($sql);		  
		$result = $this->query($sql);
		if($result)return $result;
	}

	function altertabelrpkrevisi(){
		$sql_1 = "ALTER TABLE `thbp_kak_output` CHANGE `ursasaran_1` `ursasaran_1` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
	
		$sql_2 = "ALTER TABLE `thbp_kak_output` CHANGE `ursasaran_2` `ursasaran_2` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_3 = "ALTER TABLE `thbp_kak_output` CHANGE `ursasaran_3` `ursasaran_3` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_4 = "ALTER TABLE `thbp_kak_output` CHANGE `ursasaran_4` `ursasaran_4` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_5 = "ALTER TABLE `thbp_kak_output` CHANGE `tujuan` `tujuan` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;;";

		$all_sql = array($sql_1,$sql_2,$sql_3,$sql_4,$sql_5);

		for ($i=0; $i<count($all_sql);$i++){
			//pr($all_sql[$i]);
			//exit;
			$result = $this->query($all_sql[$i]);
		}
		if($result)return $result;

	}

	function altertabelmonevrevisi(){
		$sql_1 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan` `keterangan` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
	
		$sql_2 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_2` `keterangan_2` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_3 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_3` `keterangan_3` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_4 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_4` `keterangan_4` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_5 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_5` `keterangan_5` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";

		$sql_6 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_6` `keterangan_6` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_7 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_7` `keterangan_7` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";

		$sql_8 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_8` `keterangan_8` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_9 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_9` `keterangan_9` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_10 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_10` `keterangan_10` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_11 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_11` `keterangan_11` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$sql_12 = "ALTER TABLE `monev_bulanan` CHANGE `keterangan_12` `keterangan_12` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;";
		
		$all_sql = array($sql_1,$sql_2,$sql_3,$sql_4,$sql_5,$sql_6,
						$sql_7,$sql_8,$sql_9,$sql_10,$sql_11,$sql_12);

		for ($i=0; $i<count($all_sql);$i++){
			//pr($all_sql[$i]);
			//exit;
			$result = $this->query($all_sql[$i]);
		}
		if($result)return $result;

	}


	function upddebug($id){
		pr("masukk model");
		$sql = "UPDATE bsn_struktur SET n_status = 1 WHERE id ='{$id}'";
		//pr($sql);
		$result = $this->query($sql);
		//return $result;
	}

	function altertabelmonevpp39(){
		$sql = "ALTER TABLE `monev_bulanan` CHANGE `kdkmpnen` `kdkmpnen` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";
		//pr($sql);
		$result = $this->query($sql);
		
		return $result;
	}


	
}
?>