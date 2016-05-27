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
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function bsn(){
		$thn = $this->model->getTahun();
		$data = $this->model->getpk('840000',1,false,$thn['kode']);
		foreach ($data as $key => $value) {
			$perspektif[] = $value['perspektif'];
		}
		$fix_per = array_unique($perspektif);
		
		foreach ($fix_per as $key => $val) {
			foreach ($data as $value) {
				if($val == $value['perspektif']){
					$data_fix[$val][] = $value;
				}
			}
		}
		
		// db($data_fix);
		$this->view->assign('data',$data_fix);

		return $this->loadView('pk/bsn');

	}

	public function add(){
		$ss = $this->getSS(1,'840000');
		$this->view->assign('ss',$ss);
		$thn = $this->model->getTahun();
		$this->view->assign('thn',$thn['kode']);
		return $this->loadView('pk/add');		
	}

	public function getSS($id,$kd=false)
	{
		$data = $this->model->selectSS($id,$kd);

		return $data;
	}

	public function ins_pk()
	{
		global $basedomain;
		
		foreach ($_POST['indikator'] as $val) {
			$data['th'] = $_POST['th'];
			$data['no_sasaran'] = $_POST['no_sasaran'];
			$data['kdunitkerja'] = $_POST['kdunitkerja'];
			$data['perspektif'] = $_POST['perspektif'];
			$data['no_pk'] = $val['0'];
			$data['nm_pk'] = $val['1'];
			$data['target'] = $val['2'];
			$data['satuan'] = $val['3'];
			$this->model->insert_pk($data);
		}

		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function edit()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpkSS('840000',1,$_GET['id'],$thn['kode']);
		$ss = $this->getSS(1,'840000');
		
		$this->view->assign('ss',$ss);
		$this->view->assign('no_sasaran',$_GET['id']);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data);

		return $this->loadView('pk/edit');
	}

	public function edt_pk()
	{
		global $basedomain;
		foreach ($_POST['indikator'] as $key => $value) {
			$data['th'] = $_POST['th'];
			$data['no_sasaran'] = $_POST['no_sasaran'];
			$data['perspektif'] = $_POST['perspektif'];
			$data['id'] = $value[0];
			$data['no_pk'] = $value[1];
			$data['nm_pk'] = $value[2];
			$data['target'] = $value[3];
			$data['satuan'] = $value[4];

			$this->model->edit_pk($data);
		}

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function del_pk()
	{
		global $basedomain;
		$id = $_GET['id'];
		$thn = $this->model->getTahun();
		$this->model->delete_pk($id,$thn,$idpk);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/bsn'</script>";
		exit;
	}

	public function del_pk_eselon()
	{
		global $basedomain;
		$id = $_GET['id'];
		$thn = $this->model->getTahun();
		$this->model->delete_pk(false,$thn,$idpk);

		echo "<script>alert('Data berhasil dihapus');window.location.href='".$basedomain."perjanjiankinerja/eselon1/?tp=2'</script>";
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
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);
		
		foreach ($data as $key => $value) {
			$perspektif[] = $value['perspektif'];
		}
		$fix_per = array_unique($perspektif);
		
		foreach ($fix_per as $key => $val) {
			foreach ($data as $value) {
				if($val == $value['perspektif']){
					$data_fix[$val][] = $value;
				}
			}
		}
		
		// db($data_fix);
		$this->view->assign('data',$data_fix);
		$this->view->assign('tipe',$es);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon1');
	}

	public function pk_eselon2()
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
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);

		foreach ($data as $key => $value) {
			$perspektif[] = $value['perspektif'];
		}
		$fix_per = array_unique($perspektif);
		
		foreach ($fix_per as $key => $val) {
			foreach ($data as $value) {
				if($val == $value['perspektif']){
					$data_fix[$val][] = $value;
				}
			}
		}

		
		$this->view->assign('data',$data_fix);
		$this->view->assign('tipe',$es);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon2');
	}

	public function add_eselon()
	{
		$ss = $this->getSS($_GET['id']);
		$thn = $this->model->getTahun();
		$this->view->assign('thn',$thn['kode']);
		$this->view->assign('tp',$_GET['tp']);
		$this->view->assign('ss',$ss);
		$this->view->assign('idpk',$_GET['kd']);

		return $this->loadView('pk/add_eselon');		
	}

	public function ins_pk_eselon()
	{
		global $basedomain;
		
		$tipe = $_POST['tipe'];
		
		$data['th'] = $_POST['th'];
		$data['no_sasaran'] = $_POST['no_sasaran'];
		$data['kdunitkerja'] = $_POST['kdunitkerja'];
		$data['perspektif'] = $_POST['perspektif'];
		$data['satuan'] = $_POST['satuan'];
		$data['no_pk'] = $_POST['no_pk'];
		$data['nm_pk'] = $_POST['nm_pk'];
		$data['target'] = $_POST['target'];
		
		$this->model->insert_pk($data);
		
		if($tipe == 2){
			$eselon = "eselon1";
		} else {
			$eselon = "pk_eselon2";
		}
		echo "<script>alert('Data berhasil masuk');window.location.href='".$basedomain."perjanjiankinerja/{$eselon}/?tp={$tipe}'</script>";
		exit;
	}

	public function edit_eselon()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpkSS($_GET['kd'],$_GET['pr'],$_GET['id'],$thn['kode']);
		$ss = $this->getSS($_GET['pr']);
		
		$this->view->assign('ss',$ss);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data[0]);
		$this->view->assign('kode',$_GET['kd']);

		return $this->loadView('pk/edit_eselon');
	}

	public function edit_eselon2()
	{
		$thn = $this->model->getTahun();
		$data = $this->model->getpkSS($_GET['kd'],$_GET['pr'],$_GET['id'],$thn['kode']);
		$ss = $this->getSS($_GET['pr']);
		
		$this->view->assign('ss',$ss);
		$this->view->assign('tahun',$thn['kode']);
		$this->view->assign('data',$data[0]);
		$this->view->assign('kode',$_GET['kd']);

		return $this->loadView('pk/edit_eselon2');
	}

	public function edt_pk_eselon()
	{
		global $basedomain;
		
		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/eselon1/?tp=2'</script>";
		exit;
	}

	public function edt_pk_eselon2()
	{
		global $basedomain;
		
		$this->model->edit_pk($_POST);

		echo "<script>alert('Data berhasil dirubah');window.location.href='".$basedomain."perjanjiankinerja/pk_eselon2/?tp=3'</script>";
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
		
		$thn = $this->model->getTahun();
		$data = $this->model->getpk($idpk,$parent,false,$thn['kode']);

		
		$this->view->assign('data',$data);
		$this->view->assign('struktur',$struktur);

		return $this->loadView('pk/eselon2');
	}

	function ajaxIndikator()
	{
		$ss = $_POST['ss'];
		$th = $_POST['th'];
		$ik = $this->model->getIK(8, 1, $ss, $th);

		echo json_encode($ik);
		exit;
	}

	function tesChart()
	{
		return $this->loadView('pk/charts');
	}
	
}

?>
