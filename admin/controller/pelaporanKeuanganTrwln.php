<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class pelaporanKeuanganTrwln extends Controller {
	
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
		
		// $this->contentHelper = $this->loadModel('contentHelper');
		$this->m_pelaporankeuangan = $this->loadModel('m_pelaporankeuangan');
		$this->m_penetapanAngaran = $this->loadModel('m_penetapanAngaran');
	}
	
	public function anggaranTotal(){
	//pr($_POST);
	
	$bl =date('m');
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
	

	// $thn_temp = '2015';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	
	if($_POST['kdtriwulan'] != ''){
		
		$trwln = $_POST['kdtriwulan'];
		if($trwln == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			
		}elseif($trwln == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwln == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwln == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		// pr($trwln);
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($dataselected);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		// echo"masuk";
		// exit;
		$real_menteri_triwulan_BSN = $this->m_pelaporankeuangan->real_menteri_triwulan_BSN($thn_temp,$trwln);
		$real_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		if($renc_menteri_sdtriwulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdtriwulan_BSN['jml'] / $renc_menteri_sdtriwulan_BSN['rencana'])*100,2);
		
		}
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdtriwulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdtriwulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]= $real_menteri_triwulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		// exit;
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		$renc_satker_sdtriwulan = $this->m_pelaporankeuangan->renc_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_triwulan = $this->m_pelaporankeuangan->real_satker_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdtriwulan = $this->m_pelaporankeuangan->real_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdtriwulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdtriwulan['jml'] / $renc_satker_sdtriwulan['rencana'])*100,2);
		
		}
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdtriwulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdtriwulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdtriwulan;
		$data_bsn_induk_sub[]= $real_satker_triwulan;
		$data_bsn_induk_sub[]= $real_satker_sdtriwulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		// pr($data_bsn_induk_sub);
		
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			$renc_giat_sdtriwulan = $this->m_pelaporankeuangan->renc_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_triwulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdtriwulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdtriwulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $renc_giat_sdtriwulan['rencana'])*100,2);
			}
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / ($val['pagu_giat']/2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdtriwulan']= $renc_giat_sdtriwulan['rencana'];
			$list_kegiatan[$k]['real_giat_triwulan']= $real_giat_triwulan['jml'];
			$list_kegiatan[$k]['real_giat_sdtriwulan']= $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_triwulan= $this->m_pelaporankeuangan->real_output_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdtriwulan= $this->m_pelaporankeuangan->real_output_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdtriwulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdtriwulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_triwulan']=$real_output_triwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdtriwulan']=$real_output_sdtriwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}else{
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// $trwln = 2;
		$trwln = $trwulan;
		// pr($trwln);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		// exit;
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		// pr($Select_nama_BSN);
		$renc_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		$real_menteri_triwulan_BSN = $this->m_pelaporankeuangan->real_menteri_triwulan_BSN($thn_temp,$trwln);
		$real_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		if($renc_menteri_sdtriwulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdtriwulan_BSN['jml'] / $renc_menteri_sdtriwulan_BSN['rencana'])*100,2);
		
		}
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdtriwulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdtriwulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]= $real_menteri_triwulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		// exit;
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		$renc_satker_sdtriwulan = $this->m_pelaporankeuangan->renc_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_triwulan = $this->m_pelaporankeuangan->real_satker_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdtriwulan = $this->m_pelaporankeuangan->real_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdtriwulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdtriwulan['jml'] / $renc_satker_sdtriwulan['rencana'])*100,2);
		
		}
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdtriwulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdtriwulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdtriwulan;
		$data_bsn_induk_sub[]= $real_satker_triwulan;
		$data_bsn_induk_sub[]= $real_satker_sdtriwulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		
		// pr($data_bsn_induk_sub);
		
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			$renc_giat_sdtriwulan = $this->m_pelaporankeuangan->renc_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_triwulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdtriwulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdtriwulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $renc_giat_sdtriwulan['rencana'])*100,2);
			}
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / ($val['pagu_giat'] /2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdtriwulan']= $renc_giat_sdtriwulan['rencana'];
			$list_kegiatan[$k]['real_giat_triwulan']= $real_giat_triwulan['jml'];
			$list_kegiatan[$k]['real_giat_sdtriwulan']= $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_triwulan= $this->m_pelaporankeuangan->real_output_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdtriwulan= $this->m_pelaporankeuangan->real_output_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdtriwulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdtriwulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_triwulan']=$real_output_triwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdtriwulan']=$real_output_sdtriwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
		// pr($list_kegiatan);
	}
		// pr($dataselected);
		// pr($list_kegiatan);
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranTotal');
		
		
	}

	public function anggaranJenisBelanja(){
		$bl =date('m');
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
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		// $trwln = 2;
		// $trwln = $trwulan;
		
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		// pr($thn_temp);
			if($_POST['kdtriwulan'] != ''){
				$trwln = $_POST['kdtriwulan'];
				if($trwln == 1){
					$I = "selected";
					$II = "";
					$III = "";
					$IV = "";
					$ket = "Triwulan I";
					
				}elseif($trwln == 2){
					$I = "";
					$II = "selected";
					$III = "";
					$IV = "";
					$ket = "Triwulan II";
				}elseif($trwln == 3){
					$I = "";
					$II = "";
					$III = "selected";
					$IV = "";
					$ket = "Triwulan III";
				}elseif($trwln == 4){
					$I = "";
					$II = "";
					$III = "";
					$IV = "selected";
					$ket = "Triwulan IV";
				}
				// pr($trwln);
				$dataselected[]=$I;
				$dataselected[]=$II;
				$dataselected[]=$III;
				$dataselected[]=$IV;
				$dataselected[]=$ket;
				
				// pr($dataselected);
				// exit;
			$select_data_master_bsn = $this->m_penetapanAngaran->cek_pagu($thn_temp);
			// pr($select_data_master_bsn);
			$kode_BSN = "840000";
			$Select_nama_BSN = $this->m_penetapanAngaran->nm_unit($kode_BSN);
			
			$pegawai = $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_pegawai = round(($pegawai['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_pegawai = 0 ;
			}
			//add
			$realisasi_pegawai = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,51);
			$sisa_anggaran_pegawai = $pegawai['pagu_satker'] - $realisasi_pegawai['realisasi'];
			
			$barang = $this->m_penetapanAngaran->anggaran_belanja_menteri_barang($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_barang = round(($barang['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_barang = 0 ;
			}
			//add
			$realisasi_barang = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,52);
			$sisa_anggaran_barang = $barang['pagu_satker'] - $realisasi_barang['realisasi'];
			
			$modal = $this->m_penetapanAngaran->anggaran_belanja_menteri_modal($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_modal = round(($modal['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_modal = 0 ;
			}
			//add
			$realisasi_modal = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,53);
			$sisa_anggaran_modal = $modal['pagu_satker'] - $realisasi_modal['realisasi'];
			
			$perjalanan = $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_perjalanan = round(($perjalanan['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_perjalanan = 0 ;
			}
			
			//add
			$realisasi_perjalanan = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,524);
			$sisa_anggaran_perjalanan = $perjalanan['pagu_satker'] - $realisasi_perjalanan['realisasi'];
			
			$total_realisasi_jenis_belanja = $realisasi_pegawai['realisasi'] + $realisasi_barang['realisasi'] + 
											 $realisasi_modal['realisasi'] + $realisasi_perjalanan['realisasi'];
			// pr($total_realisasi_jenis_belanja);
			$persentase_realisasi_jenis_belanja = round(($total_realisasi_jenis_belanja / $select_data_master_bsn['pagu_menteri'])*100,2) ;
			$sisa_anggaran_jenis_belanja = $select_data_master_bsn['pagu_menteri'] - $total_realisasi_jenis_belanja;
			// pr($pegawai);
			// pr($p_pegawai);
			
			$data_bsn_induk[]['kode']= '084';
			$data_bsn_induk[]= $select_data_master_bsn;
			$data_bsn_induk[]= $Select_nama_BSN;
			$data_bsn_induk[]['pegawai']= $pegawai;
			$data_bsn_induk[]['persentasepegawai']= $p_pegawai;
			$data_bsn_induk[]['barang']= $barang;
			$data_bsn_induk[]['persentasebarang']= $p_barang;
			$data_bsn_induk[]['modal']= $modal;
			$data_bsn_induk[]['persentasemodal']= $p_modal;
			$data_bsn_induk[]['perjalanan']= $perjalanan;
			$data_bsn_induk[]['persentaseperjalanan']= $p_perjalanan;
			$data_bsn_induk[]['tahun']= $thn_temp ;
			$data_bsn_induk[]['realisasi_belanja_pegawai']= $realisasi_pegawai['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_pegawai']= $sisa_anggaran_pegawai;
			$data_bsn_induk[]['realisasi_belanja_barang']= $realisasi_barang['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_barang']= $sisa_anggaran_barang;
			$data_bsn_induk[]['realisasi_belanja_modal']= $realisasi_modal['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_modal']= $sisa_anggaran_modal;
			$data_bsn_induk[]['realisasi_belanja_perjalanan']= $realisasi_perjalanan['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_perjalanan']= $sisa_anggaran_perjalanan;
			$data_bsn_induk[]['total_realisasi_jenis_belanja']= $total_realisasi_jenis_belanja;
			$data_bsn_induk[]['persentase_total_realisasi_jenis_belanja']= $persentase_realisasi_jenis_belanja;
			$data_bsn_induk[]['sisa_anggaran_total_realisasi_jenis_belanja']= $sisa_anggaran_jenis_belanja;
			
			// pr($data_bsn_induk);
			// exit;
			$select_kd_satker = $this->m_penetapanAngaran->select_data_bsn($thn_temp);
			$select_nama_satker = $this->m_penetapanAngaran->select_nama($select_kd_satker['KDSATKER']);
			$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
			$data_bsn_induk_sub[]['pagu']= $select_kd_satker['pagu_satker'];
			$data_bsn_induk_sub[]['nama']= $select_nama_satker['NMSATKER'];
			$data_bsn_induk_sub[]['pegawai']= $pegawai;
			$data_bsn_induk_sub[]['persentasepegawai']= $p_pegawai;
			$data_bsn_induk_sub[]['barang']= $barang;
			$data_bsn_induk_sub[]['persentasebarang']= $p_barang;
			$data_bsn_induk_sub[]['modal']= $modal;
			$data_bsn_induk_sub[]['persentasemodal']= $p_modal;
			$data_bsn_induk_sub[]['perjalanan']= $perjalanan;
			$data_bsn_induk_sub[]['persentaseperjalanan']= $p_perjalanan;
			$data_bsn_induk_sub[]['tahun']= $thn_temp ;
			$data_bsn_induk_sub[]['realisasi_belanja_pegawai']= $realisasi_pegawai['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_pegawai']= $sisa_anggaran_pegawai;
			$data_bsn_induk_sub[]['realisasi_belanja_barang']= $realisasi_barang['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_barang']= $sisa_anggaran_barang;
			$data_bsn_induk_sub[]['realisasi_belanja_modal']= $realisasi_modal['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_modal']= $sisa_anggaran_modal;
			$data_bsn_induk_sub[]['realisasi_belanja_perjalanan']= $realisasi_perjalanan['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_perjalanan']= $sisa_anggaran_perjalanan;
			$data_bsn_induk_sub[]['total_realisasi_jenis_belanja']= $total_realisasi_jenis_belanja;
			$data_bsn_induk_sub[]['persentase_total_realisasi_jenis_belanja']= $persentase_realisasi_jenis_belanja;
			$data_bsn_induk_sub[]['sisa_anggaran_total_realisasi_jenis_belanja']= $sisa_anggaran_jenis_belanja;
			// pr($data_bsn_induk_sub);
			// exit;
			//unit eselon II
			$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd($thn_temp,$select_kd_satker['KDSATKER']);
			// pr($select_kegiatan);
			foreach ($select_kegiatan as $k=>$val) {
				$list_kegiatan[] = $val;
				// $nama_unit= $this->m_penetapanAngaran->nm_unit($val['kdunitkerja']);
				$nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($val['KDGIAT']);
				// $list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
				$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
				$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'];
				
				//belanja pegawai
				$anggaran_belanja_menteri_pegawai_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				
				//add
				$realisasi_anggaran_belanja_menteri_pegawai_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,51);
				if($anggaran_belanja_menteri_pegawai_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_pegawai_giat = $anggaran_belanja_menteri_pegawai_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_pegawai_giat = 0;
				}
				
				//belanja barang
				$anggaran_belanja_menteri_barang_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_barang_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,52);
				if($anggaran_belanja_menteri_barang_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_barang_giat = $anggaran_belanja_menteri_barang_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_barang_giat = 0;
				}
				//barang modal
				$anggaran_belanja_menteri_modal_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_modal_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,53);
				if($anggaran_belanja_menteri_modal_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_modal_giat = $anggaran_belanja_menteri_modal_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_modal_giat = 0;
				}
				//belanja perjalanan
				$anggaran_belanja_menteri_perjalanan_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_perjalanan_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,524);
				if($anggaran_belanja_menteri_perjalanan_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_perjalanan_giat = $anggaran_belanja_menteri_perjalanan_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_perjalanan_giat = 0;
				}
				
				$realisasi_anggaran_belanja_menteri_giat_total = $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				$sisa_anggaran_belanja_menteri_giat_total = $val['pagu_giat'] - $realisasi_anggaran_belanja_menteri_giat_total;
				$persentase_anggaran_belanja_menteri_giat_total = round(($realisasi_anggaran_belanja_menteri_giat_total / $val['pagu_giat']) * 100,2);
				
				$list_kegiatan[$k]['pagu_giat_pegawai']= $anggaran_belanja_menteri_pegawai_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_pegawai']= $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_pegawai_giat']= $sisa_anggaran_belanja_menteri_pegawai_giat;
				
				$list_kegiatan[$k]['pagu_giat_barang']= $anggaran_belanja_menteri_barang_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_barang']= $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_barang_giat']= $sisa_anggaran_belanja_menteri_barang_giat;
				
				$list_kegiatan[$k]['pagu_giat_modal']= $anggaran_belanja_menteri_modal_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_modal']= $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_modal_giat']= $sisa_anggaran_belanja_menteri_modal_giat;
				
				$list_kegiatan[$k]['pagu_giat_perjalanan']= $anggaran_belanja_menteri_perjalanan_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_perjalanan']= $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_perjalanan_giat']= $sisa_anggaran_belanja_menteri_perjalanan_giat;
				
				$list_kegiatan[$k]['realisasi_anggaran_belanja_menteri_giat_total']= $realisasi_anggaran_belanja_menteri_giat_total;
				$list_kegiatan[$k]['persentase_anggaran_belanja_menteri_giat_total']= $persentase_anggaran_belanja_menteri_giat_total;
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_giat_total']= $sisa_anggaran_belanja_menteri_giat_total;
				
				$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				foreach ($select_output as $vprb=>$valprb){
					$list_kegiatan[$k]['output'][$vprb]=$valprb;
					$nama_output= $this->m_penetapanAngaran->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
					
					//belanja pegawai
					$anggaran_belanja_menteri_pegawai_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_pegawai_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,51);
					if($anggaran_belanja_menteri_pegawai_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_pegawai_output = $anggaran_belanja_menteri_pegawai_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_pegawai_output = 0;
					}
					
					//belanja barang
					$anggaran_belanja_menteri_barang_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_barang_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,52);
					if($anggaran_belanja_menteri_barang_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_barang_output = $anggaran_belanja_menteri_barang_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_barang_output = 0;
					}
					
					//belanja modal
					$anggaran_belanja_menteri_modal_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_modal_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,53);
					if($anggaran_belanja_menteri_modal_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_modal_output = $anggaran_belanja_menteri_modal_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_modal_output = 0;
					}
					//belanja perjalanan
					$anggaran_belanja_menteri_perjalanan_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_perjalanan_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,524);
					if($anggaran_belanja_menteri_perjalanan_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_perjalanan_output = $anggaran_belanja_menteri_perjalanan_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_perjalanan_output = 0;
					}
					
					$realisasi_anggaran_belanja_menteri_output_total = $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_barang_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_modal_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
																 
					$sisa_anggaran_belanja_menteri_output_total = $valprb['pagu_output'] - $realisasi_anggaran_belanja_menteri_output_total;
					$persentase_anggaran_belanja_menteri_output_total = round(($realisasi_anggaran_belanja_menteri_output_total / $valprb['pagu_output']) * 100,2);
				
					
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_pegawai']=$anggaran_belanja_menteri_pegawai_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_pegawai']=$realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_pegawai']=$sisa_anggaran_belanja_menteri_pegawai_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_barang']=$anggaran_belanja_menteri_barang_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_barang']=$realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_barang']=$sisa_anggaran_belanja_menteri_barang_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_modal']=$anggaran_belanja_menteri_modal_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_modal']=$realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_modal']=$sisa_anggaran_belanja_menteri_modal_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_perjalanan']=$anggaran_belanja_menteri_perjalanan_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_perjalanan']=$realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_perjalanan']=$sisa_anggaran_belanja_menteri_perjalanan_output;
					
					$list_kegiatan[$k]['output'][$vprb]['realisasi_anggaran_belanja_menteri_output_total']=$realisasi_anggaran_belanja_menteri_output_total;
					$list_kegiatan[$k]['output'][$vprb]['persentase_anggaran_belanja_menteri_output_total']=$persentase_anggaran_belanja_menteri_output_total;
					$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_belanja_menteri_output_total']=$sisa_anggaran_belanja_menteri_output_total;
					
				}
				
			}

		}else{
			if($trwulan == 1){
				$I = "selected";
				$II = "";
				$III = "";
				$IV = "";
				$ket = "Triwulan I";
			}elseif($trwulan == 2){
				$I = "";
				$II = "selected";
				$III = "";
				$IV = "";
				$ket = "Triwulan II";
			}elseif($trwulan == 3){
				$I = "";
				$II = "";
				$III = "selected";
				$IV = "";
				$ket = "Triwulan III";
			}elseif($trwulan == 4){
				$I = "";
				$II = "";
				$III = "";
				$IV = "selected";
				$ket = "Triwulan IV";
			}
			
			$dataselected[]=$I;
			$dataselected[]=$II;
			$dataselected[]=$III;
			$dataselected[]=$IV;
			$dataselected[]=$ket;
			
			// $trwln = 2;
			$trwln = $trwulan;
		
		
			$select_data_master_bsn = $this->m_penetapanAngaran->cek_pagu($thn_temp);
			// pr($select_data_master_bsn);
			$kode_BSN = "840000";
			$Select_nama_BSN = $this->m_penetapanAngaran->nm_unit($kode_BSN);
			
			$pegawai = $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_pegawai = round(($pegawai['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_pegawai = 0 ;
			}
			//add
			$realisasi_pegawai = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,51);
			$sisa_anggaran_pegawai = $pegawai['pagu_satker'] - $realisasi_pegawai['realisasi'];
			
			$barang = $this->m_penetapanAngaran->anggaran_belanja_menteri_barang($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_barang = round(($barang['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_barang = 0 ;
			}
			//add
			$realisasi_barang = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,52);
			$sisa_anggaran_barang = $barang['pagu_satker'] - $realisasi_barang['realisasi'];
			
			$modal = $this->m_penetapanAngaran->anggaran_belanja_menteri_modal($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_modal = round(($modal['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2,3) ;
			}else{ 
				$p_modal = 0 ;
			}
			//add
			$realisasi_modal = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,53);
			$sisa_anggaran_modal = $modal['pagu_satker'] - $realisasi_modal['realisasi'];
			
			$perjalanan = $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_perjalanan = round(($perjalanan['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_perjalanan = 0 ;
			}
			
			//add
			$realisasi_perjalanan = $this->m_pelaporankeuangan->realisasi_general_trwln($thn_temp,$trwln,524);
			$sisa_anggaran_perjalanan = $perjalanan['pagu_satker'] - $realisasi_perjalanan['realisasi'];
			
			$total_realisasi_jenis_belanja = $realisasi_pegawai['realisasi'] + $realisasi_barang['realisasi'] + 
											 $realisasi_modal['realisasi'] + $realisasi_perjalanan['realisasi'];
			// pr($total_realisasi_jenis_belanja);
			$persentase_realisasi_jenis_belanja = round(($total_realisasi_jenis_belanja / $select_data_master_bsn['pagu_menteri'])*100,2) ;
			$sisa_anggaran_jenis_belanja = $select_data_master_bsn['pagu_menteri'] - $total_realisasi_jenis_belanja;
			// pr($pegawai);
			// pr($p_pegawai);
			
			$data_bsn_induk[]['kode']= '084';
			$data_bsn_induk[]= $select_data_master_bsn;
			$data_bsn_induk[]= $Select_nama_BSN;
			$data_bsn_induk[]['pegawai']= $pegawai;
			$data_bsn_induk[]['persentasepegawai']= $p_pegawai;
			$data_bsn_induk[]['barang']= $barang;
			$data_bsn_induk[]['persentasebarang']= $p_barang;
			$data_bsn_induk[]['modal']= $modal;
			$data_bsn_induk[]['persentasemodal']= $p_modal;
			$data_bsn_induk[]['perjalanan']= $perjalanan;
			$data_bsn_induk[]['persentaseperjalanan']= $p_perjalanan;
			$data_bsn_induk[]['tahun']= $thn_temp ;
			$data_bsn_induk[]['realisasi_belanja_pegawai']= $realisasi_pegawai['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_pegawai']= $sisa_anggaran_pegawai;
			$data_bsn_induk[]['realisasi_belanja_barang']= $realisasi_barang['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_barang']= $sisa_anggaran_barang;
			$data_bsn_induk[]['realisasi_belanja_modal']= $realisasi_modal['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_modal']= $sisa_anggaran_modal;
			$data_bsn_induk[]['realisasi_belanja_perjalanan']= $realisasi_perjalanan['realisasi'];
			$data_bsn_induk[]['sisa_anggaran_perjalanan']= $sisa_anggaran_perjalanan;
			$data_bsn_induk[]['total_realisasi_jenis_belanja']= $total_realisasi_jenis_belanja;
			$data_bsn_induk[]['persentase_total_realisasi_jenis_belanja']= $persentase_realisasi_jenis_belanja;
			$data_bsn_induk[]['sisa_anggaran_total_realisasi_jenis_belanja']= $sisa_anggaran_jenis_belanja;
			
			// pr($data_bsn_induk);
			// exit;
			$select_kd_satker = $this->m_penetapanAngaran->select_data_bsn($thn_temp);
			$select_nama_satker = $this->m_penetapanAngaran->select_nama($select_kd_satker['KDSATKER']);
			$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
			$data_bsn_induk_sub[]['pagu']= $select_kd_satker['pagu_satker'];
			$data_bsn_induk_sub[]['nama']= $select_nama_satker['NMSATKER'];
			$data_bsn_induk_sub[]['pegawai']= $pegawai;
			$data_bsn_induk_sub[]['persentasepegawai']= $p_pegawai;
			$data_bsn_induk_sub[]['barang']= $barang;
			$data_bsn_induk_sub[]['persentasebarang']= $p_barang;
			$data_bsn_induk_sub[]['modal']= $modal;
			$data_bsn_induk_sub[]['persentasemodal']= $p_modal;
			$data_bsn_induk_sub[]['perjalanan']= $perjalanan;
			$data_bsn_induk_sub[]['persentaseperjalanan']= $p_perjalanan;
			$data_bsn_induk_sub[]['tahun']= $thn_temp ;
			$data_bsn_induk_sub[]['realisasi_belanja_pegawai']= $realisasi_pegawai['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_pegawai']= $sisa_anggaran_pegawai;
			$data_bsn_induk_sub[]['realisasi_belanja_barang']= $realisasi_barang['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_barang']= $sisa_anggaran_barang;
			$data_bsn_induk_sub[]['realisasi_belanja_modal']= $realisasi_modal['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_modal']= $sisa_anggaran_modal;
			$data_bsn_induk_sub[]['realisasi_belanja_perjalanan']= $realisasi_perjalanan['realisasi'];
			$data_bsn_induk_sub[]['sisa_anggaran_perjalanan']= $sisa_anggaran_perjalanan;
			$data_bsn_induk_sub[]['total_realisasi_jenis_belanja']= $total_realisasi_jenis_belanja;
			$data_bsn_induk_sub[]['persentase_total_realisasi_jenis_belanja']= $persentase_realisasi_jenis_belanja;
			$data_bsn_induk_sub[]['sisa_anggaran_total_realisasi_jenis_belanja']= $sisa_anggaran_jenis_belanja;
			// pr($data_bsn_induk_sub);
			// exit;
			//unit eselon II
			$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd($thn_temp,$select_kd_satker['KDSATKER']);
			// pr($select_kegiatan);
			foreach ($select_kegiatan as $k=>$val) {
				$list_kegiatan[] = $val;
				// $nama_unit= $this->m_penetapanAngaran->nm_unit($val['kdunitkerja']);
				$nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($val['KDGIAT']);
				// $list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
				$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
				$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'];
				
				//belanja pegawai
				$anggaran_belanja_menteri_pegawai_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				
				//add
				$realisasi_anggaran_belanja_menteri_pegawai_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,51);
				if($anggaran_belanja_menteri_pegawai_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_pegawai_giat = $anggaran_belanja_menteri_pegawai_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_pegawai_giat = 0;
				}
				
				//belanja barang
				$anggaran_belanja_menteri_barang_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_barang_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,52);
				if($anggaran_belanja_menteri_barang_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_barang_giat = $anggaran_belanja_menteri_barang_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_barang_giat = 0;
				}
				//barang modal
				$anggaran_belanja_menteri_modal_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_modal_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,53);
				if($anggaran_belanja_menteri_modal_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_modal_giat = $anggaran_belanja_menteri_modal_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_modal_giat = 0;
				}
				//belanja perjalanan
				$anggaran_belanja_menteri_perjalanan_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_perjalanan_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$trwln,524);
				if($anggaran_belanja_menteri_perjalanan_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_perjalanan_giat = $anggaran_belanja_menteri_perjalanan_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_perjalanan_giat = 0;
				}
				
				$realisasi_anggaran_belanja_menteri_giat_total = $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				$sisa_anggaran_belanja_menteri_giat_total = $val['pagu_giat'] - $realisasi_anggaran_belanja_menteri_giat_total;
				$persentase_anggaran_belanja_menteri_giat_total = round(($realisasi_anggaran_belanja_menteri_giat_total / $val['pagu_giat']) * 100,2);
				
				$list_kegiatan[$k]['pagu_giat_pegawai']= $anggaran_belanja_menteri_pegawai_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_pegawai']= $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_pegawai_giat']= $sisa_anggaran_belanja_menteri_pegawai_giat;
				
				$list_kegiatan[$k]['pagu_giat_barang']= $anggaran_belanja_menteri_barang_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_barang']= $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_barang_giat']= $sisa_anggaran_belanja_menteri_barang_giat;
				
				$list_kegiatan[$k]['pagu_giat_modal']= $anggaran_belanja_menteri_modal_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_modal']= $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_modal_giat']= $sisa_anggaran_belanja_menteri_modal_giat;
				
				$list_kegiatan[$k]['pagu_giat_perjalanan']= $anggaran_belanja_menteri_perjalanan_giat['pagu_satker'];
				$list_kegiatan[$k]['realisasi_giat_perjalanan']= $realisasi_anggaran_belanja_menteri_perjalanan_giat['realisasi'];
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_perjalanan_giat']= $sisa_anggaran_belanja_menteri_perjalanan_giat;
				
				$list_kegiatan[$k]['realisasi_anggaran_belanja_menteri_giat_total']= $realisasi_anggaran_belanja_menteri_giat_total;
				$list_kegiatan[$k]['persentase_anggaran_belanja_menteri_giat_total']= $persentase_anggaran_belanja_menteri_giat_total;
				$list_kegiatan[$k]['sisa_anggaran_belanja_menteri_giat_total']= $sisa_anggaran_belanja_menteri_giat_total;
				
				$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				foreach ($select_output as $vprb=>$valprb){
					$list_kegiatan[$k]['output'][$vprb]=$valprb;
					$nama_output= $this->m_penetapanAngaran->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
					
					//belanja pegawai
					$anggaran_belanja_menteri_pegawai_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_pegawai_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,51);
					if($anggaran_belanja_menteri_pegawai_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_pegawai_output = $anggaran_belanja_menteri_pegawai_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_pegawai_output = 0;
					}
					
					//belanja barang
					$anggaran_belanja_menteri_barang_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_barang_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,52);
					if($anggaran_belanja_menteri_barang_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_barang_output = $anggaran_belanja_menteri_barang_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_barang_output = 0;
					}
					
					//belanja modal
					$anggaran_belanja_menteri_modal_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_modal_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,53);
					if($anggaran_belanja_menteri_modal_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_modal_output = $anggaran_belanja_menteri_modal_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_modal_output = 0;
					}
					//belanja perjalanan
					$anggaran_belanja_menteri_perjalanan_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_perjalanan_output =$this->m_pelaporankeuangan->realisasi_output_general_trwln($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$trwln,524);
					if($anggaran_belanja_menteri_perjalanan_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_perjalanan_output = $anggaran_belanja_menteri_perjalanan_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_perjalanan_output = 0;
					}
					
					$realisasi_anggaran_belanja_menteri_output_total = $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_barang_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_modal_output['realisasi'] +
																 $realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
																 
					$sisa_anggaran_belanja_menteri_output_total = $valprb['pagu_output'] - $realisasi_anggaran_belanja_menteri_output_total;
					$persentase_anggaran_belanja_menteri_output_total = round(($realisasi_anggaran_belanja_menteri_output_total / $valprb['pagu_output']) * 100,2);
				
					
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_pegawai']=$anggaran_belanja_menteri_pegawai_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_pegawai']=$realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_pegawai']=$sisa_anggaran_belanja_menteri_pegawai_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_barang']=$anggaran_belanja_menteri_barang_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_barang']=$realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_barang']=$sisa_anggaran_belanja_menteri_barang_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_modal']=$anggaran_belanja_menteri_modal_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_modal']=$realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_modal']=$sisa_anggaran_belanja_menteri_modal_output;
					
					$list_kegiatan[$k]['output'][$vprb]['pagu_output_perjalanan']=$anggaran_belanja_menteri_perjalanan_output['pagu_satker'];
					$list_kegiatan[$k]['output'][$vprb]['realisasi_pagu_output_perjalanan']=$realisasi_anggaran_belanja_menteri_perjalanan_output['realisasi'];
					$list_kegiatan[$k]['output'][$vprb]['sisa_pagu_output_perjalanan']=$sisa_anggaran_belanja_menteri_perjalanan_output;
					
					$list_kegiatan[$k]['output'][$vprb]['realisasi_anggaran_belanja_menteri_output_total']=$realisasi_anggaran_belanja_menteri_output_total;
					$list_kegiatan[$k]['output'][$vprb]['persentase_anggaran_belanja_menteri_output_total']=$persentase_anggaran_belanja_menteri_output_total;
					$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_belanja_menteri_output_total']=$sisa_anggaran_belanja_menteri_output_total;
				}	
			}
		}
		
		// pr($dataselected);
		// exit;
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master_induk_sub',$data_bsn_induk_sub);
		$this->view->assign('data_master_induk_sub_sub',$list_kegiatan);
		
		// pr($data_bsn_induk);
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranJenisBelanja');
	
	}

	public function anggaranAkunBsn(){
		$bl =date('m');
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
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		// $trwln = 2;
		// $trwln = $trwulan;
		
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		// pr($thn_temp);
		//core
		if($_POST['kdtriwulan'] != ''){
			$trwln = $_POST['kdtriwulan'];
			if($trwln == 1){
				$I = "selected";
				$II = "";
				$III = "";
				$IV = "";
				$ket = "Triwulan I";
				
			}elseif($trwln == 2){
				$I = "";
				$II = "selected";
				$III = "";
				$IV = "";
				$ket = "Triwulan II";
			}elseif($trwln == 3){
				$I = "";
				$II = "";
				$III = "selected";
				$IV = "";
				$ket = "Triwulan III";
			}elseif($trwln == 4){
				$I = "";
				$II = "";
				$III = "";
				$IV = "selected";
				$ket = "Triwulan IV";
			}
			// pr($trwln);
			$dataselected[]=$I;
			$dataselected[]=$II;
			$dataselected[]=$III;
			$dataselected[]=$IV;
			$dataselected[]=$ket;
			
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$induk_bsn['bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$induk_bsn['bsn']['persentase'] = $persentase_pagu_bsn;
				$induk_bsn['bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			}
			
			$kode_akun = $this->m_pelaporankeuangan->kode_akun($thn_temp);
			$list_kode_akun = array();
			if($kode_akun){
				foreach ($kode_akun as $data_akun=>$val_kdakun){
					$list_kode_akun[] = $val_kdakun;
					$nama_akun = $this->m_pelaporankeuangan->nama_akun($val_kdakun['KDAKUN']);
					$list_kode_akun[$data_akun]['nama'] =  $nama_akun['NMAKUN'];
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		}else{
			if($trwulan == 1){
				$I = "selected";
				$II = "";
				$III = "";
				$IV = "";
				$ket = "Triwulan I";
			}elseif($trwulan == 2){
				$I = "";
				$II = "selected";
				$III = "";
				$IV = "";
				$ket = "Triwulan II";
			}elseif($trwulan == 3){
				$I = "";
				$II = "";
				$III = "selected";
				$IV = "";
				$ket = "Triwulan III";
			}elseif($trwulan == 4){
				$I = "";
				$II = "";
				$III = "";
				$IV = "selected";
				$ket = "Triwulan IV";
			}
			
			$dataselected[]=$I;
			$dataselected[]=$II;
			$dataselected[]=$III;
			$dataselected[]=$IV;
			$dataselected[]=$ket;
			
			// $trwln = 2;
			$trwln = $trwulan;
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$induk_bsn['bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$induk_bsn['bsn']['persentase'] = $persentase_pagu_bsn;
				$induk_bsn['bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			}
			
			$kode_akun = $this->m_pelaporankeuangan->kode_akun($thn_temp);
			$list_kode_akun = array();
			if($kode_akun){
				foreach ($kode_akun as $data_akun=>$val_kdakun){
					$list_kode_akun[] = $val_kdakun;
					$nama_akun = $this->m_pelaporankeuangan->nama_akun($val_kdakun['KDAKUN']);
					$list_kode_akun[$data_akun]['nama'] =  $nama_akun['NMAKUN'];
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		
		}
		
		
		
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('unit',$induk_bsn);
		$this->view->assign('list_akun',$list_kode_akun);
		
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranAkunBsn');
	}
	
	public function anggaranAkunSatker(){
	
		$bl =date('m');
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
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		// $trwln = 2;
		// $trwln = $trwulan;
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		// pr($thn_temp);
		//core
		if($_POST['kdtriwulan'] != ''){
			$trwln = $_POST['kdtriwulan'];
			if($trwln == 1){
				$I = "selected";
				$II = "";
				$III = "";
				$IV = "";
				$ket = "Triwulan I";
				
			}elseif($trwln == 2){
				$I = "";
				$II = "selected";
				$III = "";
				$IV = "";
				$ket = "Triwulan II";
			}elseif($trwln == 3){
				$I = "";
				$II = "";
				$III = "selected";
				$IV = "";
				$ket = "Triwulan III";
			}elseif($trwln == 4){
				$I = "";
				$II = "";
				$III = "";
				$IV = "selected";
				$ket = "Triwulan IV";
			}
			// pr($trwln);
			$dataselected[]=$I;
			$dataselected[]=$II;
			$dataselected[]=$III;
			$dataselected[]=$IV;
			$dataselected[]=$ket;
			//bsn 084 unit
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$induk_bsn['bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$induk_bsn['bsn']['persentase'] = $persentase_pagu_bsn;
				$induk_bsn['bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			}
			//bsn 613104 satker
			$select_kd_satker = $this->m_penetapanAngaran->select_data_bsn($thn_temp);
			if($select_kd_satker){
				$select_nama_satker = $this->m_penetapanAngaran->select_nama($select_kd_satker['KDSATKER']);
				$bsn_induk_sub['sub_bsn']['kode']= $select_kd_satker['KDSATKER'];
				$bsn_induk_sub['sub_bsn']['nama']= $select_nama_satker['NMSATKER'];
				$bsn_induk_sub['sub_bsn']['pagu']= $select_kd_satker['pagu_satker'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$bsn_induk_sub['sub_bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$bsn_induk_sub['sub_bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$bsn_induk_sub['sub_bsn']['persentase'] = $persentase_pagu_bsn;
				$bsn_induk_sub['sub_bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			
			}
			//kode akun
			$kode_akun = $this->m_pelaporankeuangan->kode_akun($thn_temp);
			$list_kode_akun = array();
			if($kode_akun){
				foreach ($kode_akun as $data_akun=>$val_kdakun){
					$list_kode_akun[] = $val_kdakun;
					$nama_akun = $this->m_pelaporankeuangan->nama_akun($val_kdakun['KDAKUN']);
					$list_kode_akun[$data_akun]['nama'] =  $nama_akun['NMAKUN'];
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		}else{
			if($trwulan == 1){
				$I = "selected";
				$II = "";
				$III = "";
				$IV = "";
				$ket = "Triwulan I";
			}elseif($trwulan == 2){
				$I = "";
				$II = "selected";
				$III = "";
				$IV = "";
				$ket = "Triwulan II";
			}elseif($trwulan == 3){
				$I = "";
				$II = "";
				$III = "selected";
				$IV = "";
				$ket = "Triwulan III";
			}elseif($trwulan == 4){
				$I = "";
				$II = "";
				$III = "";
				$IV = "selected";
				$ket = "Triwulan IV";
			}
			
			$dataselected[]=$I;
			$dataselected[]=$II;
			$dataselected[]=$III;
			$dataselected[]=$IV;
			$dataselected[]=$ket;
			
			//bsn 084 unit
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$induk_bsn['bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$induk_bsn['bsn']['persentase'] = $persentase_pagu_bsn;
				$induk_bsn['bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			}
			//bsn 613104 satker
			$select_kd_satker = $this->m_penetapanAngaran->select_data_bsn($thn_temp);
			if($select_kd_satker){
				$select_nama_satker = $this->m_penetapanAngaran->select_nama($select_kd_satker['KDSATKER']);
				$bsn_induk_sub['sub_bsn']['kode']= $select_kd_satker['KDSATKER'];
				$bsn_induk_sub['sub_bsn']['nama']= $select_nama_satker['NMSATKER'];
				$bsn_induk_sub['sub_bsn']['pagu']= $select_kd_satker['pagu_satker'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,1);
				$bsn_induk_sub['sub_bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_trwln_ini($thn_temp,$trwln,2);
				$bsn_induk_sub['sub_bsn']['pagu_sdbulan'] = $pagu_bsn_sd_bulan_ini['pagu_bulan'];
				$persentase_pagu_bsn = round(($pagu_bsn_sd_bulan_ini['pagu_bulan'] / $pagu_menteri['pagu_menteri'] ) * 100,2);
				$bsn_induk_sub['sub_bsn']['persentase'] = $persentase_pagu_bsn;
				$bsn_induk_sub['sub_bsn']['sisa_anggaran'] = $pagu_menteri['pagu_menteri'] - $pagu_bsn_sd_bulan_ini['pagu_bulan'];
			
			}
			//kode akun
			$kode_akun = $this->m_pelaporankeuangan->kode_akun($thn_temp);
			$list_kode_akun = array();
			if($kode_akun){
				foreach ($kode_akun as $data_akun=>$val_kdakun){
					$list_kode_akun[] = $val_kdakun;
					$nama_akun = $this->m_pelaporankeuangan->nama_akun($val_kdakun['KDAKUN']);
					$list_kode_akun[$data_akun]['nama'] =  $nama_akun['NMAKUN'];
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun_trwln($thn_temp,$val_kdakun['KDAKUN'],$trwln,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		
		}
		
		
		// pr($induk_bsn);
		// pr($bsn_induk_sub);
		// exit;
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('unit',$induk_bsn);
		$this->view->assign('satker',$bsn_induk_sub);
		$this->view->assign('list_akun',$list_kode_akun);
		
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranAkunSatker');
	}
	
	public function anggaranAkunGiat(){
	
	$bl =date('m');
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
	
	// $thn_temp = '2013';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	// pr($thn_temp);
	if($_POST['kdtriwulan'] != ''){
		$trwln = $_POST['kdtriwulan'];
		if($trwln == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			
		}elseif($trwln == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwln == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwln == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		// pr($trwln);
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		// $renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$trwln);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_trwln_BSN($thn_temp,$trwln);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		/*if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}*/
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdbulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdbulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdbulan_BSN;
		$data_bsn_induk[]= $real_menteri_bulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdbulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		// $renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_menteri_trwln_BSN($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		/*if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}*/
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdbulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdbulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdbulan;
		$data_bsn_induk_sub[]= $real_satker_bulan;
		$data_bsn_induk_sub[]= $real_satker_sdbulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		
		// pr($data_bsn_induk_sub);
		// exit;
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			// $renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			/*if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}*/
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdbulan['jml'] / ($val['pagu_giat'] /2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdbulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdbulan']= $renc_giat_sdbulan['rencana'];
			$list_kegiatan[$k]['real_giat_bulan']= $real_giat_bulan['jml'];
			$list_kegiatan[$k]['real_giat_sdbulan']= $real_giat_sdbulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			// $select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$select_pagu_akun_giat= $this->m_pelaporankeuangan->kode_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_pagu_akun_giat as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_akun = $this->m_pelaporankeuangan->nama_akun($valprb['KDAKUN']);
				$pagu_akun_giat_bulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat_triwulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$trwln,1);
				$pagu_akun_giat_sdbulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat_triwulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$trwln,2);
				$persentase_akun_giat = round(($pagu_akun_giat_sdbulan_ini['pagu_bulan'] / $valprb['pagu_akun'])* 100,2);
				$sisa_anggaran_pagu_akun_giat = $valprb['pagu_akun'] - $pagu_akun_giat_sdbulan_ini['pagu_bulan'];
				
				$list_kegiatan[$k]['output'][$vprb]['nama_akun']=$nama_akun['NMAKUN'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_akun_giat_bulan_ini']=$pagu_akun_giat_bulan_ini['pagu_bulan'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_akun_giat_sdbulan_ini']=$pagu_akun_giat_sdbulan_ini['pagu_bulan'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_akun_giat']=$persentase_akun_giat;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_pagu_akun_giat']=$sisa_anggaran_pagu_akun_giat;
				
			}
			
		}
	}else{
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// $trwln = 2;
		$trwln = $trwulan;
		
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		// $renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$trwln);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_trwln_BSN($thn_temp,$trwln);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		/*if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}*/
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdbulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdbulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdbulan_BSN;
		$data_bsn_induk[]= $real_menteri_bulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdbulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		// $renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_menteri_trwln_BSN($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		/*if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}*/
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdbulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdbulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdbulan;
		$data_bsn_induk_sub[]= $real_satker_bulan;
		$data_bsn_induk_sub[]= $real_satker_sdbulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		
		// pr($data_bsn_induk_sub);
		// exit;
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			// $renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			/*if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}*/
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdbulan['jml'] / ($val['pagu_giat'] /2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdbulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdbulan']= $renc_giat_sdbulan['rencana'];
			$list_kegiatan[$k]['real_giat_bulan']= $real_giat_bulan['jml'];
			$list_kegiatan[$k]['real_giat_sdbulan']= $real_giat_sdbulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			// $select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$select_pagu_akun_giat= $this->m_pelaporankeuangan->kode_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_pagu_akun_giat as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_akun = $this->m_pelaporankeuangan->nama_akun($valprb['KDAKUN']);
			
				$pagu_akun_giat_bulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat_triwulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$trwln,1);
				$pagu_akun_giat_sdbulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat_triwulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$trwln,2);
					
				$persentase_akun_giat = round(($pagu_akun_giat_sdbulan_ini['pagu_bulan'] / $valprb['pagu_akun'])* 100,2);
				$sisa_anggaran_pagu_akun_giat = $valprb['pagu_akun'] - $pagu_akun_giat_sdbulan_ini['pagu_bulan'];
				
				$list_kegiatan[$k]['output'][$vprb]['nama_akun']=$nama_akun['NMAKUN'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_akun_giat_bulan_ini']=$pagu_akun_giat_bulan_ini['pagu_bulan'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_akun_giat_sdbulan_ini']=$pagu_akun_giat_sdbulan_ini['pagu_bulan'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_akun_giat']=$persentase_akun_giat;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_pagu_akun_giat']=$sisa_anggaran_pagu_akun_giat;
				
			}
			
		}
	}
		
	
		// pr($list_kegiatan);
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		
		// pr($data_bsn_induk);
		// pr($data_bsn_induk_sub);
		// pr($list_kegiatan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranAkunGiat');
	}

	public function anggaranOutput(){
		//pr($_POST);
	
	$bl =date('m');
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
	

	// $thn_temp = '2013';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	// pr($thn_temp);
	if($_POST['kdtriwulan'] != ''){
		
		$trwln = $_POST['kdtriwulan'];
		if($trwln == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
			
		}elseif($trwln == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwln == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwln == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		// pr($trwln);
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// pr($dataselected);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		// echo"masuk";
		// exit;
		$real_menteri_triwulan_BSN = $this->m_pelaporankeuangan->real_menteri_triwulan_BSN($thn_temp,$trwln);
		$real_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		if($renc_menteri_sdtriwulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdtriwulan_BSN['jml'] / $renc_menteri_sdtriwulan_BSN['rencana'])*100,2);
		
		}
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdtriwulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdtriwulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]= $real_menteri_triwulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		// exit;
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		$renc_satker_sdtriwulan = $this->m_pelaporankeuangan->renc_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_triwulan = $this->m_pelaporankeuangan->real_satker_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdtriwulan = $this->m_pelaporankeuangan->real_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdtriwulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdtriwulan['jml'] / $renc_satker_sdtriwulan['rencana'])*100,2);
		
		}
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdtriwulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdtriwulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdtriwulan;
		$data_bsn_induk_sub[]= $real_satker_triwulan;
		$data_bsn_induk_sub[]= $real_satker_sdtriwulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		// pr($data_bsn_induk_sub);
		
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			$renc_giat_sdtriwulan = $this->m_pelaporankeuangan->renc_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_triwulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdtriwulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdtriwulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $renc_giat_sdtriwulan['rencana'])*100,2);
			}
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / ($val['pagu_giat']/2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdtriwulan']= $renc_giat_sdtriwulan['rencana'];
			$list_kegiatan[$k]['real_giat_triwulan']= $real_giat_triwulan['jml'];
			$list_kegiatan[$k]['real_giat_sdtriwulan']= $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_triwulan= $this->m_pelaporankeuangan->real_output_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdtriwulan= $this->m_pelaporankeuangan->real_output_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdtriwulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdtriwulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_triwulan']=$real_output_triwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdtriwulan']=$real_output_sdtriwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}else{
		if($trwulan == 1){
			$I = "selected";
			$II = "";
			$III = "";
			$IV = "";
			$ket = "Triwulan I";
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
			$ket = "Triwulan II";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
			$ket = "Triwulan III";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
			$ket = "Triwulan IV";
		}
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		$dataselected[]=$ket;
		
		// $trwln = 2;
		$trwln = $trwulan;
		// pr($trwln);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		$real_menteri_triwulan_BSN = $this->m_pelaporankeuangan->real_menteri_triwulan_BSN($thn_temp,$trwln);
		$real_menteri_sdtriwulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdtriwulan_BSN($thn_temp,$trwln);
		
		if($renc_menteri_sdtriwulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdtriwulan_BSN['jml'] / $renc_menteri_sdtriwulan_BSN['rencana'])*100,2);
		
		}
		
		if($select_data_master_bsn['pagu_menteri'] == 0)
		{
			$persentase_thn_pagu = 0;
		}else{
			$persentase_thn_pagu  = round(($real_menteri_sdtriwulan_BSN['jml'] / $select_data_master_bsn['pagu_menteri'])*100,2);
		}
		
		$sisa_anggaran  = $select_data_master_bsn['pagu_menteri'] - $real_menteri_sdtriwulan_BSN['jml'];
		
		$data_bsn_induk[]['kode']= '084';
		$data_bsn_induk[]= $select_data_master_bsn;
		$data_bsn_induk[]= $Select_nama_BSN;
		$data_bsn_induk[]= $renc_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]= $real_menteri_triwulan_BSN;
		$data_bsn_induk[]= $real_menteri_sdtriwulan_BSN;
		$data_bsn_induk[]['persentase_rncan_penarikan']= $persentase_thd_Rencana_Penarikan;
		$data_bsn_induk[]['persentase_thn_pagu']= $persentase_thn_pagu;
		$data_bsn_induk[]['sisa_anggaran']= $sisa_anggaran;
		// pr($data_bsn_induk);
		// exit;
		//613104-BSN
		$select_kd_satker = $this->m_pelaporankeuangan->select_data_bsn($thn_temp);
		$select_nama_satker = $this->m_pelaporankeuangan->select_nama($select_kd_satker['KDSATKER']);
		$renc_satker_sdtriwulan = $this->m_pelaporankeuangan->renc_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_triwulan = $this->m_pelaporankeuangan->real_satker_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		$real_satker_sdtriwulan = $this->m_pelaporankeuangan->real_satker_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdtriwulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdtriwulan['jml'] / $renc_satker_sdtriwulan['rencana'])*100,2);
		
		}
		
		if($select_kd_satker['pagu_satker'] == 0)
		{
			$persentase_thn_pagu_satker = 0;
		}else{
			$persentase_thn_pagu_satker  = round(($real_satker_sdtriwulan['jml'] / $select_kd_satker['pagu_satker'])*100,2);
		}
		
		$sisa_anggaran_satker  = $select_kd_satker['pagu_satker'] - $real_satker_sdtriwulan['jml'];
		
		$data_bsn_induk_sub[]['kode']= $select_kd_satker['KDSATKER'];
		$data_bsn_induk_sub[]['pagu_satker']= $select_kd_satker['pagu_satker'];
		$data_bsn_induk_sub[]= $select_nama_satker;
		$data_bsn_induk_sub[]= $renc_satker_sdtriwulan;
		$data_bsn_induk_sub[]= $real_satker_triwulan;
		$data_bsn_induk_sub[]= $real_satker_sdtriwulan;
		$data_bsn_induk_sub[]['persentase_rncan_penarikan_satker']= $persentase_thd_Rencana_Penarikan_satker;
		$data_bsn_induk_sub[]['persentase_thn_pagu_satker']= $persentase_thn_pagu_satker;
		$data_bsn_induk_sub[]['sisa_anggaran_satker']= $sisa_anggaran_satker;
		
		// pr($data_bsn_induk_sub);
		
		//unit eselon II
		$select_kegiatan= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_kd_satker['KDSATKER']);
		foreach ($select_kegiatan as $k=>$val) {
			$list_kegiatan[] = $val;
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			$renc_giat_sdtriwulan = $this->m_pelaporankeuangan->renc_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_triwulan = $this->m_pelaporankeuangan->real_giat_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdtriwulan = $this->m_pelaporankeuangan->real_giat_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdtriwulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $renc_giat_sdtriwulan['rencana'])*100,2);
			}
			
			if($val['pagu_giat'] == 0){
				$persentase_thn_pagu_satker_kegiatan = 0;
			}else{
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / ($val['pagu_giat'] /2))*100,2);
			}
			
			$sisa_anggaran_kegiatan = ($val['pagu_giat'] /2) - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['renc_giat_sdtriwulan']= $renc_giat_sdtriwulan['rencana'];
			$list_kegiatan[$k]['real_giat_triwulan']= $real_giat_triwulan['jml'];
			$list_kegiatan[$k]['real_giat_sdtriwulan']= $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['persentase_thd_Rencana_Penarikan_kegiatan']= $persentase_thd_Rencana_Penarikan_kegiatan;
			$list_kegiatan[$k]['persentase_thn_pagu_satker_kegiatan']= $persentase_thn_pagu_satker_kegiatan;
			$list_kegiatan[$k]['sisa_anggaran_kegiatan']= $sisa_anggaran_kegiatan;
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_triwulan= $this->m_pelaporankeuangan->real_output_triwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdtriwulan= $this->m_pelaporankeuangan->real_output_sdtriwulan($thn_temp,$trwln,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdtriwulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdtriwulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_triwulan']=$real_output_triwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdtriwulan']=$real_output_sdtriwulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
		// pr($list_kegiatan);
	}
		// pr($dataselected);
		// pr($list_kegiatan);
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranOutput');
	
	}
	
}
?>
