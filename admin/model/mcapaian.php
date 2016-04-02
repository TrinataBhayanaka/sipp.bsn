<?php
class mcapaian extends Database {
	
	var $prefix = "bsn";

	function __construct()
	{
		parent::__construct();
	}
	function getStrukturAll($type)
	{
		$sql = "SELECT * FROM bsn_struktur WHERE type='{$type}' AND n_status='1'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	function getStruktur($kode)
	{
		if($kode){
			$kode=$kode;
		}else{
			$kode="840000";
		}
		$sql = "SELECT * FROM bsn_Struktur WHERE kode='{$kode}'";
		// db($sql);
		$res = $this->fetch($sql);

		if ($res) return $res;
		return false;
	}
	function getcapaian($id,$parent=0)
	{
		$sql = "SELECT cp.*,b.desc,pk.nm_pk, pk.target FROM bsn_capaian as cp, bsn_news_content as b ,th_pk as pk WHERE cp.parent_id='{$parent}' AND cp.sasaran = b.id AND cp.indikator = pk.id AND b.type='7' AND b.category = '1' AND cp.categoryType='{$id}' ORDER BY cp.sasaran, cp.indikator";
		// db($sql);
		$res = $this->fetch($sql,1);

		foreach ($res as $key => $value) {
			if($value['desc'] == $res[$key-1]['desc'] || $value['desc'] == $tmp)
			{
				$res[$key]['desc'] = "";
				$tmp = $value['desc'];
			} else {
				$tmp = "";
			}
		}
		if ($res) return $res;
		return false;
	}

	function getcapaianID($id)
	{
		$sql = "SELECT * FROM bsn_capaian WHERE id='{$id}'";
		// db($sql);
		$res = $this->fetch($sql);

		if ($res) return $res;
		return false;
	}
	function getAllpk($idSsr){
		$sql = "SELECT * FROM th_pk WHERE no_sasaran='{$idSsr}'";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	function getIDpk($idSsr){
		$sql = "SELECT * FROM th_pk WHERE id='{$idSsr}'";
		$res = $this->fetch($sql);
		if ($res) return $res;
		return false;
	}
	function saveData($data=array(), $table="bsn_news_content", $debug=0)
	{
		// pr($data);
		if ($table == "bsn_news_content"){
			$getSetting = $this->getSetting();
			$data['year'] = $getSetting[0]['kode'];

		}
		
		$id = $data['id'];

		if ($id){

			$run = $this->save("update", "{$table}", $data, "id = {$id}", $debug);

		}else{
			$data['createDate'] = date('Y-m-d H:i;s');
			$run = $this->save("insert", "{$table}", $data, false, $debug);
	
		}
		// pr($data);
		// pr($run);
		// exit;
		if ($run) return true;
		return false;
	}
	function getVisi($id=false, $type=5, $cat=0, $parent=0, $debug=false)
	{
		$filter = "";
		if ($id) $filter .= " AND id = {$id}";
		if ($type) $filter .= " AND type = {$type}";
		if ($cat) $filter .= " AND category = {$cat}";
		if($parent==1) $parent=0; 
		if ($parent) $filter .= " AND parent_id = {$parent}";

		$sql = array(
                'table'=>"{$this->prefix}_news_content",
                'field'=>"*",
                'condition' => "n_status = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res)return $res;
        return false;
	}
	
}
?>