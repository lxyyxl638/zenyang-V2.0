<<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
 require 'vendor/autoload.php';
 use Mailgun\Mailgun;
 $mg = new Mailgun("key-e7b9c51f08cdfacaf18603c965990109");
 $domain = "youzenyang.com";

# Now, compose and send your message.
$mg->sendMessage($domain, array('from'    => 'zenyang@youzenyang.com', 
                                'to'      => '307571482@qq.com', 
                                'subject' => 'The PHP SDK is awesome!', 
                                'text'    => 'It is so simple to send a message.'));
?>
<p>messageSend</p>
</body>
</html>>
