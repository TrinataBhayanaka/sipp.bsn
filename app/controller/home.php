<?php

class home extends Controller {
	
	var $models = FALSE;
	var $view;
	var $reportHelper;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
		$getUserLogin = $this->isUserOnline();
		$this->user = $getUserLogin[0];
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
        $this->userHelper = $this->loadModel('userHelper');
        $this->userGallery=$this->loadModel('mgallery');
        $this->userNews=$this->loadModel('mnews');
        $this->quizHelper = $this->loadModel('quizHelper');
	}
	
	function index(){

		global $CONFIG,$basedomain;
		
		redirect($basedomain.$CONFIG['admin']['default_view']);
		exit;
    }

   
}

?>
