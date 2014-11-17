<?php
include "../../config/config.php";

$PERENCANAAN = new RETRIEVE_PERENCANAAN;

$menu_id = 2;
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id,$SessionUser);

	$paging = $LOAD_DATA->paging($_GET['pid']);
	//echo '<pre>';
	//print_r($_SESSION);
	    if (isset($_POST['submit']))
	    {
		//echo 'ada';
			//$_SESSION['ses_retrieve_filter_'.$parameter['menuID'].'_'.$this->UserSes['ses_uid']]
		unset($_SESSION['ses_retrieve_filter_'.$parameter['menuID'].'_'.$SessionUser->UserSes['ses_uid']]);
						
		// parameter yang dimasukan adalah menuID, type, post dan paging
		// $get_data_filter = $RETRIEVE->retrieve_harga_barang_filter(array('param'=>$_POST, 'menuID'=>$menu_id, 'type'=>'', 'paging'=>$paging));
		
	    }else
		{
	    $sess = $_SESSION['ses_retrieve_filter_'.$parameter['menuID'].'_'.$SessionUser->UserSes['ses_uid']];
		// $get_data_filter = $RETRIEVE->retrieve_harga_barang_filter(array('param'=>$sess, 'menuID'=>$menu_id, 'type'=>'', 'paging'=>$paging));
	    }
	//echo '<pre>';	    
	//print_r($get_data_filter);
	//echo '</pre>';
?>
<?php
	include"$path/meta.php";
	include"$path/header.php";
	include"$path/menu.php";
	

	$_POST['menuID'] = 2;
	$get_data_filter = $PERENCANAAN->retrieve_harga_barang_filter($_POST);

			?>


          <section id="main">
			<ul class="breadcrumb">
			  <li><a href="#"><i class="fa fa-home fa-2x"></i>  Home</a> <span class="divider"><b>&raquo;</b></span></li>
			  <li><a href="#">Perencanaan</a><span class="divider"><b>&raquo;</b></span></li>
			  <li class="active">Buat Standar Harga Barang</li>
			  <?php SignInOut();?>
			</ul>
			<div class="breadcrumb">
				<div class="title">Buat Standar Harga Barang</div>
				<div class="subtitle">Daftar Data </div>
			</div>	
		<section class="formLegend">
			
			<div class="detailLeft">
					<span class="label label-success">Filter data: Tidak ada filter (View seluruh data)</span>
			</div>
		
			<div class="detailRight" align="right">
						
						<ul>
							<li>
								<a href="<?php echo "$url_rewrite/module/perencanaan/"; ?>shb_import_data.php" class="btn">
								Tambah Data: Import</a>
								<a href="<?php echo "$url_rewrite/module/perencanaan/"; ?>shb_tambah_data.php" class="btn">
								Tambah Data: Manual</a>
							</li>
							<li>
								<a href="<?php echo "$url_rewrite/module/perencanaan/"; ?>shb_filter.php" class="btn">
									   Kembali ke halaman utama : Form Filter
								 </a>
							</li>
							<li>
								<input type="hidden" class="hiddenpid" value="<?php echo @$_GET['pid']?>">
								<input type="hidden" class="hiddenrecord" value="<?php echo @$_SESSION['parameter_sql_total']?>">
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
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
				<thead>
					<tr>
						<th>No</th>
						<th>Keterangan Jenis/Nama Barang</th>
						<th>Harga</th>
						<th>Tindakan</th>
					</tr>
				</thead>
				<tbody>		
							 
				<?php
					if ($_GET['pid'] == 1) $no = 1; else $no = $paging;
						if (!empty($get_data_filter))
						{
							$disabled = '';
						//$no = 1;
						$pid = 0;
						$check=0;
						
						foreach ($get_data_filter as $key => $value)

					//while($hsl_data=mysql_fetch_array($exec))
						{
				?>
						  
					<tr class="gradeA">
						<td><?php echo $no;?></td>
						<td>
							 <table align="center" border="0" width="100%">
								<tr>
									<td width="20%">Nama/Jenis Barang</td>
									<td><?php echo show_kelompok($value['Kelompok_ID']);?></td>
									<?php
									
									?>
								</tr>
								<tr>
									<td>Merk/Tipe</td>
									<td><?php echo $value['Merk'];?></td>
								</tr>
								<tr>
									<td width="20%">Tanggal</td>
									<td>
									<?php 
									$tanggal=explode("-",$value['TglUpdate']);
									$tgl=$tanggal[2]."/".$tanggal[1]."/".$tanggal[0];
									echo $tgl;
									?>		
									</td>
								</tr>
								<tr>
									<td>Spesifikasi</td>
									<td><?php echo $value['Spesifikasi'];?></td>
								</tr>
								<tr>
									<td>Satuan</td>
									<td><?php echo $value['Satuan'];?></td>
								</tr>
								<tr>
									<td>Keterangan</td>
									<td><?php echo $value['Keterangan'];?></td>
								</tr>
							</table>
						</td>
						<td><?php echo  number_format($value['NilaiStandar'],2,',','.')?></td>
						<td>	
							<form method="POST" action="shb_edit_data.php" onsubmit="return confirm('Apakah data nama/jenis barang = <?php echo show_kelompok($value->Kelompok_ID);?> ini ingin diedit?');">
								<input type="hidden" name="ID" value="<?php echo $value['StandarHarga_ID'];?>" id="ID_<?php echo $i?>">
								<input type="submit" value="Edit" class="btn btn-success" name="edit"/>
							</form>
							<form method="POST" action="shb-proses.php" onsubmit="return confirm('Apakah data nama/jenis barang = <?php echo show_kelompok($value->Kelompok_ID);?> ini ingin dihapus?');">
								<input type="hidden" name="ID" value="<?php echo $value['StandarHarga_ID'];?>" id="ID_<?php echo $i?>">
								<input type="submit" value="Hapus" class="btn btn-danger" name="submit_hapus"/>
							</form>
						</td>
					</tr>
					
				     <?php
						$no++;
						$pid++;
					 }
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
			</div>
			<div class="spacer"></div>
			
			
		</section> 
	</section>
<?php
include "$path/footer.php";
?>