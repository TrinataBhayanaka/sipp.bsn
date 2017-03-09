<?php
class mref extends Database {
	
	var $prefix = "lelang";

	function __construct()
	{
		parent::__construct();
	}
	
	//add for home
	function select_data(){
		$query = "SELECT title,filename FROM bsn_news_content WHERE type = '50' and n_status = '1' ";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
	function ceck($dataArr){
		$query = "SELECT count(1) as jml,id FROM bsn_news_content WHERE type = '{$dataArr[type]}' and n_status =  '{$dataArr[n_status]}' ";
		// pr($query);
		$result = $this->fetch($query);
		if($result['jml'] != 0){
			//update
			$query = "UPDATE bsn_news_content SET title = '{$dataArr[title]}' , filename =  '{$dataArr[filename]}' where id = '{$result[id]}'";
			// pr($query);
			$exe = $this->query($query);
		}else{
			//insert
			$query = "insert into bsn_news_content (title,filename,type,create_date,publish_date,n_status) 
						VALUES ('{$dataArr[title]}','{$dataArr[filename]}','{$dataArr[type]}','{$dataArr[create_date]}','{$dataArr[publish_date]}','{$dataArr[n_status]}')";
			// pr($query);
			$exe = $this->query($query);
		}
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
	
	function getNamaKegiatan($kdunitkerja,$kdgiat){
		$query = "SELECT kdunitkerja,kdgiat,nmgiat FROM m_kegiatan WHERE kdgiat = '{$kdgiat}' and kdunitkerja = '{$kdunitkerja}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function ceckKegiatan($kdunitkerja,$kdgiat){
		$query = "SELECT count(1) as jml FROM m_kegiatan  WHERE kdgiat = '{$kdgiat}' and kdunitkerja = '{$kdunitkerja}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function updateNamaKegiatan($kdunitkerja,$kdgiat,$nmgiat,$kdunitkerjaold,$kdgiatold){
		$query = "UPDATE m_kegiatan SET  nmgiat = '{$nmgiat}', kdunitkerja = '{$kdunitkerja}', kdgiat = '{$kdgiat}'  where kdunitkerja = '{$kdunitkerjaold}' and kdgiat = '{$kdgiatold}'";
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
	
	function updateNamaOutput($kdgiat,$kdoutput,$nmoutput,$kdgiatold,$kdoutputold){
		$query = "UPDATE t_output SET  nmoutput = '{$nmoutput}', kdgiat = '{$kdgiat}', kdoutput = '{$kdoutput}'  where kdoutput = '{$kdoutputold}' and kdgiat = '{$kdgiatold}'";
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
	
	function updateNamaAkun($kdakun,$kdakunold,$nmakun){
		$query = "UPDATE t_akun SET  NMAKUN = '{$nmakun}', KDAKUN = '{$kdakun}' where KDAKUN = '{$kdakunold}'";
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

	function insertPejabat($data){
		$type = '1';
		$category = '33';
		$n_status = '1';
		$date = date('Y-m-d H:i:s');
		$query = "INSERT INTO bsn_news_content(`parent_id`, `title`, `brief`,`desc`,
				`type`, category,`year`,`n_status`,`create_date`,`publish_date`) VALUES ('{$data[id]}','{$data[nm_satker]}','{$data[jabatan]}','{$data[nama_pejabat]}','{$type}','{$category}','{$data[tahun]}','{$n_status}','{$date}','{$date}')";
		//pr($query);
		$result = $this->query($query);


	}

	function ajax_edit($id){

		$query = "SELECT * from bsn_news_content where id= '{$id}'";
		//echo $query;
		$result = $this->fetch($query);
		
		return $result;
	}

	function updatePejabat($data){

		$query = "UPDATE bsn_news_content SET  `brief` = '{$data[jabatan]}', `desc` = '{$data[nama_pejabat]}' where `id` = '{$data[id]}'";
		$result = $this->query($query);

	}

	function delPejabat($data){

		$query = "UPDATE bsn_news_content SET  `n_status` = '0' where 
		         `id` = '{$data}'";
		$result = $this->query($query);

	}

	function countPejabat($data){

		$query = "SELECT count(id) as jml from bsn_news_content where type= '1' and category = '33' and n_status = '1' and parent_id= '{$data[parent_id]}' 
			and year = '{$data[year]}'";
		//echo $query;
		$result = $this->fetch($query);
		
		return $result;
	}


}
?>