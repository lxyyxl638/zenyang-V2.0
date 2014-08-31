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
          $this->db->select('id,title,uid,realname,follow_num,answer_num,view_num,date');
          $this->db->order_by("date","desc");
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

     function question_tag_list(&$message,$limit,$offset)
     {
         $uid = $this->session->userdata('uid');
         $order = "select * from q2a_question where id in (select distinct qid from question_tag where tagid in (select tagid from user_tag where uid = $uid)) order by date desc limit $offset, $limit";
         $query = $this->db->query($order);
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

     function user_tag_list(& $message,$limit,$offset)
     {
          $uid = $this->session->userdata('uid');
          $order = "select id,title from q2a_question where answer_num > 0 AND id in (select distinct qid from question_tag where tagid in (select tagid from user_tag where uid = $uid)) order by follow_num desc,answer_num desc,date desc limit $offset, $limit";
          $query = $this->db->query($order);
          $result = $query->result_array();
           foreach ($result as $key => $value)
           {
              $message[$key] = $this->get_best_answer($value['id']);
              $message[$key]['follow'] = $this->qa_center_model->get_follow($value['id']);
              $message[$key]['title'] = $value['title'];
              $message[$key]['qid'] = $value['id'];
           }
           return TRUE;
     }
    
     function question_day_get(& $message,$limit,$offset)
     {
         $time_point = date('Y-m-d H:i:s',time() - 60*60*24*30);
         $this->db->select('id,title,uid,realname,follow_num,answer_num,view_num,date');
         $this->db->where('date >',$time_point);
         $this->db->order_by('date','desc');
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
     
     // function question_hurry_get(& $message,$limit,$offset)
     // {
     //     $time_point = date('Y-m-d H:i:s',time() - 60*60*24);
     //     $this->db->select('id,title,uid,content,realname,follow_num,answer_num,view_num,date');
     //     $this->db->order_by("follow_num","desc");
     //     $this->db->limit($limit,$offset);
     //     $query = $this->db->get('q2a_question');
     //     $result = $query->result_array();
     //     foreach ($result as $key => $value)
     //       {
     //           $uid = $value['uid'];
     //           $value['location'] = $this->public_model->middle_photo_get($uid);
     //           $value['best_answer'] = $this->get_best_answer($value['id']);
     //           $value['follow'] = $this->qa_center_model->get_follow($value['id']);
     //           $message[$key] = $value;
     //       }
     //    return TRUE;
     // }
      
      function question_hurry_list(& $message,$limit,$offset)
      {
          $this->db->limit($limit,$offset);
          $this->db->order_by('good','desc');
          $query = $this->db->get('q2a_answer');
          $result = $query->result_array();
          foreach($result as $key => $value)
          {
              $aid = $value['id'];
              $message[$key] = $value;
              $qid = $value['qid'];
              $message[$key]['mygood'] = $this->qa_center_model->get_mygood($aid);
              $message[$key]['title'] = $this->public_model->get_qtitle($qid);
              $message[$key]['location'] = $this->public_model->middle_photo_get($value['uid']);
              $message[$key]['follow'] = $this->qa_center_model->get_follow($qid);
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
             $row['mygood'] = $this->qa_center_model->get_mygood($row['id']);
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