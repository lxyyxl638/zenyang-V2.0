<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理系统</title>

    <!-- Bootstrap -->
    <link href="http://localhost/zenyang/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <div class="btn-group-vertical btn-group-lg">    
           <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#user_table">
              用户管理
           </button>
            <div id="user_table" class="collapse">
             <ul class="nav nav-sidebar">
                 <li><a href="<?php echo site_url("manage/view_user");?>" target="home">查看用户</a></li>
             </ul>
            </div>
          <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#jd_table">
              职位管理
          </button>        
           <div id="jd_table" class="collapse">    
              <ul class="nav nav-sidebar">
                <li><a href="<?php echo site_url("manage/view_jd")?>" target="home">查看职位</a></li>
                <li><a href="<?php echo site_url("manage/add_jd")?>" target="home">添加JD</a></li>
                <li><a href="<?php echo site_url("manage/add_tag")?>" target="home">添加标签</a></li>
              </ul>
            </div>
          <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#qa_table">
              问答管理
          </button>      
           <div id="qa_table" class="collapse">    
            <ul class="nav nav-sidebar">
                <li><a href="<?php echo site_url("manage/view_question")?>" target="home">查看问题</a></li>
                <li><a href="<?php echo site_url("manage/add_qa_tag")?>" target="home">添加标签</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>   
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>