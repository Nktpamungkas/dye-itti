<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=schedule-produksi-dye-" . substr(date("Y-m-d-H-i"), 0, 20) . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$Awal = $_GET['Awal'];
$Akhir = $_GET['Akhir'];
$qTgl = mysqli_query($con, "SELECT DATE_FORMAT(now(),'%Y-%m-%d %H:%i') as tgl_skrg, DATE_FORMAT(now(),'%Y-%m-%d')+ INTERVAL 1 DAY as tgl_besok");
$rTgl = mysqli_fetch_array($qTgl);
if ($Awal != "") {
  $tgl = substr($Awal, 0, 10);
  $jam = $Awal;
} else {
  $tgl = $rTgl['tgl_skrg'];
  $jam = $rTgl['jam_skrg'];
}
?>

<body>
  <strong>Tgl: <?php echo $tgl . " " . $jam; ?></strong><br />
  <table width="100%" border="1">
    <tr>
      <th bgcolor="#99FF99">KAPASITAS</th>
      <th bgcolor="#99FF99">NO MC</th>
      <th bgcolor="#99FF99">NO. URUT</th>
      <th bgcolor="#99FF99">LANGGANAN</th>
      <th bgcolor="#99FF99">BUYER</th>
      <th bgcolor="#99FF99">NO PO</th>
      <th bgcolor="#99FF99">NO ORDER</th>
      <th bgcolor="#99FF99">JENIS KAIN</th>
      <th bgcolor="#99FF99">WARNA</th>
      <th bgcolor="#99FF99">NO WARNA</th>
      <th bgcolor="#99FF99">LOT</th>
      <th bgcolor="#99FF99">TANGGAL DELIVERY</th>
      <th bgcolor="#99FF99">ROLL</th>
      <th bgcolor="#99FF99">QUANTITY</th>
      <th bgcolor="#99FF99">KETERANGAN</th>
      <th bgcolor="#99FF99">SUFFIX</th>
      <th bgcolor="#99FF99">SUFFIX 2</th>
    </tr>
    <?php
    if ($awal != "") {
      $where = " AND DATE_FORMAT( tgl_update, '%Y-%m-%d %H:%i:%s' ) BETWEEN '$awal' AND '$akhir' ";
    } else {
      $where = " ";
    }
    $sql = mysqli_query($con, "SELECT
                                  kapasitas,
                                  no_mesin,
                                  no_urut,
                                  GROUP_CONCAT( lot SEPARATOR '/' ) AS lot,
                                  if(COUNT(lot)>1,'Gabung Kartu','') as ket_kartu,
                                  if(COUNT(lot)>1,CONCAT('(',COUNT(lot),'kk',')'),'') as kk,
                                  buyer,
                                  langganan,
                                  po,
                                  GROUP_CONCAT(DISTINCT no_order SEPARATOR '-' ) AS no_order,
                                  no_resep,
                                  nokk,
                                  jenis_kain,
                                  warna,
                                  no_warna,
                                  sum(rol) as rol,
                                  sum(bruto) as bruto,
                                  proses,
                                  ket_status,
                                  tgl_delivery,
                                  ket_kain,
                                  GROUP_CONCAT(DISTINCT personil SEPARATOR ',' ) AS personil,
                                  mc_from,
                                  suffix,
                                  suffix2,
                                  no_hanger
                                FROM
                                  tbl_schedule 
                                WHERE
                                  NOT STATUS = 'selesai' $where
                                GROUP BY
                                  no_mesin,
                                  no_urut 
                                ORDER BY
                                  kapasitas DESC,no_mesin ASC");

    $no = 1;

    $c = 0;
    $totrol = 0;
    $totberat = 0;

    while ($rowd = mysqli_fetch_array($sql)) {
    ?>
      <tr valign="top">
        <td valign="top"><?php echo $rowd['kapasitas']; ?></td>
        <td valign="top">'<?php echo $rowd['no_mesin']; ?></td>
        <td valign="top"><?php echo $rowd['no_urut']; ?></td>
        <td valign="top"><?php echo $rowd['langganan']; ?></td>
        <td valign="top"><?php echo $rowd['buyer']; ?></td>
        <td valign="top"><?php echo $rowd['po']; ?></td>
        <td valign="top"><?php echo $rowd['no_order']; ?></td>
        <td valign="top"><?php echo $rowd['jenis_kain']; ?></td>
        <td valign="top"><?php echo $rowd['warna']; ?></td>
        <td valign="top"><?php echo $rowd['no_hanger']; ?>/<?php echo $rowd['no_warna']; ?></td>
        <td valign="top">'<?php echo $rowd['lot']; ?></td>
        <td valign="top"><?php echo $rowd['tgl_delivery']; ?></td>
        <td align="right" valign="top"><?php echo $rowd['rol'] . $rowd['kk']; ?></td>
        <td align="right" valign="top"><?php echo $rowd['bruto']; ?></td>
        <td><?php echo $rowd['ket_status']; ?><br>
          <?php echo $rowd['personil']; ?><br>
          <?php echo $rowd['ket_kain']; ?><br>
          <?php echo $rowd['mc_from']; ?> </td>
        <td valign="top">'<?php echo $rowd['suffix']; ?></td>
        <td valign="top">'<?php echo $rowd['suffix2']; ?></td>
      </tr>
    <?php
      $totrol += $rowd['rol'];
      $totberat += $rowd['bruto'];
      $no++;
    } ?>
    <tr>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">Total</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99"><?php echo $totrol; ?></th>
      <th bgcolor="#99FF99"><?php echo $totberat; ?></th>
      <th bgcolor="#99FF99">&nbsp;</th>
    </tr>
  </table>
</body>