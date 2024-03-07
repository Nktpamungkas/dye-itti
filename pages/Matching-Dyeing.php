<?PHP
	ini_set("error_reporting", 1);
	session_start();
	include "koneksi.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Matching Dyeing</title>
</head>

<body>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
                    <div class="col-sm-2">
                        <a href="?p=Form-Matching-Dyeing" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambaha</a>
                    </div>
                    <div class="col-sm-4">
						<form action="" method="POST">
							<input type="date" name="tgl1" class="input-sm" value="<?= $tgl1; ?>"> S/D
							<input type="date" name="tgl2" class="input-sm" value="<?= $tgl2; ?>">
							<button type="submit" class="btn btn-primary btn-sm" name="sort" ><i class="fa fa-search"></i> Sort</button>
						</form>
					</div>
				</div>
				<div class="box-body">
					<table id="example1" class="table table-bordered table-hover table-striped" width="100%">
						<thead class="bg-blue">
							<tr>
								<th width="26"><div align="center">No.</div></th>
								<th width="26"><div align="center">No. Kartu Kerja</div></th>
								<th width="26"><div align="center">No. Demand</div></th>
								<th width="26"><div align="center">Pelanggan</div></th>
								<th width="26"><div align="center">Buyer</div></th>
								<th width="26"><div align="center">No. Order</div></th>
								<th width="26"><div align="center">Jenis Kain</div></th>
								<th width="26"><div align="center">Warna</div></th>
								<th width="26"><div align="center">Jam Terima</div></th>
								<th width="26"><div align="center">Operator Penerima</div></th>
								<th width="26"><div align="center">Jam Proses</div></th>
								<th width="26"><div align="center">Operator Matcher</div></th>
								<th width="98"><div align="center">Action</div></th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                $tgl1	= $_POST['tgl1'];
                                $tgl2	= $_POST['tgl2'];
                        
                                if($tgl1 && $tgl2){
                                    $_sortTgl = "DATE_FORMAT( SUBSTR(createdatetime, 1,10), '%Y-%m-%d' ) BETWEEN '$tgl1' AND '$tgl2'";
                                }else{
                                    $_sortTgl = "SUBSTR(createdatetime, 1,10) BETWEEN DATE_SUB(SUBSTR(NOW(), 1,10), INTERVAL 1 DAY) AND SUBSTR(NOW(), 1,10)";
                                }
                                $q_matching_dye    = mysqli_query($con, "SELECT * FROM tbl_matching_dyeing WHERE $_sortTgl ORDER BY id DESC");
                                $no = 1;
                            ?>
                            <?php while ($row_matching_dye = mysqli_fetch_array($q_matching_dye)) { ?>
                                <tr bgcolor="antiquewhite">
                                    <td align="center"><?= $no++; ?></td>
                                    <td align="center"><?= $row_matching_dye['nokk'] ?></td>
                                    <td align="center"><?= $row_matching_dye['nodemand'] ?></td>
                                    <td align="center"><?= $row_matching_dye['langganan'] ?></td>
                                    <td align="center"><?= $row_matching_dye['buyer'] ?></td>
                                    <td align="center"><?= $row_matching_dye['no_order'] ?></td>
                                    <td align="center"><?= $row_matching_dye['jenis_kain'] ?></td>
                                    <td align="center"><?= $row_matching_dye['warna'] ?></td>
                                    <td align="center"><?= $row_matching_dye['jam_terima'] ?></td>
                                    <td align="center"><?= $row_matching_dye['operator_penerima'] ?></td>
                                    <td align="center"><?= $row_matching_dye['createdatetime_proses'] ?></td>
                                    <td align="center"><?= $row_matching_dye['operator_matcher'] ?></td>
                                    <td>
										<div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#matching_dyeing<?= $row_matching_dye['id'] ?>">
                                                <i class="fa fa-exclamation-triangle" data-toggle="tooltip" data-placement="top" title="Matching Dyeing"></i> Matching Dyeing
                                            </button>
                                            <br>
                                            <a href="pages/cetak/reports-form-matching-print.php?&id=<?= $row_matching_dye['id']; ?>" class="btn btn-warning btn-xs" target="_blank" data-toggle="tooltip" data-html="true" title="Print Form Matching">
                                                <i class="fa fa-print"></i> Print Form Matching
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="matching_dyeing<?= $row_matching_dye['id'] ?>"  role="dialog" aria-labelledby="cekresep" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="exampleModalLabel">MATCHING DYEING</h3>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?= $row_matching_dye['id'] ?>" name="id">
                                                            <label class="col-sm-4 control-label">Pemberi Resep</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control select2" style="width: 100%" name="pemberi_resep">
                                                                    <option selected disabled value="-">Dipilih</option>
                                                                    <?php
                                                                        $q_staff = mysqli_query($con, "SELECT * FROM tbl_staff ");
                                                                    ?>
                                                                    <?php while ($row_staff 	= mysqli_fetch_array($q_staff)) { ?>
                                                                        <option value="<?= $row_staff['nama']; ?>" <?php if($row_staff['nama'] == $row_matching_dye['pemberi_resep']){ echo "SELECTED"; } ?>><?= $row_staff['nama']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?= $row_matching_dye['id'] ?>" name="id">
                                                            <label class="col-sm-4 control-label">Acc Resep</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control select2" style="width: 100%" name="acc_resep">
                                                                    <option selected disabled value="-">Dipilih</option>
                                                                    <?php
                                                                        $q_staff = mysqli_query($con, "SELECT * FROM tbl_staff ");
                                                                    ?>
                                                                    <?php while ($row_staff 	= mysqli_fetch_array($q_staff)) { ?>
                                                                        <option value="<?= $row_staff['nama']; ?>" <?php if($row_staff['nama'] == $row_matching_dye['acc_resep']){ echo "SELECTED"; } ?>><?= $row_staff['nama']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="nokk" class="col-sm-4 control-label">Oke ke</label>
                                                            <div class="col-sm-8">
                                                                <select name="ok_ke" class="form-control select2" style="width: 100%">
                                                                    <option value="1" <?php if('1' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>1</option>
                                                                    <option value="2" <?php if('2' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>2</option>
                                                                    <option value="3" <?php if('3' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>3</option>
                                                                    <option value="4" <?php if('4' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>4</option>
                                                                    <option value="5" <?php if('5' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>5</option>
                                                                    <option value="6" <?php if('6' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>6</option>
                                                                    <option value="7" <?php if('7' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>7</option>
                                                                    <option value="8" <?php if('8' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>8</option>
                                                                    <option value="9" <?php if('9' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>9</option>
                                                                    <option value="10" <?php if('10' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>10</option>
                                                                    <option value="11" <?php if('11' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>11</option>
                                                                    <option value="12" <?php if('12' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>12</option>
                                                                    <option value="13" <?php if('13' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>13</option>
                                                                    <option value="14" <?php if('14' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>14</option>
                                                                    <option value="15" <?php if('15' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>15</option>
                                                                    <option value="16" <?php if('16' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>16</option>
                                                                    <option value="17" <?php if('17' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>17</option>
                                                                    <option value="18" <?php if('18' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>18</option>
                                                                    <option value="19" <?php if('19' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>19</option>
                                                                    <option value="20" <?php if('20' == $row_matching_dye['ok_ke']){ echo "SELECTED"; } ?>>20</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <input type="hidden" value="<?= $row_matching_dye['id'] ?>" name="id">
                                                            <label class="col-sm-4 control-label">Operator Matcher</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control select2" style="width: 100%" name="operator_matcher">
                                                                    <option selected disabled value="-">Dipilih</option>
                                                                    <?php
                                                                        $q_staff = mysqli_query($con, "SELECT * FROM tbl_staff ");
                                                                    ?>
                                                                    <?php while ($row_staff 	= mysqli_fetch_array($q_staff)) { ?>
                                                                        <option value="<?= $row_staff['nama']; ?>" <?php if($row_staff['nama'] == $row_matching_dye['operator_matcher']){ echo "SELECTED"; } ?>><?= $row_staff['nama']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="nokk" class="col-sm-4 control-label">Note</label>
                                                            <div class="col-sm-8">
                                                                <textarea class="form-control" name="note"><?= $row_matching_dye['note']; ?></textarea>
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
</body>
</html>
<?php
	if ($_POST['save'] == "save") {
        $q_simpan   = mysqli_query($con, "UPDATE tbl_matching_dyeing SET 
                                                pemberi_resep = '$_POST[pemberi_resep]',
                                                acc_resep = '$_POST[acc_resep]',
                                                ok_ke = '$_POST[ok_ke]',
                                                operator_matcher = '$_POST[operator_matcher]',
                                                note = '$_POST[note]',
                                                createdatetime_proses = now()
                                            WHERE  
                                                id = '$_POST[id]'");
        if($q_simpan){
            echo "<script>swal({
                    title: 'Data Tersimpan',   
                    text: 'Klik Ok untuk input data kembali',
                    type: 'success',
                    }).then((result) => {
                    if (result.value) {
                        window.location.href='?p=Matching-Dyeing'; 
                    }
                });</script>";
        }
    }
?>
