
<html>
    <?php
            include "../../config/config.php";
            include "$path/header.php";
            ?>
<head>
    <body>
        <?php
           
            
            open_connection();
            
            $bup_idaset = $_POST['bup_idaset'];
            $bup_namaaset = $_POST['bup_namaaset'];
            $bup_nokontrak = $_POST['bup_nokontrak'];
            $bup_nousulan = $_POST['bup_nousulan'];
            
            $submit = $_POST ['submit'];

            if ($bup_idaset!=""){
                $query_bup_idaset="a.Aset_ID='".$bup_idaset."' ";
            }
            if($bup_nama_aset!=""){
                $query_bup_namaset ="a.NamaAset LIKE '%".$bup_namaaset."%' ";
            }
            if ($bup_nokontrak!=""){
                $query_bup_nokontrak="a.Aset_ID='".$bup_nokontrak."' ";
            }
            if($bup_nousulan!=""){
                $query_bup_nousulan ="Tahun='".$bup_nousulan."' ";
            }

            $parameter_sql="";
            
            if($bup_nama_aset!=""){
                $parameter_sql=$query_nama_aset;
            }
            if($bup_nama_aset!="" && $parameter_sql!=""){
                $parameter_sql=$parameter_sql." AND ".$query_bup_nama_aset;
            }
            if($bup_nama_aset!="" && $parameter_sql==""){
                $parameter_sql=$query_bup_nama_aset;
            }
            if($bup_nokontrak!="" && $parameter_sql!=""){
                $parameter_sql=$parameter_sql." AND ".$query_bup_nokontrak;
            }
            if ($bup_nokontrak!="" && $parameter_sql==""){
                $parameter_sql=$query_bup_nokontrak;
            }
            if($bup_nousulan!="" && $parameter_sql!=""){
                $parameter_sql=$parameter_sql." AND ".$query_bup_nousulan;
            }
            if ($bup_nousulan!="" && $parameter_sql==""){
                $parameter_sql=$query_bup_nousulan;
            }
            
            //echo 'tes'.$parameter_sql;
            if($parameter_sql!="" ) {
            $parameter_sql="WHERE ".$parameter_sql;
            }
                if (isset($submit))
                    {
                    if($bup_namaaset==""&&$bup_nokontrak==""&&$bup_nousulan==""&&$bup_skpd==""){
                        ?>
                        <script>var r=confirm('Tidak ada isian filter');
                        if (r==false)
                        {
                                document.location="<?php echo "$url_rewrite/module/penghapusan/";?>lanjut_tambah_aset.php";
                        }
                        </script>
            
            <?php
            }
                    }
                    
                    ?>
        <script type="text/javascript">
                       function pilih()
                       {
                           //alert("ada");
                           document.getElementById('checkbox').checked = true;
                           
                           
                       }
                       
                       function kosong()
                       {
                           document.getElementById('checkbox').checked = false;
                       }
                   </script>
        <?php
            if ($_GET['pid']==0)
    {
        echo '<script type=text/javascript> window.location.href="?pid=1";</script>';
    }
    if ($_GET['pid']== 1)
    {
        $paging = ((($_GET['pid'] - 1) * 10));
    }else
    {
        $paging = ((($_GET['pid'] - 1) * 10) + 1);
    }
    
    
        $query1="SELECT * FROM Aset $parameter_sql limit 10";
        //print_r($query1);
        
        $query2 = "SELECT * FROM KontrakAset ka
           INNER JOIN Aset aset ON aset.Aset_ID = ka.Aset_ID
           INNER JOIN Kontrak kontrak ON kontrak.Kontrak_ID = ka.Kontrak_ID";
        
        $query = "SELECT a.Aset_ID, a.NamaAset,a.Kelompok_ID, a.LastSatker_ID,
                            a.Lokasi_ID, a.LastKondisi_ID, c.Kelompok, 
                            d.NamaLokasi, e.KodeSatker, e.NamaSatker, f.InfoKondisi
                            FROM Aset AS a 
                            LEFT JOIN Kelompok AS c ON a.Kelompok_ID = c.Kelompok_ID
                            LEFT JOIN Lokasi AS d ON a.Lokasi_ID = d.Lokasi_ID 
                            LEFT JOIN Satker AS e ON a.LastSatker_ID = e.Satker_ID
                            LEFT JOIN Kondisi AS f ON a.LastKondisi_ID = f.Kondisi_ID
                            
                            $parameter_sql LIMIT $paging, 10";
        //print_r($query);
        $result = mysql_query($query) or die(mysql_error());
        //$result1 = mysql_query($query1) or die(mysql_error());
        $check = mysql_num_rows($result);
        //$check1 = mysql_num_rows($result1);
        
        //echo $check.'<br>';
        //echo $check1;
        
        //query untuk ambil data apl_userasetlist
        $query_apl = "SELECT aset_list FROM apl_userasetlist WHERE UserNm LIKE 'admin'";
        $result_apl = mysql_query($query_apl) or die (msyql_error());
        $data_apl = mysql_fetch_object($result_apl);
        
        $explode = explode(',',$data_apl->aset_list);
        //print_r($explode);
        // end apl_userasetlist
        $i=1;
        while ($data = mysql_fetch_object($result))
        {
            $dataArr[] = $data;
        }
        
        ?>


<link href="./datepickercontrol.css" type="text/css" rel="stylesheet">
<link href="../../css/style.css" type="text/css" rel="stylesheet">
<link href="../../modules/transfer/transfer.css" type="text/css" rel="stylesheet">
<style>
  th {
    background-color: #eeeeee;
    border: 1px solid #dddddd;
  }
  td {
    border: 1px solid #dddddd;
  }
</style>
<div style="margin: 5px; padding: 5px 5px 2px 5px; border: 1px solid #9999cc;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <th colspan="2" align="left">Filter data : <u>Tidak ada filter (View seluruh data)</u></th>

        </tr>
  </table>
</div>

<br>

<div>
<!-- Buttons: back to main page (Cari Aset)  or print preview -->
  <div align="right">
    <input type="button"
           name="aset_act_btn"
           value="Kembali ke halaman utama: Cari Aset"
           onclick="document.location='tambah_aset.php';">
            <input type="button"
               value="Cetak daftar aset (PDF)"
               onclick="window.open('doc.pdf');"
               title="Preview seluruh daftar dalam PDF">
          </div>

<!-- Result page here ... -->
Lama waktu proses: 0.0087 detik <br><form name="asetdatalist" action="menuid=40&exec=yesact=movsimbada" method="POST">
  
<div style="margin: 2px; margin-bottom:0px; margin-top:5px;">
  <table width="100%" cellpadding="0" cellspacing="0"
         class="paging">
    <tr>
      <td style="border: 0px; border-bottom: 1px solid #ccccee;"
          align="left" width="100%">&nbsp;Total&nbsp;:&nbsp;1&nbsp;halaman&nbsp;</td>

    </tr>
  </table>
</div>
<div style="padding: 5px; margin: 0px 2px 0px 2px;
            border-width: 0px 1px 0px 1px;
            border-style: solid;
            border-color: #ccccee;">
<div id="idmsg">&nbsp;</div>
<div id="iduserasetchoosed">
<!--
Jumlah aset tersimpan dalam daftar anda :&nbsp;
-->
</div>
<table width="100%">
  <tr>
    <td align="left">
      Pilihan&nbsp;:&nbsp;

      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Pilih semua..."
            onclick="CheckAll(); ModifyChart( 'movsimbada' );">Semua</span>
      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Kosongkan pilihan..."
            onclick="ClearAll(); ModifyChart( 'movsimbada' );">Nihil</span>
      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Bersihkan memory dari daftar aset yang pernah anda pilih"
            onclick="ClearChart( 'movsimbada' );">Bersihkan memory dari daftar aset</span>
    </td>
    <td align="right">
      <input type="submit" name="submit" id="btn_action_movsimbada" value="Lanjutkan"  onclick="closePopup('penetapan_penghapusan_tambah.php?act=show');" />
     
    </td>
      <tr>
      <td colspan ="2" align="right">
            <input type="button" value="Prev" <?php echo $disabled?> onclick="window.location.href='?pid=<?php echo $_GET[pid] - 1; ?>'">
            <input type="button" value="Next" <?php echo $disabled?> onclick="window.location.href='?pid=<?php echo $_GET[pid] + 1; ?>'">
        </td>
          </tr>

    
  </tr>
</table>
<table class="listdata" style="margin-top:2px;" width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <th class="listdata" align="center" style="background-color:#004933; color:white;">&nbsp;</th>
    <th class="listdata" align="left"   colspan="2" style="background-color:#004933; color:white;">&nbsp;Informasi&nbsp;Aset&nbsp;</th>
    <!--
    <th class="listdata" align="center">&nbsp;Tindakan&nbsp;</th>
    -->
      </tr>
     <?php 
    
    if ($_GET['pid'] == 1) $no = 1; else $no = $paging;
    if (!empty($dataArr))
    {
        $disabled = '';
    $pid = 0;
    foreach ($dataArr as $key => $value)
    {
        //echo $value->Aset_ID;
      
    
    ?>
        <tr>

        <td class="listdata_type_00" valign="top" align="right"><?php echo $no?>&nbsp;</td>
        <td class="listdata_type_00" valign="top" align="center">
            <input type="checkbox" name="tambahpenghapusan" class="checkbox" value="<?php echo $value->Aset_ID?>"  <?php for ($i = 0; $i <= count($explode); $i++){if ($explode[$i]==$value->Aset_ID) echo 'checked';}?>>
        </td>
        <td class="listdata_type_00" valign="top" align="left">
          <div style="padding:5px;">
            <b><span style="padding:1px 5px 1px 5px; background-color:#eeeeee; border: 1px solid #cccccc;"><?php echo $value->Aset_ID?></span></b>
              ( Aset ID - System Number )<br>
            <b><?php echo $value->NoKontrak?></b><br>
            <b><?php echo $value->NamaAset?></b>
              </div>
          <!--
          <div style="float:right"><input type="button" value="more detail"></div>
          -->
          <hr size="1px">
            <div id="idv_asetid[0]" style="width:100%; margin:0px; padding:0px;">
              <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td rowspan="5" width="50px" valign="top" align="center"
                        style="padding: 2px; border:0px;">
                   <img src="../../lib/loadpict.php?sz=1&id=107"
                        style="padding: 2px; border: 1px solid #ccc;"
                        border="0" width="85%">

                     </td></tr>
                                <tr>
                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;" width="100px">No.Kontrak</td>
                  <td valign="top" align="left" style="border-width:0px 0px 1px 0px; padding:0px 2px 0px 3px;"><b><?php echo $value->Kontrak_ID?></b></td>
                    </tr>
                <tr>
                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;" width="100px">Satker</td>

                  <td valign="top" align="left" style="border-width:0px 0px 1px 0px; padding:0px 2px 0px 3px;"><?php echo '['.$value->KodeSatker.'] '.$value->NamaSatker?></td>
                    </tr>
                <tr>
                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;">Lokasi</td>
                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;"><?php echo $value->NamaLokasi?></td>
                    </tr>
                <tr>

                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;">Status</td>
                  <td valign="top" align="left" style="padding:0px 2px 0px 3px;"><?php echo $value->Kondisi_ID. '-' .$value->InfoKondisi?></td>
                    </tr>
              </table>
            </div>
          </td>
        <!--
        <td class="listdata_type_00" valign="top" align="center"><a href="?menuid=40&m=&id=48773&exec=movsimbada" class="detail_link"></a></td>
        -->
          </tr>  
    <tr>

    <th class="listdata" align="center" style="background-color:#004933; color:white;">&nbsp;</th>
    <th class="listdata" align="left"   colspan="2" style="background-color:#004933; color:white;">&nbsp;Informasi&nbsp;Aset&nbsp;</th>
    <!--
    <th class="listdata" align="center">&nbsp;Tindakan&nbsp;</th>
    -->
      </tr>
          <?php
        $no++;
        $pid++;
    }
    }
    else
    {
        $disabled = 'disabled';
        echo '<tr><td align=center colspan=5>Tidak ada data</td></tr>';
    }
    ?> 
  </table>
<table width="100%">
  <tr>
    <td align="left">

      Pilihan&nbsp;:&nbsp;
      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Pilih semua..."
            onclick="CheckAll(); ModifyChart( 'movsimbada' );">Semua</span>
      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Kosongkan pilihan..."
            onclick="ClearAll(); ModifyChart( 'movsimbada' );">Nihil</span>
      <span class="listdata"
            style="cursor:pointer;text-decoration:underline;"
            title="Bersihkan memory dari daftar aset yang pernah anda pilih"
            onclick="ClearChart( 'movsimbada' );">Bersihkan memory dari daftar aset</span>
    </td>
      <tr>
          <td colspan ="2" align="right">
            <input type="button" value="Prev" <?php echo $disabled?> onclick="window.location.href='?pid=<?php echo $_GET[pid] - 1; ?>'">
            <input type="button" value="Next" <?php echo $disabled?> onclick="window.location.href='?pid=<?php echo $_GET[pid] + 1; ?>'">
        </td>
          </tr>
    <td align="right">

      <input type="button" name="btn_action_movsimbada" id="btn_action_movsimbada" value="Lanjutkan"  onclick="closePopup('penetapan_penghapusan_tambah.php?act=show');">
     
    </td>
    
  </tr>
</table>
</div>
</div>
<div style="margin: 2px; margin-top:0px; margin-bottom:5px;">
  <table width="100%" cellpadding="0" cellspacing="0"
         class="paging">
    <tr>
      <td style="border: 0px; border-top: 1px solid #ccccee;"
          align="left" width="100%">&nbsp;Total&nbsp;:&nbsp;1&nbsp;halaman&nbsp;</td>

    </tr>
  </table>
</div>
</form>
     <script>
          
                
         function closePopup(linkrefferer)
        {
        /*  window.opener.document.location.reload(); */
        window.opener.document.location=linkrefferer;
        self.close ()
        }
                   $('.checkbox').click(function()
                   {
                      
                        var check = $(this).attr('checked');
                        var modParam = $(this).attr('name');
                        var value = $(this).val();
                        //alert(check);
                        if (check == 'checked')
                            {
                                $.post('<?php echo $url_rewrite;?>/function/phpajax/ajax.php', {aid:value, parameter:'add', mod:modParam}, function(data){
                                //$('#check').html('');
                                alert(data);
                                })

                            }
                            else
                                {
                                    $.post('<?php echo $url_rewrite;?>/function/phpajax/ajax.php', {aid:value, parameter:'del', mod:modParam}, function(data){
                                //$('#check').html('');
                                //alert(data);
                                })
                                }
                        
                   });
                   
               </script>
    
    </body>
    </head>
</html>
