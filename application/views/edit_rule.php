<input type="hidden" name="id" value="<?=$rule['Id']?>">
<div class="form-group">
	<label>Sender Number</label>
	<input type="text" class="form-control" value="<?=$rule['SenderNumber']?>" name="SenderNumber" >
</div>
<div class="form-group">
	<label>Has the words</label>
	<input type="text" class="form-control" value="<?=$rule['HasTheWords']?>" name="HasTheWords">
</div>
<div class="form-group">
	<label>Mail Gun Domain</label>
	<input type="text" class="form-control" value="<?=$rule['MailGunDomain']?>" name="MailGunDomain" required>
</div>
<div class="form-group">
	<label>MailGun Api Key</label>
	<input type="text" class="form-control" value="<?=$rule['MailGunApiKey']?>" name="MailGunApiKey" required>
</div>
<div class="form-group">
	<label>From</label>
	<input type="text" class="form-control" value="<?=$rule['From']?>" name="From" placeholder="Subiz User <Subiz.com>">
</div>
<div class="form-group">
	<label>To</label>
	<input type="text" class="form-control" value="<?=$rule['To']?>" name="To" placeholder="Baz <to@gmail.com>">
</div>
<div class="form-group">
	<label>Subject</label>
	<input type="text" class="form-control" value="<?=$rule['Subject']?>" name="Subject">
</div>
<div class="checkbox">
	<label><input type="checkbox" <?php echo ($rule['IsActive'] == 1)?'checked':''; ?> name="IsActive">Active</label>
</div>
