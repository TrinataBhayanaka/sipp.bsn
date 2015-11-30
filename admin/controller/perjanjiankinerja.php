<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class perjanjiankinerja extends Controller {
	
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
		
		$this->model = $this->loadModel('mptn');
	}
	
	public function bsn(){
		$data = $this->model->getpk('840000',1);
		
		$this->view->assign('data',$data);

		return $this->loadView('pk/bsn');

	}

	public function add(){

		$ss = $this->getSS(1);
		$this->view->assign('ss',$ss);
		return $this->loadView('pk/add');		
	}

	public function getSS($id)
	{
		$data = $this->model->selectSS($id);

		return $data;
	}

	public function ins_pk()
	{
		global $basedomain;

		$this->model->insert_pk($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function edit()
	{

		$data = $this->model->getpk('840000',1,$_GET['id']);
		$ss = $this->getSS(1);

		$this->view->assign('ss',$ss);
		$this->view->assign('data',$data[0]);

		return $this->loadView('pk/edit');
	}

	public function edt_pk()
	{
		global $basedomain;

		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function del_pk()
	{
		global $basedomain;
		$id = $_GET['id'];
		$this->model->delete_pk($id);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function eselon1()
	{
		$struktur = $this->model->getStruktur(2);

		if(!$_POST) {
			$idpk = $struktur[0]['kode']; 
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
		}

		$data = $this->model->getpk($idpk,1);
		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon1');
	}

	
}

?>
