<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class pelaporanKegiatan extends Controller {
	
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
		$this->modelMptn = $this->loadModel('mptn');
		$this->model = $this->loadModel('mcapaian');
	}
	
	public function index(){
		$result=$this->model->getcapaian();
		// pr($result);
		foreach ($result as $key => $value) {
			$twn1=(array)json_decode($value['twn1']);
			$twn2=(array)json_decode($value['twn2']);
			$twn3=(array)json_decode($value['twn3']);
			$twn4=(array)json_decode($value['twn4']);

			$data[$key]=$value;
			$data[$key]['triwulan1_trgtO']=$twn1['trgtO'];
			$data[$key]['triwulan1_trgtP']=$twn1['trgtP'];
			$data[$key]['triwulan1_ntrgt']=$twn1['ntrgt'];
			$data[$key]['triwulan1_realO']=$twn1['realO'];
			$data[$key]['triwulan1_realP']=$twn1['realP'];
			$data[$key]['triwulan1_nreal']=$twn1['nreal'];

			$data[$key]['triwulan2_trgtO']=$twn2['trgtO'];
			$data[$key]['triwulan2_trgtP']=$twn2['trgtP'];
			$data[$key]['triwulan2_ntrgt']=$twn2['ntrgt'];
			$data[$key]['triwulan2_realO']=$twn2['realO'];
			$data[$key]['triwulan2_realP']=$twn2['realP'];
			$data[$key]['triwulan2_nreal']=$twn2['nreal'];

			$data[$key]['triwulan3_trgtO']=$twn3['trgtO'];
			$data[$key]['triwulan3_trgtP']=$twn3['trgtP'];
			$data[$key]['triwulan3_ntrgt']=$twn3['ntrgt'];
			$data[$key]['triwulan3_realO']=$twn3['realO'];
			$data[$key]['triwulan3_realP']=$twn3['realP'];
			$data[$key]['triwulan3_nreal']=$twn3['nreal'];

			$data[$key]['triwulan4_trgtO']=$twn4['trgtO'];
			$data[$key]['triwulan4_trgtP']=$twn4['trgtP'];
			$data[$key]['triwulan4_ntrgt']=$twn4['ntrgt'];
			$data[$key]['triwulan4_realO']=$twn4['realO'];
			$data[$key]['triwulan4_realP']=$twn4['realP'];
			$data[$key]['triwulan4_nreal']=$twn4['nreal'];
		}
		$this->view->assign('data',$data);
		// pr($data);exit;
		return $this->loadView('pelaporanKegiatan/capaian/index');

	}
	public function form(){
		global $basedomain;
		$dataSasaran = $this->modelMptn->selectSS(1);


		$this->view->assign('dataSasaran',$dataSasaran);

		// pr($dataSasaran);exit;
		return $this->loadView('pelaporanKegiatan/capaian/form');
	}
	public function editForm(){
		global $basedomain;

		$dataSasaran = $this->modelMptn->selectSS(1);
		$data = $this->model->getcapaianID($_GET['id']);
		// pr($dataSasaran);
			$twn1=(array)json_decode($data['twn1']);
			$twn2=(array)json_decode($data['twn2']);
			$twn3=(array)json_decode($data['twn3']);
			$twn4=(array)json_decode($data['twn4']);

			$data['triwulan1_trgtO']=$twn1['trgtO'];
			$data['triwulan1_trgtP']=$twn1['trgtP'];
			$data['triwulan1_ntrgt']=$twn1['ntrgt'];
			$data['triwulan1_realO']=$twn1['realO'];
			$data['triwulan1_realP']=$twn1['realP'];
			$data['triwulan1_nreal']=$twn1['nreal'];

			$data['triwulan2_trgtO']=$twn2['trgtO'];
			$data['triwulan2_trgtP']=$twn2['trgtP'];
			$data['triwulan2_ntrgt']=$twn2['ntrgt'];
			$data['triwulan2_realO']=$twn2['realO'];
			$data['triwulan2_realP']=$twn2['realP'];
			$data['triwulan2_nreal']=$twn2['nreal'];

			$data['triwulan3_trgtO']=$twn3['trgtO'];
			$data['triwulan3_trgtP']=$twn3['trgtP'];
			$data['triwulan3_ntrgt']=$twn3['ntrgt'];
			$data['triwulan3_realO']=$twn3['realO'];
			$data['triwulan3_realP']=$twn3['realP'];
			$data['triwulan3_nreal']=$twn3['nreal'];

			$data['triwulan4_trgtO']=$twn4['trgtO'];
			$data['triwulan4_trgtP']=$twn4['trgtP'];
			$data['triwulan4_ntrgt']=$twn4['ntrgt'];
			$data['triwulan4_realO']=$twn4['realO'];
			$data['triwulan4_realP']=$twn4['realP'];
			$data['triwulan4_nreal']=$twn4['nreal'];

		$dataIndikator = $this->model->getAllpk($data['sasaran']);
		// pr($dataIndikator);
		$this->view->assign('dataSasaran',$dataSasaran);
		$this->view->assign('dataIndikator',$dataIndikator);
		$this->view->assign('data',$data);

		// pr($dataSasaran);exit;
		return $this->loadView('pelaporanKegiatan/capaian/editform');
	}
	public function insertForm(){
		// pr($_POST);

		$_POST['twn1']=json_encode($_POST['twn1']);
		$_POST['twn2']=json_encode($_POST['twn2']);
		$_POST['twn3']=json_encode($_POST['twn3']);
		$_POST['twn4']=json_encode($_POST['twn4']);
		// pr($twn1);
		// pr($_POST);
// exit;
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['change_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST,"_capaian");
			if ($save) redirect($basedomain . 'index');
		}

		return $this->loadView('pelaporanKegiatan/capaian/form');
	}
	public function updateForm(){
		// pr($_POST);

		$_POST['twn1']=json_encode($_POST['twn1']);
		$_POST['twn2']=json_encode($_POST['twn2']);
		$_POST['twn3']=json_encode($_POST['twn3']);
		$_POST['twn4']=json_encode($_POST['twn4']);
		// pr($twn1);
		// pr($_POST);
// exit;
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['change_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST,"_capaian");
			if ($save) redirect($basedomain . 'index');
		}

		return $this->loadView('pelaporanKegiatan/capaian/form');
	}
	function getData(){
		$idSsr=$_POST['id'];
		$dataSasaran = $this->model->getAllpk($idSsr);

		$this->view->assign('data',$dataSasaran);

		$data_layout=$this->loadView('pelaporanKegiatan/capaian/select');
// pr($data_layout);
		if ($dataSasaran){
            print json_encode(array('status'=>true, 'data'=>$data_layout));
        }else{
            print json_encode(array('status'=>false,'data'=>$data_layout));
        }
        
        exit;

	}
	function getTarget(){
		$idSsr=$_POST['id'];
		$dataSasaran = $this->model->getIDpk($idSsr);

		// pr($dataSasaran);

		if ($dataSasaran){
            print json_encode(array('status'=>true, 'data'=>$dataSasaran['target']));
        }else{
            print json_encode(array('status'=>false));
        }
        
        exit;

	}


	
}

?>
