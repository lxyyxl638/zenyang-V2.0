<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<?php
class transform extends CI_controller
{

   function __construct()
   {
   	   parent::__construct();
   	   $this->load->database();
   }

   $filename = "college.json";
   $json_string = file_get_contents($filename);
   $array=json_decode($json_string,true);
   foreach ($array as $key => $value)
    {
       $data = array(
       	               'name' => $value['name'],
       	               'abbreviation' => $value['abbreviation']
       	            );
       $this->db->insert('user_college',$data);
    }
 ));
}
?>