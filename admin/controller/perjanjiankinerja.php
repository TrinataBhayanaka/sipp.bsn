<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class perjanjiankinerja extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		// $this->validatePage();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		
		$this->model = $this->loadModel('mptn');
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->m_penetapanAngaran = $this->loadModel('m_penetapanAngaran');
	}
	
	public function bsn(){
		$eselon['type'] = $this->model->getEselon($this->admin);
		$eselon['level'] = $this->admin['type'];

		$thn = $this->model->getTahun();
		$data = $this->model->getpk('840000',1,false,$thn['kode']);
		if($data){
			foreach ($data as $key => $value) {
				$perspektif[] = $value['perspektif'];
			}
			$fix_per = array_unique($perspektif);
			
			foreach ($fix_per as $key => $val) {
				foreach ($data as $value) {
					if($val == $value['perspektif']){
						$data_fix[$val][] = $value;
					}
				}
			}
		}
		// db($data_fix);
		$this->view->assign('data',$data_fix);
		$this->view->assign('eselon',$eselon);

		return $this->loadView('pk/bsn');

	}

	public function add(){
		$ss = $this->getSS(1,'840000');
		foreach ($ss as $key => $value) {
			$check = $this->model->checkSS($value['id']);

			if($check > 0) unset($ss[$key]);
		}
		$this->view->assign('ss',$ss);
		$thn = $this->model->getTahun();
		$this->view->assign('thn',$thn['kode']);
		return $this->loadView('pk/add');		
	}

	public function getSS($id,$kd=false)
	{
		$data = $this->model->selectSS($id,$kd);

		return $data;
	}

	public function ins_pk()
	{
		global $basedomain;
		
		foreach ($_POST['indikator'] as $val) {
			$data['th'] = $_POST['th'];
			$data['no_sasaran'] = $_POST['no_sasaran'];
			$data['kdunitkerja'] = $_POST['kdunitkerja'];
			$data['perspektif'] = $_POST['perspektif'];
			$data['no_pk'] = $val['0'];
			$data['nm_pk'] = $val['1'];
			$data['target'] = $val['2'];
			$data['satuan'] = $val['3'];
			$this->model->insert_pk($data);
		}

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function edit()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpkSS('840000',1,$_GET['id'],$thn['kode']);
		$ss = $this->getSS(1,'840000');
		
		$this->view->assign('ss',$ss);
		$this->view->assign('no_sasaran',$_GET['id']);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data);

		return $this->loadView('pk/edit');
	}

	public function edt_pk()
	{
		global $basedomain;
		foreach ($_POST['indikator'] as $key => $value) {
			$data['th'] = $_POST['th'];
			$data['no_sasaran'] = $_POST['no_sasaran'];
			$data['perspektif'] = $_POST['perspektif'];
			$data['id'] = $value[0];
			$data['no_pk'] = $value[1];
			$data['nm_pk'] = $value[2];
			$data['target'] = $value[3];
			$data['satuan'] = $value[4];

			$this->model->edit_pk($data);
		}

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function del_pk()
	{
		global $basedomain;
		$id = $_GET['id'];
		$thn = $this->model->getTahun();
		$this->model->delete_pk($id,$thn,$idpk);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function del_pk_eselon()
	{
		global $basedomain;
		$idpk = $_GET['id'];
		$thn = $this->model->getTahun();
		$this->model->delete_pk(false,$thn,$idpk);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/eselon1/?tp=2&kd={$_GET['kd']}'</script>";
		exit;
	}

	public function del_pk_eselon2()
	{
		global $basedomain;
		$idpk = $_GET['id'];
		$thn = $this->model->getTahun();
		$this->model->delete_pk(false,$thn,$idpk);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/pk_eselon2/?tp=3&kd={$_GET['kd']}'</script>";
		exit;
	}

	public function eselon1()
	{
		$es = $_GET['tp'];
		$struktur = $this->model->getStruktur($es);
		if($es == '2')$labelEs="I";else $labelEs="II";
		$this->view->assign('labelEs',$labelEs);
		$this->view->assign('es',$es);

		$eselon['type'] = $this->model->getEselon($this->admin);
		$eselon['level'] = $this->admin['type'];
		$this->view->assign('eselon',$eselon);

		if(!$_POST) {
			if(isset($_GET['kd'])){
				$idpk = $_GET['kd'];
			} else {
				$idpk = $struktur[0]['kode'];
			}
			foreach ($struktur as $key => $value) {
				if($value['kode'] == $idpk) {
					$parent = $value['id'];
					$this->view->assign('label',$value['nama_satker']);
					$this->view->assign('id',$value['id']);
				}
			}
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);
		if($data){
		foreach ($data as $key => $value) {
			$perspektif[] = $value['perspektif'];
		}
		$fix_per = array_unique($perspektif);
		
		foreach ($fix_per as $key => $val) {
			foreach ($data as $value) {
				if($val == $value['perspektif']){
					$data_fix[$val][] = $value;
				}
			}
		}
		}
		// db($data_fix);
		$this->view->assign('data',$data_fix);
		$this->view->assign('tipe',$es);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon1');
	}

	public function pk_eselon2()
	{
		$es = $_GET['tp'];
		$struktur = $this->model->getStruktur($es);
		if($es == '2')$labelEs="I";else $labelEs="II";
		$this->view->assign('labelEs',$labelEs);
		$this->view->assign('es',$es);

		$eselon['type'] = $this->model->getEselon($this->admin);
		$eselon['level'] = $this->admin['type'];
		$this->view->assign('eselon',$eselon);

		if(!$_POST) {
			if(isset($_GET['kd'])){
				$idpk = $_GET['kd'];
			} else {
				$idpk = $struktur[0]['kode'];
			}
			foreach ($struktur as $key => $value) {
				if($value['kode'] == $idpk) {
					$parent = $value['id'];
					$this->view->assign('label',$value['nama_satker']);
					$this->view->assign('id',$value['id']);
				}
			}
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);
		if($data){	
			foreach ($data as $key => $value) {
				$perspektif[] = $value['perspektif'];
			}
			$fix_per = array_unique($perspektif);
			
			foreach ($fix_per as $key => $val) {
				foreach ($data as $value) {
					if($val == $value['perspektif']){
						$data_fix[$val][] = $value;
					}
				}
			}
		}
		
		$this->view->assign('data',$data_fix);
		$this->view->assign('tipe',$es);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon2');
	}

	public function add_eselon()
	{
		$ss = $this->getSS($_GET['id']);
		$thn = $this->model->getTahun();
		$this->view->assign('thn',$thn['kode']);
		$this->view->assign('tp',$_GET['tp']);
		$this->view->assign('ss',$ss);
		$this->view->assign('idpk',$_GET['kd']);

		return $this->loadView('pk/add_eselon');		
	}

	public function ins_pk_eselon()
	{
		global $basedomain;
		
		$tipe = $_POST['tipe'];
		
		$data['th'] = $_POST['th'];
		$data['no_sasaran'] = $_POST['no_sasaran'];
		$data['kdunitkerja'] = $_POST['kdunitkerja'];
		$data['perspektif'] = $_POST['perspektif'];
		$data['satuan'] = $_POST['satuan'];
		$data['no_pk'] = $_POST['no_pk'];
		$data['nm_pk'] = $_POST['nm_pk'];
		$data['target'] = $_POST['target'];
		
		$this->model->insert_pk($data);
		
		if($tipe == 2){
			$eselon = "eselon1";
		} else {
			$eselon = "pk_eselon2";
		}
		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/{$eselon}/?tp={$tipe}&kd={$data['kdunitkerja']}'</script>";
		exit;
	}

	public function edit_eselon()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($_GET['kd'],$_GET['pr'],$_GET['id'],$thn['kode']);
		$ss = $this->getSS($_GET['pr']);
		
		$this->view->assign('ss',$ss);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data[0]);
		$this->view->assign('kode',$_GET['kd']);

		return $this->loadView('pk/edit_eselon');
	}

	public function edit_eselon2()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($_GET['kd'],$_GET['pr'],$_GET['id'],$thn['kode']);
		$ss = $this->getSS($_GET['pr']);
		
		$this->view->assign('ss',$ss);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data[0]);
		$this->view->assign('kode',$_GET['kd']);

		return $this->loadView('pk/edit_eselon2');
	}

	public function edt_pk_eselon()
	{
		global $basedomain;

		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/eselon1/?tp=2&kd={$_POST['kdunitkerja']}'</script>";
		exit;
	}

	public function edt_pk_eselon2()
	{
		global $basedomain;
		
		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/pk_eselon2/?tp=3&kd={$_POST['kdunitkerja']}'</script>";
		exit;
	}

	public function eselon2()
	{
		$struktur = $this->model->getStruktur(3);
		$this->view->assign('eselon','II');

		if(!$_POST) {
			$idpk = $struktur[0]['kode'];
			$parent = $struktur[0]['id'];
			$this->view->assign('label',$struktur[0]['nama_satker']);
			$this->view->assign('id',$struktur[0]['id']);
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
		}
		
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon2');
	}

	function ajaxIndikator()
	{
		$ss = $_POST['ss'];
		$th = $_POST['th'];
		$ik = $this->model->getIK(8, 1, $ss, $th);

		echo json_encode($ik);
		exit;
	}

	function tesChart()
	{
		return $this->loadView('pk/charts');
	}
	
	public function DateToIndo($date) { 
		// fungsi atau method untuk mengubah tanggal ke format indonesia
		// variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
	
		$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
		$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
		$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
		
		// $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		$result = " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
	}
	
	function print_bsn(){
		$thn = $this->model->getTahun();
		$data = $this->model->getpk('840000',1,false,$thn['kode']);
		if($data){
			foreach ($data as $key => $value) {
				$perspektif[] = $value['perspektif'];
			}
			$fix_per = array_unique($perspektif);
			$k =1;
			foreach ($fix_per as $val) {
				$i=1;
				$temp =array();
				foreach ($data as $keys=>$value) {
					$temp[] = $value['no_sasaran'];
					$hit = count($temp);
					if($val == $value['perspektif']){
						$data_fix[$val][]= $value;
						$count = count($data_fix[$val]);
						$index = $count - 1; 
						
						$data_fix[$val][$index]['no'] = $i;
						if($hit == 1){
							$data_fix[$val][$index]['no_urut'] = $k;	
						}else{
							$get_val_array = $hit - 2;
							if($temp[$get_val_array] == $value['no_sasaran']){
								// nothing
								$data_fix[$val][$index]['no_urut'] = '';	
							}else{
								$k++;
								$data_fix[$val][$index]['no_urut'] = $k;	
							}
						}
					}
					$i++;
				}
			} 
		}
		$data_program = $this->model->getProgram($thn['kode']);
		$j=1;
		foreach ($data_program as $keyval=>$values){
			$data_program_fix[] =  $values;
			if($values['brief'] == "084.01"){
				$param = '1';
			}elseif($values['brief'] == "084.02"){
				$param = '2';
			}elseif($values['brief'] == "084.06"){
				$param = '3';
			}
			$data_anggaran= $this->model->getAnggaran($param,$thn['kode']);
			foreach ($data_anggaran as $anggaran){
				$anggaran_fix += $anggaran['JML'];
			}
			$tot_angaran += $anggaran_fix;
			$data_program_fix[$keyval]['anggaran'] =  $anggaran_fix;
			$data_program_fix[$keyval]['no'] =  $j;
			$anggaran_fix = 0;
			$j++;
			
		}
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		$split = substr('840000',0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		$kd_bsn = "840000";
		$nama_pejabat = $this->model->nama_pejabat($kd_bsn);
		$target = unserialize($nama_pejabat['custom_text']);
		$this->view->assign('nama_pejabat',$target['pejabat']);
		
		// exit;
		$this->view->assign('data',$data_fix);
		$this->view->assign('program',$data_program_fix);
		$this->view->assign('all_anggaran',$tot_angaran);
		$this->view->assign('tahun',$thn['kode']);

		$html = $this->loadView('pk/print_bsn');
		// echo $html;
		// exit;
		$this->reportHelper =$this->loadModel('reportHelper');
		$generate = $this->reportHelper->loadMpdf($html, 'pk-bsn',2);
	}
	
	function print_eselon1(){
		
		$exp = explode("_", $_GET['kd_unit']);
		$idpk = $exp[2];
		$parent = $exp[0];
		$this->view->assign('label',$exp[1]);
		$this->view->assign('id',$exp[0]);
		$this->view->assign('idpk',$idpk);
		
		$thn = $this->model->getTahun();
		$this->view->assign('tahun',$thn['kode']);
	
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);
		if($data){
			foreach ($data as $key => $value) {
				$perspektif[] = $value['perspektif'];
			}
			$fix_per = array_unique($perspektif);
			$k =1;
			foreach ($fix_per as $val) {
				$i=1;
				$temp =array();
				foreach ($data as $keys=>$values) {
					$temp[] = $values['no_sasaran'];
					// pr($temp);
					$hit = count($temp);
					if($val == $values['perspektif']){
						$data_fix[$val][]= $values;
						$count = count($data_fix[$val]);
						$index = $count - 1; 
						
						$data_fix[$val][$index]['no'] = $i;
							if($hit == 1){
								$data_fix[$val][$index]['no_urut'] = $k;	
							}else{
								$get_val_array = $hit - 2;
								if($temp[$get_val_array] == $values['no_sasaran']){
									// nothing
									$data_fix[$val][$index]['no_urut'] = '';	
								}else{
									$k++;
									$data_fix[$val][$index]['no_urut'] = $k;	
								}
							}
					}
					$i++;
				}
			} 
		}
		
		$str = rtrim($exp[2], '0');
		$data_kegiatan= $this->model->kd_kegiatan($str);
		$j=1;
		foreach ($data_kegiatan as $keyval=>$values){
			$data_kegiatan_fix[] =  $values;
			
			$data_anggaran= $this->model->getAnggaraneselon($values['kdgiat'],$thn['kode']);
			foreach ($data_anggaran as $anggaran){
				$anggaran_fix += $anggaran['JML'];
			}
			$tot_angaran += $anggaran_fix;
			$data_kegiatan_fix[$keyval]['anggaran'] =  $anggaran_fix;
			$data_kegiatan_fix[$keyval]['no'] =  $j;
			$anggaran_fix = 0;
			$j++;
			
		}
		
		//get nama pejabat
		//bsn
		$kd_bsn = "840000";
		$nama_pejabat_bsn = $this->model->nama_pejabat($kd_bsn);
		$pejabat_bsn = unserialize($nama_pejabat_bsn['custom_text']);
		$this->view->assign('nama_pejabat_bsn',$pejabat_bsn['pejabat']);
		
		//eselon I
		$kd_eselon_I = $exp[2];
		$nama_pejabat_eselon_I = $this->model->nama_pejabat($kd_eselon_I);
		// pr($nama_pejabat_eselon_I);
		$pejabat_eselon_I = unserialize($nama_pejabat_eselon_I['custom_text']);
		$this->view->assign('nama_pejabat_eselon_I',$pejabat_eselon_I['pejabat']);
		
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		// db($data_fix);
		// db($data_kegiatan_fix);
		$this->view->assign('data',$data_fix);
		$this->view->assign('program',$data_kegiatan_fix);
		$this->view->assign('all_anggaran',$tot_angaran);
		$this->view->assign('tahun',$thn['kode']);
		
		$html = $this->loadView('pk/print_eselon1');
		// echo $html;
		// exit;
		$this->reportHelper =$this->loadModel('reportHelper');
		$generate = $this->reportHelper->loadMpdf($html, 'pk-bsn',2);
	}
	
	function print_eselon2(){
		$exp = explode("_", $_GET['kd_unit']);
		$idpk = $exp[2];
		$parent = $exp[0];
		$this->view->assign('label',$exp[1]);
		$this->view->assign('id',$exp[0]);
		$this->view->assign('idpk',$idpk);
		
		$thn = $this->model->getTahun();
		$this->view->assign('tahun',$thn['kode']);
	
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);
		if($data){
			foreach ($data as $key => $value) {
				$perspektif[] = $value['perspektif'];
			}
			$fix_per = array_unique($perspektif);
			$k =1;
			foreach ($fix_per as $val) {
				$i=1;
				$temp =array();
				foreach ($data as $keys=>$values) {
					$temp[] = $values['no_sasaran'];
					// pr($temp);
					$hit = count($temp);
					if($val == $values['perspektif']){
						$data_fix[$val][]= $values;
						$count = count($data_fix[$val]);
						$index = $count - 1; 
						
						$data_fix[$val][$index]['no'] = $i;
							if($hit == 1){
								$data_fix[$val][$index]['no_urut'] = $k;	
							}else{
								$get_val_array = $hit - 2;
								if($temp[$get_val_array] == $values['no_sasaran']){
									// nothing
									$data_fix[$val][$index]['no_urut'] = '';	
								}else{
									$k++;
									$data_fix[$val][$index]['no_urut'] = $k;	
								}
							}
					}
					$i++;
				}
			} 
		}
		
		$str = rtrim($exp[2], '0');
		// pr($exp[2]);
		// pr($str);
		$data_kegiatan= $this->model->kd_kegiatan($str);
		$j=1;
		foreach ($data_kegiatan as $keyval=>$values){
			$data_kegiatan_fix[] =  $values;
			
			$data_anggaran= $this->model->getAnggaraneselon($values['kdgiat'],$thn['kode']);
			foreach ($data_anggaran as $anggaran){
				$anggaran_fix += $anggaran['JML'];
			}
			$tot_angaran += $anggaran_fix;
			$data_kegiatan_fix[$keyval]['anggaran'] =  $anggaran_fix;
			$data_kegiatan_fix[$keyval]['no'] =  $j;
			$anggaran_fix = 0;
			$j++;
			
		}
		
		//get nama pejabat
		//eselon I
		$temp_kode_eselon_I = substr($exp[2],0,3);
		$kd_eselon_I = $temp_kode_eselon_I."000";
		$nama_pejabat_eselon_I = $this->model->nama_pejabat($kd_eselon_I);
		// pr($nama_pejabat_eselon_I);
		$pejabat_eselon_I = unserialize($nama_pejabat_eselon_I['custom_text']);
		$this->view->assign('nama_pejabat_eselon_I',$pejabat_eselon_I['pejabat']);
		
		$kd_eselon_II = $exp[2];
		$nama_pejabat_eselon_II = $this->model->nama_pejabat($kd_eselon_II);
		// pr($nama_pejabat_eselon_I);
		$pejabat_eselon_II = unserialize($nama_pejabat_eselon_II['custom_text']);
		$this->view->assign('nama_pejabat_eselon_II',$pejabat_eselon_II['pejabat']);
		
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		$this->view->assign('data',$data_fix);
		$this->view->assign('program',$data_kegiatan_fix);
		$this->view->assign('all_anggaran',$tot_angaran);
		$this->view->assign('tahun',$thn['kode']);
		
		//nama pejabat eselon I dan eselon II yg terkait 
		
		$html = $this->loadView('pk/print_eselon2');
		// echo $html;
		// exit;
		$this->reportHelper =$this->loadModel('reportHelper');
		$generate = $this->reportHelper->loadMpdf($html, 'pk-bsn',2);
		
	}
}

?>
