<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

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
		// $this->validatePage();
		$this->view->assign('app_domain',$app_domain);
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

		$getStruktur = $this->contentHelper->getStruktur();

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

	public function sasaran()
	{
		global $basedomain;
		$parent_id = _g('parent_id');

		$getStruktur = $this->contentHelper->getStruktur();
		if (!$parent_id){
			redirect($basedomain."renstra/sasaran/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

		$getVisiBsn = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		// $getMisiBsn = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		// $getTujuanBsn = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		// $this->view->assign('misi', $getMisiBsn);
		// $this->view->assign('tujuan', $getTujuanBsn);

		$this->view->assign('struktur', $getStruktur);

		return $this->loadView('renstra/matrik/sasaran');
		
	}

	function kinerja()
	{

		global $basedomain;
		$parent_id = _g('parent_id');
		

		$getStruktur = $this->contentHelper->getStruktur();
		$getSetting = $this->contentHelper->getSetting();
		if (!$parent_id){
			redirect($basedomain."renstra/kinerja/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

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
						if ($value['data']) $getKinerja[$k][$key]['target'] = unserialize($value['data']);
						
					}
				}
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
		

		$getStruktur = $this->contentHelper->getStruktur();
		$getSetting = $this->contentHelper->getSetting();
		if (!$parent_id){
			redirect($basedomain."renstra/program/?parent_id=".$getStruktur[0]['id']);
			exit;
		}

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
		}

		$getSasaran = $this->contentHelper->getVisi(false, 7, 1, $parent_id);
		if ($getSasaran){
			foreach ($getSasaran as $key => $value) {
				$getData = $this->contentHelper->getVisi(false, 9, 1, $value['id']);
				if ($getData) $getKinerja[] = $getData;
			}

			if ($getKinerja){
				foreach ($getKinerja as $k => $val) {
					foreach ($val as $key => $value) {
						if ($value['data']) $getKinerja[$k][$key]['target'] = unserialize($value['data']);
						
					}
				}
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
		$this->view->assign('tahuntarget', $arrayTahun);
		$this->view->assign('kinerja', $getKinerja[0]);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('sasaran', $getSasaran);

		return $this->loadView('renstra/matrik/program');
	}

	function kegiatan()
	{

		return $this->loadView('renstra/matrik/kegiatan');
	}

	function output()
	{

	}

	function dokumenBsn()
	{
		global $basedomain;
		$parent_id = _g('parent_id');
		$pid = _g('pid');
		$newData = array();

		$getStruktur = $this->contentHelper->getStruktur();
		if (!$parent_id){
			if (!$pid) $pid = 1;
			redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id=".$getStruktur[0]['id']);
			exit;
		}

		if ($pid ==1){
			$type = 5;
			$this->view->assign('isbsn', 1);
		} 
		if ($pid ==2) $type = 6;
		if ($pid ==3) $type = 7;

		$getVisiBsn = $this->contentHelper->getVisi(false, $type, 1);
		$getMisiBsn = $this->contentHelper->getVisi(false, $type, 2);
		$getTujuanBsn = $this->contentHelper->getVisi(false, $type, 3);
		
		$getDokumen = $this->contentHelper->getVisi(false, 15, 1, $parent_id);
		
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
		return $this->loadView('renstra/visi/input-bsn');
	}


	function editEselon()
	{
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		$dataStruktur['id'] = _g('parent_id');
		
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		// pr($getStruktur);

		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 6, 1);
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
			$this->view->assign('text2', "Eselon 1");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 6);
			$this->view->assign('category', 1);
		}

		if ($req==2){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 6, 2);
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
			$this->view->assign('text2', "Eselon 1");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 6);
			$this->view->assign('category', 2);
		}

		if ($req==3){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 6, 3);
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
			$this->view->assign('text2', "Eselon 1");
			$this->view->assign('text3', "Visi");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 6);
			$this->view->assign('category', 3);
		}

		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/visi_eselon');
		}

		
		return $this->loadView('renstra/visi/input-eselon');
	}

	function editSasaran()
	{
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		$dataStruktur['id'] = _g('parent_id');
		
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		// pr($getStruktur);

		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 7, 1);
				$this->view->assign('text1value', $getVisiBsn[0]['brief']);
				$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
				$this->view->assign('text3value', $getVisiBsn[0]['desc']);
				$this->view->assign('valueid', $id);
				
			}else{
				$this->view->assign('text1value', "2015-2019");
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
			if ($save) redirect($basedomain . 'renstra/sasaran');
		}
		return $this->loadView('renstra/matrik/input-sasaran');
	}

	function editKinerja()
	{
		global $basedomain;

		$id = _g('id');
		$dataStruktur['id'] = _g('parent_id');
		$child_id = _g('child');

		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		$getSetting = $this->contentHelper->getSetting();
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
		}
		// pr($arrayTahun);
		$getSasaran = $this->contentHelper->getVisi($child_id, 7, 1, $dataStruktur['id']);
		// pr($getSasaran);
		if ($id){
			$getTarget = $this->contentHelper->getVisi($id, 8, 1);
			
			if ($getTarget){
				foreach ($getTarget as $key => $value) {
					if ($value['data']) $getTarget[$key]['target'] = unserialize($value['data']);
				}
			}
			// pr($getTarget);
			$this->view->assign('text4value', $getTarget[0]['desc']);
			$this->view->assign('target', $getTarget);
			$this->view->assign('id', $getTarget[0]['id']);
			
		}else{
			
			$this->view->assign('text4value', "");
			
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
		$this->view->assign('submit', "submit");
		$this->view->assign('parent_id', $child_id);
		$this->view->assign('type', 8);
		$this->view->assign('category', 1);
		

		if ($_POST['submit']){
			
			$serial = serialize($_POST['input']);
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			$_POST['data'] = $serial;
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/kinerja');
		}

		return $this->loadView('renstra/matrik/input-kinerja');
	}

	function editProgram()
	{
		global $basedomain;

		$id = _g('id');
		$dataStruktur['id'] = _g('parent_id');
		$child_id = _g('child');
		$req = _g('req');

		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		$getSetting = $this->contentHelper->getSetting();
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
		}
		// pr($arrayTahun);
		$getSasaran = $this->contentHelper->getVisi($child_id, 7, 1, $dataStruktur['id']);
		// pr($getSasaran);
		if ($id){
			$getTarget = $this->contentHelper->getVisi($id, 8, 1);
			
			if ($getTarget){
				foreach ($getTarget as $key => $value) {
					if ($value['data']) $getTarget[$key]['target'] = unserialize($value['data']);
				}
			}
			// pr($getTarget);
			$this->view->assign('text4value', $getTarget[0]['desc']);
			$this->view->assign('target', $getTarget);
			$this->view->assign('id', $getTarget[0]['id']);
			
		}else{
			
			$this->view->assign('text4value', "");
			
		}

		
		$this->view->assign('submit', "submit");
		$this->view->assign('parent_id', $child_id);
		$this->view->assign('type', 9);
		$this->view->assign('category', 1);
		
		if ($_POST['submit']){
			
			$serial = serialize($_POST['input']);
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			$_POST['data'] = $serial;
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'renstra/kinerja');
		}

		if ($req == 2){
			$this->view->assign('text1', "Kode");
			$this->view->assign('text2', "Program");
			$this->view->assign('text1value', "");
			$this->view->assign('text2value', "");

			return $this->loadView('renstra/matrik/input-preprogram');
		}else{

			$this->view->assign('text1value', $getSetting[0]['kode']);
			$this->view->assign('text2value', $getStruktur[0]['nama_satker']);
			$this->view->assign('text3value', $getSasaran[0]['desc']);

			$this->view->assign('tahuntarget', $arrayTahun);
			$this->view->assign('text1', "Renstra");
			$this->view->assign('text2', "Lembaga");
			$this->view->assign('text3', "Sasaran Strategis");
			$this->view->assign('text4', "Indikator Kinerja Sasaran Strategis");
			$this->view->assign('text5', "Target");
			
			return $this->loadView('renstra/matrik/input-program');
		}

		

		
	}

	function editDokumen()
	{	
		global $basedomain;


		
		$id = _g('id');
		$req = _g('req');
		$pid = _g('pid');
		$dataStruktur['id'] = _g('parent_id');
		
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		// pr($getStruktur);
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
			
			$this->view->assign('pid', $pid);
			$this->view->assign('text1', "Tahun Anggaran");
			$this->view->assign('text2', "Teks yang tampil");
			$this->view->assign('text3', "Nama File");
			$this->view->assign('text4', "No. Urut");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 15);
			$this->view->assign('category', 1);
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

							// pr($image);exit;
							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['filename'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
								if ($updateData) redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}else{
								echo "<script>alert('File type not allowed');</script>";
								redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

					}
				}
				
			}
			
			// if ($save) redirect($basedomain . 'renstra/sasaran');
		}
		return $this->loadView('renstra/dokumen/input-bsn');
	}

	function delete()
	{

		global $basedomain;

		$id = _g('id');
		$req = _g('req');

		if ($req == 2) $link = 'renstra/visi_eselon';
		if ($req == 3) $link = 'renstra/sasaran';
		if ($req == 4) $link = 'renstra/dokumenBsn';
		else $link = 'renstra/visi_bsn';

		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data);
		if ($save){
			redirect($basedomain . $link);
		}
		
	}
	
}

?>
