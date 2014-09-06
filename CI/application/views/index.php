<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>后台管理系统</title>
	</head>
	<frameset rows="8%,*" frameborder="no">
		<frame src="<?php echo site_url("manage/header");?>" name="header" />
		<frameset cols="20%,*">
			<frame src="<?php echo site_url("manage/navbar");?>" name="navbar"/>
    		<frame src="<?php echo site_url("manage/view_user");?>" name="home"/>
    	</frameset>
	</frameset>
</html>