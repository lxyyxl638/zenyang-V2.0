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
          var data = {jdid:$id};
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
          var refreshpage = function(){
            console.log(request.readyState + '  ');
            if (request.readyState==4){
              window.location.assign("../manage/view_jd");
            }
          }
          var request = new XMLHttpRequest();
          request.onreadystatechange = refreshpage;
          request.open("POST","../manage/view_jd");
          request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
          request.send(encodeFormData($id));
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
          <th>职位</th>
          <th>行业</th>
          <th>公司</th>
          <th>地点</th>
        </tr>
      </thead>
      <tbody> 
            <?php foreach ($jd as $key => $value)
             {
              echo "<tr>
                      <th>".$value['occupation']."</th>". 
                     "<th>".$value['industry']."</th>".
                     "<th>".$value['company']."</th>".
                     "<th>".$value['place']."</th>".
                     "<th><input class=\"btn btn-default\" type=\"button\" value=\"删除\" name=\"delete\" onclick = \"request(".$value['jdid'].")\"></th>".
                   "</tr>"; 
             }
            ?>
      </tbody>
    </table>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>

  </body>
</html>