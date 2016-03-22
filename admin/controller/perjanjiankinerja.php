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
		$thn = $this->model->getTahun();
		$data = $this->model->getpk('840000',1,false,$thn['kode']);
		
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
		$es = $_GET['tp'];
		$struktur = $this->model->getStruktur($es);
		if($es == '2')$labelEs="I";else $labelEs="II";
		$this->view->assign('labelEs',$labelEs);
		$this->view->assign('es',$es);

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

		$data = $this->model->getpk($idpk,$parent);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon1');
	}

	public function add_eselon()
	{
		$ss = $this->getSS($_GET['id']);

		$this->view->assign('ss',$ss);
		$this->view->assign('idpk',$_GET['kd']);

		return $this->loadView('pk/add_eselon');		
	}

	public function ins_pk_eselon()
	{
		global $basedomain;
		
		$this->model->insert_pk($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/eselon1/?tp=2'</script>";
		exit;
	}

	public function edit_eselon()
	{

		$data = $this->model->getpk($_GET['kd'],$_GET['pr'],$_GET['id']);
		$ss = $this->getSS($_GET['pr']);

		$this->view->assign('ss',$ss);
		$this->view->assign('data',$data[0]);
		$this->view->assign('kode',$_GET['kd']);

		return $this->loadView('pk/edit_eselon');
	}

	public function edt_pk_eselon()
	{
		global $basedomain;

		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/eselon1'</script>";
		exit;
	}

	public function eselon2()
	{
		$struktur = $this->model->getStruktur(3);
		$this->view->assign('eselon','II');

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

		$data = $this->model->getpk($idpk,$parent);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon2');
	}
	
}

?>
