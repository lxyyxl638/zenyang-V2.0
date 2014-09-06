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
    <div class="container">
     <div class="row">
      <div class="col-lg-6">
    <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
          <li class="active"><a href="#industry" role="tab" data-toggle="tab">行业</a></li>
          <li><a href="#company" role="tab" data-toggle="tab">公司</a></li>
          <li><a href="#occupation" role="tab" data-toggle="tab">职位</a></li>
          <li><a href="#place" role="tab" data-toggle="tab">地点</a></li>
      </ul>
      </div>
     </div>

      <!-- Tab panes -->
          <div class="tab-content">
              <div class="tab-pane active" id="industry">
                <div>
                  <label for="tagname">输入行业</label>
                  <input type="text" name = "tagname" class="form-control" id="industry_input" placeholder="输入行业">
                </div>
                <div class="btn-group">
                    <button id="submit_1" type="button" class="btn btn-default">提交</button>
                </div>
              </div>

              <div class="tab-pane" id="company">
                  <!-- Single button -->
                  <div class="btn-group">
                    <select type="button" id = "industry_1" name="industry" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  </div>
                  <div>
                    <label for="tagname">输入公司</label>
                    <input type="text" name = "tagname" class="form-control" id="company_input" placeholder="输入公司">
                  </div>
                  <div class="btn-group">
                    <button id="submit_2" type="button" class="btn btn-default">提交</button>
                  </div>
              </div>

              <div class="tab-pane" id="occupation">
                  <div class="btn-group">
                    <select type="button" id = "industry_2" name="industry" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                  </div>
                  <div>
                    <label for="tagname">输入职业</label>
                    <input type="text" name = "tagname" class="form-control" id="occupation_input" placeholder="输入职业">
                  </div>
                  <div class="btn-group">
                    <button id="submit_3" type="button" class="btn btn-default">提交</button>
                  </div>
              </div>

              <div class="tab-pane" id="place">
                  <label for="tagname">输入地点</label>
                  <input type="text" name = "tagname" class="form-control" id="place_input" placeholder="输入地点">
                  <div class="btn-group">
                    <button id="submit_4" type="button" class="btn btn-default">提交</button>
                  </div>
              </div>
      </div>
     <div class="row">
        <div class="col-lg-4">
          <p id="output"></p>
        </div>
     </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
     <script src="http://localhost/zenyang/bootstrap/js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function()
          {
            $.get("../manage/checkbox_industry",function(data)
            {
              $.each(data,function(index,value){
              $("#industry_1").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>");
               });
              $.each(data,function(index,value){
              $("#industry_2").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>");
               });
            });

            $("#submit_1").click(function(){
              $.post("../manage/add_tag",{
                'tagname':$("#industry_input").val(),
                'belong':'1'
              },function(data){
                $("#output").html(data);
                $("#industry_input").val("");
              })
            });

            $("#submit_2").click(function(){
              $.post("../manage/add_tag",{
                'tagname':$("#company_input").val(),
                'belong':'2',
                'industry':$("#industry_1").val()
              },function(data){
                $("#output").html(data);
                $("#company_input").val("");
              })
            });

            $("#submit_3").click(function(){
              $.post("../manage/add_tag",{
                'tagname':$("#occupation_input").val(),
                'belong':'3',
                'industry':$("#industry_2").val()
              },function(data){
                $("#output").html(data);
                $("#occupation_input").val("");
              })
            });

            $("#submit_4").click(function(){
              $.post("../manage/add_tag",{
                'tagname':$("#place_input").val(),
                'belong':'4'
              },function(data){
                $("#output").html(data);
                $("#place_input").val("");
              })
            });
        });
    </script>
  </body>
</html>