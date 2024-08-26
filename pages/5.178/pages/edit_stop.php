<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if($_POST){ 
	extract($_POST);
	$id     = mysqli_real_escape_string($con,$_POST['id']);
	$stop   = $_POST['tgl_stop']." ".$_POST['jam_stop'];
	$mulai  = $_POST['tgl_mulai']." ".$_POST['jam_mulai'];
	$sisa	= $_POST['sisa_waktu'];
	$qCek=mysqli_query($con,"SELECT * FROM `tbl_montemp` WHERE `id`='$id' LIMIT 1");
	$rCek=mysqli_fetch_array($qCek);
	$lama	= $rCek['sisa_waktu'];
	if($_POST['tgl_mulai']!="" or $_POST['jam_mulai']!=""){
		$qmulai=", `tgl_mulai`='$mulai' , `tgl_target`=ADDDATE('$mulai', INTERVAL '$lama' HOUR_MINUTE ) ";
	}else{ $qmulai=", `sisa_waktu`='$sisa' ";}
				$sqlupdate=mysqli_query($con,"UPDATE `tbl_montemp` SET 
				`tgl_stop`='$stop'
				$qmulai
				WHERE `id`='$id' LIMIT 1");
				echo " <script>window.location='?p=Monitoring-Tempelan';</script>";
				
		}
		

?>
