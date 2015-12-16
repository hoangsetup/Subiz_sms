<script type="text/javascript">
	$(document).ready(function () {
		$('#tblSmses').DataTable({
        "order": [[ 0, "desc" ]]
    	});
	});
</script>
<div class="row">
	<div class="col-sm-12" rule="home">
		<div class="panel panel-success">
			<div class="panel-heading">
				Logs
			</div>
			<div class="panel-body">
				<table class="table table-bordered" id="tblSmses">
					<thead>
						<tr>
							<td>ID</td>
							<td>Time</td>
							<td>RecipientID</td>
							<td>SenderNumber</td>
							<td>TextDecoded</td>
							<td>Message</td>
						</tr>
					</thead>
					<tbody>
					<?php 
						foreach ($smses as $sms) {
							$msg = '';
							$_class = '';
							if(isset($sms['fwstatus'])){
								if($sms['fwstatus']['status'] == 0){
									$_class = 'danger';
								}else{
									$_class = 'success';
								}
								$msg = $sms['fwstatus']['message'];
							}
							?>
						<tr class="<?php echo $_class; ?>">
							<td><?=$sms['ID']?></td>
							<td><?=$sms['UpdatedInDB']?></td>
							<td><?=$sms['RecipientID']?></td>
							<td><?=$sms['SenderNumber']?></td>
							<td><?=$sms['TextDecoded']?></td>
							<td><?php echo $msg; ?></td>
						</tr>
							<?php
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
