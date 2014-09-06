<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />   
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
       <div class="col-md-4">
          <div>
              <label for="industry">行业</label>
          </div>    
    		  <div class="btn-group">
           <select type="button" id="industry" name="industry" class="btn btn-default dropdown-toggle">
             <option></option>
           </select>
          </div>
        </div>  
      </div>   
     
      <div class="row">
       <div class="col-md-4">
          <div>
              <label for="company">公司</label>
          </div>    
          <div class="btn-group">
           <select type="button" id="company" name="company" class="btn btn-default dropdown-toggle">
              <option></option>
           </select>   
          </div>
        </div>  
      </div>   

      <div class="row">
       <div class="col-md-4">
          <div>
              <label for="occupation">职业</label>
          </div>    
          <div class="btn-group">
           <select type="button" id="occupation" name="occupation" class="btn btn-default dropdown-toggle">
              <option></option>
           </select>   
          </div>
        </div>  
      </div>   
    	
      <div class="row">
       <div class="col-md-4">
          <div>
              <label for="place">地点</label>
          </div>    
          <div class="btn-group">
            <select type="button" id="place" name="place" class="btn btn-default dropdown-toggle">
              <option></option>
            </select>   
          </div>
        </div>  
      </div>

      <div class="row">
        <div class="col-md-4">
          <div>
              <label for="content">职位描述</label>
          </div>    
          <input type="text" id="content" class="form-control" placeholder="职位描述">
        </div>  
      </div> 

      <div class="row">
        <div class="col-md-4">
          <div>
              <label for="salary_down">薪水下限</label>
          </div>    
          <input type="text" id="salary_down" class="form-control">
        </div>  
      </div> 

      <div class="row">
        <div class="col-md-4">
          <div>
              <label for="salary_up">薪水上限</label>
          </div>    
          <input type="text" id="salary_up" class="form-control">
        </div>  
      </div> 
      <button type="button" id="submit" class="btn btn-primary">提交</button>
      <div id = "output"></div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://localhost/zenyang/bootstrap/js/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://localhost/zenyang/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function(){

          $.get("../manage/checkbox_industry",function(data){
              $.each(data,function(index,value){
              $("#industry").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>");
               });
            });

          $.get("../manage/checkbox_place",function(data){
              $.each(data,function(index,value){
              $("#place").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>");
               });
            });

          $("#submit").click(function(){
              input={
                  'industry':$("#industry option:selected").val(),
                  'company':$("#company option:selected").val(),
                  'occupation':$("#occupation option:selected").val(),
                  'content':$("#content").val(),
                  'place':$("#place").val(),
                  'salary_down':$("#salary_down").val(),
                  'salary_up':$("#salary_up").val()
              };
              $.post("../manage/add_jd",input,function(data){
                $("#output").html(data);
              })
          });

          $("#industry").change(function(){
             input={'tagid':$("#industry option:selected").val()};
             $("#company option").remove();
             $("#company").append("<option></option>");
             $("#occupation option").remove();
             $("#occupation").append("<option></option>");
             if(input)
             { 
                $.get("../manage/checkbox_other",input,function(data){
                   $.each(data['company'],function(index,value){
                   $("#company").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>")
                });
                   $.each(data['occupation'],function(index,value){
                   $("#occupation").append("<option value=\""+value['tagid']+"\">"+value['tagname']+"</option>")
                });
               });
             } 
          })
        })
    </script>
  </body>
</html>