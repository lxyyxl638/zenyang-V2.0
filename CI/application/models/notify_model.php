<?php

  class Notify_model extends CI_Model{
     
     function __construct()
     {
        parent::__construct();
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->model('public_model');
     }
     
     function new_notification(&$message)
     {
         $uid = $this->session->userdata('uid');
         /*取出上次通知的时间节点*/
         $this->db->select('last_notify');
         $this->db->where('uid',$uid);
         $query = $this->db->get('user_profile');
         $row = $query->row_array();
         $timepoint = $row['last_notify'];
         
         /*把上次通知之后的所有通知计数*/
         $this->db->where('myuid',$uid);
         $this->db->where('date >',$timepoint);
         $this->db->where('read','0');
         $this->db->from('notify_history');
         $message['num'] = $this->db->count_all_results();
         return TRUE;
     }
  
    function notify_his(&$message,$type,$limit,$offset) 
    {
        $uid = $this->session->userdata('uid');
        $this->db->select('type,qid,title,uid,realname,read');
        $this->db->where('myuid',$uid);
        $this->db->where('type',$type);
        $this->db->order_by('id','desc');
        $this->db->limit($limit,$offset);
        $query = $this->db->get('notify_history');
        $message = $query->result_array();
        return TRUE;
    }

    function notify_show(&$message,$limit,$offset)
    {
        $myuid = $this->session->userdata('uid');
         /*取出上次时间点*/
        $this->db->select('last_notify');
        $this->db->where('uid',$myuid);
        $query = $this->db->get('user_profile');
        $row = $query->row_array();
        $timepoint = $row['last_notify'];

        $this->db->select('type,qid,title,uid,realname,read');
        $this->db->where('myuid',$myuid);
        $this->db->where('date >',$timepoint);
        $this->db->where('read','0');
        $this->db->order_by('date','desc');
        $this->db->limit($limit,$offset);
        $query = $this->db->get('notify_history');
        $message = $query->result_array();

        /*更新通知时间点*/
        $data = array(
                        'last_notify' => date("Y-m-d H:i:s",time())
                     );
        $this->db->where('uid',$myuid);
        $this->db->update('user_profile',$data);
        return TRUE;
    }

    function notify_clear(&$message,$type)
    {
       $myuid = $this->session->userdata('uid');
       $this->db->where('uid',$myuid);
       $this->db->where('type',$type);
       $data = array( 
                      'read' => 1
                    );
       $this->db->update('notify_history',$data);
       return TRUE;
    }
};
?>