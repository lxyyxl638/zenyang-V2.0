<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台管理系统</title>

    <!-- Bootstrap -->
    <link href="http://localhost/zenyang/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript">
        function encodeFormData($id)
        {
          var data = {id:$id};
          var pairs=[];
          for (var name in data)
          {
            var value = data[name].toString();
            name = encodeURIComponent(name.replace("%20","+"));
            value = encodeURIComponent(value.replace("%20","+"));
            pairs.push(name + "=" + value);
          }
          return pairs.join('&');
        }

        function request($id)
        {
          var request = new XMLHttpRequest();
          request.open("POST","../manage/view_user");
          request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
          request.send(encodeFormData($id));
          window.location.assign("../manage/view_user");
        }
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <table class="table table-hover table-responsive">
      <thead>
        <tr>
          <th>邮箱</th>
          <th>姓名</th>
          <th>上次登录</th>
          <th>工作职位</th>
          <th>工作地点</th>
        </tr>
      </thead>
      <tbody>
        <form role="form" action="<?php echo site_url("manage/view_user")?>" method="post">
            <?php foreach ($user as $key => $value)
             {
              echo "<tr>
                      <th>".$value['email']."</th>". 
                     "<th>".$value['realname']."</th>".
                     "<th>".$value['lastlogin']."</th>".
                     "<th>".$value['job']."</th>".
                     "<th>".$value['jobplace']."</th>".
                     "<th><input class=\"btn btn-default\" type=\"button\" value=\"删除\" name=\"delete\" onclick = \"request(".$value['id'].")\"></th>".
                   "</tr>"; 
             }
            ?>
        </form>
      </tbody>
    </table>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>