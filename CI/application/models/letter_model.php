<?php
 // require 'vendor/autoload.php';
 // use Mailgun\Mailgun;
  class Letter_model extends CI_Model{
     
     function __construct()
     {
        parent::__construct();
        $this->load->library('upload');
        $this->load->library('session');
        $this->load->model('public_model');
     }
     

     function letter_send(& $message)
     {
         if ($this->form_validation->run('letter_send') === FALSE)
         {
             $message['detail'] = form_error('letter');
             return FALSE;
         }
         else
         {
             $send_id = $this->session->userdata('uid');
             $rece_id = $this->input->post('uid');
             $letter = $this->input->post('letter');
             

             // if ($rece_id == 1)
             // {
             //      $send_realname = $this->public_model->uidrealname($send_id);
             //      $mg = new Mailgun("key-e7b9c51f08cdfacaf18603c965990109");
             //      $domain = "youzenyang.com";

             //      # Now, compose and send your message.
             //      $mg->sendMessage($domain, array('from'    => 'admin@youzenyang.com', 
             //                                      'to'      => 'zenyangsowhat@163.com', 
             //                                      'subject' => $send_realname."意见反馈", 
             //                                      'text'    => $letter));
             // }


             if ($send_id > $rece_id)
             {
                $tmp = $send_id;
                $send_id = $rece_id;
                $rece_id = $tmp;
             }
             $data = array(
                             'uid_1' => $send_id,
                             'uid_2' => $rece_id,
                             'date' => date('Y-m-d H:i:s',time())
                          );
             
             $query = $this->db->get_where('user_message_date',array('uid_1' => $send_id,'uid_2' => $rece_id));
             if ($query->num_rows() > 0)
             {
                $this->db->where(array('uid_1' => $send_id,'uid_2' => $rece_id));
                $this->db->update('user_message_date',$data);
             }
             else
             {
                if (!$this->db->insert('user_message_date',$data))
                {
                    $message['detail'] = "insert user_message_date fails";
                    return FALSE;
                }
             }

             $query = $this->db->get_where('user_message_date',array('uid_1' => $send_id,'uid_2' => $rece_id));
             $row = $query->row_array();
             $letter_id = $row['id'];
             $send_id = $this->session->userdata('uid');
             $rece_id = $this->input->post('uid');
             $data = array(
                            'letter_id' => $letter_id,
                            'send_id' => $send_id,
                            'rece_id' => $rece_id,
                            'message' => $letter,
                            'look' => 0,
                            'date' => date('Y-m-d H:i:s',time())
                          );
             if (!$this->db->insert('user_message',$data))
             {
                $message['detail'] = "insert wrong";
                return FALSE;
             }
             else
             {
                return TRUE;
             }
         }
     }

     function letter_notify(& $message)
     {
        $myuid = $this->session->userdata('uid');
        $this->db->where('rece_id',$myuid);
        $this->db->where('look','0');
        $message['sum'] = $this->db->count_all_results('user_message');
        return TRUE; 
     }

     function letter_home(& $message,$limit,$offset)
     {
         $myuid = $this->session->userdata('uid');        
         $this->db->where('uid_1',$myuid);
         $this->db->or_where('uid_2',$myuid);
         $this->db->order_by('date','desc');
         $query = $this->db->get('user_message_date',$limit,$offset);
         $message = $query->result_array();

         foreach ($message as $key => $index)
         {
            if ($index['uid_1'] == $myuid) $index['uid'] = $index['uid_2'];
            else $index['uid'] = $index['uid_1'];
            $uid = $index['uid'];
            $this->db->select('realname');
            $query = $this->db->get_where('user_profile',array('id' => $uid));
            $row = $query->row_array();
            $index['realname'] = $row['realname'];
            unset($index['uid_1']);
            unset($index['uid_2']);
            $index['location'] = $this->public_model->middle_photo_get($uid);
            $where = "(rece_id ='$myuid' AND send_id = '$uid') OR (rece_id = '$uid' AND send_id = '$myuid')";
            $this->db->where($where);
            $this->db->from('user_message');
            $index['msg_total'] = $this->db->count_all_results();
            $this->db->where('rece_id',$myuid);
            $this->db->where('send_id',$uid);
            $this->db->where('look','0');
            $this->db->from('user_message');
            $index['msg_unread'] = $this->db->count_all_results();
            unset($index['id']);
            $message[$key] = $index; 
         } 
         return TRUE;
     }

     function letter_talk(& $message,$uid)
     {
         $myuid = $this->session->userdata('uid');
         $where = "(rece_id ='$myuid' AND send_id = '$uid') OR (rece_id = '$uid' AND send_id = '$myuid')";
         $this->db->where($where);
         $this->db->order_by('date','desc');
         $query = $this->db->get('user_message');
         $result = $query -> result_array();
         foreach ($result as $key => $value)
         {
            if ($value['rece_id'] == $myuid) 
            {
                $value['dir'] = 1;
            }
            else
            {
                $value['dir'] = 0;
            }
            unset($value['rece_id']);
            unset($value['send_id']);
            unset($value['letter_id']);
            unset($value['id']);
            unset($value['look']);
            $message[$key] = $value;
         }
         $this->db->where('rece_id',$myuid);
         $this->db->where('send_id',$uid);
         $data = array(
                        'look' => 1
                      );
         return $this->db->update('user_message',$data);
     }

     function letter_set_look(& $message)
     {
        $myuid = $this->session->userdata('uid');
        $this->db->where('rece_id',$myuid);
        $data = array(
                        'look' => 1
                     );
        if (!$this->db->update('user_message',$data))
            {
                $message['detail'] = "update fails";
                return FALSE;
            };
        return TRUE;
     }
};
?>