<?php
class m_penetapanAngaran extends Database {
	
	// m_spmmak dam m_spmind (upload foxpro dari iman)
	// d_item ,d_trktrm (upload foxpro dari bayu)
	function get_bobot_trwln($param,$kd_unit,$kd_giat,$kd_output,$kd_komponen){
		if($param == 3){
			$queryext="target_3 as tw_target";
		}elseif($param == 6){
			$queryext="target_6 as tw_target";
		}elseif($param == 9){
			$queryext="target_9 as tw_target";
		}elseif($param == 12){
			$queryext="target_12 as tw_target";
		}
		$query ="select {$queryext} from monev_bulanan where kategori = 3 and kdunitkerja = {$kd_unit} and kdgiat = {$kd_giat} and kdoutput = {$kd_output} and kdkmpnen = {$kd_komponen}" ;
		// pr($query);
		$result = $this->fetch($query);
		return $result; 
	}
	
	function get_anggaran_trwln($param,$kd_unit,$kd_giat,$kd_output,$kd_komponen){
		if($param == 3){
			$queryext="anggaran_3 as tw_anggaran";
		}elseif($param == 6){
			$queryext="anggaran_6 as tw_anggaran";
		}elseif($param == 9){
			$queryext="anggaran_9 as tw_anggaran";
		}elseif($param == 12){
			$queryext="anggaran_12 as tw_anggaran";
		}
		$query ="select {$queryext} from monev_bulanan where kategori = 3 and kdunitkerja = {$kd_unit} and kdgiat = {$kd_giat} and kdoutput = {$kd_output} and kdkmpnen = {$kd_komponen}" ;
		// pr($query);
		$result = $this->fetch($query);
		return $result; 
	}
	
	
	function bobot_komponen($thn_temp,$kd_giat,$kd_output,$kd_komponen){
		$query = "select bobot from thbp_kak_output_bobot WHERE th='{$thn_temp}' and kdgiat = '{$kd_giat}' and kdoutput ='{$kd_output}' and kdkmpnen = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result; 
	}
	
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
	
	function pagutotal_kode_output_kegiatan_perbulan_rev($thn_temp,$kd_giat)
	{
		$query = "select KDOUTPUT, sum(jumlah) as pagu_output from d_item WHERE THANG='{$thn_temp}'  
					and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function pagutotal_kode_output_kegiatan_perbulan_condtn($thn_temp,$kd_satker,$kd_giat,$kd_output)
	{
		$query = "select KDOUTPUT, sum(jumlah) as pagu_output from d_item WHERE THANG='{$thn_temp}' and 
					KDSATKER='{$kd_satker}' and KDGIAT = '{$kd_giat}' and  KDOUTPUT = '{$kd_output}' group by KDOUTPUT";
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
	
	//baru buat bayu
	function anggaran_belanja_menteri_pegawai($thn_temp)
	{
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				KDAKUN LIKE '51%' AND KDDEPT = '084' group by THANG";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function anggaran_belanja_menteri_barang($thn_temp)
	{
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND KDAKUN LIKE '52%' 
				AND KDAKUN NOT LIKE '524%' AND KDDEPT = '084' group by THANG";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function anggaran_belanja_menteri_modal($thn_temp)
	{
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				KDAKUN LIKE '53%' AND KDDEPT = '084' group by THANG";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function anggaran_belanja_menteri_perjalanan($thn_temp)
	{
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND 
				KDAKUN LIKE '524%' AND KDDEPT = '084' group by THANG";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function cek_kegiatan_group_scnd($thn_temp,$kd_satker)
	{
		$query = "select KDGIAT,sum(JUMLAH) as pagu_giat from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' group by KDGIAT";
		// $query ="select sum(JUMLAH) as pagu_giat from d_item WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDGIAT";		  
		// pr($query);
		// exit;
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function cek_kegiatan_group_scnd_rev($thn_temp,$kd_giat)
	{
		$query ="select sum(JUMLAH) as pagu_giat from d_item WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDGIAT";		  
		// pr($query);
		// exit;
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function anggaran_belanja_menteri_pegawai_giat($thn_temp,$kd_giat)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				left(KDAKUN,2) = '51' AND KDSATKER = '{$kd_satker}' AND KDGIAT ='{$kd_giat}' group by KDSATKER";*/
				
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				KDAKUN LIKE '51%' AND KDGIAT ='{$kd_giat}'";
		// pr($query);
		
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_barang_giat($thn_temp,$kd_giat)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND left(KDAKUN,2) = '52' 
				AND left(KDAKUN,3) <> '524' AND KDSATKER = '{$kd_satker}' AND KDGIAT ='{$kd_giat}' group by KDSATKER";*/
		
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND KDAKUN LIKE '52%' 
				AND KDAKUN NOT LIKE '524%' AND KDGIAT ='{$kd_giat}' ";		
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_modal_giat($thn_temp,$kd_giat)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				left(KDAKUN,2) = '53' AND KDSATKER = '{$kd_satker}' AND KDGIAT ='{$kd_giat}' group by KDSATKER";*/
				
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				  KDAKUN LIKE '53%'  AND KDGIAT ='{$kd_giat}'";		
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_perjalanan_giat($thn_temp,$kd_giat)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND 
				left(KDAKUN,3) = '524' AND KDSATKER = '{$kd_satker}' AND KDGIAT ='{$kd_giat}' group by THANG";*/
				
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND 
				KDAKUN LIKE '524%'  AND KDGIAT ='{$kd_giat}'";
				
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	
	function anggaran_belanja_menteri_pegawai_output($thn_temp,$kd_giat,$kd_output)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				left(KDAKUN,2) = '51' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}'
				group by KDSATKER";*/
				
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				KDAKUN LIKE '51%' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}'
				";		
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_barang_output($thn_temp,$kd_giat,$kd_output)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND left(KDAKUN,2) = '52' 
				AND left(KDAKUN,3) <> '524' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}' group by KDSATKER";*/
		
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND KDAKUN LIKE '52%' 
				AND KDAKUN NOT LIKE '524%' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}' ";
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_modal_output($thn_temp,$kd_giat,$kd_output)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				left(KDAKUN,2) = '53' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}' 
				group by KDSATKER";*/
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND
				KDAKUN LIKE '53%' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}' 
				";		
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function anggaran_belanja_menteri_perjalanan_output($thn_temp,$kd_giat,$kd_output)
	{
		/*$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND 
				left(KDAKUN,3) = '524' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}' group by THANG";*/
				
		$query = "select sum(JUMLAH) as pagu_satker from d_item WHERE THANG='{$thn_temp}' AND 
				KDAKUN LIKE '524%' AND KDGIAT ='{$kd_giat}' AND KDOUTPUT= '{$kd_output}'";		
		// pr($query);
		$result = $this->fetch($query);
		if($result['pagu_satker'] == 0){
			$result['pagu_satker']= 0;
		}
		return $result;
	}
	
	function detail084($thn_temp){
		$query = "select sum(RPHPAGU) as RPHPAGU,
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
					from d_trktrm WHERE THANG='{$thn_temp}' AND JNSBELANJA <> '42' group by THANG";
		
	$result = $this->fetch($query);
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
	
	function detail084_jb($thn_temp){
		$query = "select JNSBELANJA, sum(RPHPAGU) as RPHPAGU,
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
					from d_trktrm WHERE THANG='{$thn_temp}' AND JNSBELANJA <> '42' group by JNSBELANJA";
	$result = $this->fetch($query,1);
	
	return $result;
	}
	
	function detailkgtn($thn_temp,$kd_satker,$kd_giat){
		/*$query = "select RPHPAGU,JML01,JML02,JML03,JML04,JML05,JML06,JML07,JML08,JML09,JML10,JML11,JML12  from d_trktrm 
				  WHERE THANG='{$thn_temp}' AND KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' AND JNSBELANJA <> '42' ";*/
		 $query = "select sum(RPHPAGU) as RPHPAGU,
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
					from d_trktrm WHERE THANG='{$thn_temp}' AND KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' AND JNSBELANJA <> '42'";	  
		// pr($query);
	$result = $this->fetch($query);
	return $result;
	}
	
	function detail_jb($thn_temp,$kd_satker,$kd_giat){
		$query = "select JNSBELANJA, sum(RPHPAGU) as RPHPAGU,
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
					from d_trktrm WHERE THANG='{$thn_temp}' AND KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' AND JNSBELANJA <> '42' group by JNSBELANJA";
		// pr($query);
		$result = $this->fetch($query,1);
		return $result;
	}
	
	function cek_kegiatan_group_scnd_sub($thn_temp,$kd_satker,$kd_giat)
	{
		$query = "select KDGIAT,sum(JUMLAH) as pagu_giat from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' 
				  and KDGIAT =  '{$kd_giat}' group by KDGIAT";
				  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function select_soutput($thn_temp,$kd_satker,$kd_giat,$kd_output)
	{
		$query = "select KDSOUTPUT, sum(JUMLAH) as pagu_soutput from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' 
				and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' group by KDSOUTPUT";
				  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function nama_soutput($thn_temp,$kd_giat,$kd_output,$kd_soutput)
	{
		$query = "select URSOUTPUT from d_soutput where THANG='{$thn_temp}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' 
				  and KDSOUTPUT='{$kd_soutput}'";
				  
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_komponen($thn_temp,$kd_satker,$kd_giat,$kd_output,$kd_soutput)
	{
		$query = "select KDKMPNEN, sum(JUMLAH) as pagu_kmpnen from d_item WHERE THANG='{$thn_temp}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' and KDSOUTPUT='{$kd_soutput}' group by KDKMPNEN";
				  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	function nama_komponen($thn_temp,$kd_giat,$kd_output,$kd_soutput,$kd_komponen)
	{
		$query = "select URKMPNEN from d_kmpnen where THANG='{$thn_temp}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' 
				 and KDSOUTPUT='{$kd_soutput}' and KDKMPNEN='{$kd_komponen}'";
				  
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_komponen_sub($thn_temp,$kd_satker,$kd_giat,$kd_output,$kd_soutput,$kd_komponen)
	{
		$query = "select KDSKMPNEN, sum(JUMLAH) as pagu_skmpnen from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' and KDSOUTPUT='{$kd_soutput}' and KDKMPNEN='{$kd_komponen}' group by KDSKMPNEN";
				  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function nama_komponen_sub($thn_temp,$kd_giat,$kd_output,$kd_soutput,$kd_komponen,$kd_komponen_sub)
	{
		$query = "select URSKMPNEN from d_skmpnen where THANG='{$thn_temp}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' 
				and KDSOUTPUT='{$kd_soutput}' 
				and KDKMPNEN='{$kd_komponen}' and KDSKMPNEN='{$kd_komponen_sub}'";
				  
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_kode_akun_sub($thn_temp,$kd_satker,$kd_giat,$kd_output,$kd_soutput,$kd_komponen,$kd_komponen_sub)
	{
		$query = "select KDAKUN, sum(JUMLAH) as pagu_akun from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' and KDSOUTPUT='{$kd_soutput}' and KDKMPNEN='{$kd_komponen}' and KDSKMPNEN='{$kd_komponen_sub}' group by KDAKUN";
				  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function nm_akun($kd_akun){
		$query = "select NMAKUN from t_akun where KDAKUN = '{$kd_akun}'";
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function select_item($thn_temp,$kd_satker,$kd_giat,$kd_output,$kd_soutput,$kd_komponen,$kd_komponen_sub,$kd_akun){
		$query = "select NMITEM,VOLKEG,SATKEG,JUMLAH,HARGASAT from d_item WHERE THANG='{$thn_temp}' and KDSATKER='{$kd_satker}' and KDGIAT='{$kd_giat}' and KDOUTPUT='{$kd_output}' and KDSOUTPUT='{$kd_soutput}' and KDKMPNEN='{$kd_komponen}' and KDSKMPNEN='{$kd_komponen_sub}' and KDAKUN='{$kd_akun}'";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function list_dropdown(){
		$query = "select * from tb_unitkerja WHERE right(kdunit,3) <> '000' order by kdunit";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function list_unit($unit){
		$ext_query = "kdunit like '{$unit}%' AND MID(kdunit,4,1) NOT LIKE '0'";
		$query = "select * from tb_unitkerja WHERE {$ext_query} order by kdunit limit 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function list_dropdown_cstmn($param,$unit){
		if($param == 1){
			$ext_query = "kdunit like '{$unit}%'  AND MID(kdunit,4,1) NOT LIKE '0'";
		}elseif($param == 2){
			$ext_query = "kdunit = '{$unit}'";
		}
		$query = "select * from tb_unitkerja WHERE {$ext_query} order by kdunit asc ";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function kd_kegiatan($thn_temp,$kd_satker){
		$query = "SELECT kdgiat,nmgiat FROM m_kegiatan WHERE ta = '{$thn_temp}' and kdunitkerja = '{$kd_satker}' order by kdgiat asc";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function pagu_giat($thn_temp,$kd_giat){
		$query = "select KDGIAT, sum(JUMLAH) as pagu_giat from d_item WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDGIAT";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function output($thn_temp,$kd_giat){
		$query = "select KDOUTPUT, sum(JUMLAH) as pagu_output from d_item WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' group by KDOUTPUT";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function output_cndtn($thn_temp,$kd_giat,$kd_output){
		$query = "select KDOUTPUT, sum(JUMLAH) as pagu_output from d_item WHERE THANG='{$thn_temp}' and KDGIAT = '{$kd_giat}' 
				  and KDOUTPUT = '{$kd_output}'
				  group by KDOUTPUT";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function nama_unit($kd_unit){
		$query = "select nmunit from tb_unitkerja WHERE kdunit='{$kd_unit}'";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function rincian($thn,$kd_unit,$kd_giat,$kd_output){
		$query = "SELECT * FROM thbp_kak_output WHERE th = '{$thn}' and kdgiat = '{$kd_giat}' and kdoutput = '{$kd_output}' 
				  and kdunitkerja = '{$kd_unit}'  order by id desc limit 1";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function thp_kegiatan($thn,$kd_giat,$kd_output){
		$query = "SELECT KDKMPNEN,KDSOUTPUT,sum(JUMLAH) as pagu_kmpnen FROM d_item WHERE THANG = '{$thn}' and KDGIAT = '{$kd_giat}' 
							     and KDOUTPUT = '{$kd_output}' group by KDKMPNEN order by kdsoutput,kdkmpnen";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	function thp_kegiatan_condotion_monev($thn,$kd_giat,$kd_output,$kd_komponen){
		$query = "SELECT KDKMPNEN,KDSOUTPUT,sum(JUMLAH) as pagu_kmpnen FROM d_item WHERE THANG = '{$thn}' and KDGIAT = '{$kd_giat}' 
							     and KDOUTPUT = '{$kd_output}' and KDKMPNEN = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	
	function thp_kegiatan_condtn($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput){
		$query = "SELECT KDKMPNEN,KDSOUTPUT,sum(JUMLAH) as pagu_kmpnen FROM d_item WHERE THANG = '{$thn}' and KDGIAT = '{$kd_giat}' 
							     and KDOUTPUT = '{$kd_output}' and KDKMPNEN='{$kd_komponen}' and KDSOUTPUT = '{$kd_soutput}' group by KDKMPNEN order by kdsoutput,kdkmpnen";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function komponen($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput){
		$query = "SELECT URKMPNEN FROM d_kmpnen WHERE THANG = '{$thn}' and KDGIAT = '{$kd_giat}' 
				  and KDOUTPUT = '{$kd_output}' and KDSOUTPUT = '{$kd_soutput}' 
				  and KDKMPNEN = '{$kd_komponen}' ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function sub_komponen($thn,$kd_giat,$kd_output,$kd_komponen){
		$query = "SELECT * FROM thbp_kak_output_tahapan WHERE th = '{$thn}' AND kdgiat = '{$kd_giat}' 
				  AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}' ORDER BY  id";
		//ORDER BY kd_tahapan		  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function sub_komponen_cdtn($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput){
		$query = "SELECT * FROM thbp_kak_output_tahapan WHERE th = '{$thn}' AND kdgiat = '{$kd_giat}' 
				  AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}' 
				  and kdkmpnen = '{$kd_komponen}' and kdsoutput = '{$kd_soutput}'
				  ORDER BY id ";
		//ORDER BY kd_tahapan		  
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function sub_komponen_cdtn_sub($thn,$kd_giat,$kd_output,$kd_komponen,$kd_soutput,$id){
		$query = "SELECT * FROM thbp_kak_output_tahapan WHERE th = '{$thn}' AND kdgiat = '{$kd_giat}' 
				  AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}' 
				  and kdkmpnen = '{$kd_komponen}' and kdsoutput = '{$kd_soutput}' and id='{$id}'
				  ORDER BY kd_tahapan ";
		// pr($query);
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function update($tujuan,$sasaran_1,$sasaran_2,$sasaran_3,$sasaran_4,
					$ursasaran_1,$ursasaran_2,$ursasaran_3,$ursasaran_4,
					$status,$tgl_kirim,$kdunitkerja,$kdgiat,$kdoutput,
					$id,$th){
		// pr($sasaran_1);
		$query = "UPDATE thbp_kak_output SET tujuan = '{$tujuan}', 
								sasaran_1 = '{$ursasaran_1}' ,  sasaran_2 = '{$ursasaran_1}' ,
								sasaran_3 = '{$ursasaran_3}' , sasaran_4 = '{$ursasaran_4}' ,
								ursasaran_1 = '{$sasaran_1}' , ursasaran_2 = '{$sasaran_2}' ,
								ursasaran_3 = '{$sasaran_3}' , ursasaran_4 = '{$sasaran_4}' ,
							   status = '{$status}', tgl_kirim = '{$tgl_kirim}' ,
							   kdunitkerja = '{$kdunitkerja}', kdgiat = '{$kdgiat}' ,kdoutput = '{$kdoutput}',
							   th = '{$th}'
							   WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}
	
	function insert($tujuan,$sasaran_1,$ursasaran_1,$sasaran_2,$ursasaran_2,
					$sasaran_3,$ursasaran_3,$sasaran_4,$ursasaran_4,$status,
					$tgl_kirim,$kdunitkerja,$kdgiat,$kdoutput,$id,$th){
		$query = "INSERT INTO thbp_kak_output (th,kdunitkerja,kdgiat,kdoutput,tujuan,
						sasaran_1,sasaran_2,sasaran_3,sasaran_4,
						ursasaran_1,ursasaran_2,ursasaran_3,ursasaran_4)
						VALUES ( '{$th}' , '{$kdunitkerja}' , '{$kdgiat}' , '{$kdoutput}' , '{$tujuan}' ,
						'{$ursasaran_1}' , '{$ursasaran_2}' , '{$ursasaran_3}' , '{$ursasaran_4}' ,
						'{$sasaran_1}' , '{$sasaran_2}' , '{$sasaran_3}' , '{$sasaran_4}'  )";
		// pr($query);
		// exit;
		$result = $this->query($query);
	}
	
	function insert_data($kd_tahapan,$nm_tahapan,$thn,$kd_unit,$kd_giat,$kd_output,$kd_komponen,$kd_soutput){
		$query = "INSERT INTO thbp_kak_output_tahapan (th,kdunitkerja,kdgiat,kdoutput,kdsoutput,kdkmpnen,
							kd_tahapan,nm_tahapan)
						VALUES ( '{$thn}' , '{$kd_unit}' , '{$kd_giat}' , '{$kd_output}' , '{$kd_soutput}' ,
						'{$kd_komponen}' , '{$kd_tahapan}' , '{$nm_tahapan}' )";
		// pr($query);
		// exit;
		$result = $this->query($query);
	}
	function edit_data($id){
		$query = "SELECT kd_tahapan,nm_tahapan,id FROM thbp_kak_output_tahapan WHERE id='{$id}'
				  ORDER BY kd_tahapan ";
		// pr($query);
		$result = $this->fetch($query);
		
		return $result;
	}
	
	function update_data($id,$kd_tahapan,$nm_tahapan){
		// pr($sasaran_1);
		$query = "UPDATE thbp_kak_output_tahapan SET kd_tahapan = '{$kd_tahapan}', 
								nm_tahapan = '{$nm_tahapan}'
							   WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}
	
	function hapus_data($id){
		// pr($sasaran_1);
		$query = "DELETE FROM thbp_kak_output_tahapan WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}
	
	function update_data_sub($id,$target_1,$target_2,$target_3,$target_4,$target_5,$target_6,
							 $target_7,$target_8,$target_9,$target_10,$target_11,$target_12,
							 $anggaran_1,$anggaran_2,$anggaran_3,$anggaran_4,$anggaran_5,$anggaran_6,
							 $anggaran_7,$anggaran_8,$anggaran_9,$anggaran_10,$anggaran_11,$anggaran_12){
		// pr($sasaran_1);
		$query = "UPDATE thbp_kak_output_tahapan SET target_1 = '{$target_1}', target_2 = '{$target_2}',target_3 = '{$target_3}',target_4 = '{$target_4}',target_5 = '{$target_5}',
													 target_6 = '{$target_6}', target_7 = '{$target_7}',target_8 = '{$target_8}',target_9 = '{$target_9}',target_10 = '{$target_10}',target_11 = '{$target_11}',target_12 = '{$target_12}',	
													 anggaran_1 ='{$anggaran_1}',anggaran_2 ='{$anggaran_2}',anggaran_3 ='{$anggaran_3}',anggaran_4 ='{$anggaran_4}',anggaran_5 ='{$anggaran_5}',anggaran_6 ='{$anggaran_6}',
													 anggaran_7 ='{$anggaran_7}',anggaran_8 ='{$anggaran_8}',anggaran_9 ='{$anggaran_9}',anggaran_10 ='{$anggaran_10}',anggaran_11 ='{$anggaran_11}',anggaran_12 ='{$anggaran_12}'
							  WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}
	
	function thn_aktif(){
		$query = "select kode,data from bsn_sistem_setting where `desc` like 'tahun_sistem%' and n_status =1 ";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}

	function getTahunAktif()
	{
		$sql = "SELECT * FROM bsn_sistem_setting WHERE `desc` = 'tahun_sistem' AND n_status = '1'";
		$result = $this->fetch($sql);
		return $result;
	}

	function del_peryear($table,$tahun)
	{
		$sql = "DELETE FROM {$table} WHERE THANG = '{$tahun}'";
		$res = $this->query($sql);

		return true;
	}
	
	function monev_ren_sd_bulan($thn_temp,$kd_giat,$kd_output,$kd_komponen,$param,$ext){
		if($ext == 1){
			$table = "thbp_kak_output_tahapan"; 
		}elseif($ext == 2){
			$table = "monev_bulanan"; 
		}
		switch ($param){
			case 01:
					$ext_sql = "sum(target_1) as total";break;
			case 02:
					$ext_sql = "(sum(target_1) + sum(target_2)) as total"; break;
			case 03:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3)) as total"; break;
			case 04:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4)) as total"; break;
			case 05:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5)) as total"; break;
			case 06:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6)) as total"; break;
			case 07:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7)) as total"; break;
			case 08:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7) + sum(target_8)) as total"; break;
			case 09:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7) + sum(target_8) + sum(target_9)) as total"; break;
			case 10:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7) + sum(target_8) + sum(target_9) + sum(target_10)) as total"; break;
			case 11:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7) + sum(target_8) + sum(target_9) + sum(target_10) + sum(target_11)) as total"; break;
			case 12:
					$ext_sql = "(sum(target_1) + sum(target_2) + sum(target_3) + sum(target_4) + sum(target_5) + sum(target_6) +  sum(target_7) + sum(target_8) + sum(target_9) + sum(target_10) + sum(target_11) + sum(target_12)) as total"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM {$table}  
				WHERE th LIKE '{$thn_temp}' AND kdgiat LIKE '{$kd_giat}' AND kdoutput LIKE '{$kd_output}' 
				and kdkmpnen like '{$kd_komponen}%' ORDER BY id";
		// pr($query);		
		$result = $this->fetch($query);
		return $result;
	}
	
	function monev_ren_sd_bulan_anggaran($thn_temp,$kd_giat,$kd_output,$kd_komponen,$param){
		
		switch ($param){
			case 01:
					$ext_sql = "sum(anggaran_1) as total";break;
			case 02:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2)) as total"; break;
			case 03:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3)) as total"; break;
			case 04:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4)) as total"; break;
			case 05:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5)) as total"; break;
			case 06:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6)) as total"; break;
			case 07:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7)) as total"; break;
			case 08:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8)) as total"; break;
			case 09:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9)) as total"; break;
			case 10:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10) ) as total"; break;
			case 11:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10) + sum(anggaran_11)) as total"; break;
			case 12:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10) + sum(anggaran_11) + sum(anggaran_12)) as total"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM thbp_kak_output_tahapan  
				WHERE th LIKE '{$thn_temp}' AND kdgiat LIKE '{$kd_giat}' AND kdoutput LIKE '{$kd_output}' 
				and kdkmpnen like '{$kd_komponen}%' ORDER BY id";
		$result = $this->fetch($query);
		return $result;
	}
	
	function monev_realisasi_sd_bulan_anggaran($id,$param){
		
		switch ($param){
			case 01:
					$ext_sql = "sum(anggaran_1) as realisasi";break;
			case 02:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2)) as realisasi"; break;
			case 03:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3)) as realisasi"; break;
			case 04:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4)) as realisasi"; break;
			case 05:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5)) as realisasi"; break;
			case 06:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6)) as realisasi"; break;
			case 07:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7)) as realisasi"; break;
			case 08:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8)) as realisasi"; break;
			case 09:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9)) as realisasi"; break;
			case 10:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10)) as realisasi"; break;
			case 11:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10) + sum(anggaran_11)) as realisasi"; break;
			case 12:
					$ext_sql = "(sum(anggaran_1) + sum(anggaran_2) + sum(anggaran_3) + sum(anggaran_4) + sum(anggaran_5) + sum(anggaran_6) +  sum(anggaran_7) + sum(anggaran_8) + sum(anggaran_9) + sum(anggaran_10) + sum(anggaran_11) + sum(anggaran_12)) as realisasi"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM monev_bulanan  
				WHERE id = '{$id}'";
		$result = $this->fetch($query);
		return $result;
	}
	
	
	function ceck_id($thn_temp,$kd_giat,$kd_output,$kd_komponen,$param){
		if($param == 1){
			$kategori = 1;
		}elseif($param == 2){
			$kategori = 2;
		}else{	
			$kategori = 3;
		}
		
		$query = "select count(id) as hit,id from monev_bulanan WHERE th LIKE '{$thn_temp}%' 
				  AND kdgiat LIKE '{$kd_giat}%' AND kdoutput LIKE '{$kd_output}%' 
				  and kdkmpnen like '{$kd_komponen}%' and kategori = '{$kategori}'";
		$result = $this->fetch($query);
		return $result;
	}
	
	function insert_monev_trwln($th,$kdunitkerja,$kdgiat,$kdoutput,$kdkmpnen,
								$kendala,$tindaklanjut,$ygmembantu,$keterangan,$kdtriwulan){
		$kategori = '3';
		if($kdtriwulan == 1){
			$ext_keterangan ="keterangan";
			$ext_kendala ="kendala";
			$ext_tindaklanjut = "tindaklanjut";
			$ext_ygmembantu = "ygmembantu";
		}elseif($kdtriwulan == 2){
			$ext_keterangan ="keterangan_2";
			$ext_kendala ="kendala_2";
			$ext_tindaklanjut = "tindaklanjut_2";
			$ext_ygmembantu = "ygmembantu_2";
		}elseif($kdtriwulan == 3){
			$ext_keterangan ="keterangan_3";
			$ext_kendala ="kendala_3";
			$ext_tindaklanjut = "tindaklanjut_3";
			$ext_ygmembantu = "ygmembantu_3";
		}elseif($kdtriwulan == 4){
			$ext_keterangan ="keterangan_4";
			$ext_kendala ="kendala_4";
			$ext_tindaklanjut = "tindaklanjut_4";
			$ext_ygmembantu = "ygmembantu_4";
		}
		$query = "INSERT INTO monev_bulanan (th,kdunitkerja,kdgiat,kdoutput,kdkmpnen,
						{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu},{$ext_keterangan},kategori)
						VALUES ('{$th}' , '{$kdunitkerja}' , '{$kdgiat}' , '{$kdoutput}' , '{$kdkmpnen}' ,
						'".addslashes(html_entity_decode($kendala))."' , '".addslashes(html_entity_decode($tindaklanjut))."' , 
						'".addslashes(html_entity_decode($ygmembantu))."' , 
						'".addslashes(html_entity_decode($keterangan))."' , 
						'{$kategori}')";
		$result = $this->query($query);				
	}
	
	function update_monev_trwln($kendala,$tindaklanjut,$ygmembantu,$keterangan,$id,$kdtriwulan){
		if($kdtriwulan == 1){
			$ext_keterangan ="keterangan";
			$ext_kendala ="kendala";
			$ext_tindaklanjut = "tindaklanjut";
			$ext_ygmembantu = "ygmembantu";
		}elseif($kdtriwulan == 2){
			$ext_keterangan ="keterangan_2";
			$ext_kendala ="kendala_2";
			$ext_tindaklanjut = "tindaklanjut_2";
			$ext_ygmembantu = "ygmembantu_2";
		}elseif($kdtriwulan == 3){
			$ext_keterangan ="keterangan_3";
			$ext_kendala ="kendala_3";
			$ext_tindaklanjut = "tindaklanjut_3";
			$ext_ygmembantu = "ygmembantu_3";
		}elseif($kdtriwulan == 4){
			$ext_keterangan ="keterangan_4";
			$ext_kendala ="kendala_4";
			$ext_tindaklanjut = "tindaklanjut_4";
			$ext_ygmembantu = "ygmembantu_4";
		}
		
		$query = "UPDATE monev_bulanan SET {$ext_kendala} = '".addslashes(html_entity_decode($kendala))."', 
								{$ext_tindaklanjut} = '".addslashes(html_entity_decode($tindaklanjut))."' ,  
								{$ext_ygmembantu} = '".addslashes(html_entity_decode($ygmembantu))."' ,
								{$ext_keterangan} = '".addslashes(html_entity_decode($keterangan))."' 
								WHERE id = '{$id}'";
		// pr($query);	
		// exit;		
		// $result = $this->query($query);				
	}
	
	function get_data_monev_trwln($id,$kdtriwulan){
		if($kdtriwulan == 1){
			$ext_keterangan ="keterangan";
			$ext_kendala ="kendala";
			$ext_tindaklanjut = "tindaklanjut";
			$ext_ygmembantu = "ygmembantu";
		}elseif($kdtriwulan == 2){
			$ext_keterangan ="keterangan_2";
			$ext_kendala ="kendala_2";
			$ext_tindaklanjut = "tindaklanjut_2";
			$ext_ygmembantu = "ygmembantu_2";
		}elseif($kdtriwulan == 3){
			$ext_keterangan ="keterangan_3";
			$ext_kendala ="kendala_3";
			$ext_tindaklanjut = "tindaklanjut_3";
			$ext_ygmembantu = "ygmembantu_3";
		}elseif($kdtriwulan == 4){
			$ext_keterangan ="keterangan_4";
			$ext_kendala ="kendala_4";
			$ext_tindaklanjut = "tindaklanjut_4";
			$ext_ygmembantu = "ygmembantu_4";
		}
		$query = "select {$ext_keterangan},{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu} from monev_bulanan WHERE id='{$id}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	
	}
	
	function getdata($thn_temp,$kdtriwulan,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen){
		if($kdtriwulan == 1){
			$ext_keterangan ="keterangan";
			$ext_kendala ="kendala";
			$ext_tindaklanjut = "tindaklanjut";
			$ext_ygmembantu = "ygmembantu";
		}elseif($kdtriwulan == 2){
			$ext_keterangan ="keterangan_2";
			$ext_kendala ="kendala_2";
			$ext_tindaklanjut = "tindaklanjut_2";
			$ext_ygmembantu = "ygmembantu_2";
		}elseif($kdtriwulan == 3){
			$ext_keterangan ="keterangan_3";
			$ext_kendala ="kendala_3";
			$ext_tindaklanjut = "tindaklanjut_3";
			$ext_ygmembantu = "ygmembantu_3";
		}elseif($kdtriwulan == 4){
			$ext_keterangan ="keterangan_4";
			$ext_kendala ="kendala_4";
			$ext_tindaklanjut = "tindaklanjut_4";
			$ext_ygmembantu = "ygmembantu_4";
		}
		$query = "select {$ext_keterangan},{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu} from monev_bulanan 
				WHERE kdunitkerja= '{$kdunitkerja}' and kdgiat = '{$kd_giat}' and kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}' and kategori = 3";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
	function get_data_monev_bln($id,$param){
		switch ($param){
				case 1:	
						$ext_kendala = "kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "target_1 as jumlah";break; 
				case 2:
						$ext_kendala = "kendala,kendala_2";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2";
						$ext_clm = "target_2 as jumlah";break;
				case 3:
						$ext_kendala = "kendala,kendala_2,kendala_3";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3";
						$ext_clm = "target_3 as jumlah";break;
				case 4:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4";
						$ext_clm = "target_4 as jumlah";break;
				case 5:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5";
						$ext_clm = "target_5 as jumlah";break;
				case 6:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6";
						$ext_clm = "target_6 as jumlah";break;
				case 7:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7";
						$ext_clm = "target_7 as jumlah";break;
				case 8:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7,kendala_8";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7,tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7,ygmembantu_8";
						$ext_clm = "target_8 as jumlah";break;
				case 9:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7,kendala_8,kendala_9";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7,tindaklanjut_8,tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7,ygmembantu_8,ygmembantu_9";
						$ext_clm = "target_9 as jumlah";break;
				case 10:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7,kendala_8,kendala_9,kendala_10";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7,tindaklanjut_8,tindaklanjut_9,tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7,ygmembantu_8,ygmembantu_9,ygmembantu_10";
						$ext_clm = "target_10 as jumlah";break;
				case 11:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7,kendala_8,kendala_9,kendala_10,kendala_11";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7,tindaklanjut_8,tindaklanjut_9,tindaklanjut_10,tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7,ygmembantu_8,ygmembantu_9,ygmembantu_10,ygmembantu_11";
						$ext_clm = "target_11 as jumlah";break;
				case 12:
						$ext_kendala = "kendala,kendala_2,kendala_3,kendala_4,kendala_5,kendala_6,kendala_7,kendala_8,kendala_9,kendala_10,kendala_11,kendala_12";
						$ext_tindaklanjut = "tindaklanjut,tindaklanjut_2,tindaklanjut_3,tindaklanjut_4,tindaklanjut_5,tindaklanjut_6,tindaklanjut_7,tindaklanjut_8,tindaklanjut_9,tindaklanjut_10,tindaklanjut_11,tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu,ygmembantu_2,ygmembantu_3,ygmembantu_4,ygmembantu_5,ygmembantu_6,ygmembantu_7,ygmembantu_8,ygmembantu_9,ygmembantu_10,ygmembantu_11,ygmembantu_12";
						$ext_clm = "target_12 as jumlah";break;
			}	
		$query = "select keterangan,{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu},{$ext_clm} from monev_bulanan WHERE id='{$id}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	
	}
	
	function get_data_monev_bln_anggaran($id,$param){
		switch ($param){
				case 1:
						$ext_kendala = "kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "anggaran_1 as jumlah";break; 
				case 2:
						$ext_kendala = "kendala_2";
						$ext_tindaklanjut = "tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu_2";
						$ext_clm = "anggaran_2 as jumlah";break;
				case 3:
						$ext_kendala = "kendala_3";
						$ext_tindaklanjut = "tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu_3";
						$ext_clm = "anggaran_3 as jumlah";break;
				case 4:	
						$ext_kendala = "kendala_4";
						$ext_tindaklanjut = "tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu_4";
						$ext_clm = "anggaran_4 as jumlah";break;
				case 5:
						$ext_kendala = "kendala_5";
						$ext_tindaklanjut = "tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu_5";
						$ext_clm = "anggaran_5 as jumlah";break;
				case 6:
						$ext_kendala = "kendala_6";
						$ext_tindaklanjut = "tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu_6";
						$ext_clm = "anggaran_6 as jumlah";break;
				case 7:
						$ext_kendala = "kendala_7";
						$ext_tindaklanjut = "tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu_7";
						$ext_clm = "anggaran_7 as jumlah";break;
				case 8:
						$ext_kendala = "kendala_8";
						$ext_tindaklanjut = "tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu_8";
						$ext_clm = "anggaran_8 as jumlah";break;
				case 9:
						$ext_kendala = "kendala_9";
						$ext_tindaklanjut = "tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu_9";
						$ext_clm = "anggaran_9 as jumlah";break;
				case 10:
						$ext_kendala = "kendala_10";
						$ext_tindaklanjut = "tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu_10";
						$ext_clm = "anggaran_10 as jumlah";break;
				case 11:
						$ext_kendala = "kendala_11";
						$ext_tindaklanjut = "tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu_11";
						$ext_clm = "anggaran_11 as jumlah";break;
				case 12:
						$ext_kendala = "kendala_12";
						$ext_tindaklanjut = "tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu_12";
						$ext_clm = "anggaran_12 as jumlah";break;
			}	
		$query = "select keterangan,{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu},{$ext_clm} from monev_bulanan WHERE id='{$id}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	
	}
	
	
	function update_monev($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$target,$keterangan,$id){
		// pr($sasaran_1);
		switch ($bulan){
				case 01:
						$ext_kendala ="kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "target_1";break; 
				case 02:
						$ext_kendala ="kendala_2";
						$ext_tindaklanjut = "tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu_2";
						$ext_clm = "target_2";break;
				case 03:
						$ext_kendala ="kendala_3";
						$ext_tindaklanjut = "tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu_3";
						$ext_clm = "target_3";break;
				case 04:
						$ext_kendala ="kendala_4";
						$ext_tindaklanjut = "tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu_4";
						$ext_clm = "target_4";break;
				case 05:
						$ext_kendala ="kendala_5";
						$ext_tindaklanjut = "tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu_5";
						$ext_clm = "target_5";break;
				case 06:
						$ext_kendala ="kendala_6";
						$ext_tindaklanjut = "tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu_6";
						$ext_clm = "target_6";break;
				case 07:
						$ext_kendala ="kendala_7";
						$ext_tindaklanjut = "tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu_7";
						$ext_clm = "target_7";break;
				case 08:
						$ext_kendala ="kendala_8";
						$ext_tindaklanjut = "tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu_8";
						$ext_clm = "target_8";break;
				case 09:
						$ext_kendala ="kendala_9";
						$ext_tindaklanjut = "tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu_9";
						$ext_clm = "target_9";break;
				case 10:
						$ext_kendala ="kendala_10";
						$ext_tindaklanjut = "tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu_10";
						$ext_clm = "target_10";break;
				case 11:
						$ext_kendala ="kendala_11";
						$ext_tindaklanjut = "tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu_11";
						$ext_clm = "target_11";break;
				case 12:
						$ext_kendala ="kendala_12";
						$ext_tindaklanjut = "tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu_12";
						$ext_clm = "target_12";break;
			}	
		$query = "UPDATE monev_bulanan SET {$ext_kendala} = '".addslashes(html_entity_decode($kendala))."', 
								{$ext_tindaklanjut} = '".addslashes(html_entity_decode($tindaklanjut))."' ,  
								{$ext_ygmembantu} = '".addslashes(html_entity_decode($ygmembantu))."' ,
								keterangan = '".addslashes(html_entity_decode($keterangan))."' ,
								{$ext_clm} = '{$target}'
								WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}
	
	function insert_monev($th,$bulan,$kdunitkerja,$kdgiat,$kdoutput,$kdkmpnen,
						 $kendala,$tindaklanjut,$ygmembantu,$target,$keterangan){
		switch ($bulan){
				case 01:
						$ext_kendala ="kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "target_1";break; 
				case 02:
						$ext_kendala ="kendala_2";
						$ext_tindaklanjut = "tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu_2";
						$ext_clm = "target_2";break;
				case 03:
						$ext_kendala ="kendala_3";
						$ext_tindaklanjut = "tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu_3";
						$ext_clm = "target_3";break;
				case 04:
						$ext_kendala ="kendala_4";
						$ext_tindaklanjut = "tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu_4";
						$ext_clm = "target_4";break;
				case 05:
						$ext_kendala ="kendala_5";
						$ext_tindaklanjut = "tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu_5";
						$ext_clm = "target_5";break;
				case 06:
						$ext_kendala ="kendala_6";
						$ext_tindaklanjut = "tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu_6";
						$ext_clm = "target_6";break;
				case 07:
						$ext_kendala ="kendala_7";
						$ext_tindaklanjut = "tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu_7";
						$ext_clm = "target_7";break;
				case 08:
						$ext_kendala ="kendala_8";
						$ext_tindaklanjut = "tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu_8";
						$ext_clm = "target_8";break;
				case 09:
						$ext_kendala ="kendala_9";
						$ext_tindaklanjut = "tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu_9";
						$ext_clm = "target_9";break;
				case 10:
						$ext_kendala ="kendala_10";
						$ext_tindaklanjut = "tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu_10";
						$ext_clm = "target_10";break;
				case 11:
						$ext_kendala ="kendala_11";
						$ext_tindaklanjut = "tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu_11";
						$ext_clm = "target_11";break;
				case 12:
						$ext_kendala ="kendala_12";
						$ext_tindaklanjut = "tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu_12";
						$ext_clm = "target_12";break;
			}	
		$kategori = '1';
		/*$query = "INSERT INTO monev_bulanan (th,kdunitkerja,kdgiat,kdoutput,kdkmpnen,
						kendala,tindaklanjut,ygmembantu,keterangan,kategori,{$ext_clm})
						VALUES ('{$th}' , '{$kdunitkerja}' , '{$kdgiat}' , '{$kdoutput}' , '{$kdkmpnen}' ,
						'".addslashes(html_entity_decode($kendala))."' , '".addslashes(html_entity_decode($tindaklanjut))."' , 
						'".addslashes(html_entity_decode($ygmembantu))."' , 
						'".addslashes(html_entity_decode($keterangan))."' , 
						'{$kategori}' ,
						'{$target}')";*/
						
		$query = "INSERT INTO monev_bulanan (th,kdunitkerja,kdgiat,kdoutput,kdkmpnen,
						{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu},keterangan,kategori,{$ext_clm})
						VALUES ('{$th}' , '{$kdunitkerja}' , '{$kdgiat}' , '{$kdoutput}' , '{$kdkmpnen}' ,
						'".addslashes(html_entity_decode($kendala))."' , '".addslashes(html_entity_decode($tindaklanjut))."' , 
						'".addslashes(html_entity_decode($ygmembantu))."' , 
						'".addslashes(html_entity_decode($keterangan))."' , 
						'{$kategori}' ,
						'{$target}')";			
		// pr($query);
		// exit;
		$result = $this->query($query);
	}
	
	function insert_monev_anggaran($th,$bulan,$kdunitkerja,$kdgiat,$kdoutput,$kdkmpnen,
						 $kendala,$tindaklanjut,$ygmembantu,$realisasi){
		switch ($bulan){
				case 01:
						$ext_kendala ="kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "anggaran_1";break; 
				case 02:
						$ext_kendala ="kendala_2";
						$ext_tindaklanjut = "tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu_2";
						$ext_clm = "anggaran_2";break;
				case 03:
						$ext_kendala ="kendala_3";
						$ext_tindaklanjut = "tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu_3";
						$ext_clm = "anggaran_3";break;
				case 04:
						$ext_kendala ="kendala_4";
						$ext_tindaklanjut = "tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu_4";
						$ext_clm = "anggaran_4";break;
				case 05:
						$ext_kendala ="kendala_5";
						$ext_tindaklanjut = "tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu_5";
						$ext_clm = "anggaran_5";break;
				case 06:
						$ext_kendala ="kendala_6";
						$ext_tindaklanjut = "tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu_6";
						$ext_clm = "anggaran_6";break;
				case 07:
						$ext_kendala ="kendala_7";
						$ext_tindaklanjut = "tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu_7";
						$ext_clm = "anggaran_7";break;
				case 08:
						$ext_kendala ="kendala_8";
						$ext_tindaklanjut = "tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu_8";
						$ext_clm = "anggaran_8";break;
				case 09:
						$ext_kendala ="kendala_9";
						$ext_tindaklanjut = "tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu_9";
						$ext_clm = "anggaran_9";break;
				case 10:
						$ext_kendala ="kendala_10";
						$ext_tindaklanjut = "tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu_10";
						$ext_clm = "anggaran_10";break;
				case 11:
						$ext_kendala ="kendala_11";
						$ext_tindaklanjut = "tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu_11";
						$ext_clm = "anggaran_11";break;
				case 12:
						$ext_kendala ="kendala_12";
						$ext_tindaklanjut = "tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu_12";
						$ext_clm = "anggaran_12";break;
			}	
		$kategori = '2';
		$query = "INSERT INTO monev_bulanan (th,kdunitkerja,kdgiat,kdoutput,kdkmpnen,
						{$ext_kendala},{$ext_tindaklanjut},{$ext_ygmembantu},kategori,{$ext_clm})
						VALUES ('{$th}' , '{$kdunitkerja}' , '{$kdgiat}' , '{$kdoutput}' , '{$kdkmpnen}' ,
						'".addslashes(html_entity_decode($kendala))."' , '".addslashes(html_entity_decode($tindaklanjut))."' , 
						'".addslashes(html_entity_decode($ygmembantu))."' , 
						'{$kategori}' ,
						'{$realisasi}')";
		// pr($query);
		// exit;
		$result = $this->query($query);
	}
	
	function update_monev_anggaran($th,$bulan,$kendala,$tindaklanjut,$ygmembantu,$realisasi,$id){
		// pr($sasaran_1);
		switch ($bulan){
				case 01:
						$ext_kendala ="kendala";
						$ext_tindaklanjut = "tindaklanjut";
						$ext_ygmembantu = "ygmembantu";
						$ext_clm = "anggaran_1 = '{$realisasi}'";break; 
				case 02:
						$ext_kendala ="kendala_2";
						$ext_tindaklanjut = "tindaklanjut_2";
						$ext_ygmembantu = "ygmembantu_2";
						$ext_clm = "anggaran_2 = '{$realisasi}'";break;
				case 03:
						$ext_kendala ="kendala_3";
						$ext_tindaklanjut = "tindaklanjut_3";
						$ext_ygmembantu = "ygmembantu_3";
						$ext_clm = "anggaran_3 = '{$realisasi}'";break;
				case 04:
						$ext_kendala ="kendala_4";
						$ext_tindaklanjut = "tindaklanjut_4";
						$ext_ygmembantu = "ygmembantu_4";
						$ext_clm = "anggaran_4 = '{$realisasi}'";break;
				case 05:
						$ext_kendala ="kendala_5";
						$ext_tindaklanjut = "tindaklanjut_5";
						$ext_ygmembantu = "ygmembantu_5";
						$ext_clm = "anggaran_5 = '{$realisasi}'";break;
				case 06:
						$ext_kendala ="kendala_6";
						$ext_tindaklanjut = "tindaklanjut_6";
						$ext_ygmembantu = "ygmembantu_6";
						$ext_clm = "anggaran_6 = '{$realisasi}'";break;
				case 07:
						$ext_kendala ="kendala_7";
						$ext_tindaklanjut = "tindaklanjut_7";
						$ext_ygmembantu = "ygmembantu_7";
						$ext_clm = "anggaran_7 = '{$realisasi}'";break;
				case 08:
						$ext_kendala ="kendala_8";
						$ext_tindaklanjut = "tindaklanjut_8";
						$ext_ygmembantu = "ygmembantu_8";
						$ext_clm = "anggaran_8 = '{$realisasi}'";break;
				case 09:
						$ext_kendala ="kendala_9";
						$ext_tindaklanjut = "tindaklanjut_9";
						$ext_ygmembantu = "ygmembantu_9";
						$ext_clm = "anggaran_9 = '{$realisasi}'";break;
				case 10:
						$ext_kendala ="kendala_10";
						$ext_tindaklanjut = "tindaklanjut_10";
						$ext_ygmembantu = "ygmembantu_10";
						$ext_clm = "anggaran_10 = '{$realisasi}'";break;
				case 11:
						$ext_kendala ="kendala_11";
						$ext_tindaklanjut = "tindaklanjut_11";
						$ext_ygmembantu = "ygmembantu_11";
						$ext_clm = "anggaran_11 = '{$realisasi}'";break;
				case 12:
						$ext_kendala ="kendala_12";
						$ext_tindaklanjut = "tindaklanjut_12";
						$ext_ygmembantu = "ygmembantu_12";
						$ext_clm = "anggaran_12 = '{$realisasi}'";break;		
			}	
		$query = "UPDATE monev_bulanan SET {$ext_kendala} = '".addslashes(html_entity_decode($kendala))."', 
								{$ext_tindaklanjut} = '".addslashes(html_entity_decode($tindaklanjut))."' ,  
								{$ext_ygmembantu} = '".addslashes(html_entity_decode($ygmembantu))."' ,
								{$ext_clm}
								WHERE id = '{$id}' ";
		// pr($query);	
		// exit;		
		$result = $this->query($query);
	}

	function getBobotRpk($data)
	{
		$sql = "SELECT * FROM thbp_kak_output_bobot WHERE th = '{$data['thn']}' AND kdunitkerja = '{$data['kd_unit']}' AND kdgiat = '{$data['kd_giat']}' AND kdoutput = '{$data['kd_output']}' AND kdsoutput = '{$data['kd_soutput']}' AND kdkmpnen = '{$data['kd_komponen']}'";
		$bobot = $this->fetch($sql);

		return $bobot;
	}
	

	function rencana_anggaran($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen){
		switch ($trwln){
			case 1:
				$ext_sql = "sum(anggaran_1) as rencana_1,sum(anggaran_2) as rencana_2,sum(anggaran_3) as rencana_3"; break;
			case 2:
				$ext_sql = "sum(anggaran_1) as rencana_1,sum(anggaran_2) as rencana_2,sum(anggaran_3) as rencana_3,sum(anggaran_4) as rencana_4,sum(anggaran_5) as rencana_5,sum(anggaran_6) as rencana_6 "; break;
			case 3:
				$ext_sql = "sum(anggaran_1) as rencana_1,sum(anggaran_2) as rencana_2,sum(anggaran_3) as rencana_3,sum(anggaran_4) as rencana_4,sum(anggaran_5) as rencana_5,sum(anggaran_6) as rencana_6,sum(anggaran_7) as rencana_7,sum(anggaran_8) as rencana_8,sum(anggaran_9) as rencana_9 "; break;
			case 4:
				$ext_sql = "sum(anggaran_1) as rencana_1,sum(anggaran_2) as rencana_2,sum(anggaran_3) as rencana_3,sum(anggaran_4) as rencana_4,sum(anggaran_5) as rencana_5,sum(anggaran_6) as rencana_6,sum(anggaran_7) as rencana_7,sum(anggaran_8) as rencana_8,sum(anggaran_9) as rencana_9,sum(anggaran_10) as rencana_10,sum(anggaran_11) as rencana_11,sum(anggaran_12) as rencana_12"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM thbp_kak_output_tahapan  
				WHERE th LIKE '{$thn_temp}' AND kdunitkerja LIKE '{$kdunitkerja}' AND kdgiat = '{$kd_giat}' AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
	function insert_bobot($data)
	{
		foreach ($data as $key => $val) {
            $tmpfield[] = $key;
            $tmpvalue[] = "'$val'";
        }

        $field = implode(',', $tmpfield);
        $value = implode(',', $tmpvalue);

        $query = "INSERT INTO thbp_kak_output_bobot ({$field}) VALUES ($value)";

        $result = $this->query($query);

		return true;
	}

	function update_bobot($data)
	{
		$sql = "UPDATE thbp_kak_output_bobot SET bobot = {$data['bobot']} WHERE id = {$data['id']}";

		$this->query($sql);

		return true;
	}

	function sumBobot($data)
	{
		$sql = "SELECT SUM(target_1+target_2+target_3+target_4+target_5+target_6+target_7+target_8+target_9+target_10+target_11+target_12) as total FROM thbp_kak_output_tahapan WHERE th = '{$data['thn']}' AND kdunitkerja = '{$data['kd_unit']}' AND kdgiat = '{$data['kd_giat']}' AND kdoutput = '{$data['kd_output']}' AND kdsoutput = '{$data['kd_soutput']}' AND kdkmpnen = '{$data['kd_komponen']}'";

		$res = $this->fetch($sql,1);
		
		return $res;
	}
	
	function realisasi_anggaran($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen){
		switch ($trwln){
			case 1:
				$ext_sql = "sum(anggaran_1) as realisasi_1,sum(anggaran_2) as realisasi_2,sum(anggaran_3) as realisasi_3"; break;
			case 2:
				$ext_sql = "sum(anggaran_1) as realisasi_1,sum(anggaran_2) as realisasi_2,sum(anggaran_3) as realisasi_3,sum(anggaran_4) as realisasi_4,sum(anggaran_5) as realisasi_5,sum(anggaran_6) as realisasi_6 "; break;
			case 3:
				$ext_sql = "sum(anggaran_1) as realisasi_1,sum(anggaran_2) as realisasi_2,sum(anggaran_3) as realisasi_3,sum(anggaran_4) as realisasi_4,sum(anggaran_5) as realisasi_5,sum(anggaran_6) as realisasi_6,sum(anggaran_7) as realisasi_7,sum(anggaran_8) as realisasi_8,sum(anggaran_9) as realisasi_9 "; break;
			case 4:
				$ext_sql = "sum(anggaran_1) as realisasi_1,sum(anggaran_2) as realisasi_2,sum(anggaran_3) as realisasi_3,sum(anggaran_4) as realisasi_4,sum(anggaran_5) as realisasi_5,sum(anggaran_6) as realisasi_6,sum(anggaran_7) as realisasi_7,sum(anggaran_8) as realisasi_8,sum(anggaran_9) as realisasi_9,sum(anggaran_10) as realisasi_10,sum(anggaran_11) as realisasi_11,sum(anggaran_12) as realisasi_12"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM monev_bulanan  
				WHERE th LIKE '{$thn_temp}' AND kdunitkerja LIKE '{$kdunitkerja}' AND kdgiat = '{$kd_giat}' AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
	function rencana_bobot($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen){
		switch ($trwln){
			case 1:
				$ext_sql = "sum(target_1) as rencana_1,sum(target_2) as rencana_2,sum(target_3) as rencana_3"; break;
			case 2:
				$ext_sql = "sum(target_1) as rencana_1,sum(target_2) as rencana_2,sum(target_3) as rencana_3,sum(target_4) as rencana_4,sum(target_5) as rencana_5,sum(target_6) as rencana_6 "; break;
			case 3:
				$ext_sql = "sum(target_1) as rencana_1,sum(target_2) as rencana_2,sum(target_3) as rencana_3,sum(target_4) as rencana_4,sum(target_5) as rencana_5,sum(target_6) as rencana_6,sum(target_7) as rencana_7,sum(target_8) as rencana_8,sum(target_9) as rencana_9 "; break;
			case 4:
				$ext_sql = "sum(target_1) as rencana_1,sum(target_2) as rencana_2,sum(target_3) as rencana_3,sum(target_4) as rencana_4,sum(target_5) as rencana_5,sum(target_6) as rencana_6,sum(target_7) as rencana_7,sum(target_8) as rencana_8,sum(target_9) as rencana_9,sum(target_10) as rencana_10,sum(target_11) as rencana_11,sum(target_12) as rencana_12"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM monev_bulanan  
				WHERE th LIKE '{$thn_temp}' AND kdunitkerja LIKE '{$kdunitkerja}' AND kdgiat = '{$kd_giat}' AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
	function realisasi_bobot($thn_temp,$trwln,$kdunitkerja,$kd_giat,$kd_output,$kd_komponen){
		switch ($trwln){
			case 1:
				$ext_sql = "sum(target_1) as realisasi_1,sum(target_2) as realisasi_2,sum(target_3) as realisasi_3"; break;
			case 2:
				$ext_sql = "sum(target_1) as realisasi_1,sum(target_2) as realisasi_2,sum(target_3) as realisasi_3,sum(target_4) as realisasi_4,sum(target_5) as realisasi_5,sum(target_6) as realisasi_6 "; break;
			case 3:
				$ext_sql = "sum(target_1) as realisasi_1,sum(target_2) as realisasi_2,sum(target_3) as realisasi_3,sum(target_4) as realisasi_4,sum(target_5) as realisasi_5,sum(target_6) as realisasi_6,sum(target_7) as realisasi_7,sum(target_8) as realisasi_8,sum(target_9) as realisasi_9 "; break;
			case 4:
				$ext_sql = "sum(target_1) as realisasi_1,sum(target_2) as realisasi_2,sum(target_3) as realisasi_3,sum(target_4) as realisasi_4,sum(target_5) as realisasi_5,sum(target_6) as realisasi_6,sum(target_7) as realisasi_7,sum(target_8) as realisasi_8,sum(target_9) as realisasi_9,sum(target_10) as realisasi_10,sum(target_11) as realisasi_11,sum(target_12) as realisasi_12"; break;
		}
		
		$query = "SELECT {$ext_sql} FROM thbp_kak_output_tahapan  
				WHERE th LIKE '{$thn_temp}' AND kdunitkerja LIKE '{$kdunitkerja}' AND kdgiat = '{$kd_giat}' AND kdoutput = '{$kd_output}' and kdkmpnen = '{$kd_komponen}'";
		// pr($query);
		$result = $this->fetch($query);
		return $result;
	}
	
}
?>