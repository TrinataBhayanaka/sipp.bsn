<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class renaksi extends Controller {
	
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
		$this->model = $this->loadModel('mrenaksi');
	}
	
	public function bsn(){

		$struktur = $this->model->getStruktur(1);
		$data = $this->model->getpk($struktur[0]['kode']);

		$this->view->assign('data',$data);

		return $this->loadView('renaksi/bsn');

	}

	public function edit()
	{
		$id = $_GET['id'];

		$data = $this->model->getpkid($id);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);

		return $this->loadView('renaksi/edit');
	}

	public function edit_eselon1()
	{
		$id = $_GET['id'];

		$data = $this->model->getpkid($id);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);

		return $this->loadView('renaksi/edit_eselon1');
	}

	public function edit_eselon2()
	{
		$id = $_GET['id'];

		$data = $this->model->getpkid($id);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);

		return $this->loadView('renaksi/edit_eselon2');
	}

	public function edt_renaksi()
	{
		global $basedomain;

		$this->model->upd_renaksi($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."renaksi/bsn'</script>";
		exit;
	}

	public function edt_renaksi_es1()
	{
		global $basedomain;

		$this->model->upd_renaksi($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."renaksi/eselon1'</script>";
		exit;
	}

	public function edt_renaksi_es2()
	{
		global $basedomain;

		$this->model->upd_renaksi($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."renaksi/eselon2'</script>";
		exit;
	}

	public function eselon1()
	{
		$struktur = $this->model->getStruktur(2);

		if(!$_POST) {
			$idpk = $struktur[0]['kode'];
			$parent = $struktur[0]['id'];
			$this->view->assign('label',$struktur[0]['nama_satker']);
			$this->view->assign('id',$struktur[0]['id']);
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
		}

		$data = $this->model->getpk($idpk);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('renaksi/eselon1');
	}

	public function eselon2()
	{
		$struktur = $this->model->getStruktur(3);

		if(!$_POST) {
			$idpk = $struktur[0]['kode'];
			$parent = $struktur[0]['id'];
			$this->view->assign('label',$struktur[0]['nama_satker']);
			$this->view->assign('id',$struktur[0]['id']);
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
		}

		$data = $this->model->getpk($idpk);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('renaksi/eselon2');
	}
	
}

?>