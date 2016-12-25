<?php
include "../../config/config.php";

$PENGHAPUSAN = new RETRIEVE_PENGHAPUSAN;

$menu_id = 74;
($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);
//exit();
//pr($_POST);
if($_POST['cekAll'] == 1){
	/*start background process
	ket : param
	id = id usulan penghapusan pmd
	filter :
	[bup_tahun],[kodepemilik],[kodeKelompok],[jenisaset],[kodeSatker],
	*/
	$id = $_POST['usulanID'];
	$log = "usulan_aset_pmd_".$id;
	$tahun = $_POST['bup_tahun'];
	$kodePemilik = $_POST['kodepemilik'];
	$kodeKelompok = $_POST['kodeKelompok'];
	$jenisaset = $_POST['jenisaset'];
	$kodeSatker = $_POST['kodeSatker'];
	
	if($kodeSatker) $filterkontrak .="kodeSatker={$kodeSatker}"."-";
	if($kodePemilik) $filterkontrak .="kodeLokasi={$kodePemilik}"."-";
	if($tahun) $filterkontrak .="Tahun={$tahun}"."-";
	if($kodeKelompok) $filterkontrak .="kodeKelompok={$kodeKelompok}"."-";
	if($jenisaset) $filterkontrak .="TipeAset={$jenisaset}";

$status=exec("php usulan_aset_pmd_helper_all.php $id $filterkontrak > ../../log/$log.txt 2>&1 &");
echo "<meta http-equiv=\"Refresh\" content=\"0; url={$url_rewrite}/module/penghapusanv2/dftr_usulan_pmd.php\">";    
    exit;
}else{	
	/*start background process
	ket : param
	id = id usulan penghapusan pmd
	data = list aset  
	*/
	$id = $_POST['usulanID'];
	$log = "usulan_aset_pmd_".$id;

	$apl_userasetlistHPS = $PENGHAPUSAN->apl_userasetlistHPS("RVWUSPMD");
	$data = $apl_userasetlistHPS['0']['aset_list'];

	$data_delete=$PENGHAPUSAN->apl_userasetlistHPS_del("RVWUSPMD");
	//exit;

$status=exec("php usulan_aset_pmd_helper.php $id $data > ../../log/$log.txt 2>&1 &");
echo "<meta http-equiv=\"Refresh\" content=\"0; url={$url_rewrite}/module/penghapusanv2/dftr_usulan_pmd.php\">";    
    exit;
}

    
?>
