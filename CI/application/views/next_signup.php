<html>
<head>
	<title>Next_Signup</title>
</head>
<body>
<?php echo form_open('form/next_signup'); ?>

<h5>真实姓名</h5>
<?php echo form_error('realname'); ?>
<input type="text" name="realname" value="<?php echo set_value('realname'); ?>" size="50" />

<h5>邮箱</h5>
<?php echo form_error('email'); ?>
<input type="text" name="email" value="<?php echo set_value('email'); ?>" size="50" />

<!-- <h5>照片</h5>
<?php echo form_error('passconf'); ?>
<input type="password" name="passconf" value="<?php echo set_value('passconf'); ?>" size="50" />
 -->
<div><input type="submit" value="提交" /></div>

</body>
</html>