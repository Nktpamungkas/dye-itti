<?php
ini_set("error_reporting", 1);
session_start();
include"./../koneksiORGATEX.php";
include"./../koneksi.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
          <head>
            <meta charset="utf-8">
          
            <title>Status Mesin Dyeing ITTI</title>
            <meta name="description" content="Figma htmlGenerator">
            <meta name="author" content="htmlGenerator">
			<meta http-equiv="refresh" content="30">  
            
            <link rel="stylesheet" href="styles.css">
            
            <style>
              /*
                Figma Background for illustrative/preview purposes only.
                You can remove this style tag with no consequence
              */
              body {background: #FFFFFF; }
            </style>
			<link rel="stylesheet" href="./../bower_components/bootstrap/dist/css/bootstrap.min.css">
			<!-- Font Awesome -->
			<link rel="stylesheet" href="./../bower_components/font-awesome/css/font-awesome.min.css">
			<!-- Ionicons -->
			<link rel="stylesheet" href="./../bower_components/Ionicons/css/ionicons.min.css">
			<!-- Theme style -->
			<link rel="stylesheet" href="./../dist/css/AdminLTE.min.css">
			<!-- toast CSS -->
			<link href="./../bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
			<!-- DataTables -->
			<link rel="stylesheet" href="./../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
			<!-- bootstrap datepicker -->
			<link rel="stylesheet" href="./../bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
			<link rel="stylesheet" href="./../dist/css/skins/skin-purple.min.css">
			<link rel="icon" type="image/png" href="./../dist/img/index.ico">
          
          </head>
<?php
function NoMesin($mc)
{
	// Menghubungkan ke database
    include "./../koneksiORGATEX.php"; // Memastikan file koneksi sudah benar

    // Membuat query untuk mengambil data DyelotRefNo dari tabel MachineStatus
    $sql = "SELECT (Case When ms.OnlineState = 1 Then 'ON' When ms.OnlineState = 0 Then 'OFF' End) as [Online State], 
(Case When ms.RunState = 1 Then 'No Batch' When ms.RunState = 2 Then 'Batch Selected'
When ms.RunState = 3 Then 'Batch Running' When ms.RunState = 4 Then 'Controller Stopped' 
When ms.RunState = 5 Then 'Manual Operation' When ms.RunState = 6 Then 'Finished' End) as [Run State] FROM MachineStatus ms WHERE ms.Machine = ?";

    // Menyiapkan statement dengan parameter
    $params = array($mc); // Menyimpan parameter MachineCode
    $stmt = sqlsrv_query($connORG, $sql, $params);

    // Cek apakah query berhasil
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
    }

    // Mengambil hasil query
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC); 
	
	if ($row['Run State']=='No Batch' and $row['Online State']=='OFF'){
		$warnaMc="_n";
	}else if ($row['Run State']=='No Batch' and $row['Online State']=='ON'){
		$warnaMc="_n";	
	}else if($row['Run State']=='Batch Running' and $row['Online State']=='OFF'){	
		$warnaMc="";
	}else if($row['Run State']=='Batch Running' and $row['Online State']=='ON'){	
		$warnaMc="";	
	}else if($row['Run State']=='Controller Stopped' and $row['Online State']=='OFF'){	
		$warnaMc="_s";
	}else if($row['Run State']=='Controller Stopped' and $row['Online State']=='ON'){	
		$warnaMc="_s";
//	}else if($row['Run State']=='Manual Operation' and $row['Online State']=='OFF'){	
//		$warnaMc="bg-yellow";	
//	}else if($row['Run State']=='Manual Operation' and $row['Online State']=='ON'){	
//		$warnaMc="bg-yellow blink_me";
//	}else if($row['Run State']=='Finished' and $row['Online State']=='OFF'){	
//		$warnaMc="bg-red";
//	}else if($row['Run State']=='Finished' and $row['Online State']=='ON'){	
//		$warnaMc="bg-red blink_me";	
	}else{
		
		$warnaMc="_n";
	}
			
	
    // Tutup koneksi
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($connORG);
	 
    return $warnaMc;
}
	function Rajut($mc)
	{
		// Menghubungkan ke database
		include "./../koneksiORGATEX.php"; // Memastikan file koneksi sudah benar

	}

	function Waktu($mc){
	// Menghubungkan ke database
    include "./../koneksiORGATEX.php"; // Memastikan file koneksi sudah benar

    // Membuat query untuk mengambil data Run Time dari tabel MachineStatus
    $sql = "SELECT 
           FLOOR(TimeToEnd / 60) AS Hours,
           TimeToEnd % 60 AS Minutes
        FROM MachineStatus 
        WHERE Machine = ?";

    // Menyiapkan statement dengan parameter
    $params = array($mc); // Menyimpan parameter MachineCode
    $stmt = sqlsrv_query($connORG, $sql, $params);

    // Cek apakah query berhasil
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // Tampilkan error jika query gagal
    }

    // Mengambil hasil query
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		
    // Tutup koneksi
    sqlsrv_free_stmt($stmt);
	
    sqlsrv_close($connORG);	
	
	if(strlen(trim($row['Hours']))==1){
		$jam="0".$row['Hours'];
	}else{
		$jam=$row['Hours'];
	}
	if(strlen(trim($row['Minutes']))==1){
		$menit="0".$row['Minutes'];
	}else{
		$menit=$row['Minutes'];
	}	
	if($row['Hours']==0 and $row['Minutes']==0){
		$wkt="";
	}else{
		$wkt=$jam.":".$menit."<br>".$row1['DyelotRefNo'];
	}
    return $wkt; // Kembalikan nilai DyelotRefNo saja		
    }

?>          
          <body>
            <div class="e7_80">
				<a href="#"><div id="1442" class="e7_78<?php echo NoMesin("1442"); ?> detail_status"></div></a>
				<a href="#"><div id="1108" class="e7_79<?php echo NoMesin("1108"); ?> detail_status"></div></a>
				<a href="#"><div id="1411" class="e7_77<?php echo NoMesin("1411"); ?> detail_status"></div></a>
				<a href="#"><div id="1443" class="e7_76<?php echo NoMesin("1443"); ?> detail_status"></div></a>
				<a href="#"><div id="1444" class="e7_75<?php echo NoMesin("1444"); ?> detail_status"></div></a>
				<a href="#"><div id="1445" class="e7_74<?php echo NoMesin("1445"); ?> detail_status"></div></a>
				<a href="#"><div id="1420" class="e7_73<?php echo NoMesin("1420"); ?> detail_status"></div></a>
				<a href="#"><div id="1107" class="e7_72<?php echo NoMesin("1107"); ?> detail_status"></div></a>
				<a href="#"><div id="1606" class="e7_71<?php echo NoMesin("1606"); ?> detail_status"></div></a>
				<a href="#"><div id="1505" class="e7_70<?php echo NoMesin("1505"); ?> detail_status"></div></a>
				<a href="#"><div id="1104" class="e7_69<?php echo NoMesin("1104"); ?> detail_status"></div></a>
				<a href="#"><div id="1103" class="e7_68<?php echo NoMesin("1103"); ?> detail_status"></div></a>
				<a href="#"><div id="1402" class="e7_67<?php echo NoMesin("1402"); ?> detail_status"></div></a>
				<a href="#"><div id="1401" class="e7_66<?php echo NoMesin("1401"); ?> detail_status"></div></a>
				<div class="e7_65"></div><div  class="e7_64"></div><div  class="e7_63"></div><div  class="e7_62"></div><div  class="e7_61"></div><div  class="e7_60"></div><div  class="e7_59"></div><div  class="e7_58"></div><div  class="e7_57"></div><div  class="e7_56"></div><div  class="e7_55"></div><div  class="e7_54"></div><div  class="e7_53"></div>		  
			  </div>
		  
	<div>
	  
	</div>		  
	<div id="CekDetailStatus" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

	</body>
	<!-- Tooltips -->
	<!-- jQuery 3 -->
	<script src="./../bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="./../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="./../dist/js/adminlte.min.js"></script>

	<!-- DataTables -->
	<script src="./../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="./../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap datepicker -->
	<script src="./../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
	<script src="./../bower_components/toast-master/js/jquery.toast.js"></script>
	<!-- Tooltips -->
	<script src="./../../dist/js/tooltips.js"></script>
	<script>
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

	</script>
	<!-- Javascript untuk popup modal Edit-->
	<script type="text/javascript">
		$(document).on('click', '.detail_status', function(e) {
			var m = $(this).attr("id");
			$.ajax({
				url: "./cek-status-mesin-orgatex.php",
				type: "GET",
				data: {
					id: m,
				},
				success: function(ajaxData) {
					$("#CekDetailStatus").html(ajaxData);
					$("#CekDetailStatus").modal('show', {
						backdrop: 'true'
					});
				}
			});
		});

		//            tabel lookup KO status terima
		$(function() {
			$("#lookup").dataTable();
		});

	</script>
	<script>
		$(document).ready(function() {
			"use strict";
			// toat popup js
			$.toast({
				heading: 'Selamat Datang',
				text: 'Dyeing Indo Taichen',
				position: 'bottom-right',
				loaderBg: '#ff6849',
				icon: 'success',
				hideAfter: 3500,
				stack: 6
			})


		});
		$(".tst1").on("click", function() {
			var msg = $('#message').val();
			var title = $('#title').val() || '';
			$.toast({
				heading: 'Info',
				text: msg,
				position: 'top-right',
				loaderBg: '#ff6849',
				icon: 'info',
				hideAfter: 3000,
				stack: 6
			});

		});

	</script>
</html>
