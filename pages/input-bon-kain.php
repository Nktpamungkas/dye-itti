<?php
ini_set("error_reporting", 1);
session_start();
include"koneksi.php";
	$qryCek=mysqli_query($con,"SELECT * FROM tbl_gantikain WHERE `id`='$_GET[id]'");
	$rCek=mysqli_fetch_array($qryCek);
	 ?>
<?php
function no_urut($x){
include"koneksi.php";
date_default_timezone_set("Asia/Jakarta");
if($x=="Reject Buyer"){$fk="RB";}else if($x=="Kurang Qty"){$fk="GK";}	
$format = $fk.date("y/m/");
$sql=mysqli_query($con,"SELECT no_bon FROM tbl_bonkain WHERE substr(no_bon,1,10) like '".$format."%' ORDER BY no_bon DESC LIMIT 1 ") or die (mysqli_error());
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_bon'];
$str=substr($d,8,3);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=3-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$nipbr =$format.$Nol.$Urut;
return $nipbr;
}
function orderno($x,$odr){
include"koneksi.php";
date_default_timezone_set("Asia/Jakarta");
if($x=="Reject Buyer"){$fk="GR";}else if($x=="Kurang Qty"){$fk="G";}	
$format = $odr;
$sql=mysqli_query($con,"SELECT no_order FROM tbl_bonkain WHERE no_order='$format' ORDER BY no_order DESC LIMIT 1") or die (mysqli_error());
$d=mysqli_num_rows($sql);
if($d>0){
$r=mysqli_fetch_array($sql);
$d=$r['no_bon'];
$str=substr($d,8,3);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=3-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$nipbr =$format.$Nol.$Urut;
return $nipbr;
}

	if(isset($_POST['save'])){
		$bon=no_urut($_POST['alasan']);
		if($_POST['analisa']=="Reject Buyer"){$order=$rCek['no_order']." GR1";}else if($_POST['analisa']=="Kurang Qty"){$fk= $rCek['no_order']." G1";;}
		$analisa=str_replace("'","''",$_POST['analisa']);	
		$pencegahan=str_replace("'","''",$_POST['pencegahan']);	
		$alasan=str_replace("'","''",$_POST['alasan']);
		$pwar1= strpos($_POST['warna1'], ';');
		$pwar2= strpos($_POST['warna2'], ';');
		$pwar3= strpos($_POST['warna3'], ';');
		$potW1=substr($_POST['warna1'],0,$pwar1);
		$potW2=substr($_POST['warna2'],0,$pwar2);
		$potW3=substr($_POST['warna3'],0,$pwar3);
		$potKK1=substr($_POST['warna1'],$pwar1+1,15);
		$potKK2=substr($_POST['warna2'],$pwar2+1,15);
		$potKK3=substr($_POST['warna3'],$pwar3+1,15);
		$kk1=str_replace("'","''",$potKK1);
		$kk2=str_replace("'","''",$potKK2);
		$kk3=str_replace("'","''",$potKK3);
		$warna1=str_replace("'","''",$potW1);
		$warna2=str_replace("'","''",$potW2);		
		$warna3=str_replace("'","''",$potW3);
		$kg1=str_replace("'","''",$_POST['kg1']);
		$kg2=str_replace("'","''",$_POST['kg2']);		
		$kg3=str_replace("'","''",$_POST['kg3']);
		$pjg1=str_replace("'","''",$_POST['pjg1']);
		$pjg2=str_replace("'","''",$_POST['pjg2']);		
		$pjg3=str_replace("'","''",$_POST['pjg3']);
		$satuan1=str_replace("'","''",$_POST['satuan1']);
		$satuan2=str_replace("'","''",$_POST['satuan2']);		
		$satuan3=str_replace("'","''",$_POST['satuan3']);
		$qry1=mysqli_query($con,"INSERT INTO tbl_bonkain SET
		`id_nsp`='$_GET[id]',
		`no_bon`='$bon',
		`no_order`='$order',
		`alasan`='$alasan',
		`analisa`='$analisa',
		`pencegahan`='$pencegahan',
		`nokk1`='$kk1',
		`nokk2`='$kk2',
		`nokk3`='$kk3',
		`warna1`='$warna1',
		`warna2`='$warna2',
		`warna3`='$warna3',
		`kg1`='$kg1',
		`kg2`='$kg2',
		`kg3`='$kg3',
		`pjg1`='$pjg1',
		`pjg2`='$pjg2',
		`pjg3`='$pjg3',
		`satuan1`='$satuan1',
		`satuan2`='$satuan2',
		`satuan3`='$satuan3',
		`tgl_buat`=now(),
		`tgl_update`=now()
		");	
		if($qry1){	
	echo "<script>swal({
  title: 'Data Telah diSimpan',   
  text: 'Klik Ok untuk input data kembali',
  type: 'success',
  }).then((result) => {
  if (result.value) {
      window.open('pages/cetak/cetak_bon_ganti.php?no_bon=$bon','_blank');
      window.location.href='index1.php?p=input-bon-kain&id=$_GET[id]';
	 
  }
});</script>";
		}
	}
?>	

 <div class="box box-info">
 <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
  <div class="box-header with-border">
    <h3 class="box-title">Formulir Ganti Kain</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <div class="box-body">
	  			<div class="form-group">
                  <label for="alasan" class="col-sm-2 control-label">Alasan</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="alasan" required>
					<option value="">Pilih</option>	
					<option value="Kurang Qty">Kurang Qty</option>	
					</select>
                  </div>
                  </div>
	  			<div class="form-group">
                  <label for="warna1" class="col-sm-2 control-label">Warna 1</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="warna1" required>
					<option value="">Pilih</option>
					<?php $sqlw1=mysqli_query($con,"SELECT warna,nokk FROM tbl_gantikain WHERE no_order='$rCek[no_order]' and no_hanger='$rCek[no_hanger]' GROUP BY warna ORDER BY warna");
						while ($rwarna=mysqli_fetch_array($sqlw1)){ ?>
						<option value="<?php echo $rwarna['warna'].";".$rwarna['nokk'];?>"><?php echo $rwarna['warna'];?></option>
						<?php } ?>
					</select>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="kg1" type="text" class="form-control" id="kg1" value="<?php if($cek>0){echo $rcek['kg1'];} ?>" placeholder="0.00" style="text-align: right;" required>
					<span class="input-group-addon">Kg</span>	
					</div>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="pjg1" type="text" class="form-control" id="pjg1" value="<?php if($cek>0){echo $rcek['pjg1'];} ?>" placeholder="0.00" style="text-align: right;" required>
					<span class="input-group-addon"><select name="satuan1" style="font-size: 12px;" id="satuan1">
								  <option value="Yard" <?php if($rcek['satuan1']=="Yard"){ echo "SELECTED"; }?>>Yard</option>
								  <option value="Meter" <?php if($rcek['satuan1']=="Meter"){ echo "SELECTED"; }?>>Meter</option>
								  <option value="PCS" <?php if($rcek['satuan1']=="PCS"){ echo "SELECTED"; }?>>PCS</option>
							  </select></span>	
					</div>
                  </div>	
                  </div>
	  			<div class="form-group">
                  <label for="warna2" class="col-sm-2 control-label">Warna 2</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="warna2">
					<option value="">Pilih</option>
					<?php $sqlw1=mysqli_query($con,"SELECT warna,nokk FROM tbl_gantikain WHERE no_order='$rCek[no_order]' and no_hanger='$rCek[no_hanger]' GROUP BY warna ORDER BY warna");
						while ($rwarna=mysqli_fetch_array($sqlw1)){ ?>
						<option value="<?php echo $rwarna['warna'].";".$rwarna['nokk'];?>"><?php echo $rwarna['warna'];?></option>
						<?php } ?>	
					</select>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="kg2" type="text" class="form-control" id="kg2" value="<?php if($cek>0){echo $rcek['kg2'];} ?>" placeholder="0.00" style="text-align: right;">
					<span class="input-group-addon">Kg</span>	
					</div>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="pjg2" type="text" class="form-control" id="pjg2" value="<?php if($cek>0){echo $rcek['pjg2'];} ?>" placeholder="0.00" style="text-align: right;">
					<span class="input-group-addon"><select name="satuan2" style="font-size: 12px;" id="satuan2">
								  <option value="Yard" <?php if($rcek['satuan2']=="Yard"){ echo "SELECTED"; }?>>Yard</option>
								  <option value="Meter" <?php if($rcek['satuan2']=="Meter"){ echo "SELECTED"; }?>>Meter</option>
								  <option value="PCS" <?php if($rcek['satuan2']=="PCS"){ echo "SELECTED"; }?>>PCS</option>
							  </select></span>	
					</div>
                  </div>	
                  </div>
	  			<div class="form-group">
                  <label for="warna3" class="col-sm-2 control-label">Warna 3</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="warna3">
					<option value="">Pilih</option>
					<?php $sqlw1=mysqli_query($con,"SELECT warna,nokk FROM tbl_gantikain WHERE no_order='$rCek[no_order]' and no_hanger='$rCek[no_hanger]' GROUP BY warna ORDER BY warna");
						while ($rwarna=mysqli_fetch_array($sqlw1)){ ?>
						<option value="<?php echo $rwarna['warna'].";".$rwarna['nokk'];?>"><?php echo $rwarna['warna'];?></option>
						<?php } ?>	
					</select>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="kg3" type="text" class="form-control" id="kg3" value="<?php if($cek>0){echo $rcek['kg3'];} ?>" placeholder="0.00" style="text-align: right;">
					<span class="input-group-addon">Kg</span>	
					</div>
                  </div>
				  <div class="col-sm-2">
                    <div class="input-group">  
					<input name="pjg3" type="text" class="form-control" id="pjg3" value="<?php if($cek>0){echo $rcek['pjg3'];} ?>" placeholder="0.00" style="text-align: right;">
					<span class="input-group-addon"><select name="satuan3" style="font-size: 12px;" id="satuan3">
								  <option value="Yard" <?php if($rcek['satuan3']=="Yard"){ echo "SELECTED"; }?>>Yard</option>
								  <option value="Meter" <?php if($rcek['satuan3']=="Meter"){ echo "SELECTED"; }?>>Meter</option>
								  <option value="PCS" <?php if($rcek['satuan3']=="PCS"){ echo "SELECTED"; }?>>PCS</option>
							  </select></span>	
					</div>
                  </div>	
                  </div>
	  			<div class="form-group">
                  <label for="analisa" class="col-sm-2 control-label">Analisa</label>
                  <div class="col-sm-6">
                    <textarea name="analisa" class="form-control" id="analisa" placeholder="Analisa"></textarea>
                  </div>
                  </div>
	            <div class="form-group">
                  <label for="pencegahan" class="col-sm-2 control-label">Pencegahan</label>
                  <div class="col-sm-6">
                    <textarea name="pencegahan" class="form-control" id="pencegahan" placeholder="Pencegahan"></textarea>
                  </div>
                  </div>
	  			<div class="form-group hidden">
                  <label for="warna" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-6">
                    <textarea name="warna" class="form-control" id="warna" placeholder=""></textarea>
                  </div>
                </div> 
	  			
	  			
	    <!-- /.box-footer -->
  


</div>
<div class="box-footer">
	<input type="submit" value="Simpan" name="save" id="save" class="btn btn-primary pull-right" >
	 </div>	
</form>	 
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header with-border">
      
		</div>    
<div class="box-body">		
<table id="example3" class="table table-bordered table-hover table-striped nowrap" width="100%">
<thead class="bg-green">
   <tr>
      <th width="48"><div align="center">No</div></th>
	   <th width="149"><div align="center">No Bon</div></th>
      <th width="301"><div align="center">Alasan</div></th>
      <th width="343"><div align="center">Analisa</div></th>
      <th width="331"><div align="center">Pencegahan</div></th>
      <th width="331"><div align="center">Warna</div></th>
      <th width="331"><div align="center">Qty</div></th>
	   <th width="331"><div align="center">Aksi</div></th>
      </tr>
</thead>
<tbody>
  <?php 
  $sql=mysqli_query($con," SELECT * FROM tbl_bonkain WHERE id_nsp='$_GET[id]' ORDER BY no_bon ASC");
  while($r=mysqli_fetch_array($sql)){
	 
		$no++;
		$bgcolor = ($col++ & 1) ? 'gainsboro' : 'antiquewhite';	  
	  
	  	?>
   <tr bgcolor="<?php echo $bgcolor; ?>">
     <td align="center"><?php echo $no; ?></td>
     <td align="center"><a href="#" class="edit_bon" id="<?php echo $r['id'] ?>"><?php echo $r['no_bon']; ?></a></td>
     <td align="center"><?php echo $r['alasan']; ?></td>
     <td align="center"><?php echo $r['analisa']; ?></td>
     <td align="center"><?php echo $r['pencegahan']; ?></td>
     <td align="left" valign="top"><?php if($r['warna1']!=""){echo "1. ".$r['warna1']."<br>";} ?><?php if($r['warna2']!=""){echo "2. ".$r['warna2']."<br>";} ?><?php if($r['warna3']!=""){echo "3. ".$r['warna3']."<br>";} ?></td>
     <td align="right"><?php if($r['kg1']>0){echo "1. ".$r['kg1']." Kg ".$r['pjg1']." ".$r['satuan1'] ."<br>";} ?>
       <?php if($r['kg2']>0){echo "2. ".$r['kg2']." Kg ".$r['pjg2']." ".$r['satuan2'] ."<br>";} ?>
       <?php if($r['kg3']>0){echo "3. ".$r['kg3']." Kg ".$r['pjg3']." ".$r['satuan3'] ."<br>";} ?></td>
     <td align="center"><div class="btn-group"><a href="pages/cetak/cetak_bon_ganti.php?no_bon=<?php echo $r['no_bon'] ?>" class="btn btn-info btn-xs <?php if($_SESSION['akses']=='biasa'){ echo "disabled"; } ?>" target="_blank"><i class="fa fa-print"></i> </a>
     <a href="#" class="btn btn-danger btn-xs <?php if($_SESSION['akses']=='biasa'){ echo "disabled"; } ?>" onclick="confirm_delete('index1.php?p=hapusdatabon&id=<?php echo $r['id'] ?>');"><i class="fa fa-trash"></i> </a></div></td>
     </tr>   
   <?php 
	  $tpersen+=$r['persen'];
  	} 
   ?>
   </tbody>   
</table> 
	<div id="KodeEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
	<div id="PersenEdit" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
	  </div> 
	<div id="EditBon" class="modal fade modal-3d-slit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
</div>
    </div>
	</div>
</div>
<div class="modal fade" id="modal_del" tabindex="-1" >
  <div class="modal-dialog modal-sm" >
    <div class="modal-content" style="margin-top:100px;">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" style="text-align:center;">Are you sure to delete all data ?</h4>
      </div>

      <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
        <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
         <script type="text/javascript">
    function confirm_delete(delete_url)
    {
      $('#modal_del').modal('show', {backdrop: 'static'});
      document.getElementById('delete_link').setAttribute('href' , delete_url);
    }
</script>
