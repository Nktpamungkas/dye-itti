<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if(isset($_POST['ubah'])){ 
	extract($_POST);
	//tangkap data array dari form
    $urut   = $_POST['no_urut'];
	$personil = mysqli_real_escape_string($con, $_POST['personil']);
    //foreach
    foreach ($urut as $urut_key => $urut_value) {
        $query = "UPDATE `tbl_schedule` 
                    SET `no_urut` =  '$urut_value',
                        `personil`=  '$personil'
                    WHERE `id` = '$urut_key' LIMIT 1 ;";
        $result = mysqli_query($con, $query);
    }
    
    if (!$result) {
        die ('cant update:' .mysqli_error());
    }else{
		echo " <script>window.location='?p=Schedule';</script>";
	}
}elseif(isset($_POST['ubah_stdtarget'])){
    extract($_POST);
    //tangkap data array dari form
    $urut               = $_POST['no_urut'];
    $personil           = mysqli_real_escape_string($con, $_POST['personil']);
    $creationdatetime	= date('Y-m-d H:i:s');
    //foreach
    foreach ($urut as $urut_key => $urut_value) {
        $target = $_POST['target'][$urut_key];

        $query = "UPDATE `tbl_schedule` 
                    SET `target`  = '$target',
                        personil_stdtarget = '$personil',
                        lastupdatetime_stdtarget = '$creationdatetime' 
                    WHERE `id` = '$urut_key' LIMIT 1 ;";
        $result = mysqli_query($con, $query);
    }
    
    if (!$result) {
        die ('cant update:' .mysqli_error());
    }else{
        echo " <script>window.location='?p=Schedule';</script>";
    }
}		
?>
