<?php
include "../../config/config.php";

        $no_penetapan=$_POST['peman_valid_filt_nopenet'];
        $tgl_penetapan=$_POST['peman_valid_filt_tglpenet'];
        $tgl_fix=format_tanggal_db2($tgl_penetapan);
        $tipe_pemanfaatan=$_POST['peman_valid_filt_tipe'];
        $alasan=$_POST['peman_valid_filt_alasan'];
        $submit=$_POST['tampil_valid_filter'];
        
        //open_connection();
        
        if ($no_penetapan!=""){
            $query_no_penetapan="NoSKKDH LIKE '%$no_penetapan%'";
            }
            if($tgl_penetapan!=""){
            $query_tgl_penetapan="TglSKKDH LIKE '%$tgl_fix%'";
            }
            if($tipe_pemanfaatan!=""){
            $query_tipe_pemanfaatan="TipePemanfaatan LIKE '%$tipe_pemanfaatan%'";
            }
            if($alasan!=""){
            $query_alasan="Keterangan LIKE '%$alasan%'";
            }

            $parameter_sql="";
            if($no_penetapan!=""){
            $parameter_sql=$query_no_penetapan;
            }
            if($tgl_penetapan!="" && $parameter_sql!=""){
            $parameter_sql=$parameter_sql." AND ".$query_tgl_penetapan;
            }
            if($tgl_penetapan!="" && $parameter_sql==""){
            $parameter_sql=$query_tgl_penetapan;
            }
            if($tipe_pemanfaatan!="" && $parameter_sql!=""){
            $parameter_sql=$parameter_sql." AND ".$query_tipe_pemanfaatan;
            }
            if($tgl_penetapan!="" && $parameter_sql==""){
            $parameter_sql=$query_tipe_pemanfaatan;
            }
            if($alasan!="" && $parameter_sql!=""){
            $parameter_sql=$parameter_sql." AND ".$query_alasan;
            }
            if ($alasan!="" && $parameter_sql==""){
            $parameter_sql=$query_alasan;
            }
            
            if($parameter_sql!="" ) {
		$parameter_sql="WHERE ".$parameter_sql." AND ";
               }
               else
               {
                   $parameter_sql = " WHERE ";
               }
            
            // echo "$parameter_sql";
            
            $_SESSION['parameter_sql'] = $parameter_sql;
            
            
            $query_apl = "SELECT aset_list FROM apl_userasetlist WHERE aset_action = 'ValidasiPemanfaatan' AND UserSes = '$_SESSION[ses_uid]'";
            //print_r($query_apl);
            $result_apl = $DBVAR->query($query_apl) or die ($DBVAR->error());
            $data_apl = $DBVAR->fetch_object($result_apl);

            $array = explode(',',$data_apl->aset_list);

            foreach ($array as $id)
            {
                if ($id !='')
                {
                    $dataAsetList[] = $id;
                }
            }
            if($dataAsetList!=''){
            $explode = array_unique($dataAsetList);
            }
                                                
        
        if (isset($submit)){
                if ($no_penetapan=="" && $tgl_penetapan=="" && $tipe_pemanfaatan=="" && $alasan==""){
    ?>
                <script>var r=confirm('Tidak ada isian filter');
                            if (r==false){
                                document.location="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_filter.php?pid";
                            }
                    </script>
    <?php
            }
        }
    ?>
<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	
			?>
		<script type="text/javascript">
		function show_confirm()
		{
		var r=confirm("Validasi data ?");
		if (r==true)
		  {
		  alert("Data sudah tervalidasi");
		  document.location="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_filter.php";
		  }
		else
		  {
		  alert("You pressed Cancel!");
		  document.location="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_daftar.php";
		  }
		}
	</script>
	 <script language="Javascript" type="text/javascript">  
			function enable(){  
			var tes=document.getElementsByTagName('*');
			var button=document.getElementById('submit');
			var boxeschecked=0;
			for(k=0;k<tes.length;k++)
			{
				if(tes[k].className=='checkbox')
					{
						//
						tes[k].checked == true  ? boxeschecked++: null;
					}
			}
				//alert(boxeschecked);
				if(boxeschecked!=0)
					button.disabled=false;
				else
					button.disabled=true;
				}
				function disable_submit(){
					var enable = document.getElementById('pilihHalamanIni');
					var disable = document.getElementById('kosongkanHalamanIni');
					var button=document.getElementById('submit');
					if (disable){
						button.disabled=true;
					} 
				}
				function enable_submit(){
					var enable = document.getElementById('pilihHalamanIni');
					var disable = document.getElementById('kosongkanHalamanIni');
					var button=document.getElementById('submit');
					if (enable){
						button.disabled=false;
					} 
				}
			</script>

          <section id="main">
			<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Pemanfaatan</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Validasi Pemanfaatan</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Validasi Pemanfaatan</div>
				<div class="subtitle">Daftar Data</div>
			</div>	
		<section class="formLegend">
			
			<div class="detailLeft">
					<span class="label label-success">Filter data: Tidak ada filter (View seluruh data)</span>
			</div>
		
			<div class="detailRight" align="right">
							<?php 
								$query="SELECT Pemanfaatan_ID FROM Pemanfaatan WHERE FixPemanfaatan=1 AND Status=0 ORDER BY Aset_ID ASC ";
                                        $result = $DBVAR->query($query) or die($DBVAR->error());
                                        
										while ($data = $DBVAR->fetch_object($result))
                                        {
                                            $dataArray[] = $data;
                                        }
										if($dataArray!=""){
                                            foreach ($dataArray as $pemanfaatan_id)
                                                {
													if($_SESSION['ses_uaksesadmin'] == 1){
														$query2="SELECT * FROM Pemanfaatan
                                                                    WHERE  Pemanfaatan_ID = $pemanfaatan_id->Pemanfaatan_ID
                                                                    ORDER BY Pemanfaatan_ID asc ";
                                                    }else{
														$query2="SELECT * FROM Pemanfaatan
                                                                    WHERE  Pemanfaatan_ID = $pemanfaatan_id->Pemanfaatan_ID
                                                                    AND UserNm = '$_SESSION[ses_uoperatorid]' ORDER BY Pemanfaatan_ID asc ";
													}
													$exec=$DBVAR->query($query2) or die(mysql_error());
                                                    $row[] = $DBVAR->fetch_object($exec);       
                                                }
                                            }
                                        $rows = $DBVAR->num_rows($exec);
										$query_apl = "SELECT aset_list FROM apl_userasetlist WHERE aset_action = 'ValidasiPemanfaatan[]'";
										$result_apl = $DBVAR->query($query_apl) or die ($DBVAR->error());
										$data_apl = $DBVAR->fetch_object($result_apl);
										$array = explode(',',$data_apl->aset_list);
											
										foreach ($array as $id)
										{
											if ($id !='')
											{
											$dataAsetList[] = $id;
											}
										}
										
										if ($dataAsetList !='')
										{
											$explode = array_unique($dataAsetList);
										}
								?>
						<ul>
							<li>
								<a href="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_filter.php" class="btn">
								Kembali ke Halaman Utama: Cari Aset</a>
							</li>
							<li>
								<a href="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_daftar_valid.php?pid=1" class="btn">
									Daftar Barang
								 </a>
							</li>
							<li>
								<input type="hidden" class="hiddenpid" value="<?php echo @$_GET['pid']?>">
								<input type="hidden" class="hiddenrecord" value="<?php echo @$rows?>">
								   <ul class="pager">
										<li><a href="#" class="buttonprev" >Previous</a></li>
										<li>Page</li>
										<li><a href="#" class="buttonnext">Next</a></li>
									</ul>
							</li>
						</ul>
							
					</div>
			<div style="height:5px;width:100%;clear:both"></div>
			
			
			<div id="demo">
			 <form name="form" method="POST" action="<?php echo "$url_rewrite/module/pemanfaatan/"; ?>pemanfaatan_validasi_proses.php?pid=1">
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						  <td width="130px"><span><a href="#" onclick="enable_submit()" id="pilihHalamanIni"><u>Pilih halaman ini</u></a></span></td>
						<td  align=left><a href="#" onclick="disable_submit()" id="kosongkanHalamanIni" ><u>Kosongkan halaman ini</u></a></td>
						<td align=right>
							<input type="submit" name="submit" value="Validasi Barang" id="submit" disabled/>
						</td>
					</tr>
					<tr>
						<th>No</th>
						<th>Pilihan</th>
						<th>Nomor SKKDH</th>
						<th>Tanggal SKKDH</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>		
					<?php
					if($row!=""){
						$page = @$_GET['pid'];
					if ($page > 1){
						$no = intval($page - 1 .'01');
					}else{
						$no = 1;
					}	  
					// pr($row);	
				   foreach ($row as $value){
						$id = $value->Pemanfaatan_ID;
						?>
						  
					<tr class="gradeA">
						<td><?php echo "$no";?></td>
						<td>
							<input type="checkbox" class="checkbox" onchange="enable()" name="ValidasiPemanfaatan[]" value="<?php echo $id?>" 
							<?php for ($j = 0; $j <= count($explode); $j++){if ($explode[$j]==$id) echo 'checked';}?>/>
						</td>
						<td><?php echo "$value->NoSKKDH";?></td>
						<td><?php $change=$value->TglSKKDH; $change2=  format_tanggal_db3($change); echo "$change2";?></td>
						<td>	
						<?php echo "$value->Keterangan";?>
						</td>
					</tr>
					  <?php 
						$no++; }}
								else
						{
							$disabled = 'disabled';
						}
								
								 ?>
				</tbody>
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</tfoot>
			</table>
				</form>
			</div>
			<div class="spacer"></div>
			
			
		</section> 
	</section>
<?php
include "$path/footer.php";
?>