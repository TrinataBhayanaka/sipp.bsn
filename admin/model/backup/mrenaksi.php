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

	function getpk($kode)
	{
		$sql = "SELECT * FROM th_pk WHERE kdunitkerja = '{$kode}' AND th = '2015'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}

	function getpkid($id)
	{
		$sql = "SELECT a.*,b.desc FROM th_pk as a, bsn_news_content as b WHERE a.id = '{$id}' AND a.th = '2015' AND b.type = '7' AND b.category = '1'";
		$res = $this->fetch($sql,0);
		if ($res) return $res;
		return false;
	}

	function upd_renaksi($data)
	{
		$sql = "UPDATE th_pk SET formula = '{$data['formula']}', rencana_1 = '{$data['rencana_1']}', ren_hasil_1 = '{$data['ren_hasil_1']}', aksi_1 = '{$data['aksi_1']}', rencana_2 = '{$data['rencana_2']}', ren_hasil_2 = '{$data['ren_hasil_2']}', aksi_2 = '{$data['aksi_2']}', rencana_3 = '{$data['rencana_3']}', ren_hasil_3 = '{$data['ren_hasil_3']}', aksi_3 = '{$data['aksi_3']}', rencana_4 = '{$data['rencana_4']}', ren_hasil_4 = '{$data['ren_hasil_4']}', aksi_4 = '{$data['aksi_4']}' WHERE id = '{$data['id']}'";
		$res = $this->query($sql);
		return true;
	}
	
}
?>