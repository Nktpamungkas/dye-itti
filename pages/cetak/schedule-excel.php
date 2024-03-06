<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=report-schedule-produksi-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php 
ini_set("error_reporting", 1);
include "../../koneksi.php";
include "../../tgl_indo.php";
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
//-
$qTgl=mysqli_query($con,"SELECT DATE_FORMAT(now(),'%Y-%m-%d %H:%i') as tgl_skrg, DATE_FORMAT(now(),'%Y-%m-%d %H:%i')+ INTERVAL 1 DAY as tgl_besok");
$rTgl=mysqli_fetch_array($qTgl);
$Awal=$_GET['awal'];
$Akhir=$_GET['akhir'];
if($Awal==$Akhir){$TglPAl=substr($Awal,0,10);$TglPAr=substr($Akhir,0,10);}else{ $TglPAl=$Awal;$TglPAr=$Akhir; }
$shft=$_GET['shft'];
function cekDesimal($angka){
	$bulat=round($angka);
	if($bulat>$angka){
		$jam=$bulat-1;
		$waktu=$jam." Jam 30 Menit";
	}else{
		$jam=$bulat;
		$waktu=$jam." Jam 00 Menit";
	}
	return $waktu;
}
?>
<body>
	
<strong>Periode: <?php echo $TglPAl; ?> s/d <?php echo $TglPAr; ?></strong><br>
<strong>Shift: <?php echo $shft; ?></strong><br />
<table width="100%" border="1">
    <tr>
      <th bgcolor="#99FF99">NO.</th>
      <th bgcolor="#99FF99">NO MC</th>
      <th bgcolor="#99FF99">SHIFT</th>
      <th bgcolor="#99FF99">NOKK</th>
      <th bgcolor="#99FF99">KAPASITAS</th>
      <th bgcolor="#99FF99">LANGGANAN</th>
      <th bgcolor="#99FF99">BUYER</th>
      <th bgcolor="#99FF99">NO PO</th>
      <th bgcolor="#99FF99">NO ORDER</th>
      <th bgcolor="#99FF99">JENIS KAIN</th>
      <th bgcolor="#99FF99">WARNA</th>
      <th bgcolor="#99FF99">NO WARNA</th>
      <th bgcolor="#99FF99">LOT</th>
      <th bgcolor="#99FF99">ROLL</th>
      <th bgcolor="#99FF99">QUANTITY</th>
      <th bgcolor="#99FF99">LOADING</th>
      <th bgcolor="#99FF99">PROSES</th>
      <th bgcolor="#99FF99">TARGET PROSES</th>
      <th bgcolor="#99FF99">LAMA PROSES</th>
      <th bgcolor="#99FF99">LAMA STOP</th>
      <th bgcolor="#99FF99">OVER TIME</th>
      <th bgcolor="#99FF99">K.R</th>
      <th bgcolor="#99FF99">R.B/R.L</th>
    </tr>
    <?php
	$Awal=$_GET['awal'];
	$Akhir=$_GET['akhir'];
	$Tgl=substr($Awal,0,10);	
	if($Awal!=$Akhir){ $Where=" DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$Awal' AND '$Akhir' ";}else{
		$Where=" DATE_FORMAT(c.tgl_update, '%Y-%m-%d')='$Tgl' ";
	}
	if($_GET['shft']=="ALL"){$shft=" ";}else{$shft=" if(ISNULL(a.g_shift),b.g_shift,a.g_shift)='$_GET[shft]' AND ";}
		$sql=mysqli_query($con,"SELECT x.*,a.no_mesin as mc FROM tbl_mesin a
  LEFT JOIN
  (SELECT
	a.kd_stop,
	a.mulai_stop,
	a.selesai_stop,
	a.ket,	if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))) as lama_proses,
	a.status as sts_hasil,	TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%H') as jam,	TIME_FORMAT(if(ISNULL(TIMEDIFF(c.tgl_mulai,c.tgl_stop)),a.lama_proses,CONCAT(LPAD(FLOOR((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))/60),2,0),':',LPAD(((((HOUR(a.lama_proses)*60)+MINUTE(a.lama_proses))-((HOUR(TIMEDIFF(c.tgl_mulai,c.tgl_stop))*60)+MINUTE(TIMEDIFF(c.tgl_mulai,c.tgl_stop))))%60),2,0))),'%i') as menit,
	a.point,
	DATE_FORMAT(a.mulai_stop,'%Y-%m-%d') as t_mulai,
	DATE_FORMAT(a.selesai_stop,'%Y-%m-%d') as t_selesai,
	TIME_FORMAT(a.mulai_stop,'%H:%i') as j_mulai,
	TIME_FORMAT(a.selesai_stop,'%H:%i') as j_selesai,
	TIMESTAMPDIFF(MINUTE,a.mulai_stop,a.selesai_stop) as lama_stop_menit,
	a.acc_keluar,
	a.analisa,
  a.k_resep,
	b.proses,
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
	c.l_r,
	c.rol,
	c.bruto,
	c.pakai_air,
	DATE_FORMAT(c.tgl_buat,'%Y-%m-%d') as tgl_in,
	DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') as tgl_out,
	DATE_FORMAT(c.tgl_buat,'%H:%i') as jam_in,
	DATE_FORMAT(a.tgl_buat,'%H:%i') as jam_out,
	if(ISNULL(a.g_shift),b.g_shift,a.g_shift) as shft,
	a.operator_keluar,
	b.nokk,
	b.no_warna,
	b.lebar,
	b.gramasi,
	c.carry_over,
	b.no_hanger,
	b.no_item,
	b.po,
	b.tgl_delivery,
	b.target,
	if((TIME_FORMAT(a.lama_proses,'%H')+round(TIME_FORMAT(a.lama_proses,'%i')/60,2))>b.target,'lebih','kurang') as jjm
FROM
	tbl_schedule b
	LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
	LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
WHERE
	$shft 
	$Where)x ON (a.no_mesin=x.no_mesin or a.no_mc_lama=x.no_mesin) ORDER BY a.no_mesin");
  
   $no=1;
   $totrol=0;
   $totberat=0;
   $c=0;
   
    while($rowd=mysqli_fetch_array($sql)){
      $target = explode(".", $rowd['target']);
      $jamtarget = (int)$target[0] * 60;
      if($target[1]=='5'){$menittarget = 30;}else{$menittarget = 0;}
      $jmltarget = $jamtarget + $menittarget;
      $jamproses = (int)$rowd['jam'] * 60;
      $jmlproses = $jamproses + (int)$rowd['menit'];
      $overtime = $jmlproses - $jmltarget;
      $hours = floor($overtime / 60);
      $min = $overtime - ($hours * 60);
		   ?>
      <tr valign="top">
      <td><?php echo $no;?></td>
      <td>'<?php echo $rowd['mc'];?></td>
      <td><?php echo $rowd['shft'];?></td>
      <td>'<?php echo $rowd['nokk'];?></td>
      <td><?php echo $rowd['kapasitas'];?></td>
      <td><?php echo $rowd['langganan'];?></td>
      <td><?php echo $rowd['buyer'];?></td>
      <td><?php echo $rowd['po'];?></td>
      <td><?php echo $rowd['no_order']; ?></td>
      <td><?php echo $rowd['jenis_kain'];?></td>
      <td><?php echo $rowd['warna']; ?></td>
      <td><?php echo $rowd['no_warna'];?></td>
      <td>'<?php echo $rowd['lot']; ?></td>
      <td align="right"><?php if($rowd['tgl_out']!=""){$rol=$rowd['rol'];}else{ $rol=0; } echo $rol; ?></td>
      <td align="right"><?php if($rowd['tgl_out']!=""){$brt=$rowd['bruto'];}else{ $brt=0; } echo $brt; ?></td>
      <td><?php echo $rowd['loading']; ?></td>
      <td><?php echo $rowd['proses']; ?></td>
      <td><?php echo cekDesimal($rowd['target']); ?></td>
      <td bgcolor="<?php if($rowd['jjm']=="lebih"){echo"yellow";} ?>"><?php if($rowd['lama_proses']!=""){echo $rowd['jam']." Jam ".$rowd['menit']." Menit";}?><br><?php echo $rowd['sts_hasil']; ?></td>
      <td bgcolor="<?php if($rowd['jjm']=="lebih"){echo"yellow";} ?>"><?php echo $rowd['analisa'];?><br><?php if($rowd['lama_stop_menit'] !=""){$jam=floor(round($rowd['lama_stop_menit'])/60);$menit=round($rowd['lama_stop_menit'])%60; echo $jam." Jam ".$menit." Menit";}?></td>
      <td><?php if($overtime>0){echo $hours." Jam ".$min." Menit";}else{echo "0";}?></td>
      <td><?php echo $rowd['k_resep'];?></td>
      <td><?php if($rowd['ket_status']==""){echo "";}else if($rowd['ket_status']!="MC Stop"){if($rowd['resep']=="Baru"){echo"R.B";}else{echo"R.L";}}?></td>
    </tr>
     <?php 
	 $totrol = $totrol+$rol;
	 $totberat = $totberat+$brt;
	 $no++;} ?>
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
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">Total</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99"><?php echo $totrol;?></th>
      <th bgcolor="#99FF99"><?php echo $totberat;?></th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
	  <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
	  <td bgcolor="#99FF99">&nbsp;</td>	
    </tr>
</table>
</body>