<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<title>change_password</title>
<body>

<?php echo form_open('person/change_password'); ?>

<h5>旧密码</h5>
<?php echo form_error('OldPassword'); ?>
<input type="password" name="OldPassword" value=""; size="50" />

<h5>新密码</h5>
<?php echo form_error('NewPassword'); ?>
<input type="password" name="NewPassword" value=""; size="50" />

<h5>确认密码</h5>
<?php echo form_error('Passconf'); ?>
<input type="password" name="Passconf" value=""; size="50" />

<div><input type="submit" value="提交" /></div>

</body>
</html>