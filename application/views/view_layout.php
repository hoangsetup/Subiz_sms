<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="http://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      <?php $this->load->helper('url'); ?>
      /*Load status*/
      $('#gammu-info').load("<?php echo base_url().'/index.php/Home/gammuinfo'; ?>");
    });
    var refreshStatus = setInterval(function () {
      $('#gammu-info').load("<?php echo base_url().'/index.php/Home/gammuinfo'; ?>");
    }, 45 * 1000);
    var refreshLogs = setInterval(function () {
      $('#logs-rules').load("<?php echo base_url().'/index.php/Home/homecontent'; ?>");
    }, 2 * 60 * 1000);

    var server_url = '<?php $this->load->helper('url'); echo base_url(); ?>';
  var html_addnew = '<div class=\"form-group\"><label>Sender Number</label><input type=\"text\" class=\"form-control\" name=\"SenderNumber\" ></div><div class=\"form-group\"><label>Has the words</label><input type=\"text\" class=\"form-control\" name=\"HasTheWords\"></div><div class=\"form-group\"><label>Mail Gun Domain</label><input type=\"text\" class=\"form-control\" name=\"MailGunDomain\" required></div><div class=\"form-group\"><label>MailGun Api Key</label><input type=\"text\" class=\"form-control\"  name=\"MailGunApiKey\" required></div><div class=\"form-group\"><label>From</label><input type=\"text\" class=\"form-control\" name=\"From\" placeholder=\"Subiz User <Subiz.com>\"></div><div class=\"form-group\"><label>To</label><input type=\"text\" class=\"form-control\" name=\"To\" placeholder=\"Baz <to@gmail.com>\"></div><div class=\"form-group\"><label>Subject</label><input type=\"text\" class=\"form-control\" name=\"Subject\"></div><div class=\"checkbox\"><label><input type=\"checkbox\" checked name=\"IsActive\">Active</label></div>';

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

    function checkMailgunApi () {
      var mail_api_key = $("[name='MailGunApiKey']").val();
      var mail_domain = $("[name='MailGunDomain']").val();
      $.ajax({
          type: "POST",
          url: server_url + 'index.php/Home/checkmailgun',
          dataType: 'json',
          data: {'api': mail_api_key, 'domain': mail_domain},
          beforeSend: function(){
            $('#btncheck').html('Checking...');
          },
          success: function (data){
              console.log(data);
              var http_code = data['http_code'];
              if(http_code === 404 || http_code === 0){
                alert('Error: Domain is not corect!')
              }else if(http_code === 401){
                alert('Error: Api key is not corect!');
              }else{
                alert('Success!');
              }
          },
          error: function (xhr) {
            console.log(xhr);
            alert('Unknown error!')
          }
      }).done(function() {
          $('#btncheck').html('Check api key');
      });
    }
  </script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">SubizSms</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="#home">Home</a></li>
        <li><a href="#rules">Rules</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo base_url(); ?>index.php/Login/logout"><span class="glyphicon glyphicon-user"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        System information
      </div>
      <div class="panel-body" id="gammu-info">
        <!--  -->
        
      </div>
    </div>
  </div>
</div >
<div id="logs-rules">
    <?php $this->load->view('home'); ?>
</div>
<!-- Rules -->
<div class="row">
  <div class="col-sm-12" id="rules">
    <div class="panel panel-danger">
      <div class="panel-heading">
        Rules
      </div>
      <div class="panel-body">
        <table class="table table-bordered" id="tblRules">
          <thead>
            <tr>
              <th>Time</th>
              <th>SenderNumber</th>
              <th>Contain</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php 
            foreach ($rules as $rule) {
              ?>
            <tr id="row<?=$rule['Id']?>">
              <td><?=$rule['TimeUpdate']?></td>
              <td><?=$rule['SenderNumber']?></td>
              <td><?=$rule['HasTheWords']?></td>
              <td>
                <div class="btn-group btn-group" >
                  <button type="button" class="btn btn-warning" onclick="onEditRuleClick('<?=$rule['Id']?>');"><span class="glyphicon glyphicon-pencil"></span></button>

                  <button type="button" class="btn btn-danger" onclick="onDeleteRuleClick(<?=$rule['Id']?>);"><span class="glyphicon glyphicon-remove"></span></button>
                </div>
              </td>
            </tr>             
              <?php
            }
          ?>

          </tbody>
        </table>
      </div>
      <div class="panel-footer">
        <a href="javascript:onAddnewClick();" class="btn btn-success" >Add new</a>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="modalRule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="<?php $this->load->helper('url'); echo base_url().'index.php/Home/RuleControler'; ?>" method="POST" rule="form">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" >Add new rule</h4>
        </div>
        <div class="modal-body" id="modal-content">
          <!-- Content modal -->
        </div>
        <div class="modal-footer">
          <button id="btncheck" type="button" class="btn btn-info" onclick="checkMailgunApi();">Check api key</button>
          <input type="submit" name="submit" class="btn btn-success" value="Save">
        </div>
      </div>
    </form>
  </div>
</div>
<!-- /Modal -->

</div>

</body>
</html>