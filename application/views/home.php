<script type="text/javascript">
	$(document).ready(function () {
		$('#tblSmses').DataTable({
        "order": [[ 0, "desc" ]]
    	});
	});
	var server_url = '<?php $this->load->helper('url'); echo base_url(); ?>';
	var html_addnew = '<div class=\"form-group\"><label>Sender Number</label><input type=\"text\" class=\"form-control\" name=\"SenderNumber\" ></div><div class=\"form-group\"><label>Has the words</label><input type=\"text\" class=\"form-control\" name=\"HasTheWords\"></div><div class=\"form-group\"><label>Mail Gun Domain</label><input type=\"text\" class=\"form-control\" name=\"MailGunDomain\"></div><div class=\"form-group\"><label>MailGun Api Key</label><input type=\"text\" class=\"form-control\"  name=\"MailGunApiKey\"></div><div class=\"form-group\"><label>From</label><input type=\"text\" class=\"form-control\" name=\"From\" placeholder=\"Subiz User <Subiz.com>\"></div><div class=\"form-group\"><label>To</label><input type=\"text\" class=\"form-control\" name=\"To\" placeholder=\"Baz <to@gmail.com>\"></div><div class=\"form-group\"><label>Subject</label><input type=\"text\" class=\"form-control\" name=\"Subject\"></div><div class=\"checkbox\"><label><input type=\"checkbox\" checked name=\"IsActive\">Active</label></div>';

    function onAddnewClick () {
    	$('.modal-title').text('Add new rule');
    	$('#modal-content').html(html_addnew);
    	$('#modalRule').modal();
    }

	function onEditRuleClick(id){
		$.ajax({
			method: 'POST',
			url: server_url + 'index.php/Home/RuleControler',
			data: {'action': 'getdetail', 'id': id},
			dataType: "text",  
         	cache:false,
         	success: function (data) {
         		$('.modal-title').text('Edit rule');
         		$('#modal-content').html(data);
         		$('#modalRule').modal();    
         	},
         	error: function(data){
         		console.log(data);
         	}
		});
	}

	function onDeleteRuleClick (id) {
		if (confirm('Are you sure?')) {
			$.ajax({
				method: 'POST',
				url: server_url + 'index.php/Home/RuleControler',
				data: {'action': 'delete', 'id': id},
				dataType: "text",  
		     	cache:false,
		     	success: function (data) {
		     		  $('#row'+id).empty();
		     	},
		     	error: function(data){
		     		alert('Error!');
		     	}
			});
		};
	}
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
