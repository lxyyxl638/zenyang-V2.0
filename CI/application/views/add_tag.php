<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<head>
<title>My Form</title>
</head>
<body>

<?php echo validation_errors(); ?>

<?php echo form_open('add/add_tag'); ?>

<h5>标签</h5>
<input type="text" name="tagname" value="" size="50" />

<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>