<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class monev_trwln extends Controller {
	
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
		$this->m_penetapanAngaran = $this->loadModel('m_penetapanAngaran');
		$this->model = $this->loadModel('mptn');
	}
	public function rencana(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$rencana = $this->m_penetapanAngaran->rencana_anggaran($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		$realisasi = $this->m_penetapanAngaran->realisasi_anggaran($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		// pr($rencana);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('rncn'=>$rencana,'real'=>$realisasi);
		print json_encode($newformat);
		exit;
	}
	
	public function bobot(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$rencana = $this->m_penetapanAngaran->rencana_bobot($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		$realisasi = $this->m_penetapanAngaran->realisasi_bobot($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		// pr($rencana);
		// pr($realisasi);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('rncn'=>$rencana,'real'=>$realisasi);
		print json_encode($newformat);
		exit;
	}
	
	public function getdata(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$data = $this->m_penetapanAngaran->getdata($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		if($trwln == 1){
			$data['keterangan'] = $data['keterangan'];
			$data['kendala'] = $data['kendala'];
			$data['tindaklanjut'] = $data['tindaklanjut'];
			$data['ygmembantu'] = $data['ygmembantu'];
		}elseif($trwln == 2){
			$data['keterangan'] = $data['keterangan_2'];
			$data['kendala'] = $data['kendala_2'];
			$data['tindaklanjut'] = $data['tindaklanjut_2'];
			$data['ygmembantu'] = $data['ygmembantu_2'];
		}elseif($trwln == 3){
			$data['keterangan'] = $data['keterangan_3'];
			$data['kendala'] = $data['kendala_3'];
			$data['tindaklanjut'] = $data['tindaklanjut_3'];
			$data['ygmembantu'] = $data['ygmembantu_3'];
		}elseif($trwln == 4){
			$data['keterangan'] = $data['keterangan_4'];
			$data['kendala'] = $data['kendala_4'];
			$data['tindaklanjut'] = $data['tindaklanjut_4'];
			$data['ygmembantu'] = $data['ygmembantu_4'];
		}
		// pr($rencana);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('keterangan'=>$data['keterangan'],'kendala'=>$data['kendala'],'tindaklanjut'=>$data['tindaklanjut'],'ygmembantu'=>$data['ygmembantu']);
		print json_encode($newformat);
		exit;
	}
	
	public function index(){
		global $basedomain;
		//=============================== 
		/*if($this->admin['type'] == 1){
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		}else{
			$str = rtrim($this->admin['kode'], '0');
			$length = strlen($str);
			if($length == 3){
				$param_list = '1';
				$kd_unit_list = $str;
			}elseif($length == 4){
				$param_list = '2';
				$kd_unit_list = $this->admin['kode'];
			}
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown_cstmn($param_list,$kd_unit_list);
		}*/
		$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		//===============================
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// pr($thn_aktif);
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		$thn_renstra =$thn_aktif['data'];
		
		if($_POST['unit'] !=''){
			// pr($_POST['unit']);
			// echo "masukk";
			// exit;
			$kd_unit = $_POST['unit'];
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			
			
			/*if($thn_temp >= 2015 and $thn_temp<=2019){
				$thn_renstra ='2015-2019'; 
			}elseif($thn_temp >= 2020 and $thn_temp<=2024){
				$thn_renstra ='2020-2024'; 
			}elseif($thn_temp >= 2025 and $thn_temp<=2029){
				$thn_renstra ='2025-2029'; 
			}elseif($thn_temp >= 2030 and $thn_temp<=2034){
				$thn_renstra ='2030-2034'; 
			}*/
			
			
			// pr($thn_renstra);
			$kd_kegiatan = $this->m_penetapanAngaran->kd_kegiatan($thn_renstra,$kd_unit);
			foreach($kd_kegiatan as $key=>$val){
				$list[] = $val;
				$pagu_giat = $this->m_penetapanAngaran->pagu_giat($thn_temp,$val['kdgiat']);
				$list[$key]['pagu_giat'] = $pagu_giat['pagu_giat'];
				
				//output
				$output = $this->m_penetapanAngaran->output($thn_temp,$val['kdgiat']);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
					
					//$thp kegiatan
					$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn_temp,$val['kdgiat'],$out['KDOUTPUT']);
					foreach ($thp_kegiatan as $kav=>$values){
						$list[$key]['output'][$k]['tahapan'][] = $values; 
						$komponen = $this->m_penetapanAngaran->komponen($thn_temp,$val['kdgiat'],$out['KDOUTPUT'],$values['KDKMPNEN'],$values['KDSOUTPUT']);
						$list[$key]['output'][$k]['tahapan'][$kav]['nama_komponen'] = $komponen['URKMPNEN']; 
					}
				}
				
			}
		}else{
			//default Biro perencanaan, keuangan dan tata usaha
			//===============================		
			/*if($this->admin['type'] == 1){
				$kode = '841100';
			}else{
				$str = rtrim($this->admin['kode'], '0');
				$length = strlen($str);
				if($length == 3){
					$param_list = $str;
					$select_unit = $this->m_penetapanAngaran->list_unit($param_list);
					// pr($select_unit);
					$kode = $select_unit['kdunit']; 
				}elseif($length == 4){
					$kode = $this->admin['kode'];
				}
			}*/
			//===============================
			$kd_unit ='841100';
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			
			/*if($thn_temp >= 2015 and $thn_temp<=2019){
				$thn_renstra ='2015-2019'; 
			}elseif($thn_temp >= 2020 and $thn_temp<=2024){
				$thn_renstra ='2020-2024'; 
			}elseif($thn_temp >= 2025 and $thn_temp<=2029){
				$thn_renstra ='2025-2029'; 
			}elseif($thn_temp >= 2030 and $thn_temp<=2034){
				$thn_renstra ='2030-2034'; 
			}*/
			
			
			$kd_kegiatan = $this->m_penetapanAngaran->kd_kegiatan($thn_renstra,$kd_unit);
			foreach($kd_kegiatan as $key=>$val){
				$list[] = $val;
				$pagu_giat = $this->m_penetapanAngaran->pagu_giat($thn_temp,$val['kdgiat']);
				// pr($pagu_giat);
				$list[$key]['pagu_giat'] = $pagu_giat['pagu_giat'];
				
				//output
				$output = $this->m_penetapanAngaran->output($thn_temp,$val['kdgiat']);
				// pr($output);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
				
					//$thp kegiatan
					$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn_temp,$val['kdgiat'],$out['KDOUTPUT']);
					foreach ($thp_kegiatan as $kav=>$values){
						$list[$key]['output'][$k]['tahapan'][] = $values; 
						$komponen = $this->m_penetapanAngaran->komponen($thn_temp,$val['kdgiat'],$out['KDOUTPUT'],$values['KDKMPNEN'],$values['KDSOUTPUT']);
						$list[$key]['output'][$k]['tahapan'][$kav]['nama_komponen'] = $komponen['URKMPNEN']; 
					}
				}	
			}
		}
		// pr($list);
		// exit;
		$this->view->assign('kd_unit',$kd_unit);
		$this->view->assign('list_dropdown',$list_dropdown);
		$this->view->assign('data',$list);
		$this->view->assign('param',$param);
		$this->view->assign('akses',$akses);
		// pr($list);
		//default kode
		
		return $this->loadView('monev_trwln/list');

	}
	public function editBobot(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev($thn,$kd_giat,$kd_output,$kd_komponen);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				
			}
		
		//add	
		$dinamic_tw = $_GET['trwln'];
		if($dinamic_tw){
			$bl = $dinamic_tw;
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 2;break;
				case 3:$trwulan = 3;break;
				case 4:$trwulan = 4;break;
				}
		// pr($trwulan);		
		}else{
			$bl = date('m');
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 1;break;
				case 3:$trwulan = 1;break;
				case 4:$trwulan = 2;break;
				case 5:$trwulan = 2;break;
				case 6:$trwulan = 2;break;
				case 7:$trwulan = 3;break;
				case 8:$trwulan = 3;break;
				case 9:$trwulan = 3;break;
				case 10:$trwulan = 4;break;
				case 11:$trwulan = 4;break;
				case 12:$trwulan = 4;break;
			}
		}
		// $bl =date('m');
		//coba
		
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			//add 
			$param = '3';
			
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
			$param = '6';
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
			$param = '9';
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
			$param = '12';
		}
		
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($trwulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,3);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_trwln($count['id'],$trwulan);
			if($trwulan == 1){
				$data['kendala'] = $get_data ['kendala'];
				$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
				$data['ygmembantu'] = $get_data['ygmembantu'];
				$data['keterangan'] = $get_data['keterangan'] ;
			}elseif($trwulan == 2){
				$data['kendala'] = $get_data ['kendala_2'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_2'];
				$data['keterangan'] = $get_data['keterangan_2'] ;
			}elseif($trwulan == 3){
				$data['kendala'] = $get_data ['kendala_3'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_3'];
				$data['keterangan'] = $get_data['keterangan_3'] ;
			}elseif($trwulan == 4){
				$data['kendala'] = $get_data ['kendala_4'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_4'];
				$data['keterangan'] = $get_data['keterangan_4'] ;
			}
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			// $data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			if($trwulan == 1){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 2){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 3){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 4){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}
			// $data['keterangan'] = '';
			// $data['kendala'] = '';
			// $data['tindaklanjut'] = '';
			// $data['ygmembantu'] = '';
		}
		
		
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		return $this->loadView('monev_trwln/editBobot');
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
		
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		//$result = " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
	}
	
	public function print_triwulan(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev($thn,$kd_giat,$kd_output,$kd_komponen);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				
			}
		
		//add	
		$dinamic_tw = $_GET['trwln'];
		if($dinamic_tw){
			$bl = $dinamic_tw;
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 2;break;
				case 3:$trwulan = 3;break;
				case 4:$trwulan = 4;break;
				}
		// pr($trwulan);		
		}else{
			$bl = date('m');
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 1;break;
				case 3:$trwulan = 1;break;
				case 4:$trwulan = 2;break;
				case 5:$trwulan = 2;break;
				case 6:$trwulan = 2;break;
				case 7:$trwulan = 3;break;
				case 8:$trwulan = 3;break;
				case 9:$trwulan = 3;break;
				case 10:$trwulan = 4;break;
				case 11:$trwulan = 4;break;
				case 12:$trwulan = 4;break;
			}
		}
		// $bl =date('m');
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			//add 
			$param = '3';
			
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
			$param = '6';
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
			$param = '9';
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
			$param = '12';
		}
		
		$realisasi_bobot = $this->m_penetapanAngaran->get_bobot_trwln($param,$kd_unit,$kd_giat,$kd_output,$kd_komponen);
		if($realisasi_bobot['tw_target']){
			$realisasi_bobot_fix = $realisasi_bobot['tw_target'];
		}else{
			$realisasi_bobot_fix = 0;
		}
		$this->view->assign('realisasi_bobot_fix',$realisasi_bobot_fix);
		
		$realisasi_anggaran = $this->m_penetapanAngaran->get_anggaran_trwln($param,$kd_unit,$kd_giat,$kd_output,$kd_komponen);
		if($realisasi_anggaran['tw_anggaran']){
			$realisasi_anggaran_fix = $realisasi_anggaran['tw_anggaran'];
		}else{
			$realisasi_anggaran_fix = 0;
		}
		$this->view->assign('realisasi_anggaran_fix',$realisasi_anggaran_fix);
		
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($trwulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,3);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_trwln($count['id'],$trwulan);
			if($trwulan == 1){
				$data['kendala'] = $get_data ['kendala'];
				$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
				$data['ygmembantu'] = $get_data['ygmembantu'];
				$data['keterangan'] = $get_data['keterangan'] ;
			}elseif($trwulan == 2){
				$data['kendala'] = $get_data ['kendala_2'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_2'];
				$data['keterangan'] = $get_data['keterangan_2'] ;
			}elseif($trwulan == 3){
				$data['kendala'] = $get_data ['kendala_3'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_3'];
				$data['keterangan'] = $get_data['keterangan_3'] ;
			}elseif($trwulan == 4){
				$data['kendala'] = $get_data ['kendala_4'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_4'];
				$data['keterangan'] = $get_data['keterangan_4'] ;
			}
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			// $data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			if($trwulan == 1){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 2){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 3){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 4){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}
			// $data['keterangan'] = '';
			// $data['kendala'] = '';
			// $data['tindaklanjut'] = '';
			// $data['ygmembantu'] = '';
		}
		
		//kendala
		$exp_kendala = explode('-',$data['kendala']);
		$kendala_fix = array_filter($exp_kendala);
		$this->view->assign('kendala',$kendala_fix);
		
		//tindak lanjut
		$exp_tdklanjut = explode('-',$data['tindaklanjut']);
		$tdklanjut_fix = array_filter($exp_tdklanjut);
		$this->view->assign('tdklanjut',$tdklanjut_fix);
		
		//yang membantu
		$exp_yg_membantu = explode('-',$data['ygmembantu']);
		$yg_membantu_fix = array_filter($exp_yg_membantu);
		$this->view->assign('ygmembantu',$yg_membantu_fix);
		
		//capaian
		$exp_yg_capaian = explode('-',$data['keterangan']);
		$yg_capaian_fix = array_filter($exp_yg_capaian);
		$this->view->assign('capaian',$yg_capaian_fix);
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		$split = substr($kd_unit,0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		$this->reportHelper =$this->loadModel('reportHelper');
		$html = $this->loadView('monev_trwln/print');
		$generate = $this->reportHelper->loadMpdf($html, 'monev-triwulan',2);
	}
	
	
	public function editAnggaran(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev($thn,$kd_giat,$kd_output,$kd_komponen);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				
			}
		
		$bl = date('m');
		$ex = explode ('0',$bl);
		if($ex[0] == ''){
			$arrBln = $ex[1] - 1; 
		}else{
			if($bl == 10){
				$arrBln = $bl - 1;
			}else{
				$arrBln = $ex[0] - 1;
			}
		}
		
		$monthArray = array("01"=>"Januari", "02"=>"Februari", "03"=>"Maret","04"=>"April","05"=>"Mei","06"=>"Juni",
							"07"=>"Juli","08"=>"Agustus","09"=>"September","10"=>"Oktober","11"=>"November","12"=>"Desember");
		foreach ($monthArray as $key=> $valbln){
			if ($bl == $key){
				$ket[]= $valbln;
			}else{
				$ket[]= '';
			}	
		}
			
		$ketBulan = $ket[$arrBln]; 
		
		// pr($tgl);
		switch ($bl){
			case 01:$param = 1;break;
			case 02:$param = 2;break;
			case 03:$param = 3;break;
			case 04:$param = 4;break;
			case 05:$param = 5;break;
			case 06:$param = 6;break;
			case 07:$param = 7;break;
			case 08:$param = 8;break;
			case 09:$param = 9;break;
			case 10:$param = 10;break;
			case 11:$param = 11;break;
			case 12:$param = 12;break;
		}
		//rencana sd bulan
		$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan_anggaran($thn,$kd_giat,$kd_output,$kd_komponen,$param);
		// pr($rencana_sd_bulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,2);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln_anggaran($count['id'],$param);
			$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran($count['id'],$param);
			// pr($get_realisasi);
			$data['pagu'] =  $thp_kegiatan[0]['pagu_kmpnen'];
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = $get_data['jumlah'] ;
			$data['realisasi_sdbulan'] = $get_realisasi['realisasi'] ;
			if($get_realisasi['realisasi'] != 0 || $get_realisasi['realisasi'] != ''){
				$data['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$data['persentase_pagu'] = round(($get_realisasi['realisasi'] / $thp_kegiatan[0]['pagu_kmpnen']) * 100 ,2);
			}else{
				$data['persentase_rencana'] = 0;
				$data['persentase_pagu'] = 0;
			}
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] - $get_realisasi['realisasi'];
			$data['kendala'] = $get_data ['kendala'];
			$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			$data['ygmembantu'] = $get_data['ygmembantu'];
			
		}else{
			$data['pagu'] =  $thp_kegiatan[0]['pagu_kmpnen'];
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = 0;
			$data['realisasi_sdbulan'] = 0;
			$data['persentase_rencana'] = 0;
			$data['persentase_pagu'] = 0;
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] ;
			$data['kendala'] = '';
			$data['tindaklanjut'] = '';
			$data['ygmembantu'] = '';
			$data['jml_target'] = '0';
			
			
		}
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
		// pr($data);
		// pr($info);
		// pr($rinc);
		// pr($list);
		// exit;
		// pr($data);
		//statis
		$totalbobot = '15';
		$sisacapaian = $totalbobot - $data['jml_target']; 
		$this->view->assign('totalbobot',$totalbobot);
		$this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		// $this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('data',$data);
		
		return $this->loadView('monev_trwln/editAnggaran');
	
	}
	public function post(){
		// pr($_POST);
		
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		$kdtriwulan = $_POST['kdtriwulan'];
		
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		$keterangan = $_POST['keterangan'];
		
		//str_replace($bad_symbols, ".",$_POST['target_1']);
		// $target = $_POST['target'];
		
		$bad_symbols = array(",", ".");
		
		// exit;
		// pr($data);
		$count = $this->m_penetapanAngaran->ceck_id($th,$kd_giat,$kd_output,$kd_komponen,3);
		// pr($count);
		if($count['hit'] == 1){
			// echo "masuk";
			// exit;
			$id = $count['id'];
			$update = $this->m_penetapanAngaran->update_monev_trwln($kendala,$tindaklanjut,$ygmembantu,$keterangan,$id,$kdtriwulan);
		}else{
			
			$insert = $this->m_penetapanAngaran->insert_monev_trwln($th,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen,
															$kendala,$tindaklanjut,$ygmembantu,$keterangan,$kdtriwulan);
		}
		
		exit;
		// return $this->loadView('monev_trwln/editTahapan');

	}
	
	public function post_anggaran(){
		// pr($_POST);
		
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		
		
		$bad_symbols = array(",", ".");
		$realisasi = str_replace($bad_symbols, "",$_POST['realisasi']);
		
		// exit;
		// pr($data);
		$count = $this->m_penetapanAngaran->ceck_id($th,$kd_giat,$kd_output,$kd_komponen,2);
		// pr($count);
		if($count['hit'] == 1){
			// echo "masuk";
			// exit;
			$id = $count['id'];
			$update = $this->m_penetapanAngaran->update_monev_anggaran($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$realisasi,$id);
		}else{
			
			$insert = $this->m_penetapanAngaran->insert_monev_anggaran($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen,
															$kendala,$tindaklanjut,$ygmembantu,$realisasi);
		}
		
		exit;
		// return $this->loadView('monev_trwln/editTahapan');

	}
	
	public function editTahapan(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		$kd_soutput = $_GET['kd_soutput'];
		// pr($_GET);
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		$info['kd_soutput'] = $kd_soutput;
		
		//$thp kegiatan
		$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condtn($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				$sub_komponen = $this->m_penetapanAngaran->sub_komponen_cdtn($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
				
					foreach($sub_komponen as $sb=>$sub){
						$list[$key]['sub_komponen'][] = $sub;
						
					}	
				
			}	
		// pr($info);		
		// pr($list);
		// exit;
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		return $this->loadView('monev_trwln/editTahapan');

	}

	public function ajax_insert(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		// pr($_POST);
		$kd_tahapan =$_POST['kd_tahapan'];
		$nm_tahapan =$_POST['nm_tahapan'];
		$thn =$_POST['thn'];
		$kd_unit =$_POST['kd_unit'];
		$kd_giat =$_POST['kd_giat'];
		$kd_output =$_POST['kd_output'];
		$kd_komponen =$_POST['kd_komponen'];
		$kd_soutput =$_POST['kd_soutput'];
		
		
		
		if ($kd_tahapan != '' && $nm_tahapan != ''){
			$insert = $this->m_penetapanAngaran->insert_data($kd_tahapan,$nm_tahapan,$thn,$kd_unit,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
			// echo json_encode($data);
		}
		exit;
		
	}
	
	public function ajax_edit(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$id =$_POST['id'];
		
		if ($id != ''){
			$edit = $this->m_penetapanAngaran->edit_data($id);
			echo json_encode($edit);
		}
		exit;
	}
	
	public function ajax_update(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$id =$_POST['id_hid'];
		$kd_tahapan =$_POST['kd_tahapan'];
		$nm_tahapan =$_POST['nm_tahapan'];
		
		if ($id != ''){
			$update = $this->m_penetapanAngaran->update_data($id,$kd_tahapan,$nm_tahapan);
			// echo json_encode($edit);
		}
		exit;
		
	}
	public function ajax_hapus(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$id =$_POST['id'];
		if ($id != ''){
			$update = $this->m_penetapanAngaran->hapus_data($id);
			// echo json_encode($edit);
		}
		exit;
		
	}
	public function editRencanaAnggaran(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		$kd_soutput = $_GET['kd_soutput'];
		$id = $_GET['id'];
		// pr($_GET);
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		$info['kd_soutput'] = $kd_soutput;
		
		$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condtn($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				$sub_komponen = $this->m_penetapanAngaran->sub_komponen_cdtn_sub($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput,$id);
				
					foreach($sub_komponen as $sb=>$sub){
						$list[$key]['sub_komponen'][] = $sub;
						
					}	
				
			}	
		// pr($list);	
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);	
		return $this->loadView('monev_trwln/editRencanaAnggaran');

	}

	public function ajax_simpan_sub(){
	
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		// pr($_POST);
		$bad_symbols = array(",", ".");
		$id =$_POST['id'];
		/*$target_1 =round($_POST['target_1'],2);
		$target_2 =round($_POST['target_2'],2);
		$target_3 =round($_POST['target_3'],2);
		$target_4 =round($_POST['target_4'],2);
		$target_5 =round($_POST['target_5'],2);
		$target_6 =round($_POST['target_6'],2);
		$target_7 =round($_POST['target_7'],2);
		$target_8 =round($_POST['target_8'],2);
		$target_9 =round($_POST['target_9'],2);
		$target_10 =round($_POST['target_10'],2);
		$target_11 =round($_POST['target_11'],2);
		$target_12 =round($_POST['target_12'],2);*/
		
		$target_1 =str_replace($bad_symbols, ".",$_POST['target_1']);
		$target_2 =str_replace($bad_symbols, ".",$_POST['target_2']);
		$target_3 =str_replace($bad_symbols, ".",$_POST['target_3']);
		$target_4 =str_replace($bad_symbols, ".",$_POST['target_4']);
		$target_5 =str_replace($bad_symbols, ".",$_POST['target_5']);
		$target_6 =str_replace($bad_symbols, ".",$_POST['target_6']);
		$target_7 =str_replace($bad_symbols, ".",$_POST['target_7']);
		$target_8 =str_replace($bad_symbols, ".",$_POST['target_8']);
		$target_9 =str_replace($bad_symbols, ".",$_POST['target_9']);
		$target_10 =str_replace($bad_symbols, ".",$_POST['target_10']);
		$target_11 =str_replace($bad_symbols, ".",$_POST['target_11']);
		$target_12 =str_replace($bad_symbols, ".",$_POST['target_12']);
		
		$anggaran_1 =str_replace($bad_symbols, "",$_POST['anggaran_1']);
		$anggaran_2 =str_replace($bad_symbols, "",$_POST['anggaran_2']);
		$anggaran_3 =str_replace($bad_symbols, "",$_POST['anggaran_3']);
		$anggaran_4 =str_replace($bad_symbols, "",$_POST['anggaran_4']);
		$anggaran_5 =str_replace($bad_symbols, "",$_POST['anggaran_5']);
		$anggaran_6 =str_replace($bad_symbols, "",$_POST['anggaran_6']);
		$anggaran_7 =str_replace($bad_symbols, "",$_POST['anggaran_7']);
		$anggaran_8 =str_replace($bad_symbols, "",$_POST['anggaran_8']);
		$anggaran_9 =str_replace($bad_symbols, "",$_POST['anggaran_9']);
		$anggaran_10 =str_replace($bad_symbols, "",$_POST['anggaran_10']);
		$anggaran_11 =str_replace($bad_symbols, "",$_POST['anggaran_11']);
		$anggaran_12 =str_replace($bad_symbols, "",$_POST['anggaran_12']);
		
		
		
		
		if ($id != ''){
			$update = $this->m_penetapanAngaran->update_data_sub($id,$target_1,$target_2,$target_3,$target_4,$target_5,$target_6,
															 $target_7,$target_8,$target_9,$target_10,$target_11,$target_12,
															 $anggaran_1,$anggaran_2,$anggaran_3,$anggaran_4,$anggaran_5,$anggaran_6,
															 $anggaran_7,$anggaran_8,$anggaran_9,$anggaran_10,$anggaran_11,$anggaran_12);
			// echo json_encode($data);
		}
		exit;
		
	}

	public function monev_trwln(){
		global $basedomain;
		//=============================== 
		/*if($this->admin['type'] == 1){
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		}else{
			$str = rtrim($this->admin['kode'], '0');
			$length = strlen($str);
			if($length == 3){
				$param_list = '1';
				$kd_unit_list = $str;
			}elseif($length == 4){
				$param_list = '2';
				$kd_unit_list = $this->admin['kode'];
			}
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown_cstmn($param_list,$kd_unit_list);
		}*/
		$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		//===============================
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// pr($thn_aktif);
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		$thn_renstra =$thn_aktif['data'];
		
		if($_POST['unit'] !=''){
			// pr($_POST['unit']);
			// echo "masukk";
			// exit;
			$kd_unit = $_POST['unit'];
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			
			
			/*if($thn_temp >= 2015 and $thn_temp<=2019){
				$thn_renstra ='2015-2019'; 
			}elseif($thn_temp >= 2020 and $thn_temp<=2024){
				$thn_renstra ='2020-2024'; 
			}elseif($thn_temp >= 2025 and $thn_temp<=2029){
				$thn_renstra ='2025-2029'; 
			}elseif($thn_temp >= 2030 and $thn_temp<=2034){
				$thn_renstra ='2030-2034'; 
			}*/
			
			
			// pr($thn_renstra);
			$kd_kegiatan = $this->m_penetapanAngaran->kd_kegiatan($thn_renstra,$kd_unit);
			foreach($kd_kegiatan as $key=>$val){
				$list[] = $val;
				$pagu_giat = $this->m_penetapanAngaran->pagu_giat($thn_temp,$val['kdgiat']);
				$list[$key]['pagu_giat'] = $pagu_giat['pagu_giat'];
				
				//output
				$output = $this->m_penetapanAngaran->output($thn_temp,$val['kdgiat']);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
					
					//$thp kegiatan
					/*$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn_temp,$val['kdgiat'],$out['KDOUTPUT']);
					foreach ($thp_kegiatan as $kav=>$values){
						$list[$key]['output'][$k]['tahapan'][] = $values; 
						$komponen = $this->m_penetapanAngaran->komponen($thn_temp,$val['kdgiat'],$out['KDOUTPUT'],$values['KDKMPNEN'],$values['KDSOUTPUT']);
						$list[$key]['output'][$k]['tahapan'][$kav]['nama_komponen'] = $komponen['URKMPNEN']; 
					}*/
				}
				
			}
		}else{
			//default Biro perencanaan, keuangan dan tata usaha
			//===============================		
			/*if($this->admin['type'] == 1){
				$kode = '841100';
			}else{
				$str = rtrim($this->admin['kode'], '0');
				$length = strlen($str);
				if($length == 3){
					$param_list = $str;
					$select_unit = $this->m_penetapanAngaran->list_unit($param_list);
					// pr($select_unit);
					$kode = $select_unit['kdunit']; 
				}elseif($length == 4){
					$kode = $this->admin['kode'];
				}
			}*/
			//===============================
			$kd_unit ='841100';
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			
			/*if($thn_temp >= 2015 and $thn_temp<=2019){
				$thn_renstra ='2015-2019'; 
			}elseif($thn_temp >= 2020 and $thn_temp<=2024){
				$thn_renstra ='2020-2024'; 
			}elseif($thn_temp >= 2025 and $thn_temp<=2029){
				$thn_renstra ='2025-2029'; 
			}elseif($thn_temp >= 2030 and $thn_temp<=2034){
				$thn_renstra ='2030-2034'; 
			}*/
			
			
			$kd_kegiatan = $this->m_penetapanAngaran->kd_kegiatan($thn_renstra,$kd_unit);
			foreach($kd_kegiatan as $key=>$val){
				$list[] = $val;
				$pagu_giat = $this->m_penetapanAngaran->pagu_giat($thn_temp,$val['kdgiat']);
				// pr($pagu_giat);
				$list[$key]['pagu_giat'] = $pagu_giat['pagu_giat'];
				
				//output
				$output = $this->m_penetapanAngaran->output($thn_temp,$val['kdgiat']);
				// pr($output);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
				
					//$thp kegiatan
					// $thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn_temp,$val['kdgiat'],$out['KDOUTPUT']);
					// pr($thp_kegiatan);
					/*foreach ($thp_kegiatan as $kav=>$values){
						$list[$key]['output'][$k]['tahapan'][] = $values; 
						$komponen = $this->m_penetapanAngaran->komponen($thn_temp,$val['kdgiat'],$out['KDOUTPUT'],$values['KDKMPNEN'],$values['KDSOUTPUT']);
						$list[$key]['output'][$k]['tahapan'][$kav]['nama_komponen'] = $komponen['URKMPNEN']; 
					}*/
				}	
			}
		}
		// pr($list);
		// exit;
		$this->view->assign('kd_unit',$kd_unit);
		$this->view->assign('list_dropdown',$list_dropdown);
		$this->view->assign('data',$list);
		$this->view->assign('param',$param);
		$this->view->assign('akses',$akses);
		// pr($list);
		//default kode
		
		return $this->loadView('monev_trwln/listmonev');

	}	
	
	public function editBobotmonev(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		$kd_komponen = $_GET['kd_komponen'];
		
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		$info['kd_komponen'] = $kd_komponen;
		
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev_rev($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				
			}
		// pr($list);
		//add	
		$dinamic_tw = $_GET['trwln'];
		if($dinamic_tw){
			$bl = $dinamic_tw;
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 2;break;
				case 3:$trwulan = 3;break;
				case 4:$trwulan = 4;break;
				}
		// pr($trwulan);		
		}else{
			$bl = date('m');
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 1;break;
				case 3:$trwulan = 1;break;
				case 4:$trwulan = 2;break;
				case 5:$trwulan = 2;break;
				case 6:$trwulan = 2;break;
				case 7:$trwulan = 3;break;
				case 8:$trwulan = 3;break;
				case 9:$trwulan = 3;break;
				case 10:$trwulan = 4;break;
				case 11:$trwulan = 4;break;
				case 12:$trwulan = 4;break;
			}
		}
		// $bl =date('m');
		//coba
		
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			//add 
			$param = '3';
			
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
			$param = '6';
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
			$param = '9';
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
			$param = '12';
		}
		
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($trwulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,3);
		// pr($count);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_trwln($count['id'],$trwulan);
			if($trwulan == 1){
				$data['kendala'] = $get_data ['kendala'];
				$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
				$data['ygmembantu'] = $get_data['ygmembantu'];
				$data['keterangan'] = $get_data['keterangan'] ;
			}elseif($trwulan == 2){
				$data['kendala'] = $get_data ['kendala_2'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_2'];
				$data['keterangan'] = $get_data['keterangan_2'] ;
			}elseif($trwulan == 3){
				$data['kendala'] = $get_data ['kendala_3'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_3'];
				$data['keterangan'] = $get_data['keterangan_3'] ;
			}elseif($trwulan == 4){
				$data['kendala'] = $get_data ['kendala_4'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_4'];
				$data['keterangan'] = $get_data['keterangan_4'] ;
			}
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			// $data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			if($trwulan == 1){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 2){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 3){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 4){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}
			// $data['keterangan'] = '';
			// $data['kendala'] = '';
			// $data['tindaklanjut'] = '';
			// $data['ygmembantu'] = '';
		}
		
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		
		//validator
		//if($this->admin['type'] == 1){
			$acces = "";
			$sub = "";
			$valid = 1;
			$akses = 1;
		/*}else{
			$acces = "disabled";
			$akses = 0;
			$ceck_month = date('m');
			$ceck_date = date('d');
			$limit_date = 15;
			// pr($ceck_month);
			// pr($ceck_date);
			if($trwulan == 1){
				// pr("TW I");
				if($ceck_month < '03' ){
					$sub = "";
					$valid = 1;
					// pr("SUCCESS");
				}else{
					if($ceck_date <= $limit_date){
						$sub = "";
						$valid = 1;
						// pr("SUCCESS");
					}else{
						$sub = "disabled";
						$valid = 0;
						// pr("DENIED");
					}
				}
			}elseif($trwulan == 2){
				// pr("TW II");
				if($ceck_month < '06' ){
					$sub = "";
					$valid = 1;
					// pr("SUCCESS");
				}else{
					if($ceck_date <= $limit_date){
						$sub = "";
						$valid = 1;
						// pr("SUCCESS");
					}else{
						$sub = "disabled";
						$valid = 0;
						// pr("DENIED");
					}
				}
			}elseif($trwulan == 3){
				// pr("TW III");
				if($ceck_month < '09' ){
					$sub = "";
					$valid = 1;
					// pr("SUCCESS");
				}else{
					if($ceck_date <= $limit_date){
						$sub = "";
						$valid = 1;
						// pr("SUCCESS");
					}else{
						$sub = "disabled";
						$valid = 0;
						// pr("DENIED");
					}
				}
			}elseif($trwulan == 4){
				// pr("TW IV");
				if($ceck_month < '12' ){
					$sub = "";
					$valid = 1;
					// pr("SUCCESS");
				}else{
					if($ceck_date <= $limit_date){
						$sub = "";
						$valid = 1;
						// pr("SUCCESS");
					}else{
						$sub = "disabled";
						$valid = 0;
						// pr("DENIED");
					}
				}
			}
		}*/
		
		$this->view->assign('acces',$acces);
		$this->view->assign('sub',$sub);
		$this->view->assign('valid',$valid);
		$this->view->assign('akses',$akses);
		
		return $this->loadView('monev_trwln/editBobotmonev');
	}
	
	public function postmonev(){
		// pr($_POST);
		// exit;
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		// $kd_komponen = $_POST['kd_komponen'];
		$kdtriwulan = $_POST['kdtriwulan'];
		
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		$keterangan = $_POST['keterangan'];
		
		//str_replace($bad_symbols, ".",$_POST['target_1']);
		// $target = $_POST['target'];
		
		// $bad_symbols = array(",", ".");
		
		// exit;
		// pr($data);
		$count = $this->m_penetapanAngaran->ceck_id_output($th,$kd_giat,$kd_output,3);
		// pr($count);
		if($count['hit'] == 1){
			// echo "masuk";
			// exit;
			$id = $count['id'];
			$update = $this->m_penetapanAngaran->update_monev_trwln($kendala,$tindaklanjut,$ygmembantu,$keterangan,$id,$kdtriwulan);
		}else{
			
			$insert = $this->m_penetapanAngaran->insert_monev_trwln_rev($th,$kdunitkerja,$kd_giat,$kd_output,
															$kendala,$tindaklanjut,$ygmembantu,$keterangan,$kdtriwulan);
		}
		
		exit;
		// return $this->loadView('monev_trwln/editTahapan');

	}
	
	public function getdata_rev(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		//$kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$data = $this->m_penetapanAngaran->getdata_rev($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen);
		if($trwln == 1){
			$data['keterangan'] = $data['keterangan'];
			$data['kendala'] = $data['kendala'];
			$data['tindaklanjut'] = $data['tindaklanjut'];
			$data['ygmembantu'] = $data['ygmembantu'];
		}elseif($trwln == 2){
			$data['keterangan'] = $data['keterangan_2'];
			$data['kendala'] = $data['kendala_2'];
			$data['tindaklanjut'] = $data['tindaklanjut_2'];
			$data['ygmembantu'] = $data['ygmembantu_2'];
		}elseif($trwln == 3){
			$data['keterangan'] = $data['keterangan_3'];
			$data['kendala'] = $data['kendala_3'];
			$data['tindaklanjut'] = $data['tindaklanjut_3'];
			$data['ygmembantu'] = $data['ygmembantu_3'];
		}elseif($trwln == 4){
			$data['keterangan'] = $data['keterangan_4'];
			$data['kendala'] = $data['kendala_4'];
			$data['tindaklanjut'] = $data['tindaklanjut_4'];
			$data['ygmembantu'] = $data['ygmembantu_4'];
		}
		// pr($rencana);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('keterangan'=>$data['keterangan'],'kendala'=>$data['kendala'],'tindaklanjut'=>$data['tindaklanjut'],'ygmembantu'=>$data['ygmembantu']);
		print json_encode($newformat);
		exit;
	}
	
	public function rencana_rev(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		// $kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$rencana = $this->m_penetapanAngaran->rencana_anggaran_rev($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output);
		$realisasi = $this->m_penetapanAngaran->realisasi_anggaran_rev($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output);
		// pr($rencana);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('rncn'=>$rencana,'real'=>$realisasi);
		print json_encode($newformat);
		exit;
	}
	
	public function bobot_rev(){
		$trwln = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		$thn_temp = $thn_aktif['kode'];
		
		
		$rencana = $this->m_penetapanAngaran->rencana_bobot_rev($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output);
		$realisasi = $this->m_penetapanAngaran->realisasi_bobot_rev($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output);
		// pr($rencana);
		// pr($realisasi);
		// $newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		$newformat = array('rncn'=>$rencana,'real'=>$realisasi);
		print json_encode($newformat);
		exit;
	}
	
	public function print_triwulan_rev(){
		
		// pr($_POST);
		// exit;
		
		global $basedomain;
		$thn = $_POST['th'];
		$kd_unit = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		//$kd_komponen = $_GET['kd_komponen'];
		// pr($_POST);
		//exit;
		//Deskripsi Kegiatan
		//nama output
		$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
		$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
		//nama kegiatan
		$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		//unit eselon 
		$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
		
		$info['nama_output'] = $nama_output['NMOUTPUT'];
		$info['pagu_output'] = $pagu_output['pagu_output'];
		$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
		$info['unit_eselon'] = $unit_eselon['nmunit'];
		$info['thn'] = $thn;
		$info['kd_unit'] = $kd_unit;
		$info['kd_giat'] = $kd_giat;
		$info['kd_output'] = $kd_output;
		//$info['kd_komponen'] = $kd_komponen;
		
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev_rev($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				
			}
		
		//add	
		$dinamic_tw = $_POST['kdtriwulan'];
		if($dinamic_tw){
			$bl = $dinamic_tw;
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 2;break;
				case 3:$trwulan = 3;break;
				case 4:$trwulan = 4;break;
				}
		// pr($trwulan);		
		}else{
			$bl = date('m');
			switch ($bl){
				case 1:$trwulan = 1;break;
				case 2:$trwulan = 1;break;
				case 3:$trwulan = 1;break;
				case 4:$trwulan = 2;break;
				case 5:$trwulan = 2;break;
				case 6:$trwulan = 2;break;
				case 7:$trwulan = 3;break;
				case 8:$trwulan = 3;break;
				case 9:$trwulan = 3;break;
				case 10:$trwulan = 4;break;
				case 11:$trwulan = 4;break;
				case 12:$trwulan = 4;break;
			}
		}
		// $bl =date('m');
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			//add 
			$param = '3';
			
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
			$param = '6';
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
			$param = '9';
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
			$param = '12';
		}
		
		$realisasi_bobot = $this->m_penetapanAngaran->get_bobot_trwln_rev($param,$kd_unit,$kd_giat,$kd_output);
		if($realisasi_bobot['tw_target']){
			$realisasi_bobot_fix = $realisasi_bobot['tw_target'];
		}else{
			$realisasi_bobot_fix = 0;
		}
		$this->view->assign('realisasi_bobot_fix',$realisasi_bobot_fix);
		
		$realisasi_anggaran = $this->m_penetapanAngaran->get_anggaran_trwln_rev($param,$kd_unit,$kd_giat,$kd_output);
		if($realisasi_anggaran['tw_anggaran']){
			$realisasi_anggaran_fix = $realisasi_anggaran['tw_anggaran'];
		}else{
			$realisasi_anggaran_fix = 0;
		}
		$this->view->assign('realisasi_anggaran_fix',$realisasi_anggaran_fix);
		
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($trwulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,3);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_trwln($count['id'],$trwulan);
			if($trwulan == 1){
				$data['kendala'] = $get_data ['kendala'];
				$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
				$data['ygmembantu'] = $get_data['ygmembantu'];
				$data['keterangan'] = $get_data['keterangan'] ;
			}elseif($trwulan == 2){
				$data['kendala'] = $get_data ['kendala_2'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_2'];
				$data['keterangan'] = $get_data['keterangan_2'] ;
			}elseif($trwulan == 3){
				$data['kendala'] = $get_data ['kendala_3'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_3'];
				$data['keterangan'] = $get_data['keterangan_3'] ;
			}elseif($trwulan == 4){
				$data['kendala'] = $get_data ['kendala_4'];
				$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
				$data['ygmembantu'] = $get_data['ygmembantu_4'];
				$data['keterangan'] = $get_data['keterangan_4'] ;
			}
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			// $data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			if($trwulan == 1){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 2){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 3){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}elseif($trwulan == 4){
				$data['keterangan'] = '';
				$data['kendala'] = '';
				$data['tindaklanjut'] = '';
				$data['ygmembantu'] = '';
			}
			// $data['keterangan'] = '';
			// $data['kendala'] = '';
			// $data['tindaklanjut'] = '';
			// $data['ygmembantu'] = '';
		}
		
		//kendala
		$exp_kendala = explode('-',$data['kendala']);
		$kendala_fix = array_filter($exp_kendala);
		// $this->view->assign('kendala',$kendala_fix);
		// pr($kendala_fix);
		//tindak lanjut
		$exp_tdklanjut = explode('-',$data['tindaklanjut']);
		$tdklanjut_fix = array_filter($exp_tdklanjut);
		// $this->view->assign('tdklanjut',$tdklanjut_fix);
		// pr($tdklanjut_fix);
		//yang membantu
		$exp_yg_membantu = explode('-',$data['ygmembantu']);
		$yg_membantu_fix = array_filter($exp_yg_membantu);
		// $this->view->assign('ygmembantu',$yg_membantu_fix);
		// pr($yg_membantu_fix);
		//capaian
		$exp_yg_capaian = explode('-',$data['keterangan']);
		$yg_capaian_fix = array_filter($exp_yg_capaian);
		// $this->view->assign('capaian',$yg_capaian_fix);
		// pr($yg_capaian_fix);
		
		//new add		
		/*$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);*/
		//pr($_POST);
		$tglcetak = $_POST['tglcetak'];
		if($tglcetak){
			$ex = explode("/", $tglcetak);
			$length_tgl    = strlen($ex['0']);
			if($length_tgl == 1){
				$tanggal = "0".$ex['0'];
			}else{
				$tanggal = $ex['0'];
			}
			
			$length_bulan  = strlen($ex['1']); 
			if($length_bulan == 1){
				$bulan = "0".$ex['1'];
			}else{
				$bulan = $ex['1'];
			}			
			
			$thn = $ex['2'];
			$tgl = $thn.'-'.$bulan.'-'.$tanggal;
			$tgl_format = $this->DateToIndo($tgl);
		}else{
			$tgl = date("Y-m-d");
			$tgl_format = $this->DateToIndo($tgl);
		}
		//pr($tgl_format);
		//exit;
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		if($kd_unit === '845100'){
			$join = '841000';
		}else{
			$split = substr($kd_unit,0,3);
			$join = $split.'000';
		}
		//$ttd_nama[nmunit]
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		$kd_eselon_I = $join;
		$nama_pejabat_eselon_I = $this->model->nama_pejabat($kd_eselon_I);
		// pr($nama_pejabat_eselon_I);
		//$pejabat_eselon_I = unserialize($nama_pejabat_eselon_I['custom_text']);
		// $this->view->assign('nama_pejabat_eselon_I',$pejabat_eselon_I['pejabat']);
		//$nama_pejabat_eselon_I = $pejabat_eselon_I['pejabat'];
		//$this->view->assign('jabatan_pejabat_eselon_I',$nama_pejabat_eselon_I['brief']);
		//$this->view->assign('nama_pejabat_eselon_I',$nama_pejabat_eselon_I['desc']);
		$jabatan_pejabat_eselon_I = $nama_pejabat_eselon_I['brief'];
		$nama_pejabat_eselon_I = $nama_pejabat_eselon_I['desc'];

		$kd_eselon_II = $kd_unit;
		$nama_pejabat_eselon_II = $this->model->nama_pejabat($kd_eselon_II);
		// pr($nama_pejabat_eselon_I);
		//$pejabat_eselon_II = unserialize($nama_pejabat_eselon_II['custom_text']);
		// $this->view->assign('nama_pejabat_eselon_II',$pejabat_eselon_II['pejabat']);
		//$nama_pejabat_eselon_II = $pejabat_eselon_II['pejabat'];
		//$this->view->assign('jabatan_pejabat_eselon_II',$nama_pejabat_eselon_II['brief']);
		//$this->view->assign('nama_pejabat_eselon_II',$nama_pejabat_eselon_II['desc']);
		$jabatan_pejabat_eselon_II = $nama_pejabat_eselon_II['brief'];
		$nama_pejabat_eselon_II = $nama_pejabat_eselon_II['desc'];
		//code_for_view_chart_anggaran
		$scrn_anggaran = $_POST['scrn_anggaran'];
		$this->view->assign('chart_anggaran',$scrn_anggaran);
		
		//code_for_view_chart_kinerja 
		$scrn_kinerja = $_POST['scrn_kinerja'];
		$this->view->assign('chart_kinerja',$scrn_kinerja);
		
		/*$this->view->assign('dataselected',$dataselected);
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);*/
		// pr($info);
		// exit;
		$output = $info[nama_output]." (".$info[kd_giat].".".$info[kd_output].")";
		$kegiatan = $info[nama_kegiatan]." (".$info[kd_giat].")";
		$jml_anggran = number_format($info[pagu_output],"0",",",".");
		$realisasi_bobot = $realisasi_bobot_fix." %";
		$realisasi_anggaran = number_format($realisasi_anggaran_fix,"0",",",".");
		
		$this->reportHelper =$this->loadModel('reportHelper');
		$header="<div class=\"row\">
				<div class=\"col-md-12\">
					<h3 align=\"center\" style=\"text-transform: uppercase;\">LAPORAN KEGIATAN s.d $dataselected[4]</h3>
					<h3 align=\"center\" style=\"text-transform: uppercase;\">Tahun $info[thn]</h3>
				</div>
			</div>
		<div>
	<label> <strong>A. Deskripsi</strong></label>
	</div>
	<div>
		<table cellspacing=\"0\" width=\"100%\">
			<tr>
				<td style=\"width:10px\">1.</td>
				<td style=\"width:250px\">Output/Indikator Kinerja Program </td>
				<td>: $output</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style=\"width:10px\">2.</td>
				<td style=\"width:250px\">Kegiatan/Nama Program</td>
				<td>: $kegiatan</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style=\"width:10px\">3.</td>
				<td style=\"width:250px\">Jumlah Anggaran</td>
				<td>: $jml_anggran</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style=\"width:10px\">4.</td>
				<td style=\"width:250px\">Unit Kerja</td>
				<td>: $info[unit_eselon]</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style=\"width:10px\">5.</td>
				<td style=\"width:250px\">Realisasi Kinerja</td>
				<td>: $realisasi_bobot</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td style=\"width:10px\">6.</td>
				<td style=\"width:250px\">Realisasi Anggaran</td>
				<td>: $realisasi_anggaran</td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>
	<br/>
	<div>";
	$chart="<div>
				<label class=\"control-label\"><strong>B. Kemajuan Realisasi Pembiayaan</strong></label>
			</div>
			
			<img src=\"$scrn_anggaran\" alt=\"\"  />
			<br/>
			<div>
				<label class=\"control-label\"><strong>C. Grafik Pencapaian Realisasi Kinerja</strong></label>
			</div>
				<img src=\"$scrn_kinerja\" alt=\"\" />
			<br/>";
	$content ="<div>
				<label class=\"control-label\"><strong>D. Realisasi Kinerja</strong></label>
			   </div>
					<dl>
						<dt><strong><u>Capaian kinerja kegiatan :</u></strong></dt>";
						/*foreach ($yg_capaian_fix as $dt_capaian){
							$content .="<dd>- $dt_capaian</dd>";
						}
					$content .="</dl>";*/
					$content .="$data[keterangan]";
					$content .="</dl>";
	$content.="<dl>
					<dt><strong><u>Kendala-kendala yang dihadapi :</u></strong></dt>";
						/*foreach ($kendala_fix as $dt_kendala){
							$content .="<dd>- $dt_kendala</dd>";
					  }
			   $content .="</dl>";*/
					$content .="$data[kendala]";
					$content .="</dl>";
	$content.="<dl>
					<dt><strong><u>Rencana Tindak lanjut :</u></strong></dt>";
						/*foreach ($tdklanjut_fix as $dt_tdklnjt){
							$content .="<dd>- $dt_tdklnjt</dd>";
					  }
			   $content .="</dl>";*/
					$content .="$data[tindaklanjut]";
					$content .="</dl>";
	$content.="<dl>
					<dt><strong><u>Pihak yang dapat membantu penyelesaian kendala/permasalahan :</u></strong></dt>";
						/*foreach ($yg_membantu_fix as $dt_ygmembantu){
							$content .="<dd>- $dt_ygmembantu</dd>";
					  }
			  $content .="<dl>";*/
					$content .="$data[ygmembantu]";
					$content .="</dl>";
			  
	$footer = "<br/>
				<div>
				<table style=\"text-align: left; border-collapse: collapse; width: 100%; height: 90px; font-size:\" border=\"0\" >
					<tr>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">Jakarta, $tgl_format</td>
					</tr>
					<tr>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">Mengetahui:</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">Penanggung Jawab Kegiatan</td>
					</tr>
					<tr>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\"> 
					   $jabatan_pejabat_eselon_I </td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\"> 
					   $jabatan_pejabat_eselon_II $info[unit_eselon]</td>
					</tr>
					<tr>
					   <td colspan=\"11\" style=\"height: 100px\"></td>
					</tr>
					<tr>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">($nama_pejabat_eselon_I)</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td>&nbsp;</td>
					   <td style=\"text-align: center;\" colspan=\"3\" width=\"400px\">($nama_pejabat_eselon_II)</td>
					</tr>
				</table>
				</div>";				
	$html = $header.$chart.$content.$footer;
	// echo $html;
	// exit;
		// $html = $this->loadView('monev_trwln/printrev');
		// echo $html; 
		$generate = $this->reportHelper->loadMpdf($html, 'monev-triwulan',2);
		exit;
	}
	
	
}

?>
