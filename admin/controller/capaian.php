<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class capaian extends Controller {
	
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
		$this->model = $this->loadModel('mcapaian');
	}
	
	public function bsn(){
		// pr($this->admin);exit;
		$thn = $this->model->getTahun();
		$struktur = $this->model->getStruktur(1);
		$data = $this->model->getpk($struktur[0]['kode'],$thn['kode']);

		$Struktur=$this->model->getStrukturId($this->admin[type]);
		// pr($Struktur);
		foreach ($data as $key => $value) {
			$data[$key]=$value;
			if($Struktur[type]==1){

				$data[$key]['linkEdit']=true;

			}elseif($Struktur[type]==3 && $value['kodeUser']==$this->admin[kode]){

				$data[$key]['linkEdit']=true;

			}
		}
		
		$this->view->assign('data',$data);

		return $this->loadView('capaian/bsn');

	}

	public function edit()
	{
		$id = $_GET['id'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);

		return $this->loadView('capaian/edit');
	}

	public function edit_eselon1()
	{
		$id = $_GET['id'];
		$kd = $_GET['kd'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);
		$this->view->assign('kode',$kd);

		return $this->loadView('capaian/edit_eselon1');
	}

	public function edit_eselon2()
	{
		$id = $_GET['id'];
		$kd = $_GET['kd'];
		$thn = $this->model->getTahun();
		$data = $this->model->getpkid($id,$thn['kode']);

		$this->view->assign('data',$data);
		$this->view->assign('id',$id);
		$this->view->assign('kode',$kd);

		return $this->loadView('capaian/edit_eselon2');
	}

	public function edt_capaian()
	{
		global $basedomain;

		$this->model->upd_capaian($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."capaian/bsn'</script>";
		exit;
	}

	public function edt_capaian_es1()
	{
		global $basedomain;
		$kode = $_POST['kd'];
		unset($_POST['kd']);
		$this->model->upd_capaian($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."capaian/eselon1/?kd={$kode}'</script>";
		exit;
	}

	public function edt_capaian_es2()
	{
		global $basedomain;
		$kode = $_POST['kd'];
		unset($_POST['kd']);
		$this->model->upd_capaian($_POST);

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."capaian/eselon2/?kd={$kode}'</script>";
		exit;
	}

	public function eselon1()
	{
		$struktur = $this->model->getStruktur(2);

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
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$thn['kode']);
		$Struktur=$this->model->getStrukturId($this->admin[type]);
		// pr($Struktur);
		foreach ($data as $key => $value) {
			$data[$key]=$value;
			if($Struktur[type]==1){

				$data[$key]['linkEdit']=true;

			}elseif($Struktur[type]==3 && $value['kodeUser']==$this->admin[kode]){

				$data[$key]['linkEdit']=true;

			}
		}
		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('capaian/eselon1');
	}

	public function eselon2()
	{
		$struktur = $this->model->getStruktur(3);

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
		}
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$thn['kode']);
		$Struktur=$this->model->getStrukturId($this->admin[type]);
		// pr($Struktur);
		foreach ($data as $key => $value) {
			$data[$key]=$value;
			if($Struktur[type]==1 || $Struktur[type]==3 ){

				$data[$key]['linkEdit']=true;

			}
		}
		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('capaian/eselon2');
	}

	public function add_bsn()
	{
		$ss = $this->getSS(1);
		$this->view->assign('ss',$ss);
		return $this->loadView('capaian/add_bsn');
	}

	public function getSS($id)
	{
		$data = $this->model->selectSS($id);

		return $data;
	}
	
}

?>
