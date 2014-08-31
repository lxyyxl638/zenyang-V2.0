<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Jd_qa_center extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('jd_qa_center_model');
        $this->load->model('public_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }
   
   function question_ask_post()
   {
      $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           $message = "";
           if (!$this->jd_qa_center->question_ask($message))
           {
               $message['state'] = "fail";
               $this->response($message,200);
           }
           else
           {
               $this->response($message,200);
           }
        }
        else
        {
           $message['state'] = "fail";
           $message['detail'] = "Unlogin";
           $this->response($message,200);
        }
   }

  function question_answer_post()
   {
     $status = $this->session->userdata('status');

     if (isset($status) && $status === 'OK')
     {
         if ($this->jd_qa_center_model->question_answer($message) != FALSE)
         {
             $message['state'] = "success";
             $this->response($message,200);
         }
         else
         {
             $message['state'] = "fail";
             $this->response($message,200);     
         }        
     }
     else
     {
        $message['state'] = "fail";
        $message['detail'] = "Unlogin";
        $this->response($message,200);
     }
   }

  /*查看JD内容*/
  function view_jd_get($jdid)
    {  
       if ($this->jd_qa_center_model->view_jd_get($message,$jdid))
         {  
            $message['state'] = "success";
            $this->response($message,200);
         }
       else
         {
            $message['state'] = "fail";
            $this->response($message,200);
         }  
    }

  /*查看JD回答*/
  function view_jd_answer_get($jdid = 0,$aid = 0,$limit = 10,$offset = 0) 
    {
        $status = $this->session->userdata('status');
        $message = "";
        if (isset($status) && $status === 'OK')
        {
           if ($this->jd_qa_center_model->view_jd_answer_get($message,$jdid,$aid,$limit,$offset))
           {  
              $this->response($message,200);
           }
           else
           {
              $message['state'] = "fail";
              $this->response($message,200);
           }
        }
        else
        {
           $message['state'] = "fail";
           $message['detail'] = "Unlogin";
           $this->response($message,200);
        }
    }

  function mark_answer_get($jdid,$qid)
   {
        $status = $this->session->userdata('status');
        $message = "";
        if (isset($status) && $status === 'OK')
        {
           if ($this->jd_qa_center_model->mark_answer($message,$jdid,$aid))
           {  
              $this->response($message,200);
           }
           else
           {
              $message['state'] = "fail";
              $this->response($message,200);
           }
        }
        else
        {
           $message['state'] = "fail";
           $message['detail'] = "Unlogin";
           $this->response($message,200);
        }
    }  


   function good_get($jdid,$aid)
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
      {
          if ($this->jd_qa_center_model->good($jdid,$aid) != FALSE)
          {
             $this->db->select('good,bad');
             $query = $this->db->get_where('jd_answer',array('aid' => $aid));
             if ($query->num_rows() > 0)
             { 
                $message = $query->row_array();
                $message['mygood'] = $this->jd_qa_center_model->get_mygood($aid);
                $message['state'] = "success";
                $this->response($message,200);
             }
             else
             {
                $message['state'] = "fail";
                $message['detail'] = "Unlogin";
                $this->response($message,200);   
             }  
          }
          else
          {
             $message['state'] = "fail";
             $message['detail'] = "Unlogin";
             $this->response($message,200);
          }
      }
      else
      {
         $message['state'] = "fail";
         $message['detail'] = "Unlogin";
         $this->response($message,200);
      }
  }

  function bad_get($jdid,$aid)
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
      {
          if ($this->jd_qa_center_model->bad($jdid,$aid) != FALSE)
          {
             $this->db->select('good,bad');
             $query = $this->db->get_where('jd_answer',array('aid' => $aid));
             if ($query->num_rows() > 0)
             { 
                $message = $query-> row_array();
                $message['mygood'] = $this->jd_qa_center_model->get_mygood($aid);
                $message['state'] = "success";
                $this->response($message,200);
             }
             else
             {
                $message['state'] = "fail";
                $message['detail'] = "Unlogin";
                $this->response($message,200);   
             }  
          }
          else
          {
             $message['state'] = "fail";
             $message['detail'] = "Unlogin";
             $this->response($message,200);
          }
      }
      else
      {
         $message['state'] = "fail";
         $message['detail'] = "Unlogin";
         $this->response($message,200);
      }
  }

/*（取消）关注某个问题*/
  function jd_follow_get($jdid)
  {
      $message = '';
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           if (!$this->jd_qa_center_model->jd_follow($message,$jdid))
           {
              $message['state'] = "fail";
              $this->response($message,200);
           }
           else
           {
              $this->response($message,200);
           }
        }
        else
        {
           $message['state'] = "fail";
           $message['detail'] = "Unlogin";
           $this->response($message,200);
        }
  }
}