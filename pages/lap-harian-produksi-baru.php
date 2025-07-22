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
    if($_POST['status']=="update_row" && $id != 0){
      try {
        $query = "UPDATE tbl_export_laporan SET $row = ? WHERE nokk = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $proses, $nokk);
        $stmt->execute();
    
        echo "OK";
      } catch (mysqli_sql_exception $e) {
        echo "Gagal: " . $e->getMessage();
      }
      exit();
    }
  
    
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
    //$stop_date  = date('Y-m-d', strtotime($Awal . ' +1 day')).' 07:00:00';	
    
  if($Awal!="" && $Akhir!=""){
    //check apakah data sebelumnya sudah pernah di buat
    $export_laporan_check = mysqli_query($con, "SELECT id FROM tbl_export_laporan WHERE tanggal_awal = '$Awal' && tanggal_akhir = '$Akhir' limit 1 ");
    $export_laporan_count=mysqli_num_rows($export_laporan_check);
    if($export_laporan_count==0){
      //get data dan insert
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
      // $rLama = mysqli_fetch_array($qryLama);
      while ($rLama = mysqli_fetch_array($qryLama)) {
        $lama_proses_all[$rLama['nokk']]=$rLama;
      }
      $insertData=array();
      $fix_data=array();
      foreach($data as $id => $rowd){
        $fix_data[$id]['tanggal_awal']= $Awal;
        $fix_data[$id]['tanggal_akhir']= $Akhir;
        $fix_data[$id]['jam_awal']= $jamA;
        $fix_data[$id]['jam_akhir']= $jamAr;
        if ($rowd['langganan'] == "" and substr($rowd['proses'], 0, 10) != "Cuci Mesin") {
          $fix_data[$id]['shft']=       $mesin[$rowd['mc']]['shiftSM'];
          $fix_data[$id]['kapasitas']=  $mesin[$rowd['mc']]['kapSM'];
          $fix_data[$id]['proses']=     $mesin[$rowd['mc']]['proses'];
          $fix_data[$id]['keterangan']= $mesin[$rowd['mc']]['keterangan'] . "" . $mesin[$rowd['mc']]['no_stop'];
        } else {
          $fix_data[$id]['shft']=       $rowd['shft'];
          $fix_data[$id]['kapasitas']=  $rowd['kapasitas'];
          $fix_data[$id]['proses']=     $rowd['proses'];
          $fix_data[$id]['keterangan']= $rowd['ket'] . "" . $rowd['status'];
        }

        if ($rowd['kk_kestabilan'] == "1" and $rowd['kk_normal'] == "0") {
          $fix_data[$id]['keterangan'] .= "<br>Test Kestabilan";
        }

        if ($rowd['lama_proses']) {
          $fix_data[$id]['lama_proses']= $rowd['jam'] . ":" . $rowd['menit'];
        }else{
          $fix_data[$id]['lama_proses']= $lama_proses_all[$rowd['nokk']]['nokk'];
        }

        $fix_data[$id]['mc']=$rowd['mc'];
        $fix_data[$id]['buyer']=$rowd['buyer'];
        $fix_data[$id]['no_order']=$rowd['no_order'];
        $fix_data[$id]['nodemand']=$rowd['nodemand'];
        $fix_data[$id]['SUBCODE01']=$subcode_all[$rowd['nokk']]['SUBCODE01'];
        $fix_data[$id]['kategori_warna']=$rowd['kategori_warna'];
        $fix_data[$id]['loading']=$rowd['loading'];
        $fix_data[$id]['l_r']=$rowd['l_r'];
        $fix_data[$id]['k_resep']=$rowd['k_resep'];
        $fix_data[$id]['resep']=$rowd['resep'];
        $fix_data[$id]['sts']=$rowd['sts'];
        $fix_data[$id]['dyestuff']=$rowd['dyestuff'];
        $fix_data[$id]['point']=$rowd['point2'];
        $fix_data[$id]['carry_over']=$rowd['carry_over'];
        $fix_data[$id]['air_awal']=$rowd['air_awal'];
        $fix_data[$id]['air_akhir']=$rowd['air_akhir'];
        $fix_data[$id]['pemakaian_air']=($rowd['air_akhir']) ? $rowd['air_akhir'] - $rowd['air_awal']: "";
        //untuk insert
        $i=$fix_data[$id];
        $insertData[]='(NULL, "'.$i['tanggal_awal'].'","'.$i['tanggal_akhir'].'","'.$i['jam_awal'].'","'.$i['jam_akhir'].'","'.$i['shft'].'","'.$i['mc'].'","'.$i['kapasitas'].'","'.$i['buyer'].'","'.$i['no_order'].'","'.$i['nodemand'].'","'.$i['SUBCODE01'].'","'.$i['kategori_warna'].'","'.$i['proses'].'","'.$i['loading'].'","'.$i['l_r'].'","'.$i['keterangan'].'","'.$i['k_resep'].'","'.$i['resep'].'","'.$i['sts'].'","'.$i['dyestuff'].'","'.$i['lama_proses'].'","'.$i['point'].'","'.$i['carry_over'].'","'.$i['air_awal'].'","'.$i['air_akhir'].'","'.$i['pemakaian_air'].'")';
      }

      $sqlInsert = "INSERT INTO tbl_export_laporan VALUES ". join(",",$insertData);

      if (mysqli_query($con, $sqlInsert)) {
        
      } else {
        echo "Error Insert: " . $sql . "<br>" . mysqli_error($conn);
        exit();
      }
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
              <input type="text" class="form-control " name="jam_awal" placeholder="00:00" value="<?php echo $jamA; ?>" readonly autocomplete="off">

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
              <input type="text" class="form-control " name="jam_akhir" placeholder="00:00" value="<?php echo $jamAr; ?>" readonly autocomplete="off">
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
                  <div >NO.</div>
                </th>
                <th width="38">SHIFT</th>
                <th width="224">
                  <div >NO MC</div>
                </th>
                <th width="215">
                  <div align="center">KAPASITAS</div>
                </th>
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
                  <div align="center">% LOADING</div>
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
              $bgcolor = ($no & 1) ? 'gainsboro' : 'antiquewhite';
              $no=1;
              if($Awal!="" && $Akhir!=""){
                $export_laporan_check = mysqli_query($con, "SELECT * FROM tbl_export_laporan WHERE tanggal_awal = '$Awal' && tanggal_akhir = '$Akhir' ");
                while ($rowd = mysqli_fetch_assoc($export_laporan_check)) {                
              ?>
                <tr data-id="<?=$rowd['id']?>" class="table table-bordered table-hover table-striped">
                  <td data-sts="close" data-row="no"><?=$no?></td>
                  <td data-sts="close" data-row="shft" data-type="text"><?=$rowd['shft']?></td>
                  <td data-sts="close" data-row="mc" data-type="text"><?=$rowd['mc']?></td>
                  <td data-sts="close" data-row="kapasitas" data-type="text"><?=$rowd['kapasitas']?></td>
                  <td data-sts="close" data-row="buyer"><?=$rowd['buyer']?></td>
                  <td data-sts="close" data-row="no_order"><?=$rowd['no_order']?></td>
                  <td data-sts="close" data-row="nodemand"><?=$rowd['nodemand']?></td>
                  <td data-sts="close" data-row="SUBCODE01"><?=$rowd['SUBCODE01']?></td>
                  <td data-sts="close" data-row="kategori_warna"><?=$rowd['kategori_warna']?></td>
                  <td data-sts="close" data-row="proses"><?=$rowd['proses']?></td>
                  <td data-sts="close" data-row="loading"><?=$rowd['loading']?></td>
                  <td data-sts="close" data-row="l_r"><?=$rowd['l_r']?></td>
                  <td data-sts="close" data-row="keterangan"><?=$rowd['keterangan']?></td>
                  <td data-sts="close" data-row="k_resep"><?=$rowd['k_resep']?></td>
                  <td data-sts="close" data-row="resep"><?=$rowd['resep']?></td>
                  <td data-sts="close" data-row="sts"><?=$rowd['sts']?></td>
                  <td data-sts="close" data-row="dyestuff"><?=$rowd['dyestuff']?></td>
                  <td data-sts="close" data-row="lama_proses"><?=$rowd['lama_proses']?></td>
                  <td data-sts="close" data-row="point"><?=$rowd['point']?></td>
                  <td data-sts="close" data-row="carry_over"><?=$rowd['carry_over']?></td>
                  <td data-sts="close" data-row="air_awal"><?=$rowd['air_awal']?></td>
                  <td data-sts="close" data-row="air_akhir"><?=$rowd['air_akhir']?></td>
                  <td data-sts="close" data-row="pemakaian_air"><?=$rowd['pemakaian_air']?></td>
                </tr>
              <?php 
                $no++;
                }
              } 
              ?>
            </tbody>
          </table>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div id="EditStsCelup" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            console.log("Respon:", data);
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
            console.log("Respon:", data);
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
            console.log("Respon:", data);
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
            console.log("Respon:", data);
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
      console.log(dataPost);
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

</body>

</html>