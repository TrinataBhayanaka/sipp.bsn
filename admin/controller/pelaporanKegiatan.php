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
		// pr($this->admin);
		$this->view->assign('app_domain',$app_domain);
	}
	
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->modelMptn = $this->loadModel('mptn');
		$this->model = $this->loadModel('mcapaian');
	}
	
	public function index(){
		
		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->listData('bsn','1');
		}else{
			return $this->listData('bsn','1');
		}

	}
	public function bsn(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->listData('bsn','1',true);
		}else{
			return $this->listData('bsn','1');
		}

	}
	public function eselon1(){
		
		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->listData('es1','2',true);
		}else{
			return $this->listData('es1','2');
		}

	}

	public function eselon2(){
		
		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1 || $Struktur[type]==3){
			return $this->listData('es2','3',true);
		}else{
			return $this->listData('es2','3');
		}


	}
	public function formbsn(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->form(1,'1','840000');
		}else{
			redirect($basedomain . "bsn");
		}

	}
	public function formes1(){
		
		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->form(3,'2',$_GET['kd']);
		}else{
			redirect($basedomain . "es1");
		}

	}
	public function formes2(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1 || $Struktur[type]==3){
			return $this->form(4,'3',$_GET['kd']);
		}else{
			redirect($basedomain . "es2");
		}

	}
	public function editFormbsn(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->editForm(1,$_GET['id']);
		}else{
			redirect($basedomain . "bsn");
		}

	}
	public function editFormes1(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1){
			return $this->editForm(3,$_GET['id']);
		}else{
			redirect($basedomain . "es1");
		}

	}
	public function editFormes2(){

		$Struktur=$this->model->getStruktur($this->admin[kode]);

		if($Struktur[type]==1 || $Struktur[type]==3){
			return $this->editForm(4,$_GET['id']);
		}else{
			redirect($basedomain . "es2");
		}

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
			$_POST['iduser'] 	  = $this->admin['id'];
			$_POST['kodeUser'] 	  = $this->admin['kode'];
			$_POST['n_status'] = 1;
			if($_POST['categoryType']==1){
				$url="bsn";
			}elseif($_POST['categoryType']==2){
				$url="eselon1";
			}elseif($_POST['categoryType']==3){
				$url="eselon2";
			}
		// pr($_POST);
			$save = $this->contentHelper->saveData($_POST,"_capaian");
			if ($save) redirect($basedomain . $url);
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
			if($_POST['categoryType']==1){
				$url="bsn";
			}elseif($_POST['categoryType']==2){
				$url="eselon1";
			}elseif($_POST['categoryType']==3){
				$url="eselon2";
			}
			$save = $this->contentHelper->saveData($_POST,"_capaian");
			if ($save) redirect($basedomain . $url);
		}

		return $this->loadView('pelaporanKegiatan/capaian/form');
	}

	function dokumenLakip()
	{
		global $basedomain;
		$parent_id = _g('parent_id');
		$pid = _g('pid');
		$newData = array();

		
		if ($pid ==1){
			$dataStruktur['type'] = 1;
			$type = 5;
			$this->view->assign('isbsn', 1);

			$Struktur=$this->model->getStruktur($this->admin[kode]);

			if($Struktur[type]==1){

				$this->view->assign('button', true);
			}else{

				$this->view->assign('button', false);
			}
		} 
		if ($pid ==2){
			$dataStruktur['type'] = 2;
			$type = 6;
			$Struktur=$this->model->getStruktur($this->admin[kode]);

			if($Struktur[type]==1){

				$this->view->assign('button', true);
			}else{

				$this->view->assign('button', false);
			}
		} 
		if ($pid ==3){
			$dataStruktur['type'] = 3;
			$type = 7;
			$Struktur=$this->model->getStruktur($this->admin[kode]);

			if($Struktur[type]==1 || $Struktur[type]==3 ){

				$this->view->assign('button', true);
			}else{

				$this->view->assign('button', false);
			}
		} 

		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		// pr($getStruktur);
		
		if ($pid==1){
			foreach ($getStruktur as $key => $value) {
				if ($value['kode']=='840000') $bsnid = $value['id'];
			}
		}
		

		if (!$parent_id){
			if (!$pid) $pid = 1;
			
			if ($pid==1){
				foreach ($getStruktur as $key => $value) {
					if ($value['kode']=='840000') $bsnid = $value['id'];
				}
			}else{
				$bsnid = $getStruktur[0]['id'];
			}
			redirect($basedomain."pelaporanKegiatan/dokumenLakip/?pid={$pid}&parent_id=".$bsnid);
			exit;
		}
		// pr($bsnid);
		
		$this->view->assign('bsnid', $bsnid);

		$getVisiBsn = $this->model->getVisi(false, $type, 1,$parent_id);
		$getMisiBsn = $this->model->getVisi(false, $type, 2,$parent_id);
		$getTujuanBsn = $this->model->getVisi(false, $type, 3,$parent_id);
		
		$getDokumen = $this->model->getVisi(false, 16, 1, $parent_id);
		// pr($getDokumen);
		if ($getDokumen){
			foreach ($getDokumen as $key => $value) {
				$tags[$value['id']] = $value['tags'];
			}

			if (is_array($tags)){
				asort($tags);
				foreach ($tags as $key => $val) {
					foreach ($getDokumen as $k => $value) {
						if ($key == $value['id']) $newData[] = $value;
					}
				}
			}
			

		}

		$this->view->assign('pid', $pid);
		$this->view->assign('parent_id', $parent_id);
		// pr($getStruktur);
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);
		$this->view->assign('dokumen', $newData);
		$this->view->assign('struktur', $getStruktur);
		return $this->loadView('pelaporanKegiatan/dokumen/bsn');
	}

	function editDokumen()
	{	
		global $basedomain;

		
		$id = _g('id');
		$req = _g('req');
		$pid = _g('pid');
		$dataStruktur['id'] = _g('parent_id');
		
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);

		$getSetting = $this->contentHelper->getSetting();
		$this->view->assign('setting', $getSetting);
		if ($req==1){

			if ($id){
				$getVisiBsn = $this->contentHelper->getVisi($id, 15, 1);
				$this->view->assign('text1value', $getVisiBsn[0]['title']);
				$this->view->assign('text2value', $getVisiBsn[0]['desc']);
				$this->view->assign('text3value', $getVisiBsn[0]['filename']);
				$this->view->assign('valueid', $id);
				$this->view->assign('parent_id', $getStruktur);
				$this->view->assign('parent_name', $id);
				
			}else{
				$this->view->assign('parent_id', $getStruktur[0]['id']);
				$this->view->assign('parent_name', $getStruktur[0]['nama_satker']);
				$this->view->assign('text1value', $getSetting[0]['kode']);
				$this->view->assign('text2value', "");
				
			}
			
			$this->view->assign('pid', $pid);
			$this->view->assign('text1', "Tahun Anggaran");
			$this->view->assign('text2', "Teks yang tampil");
			$this->view->assign('text3', "Nama File");
			$this->view->assign('text4', "No. Urut");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 16);
			$this->view->assign('category', 1);
		}

		if ($_POST['submit']){
			
				// pr($_POST['submit']);
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$pid = $_POST['pid'];
			$parent_id = $_POST['parent_id'];
			$save = $this->contentHelper->saveData($_POST,"_news_content");
				// pr($save);
			if ($save){

				$getLastData = $this->contentHelper->getDocument(false, 16);
				// pr($getLastData);
				if ($getLastData){
					if(!empty($_FILES)){
						if($_FILES['file']['name'] != ''){
							$image = uploadFile('file');
							// pr($_FILES);
							// pr($_POST);
							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['filename'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
							// pr($dataArr);
							// pr($image);exit;
								if ($updateData) redirect($basedomain."pelaporanKegiatan/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
							}else{
								echo "<script>alert('File type not allowed');</script>";
								// redirect($basedomain."pelaporanKegiatan/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

					}
				}
				
			}
			
			// if ($save) redirect($basedomain . 'renstra/sasaran');
		}

		return $this->loadView('pelaporanKegiatan/dokumen/input-bsn');
	}
function delete()
	{

		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		$pid = $_POST['pid'];
		$parent_id = $_POST['parent_id'];


		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data);
		if ($save){
			redirect($basedomain . "pelaporanKegiatan/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
		}
		
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

	function listData($url,$id=1,$button=false,$formSearch=true){
		if($url){

			$struktur = $this->model->getStrukturAll($id);
// pr($_POST);
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

			$result=$this->model->getcapaian($id,$parent);
			// pr($struktur);
			if($result){
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

					$Struktur=$this->model->getStruktur($this->admin[kode]);
					// pr($Struktur);
					// pr($struktur);

					if($Struktur[type]==1){

						$data[$key]['linkEdit']=true;

					}elseif($Struktur[type]==3 && $value['kodeUser']==$this->admin[kode]){

						$data[$key]['linkEdit']=true;

					}
				}
			}
			// pr($url);
			if($url=="es1"){
				$formUrl="eselon1";
			}elseif($url=="es2"){
				$formUrl="eselon2";

			}else{
				$formUrl="bsn";
			}
			if($parent==1){
				$formSearch=false;
			}
			$this->view->assign('buttonId',$parent);

			$this->view->assign('formSearch',$formSearch);
			$this->view->assign('button',$button);
			$this->view->assign('formUrl',$formUrl);
			$this->view->assign('kd',$idpk);
			$this->view->assign('struktur',$struktur);
			$this->view->assign('data',$data);
			$this->view->assign('url',$url);
			// pr($data);exit;
			return $this->loadView('pelaporanKegiatan/capaian/index');
		}else{
			return false;
		}
	}

	function form($id,$type,$kd=false){
		if($id){
			global $basedomain;
			$dataSasaran = $this->modelMptn->selectSS($id,$kd);


			$this->view->assign('dataSasaran',$dataSasaran);
			$this->view->assign('type',$type);
			$this->view->assign('parent',$_GET['parent']);

			// pr($dataSasaran);exit;
			return $this->loadView('pelaporanKegiatan/capaian/form');
		}else{
			return false;
		}
	}

	function editForm($id,$get){
		global $basedomain;

		$dataSasaran = $this->modelMptn->selectSS($id);
		$data = $this->model->getcapaianID($get);
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


	
}

?>
