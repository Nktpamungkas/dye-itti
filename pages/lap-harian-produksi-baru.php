<?PHP
  ini_set("error_reporting", 1);
  session_start();
  include "koneksi.php";
  $username = $_SESSION['user_id10'];

?>

<?php
  include "koneksi.php";
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  if (isset($_POST['status'])) {
    
    $id = intval($_POST['id_dt']);
    $row = $_POST['row'];
    $val = $_POST['val'];
  }
  if (isset($_POST['update_kresep'])) {
    $nokk = $_POST['nokk'];
    $kresep = $_POST['kresep'];
  
    try {
      $query = "UPDATE tbl_hasilcelup SET k_resep = ?, tgl_update = NOW() WHERE nokk = ?";
      $stmt = $con->prepare($query);
      $stmt->bind_param("si", $kresep, $nokk);
      $stmt->execute();
  
      echo "OK";
    } catch (mysqli_sql_exception $e) {
      echo "Gagal: " . $e->getMessage();
    }
    exit();
  }
  if (isset($_POST['update_resep'])) {
    $nokk = $_POST['nokk'];
    $resep = $_POST['resep'];
  
    try {
      $query = "UPDATE tbl_schedule SET resep = ?, tgl_update = NOW() WHERE nokk = ?";
      $stmt = $con->prepare($query);
      $stmt->bind_param("si", $resep, $nokk);
      $stmt->execute();
  
      echo "OK";
    } catch (mysqli_sql_exception $e) {
      echo "Gagal: " . $e->getMessage();
    }
    exit();
  }
  if (isset($_POST['update_StatusResep'])) {
    $nokk         = $_POST['nokk'];
    $statusresep  = $_POST['statusResep'];

    try {
      $query = "UPDATE tbl_hasilcelup SET status_resep = ? WHERE nokk = ?";
      $stmt = $con->prepare($query);
      $stmt->bind_param("si", $statusresep, $nokk);
      $stmt->execute();
  
      echo "OK";
    } catch (mysqli_sql_exception $e) {
      echo "Gagal: " . $e->getMessage();
    }
    exit();
  }
  if (isset($_POST['update_multi'])) {

    $shift          = $_POST['shift'];
    $mc             = $_POST['mc'];
    $kapasitas      = $_POST['kapasitas'];
    $buyer          = $_POST['buyer'];
    $no_order       = $_POST['no_order'];
    $nodemand       = $_POST['nodemand'];
    $subcode01      = $_POST['subcode01'];
    $kategori_warna = $_POST['kategori_warna'];
    $proses         = $_POST['proses'];
    $loading        = $_POST['loading'];
    $l_r            = $_POST['l_r'];
    $ket            = $_POST['ket'];
    $k_resep        = $_POST['k_resep'];
    $resep          = $_POST['resep'];
    $sts            = $_POST['sts'];
    $dyestuff       = $_POST['dyestuff'];
    $lama_proses    = $_POST['lama_proses'];
    $point2         = $_POST['point2'];
    $carry_over     = $_POST['carry_over'];
    $air_awal       = $_POST['air_awal'];
    $air_akhir      = $_POST['air_akhir'];
    $pake_air       = $_POST['pake_air'];

    // try {
    //   $query1 = "UPDATE tbl_hasilcelup SET keterangan = ?, tgl_update = NOW() WHERE nokk = ?";
    //   $stmt1 = $con->prepare($query1);
    //   $stmt1->bind_param("si", $keterangan, $nokk);
    //   $stmt1->execute();

    //   $query2 = "UPDATE tbl_schedule SET status = ?, tgl_update = NOW() WHERE nokk = ?";
    //   $stmt2 = $con->prepare($query2);
    //   $stmt2->bind_param("si", $status, $nokk);
    //   $stmt2->execute();

    //   echo "OK";
    // } catch (mysqli_sql_exception $e) {
    //   echo "Gagal: " . $e->getMessage();
    // }
    // exit();
  }

  $daftarProses = [];
  $queryProses = mysqli_query($con, "SELECT DISTINCT proses FROM tbl_proses ORDER BY proses ASC");
  while ($rowProses = mysqli_fetch_assoc($queryProses)) {
    $daftarProses[] = $rowProses['proses'];
  }
?>
<?php
  $Awal  = isset($_POST['awal']) ? $_POST['awal'] : '';
  $Akhir  = isset($_POST['akhir']) ? $_POST['akhir'] : '';
  $GShift  = isset($_POST['gshift']) ? $_POST['gshift'] : '';
  $Fs    = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : '';
  $jamA  = isset($_POST['jam_awal']) ? $_POST['jam_awal'] : '';
  $jamAr  = isset($_POST['jam_akhir']) ? $_POST['jam_akhir'] : '';
  $Rcode  = isset($_POST['rcode']) ? $_POST['rcode'] : '';
  if (strlen($jamA) == 5) {
    $start_date = $Awal . ' ' . $jamA;
  } else {
    $start_date = $Awal . ' 0' . $jamA;
  }
  if (strlen($jamAr) == 5) {
    $stop_date  = $Akhir . ' ' . $jamAr;
  } else {
    $stop_date  = $Akhir . ' 0' . $jamAr;
  }	
    
  if($Awal!="" && $Akhir!=""){
    $Tgl = substr($start_date, 0, 10);
    if ($start_date != $stop_date) {
      $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$start_date' AND '$stop_date' ";
    } else {
      $Where = " DATE_FORMAT(c.tgl_update, '%Y-%m-%d')='$Tgl' ";
    }
    if ($GShift == "ALL") {
      $shft = " ";
    } else {
      $shft = " if(ISNULL(a.g_shift),c.g_shift,a.g_shift)='$GShift' AND ";
    }
    $sql = mysqli_query($con, "SELECT x.*, a.no_mesin as mc 
                                  FROM tbl_mesin a
                                      LEFT JOIN
                                      (SELECT
                                      -- a.kd_stop,
                                      -- a.mulai_stop,
                                      -- a.selesai_stop,
                                      a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
                                      a.status as sts,
                                      TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,
                                      TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
                                      -- a.point,
                                      -- DATE_FORMAT(a.mulai_stop,'%Y-%m-%d') as t_mulai,
                                      -- DATE_FORMAT(a.selesai_stop,'%Y-%m-%d') as t_selesai,
                                      -- TIME_FORMAT(a.mulai_stop,'%H:%i') as j_mulai,
                                      -- TIME_FORMAT(a.selesai_stop,'%H:%i') as j_selesai,
                                      -- TIMESTAMPDIFF(MINUTE,a.mulai_stop,a.selesai_stop) as lama_stop_menit,
                                      -- a.acc_keluar,
                                      if(a.proses='' or ISNULL(a.proses),b.proses,a.proses) as proses,
                                      b.buyer,
                                      b.langganan,
                                      b.no_order,
                                      -- b.jenis_kain,
                                      b.no_mesin,
                                      -- b.warna,
                                      -- b.lot,
                                      -- b.energi,
                                      b.dyestuff,	
                                      -- b.ket_status,
                                      b.kapasitas,
                                      b.loading,
                                      b.resep,
                                      CASE
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'D' THEN 'Dark'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'H' THEN 'Heater'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'L' THEN 'Light'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'M' THEN 'Medium'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'S' THEN 'Dark'
                                        WHEN SUBSTR(b.kategori_warna, 1,1) = 'W' THEN 'White'
                                      END AS kategori_warna,
                                      -- b.target,
                                      c.l_r,
                                      c.rol,
                                      -- c.bruto,
                                      -- c.pakai_air,
                                      -- c.no_program,
                                      -- c.pjng_kain,
                                      -- c.cycle_time,
                                      -- c.rpm,
                                      -- c.tekanan,
                                      -- c.nozzle,
                                      -- c.plaiter,
                                      -- c.blower,
                                      -- DATE_FORMAT(c.tgl_buat,'%Y-%m-%d') as tgl_in,
                                      -- DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') as tgl_out,
                                      -- DATE_FORMAT(c.tgl_buat,'%H:%i') as jam_in,
                                      -- DATE_FORMAT(a.tgl_buat,'%H:%i') as jam_out,
                                      if(ISNULL(a.g_shift),c.g_shift,a.g_shift) as shft,
                                      -- a.operator_keluar,
                                      a.k_resep,
                                      a.status,
                                      -- a.proses_point,
                                      -- a.analisa,
                                      b.nokk,
                                      -- b.no_warna,
                                      b.lebar,
                                      -- b.gramasi,
                                      c.carry_over,
                                      -- b.no_hanger,
                                      -- b.no_item,
                                      b.po,	
                                      -- b.tgl_delivery,
                                      b.kk_kestabilan,
                                      b.kk_normal,
                                      c.air_awal,
                                      a.air_akhir,
                                      -- c.nokk_legacy,
                                      -- c.loterp,
                                      c.nodemand,
                                      -- a.tambah_obat,
                                      -- a.tambah_obat1,
                                      -- a.tambah_obat2,
                                      -- a.tambah_obat3,
                                      -- a.tambah_obat4,
                                      -- a.tambah_obat5,
                                      -- a.tambah_obat6,
                                      -- c.leader,
                                      -- b.suffix,
                                      -- b.suffix2,
                                      -- c.l_r_2,
                                      -- c.lebar_fin,
                                      -- c.grm_fin,
                                      -- c.lebar_a,
                                      -- c.gramasi_a,
                                      c.operator,
                                      a.id as idhslclp,
                                      -- a.tambah_dyestuff,
                                      -- a.arah_warna,
                                      -- a.status_warna,
                                      COALESCE(a.point2, b.target) as point2
                                      -- c.note_wt,
                                      -- a.operatorpolyester,
		                                  -- a.operatorcotton
                                    FROM
                                      tbl_schedule b
                                        LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
                                        LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
                                    WHERE
                                      $shft 
                                      $Where
                                      )x ON (a.no_mesin=x.no_mesin) ORDER BY a.no_mesin");
    $no = 1;
    $c = 0;
    $totrol = 0;
    $totberat = 0;
    $data=array();
    $mc_all=array();
    $nokk_all=array();
    while ($rowd = mysqli_fetch_assoc($sql)) {
      $rl ="";
      if (strlen($rowd['rol']) > 5) {
        $jk = strlen($rowd['rol']) - 5;
        $rl = substr($rowd['rol'], 0, $jk);
      } else {
        $rl = $rowd['rol'];
      }
      $rowd['rl']=$rl;
      
      $data[]=$rowd;
      $mc_all[]=$rowd['mc'];
      if($rowd['nokk']!=""){
        $nokk_all[]=$rowd['nokk'];
      }
    }
    $mesin_join= join("','",$mc_all);
    if ($GShift == "ALL") {
      $shftSM = " ";
    } else {
      $shftSM = " g_shift='$GShift' AND ";
    }
    $mesin=array();
    $sqlSM = mysqli_query($con, "SELECT *, TIME_FORMAT(timediff(selesai,mulai),'%H:%i') as menitSM,
                    DATE_FORMAT(mulai,'%Y-%m-%d') as tgl_masuk,
                    DATE_FORMAT(selesai,'%Y-%m-%d') as tgl_selesai,
                    TIME_FORMAT(mulai,'%H:%i') as jam_masuk,
                    TIME_FORMAT(selesai,'%H:%i') as jam_selesai,
                    kapasitas as kapSM,
                    g_shift as shiftSM
                    FROM tbl_stopmesin
                    WHERE $shftSM tgl_update BETWEEN '$start_date' AND '$stop_date' AND no_mesin IN ('$mesin_join')");
    while ($rowSM = mysqli_fetch_assoc($sqlSM)) {
      $mesin[$rowSM['no_mesin']]=$rowSM;
    }
                
    $subcode_all=array();
    $nokk_join= join("','",$nokk_all);
                
    $q_itxviewkk = db2_exec($conn2, "SELECT TRIM(PRODUCTIONORDERCODE) AS PRODUCTIONORDERCODE,
                                        LISTAGG(DISTINCT TRIM(LOT), ', ') AS LOT,
                                        LISTAGG(DISTINCT TRIM(SUBCODE01), ', ') AS SUBCODE01 
                                      FROM 
                                        ITXVIEWKK 
                                        WHERE PRODUCTIONORDERCODE IN ('$nokk_join')
                                  GROUP BY PRODUCTIONORDERCODE; ");
    while ($rowCode = db2_fetch_assoc($q_itxviewkk)) {
      $subcode_all[$rowCode['PRODUCTIONORDERCODE']]=$rowCode;
    }
    $lama_proses_all=array();
    $qryLama = mysqli_query($con, "SELECT b.nokk,
                                      TIME_FORMAT( timediff( now(), b.tgl_buat ), '%H:%i' ) AS lama 
                                    FROM
                                      tbl_schedule a
                                    LEFT JOIN tbl_montemp b ON a.id = b.id_schedule 
                                    WHERE
                                       b.nokk IN ('$nokk_join')
                                    AND b.STATUS = 'sedang jalan' 
                                      ORDER BY
                                    a.no_urut ASC");
    while ($rLama = mysqli_fetch_array($qryLama)) {
      $lama_proses_all[$rLama['nokk']]=$rLama;
    }
  }  
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Harian Produksi (Baru)</title>

</head>

<body>
  
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title"> Filter Laporan Harian Produksi (Baru)</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal" id="form1">
      <div class="box-body">
        <div class="form-group">
          <div class="col-sm-3">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" />
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="text" class="form-control " name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>"  autocomplete="off">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
            <div>
            </div>
          </div>
          <!-- /.input group -->
        </div>

        <div class="form-group">
          <div class="col-sm-3">
            <div class="input-group date">
              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
              <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off" />
            </div>
          </div>
          <div class="col-sm-2">
            <div class="input-group">
              <input type="text" class="form-control " name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" autocomplete="off">
              <div class="input-group-addon">
                <i class="fa fa-clock-o"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-3">
            <select name="gshift" class="form-control pull-right">
              <option value="ALL">ALL</option>
              <option value="A" <?php if ($GShift == "A") {
                                  echo "SELECTED";
                                } ?>>A</option>
              <option value="B" <?php if ($GShift == "B") {
                                  echo "SELECTED";
                                } ?>>B</option>
              <option value="C" <?php if ($GShift == "C") {
                                  echo "SELECTED";
                                } ?>>C</option>
            </select>
          </div>
        
          <div class="col-sm-2">
            <!-- <input type="text" class="form-control" name="rcode" value="<?= $Rcode; ?>" placeholder="Rcode"> -->
          </div>
        </div>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-2">
          <button type="submit" class="btn btn-block btn-social btn-default btn-sm" name="save" style="width: 60%">Search <i class="fa fa-search"></i></button>
        </div>
        
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Laporan Harian Produksi (Baru)</h3><br><br>
          <?php if ($_POST['awal'] != "") { ?><b>Periode: <?php echo $start_date . " to " . $stop_date; ?></b>
            <div class="btn-group pull-right">
             
            </div>
          <?php }elseif($Rcode != ""){ ?>
            <div class="btn-group pull-right">              
              
            </div>
          <?php } ?>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-bordered table-hover" width="100%">
            <thead class="btn-danger">
              <tr>
                <th width="38">
                  <div >Aksi</div>
                </th>
                <th width="38">
                  <div >NO.</div>
                </th>
                <th width="224">
                  <div >NO MC</div>
                </th>
                <th width="215">
                  <div align="center">KAPASITAS</div>
                </th>
                <th width="38">SHIFT</th>
                <th width="314">
                  <div align="center">BUYER</div>
                </th>
                <th width="404">
                  <div align="center">NO ORDER</div>
                </th>
                <th width="215">
                  <div align="center">Prod. Demand</div>
                </th>
                <th width="404">
                  <div align="center">KODE</div>
                </th>
                <th width="404">
                  <div align="center">K.W</div>
                </th>
                <th width="404">
                  <div align="center">PROSES</div>
                </th>
                <th width="215">
                  <div align="center">LOADING</div>
                </th>
                <th width="215">
                  <div align="center">L:R</div>
                </th>
                <th width="215">
                  <div align="center">KETERANGAN</div>
                </th>
                <th width="215">
                  <div align="center">K.R</div>
                </th>
                <th width="215">
                  <div align="center">R.B/R.L</div>
                </th>
                <th width="215">
                  <div align="center">STATUS</div>
                </th>
                <th width="215">
                  <div align="center">REMARKS</div>
                </th>
                <th width="215">
                  <div align="center">DYESTUFF</div>
                </th>
                <th width="215">
                  <div align="center">LAMA PROSES</div>
                </th>
                <th width="215">
                  <div align="center">POINT</div>
                </th>
                <th width="215">
                  <div align="center">Carry Over</div>
                </th>
                <th width="215">
                  <div align="center">Air Awal</div>
                </th>
                <th width="215">
                  <div align="center">Air Akhir</div>
                </th>
                <th width="215">
                  <div align="center">Total Pemakaian Air</div>
                </th>
              </tr>
            </thead>
            <tbody>
              <?php
                $no = 1;
                foreach ($data as $rowd) {
                ?>
                  <tr data-id="<?=$rowd['id']?>" class="table table-bordered table-hover table-striped">
                    <td>
                      <button class="btn btn-primary btn-edit"
                              data-id="<?= $rowd['id'] ?>"
                              data-shift="<?= $rowd['shft'] ?>"
                              data-mc="<?= $rowd['mc'] ?>"
                              data-kapasitas="<?= $rowd['kapasitas'] ?>"
                              data-buyer="<?= $rowd['buyer'] ?>"
                              data-no_order="<?= $rowd['no_order'] ?>"
                              data-nodemand="<?= $rowd['nodemand'] ?>"
                              data-SUBCODE01="<?=$subcode_all[$rowd['nokk']]['SUBCODE01'] ?? ''?>"
                              data-kategori_warna="<?= $rowd['kategori_warna'] ?>"
                              data-proses="<?= $rowd['proses'] ?>"
                              data-loading="<?= $rowd['loading'] ?>"
                              data-l_r="<?= $rowd['l_r'] ?>"
                              data-ket="<?= $rowd['ket'] ?>"
                              data-k_resep="<?= $rowd['k_resep'] ?>"
                              data-resep="<?= $rowd['resep'] ?>"
                              data-sts="<?= $rowd['sts'] ?>"
                              data-dyestuff="<?= $rowd['dyestuff'] ?>"
                              data-lama_proses="<?= $rowd['lama_proses'] ?>"
                              data-point2="<?= $rowd['point2'] ?>"
                              data-carry_over="<?= $rowd['carry_over'] ?>"
                              data-air_awal="<?= $rowd['air_awal'] ?>"
                              data-air_akhir="<?= $rowd['air_akhir'] ?>"
                              data-pake_air="<?= $rowd['air_awal'] != "" && $rowd['air_akhir'] != "" 
                                              ? $rowd['air_akhir'] - $rowd['air_awal']
                                              : ''; ?>"
                              data-nokk="<?= $rowd['nokk'] ?>"
                              data-idhslclp="<?= $rowd['idhslclp'] ?>"

                      >Edit
                      </button>
                    </td>
                    <td><?=$no?></td>
                    <td><?=$rowd['mc']?></td>
                    <td><?=$rowd['kapasitas']?></td>
                    <td><?=$rowd['shft']?></td>
                    <td><?=$rowd['buyer']?></td>
                    <td><?=$rowd['no_order']?></td>
                    <td><?=$rowd['nodemand']?></td>
                    <td><?=$subcode_all[$rowd['nokk']]['SUBCODE01'] ?? ''?></td>
                    <td><?=$rowd['kategori_warna']?></td>
                    <td><?=$rowd['proses']?></td>
                    <td><?=$rowd['loading']?> %</td>
                    <td><?=$rowd['l_r']?></td>
                    <td><?=$rowd['ket']?></td>
                    <td><?=$rowd['k_resep']?></td>
                    <td><?=$rowd['resep']?></td>
                    <td><?=$rowd['sts']?></td>
                    <td><?=$rowd['sts']?></td>
                    <td><?=$rowd['dyestuff']?></td>
                    <td><?=$rowd['lama_proses']?></td>
                    <td><?=$rowd['point2']?></td>
                    <td><?=$rowd['carry_over']?></td>
                    <td><?=$rowd['air_awal']?></td>
                    <td><?=$rowd['air_akhir']?></td>
                    <td >
                      <?= $rowd['air_awal'] != "" && $rowd['air_akhir'] != "" 
                        ? $rowd['air_akhir'] - $rowd['air_awal']
                        : ''; ?>
                    </td>
                  </tr>
                <?php
                  $no++;
                }
                ?>
            </tbody>
          </table>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalDetailPoLabel" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                  <div class="card-body">
                    <h4 class="mb-2 font-weight-bold text-primary">Form Edit</h4>
                    <div class="row">
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputShift" class="form-label">Shift</label>
                        <select class="form-control" id="inputShift" name="shift" required>
                          <option value="">-- Pilih Shift --</option>
                          <option value="A">A</option>
                          <option value="B">B</option>
                          <option value="C">C</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="txQty" class="form-label">Mesin</label>
                        <input type="text" class="form-control" id="inputMc" placeholder="0" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="tsDate" class="form-label">Kapasitas</label>
                        <input type="text" class="form-control readonly" id="inputKapasitas" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="txDate" class="form-label">Buyer</label>
                        <input type="text" class="form-control readonly" id="inputBuyer" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputNoOrder" class="form-label">No Order</label>
                        <input type="text" class="form-control readonly" id="inputNoOrder" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputNodemand" class="form-label">No Demand</label>
                        <input type="text" class="form-control readonly" id="inputNodemand" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputSubcode01" class="form-label">Kode</label>
                        <input type="text" class="form-control readonly" id="inputSubcode01" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKategoriWarna" class="form-label">K Warna</label>
                        <input type="text" class="form-control readonly" id="inputKategoriWarna" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputProses" class="form-label">Proses</label>
                        <select class="form-control" id="inputProses" name="proses" required>
                          <option value="">-- Pilih Proses --</option>
                          <?php foreach ($daftarProses as $proses): ?>
                            <option value="<?= htmlspecialchars($proses) ?>"><?= htmlspecialchars($proses) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLoading" class="form-label">Loading</label>
                        <input type="text" class="form-control readonly" id="inputLoading" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLR" class="form-label">L R</label>
                        <input type="text" class="form-control readonly" id="inputLR" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKet" class="form-label">Keterangan</label>
                        <input type="text" class="form-control readonly" id="inputKet" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputKResep" class="form-label">K Resep</label>
                        <select class="form-control" id="inputKResep" name="KResep" required>
                          <option value="-" >-</option>
                          <option value="0x">0x</option>
                          <option value="1x">1x</option>
                          <option value="2x">2x</option>
                          <option value="3x">3x</option>
                          <option value="4x">4x</option>
                          <option value="5x">5x</option>
                          <option value="6x">6x</option>
                          <option value="7x">7x</option>
                          <option value="8x">8x</option>
                          <option value="9x">9x</option>
                          <option value="10x">10x</option>
                          <option value=">10x">>10x</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputResep" class="form-label">Resep</label>
                        <select class="form-control" id="inputResep" name="Resep" required>
                          <option value="-">-</option>
                          <option value="Baru">Baru</option>
                          <option value="Lama">Lama</option>
                          <option value="Setting">Setting</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputSts" class="form-label">Status</label>
                        <select class="form-control" id="inputSts" autocomplete="off" required>
						            	<option value="">Pilih</option>
						            	<option value="OK">OK</option>
						            	<option value="Celup Poly Dulu-Matching">Celup Poly Dulu-Matching</option>
						            	<option value="Gagal Proses">Gagal Proses</option>
						            	<option value="Levelling-Matching">Levelling-Matching</option>
						            	<option value="Pelunturan-Matching">Pelunturan-Matching</option>
						            	<option value="Scouring Turun">Scouring Turun</option>
						            	<option value="Continuous - Bleaching">Continuous - Bleaching</option>
						            	<option value="Relaxing - Priset">Relaxing - Priset</option>
						            	<option value="Tunggu Review">Tunggu Review</option>
						            </select>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputRemarks" class="form-label">Remarks</label>
                        <input type="text" class="form-control" id="inputRemarks" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputDyestuff" class="form-label">Dye Stuff</label>
                        <input type="text" class="form-control readonly" id="inputDyestuff" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputLamaProses" class="form-label">Lama Proses</label>
                        <input type="text" class="form-control readonly" id="inputLamaProses" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputPoint2" class="form-label">Point</label>
                        <input type="text" class="form-control readonly" id="inputPoint2" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputCarryOver" class="form-label">Carry Over</label>
                        <input type="text" class="form-control readonly" id="inputCarryOver" autocomplete="off" readonly>
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputAirAwal" class="form-label">Air Awal</label>
                        <input type="text" class="form-control readonly" id="inputAirAwal" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputAirAkhir" class="form-label">Air Akhir</label>
                        <input type="text" class="form-control readonly" id="inputAirAkhir" autocomplete="off">
                      </div>
                      <div class="col-xl-3 col-md-6 mb-3">
                        <label for="inputPakeAir" class="form-label">Total Pemakaian Air</label>
                        <input type="text" class="form-control readonly" id="inputPakeAir" autocomplete="off" readonly>
                      </div>

                      <input type="hidden" id="typeSave" name="typeSave" value="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="btnModalCancel" type="cancel" class="btn btn-secondary">Cancel</button>
          <button id="btnModalSave" type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="dist/js/jquery.redirect.js"></script>

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>

  <script>
    function updateProses(selectEl, nokk) {
      const proses = selectEl.value;
    
      Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: `Apakah Anda yakin ingin mengubah proses menjadi "${proses}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(window.location.href, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `update_proses=1&nokk=${nokk}&proses=${encodeURIComponent(proses)}`
          })
          .then(response => response.text())
          .then(data => {
            Swal.fire('Sukses!', 'Proses berhasil diperbarui.', 'success');
          })
          .catch(err => {
            console.error("Error:", err);
            Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui proses.', 'error');
          });
        }
      });
    }
    function updateKresep(selectEl, nokk) {
      const kresep = selectEl.value;
    
      Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: `Apakah Anda yakin ingin mengubah kestabilan resep menjadi "${kresep}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(window.location.href, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `update_kresep=1&nokk=${nokk}&kresep=${encodeURIComponent(kresep)}`
          })
          .then(response => response.text())
          .then(data => {
            Swal.fire('Sukses!', 'Kestabilan resep berhasil diperbarui.', 'success');
          })
          .catch(err => {
            console.error("Error:", err);
            Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui kestabilan resep.', 'error');
          });
        }
      });
    }
    function updateResep(selectEl, nokk) {
      const resep = selectEl.value;
    
      Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: `Apakah Anda yakin ingin mengubah Resep menjadi "${resep}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(window.location.href, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `update_resep=1&nokk=${nokk}&resep=${encodeURIComponent(resep)}`
          })
          .then(response => response.text())
          .then(data => {
            Swal.fire('Sukses!', 'Resep berhasil diperbarui.', 'success');
          })
          .catch(err => {
            console.error("Error:", err);
            Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui resep.', 'error');
          });
        }
      });
    }
    
    function updateStatusResep(selectEl, nokk) {
      const statusResep = selectEl.value;
    
      Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: `Apakah Anda yakin ingin mengubah Status Resep menjadi "${statusResep}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Perbarui!',
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(window.location.href, {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `update_StatusResep=1&nokk=${nokk}&statusResep=${encodeURIComponent(statusResep)}`
          })
          .then(response => response.text())
          .then(data => {
            Swal.fire('Sukses!', 'Status Resep berhasil diperbarui.', 'success');
          })
          .catch(err => {
            console.error("Error:", err);
            Swal.fire('Gagal!', 'Terjadi kesalahan saat memperbarui status resep.', 'error');
          });
        }
      });
    }

    var tmp_val="";
    $('td').click(function(){
      if($(this).data("sts")=="open"){

      }else if($(this).data("sts")=="close"){
        $(this).data("sts", "open")
        let id=$(this).parent().data("id");
        let row=$(this).data("row");
        tmp_val=$(this).html();
        let type=$(this).data("type");
        let cls=id+'-'+row;

        if(type=="text"){
          $(this).html("<input class='"+cls+" newInput' type='text' value='"+tmp_val+"'>");
          $('.'+cls).focus();
        }
      }
      
    });

    $('table').on('focusout', '.newInput', function() {
      save($(this));
    });

    $('table').on('keyup', '.newInput', function(e) {
      //if enter
      if (e.keyCode === 13) {
        // $(this).parent().data("sts", "close");
        // let val= $(this).val();
        // $(this).parent().html(val);

        save($(this));
      }
      //if esc
      else if (e.keyCode === 27) {
        $(this).parent().data("sts", "close");
        $(this).parent().html(tmp_val);
        tmp_val="";
        Swal.fire({
            title: 'Not Saved',
            text: 'Tidak Menyimpan data.',
            icon: 'error',
            timer: 1000,
            topLayer: false,
            position : 'top-end',
            showConfirmButton: false
        })
      }
    });

    function save(input){
      let id_dt=input.parent().parent().data("id");
      let row=input.parent().data('row');
      let val= input.val();

      input.parent().data("sts", "close");
      input.parent().html(val);
      Swal.fire({
            title: 'Saved',
            text: 'Berhasil Menyimpan data.',
            icon: 'success',
            timer: 1000,
            topLayer: false,
            position : 'top-end',
            showConfirmButton: false
        })

      // const uri = "<?=$_SERVER['REQUEST_URI'];?>";
      // const uriSplit = uri.split("?");
      // const baseUrl= "<?=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];?>"+uriSplit[0];
      let dataPost={status:"update_row", id_dt : id_dt, row : row, val:val};
      // console.log(dataPost);
      // $.ajax({
      //   url: baseUrl+"?p=lap-harian-produksi-baru",
      //   type: "POST",
      //   data: {status:"update_row" id : id_row, row : row, data:data_row},
      //   success: function(response){
      //     console.log(response);
      //   }
      // });
    }
  </script>

  <script>
    let originalData = {};
    let no_kk = "NONE"
    let idhasilcelup = "NONE"

    $(document).ready(function () {
      $('.btn-edit').click(function () {
        const btn = $(this);

        no_kk = btn.data('nokk')
        idhasilcelup = btn.data('idhslclp')

        // Simpan data awal
        originalData = {
          shift: btn.data('shift'),
          mc: btn.data('mc'),
          kapasitas: btn.data('kapasitas'),
          buyer: btn.data('buyer'),
          no_order: btn.data('no_order'),
          nodemand: btn.data('nodemand'),
          subcode01: btn.data('subcode01'),
          kategori_warna: btn.data('kategori_warna'),
          proses: btn.data('proses'),
          loading: btn.data('loading'),
          l_r: btn.data('l_r'),
          ket: btn.data('ket'),
          k_resep: btn.data('k_resep'),
          resep: btn.data('resep'),
          sts: btn.data('sts'),
          dyestuff: btn.data('dyestuff'),
          lama_proses: btn.data('lama_proses'),
          point2: btn.data('point2'),
          carry_over: btn.data('carry_over'),
          air_awal: btn.data('air_awal'),
          air_akhir: btn.data('air_akhir'),
          nokk: btn.data('nokk'),
          idhslclp: btn.data('idhslclp')
        };

        // console.log(originalData);

        for (const key in originalData) {
          $('#input' + toCamelCase(key)).val(originalData[key]);
        }

        $('#typeSave').val(btn.data('id'));

        $('#editModal').modal({
          backdrop: 'static',
          keyboard: false
        });
      });
      $('#btnModalCancel').click(function () {
        if (isFormChanged()) {
          if (confirm("Ada data yang dirubah. Yakin ingin keluar tanpa menyimpan?")) {
            $('#editModal').modal('hide');
          }
        } else {
          $('#editModal').modal('hide');
        }
      });
      $('#btnModalSave').click(function () {
        const nokk = $(this).data('nokk');
        const id = $(this).data('idhslclp');

        if (!isFormChanged()) {
          alert("Tidak ada perubahan data untuk disimpan.");
          return;
        }

        const dataToSend = {
          update_multi: true,
          nokk: no_kk,
          id: idhasilcelup,
          shift: $('#inputShift').val(),
          mc: $('#inputMc').val(),
          kapasitas: $('#inputKapasitas').val(),
          buyer: $('#inputBuyer').val(),
          no_order: $('#inputNoOrder').val(),
          nodemand: $('#inputNodemand').val(),
          subcode01: $('#inputSubcode01').val(),
          kategori_warna: $('#inputKategoriWarna').val(),
          proses: $('#inputProses').val(),
          loading: $('#inputLoading').val(),
          l_r: $('#inputLR').val(),
          ket: $('#inputKet').val(),
          k_resep: $('#inputKResep').val(),
          resep: $('#inputResep').val(),
          sts: $('#inputSts').val(),
          dyestuff: $('#inputDyestuff').val(),
          lama_proses: $('#inputLamaProses').val(),
          point2: $('#inputPoint2').val(),
          carry_over: $('#inputCarryOver').val(),
          air_awal: $('#inputAirAwal').val(),
          air_akhir: $('#inputAirAkhir').val(),
          pake_air: $('#inputPakeAir').val()
        };

        console.log(dataToSend);

        $.post('', dataToSend, function(response) {
          if (response.trim() === 'OK') {
            alert("Data berhasil disimpan.");
            $('#editModal').modal('hide');
          } else {
            alert("Gagal menyimpan: " + response);
          }
        });
      });

      window.addEventListener('beforeunload', function (e) {
        if ($('#editModal').hasClass('show') && isFormChanged()) {
          e.preventDefault();
          e.returnValue = '';
        }
      });

      function getCurrentFormData() {
        return {
          shift: $('#inputShift').val(),
          mc: $('#inputMc').val(),
          kapasitas: $('#inputKapasitas').val(),
          buyer: $('#inputBuyer').val(),
          no_order: $('#inputNoOrder').val(),
          nodemand: $('#inputNoDemand').val(),
          subcode01: $('#inputSubcode01').val(),
          kategori_warna: $('#inputKategoriWarna').val(),
          proses: $('#inputProses').val(),
          loading: $('#inputLoading').val(),
          l_r: $('#inputLR').val(),
          ket: $('#inputKet').val(),
          k_resep: $('#inputKResep').val(),
          resep: $('#inputResep').val(),
          sts: $('#inputSts').val(),
          dyestuff: $('#inputDyestuff').val(),
          lama_proses: $('#inputLamaProses').val(),
          point2: $('#inputPoint2').val(),
          carry_over: $('#inputCarryOver').val(),
          air_awal: $('#inputAirAwal').val(),
          air_akhir: $('#inputAirAkhir').val(),
          pake_air: $('#inputPakeAir').val()
        };
      }
      function isFormChanged() {
        const currentData = getCurrentFormData();

        console.log(currentData);
        console.log(originalData);
        
        for (const key in originalData) {
          if (originalData[key] != currentData[key]) {
            return true;
          }
        }
        return false;
      }
      function toCamelCase(str) {
        // console.log(str);
        return str.replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); }).replace(/^([a-z])/,
          function (g) { return g.toUpperCase(); });
      }
      function toPascalCase(str) {
        return str
          .split('_')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1))
          .join('');
      }
    });
  </script>
</body>

</html>