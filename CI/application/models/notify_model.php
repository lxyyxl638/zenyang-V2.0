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
                $data = array(
                                'type' => 2,
                                'myuid' => $uid,
                                'qid' => $qid,
                                'title' => $title,
                                'uid' => $value['uid'],
                                'realname' => $value['realname'],
                                'read' => 'N'
                             );
                $this->db->insert('notify_history',$data);
                $data = array(
                               'flushtime_of_new_answer' => date('Y-m-d,H:i:s',time())
                              )
                $this->db->where('uid',$uid);
                $this->db->where('qid',$qid);
                $this->db->update('user_question',$data);
                unset($value['flushtime_of_new_answer']);
                $message[$key] = $value;
             }
          }
          return TRUE;
     }

     function myanswer_get_good(&$message, &$num_2 = 0,$limit = 0,$offset = 0)
     {
          $uid = $this->session->userdata('uid');
          $tmp_num = 0;
          $this->db->select('id,flushtime_of_myanswer_get_good');
          $this->db->where('uid',$uid);
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
            
             $this->db->order_by('date','desc');
             $query = $this->db->get('answer_vote');
             if ($query->num_rows() > 0)
              {  
                 $this->db->select('qid');
                 $this->db->where('id',$aid);
                 $tmp = $this->db->get_where('q2a_answer');
                 $row = $tmp->row_array();
                 $qid = $row['qid'];
                 $title = $this->public_model->get_qtitle($qid);

                 $tmp_result = $query->result_array();
                 foreach ($tmp_result as $tmp_key => $tmp_value) 
                 {  
                    if ($tmp_num > $limit) 
                      {
                        $num_2 += $tmp_num;
                        break;
                      }
                    $tmp_uid = $tmp_value['uid'];
                    $tmp_realname = $this->public_model->get_realname($tmp_uid);
                    $data = array(
                                    'type' => 3,
                                   'myuid' => $uid,
                                     'qid' => $qid,
                                   'title' => $title,
                                     'uid' => $tmp_uid,
                                'realname' => $tmp_realname,
                                'read' => 'N'
                             );
                    $this->db->insert('notify_history',$data);
                    $message[$tmp_num]['qid'] = $qid;
                    $message[$tmp_num]['title'] = $title;
                    $message[$tmp_num]['uid'] = $tmp_uid;
                    $message[$tmp_num]['realname'] = $tmp_realname;
                    $tmp_num++;
                 }
                 
                $data = array(
                               'flushtime_of_new_answer' => date('Y-m-d,H:i:s',time())
                              )
                $this->db->where('uid',$uid);
                $this->db->where('qid',$qid);
                $this->db->update('user_question',$data); 
              }
              
          }       
          return TRUE;
     }


     function myquestion_new_answer(& $message, & $num_1 = 0,$limit = 0,$offset = 0)
     {
          $uid = $this->session->userdata('uid');
          
          $this->db->select('id,flushtime_of_myquestion_new_answer');
          $this->db->where('uid',$uid);
          $query = $this->db->get('q2a_question');
          $result = $query->result_array();
          $tmp_num_1 = 0;

          foreach ($result as $key => $value)
          {
             $qid = $value['id'];
             $title = $this->public_model->get_qtitle($qid);
             $timepoint = $value['flushtime_of_myquestion_new_answer'];
             
             $this->db->select('uid');
             $this->db->where('date >=',$timepoint);
             $this->db->where('qid',$qid);
             $this->db->order_by('date','desc');
             $query = $this->db->get_where('q2a_answer');
             if ($query->num_rows() > 0)
              {
                 $num_1 += $query->rows_num();
                 $tmp_result = $query->result_array();
                 foreach($tmp_result as $tmp_key => $tmp_value)
                  { 
                     if ($tmp_num_1 < $limit)
                     {
                         $tmp_uid = $tmp_value['uid'];
                         $tmp_realname = $tmp_value['realname'];
                         $message[$tmp_num_1]['qid'] = $qid;
                         $message[$tmp_num_1]['title'] = $this->public_model->get_qtitle($qid);
                         $message[$tmp_num_1]['uid'] = $tmp_uid;
                         $message[$tmp_num_1]['realname'] = $tmp_realname;
                     }
                     $tmp_num_1++;
                  }
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

    function notify_his(&$message,$uid,$type,$limit,$offset) 
    {
        $this->db->where('myuid',$uid);
        $this->db->order_by('id','desc');
        $this->db->limit($limit,$offset);
        $query = $this->db->get('notify_history');
        $message = $query->result_array();
        return TRUE;
    }
};
?>