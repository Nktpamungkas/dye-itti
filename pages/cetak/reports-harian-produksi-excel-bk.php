<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=report-produksi-".substr($_GET['awal'],0,10).".xls");//ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php 
$host1="10.0.0.4";
$username1="timdit";
$password1="4dm1n";
$db_name1="TM";
 set_time_limit(600);
	$conn1=mssql_connect($host1,$username1,$password1) or die ("Sorry our web is under maintenance. Please visit us later");
	$db=mssql_select_db($db_name1) or die ("Under maintenance");
include "../../koneksiLAB.php";
db_connect($db_name);
$con=mysql_connect("10.0.0.10","dit","4dm1n");
$db=mysql_select_db("db_dying",$con)or die("Gagal Koneksi");
include "../../tgl_indo.php";
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
//-
$qTgl=mysql_query("SELECT DATE_FORMAT(now(),'%Y-%m-%d') as tgl_skrg, DATE_FORMAT(now(),'%Y-%m-%d')+ INTERVAL 1 DAY as tgl_besok");
$rTgl=mysql_fetch_array($qTgl);
$Awal=$_GET[awal];
$Akhir=$_GET[akhir];
if($Awal==$Akhir){$TglPAl=substr($Awal,0,10);$TglPAr=substr($Akhir,0,10);}else{ $TglPAl=$Awal;$TglPAr=$Akhir; }
$shft=$_GET[shft];
?>
<body>
	
<strong>Periode: <?php echo $TglPAl; ?> s/d <?php echo $TglPAr; ?></strong><br>
<strong>Shift: <?php echo $shft; ?></strong><br />
<table width="100%" border="1">
    <tr>
      <th rowspan="2" bgcolor="#99FF99">NO.</th>
      <th rowspan="2" bgcolor="#99FF99">SHIFT</th>
      <th rowspan="2" bgcolor="#99FF99">NO MC</th>
      <th rowspan="2" bgcolor="#99FF99">KAPASITAS</th>
      <th rowspan="2" bgcolor="#99FF99">LANGGANAN</th>
      <th rowspan="2" bgcolor="#99FF99">BUYER</th>
      <th rowspan="2" bgcolor="#99FF99">NO ORDER</th>
      <th rowspan="2" bgcolor="#99FF99">JENIS KAIN</th>
      <th rowspan="2" bgcolor="#99FF99">WARNA</th>
      <th rowspan="2" bgcolor="#99FF99">K.W</th>
      <th rowspan="2" bgcolor="#99FF99">LOT</th>
      <th rowspan="2" bgcolor="#99FF99">ROLL</th>
      <th rowspan="2" bgcolor="#99FF99">QTY</th>
      <th rowspan="2" bgcolor="#99FF99">PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">% LOADING</th>
      <th rowspan="2" bgcolor="#99FF99">L:R</th>
      <th rowspan="2" bgcolor="#99FF99">PEMAKAIAN AIR</th>
      <th rowspan="2" bgcolor="#99FF99">KETERANGAN</th>
      <th rowspan="2" bgcolor="#99FF99">K.R</th>
      <th rowspan="2" bgcolor="#99FF99">R.B/R.L</th>
      <th rowspan="2" bgcolor="#99FF99">STATUS</th>
      <th rowspan="2" bgcolor="#99FF99">DYESTUFF</th>
      <th rowspan="2" bgcolor="#99FF99">ENERGY</th>
      <th colspan="4" bgcolor="#99FF99">JAM PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">LAMA PROSES</th>
      <th rowspan="2" bgcolor="#99FF99">POINT</th>
      <th colspan="4" bgcolor="#99FF99">STOP MESIN</th>
      <th rowspan="2" bgcolor="#99FF99">LAMA STOP</th>
      <th rowspan="2" bgcolor="#99FF99">KODE STOP</th>
      <th rowspan="2" bgcolor="#99FF99">Acc Keluar Kain</th>
      <th rowspan="2" bgcolor="#99FF99">Operator</th>
      <th rowspan="2" bgcolor="#99FF99">NoKK</th>
      <th rowspan="2" bgcolor="#99FF99">No Warna</th>
      <th rowspan="2" bgcolor="#99FF99">Lebar</th>
      <th rowspan="2" bgcolor="#99FF99">Gramasi</th>
      <th rowspan="2" bgcolor="#99FF99">Carry Over</th>
      <th rowspan="2" bgcolor="#99FF99">ACUAN QUALITY</th>
      <th rowspan="2" bgcolor="#99FF99">ITEM</th>
      <th rowspan="2" bgcolor="#99FF99">NO PO</th>
      <th rowspan="2" bgcolor="#99FF99">TGL DELIVERY</th>
    </tr>
    <tr>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">IN</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">OUT</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">JAM</th>
      <th bgcolor="#99FF99">TGL</th>
      <th bgcolor="#99FF99">S/D</th>
    </tr>
    <?php
	$Awal=$_GET[awal];
	$Akhir=$_GET[akhir];
	$Tgl=substr($Awal,0,10);	
	if($Awal!=$Akhir){ $Where=" DATE_FORMAT(c.tgl_update, '%Y-%m-%d %H:%i') BETWEEN '$Awal' AND '$Akhir' ";}else{
		$Where=" DATE_FORMAT(c.tgl_update, '%Y-%m-%d')='$Tgl' ";
	}
	if($_GET[shft]=="ALL"){$shft=" ";}else{$shft=" if(ISNULL(a.g_shift),c.g_shift,a.g_shift)='$_GET[shft]' AND ";}
		$sql=mysql_query("SELECT x.*,a.no_mesin as mc FROM tbl_mesin a
  LEFT JOIN
  (SELECT
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
	c.l_r,
	c.rol,
	c.bruto,
	c.pakai_air,
	DATE_FORMAT(c.tgl_buat,'%Y-%m-%d') as tgl_in,
	DATE_FORMAT(a.tgl_buat,'%Y-%m-%d') as tgl_out,
	DATE_FORMAT(c.tgl_buat,'%H:%i') as jam_in,
	DATE_FORMAT(a.tgl_buat,'%H:%i') as jam_out,
	if(ISNULL(a.g_shift),c.g_shift,a.g_shift) as shft,
	a.operator_keluar,
	a.k_resep,
	a.status,
	b.nokk,
	b.no_warna,
	b.lebar,
	b.gramasi,
	c.carry_over,
	b.no_hanger,
	b.no_item,
	b.po,	
	b.tgl_delivery
FROM
	tbl_schedule b
	LEFT JOIN  tbl_montemp c ON c.id_schedule = b.id
	LEFT JOIN tbl_hasilcelup a ON a.id_montemp=c.id	
WHERE
	$shft 
	$Where
	)x ON a.no_mesin=x.no_mesin ORDER BY a.no_mesin");
  
   $no=1;
   
   $c=0;
   
    while($rowd=mysql_fetch_array($sql)){
		   ?>
      <tr valign="top">
      <td><?php echo $no;?></td>
      <td><?php echo $rowd['shft'];?></td>
      <td>'<?php echo $rowd['mc'];?></td>
      <td><?php echo $rowd['kapasitas'];?></td>
      <td><?php echo $rowd['langganan'];?></td>
      <td><?php echo $rowd['buyer'];?></td>
      <td><?php echo $rowd['no_order']; ?></td>
      <td><?php echo $rowd['jenis_kain'];?></td>
      <td><?php echo $rowd['warna']; ?></td>
      <td><?php echo $rowd['kategori_warna']; ?></td>
      <td>'<?php echo $rowd['lot']; ?></td>
      <td><?php if($rowd['tgl_out']!=""){$rol=$rowd['rol'];}else{ $rol=" "; } echo $rol; ?></td>
      <td><?php if($rowd['tgl_out']!=""){$brt=$rowd['bruto'];}else{ $brt=" "; } echo $brt; ?></td>
      <td><?php echo $rowd['proses']; ?></td>
      <td><?php echo $rowd['loading']; ?></td>
      <td>'<?php echo $rowd['l_r']; ?></td>
      <td><?php echo $rowd['pakai_air']; ?></td>
      <td><?php echo $rowd['ket']."".$rowd['status']; ?></td>
      <td><?php echo $rowd['k_resep'];?></td>
      <td><?php if($rowd['ket_status']==""){echo "";}else if($rowd['ket_status']!="MC Stop"){if($rowd['resep']=="Baru"){echo"R.B";}else{echo"R.L";}}?></td>
      <td><?php echo $rowd['sts']; ?></td>
      <td><?php echo $rowd['dyestuff'];?></td>
      <td><?php echo $rowd['energi'];?></td>
      <td><?php echo $rowd['tgl_in']; ?></td>
      <td><?php echo $rowd['jam_in']; ?></td>
      <td><?php echo $rowd['tgl_out']; ?></td>
      <td><?php echo $rowd['jam_out'];?></td>
      <td><?php if($rowd['lama_proses']!=""){echo $rowd['jam']." Jam ".$rowd['menit']." Menit";}?></td>
      <td><?php echo $rowd['point'];?></td>
      <td><?php echo $rowd['t_mulai']; ?></td>
      <td><?php echo $rowd['j_mulai'];?></td>
      <td><?php echo $rowd['t_selesai']; ?></td>
      <td><?php echo $rowd['j_selesai'];?></td>
      <td><?php if($rowd['lama_stop_menit'] !=""){$jam=floor(round($rowd[lama_stop_menit])/60);$menit=round($rowd[lama_stop_menit])%60; echo $jam." Jam ".$menit." Menit";}?></td>
      <td><?php echo $rowd['kd_stop'];?></td>
      <td><?php echo $rowd['acc_keluar'];?></td>
      <td><?php echo $rowd['operator_keluar'];?></td>
      <td>'<?php echo $rowd['nokk'];?></td>
      <td><?php echo $rowd['no_warna'];?></td>
      <td><?php echo $rowd['lebar'];?></td>
      <td><?php echo $rowd['gramasi'];?></td>
      <td><?php echo $rowd['carry_over'];?></td>
      <td><?php echo $rowd['no_hanger'];?></td>
      <td><?php echo $rowd['no_item'];?></td>
      <td><?php echo $rowd['po'];?></td>
      <td><?php echo $rowd['tgl_delivery'];?></td>
    </tr>
     <?php 
	 $totrol +=$rol;
	 $totberat +=$brt;
	 $no++;} ?>
    <tr>
       <td colspan="8" bgcolor="#99FF99">&nbsp;</td>
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
      <th bgcolor="#99FF99"><?php echo $totrol;?></th>
      <th bgcolor="#99FF99"><?php echo $totberat;?></th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
      <th bgcolor="#99FF99">&nbsp;</th>
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
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
      <td bgcolor="#99FF99">&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th colspan="3">&nbsp;</th>
    <th colspan="9">DIBUAT OLEH:</th>
    <th colspan="11">DIPERIKSA OLEH:</th>
    <th colspan="23">DIKETAHUI OLEH:</th>
  </tr>
  <tr>
    <td colspan="3">NAMA</td>
    <td colspan="9">&nbsp;</td>
    <td colspan="11">&nbsp;</td>
    <td colspan="23">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">JABATAN</td>
    <td colspan="9">&nbsp;</td>
    <td colspan="11">&nbsp;</td>
    <td colspan="23">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">TANGGAL</td>
    <td colspan="9">&nbsp;</td>
    <td colspan="11">&nbsp;</td>
    <td colspan="23">&nbsp;</td>
  </tr>
  <tr>
    <td height="60" colspan="3" valign="top">TANDA TANGAN</td>
    <td colspan="9">&nbsp;</td>
    <td colspan="11">&nbsp;</td>
    <td colspan="23">&nbsp;</td>
  </tr>
</table>
</body>