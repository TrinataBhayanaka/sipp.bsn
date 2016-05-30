<?php

class struktur extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->marticle = $this->loadModel('marticle');
		$this->mquiz = $this->loadModel('mquiz');
		$this->mcourse = $this->loadModel('mcourse');
	}
	
	public function index(){

		$getStruktur = $this->contentHelper->getContent(false, '_struktur');
		if ($getStruktur){
			$this->view->assign('struktur', $getStruktur);
		}
		return $this->loadView('struktur/strukturOrganisasi');

	}
	public function bsn(){

		$data['type'] = 1;
		$data['category'] = 1;
		$getStruktur = $this->contentHelper->getContent($data);
		
		if ($getStruktur){
			foreach ($getStruktur as $key => $value) {
				$fungsi['type'] = 1;
				$fungsi['category'] = 2;
				$fungsi['parent_id'] = $value['id'];
				$getFungsi = $this->contentHelper->getContent($fungsi);
				if ($getFungsi){
					$getStruktur[$key]['fungsi'] = $getFungsi;
				}
			}

			$this->view->assign('struktur', $getStruktur);
		}
		return $this->loadView('struktur/bsn');

	}
	public function eselon1(){

		global $basedomain;

		$data['type'] = 2;
		$data['category'] = 1;
		
		$parent_id = _g('parent_id');
		$dataStruktur['type'] = 2;
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);

		if (!$parent_id){
			redirect($basedomain."struktur/eselon1/?parent_id=".$getStruktur[0]['id']);
			exit;
		}
		$this->view->assign('struktur', $getStruktur);
		$this->view->assign('eselon_id', $parent_id);
		$getTugas = $this->contentHelper->getContent($data);
		
		if ($getTugas){
			foreach ($getTugas as $key => $value) {
				$a['id'] = $value['title'];
				$struktur = $this->contentHelper->getContent($a, '_struktur');
				if ($struktur)$getTugas[$key]['nama_satker'] = $struktur[0]['nama_satker'];
				$fungsi['type'] = 2;
				$fungsi['category'] = 2;
				$fungsi['parent_id'] = $value['id'];
				$getFungsi = $this->contentHelper->getContent($fungsi);
				if ($getFungsi){
					$getTugas[$key]['fungsi'] = $getFungsi;
				}
			}
			// pr($getTugas);
			// exit;
			$this->view->assign('tugas', $getTugas);
			
		}

		return $this->loadView('struktur/eseloni');

	}

	public function eselon2(){
		global $basedomain;

		$data['type'] = 3;
		$data['category'] = 1;
		
		$parent_id = _g('parent_id');
		$dataStruktur['type'] = 3;
		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);

		if (!$parent_id){
			redirect($basedomain."struktur/eselon2/?parent_id=".$getStruktur[0]['id']);
			exit;
		}
		$this->view->assign('struktur', $getStruktur);
		$this->view->assign('eselon_id', $parent_id);
		$getTugas = $this->contentHelper->getContent($data);
		
		if ($getTugas){
			foreach ($getTugas as $key => $value) {
				$a['id'] = $value['title'];
				$struktur = $this->contentHelper->getContent($a, '_struktur');
				if ($struktur)$getTugas[$key]['nama_satker'] = $struktur[0]['nama_satker'];
				$fungsi['type'] = 3;
				$fungsi['category'] = 2;
				$fungsi['parent_id'] = $value['id'];
				$getFungsi = $this->contentHelper->getContent($fungsi);
				if ($getFungsi){
					$getTugas[$key]['fungsi'] = $getFungsi;
				}
			}

			$this->view->assign('tugas', $getTugas);
		}
		return $this->loadView('struktur/eselon2');

	}

	function editStruktur()
	{
		global $basedomain;
		// pr($_POST);
		// exit;
		$id = _g('id');
		
		if ($id){
			$data['id'] = $id;
			$getStruktur = $this->contentHelper->getContent($data, '_struktur');

			$kode = $getStruktur[0]['kode'];
			$nama_satker =$getStruktur[0]['nama_satker'];
			$singkatan = $getStruktur[0]['singkatan'];
			$custom_text = unserialize($getStruktur[0]['custom_text']);
			$pejabat = $custom_text['pejabat'];
			$this->view->assign('satker', $getStruktur);
		}else{
			$kode = "";
			$nama_satker ="";
			$singkatan = "";
		}
		$dataForm[] = array('text'=>true, 'title'=>'Kode', 'name'=>'kode', 'value'=>$kode);
		$dataForm[] = array('textarea'=>true, 'title'=>'Unit Kerja', 'name'=>'nama_satker', 'value'=>$nama_satker);
		$dataForm[] = array('text'=>true, 'title'=>'Singkatan', 'name'=>'singkatan', 'value'=>$singkatan);
		$dataForm[] = array('text'=>true, 'title'=>'Nama Pejabat', 'name'=>'pejabat', 'value'=>$pejabat);
		$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
		$this->view->assign('submit', "submit");

		$generataField = $this->generateField($dataForm);
		$this->view->assign('form', $generataField);
		if ($_POST['submit']){
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			if ($_POST['pejabat']) $_POST['custom_text'] = serialize(array('pejabat'=>$_POST['pejabat']));
			$save = $this->contentHelper->saveData($_POST, "_struktur");
			if ($save) redirect($basedomain . 'struktur/index');
		}

		return $this->loadView('struktur/input-struktur');
	}
	
	function editBsn()
	{
		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		
		if ($id){
			$data['id'] = $id;
			$getStruktur = $this->contentHelper->getContent($data);

			$kode = $getStruktur[0]['title'];
			$nama_satker =$getStruktur[0]['desc'];
			
		}else{
			$kode = "";
			$nama_satker ="";
			
		}

		if ($req == 1){

			$data['id'] = _g('parent_id');
			$getStruktur = $this->contentHelper->getContent($data);

			$kode = $getStruktur[0]['title'];
			$nama_satker =$getStruktur[0]['desc'];

			if ($id){
				$data1['id'] =$id;
				$getStruktur1 = $this->contentHelper->getContent($data1);
				$desc = $getStruktur1[0]['desc'];
			}else{
				$desc = "";
			}
			$dataForm[] = array('text'=>true, 'title'=>'Kementerian', 'name'=>'title', 'value'=>$kode, 'readonly'=>'readonly');
			$dataForm[] = array('text'=>true, 'title'=>'Tugas Pokok', 'name'=>'desc', 'value'=>$nama_satker, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Fungsi', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$data['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>2);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>1);
		}else{
			$dataForm[] = array('text'=>true, 'title'=>'Kementrian', 'name'=>'title', 'value'=>$kode);
			$dataForm[] = array('textarea'=>true, 'title'=>'Tugas Pokok', 'name'=>'desc', 'value'=>$nama_satker);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>1);
		}
		
		$this->view->assign('submit', "submit");

		$generataField = $this->generateField($dataForm);
		$this->view->assign('form', $generataField);
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'struktur/bsn');
		}

		return $this->loadView('struktur/input-bsn');
	}

	function editEselon1()
	{
		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		
		$tupoksiData['type'] = 2;
		$tupoksiData['category'] = 1;
		$getTugas = $this->contentHelper->getContent($tupoksiData);
		$tupoksiExist = [];
		if ($getTugas){
			foreach ($getTugas as $key => $value) {
				$tupoksiExist[] = $value['title'];
			}
		}

		$dataStruktur['type'] = 2;
		$getStrukturOrgData = $this->contentHelper->getStruktur($dataStruktur);
		/*
		if (count($tupoksiExist > 0)){
			foreach ($getStrukturOrgData as $key => $value) {
				if (!in_array($value['id'], $tupoksiExist)) $getStrukturOrg[] = $value;
			}
		}else{
			$getStrukturOrg = $getStrukturOrgData;
		}
		*/
		$getStrukturOrg = $getStrukturOrgData;
		if ($id){
			$data['id'] = $id;
			$getStruktur = $this->contentHelper->getContent($data);
			$kode = $getStruktur[0]['title'];
			$nama_satker =$getStruktur[0]['desc'];
			$this->view->assign('kegiatan', $getStruktur);
		}else{
			$kode = "";
			$nama_satker ="";
			
		}

		if ($req == 1){

			$data['id'] = _g('parent_id');
			$getStruktur2 = $this->contentHelper->getContent($data);
			if ($getStruktur2){
				foreach ($getStruktur2 as $key => $value) {
					$a['id'] = $value['title'];
					$struktur = $this->contentHelper->getContent($a, '_struktur');
					if ($struktur)$getStruktur2[$key]['nama_satker'] = $struktur[0]['nama_satker'];
				}
			}
			

			$kode = $getStruktur2[0]['nama_satker'];
			$nama_satker =$getStruktur2[0]['desc'];

			if ($id){
				$data1['id'] =$id;
				$getStruktur1 = $this->contentHelper->getContent($data1);

				$desc = $getStruktur1[0]['desc'];
			}else{
				$desc = "";
			}

			$dataForm[] = array('text'=>true, 'title'=>'Kode', 'name'=>'title', 'value'=>$kode, 'readonly'=>'readonly');
			$dataForm[] = array('text'=>true, 'title'=>'Kementerian', 'name'=>'desc', 'value'=>$nama_satker, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Fungsi', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$data['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>2);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>2);
			$this->view->assign('req', 1);
		}else{
			$dataForm[] = array('textarea'=>true, 'title'=>'Tugas Pokok', 'name'=>'desc', 'value'=>$nama_satker);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>2);
		}
		
		$this->view->assign('struktur', $getStrukturOrg);
		$this->view->assign('submit', "submit");

		$generataField = $this->generateField($dataForm);
		$this->view->assign('form', $generataField);
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'struktur/eselon1');
		}

		return $this->loadView('struktur/input-eselon1');
	}


	function editEselon2()
	{
		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		
		$dataStruktur['type'] = 3;
		$getStrukturOrg = $this->contentHelper->getStruktur($dataStruktur);

		if ($id){
			$data['id'] = $id;
			$getStruktur = $this->contentHelper->getContent($data);

			$kode = $getStruktur[0]['title'];
			$nama_satker =$getStruktur[0]['desc'];
			$this->view->assign('kegiatan', $getStruktur);
		}else{
			$kode = "";
			$nama_satker ="";
			
		}

		if ($req == 1){

			$data['id'] = _g('parent_id');
			$getStruktur2 = $this->contentHelper->getContent($data);
			if ($getStruktur2){
				foreach ($getStruktur2 as $key => $value) {
					$a['id'] = $value['title'];
					$struktur = $this->contentHelper->getContent($a, '_struktur');
					if ($struktur)$getStruktur2[$key]['nama_satker'] = $struktur[0]['nama_satker'];
				}
			}
			

			$kode = $getStruktur2[0]['nama_satker'];
			$nama_satker =$getStruktur2[0]['desc'];

			if ($id){
				$data1['id'] =$id;
				$getStruktur1 = $this->contentHelper->getContent($data1);

				$desc = $getStruktur1[0]['desc'];
			}else{
				$desc = "";
			}

			$dataForm[] = array('text'=>true, 'title'=>'Kode', 'name'=>'title', 'value'=>$kode, 'readonly'=>'readonly');
			$dataForm[] = array('text'=>true, 'title'=>'Kementerian', 'name'=>'desc', 'value'=>$nama_satker, 'disabled'=>'disabled');
			$dataForm[] = array('textarea'=>true, 'title'=>'Fungsi', 'name'=>'desc', 'value'=>$desc);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'parent_id', 'value'=>$data['id']);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>2);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>3);
			$this->view->assign('req', 1);
		}else{
			$dataForm[] = array('textarea'=>true, 'title'=>'Tugas Pokok', 'name'=>'desc', 'value'=>$nama_satker);
			$dataForm[] = array('hidden'=>1, 'name'=>'id', 'value'=> $id);
			$dataForm[] = array('hidden'=>1, 'name'=>'category', 'value'=>1);
			$dataForm[] = array('hidden'=>1, 'name'=>'type', 'value'=>3);
		}
		
		$this->view->assign('struktur', $getStrukturOrg);
		$this->view->assign('submit', "submit");

		$generataField = $this->generateField($dataForm);
		$this->view->assign('form', $generataField);
		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;
			
			$save = $this->contentHelper->saveData($_POST);
			if ($save) redirect($basedomain . 'struktur/eselon2');
		}

		return $this->loadView('struktur/input-eselon2');
	}

	function delete()
	{

		global $basedomain;

		$id = _g('id');
		$req = _g('req');
		$param = _g('param');
		
		if ($req == 1) $link = 'struktur';
		if ($req == 2) $link = 'struktur/bsn';
		if ($req == 3) $link = 'struktur/eselon1';
		if ($req == 4) $link = 'struktur/eselon2';
		
		if ($param ==1) $table = "_struktur";
		else $table = "_news_content";
		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data,$table);
		if ($save){
			redirect($basedomain . $link);
		}
		
	}

	function generateField($data=array(), $is_hidden=false)
	{
		$html = "";
		$arrayHtml = array();
		foreach ($data as $key => $value) {
			

			if ($value['hidden']){ 
				$html .= "<input type='hidden' name='{$value['name']}' value='{$value['value']}'>";
			}else{
				$html .= "<div class='form-group'>";
				$html .=		"<label class='col-sm-3 control-label'>{$value['title']}</label>";
				$html .=		"<div class='col-sm-4'>";
				if ($value['text']){
					$html .=		"<input type='text' name='{$value['name']}' class='form-control' value='{$value['value']}' required {$value['readonly']} {$value['disabled']}>";
				}
				if ($value['textarea']){
					$html .= "<textarea rows='5' id='{$value['name']}' name='{$value['name']}' class='form-control' required {$value['readonly']} {$value['disabled']}>{$value['value']}</textarea>"; 
				}
				$html .=		"</div>";
				$html .=	"</div>";
			}
			
			$arrayHtml[] = $html; 
			$html ="";
		}

		if ($arrayHtml) return $arrayHtml;
		else return false;
	}
}

?>
