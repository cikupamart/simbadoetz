<?php
    include "../../config/config.php";
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$PENGHAPUSAN = new RETRIEVE_PENGHAPUSAN;
    
$menu_id = 74;
($SessionUser['ses_uid']!='') ? $Session = $SessionUser : $Session = $SESSION->get_session(array('title'=>'GuestMenu', 'ses_name'=>'menu_without_login')); 
$SessionUser = $SESSION->get_session_user();
$USERAUTH->FrontEnd_check_akses_menu($menu_id, $SessionUser);

if($_POST['cekAll'] == 1){
    $id = $_POST['usulanID'];
    $log = "hapus_aset_usulan_pmd_".$id;

    $status=exec("php hapus_aset_usulan_pmd_helper_all.php $id > ../../log/$log.txt 2>&1 &");
    echo "<meta http-equiv=\"Refresh\" content=\"0; url={$url_rewrite}/module/penghapusanv2/dftr_review_edit_aset_usulan_pmd.php?id=$id\">";    
    exit;
}else{
    $id = $_POST['usulanID'];
    $log = "hapus_aset_usulan_pmd_".$id;

    $apl_userasetlistHPS = $PENGHAPUSAN->apl_userasetlistHPS("DELUSPMD");
    $data = $apl_userasetlistHPS['0']['aset_list'];
    //pr($data);
    //exit;
    $data_delete=$PENGHAPUSAN->apl_userasetlistHPS_del("DELUSPMD");
    

    $status=exec("php hapus_aset_usulan_pmd_helper.php $id $data > ../../log/$log.txt 2>&1 &");
    echo "<meta http-equiv=\"Refresh\" content=\"0; url={$url_rewrite}/module/penghapusanv2/dftr_review_edit_aset_usulan_pmd.php?id=$id\">";    
    exit;
}
    
?>
