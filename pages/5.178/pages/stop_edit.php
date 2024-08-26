<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$modal=mysqli_query($con,"SELECT *,DATE_FORMAT(tgl_stop,'%Y-%m-%d') as tglS,DATE_FORMAT(tgl_stop,'%H:%i') as jamS,DATE_FORMAT(tgl_mulai,'%Y-%m-%d') as tglM,DATE_FORMAT(tgl_mulai,'%H:%i') as jamM FROM `tbl_montemp` WHERE id='$modal_id' ");
while($r=mysqli_fetch_array($modal)){
	$qLama=mysqli_query($con,"SELECT TIME_FORMAT(timediff(b.tgl_target,now()),'%H:%i') as lama,b.id FROM tbl_schedule a
LEFT JOIN tbl_montemp b ON a.id=b.id_schedule
WHERE b.id='$modal_id' AND b.status='sedang jalan'  ORDER BY a.no_urut ASC");
			$dLama=mysqli_fetch_array($qLama);
?>
          <div class="modal-dialog">
            <div class="modal-content">
            <form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="?p=edit_stop" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Stop - Start Mesin</h4>
              </div>
              <div class="modal-body">
                  <input type="hidden" id="id" name="id" value="<?php echo $r['id'];?>">
				  <input type="hidden" id="sisa_waktu" name="sisa_waktu" value="<?php echo $dLama['lama'];?>">
                  <div class="form-group">
                  <label for="mulaism" class="col-sm-3 control-label">Mulai Stop Mesin</label>
			      <div class="col-sm-3">
				  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="jam_stop" placeholder="00:00" value="<?php echo $r['jamS'];?>">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                   </div>
                  </div>
			</div>	  
                  <div class="col-sm-4">					  
						  <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="tgl_stop" type="text" class="form-control pull-right" id="datepicker3" placeholder="0000-00-00" value="<?php echo $r['tglS'];?>"/>
          </div>
				  </div>
					  
		</div>		 
		<div class="form-group">
                  <label for="selesaism" class="col-sm-3 control-label">Selesai Stop Mesin</label>
			      <div class="col-sm-3">
				  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="jam_mulai" placeholder="00:00" value="<?php echo $r['jamM'];?>">
                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
			</div>
                  <div class="col-sm-4">					  
						  <div class="input-group date">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input name="tgl_mulai" type="text" class="form-control pull-right" id="datepicker" placeholder="0000-00-00" value="<?php echo $r['tglM'];?>"/>
          </div>
				  </div>
					  
		</div>  			    
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" >Save</button>
              </div>
            </form>
            </div>
            <!-- /.modal-content -->
  </div>
          <!-- /.modal-dialog -->
          <?php } ?>
<script>
		//Date picker
        $('#datepicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd',
          todayHighlight: true,
        });
		//Date picker
        $('#datepicker3').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd',
          todayHighlight: true,
        });
		//Timepicker
    	$('#timepicker').timepicker({
      	showInputs: false,
    	});
	    
	$(function () {	
//Timepicker
    $('.timepicker').timepicker({
                minuteStep: 1,
                showInputs: true,
                showMeridian: false,
                defaultTime: false	
	  	
    })
})
		
</script>
