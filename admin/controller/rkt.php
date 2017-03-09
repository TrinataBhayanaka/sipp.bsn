<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class pelaporanKegiatan extends Controller {
	
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
		$this->marticle = $this->loadModel('marticle');
		$this->mquiz = $this->loadModel('mquiz');
		$this->mcourse = $this->loadModel('mcourse');
	}
	
	public function index(){

		return $this->loadView('pelaporanKegiatan/capaian/index');

	}
	public function form(){

		return $this->loadView('pelaporanKegiatan/capaian/form');
	}
	public function insertForm(){
		pr($_POST);

		$_POST['twn1']=json_encode($_POST['twn1']);
		$_POST['twn2']=json_encode($_POST['twn2']);
		$_POST['twn3']=json_encode($_POST['twn3']);
		$_POST['twn4']=json_encode($_POST['twn4']);
		// pr($twn1);
		pr($_POST);
// exit;
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['change_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST,"_capaian");
			if ($save) redirect($basedomain . 'index');
		}

		return $this->loadView('pelaporanKegiatan/capaian/form');
	}


	
}

?>
