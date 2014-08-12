<?php
 require 'vendor/autoload.php';
 use Mailgun\Mailgun;  
 
     $mg = new Mailgun("key-e7b9c51f08cdfacaf18603c965990109");
     $domain = "youzenyang.com";

     # Now, compose and send your message.
     $mg->sendMessage($domain, array('from'    => 'admin@youzenyang.com', 
                                     'to'      => '307571482@qq.com', 
                                     'subject' => "欢迎您加入'怎样'", 
                                     'text'    => "您好：
     欢迎您加入'怎样'的大家庭~成为我们第一批内测用户，
     首先万分感谢大家珍贵的意见反馈，同时我们也为'怎样'存在的种种不足感到抱歉，我们会继续努力，把网站建设得越来越好
     怎样"));
 

?>
