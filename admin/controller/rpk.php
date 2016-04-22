<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class rpk extends Controller {
	
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
		// pr($list_dropdown);
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// pr($thn_aktif);
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		$thn_renstra =$thn_aktif['data'];
		// $thn_temp = '2013';
		//=============================== 
		/*if($this->admin['type'] == 1){
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		}else{
			$str = rtrim($this->admin['kode'], '0');
			$length = strlen($str);
			if($length == 3){
				$param_list = '1';
				$kd_unit_a = $str;
			}elseif($length == 4){
				$param_list = '2';
				$kd_unit_a = $this->admin['kode'];
			}
			$list_dropdown = $this->m_penetapanAngaran->list_dropdown_cstmn($param_list,$kd_unit_a);
		}*/	
		$list_dropdown = $this->m_penetapanAngaran->list_dropdown();
		//===============================
		if($_POST['unit'] !=''){
			// pr($_POST['unit']);
			// echo "masukk";
			// exit;
			$param = array();
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
				}
				
			}
		}else{
			$param = array();
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
			}
			$kd_unit = $kode;
			*/
			$kd_unit = '841100';
			if($this->admin['type'] == 1){
				$akses = '1';
			}elseif($this->admin['kode'] == $kd_unit){
				$akses = '1';
			}else{
				$akses = '0';
			}
			//===============================
			
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
			// pr($kd_kegiatan);
			foreach($kd_kegiatan as $key=>$val){
				$list[] = $val;
				$pagu_giat = $this->m_penetapanAngaran->pagu_giat($thn_temp,$val['kdgiat']);
				// pr($pagu_giat);
				$list[$key]['pagu_giat'] = $pagu_giat['pagu_giat'];
				
				//output
				$output = $this->m_penetapanAngaran->output($thn_temp,$val['kdgiat']);
				$list_out = array();
				foreach($output as $k=>$out){
					$list[$key]['output'][$k] = $out;
					$nama_output = $this->m_penetapanAngaran->nama_output($val['kdgiat'],$out['KDOUTPUT']);
					$list[$key]['output'][$k]['nama_output'] = $nama_output['NMOUTPUT'];
				}	
			}
		}
		// pr($this->admin['kode']);
		// pr($kd_unit);
		// pr($akses);
			
		$this->view->assign('kd_unit',$kd_unit);
		$this->view->assign('list_dropdown',$list_dropdown);
		$this->view->assign('data',$list);
		$this->view->assign('param',$param);
		$this->view->assign('akses',$akses);
		// pr($list);
		//default kode
		
		return $this->loadView('rpk/list');

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
		return($result);
	}
	
	public function edit(){
		global $basedomain;
		$thn = $_GET['thn'];
		$kd_unit = $_GET['kd_unit'];
		$kd_giat = $_GET['kd_giat'];
		$kd_output = $_GET['kd_output'];
		
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
		
			//Rincian Kegiatan
		$rincian = $this->m_penetapanAngaran->rincian($thn,$kd_unit,$kd_giat,$kd_output);
		// pr($rincian);
		$rinc['tujuan'] = $rincian['tujuan'];
		$rinc['sasaran_trw_1'] = $rincian['ursasaran_1'];
		$rinc['sasaran_trw_2'] = $rincian['ursasaran_2'];
		$rinc['sasaran_trw_3'] = $rincian['ursasaran_3'];
		$rinc['sasaran_trw_4'] = $rincian['ursasaran_4'];
		$rinc['persentase_trw_1'] = $rincian['sasaran_1'];
		$rinc['persentase_trw_2'] = $rincian['sasaran_2'];
		$rinc['persentase_trw_3'] = $rincian['sasaran_3'];
		$rinc['persentase_trw_4'] = $rincian['sasaran_4'];
		$rinc['id'] = $rincian['id'];
		$rinc['status'] = $rincian['status'];
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				$sub_komponen = $this->m_penetapanAngaran->sub_komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN']);
				// db($sub_komponen);
					foreach($sub_komponen as $sb=>$sub){
						$list[$key]['bobot']['target_1'] = $list[$key]['bobot']['target_1'] + $sub['target_1']; 
						$list[$key]['bobot']['target_2'] = $list[$key]['bobot']['target_2'] + $sub['target_2']; 
						$list[$key]['bobot']['target_3'] = $list[$key]['bobot']['target_3'] + $sub['target_3']; 
						$list[$key]['bobot']['target_4'] = $list[$key]['bobot']['target_4'] + $sub['target_4']; 
						$list[$key]['bobot']['target_5'] = $list[$key]['bobot']['target_5'] + $sub['target_5']; 
						$list[$key]['bobot']['target_6'] = $list[$key]['bobot']['target_6'] + $sub['target_6']; 
						$list[$key]['bobot']['target_7'] = $list[$key]['bobot']['target_7'] + $sub['target_7']; 
						$list[$key]['bobot']['target_8'] = $list[$key]['bobot']['target_8'] + $sub['target_8']; 
						$list[$key]['bobot']['target_9'] = $list[$key]['bobot']['target_9'] + $sub['target_9']; 
						$list[$key]['bobot']['target_10'] = $list[$key]['bobot']['target_10'] + $sub['target_10']; 
						$list[$key]['bobot']['target_11'] = $list[$key]['bobot']['target_11'] + $sub['target_11']; 
						$list[$key]['bobot']['target_12'] = $list[$key]['bobot']['target_12'] + $sub['target_12'];
						$list[$key]['total_bobot'] = array_sum($list[$key]['bobot']);
						$detail['bobot_1'] = $detail['bobot_1'] + $sub['target_1'] + $sub['target_2'] + $sub['target_3']; 
						$detail['bobot_2'] = $detail['bobot_2'] + $sub['target_4'] + $sub['target_5'] + $sub['target_6'];
						$detail['bobot_3'] = $detail['bobot_3'] + $sub['target_7'] + $sub['target_8'] + $sub['target_9'];
						$detail['bobot_4'] = $detail['bobot_4'] + $sub['target_10'] + $sub['target_11'] + $sub['target_12'];

						$list[$key]['anggaran_1'] = $list[$key]['anggaran_1'] + $sub['anggaran_1']; 
						$list[$key]['anggaran_2'] = $list[$key]['anggaran_2'] + $sub['anggaran_2']; 
						$list[$key]['anggaran_3'] = $list[$key]['anggaran_3'] + $sub['anggaran_3']; 
						$list[$key]['anggaran_4'] = $list[$key]['anggaran_4'] + $sub['anggaran_4']; 
						$list[$key]['anggaran_5'] = $list[$key]['anggaran_5'] + $sub['anggaran_5']; 
						$list[$key]['anggaran_6'] = $list[$key]['anggaran_6'] + $sub['anggaran_6']; 
						$list[$key]['anggaran_7'] = $list[$key]['anggaran_7'] + $sub['anggaran_7']; 
						$list[$key]['anggaran_8'] = $list[$key]['anggaran_8'] + $sub['anggaran_8']; 
						$list[$key]['anggaran_9'] = $list[$key]['anggaran_9'] + $sub['anggaran_9']; 
						$list[$key]['anggaran_10'] = $list[$key]['anggaran_10'] + $sub['anggaran_10']; 
						$list[$key]['anggaran_11'] = $list[$key]['anggaran_11'] + $sub['anggaran_11']; 
						$list[$key]['anggaran_12'] = $list[$key]['anggaran_12'] + $sub['anggaran_12'];

						$list[$key]['sub_komponen'][] = $sub;
						$sum_bobot = $sub['target_1'] + $sub['target_2'] + $sub['target_3'] + $sub['target_4'] + $sub['target_5'] +
									 $sub['target_6'] + $sub['target_7'] + $sub['target_8'] + $sub['target_9'] + $sub['target_10'] +
									 $sub['target_11'] + $sub['target_12'];
						$sum_anggaran = $sub['anggaran_1'] + $sub['anggaran_2'] + $sub['anggaran_3'] + $sub['anggaran_4'] + $sub['anggaran_5'] +
									 $sub['anggaran_6'] + $sub['anggaran_7'] + $sub['anggaran_8'] + $sub['anggaran_9'] + $sub['anggaran_10'] +
									 $sub['anggaran_11'] + $sub['anggaran_12'];	
						$list[$key]['sub_komponen'][$sb]['sum_bobot'] = $sum_bobot;
						$list[$key]['sub_komponen'][$sb]['sum_anggaran'] = $sum_anggaran;
					}
					// db($thp_kegiatan);
			}
		/*pr($list);
		pr($info);
		pr($rinc);
		pr($detail);*/
		// pr($info);
		
		// exit;
		$this->view->assign('detail',$detail);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		return $this->loadView('rpk/edit');
	}
	
	public function print_rpk(){
		global $basedomain;
		// pr($_POST);
		$thn = $_GET['th'];
		$kd_unit = $_GET['kdunitkerja'];
		$kd_giat = $_GET['kdgiat'];
		$kd_output = $_GET['kdoutput'];
		
		//temp:
		$split = substr($kd_unit,0,3);
		$join = $split.'000';
		$ttd_nama = $this->m_penetapanAngaran->nama_unit($join);
		// pr($join);
		// pr($ttd_nama['nmunit']);
		
		//tanggal
		$tgl = date("Y-m-d");
		$tgl_format = $this->DateToIndo($tgl);
		// pr($tgl);
		// pr($tgl_format);
		
		
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
		
			//Rincian Kegiatan
		$rincian = $this->m_penetapanAngaran->rincian($thn,$kd_unit,$kd_giat,$kd_output);
		// pr($rincian);
		$rinc['tujuan'] = $rincian['tujuan'];
		$rinc['sasaran_trw_1'] = $rincian['ursasaran_1'];
		$rinc['sasaran_trw_2'] = $rincian['ursasaran_2'];
		$rinc['sasaran_trw_3'] = $rincian['ursasaran_3'];
		$rinc['sasaran_trw_4'] = $rincian['ursasaran_4'];
		$rinc['persentase_trw_1'] = $rincian['sasaran_1'];
		$rinc['persentase_trw_2'] = $rincian['sasaran_2'];
		$rinc['persentase_trw_3'] = $rincian['sasaran_3'];
		$rinc['persentase_trw_4'] = $rincian['sasaran_4'];
		$rinc['id'] = $rincian['id'];
		$rinc['status'] = $rincian['status'];
		
		//$thp kegiatan
			$thp_kegiatan = $this->m_penetapanAngaran->thp_kegiatan($thn,$kd_giat,$kd_output);
			// pr($thp_kegiatan);
			// exit;
			foreach ($thp_kegiatan as $key=>$val){
				$list[] = $val;
				$komponen = $this->m_penetapanAngaran->komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN'],$val['KDSOUTPUT']);
				// pr($komponen);
				$list[$key]['nama_komponen'] = $komponen['URKMPNEN'];
				
				$sub_komponen = $this->m_penetapanAngaran->sub_komponen($thn,$kd_giat,$kd_output,$val['KDKMPNEN']);
				// db($sub_komponen);
					foreach($sub_komponen as $sb=>$sub){
						$list[$key]['bobot']['target_1'] = $list[$key]['bobot']['target_1'] + $sub['target_1']; 
						$list[$key]['bobot']['target_2'] = $list[$key]['bobot']['target_2'] + $sub['target_2']; 
						$list[$key]['bobot']['target_3'] = $list[$key]['bobot']['target_3'] + $sub['target_3']; 
						$list[$key]['bobot']['target_4'] = $list[$key]['bobot']['target_4'] + $sub['target_4']; 
						$list[$key]['bobot']['target_5'] = $list[$key]['bobot']['target_5'] + $sub['target_5']; 
						$list[$key]['bobot']['target_6'] = $list[$key]['bobot']['target_6'] + $sub['target_6']; 
						$list[$key]['bobot']['target_7'] = $list[$key]['bobot']['target_7'] + $sub['target_7']; 
						$list[$key]['bobot']['target_8'] = $list[$key]['bobot']['target_8'] + $sub['target_8']; 
						$list[$key]['bobot']['target_9'] = $list[$key]['bobot']['target_9'] + $sub['target_9']; 
						$list[$key]['bobot']['target_10'] = $list[$key]['bobot']['target_10'] + $sub['target_10']; 
						$list[$key]['bobot']['target_11'] = $list[$key]['bobot']['target_11'] + $sub['target_11']; 
						$list[$key]['bobot']['target_12'] = $list[$key]['bobot']['target_12'] + $sub['target_12'];
						$list[$key]['total_bobot'] = array_sum($list[$key]['bobot']);
						
						$detail['bobot_1'] = $detail['bobot_1'] + $sub['target_1'] + $sub['target_2'] + $sub['target_3']; 
						$detail['bobot_2'] = $detail['bobot_2'] + $sub['target_4'] + $sub['target_5'] + $sub['target_6'];
						$detail['bobot_3'] = $detail['bobot_3'] + $sub['target_7'] + $sub['target_8'] + $sub['target_9'];
						$detail['bobot_4'] = $detail['bobot_4'] + $sub['target_10'] + $sub['target_11'] + $sub['target_12'];

						$list[$key]['anggaran_1'] = $list[$key]['anggaran_1'] + $sub['anggaran_1']; 
						$list[$key]['anggaran_2'] = $list[$key]['anggaran_2'] + $sub['anggaran_2']; 
						$list[$key]['anggaran_3'] = $list[$key]['anggaran_3'] + $sub['anggaran_3']; 
						$list[$key]['anggaran_4'] = $list[$key]['anggaran_4'] + $sub['anggaran_4']; 
						$list[$key]['anggaran_5'] = $list[$key]['anggaran_5'] + $sub['anggaran_5']; 
						$list[$key]['anggaran_6'] = $list[$key]['anggaran_6'] + $sub['anggaran_6']; 
						$list[$key]['anggaran_7'] = $list[$key]['anggaran_7'] + $sub['anggaran_7']; 
						$list[$key]['anggaran_8'] = $list[$key]['anggaran_8'] + $sub['anggaran_8']; 
						$list[$key]['anggaran_9'] = $list[$key]['anggaran_9'] + $sub['anggaran_9']; 
						$list[$key]['anggaran_10'] = $list[$key]['anggaran_10'] + $sub['anggaran_10']; 
						$list[$key]['anggaran_11'] = $list[$key]['anggaran_11'] + $sub['anggaran_11']; 
						$list[$key]['anggaran_12'] = $list[$key]['anggaran_12'] + $sub['anggaran_12'];

						$list[$key]['sub_komponen'][] = $sub;
						$sum_bobot = $sub['target_1'] + $sub['target_2'] + $sub['target_3'] + $sub['target_4'] + $sub['target_5'] +
									 $sub['target_6'] + $sub['target_7'] + $sub['target_8'] + $sub['target_9'] + $sub['target_10'] +
									 $sub['target_11'] + $sub['target_12'];
						$sum_anggaran = $sub['anggaran_1'] + $sub['anggaran_2'] + $sub['anggaran_3'] + $sub['anggaran_4'] + $sub['anggaran_5'] +
									 $sub['anggaran_6'] + $sub['anggaran_7'] + $sub['anggaran_8'] + $sub['anggaran_9'] + $sub['anggaran_10'] +
									 $sub['anggaran_11'] + $sub['anggaran_12'];	
						$list[$key]['sub_komponen'][$sb]['sum_bobot'] = $sum_bobot;
						$list[$key]['sub_komponen'][$sb]['sum_anggaran'] = $sum_anggaran;
					}
					// db($thp_kegiatan);
			}
		// pr($list);
		// pr($info);
		// pr($rinc);
		// pr($detail);
		//get total bobot
		foreach ($list as $valbobot){
			$totalBobot += $valbobot['total_bobot'];
		}
		
		//load 
		$this->reportHelper =$this->loadModel('reportHelper');
		$this->view->assign('detail',$detail);
		$this->view->assign('info',$info);
		$this->view->assign('rinc',$rinc);
		$this->view->assign('list',$list);
		$this->view->assign('totalBobot',$totalBobot);
		$this->view->assign('tgl_format',$tgl_format);
		$this->view->assign('ttd_nama',$ttd_nama['nmunit']);
		$html = $this->loadView('rpk/print');
		$generate = $this->reportHelper->loadMpdf($html, 'rpk');
		
		
		//for xls document
		/*$waktu=date("d-m-y_h:i:s");
		$filename ="visitor_$waktu.xls";
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);*/
		
	}
	
	public function post(){
		// pr($_POST);
		$tujuan = $_POST['tujuan'];
		$sasaran_1 = $_POST['sasaran_1'];
		$sasaran_2 = $_POST['sasaran_2'];
		$sasaran_3 = $_POST['sasaran_3'];
		$sasaran_4 = $_POST['sasaran_4'];
		$ursasaran_1 = $_POST['ursasaran_1'];
		$ursasaran_2 = $_POST['ursasaran_2'];
		$ursasaran_3 = $_POST['ursasaran_3'];
		$ursasaran_4 = $_POST['ursasaran_4'];
		$status = $_POST['status'];
		$tgl_kirim = date("Y-m-d");
		$kdunitkerja = $_POST['kdunitkerja'];
		$kdgiat = $_POST['kdgiat'];
		$kdoutput = $_POST['kdoutput'];
		$id = $_POST['id'];
		$th = $_POST['th'];
		// pr($data);
		if($_POST['id'] != ''){
			$update = $this->m_penetapanAngaran->update($tujuan,$sasaran_1,$sasaran_2,$sasaran_3,$sasaran_4,
														$ursasaran_1,$ursasaran_2,$ursasaran_3,$ursasaran_4,
														$status,$tgl_kirim,$kdunitkerja,$kdgiat,$kdoutput,
														$id,$th
														);
		}else{
			$insert = $this->m_penetapanAngaran->insert($tujuan,$sasaran_1,$ursasaran_1,$sasaran_2,$ursasaran_2,
														$sasaran_3,$ursasaran_3,$sasaran_4,$ursasaran_4,$status,
														$tgl_kirim,$kdunitkerja,$kdgiat,$kdoutput,$id,$th);
		}
		exit;
		// return $this->loadView('rpk/editTahapan');

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
		return $this->loadView('rpk/editTahapan');

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
		// pr($info);
		$bobot = $this->m_penetapanAngaran->getBobotRpk($_GET);
		$sumBobot = $this->m_penetapanAngaran->sumBobot($_GET);

		$this->view->assign('usertype',$this->admin['type']);
		$this->view->assign('sumBobot',$sumBobot[0]);
		$this->view->assign('bobot',$bobot);	
		$this->view->assign('info',$info);
		$this->view->assign('list',$list);	
		return $this->loadView('rpk/editRencanaAnggaran');

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

	public function editBobot()
	{
		$data = $this->m_penetapanAngaran->getBobotRpk($_GET);

		$this->view->assign('data',$data);
		$this->view->assign('get',$_GET);

		return $this->loadView('rpk/editBobot');
	}

	public function ins_bobot()
	{
		global $basedomain;

		if($_POST['id'] == ""){
			$this->m_penetapanAngaran->insert_bobot($_POST);
		} else {
			$this->m_penetapanAngaran->update_bobot($_POST);
		}

		redirect($basedomain.'rpk/edit/?thn='.$_POST['th'].'&kd_unit='.$_POST['kdunitkerja'].'&kd_giat='.$_POST['kdgiat'].'&kd_output='.$_POST['kdoutput']);
		exit;
	}
}

?>
