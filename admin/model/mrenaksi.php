<?php
class mrenaksi extends Database {
	
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

	function getpk($kode,$thn)
	{
		$sql = "SELECT * FROM th_pk WHERE kdunitkerja = '{$kode}' AND th = '{$thn}'";
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

	function upd_renaksi($data)
	{
		$sql = "UPDATE th_pk SET formula = '{$data['formula']}', rencana_1 = '{$data['rencana_1']}', ren_hasil_1 = '{$data['ren_hasil_1']}', aksi_1 = '{$data['aksi_1']}', rencana_2 = '{$data['rencana_2']}', ren_hasil_2 = '{$data['ren_hasil_2']}', aksi_2 = '{$data['aksi_2']}', rencana_3 = '{$data['rencana_3']}', ren_hasil_3 = '{$data['ren_hasil_3']}', aksi_3 = '{$data['aksi_3']}', rencana_4 = '{$data['rencana_4']}', ren_hasil_4 = '{$data['ren_hasil_4']}', aksi_4 = '{$data['aksi_4']}' WHERE id = '{$data['id']}'";
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

	function getEselon($data)
	{
		$query = "SELECT type FROM bsn_struktur WHERE kode = '{$data['kode']}'";
		$res = $this->fetch($query);

		if($res['type'] == ""){
			$res['type'] = 1;
		}

		return $res['type'];
	}
	
}
?>