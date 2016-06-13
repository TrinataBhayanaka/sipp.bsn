<?php

class lakip extends Controller {
	
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
		$this->view->assign('user',$this->admin);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	

	function dokumenLakip()
	{
		global $basedomain,$app_domain;
		$parent_id = _g('parent_id');
		$pid = _g('pid');
		$newData = array();

		if ($pid ==1){
			$dataStruktur['type'] = 1;
			$type = 5;
			$this->view->assign('isbsn', 1);
			$parentid = 0;
		} 
		if ($pid ==2){
			$dataStruktur['type'] = 2;
			$type = 6;
			$parentid = $parent_id;
		} 
		if ($pid ==3){
			$dataStruktur['type'] = 3;
			$type = 7;
			$parentid = $parent_id;
		} 


		$getStruktur = $this->contentHelper->getStruktur($dataStruktur);
		
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
			redirect($basedomain."lakip/dokumenLakip/?pid={$pid}&parent_id=".$bsnid);
			exit;
		}

		
		$this->view->assign('bsnid', $bsnid);
		$getVisiBsn = $this->contentHelper->getVisi(false, $type, 1, $parentid);
		// pr($getVisiBsn);
		$getMisiBsn = $this->contentHelper->getVisi(false, $type, 2, $parentid);
		$getTujuanBsn = $this->contentHelper->getVisi(false, $type, 3, $parentid);
		
		$getDokumen = $this->contentHelper->getVisi(false, 16, 1, $parentid);
		
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
		// pr($newData);
		$this->view->assign('pid', $pid);
		$this->view->assign('parent_id', $parent_id);
		$this->view->assign('visi', $getVisiBsn);
		$this->view->assign('misi', $getMisiBsn);
		$this->view->assign('tujuan', $getTujuanBsn);
		$this->view->assign('dokumen', $newData);
		$this->view->assign('struktur', $getStruktur);
		return $this->loadView('lakip/dokumen/lakip');
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
			
			$isCover = false;
			$parent_id = _g('parent_id');
			$pid = _g('pid');
			
			if ($pid ==1) $parentid = 0;
			if ($pid ==2 or $pid == 3) $parentid = $parent_id;
			
			$getDokumen = $this->contentHelper->getVisi(false, 15, 1, $parent_id);
			if ($getDokumen){
				foreach ($getDokumen as $key => $value) {
					if ($value['tags']) $isCover = true;
				}
			}

			$this->view->assign('pid', $pid);
			$this->view->assign('text1', "Tahun Anggaran");
			$this->view->assign('text2', "Teks yang tampil");
			$this->view->assign('text3', "Nama File");
			$this->view->assign('text4', "No. Urut");
			$this->view->assign('text5', "Cover");
			$this->view->assign('submit', "submit");
			$this->view->assign('type', 16);
			$this->view->assign('category', 1);
			$this->view->assign('cover', $isCover);
		}

		if ($_POST['submit']){
			
			$_POST['create_date'] = date('Y-m-d H:i:s');
			$_POST['publish_date'] = date('Y-m-d H:i:s');
			$_POST['n_status'] = 1;

			$pid = $_POST['pid'];
			$parent_id = $_POST['parent_id'];
			$save = $this->contentHelper->saveData($_POST);
			if ($save){

				$getLastData = $this->contentHelper->getDocument(false, 15);
				if ($getLastData){
					if(!empty($_FILES)){
						if($_FILES['file']['name'] != ''){
							$image = uploadFile('file',null,'all');

							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['filename'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
								// if ($updateData) redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}else{
								// echo "<script>alert('File type not allowed');</script>";
								redirect($basedomain."lakip/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

						if($_FILES['cover']['name'] != ''){
							$image = uploadFile('cover',null,'all');

							if ($image['status']){
								$dataArr['id'] = $getLastData[0]['id'];
								$dataArr['tags'] = $image['full_name'];
								$updateData = $this->contentHelper->saveData($dataArr);
								// if ($updateData) redirect($basedomain."renstra/dokumenBsn/?pid={$pid}&parent_id={$parent_id}");
							}else{
								// echo "<script>alert('File type not allowed');</script>";
								redirect($basedomain."lakip/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
							}	
						}

					}
				}
				
				redirect($basedomain."lakip/dokumenLakip/?pid={$pid}&parent_id={$parent_id}");
			}
			
		}
		return $this->loadView('lakip/dokumen/input-lakip');
	}

	function delete()
	{

		global $basedomain;
		
		$id = _g('id');
		$data['id'] = $id;
		$data['n_status'] = 0;
		$save = $this->contentHelper->saveData($data);
		if ($save){
			redirect($basedomain . 'lakip/dokumenLakip');
		}
		
	}
	
}

?>
