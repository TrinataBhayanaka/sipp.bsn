<?php
class mref extends Database {
	
	var $prefix = "lelang";

	function __construct()
	{
		parent::__construct();
	}
	
	/*function getRefKegiatan($tahun){
		$query = "SELECT kdunitkerja,kdgiat,nmgiat FROM m_kegiatan WHERE ta = '{$tahun}' order by kdunitkerja asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}*/
	function getRefKegiatan(){
		$query = "SELECT kdunitkerja,kdgiat,nmgiat FROM m_kegiatan order by kdunitkerja asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getRefOutput(){
		$query = "SELECT kdgiat,kdoutput,nmoutput FROM t_output group by kdgiat,kdoutput,nmoutput order by kdgiat asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function getRefAkun(){
		$query = "SELECT KDAKUN,NMAKUN FROM t_akun order by KDAKUN asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function nama_pejabat($kd_satker){
		$query = "SELECT custom_text FROM bsn_struktur WHERE kode = '{$kd_satker}' ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	/*function getNamaKegiatan($kd_giat,$tahun){
		$query = "SELECT kdunitkerja,kdgiat,nmgiat FROM m_kegiatan WHERE kdgiat = '{$kd_giat}' and  ta = '{$tahun}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}*/
	
	function getNamaKegiatan($kd_giat){
		$query = "SELECT kdunitkerja,kdgiat,nmgiat FROM m_kegiatan WHERE kdgiat = '{$kd_giat}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function updateNamaKegiatan($kdunitkerja,$kdgiat,$nmgiat){
		$query = "UPDATE m_kegiatan SET  nmgiat = '{$nmgiat}' where kdunitkerja = '{$kdunitkerja}' and kdgiat = '{$kdgiat}'";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function getNamaOutput($kd_giat,$kdoutput){
		$query = "SELECT kdgiat,kdoutput,nmoutput FROM t_output  WHERE kdgiat = '{$kd_giat}' and kdoutput = '{$kdoutput}' limit 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function ceckOutput($kd_giat,$kdoutput){
		$query = "SELECT count(1) as jml FROM t_output  WHERE kdgiat = '{$kd_giat}' and kdoutput = '{$kdoutput}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function updateNamaOutput($kdgiat,$kdoutput,$nmoutput){
		$query = "UPDATE t_output SET  nmoutput = '{$nmoutput}' where kdoutput = '{$kdoutput}' and kdgiat = '{$kdgiat}'";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function insertOutput($kdgiat,$kdoutput,$nmoutput){
		$query = "INSERT INTO t_output(kdgiat, kdoutput, nmoutput) VALUES ('{$kdgiat}','{$kdoutput}','{$nmoutput}')";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function deleteOutput($kdgiat,$kdoutput){
		$query = "DELETE from t_output where kdgiat = '{$kdgiat}' and kdoutput = '{$kdoutput}'";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function getNamaAkun($kdakun){
		$query = "SELECT KDAKUN,NMAKUN FROM t_akun  WHERE KDAKUN = '{$kdakun}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function updateNamaAkun($kdakun,$nmakun){
		$query = "UPDATE t_akun SET  NMAKUN = '{$nmakun}' where KDAKUN = '{$kdakun}'";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function ceckAkun($kdakun){
		$query = "SELECT count(KDAKUN) as jml FROM t_akun  WHERE KDAKUN = '{$kdakun}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function insertAkun($kdakun,$nmakun){
		$query = "INSERT INTO t_akun(KDAKUN, NMAKUN) VALUES ('{$kdakun}','{$nmakun}')";
		// pr($query);
		$result = $this->query($query);
		
	}
	
	function deleteAkun($kdakun){
		$query = "DELETE from t_akun where KDAKUN = '{$kdakun}'";
		// pr($query);
		$result = $this->query($query);
		
	}
}
?>