<?php
  // ini_set("error_reporting", 0);
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=report-produksi-With-Whitness" . substr($_GET['awal'], 0, 10) . ".xls"); //ganti nama sesuai keperluan
  header("Pragma: no-cache");
  header("Expires: 0");
  //disini script laporan anda
?>
<?php
  // ini_set("error_reporting", 1);
  include "../../koneksi.php";
  include "../../koneksiLAB.php";
  include "../../tgl_indo.php";
  //--
  // $idkk = $_REQUEST['idkk'];
  // $act = $_GET['g'];
  //-
  $qTgl = mysqli_query($con, "SELECT DATE_FORMAT(now(),'%Y-%m-%d') as tgl_skrg, DATE_FORMAT(now(),'%Y-%m-%d')+ INTERVAL 1 DAY as tgl_besok");
  $rTgl = mysqli_fetch_array($qTgl);
  $Awal = $_GET['awal'];
  $Akhir = $_GET['akhir'];
  if ($Awal == $Akhir) {
    $TglPAl = substr($Awal, 0, 10);
    $TglPAr = substr($Akhir, 0, 10);
  } else {
    $TglPAl = $Awal;
    $TglPAr = $Akhir;
  }
  $shft = $_GET['shft'];
?>

<body>
  <strong>Periode: <?php echo $TglPAl; ?> s/d <?php echo $TglPAr; ?></strong><br>
  <strong>Shift: <?php echo $shft; ?></strong><br />
  <table width="100%" border="1">
    <tr>
      <th bgcolor="#99FF99">NO.</th>
      <th bgcolor="#99FF99">PRODUCTION ORDER</th>
      <th bgcolor="#99FF99">PRODUCTION DEMAND</th>
      <th bgcolor="#99FF99">NO ITEM</th>
      <th bgcolor="#99FF99">JENIS KAIN</th>
      <th bgcolor="#99FF99">WARNA</th>
      <th bgcolor="#99FF99">TGL IN</th>
      <th bgcolor="#99FF99">NO MESIN</th>
      <th bgcolor="#99FF99">PRD. RSV. LINK GROUP CODE</th>
      <th bgcolor="#99FF99">WHITENESS</th>
      <th bgcolor="#99FF99">YELLOWNESS</th>
      <th bgcolor="#99FF99">TINT</th>
      <th bgcolor="#99FF99">LR</th>
      <th bgcolor="#99FF99">SUFFIX</th>
      <th bgcolor="#99FF99">DESCRIPTION</th>
    <?php
        // ini_set("error_reporting", 0);
        $Awal = $_GET['awal'];
        $Akhir = $_GET['akhir'];
        $Tgl = substr($Awal, 0, 10);
        if ($Awal != $Akhir) {
          $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$Awal' AND '$Akhir' ";
        } else {
          $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d')='$Tgl' ";
        }
        if ($_GET['shft'] == "ALL") {
          $shft = " ";
        } else {
          $shft = " if(ISNULL(a.g_shift),c.g_shift,a.g_shift)='$_GET[shft]' AND ";
        }
        // if($_GET['rcode']){
          // $left_right = 'RIGHT';
          // $where_new = "a.rcode LIKE '%$_GET[rcode]%'";
        // }else{
          $left_right = 'LEFT';
          $where_new = $shft.' '.$Where;
        // }
        $sql = mysqli_query($con, "SELECT 
                                        x.*
                                    FROM tbl_mesin a
                                    $left_right JOIN (
                                          SELECT
                                            b.no_mesin,
                                            a.nokk
                                          FROM
                                              tbl_schedule b
                                              LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                                              LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                                          WHERE
                                              $where_new)x ON (a.no_mesin=x.no_mesin or a.no_mc_lama = x.no_mesin) 
                                          WHERE 
                                            NOT x.nokk IS NULL
                                          ORDER BY a.no_mesin");
        while ($rowd = mysqli_fetch_array($sql)) {
            $r_prodorder[]      = "'".$rowd['nokk']."'";
        }
        $value_prod_order       = implode(',', $r_prodorder);

        $q_whiteness  = db2_exec($conn2, "SELECT DISTINCT 
                                                p.PRODUCTIONORDERCODE,
                                                p.GROUPLINE,
                                                p.GROUPSTEPNUMBER,
                                                p.PRODRESERVATIONLINKGROUPCODE,
                                                w.WHITENESS,
                                                y.YELLOWNESS,
                                                t.TINT,
                                                TRIM(p.SUBCODE01) || '-' ||TRIM(p.SUFFIXCODE) AS SUFFIX,
                                                p.PICKUPQUANTITY AS LR,
                                                r.SEARCHDESCRIPTION AS DESKRIPSI
                                              FROM 
                                                PRODUCTIONRESERVATION p 
                                              LEFT JOIN 
                                                (SELECT
                                                  a.VALUEINT,
                                                  v.PRODUCTIONORDERCODE,
                                                  v.CHARACTERISTICCODE,
                                                  v.VALUEQUANTITY AS WHITENESS
                                                FROM
                                                  VIEWQUALITYDOCHEADERLINE v 
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = v.ABSUNIQUEID ) w ON w.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND w.CHARACTERISTICCODE = 'WHITENESS' AND w.VALUEINT = p.GROUPSTEPNUMBER 
                                              LEFT JOIN 
                                                (SELECT
                                                  a.VALUEINT,
                                                  v.PRODUCTIONORDERCODE,
                                                  v.CHARACTERISTICCODE,
                                                  v.VALUEQUANTITY AS YELLOWNESS
                                                FROM
                                                  VIEWQUALITYDOCHEADERLINE v 
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = v.ABSUNIQUEID ) y ON y.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND y.CHARACTERISTICCODE = 'YELLOWNESS' AND y.VALUEINT = p.GROUPSTEPNUMBER 
                                              LEFT JOIN 
                                                (SELECT
                                                  a.VALUEINT,
                                                  v.PRODUCTIONORDERCODE,
                                                  v.CHARACTERISTICCODE,
                                                  v.VALUEQUANTITY AS TINT
                                                FROM
                                                  VIEWQUALITYDOCHEADERLINE v 
                                                LEFT JOIN ADSTORAGE a ON a.UNIQUEID = v.ABSUNIQUEID ) t ON t.PRODUCTIONORDERCODE = p.PRODUCTIONORDERCODE AND t.CHARACTERISTICCODE = 'TINT' AND t.VALUEINT = p.GROUPSTEPNUMBER 
                                              LEFT JOIN RECIPE r ON r.ITEMTYPECODE = p.ITEMTYPEAFICODE AND
                                                r.SUBCODE01 = p.SUBCODE01 AND
                                                r.SUFFIXCODE = p.SUFFIXCODE
                                              WHERE 
                                                (SUBSTR(p.SUBCODE01, 1, 2) = 'SC' OR 
                                                SUBSTR(p.SUBCODE01, 1, 2) = 'TC' OR 
                                                SUBSTR(p.SUBCODE01, 1, 2) = 'CB' OR 
                                                SUBSTR(p.SUFFIXCODE, 1, 2) = 'SC' OR
                                                SUBSTR(p.SUFFIXCODE, 1, 2) = 'TC')
                                                AND p.PRODUCTIONORDERCODE IN ($value_prod_order)
                                                -- AND NOT w.WHITENESS IS NULL
                                                -- AND NOT y.YELLOWNESS IS NULL 
                                                -- AND NOT t.TINT IS NULL
                                              ORDER BY
                                                p.PRODUCTIONORDERCODE, p.GROUPLINE ASC");
        $no = 1;
        while ($row_whiteness = db2_fetch_assoc($q_whiteness)) {
          $q_rincian_hasilcelup = mysqli_query($con, "SELECT 
                                                          x.*
                                                      FROM tbl_mesin a
                                                      LEFT JOIN (
                                                            SELECT		
                                                              a.kd_stop,
                                                              a.mulai_stop,
                                                              a.selesai_stop,
                                                              a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                                                              a.status as sts,
                                                              TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                                                              TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                                                              a.point,
                                                              DATE_FORMAT(a.mulai_stop,'%Y-%m-%d') as t_mulai,
                                                              DATE_FORMAT(a.selesai_stop,'%Y-%m-%d') as t_selesai,
                                                              TIME_FORMAT(a.mulai_stop,'%H:%i') as j_mulai,
                                                              TIME_FORMAT(a.selesai_stop,'%H:%i') as j_selesai,
                                                              TIMESTAMPDIFF(MINUTE,a.mulai_stop,a.selesai_stop) as lama_stop_menit,
                                                              a.acc_keluar,
                                                              if(a.proses='' or ISNULL(a.proses),b.proses,a.proses) as proses,
                                                              b.buyer,
                                                              b.langganan,
                                                              b.no_order,
                                                              b.jenis_kain,
                                                              b.no_mesin,
                                                              b.warna,
                                                              b.lot,
                                                              b.energi,
                                                              b.dyestuff,	
                                                              b.ket_status,
                                                              b.kapasitas,
                                                              b.loading,
                                                              b.resep,
                                                              b.kategori_warna,
                                                              b.target,
                                                              c.l_r,
                                                              c.rol,
                                                              c.bruto,
                                                              c.pakai_air,
                                                              c.no_program,
                                                              c.pjng_kain,
                                                              c.cycle_time,
                                                              c.rpm,
                                                              c.tekanan,
                                                              c.nozzle,
                                                              c.plaiter,
                                                              c.blower,
                                                              DATE_FORMAT(c.tgl_buat,'%Y-%m-%d') as tgl_in,
                                                              DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') as tgl_out,
                                                              DATE_FORMAT(c.tgl_buat,'%H:%i') as jam_in,
                                                              DATE_FORMAT(a.tgl_buat,'%H:%i') as jam_out,
                                                              if(ISNULL(a.g_shift),c.g_shift,a.g_shift) as shft,
                                                              a.operator_keluar,
                                                              a.k_resep,
                                                              a.status,
                                                              a.proses_point,
                                                              a.analisa,
                                                              b.nokk,
                                                              b.no_warna,
                                                              b.lebar,
                                                              b.gramasi,
                                                              c.carry_over,
                                                              b.no_hanger,
                                                              b.no_item,
                                                              b.po,	
                                                              b.tgl_delivery,
                                                              b.kk_kestabilan,
                                                              b.kk_normal,
                                                              c.air_awal,
                                                              a.air_akhir,
                                                              c.nokk_legacy,
                                                              c.loterp,
                                                              c.nodemand,
                                                              a.tambah_obat,
                                                              a.tambah_obat1,
                                                              a.tambah_obat2,
                                                              a.tambah_obat3,
                                                              a.tambah_obat4,
                                                              a.tambah_obat5,
                                                              a.tambah_obat6,
                                                              c.leader,
                                                              b.suffix,
                                                              b.suffix2,
                                                              c.l_r_2,
                                                              c.lebar_fin,
                                                              c.grm_fin,
                                                              c.lebar_a,
                                                              c.gramasi_a,
                                                              c.operator,
                                                              a.status_resep,
                                                              a.analisa_resep
                                                            FROM
                                                                tbl_schedule b
                                                                LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                                                                LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                                                            WHERE
                                                                a.nokk = '$row_whiteness[PRODUCTIONORDERCODE]')x ON (a.no_mesin=x.no_mesin or a.no_mc_lama = x.no_mesin) 
                                                            WHERE 
                                                              NOT x.nokk IS NULL
                                                            ORDER BY a.no_mesin");
          $row_hasilcelup = mysqli_fetch_assoc($q_rincian_hasilcelup);
    ?>
      <tr valign="top">
        <td><?= $no++; ?></td>
        <td>`<?= $row_whiteness['PRODUCTIONORDERCODE']; ?></td>
        <td>`<?= $row_hasilcelup['nodemand']; ?></td>
        <td><?= $row_hasilcelup['no_hanger']; ?></td>
        <td><?= $row_hasilcelup['jenis_kain']; ?></td>
        <td><?= $row_hasilcelup['warna']; ?></td>
        <td><?= $row_hasilcelup['tgl_in'].' '.$row_hasilcelup['jam_in']; ?></td>
        <td><?= $row_hasilcelup['no_mesin']; ?></td>
        <td><?= $row_whiteness['PRODRESERVATIONLINKGROUPCODE'] ?></td>
        <td><?= number_format($row_whiteness['WHITENESS'], 2); ?></td>
        <td><?= number_format($row_whiteness['YELLOWNESS'], 2); ?></td>
        <td><?= number_format($row_whiteness['TINT'], 2); ?></td>
        <td><?= number_format($row_whiteness['LR'], 2); ?></td>
        <td><?= $row_whiteness['SUFFIX']; ?></td>
        <td><?= $row_whiteness['DESKRIPSI']; ?></td>
      </tr>
      <?php } ?>
  </table>
</body>