<script>
function aktif(){
		if(document.forms['form1']['kodesm'].value == ""){
		document.form1.waktu_mulai.setAttribute("disabled",true);
		document.form1.waktu_mulai.removeAttribute("required");
		document.form1.waktu_stop.setAttribute("disabled",true);
		document.form1.waktu_stop.removeAttribute("required");
		document.form1.datepicker.setAttribute("disabled",true);
		document.form1.datepicker.removeAttribute("required");	
		document.form1.datepicker3.setAttribute("disabled",true);
		document.form1.datepicker3.removeAttribute("required");						
		}
		else{
		document.form1.waktu_mulai.removeAttribute("disabled");
		document.form1.waktu_mulai.setAttribute("required",true);
		document.form1.waktu_stop.removeAttribute("disabled");
		document.form1.waktu_stop.setAttribute("required",true);	
		document.form1.datepicker.removeAttribute("disabled");
		document.form1.datepicker.setAttribute("required",true);
		document.form1.datepicker3.removeAttribute("disabled");
		document.form1.datepicker3.setAttribute("required",true);	
			
		}
	   	
	}
function aktif1(){
		if(document.forms['form1']['dyestuff'].value == "D"){
		document.form1.suhu_poly.removeAttribute("readonly");
		document.form1.suhu_poly.setAttribute("required",true);
		document.form1.ph_poly.removeAttribute("readonly");
		document.form1.ph_poly.setAttribute("required",true);
		document.form1.k_resep.removeAttribute("disabled");
		document.form1.k_resep.setAttribute("required",true);	
			
	   }else{  
		document.form1.suhu_poly.setAttribute("readonly",true);
		document.form1.suhu_poly.removeAttribute("required");
		document.form1.ph_poly.setAttribute("readonly",true);
		document.form1.ph_poly.removeAttribute("required"); 
		document.form1.k_resep.setAttribute("disabled",true);
		document.form1.k_resep.removeAttribute("required");   
	   }
	}
function aktif2(){if(document.forms['form1']['sts'].value == "1" || document.forms['form1']['sts'].value == "5"){
	document.form1.k_resep.removeAttribute("disabled");
	document.form1.k_resep.setAttribute("required",true);
}else{
	document.form1.k_resep.setAttribute("disabled",true);
		document.form1.k_resep.removeAttribute("required");
}
}	
</script>
<?php
$nokk=$_GET['nokk'];
$sqlCek=mysql_query("SELECT
	a.*,b.id as idm 
FROM
	tbl_schedule a
INNER JOIN tbl_montemp b ON a.id=b.id_schedule	
WHERE
	a.nokk = '$nokk' 
ORDER BY
	a.id DESC 
	LIMIT 1",$con);
$cek=mysql_num_rows($sqlCek);
$rcek=mysql_fetch_array($sqlCek);
$sqlCek1=mysql_query("SELECT
	c.*,a.id as ids,b.id as idm 
FROM
	tbl_schedule a
INNER JOIN tbl_montemp b ON a.id=b.id_schedule
INNER JOIN tbl_hasilcelup c ON b.id=c.id_montemp
WHERE
	a.nokk = '$nokk' and b.status='selesai'
ORDER BY
	a.id DESC 
	LIMIT 1",$con);
$cek1=mysql_num_rows($sqlCek1);
$rcek1=mysql_fetch_array($sqlCek1);
$qryLama=mysql_query("SELECT TIME_FORMAT(timediff(now(),b.tgl_buat),'%H:%i') as lama FROM tbl_schedule a
LEFT JOIN tbl_montemp b ON a.id=b.id_schedule
WHERE b.nokk='$nokk' AND b.status='sedang jalan' ORDER BY a.no_urut ASC");
$rLama=mysql_fetch_array($qryLama);
$sqlCek2=mysql_query("SELECT
   	id,
	if(COUNT(lot)>1,'Gabung Kartu','') as ket_kartu,
	if(COUNT(lot)>1,CONCAT('(',COUNT(lot),'kk',')'),'') as kk,
	GROUP_CONCAT(nokk SEPARATOR ', ') as g_kk,
	no_mesin,
	no_urut,	
	sum(rol) as rol,
	sum(bruto) as bruto
FROM
	tbl_schedule 
WHERE
	NOT `status` = 'selesai' and no_mesin='$rcek[no_mesin]' and no_urut='$rcek[no_urut]'
GROUP BY
	no_mesin,
	no_urut 
ORDER BY
	id ASC");
$cek2=mysql_num_rows($sqlCek2);
$rcek2=mysql_fetch_array($sqlCek2);
if($rcek2['ket_kartu']!=""){$ketsts=$rcek2['ket_kartu']."\n(".$rcek2['g_kk'].")";}else{$ketsts="";}
$sqlCek3=mysql_query("SELECT * FROM tbl_montemp WHERE nokk='$nokk' and not status='selesai' ORDER BY id DESC LIMIT 1",$con);
$cek3=mysql_num_rows($sqlCek3);
$rcek3=mysql_fetch_array($sqlCek3);
?>	
<?php
$Kapasitas	= isset($_POST['kapasitas']) ? $_POST['kapasitas'] : '';
$TglMasuk	= isset($_POST['tglmsk']) ? $_POST['tglmsk'] : '';
$Item		= isset($_POST['item']) ? $_POST['item'] : '';
$Warna		= isset($_POST['warna']) ? $_POST['warna'] : '';
$Langganan	= isset($_POST['langganan']) ? $_POST['langganan'] : '';
?>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="form1">
 <div class="box box-info">
  	<div class="box-header with-border">
    <h3 class="box-title">Input Data Kartu Kerja</h3>
    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
 	<div class="box-body"> 
	  <div class="col-md-6">
		<div class="form-group">
                  <label for="no_po" class="col-sm-3 control-label">No KK</label>
                  <div class="col-sm-4">
				  <input name="nokk" type="text" class="form-control" id="nokk" 
                     onchange="window.location='?p=Form-Celup&nokk='+this.value" value="<?php echo $_GET[nokk];?>" placeholder="No KK" required >
		  </div>
			      <div class="col-sm-4">
				  <input name="id" type="hidden" class="form-control" id="id" value="<?php echo $rcek[idm];?>" placeholder="ID">
		          </div>
        </div>		  
		<div class="form-group">
                  <label for="langganan" class="col-sm-3 control-label">Langganan</label>
                  <div class="col-sm-8">
                    <input name="langganan" type="text" class="form-control" id="langganan" placeholder="Langganan" 
                    value="<?php if($cek>0){echo $rcek[langganan];}else{echo $pelanggan;}?>" readonly="readonly">
                  </div>				   
        </div>
		<div class="form-group">
                  <label for="buyer" class="col-sm-3 control-label">Buyer</label>
                  <div class="col-sm-8">
                    <input name="buyer" type="text" class="form-control" id="buyer" placeholder="Buyer" 
                    value="<?php if($cek>0){echo $rcek[buyer];}else{echo $buyer;}?>" readonly="readonly">
                  </div>				   
        </div>
	    <div class="form-group">
                  <label for="no_order" class="col-sm-3 control-label">No Order</label>
                  <div class="col-sm-4">
                    <input name="no_order" type="text" required class="form-control" id="no_order" placeholder="No Order" 
                    value="<?php if($cek>0){echo $rcek[no_order];}else{if($r[NoOrder]!=""){echo $r[NoOrder];}else if($nokk!=""){echo $cekM[no_order];}} ?>" readonly="readonly">
                  </div>				   
        </div>
	    <div class="form-group">
                  <label for="no_po" class="col-sm-3 control-label">PO</label>
                  <div class="col-sm-5">
                    <input name="no_po" type="text" class="form-control" id="no_po" placeholder="PO" 
                    value="<?php if($cek>0){echo $rcek[po];}else{if($r[PONumber]!=""){echo $r[PONumber];}else if($nokk!=""){echo $cekM[no_po];}} ?>" readonly="readonly" >
                  </div>				   
        </div>
		<div class="form-group">
                  <label for="no_hanger" class="col-sm-3 control-label">No Hanger / No Item</label>
                  <div class="col-sm-3">
                    <input name="no_hanger" type="text" class="form-control" id="no_hanger" placeholder="No Hanger" 
                    value="<?php if($cek>0){echo $rcek[no_hanger];}else{if($r[HangerNo]){echo $r[HangerNo];}else if($nokk!=""){echo $cekM[no_item];}}?>" readonly="readonly">  
                  </div>
				  <div class="col-sm-3">
				  <input name="no_item" type="text" class="form-control" id="no_item" placeholder="No Item" 
                    value="<?php if($rcek[no_item]!=""){echo $rcek[no_item];}else if($r[ProductCode]!=""){echo $r[ProductCode];}else{if($r[HangerNo]){echo $r[HangerNo];}else if($nokk!=""){echo $cekM[no_item];}}?>" readonly="readonly">
				  </div>	
        </div>
	    <div class="form-group">
                  <label for="jns_kain" class="col-sm-3 control-label">Jenis Kain</label>
                  <div class="col-sm-8">
					  <textarea name="jns_kain" readonly="readonly" class="form-control" id="jns_kain" placeholder="Jenis Kain"><?php if($cek>0){echo $rcek[jenis_kain];}else{if($r[ProductDesc]!=""){echo $r[ProductDesc];}else if($nokk!=""){ echo $cekM[jenis_kain]; } }?></textarea>
		  </div>
        </div>
		<div class="form-group">
        <label for="tgl_delivery" class="col-sm-3 control-label">Tgl. Delivery</label>
        <div class="col-sm-4">
          <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="tgl_delivery" type="text" disabled="disabled" required class="form-control pull-right" id="datepicker2" placeholder="0000-00-00" value="<?php if($cek>0){echo $rcek[tgl_delivery];}else{if($r[RequiredDate]!=""){echo date('Y-m-d', strtotime($r[RequiredDate]));}}?>"/>
          </div>
        </div>
	  </div>
		<div class="form-group">
			  <label for="l_g" class="col-sm-3 control-label">L X Grm Permintaan</label>
			  <div class="col-sm-2">
				<input name="lebar" type="text" required class="form-control" id="lebar" placeholder="0" 
				value="<?php if($cek>0){echo $rcek[lebar];}else{echo round($r[Lebar]);} ?>" readonly="readonly">
			  </div>
			  <div class="col-sm-2">
				<input name="grms" type="text" required class="form-control" id="grms" placeholder="0" 
				value="<?php if($cek>0){echo $rcek[gramasi];}else{echo round($r[Gramasi]);} ?>" readonly="readonly">
			  </div>		
		</div>
		<div class="form-group">
			  <label for="warna" class="col-sm-3 control-label">Warna</label>
			  <div class="col-sm-8">
				<input name="warna" type="text" class="form-control" id="warna" placeholder="Warna" 
				value="<?php if($cek>0){echo $rcek[warna];}else{if($r[Color]!=""){echo $r[Color];}else if($nokk!=""){ echo $cekM[warna];} }?>" readonly="readonly">  
			  </div>				   
		</div>
		<div class="form-group">
			  <label for="no_warna" class="col-sm-3 control-label">No Warna</label>
			  <div class="col-sm-8">
				<input name="no_warna" type="text" class="form-control" id="no_warna" placeholder="No Warna" 
				value="<?php if($cek>0){echo $rcek[no_warna];}else{if($r[ColorNo]!=""){echo $r[ColorNo];}else if($nokk!=""){echo $cekM[no_warna];}}?>" readonly="readonly">  
			  </div>				   
		</div>    
		<div class="form-group">
                  <label for="qty_order" class="col-sm-3 control-label">Qty Order</label>
                  <div class="col-sm-3">
					<div class="input-group">  
                    <input name="qty1" type="text" required class="form-control" id="qty1" placeholder="0.00" 
                    value="<?php if($cek>0){echo $rcek[qty_order];}else{echo round($r[BatchQuantity],2);} ?>" readonly="readonly">
					  <span class="input-group-addon">KGs</span></div>  
                  </div>
				  <div class="col-sm-4">
					<div class="input-group">  
                    <input name="qty2" type="text" required class="form-control" id="qty2" placeholder="0.00" style="text-align: right;" 
                    value="<?php if($cek>0){echo $rcek[pjng_order];}else{echo round($r[Quantity],2);} ?>" readonly="readonly">
                    <span class="input-group-addon">
							  <select name="satuan1" disabled="disabled" style="font-size: 12px;">
							    <option value="Yard" <?php if($rcek[satuan_order]=="Yard"){ echo "SELECTED"; }?>>Yard</option>
							    <option value="Meter" <?php if($rcek[satuan_order]=="Meter"){ echo "SELECTED"; }?>>Meter</option>
							    <option value="PCS" <?php if($rcek[satuan_order]=="PCS"){ echo "SELECTED"; }?>>PCS</option>
							  </select>
				      </span>
					</div>	
                  </div>		
        </div>
		<div class="form-group">
                  <label for="lot" class="col-sm-3 control-label">Lot</label>
                  <div class="col-sm-2">
                    <input name="lot" type="text" class="form-control" id="lot" placeholder="Lot" 
                    value="<?php if($cek>0){echo $rcek[lot];}else{if($nomorLot!=""){echo $lotno;}else if($nokk!=""){echo $cekM[lot];} } ?>" readonly="readonly" >
                  </div>				   
        </div>
		<div class="form-group">
			  <label for="jml_bruto" class="col-sm-3 control-label">Rol &amp; Qty</label>
			  <div class="col-sm-2">
				<input name="qty3" type="text" required class="form-control" id="qty3" placeholder="0.00" 
				value="<?php if($cek2>0){echo $rcek2[rol].$rcek2[kk];}else{if($r[RollCount]!=""){echo round($r[RollCount]);}else if($nokk!=""){echo $cekM[jml_roll];}} ?>" readonly="readonly">
			  </div>
			  <div class="col-sm-3">
				<div class="input-group">  
				<input name="qty4" type="text" required class="form-control" id="qty4" placeholder="0.00" style="text-align: right;" 
				value="<?php if($cek2>0){echo $rcek2[bruto];}else{if($r[Weight]!=""){echo round($r[Weight],2);}else if($nokk!=""){echo $cekM[bruto];}} ?>" readonly="readonly">
				<span class="input-group-addon">KGs</span>
				</div>	
			  </div>		
		</div>
	  		<?php if($cek>0 and $_GET[kap]!=""){$loading=round($rcek[bruto]/$_GET[kap],4)*100;}else{if($r[Weight]!="" and $_GET[kap]!=""){$loading=round($r[Weight]/$_GET[kap],4)*100;}else if($nokk!="" and $_GET[kap]!=""){$loading=round($cekM[bruto]/$_GET[kap],4)*100;}} ?>  
		<div class="form-group">
                  <label for="kapasitas" class="col-sm-3 control-label">Kapasitas Mesin</label>
                  <div class="col-sm-3">
			  	    <select name="kapasitas" disabled="disabled" class="form-control">
							  <option value="">Pilih</option>
							  <?php 
							  $sqlKap=mysql_query("SELECT kapasitas FROM tbl_mesin GROUP BY kapasitas ORDER BY kapasitas DESC",$con);
							  while($rK=mysql_fetch_array($sqlKap)){
							  ?>
								  <option value="<?php echo $rK[kapasitas]; ?>" <?php if($rcek[kapasitas]==$rK[kapasitas]){ echo "SELECTED"; }?>><?php echo $rK[kapasitas]; ?> KGs</option>
							 <?php } ?>	  
					  </select>					  
				  </div>
					  
	    </div>
		<?php if($cek>0 and ($rcek[kapasitas]!="" and $rcek[kapasitas]!="0")){$loading=round($rcek[bruto]/$rcek[kapasitas],4)*100;}else{if($r[Weight]!="" and ($rcek[kapasitas]!="" and $rcek[kapasitas]!="0")){$loading=round($r[Weight]/$rcek[kapasitas],4)*100;}else if($nokk!="" and ($rcek[kapasitas]!="" and $rcek[kapasitas]!="0")){$loading=round($cekM[bruto]/$rcek[kapasitas],4)*100;}} ?>  
		<div class="form-group">
                  <label for="no_mc" class="col-sm-3 control-label">No MC</label>
                  <div class="col-sm-2">					  
						  <select name="no_mc" disabled="disabled" class="form-control">
							  	<option value="">Pilih</option>
							  <?php 
							  $sqlKap=mysql_query("SELECT no_mesin FROM tbl_mesin WHERE kapasitas='$rcek[kapasitas]' ORDER BY no_mesin ASC",$con);
							  while($rK=mysql_fetch_array($sqlKap)){
							  ?>
						    <option value="<?php echo $rK[no_mesin]; ?>" <?php if($rcek[no_mesin]==$rK[no_mesin]){ echo "SELECTED"; }?> ><?php echo $rK[no_mesin]; ?></option>
							 <?php } ?>	  
					  </select>
				  </div>
					  
		</div> 
	  </div>
	  		<!-- col --> 
	  <div class="col-md-6">
		<div class="form-group">
                  <label for="sts" class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-5">					  
						  <select name="sts" class="form-control" id="sts" onChange="aktif2();" required>
							  	<option value="">Pilih</option>
							  	<option value="1">OK</option>
							    <option value="5">Celup Poly Dulu-Matching</option>
							    <option value="2">Gagal Proses</option>
							  	<option value="3">Levelling-Matching</option>
							  	<option value="4">Pelunturan-Matching</option>
							  	<option value="6">Scouring Turun</option>
					  </select>
				  </div>
					  
		</div>  
		<div class="form-group">
                  <label for="shift" class="col-sm-3 control-label">Shift</label>
                  <div class="col-sm-2">					  
						  <select name="shift" class="form-control" required>
							  	<option value="">Pilih</option>
							  	<option value="1">1</option>
							    <option value="2">2</option>
							  	<option value="3">3</option>
					  </select>
				  </div>
					  
		</div>
		<div class="form-group">
                  <label for="g_shift" class="col-sm-3 control-label">Group Shift</label>
                  <div class="col-sm-2">					  
						  <select name="g_shift" class="form-control" required>
							  	<option value="">Pilih</option>
							  	<option value="A">A</option>
							    <option value="B">B</option>
							  	<option value="C">C</option>
					  </select>
				  </div>
					  
		</div>
		<div class="form-group">
                  <label for="kodesm" class="col-sm-3 control-label">Kode Stop Mesin</label>
                  <div class="col-sm-2">					  
						  <select name="kodesm" class="form-control" onChange="aktif();" id="kodesm">
							  	<option value="">Pilih</option>
							  	<option value="LM">LM</option>
							  	<option value="KM">KM</option>
							  	<option value="PT">PT</option>
							  	<option value="KO">KO</option>
							  	<option value="AP">AP</option>
							  	<option value="PA">PA</option>
							  	<option value="PM">PM</option>
							  	<option value="GT">GT</option>
							  	<option value="TG">TG</option>
							  	<option value="OK">OK</option>							  	
				      </select>
				  </div>
					  
		</div>  
		<div class="form-group">
                  <label for="mulaism" class="col-sm-3 control-label">Mulai Stop Mesin</label>
			      <div class="col-sm-3">
				  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="waktu_mulai" id="waktu_mulai" placeholder="00:00" disabled>					  
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                   </div>
                  </div>
			</div>	  
                  <div class="col-sm-4">					  
						  <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="mulaism" type="text" class="form-control pull-right" id="datepicker3" placeholder="0000-00-00" value="" disabled/>
          </div>
				  </div>
					  
		</div>		 
		<div class="form-group">
                  <label for="selesaism" class="col-sm-3 control-label">Selesai Stop Mesin</label>
			      <div class="col-sm-3">
				  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="waktu_stop" placeholder="00:00" disabled>
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
			</div>
                  <div class="col-sm-4">					  
						  <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="selesaism" type="text" class="form-control pull-right" id="datepicker" placeholder="0000-00-00" value="" disabled/>
          </div>
				  </div>
					  
		</div>  
		<div class="form-group" hidden="">
                  <label for="point" class="col-sm-3 control-label">Point</label>
                  <div class="col-sm-2">					  
				    <input name="point" type="number" class="form-control" id="point" 
                    value="" placeholder="0">
				  </div>
					  
		</div>
		<div class="form-group">
                  <label for="k_resep" class="col-sm-3 control-label">Kestabilan Resep</label>
                  <div class="col-sm-2">					  
						  <select name="k_resep" class="form-control" id="k_resep" required disabled="disabled">
							    <option value="">Pilih</option>  
							    <option value="-">-</option>
							  	<option value="0x">0x</option>						
							  	<option value="1x">1x</option>
							    <option value="2x">2x</option>
							  	<option value="3x">3x</option>
							    <option value="4x">4x</option>
							  	<option value="5x">5x</option>
					  </select>
				  </div>
					  
		</div>   
		<div class="form-group">
          <label for="acc_keluar" class="col-sm-3 control-label">Acc Keluar Kain </label>
                  <div class="col-sm-5">					  
				    <select name="acc_keluar" class="form-control" required>
							  	<option value="">Pilih</option>
							  <?php 
							  $sqlKap=mysql_query("SELECT nama FROM tbl_staff WHERE jabatan='SPV' or jabatan='Colorist' or jabatan='Asst. Manager' or jabatan='Manager' or jabatan='Senior Manager' or jabatan='DMF' ORDER BY nama ASC",$con);
							  while($rK=mysql_fetch_array($sqlKap)){
							  ?>
								  <option value="<?php echo $rK[nama]; ?>"><?php echo $rK[nama]; ?></option>
							 <?php } ?>	  
					  </select>
				  </div>
					  
	    </div>
		<div class="form-group">
          <label for="operator" class="col-sm-3 control-label">Operator Keluar Kain </label>
                  <div class="col-sm-5">					  
				    <select name="operator" class="form-control" required>
							  	<option value="">Pilih</option>
							  <?php 
							  $sqlKap=mysql_query("SELECT nama FROM tbl_staff WHERE jabatan='Operator' ORDER BY nama ASC",$con);
							  while($rK=mysql_fetch_array($sqlKap)){
							  ?>
								  <option value="<?php echo $rK[nama]; ?>"><?php echo $rK[nama]; ?></option>
							 <?php } ?>	  
					  </select>
				  </div>
					  
	    </div>
		<div class="form-group">
                  <label for="operator_potong" class="col-sm-3 control-label">Operator Potong Celup</label>
                  <div class="col-sm-5">					  
						  <select name="operator_potong" class="form-control" required>
							  	<option value="">Pilih</option>
							  <?php 
							  $sqlKap=mysql_query("SELECT nama FROM tbl_staff WHERE jabatan='Operator' ORDER BY nama ASC",$con);
							  while($rK=mysql_fetch_array($sqlKap)){
							  ?>
								  <option value="<?php echo $rK[nama]; ?>"><?php echo $rK[nama]; ?></option>
							 <?php } ?>	  
					  </select>
				  </div>
					  
		    </div>  
		  <div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">pH &amp; Suhu Tes Cuci Bulu</label>
          <div class="col-sm-2">
                    <input name="ph_cb" type="text" class="form-control" id="ph_cb" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R" or $rcek[dyestuff]=="OBA"){}else{echo "readonly";}?>>
                  </div>
		  <div class="col-sm-2">
					<div class="input-group">  
                    <input name="suhu_cb" type="text" class="form-control" id="suhu_cb" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R" or $rcek[dyestuff]=="OBA"){}else{echo "readonly";}?>>
					<span class="input-group-addon">&deg;</span></div>  
                  </div>	  
        </div>  
		
		<div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">pH &amp; Suhu Tes Poly</label>
          <div class="col-sm-2">
                    <input name="ph_poly" type="text" class="form-control" id="ph_poly" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="D"){}else{echo "readonly";}?>>
                  </div>
		  <div class="col-sm-2">
					<div class="input-group">  
                    <input name="suhu_poly" type="text" class="form-control" id="suhu_poly" 
                    value="" placeholder="0" style="text-align: right" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="D"){}else{echo "readonly";}?>>
					<span class="input-group-addon">&deg;</span></div>  
                  </div> 	
        </div> 		
		<div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">pH &amp; Suhu Tes Cotton</label>
          <div class="col-sm-2">
                    <input name="ph_cott" type="text" class="form-control" id="ph_cott" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R"){}else{echo "readonly";}?>>
                  </div>
		  <div class="col-sm-2">
					<div class="input-group">  
                    <input name="suhu_cott" type="text" class="form-control" id="suhu_cott" 
                    value="" placeholder="0" style="text-align: right" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R"){}else{echo "readonly";}?>>
					<span class="input-group-addon">&deg;</span></div> 
                  </div>	
        </div>
		<div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">Berat Jenis</label>
          <div class="col-sm-2">
                    <input name="berat_jns" type="text" class="form-control" id="berat_jns" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R"){}else{echo "readonly";}?>>
                  </div>				   
        </div>
		<div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">pH Na<sub>2</sub>CO<sub>3</sub></label>
          <div class="col-sm-2">
                    <input name="ph_naco" type="text" class="form-control" id="ph_naco" 
                    value="" placeholder="0" style="text-align: right;" <?php if($rcek[dyestuff]=="D+R"){}else if($rcek[dyestuff]=="R"){}else{echo "readonly";}?>>
                  </div>				   
        </div>
		<!--  
		<div class="form-group">
          <label for="air" class="col-sm-3 control-label">Air</label>
          <div class="col-sm-2">
                    <input name="a_dingin" type="text" class="form-control" id="a_dingin" 
                    value="" placeholder="Dingin" style="text-align: right;">
                  </div>
		  <div class="col-sm-2">
                    <input name="a_panas" type="text" class="form-control" id="a_panas" 
                    value="" placeholder="Panas" style="text-align: right;">
                  </div>	
        </div>	
        -->
		<div class="form-group">
          <label for="a_dingin" class="col-sm-3 control-label">Lama Proses</label>
          <div class="col-sm-3">
					<div class="input-group">  
                    <input name="lama_proses" type="text" class="form-control" id="lama_proses" placeholder="HH:MM" 
                    value="<?php if($nokk!=""){echo $rLama[lama];}?>" readonly="readonly">
					<div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                   </div>
					  </div>	
          </div>				   
        </div>  
		<div class="form-group">
                  <label for="ket" class="col-sm-3 control-label">Keterangan</label>
                  <div class="col-sm-8">					  
						  <textarea name="ket" class="form-control"><?php echo $ketsts;?></textarea>
				  </div>
					  
		</div>  
      </div>  		
	 	  <input type="hidden" value="<?php if($cek>0){echo $rcek[no_ko];}else{echo $rKO[KONo];}?>" name="no_ko">
		  <input type="hidden" value="<?php if($cek>0){echo $rcek[no_mesin];}?>" name="no_mesin">
		
		  
 	</div>
   	<div class="box-footer">
	<button type="button" class="btn btn-default pull-left" name="back" value="kembali" onClick="window.location='?p=Hasil-Celup'">Kembali <i class="fa fa-arrow-circle-o-left"></i></button>	
   <?php if($cek1111>0){ ?>   		
   <!-- <button type="submit" class="btn btn-primary pull-right" name="update" value="update">Ubah <i class="fa fa-edit"></i></button> -->
   <?php }else{ ?>	   
   <button type="submit" class="btn btn-primary pull-right" name="save" value="save">Simpan <i class="fa fa-save"></i></button> 
   <?php } ?>
   
   </div>
    <!-- /.box-footer -->
 </div>
</form>
  
						
                    

<?php 
	if($_POST['save']=="save"){			
	  $ket=str_replace("'","''",$_POST[ket]);
	  $mulai=$_POST[mulaism]." ".$_POST[waktu_mulai];
	  $selesai=$_POST[selesaism]." ".$_POST[waktu_stop];
	  if($_POST[kodesm]!=""){
		  $jam_stop=" mulai_stop='$mulai', selesai_stop='$selesai', ";
	  }else{
		  $jam_stop=" ";
	  }	
  	  $sqlData=mysql_query("INSERT INTO tbl_hasilcelup SET
	  	id_montemp='$_POST[id]',
		nokk='$_POST[nokk]',
		shift='$_POST[shift]',
		g_shift='$_POST[g_shift]',
		lama_proses='$_POST[lama_proses]',
		status='$_POST[sts]',		
		ph_cb='$_POST[ph_cb]',
		suhu_cb='$_POST[suhu_cb]',
		ph_poly='$_POST[ph_poly]',
		suhu_poly='$_POST[suhu_poly]',
		ph_cott='$_POST[ph_cott]',
		suhu_cott='$_POST[suhu_cott]',
		berat_jns='$_POST[berat_jns]',
		ph_naco='$_POST[ph_naco]',
		a_panas='$_POST[a_panas]',
		a_dingin='$_POST[a_dingin]',
		point='$_POST[point]',
		k_resep='$_POST[k_resep]',
		operator_keluar='$_POST[operator]',
		operator_potong='$_POST[operator_potong]',
		acc_keluar='$_POST[acc_keluar]',
		kd_stop='$_POST[kodesm]',
		$jam_stop 
		ket='$ket',
		tgl_buat=now(),
		tgl_update=now()
        ",$con);	 	  
	  
		if($sqlData){
			/* awal form potong */
		  $sqlCekP=mysql_query("SELECT a.*,c.k_resep,c.acc_keluar,c.operator_keluar,c.shift as shift_keluar,c.g_shift as g_shift_keluar,c.id as idcelup from tbl_schedule a
INNER JOIN tbl_montemp b ON a.id=b.id_schedule
INNER JOIN tbl_hasilcelup c ON b.id=c.id_montemp 
WHERE a.nokk='$_POST[nokk]' ORDER BY c.id DESC LIMIT 1",$con);
		  $rcekP=mysql_fetch_array($sqlCekP);	
		  $sqlDataP=mysql_query("INSERT INTO tbl_potongcelup SET
		  id_hasilcelup='$rcekP[idcelup]',
		  nokk='$_POST[nokk]',
		  shift='$_POST[shift]',
		  g_shift='$_POST[g_shift]',
		  operator='$_POST[operator_potong]',
		  tgl_buat=now(),
		  tgl_update=now()",$con);
			/* akhir form potong */
		  $sqlMonT=mysql_query("SELECT * FROM tbl_montemp WHERE id='$_POST[id]'");
		  $rMonT=mysql_fetch_array($sqlMonT);	
		  $sqlD=mysql_query("UPDATE tbl_schedule SET 
		  status='selesai',
		  tgl_update=now()
		  WHERE no_mesin = '$rcek[no_mesin]' and no_urut='1' and status='sedang jalan' ",$con);
		  $sqlDT=mysql_query("UPDATE tbl_montemp SET 
		  status='selesai',
		  tgl_update=now()
		  WHERE id='$_POST[id]'",$con);	
		  $sqlUrut=mysql_query("UPDATE tbl_schedule 
		  SET no_urut = no_urut - 1 
		  WHERE no_mesin = '$rcek[no_mesin]' 
		  AND `status` = 'antri mesin' AND not no_urut='1' ",$con);		  
			echo "<script>swal({
  title: 'Data Tersimpan',   
  text: 'Klik Ok untuk input data kembali',
  type: 'success',
  }).then((result) => {
  if (result.value) {
    
	 window.location.href='?p=Hasil-Celup'; 
  }
});</script>";
		}
		
			
	}
    if($_POST[update]=="update"){
	  $ket=str_replace("'","''",$_POST[ket]);
	  $mulai=$_POST[mulaism]." ".$_POST[waktu_mulai];
	  $selesai=$_POST[selesaism]." ".$_POST[waktu_stop];	
	  if($_POST[kodesm]!=""){
		  $jam_stop=" mulai_stop='$mulai', selesai_stop='$selesai', ";
	  }else{
		  $jam_stop=" ";
	  }		
  	  $sqlData=mysql_query("UPDATE tbl_hasilcelup SET 
	      id_montemp='$_POST[id]',
		  shift='$_POST[shift]',
		  g_shift='$_POST[g_shift]',
		  status='$_POST[sts]',		
		  ph_cb='$_POST[ph_cb]',
		  suhu_cb='$_POST[suhu_cb]',
		  ph_poly='$_POST[ph_poly]',
		  suhu_poly='$_POST[suhu_poly]',
		  ph_cott='$_POST[ph_cott]',
		  suhu_cott='$_POST[suhu_cott]',
		  berat_jns='$_POST[berat_jns]',
		  ph_naco='$_POST[ph_naco]',
		  a_panas='$_POST[a_panas]',
		  a_dingin='$_POST[a_dingin]',
		  point='$_POST[point]',
		  k_resep='$_POST[k_resep]',
		  operator_keluar='$_POST[operator]',
		  acc_keluar='$_POST[acc_keluar]',
		  kd_stop='$_POST[kodesm]',
		  $jam_stop 
		  ket='$ket',
		  tgl_update=now()
		  WHERE nokk='$_POST[nokk]'",$con);	 	  
	  
		if($sqlData){			
			echo "<script>swal({
  title: 'Data Telah DiUbah',   
  text: 'Klik Ok untuk input data kembali',
  type: 'success',
  }).then((result) => {
  if (result.value) {
    
	 window.location.href='?p=Hasil-Celup'; 
  }
});</script>";
		}
		
			
	}
?>