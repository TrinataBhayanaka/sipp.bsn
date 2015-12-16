<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class penetapanAnggaran extends Controller {
	
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
		$this->m_penetapanAngaran = $this->loadModel('m_penetapanAngaran');
		$this->model = $this->loadModel('mptn');
	}
	
	public function penetapananggaran(){
		$thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = '2015';
		$thn_temp = $thn_aktif['kode'];
		
		$select_data_master_bsn = $this->m_penetapanAngaran->cek_pagu($thn_temp);
		// pr($select_data_master_bsn);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_penetapanAngaran->nm_unit($kode_BSN);
		
		$pegawai = $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai($thn_temp);
		if($select_data_master_bsn['pagu_menteri'] != 0){
			$p_pegawai = ($pegawai['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100 ;
		}else{ 
			$p_pegawai = 0 ;
		}
		
		$barang = $this->m_penetapanAngaran->anggaran_belanja_menteri_barang($thn_temp);
		if($select_data_master_bsn['pagu_menteri'] != 0){
			$p_barang = ($barang['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100 ;
		}else{ 
			$p_barang = 0 ;
		}
		
		$modal = $this->m_penetapanAngaran->anggaran_belanja_menteri_modal($thn_temp);
		if($select_data_master_bsn['pagu_menteri'] != 0){
			$p_modal = ($modal['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100 ;
		}else{ 
			$p_modal = 0 ;
		}
		
		$perjalanan = $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan($thn_temp);
		if($select_data_master_bsn['pagu_menteri'] != 0){
			$p_perjalanan = ($perjalanan['pagu_satker']/$select_data_master_bsn['pagu_menteri'])*100 ;
		}else{ 
			$p_perjalanan = 0 ;
		}
		
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
		// pr($data_bsn_induk);
		
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
		// pr($data_bsn_induk_sub);
		
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
			
			$anggaran_belanja_menteri_pegawai_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			$anggaran_belanja_menteri_barang_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			$anggaran_belanja_menteri_modal_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			$anggaran_belanja_menteri_perjalanan_giat= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_giat($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			
			$list_kegiatan[$k]['pagu_giat_pegawai']= $anggaran_belanja_menteri_pegawai_giat['pagu_satker'];
			$list_kegiatan[$k]['pagu_giat_barang']= $anggaran_belanja_menteri_barang_giat['pagu_satker'];
			$list_kegiatan[$k]['pagu_giat_modal']= $anggaran_belanja_menteri_modal_giat['pagu_satker'];
			$list_kegiatan[$k]['pagu_giat_perjalanan']= $anggaran_belanja_menteri_perjalanan_giat['pagu_satker'];
			
			$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT']);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_penetapanAngaran->nama_output($val['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				
				$anggaran_belanja_menteri_pegawai_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_pegawai_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
			
				$anggaran_belanja_menteri_barang_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_barang_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
				$valprb['KDOUTPUT']);
				
				$anggaran_belanja_menteri_modal_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_modal_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],$valprb['KDOUTPUT']);
				
				$anggaran_belanja_menteri_perjalanan_output= $this->m_penetapanAngaran->anggaran_belanja_menteri_perjalanan_output($thn_temp,$select_kd_satker['KDSATKER'],$val['KDGIAT'],
				$valprb['KDOUTPUT']);
				
				$list_kegiatan[$k]['output'][$vprb]['pagu_output_pegawai']=$anggaran_belanja_menteri_pegawai_output['pagu_satker'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_output_barang']=$anggaran_belanja_menteri_barang_output['pagu_satker'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_output_modal']=$anggaran_belanja_menteri_modal_output['pagu_satker'];
				$list_kegiatan[$k]['output'][$vprb]['pagu_output_perjalanan']=$anggaran_belanja_menteri_perjalanan_output['pagu_satker'];
			}
			
		}
		
		// pr($list_kegiatan);
		$this->view->assign('data_master_induk',$data_bsn_induk);
		$this->view->assign('data_master_induk_sub',$data_bsn_induk_sub);
		$this->view->assign('data_master_induk_sub_sub',$list_kegiatan);
		return $this->loadView('penetapantahun/penetapanAnggaran');
	
	}

	public function detail084(){
		$tahun =$_GET['thn'];
		$detail084= $this->m_penetapanAngaran->detail084($tahun);
		$kode_BSN = "840000";
		$Select_nama_BSN = $this->m_penetapanAngaran->nm_unit($kode_BSN);
		foreach ($detail084 as $dtl){
			$up_detail084[] = $dtl;
		}
		$jml = 0;
		for($i=1;$i<=12;$i++){
			$jml = $jml + $up_detail084[$i];
		}
		$up_detail084[] = '084';
		$up_detail084[] = $Select_nama_BSN['nmunit'];
		$up_detail084[] = $jml;
		
		$detail084_jb= $this->m_penetapanAngaran->detail084_jb($tahun);
		foreach ($detail084_jb as $key=>$dtljb){
			$up_detail084_jb[] = $dtljb;
			if($dtljb['JNSBELANJA'] == '51'){
				$ket = "Belanja Pegawai";
			}elseif($dtljb['JNSBELANJA'] == '52'){
				$ket = "Belanja Barang";
			}elseif($dtljb['JNSBELANJA'] == '53'){
				$ket = "Belanja Modal";
			}
			$up_detail084_jb[$key]['KET_JNSBELANJA'] = $ket;
			$up_detail084_jb[$key]['jml'] = $dtljb['JML01'] + $dtljb['JML02'] + $dtljb['JML03'] + $dtljb['JML04'] + $dtljb['JML05'] +
											$dtljb['JML06'] + $dtljb['JML07'] + $dtljb['JML08'] + $dtljb['JML09'] + $dtljb['JML10'] +
											$dtljb['JML11'] + $dtljb['JML12'];
									
		}
		$this->view->assign('detail084',$up_detail084);
		$this->view->assign('detail084_jb',$up_detail084_jb);
		$this->view->assign('tahun',$tahun);
		// pr($up_detail084);
		// pr($up_detail084_jb);
		
		// exit;
		return $this->loadView('penetapantahun/penetapanAnggarandetail084');
	
	}
	
	public function detail613104(){
		$tahun =$_GET['thn'];
		$kodeSatker= $this->m_penetapanAngaran->select_data_bsn($tahun);
		$kode_BSN = $kodeSatker['KDSATKER'];
		$Select_nama_BSN = $this->m_penetapanAngaran->select_nama($kode_BSN);
		$detail613104= $this->m_penetapanAngaran->detail084($tahun);
		foreach ($detail613104 as $dtl){
			$up_detail613104[] = $dtl;
		}
		$jml = 0;
		for($i=1;$i<=12;$i++){
			$jml = $jml + $up_detail613104[$i];
		}
		$up_detail613104[] = $kode_BSN;
		$up_detail613104[] = $Select_nama_BSN['NMSATKER'];
		$up_detail613104[] = $jml;
		
		$detail613104_jb= $this->m_penetapanAngaran->detail084_jb($tahun);
		foreach ($detail613104_jb as $key=>$dtljb){
			$up_detail613104_jb[] = $dtljb;
			if($dtljb['JNSBELANJA'] == '51'){
				$ket = "Belanja Pegawai";
			}elseif($dtljb['JNSBELANJA'] == '52'){
				$ket = "Belanja Barang";
			}elseif($dtljb['JNSBELANJA'] == '53'){
				$ket = "Belanja Modal";
			}
			$up_detail613104_jb[$key]['KET_JNSBELANJA'] = $ket;
			$up_detail613104_jb[$key]['jml'] = $dtljb['JML01'] + $dtljb['JML02'] + $dtljb['JML03'] + $dtljb['JML04'] + $dtljb['JML05'] +
											$dtljb['JML06'] + $dtljb['JML07'] + $dtljb['JML08'] + $dtljb['JML09'] + $dtljb['JML10'] +
											$dtljb['JML11'] + $dtljb['JML12'];
									
		}
		$this->view->assign('detail613104',$up_detail613104);
		$this->view->assign('detail613104_jb',$up_detail613104_jb);
		$this->view->assign('tahun',$tahun);
		// pr($up_detail084);
		// pr($up_detail084_jb);
		
		// exit;
		return $this->loadView('penetapantahun/penetapanAnggarandetail613104');
	
	}
	
	public function detailkegiatanpenarikan(){
		$tahun =$_GET['thn'];
		$kd_satker =$_GET['kd_satker'];
		$kd_giat =$_GET['kd_giat'];
		
		$kode_giat= $kd_giat;
		$nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($kd_giat);
		
		$detailkgtn= $this->m_penetapanAngaran->detailkgtn($tahun,$kd_satker,$kd_giat);
		foreach ($detailkgtn as $dtl){
			$up_detailkgtn[] = $dtl;
		}

		$jml = 0;
		for($i=1;$i<=12;$i++){
			$jml = $jml + $up_detailkgtn[$i];
		}
	
		$up_detailkgtn[] = $kode_giat;
		$up_detailkgtn[] = $nama_kegiatan['nmgiat'];
		$up_detailkgtn[] = $jml;
		
		$detail_jb= $this->m_penetapanAngaran->detail_jb($tahun,$kd_satker,$kd_giat);
		foreach ($detail_jb as $key=>$dtljb){
			$up_detail_jb[] = $dtljb;
			if($dtljb['JNSBELANJA'] == '51'){
				$ket = "Belanja Pegawai";
			}elseif($dtljb['JNSBELANJA'] == '52'){
				$ket = "Belanja Barang";
			}elseif($dtljb['JNSBELANJA'] == '53'){
				$ket = "Belanja Modal";
			}
			$up_detail_jb[$key]['KET_JNSBELANJA'] = $ket;
			$up_detail_jb[$key]['jml'] = $dtljb['JML01'] + $dtljb['JML02'] + $dtljb['JML03'] + $dtljb['JML04'] + $dtljb['JML05'] +
											$dtljb['JML06'] + $dtljb['JML07'] + $dtljb['JML08'] + $dtljb['JML09'] + $dtljb['JML10'] +
											$dtljb['JML11'] + $dtljb['JML12'];
									
		}
		$this->view->assign('detailkgtn',$up_detailkgtn);
		$this->view->assign('detail_jb',$up_detail_jb);
		$this->view->assign('tahun',$tahun);
		// pr($up_detailkgtn);
		// pr($up_detail_jb);
		
		// exit;
		return $this->loadView('penetapantahun/penetapanAnggarandetailkegiatan');
	
	}
	
	public function detaildipakegiatan(){
		$tahun =$_GET['thn'];
		$kd_satker =$_GET['kd_satker'];
		$kd_giat =$_GET['kd_giat'];
		
		//kegiatan
		$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd_sub($tahun,$kd_satker,$kd_giat);
		foreach ($select_kegiatan as $k=>$dtl){
			$list_kegiatan[] = $dtl;
			$nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($dtl['KDGIAT']);
			$list_kegiatan[$k]['nama_giat'] = $nama_kegiatan['nmgiat'];
			
			//output
			$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan($tahun,$kd_satker,$kd_giat);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_penetapanAngaran->nama_output($dtl['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				
				//soutput
				$select_soutput= $this->m_penetapanAngaran->select_soutput($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['soutput'] = $select_soutput;
				foreach($list_kegiatan[$k]['output'][$vprb]['soutput'] as $key=>$kl){
					$nama_soutput= $this->m_penetapanAngaran->nama_soutput($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['nama']=	$nama_soutput['URSOUTPUT'];		
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['kode']=	$valprb['KDOUTPUT'].".".$kl['KDSOUTPUT'];		

					//komponen
					 $select_komponen= $this->m_penetapanAngaran->select_komponen($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT'],
																$kl['KDSOUTPUT']);	
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen']= $select_komponen;	
					foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'] as $ky=>$kmpn){
						$nama_komponen= $this->m_penetapanAngaran->nama_komponen($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['nama']= $nama_komponen['URKMPNEN'];							
						//subkomponen
						$select_komponen_sub= $this->m_penetapanAngaran->select_komponen_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen']= $select_komponen_sub;	
						foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'] as $sky=>$kmpnsb){
							$nama_komponen_sub= $this->m_penetapanAngaran->nama_komponen_sub($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['nama']= 			$nama_komponen_sub['URSKMPNEN'];
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode']= 			$kmpn['KDKMPNEN'].".".$kmpnsb['KDSKMPNEN'];	
							//kode akun
							$select_kode_akun_sub= $this->m_penetapanAngaran->select_kode_akun_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
								$kode_akun_jb = array();
								foreach ($select_kode_akun_sub as $keykompn=>$dtkompn){
									$kode_akun_jb[] = $dtkompn;
									$nm_akun= $this->m_penetapanAngaran->nm_akun($dtkompn['KDAKUN']);
									$kode_akun_jb[$keykompn]['KET_JNSBELANJA'] = $nm_akun['NMAKUN'];
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun']=$kode_akun_jb;
								}	
						}											
					}
				}
			}
		}
		
		// pr($list_kegiatan);	
		// exit;
		$this->view->assign('detailkgtn',$list_kegiatan);
		return $this->loadView('penetapantahun/penetapanAnggarandetaildipa');
	}
	
	public function detailpokkegiatan(){
		$tahun =$_GET['thn'];
		$kd_satker =$_GET['kd_satker'];
		$kd_giat =$_GET['kd_giat'];
		// exit;
		//kegiatan
		$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd_sub($tahun,$kd_satker,$kd_giat);
		foreach ($select_kegiatan as $k=>$dtl){
			$list_kegiatan[] = $dtl;
			$nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($dtl['KDGIAT']);
			$list_kegiatan[$k]['nama_giat'] = $nama_kegiatan['nmgiat'];
			
			//output
			$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan($tahun,$kd_satker,$kd_giat);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_penetapanAngaran->nama_output($dtl['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				
				//soutput
				$select_soutput= $this->m_penetapanAngaran->select_soutput($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['soutput'] = $select_soutput;
				foreach($list_kegiatan[$k]['output'][$vprb]['soutput'] as $key=>$kl){
					$nama_soutput= $this->m_penetapanAngaran->nama_soutput($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['nama']=	$nama_soutput['URSOUTPUT'];		
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['kode']=	$valprb['KDOUTPUT'].".".$kl['KDSOUTPUT'];		

					//komponen
					 $select_komponen= $this->m_penetapanAngaran->select_komponen($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT'],
																$kl['KDSOUTPUT']);	
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen']= $select_komponen;	
					foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'] as $ky=>$kmpn){
						$nama_komponen= $this->m_penetapanAngaran->nama_komponen($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['nama']= $nama_komponen['URKMPNEN'];							
						//subkomponen
						$select_komponen_sub= $this->m_penetapanAngaran->select_komponen_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen']= $select_komponen_sub;	
						foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'] as $sky=>$kmpnsb){
							$nama_komponen_sub= $this->m_penetapanAngaran->nama_komponen_sub($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['nama']= 			$nama_komponen_sub['URSKMPNEN'];
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode']= 			$kmpn['KDKMPNEN'].".".$kmpnsb['KDSKMPNEN'];	
							//kode akun
							$select_kode_akun_sub= $this->m_penetapanAngaran->select_kode_akun_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun']=$select_kode_akun_sub;
								foreach ($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'] as $keykompn=>$dtkompn){
									$nm_akun= $this->m_penetapanAngaran->nm_akun($dtkompn['KDAKUN']);
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'][$keykompn]['KET_JNSBELANJA'] = $nm_akun['NMAKUN'];
									$select_item= $this->m_penetapanAngaran->select_item($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN'],$dtkompn['KDAKUN']);
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'][$keykompn]['sub']=$select_item;
									
								}	
						}											
					}
				}
			}
		}
		
		// pr($list_kegiatan);	
		// exit;
		$this->view->assign('detailkgtn',$list_kegiatan);
		return $this->loadView('penetapantahun/penetapanAnggarandetailpok');
	}
	
	public function detaildipaoutput(){
		$tahun =$_GET['thn'];
		$kd_satker =$_GET['kd_satker'];
		$kd_giat =$_GET['kd_giat'];
		$kd_output =$_GET['kd_output'];
		
		//kegiatan
		$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd_sub($tahun,$kd_satker,$kd_giat);
		foreach ($select_kegiatan as $k=>$dtl){
			//output
			$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan_condtn($tahun,$kd_satker,$kd_giat,$kd_output);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_penetapanAngaran->nama_output($dtl['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				
				//soutput
				$select_soutput= $this->m_penetapanAngaran->select_soutput($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['soutput'] = $select_soutput;
				foreach($list_kegiatan[$k]['output'][$vprb]['soutput'] as $key=>$kl){
					$nama_soutput= $this->m_penetapanAngaran->nama_soutput($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['nama']=	$nama_soutput['URSOUTPUT'];		
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['kode']=	$valprb['KDOUTPUT'].".".$kl['KDSOUTPUT'];		

					//komponen
					 $select_komponen= $this->m_penetapanAngaran->select_komponen($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT'],
																$kl['KDSOUTPUT']);	
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen']= $select_komponen;	
					foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'] as $ky=>$kmpn){
						$nama_komponen= $this->m_penetapanAngaran->nama_komponen($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['nama']= $nama_komponen['URKMPNEN'];							
						//subkomponen
						$select_komponen_sub= $this->m_penetapanAngaran->select_komponen_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen']= $select_komponen_sub;	
						foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'] as $sky=>$kmpnsb){
							$nama_komponen_sub= $this->m_penetapanAngaran->nama_komponen_sub($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['nama']= 			$nama_komponen_sub['URSKMPNEN'];
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode']= 			$kmpn['KDKMPNEN'].".".$kmpnsb['KDSKMPNEN'];	
							//kode akun
							$select_kode_akun_sub= $this->m_penetapanAngaran->select_kode_akun_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
								$kode_akun_jb = array();
								foreach ($select_kode_akun_sub as $keykompn=>$dtkompn){
									$kode_akun_jb[] = $dtkompn;
									$nm_akun= $this->m_penetapanAngaran->nm_akun($dtkompn['KDAKUN']);
									$kode_akun_jb[$keykompn]['KET_JNSBELANJA'] = $nm_akun['NMAKUN'];
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun']=$kode_akun_jb;
								}	
						}											
					}
				}
			}
		}
		
		// pr($list_kegiatan);	
		// exit;
		$this->view->assign('detailkgtn',$list_kegiatan);
		return $this->loadView('penetapantahun/penetapanAnggarandetaildipaoutput');
	}
	
	public function detailpokoutput(){
		$tahun =$_GET['thn'];
		$kd_satker =$_GET['kd_satker'];
		$kd_giat =$_GET['kd_giat'];
		$kd_output =$_GET['kd_output'];
		// pr($_GET);
		// exit;
		//kegiatan
		$select_kegiatan= $this->m_penetapanAngaran->cek_kegiatan_group_scnd_sub($tahun,$kd_satker,$kd_giat);
		foreach ($select_kegiatan as $k=>$dtl){
			// $list_kegiatan[] = $dtl;
			// $nama_kegiatan= $this->m_penetapanAngaran->nama_kegiatan($dtl['KDGIAT']);
			// $list_kegiatan[$k]['nama_giat'] = $nama_kegiatan['nmgiat'];
			
			//output
			$select_output= $this->m_penetapanAngaran->pagutotal_kode_output_kegiatan_perbulan_condtn($tahun,$kd_satker,$kd_giat,$kd_output);
			foreach ($select_output as $vprb=>$valprb){
				$list_kegiatan[$k]['output'][$vprb]=$valprb;
				$nama_output= $this->m_penetapanAngaran->nama_output($dtl['KDGIAT'],$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['namaoutput']=$nama_output['NMOUTPUT'];
				
				//soutput
				$select_soutput= $this->m_penetapanAngaran->select_soutput($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT']);
				$list_kegiatan[$k]['output'][$vprb]['soutput'] = $select_soutput;
				foreach($list_kegiatan[$k]['output'][$vprb]['soutput'] as $key=>$kl){
					$nama_soutput= $this->m_penetapanAngaran->nama_soutput($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT']);
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['nama']=	$nama_soutput['URSOUTPUT'];		
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['kode']=	$valprb['KDOUTPUT'].".".$kl['KDSOUTPUT'];		

					//komponen
					 $select_komponen= $this->m_penetapanAngaran->select_komponen($tahun,$kd_satker,$kd_giat,$valprb['KDOUTPUT'],
																$kl['KDSOUTPUT']);	
					$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen']= $select_komponen;	
					foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'] as $ky=>$kmpn){
						$nama_komponen= $this->m_penetapanAngaran->nama_komponen($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['nama']= $nama_komponen['URKMPNEN'];							
						//subkomponen
						$select_komponen_sub= $this->m_penetapanAngaran->select_komponen_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN']);
						$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen']= $select_komponen_sub;	
						foreach($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'] as $sky=>$kmpnsb){
							$nama_komponen_sub= $this->m_penetapanAngaran->nama_komponen_sub($tahun,$dtl['KDGIAT'],$valprb['KDOUTPUT'],
												$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['nama']= 			$nama_komponen_sub['URSKMPNEN'];
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode']= 			$kmpn['KDKMPNEN'].".".$kmpnsb['KDSKMPNEN'];	
							//kode akun
							$select_kode_akun_sub= $this->m_penetapanAngaran->select_kode_akun_sub($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN']);
							$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun']=$select_kode_akun_sub;
								foreach ($list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'] as $keykompn=>$dtkompn){
									$nm_akun= $this->m_penetapanAngaran->nm_akun($dtkompn['KDAKUN']);
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'][$keykompn]['KET_JNSBELANJA'] = $nm_akun['NMAKUN'];
									$select_item= $this->m_penetapanAngaran->select_item($tahun,$kd_satker,$kd_giat,
														$valprb['KDOUTPUT'],$kl['KDSOUTPUT'],$kmpn['KDKMPNEN'],$kmpnsb['KDSKMPNEN'],$dtkompn['KDAKUN']);
									$list_kegiatan[$k]['output'][$vprb]['soutput'][$key]['komponen'][$ky]['subkomponen'][$sky]['kode_akun'][$keykompn]['sub']=$select_item;
									
								}	
						}											
					}
				}
			}
		}
		
		// pr($list_kegiatan);	
		// exit;
		$this->view->assign('detailkgtn',$list_kegiatan);
		return $this->loadView('penetapantahun/penetapanAnggarandetailpokoutput');
	}

	public function import()
	{
		$tahun = $this->m_penetapanAngaran->getTahunAktif();

		$this->view->assign('tahunAktif',$tahun);

		return $this->loadView('penetapantahun/import/formimport');
	}

	public function ins_import()
	{
		global $basedomain;
		
		if($_FILES['output']['error'] == 0){

			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_output',$_POST['tahunAktif']);
			$i=0;
			$Test = new Prodigy_DBF($_FILES['output']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['VOL'] = $Record['VOL'];

		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_output');
		        }

		        $i++;
		    }
		}

		if($_FILES['suboutput']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_soutput',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['suboutput']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['KDSOUTPUT'] = $Record['KDSOUTPUT'];
		        $data[$i]['URSOUTPUT'] = $Record['URSOUTPUT'];
		        $data[$i]['VOLSOUT'] = $Record['VOLSOUT'];
		        
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_soutput');
		        }

		        $i++;
		    }
		}

		if($_FILES['komponen']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_kmpnen',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['komponen']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['KDSOUTPUT'] = $Record['KDSOUTPUT'];
		        $data[$i]['KDKMPNEN'] = $Record['KDKMPNEN'];
		        $data[$i]['URKMPNEN'] = $Record['URKMPNEN'];
		       
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_kmpnen');
		        }

		        $i++;
		    }
		}

		if($_FILES['subkomponen']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_skmpnen',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['subkomponen']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['KDSOUTPUT'] = $Record['KDSOUTPUT'];
		        $data[$i]['KDKMPNEN'] = $Record['KDKMPNEN'];
		        $data[$i]['KDSKMPNEN'] = $Record['KDSKMPNEN'];
		        $data[$i]['URSKMPNEN'] = $Record['URSKMPNEN'];
		       
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_skmpnen');
		        }

		        $i++;
		    }
		}

		if($_FILES['akun']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_akun',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['akun']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['KDSOUTPUT'] = $Record['KDSOUTPUT'];
		        $data[$i]['KDKMPNEN'] = $Record['KDKMPNEN'];
		        $data[$i]['KDSKMPNEN'] = $Record['KDSKMPNEN'];
		        $data[$i]['KDAKUN'] = $Record['KDAKUN'];
		        $data[$i]['JUMLAH'] = $Record['JUMLAH'];
		       
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_akun');
		        }

		        $i++;
		    }
		}

		if($_FILES['pok']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_item',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['pok']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['KDOUTPUT'] = $Record['KDOUTPUT'];
		        $data[$i]['KDSOUTPUT'] = $Record['KDSOUTPUT'];
		        $data[$i]['KDKMPNEN'] = $Record['KDKMPNEN'];
		        $data[$i]['KDSKMPNEN'] = $Record['KDSKMPNEN'];
		        $data[$i]['KDAKUN'] = $Record['KDAKUN'];
		        $data[$i]['HEADER1'] = $Record['HEADER1'];
		        $data[$i]['HEADER2'] = $Record['HEADER2'];
		        $data[$i]['KDHEADER'] = $Record['KDHEADER'];
		        $data[$i]['NOITEM'] = $Record['NOITEM'];
		        $data[$i]['NMITEM'] = $Record['NMITEM'];
		        $data[$i]['VOL1'] = $Record['VOL1'];
		        $data[$i]['SAT1'] = $Record['SAT1'];
		        $data[$i]['VOL2'] = $Record['VOL2'];
		        $data[$i]['SAT2'] = $Record['SAT2'];
		        $data[$i]['VOL3'] = $Record['VOL3'];
		        $data[$i]['SAT3'] = $Record['SAT3'];
		        $data[$i]['VOL4'] = $Record['VOL4'];
		        $data[$i]['SAT4'] = $Record['SAT4'];
		        $data[$i]['VOLKEG'] = $Record['VOLKEG'];
		        $data[$i]['SATKEG'] = $Record['SATKEG'];
		        $data[$i]['HARGASAT'] = $Record['HARGASAT'];
		        $data[$i]['JUMLAH'] = $Record['JUMLAH'];
		        $data[$i]['JANUARI'] = $Record['JANUARI'];
		        $data[$i]['PEBRUARI'] = $Record['PEBRUARI'];
		        $data[$i]['MARET'] = $Record['MARET'];
		        $data[$i]['APRIL'] = $Record['APRIL'];
		        $data[$i]['MEI'] = $Record['MEI'];
		        $data[$i]['JUNI'] = $Record['JULI'];
		        $data[$i]['AGUSTUS'] = $Record['AGUSTUS'];
		        $data[$i]['SEPTEMBER'] = $Record['SEPTEMBER'];
		        $data[$i]['OKTOBER'] = $Record['OKTOBER'];
		        $data[$i]['NOPEMBER'] = $Record['NOPEMBER'];
		        $data[$i]['DESEMBER'] = $Record['DESEMBER'];

		       
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_item');
		        }

		        $i++;
		    }
		}

		if($_FILES['trktrm']['error'] == 0){
			unset($data);
			$i=0;
			//delete then replace as year
		    $this->m_penetapanAngaran->del_peryear('d_trktrm',$_POST['tahunAktif']);
			
			$Test = new Prodigy_DBF($_FILES['trktrm']['tmp_name']);
		    while(($Record = $Test->GetNextRecord(true)) and !empty($Record)) {
		        $data[$i]['THANG'] = $Record['THANG'];
		        $data[$i]['KDSATKER'] = $Record['KDSATKER'];
		        $data[$i]['KDDEPT'] = $Record['KDDEPT'];
		        $data[$i]['KDUNIT'] = $Record['KDUNIT'];
		        $data[$i]['KDPROGRAM'] = $Record['KDPROGRAM'];
		        $data[$i]['KDGIAT'] = $Record['KDGIAT'];
		        $data[$i]['RPHPAGU'] = $Record['RPHPAGU'];
		        $data[$i]['KDTRKTRM'] = $Record['KDTRKTRM'];
		        $data[$i]['JNSBELANJA'] = $Record['JNSBELANJA'];
		        $data[$i]['JML01'] = $Record['JML01'];
		        $data[$i]['JML02'] = $Record['JML02'];
		        $data[$i]['JML03'] = $Record['JML03'];
		        $data[$i]['JML04'] = $Record['JML04'];
		        $data[$i]['JML05'] = $Record['JML05'];
		        $data[$i]['JML06'] = $Record['JML06'];
		        $data[$i]['JML07'] = $Record['JML07'];
		        $data[$i]['JML08'] = $Record['JML08'];
		        $data[$i]['JML09'] = $Record['JML09'];
		        $data[$i]['JML10'] = $Record['JML10'];
		        $data[$i]['JML11'] = $Record['JML11'];
		        $data[$i]['JML12'] = $Record['JML12'];
		       
		        //insert data
		        if($data[$i]['THANG'] != ''){
		        	$this->model->insert_import($data[$i],'d_trktrm');
		        }

		        $i++;
		    }
		}


		echo "<script>alert('Data Berhasil Masuk');window.location.href='".$basedomain."penetapanAnggaran/penetapananggaran'</script>";
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
