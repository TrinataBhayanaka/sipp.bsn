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
		$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// pr($thn_aktif);
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		// $thn_temp = '2013';
		
		if($_POST['unit'] !=''){
			// pr($_POST['unit']);
			// echo "masukk";
			// exit;
			$kd_unit = $_POST['unit'];
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
			$kd_unit = '841100';
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
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['kendala_8'] = $get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['tindaklanjut_8'] = $get_data['tindaklanjut_8'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
					$data['ygmembantu_8'] = $get_data['ygmembantu_8'];
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['kendala_8'] = $get_data ['kendala_8'];
					$data['kendala_9'] = $get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['tindaklanjut_8'] = $get_data['tindaklanjut_8'] ;
					$data['tindaklanjut_9'] = $get_data['tindaklanjut_9'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
					$data['ygmembantu_8'] = $get_data['ygmembantu_8'];
					$data['ygmembantu_9'] = $get_data['ygmembantu_9'];
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['kendala_8'] = $get_data ['kendala_8'];
					$data['kendala_9'] = $get_data ['kendala_9'];
					$data['kendala_10'] = $get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['tindaklanjut_8'] = $get_data['tindaklanjut_8'] ;
					$data['tindaklanjut_9'] = $get_data['tindaklanjut_9'] ;
					$data['tindaklanjut_10'] = $get_data['tindaklanjut_10'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
					$data['ygmembantu_8'] = $get_data['ygmembantu_8'];
					$data['ygmembantu_9'] = $get_data['ygmembantu_9'];
					$data['ygmembantu_10'] = $get_data['ygmembantu_10'];
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['kendala_8'] = $get_data ['kendala_8'];
					$data['kendala_9'] = $get_data ['kendala_9'];
					$data['kendala_10'] = $get_data ['kendala_10'];
					$data['kendala_11'] = $get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['tindaklanjut_8'] = $get_data['tindaklanjut_8'] ;
					$data['tindaklanjut_9'] = $get_data['tindaklanjut_9'] ;
					$data['tindaklanjut_10'] = $get_data['tindaklanjut_10'] ;
					$data['tindaklanjut_11'] = $get_data['tindaklanjut_11'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
					$data['ygmembantu_8'] = $get_data['ygmembantu_8'];
					$data['ygmembantu_9'] = $get_data['ygmembantu_9'];
					$data['ygmembantu_10'] = $get_data['ygmembantu_10'];
					$data['ygmembantu_11'] = $get_data['ygmembantu_11'];
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala'];
					$data['kendala_2'] = $get_data ['kendala_2'];
					$data['kendala_3'] = $get_data ['kendala_3'];
					$data['kendala_4'] = $get_data ['kendala_4'];
					$data['kendala_5'] = $get_data ['kendala_5'];
					$data['kendala_6'] = $get_data ['kendala_6'];
					$data['kendala_7'] = $get_data ['kendala_7'];
					$data['kendala_8'] = $get_data ['kendala_8'];
					$data['kendala_9'] = $get_data ['kendala_9'];
					$data['kendala_10'] = $get_data ['kendala_10'];
					$data['kendala_11'] = $get_data ['kendala_11'];
					$data['kendala_12'] = $get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ;
					$data['tindaklanjut_2'] = $get_data['tindaklanjut_2'] ;
					$data['tindaklanjut_3'] = $get_data['tindaklanjut_3'] ;
					$data['tindaklanjut_4'] = $get_data['tindaklanjut_4'] ;
					$data['tindaklanjut_5'] = $get_data['tindaklanjut_5'] ;
					$data['tindaklanjut_6'] = $get_data['tindaklanjut_6'] ;
					$data['tindaklanjut_7'] = $get_data['tindaklanjut_7'] ;
					$data['tindaklanjut_8'] = $get_data['tindaklanjut_8'] ;
					$data['tindaklanjut_9'] = $get_data['tindaklanjut_9'] ;
					$data['tindaklanjut_10'] = $get_data['tindaklanjut_10'] ;
					$data['tindaklanjut_11'] = $get_data['tindaklanjut_11'] ;
					$data['tindaklanjut_12'] = $get_data['tindaklanjut_12'] ;
					$data['ygmembantu'] = $get_data['ygmembantu'];
					$data['ygmembantu_2'] = $get_data['ygmembantu_2'];
					$data['ygmembantu_3'] = $get_data['ygmembantu_3'];
					$data['ygmembantu_4'] = $get_data['ygmembantu_4'];
					$data['ygmembantu_5'] = $get_data['ygmembantu_5'];
					$data['ygmembantu_6'] = $get_data['ygmembantu_6'];
					$data['ygmembantu_7'] = $get_data['ygmembantu_7'];
					$data['ygmembantu_8'] = $get_data['ygmembantu_8'];
					$data['ygmembantu_9'] = $get_data['ygmembantu_9'];
					$data['ygmembantu_10'] = $get_data['ygmembantu_10'];
					$data['ygmembantu_11'] = $get_data['ygmembantu_11'];
					$data['ygmembantu_12'] = $get_data['ygmembantu_12'];
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
					$data['kendala_2'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '' ;
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
				break;
				case 03:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '' ;
					$data['tindaklanjut_3'] = '' ;
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
				break;
				case 04:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['tindaklanjut'] = '' ;
					$data['tindaklanjut_2'] = '' ;
					$data['tindaklanjut_3'] = '' ;
					$data['tindaklanjut_4'] = '' ;
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
				break;
				case 05:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
				break;
				case 06:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
				break;
				case 07:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
				break;
				case 08:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['kendala_8'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['tindaklanjut_8'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = ''; 
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
					$data['ygmembantu_8'] = '';
				break;
				case 09:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['kendala_8'] = '';
					$data['kendala_9'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['tindaklanjut_8'] = '';
					$data['tindaklanjut_9'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
					$data['ygmembantu_8'] = '';
					$data['ygmembantu_9'] = '';
				break;
				case 10:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['kendala_8'] = '';
					$data['kendala_9'] = '';
					$data['kendala_10'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['tindaklanjut_8'] = '';
					$data['tindaklanjut_9'] = '';
					$data['tindaklanjut_10'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
					$data['ygmembantu_8'] = '';
					$data['ygmembantu_9'] = '';
					$data['ygmembantu_10'] = '';
				break;
				case 11:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['kendala_8'] = '';
					$data['kendala_9'] = '';
					$data['kendala_10'] = '';
					$data['kendala_11'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['tindaklanjut_8'] = '';
					$data['tindaklanjut_9'] = '';
					$data['tindaklanjut_10'] = '';
					$data['tindaklanjut_11'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
					$data['ygmembantu_8'] = '';
					$data['ygmembantu_9'] = '';
					$data['ygmembantu_10'] = '';
					$data['ygmembantu_11'] = '';
				break;
				case 12:
					$data['kendala'] = '';
					$data['kendala_2'] = '';
					$data['kendala_3'] = '';
					$data['kendala_4'] = '';
					$data['kendala_5'] = '';
					$data['kendala_6'] = '';
					$data['kendala_7'] = '';
					$data['kendala_8'] = '';
					$data['kendala_9'] = '';
					$data['kendala_10'] = '';
					$data['kendala_11'] = '';
					$data['kendala_12'] = '';
					$data['tindaklanjut'] = '';
					$data['tindaklanjut_2'] = '';
					$data['tindaklanjut_3'] = '';
					$data['tindaklanjut_4'] = '';
					$data['tindaklanjut_5'] = '';
					$data['tindaklanjut_6'] = '';
					$data['tindaklanjut_7'] = '';
					$data['tindaklanjut_8'] = '';
					$data['tindaklanjut_9'] = '';
					$data['tindaklanjut_10'] = '';
					$data['tindaklanjut_11'] = '';
					$data['tindaklanjut_12'] = '';
					$data['ygmembantu'] = '';
					$data['ygmembantu_2'] = '';
					$data['ygmembantu_3'] = '';
					$data['ygmembantu_4'] = '';
					$data['ygmembantu_5'] = '';
					$data['ygmembantu_6'] = '';
					$data['ygmembantu_7'] = '';
					$data['ygmembantu_8'] = '';
					$data['ygmembantu_9'] = '';
					$data['ygmembantu_10'] = '';
					$data['ygmembantu_11'] = '';
					$data['ygmembantu_12'] = '';
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
		//statis
		// $totalbobot = '15';
		$totalbobot = $this->m_penetapanAngaran->bobot_komponen($thn,$kd_giat,$kd_output,$kd_komponen);
		// pr($totalbobot);
		$sisacapaian = $totalbobot['bobot'] - $realisasi_sd_bulan['total']; 
		$this->view->assign('totalbobot',$totalbobot['bobot']);
		$this->view->assign('sisacapaian',$sisacapaian);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		$this->view->assign('rencanasdbulan',$rencana_sd_bulan['total']);
		$this->view->assign('realisasisdbulan',$realisasi_sd_bulan['total']);
		$this->view->assign('data',$data);
		$this->view->assign('data',$data);
		
		return $this->loadView('monev/editBobot');
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
					$data['kendala'] = $get_data ['kendala'] ."{'\n'}".$get_data ['kendala_2'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."{'\n'}".$get_data['tindaklanjut_2'];
					$data['ygmembantu'] = $get_data['ygmembantu']."{'\n'}".$get_data['ygmembantu_2'];
				break;
				case 03:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3'];
				break;
				case 04:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4'];
				break;
				case 05:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5'];
				break;
				case 06:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6'];
				
				break;
				case 07:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7'];
				
				break;
				case 08:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7']."<br/>".$get_data ['kendala_8'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7']."<br/>".$get_data['tindaklanjut_8'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7']."<br/>".$get_data['ygmembantu_8'];
				
				break;
				case 09:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7']."<br/>".$get_data ['kendala_8']."<br/>".$get_data ['kendala_9'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7']."<br/>".$get_data['tindaklanjut_8']."<br/>".$get_data['tindaklanjut_9'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7']."<br/>".$get_data['ygmembantu_8']."<br/>".$get_data['ygmembantu_9'];
				
				break;
				case 10:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7']."<br/>".$get_data ['kendala_8']."<br/>".$get_data ['kendala_9']."<br/>".$get_data ['kendala_10'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7']."<br/>".$get_data['tindaklanjut_8']."<br/>".$get_data['tindaklanjut_9']."<br/>".$get_data['tindaklanjut_10'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7']."<br/>".$get_data['ygmembantu_8']."<br/>".$get_data['ygmembantu_9']."<br/>".$get_data['ygmembantu_10'];
				
				break;
				case 11:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7']."<br/>".$get_data ['kendala_8']."<br/>".$get_data ['kendala_9']."<br/>".$get_data ['kendala_10']."<br/>".$get_data ['kendala_11'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7']."<br/>".$get_data['tindaklanjut_8']."<br/>".$get_data['tindaklanjut_9']."<br/>".$get_data['tindaklanjut_10']."<br/>".$get_data['tindaklanjut_11'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7']."<br/>".$get_data['ygmembantu_8']."<br/>".$get_data['ygmembantu_9']."<br/>".$get_data['ygmembantu_10']."<br/>".$get_data['ygmembantu_11'];
				
				break;
				case 12:
					$data['kendala'] = $get_data ['kendala'] ."<br/>".$get_data ['kendala_2']."<br/>".$get_data ['kendala_3']."<br/>".$get_data ['kendala_4']."<br/>".$get_data ['kendala_5']."<br/>".$get_data ['kendala_6']."<br/>".$get_data ['kendala_7']."<br/>".$get_data ['kendala_8']."<br/>".$get_data ['kendala_9']."<br/>".$get_data ['kendala_10']."<br/>".$get_data ['kendala_11']."<br/>".$get_data ['kendala_12'];
					$data['tindaklanjut'] = $get_data['tindaklanjut'] ."<br/>".$get_data['tindaklanjut_2']."<br/>".$get_data['tindaklanjut_3']."<br/>".$get_data['tindaklanjut_4']."<br/>".$get_data['tindaklanjut_5']."<br/>".$get_data['tindaklanjut_6']."<br/>".$get_data['tindaklanjut_7']."<br/>".$get_data['tindaklanjut_8']."<br/>".$get_data['tindaklanjut_9']."<br/>".$get_data['tindaklanjut_10']."<br/>".$get_data['tindaklanjut_11']."<br/>".$get_data['tindaklanjut_12'];
					$data['ygmembantu'] = $get_data['ygmembantu']."<br/>".$get_data['ygmembantu_2']."<br/>".$get_data['ygmembantu_3']."<br/>".$get_data['ygmembantu_4']."<br/>".$get_data['ygmembantu_5']."<br/>".$get_data['ygmembantu_6']."<br/>".$get_data['ygmembantu_7']."<br/>".$get_data['ygmembantu_8']."<br/>".$get_data['ygmembantu_9']."<br/>".$get_data['ygmembantu_10']."<br/>".$get_data['ygmembantu_11']."<br/>".$get_data['ygmembantu_12'];
				
				break;
			}
			$data['jml_target'] = $get_data['jumlah'] ;
		}else{
			$data['kendala'] = '';
			$data['tindaklanjut'] ='';
			$data['ygmembantu'] ='';
			$data['jml_target'] =0;
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
			if($get_realisasi['realisasi'] != 0 || $get_realisasi['realisasi'] != ''){
				$data['persentase_rencana'] = round(($get_realisasi['realisasi'] / $rencana_sd_bulan['total']) * 100 ,2);
				$data['persentase_pagu'] = round(($get_realisasi['realisasi'] / $pagu) * 100 ,2);
			}else{
				$data['persentase_rencana'] = 0;
				$data['persentase_pagu'] = 0;
			}
			$sisa_anggaran = $pagu - round($get_realisasi['realisasi']);
			$data['sisa_anggaran'] = number_format($sisa_anggaran,'0',',','.');
			
		}else{
			$data['rencanasdbulan'] =  $rencana_sd_bulan['total'];
			$data['realisasi_blnini'] = 0;
			$data['realisasi_sdbulan'] = 0;
			$data['persentase_rencana'] = 0;
			$data['persentase_pagu'] = 0;
			$data['sisa_anggaran'] = number_format($pagu,'0',',','.'); ;
			
		}
		$newformat = array('rncn_sdbln'=>$data['rencanasdbulan'],'real_blnini'=>$data['realisasi_blnini'],'real_sdbln'=>$data['realisasi_sdbulan'],'pers_rencana'=>$data['persentase_rencana'],'pers_pagu'=>$data['persentase_pagu'],'sisa'=>$data['sisa_anggaran']);
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
		
		return $this->loadView('monev/editAnggaran');
	
	}
	public function post(){
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
}

?>
