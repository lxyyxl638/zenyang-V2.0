﻿<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<html>
<head>
<style type="text/css" media="screen">
	#container {
	 width: 600px;
	 margin: auto;
	font-family: helvetica, arial;
	}

	table {
	 width: 600px;
	 margin-bottom: 10px;
	}

	td {
	 border-right: 1px solid #aaaaaa;
	 padding: 1em;
	}

	td:last-child {
	 border-right: none;
	}

	th {
	 text-align: left;
	 padding-left: 1em;
	 background: #cac9c9;
	border-bottom: 1px solid white;
	border-right: 1px solid #aaaaaa;
	}

	#pagination a, #pagination strong {
	 background: #e3e3e3;
	 padding: 4px 7px;
	 text-decoration: none;
	border: 1px solid #cac9c9;
	color: #292929;
	font-size: 13px;
	}

	#pagination strong, #pagination a:hover {
	 font-weight: normal;
	 background: #cac9c9;
	}		
	</style>
</head>
<body>
   <div id = "container">
     <h1> 提问列表</h1>
     <table border = "1">
	 <tr>
	    <th>提问者</th>
		<th>题目</th>
	 </tr>
	 <?php foreach ($records as $data_item){?>
		<tr>
		  <td><?php echo $data_item['realname'];?></td>
		  <td><?php echo anchor('q2a/answer_view/'.$data_item['id'],$data_item['title'])?></td>
		</tr>
	 <?php } ?>
	   	
	 <?php echo $this->pagination->create_links();?>
    </table>	 
   </div>
   <div><?php echo anchor('q2a/ask','我要提问') ?><br>
        <?php echo anchor('person','个人中心')?>
   </div>
   </body>
</html>