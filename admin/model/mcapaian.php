<?php
class mcapaian extends Database {
	
	var $prefix = "bsn";

	function __construct()
	{
		parent::__construct();
	}

	function getStruktur($type)
	{
		$sql = "SELECT * FROM bsn_struktur WHERE type='{$type}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	function getStrukturId($type)
	{
		$sql = "SELECT * FROM bsn_struktur WHERE type='{$type}' AND n_status='1'";
		$res = $this->fetch($sql);
		if ($res) return $res;
		return false;
	}
	function getStrukturUser($kode)
	{
		if($kode){
			$kode=$kode;
		}else{
			$kode="840000";
		}
		$sql = "SELECT * FROM bsn_Struktur WHERE kode='{$kode}'";
		// db($sql);
		$res = $this->fetch($sql);

		if ($res) return $res;
		return false;
	}
	function getpk($kode,$thn)
	{
		/*$sql = "SELECT * FROM th_pk WHERE kdunitkerja = '{$kode}' AND th = '{$thn}'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;*/
		//revisi iman
		//$sql = "SELECT * FROM th_pk WHERE kdunitkerja = '{$kode}' AND th = '{$thn}'";
		$sql = "SELECT a.* FROM th_pk as a, bsn_news_content as b WHERE a.kdunitkerja = '{$kode}' AND a.th = '{$thn}' AND a.no_sasaran = b.id 
			AND b.type='7' AND b.category = '1'
			AND b.n_status = 1 {$cond} ORDER BY a.no_sasaran, a.no_pk";
		//pr($sql);
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}

	function getpkid($id,$thn)
	{
		$sql = "SELECT a.*,b.desc FROM th_pk as a, bsn_news_content as b WHERE a.id = '{$id}' AND a.th = '{$thn}' AND b.type = '7' AND b.category = '1'";
		$res = $this->fetch($sql,0);

		$sql = "SELECT * FROM bsn_news_content WHERE type='7' AND category='1' AND id='{$res['no_sasaran']}' AND n_status='1'";
		$ss = $this->fetch($sql,0);

		$res['ss'] = $ss['desc'];

		if ($res) return $res;
		return false;
	}

	function upd_capaian($data)
	{
		//$sql = "UPDATE th_pk SET formula = '{$data['formula']}', real_1 = '{$data['real_1']}', hasil_1 = '{$data['hasil_1']}', capaian_1 = '{$data['capaian_1']}', real_2 = '{$data['real_2']}', hasil_2 = '{$data['hasil_2']}', capaian_2 = '{$data['capaian_2']}', real_3 = '{$data['real_3']}', hasil_3 = '{$data['hasil_3']}', capaian_3 = '{$data['capaian_3']}', real_4 = '{$data['real_4']}', hasil_4 = '{$data['hasil_4']}', capaian_4 = '{$data['capaian_4']}', permasalahan = '{$data['permasalahan']}', perbaikan = '{$data['perbaikan']}' WHERE id = '{$data['id']}'";
		$sql = "UPDATE th_pk SET formula = '".addslashes(html_entity_decode($data['formula']))."', 
								 real_1 = '".addslashes(html_entity_decode($data['real_1']))."', hasil_1 = '{$data['hasil_1']}', capaian_1 = '{$data['capaian_1']}', 
								 real_2 = '".addslashes(html_entity_decode($data['real_2']))."', hasil_2 = '{$data['hasil_2']}', capaian_2 = '{$data['capaian_2']}', 
								 real_3 = '".addslashes(html_entity_decode($data['real_3']))."', hasil_3 = '{$data['hasil_3']}', capaian_3 = '{$data['capaian_3']}', 
								 real_4 = '".addslashes(html_entity_decode($data['real_4']))."', hasil_4 = '{$data['hasil_4']}', capaian_4 = '{$data['capaian_4']}', 
								 permasalahan = '".addslashes(html_entity_decode($data['permasalahan']))."', perbaikan = '".addslashes(html_entity_decode($data['perbaikan']))."' WHERE id = '{$data['id']}'";
		$res = $this->query($sql);
		return true;
	}

	function selectSS($id)
	{
		$sql = "SELECT * FROM bsn_news_content WHERE type='7' AND category='1' AND parent_id='{$id}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}

	function getTahun()
	{
		$query = "SELECT kode FROM bsn_sistem_setting WHERE `desc` = 'tahun_sistem' AND n_status = 1 ";
		$res = $this->fetch($query);

		return $res;
	}

	function getNamaStruktur($kode){
		$query = "SELECT nama_satker FROM bsn_struktur WHERE kode = '{$kode}'";
		$res = $this->fetch($query);
		return $res;
	}

	function del_capaian($id){
		$query = "DELETE FROM th_pk WHERE id= '{$id}'";
		//pr($query);
		//exit;
		$res = $this->query($query);
		//return true;

	}
	
}
?>