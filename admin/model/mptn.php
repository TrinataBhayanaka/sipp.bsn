<?php
class mptn extends Database {
	
	var $prefix = "lelang";

	function __construct()
	{
		parent::__construct();
	}

	function selectSS($id)
	{
		$sql = "SELECT * FROM bsn_news_content WHERE type='7' AND category='1' AND parent_id='{$id}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function getpk($kd,$fkd,$id=false)
	{
		if($id)$cond="AND a.id='{$id}'";else $cond="";
		$sql = "SELECT a.*,b.desc FROM th_pk as a, bsn_news_content as b WHERE a.kdunitkerja = '{$kd}' AND a.th = '2015' AND a.no_sasaran = b.id AND b.parent_id = '{$fkd}' AND b.type='7' AND b.category = '1' {$cond} ORDER BY a.no_sasaran, a.no_pk";
		// db($sql);
		$res = $this->fetch($sql,1);

		foreach ($res as $key => $value) {
			if($value['desc'] == $res[$key-1]['desc'])
			{
				$res[$key]['desc'] = "";
			}
		}
		if ($res) return $res;
		return false;
	}

	function insert_pk($data)
	{
		$sql = $this->insert($data,'th_pk');

		return true;
	}

	function edit_pk($data)
	{
		$sql = "UPDATE th_pk SET th = '{$data['th']}', no_sasaran = '{$data['no_sasaran']}', no_pk = '{$data['no_pk']}', nm_pk = '{$data['nm_pk']}', target = '{$data['target']}' WHERE id = '{$data['id']}'";
		$res = $this->query($sql);

		return true;
	}

	function delete_pk($id)
	{
		$sql = "DELETE FROM th_pk WHERE id = '{$id}'";
		$res = $this->query($sql);

		return true;

	}

	function getStruktur($type)
	{
		$sql = "SELECT * FROM bsn_struktur WHERE type='{$type}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
}
?>