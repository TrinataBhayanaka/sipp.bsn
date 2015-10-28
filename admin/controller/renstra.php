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

	function delete()
	{

		global $basedomain;

		$id = _g('id');
		$req = _g('req');

		if ($req == 2) $link = 'renstra/visi_eselon';
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
