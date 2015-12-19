<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class pelaporanKeuangan extends Controller {
	
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
	
	public function index(){
		//realisasi Keuangan
		//select mster data induk Badan Standarisasi Nasional(kode,nama,realisasi)
		// $thn_temp = '2013';
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		
		$select_data_master_bsn = $this->m_pelaporankeuangan->select_data_master_bsn($thn_temp);
		// pr($select_data_master_bsn);
		//select pagu total
		$select_data_pagu_master_bsn =$this->m_pelaporankeuangan->select_data_pagu_master_bsn($thn_temp,$select_data_master_bsn['KDSATKER']);
		$select_nama = $this->m_pelaporankeuangan->select_nama($select_data_master_bsn['KDSATKER']);
		if($select_data_pagu_master_bsn['pagu_satker'] == 0){
			$select_persentase = 0; 
		}else{
			$select_persentase = round(($select_data_master_bsn['real_satker'] / $select_data_pagu_master_bsn['pagu_satker'])*100,2); 
		}
		$select_data_master_bsn_fix[]=$select_data_master_bsn;
		foreach ($select_data_master_bsn_fix  as $s=>$ms)
		{
			$select_data_master_bsn_fix[0]['pagu_total'] = $select_data_pagu_master_bsn['pagu_satker'];
			$select_data_master_bsn_fix[0]['nama_satker'] = $select_nama['NMSATKER'];
			$select_data_master_bsn_fix[0]['persentase'] = $select_persentase;
		}
		// pr($select_data_master_bsn_fix);
		
		//cek data upload 
		$cek_data_upload = $this->m_pelaporankeuangan->cek_data_upload();
		// pr($cek_data_upload);
		
		//cek realisasi Badan Standarisasi Nasional (084)
		$cek_realisasi= $this->m_pelaporankeuangan->cek_realisasi($thn_temp);
		
		//cek pagu Badan Standarisasi Nasional (084)
		$cek_pagu= $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		
		//cek kegiatan 
		$cek_kegiatan_group= $this->m_pelaporankeuangan->cek_kegiatan_group($thn_temp,$select_data_master_bsn['KDSATKER']);
		// pr($cek_kegiatan_group);
		foreach ($cek_kegiatan_group as $k=>$val) {
			// pr($val);
			$list_kegiatan[] = $val;
			// pr($list_kegiatan);
			$pagu_giat_group= $this->m_pelaporankeuangan->pagu_giat_group($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			if($pagu_giat_group['pagu_giat'] == 0){
				$persentase = 0;
			}else{
				$persentase = round(($val['real_giat'] / $pagu_giat_group['pagu_giat'])*100,2);
			}
			
			$list_kegiatan[$k]['pagu_giat']= $pagu_giat_group['pagu_giat'];
			$list_kegiatan[$k]['persentase']= $persentase;
			$list_kegiatan[$k]['nama_giat']= $nama_kegiatan['nmgiat'];
			
			$pagutotal_kode_output_kegiatan= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			// pr($pagutotal_kode_output_kegiatan);
			foreach ($pagutotal_kode_output_kegiatan as $v=>$value)
			{
				$list_kegiatan[$k]['output'][$v]=$value;
				$pagurealisasi_output_kegiatan= $this->m_pelaporankeuangan->pagurealisasi_output_kegiatan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$value['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$v]['pagutotal']=$pagurealisasi_output_kegiatan['pagu_output'];
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$value['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$v]['namaoutput']=$nama_output['NMOUTPUT'];
				$persentase_output = round(($value['real_output'] / $pagurealisasi_output_kegiatan['pagu_output'])*100,2);
				
				$list_kegiatan[$k]['output'][$v]['realisasioutput']=$persentase_output;
				
			}
			
			
		}
		// pr($list_kegiatan);
		//data master
		$this->view->assign('data_master',$select_data_master_bsn_fix);
		$this->view->assign('data_master_sub',$list_kegiatan);
		// pr($list_kegiatan);
		// pr($list_kegiatan);
		
		return $this->loadView('pelaporanKeuangan/realisasiKeuangan');

	}

	public function realisasiBulanan(){
		//realisasi Bulanan
		// $thn_temp = '2013';
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		$max_bulan = '12';
		$monthArray =array('1','2','3','4','5','6','7','8','9','10','11','12');
		//KODE BSN
		//start====084(BSN)=============
		$kode_BSN = "840000";
		
		$select_data_master_induk_bsn = $this->m_pelaporankeuangan->select_data_master_bsn($thn_temp);
		$select_data_pagu_master_induk_bsn =$this->m_pelaporankeuangan->select_data_pagu_master_bsn($thn_temp,$select_data_master_induk_bsn['KDSATKER']);
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		if($select_data_pagu_master_induk_bsn['pagu_satker'] == 0){
			$select_persentase_induk = 0;
		}else{
			$select_persentase_induk = round(($select_data_master_induk_bsn['real_satker'] / $select_data_pagu_master_induk_bsn['pagu_satker'])*100,2); 
		}
		$select_data_master_bsn_fix_induk[]=$select_data_master_induk_bsn;
		foreach ($select_data_master_bsn_fix_induk  as $s=>$ms)
		{
			$select_data_master_bsn_fix_induk[0]['pagu_total'] = $select_data_pagu_master_induk_bsn['pagu_satker'];
			$select_data_master_bsn_fix_induk[0]['nama_satker'] = $Select_nama_BSN['nmunit'];
			$select_data_master_bsn_fix_induk[0]['persentase'] = $select_persentase_induk;
		}
		//start add new array
		$realisasi_perbulan = $this->m_pelaporankeuangan->realisasi_perbulan_unit($thn_temp,$monthArray); 
		//realisasi januari sampe desember
 		foreach ($realisasi_perbulan  as $rbv)
		{
			$select_data_master_bsn_fix_induk[0]['realisasi_bulan'] = $rbv;
		}
		
		// $ex='2015';
		$penarikan_unit_perbulan = $this->m_pelaporankeuangan->penarikan_unit_perbulan($thn_temp);
		//penarikan januari sampe desember
		foreach ($penarikan_unit_perbulan  as $prb=>$prbv)
		{
			$select_data_master_bsn_fix_induk[0]['penarikan_bulan'] = $penarikan_unit_perbulan;
		}
		
		$select_all_bulan_unit = $this->m_pelaporankeuangan->realisasi_allbulan_unit($thn_temp,$max_bulan);
		$select_data_master_bsn_fix_induk[0]['total_realisasi'] = $select_all_bulan_unit['jml'];
		$sisa_anggaran = $select_data_master_bsn_fix_induk[0]['pagu_total'] - $select_data_master_bsn_fix_induk[0]['total_realisasi'];
		$select_data_master_bsn_fix_induk[0]['sisa_anggaran'] = $sisa_anggaran;
		//end add new array
		// pr($select_data_master_bsn_fix_induk);
		//end====084(BSN)=============
		
		
		//start==613104===============
		$select_data_master_bsn = $this->m_pelaporankeuangan->select_data_master_bsn($thn_temp);
		// pr($select_data_master_bsn);
		//select pagu total
		$select_data_pagu_master_bsn =$this->m_pelaporankeuangan->select_data_pagu_master_bsn($thn_temp,$select_data_master_bsn['KDSATKER']);
		$select_nama = $this->m_pelaporankeuangan->select_nama($select_data_master_bsn['KDSATKER']);
		if($select_data_pagu_master_bsn['pagu_satker'] == 0){
			$select_persentase = 0;
		}else{
			$select_persentase = round(($select_data_master_bsn['real_satker'] / $select_data_pagu_master_bsn['pagu_satker'])*100,2); 
		}
		$select_data_master_bsn_fix[]=$select_data_master_bsn;
		foreach ($select_data_master_bsn_fix  as $s=>$ms)
		{
			$select_data_master_bsn_fix[0]['pagu_total'] = $select_data_pagu_master_bsn['pagu_satker'];
			$select_data_master_bsn_fix[0]['nama_satker'] = $select_nama['NMSATKER'];
			$select_data_master_bsn_fix[0]['persentase'] = $select_persentase;
		}
		$select_data_master_bsn_fix[0]['realisasi_bulan'] = $select_data_master_bsn_fix_induk[0]['realisasi_bulan'];
		$select_data_master_bsn_fix[0]['penarikan_bulan'] = $select_data_master_bsn_fix_induk[0]['penarikan_bulan'];
		$select_data_master_bsn_fix[0]['total_realisasi'] = $select_data_master_bsn_fix_induk[0]['total_realisasi'];
		$select_data_master_bsn_fix[0]['sisa_anggaran'] = $select_data_master_bsn_fix_induk[0]['sisa_anggaran'];
			
		// pr($select_data_master_bsn_fix);
		//end==613104===============
		
		
		//start Unit Eselon II
		//cek kegiatan 
		$cek_kegiatan_group= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_data_master_bsn['KDSATKER']);
		// pr($cek_kegiatan_group);
		foreach ($cek_kegiatan_group as $k=>$val) {
			// pr($val);
			$list_kegiatan[] = $val;
			// pr($list_kegiatan);
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			$realisasi_perbulan_kegiatan= $this->m_pelaporankeuangan->realisasi_perbulan_unit_kegiatan($thn_temp,$monthArray,$val['KDGIAT'],$select_data_master_bsn['KDSATKER']);
			$list_kegiatan[$k]['nama_giat']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'];
			
			foreach ($realisasi_perbulan_kegiatan  as $rkbv)
			{
				$list_kegiatan[$k]['realisasi_bulan'] = $rkbv;
			}
			$penarikan_unit_perbulan_kegiatan = $this->m_pelaporankeuangan->penarikan_unit_perbulan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			foreach ($penarikan_unit_perbulan_kegiatan  as $pkrb=>$pkrbv)
			{
				$list_kegiatan[$k]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan;
			}
			$select_all_bulan_unit_kegiatan = $this->m_pelaporankeuangan->realisasi_allbulan_unit_kegiatan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			$list_kegiatan[$k]['total_realisasi'] = $select_all_bulan_unit_kegiatan['jml'];
			if($val['pagu_giat'] == 0){
				$persentase = 0;
			}else{
				$persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $val['pagu_giat'])*100,2);
			}
			$list_kegiatan[$k]['persentase']= $persentase;
			$sisa_anggaran_kegiatan = $val['pagu_giat'] - $select_all_bulan_unit_kegiatan['jml'];
			$list_kegiatan[$k]['sisa_anggaran'] = $sisa_anggaran_kegiatan;
			// $list_kegiatan[$k]['persentase']= 0;
			
			
			$pagutotal_kode_output_kegiatan_perbulan= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			// pr($pagutotal_kode_output_kegiatan_perbulan);
			foreach ($pagutotal_kode_output_kegiatan_perbulan as $vprb=>$valprb)
			{
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$penarikan_unit_perbulan_kegiatan_perbulan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_kegiatan_perbulan($thn_temp,$monthArray,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				foreach ($penarikan_unit_perbulan_kegiatan_perbulan  as $pukrb=>$upukrbv)
				{
					$list_kegiatan[$k]['output'][$vprb]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan_perbulan;
				}
				$select_all_bulan_unit_kegiatan_ouput_perbulan = $this->m_pelaporankeuangan->select_all_bulan_unit_kegiatan_ouput_perbulan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['total_realisasi'] = $select_all_bulan_unit_kegiatan_ouput_perbulan['jml'];
				if($valprb['pagu_output'] == 0){
					$persentase_output = 0;
				}else{
					$persentase_output = round(($select_all_bulan_unit_kegiatan_ouput_perbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$list_kegiatan[$k]['output'][$vprb]['persentase']=$persentase_output;
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $select_all_bulan_unit_kegiatan_ouput_perbulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
			
		}
		//end Unit Eselon II
		$this->view->assign('data_master_induk',$select_data_master_bsn_fix_induk);
		$this->view->assign('data_master',$select_data_master_bsn_fix);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		// pr($select_data_master_bsn_fix_induk);
		// pr($select_data_master_bsn_fix);
		// pr($list_kegiatan);
		return $this->loadView('pelaporanKeuangan/laporanBulanan/realisasiBulanan');
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
		}elseif($trwln == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
		}elseif($trwln == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
		}elseif($trwln == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
		}
		// pr($trwln);
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		
		
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
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $val['pagu_giat'])*100,2);
			}
			
			$sisa_anggaran_kegiatan = $val['pagu_giat'] - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'];
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
		}elseif($trwulan == 2){
			$I = "";
			$II = "selected";
			$III = "";
			$IV = "";
		}elseif($trwulan == 3){
			$I = "";
			$II = "";
			$III = "selected";
			$IV = "";
		}elseif($trwulan == 4){
			$I = "";
			$II = "";
			$III = "";
			$IV = "selected";
		}
		
		$dataselected[]=$I;
		$dataselected[]=$II;
		$dataselected[]=$III;
		$dataselected[]=$IV;
		
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
				$persentase_thn_pagu_satker_kegiatan  = round(($real_giat_sdtriwulan['jml'] / $val['pagu_giat'])*100,2);
			}
			
			$sisa_anggaran_kegiatan = $val['pagu_giat'] - $real_giat_sdtriwulan['jml'];
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$list_kegiatan[$k]['nama_kegiatan']= $nama_kegiatan['nmgiat'];
			$list_kegiatan[$k]['pagu_giat']= $val['pagu_giat'];
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
		
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		return $this->loadView('pelaporanKeuangan/laporanTriwulan/anggaranTotal');
		
		
	}

	public function import(){

		$satker = $this->m_pelaporankeuangan->getSatker('1');
		$tahun = $this->m_pelaporankeuangan->getTahunAnggaran();
		$riwayat = $this->m_pelaporankeuangan->getRiwayat();

		$this->view->assign('satker',$satker);
		$this->view->assign('tahunAnggaran',$tahun);
		$this->view->assign('riwayat',$riwayat);

		return $this->loadView('pelaporanKeuangan/importSKKA');
	}
	
	public function detailKegiatan(){
		$kd_satker = $_GET['kd_satker'];
		$thn = $_GET['thn'];
		$kd_giat = $_GET['kd_giat'];
		$pagu = $_GET['pagu'];
		// pr($_GET);
		$select_nama_kegiatan = $this->m_pelaporankeuangan->nama_kegiatan($kd_giat);
		$datakegiatan['kode'] = $kd_giat;
		$datakegiatan['nmgiat'] = $select_nama_kegiatan['nmgiat'];
		$datakegiatan['pagu'] = $pagu;
		
		$select_nama_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan($thn,$kd_satker,$kd_giat);
		foreach ($select_nama_output as $key=>$val){
			$datadetailkegiatan[] = $val;
			$nama_output= $this->m_pelaporankeuangan->nama_output($kd_giat,$val['KDOUTPUT']);
			$datadetailkegiatan[$key]['kodebaru'] = $kd_giat.".".$val['KDOUTPUT'];
			$datadetailkegiatan[$key]['namaoutput'] = $nama_output['NMOUTPUT'];	
			
			$select_spm = $this->m_pelaporankeuangan->select_spm($thn,$kd_satker,$val['KDOUTPUT']);
			foreach ($select_spm as $v=>$value ){
				$datadetailkegiatan[$key]['output'][$v]=$value;
				$expTGSPM = explode('-',$value['TGSPM']);
				$newTGSPM = $expTGSPM[2]."/".$expTGSPM[1]."/".$expTGSPM[0];
				$datadetailkegiatan[$key]['output'][$v]['newTGSPM']=$newTGSPM;
				$expTGSP2D = explode('-',$value['TGSP2D']);
				$newTGSP2D = $expTGSP2D[2]."/".$expTGSP2D[1]."/".$expTGSP2D[0];
				$datadetailkegiatan[$key]['output'][$v]['newTGSP2D']=$newTGSP2D;
				$select_kode_akun = $this->m_pelaporankeuangan->select_kode_akun($thn,$kd_satker,$value['NOSPM'],$value['NOSP2D']);
				$datadetailkegiatan[$key]['output'][$v]['subdetail']=$select_kode_akun;
			}
			
		}
		
		// pr($datakegiatan);
		// pr($datadetailkegiatan);
		$this->view->assign('data_master',$datakegiatan);
		$this->view->assign('data_master_sub',$datadetailkegiatan);
		
		return $this->loadView('pelaporanKeuangan/detailKegiatan');
	}
	
	public function detailKegiatanSub(){
		$kd_satker = $_GET['kd_satker'];
		$thn = $_GET['thn'];
		$kd_giat = $_GET['kd_giat'];
		$kd_ouput = $_GET['kd_ouput'];
		// pr($_GET);
		
		
		$select_nama_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_condtn($thn,$kd_satker,$kd_giat,$kd_ouput);
		foreach ($select_nama_output as $key=>$val){
			$datadetailkegiatan[] = $val;
			$nama_output= $this->m_pelaporankeuangan->nama_output($kd_giat,$val['KDOUTPUT']);
			$datadetailkegiatan[$key]['kodebaru'] = $kd_giat.".".$val['KDOUTPUT'];
			$datadetailkegiatan[$key]['namaoutput'] = $nama_output['NMOUTPUT'];	
			
			$select_spm = $this->m_pelaporankeuangan->select_spm($thn,$kd_satker,$val['KDOUTPUT']);
			foreach ($select_spm as $v=>$value ){
				$datadetailkegiatan[$key]['output'][$v]=$value;
				$expTGSPM = explode('-',$value['TGSPM']);
				$newTGSPM = $expTGSPM[2]."/".$expTGSPM[1]."/".$expTGSPM[0];
				$datadetailkegiatan[$key]['output'][$v]['newTGSPM']=$newTGSPM;
				$expTGSP2D = explode('-',$value['TGSP2D']);
				$newTGSP2D = $expTGSP2D[2]."/".$expTGSP2D[1]."/".$expTGSP2D[0];
				$datadetailkegiatan[$key]['output'][$v]['newTGSP2D']=$newTGSP2D;
				$select_kode_akun = $this->m_pelaporankeuangan->select_kode_akun($thn,$kd_satker,$value['NOSPM'],$value['NOSP2D']);
				$datadetailkegiatan[$key]['output'][$v]['subdetail']=$select_kode_akun;
			}
			
		}
		// pr($datadetailkegiatan);
		$this->view->assign('data_master_sub',$datadetailkegiatan);
		
		return $this->loadView('pelaporanKeuangan/detailKegiatanSub');	
	}

	public function ins_import()
	{
		global $basedomain;
		
		if($_FILES['total']['error'] == 0){

			//delete then replace as year
		    $this->m_pelaporankeuangan->del_peryear('m_spmind',$_POST['thang']);
			$i=0;
			$Test = new Prodigy_DBF($_FILES['total']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['TOTNILMAK'] = $Record['TOTNILMAK'];
		        $data[$i]['TOTNILMAP'] = $Record['TOTNILMAP'];
		        $data[$i]['NOSPM'] = $Record['NOSPM'];
		        $data[$i]['TGSPM'] = $Record['TGSPM'];
		        $data[$i]['NOSP2D'] = $Record['NOSP2D'];
		        $data[$i]['TGSP2D'] = $Record['TGSP2D'];

		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->m_pelaporankeuangan->insert_data($data[$i],'m_spmind');
		        }

		        $i++;
		    }

		    //insert riwayat
		    $riwayat['kdfile'] = 'M_SPMIND';
		    $riwayat['nama_file'] = $_FILES['total']['name'];
		    $riwayat['user_upload'] = $this->admin['id'];
		    $riwayat['type'] = 'sakpa';
		    $riwayat['keterangan'] = 'File Realisasi Total';
		    $riwayat['KDSATKER'] = $_POST['kdsatker'];

		    $this->m_pelaporankeuangan->insert_data($riwayat,'dt_fileupload_keu');

		}

		if($_FILES['akun']['error'] == 0){

			//delete then replace as year
		    $this->m_pelaporankeuangan->del_peryear('m_spmmak',$_POST['thang']);
			unset($data);
			$i=0;
			$Test = new Prodigy_DBF($_FILES['akun']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['NOSPM'] = $Record['NOSPM'];
		        $data[$i]['TGSPM'] = $Record['TGSPM'];
		        $data[$i]['NOSP2D'] = $Record['NOSP2D'];
		        $data[$i]['TGSP2D'] = $Record['TGSP2D'];
		        $data[$i]['KDAKUN'] = $Record['KDAKUN'];
		        $data[$i]['NILMAK'] = intval($Record['NILMAK']);

		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->m_pelaporankeuangan->insert_data($data[$i],'m_spmmak');
		        }

		        $i++;
		    }

		    //insert riwayat
		    $riwayat['kdfile'] = 'M_SPMMAK';
		    $riwayat['nama_file'] = $_FILES['akun']['name'];
		    $riwayat['user_upload'] = $this->admin['id'];
		    $riwayat['type'] = 'sakpa';
		    $riwayat['keterangan'] = 'File Realisasi Akun';
		    $riwayat['KDSATKER'] = $_POST['kdsatker'];

		    $this->m_pelaporankeuangan->insert_data($riwayat,'dt_fileupload_keu');

		}

		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."pelaporanKeuangan/import'</script>";
		exit;

	}
}

class Prodigy_DBF {
    private $Filename, $DB_Type, $DB_Update, $DB_Records, $DB_FirstData, $DB_RecordLength, $DB_Flags, $DB_CodePageMark, $DB_Fields, $FileHandle, $FileOpened;
    private $Memo_Handle, $Memo_Opened, $Memo_BlockSize;

    private function Initialize() {

        if($this->FileOpened) {
            fclose($this->FileHandle);
        }

        if($this->Memo_Opened) {
            fclose($this->Memo_Handle);
        }

        $this->FileOpened = false;
        $this->FileHandle = NULL;
        $this->Filename = NULL;
        $this->DB_Type = NULL;
        $this->DB_Update = NULL;
        $this->DB_Records = NULL;
        $this->DB_FirstData = NULL;
        $this->DB_RecordLength = NULL;
        $this->DB_CodePageMark = NULL;
        $this->DB_Flags = NULL;
        $this->DB_Fields = array();

        $this->Memo_Handle = NULL;
        $this->Memo_Opened = false;
        $this->Memo_BlockSize = NULL;
    }

    public function __construct($Filename, $MemoFilename = NULL) {
        $this->Prodigy_DBF($Filename, $MemoFilename);
    }

    public function Prodigy_DBF($Filename, $MemoFilename = NULL) {
        $this->Initialize();
        $this->OpenDatabase($Filename, $MemoFilename);
    }

    public function OpenDatabase($Filename, $MemoFilename = NULL) {
        $Return = false;
        $this->Initialize();

        $this->FileHandle = fopen($Filename, "r");
        if($this->FileHandle) {
            // DB Open, reading headers
            $this->DB_Type = dechex(ord(fread($this->FileHandle, 1)));
            $LUPD = fread($this->FileHandle, 3);
            $this->DB_Update = ord($LUPD[0])."/".ord($LUPD[1])."/".ord($LUPD[2]);
            $Rec = unpack("V", fread($this->FileHandle, 4));
            $this->DB_Records = $Rec[1];
            $Pos = fread($this->FileHandle, 2);
            $this->DB_FirstData = (ord($Pos[0]) + ord($Pos[1]) * 256);
            $Len = fread($this->FileHandle, 2);
            $this->DB_RecordLength = (ord($Len[0]) + ord($Len[1]) * 256);
            fseek($this->FileHandle, 28); // Ignoring "reserved" bytes, jumping to table flags
            $this->DB_Flags = dechex(ord(fread($this->FileHandle, 1)));
            $this->DB_CodePageMark = ord(fread($this->FileHandle, 1));
            fseek($this->FileHandle, 2, SEEK_CUR);    // Ignoring next 2 "reserved" bytes

            // Now reading field captions and attributes
            while(!feof($this->FileHandle)) {

                // Checking for end of header
                if(ord(fread($this->FileHandle, 1)) == 13) {
                    break;  // End of header!
                } else {
                    // Go back
                    fseek($this->FileHandle, -1, SEEK_CUR);
                }

                $Field["Name"] = trim(fread($this->FileHandle, 11));
                $Field["Type"] = fread($this->FileHandle, 1);
                fseek($this->FileHandle, 4, SEEK_CUR);  // Skipping attribute "displacement"
                $Field["Size"] = ord(fread($this->FileHandle, 1));
                fseek($this->FileHandle, 15, SEEK_CUR); // Skipping any remaining attributes
                $this->DB_Fields[] = $Field;
            }

            // Setting file pointer to the first record
            fseek($this->FileHandle, $this->DB_FirstData);

            $this->FileOpened = true;

            // Open memo file, if exists
            if(!empty($MemoFilename) and file_exists($MemoFilename) and preg_match("%^(.+).fpt$%i", $MemoFilename)) {
                $this->Memo_Handle = fopen($MemoFilename, "r");
                if($this->Memo_Handle) {
                    $this->Memo_Opened = true;

                    // Getting block size
                    fseek($this->Memo_Handle, 6);
                    $Data = unpack("n", fread($this->Memo_Handle, 2));
                    $this->Memo_BlockSize = $Data[1];
                }
            }
        }

        return $Return;
    }

    public function GetNextRecord($FieldCaptions = false) {
        $Return = NULL;
        $Record = array();

        if(!$this->FileOpened) {
            $Return = false;
        } elseif(feof($this->FileHandle)) {
            $Return = NULL;
        } else {
            // File open and not EOF
            fseek($this->FileHandle, 1, SEEK_CUR);  // Ignoring DELETE flag
            foreach($this->DB_Fields as $Field) {
                $RawData = fread($this->FileHandle, $Field["Size"]);
                // Checking for memo reference
                if($Field["Type"] == "M" and $Field["Size"] == 4 and !empty($RawData)) {
                    // Binary Memo reference
                    $Memo_BO = unpack("V", $RawData);
                    if($this->Memo_Opened and $Memo_BO != 0) {
                        fseek($this->Memo_Handle, $Memo_BO[1] * $this->Memo_BlockSize);
                        $Type = unpack("N", fread($this->Memo_Handle, 4));
                        if($Type[1] == "1") {
                            $Len = unpack("N", fread($this->Memo_Handle, 4));
                            $Value = trim(fread($this->Memo_Handle, $Len[1]));
                        } else {
                            // Pictures will not be shown
                            $Value = "{BINARY_PICTURE}";
                        }
                    } else {
                        $Value = "{NO_MEMO_FILE_OPEN}";
                    }
                } else {
                    $Value = trim($RawData);
                }

                if($FieldCaptions) {
                    $Record[$Field["Name"]] = $Value;
                } else {
                    $Record[] = $Value;
                }
            }

            $Return = $Record;
        }

        return $Return;
    }

    function __destruct() {
        // Cleanly close any open files before destruction
        $this->Initialize();
    }
}
?>
