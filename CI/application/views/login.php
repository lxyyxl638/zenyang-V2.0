<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<head>
<title>登陆注册</title>
</head>
<body>

<?php echo form_open('form'); ?>

<h5>用户名</h5>
<input type="text" name="username" value="<?php echo set_value('username'); ?>" size="50" />
<?php echo "$error_username"?>

<h5>密码</h5>
<input type="password" name="password" size="50" />
<?php echo "$error_password"?>

<div><input type="submit" value="Submit" /></div>
<div><p><?php echo anchor('form/signup','注册')?> </p></div> 
</form>

</body>
</html>