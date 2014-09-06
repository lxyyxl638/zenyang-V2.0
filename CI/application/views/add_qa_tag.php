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
    <form role="form" action="../manage/add_qa_tag" method="POST">
       <div class="form-group">
         <label for="tagname">输入标签</label>
         <input type="text" name = "tagname" class="form-control" id="tagname" placeholder="输入标签">
       </div>
       <div class="form-group">
         <label for="tagabbr">输入拼音</label>
         <input type="text" name = "tagabbr" class="form-control" id="tagabbr" placeholder="输入拼音">
       </div>  
  <button type="submit" class="btn btn-default">提交</button>
</form>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>