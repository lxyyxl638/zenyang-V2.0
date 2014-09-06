<?php
class Jd_home_model extends CI_Model
{
     function __construct()
     {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
     } 
    
     function checkbox_choice(&$message)
     {
        $industry = $this->input->post('industry');
        if (isset($industry) && !empty($industry))
          {
              $this->db->select('tagid,tagname');
              $this->db->where('belong','2');
              $this->db->where('inside',$industry);
              $query = $this->db->get('jd_tag');
              $message['company'] = $query->result_array();

              $this->db->select('tagid,tagname');
              $this->db->where('belong','3');
              $this->db->where('inside',$industry);
              $query = $this->db->get('jd_tag');
              $message['occupation'] = $query->result_array();
              return TRUE;
          }
     }

     function checkbox_show(&$message)
     {
        /*取出行业*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong','1');
        $query = $this->db->get('jd_tag');
        $message['industry'] = $query->result_array();

        // /*取出公司*/
        // $this->db->select('tagid,tagname');
        // $this->db->where('belong',2);
        // $query = $this->db->get('jd_tag');
        // $message['company'] = $query->result_array();

        // /*取出职位*/
        // $this->db->select('tagid,tagname');
        // $this->db->where('belong',3);
        // $query = $this->db->get('jd_tag');
        // $message['occupation'] = $query->result_array();

        /*取出工资*/
        // $this->db->select('tagid,tagname');
        // $this->db->where('belong',5);
        // $query = $this->db->get('jd_tag');
        // $message['salary'] = $query->result_array();

        /*取出地点*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',4);
        $query = $this->db->get('jd_tag');
        $message['place'] = $query->result_array();
        return TRUE;
     }

     function checkbox(&$message)
     {
        if (!empty($_POST['place']))
           {   
               $place = $this->input->post('place');
               $this->db->where('place',$place);
           }

        if (!empty($_POST['industry']))
           {
              $industry = $this->input->post('industry');
              $this->db->where('industry',$industry);
           }
        if (!empty($_POST['company']))
           {   
              $company = $this->input->post('company');
              $this->db->where('company',$company);
           }
        if (!empty($_POST['occupation']))
           {   
              $occupation = $this->input->post('occupation');
              $this->db->where('occupation',$occupation);
           }

        if (!empty($_POST['place']))
           {
              $place = $this->input->post('place');
              $this->db->where('place',$place);
           }

        if (!empty($_POST['salary_down']))
          {
              $salary_down = $this->input->post('salary_down');
              $salary_up = $this->input->post('salary_up');
              $salary_down = (int)$salary_down;
              $salary_up = (int)$salary_up;
              $this->db->where('salary_down >=',$salary_down);
              $this->db->where('salary_up <=',$salary_up);
          }   
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');
        $this->db->limit($limit,$offset);   
        $query = $this->db->get('jd_jd');
        $message = $query->result_array();
        foreach ($message as $key => $value)
         {
             $message[$key]['industry']=$this->get_jd_tag_name($message[$key]['industry']);
             $message[$key]['company']=$this->get_jd_tag_name($message[$key]['company']);
             $message[$key]['occupation']=$this->get_jd_tag_name($message[$key]['occupation']);
             $message[$key]['salary_down']=$message[$key]['salary_down'];
             $message[$key]['salary_up']=$message[$key]['salary_up'];
             $message[$key]['place']=$this->get_jd_tag_name($message[$key]['place']);
             unset($message[$key]['content']); 
             unset($message[$key]['active']);
         }

        if (empty($_POST['industry']) && empty($_POST['company']) && empty($_POST['occupation'])
            && empty($_POST['salary']) && empty($_POST['place']))
        {
            $this->db->order_by('jdid','desc');
            $this->db->limit($limit,$offset);
            $query = $this->db->get('jd_jd');
            $message = $query->result_array();
            foreach ($message as $key => $value)
            {
                $message[$key]['industry']=$this->get_jd_tag_name($message[$key]['industry']);
                $message[$key]['company']=$this->get_jd_tag_name($message[$key]['company']);
                $message[$key]['occupation']=$this->get_jd_tag_name($message[$key]['occupation']);
                $message[$key]['salary_down']=$message[$key]['salary_down'];
                $message[$key]['salary_up']=$message[$key]['salary_up'];
                $message[$key]['place']=$this->get_jd_tag_name($message[$key]['place']);
                unset($message[$key]['content']);
                unset($message[$key]['active']);
            }
        }
        return TRUE;
     }

     function get_jd_tag_name($tagid)
       {
         $this->db->select('tagname');
         $this->db->where('tagid',$tagid);
         $query = $this->db->get('jd_tag');
         if ($query->num_rows() > 0)
         {
           $row =$query->row_array();
           return $row['tagname'];
         }
         else
         {
           return "未填写";
         }
       }
     // function tag_jd_list(&$message,$limit,$offset)
     // {
     //     $uid = $this->session->userdata('uid');
     //     $query = "select * from jd_jd where jdid in (select distinct jdid from jd_tag where tagid in (select tagid from user_jd_tag where uid = $uid)) order by date desc limit $offset,$limit";
     //     $query = $this->db->query($query);
     //     if ($query->num_rows() > 0)
     //     {
     //        $message = $query->result_array();      
     //     }
     //     else
     //     {
     //        $this->db->limit($limit,$offset);
     //        $query = $this->db->get('jd_jd');
     //        $message = $query->result_array();
     //     }
     //     return TRUE;
     // } 
}
?>