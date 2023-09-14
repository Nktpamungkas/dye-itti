<?php
  ini_set("error_reporting", 0);
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=report-produksi-With-Whitness" . substr($_GET['awal'], 0, 10) . ".xls"); //ganti nama sesuai keperluan
  header("Pragma: no-cache");
  header("Expires: 0");
  //disini script laporan anda
?>
<?php
  ini_set("error_reporting", 1);
  include "../../koneksi.php";
  include "../../koneksiLAB.php";
  include "../../tgl_indo.php";
  //--
  $idkk = $_REQUEST['idkk'];
  $act = $_GET['g'];
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
      <th bgcolor="#99FF99">PRD. RSV. LINK GROUP CODE</th>
      <th bgcolor="#99FF99">WHITENESS</th>
      <th bgcolor="#99FF99">YELLOWNESS</th>
      <th bgcolor="#99FF99">TINT</th>
      <th bgcolor="#99FF99">LR</th>
      <th bgcolor="#99FF99">SUFFIX</th>
      <th bgcolor="#99FF99">DESCRIPTION</th>
    <?php
        ini_set("error_reporting", 0);
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
        if($_GET['rcode']){
          $left_right = 'RIGHT';
          $where_new = "a.rcode LIKE '%$_GET[rcode]%'";
        }else{
          $left_right = 'LEFT';
          $where_new = $shft.' '.$Where;
        }
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
                                                AND NOT w.WHITENESS IS NULL
                                                AND NOT y.YELLOWNESS IS NULL 
                                                AND NOT t.TINT IS NULL
                                              ORDER BY
                                                p.PRODUCTIONORDERCODE, p.GROUPLINE ASC");
        $no = 1;
        while ($row_whiteness = db2_fetch_assoc($q_whiteness)) {
    ?>
      <tr valign="top">
        <td><?= $no++; ?></td>
        <td>`<?= $row_whiteness['PRODUCTIONORDERCODE'] ?></td>
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