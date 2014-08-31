<?php
class Jd_home_model extends CI_Model
{
     function __construct()
     {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
     } 
    
     function checkbox_show(&$message)
     {
        /*取出行业*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',1);
        $query = $this->db->get('jd_tag');
        $message['industry'] = $query->result_array();

        /*取出公司*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',2);
        $query = $this->db->get('jd_tag');
        $message['company'] = $query->result_array();

        /*取出职位*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',3);
        $query = $this->db->get('jd_tag');
        $message['occupation'] = $query->result_array();

        /*取出工资*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',4);
        $query = $this->db->get('jd_tag');
        $message['salary'] = $query->result_array();

        /*取出地点*/
        $this->db->select('tagid,tagname');
        $this->db->where('belong',5);
        $query = $this->db->get('jd_tag');
        $message['place'] = $query->result_array();
        return TRUE;
     }

     function checkbox(&$message)
     {
        if (!empty($_POST['place']))
           {   
               $place = $this->input->post('place');
               $this->db->select('jdid');
               $this->db->where('tagid',$place);
               $query = $this->db->get('jd_belong_tag');
               $result = $query->result_array();
               foreach ($result as $key => $value)
               {
                  $tmp[$key] = $value['jdid'];
               }
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
        if (!empty($_POST['salary']))
           {   
               $salary = $this->input->post('salary');
               $this->db->where('salary',$salary); 
           }
        if (!empty($_POST['place']))
           {
                $this->db->where_in('jdid',$tmp);
           }
        $query = $this->db->get('jd_jd');
        $message = $query->result_array();
        foreach ($message as $key => $value)
         {
             unset($message[$key]['industry']);
             unset($message[$key]['company']);
             unset($message[$key]['occupation']);
             unset($message[$key]['salary']);
             unset($message[$key]['active']);
         }
        return TRUE;
     }

     function tag_jd_list(&$message,$limit,$offset)
     {
         $uid = $this->session->userdata('uid');
         $query = "select * from jd_jd where jdid in (select distinct jdid from jd_tag where tagid in (select tagid from user_jd_tag where uid = $uid)) order by date desc limit $offset,$limit";
         $query = $this->db->query($query);
         if ($query->num_rows() > 0)
         {
            $message = $query->result_array();      
         }
         else
         {
            $this->db->limit($limit,$offset);
            $query = $this->db->get('jd_jd');
            $message = $query->result_array();
         }
         return TRUE;
     } 
}
?>