<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class referensi extends Controller {
	
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
		
		$this->model = $this->loadModel('mref');
		// $this->contentHelper = $this->loadModel('contentHelper');
		$this->m_penetapanAngaran = $this->loadModel('m_penetapanAngaran');
		}
	
	public function ref_kegiatan(){
		// $thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = $thn_aktif['kode'];
		// $thn_renstra =$thn_aktif['data'];
		// $ref_kegiatan = $this->model->getRefKegiatan($thn_renstra);
		$ref_kegiatan = $this->model->getRefKegiatan();
		
		// db($ref_kegiatan);
		$this->view->assign('data',$ref_kegiatan);

		return $this->loadView('referensi/ref_keg');

	}
	
	public function ajax_edit_kegiatan(){
		// $thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_renstra =$thn_aktif['data'];
		$kdunitkerja = $_POST['kdunitkerja'];
		$kdgiat = $_POST['kdgiat'];
		// $ref_nama_kegiatan = $this->model->getNamaKegiatan($kd_giat,$thn_renstra);
		$ref_nama_kegiatan = $this->model->getNamaKegiatan($kdunitkerja,$kdgiat);
		echo json_encode($ref_nama_kegiatan);
		exit;
		
	}
	public function ajax_ceck_kegiatan(){
		$kdunitkerja = $_POST['kdunitkerja'];
		$kdgiat = $_POST['kdgiat'];
		// $ref_nama_kegiatan = $this->model->getNamaKegiatan($kd_giat,$thn_renstra);
		$ref_nama_kegiatan = $this->model->ceckKegiatan($kdunitkerja,$kdgiat);
		echo json_encode($ref_nama_kegiatan);
		exit;
		
	}
	
	public function ajax_update_kegiatan(){
	// pr($_POST);
		$kdunitkerja = $_POST['kdunitkerja'];
		$kdunitkerjaold = $_POST['kdunitkerjaold'];
		$kdgiat = $_POST['kdgiat'];
		$kdgiatold = $_POST['kdgiatold'];
		$nmgiat = $_POST['nmgiat'];
		$ref_nama_kegiatan = $this->model->updateNamaKegiatan($kdunitkerja,$kdgiat,$nmgiat,$kdunitkerjaold,$kdgiatold);
		exit;
		
	}

	public function ref_output(){
		// $thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_temp = $thn_aktif['kode'];
		// $thn_renstra =$thn_aktif['data'];
		// $ref_kegiatan = $this->model->getRefKegiatan($thn_renstra);
		$ref_kegiatan = $this->model->getRefOutput();
		
		// db($data_fix);
		$this->view->assign('data',$ref_kegiatan);

		return $this->loadView('referensi/ref_out');

	}
	
	public function ajax_edit_output(){
		// $thn_aktif = $this->m_penetapanAngaran->thn_aktif();
		// $thn_renstra =$thn_aktif['data'];
		$kd_giat = $_POST['kdunitkerja'];
		$kdoutput = $_POST['kdoutput'];
		// $ref_nama_kegiatan = $this->model->getNamaKegiatan($kd_giat,$thn_renstra);
		$ref_nama_output = $this->model->getNamaOutput($kd_giat,$kdoutput);
		echo json_encode($ref_nama_output);
		exit;
		
	}
	
	public function ajax_update_output(){
		// pr($_POST);
		$kdgiat = $_POST['kdgiat'];
		$kdgiatold = $_POST['kdgiatold'];
		$kdoutput = $_POST['kdoutput'];
		$kdoutputold = $_POST['kdoutputold'];
		$nmoutput = $_POST['nmoutput'];
		$ref_nama_kegiatan = $this->model->updateNamaOutput($kdgiat,$kdoutput,$nmoutput,$kdgiatold,$kdoutputold);
		exit;
		
	}
	
	public function ajax_ceck_output(){
		$kdgiat = $_POST['kdgiat'];
		$kdoutput = $_POST['kdoutput'];
		// $ref_nama_kegiatan = $this->model->getNamaKegiatan($kd_giat,$thn_renstra);
		$ref_nama_output = $this->model->ceckOutput($kdgiat,$kdoutput);
		echo json_encode($ref_nama_output);
		exit;
		
	}
	
	public function ajax_insert_output(){
		// pr($_POST);
		$kdgiat = $_POST['kdgiat'];
		$kdoutput = $_POST['kdoutput'];
		$nmoutput = $_POST['nmoutput'];
		$insert_output = $this->model->insertOutput($kdgiat,$kdoutput,$nmoutput);
		exit;
	}
	
	public function ajax_del_output(){
		// pr($_POST);
		$kdgiat = $_POST['kdgiat'];
		$kdoutput = $_POST['kdoutput'];
		$delete_output = $this->model->deleteOutput($kdgiat,$kdoutput);
		exit;
	}
	
	public function ref_akun(){
		
		$ref_akun = $this->model->getRefAkun();
		$this->view->assign('data',$ref_akun);

		return $this->loadView('referensi/ref_akun');

	}
	
	public function ajax_edit_akun(){
		$kdakun = $_POST['kdakun'];
		$ref_nama_akun = $this->model->getNamaAkun($kdakun);
		echo json_encode($ref_nama_akun);
		exit;
		
	}
	
	public function ajax_update_akun(){
		$kdakun = $_POST['kdakun'];
		$kdakunold = $_POST['kdakunold'];
		$nmakun = $_POST['nmakun'];
		$ref_nama_akun = $this->model->updateNamaAkun($kdakun,$kdakunold,$nmakun);
		exit;
		
	}
	
	public function ajax_ceck_akun(){
		$kdakun = $_POST['kdakun'];
		$ref_nama_akun = $this->model->ceckAkun($kdakun);
		echo json_encode($ref_nama_akun);
		exit;
		
	}
	
	public function ajax_insert_akun(){
		// pr($_POST);
		$kdakun = $_POST['kdakun'];
		$nmakun = $_POST['nmakun'];
		$insert_akun = $this->model->insertAkun($kdakun,$nmakun);
		exit;
	}
	
	public function ajax_del_akun(){
		// pr($_POST);
		$kdakun = $_POST['kdakun'];
		$delete_akun = $this->model->deleteAkun($kdakun);
		exit;
	}
	
}

?>
