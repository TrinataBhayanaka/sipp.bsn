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
		$eselon['type'] = $this->model->getEselon($this->admin);
		$eselon['level'] = $this->admin['type'];
		$this->view->assign('eselon',$eselon);

		$thn = $this->model->getTahun();
		$struktur = $this->model->getStruktur(1);
		$data = $this->model->getpk($struktur[0]['kode'],$thn['kode']);

		$this->view->assign('data',$data);
		
		return $this->loadView('renaksi/bsn');

	}

	public function edit_eselon1()
	{
		$id = $_GET['id'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);
		$kd = '840000';
		$nama_unit = $this->model->getNamaStruktur($kd);
		
		$this->view->assign('data',$data);
		$this->view->assign('id',$id);
		$this->view->assign('nama_unit',$nama_unit['nama_satker']);

		return $this->loadView('renaksi/edit');
	}

	/*public function edit_eselon1()
	{
		$id = $_GET['id'];
		$kd = $_GET['kd'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);
		$nama_unit = $this->model->getNamaStruktur($kd);
		$this->view->assign('data',$data);
		$this->view->assign('id',$id);
		$this->view->assign('kode',$kd);
		$this->view->assign('nama_unit',$nama_unit['nama_satker']);

		return $this->loadView('renaksi/edit_eselon1');
	}*/

	public function edit_eselon2()
	{
		$id = $_GET['id'];
		$kd = $_GET['kd'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);
		$nama_unit = $this->model->getNamaStruktur($kd);
		
		$this->view->assign('data',$data);
		$this->view->assign('id',$id);
		$this->view->assign('kode',$kd);
		$this->view->assign('nama_unit',$nama_unit['nama_satker']);

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
		$kode = $_POST['kd'];
		unset($_POST['kd']);
		$this->model->upd_renaksi($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."renaksi/eselon1/?kd={$kode}'</script>";
		exit;
	}

	public function edt_renaksi_es2()
	{
		global $basedomain;
		$kode = $_POST['kd'];
		unset($_POST['kd']);
		$this->model->upd_renaksi($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."renaksi/eselon2/?kd={$kode}'</script>";
		exit;
	}

	public function eselon1()
	{
		$struktur = $this->model->getStruktur(2);

		$eselon['type'] = $this->model->getEselon($this->admin);
		// $eselon['level'] = $this->admin['type'];
		//add new
		$eselon['kode'] = $this->admin['kode'];
		$this->view->assign('eselon',$eselon);

		if(!$_POST) {
			if(isset($_GET['kd'])){
				$idpk = $_GET['kd'];
			} else {
				$idpk = $struktur[0]['kode'];
			}
			foreach ($struktur as $key => $value) {
				if($value['kode'] == $idpk) {
					$parent = $value['id'];
					$this->view->assign('label',$value['nama_satker']);
					$this->view->assign('id',$value['id']);
					//add new
					$this->view->assign('kode',$value['kode']);
				}
			}
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
			//add new
			$this->view->assign('kode',$exp[2]);
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$thn['kode']);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('renaksi/eselon1');
	}

	public function eselon2()
	{
		$struktur = $this->model->getStruktur(3);

		$eselon['type'] = $this->model->getEselon($this->admin);
		$eselon['kode'] = $this->admin['kode'];
		$this->view->assign('eselon',$eselon);

		if(!$_POST) {
			if(isset($_GET['kd'])){
				$idpk = $_GET['kd'];
			} else {
				$idpk = $struktur[0]['kode'];
			}
			foreach ($struktur as $key => $value) {
				if($value['kode'] == $idpk) {
					$parent = $value['id'];
					$this->view->assign('label',$value['nama_satker']);
					$this->view->assign('id',$value['id']);
					$this->view->assign('kode',$value['kode']);
				}
			}
			$this->view->assign('idpk',$idpk);
		} else {
			$exp = explode("_", $_POST['struktur']);
			$idpk = $exp[2];
			$parent = $exp[0];
			$this->view->assign('label',$exp[1]);
			$this->view->assign('id',$exp[0]);
			$this->view->assign('idpk',$idpk);
			$this->view->assign('kode',$exp[2]);
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$thn['kode']);
		//pr($data);
		//exit;
		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('renaksi/eselon2');
	}

	public function add_bsn()
	{
		$ss = $this->getSS(1);
		$this->view->assign('ss',$ss);
		return $this->loadView('renaksi/add_bsn');
	}

	public function getSS($id)
	{
		$data = $this->model->selectSS($id);

		return $data;
	}

	public function del_renaksi($id){
		global $basedomain;
		$id = $_GET['id'];
		$data = $this->model->del_renaksi($id);

		echo "<script>window.location.href='".$basedomain."renaksi/bsn'</script>";
		exit;
	}
	
	public function del_renaksi_es1($id){
		global $basedomain;
		$id = $_GET['id'];
		$kode = $_GET['kd'];
		$data = $this->model->del_renaksi($id);

		echo "<script>window.location.href='".$basedomain."renaksi/eselon1/?kd={$kode}'</script>";
		exit;


	}

	public function del_renaksi_es2($id){
		global $basedomain;
		$id = $_GET['id'];
		$kode = $_GET['kd'];
		$data = $this->model->del_renaksi($id);

		echo "<script>window.location.href='".$basedomain."renaksi/eselon2/?kd={$kode}'</script>";
		exit;


	}

}

?>
