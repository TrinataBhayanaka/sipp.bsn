<?php

class setting extends Controller {
	
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
		
		$this->userHelper = $this->loadModel('userHelper');
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){
       
		
		$dataUser = $this->userHelper->getUserData();

		
		if ($dataUser){

			foreach ($dataUser as $key => $value) {
				$dataUser[$key]['change_date'] = changeDate($value['register_date']);
				
			}
		}

		
		$this->view->assign('data',$dataUser);
		return $this->loadView('user/userlist');

	}

	function tahun()
	{
		global $basedomain;

		$getSetting = $this->contentHelper->getSetting();
		if ($getSetting){
			$this->view->assign('text1value',$getSetting[0]['kode']);
			$this->view->assign('text2value',$getSetting[0]['data']);
			$this->view->assign('desc',$getSetting[0]['desc']);
			$this->view->assign('id',$getSetting[0]['id']);
		}else{
			$this->view->assign('text1value',"");
			$this->view->assign('text2value',"");
			$this->view->assign('desc',"tahun_sistem");
			$this->view->assign('id',"");
		}
		

		if ($_POST['submit']){
			
			
			$_POST['n_status'] = 1;

			$save = $this->contentHelper->saveData($_POST, '_sistem_setting');
			if ($save) redirect($basedomain . 'setting/tahun');
		}

		return $this->loadView('setting/tahun');
	}
    
	function deltable(){
		echo "masukk";
		$table = 'bsn_news_content';
		$where = "where type = '7' and category = '1'";
		// pr($where);
		$data = $this->contentHelper->deltable($table,$where);
	}
}

?>
