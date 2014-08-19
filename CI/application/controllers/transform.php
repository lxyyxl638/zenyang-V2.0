<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<?php
class transform extends CI_controller
{

   function __construct()
   {
   	   parent::__construct();
   	   $this->load->database();
       $this->load->helper('url');
   }
   
   function index()
   {
        $filename = base_url()."college.json";
        $json_string = file_get_contents($filename);
        $array=json_decode($json_string,true);
        foreach ($array as $key => $value)
         {
            $data = array(
            	               'college' => $value['name'],
            	               'abbreviation' => $value['abbreviation']
            	            );
            $this->db->insert('user_college',$data);
         }
    }

   function major()
   {
       $filename = base_url()."major.json";
       $json_string = file_get_contents($filename);
       $json_string = str_replace("/\n/", ' ', $json_string);
       $json_string = str_replace("/\t/", ' ', $json_string);
       $array=json_decode($json_string,true);
       echo json_last_error();
       foreach ($array as $key => $value)
         {
            $data = array(
                             'major' => $value['name'],
                             'abbreviation' => $value['abbreviation']
                          );
            $this->db->insert('user_major',$data);
         }
   } 
}
?>