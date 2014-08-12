<?php

  class Notify_model extends CI_Model{
     
     function __construct()
     {
        parent::__construct();
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->model('public_model');
     }
     


     function follow_new_answer(& $message,& $num_1 = 0,$limit = 0,$offset = 0)
     {
          $uid = $this->session->userdata('uid');
    
          $this->db->select('qid,flushtime_of_new_answer');
          $this->db->where('uid',$uid);
          if ($limit > 0)
          {
            $this->db->limit($limit,$offset);
          }
          $query = $this->db->get('user_question');
          $result = $query->result_array();

          foreach ($result as $key => $value)
          {
             $qid = $value['qid'];
             $this->db->select('title');
             $this->db->where('id',$qid);
             $query = $this->db->get('q2a_question');
             $row = $query-> row_array();
             $title = $row['title'];
             $timepoint = $value['flushtime_of_new_answer'];
             //$message['timepoint'] = $timepoint;
             $this->db->select('uid');
             $this->db->where('qid',$qid);
             $this->db->where('date >=',$timepoint);
             //$this->db->where('uid !=',$uid);
             $this->db->order_by('date','desc');
             $query = $this->db->get('q2a_answer');
             if ($query->num_rows() > 0)
             {
                $num_1 += $query->num_rows();
                $value= $query->result_array();
                $value['qid'] = $qid;
                $value['title'] = $title;
                unset($value['flushtime_of_new_answer']);
                $message[$key] = $value;
             }
          }
          return TRUE;
     }

     function myanswer_get_good(& $message, & $num_2 = 0,$limit = 0,$offset = 0)
     {
          $uid = $this->session->userdata('uid');
          
          $this->db->select('id,flushtime_of_myanswer_get_good');
          $this->db->where('uid',$uid);
          if ($limit > 0)
          {
            $this->db->limit($limit,$offset);
          }
          $query = $this->db->get('q2a_answer');
          $result = $query->result_array();

          foreach ($result as $key => $value)
          {
             $aid = $value['id'];
             $timepoint = $value['flushtime_of_myanswer_get_good'];
               
             $this->db->select('uid');
             $this->db->where('aid',$aid);
             $this->db->where('vote','1');
             $this->db->where('date >=',$timepoint);
            // $this->db->where('uid !=',$uid);
             $this->db->order_by('date','desc');
             $query = $this->db->get('answer_vote');
             if ($query->num_rows() > 0)
              {  
                 $num_2 += $query->num_rows();
                 $this->db->select('qid');
                 $this->db->where('id',$aid);
                 $tmp = $this->db->get_where('q2a_answer');
                 $row = $tmp->row_array();
                 $message[$key] = $query->result_array();
                 $message[$key]['qid'] = $row['qid'];
                 $message[$key]['title'] = $this->public_model->get_qtitle($row['qid']);
              }
              
          }       
          return TRUE;
     }


     function myquestion_new_answer(& $message, & $num_1 = 0,$limit = 0,$offset = 0)
     {
          $uid = $this->session->userdata('uid');
          
          $this->db->select('id,flushtime_of_myquestion_new_answer');
          $this->db->where('uid',$uid);
          if ($limit > 0)
          {
            $this->db->limit($limit,$offset);
          }
          $query = $this->db->get('q2a_question');
          $result = $query->result_array();

          foreach ($result as $key => $value)
          {
             $qid = $value['id'];
             $timepoint = $value['flushtime_of_myquestion_new_answer'];
             
             $this->db->select('uid');
             $this->db->where('date >=',$timepoint);
             $this->db->where('qid',$qid);
             $this->db->order_by('date','desc');
             $query = $this->db->get_where('q2a_answer');
             if ($query->num_rows() > 0)
              {
                 $num_1 += $query->num_rows();
                 $message[$key] = $query->result_array();
                 $message[$key]['qid'] = $qid;
                 $message[$key]['title'] = $this->public_model->get_qtitle($qid);
              }
          }       
          return TRUE;
     }

    function followed(& $message,$num_3,$limit = 0,$offset = 0)
     {
          $myuid = $this->session->userdata('uid');
          $this->db->select('followed_flush_time');
          $this->db->where('uid',$myuid);
          $query = $this->db->get('user_profile');
          $row = $query->row_array();
          $timepoint = $row['followed_flush_time'];
          $this->db->select('follower');
          $this->db->where('master',$myuid);
          $this->db->where('date >',$timepoint);
          $this->db->order_by('date','desc');
          $query = $this->db->get('master_follower');
          $result = $query->result_array();
          foreach ($result as $key => $value)
          {
              $value['realname'] = $this->public_model->uidrealname($value['follower']);
              $message[$key] = $value;   
          }
          return TRUE;
     }
};
?>