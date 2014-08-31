<?php

 class Search_model extends CI_model{

   function __construct()	
   {
   	   parent::__construct();
   }

   function search (&$message)
   {
   	  $keyword = $this->input->post('keyword');

   	  $this->db->select('uid,realname');
   	  $this->db->like('realname',$keyword);
   	  $this->db->limit(3,0);
   	  $query = $this->db->get('user_profile');
   	  $message['user'] = $query->result_array();

        $this->db->select('id,title');
        $this->db->like('title',$keyword);
        $this->db->limit(6,0);
        $query = $this->db->get('q2a_question');
        $message['question'] = $query->result_array();

        $this->db->select('tagid,tagname');
        $this->db->like('tagname',$keyword);
        $this->db->or_like('tagabbr',$keyword);
        $this->db->limit(3,0);
        $query = $this->db->get('tag_type');
        $message['tag'] = $query->result_array();

        $this->db->select('jdid,title');
        $this->db->like('title',$keyword);
        $this->db->limit(3,0);
        $query = $this->db->get('jd_jd');
        $message['jd'] = $query->result_array();

        $this->db->select('tagid,tagname');
        $this->db->like('tagname',$keyword);
        $this->db->limit(3,0);
        $query = $this->db->get('jd_tag');
        $message['jd_tag'] = $query->result_array();
        return TRUE;      
   }

   function search_user(&$message)
   {
   	  $keyword = $this->input->post('keyword');
      $limit = $this->input->post('limit');
      $offset = $this->input->post('offset');
      $this->db->select('uid,realname');
   	  $this->db->like('realname',$keyword);
   	  $this->db->limit($limit,$offset);
   	  $query = $this->db->get('user_profile');
   	  $message = $query->result_array();
   	  return TRUE;
   }

   function search_question(&$message)
   {
   	  $keyword = $this->input->post('keyword');
      $limit = $this->input->post('limit');
      $offset = $this->input->post('offset');
      $this->db->select('id,title');
      $this->db->like('title',$keyword);
      $this->db->limit($limit,$offset);
      $query = $this->db->get('q2a_question');
      $message = $query->result_array();
   	  return TRUE;
   } 

   function search_tag(&$message)
   {
   	   $keyword = $this->input->post('keyword');
       $limit = $this->input->post('limit');
       $offset = $this->input->post('offset');

       $this->db->select('tagid,tagname');
       $this->db->like('tagname',$keyword);
       $this->db->or_like('tagabbr',$keyword);
       $this->db->limit($limit,$offset);
       $query = $this->db->get('tag_type');
       $message = $query->result_array();
   	   return TRUE;
   } 

   function search_jd(&$message)
   {
       $keyword = $this->input->post('keyword');
       $limit = $this->input->post('limit');
       $offset = $this->input->post('offset');

       $this->db->select('jdid,title,content,view_num,answer_num,follow_num,date');
       $this->db->like('title',$keyword);
       $this->db->order_by('date','desc');
       $this->db->limit($limit,$offset);
       $query = $this->db->get('jd_jd');
       $message = $query->result_array();
       return TRUE;
   } 
 } 
?>