<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class home extends Controller {
	
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
		
		// $this->contentHelper = $this->loadModel('contentHelper');
		// $this->marticle = $this->loadModel('marticle');
		// $this->mquiz = $this->loadModel('mquiz');
		$this->model = $this->loadModel('mref');
		$this->contentHelper = $this->loadModel('contentHelper');
	}
	
	public function index(){
		// pr($this->admin);
		$kegiatan = $this->model->select_data();
		// pr($kegiatan);
		$this->view->assign('data',$kegiatan);
		return $this->loadView('home/home');

	}
	
	public function proc(){
		if($_FILES['file']['name'] != ''){
			$image = uploadFile('file',null,'all');
			if ($image['status']){
				$dataArr['title'] = $_POST['judul'];
				$dataArr['filename'] = $image['full_name'];
				$dataArr['type'] = 50;
				$dataArr['n_status'] =  1;
				$dataArr['create_date'] = date('Y-m-d H:i:s');;
				$dataArr['publish_date'] = date('Y-m-d H:i:s');;
				
				$ref_nama_kegiatan = $this->model->ceck($dataArr);
			}
		}else{
			
		}
		exit;

	}

	public function sampleform()
	{
		return $this->loadView('home/sample_form');
	}
	
	public function chart(){
		
		$monthArray =array('1','2','3','4','5','6','7','8','9','10','11','12');
		
		$year = date('Y');
		
		$register_user= $this->mcourse->select_data_register_user_condt_home($monthArray,$year);
		$visitor_user= $this->mcourse->select_data_visitor_user_condt_home($monthArray,$year);
		
		$newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		print json_encode($newformat);
		exit;
	}
	
	//view glosarium
	public function glosariumlist(){
		// echo "masukk ajaa";
		$select = $this->marticle->select_data();
		// pr($select);
		$this->view->assign('data',$select);
		
		return $this->loadView('home/glosarium');

	}
	
	public function ajax_insert(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$judul =$_POST['judul'];
		$keterangan =$_POST['keterangan'];
		$n_status = 1;
		$tipe = 1;
		if ($judul != '' && $keterangan != ''){
			$insert = $this->marticle->insert_data($judul,$keterangan,$n_status,$tipe);
			// echo json_encode($data);
		}
		exit;
		
	}
	
	public function ajax_edit(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idCatatan =$_POST['idCatatan'];
		$wilayah = $_POST['wilayah'];
		
		if ($idCatatan != ''){
			$edit = $this->marticle->edit_data($idCatatan, $wilayah);
			echo json_encode($edit);
		}
		exit;
	}
	
	public function ajax_edit_wilayah(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		global $basedomain;
		$idCatatan =$_POST['kode_wilayah'];
		
		if ($idCatatan != ''){
			$edit = $this->marticle->edit_data($idCatatan, 1);
			echo json_encode($edit);
		}
		exit;
	}

	public function ajax_update(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$id = $_POST['id'];
		$judul =$_POST['judul'];
		$keterangan =$_POST['keterangan'];
		$wilayah = $_POST['wilayah'];
		if ($judul != '' && $keterangan != ''){
			$update = $this->marticle->update_data($id,$judul,$keterangan,$wilayah);
		}
		exit;
		
	}

	public function ajax_update_status(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$id = $_POST['id'];
		$status =$_POST['status'];
		$wilayah = $_POST['wilayah'];
		if($status == 1){
			$n_status = 0;
		}else{
			$n_status = 1;
		}
		if ($id != '' && $status != ''){
			$update_status = $this->marticle->update_status($id,$n_status,$wilayah);
		}
		exit;
		
	}

	function ajax_update_status_testimoni()
	{
		$id = $_POST['id'];
		$status =$_POST['status'];

		
		if($status == 1){
			$n_status = 0;
		}else{
			$n_status = 1;
		}
		if ($id != '' && $status != ''){

			$update_status = $this->mquiz->update_status_testimoni($id,$n_status);
		}
		exit;
	}
	
	public function ajax_delete(){
		
		// pr($_POST);
		// echo masuk;
		// exit;
		$kode_wilayah = $_POST['kode_wilayah'];
		$id =$_POST['id'];
		$n_status = 2;
		if ($id != ''){
			$insert = $this->marticle->delete_data($id,$n_status, $kode_wilayah);
			// echo json_encode($data);
		}
		exit;
		
	}

	public function quoteslist(){
		// echo "masukk ajaa";
		$select = $this->marticle->select_data_quotes();
		// pr($select);
		$this->view->assign('data',$select);
		
		return $this->loadView('home/quotes');

	}

	public function testimoni(){
		// echo "masukk ajaa";
		$select = $this->mquiz->getNilai();
		// pr($select);
		if ($select){
			$i = 0;
			$data = array();
			foreach ($select as $key => $value) {
				if ($value['data']){
					$unserial = unserialize($value['data']);
					// $select[$key]['idNilai'] = $value['idNilai'];
					$select[$key]['testimoni'] = $unserial['testimoni'];
					$select[$key]['status_testimoni'] = intval($unserial['status_testimoni']);
				}
			}
		}
		// pr($select);
		$this->view->assign('data',$select);
		
		return $this->loadView('home/testimoni');

	}

	public function externalLink(){
		// echo "masukk ajaa";
		$select = $this->marticle->select_data_link();
		// pr($select);
		$this->view->assign('data',$select);
		
		return $this->loadView('home/link');

	}
	
	public function ajax_insert_quotes(){
		
		global $basedomain;
		$judul =$_POST['judul'];
		$keterangan =$_POST['keterangan'];
		$wilayah = $_POST['wilayah'];
		$n_status = 1;
		$typedata = _p('type');
		if ($typedata) $tipe = 3;
		else $tipe = 2;
		if ($judul != '' && $keterangan != ''){
			$insert = $this->marticle->insert_data($judul,$keterangan,$n_status,$tipe, $wilayah);
			// echo json_encode($data);
		}
		exit;
		
	}

	function cetak()
	{
		
		global $basedomain;
		$background_certificate =  $basedomain."assets/img/certificate/bg.jpg";
		$this->reportHelper =$this->loadModel('reportHelper');
		$html = "<h1>hello world</h1>";

		// echo $html;
		// exit;
    	$generate = $this->reportHelper->loadMpdf($html, 'certificate');
    
	}

	function wilayah()
	{

		$select = $this->marticle->getWilayah($id, $all);
		// pr($select);
		$this->view->assign('data',$select);
		return $this->loadView('home/wilayah');
	}

	function test()
	{
		
		$dsn = "Driver={Microsoft Visual FoxPro Driver}; SourceType=DBF; SourceDB=/home/ovancop/Data/htdocs/sipp-ori/file_dipa/; Exclusive=No;";
		$sambung_dipa = odbc_connect( $dsn,"","");
		    if ( $sambung_dipa != 0 )   
		    {
		        echo "<strong> Tersambung SAKPA </strong></<br>";
		
			}
	}

	function getAllTableLala()
	{
		$table = $_GET['tbl'];
		$data = $this->contentHelper->getStructure($table);

		db($data);
	}

	function lalagantikolom()
	{
		$this->contentHelper->gantiTabel();

		db($done);
	}

	function getDescTableLala()
	{
		pr($_GET);	
		$table = $_GET['tbl'];
		pr($table);
		$data = $this->contentHelper->getDesc($table);

		db($data);
	}
	function getDataDebug(){
		// echo "masuk";
		// exit;
		// if($_GET['tbl'] == 1){
			$table  ="bsn_news_content";
			$where = "where type = 7 and category = 1 and n_status =1 order by id asc";
		// }elseif($_GET['tbl'] == 2){
			// $table  ="bsn_sistem_setting";
		// }
		// $table = $_GET['tbl'];
		$data = $this->contentHelper->getdatadebug($table,$where);

		db($data);
	
	}
	function deltable(){
		// echo "masukk";
		$table = 'bsn_news_content';
		$where = "where type = 7 and category = 1";
		// pr($where);
		$data = $this->contentHelper->deltable($table,$where);
	}

	function altertabel(){
		$data = $this->contentHelper->altertabel();
	}

	function altertabelmonev(){
		
		$data = $this->contentHelper->altertabelmonev();
		if($data){
			pr('sukses');
		}

	}

	function altertabelrpk(){
		$data = $this->contentHelper->altertabelrpk();
		if($data){
			pr('sukses');
		}
	}

	function altertabelrpkrevisi(){
		$data = $this->contentHelper->altertabelrpkrevisi();
		if($data){
			pr('sukses');
		}
	}

	function altertabelmonevrevisi(){
		$data = $this->contentHelper->altertabelmonevrevisi();
		if($data){
			pr('sukses');
		}
	}
	
	function altertabelmonevpp39(){
		$data = $this->contentHelper->altertabelmonevpp39();
		if($data){
			pr('sukses');
		}
	}

	function selectdebug(){
		$table  ="bsn_struktur";
		$where = "where n_status is not null order by id asc";
		$data = $this->contentHelper->getdatadebug($table,$where);

		db($data);

	}

	function updatedebug(){
		//$id =$_GET['id'];
		//pr($id);
		$table  ="bsn_struktur";
		$where = "where n_status = '0' and id = '10' order by id asc";
		$data = $this->contentHelper->getdatadebug($table,$where);
		$id = $data['0']['id'];
		//pr($id);
		//echo "masuk";
		//exit;
		$update = $this->contentHelper->upddebug($id);
		db($data);
		//echo "masuk last";
	}
}

?>
