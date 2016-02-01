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
		// echo "masuk";
		//realisasi Keuangan
		//select mster data induk Badan Standarisasi Nasional(kode,nama,realisasi)
		// $thn_temp = '2013';
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		
		$select_data_master_bsn = $this->m_pelaporankeuangan->select_data_master_bsn($thn_temp);
		// pr($select_data_master_bsn);
		//manipulasi sementara
		// $select_data_master_bsn['THANG'] = $thn_temp;
		// $select_data_master_bsn['KDSATKER'] = '613104';
		// $select_pagu_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// $select_data_master_bsn['real_satker'] = $select_pagu_bsn['pagu_menteri'];
		// pr($select_data_master_bsn);
		//manipulasi
		
		//select pagu total
		$select_data_pagu_master_bsn =$this->m_pelaporankeuangan->select_data_pagu_master_bsn($thn_temp,$select_data_master_bsn['KDSATKER']);
		// pr($select_data_pagu_master_bsn);
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
		// pr($select_data_master_induk_bsn);
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
		// exit;
		// end====084(BSN)=============
		
		
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
		// end==613104===============
		// exit;
		
		//start Unit Eselon II
		//cek kegiatan 
		$cek_kegiatan_group= $this->m_pelaporankeuangan->cek_kegiatan_group_realisasi($thn_temp,$select_data_master_bsn['KDSATKER']);
		// pr($cek_kegiatan_group);
		// exit;
		foreach ($cek_kegiatan_group as $k=>$val) {
			// pr($val);
			$list_kegiatan[] = $val;
			// pr($list_kegiatan);
			$nama_unit= $this->m_pelaporankeuangan->nm_unit($val['kdunitkerja']);
			$list_kegiatan[$k]['nama_unit']= $nama_unit['nmunit'];
			$nama_kegiatan= $this->m_pelaporankeuangan->nama_kegiatan($val['KDGIAT']);
			
			$realisasi_perbulan_kegiatan= $this->m_pelaporankeuangan->realisasi_perbulan_unit_kegiatan($thn_temp,$monthArray,$val['KDGIAT'],$select_data_master_bsn['KDSATKER']);
			// pr($realisasi_perbulan_kegiatan);
			// exit;
			$list_kegiatan[$k]['nama_giat']= $nama_kegiatan['nmgiat'];
			/*echo "<pre>";
			echo "pagu giat=".$val['pagu_giat'];
			echo "</pre>";
			echo "hasil pagu = ".$val['pagu_giat'] / 2;*/
			$pagu_real = $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['pagu_giat']= $pagu_real;
			// $count_array = 0 ;
			
			foreach ($realisasi_perbulan_kegiatan  as $rkbv)
			{
				$list_kegiatan[$k]['realisasi_bulan'] = $rkbv;
				
			}
		
			$penarikan_unit_perbulan_kegiatan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_kegiatan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			foreach ($penarikan_unit_perbulan_kegiatan  as $pkrb=>$pkrbv)
			{
				$list_kegiatan[$k]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan;
			}
			$select_all_bulan_unit_kegiatan = $this->m_pelaporankeuangan->realisasi_allbulan_unit_kegiatan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			$list_kegiatan[$k]['total_realisasi'] = $select_all_bulan_unit_kegiatan['jml'];
			// if($val['pagu_giat'] == 0){
			if($pagu_real == 0){
				$persentase = 0;
			}else{
				// $persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $val['pagu_giat'])*100,2);
				$persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $pagu_real)*100,2);
			}
			$list_kegiatan[$k]['persentase']= $persentase;
			// $sisa_anggaran_kegiatan = $val['pagu_giat'] - $select_all_bulan_unit_kegiatan['jml'];
			$sisa_anggaran_kegiatan = $pagu_real - $select_all_bulan_unit_kegiatan['jml'];
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
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/realisasiBulanan');
	}
	
	//new
	public function realisasisdBulanan(){
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
		$realisasi_perbulan = $this->m_pelaporankeuangan->realisasi_sdbulan_unit($thn_temp,$monthArray); 
		// pr($realisasi_perbulan);
		// exit;
		//realisasi januari sampe desember
 		foreach ($realisasi_perbulan  as $rbv)
		{
			$select_data_master_bsn_fix_induk[0]['realisasi_bulan'] = $rbv;
		}
		
		foreach ($realisasi_perbulan  as $prkbv)
		{
			foreach ($prkbv as $prst_prkbv){
				if($prst_prkbv != 0){
					$temp_rest = round(($prst_prkbv / $select_data_pagu_master_induk_bsn['pagu_satker']) * 100,2);
				}else{
					$temp_rest = 0;
				}
				$persentase_realisasi_bulan_induk[] = $temp_rest;
			}
		}
		$select_data_master_bsn_fix_induk[0]['persentase_realisasi_bulan'] = $persentase_realisasi_bulan_induk;
		
		
		// $ex='2015';
		$penarikan_unit_perbulan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_2($thn_temp);
		// pr($penarikan_unit_perbulan);
		//penarikan januari sampe desember
		foreach ($penarikan_unit_perbulan  as $prb=>$prbv)
		{
			$select_data_master_bsn_fix_induk[0]['penarikan_bulan'] = $penarikan_unit_perbulan;
		}
		
		$select_all_bulan_unit = $this->m_pelaporankeuangan->realisasi_allbulan_unit($thn_temp,$max_bulan);
		$select_data_master_bsn_fix_induk[0]['total_realisasi'] = $select_all_bulan_unit['jml'];
		$sisa_anggaran = $select_data_master_bsn_fix_induk[0]['pagu_total'] - $select_data_master_bsn_fix_induk[0]['total_realisasi'];
		$select_data_master_bsn_fix_induk[0]['sisa_anggaran'] = $sisa_anggaran;
		if($sisa_anggaran != 0){
			$select_data_master_bsn_fix_induk[0]['persentase_sisa_anggaran'] = round(($sisa_anggaran / $select_data_pagu_master_induk_bsn['pagu_satker']) * 100,2);
		}else{
			$select_data_master_bsn_fix_induk[0]['persentase_sisa_anggaran'] = 0;
		}
		//end add new array
		// pr($select_data_master_bsn_fix_induk);
		//end====084(BSN)=============
		// exit;
		
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
		$select_data_master_bsn_fix[0]['persentase_sisa_anggaran'] = $select_data_master_bsn_fix_induk[0]['persentase_sisa_anggaran'];
			
		// pr($select_data_master_bsn_fix);
		// exit;
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
			
			$realisasi_perbulan_kegiatan= $this->m_pelaporankeuangan->realisasi_sdbulan_unit_kegiatan($thn_temp,$monthArray,$val['KDGIAT'],$select_data_master_bsn['KDSATKER']);
			// pr($realisasi_perbulan_kegiatan);
			// exit;
			$list_kegiatan[$k]['nama_giat']= $nama_kegiatan['nmgiat'];
			/*echo "<pre>";
			echo "pagu giat=".$val['pagu_giat'];
			echo "</pre>";
			echo "hasil pagu = ".$val['pagu_giat'] / 2;*/
			$pagu_real = $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['pagu_giat']= $pagu_real;
			// $count_array = 0 ;
			
			foreach ($realisasi_perbulan_kegiatan  as $rkbv)
			{
				$list_kegiatan[$k]['realisasi_bulan'] = $rkbv;
				
			}
			$persentase_realisasi_bulan = array();
			foreach ($realisasi_perbulan_kegiatan  as $prkbv)
			{
				foreach ($prkbv as $prst_prkbv){
					$temp_rest = round(($prst_prkbv / $pagu_real) * 100,2);
					$persentase_realisasi_bulan[] = $temp_rest;
				}
			}
			$list_kegiatan[$k]['persentase_realisasi_bulan'] = $persentase_realisasi_bulan;
			// pr($list_kegiatan);
			// exit;
			$penarikan_unit_perbulan_kegiatan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_kegiatan_2($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			// pr($penarikan_unit_perbulan_kegiatan);
			// exit;
			foreach ($penarikan_unit_perbulan_kegiatan  as $pkrb=>$pkrbv)
			{
				$list_kegiatan[$k]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan;
			}
			// exit;
			$select_all_bulan_unit_kegiatan = $this->m_pelaporankeuangan->realisasi_allbulan_unit_kegiatan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			$list_kegiatan[$k]['total_realisasi'] = $select_all_bulan_unit_kegiatan['jml'];
			// if($val['pagu_giat'] == 0){
			if($pagu_real == 0){
				$persentase = 0;
			}else{
				// $persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $val['pagu_giat'])*100,2);
				$persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $pagu_real)*100,2);
			}
			$list_kegiatan[$k]['persentase']= $persentase;
			// $sisa_anggaran_kegiatan = $val['pagu_giat'] - $select_all_bulan_unit_kegiatan['jml'];
			$sisa_anggaran_kegiatan = $pagu_real - $select_all_bulan_unit_kegiatan['jml'];
			$list_kegiatan[$k]['sisa_anggaran'] = $sisa_anggaran_kegiatan;
			$list_kegiatan[$k]['persentase_sisa_anggaran'] = round(($sisa_anggaran_kegiatan / $pagu_real)*100,2);
			
			// $list_kegiatan[$k]['persentase']= 0;
			
			
			$pagutotal_kode_output_kegiatan_perbulan= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			// pr($pagutotal_kode_output_kegiatan_perbulan);
			foreach ($pagutotal_kode_output_kegiatan_perbulan as $vprb=>$valprb)
			{
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$penarikan_unit_perbulan_kegiatan_perbulan = $this->m_pelaporankeuangan->penarikan_unit_sdbulan_kegiatan_perbulan($thn_temp,$monthArray,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				foreach ($penarikan_unit_perbulan_kegiatan_perbulan  as $pukrb=>$upukrbv)
				{
					$list_kegiatan[$k]['output'][$vprb]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan_perbulan;
				}
				$persentase_realisasi_bulan_out = array();
				foreach ($penarikan_unit_perbulan_kegiatan_perbulan  as $realsdout)
				{
					foreach ($realsdout as $real_out){
						$temp_rest_out = round(($real_out / $valprb['pagu_output']) * 100,2);
						$persentase_realisasi_bulan_out[] = $temp_rest_out;
					}
				}
				
				$list_kegiatan[$k]['output'][$vprb]['persentase_realisasi_sd_bulan'] = $persentase_realisasi_bulan_out;
				
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
				$list_kegiatan[$k]['output'][$vprb]['persentase_sisa_anggaran_kegiatan_output']= round(($sisa_anggaran_kegiatan_output / $valprb['pagu_output'])*100,2);
			}
			
			
		}
		//end Unit Eselon II
		$this->view->assign('data_master_induk',$select_data_master_bsn_fix_induk);
		$this->view->assign('data_master',$select_data_master_bsn_fix);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		// pr($select_data_master_bsn_fix_induk);
		// pr($select_data_master_bsn_fix);
		// pr($list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/realisasisdBulanan');
	}
	
	public function realisasisdBulananRp(){
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
			$select_data_master_bsn_fix_induk[0]['pagu_total'] = round($select_data_pagu_master_induk_bsn['pagu_satker'] / 1000000,0);
			// $select_data_master_bsn_fix_induk[0]['pagu_total'] = $select_data_pagu_master_induk_bsn['pagu_satker'];
			$select_data_master_bsn_fix_induk[0]['nama_satker'] = $Select_nama_BSN['nmunit'];
			$select_data_master_bsn_fix_induk[0]['persentase'] = $select_persentase_induk;
		}
		//start add new array
		$realisasi_perbulan = $this->m_pelaporankeuangan->realisasi_sdbulan_unit($thn_temp,$monthArray,1); 
		// pr($realisasi_perbulan);
		// exit;
		//realisasi januari sampe desember
 		foreach ($realisasi_perbulan  as $rbv)
		{
			$select_data_master_bsn_fix_induk[0]['realisasi_bulan'] = $rbv;
		}
	
		// $ex='2015';
		$penarikan_unit_perbulan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_2($thn_temp,1);
		// pr($penarikan_unit_perbulan);
		//penarikan januari sampe desember
		foreach ($penarikan_unit_perbulan  as $prb=>$prbv)
		{
			$select_data_master_bsn_fix_induk[0]['penarikan_bulan'] = $penarikan_unit_perbulan;
		}
		
		$select_all_bulan_unit = $this->m_pelaporankeuangan->realisasi_allbulan_unit($thn_temp,$max_bulan);
		$select_data_master_bsn_fix_induk[0]['total_realisasi'] = round($select_all_bulan_unit['jml'] / 1000000,0);
		// $select_data_master_bsn_fix_induk[0]['total_realisasi'] = $select_all_bulan_unit['jml'];
		$sisa_anggaran = $select_data_master_bsn_fix_induk[0]['pagu_total'] - $select_data_master_bsn_fix_induk[0]['total_realisasi'];
		$select_data_master_bsn_fix_induk[0]['sisa_anggaran'] = round($sisa_anggaran,0);
		// $select_data_master_bsn_fix_induk[0]['sisa_anggaran'] = $sisa_anggaran;
		// $select_data_master_bsn_fix_induk[0]['persentase_sisa_anggaran'] = round(($sisa_anggaran / $select_data_pagu_master_induk_bsn['pagu_satker']) * 100,2);
		
		//end add new array
		// pr($select_data_master_bsn_fix_induk);
		//end====084(BSN)=============
		// exit;
		
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
			$select_data_master_bsn_fix[0]['pagu_total'] = round($select_data_pagu_master_bsn['pagu_satker'] / 1000000 ,0);
			$select_data_master_bsn_fix[0]['nama_satker'] = $select_nama['NMSATKER'];
			$select_data_master_bsn_fix[0]['persentase'] = $select_persentase;
		}
		$select_data_master_bsn_fix[0]['realisasi_bulan'] = $select_data_master_bsn_fix_induk[0]['realisasi_bulan'];
		$select_data_master_bsn_fix[0]['penarikan_bulan'] = $select_data_master_bsn_fix_induk[0]['penarikan_bulan'];
		$select_data_master_bsn_fix[0]['total_realisasi'] = $select_data_master_bsn_fix_induk[0]['total_realisasi'];
		$select_data_master_bsn_fix[0]['sisa_anggaran'] = $select_data_master_bsn_fix_induk[0]['sisa_anggaran'];
		// $select_data_master_bsn_fix[0]['persentase_sisa_anggaran'] = $select_data_master_bsn_fix_induk[0]['persentase_sisa_anggaran'];
			
		// pr($select_data_master_bsn_fix);
		// exit;
		// end==613104===============
		
		
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
			
			$realisasi_perbulan_kegiatan= $this->m_pelaporankeuangan->realisasi_sdbulan_unit_kegiatan($thn_temp,$monthArray,$val['KDGIAT'],$select_data_master_bsn['KDSATKER'],1);
			// pr($realisasi_perbulan_kegiatan);
			// exit;
			$list_kegiatan[$k]['nama_giat']= $nama_kegiatan['nmgiat'];
			$pagu_real = $val['pagu_giat'] / 2;
			$list_kegiatan[$k]['pagu_giat']= round($pagu_real / 1000000,0);
			// $count_array = 0 ;
			
			foreach ($realisasi_perbulan_kegiatan  as $rkbv)
			{
				$list_kegiatan[$k]['realisasi_bulan'] = $rkbv;
				
			}
			$persentase_realisasi_bulan = array();
			/*foreach ($realisasi_perbulan_kegiatan  as $prkbv)
			{
				foreach ($prkbv as $prst_prkbv){
					$temp_rest = round(($prst_prkbv / $pagu_real) * 100,2);
					$persentase_realisasi_bulan[] = $temp_rest;
				}
			}
			$list_kegiatan[$k]['persentase_realisasi_bulan'] = $persentase_realisasi_bulan;*/
			// pr($list_kegiatan);
			// exit;
			$penarikan_unit_perbulan_kegiatan = $this->m_pelaporankeuangan->penarikan_unit_perbulan_kegiatan_2($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],1);
			// pr($penarikan_unit_perbulan_kegiatan);
			// exit;
			foreach ($penarikan_unit_perbulan_kegiatan  as $pkrb=>$pkrbv)
			{
				$list_kegiatan[$k]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan;
			}
			// exit;
			$select_all_bulan_unit_kegiatan = $this->m_pelaporankeuangan->realisasi_allbulan_unit_kegiatan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT']);
			$list_kegiatan[$k]['total_realisasi'] = round($select_all_bulan_unit_kegiatan['jml'] / 1000000,0);
			// if($val['pagu_giat'] == 0){
			if($pagu_real == 0){
				$persentase = 0;
			}else{
				// $persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $val['pagu_giat'])*100,2);
				$persentase = round(($select_all_bulan_unit_kegiatan['jml'] / $pagu_real)*100,2);
			}
			$list_kegiatan[$k]['persentase']= $persentase;
			// $sisa_anggaran_kegiatan = $val['pagu_giat'] - $select_all_bulan_unit_kegiatan['jml'];
			$sisa_anggaran_kegiatan = $pagu_real - $select_all_bulan_unit_kegiatan['jml'];
			$list_kegiatan[$k]['sisa_anggaran'] = round($sisa_anggaran_kegiatan / 1000000,0);
			// $list_kegiatan[$k]['persentase_sisa_anggaran'] = round(($sisa_anggaran_kegiatan / $pagu_real)*100,2);
			
			// $list_kegiatan[$k]['persentase']= 0;
			
			// pr($list_kegiatan);
			// exit;
			// $pagutotal_kode_output_kegiatan_perbulan= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],1);
			$pagutotal_kode_output_kegiatan_perbulan= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],1);
			// pr($pagutotal_kode_output_kegiatan_perbulan);
			foreach ($pagutotal_kode_output_kegiatan_perbulan as $vprb=>$valprb)
			{
				// pr($valprb);
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$list_kegiatan[$k]['output'][$vprb]['new_pagu_output']=round($valprb['pagu_output'] / 1000000,0);
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$penarikan_unit_perbulan_kegiatan_perbulan = $this->m_pelaporankeuangan->penarikan_unit_sdbulan_kegiatan_perbulan($thn_temp,$monthArray,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],1);
				foreach ($penarikan_unit_perbulan_kegiatan_perbulan  as $pukrb=>$upukrbv)
				{
					$list_kegiatan[$k]['output'][$vprb]['penarikan_bulan'] = $penarikan_unit_perbulan_kegiatan_perbulan;
				}
				$persentase_realisasi_bulan_out = array();
				/*foreach ($penarikan_unit_perbulan_kegiatan_perbulan  as $realsdout)
				{
					foreach ($realsdout as $real_out){
						$temp_rest_out = round(($real_out / $valprb['pagu_output']) * 100,2);
						$persentase_realisasi_bulan_out[] = $temp_rest_out;
					}
				}
				
				$list_kegiatan[$k]['output'][$vprb]['persentase_realisasi_sd_bulan'] = $persentase_realisasi_bulan_out;*/
				
				$select_all_bulan_unit_kegiatan_ouput_perbulan = $this->m_pelaporankeuangan->select_all_bulan_unit_kegiatan_ouput_perbulan($thn_temp,$max_bulan,$select_data_master_bsn['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				// pr($select_all_bulan_unit_kegiatan_ouput_perbulan);
				$list_kegiatan[$k]['output'][$vprb]['total_realisasi'] = round($select_all_bulan_unit_kegiatan_ouput_perbulan['jml'] / 1000000,0);
				if($valprb['pagu_output'] == 0){
					$persentase_output = 0;
				}else{
					// $persentase_output = round(($select_all_bulan_unit_kegiatan_ouput_perbulan['jml'] / $valprb['pagu_output'])*100,2);
					$persentase_output = round(($select_all_bulan_unit_kegiatan_ouput_perbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$list_kegiatan[$k]['output'][$vprb]['persentase']=$persentase_output;
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - ($select_all_bulan_unit_kegiatan_ouput_perbulan['jml']);
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=round($sisa_anggaran_kegiatan_output / 1000000,0);
				// $list_kegiatan[$k]['output'][$vprb]['persentase_sisa_anggaran_kegiatan_output']= round(($sisa_anggaran_kegiatan_output / $valprb['pagu_output'])*100,2);
				
				// pr($list_kegiatan);
				// exit;
			}
			
			
		}
		//end Unit Eselon II
		$this->view->assign('data_master_induk',$select_data_master_bsn_fix_induk);
		$this->view->assign('data_master',$select_data_master_bsn_fix);
		$this->view->assign('data_master_sub',$list_kegiatan);
		
		// pr($select_data_master_bsn_fix_induk);
		// pr($select_data_master_bsn_fix);
		// pr($list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/realisasisdBulananRp');
	}
	
	public function anggaranTotalBulanan(){
	
	if($_POST['bulan'] != ''){
		$bl = $_POST['bulan'];
	}else{
		$bl = date('m');
	}
	
	// $bl = '10';
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
	
	// $thn_temp = '2013';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	if($_POST['bulan'] != ''){
		// pr($_POST['bulan']);
		$bulan = $_POST['bulan'];
		
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_bulan= $this->m_pelaporankeuangan->real_output_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdbulan= $this->m_pelaporankeuangan->real_output_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdbulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_bulan']=$real_output_bulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdbulan']=$real_output_sdbulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}else{
		$bulan = $bl;
		// pr($bulan);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_bulan= $this->m_pelaporankeuangan->real_output_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdbulan= $this->m_pelaporankeuangan->real_output_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdbulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_bulan']=$real_output_bulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdbulan']=$real_output_sdbulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}
		// pr($dataselected);
		// pr($list_kegiatan);
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		// pr($data_bsn_induk);
		// pr($data_bsn_induk_sub);
		// pr($list_kegiatan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranTotalBulanan');
		
	}
	
	public function anggaranJenisBelanja(){
		
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
		}else{
			$bl = date('m');
			// $bl = '02';
		}
		
		// $bl = '10';
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
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
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
			$realisasi_pegawai = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,51);
			$sisa_anggaran_pegawai = $pegawai['pagu_satker'] - $realisasi_pegawai['realisasi'];
			
			$barang = $this->m_penetapanAngaran->anggaran_belanja_menteri_barang($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_barang = round(($barang['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_barang = 0 ;
			}
			//add
			$realisasi_barang = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,52);
			$sisa_anggaran_barang = $barang['pagu_satker'] - $realisasi_barang['realisasi'];
			
			$modal = $this->m_penetapanAngaran->anggaran_belanja_menteri_modal($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_modal = round(($modal['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_modal = 0 ;
			}
			//add
			$realisasi_modal = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,53);
			$sisa_anggaran_modal = $modal['pagu_satker'] - $realisasi_modal['realisasi'];
			
			$perjalanan = $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_perjalanan = round(($perjalanan['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_perjalanan = 0 ;
			}
			
			//add
			$realisasi_perjalanan = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,524);
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
				$realisasi_anggaran_belanja_menteri_pegawai_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,51);
				if($anggaran_belanja_menteri_pegawai_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_pegawai_giat = $anggaran_belanja_menteri_pegawai_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_pegawai_giat = 0;
				}
				
				//belanja barang
				$anggaran_belanja_menteri_barang_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_barang_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,52);
				if($anggaran_belanja_menteri_barang_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_barang_giat = $anggaran_belanja_menteri_barang_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_barang_giat = 0;
				}
				//barang modal
				$anggaran_belanja_menteri_modal_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_modal_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,53);
				if($anggaran_belanja_menteri_modal_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_modal_giat = $anggaran_belanja_menteri_modal_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_modal_giat = 0;
				}
				//belanja perjalanan
				$anggaran_belanja_menteri_perjalanan_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_perjalanan_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,524);
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
					$realisasi_anggaran_belanja_menteri_pegawai_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,51);
					if($anggaran_belanja_menteri_pegawai_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_pegawai_output = $anggaran_belanja_menteri_pegawai_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_pegawai_output = 0;
					}
					
					//belanja barang
					$anggaran_belanja_menteri_barang_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_barang_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,52);
					if($anggaran_belanja_menteri_barang_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_barang_output = $anggaran_belanja_menteri_barang_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_barang_output = 0;
					}
					
					//belanja modal
					$anggaran_belanja_menteri_modal_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_modal_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,53);
					if($anggaran_belanja_menteri_modal_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_modal_output = $anggaran_belanja_menteri_modal_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_modal_output = 0;
					}
					//belanja perjalanan
					$anggaran_belanja_menteri_perjalanan_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_perjalanan_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,524);
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
			$realisasi_pegawai = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,51);
			$sisa_anggaran_pegawai = $pegawai['pagu_satker'] - $realisasi_pegawai['realisasi'];
			
			$barang = $this->m_penetapanAngaran->anggaran_belanja_menteri_barang($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_barang = round(($barang['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_barang = 0 ;
			}
			//add
			$realisasi_barang = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,52);
			$sisa_anggaran_barang = $barang['pagu_satker'] - $realisasi_barang['realisasi'];
			
			$modal = $this->m_penetapanAngaran->anggaran_belanja_menteri_modal($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_modal = round(($modal['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_modal = 0 ;
			}
			//add
			$realisasi_modal = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,53);
			$sisa_anggaran_modal = $modal['pagu_satker'] - $realisasi_modal['realisasi'];
			
			$perjalanan = $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan($thn_temp);
			if($select_data_master_bsn['pagu_menteri'] != 0){
				$p_perjalanan = round(($perjalanan['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100,2) ;
			}else{ 
				$p_perjalanan = 0 ;
			}
			
			//add
			$realisasi_perjalanan = $this->m_pelaporankeuangan->realisasi_general($thn_temp,$bl,524);
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
				$realisasi_anggaran_belanja_menteri_pegawai_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,51);
				if($anggaran_belanja_menteri_pegawai_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_pegawai_giat = $anggaran_belanja_menteri_pegawai_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_pegawai_giat = 0;
				}
				
				//belanja barang
				$anggaran_belanja_menteri_barang_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_barang_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,52);
				if($anggaran_belanja_menteri_barang_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_barang_giat = $anggaran_belanja_menteri_barang_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_barang_giat = 0;
				}
				//barang modal
				$anggaran_belanja_menteri_modal_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_modal_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,53);
				if($anggaran_belanja_menteri_modal_giat['pagu_satker']){
					$sisa_anggaran_belanja_menteri_modal_giat = $anggaran_belanja_menteri_modal_giat['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_giat['realisasi'];
				}else{
					$sisa_anggaran_belanja_menteri_modal_giat = 0;
				}
				//belanja perjalanan
				$anggaran_belanja_menteri_perjalanan_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
				//add
				$realisasi_anggaran_belanja_menteri_perjalanan_giat = $this->m_pelaporankeuangan->realisasi_kegiatan_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$bl,524);
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
					$realisasi_anggaran_belanja_menteri_pegawai_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,51);
					if($anggaran_belanja_menteri_pegawai_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_pegawai_output = $anggaran_belanja_menteri_pegawai_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_pegawai_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_pegawai_output = 0;
					}
					
					//belanja barang
					$anggaran_belanja_menteri_barang_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_barang_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,52);
					if($anggaran_belanja_menteri_barang_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_barang_output = $anggaran_belanja_menteri_barang_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_barang_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_barang_output = 0;
					}
					
					//belanja modal
					$anggaran_belanja_menteri_modal_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_modal_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,53);
					if($anggaran_belanja_menteri_modal_output['pagu_satker']){
						$sisa_anggaran_belanja_menteri_modal_output = $anggaran_belanja_menteri_modal_output['pagu_satker'] - $realisasi_anggaran_belanja_menteri_modal_output['realisasi'];
					}else{
						$sisa_anggaran_belanja_menteri_modal_output = 0;
					}
					//belanja perjalanan
					$anggaran_belanja_menteri_perjalanan_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
					$valprb['KDOUTPUT']);
					//add
					$realisasi_anggaran_belanja_menteri_perjalanan_output =$this->m_pelaporankeuangan->realisasi_output_general($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT'],$bl,524);
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
		
		// pr($list_kegiatan);
		// exit;
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master_induk_sub',$data_bsn_induk_sub);
		$this->view->assign('data_master_induk_sub_sub',$list_kegiatan);
		
		// pr($data_bsn_induk);
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranJenisBelanja');
	
	}
	
	public function anggaranAkunBsn(){
	
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
		}else{
			$bl = date('m');
			// $bl = '02';
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
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		// pr($thn_temp);
		//core
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		}else{
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		
		}
		
		
		
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		$this->view->assign('unit',$induk_bsn);
		$this->view->assign('list_akun',$list_kode_akun);
		
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranAkunBsn');
	}
	
	public function anggaranAkunSatker(){
	
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
		}else{
			$bl = date('m');
			// $bl = '02';
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
	
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2013';
		$thn_temp = $thn_aktif['kode'];
		// pr($thn_temp);
		//core
		if($_POST['bulan'] != ''){
			$bl = $_POST['bulan'];
			//bsn 084 unit
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$bsn_induk_sub['sub_bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,2);
					$list_kode_akun[$data_akun]['pagu_akun_bulan'] =  $pagu_akun_bulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['pagu_akun_sdbulan'] =  $pagu_akun_sdbulan['pagu_bulan'];
					$persentase_pagu_akun = round(($pagu_akun_sdbulan['pagu_bulan'] / $val_kdakun['pagu_akun']) * 100,2);
					$list_kode_akun[$data_akun]['persentase_pagu_akun'] =  $persentase_pagu_akun;
					$sisa_anggaran_pagu_akun = $val_kdakun['pagu_akun'] - $pagu_akun_sdbulan['pagu_bulan'];
					$list_kode_akun[$data_akun]['sisa_anggaran_pagu_akun'] =  $sisa_anggaran_pagu_akun;
				}
			}
		}else{
			//bsn 084 unit
			$pagu_menteri = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
			if($pagu_menteri){
				$induk_bsn['bsn']['kode'] = '084';
				$kode_unit = '840000';
				$nama_unit = $this->m_pelaporankeuangan->nm_unit($kode_unit);
				$induk_bsn['bsn']['nama'] = $nama_unit['nmunit'];
				$induk_bsn['bsn']['pagu'] = $pagu_menteri['pagu_menteri'];
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$induk_bsn['bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
				$pagu_bsn_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,1);
				$bsn_induk_sub['sub_bsn']['pagu_bulan'] = $pagu_bsn_bulan_ini['pagu_bulan'];
				$pagu_bsn_sd_bulan_ini = $this->m_pelaporankeuangan->pagu_bsn_bulan_ini($thn_temp,$bl,2);
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
					$pagu_akun_bulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,1);
					$pagu_akun_sdbulan = $this->m_pelaporankeuangan->pagu_akun($thn_temp,$val_kdakun['KDAKUN'],$bl,2);
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
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		$this->view->assign('unit',$induk_bsn);
		$this->view->assign('satker',$bsn_induk_sub);
		$this->view->assign('list_akun',$list_kode_akun);
		
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranAkunSatker');
	}
	
	public function anggaranAkunGiat(){
		if($_POST['bulan'] != ''){
		$bl = $_POST['bulan'];
	}else{
		$bl = date('m');
	}
	
	// $bl = '10';
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
	
	// $thn_temp = '2013';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	// pr($thn_temp);
	if($_POST['bulan'] != ''){
		// pr($_POST['bulan']);
		$bulan = $_POST['bulan'];
		
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
			
			
			$select_pagu_akun_giat= $this->m_pelaporankeuangan->kode_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_pagu_akun_giat as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_akun = $this->m_pelaporankeuangan->nama_akun($valprb['KDAKUN']);
				$pagu_akun_giat_bulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$bl,1);
				$pagu_akun_giat_sdbulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$bl,2);
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
		$bulan = $bl;
		// pr($bulan);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
				$pagu_akun_giat_bulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$bl,1);
				$pagu_akun_giat_sdbulan_ini = $this->m_pelaporankeuangan->pagu_akun_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDAKUN'],$bl,2);
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
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		// pr($data_bsn_induk);
		// pr($data_bsn_induk_sub);
		// pr($list_kegiatan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranAkunGiat');
	}
	
	public function anggaranOutput(){
		if($_POST['bulan'] != ''){
		$bl = $_POST['bulan'];
	}else{
		$bl = date('m');
	}
	
	// $bl = '10';
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
	
	// $thn_temp = '2013';
	$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	$thn_temp = $thn_aktif['kode'];
	
	if($_POST['bulan'] != ''){
		// pr($_POST['bulan']);
		$bulan = $_POST['bulan'];
		
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_bulan= $this->m_pelaporankeuangan->real_output_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdbulan= $this->m_pelaporankeuangan->real_output_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdbulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_bulan']=$real_output_bulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdbulan']=$real_output_sdbulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}else{
		$bulan = $bl;
		// pr($bulan);
		// exit;
		//084-BSN
		$select_data_master_bsn = $this->m_pelaporankeuangan->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_pelaporankeuangan->nm_unit($kode_BSN);
		$renc_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->renc_menteri_sdbulan_BSN($thn_temp,$bulan);
		$real_menteri_bulan_BSN = $this->m_pelaporankeuangan->real_menteri_bulan_BSN($thn_temp,$bulan);
		$real_menteri_sdbulan_BSN = $this->m_pelaporankeuangan->real_menteri_sdbulan_BSN($thn_temp,$bulan);
		
		if($renc_menteri_sdbulan_BSN['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan  = round(($real_menteri_sdbulan_BSN['jml'] / $renc_menteri_sdbulan_BSN['rencana'])*100,2);
		
		}
		
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
		$renc_satker_sdbulan = $this->m_pelaporankeuangan->renc_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_bulan = $this->m_pelaporankeuangan->real_satker_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		$real_satker_sdbulan = $this->m_pelaporankeuangan->real_satker_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER']);
		
		if($renc_satker_sdbulan['rencana'] == 0)
		{
			$persentase_thd_Rencana_Penarikan_satker  = 0;
		}else{
			$persentase_thd_Rencana_Penarikan_satker  = round(($real_satker_sdbulan['jml'] / $renc_satker_sdbulan['rencana'])*100,2);
		
		}
		
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
			$renc_giat_sdbulan = $this->m_pelaporankeuangan->renc_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_bulan = $this->m_pelaporankeuangan->real_giat_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			$real_giat_sdbulan = $this->m_pelaporankeuangan->real_giat_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			if($renc_giat_sdbulan['rencana'] == 0){
				$persentase_thd_Rencana_Penarikan_kegiatan  = 0;
			}else{
				$persentase_thd_Rencana_Penarikan_kegiatan  = round(($real_giat_sdbulan['jml'] / $renc_giat_sdbulan['rencana'])*100,2);
			}
			
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
			
			
			$select_output= $this->m_pelaporankeuangan->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_pelaporankeuangan->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_bulan= $this->m_pelaporankeuangan->real_output_bulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				$real_output_sdbulan= $this->m_pelaporankeuangan->real_output_sdbulan($thn_temp,$bulan,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				if($valprb['pagu_output'] == 0){
					$persentase_pagu_output = 0;
				}else{
					$persentase_pagu_output  = round(($real_output_sdbulan['jml'] / $valprb['pagu_output'])*100,2);
				}
				$sisa_anggaran_kegiatan_output = $valprb['pagu_output'] - $real_output_sdbulan['jml'];
				
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_bulan']=$real_output_bulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['real_output_sdbulan']=$real_output_sdbulan['jml'];
				$list_kegiatan[$k]['output'][$vprb]['persentase_pagu_output']=$persentase_pagu_output;
				$list_kegiatan[$k]['output'][$vprb]['sisa_anggaran_kegiatan_output']=$sisa_anggaran_kegiatan_output;
			}
			
		}
	}
		// pr($dataselected);
		// pr($list_kegiatan);
		$this->view->assign('dataselected',$dataselected);
		$this->view->assign('tahun',$thn_temp);
		$this->view->assign('bulan',$monthArray);
		$this->view->assign('keybln',$bl);
		$this->view->assign('ketBulan',$ketBulan);
		// pr($data_bsn_induk);
		// pr($data_bsn_induk_sub);
		// pr($list_kegiatan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master',$data_bsn_induk_sub);
		$this->view->assign('data_master_sub',$list_kegiatan);
		// exit;
		return $this->loadView('pelaporanKeuangan/laporanBulanan/anggaranOutput');
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
	

	$thn_temp = '2013';
	// $thn_aktif = $this->m_penetapanAngaran->thn_aktif();
	// $thn_temp = $thn_aktif['kode'];
	
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
