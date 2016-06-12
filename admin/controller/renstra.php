<?php

class renstra extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		$this->view->assign('app_domain',$app_domain);
		$this->view->assign('user',$this->admin);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){

		return $this->loadView('home/home');

	}

	public function visi_bsn()
	{

		$getVisiBsn = $this->contentHelper->getVisi(false, 5, 1);
		$getMisiBsn = $this->contentHelper->getVisi(false, 5, 2);
		$getTujuanBsn = $this->contentHelper->getVisi(false, 5, 3);
		
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);

		return $this->loadView('renstra/visi/bsn');
		
	}
	
	public function visi_eselon()
	{
		global $basedomain;
		$parent_id = _g('parent_id');

		$dataStruktur['type'] = 2;
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);

		if (!$parent_id){
			redirect($basedomain."renstra/visi_eselon/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		$getVisiBsn = $this->contentHelper->getVisi(false, 6, 1, $parent_id);
		$getMisiBsn = $this->contentHelper->getVisi(false, 6, 2, $parent_id);
		$getTujuanBsn = $this->contentHelper->getVisi(false, 6, 3, $parent_id);
		
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);

		$this->view->assign('struktur', $getStruktur);

		return $this->loadView('renstra/visi/eselon');
		
	}

	public function visi_eselon2()
	{
		global $basedomain;
		$parent_id = _g('parent_id');

		$dataStruktur['type'] = 3;
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		// pr($getStruktur);
		if (!$parent_id){
			redirect($basedomain."renstra/visi_eselon2/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		$getVisiBsn = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		$getMisiBsn = $this->contentHelper->getVisi(false, 7, 2, $parent_id);
		$getTujuanBsn = $this->contentHelper->getVisi(false, 7, 3, $parent_id);
		
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);

		$this->view->assign('struktur', $getStruktur);

		return $this->loadView('renstra/visi/eselon2');
		
	}

	public function sasaran()
	{
		global $basedomain;
		$parent_id = _g('parent_id');

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3', 'n_status'=>1);
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		if (!$parent_id){
			redirect($basedomain."renstra/sasaran/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		$getVisiBsn = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		
		$this->view->assign('struktur', $getStruktur);
// pr($getVisiBsn);
		return $this->loadView('renstra/matrik/sasaran');
		
	}

	function kinerja()
	{

		global $basedomain;
		$parent_id = _g('parent_id');
		

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3');
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);

		$dataSetting['table'] = 'bsn_sistem_setting';
		$dataSetting['condition'] = array('n_status'=>1, 'desc'=>'tahun_sistem');
		$getSetting = $this->contentHelper->fetchData($dataSetting);
		if (!$parent_id){
			redirect($basedomain."renstra/kinerja/?parent_id=".$getStruktur[0]['id']);
			exit;
		}
		
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=10; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}

		$getSasaran = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		if ($getSasaran){
			foreach ($getSasaran as $key => $value) {
				$getData = $this->contentHelper->getVisi(false, 8, 1, $value['id']);
				if ($getData) $getKinerja[] = $getData;
			}

			if ($getKinerja){
				foreach ($getKinerja as $k => $val) {
					foreach ($val as $key => $value) {
						if ($value['data']){
							$target = unserialize($value['data']);
							$getKinerja[$k][$key]['target'] = $target['target'];
							$getKinerja[$k][$key]['satuan_target'] = $target['satuan_target'];
						} 
						
					}
				}
				// pr($getKinerja);
				foreach ($getSasaran as $key => $value) {
					
					foreach ($getKinerja as $b) {
						foreach ($b as $k => $v) {
							if ($value['id']==$v['parent_id']){
								$getSasaran[$key]['target'][] = $v;
							}
						}
					}
				}
			}
		}
		// pr($getSasaran);
		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('kinerja', $getKinerja[0]);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('sasaran', $getSasaran);

		return $this->loadView('renstra/matrik/kinerja');
	}

	function program()
	{
		global $basedomain;
		$parent_id = _g('parent_id');
		

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3');
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);

		$getSetting = $this->contentHelper->getSetting();
		if (!$parent_id){
			redirect($basedomain."renstra/program/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		// $arrayTahun = $this->tahunRenstra($getSetting);
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=5; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}
		$getSasaran = $this->contentHelper->getVisi(false, 9, 1, $parent_id);
		if ($getSasaran){
			foreach ($getSasaran as $key => $value) {
				$getData = $this->contentHelper->getVisi(false, 9, 2, $value['id']);
				
				if ($getData){

					foreach ($getData as $k => $val) {
						if ($val['tags']){
							$getEselon = $this->contentHelper->getStruktur(array('id'=>$val['tags']));
							if ($getEselon) $getData[$k]['eselon'] = $getEselon[0];
						}

						$getIndikator = $this->contentHelper->getVisi(false, 9, 3, $val['id']);
						if ($getIndikator){
							foreach ($getIndikator as $index => $v) {
								if ($v['data']){
									$target = unserialize($v['data']);
									$getIndikator[$index]['target'] = $target['target'];
									$getIndikator[$index]['satuan_target'] = $target['satuan_target'];
								} 
							}

							$getSasaran[$key]['is_indikator'] = true;
							$getData[$k]['indikator'] = $getIndikator;
							
						} 
					}
					$getSasaran[$key]['outcome'] = $getData;
				} 
				
			}
			
		}
		// pr($getSasaran);
		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('kinerja', $getKinerja[0]);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('sasaran', $getSasaran);

		// pr($getSasaran);
		return $this->loadView('renstra/matrik/program');
	}

	function kegiatan()
	{

		global $basedomain;
		$parent_id = _g('parent_id');
		

		$getStruktur = $this->contentHelper->getStruktur();
		$getSetting = $this->contentHelper->getSetting();
		if (!$parent_id){
			redirect($basedomain."renstra/kegiatan/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		$arrayTahun = $this->tahunRenstra($getSetting);
		$getKegiatan = $this->contentHelper->getVisi(false, 9, 1, $parent_id);
		if ($getKegiatan){
			foreach ($getKegiatan as $key => $value) {
				$getData = $this->contentHelper->getVisi(false, 10, 1, $value['id']);
				
				if ($getData){

					foreach ($getData as $k => $val) {
						if ($val['data']){
							$getEselon = $this->contentHelper->getStruktur(array('id'=>$val['data']));
							if ($getEselon) $getData[$k]['eselon'] = $getEselon[0];
						}

					}
					$getKegiatan[$key]['kegiatan'] = $getData;
				} 
				
			}
			
		}
		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('kinerja', $getKinerja[0]);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('kegiatan', $getKegiatan);

		return $this->loadView('renstra/matrik/kegiatan');
	}

	function output()
	{	
		global $basedomain;
		$parent_id = _g('parent_id');
		

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3');
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$getSetting = $this->contentHelper->getSetting();
		if (!$parent_id){
			redirect($basedomain."renstra/output/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		// $arrayTahun = $this->tahunRenstra($getSetting);
		$getSetting = $this->contentHelper->getSetting();
		// $arrayTahun = $this->tahunRenstra($getSetting);
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=5; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}
		
		$out['type'] = 10;
		$out['category'] = 1;
		
		$getKegiatan = $this->contentHelper->getContent($out);
		if ($getKegiatan){
			foreach ($getKegiatan as $key => $value) {
				$getData = $this->contentHelper->getVisi(false, 11, 1, $value['id']);
				
				if ($getData){

					foreach ($getData as $k => $val) {
						if ($val['data']){
							$target = unserialize($val['data']);
							$getData[$k]['target'] = $target['target'];
							$getData[$k]['satuan_target'] = $target['satuan_target'];

							$getData[$k]['target_anggaran'] = $target['target_anggaran'];
							$getData[$k]['satuan_target_anggaran'] = $target['satuan_target_anggaran'];
						} 

						$getDataOut = $this->contentHelper->getVisi(false, 11, 2, $val['id']);
						if ($getDataOut){
							foreach ($getDataOut as $ka => $v) {
								if ($v['data']){
									$target = unserialize($v['data']);
									$getDataOut[$ka]['target'] = $target['target'];
									$getDataOut[$ka]['satuan_target'] = $target['satuan_target'];

									$getDataOut[$ka]['target_anggaran'] = $target['target_anggaran'];
									$getDataOut[$ka]['satuan_target_anggaran'] = $target['satuan_target_anggaran'];
								} 
							}

							$getData[$k]['output'] = $getDataOut;
						}
					}
					
					$getKegiatan[$key]['kegiatan'] = $getData;
				} 
				
			}
			
		}
		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('kinerja', $getKinerja[0]);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('kegiatan', $getKegiatan);
		return $this->loadView('renstra/matrik/output');
	}

	function dokumenBsn()
	{
		global $basedomain,$app_domain;
		$parent_id = _g('parent_id');
		$pid = _g('pid');
		$newData = array();

		if ($pid ==1){
			$dataStruktur['type'] = 1;
			$type = 5;
			$this->view->assign('isbsn', 1);
			$parentid = 0;
		} 
		if ($pid ==2){
			$dataStruktur['type'] = 2;
			$type = 6;
			$parentid = $parent_id;
		} 
		if ($pid ==3){
			$dataStruktur['type'] = 3;
			$type = 7;
			$parentid = $parent_id;
		} 


		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		
		if ($pid==1){
			foreach ($getStruktur as $key => $value) {
				if ($value['kode']=='840000') $bsnid = $value['id'];
			}
		}
		if (!$parent_id){
			if (!$pid) $pid = 1;
			
			if ($pid==1){
				foreach ($getStruktur as $key => $value) {
					if ($value['kode']=='840000') $bsnid = $value['id'];
				}
			}else{
				$bsnid = $getStruktur[0]['id'];
			}
			redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id=".$bsnid);
			exit;
		}

		
		$this->view->assign('bsnid', $bsnid);
		$getVisiBsn = $this->contentHelper->getVisi(false, $type, 1, $parentid);
		// pr($getVisiBsn);
		$getMisiBsn = $this->contentHelper->getVisi(false, $type, 2, $parentid);
		$getTujuanBsn = $this->contentHelper->getVisi(false, $type, 3, $parentid);
		
		$getDokumen = $this->contentHelper->getVisi(false, 15, 1, $parentid);
		
		if ($getDokumen){
			foreach ($getDokumen as $key => $value) {
				$tags[$value['id']] = $value['tags'];
			}

			if (is_array($tags)){
				asort($tags);
				foreach ($tags as $key => $val) {
					foreach ($getDokumen as $k => $value) {
						if ($key == $value['id']) $newData[] = $value;
					}
				}
			}
			

		}
		// pr($newData);
		$this->view->assign('pid', $pid);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);
		$this->view->assign('dokumen', $newData);
		$this->view->assign('struktur', $getStruktur);
		return $this->loadView('renstra/dokumen/bsn');
	}

	
	function edit()
	{
		global $basedomain;

		$id = _g('id');
		$req = _g('req');

		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 5, 1);
				$this->view->assign('text1value', $getVisiBsn[0]['title']);
				$this->view->assign('text2value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', "Badan Standardisasi Nasional");
			}
			
			$this->view->assign('text1', "Lembaga");
			$this->view->assign('text2', "Visi");
			$this->view->assign('submit', "visi");
		}

		if ($req==2){
			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 5, 2);
				$this->view->assign('text1value', $getVisiBsn[0]['title']);
				$this->view->assign('text2value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', "Badan Standardisasi Nasional");
			}
			$this->view->assign('text1', "Lembaga");
			$this->view->assign('text2', "Misi");
			$this->view->assign('submit', "misi");
		}

		if ($req==3){
			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 5, 3);
				$this->view->assign('text1value', $getVisiBsn[0]['title']);
				$this->view->assign('text2value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', "Badan Standardisasi Nasional");
			}
			$this->view->assign('text1', "Lembaga");
			$this->view->assign('text2', "Tujuan");
			$this->view->assign('submit', "tujuan");
		}


		if ($_POST['visi']){
			
			$_POST['type'] = 5;
			$_POST['category'] = 1;
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/visi_bsn');
		}

		if ($_POST['misi']){
			
			$_POST['type'] = 5;
			$_POST['category'] = 2;
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/visi_bsn');
		}

		if ($_POST['tujuan']){
			
			$_POST['type'] = 5;
			$_POST['category'] = 3;
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/visi_bsn');
		}

		$this->view->assign('button', ucfirst(_g('button') ? _g('button') : 'Visi'));
		return $this->loadView('renstra/visi/input-bsn');
	}


	function editEselon()
	{
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		$menu = _g('menu');

		if ($menu == 1) $type = 6;
		else $type = 7;
		
		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3','id'=>_g('parent_id'));
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$dataStruktur['id'] = _g('parent_id');

		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, $type, 1);
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				$this->view->assign('text3value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				
			}
			
			$this->view->assign('parent_id', $dataStruktur['id']);
			$this->view->assign('text1', "Kode");
			$this->view->assign('text2', "Eselon I");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', $type);
			$this->view->assign('category', 1);
		}

		if ($req==2){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, $type, 2);
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				$this->view->assign('text3value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				
			}
			
			$this->view->assign('parent_id', $dataStruktur['id']);
			$this->view->assign('text1', "Kode");
			$this->view->assign('text2', "Eselon I");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', $type);
			$this->view->assign('category', 2);
		}

		if ($req==3){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, $type, 3);
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				$this->view->assign('text3value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', $getStruktur[0]['kode']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				
			}
			
			$this->view->assign('parent_id', $dataStruktur['id']);
			$this->view->assign('text1', "Kode");
			$this->view->assign('text2', "Eselon I");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', $type);
			$this->view->assign('category', 3);
		}

		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;


			$save = $this->contentHelper->saveData($_POST);
			if ($_POST['menu']==1) $function = "visi_eselon";
			else $function = "visi_eselon2";
			if ($save) redirect($basedomain . "renstra/{$function}/?parent_id=". $_POST['parent_id']);
		}

		$this->view->assign('menu', _g('menu'));
		$this->view->assign('button', ucfirst(_g('button') ? _g('button') : 'Visi'));
		return $this->loadView('renstra/visi/input-eselon');
	}

	function editSasaran()
	{
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		
		
		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3','id'=>_g('parent_id'));
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$dataStruktur['id'] = _g('parent_id');
		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 7, 1);
				$this->view->assign('text1value', $getVisiBsn[0]['brief']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				$this->view->assign('text3value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{

				$getSetting = $this->contentHelper->getSetting();
				$this->view->assign('text1value', $getSetting[0]['data']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				
			}
			
			$this->view->assign('parent_id', $dataStruktur['id']);
			$this->view->assign('text1', "Rentra");
			$this->view->assign('text2', "Unit Kerja");
			$this->view->assign('text3', "Sasaran");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 7);
			$this->view->assign('category', 1);
		}

		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/sasaran/?parent_id='. $_POST['parent_id']);
		}

		$this->view->assign('button', ucfirst(_g('button') ? _g('button') : 'sasaran utama'));

		return $this->loadView('renstra/matrik/input-sasaran');
	}

	function editKinerja()
	{
		global $basedomain;

		$id = _g('id');
		
		$child_id = _g('child');

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3','id'=>_g('parent_id'));
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$dataStruktur['id'] = _g('parent_id');

		$getSetting = $this->contentHelper->getSetting();
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=10; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}
		$getSasaran = $this->contentHelper->getVisi($child_id, 7, 1, $dataStruktur['id']);
		if ($id){
			$getTarget = $this->contentHelper->getVisi($id, 8, 1);
			
			if ($getTarget){
				foreach ($getTarget as $key => $value) {
					if ($value['data']){
						$target = unserialize($value['data']);
						$getTarget[$key]['target'] = $target['target'];
						$getTarget[$key]['satuan_target'] = $target['satuan_target'];
					} 

				}
			}
			
			$this->view->assign('text4value', $getTarget[0]['desc']);
			$this->view->assign('text6value', $getTarget[0]['satuan_target']);
			$this->view->assign('target', $getTarget);
			$this->view->assign('id', $getTarget[0]['id']);
			
		}else{
			
			$this->view->assign('text4value', "");
			$this->view->assign('text6value', "");
			
		}

		$this->view->assign('text1value', $getSetting[0]['kode']);
		$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
		$this->view->assign('text3value', $getSasaran[0]['desc']);

		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('text1', "Renstra");
		$this->view->assign('text2', "Lembaga");
		$this->view->assign('text3', "Sasaran Strategis");
		$this->view->assign('text4', "Indikator Kinerja Sasaran Strategis");
		$this->view->assign('text5', "Target");
		$this->view->assign('text6', "Satuan Target");
		$this->view->assign('submit', "submit");
		$this->view->assign('parent_id', $child_id);
		$this->view->assign('type', 8);
		$this->view->assign('category', 1);
		

		if ($_POST['submit']){
			
			// $serial = serialize($_POST['input']);
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			$_POST['data'] = serialize(array('target'=>$_POST['input'], 'satuan_target'=>$_POST['satuan_target']));

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/kinerja');
		}

		return $this->loadView('renstra/matrik/input-kinerja');
	}

	function editProgram()
	{
		global $basedomain;

		$id = _g('id');
		
		$child_id = _g('child');
		$req = _g('req');

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3','id'=>_g('parent_id'));
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$getSetting = $this->contentHelper->getSetting();
		// $arrayTahun = $this->tahunRenstra($getSetting);
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=5; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}
		
		$dataStruktur['id'] = _g('parent_id');
		$getSasaran = $this->contentHelper->getVisi($child_id, 9, 1, $dataStruktur['id']);
		$this->view->assign('submit', "submit");
		
		$dataOutcome = $this->contentHelper->getVisi(false, 9, 2, $dataStruktur['id']);
		$outcomeExist = [];
		if ($dataOutcome){
			foreach ($dataOutcome as $key => $value) {
				$outcomeExist[] = $value['tags'];
			}
		}
		
		if ($req == 1){
			// input prograM
			if ($child_id){
				$brief = $getSasaran[0]['brief'];
				$desc = $getSasaran[0]['desc'];
			}else{
				$brief = "";
				$desc = "";
			}
			$dataForm[] = array('text'=>true, 'title'=>'Kode', 'name'=>'brief', 'value'=>$brief);
			$dataForm[] = array('text'=>true, 'title'=>'Program', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'title', 'value'=>$getStruktur[0]['nama_satker']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>9);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $child_id);


			$generataField = $this->generateField($dataForm);
			$this->view->assign('form', $generataField);
			
			
		}else if ($req == 2){

			// input outcome
			// $dataStruktur = [];
			// $dataStruktur['type'] = 2;
			// $getStruktur = $this->contentHelper->getStruktur($dataStruktur);
			$getOutcome = $this->contentHelper->getVisi($dataStruktur['id'], 9, 1);
			// pr($getOutcome);
			if ($dataStruktur['id']){
				$brief = $getOutcome[0]['brief'];
				$title = $getOutcome[0]['desc'];
				if ($id){
					$getOutcome = $this->contentHelper->getVisi($id, 9, 2);
					$desc = $getOutcome[0]['desc'];
					$this->view->assign('outcome', $getOutcome);
				}else{
					$desc = "";
				}
				
			}else{
				$brief = "";
				$desc = "";
			}
		
			$getStrukturData = $this->contentHelper->getStruktur(['type'=>2]);
			
			// if (count($outcomeExist > 0)){
			// 	foreach ($getStrukturData as $key => $value) {
			// 		if (!in_array($value['id'], $outcomeExist)) $getStruktur[] = $value;
			// 	}
			// }else{
			// 	$getStruktur = $getStrukturData;
			// }
			$getStruktur = $getStrukturData;
			// pr($getOutcome);
			// pr($getStruktur);
			$dataForm[] = array('text'=>true, 'title'=>'Kode', 'name'=>'brief', 'value'=>$brief, 'readonly'=>'readonly');
			$dataForm[] = array('text'=>true, 'title'=>'Program', 'name'=>'title', 'value'=>$title, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Outcome', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'title', 'value'=>$getStruktur[0]['nama_satker']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>2);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>9);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);

			$generataField = $this->generateField($dataForm);
			$this->view->assign('req', 2);
			$this->view->assign('form', $generataField);
			$this->view->assign('struktur', $getStruktur);

			
		}else{
			// input indikator
			$getOutcome = $this->contentHelper->getVisi($dataStruktur['id'], 9, 2);

			if ($dataStruktur['id']){
				$getProgram = $this->contentHelper->getVisi($getOutcome[0]['parent_id'], 9, 1);
				$brief = $getProgram[0]['desc'];
				$title = $getProgram[0]['brief'];
				$outcome = $getOutcome[0]['desc'];
				if ($id){
					$getOutcome = $this->contentHelper->getVisi($id, 9, 3);
					
					if ($getOutcome){
						foreach ($getOutcome as $key => $value) {
							if ($value['data']){
								$target = unserialize($value['data']);
								$getOutcome[$key]['target'] = $target['target'];
								$getOutcome[$key]['satuan_target'] = $target['satuan_target'];
							} 
						}
					}
					$desc = $getOutcome[0]['desc'];

					// pr($getOutcome);
					$this->view->assign('outcome', $getOutcome);
					$this->view->assign('satuan_target', $getOutcome[0]['satuan_target']);
				}else{
					$desc = "";
				}
				
			}else{
				$brief = "";
				$desc = "";
			}
			
			$getSetting = $this->contentHelper->getSetting();
			// pr($getSetting);
			// $arrayTahun = $this->tahunRenstra($getSetting);
			
			// pr($arrayTahun);
			// exit;
			$dataForm[] = array('text'=>true, 'title'=>'Kode Program', 'name'=>'title', 'value'=>$title, 'readonly'=>'readonly');
			$dataForm[] = array('text'=>true, 'title'=>'Program', 'name'=>'brief', 'value'=>$brief, 'readonly'=>'readonly');
			$dataForm[] = array('textarea'=>true, 'title'=>'Outcome', 'name'=>'outcome', 'value'=>$outcome, 'disabled'=>'disabled' );
			$dataForm[] = array('textarea'=>true, 'title'=>'Indikator', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'title', 'value'=>$getStruktur[0]['nama_satker']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>3);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>9);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);

			$generataField = $this->generateField($dataForm);
			$this->view->assign('req', 3);
			$this->view->assign('form', $generataField);
			$this->view->assign('struktur', $getStruktur);
			$this->view->assign('tahuntarget', $arrayTahun);

			
		}

		if ($_POST['submit']){
			
			if ($_POST['category']==3){
				$_POST['data'] = serialize(array('target'=>$_POST['input'], 'satuan_target'=>$_POST['satuan_target']));
			}
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/program');
		}


		return $this->loadView('renstra/matrik/input-program');
		
	}

	function editKegiatan()
	{
		global $basedomain;

		$id = _g('id');
		$dataStruktur['id'] = _g('parent_id');
		$child_id = _g('child');
		$req = _g('req');

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3');
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		// pr($getStruktur);
		$getSetting = $this->contentHelper->getSetting();
		$arrayTahun = $this->tahunRenstra($getSetting);

		$getSasaran = $this->contentHelper->getVisi($dataStruktur['id'], 9, 1);
		$this->view->assign('submit', "submit");
		
		if ($req == 1){
			// input prograM
			if ($id){
				$getKegiatan = $this->contentHelper->getVisi($id, 10, 1);
				$brief1 = $getSasaran[0]['brief'];
				$desc1 = $getSasaran[0]['desc'];

				$title = $getKegiatan[0]['title'];
				$desc = $getKegiatan[0]['desc'];
				$this->view->assign('kegiatan', $getKegiatan);

			}else{
				$brief1 = $getSasaran[0]['brief'];
				$desc1 = $getSasaran[0]['desc'];
			}
			$dataForm[] = array('text'=>true, 'title'=>'Kode Program', 'name'=>'brief1', 'value'=>$brief1, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Program', 'name'=>'desc1', 'value'=>$desc1, 'disabled'=>'disabled');
			$dataForm[] = array('text'=>true, 'title'=>'Kode Kegiatan', 'name'=>'title', 'value'=>$title);
			$dataForm[] = array('textarea'=>true, 'title'=>'Nama Kegiatan', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>10);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'tags', 'value'=> $id);


			$generataField = $this->generateField($dataForm);
			$this->view->assign('form', $generataField);
			$this->view->assign('req', 1);
			$this->view->assign('struktur', $getStruktur);


		}

		if ($_POST['submit']){
			
			if ($_POST['category']==3){
				$serial = serialize($_POST['input']);
				$_POST['data'] = $serial;
			}
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/kegiatan');
		}

		return $this->loadView('renstra/matrik/input-kegiatan');
		
	}

	function editOutput()
	{
		global $basedomain;

		$id = _g('id');
		$dataStruktur['id'] = _g('parent_id');
		$child_id = _g('child');
		$req = _g('req');

		$dataStruktur['table'] = 'bsn_struktur';
		$dataStruktur['condition'] = array('type'=>'1,2,3','id'=>_g('parent_id'));
		$dataStruktur['in'] = array('type');
		$getStruktur = $this->contentHelper->fetchData($dataStruktur);
		
		$getSetting = $this->contentHelper->getSetting();
		// $arrayTahun = $this->tahunRenstra($getSetting);
		if ($getSetting){
			list ($tahunawal, $tahunakhir) = explode('-',$getSetting[0]['data']);
			$start = 1;
			for($i=1; $i<=5; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahunawal;
				}
				$tahunawal++;
				$start++;
			}
		}
		$getSasaran = $this->contentHelper->getVisi($dataStruktur['id'], 10, 1);
		$this->view->assign('submit', "submit");
		
		if ($req == 1){
			// input prograM
			if ($id){
				$getOutput = $this->contentHelper->getVisi($id, 11, 1);
				
				foreach ($getOutput as $k => $val) {
					if ($val['data']){
						$target = unserialize($val['data']);
						$getOutput[$k]['target'] = $target['target'];
						$getOutput[$k]['satuan_target'] = $target['satuan_target'];

						$getOutput[$k]['target_anggaran'] = $target['target_anggaran'];
						$getOutput[$k]['satuan_target_anggaran'] = $target['satuan_target_anggaran'];
					} 
				}
				$brief1 = $getSasaran[0]['title'];
				$desc1 = $getSasaran[0]['desc'];

				$title = $getOutput[0]['title'];
				$desc = $getOutput[0]['desc'];
				$this->view->assign('output', $getOutput);
				$this->view->assign('satuan_target', $getOutput[0]['satuan_target']);
				$this->view->assign('satuan_target_anggaran', $getOutput[0]['satuan_target_anggaran']);

			}else{
				$brief1 = $getSasaran[0]['title'];
				$desc1 = $getSasaran[0]['desc'];
			}
			$dataForm[] = array('text'=>true, 'title'=>'Kode Kegiatan', 'name'=>'brief1', 'value'=>$brief1, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Nama Kegiatan', 'name'=>'desc1', 'value'=>$desc1, 'disabled'=>'disabled');
			$dataForm[] = array('text'=>true, 'title'=>'Kode Output', 'name'=>'title', 'value'=>$title);
			$dataForm[] = array('textarea'=>true, 'title'=>'Nama Output', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>11);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			// pr($dataForm);

			$generataField = $this->generateField($dataForm);
			$this->view->assign('form', $generataField);
			$this->view->assign('req', 1);
			$this->view->assign('struktur', $getStruktur);
			$this->view->assign('tahuntarget', $arrayTahun);

		}

		if ($req == 2){
			// input prograM

			$dataStruktur['table'] = 'bsn_struktur';
			$dataStruktur['condition'] = array('type'=>'1,2,3');
			$dataStruktur['in'] = array('type');
			$getStruktur = $this->contentHelper->fetchData($dataStruktur);

			$getSasaran = $this->contentHelper->getVisi($dataStruktur['id'], 11, 1);
			
			$out['type'] = 10;
			$out['category'] = 1;
			$out['id'] = $getSasaran[0]['parent_id'];
			$getKegiatan = $this->contentHelper->getContent($out);
			if ($id){
				$getOutput = $this->contentHelper->getVisi($id, 11, 2);

				$out1['id'] = $getOutput[0]['parent_id'];
				$getKegiatan = $this->contentHelper->getContent($out1);

				$out2['id'] = $getKegiatan[0]['parent_id'];
				$getKegiatan1 = $this->contentHelper->getContent($out2);

				foreach ($getOutput as $k => $val) {
					if ($val['data']){
						$target = unserialize($val['data']);
						$getOutput[$k]['target'] = $target['target'];
						$getOutput[$k]['satuan_target'] = $target['satuan_target'];
					} 
				}
				$brief1 = $getSasaran[0]['title'];
				$desc1 = $getKegiatan1[0]['desc'];
				$desc2 = $getSasaran[0]['desc'];

				$title = $getOutput[0]['title'];
				$desc = $getOutput[0]['desc'];
				$this->view->assign('output', $getOutput);
				$this->view->assign('satuan_target', $getOutput[0]['satuan_target']);

			}else{
				$title = $getSasaran[0]['title'];
				$desc1 = $getKegiatan[0]['desc'];
				$desc2 = $getSasaran[0]['desc'];
				$desc = "";
			}
			$dataForm[] = array('text'=>true, 'title'=>'Kode Output', 'name'=>'title', 'value'=>$title, 'readonly'=>'readonly');
			$dataForm[] = array('textarea'=>true, 'title'=>'Nama Kegiatan', 'name'=>'desc1', 'value'=>$desc1, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Nama Output', 'name'=>'desc2', 'value'=>$desc2, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Indikator Kinerja Kegiatan', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$dataStruktur['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>2);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>11);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);

			// pr($getStruktur);
			$generataField = $this->generateField($dataForm);
			$this->view->assign('form', $generataField);
			$this->view->assign('req', _g('req'));
			$this->view->assign('struktur', $getStruktur);
			$this->view->assign('tahuntarget', $arrayTahun);

		}

		if ($_POST['submit']){
			
			$_POST['data'] = serialize(array('target'=>$_POST['input'], 'satuan_target'=>$_POST['satuan_target'], 
								'target_anggaran'=> $_POST['input_anggaran'], 'satuan_target_anggaran'=>$_POST['satuan_target_anggaran']));
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/output');
		}

		return $this->loadView('renstra/matrik/input-output');
		
	}

	function editDokumen()
	{	
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		$pid = _g('pid');
		$dataStruktur['id'] = _g('parent_id');
		
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		$getSetting = $this->contentHelper->getSetting();
		$this->view->assign('setting', $getSetting);
		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 15, 1);
				$this->view->assign('text1value', $getVisiBsn[0]['title']);
				$this->view->assign('text2value', $getVisiBsn[0]['desc']);
				$this->view->assign('text3value', $getVisiBsn[0]['filename']);
				$this->view->assign('valueid', $id);
				$this->view->assign('parent_id', $getStruktur);
				$this->view->assign('parent_name', $id);
				
			}else{
				$this->view->assign('parent_id', $getStruktur[0]['id']);
				$this->view->assign('parent_name', $getStruktur[0]['nama_satker']);
				$this->view->assign('text1value', $getSetting[0]['kode']);
				$this->view->assign('text2value', "");
				
			}
			
			$isCover = false;
			$parent_id = _g('parent_id');
			$pid = _g('pid');
			
			if ($pid ==1) $parentid = 0;
			if ($pid ==2 or $pid == 3) $parentid = $parent_id;
			
			$getDokumen = $this->contentHelper->getVisi(false, 15, 1, $parent_id);
			if ($getDokumen){
				foreach ($getDokumen as $key => $value) {
					if ($value['tags']) $isCover = true;
				}
			}

			$this->view->assign('pid', $pid);
			$this->view->assign('text1', "Tahun Anggaran");
			$this->view->assign('text2', "Teks yang tampil");
			$this->view->assign('text3', "Nama File");
			$this->view->assign('text4', "No. Urut");
			$this->view->assign('text5', "Cover");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 15);
			$this->view->assign('category', 1);
			$this->view->assign('cover', $isCover);
		}

		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$pid = $_POST['pid'];
			$parent_id = $_POST['parent_id'];
			$save = $this->contentHelper->saveData($_POST);
			if ($save){

				$getLastData = $this->contentHelper->getDocument(false, 15);
				if ($getLastData){
					if(!empty($_FILES)){
						if($_FILES['file']['name'] != ''){
							$image = uploadFile('file',null,'all');

							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['filename'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
								// if ($updateData) redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}else{
								echo "<script>alert('File type not allowed');</script>";
								redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

						if($_FILES['cover']['name'] != ''){
							$image = uploadFile('cover',null,'all');

							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['tags'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
								// if ($updateData) redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}else{
								echo "<script>alert('File type not allowed');</script>";
								redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

					}
				}
				
				redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
			}
			
		}
		return $this->loadView('renstra/dokumen/input-bsn');
	}

	function delete()
	{

		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		$id_parent = _g('id_parent');
		$menu = _g('menu');

		if ($req == 1) $link = 'renstra/visi_bsn';
		if ($req == 2){
			if ($menu == 1)$link = 'renstra/visi_eselon/?parent_id='.$id_parent;
			else $link = 'renstra/visi_eselon2/?parent_id='.$id_parent;
		} 
		if ($req == 3) $link = 'renstra/sasaran/?parent_id='.$id_parent;
		if ($req == 4) $link = 'renstra/kinerja';
		if ($req == 5) $link = 'renstra/program';
		if ($req == 6) $link = 'renstra/kegiatan';
		if ($req == 7) $link = 'renstra/output';
		if ($req == 8) $link = 'renstra/dokumenBsn';
		
		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data);
		if ($save){
			redirect($basedomain . $link);
		}
		
	}
	
	function tahunRenstra($getSetting)
	{
		
		if ($getSetting){
			$tahun = $getSetting[0]['kode'];
			$start = 1;
			for($i=1; $i<=10; $i++){
				if ($start<=5){
					$arrayTahun[] = $tahun;
				}
				$tahun++;
				$start++;
			}
			return $arrayTahun;
		}

		return false;
	}

	function generateField($data=array(), $is_hidden=false)
	{
		$html = "";
		$arrayHtml = array();
		foreach ($data as $key => $value) {
			

			if ($value['hidden']){ 
				$html .= "<input type='hidden' name='{$value['name']}' value='{$value['value']}'>";
			}else{
				$html .= "<div class='form-group'>";
				$html .=		"<label class='col-sm-3 control-label'>{$value['title']}</label>";
				$html .=		"<div class='col-sm-4'>";
				if ($value['text']){
					$html .=		"<input type='text' name='{$value['name']}' class='form-control' value='{$value['value']}' required {$value['readonly']} {$value['disabled']}>";
				}
				if ($value['textarea']){
					$html .= "<textarea rows='5' id='{$value['name']}' name='{$value['name']}' class='form-control' required {$value['readonly']} {$value['disabled']}>{$value['value']}</textarea>"; 
				}
				$html .=		"</div>";
				$html .=	"</div>";
			}
			
			$arrayHtml[] = $html; 
			$html ="";
		}

		if ($arrayHtml) return $arrayHtml;
		else return false;
	}
}

?>
