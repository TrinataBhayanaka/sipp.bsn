<?php
class m_pelaporankeuangan extends Database {
	
	// m_spmmak dam m_spmind (upload foxpro dari iman)
	// d_item ,d_trktrm (upload foxpro dari bayu)
	
	// m_spmmak
	function select_data_master_bsn($thn_temp)
	{
		$query = "select THANG, KDSATKER, sum(NILMAK) as real_satker from m_spmmak WHERE THANG='{$thn_temp}' AND left(KDAKUN,1) = '5' group by KDSATKER";
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
		$query = "select KDGIAT, sum(TOTNILMAK) as real_giat from m_spmind WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT<>'0000' group by KDGIAT";
		// pr($query);
		$result = $this->fetch($query,1);
		
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
	
	//d_item
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
	
	// m_spmind
	function pagutotal_kode_output_kegiatan_condtn($thn_temp,$kd_satker,$kd_giat,$kd_output)
	{
		$query = "select KDOUTPUT, sum(TOTNILMAK) as real_output from m_spmind WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' and KDOUTPUT = '{$kd_output}'";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	//d_item
	function pagutotal_kode_output_kegiatan_perbulan($thn_temp,$kd_satker,$kd_giat)
	{
		$query = "select KDOUTPUT, sum(jumlah) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
					KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
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
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and mk.KDGIAT <> '0000' order by NILMAK";
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
	
	// m_spmind dan m_spmmak
	function realisasi_perbulan_unit_kegiatan($thn_temp,$monthArray,$kd_giat,$kd_satker)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
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
	
	// m_spmind dan m_spmmak
	function penarikan_unit_perbulan_kegiatan_perbulan($thn_temp,$monthArray,$kd_satker,$kd_giat,$kd_output)
	{
		foreach ($monthArray as $val) {	
			// $query = "SELECT COUNT(1) AS total FROM user WHERE  YEAR(register_date) = {$year} AND MONTH(register_date) = {$val} AND n_status IN (1)";
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					   inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					   where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) = '{$val}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}' order by NILMAK";
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
	
	// m_spmind dan m_spmmak
	function realisasi_allbulan_unit($thn_temp,$max_bulan)
	{
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and mk.KDGIAT <> '0000' order by NILMAK";
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
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' order by NILMAK";
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
			$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$max_bulan}' and ms.KDSATKER = '{$kd_satker}' and mk.KDGIAT ='{$kd_giat}' and mk.KDOUTPUT ='{$kd_output}'  order by NILMAK";
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
					AND KDGIAT = '{$kd_giat}' group by KDGIAT
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
	
	// m_spmind dan m_spmmak
	function real_menteri_triwulan_BSN($thn_temp,$trwln)
	{
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
	
	// m_spmind dan m_spmmak
	function real_satker_triwulan($thn_temp,$trwln,$kd_satker)
	{
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
	
	// m_spmind dan m_spmmak
	function real_giat_triwulan($thn_temp,$trwln,$kd_satker,$kd_giat)
	{
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' order by NILMAK";
			
			}
			
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
			if($trwln == 1){
				$first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			
			}else if($trwln == 2){
				$first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			
			}else if($trwln == 3){
				$first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			
			}else if($trwln == 4){
				$first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) >= '{$first_month}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}' order by NILMAK";
			
			}
			
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
			if($trwln == 1){
				// $first_month = 1;
				$last_month = 3;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			
			}else if($trwln == 2){
				// $first_month = 4;
				$last_month = 6;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			
			}else if($trwln == 3){
				// $first_month = 7;
				$last_month = 9;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			
			}else if($trwln == 4){
				// $first_month = 10;
				$last_month = 12;
				$query = "select sum(ms.NILMAK) as jml from m_spmmak as ms 
					  inner join m_spmind as mk on mk.NOSP2D = ms.NOSP2D 
					  where ms.THANG = '{$thn_temp}' and MONTH(ms.TGSP2D) <= '{$last_month}' and ms.KDSATKER = {$kd_satker} and mk.KDGIAT = {$kd_giat} and mk.KDGIAT <> '0000' and mk.KDOUTPUT = '{$kd_output}'  order by NILMAK";
			
			}
			
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
	
	
}
?>