<?PHP
	ini_set("error_reporting", 1);
	session_start();
	include "koneksi.php";
    $Awal   = isset($_POST['awal']) ? $_POST['awal'] : '';
    $Akhir  = isset($_POST['akhir']) ? $_POST['akhir'] : '';
    $GShift  = isset($_POST['gshift']) ? $_POST['gshift'] : '';

    if($GShift == 'ALL'){
        $where_gshift = "";
    }else{
        $where_gshift = "AND gshift = '$GShift'";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>laporan Harian Buka Resep</title>
</head>

<body>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"> Filter Laporan Harian Buka Resep</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <form method="post" enctype="multipart/form-data" name="form1" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                    <input name="awal" type="text" class="form-control pull-right" id="datepicker" placeholder="Tanggal Awal" value="<?php echo $Awal; ?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                                    <input name="akhir" type="text" class="form-control pull-right" id="datepicker1" placeholder="Tanggal Akhir" value="<?php echo $Akhir;  ?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <select name="gshift" class="form-control pull-right">
                                    <option value="ALL">ALL</option>
                                    <option value="A" <?php if ($GShift == "A") { echo "SELECTED"; } ?>>A</option>
                                    <option value="B" <?php if ($GShift == "B") { echo "SELECTED"; } ?>>B</option>
                                    <option value="C" <?php if ($GShift == "C") { echo "SELECTED"; } ?>>C</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block btn-social btn-linkedin btn-sm" name="submit" style="width: 60%">Search <i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <?php if(isset($_POST['submit'])) : ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Data Laporan Buka Resep</h3><br><br>
                                </div>
                                <div class="box-body">
                                    <table id="example1" class="table table-bordered table-hover table-striped" width="100%">
                                        <thead class="bg-blue">
                                            <tr>
                                                <th width="26"><div align="center">No.</div></th>
                                                <th width="26"><div align="center">TGL</div></th>
                                                <th width="26"><div align="center">Buka Resep</div></th>
                                                <th width="26"><div align="center">Ok</div></th>
                                                <th width="26"><div align="center">Tidak Ok</div></th>
                                                <th width="26"><div align="center">Belum Diperiksa</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $q_bukaresep    = mysqli_query($con, "SELECT
                                                                                        DATE(br1.createdatetime) AS TGL,
                                                                                        COUNT(br1.nokk) AS buka_resep
                                                                                    FROM
                                                                                        tbl_bukaresep br1
                                                                                    WHERE 
                                                                                        DATE(br1.createdatetime) BETWEEN '$Awal' AND '$Akhir' $where_gshift
                                                                                    GROUP BY 
                                                                                        DATE(br1.createdatetime)");
                                                $no = 1;
                                            ?>
                                            <?php while ($row_bukaresep = mysqli_fetch_array($q_bukaresep)) { ?>
                                                <?php
                                                    $q_bukaresep_ok     = mysqli_query($con, "SELECT
                                                                                                DATE(createdatetime) AS TGL,
                                                                                                COUNT(nokk) AS ok
                                                                                            FROM
                                                                                                tbl_bukaresep 
                                                                                            WHERE 
                                                                                                cek_resep = 'Resep Ok' AND DATE(createdatetime) = '$row_bukaresep[TGL]'
                                                                                            GROUP BY 
                                                                                                DATE(createdatetime)");
                                                    $row_bukaresep_ok   = mysqli_fetch_assoc($q_bukaresep_ok);
                                                    
                                                    $q_bukaresep_tidakok     = mysqli_query($con, "SELECT
                                                                                                DATE(createdatetime) AS TGL,
                                                                                                COUNT(nokk) AS tidak_ok
                                                                                            FROM
                                                                                                tbl_bukaresep 
                                                                                            WHERE 
                                                                                                cek_resep = 'Resep Tidak Ok' AND DATE(createdatetime) = '$row_bukaresep[TGL]'
                                                                                            GROUP BY 
                                                                                                DATE(createdatetime)");
                                                    $row_bukaresep_tidakok   = mysqli_fetch_assoc($q_bukaresep_tidakok);
                                                    
                                                    $q_bukaresep_blmdiperiksa     = mysqli_query($con, "SELECT
                                                                                                            DATE(createdatetime) AS TGL,
                                                                                                            COUNT(nokk) AS belum_diperiksa
                                                                                                        FROM
                                                                                                            tbl_bukaresep
                                                                                                        WHERE 
                                                                                                            cek_resep is null AND DATE(createdatetime) = '$row_bukaresep[TGL]'
                                                                                                        GROUP BY 
                                                                                                            DATE(createdatetime)");
                                                    $row_bukaresep_blmdiperiksa   = mysqli_fetch_assoc($q_bukaresep_blmdiperiksa);
                                                ?>
                                                <tr bgcolor="antiquewhite">
                                                    <td align="center"><?= $no++; ?></td>
                                                    <td align="center"><?= $row_bukaresep['TGL']; ?></td>
                                                    <td align="center"><?= $row_bukaresep['buka_resep']; ?></td>
                                                    <td align="center"><?= $row_bukaresep_ok['ok']; ?></td>
                                                    <td align="center"><?= $row_bukaresep_tidakok['tidak_ok']; ?></td>
                                                    <td align="center"><?= $row_bukaresep_blmdiperiksa['belum_diperiksa']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot class="bg-red">
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
