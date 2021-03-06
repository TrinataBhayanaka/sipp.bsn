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
			$sql = "SELECT id FROM bsn_struktur WHERE kode = '{$kd}' and n_status = '1'";
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
		$sql = "SELECT id FROM bsn_struktur WHERE kode = '{$kd}' AND n_status='1'";
		$res = $this->fetch($sql);
		// pr($res);
		$fkd=$res['id'];
		//revisi iman
		$sql = "SELECT a.*,b.desc FROM th_pk as a, bsn_news_content as b WHERE a.kdunitkerja = '{$kd}' AND a.th = '{$thn}' AND a.no_sasaran = b.id AND b.parent_id = '{$fkd}' AND b.type='7' AND b.category = '1'
			AND b.n_status = 1 {$cond} ORDER BY a.no_sasaran, a.no_pk";
		//AND b.n_status = 1 
		 //db($sql);
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
		//pr($sql);
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
		// pr($sql);
		// exit;
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
		$query = "SELECT * FROM bsn_news_content WHERE type = {$type} AND category = {$cat} AND parent_id = {$parent} AND year = {$tahun}  and n_status = 1";
		// pr($query);
		$data = $this->fetch($query,1);

		return $data;
	}
	
	function getProgram($tahun){
		$query = "SELECT b.brief,b.desc as decription from bsn_news_content as b where b.type = '9' and b.category = '1' and b.year = '{$tahun}' and n_status = '1'";
		// pr($query);
		$data = $this->fetch($query,1);

		return $data;
	}
	
	function getAnggaran($param,$tahun){
		if($param == 1){
			$ext_query = "KDGIAT BETWEEN 3549 AND 3551";
		}elseif($param == 2){
			$ext_query = "KDGIAT = 3552";
		}elseif($param == 3){
			$ext_query = "KDGIAT BETWEEN 3553 AND 3561";
		}
		$query = "SELECT COUNT(1),KDGIAT,SUM(JUMLAH) AS JML FROM `d_item` WHERE {$ext_query} AND THANG = {$tahun} GROUP BY KDGIAT";
		// pr($query);
		$data = $this->fetch($query,1);
		return $data;
	}
	
	function getAnggaraneselon($param,$tahun){
		$ext_query = "KDGIAT = $param";
		
		$query = "SELECT COUNT(1),KDGIAT,SUM(JUMLAH) AS JML FROM `d_item` WHERE {$ext_query} AND THANG = {$tahun} GROUP BY KDGIAT";
		// pr($query);
		$data = $this->fetch($query,1);
		return $data;
	}
	
	function kd_kegiatan($kd_satker){
		$query = "SELECT kdgiat,nmgiat FROM m_kegiatan WHERE kdunitkerja like '{$kd_satker}%' order by kdgiat asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}

	function checkSS($idss)
	{
		$query = "SELECT COUNT(*) as total FROM th_pk WHERE no_sasaran = {$idss}";
		$res = $this->fetch($query);

		return $res['total'];
	}

	function getEselon($data)
	{
		$query = "SELECT type FROM bsn_struktur WHERE kode = '{$data['kode']}' and n_status = '1'";
		$res = $this->fetch($query);

		if($res['type'] == ""){
			$res['type'] = 1;
		}

		return $res['type'];
	}
	
	function nama_pejabat($kd_satker){
		//$query = "SELECT custom_text FROM bsn_struktur WHERE kode = '{$kd_satker}' ";
		$query_1 = "SELECT id FROM bsn_struktur WHERE kode = '{$kd_satker}' and n_status = '1'";
		// pr($query);
		$result_1 = $this->fetch($query_1);
		$parent_id = $result_1['id']; 
		
		$query_2 = "SELECT `brief`,`desc` FROM bsn_news_content WHERE parent_id = '{$parent_id}' and n_status = '1' and type = '1' and category='33'";
		$result_2 = $this->fetch($query_2);
		

		return $result_2;
	}

	function getNamaStruktur($kode){
		$query = "SELECT nama_satker FROM bsn_struktur WHERE kode = '{$kode}'";
		$res = $this->fetch($query);
		return $res;
	}
	
}
?>