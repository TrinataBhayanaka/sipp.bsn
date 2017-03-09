<?php
class m_pelaporankeuangan extends Database {
	
	// m_spmmak dam m_spmind (upload foxpro dari iman)
	// d_item ,d_trktrm (upload foxpro dari bayu)
	
	// m_spmmak
	function select_data_master_bsn($thn_temp)
	{
		$query = "select THANG, KDSATKER, sum(NILMAK) as real_satker from m_spmmak WHERE THANG='{$thn_temp}' AND left(KDAKUN,1) = '5' group by KDSATKER";
		// $query = "select THANG, KDSATKER, sum(jumlah) as pagu_satker from d_item  WHERE THANG = '{$thn_temp}' group by KDSATKER";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function cek_data_upload()
	{
		$query = "select tgl_upload from dt_fileupload_keu WHERE kdfile = 'M_SPMIND'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	// m_spmmak
	function cek_realisasi($thn_temp)
	{
		$query = "select sum(NILMAK) as real_menteri from m_spmmak WHERE THANG='{$thn_temp}' AND left(KDAKUN,1) = '5' group by THANG";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	//d_item
	function cek_pagu($thn_temp)
	{
		$query = "select sum(Jumlah) as pagu_menteri from d_item where THANG = '{$thn_temp}' and KDDEPT = '084' group by KDDEPT";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	//d_item
	function select_data_pagu_master_bsn($thn_temp,$kd_satker)
	{
		$query = "select sum(Jumlah) as pagu_satker from d_item where THANG = '{$thn_temp}' and KDSATKER = '{$kd_satker}' group by KDSATKER";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	//manipulasi
	function select_data_pagu_master_bsn_manipulasi($thn_temp)
	{
		$query = "select THANG, KDSATKER, sum(Jumlah) as pagu_satker from d_item where THANG = '{$thn_temp}' group by KDSATKER";
			// select THANG, KDSATKER, sum(Jumlah) as pagu_satker from d_item WHERE THANG = '2015' group by KDSATKER
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	
	function select_nama($kd_satker)
	{
		$query = "select NMSATKER from t_satker where KDSATKER = '{$kd_satker}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	// m_spmind
	function cek_kegiatan_group($thn_temp,$kd_satker)
	{
		$query = "select KDGIAT, sum(TOTNILMAK) as real_giat from m_spmind WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}'  group by KDGIAT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function cek_kegiatan_group_rev($thn_temp,$kd_giat)
	{
		$query = "select sum(TOTNILMAK) as real_giat from m_spmind WHERE THANG='{$thn_temp}' and KDGIAT='{$kd_giat}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	
	//d_item
	function pagu_giat_group($thn_temp,$kd_satker,$kd_giat)
	{
		$query = "select sum(Jumlah) as pagu_giat from d_item where THANG = '{$thn_temp}' and KDSATKER = '{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDGIAT";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	//d_item SALAH QUERY(DATA JADI DOUBLE)
	function cek_kegiatan_group_realisasi($thn_temp,$kd_satker)
	{
		$query = "select a.THANG, a.KDGIAT, sum(a.jumlah) as pagu_giat, b.kdunitkerja from d_item a 
				  INNER JOIN m_kegiatan b ON a.KDGIAT = b.kdgiat 
				  WHERE a.THANG = '{$thn_temp}' and a.KDSATKER = '{$kd_satker}' group by a.KDGIAT order by b.KDGIAT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	
	
	function nama_kegiatan($kd_giat)
	{
		$query = "select nmgiat from m_kegiatan where kdgiat = '{$kd_giat}'";
		//pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	// m_spmind
	function pagutotal_kode_output_kegiatan($thn_temp,$kd_satker,$kd_giat)
	{
		$query = "select KDOUTPUT, sum(TOTNILMAK) as real_output from m_spmind WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function pagutotal_kode_output_kegiatan_rev($thn_temp,$kd_giat)
	{
		$query = "select KDOUTPUT, sum(TOTNILMAK) as real_output from m_spmind WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	// m_spmind
	function pagutotal_kode_output_kegiatan_condtn($thn_temp,$kd_satker,$kd_giat,$kd_output)
	{
		$query = "select KDOUTPUT, sum(TOTNILMAK) as real_output from m_spmind WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' and KDOUTPUT = '{$kd_output}'";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	//d_item
	// function pagutotal_kode_output_kegiatan_perbulan($thn_temp,$kd_satker,$kd_giat,$param=0)
	function pagutotal_kode_output_kegiatan_perbulan($thn_temp,$kd_satker,$kd_giat)
	{	
		// if($param == 1){
			// $query = "select KDOUTPUT, ROUND(sum(jumlah) / 1000000,0) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
					// KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// }else{
			$query = "select KDOUTPUT, sum(jumlah) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
				KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// }
		
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function pagutotal_kode_output_kegiatan_perbulan_rev($thn_temp,$kd_giat)
	{	
		// if($param == 1){
			// $query = "select KDOUTPUT, ROUND(sum(jumlah) / 1000000,0) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
					// KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// }else{
			$query = "select KDOUTPUT, sum(jumlah) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
						KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// }
		
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	
	//d_item
	function pagurealisasi_output_kegiatan($thn_temp,$kd_satker,$kd_giat,$kdoutput)
	{
		$query = "select sum(Jumlah) as pagu_output from d_item where THANG = '{$thn_temp}' and KDSATKER = '{$kd_satker}' and KDGIAT = '{$kd_giat}' and KDOUTPUT = '{$kdoutput}' group by KDOUTPUT"; 
	
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function pagurealisasi_output_kegiatan_rev($thn_temp,$kd_giat,$kdoutput)
	{
		$query = "select sum(Jumlah) as pagu_output from d_item where THANG = '{$thn_temp}' and KDGIAT = '{$kd_giat}' and KDOUTPUT = '{$kdoutput}' "; 
	
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function nama_output($kd_giat,$kdoutput)
	{
		$query = "select NMOUTPUT from t_output where KDGIAT='{$kd_giat}' and KDOUTPUT='{$kdoutput}'"; 
	
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function nm_unit($kode_BSN) {
		$query = "select nmunit from tb_unitkerja where kdunit='{$kode_BSN}'"; 
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	// m_spmind dan m_spmmak
	function realisasi_perbulan_unit($thn_temp,$monthArray)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}'order by NILMAK";*/
					   
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}'";		   
					   
			// pr($query);		   
			
			//untuk query s/d bulan
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			
			$newArray[]= $result;
		}
		return array($newArray);
	}
	
	function realisasi_sdbulan_unit($thn_temp,$monthArray,$param =0)
	{
		foreach ($monthArray as $val) {	
			
			//untuk query bulan
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}'  order by NILMAK";*/
					   
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}'";
					   
			// and mk.KDGIAT <> '0000'
			$result = $this->fetch($query);
			
			//untuk query s/d bulan
			/*$query2 = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}'  order by NILMAK";*/
					   
			$query2 = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}'";		   
			// and mk.KDGIAT <> '0000'
			$result2 = $this->fetch($query2);
			
			
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			if($result2['jml'] == 0){
				$result2['jml']=0;
			}
			if($val == 1){
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round(($result['jml'] / 1000000),0);
					}else{
						$res_akumulasi = $result['jml'] ;
					}
				}else{
					$res_akumulasi = 0;
				}
			}else{
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round(($result2['jml'] / 1000000),0);
					}else{
						$res_akumulasi = $result2['jml'];
					}	
				}else{
					$res_akumulasi = 0;
				}
				
			}
			
			// $newArray[]= $result;
			$newArray[]= $res_akumulasi;
			// pr($newArray);
			// exit;
		}
		// pr($newArray);
			// exit;
		return array($newArray);
	}
	
	
	// m_spmind dan m_spmmak
	function realisasi_perbulan_unit_kegiatan($thn_temp,$monthArray,$kd_giat,$kd_satker)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}'";
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			
			// pr($newArray);
			$newArray[]= $result;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function realisasi_perbulan_unit_kegiatan_rev($thn_temp,$monthArray,$kd_giat)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}' and ms.KDGIAT ='{$kd_giat}'";
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			
			// pr($newArray);
			$newArray[]= $result;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function realisasi_sdbulan_unit_kegiatan($thn_temp,$monthArray,$kd_giat,$kd_satker,$param=0)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}'";
					 
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			
			//untuk query s.d bulan
			// $query2 = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query2="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}'";
			// pr($query);
			$result2 = $this->fetch($query2);
			// pr($result);
			
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			if($result2['jml'] == 0){
				$result2['jml']=0;
			}
			if($val == 1){
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result['jml'] ;
					}
				}else{
					$res_akumulasi = 0;
				}
			}else{
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result2['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result2['jml'];
					}
				}else{
					$res_akumulasi = 0;
				}
				
			}
			// pr($newArray);
			// $newArray[]= $result;
			$newArray[]= $res_akumulasi;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function realisasi_sdbulan_unit_kegiatan_rev($thn_temp,$monthArray,$kd_giat,$param=0)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDGIAT ='{$kd_giat}'";
					 
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			
			//untuk query s.d bulan
			// $query2 = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query2="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}'  and ms.KDGIAT ='{$kd_giat}'";
			// pr($query);
			$result2 = $this->fetch($query2);
			// pr($result);
			
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			if($result2['jml'] == 0){
				$result2['jml']=0;
			}
			if($val == 1){
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result['jml'] ;
					}
				}else{
					$res_akumulasi = 0;
				}
			}else{
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result2['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result2['jml'];
					}
				}else{
					$res_akumulasi = 0;
				}
				
			}
			// pr($newArray);
			// $newArray[]= $result;
			$newArray[]= $res_akumulasi;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	// m_spmind dan m_spmmak
	function penarikan_unit_perbulan_kegiatan_perbulan($thn_temp,$monthArray,$kd_satker,$kd_giat,$kd_output)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			
			$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			// pr($query);
			$result = $this->fetch($query);
			
			
			
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			
			// pr($newArray);
			$newArray[]= $result;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function penarikan_unit_perbulan_kegiatan_perbulan_rev($thn_temp,$monthArray,$kd_giat,$kd_output)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			
			$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}'  and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			// pr($query);
			$result = $this->fetch($query);
			
			
			
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			
			// pr($newArray);
			$newArray[]= $result;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function penarikan_unit_sdbulan_kegiatan_perbulan($thn_temp,$monthArray,$kd_satker,$kd_giat,$kd_output,$param=0)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			// pr($query);
			$result = $this->fetch($query);
			
			//untuk query bulan
			// $query2 = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			$query2 = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			
			// pr($query);
			$result2 = $this->fetch($query2);
			
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			if($result2['jml'] == 0){
				$result2['jml']=0;
			}
			if($val == 1){
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result['jml'] / 1000000,0) ;
					}else{
						$res_akumulasi = $result['jml'] ;
					}
				}else{
					$res_akumulasi = 0;
				}
			}else{
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result2['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result2['jml'];
					}
				}else{
					$res_akumulasi = 0;
				}
				
			}
			// pr($newArray);
			// $newArray[]= $result;
			$newArray[]= $res_akumulasi;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	function penarikan_unit_sdbulan_kegiatan_perbulan_rev($thn_temp,$monthArray,$kd_giat,$kd_output,$param=0)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			
			//untuk query bulan
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) = '{$val}'  and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			// pr($query);
			$result = $this->fetch($query);
			
			//untuk query bulan
			// $query2 = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
			$query2 = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$val}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			
			// pr($query);
			$result2 = $this->fetch($query2);
			
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']=0;
			}
			if($result2['jml'] == 0){
				$result2['jml']=0;
			}
			if($val == 1){
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result['jml'] / 1000000,0) ;
					}else{
						$res_akumulasi = $result['jml'] ;
					}
				}else{
					$res_akumulasi = 0;
				}
			}else{
				if($result['jml'] != 0){
					if($param == 1){
						$res_akumulasi = round($result2['jml'] / 1000000,0);
					}else{
						$res_akumulasi = $result2['jml'];
					}
				}else{
					$res_akumulasi = 0;
				}
				
			}
			// pr($newArray);
			// $newArray[]= $result;
			$newArray[]= $res_akumulasi;
			// $newArray."_".$val= $result;
		}
		return array($newArray);
	}
	
	// m_spmind dan m_spmmak
	function realisasi_allbulan_unit($thn_temp,$max_bulan)
	{		
			//and mk.KDGIAT <> '0000'
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}'  order by NILMAK";*/
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <='{$max_bulan}'";		  
					  
					  
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	// m_spmind dan m_spmmak
	function realisasi_allbulan_unit_kegiatan($thn_temp,$max_bulan,$kd_satker,$kd_giat)
	{
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}'";	
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function realisasi_allbulan_unit_kegiatan_rev($thn_temp,$max_bulan,$kd_giat)
	{
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
			
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDGIAT ='{$kd_giat}'";	
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	// m_spmind dan m_spmmak
	function select_all_bulan_unit_kegiatan_ouput_perbulan($thn_temp,$max_bulan,$kd_satker,$kd_giat,$kd_output)
	{
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function select_all_bulan_unit_kegiatan_ouput_perbulan_rev($thn_temp,$max_bulan,$kd_giat,$kd_output)
	{
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms where ms.THANG = '{$thn_temp}'
					 and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDGIAT ='{$kd_giat}' and ms.KDOUTPUT ='{$kd_output}'";
			
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	//d_trktrm
	function penarikan_unit_perbulan($thn_temp)
	{
			$query = "select 	sum(RPHPAGU) as RPHPAGU,
					sum(JML01) as JML01,
					sum(JML02) as JML02,
					sum(JML03) as JML03,
					sum(JML04) as JML04,
					sum(JML05) as JML05,
					sum(JML06) as JML06,
					sum(JML07) as JML07,
					sum(JML08) as JML08,
					sum(JML09) as JML09,
					sum(JML10) as JML10,
					sum(JML11) as JML11,
					sum(JML12) as JML12
					from d_trktrm WHERE THANG='{$thn_temp}' group by THANG";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result){
				$result['RPHPAGU']= $result['RPHPAGU'];
				$result['JML01']= $result['JML01'];
				$result['JML02']= $result['JML02'];
				$result['JML03']= $result['JML03'];
				$result['JML04']= $result['JML04'];
				$result['JML05']= $result['JML05'];
				$result['JML06']= $result['JML06'];
				$result['JML07']= $result['JML07'];
				$result['JML08']= $result['JML08'];
				$result['JML09']= $result['JML09'];
				$result['JML10']= $result['JML10'];
				$result['JML11']= $result['JML11'];
				$result['JML12']= $result['JML12'];	
			}else{
				$result['RPHPAGU']= 0;
				$result['JML01']= 0;
				$result['JML02']= 0;
				$result['JML03']= 0;
				$result['JML04']= 0;
				$result['JML05']= 0;
				$result['JML06']= 0;
				$result['JML07']= 0;
				$result['JML08']= 0;
				$result['JML09']= 0;
				$result['JML10']= 0;
				$result['JML11']= 0;
				$result['JML12']= 0;
			}
		return $result;
	}
	
	function penarikan_unit_perbulan_2($thn_temp,$param=0)
	{
			$query = "select 	sum(RPHPAGU) as RPHPAGU,
					sum(JML01) as JML01,
					sum(JML02) as JML02,
					sum(JML03) as JML03,
					sum(JML04) as JML04,
					sum(JML05) as JML05,
					sum(JML06) as JML06,
					sum(JML07) as JML07,
					sum(JML08) as JML08,
					sum(JML09) as JML09,
					sum(JML10) as JML10,
					sum(JML11) as JML11,
					sum(JML12) as JML12
					from d_trktrm WHERE THANG='{$thn_temp}' group by THANG";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result){
				if($param == 1){
					$result['RPHPAGU']= $result['RPHPAGU'];
					$result['JML01']= round($result['JML01'],0);
					$result['JML02']= round(($result['JML01'] + $result['JML02']) / 1000000,0);
					$result['JML03']= round(($result['JML02'] + $result['JML03']) / 1000000,0);
					$result['JML04']= round(($result['JML03'] + $result['JML04']) / 1000000,0);
					$result['JML05']= round(($result['JML04'] + $result['JML05']) / 1000000,0);
					$result['JML06']= round(($result['JML05'] + $result['JML06']) / 1000000,0);
					$result['JML07']= round(($result['JML06'] + $result['JML07']) / 1000000,0);
					$result['JML08']= round(($result['JML07'] + $result['JML08']) / 1000000,0);
					$result['JML09']= round(($result['JML08'] + $result['JML09']) / 1000000,0);
					$result['JML10']= round(($result['JML09'] + $result['JML10']) / 1000000,0);
					$result['JML11']= round(($result['JML10'] + $result['JML11']) / 1000000,0);
					$result['JML12']= round(($result['JML11'] + $result['JML12']) / 1000000,0);	
				}else{
					$result['RPHPAGU']= $result['RPHPAGU'];
					$result['JML01']= $result['JML01'];
					$result['JML02']= $result['JML01'] + $result['JML02'];
					$result['JML03']= $result['JML02'] + $result['JML03'];
					$result['JML04']= $result['JML03'] + $result['JML04'];
					$result['JML05']= $result['JML04'] + $result['JML05'];
					$result['JML06']= $result['JML05'] + $result['JML06'];
					$result['JML07']= $result['JML06'] + $result['JML07'];
					$result['JML08']= $result['JML07'] + $result['JML08'];
					$result['JML09']= $result['JML08'] + $result['JML09'];
					$result['JML10']= $result['JML09'] + $result['JML10'];
					$result['JML11']= $result['JML10'] + $result['JML11'];
					$result['JML12']= $result['JML11'] + $result['JML12'];	
				}
				
			}else{
				$result['RPHPAGU']= 0;
				$result['JML01']= 0;
				$result['JML02']= 0;
				$result['JML03']= 0;
				$result['JML04']= 0;
				$result['JML05']= 0;
				$result['JML06']= 0;
				$result['JML07']= 0;
				$result['JML08']= 0;
				$result['JML09']= 0;
				$result['JML10']= 0;
				$result['JML11']= 0;
				$result['JML12']= 0;
			}
		return $result;
	}
	//d_trktrm
	function penarikan_unit_perbulan_kegiatan($thn_temp,$kd_satker,$kd_giat)
	{
			$query = "select 	sum(RPHPAGU) as RPHPAGU,
					sum(JML01) as JML01,
					sum(JML02) as JML02,
					sum(JML03) as JML03,
					sum(JML04) as JML04,
					sum(JML05) as JML05,
					sum(JML06) as JML06,
					sum(JML07) as JML07,
					sum(JML08) as JML08,
					sum(JML09) as JML09,
					sum(JML10) as JML10,
					sum(JML11) as JML11,
					sum(JML12) as JML12
					from d_trktrm WHERE THANG='{$thn_temp}' AND KDSATKER = '{$kd_satker}' 
					AND KDGIAT = '{$kd_giat}' 
					group by THANG";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result){
					$result['RPHPAGU']= $result['RPHPAGU'];
					$result['JML01']= $result['JML01'];
					$result['JML02']= $result['JML02'];
					$result['JML03']= $result['JML03'];
					$result['JML04']= $result['JML04'];
					$result['JML05']= $result['JML05'];
					$result['JML06']= $result['JML06'];
					$result['JML07']= $result['JML07'];
					$result['JML08']= $result['JML08'];
					$result['JML09']= $result['JML09'];
					$result['JML10']= $result['JML10'];
					$result['JML11']= $result['JML11'];
					$result['JML12']= $result['JML12'];
			}else{
					$result['RPHPAGU']= 0;
					$result['JML01']= 0;
					$result['JML02']= 0;
					$result['JML03']= 0;
					$result['JML04']= 0;
					$result['JML05']= 0;
					$result['JML06']= 0;
					$result['JML07']= 0;
					$result['JML08']= 0;
					$result['JML09']= 0;
					$result['JML10']= 0;
					$result['JML11']= 0;
					$result['JML12']= 0;
			}
		return $result;
	}
	
	function penarikan_unit_perbulan_kegiatan_2($thn_temp,$kd_satker,$kd_giat,$param=0)
	{
			$query = "select 	sum(RPHPAGU) as RPHPAGU,
					sum(JML01) as JML01,
					sum(JML02) as JML02,
					sum(JML03) as JML03,
					sum(JML04) as JML04,
					sum(JML05) as JML05,
					sum(JML06) as JML06,
					sum(JML07) as JML07,
					sum(JML08) as JML08,
					sum(JML09) as JML09,
					sum(JML10) as JML10,
					sum(JML11) as JML11,
					sum(JML12) as JML12
					from d_trktrm WHERE THANG='{$thn_temp}' AND KDSATKER = '{$kd_satker}' 
					AND KDGIAT = '{$kd_giat}' 
					group by THANG";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result){
				if($param == 1){
					$result['RPHPAGU']= $result['RPHPAGU'];
					$result['JML01']= round($result['JML01'],0);
					$result['JML02']= round(($result['JML01'] + $result['JML02']) / 1000000,0);
					$result['JML03']= round(($result['JML02'] + $result['JML03']) / 1000000,0);
					$result['JML04']= round(($result['JML03'] + $result['JML04']) / 1000000,0);
					$result['JML05']= round(($result['JML04'] + $result['JML05']) / 1000000,0);
					$result['JML06']= round(($result['JML05'] + $result['JML06']) / 1000000,0);
					$result['JML07']= round(($result['JML06'] + $result['JML07']) / 1000000,0);
					$result['JML08']= round(($result['JML07'] + $result['JML08']) / 1000000,0);
					$result['JML09']= round(($result['JML08'] + $result['JML09']) / 1000000,0);
					$result['JML10']= round(($result['JML09'] + $result['JML10']) / 1000000,0);
					$result['JML11']= round(($result['JML10'] + $result['JML11']) / 1000000,0);
					$result['JML12']= round(($result['JML11'] + $result['JML12']) / 1000000,0);	
				}else{
					$result['RPHPAGU']= $result['RPHPAGU'];
					$result['JML01']= $result['JML01'];
					$result['JML02']= $result['JML01'] + $result['JML02'];
					$result['JML03']= $result['JML02'] + $result['JML03'];
					$result['JML04']= $result['JML03'] + $result['JML04'];
					$result['JML05']= $result['JML04'] + $result['JML05'];
					$result['JML06']= $result['JML05'] + $result['JML06'];
					$result['JML07']= $result['JML06'] + $result['JML07'];
					$result['JML08']= $result['JML07'] + $result['JML08'];
					$result['JML09']= $result['JML08'] + $result['JML09'];
					$result['JML10']= $result['JML09'] + $result['JML10'];
					$result['JML11']= $result['JML10'] + $result['JML11'];
					$result['JML12']= $result['JML11'] + $result['JML12'];	
				}
					
			}else{
					$result['RPHPAGU']= 0;
					$result['JML01']= 0;
					$result['JML02']= 0;
					$result['JML03']= 0;
					$result['JML04']= 0;
					$result['JML05']= 0;
					$result['JML06']= 0;
					$result['JML07']= 0;
					$result['JML08']= 0;
					$result['JML09']= 0;
					$result['JML10']= 0;
					$result['JML11']= 0;
					$result['JML12']= 0;
			}
			// pr($result);
			// exit;
		return $result;
	}
	//d_trktrm
	function renc_menteri_sdtriwulan_BSN($thn_temp,$trwln){
		if($trwln == 1){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($trwln == 2){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($trwln == 3){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($trwln == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	function renc_menteri_sdbulan_BSN($thn_temp,$bulan){
		if($bulan == 1){
			$query = "select sum(JML01) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 2){
			$query = "select sum(JML01 + JML02) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 3){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 5){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 6){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 7){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 8){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 9){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 10){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 11){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}elseif($bulan == 12){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' GROUP BY THANG";
		}
			
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	
	// m_spmind dan m_spmmak
	function real_menteri_triwulan_BSN($thn_temp,$trwln)
	{
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
				
				// and mk.KDGIAT <> '0000'
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
				
				// and mk.KDGIAT <> '0000'
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
				// and mk.KDGIAT <> '0000'
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' order by NILMAK";
				// and mk.KDGIAT <> '0000' 
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
				
			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_menteri_bulan_BSN($thn_temp,$bulan)
	{
		/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
			  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
			  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and mk.KDGIAT <> '0000' order by NILMAK";*/
		$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}'";	
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['jml'] == 0){
			$result['jml']= 0;
		}
		return $result;
	}
	
	function real_menteri_trwln_BSN($thn_temp,$trwln)
	{
		if($trwln == 1){
			$first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			$first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			$first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			$first_month = 10;
			$last_month = 12;
		}
		/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
			  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
			  where ms.THANG = '{$thn_temp}' 
			  and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' 
			  and mk.KDGIAT <> '0000' order by NILMAK";*/
		$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";	  
			
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['jml'] == 0){
			$result['jml']= 0;
		}
		return $result;
	}
	
	// m_spmind dan m_spmmak
	function real_menteri_sdtriwulan_BSN($thn_temp,$trwln)
	{
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				// and mk.KDGIAT <> '0000'
				
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' order by NILMAK";
				 // and mk.KDGIAT <> '0000'
				 $query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				// and mk.KDGIAT <> '0000'
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  order by NILMAK";
				// and mk.KDGIAT <> '0000'
				$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_menteri_sdbulan_BSN($thn_temp,$bulan)
	{
		/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
			  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
			  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}'  order by NILMAK";*/
		$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' ";	  
			  
		// and mk.KDGIAT <> '0000'
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['jml'] == 0){
			$result['jml']= 0;
		}
		return $result;
	}
	
	
	//d_item
	function select_data_bsn($thn_temp)
	{
		$query = "select THANG, KDSATKER, sum(jumlah) as pagu_satker from d_item  WHERE THANG = '{$thn_temp}' group by KDSATKER";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	//d_trktrm
	function renc_satker_sdtriwulan($thn_temp,$trwln,$kd_satker){
		if($trwln == 1){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY KDSATKER";
		}elseif($trwln == 2){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY KDSATKER";
		}elseif($trwln == 3){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY KDSATKER";
		}elseif($trwln == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY KDSATKER";
		}
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	function renc_satker_sdbulan($thn_temp,$bulan,$kd_satker){
		if($bulan == 1){
			$query = "select sum(JML01) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 2){
			$query = "select sum(JML01 + JML02) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 3){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 5){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 6){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 7){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 8){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 9){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 10){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 11){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}elseif($bulan == 12){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' GROUP BY THANG";
		}		
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	
	// m_spmind dan m_spmmak
	function real_satker_triwulan($thn_temp,$trwln,$kd_satker)
	{
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				// and mk.KDGIAT <> '0000'
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
				
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
				
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
				
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
				
			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_satker_bulan($thn_temp,$bulan,$kd_satker)
	{
		// and mk.KDGIAT <> '0000'
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";*/
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} ";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	// m_spmind dan m_spmmak
	function real_satker_sdtriwulan($thn_temp,$trwln,$kd_satker)
	{
		// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";

			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} ";
			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_satker_sdbulan($thn_temp,$bulan,$kd_satker)
	{
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} order by NILMAK";*/
			$query = "select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker}";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	
	//d_trktrm 
	function renc_giat_sdtriwulan($thn_temp,$trwln,$kd_satker,$kd_giat){
		if($trwln == 1){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY KDSATKER";
		}elseif($trwln == 2){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY KDSATKER";
		}elseif($trwln == 3){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY KDSATKER";
		}elseif($trwln == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY KDSATKER";
		}
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	function renc_giat_sdbulan($thn_temp,$trwln,$kd_satker,$kd_giat){
		if($bulan == 1){
			$query = "select sum(JML01) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 2){
			$query = "select sum(JML01 + JML02) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 3){
			$query = "select sum(JML01 + JML02 + JML03) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 4){
			$query = "select sum(JML01 + JML02 + JML03 + JML04) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 5){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 6){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 7){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 8){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}'  GROUP BY THANG";
		}elseif($bulan == 9){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY THANG";
		}elseif($bulan == 10){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY THANG";
		}elseif($bulan == 11){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY THANG";
		}elseif($bulan == 12){
			$query = "select sum(JML01 + JML02 + JML03 + JML04 + JML05 + JML06 + JML07 + JML08 + JML09 + JML10 + JML11 + JML12) as rencana from d_trktrm where THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' AND KDGIAT = '{$kd_giat}' GROUP BY THANG";
		}		
		// pr($query);
		$result = $this->fetch($query);
		if($result ==0){
			$result['rencana'] = 0;
		}
		return $result;
	}
	
	
	// m_spmind dan m_spmmak
	function real_giat_triwulan($thn_temp,$trwln,$kd_satker,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_giat_triwulan_rev($thn_temp,$trwln,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_giat_bulan($thn_temp,$bulan,$kd_satker,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";*/
			$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_giat_bulan_rev($thn_temp,$bulan,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			/*$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";*/
			$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDGIAT = {$kd_giat}";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	
	// m_spmind dan m_spmmak
	function real_giat_sdtriwulan($thn_temp,$trwln,$kd_satker,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} ";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_giat_sdtriwulan_rev($thn_temp,$trwln,$kd_giat)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat} ";

			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  and ms.KDGIAT = {$kd_giat} ";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_giat_sdbulan($thn_temp,$bulan,$kd_satker,$kd_giat)
	{
			// and mk.KDGIAT <> '0000' 
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	
	function real_giat_sdbulan_rev($thn_temp,$bulan,$kd_giat)
	{
			// and mk.KDGIAT <> '0000' 
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} order by NILMAK";
			
			$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					 where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDGIAT = {$kd_giat}";
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	
	// m_spmind dan m_spmmak
	function real_output_triwulan($thn_temp,$trwln,$kd_satker,$kd_giat,$kd_output)
	{		
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
						// where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_triwulan_rev($thn_temp,$trwln,$kd_giat,$kd_output)
	{		
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'  and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
						// where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'  and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_bulan($thn_temp,$bulan,$kd_satker,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000' 
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat} and ms.KDOUTPUT = '{$kd_output}'";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_bulan_rev($thn_temp,$bulan,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000' 
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$bulan}' and ms.KDGIAT = {$kd_giat} and ms.KDOUTPUT = '{$kd_output}'";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	// m_spmind dan m_spmmak
	function real_output_sdtriwulan($thn_temp,$trwln,$kd_satker,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_sdtriwulan_rev($thn_temp,$trwln,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000'
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
						where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'  and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
				$query ="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";

			}
			
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_sdbulan($thn_temp,$bulan,$kd_satker,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000'
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	
	function real_output_sdbulan_rev($thn_temp,$bulan,$kd_giat,$kd_output)
	{
			// and mk.KDGIAT <> '0000'
			// $query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
				  // inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
				  // where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat}  and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			$query="select sum(ms.TOTNILMAK) as jml from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$bulan}' and ms.KDGIAT = {$kd_giat}  and ms.KDOUTPUT = '{$kd_output}'";
			// pr($query);
			$result = $this->fetch($query);
			// pr($result);
			if($result['jml'] == 0){
				$result['jml']= 0;
			}
		return $result;
	}
	// m_spmind
	function select_spm($thn_temp,$kd_satker,$kd_output) {
		$query = "select NOSPM,TGSPM,NOSP2D,TGSP2D, sum(TOTNILMAK) as real_spm from m_spmind 
			WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDOUTPUT='{$kd_output}' 
			group by concat(NOSPM,NOSP2D) order by TGSP2D desc"; 
			
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	//m_spmmak dan t_akun(t_akun dpt dari mana ya?)
	function select_kode_akun($thn_temp,$kd_satker,$no_spm,$no_sp2d) {
		$query = "select a.KDAKUN,a.NILMAK,b.NMAKUN from m_spmmak as a 
				  inner join t_akun as b on b.KDAKUN = a.KDAKUN
				  WHERE a.THANG='{$thn_temp}' and a.KDSATKER='{$kd_satker}' and a.NOSPM='{$no_spm}' 
				  and a.NOSP2D='{$no_sp2d}' order by a.KDAKUN"; 
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}

	function getSatker($type)
	{
		$sql = "SELECT * FROM bsn_struktur WHERE type='{$type}'";
		// echo $sql;
		$res = $this->fetch($sql,1);

		return $res;
	}

	function getTahunAnggaran()
	{
		$sql = "SELECT * FROM bsn_sistem_setting WHERE `desc` = 'tahun_sistem' AND n_status='1'";
		$res = $this->fetch($sql);

		return $res;

	}

	function getRiwayat()
	{
		$sql = "SELECT * FROM dt_fileupload_keu";
		$res = $this->fetch($sql,1);
		foreach ($res as $key => $value) {
			$sql = "SELECT name FROM ck_admin_member WHERE id='{$value['user_upload']}'";
			$user = $this->fetch($sql);
			$res[$key]['user'] = $user['name'];
		}

		return $res;
	}
	
	function del_peryear($table,$tahun)
	{
		$sql = "DELETE FROM {$table} WHERE THANG = '{$tahun}'";
		$res = $this->query($sql);

		return true;
	}

	function insert_data($data,$table)
	{
		$this->insert($data,$table);

		return true;
	}
	
	
	//Anggaran Jenis Belanja
	function realisasi_general($thn_temp,$bulan,$kdbel){
		// and MONTH(TGSP2D) = '{$bulan}'
		if($kdbel == 51){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '51%' and MONTH(TGSP2D) <= '{$bulan}'";
		}elseif($kdbel == 52){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '52%' and  KDAKUN not like '524%' and MONTH(TGSP2D) <= '{$bulan}'";
		}elseif($kdbel == 53){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN  like '53%' and MONTH(TGSP2D) <= '{$bulan}'";
		}elseif($kdbel == 524){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '524%' and MONTH(TGSP2D) <= '{$bulan}'";
		}
		
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['realisasi'] == 0){
			$result['realisasi']= 0;
		}
		return $result;
	}
	
	//Anggaran Jenis Belanja
	function realisasi_general_trwln($thn_temp,$trwln,$kdbel){
		// and MONTH(TGSP2D) = '{$bulan}'
		if($trwln == 1){
			// $first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			// $first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			// $first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			// $first_month = 10;
			$last_month = 12;
		}
		
		if($kdbel == 51){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '51%'
				  and MONTH(TGSP2D) <= '{$last_month}'";
		}elseif($kdbel == 52){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '52%' and  KDAKUN not like '524%'
				  and MONTH(TGSP2D) <= '{$last_month}'";
		}elseif($kdbel == 53){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN  like '53%'
				  and MONTH(TGSP2D) <= '{$last_month}'";
		}elseif($kdbel == 524){
			$query = "select sum(NILMAK) as realisasi from m_spmmak 
				  where THANG = '{$thn_temp}' and KDAKUN like '524%' 
				  and MONTH(TGSP2D) <= '{$last_month}'";
		}
		
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['realisasi'] == 0){
			$result['realisasi']= 0;
		}
		return $result;
	}
	
	
	function realisasi_kegiatan_general($thn_temp,$kd_giat,$bulan,$kdbel){
		$result = array();
		$nilai = 0;
		if($kdbel == 51){
			$ext_query = "AND KDAKUN LIKE '51%'";
		}elseif($kdbel == 52){
			$ext_query = "AND KDAKUN LIKE '52%' AND KDAKUN NOT LIKE '524%' ";
		}elseif($kdbel == 53){
			$ext_query = "AND KDAKUN LIKE '53%'";
		}elseif($kdbel == 524){
			$ext_query = "AND KDAKUN LIKE '524%'";
		}
		//get data NOSPM dan NOSP2D from m_spmind
		$query_m_spmind = "select NOSPM,NOSP2D from m_spmind where THANG = '{$thn_temp}'   
						   and KDGIAT ='{$kd_giat}' and MONTH(TGSP2D) <= '{$bulan}'";
		// pr($query_m_spmind);
		$result_m_spmind = $this->fetch($query_m_spmind,1);
		// pr($result_m_spmind);
		if($result_m_spmind){
			foreach($result_m_spmind as $val){
				//get data NILMAK BY KDAKUN
				$query_m_spmmak = "select NILMAK from m_spmmak where THANG = '{$thn_temp}' and NOSPM = '{$val[NOSPM]}'  
							   and NOSP2D ='{$val[NOSP2D]}' {$ext_query}";
				// pr($query_m_spmmak);
				$result_m_spmmak = $this->fetch($query_m_spmmak,1);	
				// pr($result_m_spmmak);
				if($result_m_spmmak){
					foreach($result_m_spmmak as $values){
						$nilai = $nilai + $values['NILMAK'];
					
					}
				}	
			}
		}
		$result['realisasi']= $nilai;
		return $result;
		
		
	}
	
	function realisasi_kegiatan_general_trwln($thn_temp,$kd_giat,$trwln,$kdbel){
		if($trwln == 1){
			// $first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			// $first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			// $first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			// $first_month = 10;
			$last_month = 12;
		}
		$result = array();
		$nilai = 0;
		if($kdbel == 51){
			$ext_query = "AND KDAKUN LIKE '51%'";
		}elseif($kdbel == 52){
			$ext_query = "AND KDAKUN LIKE '52%' AND KDAKUN NOT LIKE '524%' ";
		}elseif($kdbel == 53){
			$ext_query = "AND KDAKUN LIKE '53%'";
		}elseif($kdbel == 524){
			$ext_query = "AND KDAKUN LIKE '524%'";
		}
		//get data NOSPM dan NOSP2D from m_spmind
		$query_m_spmind = "select NOSPM,NOSP2D from m_spmind where THANG = '{$thn_temp}'   
						   and KDGIAT ='{$kd_giat}' and MONTH(TGSP2D) <= '{$last_month}'";
		// pr($query_m_spmind);
		$result_m_spmind = $this->fetch($query_m_spmind,1);
		// pr($result_m_spmind);
		if($result_m_spmind){
			foreach($result_m_spmind as $val){
				//get data NILMAK BY KDAKUN
				$query_m_spmmak = "select NILMAK from m_spmmak where THANG = '{$thn_temp}' and NOSPM = '{$val[NOSPM]}'  
							   and NOSP2D ='{$val[NOSP2D]}' {$ext_query}";
				// pr($query_m_spmmak);
				$result_m_spmmak = $this->fetch($query_m_spmmak,1);	
				// pr($result_m_spmmak);
				if($result_m_spmmak){
					foreach($result_m_spmmak as $values){
						$nilai = $nilai + $values['NILMAK'];
					
					}
				}	
			}
		}
		$result['realisasi']= $nilai;
		return $result;
		
		// and MONTH(TGSP2D) = '{$bulan}'
		/*if($kdbel == 51){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '51'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	  
				  
		}elseif($kdbel == 52){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '52' and left(ms.KDAKUN,3) <> '524' AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	  
				  
		}elseif($kdbel == 53){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '53'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 
			
		}elseif($kdbel == 524){
		  $query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,3) = '524'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	
		}
		
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['realisasi'] == 0){
			$result['realisasi']= 0;
		}
		return $result;*/
	}
	
	//iman mau edit
	function realisasi_output_general($thn_temp,$kd_giat,$kd_output,$bulan,$kdbel){
		// and MONTH(TGSP2D) = '{$bulan}'
		$result = array();
		$nilai = 0;
		if($kdbel == 51){
			$ext_query = "AND KDAKUN LIKE '51%'";
		}elseif($kdbel == 52){
			$ext_query = "AND KDAKUN LIKE '52%' and KDAKUN NOT LIKE '524%' ";
		}elseif($kdbel == 53){
			$ext_query = "AND KDAKUN LIKE '53%'";
		}elseif($kdbel == 524){
			$ext_query = "AND KDAKUN LIKE '524%'";
		}
		//get data NOSPM dan NOSP2D from m_spmind
		$query_m_spmind = "select NOSPM,NOSP2D from m_spmind where THANG = '{$thn_temp}' 
						   and KDGIAT ='{$kd_giat}' and KDOUTPUT = '{$kd_output}' and MONTH(TGSP2D) <= '{$bulan}'";
		// pr($query_m_spmind);
		$result_m_spmind = $this->fetch($query_m_spmind,1);
		// pr($result_m_spmind);
		if($result_m_spmind){
			foreach($result_m_spmind as $val){
				//get data NILMAK BY KDAKUN
				$query_m_spmmak = "select NILMAK from m_spmmak where THANG = '{$thn_temp}' and NOSPM = '{$val[NOSPM]}'  
							   and NOSP2D ='{$val[NOSP2D]}' {$ext_query}";
				// pr($query_m_spmmak);
				$result_m_spmmak = $this->fetch($query_m_spmmak,1);	
				// pr($result_m_spmmak);
				if($result_m_spmmak){
					foreach($result_m_spmmak as $values){
						$nilai = $nilai + $values['NILMAK'];
					
					}
				}	
			}
		}
		$result['realisasi']= $nilai;
		return $result;
		
	}
	
	function realisasi_output_general_trwln($thn_temp,$kd_giat,$kd_output,$trwln,$kdbel){
		// and MONTH(TGSP2D) = '{$bulan}'
		if($trwln == 1){
			// $first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			// $first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			// $first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			// $first_month = 10;
			$last_month = 12;
		}
		$result = array();
		$nilai = 0;
		if($kdbel == 51){
			$ext_query = "AND KDAKUN LIKE '51%'";
		}elseif($kdbel == 52){
			$ext_query = "AND KDAKUN LIKE '52%' and KDAKUN NOT LIKE '524%' ";
		}elseif($kdbel == 53){
			$ext_query = "AND KDAKUN LIKE '53%'";
		}elseif($kdbel == 524){
			$ext_query = "AND KDAKUN LIKE '524%'";
		}
		//get data NOSPM dan NOSP2D from m_spmind
		$query_m_spmind = "select NOSPM,NOSP2D from m_spmind where THANG = '{$thn_temp}' 
						   and KDGIAT ='{$kd_giat}' and KDOUTPUT = '{$kd_output}' and MONTH(TGSP2D) <= '{$last_month}'";
		// pr($query_m_spmind);
		$result_m_spmind = $this->fetch($query_m_spmind,1);
		// pr($result_m_spmind);
		if($result_m_spmind){
			foreach($result_m_spmind as $val){
				//get data NILMAK BY KDAKUN
				$query_m_spmmak = "select NILMAK from m_spmmak where THANG = '{$thn_temp}' and NOSPM = '{$val[NOSPM]}'  
							   and NOSP2D ='{$val[NOSP2D]}' {$ext_query}";
				// pr($query_m_spmmak);
				$result_m_spmmak = $this->fetch($query_m_spmmak,1);	
				// pr($result_m_spmmak);
				if($result_m_spmmak){
					foreach($result_m_spmmak as $values){
						$nilai = $nilai + $values['NILMAK'];
					
					}
				}	
			}
		}
		$result['realisasi']= $nilai;
		return $result;
		/*if($kdbel == 51){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '51'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' AND mk.KDOUTPUT='{$kd_output}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	  
				  
		}elseif($kdbel == 52){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '52' and left(ms.KDAKUN,3) <> '524' AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' AND mk.KDOUTPUT='{$kd_output}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	  
				  
		}elseif($kdbel == 53){
			$query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,2) = '53'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' AND mk.KDOUTPUT='{$kd_output}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 
			
		}elseif($kdbel == 524){
		  $query = "select sum(ms.NILMAK) as realisasi from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND left(ms.KDAKUN,3) = '524'  AND ms.KDSATKER = '{$kd_satker}' 
					AND mk.KDGIAT ='{$kd_giat}' AND mk.KDOUTPUT='{$kd_output}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; 	
		}
		
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['realisasi'] == 0){
			$result['realisasi']= 0;
		}
		return $result;*/
	}
	
	function pagu_bsn_bulan_ini($thn_temp,$bulan,$param){
		if($param == 1){
			/*$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND mk.KDGIAT <> '0000' 
					and MONTH(ms.TGSP2D) = '{$bulan}'"; */
			
			$query ="select sum(ms.TOTNILMAK) as pagu_bulan from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and  MONTH(ms.TGSP2D) = '{$bulan}'";
		
		}elseif($param == 2){
			$query ="select sum(ms.TOTNILMAK) as pagu_bulan from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and  MONTH(ms.TGSP2D) <= '{$bulan}'";
		
		}
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['pagu_bulan'] == 0){
			$result['pagu_bulan']= 0;
		}
		return $result;			
					
	}
	
	function pagu_bsn_trwln_ini($thn_temp,$trwln,$param){
		if($param == 1){
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
			}elseif($trwln == 2){
				$first_month = 4;
				$last_month = 6;
			}elseif($trwln == 3){
				$first_month = 7;
				$last_month = 9;
			}elseif($trwln == 4){
				$first_month = 10;
				$last_month = 12;
			}
		// AND mk.KDGIAT <> '0000' 
			/*$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' 
					and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'"; */
			$query ="select sum(ms.TOTNILMAK) as pagu_bulan from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";		
			// pr($query);		
		}elseif($param == 2){
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
			}elseif($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
			}elseif($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
			}elseif($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
			}
			// AND mk.KDGIAT <> '0000'
			/*$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}'  
					and MONTH(ms.TGSP2D) <= '{$last_month}'"; */
			$query ="select sum(ms.TOTNILMAK) as pagu_bulan from m_spmind as ms 
					where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}'";	
		}
		// pr($query);
		$result = $this->fetch($query);
		// pr($result);
		if($result['pagu_bulan'] == 0){
			$result['pagu_bulan']= 0;
		}
		return $result;			
					
	}
	
	
	function kode_akun($thn_temp,$kdbel){
		if($kdbel == 51){
			$ext_query = "AND KDAKUN LIKE '51%'";
		}elseif($kdbel == 52){
			$ext_query = "AND (KDAKUN LIKE '521%' or KDAKUN LIKE '522%' or KDAKUN LIKE '523%') ";
		}elseif($kdbel == 53){
			$ext_query = "AND KDAKUN LIKE '53%'";
		}elseif($kdbel == 524){
			$ext_query = "AND KDAKUN LIKE '524%'";
		}
		$query = "select KDAKUN, sum(jumlah) as pagu_akun from d_item  WHERE THANG = '{$thn_temp}' 
				{$ext_query}
				group by KDAKUN"; 
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;			
				
	}
	
	function kode_akun_giat($thn_temp,$kd_satker,$kd_giat){
		$query = "select KDAKUN, sum(jumlah) as pagu_akun from d_item  
				  WHERE THANG = '{$thn_temp}' AND KDSATKER = '{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDAKUN"; 
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;			
				
	}
	
	function kode_akun_giat_rev($thn_temp,$kd_giat){
		$query = "select KDAKUN, sum(jumlah) as pagu_akun from d_item  
				  WHERE THANG = '{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDAKUN"; 
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;			
				
	}
	
	function nama_akun($kd_akun){
		$query = "select NMAKUN from t_akun where KDAKUN = '{$kd_akun}'"; 
		$result = $this->fetch($query);
		
		return $result;		
	}
		
	function pagu_akun($thn_temp,$kode_akun,$bulan,$param){
	$nilai = 0;
	$resultfix = array();
		if($param == 1){
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' 
				and MONTH(TGSP2D) = '{$bulan}'";
			// pr($query);	
			$result = $this->fetch($query,1);
			// pr($result);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				// pr($query2);
				$result2 = $this->fetch($query2,1);			
				// pr($result2);
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}
		}elseif($param == 2){
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' 
				and MONTH(TGSP2D) <= '{$bulan}'";
			// pr($query);
			$result = $this->fetch($query,1);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				$result2 = $this->fetch($query2,1);			
				
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}
		}
		// exit;
		$resultfix['pagu_bulan']= $nilai;
		return $resultfix;	
	}

	
	function pagu_akun_trwln($thn_temp,$kode_akun,$trwln,$param){
	$nilai = 0;
	$resultfix = array();
	if($param == 1){
		if($trwln == 1){
			$first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			$first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			$first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			$first_month = 10;
			$last_month = 12;
		}
		// AND mk.KDGIAT <> '0000' 
		/*$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					
					and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";*/
		$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' 
				and MONTH(TGSP2D) >= '{$first_month}' and MONTH(TGSP2D) <= '{$last_month}'";
			// pr($query);	
			$result = $this->fetch($query,1);
			// pr($result);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				// pr($query2);
				$result2 = $this->fetch($query2,1);			
				// pr($result2);
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}			
					
	}elseif($param == 2){
		if($trwln == 1){
			// $first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			// $first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			// $first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			// $first_month = 10;
			$last_month = 12;
		}
		// AND mk.KDGIAT <> '0000' 
		/*$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					
					and MONTH(ms.TGSP2D) <= '{$last_month}'";*/
		$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' 
				  and MONTH(TGSP2D) <= '{$last_month}'";
			// pr($query);	
			$result = $this->fetch($query,1);
			// pr($result);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				// pr($query2);
				$result2 = $this->fetch($query2,1);			
				// pr($result2);
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}			
	}	
	$resultfix['pagu_bulan']= $nilai;
	return $resultfix;	
	// pr($query);
	// $result = $this->fetch($query);
		// pr($result);
		// if($result['pagu_bulan'] == 0){
			// $result['pagu_bulan']= 0;
		// }
		// return $result;	
	}

	function pagu_akun_giat($thn_temp,$kd_satker,$kd_giat,$kode_akun,$bulan,$param){
	if($param == 1){
		$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					AND mk.KDSATKER = '{$kd_satker}' AND mk.KDGIAT = '{$kd_giat}' and MONTH(ms.TGSP2D) = '{$bulan}'";
	}elseif($param == 2){
		$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					AND mk.KDSATKER = '{$kd_satker}' AND mk.KDGIAT = '{$kd_giat}' and MONTH(ms.TGSP2D) <= '{$bulan}'";
	}	
	// pr($query);
	$result = $this->fetch($query);
		// pr($result);
		if($result['pagu_bulan'] == 0){
			$result['pagu_bulan']= 0;
		}
		return $result;	
	}
	
	function pagu_akun_giat_rev($thn_temp,$kd_giat,$kode_akun,$bulan,$param){
	$nilai = 0;
	$resultfix = array();
		if($param == 1){
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' and KDGIAT = '{$kd_giat}'
				and MONTH(TGSP2D) = '{$bulan}'";
			// pr($query);	
			$result = $this->fetch($query,1);
			// pr($result);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				// pr($query2);
				$result2 = $this->fetch($query2,1);			
				// pr($result2);
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}
		}elseif($param == 2){
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' and KDGIAT = '{$kd_giat}'
				and MONTH(TGSP2D) <= '{$bulan}'";
			// pr($query);
			$result = $this->fetch($query,1);
			foreach ($result as $val){
				$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
							NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
				$result2 = $this->fetch($query2,1);			
				
				foreach ($result2 as $value){
					$nilai = $nilai + $value['pagu_bulan'];
				}
			}
		}
		// exit;
		$resultfix['pagu_bulan']= $nilai;
		return $resultfix;	
	}
	function pagu_akun_giat_triwulan_rev($thn_temp,$kd_giat,$kode_akun,$trwln,$param){
		if($param == 1){
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
			}elseif($trwln == 2){
				$first_month = 4;
				$last_month = 6;
			}elseif($trwln == 3){
				$first_month = 7;
				$last_month = 9;
			}elseif($trwln == 4){
				$first_month = 10;
				$last_month = 12;
			}
			
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' and KDGIAT = '{$kd_giat}'
					and MONTH(TGSP2D) >= '{$first_month}' and MONTH(TGSP2D) <= '{$last_month}'";
				// pr($query);	
				$result = $this->fetch($query,1);
				// pr($result);
				foreach ($result as $val){
					$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
								NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
					// pr($query2);
					$result2 = $this->fetch($query2,1);			
					// pr($result2);
					foreach ($result2 as $value){
						$nilai = $nilai + $value['pagu_bulan'];
					}
				}			
		}elseif($param == 2){
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
			}elseif($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
			}elseif($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
			}elseif($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
			}
			$query = "SELECT NOSPM,TGSP2D FROM db_sipp_dev.m_spmind where THANG ='{$thn_temp}' and KDGIAT = '{$kd_giat}'
					and MONTH(TGSP2D) <= '{$last_month}'";
				// pr($query);	
				$result = $this->fetch($query,1);
				// pr($result);
				foreach ($result as $val){
					$query2 = "SELECT  NILMAK as pagu_bulan from m_spmmak where
								NOSPM = '{$val[NOSPM]}' AND TGSP2D = '{$val[TGSP2D]}' AND KDAKUN = '{$kode_akun}'";
					// pr($query2);
					$result2 = $this->fetch($query2,1);			
					// pr($result2);
					foreach ($result2 as $value){
						$nilai = $nilai + $value['pagu_bulan'];
					}
				}	
		}	
		// pr($query);
		$resultfix['pagu_bulan']= $nilai;
		return $resultfix;	
	}
	function pagu_akun_giat_triwulan($thn_temp,$kd_satker,$kd_giat,$kode_akun,$trwln,$param){
	if($param == 1){
		if($trwln == 1){
			$first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			$first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			$first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			$first_month = 10;
			$last_month = 12;
		}
		$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					AND mk.KDSATKER = '{$kd_satker}' AND mk.KDGIAT = '{$kd_giat}' 
					and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}'";
	}elseif($param == 2){
		if($trwln == 1){
			// $first_month = 1;
			$last_month = 3;
		}elseif($trwln == 2){
			// $first_month = 4;
			$last_month = 6;
		}elseif($trwln == 3){
			// $first_month = 7;
			$last_month = 9;
		}elseif($trwln == 4){
			// $first_month = 10;
			$last_month = 12;
		}
		$query = "select sum(ms.NILMAK) as pagu_bulan from m_spmmak as ms 
					inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D
					where ms.THANG = '{$thn_temp}' AND ms.KDAKUN = '{$kode_akun}'  
					AND mk.KDSATKER = '{$kd_satker}' AND mk.KDGIAT = '{$kd_giat}' 
					and MONTH(ms.TGSP2D) <= '{$last_month}'";
	}	
	// pr($query);
	$result = $this->fetch($query);
		// pr($result);
		if($result['pagu_bulan'] == 0){
			$result['pagu_bulan']= 0;
		}
		return $result;	
	}
}
?>