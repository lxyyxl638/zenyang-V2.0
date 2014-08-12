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
      $this->db->limit(3,0);
      $query = $this->db->get('q2a_question');
      $message['question'] = $query->result_array();

      $this->db->select('id,tag');
      $this->db->like('tag',$keyword);
      $this->db->limit(3,0);
      $query = $this->db->get('tag_type');
      $message['tag'] = $query->result_array();

      return TRUE;      
   }

   function search_user(&$message,$limit,$offset)
   {
   	  $keyword = $this->input->post('keyword');
      $this->db->select('uid,realname');
   	  $this->db->like('realname',$keyword);
   	  $this->db->limit($limit,$offset);
   	  $query = $this->db->get('user_profile');
   	  $message = $query->result_array();
   	  return TRUE;
   }

   function search_question(&$message,$limit,$offset)
   {
   	  $keyword = $this->input->post('keyword');
      $this->db->select('id,title');
      $this->db->like('title',$keyword);
      $this->db->limit($limit,$offset);
      $query = $this->db->get('q2a_question');
      $message = $query->result_array();
   	  return TRUE;
   } 

   function search_tag(&$message,$limit,$offset)
   {
   	  $keyword = $this->input->post('keyword');
      $this->db->select('id,tag');
      $this->db->like('tag',$keyword);
      $this->db->limit($limit,$offset);
      $query = $this->db->get('tag_type');
      $message = $query->result_array();
   	  return TRUE;
   } 
 } 
?>