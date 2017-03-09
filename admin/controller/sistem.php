<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class sistem extends Controller {
	
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
		
		$this->contentHelper = $this->loadModel('msistem');
		$this->userHelper = $this->loadModel('userHelper');
	}
	
	public function index(){

		return $this->loadView('home/home');

	}

	public function pengguna()
	{
		$dataUser['table'] = "ck_admin_member";
		$dataUser['condition'] = array('type'=>'1,2', 'n_status'=>'1');
		$dataUser['in'] = array('n_status','type');
		$getUser = $this->userHelper->fetchData($dataUser);
		
		if ($getUser){
			foreach ($getUser as $key => $value) {
				$dataSatker['table'] = "bsn_struktur";
				$dataSatker['condition'] = array('n_status'=>1, 'kode'=>$value['kode']);
				$getSatker = $this->userHelper->fetchData($dataSatker);
				$getUser[$key]['nama_satker'] = $getSatker[0];
				if($value['type'] == 1) $getUser[$key]['type'] = 'Admin'; else $getUser[$key]['type'] = 'Unit Kerja'; 
			}

			// pr($getUser);
			$this->view->assign('user', $getUser);
		}

		return $this->loadView('sistem/pengguna');
	}

	public function edit(){
		
		global $basedomain;

		$id = _g('id');
		if ($_POST['token']){

			// check if exist
			if (!$_POST['id']){
				$check['table'] = "ck_admin_member";
				$check['condition'] = array('type'=>'1,2', 'username'=>$_POST['username'], 'n_status'=>1);
				$check['in'] = array('type');
				$checkUser = $this->userHelper->fetchData($check);
				if ($checkUser){
					echo "<script>alert('User sudah ada'); window.location.href='{$basedomain}sistem/pengguna';</script>";
					exit;
				}
			}
			
			// db($_POST);
			if ($_POST['id']){
				$dataUser['table'] = "ck_admin_member";
				$dataUser['condition'] = array('type'=>'1,2', 'id'=>$id);
				$dataUser['in'] = array('type');
				$getUser = $this->userHelper->fetchData($dataUser);
				$salt = $getUser[0]['salt'];
			}else{
				$salt = "codekir v3.0";
				$_POST['salt'] = $salt;
			}
			
			if ($_POST['pass']!='') $_POST['password'] = sha1($_POST['pass'] . $salt);
			$_POST['register_date'] = date('Y-m-d');
			$_POST['login_count'] = 0;
			$_POST['n_status'] = 1;
			
			$getUser = $this->userHelper->saveData($_POST,"ck_admin_member");
			
			if ($getUser){
				redirect($basedomain . 'sistem/pengguna');
			}else{
				redirect($basedomain . 'sistem/edit');
			}
			
		}

		if ($id){

			$dataUser['table'] = "ck_admin_member";
			$dataUser['condition'] = array('type'=>'1,2', 'n_status'=>'1', 'id'=>$id);
			$dataUser['in'] = array('type');
			$getUser = $this->userHelper->fetchData($dataUser);
			// //pr($getUser);
			if ($getUser){

				$this->view->assign('user', $getUser[0]);
			}
			
		}

		$dataSatker['table'] = "bsn_struktur";
		$dataSatker['condition'] = array('n_status'=>'1');
		$getSatker = $this->userHelper->fetchData($dataSatker);
		// //pr($getSatker);
		$this->view->assign('satker', $getSatker);
		return $this->loadView('sistem/edit_admin');
	}

	function updateData()
	{
		global $basedomain;

		$id = _g('id');
		$dataUser['id'] = $id;
		$dataUser['n_status'] = 0;
		$getUser = $this->userHelper->UpdateData($dataUser);
		
		redirect($basedomain . 'sistem/pengguna');
		
	}
	
	
}

?>
