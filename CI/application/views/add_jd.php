<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<head>
<title>My Form</title>
</head>
<body>

<?php echo validation_errors(); ?>

<?php echo form_open('add/add_jd'); ?>

<h5>行业</h5>
<input type="text" name="industry" value="" size="50" />

<h5>公司</h5>
<input type="text" name="company" value="" size="50" />

<h5>职位</h5>
<input type="text" name="occupation" value="" size="50" />

<h5>薪水等级</h5>
<input type="text" name="salary" value="" size="50" />

<h5>地点</h5>
<input type="text" name="place" value="" size="50" />

<h5>简介</h5>
<input type="text" name="title" value="" size="50" />

<h5>详情</h5>
<input type="text" name="content" value="" size="50" />
<div><input type="submit" value="Submit" /></div>

</form>

</body>
</html>