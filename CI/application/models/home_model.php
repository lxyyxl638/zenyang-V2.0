<?php
class home_model extends CI_Model
{
     function __construct()
     {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
     } 
    
     
     function question_date_get(& $message,$limit,$offset)
       {
          $time_point = date('Y-m-d H:i:s',time() - 30 * 60 * 60 * 24);
          $this->db->select('id,title,uid,realname,follow_num,answer_num,view_num,date');
          $this->db->where('date >',$time_point);
          $this->db->order_by("date","desc");
          $this->db->limit($limit,$offset);
          $query = $this->db->get('q2a_question');
          $result = $query->result_array();
          foreach ($result as $key => $value)
            {
              $uid = $value['uid'];
              $value['location'] = $this->public_model->middle_photo_get($uid);
              $value['best_answer'] = $this->get_best_answer($value['id']);
              $value['follow'] = $this->qa_center_model->get_follow($value['id']);
              $message[$key] = $value;
            }
          return TRUE;
       }
     
     function question_focus_get(& $message,$limit,$offset)
     {
          $uid = $this->session->userdata('uid');
          $order = "select * from q2a_question where id in (select distinct     qid from tag_question where tid in (select tid from user_tag     where uid = 5)) order by date desc";
          $this->db->limit($limit,$offset);
          $query = $this->db->query($order);
          $result = $query->result_array();
           foreach ($result as $key => $value)
           {
              $uid = $value['uid'];
              $value['location'] = $this->public_model->middle_photo_get($uid);
              $value['best_answer'] = $this->get_best_answer($value['id']);
              $value['follow'] = $this->qa_center_model->get_follow($value['id']);
              $message[$key] = $value;
           }
           return TRUE;
     }
    
     function question_day_get(& $message,$limit,$offset)
     {
         $time_point = date('Y-m-d H:i:s',time() - 60*60*24);
         $this->db->select('id,title,uid,realname,follow_num,answer_num,view_num,date')    ;
         $this->db->where('date >',$time_point);
         $this->db->order_by("view_num","desc");
         $this->db->limit($limit,$offset);
         $query = $this->db->get('q2a_question');
         $result = $query->result_array();
         foreach ($result as $key => $value)
           {
               $uid = $value['uid'];
               $value['location'] = $this->public_model->middle_photo_get($uid);
               $value['best_answer'] = $this->get_best_answer($value['id']);
               $value['follow'] = $this->qa_center_model->get_follow($value['id']);
               $message[$key] = $value;
            }
        return TRUE;
     }
     
     function question_hurry_get(& $message,$limit,$offset)
     {
         $time_point = date('Y-m-d H:i:s',time() - 60*60*24);
         $this->db->select('id,title,uid,content,realname,follow_num,answer_num,view_num,date')    ;
         $this->db->order_by("follow_num","desc");
         $this->db->limit($limit,$offset);
         $query = $this->db->get('q2a_question');
         $result = $query->result_array();
         foreach ($result as $key => $value)
           {
               $uid = $value['uid'];
               $value['location'] = $this->public_model->middle_photo_get($uid);
               $value['follow'] = $this->qa_center_model->get_follow($value['id']);
               $message[$key] = $value;
            }
        return TRUE;
     }
     function get_best_answer($qid)
     { 
         $this->db->select('id,uid,content,realname,good,bad,date');
         $this->db->where('qid',$qid);
         $this->db->order_by('good desc,bad asc');
         $query = $this->db->get('q2a_answer');
         if ($query->num_rows() > 0)
         {
             $row = $query->row_array();
             $row['location'] = $this->public_model->middle_photo_get($row['uid']);
             $row['follow'] = $this->qa_center_model->get_mygood($row['id']);
             return $row;
         }
         else
         {
             return "";
         }
     }

     function get_answer(& $message,$aid)
     {
         $this->db->where('id',$aid);
         $query = $this->db->get('q2a_answer');
         $message = $query->row_array();
         return TRUE;
     }
}
?>