<?php
  class Public_model extends CI_Model{
   
   function __construct()
   {
   	  parent::__construct();
   	  $this->load->database();
      $this->load->helper('url');
   }

   function getid($email)
   {
   	$this->db->select('id');
   	$query = $this->db->get_where('user',array('email' => $email));
      $row = $query-> row_array();
      return $row['id'];
   }

   function getemail($id)
   {
   	  $this->db->select('email');
   	  $query = $this->db->get_where('user',array('id' => $id));
      $row = $query-> row_array();
      return $row['email'];
   }
   
   function uidrealname(&$message,$uid)
   {
       $this->db->select('realname');
       $this->db->where('uid',$uid);
       $query = $this->db->get('user_profile');
       $row = $query->row_array();
       $message['uidrealname'] = $row['realname'];
       return TRUE;
   }
   
   function get_qtitle($qid)
   {
      $this->db->select('title');
      $this->db->where('id',$qid);
      $query = $this->db->get('q2a_question');
      $row = $query->row_array();
      return $row['title'];
   }
   /*判断是否有照片*/
     function get_photo($uid)
     {
         $this->db->select('photo_upload');
         $this->db->where('uid',$uid);
         $query = $this->db->get('user_profile');
         $row = $query->row_array();
         if (isset($row['photo_upload']) && $row['photo_upload'] == 'Y') 
            {
                return TRUE;
            }
         else
            {
                return FALSE;
            }
     }

   function large_photo_get($uid)
    {   
         if (!$this->get_photo($uid))
           {
               $location = "uploads/default_large.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_large.jpg";
           }
         return base_url("$location");
    } 

   function middle_photo_get($uid)
    {   
         if (!$this->get_photo($uid))
           {
               $location = "uploads/default_middle.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_middle.jpg";
           }
         return base_url("$location");
    } 

    function small_photo_get($uid)
    {   
         if (!$this->get_photo($uid))
           {
               $location = "uploads/default_small.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_small.jpg";
           }
         return base_url("$location");
    }

    function tiny_photo_get($uid)
    {   
         if (!$this->get_photo($uid))
           {
               $location = "uploads/default_tiny.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_tiny.jpg";
           }
         return base_url("$location");
    }  
};
?>