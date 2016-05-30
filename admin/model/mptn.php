<?php
class mptn extends Database {
	
	var $prefix = "lelang";

	function __construct()
	{
		parent::__construct();
	}

	function selectSS($id,$kd=false)
	{
		if($kd){
			$sql = "SELECT id FROM bsn_struktur WHERE kode = '{$kd}'";
			$res = $this->fetch($sql);
			$id=$res['id'];
		}
		$sql = "SELECT * FROM bsn_news_content WHERE type='7' AND category='1' AND parent_id='{$id}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function getpk($kd,$fkd,$id=false,$thn)
	{
		if($id)$cond="AND a.id='{$id}'";else $cond="";
		$sql = "SELECT id FROM bsn_struktur WHERE kode = '{$kd}'";
		$res = $this->fetch($sql);
		// pr($res);
		$fkd=$res['id'];
		$sql = "SELECT a.*,b.desc FROM th_pk as a, bsn_news_content as b WHERE a.kdunitkerja = '{$kd}' AND a.th = '{$thn}' AND a.no_sasaran = b.id AND b.parent_id = '{$fkd}' AND b.type='7' AND b.category = '1' {$cond} ORDER BY a.no_sasaran, a.no_pk";
		// db($sql);
		$res = $this->fetch($sql,1);

		foreach ($res as $key => $value) {
			if($value['desc'] == $res[$key-1]['desc'] || $value['desc'] == $tmp)
			{
				$res[$key]['desc'] = "";
				$tmp = $value['desc'];
			} else {
				$tmp = "";
			}
		}
		if ($res) return $res;
		return false;
	}

	function getpkSS($kd,$fkd,$id=false,$thn)
	{
		$sql = "SELECT * FROM th_pk WHERE no_sasaran = '{$id}'";
		$res = $this->fetch($sql,1);

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
		$sql = "UPDATE th_pk SET th = '{$data['th']}', no_sasaran = '{$data['no_sasaran']}', no_pk = '{$data['no_pk']}', nm_pk = '{$data['nm_pk']}', target = '{$data['target']}', satuan = '{$data['satuan']}', perspektif = '{$data['perspektif']}' WHERE id = '{$data['id']}'";
		$res = $this->query($sql);

		return true;
	}

	function delete_pk($id=false,$thn,$idpk=false)
	{
		if($id) $cndid = "AND no_sasaran = '{$id}'"; else $cndid = "";
		if($idpk) $cndidpk = "AND id = '{$idpk}'"; else $cndidpk = "";
		$sql = "DELETE FROM th_pk WHERE th = '{$thn['kode']}' {$cndid} {$cndidpk}";
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

	function insert_import($data,$table)
	{
		$res = $this->insert($data,$table);
		return true;
	}

	function getTahun()
	{
		$query = "SELECT kode FROM bsn_sistem_setting WHERE `desc` = 'tahun_sistem' AND n_status = 1 ";
		$res = $this->fetch($query);

		return $res;
	}

	function getIK($type=5, $cat=0, $parent=0, $tahun=false)
	{
		$query = "SELECT * FROM bsn_news_content WHERE type = {$type} AND category = {$cat} AND parent_id = {$parent} AND year = {$tahun}";
		$data = $this->fetch($query,1);

		return $data;
	}
	
}
?>