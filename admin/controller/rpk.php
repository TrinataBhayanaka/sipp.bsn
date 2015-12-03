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
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->marticle = $this->loadModel('marticle');
		$this->mquiz = $this->loadModel('mquiz');
		$this->mcourse = $this->loadModel('mcourse');
	}
	
	public function index(){

		return $this->loadView('rpk/list');

	}
	public function edit(){

		return $this->loadView('rpk/edit');

	}
	public function editTahapan(){

		return $this->loadView('rpk/editTahapan');

	}

	public function editRencanaAnggaran(){

		return $this->loadView('rpk/editRencanaAnggaran');

	}

	
}

?>
