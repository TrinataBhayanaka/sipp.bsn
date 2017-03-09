<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class monev_pp39 extends Controller {
	
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
		$this->contentHelper = $this->loadModel('contentHelper');
	
		
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
			$kd_unit = '841100';
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			$thn_renstra =$thn_aktif['data'];
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
		
		return $this->loadView('monev/list');

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
		$dinamic_bl = $_GET['bln'];
		if($dinamic_bl){
			$bl = $dinamic_bl;
		}else{
			$bl = date('m');
		}
		
		
		
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
		$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,1);
		$realisasi_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,2);
		
		// pr($rencana_sd_bulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,1);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln($count['id'],$param);
			
			switch ($bl){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			
			$data['jml_target'] = $get_data['jumlah'] ;
			$data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			switch ($bl){
				case 01:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 02:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
			}
			
			$data['keterangan'] = '';
			$data['jml_target'] = '0';
			
			
		}
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
		// pr($info);
		// pr($rinc);
		// pr($list);
		// exit;
		// pr($data);
		//example
		// $exp_tdklanjut = explode('-',$data['tindaklanjut']);
		// pr($exp_tdklanjut);
		//statis
		// $totalbobot = '15';
		$totalbobot = $this->m_penetapanAngaran->bobot_komponen($thn,$kd_giat,$kd_output,$kd_komponen);
		// pr($totalbobot);
		$this->view->assign('usertype',$this->admin['type']);
		$sisacapaian = $totalbobot['bobot'] - $realisasi_sd_bulan['total']; 
		$this->view->assign('totalbobot',$totalbobot['bobot']);
		$this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		$this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('realisasisdbulan',$realisasi_sd_bulan['total']);
		$this->view->assign('data',$data);
	
		return $this->loadView('monev/editBobot');
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
	
	public function print_monev_bobot(){
		global $basedomain;
		$thn = $_GET['th'];
		$kd_unit = $_GET['kdunitkerja'];
		$kd_giat = $_GET['kdgiat'];
		$kd_output = $_GET['kdoutput'];
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
		$dinamic_bl = $_GET['bulan'];
		if($dinamic_bl){
			$bl = $dinamic_bl;
		}else{
			$bl = date('m');
		}
		
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
		$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,1);
		$realisasi_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,2);
		
		// pr($rencana_sd_bulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,1);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln($count['id'],$param);
			
			switch ($bl){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			
			$data['jml_target'] = $get_data['jumlah'] ;
			$data['keterangan'] = $get_data['keterangan'] ;
			
		}else{
			switch ($bl){
				case 01:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 02:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
			}
			
			$data['keterangan'] = '';
			$data['jml_target'] = '0';
			
			
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
		
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		$split = substr($kd_unit,0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		// pr($info);
		// pr($rinc);
		// pr($list);
		// exit;
		// pr($data);
		//statis
		// $totalbobot = '15';
		$totalbobot = $this->m_penetapanAngaran->bobot_komponen($thn,$kd_giat,$kd_output,$kd_komponen);
		// pr($totalbobot);
		$this->view->assign('usertype',$this->admin['type']);
		$sisacapaian = $totalbobot['bobot'] - $realisasi_sd_bulan['total']; 
		$this->view->assign('totalbobot',$totalbobot['bobot']);
		$this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		$this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('realisasisdbulan',$realisasi_sd_bulan['total']);
		$this->view->assign('data',$data);
		$this->reportHelper =$this->loadModel('reportHelper');
		$html = $this->loadView('monev/printBobot');
		$generate = $this->reportHelper->loadMpdf($html, 'monev-bulanan-bobot',2);
	}
	
	public function getdatabobot(){
		$thn = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		$total = $_POST['total'];
		
		switch ($bulan){
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
		
		$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,1);
		$realisasi_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$kd_komponen,$param,2);
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,1);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln($count['id'],$param);
			// $data = $get_data['jumlah'] ;
			switch ($bulan){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'];
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'];
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'];
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'];
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'];
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'];
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'];
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'];
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'];
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'];
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'];
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			$data['jml_target'] = number_format($get_data['jumlah'],'2',',','.');
		}else{
			$data['kendala'] = '';
			$data['tindaklanjut'] ='';
			$data['ygmembantu'] ='';
			$data['jml_target'] = number_format(0,'2',',','.');
			// $data = '0';
			
		}
		$sisa = $total - $realisasi_sd_bulan['total'];
		$newformat = array('rncn_sdbln'=>$rencana_sd_bulan['total'],'real_blnini'=>$data['jml_target'],'real_sdbln'=>$realisasi_sd_bulan['total'],'sisa'=>$sisa,'kendala'=>$data['kendala'],'tindaklanjut'=>$data['tindaklanjut'],'ygmembantu'=>$data['ygmembantu']);
		print json_encode($newformat);
		exit;
	}
	
	public function getdataanggaran(){
		
		$thn = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		$pagu = $_POST['pagu'];
		
		$bad_symbols = array(",", ".");
		$pagu =str_replace($bad_symbols, "",$_POST['pagu']);
		// pr($pagu);
		switch ($bulan){
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
		$count = $this->m_penetapanAngaran->ceck_id($thn,$kd_giat,$kd_output,$kd_komponen,2);
		if($count['hit'] == 1){
			// echo "masukk";
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln_anggaran($count['id'],$param);
			$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran($count['id'],$param);
			// pr($get_realisasi);
			$data['rencanasdbulan'] =  number_format($rencana_sd_bulan['total'],'0',',','.');
			$data['realisasi_blnini'] = number_format($get_data['jumlah'],'0',',','.');
			$data['realisasi_sdbulan'] = number_format($get_realisasi['realisasi'],'0',',','.') ;
			if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
				$data['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$data['persentase_pagu'] = round(($get_realisasi['realisasi'] / $pagu) * 100 ,2);
			}else{
				$data['persentase_rencana'] = number_format(0,'0',',','.');
				$data['persentase_pagu'] = number_format(0,'0',',','.');;
			}
			$sisa_anggaran = $pagu - round($get_realisasi['realisasi']);
			$data['sisa_anggaran'] = number_format($sisa_anggaran,'0',',','.');
			switch ($bulan){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			
		}else{
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = number_format(0,'0',',','.');
			$data['realisasi_sdbulan'] = number_format(0,'0',',','.');
			$data['persentase_rencana'] = number_format(0,'2',',','.');
			$data['persentase_pagu'] = number_format(0,'2',',','.');
			$data['sisa_anggaran'] = number_format($pagu,'0',',','.'); 
			switch ($bl){
				case 01:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 02:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
			}
			
		}
		$newformat = array('rncn_sdbln'=>$data['rencanasdbulan'],'real_blnini'=>$data['realisasi_blnini'],'real_sdbln'=>$data['realisasi_sdbulan'],'pers_rencana'=>$data['persentase_rencana'],'pers_pagu'=>$data['persentase_pagu'],'sisa'=>$data['sisa_anggaran'],'kendala'=>$data['kendala'],'tindaklanjut'=>$data['tindaklanjut'],'ygmembantu'=>$data['ygmembantu']);
		print json_encode($newformat);
		exit;
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
		//add	
		$dinamic_bl = $_GET['bln'];
		if($dinamic_bl){
			$bl = $dinamic_bl;
		}else{
			$bl = date('m');
		}
		
		// $bl = date('m');
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
			if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
				$data['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$data['persentase_pagu'] = round(($get_realisasi['realisasi'] / $thp_kegiatan[0]['pagu_kmpnen']) * 100 ,2);
			}else{
				$data['persentase_rencana'] = 0;
				$data['persentase_pagu'] = 0;
			}
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] - $get_realisasi['realisasi'];
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			switch ($bl){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			
		}else{
			$data['pagu'] =  $thp_kegiatan[0]['pagu_kmpnen'];
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = 0;
			$data['realisasi_sdbulan'] = 0;
			$data['persentase_rencana'] = 0;
			$data['persentase_pagu'] = 0;
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] ;
			/*$data['kendala'] = '';
			$data['tindaklanjut'] = '';
			$data['ygmembantu'] = '';*/
			switch ($bl){
				case 01:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 02:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
			}
			
		}
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		$this->view->assign('usertype',$this->admin['type']);
		// pr($data);
		// pr($info);
		// pr($rinc);
		// pr($list);
		// exit;
		// pr($data);
		//statis
		// $totalbobot = '15';
		// $sisacapaian = $totalbobot - $data['jml_target']; 
		// $this->view->assign('totalbobot',$totalbobot);
		// $this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		// $this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('data',$data);
		
		return $this->loadView('monev/editAnggaran');
	
	}
	
	public function print_monev_anggaran(){
		global $basedomain;
		
		$thn = $_GET['th'];
		$kd_unit = $_GET['kdunitkerja'];
		$kd_giat = $_GET['kdgiat'];
		$kd_output = $_GET['kdoutput'];
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
		$dinamic_bl = $_GET['bulan'];
		if($dinamic_bl){
			$bl = $dinamic_bl;
		}else{
			$bl = date('m');
		}
		
		// $bl = date('m');
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
			if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
				$data['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$data['persentase_pagu'] = round(($get_realisasi['realisasi'] / $thp_kegiatan[0]['pagu_kmpnen']) * 100 ,2);
			}else{
				$data['persentase_rencana'] = 0;
				$data['persentase_pagu'] = 0;
			}
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] - $get_realisasi['realisasi'];
			// $data['kendala'] = $get_data ['kendala'];
			// $data['tindaklanjut'] = $get_data['tindaklanjut'] ;
			// $data['ygmembantu'] = $get_data['ygmembantu'];
			switch ($bl){
				case 01:
					$data['kendala'] = $get_data ['kendala'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
				break;
				case 02:
					$data['kendala'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu_12'];
				break;
			}
			
		}else{
			$data['pagu'] =  $thp_kegiatan[0]['pagu_kmpnen'];
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = 0;
			$data['realisasi_sdbulan'] = 0;
			$data['persentase_rencana'] = 0;
			$data['persentase_pagu'] = 0;
			$data['sisa_anggaran'] = $thp_kegiatan[0]['pagu_kmpnen'] ;
			/*$data['kendala'] = '';
			$data['tindaklanjut'] = '';
			$data['ygmembantu'] = '';*/
			switch ($bl){
				case 01:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 02:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['tindaklanjut'] = '';
					$data['ygmembantu'] = '';
				break;
			}
			
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
		
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		$split = substr($kd_unit,0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		$this->view->assign('usertype',$this->admin['type']);
		// pr($data);
		// pr($info);
		// pr($rinc);
		// pr($list);
		// exit;
		// pr($data);
		//statis
		// $totalbobot = '15';
		// $sisacapaian = $totalbobot - $data['jml_target']; 
		// $this->view->assign('totalbobot',$totalbobot);
		// $this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		// $this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('data',$data);
		
		$this->reportHelper =$this->loadModel('reportHelper');
		$html = $this->loadView('monev/printAnggaran');
		$generate = $this->reportHelper->loadMpdf($html, 'monev-bulanan-anggaran',2);
	}
	
	public function post(){
		// pr($_POST);
		// exit;
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		$keterangan = $_POST['keterangan'];
		
		//str_replace($bad_symbols, ".",$_POST['target_1']);
		// $target = $_POST['target'];
		
		$bad_symbols = array(",", ".");
		$target = str_replace($bad_symbols, ".",$_POST['target']);
		// exit;
		// pr($data);
		$count = $this->m_penetapanAngaran->ceck_id($th,$kd_giat,$kd_output,$kd_komponen,1);
		// pr($count);
		if($count['hit'] == 1){
			// echo "masuk";
			// exit;
			$id = $count['id'];
			$update = $this->m_penetapanAngaran->update_monev($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$target,$keterangan,$id);
		}else{
			
			$insert = $this->m_penetapanAngaran->insert_monev($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen,
															$kendala,$tindaklanjut,$ygmembantu,$target,$keterangan);
		}
		
		exit;
		// return $this->loadView('monev/editTahapan');

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
		// return $this->loadView('monev/editTahapan');

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
		return $this->loadView('monev/editTahapan');

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
		return $this->loadView('monev/editRencanaAnggaran');

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

	//revisi PP39
	public function monev_pp39(){
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
		$thn_temp = $thn_aktif['kode'];
		$thn_renstra =$thn_aktif['data'];
		
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
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			//add 
			$param_trw = '3';
			
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
			$param_trw = '6';
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
			$param_trw = '9';
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
			$param_trw = '12';
		}
		
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		$dataselected[]=$param_trw;
		$this->view->assign('dataselected',$dataselected);
		
		if($_POST['unit'] !=''){
			// pr($_POST['unit']);
			// echo "masukk";
			// exit;
			$kd_unit = $_POST['unit'];
			if($this->admin['type'] == 1){
				$akses = '1';
				$flag  = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
				$flag  = '0';
			}else{
				$akses = '0';
				$flag  = '0';
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
				// pr($output);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
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
			$kd_unit = '841100';
			if($this->admin['type'] == 1){
				$akses = '1';
				$flag  = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
				$flag  = '0';
			}else{
				$akses = '0';
				$flag  = '0';
			}
			$param['kd_unit'] = $kd_unit;
			$param['thn_temp'] = $thn_temp;
			$thn_renstra =$thn_aktif['data'];
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
		$this->view->assign('flag',$flag);
		$download = "download";
		$this->view->assign('download',$download);
		// pr($list);
		//default kode
		
		return $this->loadView('monev_pp39/listmonev');

	}
	
	public function edit_pp39(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		// $kd_komponen = $_GET['kd_komponen'];
		
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
		// $info['kd_komponen'] = $kd_komponen;
		
		
		//change month to triwulan
		$dinamic_tw = $_GET['kdtriwulan'];
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
		
		//validator
		//if($this->admin['type'] == 1){
			$acces = "";
			$sub = "";
			$valid = 1;
		/*}else{
			$acces = "disabled";
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
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev_rev($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				// pr($val);
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				/*
				//TARGET
				//rencana sd triwulan anggaran
				$rencana_sd_tw = $this->m_penetapanAngaran->monev_ren_sd_bulan_anggaran_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['rencana_anggaran_sd_tw'] = $rencana_sd_tw['total'];
				
				// $tmp_rencana_sd_tw = explode('.',$rencana_sd_tw['total']);
				
				if($rencana_sd_tw['total'] != 0 && $rencana_sd_tw['total'] != ''){
					$list[$key]['persentase_rencana_anggaran'] = round(($rencana_sd_tw['total'] / $val['pagu_kmpnen']) * 100 ,2);
					
				}else{
					$list[$key]['persentase_rencana_anggaran'] = 0;
				}
				
				//rencana sd tw target
				$rencana_sd_tw_target = $this->m_penetapanAngaran->monev_ren_sd_bulan_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan,1);
				$list[$key]['rencana_target_sd_tw'] = $rencana_sd_tw_target['total'];
				
				
				//REALISASI
				//realisasi sd bulan ini (triwulan)
 				$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran_rev_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['realisasi_anggaran_sd_tw']= $get_realisasi['realisasi'] ;
				
				if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
					$list[$key]['persentase_realisasi_anggaran'] = round(($get_realisasi['realisasi'] / $val['pagu_kmpnen']) * 100 ,2);
					
				}else{
					$list[$key]['persentase_realisasi_anggaran'] = 0;
				}
				
				$realisasi_sd_tw_target = $this->m_penetapanAngaran->monev_ren_sd_bulan_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan,2);
				$list[$key]['realisasi_target_sd_tw'] = $realisasi_sd_tw_target['total'];
				
				//target PP39
				$target_pp39 =  $this->m_penetapanAngaran->get_target_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['target'] = $target_pp39['target'];
				
				//output fisik PP39
				$output_fisik_pp39 =  $this->m_penetapanAngaran->get_output_fisik_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['output_fisik'] = $output_fisik_pp39['output_fisik'];*/
			
			}
			// pr($list);
			//revisi add
			//get program
			$getProgram = $this->contentHelper->getVisi(false, 9, 1, $parent_id);
			if($getProgram){
				foreach ($getProgram as $value) {
					$idProgram[] =  $value['id'];
				}
			}
			if($idProgram){
				$impld = implode(',', $idProgram);
			}
			
			//new
			//$get_id_kegiatan = $this->m_penetapanAngaran->get_id_kegiatan($thn,$kd_giat);
			$get_id_kegiatan = $this->m_penetapanAngaran->get_id_kegiatan($thn,$kd_giat,$impld);
			//pr($get_id_kegiatan);
			$parent_id_kegiatan = $get_id_kegiatan['id'];
			//pr($parent_id_kegiatan);
			$get_id_output = $this->m_penetapanAngaran->get_id_output($thn,$parent_id_kegiatan,$kd_output);
			//pr($get_id_output);
			$parent_id_output = $get_id_output['id'];
			//pr($parent_id_output);
			//$get_data_ikk = $this->m_penetapanAngaran->get_data_ikk($thn,$parent_id_output,$kd_output);
		    $get_data_ikk = $this->m_penetapanAngaran->get_data_ikk($thn,$parent_id_output);
			//pr($get_data_ikk);
			//exit;
			foreach ($get_data_ikk as $key=>$val){
				// pr($key);
				// pr($val);
				$data_list[] = $val;
				$get_detail_ikk = $this->m_penetapanAngaran->get_detail_ikk($val['id'],$trwulan);
				// pr($get_detail_ikk);
				
				$data_list[$key]['detail'] = $get_detail_ikk;
				
			}
			// pr($data_list);
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
		}
			// pr($list);
		// exit;	
		
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('info',$info);
		$this->view->assign('list2',$list);
		// pr($get_data_ikk);
		$this->view->assign('list',$data_list);
		$this->view->assign('data',$data);
		
		$this->view->assign('sub',$sub);
		$this->view->assign('valid',$valid);
		
		$var_target = 'target';  
		$this->view->assign('target',$var_target);
		
		$var_realisasi_fisik = 'realisasifisik';  
		$this->view->assign('realisasifisik',$var_realisasi_fisik);
		
		return $this->loadView('monev_pp39/editBobotmonev');
	
	}
	
	public function post_monev_pp39(){
		// pr($_POST);
		//echo "field".$_POST['data'][0]['name'];
		//$unserialize = unserialize($_POST['data']);
		//pr($unserialize);
		/*$th = $_POST['th'];
		$kdtriwulan = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kd_komponen = $_POST['kd_komponen'];
		
		$target = $_POST['target'];
		$realisasifisik = $_POST['realisasifisik'];
	
		$param_loop = count($kd_komponen);
		for($i = 0 ; $i<$param_loop ; $i++){
			$param_kode_komponen = $kd_komponen[$i];
			$bad_symbols = array(",", ".");
			$param_target = str_replace($bad_symbols, ".",$target[$i]);
			$param_realisasi_fisik = str_replace($bad_symbols, ".",$realisasifisik[$i]);
			
			//cek id 
			$ceck_id_komponen = $this->m_penetapanAngaran->ceck_id_komponen($th,$kd_giat,$kd_output,$param_kode_komponen,4);
			
			// pr($ceck_id_komponen);
			if($ceck_id_komponen['hit'] == 1){
				//update
				$id_kmpn = $ceck_id_komponen['id'];
				$update_komponen = $this->m_penetapanAngaran->update_monev_output_komponen_pp39($th,$kdtriwulan,$param_target,$param_realisasi_fisik,$id_kmpn);
			}else{
				//insert
				$insert_komponen = $this->m_penetapanAngaran->insert_monev_ouput_komponen_pp39($th,$kdtriwulan,$kdunitkerja,$kd_giat,$kd_output,$param_kode_komponen,$param_target,$param_realisasi_fisik);
			}
			
		}
		exit;*/
		// pr($_POST);
		$th = $_POST['th'];
		$kdtriwulan = $_POST['kdtriwulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		
		// $parent_id = count($_POST['parent_id']);
		$bad_symbols = array(",", ".");
		
			
		for($i = 0 ; $i< count($_POST['parent_id']); $i++){
			//pr($bad_symbols);
			
			$parent_id = $_POST['parent_id'][$i];
			
			$target = $_POST['target'][$i];
			$target_fix = str_replace($bad_symbols, "",$target);
			
			$satuan = $_POST['satuan'][$i];
			
			$pagu_kmpnen = $_POST['pagu_kmpnen'][$i];
			// pr($pagu_kmpnen);
			$pagu_kmpnen_fix = str_replace($bad_symbols, "",$pagu_kmpnen); 
			// pr($pagu_kmpnen_fix);
			
			$rencana_anggaran_sd_tw = $_POST['rencana_anggaran_sd_tw'][$i];
			$rencana_anggaran_sd_tw_fix = str_replace($bad_symbols, "",$rencana_anggaran_sd_tw);
			// pr($rencana_anggaran_sd_tw_fix);
			
			$persentase_rencana_anggaran = $_POST['persentase_rencana_anggaran'][$i];
			$persentase_rencana_anggaran_fix = str_replace($bad_symbols, ".",$persentase_rencana_anggaran);
			// pr($persentase_rencana_anggaran_fix);
			
			$rencana_target_sd_tw = $_POST['rencana_target_sd_tw'][$i];
			$rencana_target_sd_tw_fix = str_replace($bad_symbols, ".",$rencana_target_sd_tw);
			// pr($rencana_target_sd_tw_fix);
			
			$realisasi_anggaran_sd_tw = $_POST['realisasi_anggaran_sd_tw'][$i];
			$realisasi_anggaran_sd_tw_fix = str_replace($bad_symbols, "",$realisasi_anggaran_sd_tw);
			// pr($realisasi_anggaran_sd_tw_fix);
			
			$persentase_realisasi_anggaran = $_POST['persentase_realisasi_anggaran'][$i];
			$persentase_realisasi_anggaran_fix = str_replace($bad_symbols, ".",$persentase_realisasi_anggaran);
			// pr($persentase_realisasi_anggaran_fix);
			
			$realisasi_target_sd_tw = $_POST['realisasi_target_sd_tw'][$i];
			$realisasi_target_sd_tw_fix = str_replace($bad_symbols, ".",$realisasi_target_sd_tw);
			// pr($realisasi_target_sd_tw_fix);
			
			$outikk = $_POST['outikk'][$i];
			$outikk_fix = str_replace($bad_symbols, "",$outikk);
			// pr($outikk_fix);
			// exit;
			//cek id 
			$ceck_id_komponen = $this->m_penetapanAngaran->ceck_id_komponen($th,$kd_giat,$kd_output,$parent_id,4);
			// pr($ceck_id_komponen);
			// exit;
			// pr($ceck_id_komponen);
			if($ceck_id_komponen['hit'] == 1){
				//update
				$id_kmpn = $ceck_id_komponen['id'];
				$update_komponen = $this->m_penetapanAngaran->update_monev_output_komponen_pp39($id_kmpn,$th,$kdtriwulan,$target_fix,$satuan,$pagu_kmpnen_fix,$rencana_anggaran_sd_tw_fix,$persentase_rencana_anggaran_fix,
																								$rencana_target_sd_tw_fix,$realisasi_anggaran_sd_tw_fix,$persentase_realisasi_anggaran_fix,$realisasi_target_sd_tw_fix,$outikk_fix);
			}else{
				//insert
				$insert_komponen = $this->m_penetapanAngaran->insert_monev_ouput_komponen_pp39($th,$kdtriwulan,$kdunitkerja,$kd_giat,$kd_output,$parent_id,$target_fix,$satuan,$pagu_kmpnen_fix,$rencana_anggaran_sd_tw_fix,$persentase_rencana_anggaran_fix,
																								$rencana_target_sd_tw_fix,$realisasi_anggaran_sd_tw_fix,$persentase_realisasi_anggaran_fix,$realisasi_target_sd_tw_fix,$outikk_fix);
			}
			
		}
		
	}	
	
	public function print_pp39(){
		// pr($_GET);
		$trwulan = $_GET['kdtriwulan']; 
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
		
		$kd_unit = $_GET['kdunitkerja'];
		$kd_giat = $_GET['kdgiat'];
		$kd_output = $_GET['kdoutput'];
		$thn = $_GET['th'];
		$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan_condotion_monev_rev($thn,$kd_giat,$kd_output);
		// pr($thp_kegiatan);
		foreach ($thp_kegiatan as $key=>$val){
				// pr($val);
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				$tot_pagu += $val['pagu_kmpnen'];
				//TARGET
				//rencana sd triwulan anggaran
				$rencana_sd_tw = $this->m_penetapanAngaran->monev_ren_sd_bulan_anggaran_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['rencana_anggaran_sd_tw'] = $rencana_sd_tw['total'];
				
				$tot_rencana_sd_tw += $rencana_sd_tw['total'];
				
				// $tmp_rencana_sd_tw = explode('.',$rencana_sd_tw['total']);
				
				if($rencana_sd_tw['total'] != 0 && $rencana_sd_tw['total'] != ''){
					$list[$key]['persentase_rencana_anggaran'] = round(($rencana_sd_tw['total'] / $val['pagu_kmpnen']) * 100 ,2);
					$tmp_persentase_renc = round(($rencana_sd_tw['total'] / $val['pagu_kmpnen']) * 100 ,2);
					
				}else{
					$list[$key]['persentase_rencana_anggaran'] = 0;
					$tmp_persentase_renc = 0;
				}
				
				$tot_persentase_rencana_anggaran += $tmp_persentase_renc;
				
				//rencana sd tw target
				$rencana_sd_tw_target = $this->m_penetapanAngaran->monev_ren_sd_bulan_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan,1);
				$list[$key]['rencana_target_sd_tw'] = $rencana_sd_tw_target['total'];
				
				$tot_rencana_sd_tw_target += $rencana_sd_tw_target['total'];
				
				//REALISASI
				//realisasi sd bulan ini (triwulan)
 				$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran_rev_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan);
				$list[$key]['realisasi_anggaran_sd_tw']= $get_realisasi['realisasi'] ;
				$tot_get_realisasi += $get_realisasi['realisasi'] ;
				
				if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
					$list[$key]['persentase_realisasi_anggaran'] = round(($get_realisasi['realisasi'] / $val['pagu_kmpnen']) * 100 ,2);
					$tmp_persentase_real += round(($get_realisasi['realisasi'] / $val['pagu_kmpnen']) * 100 ,2);
				}else{
					$list[$key]['persentase_realisasi_anggaran'] = 0;
					$tmp_persentase_real += 0;
				}
				
				$tot_persentase_reals_anggaran += $tmp_persentase_real;
				
				$realisasi_sd_tw_target = $this->m_penetapanAngaran->monev_ren_sd_bulan_pp39($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$trwulan,2);
				$list[$key]['realisasi_target_sd_tw'] = $realisasi_sd_tw_target['total'];
				$tot_realisasi_sd_tw_target +=$realisasi_sd_tw_target['total'];
				
			}
			$getProgram = $this->contentHelper->getVisi(false, 9, 1, $parent_id);
			if($getProgram){
				foreach ($getProgram as $value) {
					$idProgram[] =  $value['id'];
				}
			}
			if($idProgram){
				$impld = implode(',', $idProgram);
			}

			//$get_id_kegiatan = $this->m_penetapanAngaran->get_id_kegiatan($thn,$kd_giat);
			$get_id_kegiatan = $this->m_penetapanAngaran->get_id_kegiatan($thn,$kd_giat,$impld);
			// pr($get_id_kegiatan);
			$parent_id_kegiatan = $get_id_kegiatan['id'];
			$get_all_output = $this->m_penetapanAngaran->get_all_output($thn,$parent_id_kegiatan,$kd_output);
			
			// pr($get_all_output);
			foreach ($get_all_output as $k => $val) {
						if ($val['data']){
							$target = unserialize($val['data']);
							$getData[$k]['target'] = $target['target'];
							$getData[$k]['satuan_target'] = $target['satuan_target'];

							$getData[$k]['target_anggaran'] = $target['target_anggaran'];
							$getData[$k]['satuan_target_anggaran'] = $target['satuan_target_anggaran'];
						} 
			}
			// pr($getData);
			//start tahun renstra = 2015 - 2019
			$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
			$thn_temp = $thn_aktif['kode'];
			$thn_renstra =$thn_aktif['data'];
			$limit =explode('-',$thn_renstra);
			$limit_min_tahun = $limit[0];
			$limit_max_tahun = $limit[1];
			if($thn_temp <= $limit_max_tahun){
				if($limit_min_tahun <= $thn_temp){
					$indx  = $thn_temp - $limit_min_tahun;
				}
			}

			//data header
			//Deskripsi Kegiatan
			//nama output
			$nama_output = $this->m_penetapanAngaran->nama_output($kd_giat,$kd_output);
			$pagu_output = $this->m_penetapanAngaran->output_cndtn($thn,$kd_giat,$kd_output);
			//nama kegiatan
			$nama_kegiatan = $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
			//unit eselon 
			$unit_eselon = $this->m_penetapanAngaran->nama_unit($kd_unit);
			
			$target = $getData['0']['target'][$indx];
			$target_satuan = $getData['0']['satuan_target'];
			$info['nama_output'] = $get_all_output['0']['desc'];
			$info['nama_kegiatan'] = $nama_kegiatan['nmgiat'];
			$info['pagu_output'] = $pagu_output['pagu_output'];
			$info['unit_eselon'] = $unit_eselon['nmunit'];
			
			$info['trwulan'] = $ket;
			$info['thn'] = $thn_temp;
			$info['kd_giat'] = $kd_giat;
			$info['kd_output'] = $kd_output;
			
			$info['target'] = $target;
			$info['target_satuan'] = $target_satuan;
			$info['tot_pagu'] = $tot_pagu;
			$info['tot_rencana_sd_tw'] = $tot_rencana_sd_tw;
			$info['tot_persentase_rencana_anggaran'] = $tot_persentase_rencana_anggaran;
			$info['tot_rencana_sd_tw_target'] = $tot_rencana_sd_tw_target;
			$info['tot_get_realisasi'] = $tot_get_realisasi;
			$info['tot_persentase_reals_anggaran'] = $tot_persentase_reals_anggaran;
			$info['tot_realisasi_sd_tw_target'] = $tot_realisasi_sd_tw_target;
			$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,3);

		if($count['hit'] == 1){
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
		}
		
			
		//data child
			$parent_id_output = $get_all_output['0']['id'];
			$get_data_ikk = $this->m_penetapanAngaran->get_data_ikk($thn,$parent_id_output,$kd_output);
			foreach ($get_data_ikk as $key=>$val){
				$data_list[] = $val;
				$get_detail_ikk = $this->m_penetapanAngaran->get_detail_ikk($val['id'],$trwulan);
				$data_list[$key]['detail'] = $get_detail_ikk;
				
			}
			$this->view->assign('info',$info);
			$this->view->assign('list',$data_list);
			$this->view->assign('kendala',$data['kendala']);
			$this->view->assign('tdklanjut',$data['tindaklanjut']);
			
			//new add		
			/*$tgl = date("Y-m-d");
			$tgl_format = $this->DateToIndo($tgl);
			$this->view->assign('tgl_format',$tgl_format);*/
			$tglcetak = $_GET['tglcetak'];
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
			$this->view->assign('tgl_format',$tgl_format);

			//ttd nama
			if($kd_unit === '845100'){
				$join = '841000';
			}else{
				$split = substr($kd_unit,0,3);
				$join = $split.'000';
			}
			$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
			$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
			
			//eselon I
			$kd_eselon_I = $join;
			$nama_pejabat_eselon_I = $this->model->nama_pejabat($kd_eselon_I);
			// pr($nama_pejabat_eselon_I);
			//$pejabat_eselon_I = unserialize($nama_pejabat_eselon_I['custom_text']);
			//$this->view->assign('nama_pejabat_eselon_I',$pejabat_eselon_I['pejabat']);
			$this->view->assign('jabatan_pejabat_eselon_I',$nama_pejabat_eselon_I['brief']);
			$this->view->assign('nama_pejabat_eselon_I',$nama_pejabat_eselon_I['desc']);
		
			$kd_eselon_II = $kd_unit;
			$nama_pejabat_eselon_II = $this->model->nama_pejabat($kd_eselon_II);
			// pr($nama_pejabat_eselon_I);
			//$pejabat_eselon_II = unserialize($nama_pejabat_eselon_II['custom_text']);
			//$this->view->assign('nama_pejabat_eselon_II',$pejabat_eselon_II['pejabat']);
			$this->view->assign('jabatan_pejabat_eselon_II',$nama_pejabat_eselon_II['brief']);
			$this->view->assign('nama_pejabat_eselon_II',$nama_pejabat_eselon_II['desc']);
		
			// return $this->loadView('monev/printAll');
			$this->reportHelper =$this->loadModel('reportHelper');
			$html = $this->loadView('monev_pp39/printAll');
			// echo $html;
			// exit;
			$generate = $this->reportHelper->loadMpdf($html, 'monev-pp39',1);
		
	}
		
}

?>
