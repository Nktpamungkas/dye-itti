<?php
$host="10.0.4.4";
$username="timdit";
$password="4dm1n";
$db_name="TM";
$conn=mssql_connect($host,$username,$password) or die ("Sorry our web is under maintenance. Please visit us later");
mssql_select_db($db_name) or die ("Under maintenance");
$con=mysqli_connect("10.0.5.0","dit","4dm1n","db_dying");

// $hostname="10.0.0.21";
// $database = "NOWPRD";
// $user = "db2admin";
// $passworddb2 = "Sunkam@24809";
// $port="25000";
// $conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
// $conn2 = db2_connect($conn_string,'', '');
// if($conn2) {
// }
// else{
//     exit("DB2 Connection failed");
//     }

//$ct=mysql_connect("10.0.6.140","dit","4dm1n");
//$db1=mysql_select_db("dyeing",$ct)or die("Gagal Koneksi Dyeing");