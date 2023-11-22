<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename= Report Buka Resep.xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
    ini_set("error_reporting", 1);
    include "../../koneksi.php";
    $Awal   = $_GET['awal'];
    $Akhir  = $_GET['akhir'];
    $jamA   = $_GET['jam_awal'];
    $jamAr  = $_GET['jam_akhir'];
    $GShift = $_GET['gshift'];
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
    if($jamA & $jamAr){
        $where_jam  = "createdatetime BETWEEN '$start_date' AND '$stop_date'";
    }else{
        $where_jam  = "DATE(createdatetime) BETWEEN '$Awal' AND '$Akhir'";
    }

    if($GShift == 'ALL'){
        $where_gshift = "";
    }else{
        $where_gshift = "AND gshift = '$GShift'";
    }
?>
<table border="1" style="width:100%">
    <thead>
        <tr>
            <th style="width:5%">No.</th>
            <th style="width:10%">No. Kartu Kerja</th>
            <th style="width:10%">No. Demand</th>
            <th style="width:10%">Pelanggan</th>
            <th>No. Order</th>
            <th>No. Item</th>
            <th align="center">Jenis Kain</th>
            <th>Warna</th>
            <th align="center">Bon Resep 1 <br> Suffix</th>
            <th align="center">Bon Resep 2 <br> Suffix</th>
            <th>Cek Resep</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $q_bukaresep    = mysqli_query($con, "SELECT
                                                    *
                                                FROM
                                                    tbl_bukaresep 
                                                WHERE 
                                                    $where_jam $where_gshift");
            $no = 1;
        ?>
        <?php while ($row_bukaresep = mysqli_fetch_array($q_bukaresep)) { ?>
            <?php
                $sql_ITXVIEWKK  = db2_exec($conn2, "SELECT * FROM ITXVIEWKK WHERE PRODUCTIONORDERCODE = '$row_bukaresep[nokk]'");
                $dt_ITXVIEWKK	= db2_fetch_assoc($sql_ITXVIEWKK);

                $sql_pelanggan_buyer 	= db2_exec($conn2, "SELECT TRIM(LANGGANAN) AS PELANGGAN, TRIM(BUYER) AS BUYER FROM ITXVIEW_PELANGGAN 
                                                            WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$dt_ITXVIEWKK[ORDPRNCUSTOMERSUPPLIERCODE]' AND CODE = '$dt_ITXVIEWKK[PROJECTCODE]'");
                $dt_pelanggan_buyer		= db2_fetch_assoc($sql_pelanggan_buyer);
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row_bukaresep['nokk']; ?></td>
                <td><?= $row_bukaresep['nodemand']; ?></td>
                <td><?= $dt_pelanggan_buyer['PELANGGAN']; ?></td>
                <td><?= $dt_ITXVIEWKK['PROJECTCODE']; ?></td>
                <td><?= TRIM($dt_ITXVIEWKK['SUBCODE02']).' '.TRIM($dt_ITXVIEWKK['SUBCODE03']); ?></td>
                <td><?= $dt_ITXVIEWKK['ITEMDESCRIPTION']; ?></td>
                <td><?= $dt_ITXVIEWKK['WARNA']; ?></td>
                <td align="center"><?= $row_bukaresep['noresep1'].'<br>'.$row_bukaresep['suffix1']; ?></td>
                <td align="center"><?= $row_bukaresep['noresep2'].'<br>'.$row_bukaresep['suffix2']; ?></td>
                <td><?= $row_bukaresep['cek_resep']; ?></td>
                <td><?= $row_bukaresep['ket']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>