<?php
  class Public_model extends CI_Model{
   
   function __construct()
   {
   	  parent::__construct();
   	  $this->load->database();
      $this->load->helper('url');
   }
   
   
  function get_jd_title($jdid)
  {
     $this->db->select('title');
     $this->db->where('jdid',$jdid);
     $query = $this->db->get('jd_jd');
     $row = $query->row_array();
     return $row['title'];
  }  

  function get_jd_qtitle($qid)
  {
      if ($qid == 0) return "";
      $this->db->select('title');
      $this->db->where('qid',$qid);
      $query = $this->db->get('jd_question');
      $row = $query->row_array();
      return $row['title'];
  }

   function set_notify_good($uid,$qid,$aid)
   {
      $data = array(
                      'type' => 3,
                      'myuid' => $this->get_aid_uid($aid),
                      'qid' => $qid,
                      'title' => $this->get_qtitle($qid),
                      'uid' => $uid,
                      'realname' => $this->get_realname($uid),
                      'read' => 0,
                      'date' => date('Y-m-d H:i:s',time())
                   );
      $this->db->insert('notify_history',$data);
      return TRUE;
   }

   function set_notify_jd_good($uid,$jdid,$aid)
   {
      $data = array(
                      'jdid' =>$jdid,
                      'type' => 8,
                      'myuid' => $this->get_jd_aid_uid($aid),
                      'qid' => 0,
                      'title' => $this->get_jd_title($jdid),
                      'uid' => $uid,
                      'realname' => $this->get_realname($uid),
                      'read' => 0,
                      'date' => date('Y-m-d H:i:s',time())
                   );
      $this->db->insert('notify_history',$data);
      return TRUE;
   }
  

   function unset_notify_jd_good($uid,$jdid,$aid)
   {
      $notify_host = $this->get_jd_aid_uid($aid);
      $this->db->where('uid',$uid);
      $this->db->where('myuid',$notify_host);
      $this->db->where('jdid',$jdid);
      $this->db->where('type',8);
      $this->db->delete('notify_history');
      return TRUE;
   }

   function unset_notify_good($uid,$qid,$aid)
   {
      $notify_host = $this->get_jd_aid_uid($aid);
      $this->db->where('uid',$uid);
      $this->db->where('myuid',$notify_host);
      $this->db->where('qid',$qid);
      $this->db->where('type',3);
      $this->db->delete('notify_history');
      return TRUE;
   }

   function get_aid_uid($aid)
   {
      $this->db->select('uid');
      $this->db->where('id',$aid);
      $query = $this->db->get('q2a_answer');
      $row = $query->row_array();
      return $row['uid'];
   }

   function get_jd_aid_uid($aid)
   {
      $this->db->select('uid');
      $this->db->where('aid',$aid);
      $query = $this->db->get('jd_answer');
      $row = $query->row_array();
      return $row['uid'];
   }

   function get_aid_realname($aid)
   {
      $this->db->select('realname');
      $this->db->where('id',$aid);
      $query = $this->db->get('q2a_answer');
      $row = $query->row_array();
      return $row['realname'];
   }

   function get_realname($uid)
   {
     $this->db->select('realname');
     $this->db->where('uid',$uid);
     $query = $this->db->get('user_profile');
     $row = $query->row_array();
     return $row['realname'];
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

   function get_quid($qid)
   {
      $this->db->select('uid');
      $this->db->where('id',$qid);
      $query = $this->db->get('q2a_question');
      $row = $query->row_array();
      return $row['uid'];
   }
   
   /*判断是否有照片*/
     function get_photo($uid,&$photo_id)
     {
         $this->db->select('photo_upload,photo_id');
         $this->db->where('uid',$uid);
         $query = $this->db->get('user_profile');
         $row = $query->row_array();
         $photo_id = $row['photo_id'];
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
         if (!$this->get_photo($uid,$photo_id))
           {
               $location = "uploads/default_large.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_".$photo_id."_large.jpg";
           }
         return base_url("$location");
    } 

   function middle_photo_get($uid)
    {   
         if (!$this->get_photo($uid,$photo_id))
           {
               $location = "uploads/default_middle.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_".$photo_id."_middle.jpg";
           }
         return base_url("$location");
    } 

    function small_photo_get($uid)
    {   
         if (!$this->get_photo($uid,$photo_id))
           {
               $location = "uploads/default_small.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_".$photo_id."_small.jpg";
           }
         return base_url("$location");
    }

    function tiny_photo_get($uid)
    {   
         if (!$this->get_photo($uid,$photo_id))
           {
               $location = "uploads/default_tiny.jpg";
           }
           else
           {
               $location = "uploads/".$uid."_".$photo_id."_tiny.jpg";
           }
         return base_url("$location");
    }  

    function upload(&$message)
    {
        $config['upload_path'] = 'answerpic';
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = $this->session->userdata('uid');
        $config['max_size'] = 4096;
        $config['overwrite'] = FALSE;
        $config['remove_spaces'] = TRUE;
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        $userfile = 'userfile';
        if (!$this->upload->do_upload($userfile))
         {
            $message['detail'] = "uploaddeny";
            return FALSE;
         }
       
        $data = $this->upload->data();

        $config = '';
        $config['image_library'] = 'gd2';
        $config['source_image'] = $data['full_path'];
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 200;
        $config['height'] = 200;
        $this->load->library('image_lib',$config);
        $this->image_lib->resize();
        $message = "http://121.40.146.229/CI/answerpic/".$data['file_name'];
        return  TRUE;
    }

    
};
?>