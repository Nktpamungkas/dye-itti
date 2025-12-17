<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
if(isset($_POST['ubah'])){ 
	extract($_POST);
	//tangkap data array dari form
    $urut       = $_POST['no_urut'];
    $personil   = $_POST['personil'];
    $user_id    = $_SESSION['nama10']; 
    $getdate    = date('Y-m-d H:i:s'); 
    $remote_add = $_SERVER['REMOTE_ADDR']; 
    //foreach
    foreach ($urut as $urut_key => $urut_value) {
        // Ambil data urutan lama dari database
        $q_old = mysqli_query($con, "SELECT * FROM tbl_schedule WHERE id = '$urut_key'");
        $d_old = mysqli_fetch_assoc($q_old);
        $no_urut_lama = $d_old['no_urut'];

        // Cek jika urutan berubah baru lakukan update
        if ($urut_value != $no_urut_lama) {
            $nama_personil = mysqli_real_escape_string($con, $personil[$urut_key]);

            $query = "UPDATE `tbl_schedule` 
                        SET `no_urut` =  '$urut_value',
                            `personil`=  '$nama_personil'
                        WHERE `id` = '$urut_key' LIMIT 1;";
            $result = mysqli_query($con, $query);

            if (!$result) {
                die('Gagal update: ' . mysqli_error($con));
            }else{
                $sqlLog = mysqli_query($con, "INSERT INTO tbl_log_mc_schedule SET 
                        id_schedule = '$urut_key',
                        nodemand    = '{$d_old['nodemand']}',
                        nokk        = '{$d_old['nokk']}',
                        no_mc       = '{$d_old['no_mesin']}', 
                        no_urut     = '$urut_value', 
                        no_sch      = '{$d_old['no_sch']}', 
                        user_update = '$user_id',
                        date_update = '$getdate',
                        ip_update   = '$remote_add',
                        col_update  = 'UPDATE_NO_URUT'
                    ");
                echo " <script>window.location='?p=Schedule';</script>";
            }
        } 
        // else {
        //     echo " <script>window.location='?p=Schedule';</script>";
        // }
    }
}elseif(isset($_POST['ubah_stdtarget'])){
    extract($_POST);
    //tangkap data array dari form
    $urut               = $_POST['no_urut'];
    $personil           = $_POST['personil'];
    $creationdatetime	= date('Y-m-d H:i:s');
    $user_id            = $_SESSION['nama10']; 
    $getdate            = date('Y-m-d H:i:s'); 
    $remote_add         = $_SERVER['REMOTE_ADDR'];
    //foreach
    foreach ($urut as $urut_key => $urut_value) {
        $target = $_POST['target'][$urut_key];
        $nama_personil = mysqli_real_escape_string($con, $personil[$urut_key]);
        $q_old = mysqli_query($con, "SELECT * FROM tbl_schedule WHERE id = '$urut_key'");
        $d_old = mysqli_fetch_assoc($q_old);
        $query = "UPDATE `tbl_schedule` 
                    SET `target`  = '$target',
                        personil_stdtarget = '$nama_personil',
                        lastupdatetime_stdtarget = '$creationdatetime' 
                    WHERE `id` = '$urut_key' LIMIT 1 ;";
        $result = mysqli_query($con, $query);
    }

    if (!$result) {
        die ('cant update:' .mysqli_error($con));
    }else{
        $sqlLog = mysqli_query($con, "INSERT INTO tbl_log_mc_schedule SET 
                        id_schedule = '$urut_key',
                        nodemand    = '{$d_old['nodemand']}',
                        nokk        = '{$d_old['nokk']}',
                        no_mc       = '{$d_old['no_mesin']}', 
                        no_urut     = '{$d_old['no_urut']}', 
                        no_sch      = '{$d_old['no_sch']}', 
                        user_update = '$user_id',
                        date_update = '$getdate',
                        ip_update   = '$remote_add',
                        col_update  = 'UPDATE_STD_TARGET'
                    ");
        echo " <script>window.location='?p=Schedule';</script>";
    }
}		
?>
