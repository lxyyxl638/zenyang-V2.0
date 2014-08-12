<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<head>
<title>注册</title>
</head>
<body>

<?php echo form_open('form/signup'); ?>

<h5>用户名</h5>
<?php echo form_error('username'); ?>
<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" />

<h5>密码</h5>
<?php echo form_error('password'); ?>
<input type="password" name="password" value="<?php echo set_value('password'); ?>" size="50" />

<h5>确认密码</h5>
<?php echo form_error('passconf'); ?>
<input type="password" name="passconf" value="<?php echo set_value('passconf'); ?>" size="50" />

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>