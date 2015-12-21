<!DOCTYPE html>
<html>
<head>
	<title>Subiz_sms/Login</title>
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <style type="text/css">
  	a{
  		text-decoration: none;
  	}

  </style>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4" style="margin-top: 150px;">
			<?php echo form_open('login'); ?>
				<div class="panel panel-success">
					<div class="panel-heading">
						Login
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label>Username: </label>
							<input type="text" name="username" class="form-control" required>
							<label>Password: </label>
							<input type="password" name="password" class="form-control" required>
							
							<span class="label label-danger"><?php if($this->session->userdata('errorlogin')) echo $this->session->userdata('errorlogin'); ?></span>
							<br />
							<br />
							<input type="submit" value="Login" class="btn btn-success form-control" />
							<br />
							<br />
							<a href="#" data-toggle="modal" data-target="#modalResetpassword">Change Password!</a>
						</div>
					</div>
				</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>
<!-- Modal change password -->
<!-- Modal -->
<div id="modalResetpassword" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="<?php $this->load->helper('url'); echo base_url().'index.php/Login/changePassword'; ?>" method="POST" rule="form">
      <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title" >Change password</h4>
        </div>
        <div class="modal-body" id="modal-content">
        	<div class="form-group">
        		<label>Username</label>
        		<input type="text" class="form-control" name="username" required>
        	</div>
        	<div class="form-group">
        		<label>Old password</label>
        		<input type="password" class="form-control" name="oldpassword" required>
        	</div>
        	<div class="form-group">
        		<label>New password</label>
        		<input type="password" class="form-control" name="newpassword" required>
        	</div>
        </div>
        <div class="modal-footer">
			<input type="submit" name="submit" class="btn btn-success" value="Change">
        </div>
      </div>
    </form>
  </div>
</div>
<!-- /Modal -->
</body>
</html>