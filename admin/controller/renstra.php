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

	function delete()
	{

		global $basedomain;
		
		$id = _g('id');

		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data);
		if ($save){
			redirect($basedomain . 'renstra/visi_bsn');
		}
		
	}
	
}

?>
