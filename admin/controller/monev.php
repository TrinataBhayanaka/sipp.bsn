<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class monev extends Controller {
	
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
		
		// $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		$result = " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
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

	//revisi
	public function monev_bulan(){
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
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
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
				// pr($output);
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
					/*$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn_temp,$val['kdgiat'],$out['KDOUTPUT']);
					foreach ($thp_kegiatan as $kav=>$values){
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
		
		return $this->loadView('monev/listmonev');

	}
	
	public function editBobot_bulan(){
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
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				$totbobot = $this->m_penetapanAngaran->bobot_komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN']);
				$list[$key]['totalbobot'] = $totbobot['bobot'];
				
				//rencana sd bulan
				$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param,1);
				$list[$key]['rencana_sd_bulan'] = $rencana_sd_bulan['total'];
				
				//realisasi bulan ini
				$realisasi_bulan_ini = $this->m_penetapanAngaran->monev_ren_bulan_ini($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['realisasi_bulan_ini'] = $realisasi_bulan_ini['total'];
				
				$realisasi_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param,2);
				$list[$key]['realisasi_sd_bulan'] = $realisasi_sd_bulan['total'];
				
				//keterangan
				$get_keterangan = $this->m_penetapanAngaran->get_keterangan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['keterangan'] = $get_keterangan['keterangan'];
				
				//sisa capaian bobot kinerja
				$sisacapaian = $totbobot['bobot'] - $realisasi_sd_bulan['total']; 
				$list[$key]['sisacapaian'] = $sisacapaian;
				
				// exit;
			}
		
		// pr($list);
		//add	
		
		// pr($rencana_sd_bulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,1);
		// pr($count);
		// pr($param);
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
			
		}
		// pr($data);
		// pr($list);
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
		
		$this->view->assign('usertype',$this->admin['type']);
		
		// $this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		
		$var_total = 'total';  
		$this->view->assign('total',$var_total);
		
		$var_target = 'target';  
		$this->view->assign('target',$var_target);
		
		$var_targetsd = 'tagetsd';  
		$this->view->assign('tagetsd',$var_targetsd);
		
		return $this->loadView('monev/editBobotmonev');
	}
	
	public function print_monev_all(){
		global $basedomain;
		$thn = $_GET['th'];
		$kd_unit = $_GET['kdunitkerja'];
		$kd_giat = $_GET['kdgiat'];
		$kd_output = $_GET['kdoutput'];
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
		// $info['kd_komponen'] = $kd_komponen;
		
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
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				$totbobot = $this->m_penetapanAngaran->bobot_komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN']);
				$list[$key]['totalbobot'] = $totbobot['bobot'];
				
				//rencana sd bulan
				$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param,1);
				$list[$key]['rencana_sd_bulan'] = $rencana_sd_bulan['total'];
				
				//realisasi bulan ini
				$realisasi_bulan_ini = $this->m_penetapanAngaran->monev_ren_bulan_ini($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['realisasi_bulan_ini'] = $realisasi_bulan_ini['total'];
				
				$realisasi_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param,2);
				$list[$key]['realisasi_sd_bulan'] = $realisasi_sd_bulan['total'];
				
				//keterangan
				$get_keterangan = $this->m_penetapanAngaran->get_keterangan($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['keterangan'] = $get_keterangan['keterangan'];
				
				//sisa capaian bobot kinerja
				$sisacapaian = $totbobot['bobot'] - $realisasi_sd_bulan['total']; 
				$list[$key]['sisacapaian'] = $sisacapaian;
				
				$jml_tot_bobot 		+= $totbobot['bobot'];
				$jml_renc_sd_bln 	+= $rencana_sd_bulan['total'];
				$jml_reals_bln 		+= $realisasi_bulan_ini['total'];
				$jml_reals_sd_bln 	+= $realisasi_sd_bulan['total'];
				$jml_sisa_cpn 		+= $sisacapaian;
				
				
				// exit;
			}
		
		// pr($list);
		//add	
		
		// pr($rencana_sd_bulan);
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,1);
		// pr($count);
		// pr($param);
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
			
		}
		
		//kendala
		$exp_kendala = explode('-',$data['kendala']);
		$kendala_fix = array_filter($exp_kendala);
		// $this->view->assign('kendala',$kendala_fix);
		$this->view->assign('kendala',$data['kendala']);
		
		//tindak lanjut
		$exp_tdklanjut = explode('-',$data['tindaklanjut']);
		$tdklanjut_fix = array_filter($exp_tdklanjut);
		// $this->view->assign('tdklanjut',$tdklanjut_fix);
		$this->view->assign('tdklanjut',$data['tindaklanjut']);
		
		//yang membantu
		$exp_yg_membantu = explode('-',$data['ygmembantu']);
		$yg_membantu_fix = array_filter($exp_yg_membantu);
		// $this->view->assign('ygmembantu',$yg_membantu_fix);
		$this->view->assign('ygmembantu',$data['ygmembantu']);
		
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		
		
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		
		$this->view->assign('jml_tot_bobot',$jml_tot_bobot);
		$this->view->assign('jml_renc_sd_bln',$jml_renc_sd_bln);
		$this->view->assign('jml_reals_bln',$jml_reals_bln);
		$this->view->assign('jml_reals_sd_bln',$jml_reals_sd_bln);
		$this->view->assign('sisacapaian',$sisacapaian);
		
		//Anggaran
		//$thp kegiatan
		$thp_kegiatan2 = $this->m_penetapanAngaran->thp_kegiatan($thn,$kd_giat,$kd_output);
		foreach ($thp_kegiatan2 as $key=>$val2){
			// pr($val2);
			$list2[] = $val2;
			$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val2['KDKMPNEN'],$val2['KDSOUTPUT']);
			// pr($komponen);
			$list2[$key]['nama_komponen'] = $komponen['URKMPNEN'];
			
			//rencana sd bulan
			$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan_anggaran($thn,$kd_giat,$kd_output,$val2['KDKMPNEN'],$param);
			$list2[$key]['rencanasdbulan'] = $rencana_sd_bulan['total'];
			
			//realisasi bulan ini
			$get_data_bln = $this->m_penetapanAngaran->get_data_monev_bln_anggaran_rev($thn,$kd_giat,$kd_output,$val2['KDKMPNEN'],$param);
			$list2[$key]['realisasi_blnini'] = $get_data_bln['jumlah'] ;
			// exit;
			//realisasi sd bulan ini
			$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran_rev($thn,$kd_giat,$kd_output,$val2['KDKMPNEN'],$param);
			$list2[$key]['realisasi_sdbulan']= $get_realisasi['realisasi'] ;
			
			if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
				$list2[$key]['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$list2[$key]['persentase_pagu'] = round(($get_realisasi['realisasi'] / $val2['pagu_kmpnen']) * 100 ,2);
				
				$temp_persentase_rencana = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$temp_persentase_pagu = round(($get_realisasi['realisasi'] / $val2['pagu_kmpnen']) * 100 ,2);
				
			}else{
				$list2[$key]['persentase_rencana'] = 0;
				$list2[$key]['persentase_pagu'] = 0;
				$temp_persentase_rencana = 0;
				$temp_persentase_pagu = 0;
			}
			$list2[$key]['sisa_anggaran'] = $val2['pagu_kmpnen'] - $get_realisasi['realisasi'];
			$temp_sisa = $val2['pagu_kmpnen'] - $get_realisasi['realisasi'];
			
			$jml_tot_pagu				+= $val2['pagu_kmpnen'];
			$jml_renc_sd_bln_anggaran 	+= $rencana_sd_bulan['total'];
			$jml_reals_bln_anggaran 	+= $get_data_bln['jumlah'];
			$jml_reals_sd_bln_anggaran 	+= $get_realisasi['realisasi'];
			$sisa_anggaran          	+= $temp_sisa;
			$jml_persentase_rencana 	+= $temp_persentase_rencana;
			$jml_persentase_pagu	    += $temp_persentase_pagu;
			
		}
		
		$count2 = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,2);
		if($count2['hit'] == 1){
			$get_data2 = $this->m_penetapanAngaran->get_data_monev_bln_anggaran($count2['id'],$param);
			
			switch ($bl){
				case 01:
					$data2['kendala'] = $get_data2 ['kendala'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu'];
				break;
				case 02:
					$data2['kendala'] = $get_data2 ['kendala_2'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_2'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_2'];
				break;
				case 03:
					$data2['kendala'] = $get_data2 ['kendala_3'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_3'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_3'];
				break;
				case 04:
					$data2['kendala'] = $get_data2 ['kendala_4'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_4'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_4'];
				break;
				case 05:
					$data2['kendala'] = $get_data2 ['kendala_5'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_5'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_5'];
				break;
				case 06:
					$data2['kendala'] = $get_data2 ['kendala_6'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_6'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_6'];
				break;
				case 07:
					$data2['kendala'] = $get_data2 ['kendala_7'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_7'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_7'];
				break;
				case 08:
					$data2['kendala'] = $get_data2 ['kendala_8'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_8'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_8'];
				break;
				case 09:
					$data2['kendala'] = $get_data2 ['kendala_9'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_9'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_9'];
				break;
				case 10:
					$data2['kendala'] = $get_data2 ['kendala_10'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_10'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_10'];
				break;
				case 11:
					$data2['kendala'] = $get_data2 ['kendala_11'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_11'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_11'];
				break;
				case 12:
					$data2['kendala'] = $get_data2 ['kendala_12'];
					$data2['tindaklanjut'] = $get_data2['tindaklanjut_12'] ;
					$data2['ygmembantu'] = $get_data2['ygmembantu_12'];
				break;
			}
			
		}else{
			switch ($bl){
				case 01:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 02:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 03:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 04:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 05:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 06:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 07:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 08:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 09:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 10:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 11:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
				case 12:
					$data2['kendala'] = '';
					$data2['tindaklanjut'] = '';
					$data2['ygmembantu'] = '';
				break;
			}
			
		}
		$this->view->assign('list2',$list2);
		// $this->view->assign('data2',$data2);
		$this->view->assign('kendala2',$data2['kendala']);
		$this->view->assign('tdklanjut2',$data2['tindaklanjut']);
		$this->view->assign('ygmembantu2',$data2['ygmembantu']);
		
		
		$this->view->assign('jml_tot_pagu',$jml_tot_pagu);
		$this->view->assign('jml_renc_sd_bln_anggaran',$jml_renc_sd_bln_anggaran);
		$this->view->assign('jml_reals_bln_anggaran',$jml_reals_bln_anggaran);
		$this->view->assign('jml_reals_sd_bln_anggaran',$jml_reals_sd_bln_anggaran);
		$this->view->assign('sisa_anggaran',$sisa_anggaran);
		$this->view->assign('jml_persentase_rencana',$jml_persentase_rencana);
		$this->view->assign('jml_persentase_pagu',$jml_persentase_pagu);
		
		//new add		
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		$this->view->assign('tgl_format',$tgl_format);
		
		//ttd nama
		$split = substr($kd_unit,0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		
		// return $this->loadView('monev/printAll');
		$this->reportHelper =$this->loadModel('reportHelper');
		$html = $this->loadView('monev/printAll');
		$generate = $this->reportHelper->loadMpdf($html, 'monev-bulanan-bobot',2);
		// exit;
	}
	
	public function post_monev(){
		// pr($_POST);
		//echo "field".$_POST['data'][0]['name'];
		//$unserialize = unserialize($_POST['data']);
		//pr($unserialize);
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		
		//cek id u/ Kendala yang dihadapi,Tindak Lanjut yang diperlukan,Pihak yang dapat mengatasi masalah
		$ceck_id_output = $this->m_penetapanAngaran->ceck_id_output($th,$kd_giat,$kd_output,1);
		// pr($ceck_id_output);
		if($ceck_id_output['hit'] == 1){
			//update
			$id = $ceck_id_output['id'];
			// echo "update";
			$update = $this->m_penetapanAngaran->update_monev_output($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$id);
		
		}else{
			//insert
			// echo "insert";
			$insert = $this->m_penetapanAngaran->insert_monev_ouput($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,
														$kendala,$tindaklanjut,$ygmembantu,1);
		}
		// exit;
		//array 
		$kd_komponen = $_POST['kd_komponen'];
		$target = $_POST['target'];
		$keterangan = $_POST['keterangan'];
		
		$param_loop = count($kd_komponen);
		for($i = 0 ; $i<$param_loop ; $i++){
			$param_kode_komponen = $kd_komponen[$i];
			$bad_symbols = array(",", ".");
			$param_target = str_replace($bad_symbols, ".",$target[$i]);
			$param_keterangan = $keterangan[$i];
			
			//cek id u/target dan keterangan berdasarkan kode komponen
			$ceck_id_komponen = $this->m_penetapanAngaran->ceck_id_komponen($th,$kd_giat,$kd_output,$param_kode_komponen);
			// pr($ceck_id_komponen);
			if($ceck_id_komponen['hit'] == 1){
				//update
				$id_kmpn = $ceck_id_komponen['id'];
				$update_komponen = $this->m_penetapanAngaran->update_monev_output_komponen($th,$bulan,$param_target,$param_keterangan,$id_kmpn);
			}else{
				//insert
				$insert_komponen = $this->m_penetapanAngaran->insert_monev_ouput_komponen($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,$param_kode_komponen,$param_target,$param_keterangan);
			}
			
			//echo "kode =".$param_kode_komponen;
			// exit;
		}
		exit;
	}	

	public function editAnggaran_bulan(){
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
				
				//rencana sd bulan
				$rencana_sd_bulan = $this->m_penetapanAngaran->monev_ren_sd_bulan_anggaran($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['rencanasdbulan'] = $rencana_sd_bulan['total'];
				
				//realisasi bulan ini
				$get_data_bln = $this->m_penetapanAngaran->get_data_monev_bln_anggaran_rev($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['realisasi_blnini'] = $get_data_bln['jumlah'] ;
				// exit;
				//realisasi sd bulan ini
				$get_realisasi = $this->m_penetapanAngaran->monev_realisasi_sd_bulan_anggaran_rev($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$param);
				$list[$key]['realisasi_sdbulan']= $get_realisasi['realisasi'] ;
				
				if($get_realisasi['realisasi'] != 0 && $get_realisasi['realisasi'] != ''){
					$list[$key]['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
					$list[$key]['persentase_pagu'] = round(($get_realisasi['realisasi'] / $val['pagu_kmpnen']) * 100 ,2);
				}else{
					$list[$key]['persentase_rencana'] = 0;
					$list[$key]['persentase_pagu'] = 0;
				}
				$list[$key]['sisa_anggaran'] = $val['pagu_kmpnen'] - $get_realisasi['realisasi'];
			}
			// pr($list);
		// exit;	
			
		
		//cek id
		$count = $this->m_penetapanAngaran->ceck_id_output($thn,$kd_giat,$kd_output,2);
		if($count['hit'] == 1){
			$get_data = $this->m_penetapanAngaran->get_data_monev_bln_anggaran($count['id'],$param);
			
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
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		$this->view->assign('data',$data);
		
		$var_pagu = 'pagu';  
		$this->view->assign('pagu',$var_pagu);
		
		$var_realisasi_blnini = 'realisasiblnini';  
		$this->view->assign('realisasiblnini',$var_realisasi_blnini);
		
		$var_realisasi_sdbulan = 'realisasisdbulan';  
		$this->view->assign('realisasisdbulan',$var_realisasi_sdbulan);
		
		return $this->loadView('monev/editAnggaranmonev');
	
	}	

	public function post_anggaran_monev(){
		// pr($_POST);
		
		$th = $_POST['th'];
		$bulan = $_POST['bulan'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kd_giat = $_POST['kdgiat'];
		$kd_output = $_POST['kdoutput'];
		
		$kendala = $_POST['kendala'];
		$tindaklanjut = $_POST['tindaklanjut'];
		$ygmembantu = $_POST['ygmembantu'];
		
		// pr($_POST);
		// exit;
		//cek id u/ Kendala yang dihadapi,Tindak Lanjut yang diperlukan,Pihak yang dapat mengatasi masalah
		$ceck_id_output = $this->m_penetapanAngaran->ceck_id_output($th,$kd_giat,$kd_output,2);
		// pr($ceck_id_output);
		if($ceck_id_output['hit'] == 1){
			//update
			$id = $ceck_id_output['id'];
			// echo "update";
			$update = $this->m_penetapanAngaran->update_monev_output($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$id);
		
		}else{
			//insert
			// echo "insert";
			$insert = $this->m_penetapanAngaran->insert_monev_ouput($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,
														$kendala,$tindaklanjut,$ygmembantu,2);
		}
		
		// $bad_symbols = array(",", ".");
		// $realisasi = str_replace($bad_symbols, "",$_POST['realisasi']);
		
		$kd_komponen = $_POST['kd_komponen'];
		$realisasi = $_POST['realisasi'];
		
		$param_loop = count($kd_komponen);
		
		for($i = 0 ; $i<$param_loop ; $i++){
			$param_kode_komponen = $kd_komponen[$i];
			$bad_symbols = array(",", ".");
			$param_realisasi = str_replace($bad_symbols, "",$realisasi[$i]);
			
			//cek id u/realisasi dan keterangan berdasarkan kode komponen
			$ceck_id_komponen = $this->m_penetapanAngaran->ceck_id_komponen($th,$kd_giat,$kd_output,$param_kode_komponen,2);
			// pr($ceck_id_komponen);
			if($ceck_id_komponen['hit'] == 1){
				//update
				$id_kmpn = $ceck_id_komponen['id'];
				$update_komponen = $this->m_penetapanAngaran->update_monev_anggaran_komponen($th,$bulan,$param_realisasi,$id_kmpn);
			}else{
				//insert
				$insert_komponen = $this->m_penetapanAngaran->insert_monev_anggaran_komponen($th,$bulan,$kdunitkerja,$kd_giat,$kd_output,$param_kode_komponen,$param_realisasi);
			}
		}
		
		exit;
	}
	
}

?>
