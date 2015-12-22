<?php 
if(!$devices)
return;
$db_schema = (isset($gammu))?$gammu['Version']:'unknown';
$_class = '';
$_stt = 'Unknown';
foreach($devices as  $info){

if($status[$info['IMEI']] == 1){
	$_class = 'label label-success';
	$_stt = 'Connect';
}
if($status[$info['IMEI']] == 0){
	$_class = 'label label-danger';
	$_stt = 'Disconnect';
}
if($status[$info['IMEI']] == -1){
	$_class = 'label label-default';
	$_stt = 'Unknown';
}

//foreach ($devices as $info) {
	?>
<div class="panel panel-default">
  <div class="panel-heading"><?=$info['ID']?> <label class="<?php echo $_class ?>"><?=$_stt?></label></div>
  <div class="panel-body">
    <div class="list-group">
      <button type="button" class="list-group-item list-group-item-default">Operating System: <?=PHP_OS?></button>
      <button type="button" class="list-group-item list-group-item-default">Gammu Version: <?=$info['Client']?></button>
      <button type="button" class="list-group-item list-group-item-default">Gammu DB Schema: <?=$db_schema?></button>
      <button type="button" class="list-group-item list-group-item-default">Modem IMEI: <?=$info['IMEI']?></button>
    </div>
  </div>
</div>
	<?php
}
?>
