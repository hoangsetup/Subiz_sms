<!DOCTYPE html>
<html>
<head>
	<title>Subiz_sms/Login</title>
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="http://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
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
							<br />
							<input type="submit" value="Login" class="btn btn-success form-control" />
							<?php if($this->session->flashdata('errorlogin')); ?>
						</div>
					</div>
				</div>
			<?php echo form_close();?>
		</div>
	</div>
</div>
</body>
</html>