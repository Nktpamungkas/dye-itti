<?PHP
	ini_set("error_reporting", 1);
	session_start();
	include "koneksi.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Buka Resep</title>
</head>

<body>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<a href="?p=Form-Buka-Resep" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah</a>
				</div>
				<div class="box-body">
					<table id="example1" class="table table-bordered table-hover table-striped" width="100%">
						<thead class="bg-blue">
							<tr>
								<th width="26"><div align="center">No.</div></th>
								<th width="26"><div align="center">No. Kartu Kerja</div></th>
								<th width="26"><div align="center">No. Demand</div></th>
                                <th width="121"><div align="center">No. Order</div></th>
								<th width="165"><div align="center">Pelanggan</div></th>
								<th width="121"><div align="center">No. Item</div></th>
								<th width="125"><div align="center">Jenis Kain</div></th>
								<th width="88"><div align="center">Warna</div></th>
								<th width="85"><div align="center">Bon Resep 1 <br> Suffix</div></th>
								<th width="85"><div align="center">Bon Resep 2 <br> Suffix 2</div></th>
								<th width="71"><div align="center">Cek Resep</div></th>
								<th width="90"><div align="center">Ket Resep</div></th>
								<th width="98"><div align="center">Action</div></th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                $q_bukaresep    = mysqli_query($con, "SELECT * FROM tbl_bukaresep");
                                $no = 1;
                            ?>
                            <?php while ($row_bukaresep = mysqli_fetch_array($q_bukaresep)) { ?>
                                <?php
                                    $sql_ITXVIEWKK  = db2_exec($conn2, "SELECT * FROM ITXVIEWKK WHERE PRODUCTIONORDERCODE = '$row_bukaresep[nokk]'");
                                    $dt_ITXVIEWKK	= db2_fetch_assoc($sql_ITXVIEWKK);

                                    $sql_pelanggan_buyer 	= db2_exec($conn2, "SELECT TRIM(LANGGANAN) AS PELANGGAN, TRIM(BUYER) AS BUYER FROM ITXVIEW_PELANGGAN 
                                                                                WHERE ORDPRNCUSTOMERSUPPLIERCODE = '$dt_ITXVIEWKK[ORDPRNCUSTOMERSUPPLIERCODE]' AND CODE = '$dt_ITXVIEWKK[PROJECTCODE]'");
                                    $dt_pelanggan_buyer		= db2_fetch_assoc($sql_pelanggan_buyer);

                                    $sql_warna		= db2_exec($conn2, "SELECT DISTINCT TRIM(WARNA) AS WARNA FROM ITXVIEWCOLOR 
                                                                    WHERE ITEMTYPECODE = '$dt_ITXVIEWKK[ITEMTYPEAFICODE]' 
                                                                        AND SUBCODE01 = '$dt_ITXVIEWKK[SUBCODE01]' 
                                                                        AND SUBCODE02 = '$dt_ITXVIEWKK[SUBCODE02]'
                                                                        AND SUBCODE03 = '$dt_ITXVIEWKK[SUBCODE03]' 
                                                                        AND SUBCODE04 = '$dt_ITXVIEWKK[SUBCODE04]'
                                                                        AND SUBCODE05 = '$dt_ITXVIEWKK[SUBCODE05]' 
                                                                        AND SUBCODE06 = '$dt_ITXVIEWKK[SUBCODE06]'
                                                                        AND SUBCODE07 = '$dt_ITXVIEWKK[SUBCODE07]' 
                                                                        AND SUBCODE08 = '$dt_ITXVIEWKK[SUBCODE08]'
                                                                        AND SUBCODE09 = '$dt_ITXVIEWKK[SUBCODE09]' 
                                                                        AND SUBCODE10 = '$dt_ITXVIEWKK[SUBCODE10]'");
                                    $dt_warna		= db2_fetch_assoc($sql_warna);
                                ?>
                                <tr bgcolor="antiquewhite">
                                    <td align="center"><?= $no++; ?></td>
                                    <td align="center"><?= $row_bukaresep['nokk'] ?></td>
                                    <td align="center"><?= $row_bukaresep['nodemand'] ?></td>
                                    <td align="center"><?= TRIM($dt_ITXVIEWKK['PROJECTCODE']) ?></td>
                                    <td align="center"><?= TRIM($dt_pelanggan_buyer['PELANGGAN']) ?></td>
                                    <td align="center"><?= TRIM($dt_ITXVIEWKK['SUBCODE02']).'-'.TRIM($dt_ITXVIEWKK['SUBCODE03']) ?></td>
                                    <td align="center"><?= $dt_ITXVIEWKK['ITEMDESCRIPTION'] ?></td>
                                    <td align="center"><?= $dt_warna['WARNA'] ?></td>
                                    <td align="center"><?= $row_bukaresep['noresep1'].'<br>'.$row_bukaresep['suffix1'] ?></td>
                                    <td align="center"><?= $row_bukaresep['noresep2'].'<br>'.$row_bukaresep['suffix2'] ?></td>
                                    <td align="center"><?= $row_bukaresep['cek_resep']; ?></td>
                                    <td align="center"><?= $row_bukaresep['ket_resep']; ?></td>
                                    <td>
										<div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#cekresep<?= $row_bukaresep['id'] ?>">
                                                <i class="fa fa-exclamation-triangle" data-toggle="tooltip" data-placement="top" title="Cek Resep"></i> Cek Resep
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="cekresep<?= $row_bukaresep['id'] ?>"  role="dialog" aria-labelledby="cekresep" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalLabel">CEK RESEP</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?= $row_bukaresep['id'] ?>" name="id">
                                                            <label for="nokk" class="col-sm-4 control-label">Cek Resep</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control" name="cek_resep">
                                                                    <option disabled selected value="">Dipilih</option>
                                                                    <option value="Resep Ok">Resep Ok</option>
                                                                    <option value="Resep Tidak Ok">Resep Tidak Ok</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="nokk" class="col-sm-4 control-label">Keterangan</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" name="ket_resep"></textarea>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="nokk" class="col-sm-4 control-label">Diperiksa oleh</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control" name="diperiksa_oleh">
                                                                    <option selected disabled value="-">Dipilih</option>
                                                                    <?php
                                                                        $q_staff = mysqli_query($con, "SELECT * FROM tbl_staff WHERE NOT jabatan = 'Operator' AND NOT jabatan = 'Staff' AND NOT jabatan = 'Leader' AND NOT jabatan = 'Colorist' AND NOT jabatan = 'Asst. SPV'");
                                                                    ?>
                                                                    <?php while ($row_staff 	= mysqli_fetch_array($q_staff)) { ?>
                                                                        <option value="<?= $row_staff['nama']; ?>"><?= $row_staff['nama']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary pull-right" name="save" value="save">Simpan <i class="fa fa-save"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                            <?php } ?>
						</tbody>
						<tfoot class="bg-red">
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
<?php
	if ($_POST['save'] == "save") {
        $q_simpan   = mysqli_query($con, "UPDATE tbl_bukaresep SET 
                                                cek_resep = '$_POST[cek_resep]',
                                                ket_resep = '$_POST[ket_resep]',
                                                diperiksa_oleh = '$_POST[diperiksa_oleh]'
                                            WHERE  
                                                id = '$_POST[id]'");
        if($q_simpan){
            echo "<script>swal({
                    title: 'Data Tersimpan',   
                    text: 'Klik Ok untuk input data kembali',
                    type: 'success',
                    }).then((result) => {
                    if (result.value) {
                        window.location.href='?p=buka-resep'; 
                    }
                });</script>";
        }
    }
?>
