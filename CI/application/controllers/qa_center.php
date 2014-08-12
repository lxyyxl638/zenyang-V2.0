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

class qa_center extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('qa_center_model');
        $this->load->model('public_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }
// function initial()
//     {
//          $xml = file_get_contents('php://input');
//          $xml = simplexml_load_string($xml);
//          foreach($xml->children() as $child)
//          { 
//              $_POST[$child->getName()] = "$child";
//          }
//          return $_POST;
//     }


/*提问*/
  function question_ask_post()
    {
        $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           $qid = 0;
           $message = '';
           if ($this->qa_center_model->ask($message,$qid))
            {
                 $this->qa_center_model->tag($qid);
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

  /*查看问题内容*/
  function view_question_get($qid)
    {
        $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->qa_center_model->view_question_get($message,$qid))
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

/*查看问题回答*/
  function view_answer_get($qid = 0,$aid = 0,$limit = 10,$offset = 0)  
    {
        $status = $this->session->userdata('status');
        $message = "";
        if (isset($status) && $status === 'OK')
        {
           if ($this->qa_center_model->view_answer_get($message,$qid,$aid,$limit,$offset))
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

/*添加回答*/
  function question_answer_post($qid)
   {
     $status = $this->session->userdata('status');

     if (isset($status) && $status === 'OK')
     {
         if ($this->qa_center_model->answer($message,$qid) != FALSE)
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

  function good_get($qid,$aid)
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
      {
          if ($this->qa_center_model->good($qid,$aid) != FALSE)
          {
             $this->db->select('good,bad');
             $query = $this->db->get_where('q2a_answer',array('id' => $aid));
             if ($query->num_rows() > 0)
             { 
                $message = $query->row_array();
                $message['mygood'] = $this->qa_center_model->get_mygood($aid);
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

  function bad_get($qid,$aid)
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
      {
          if ($this->qa_center_model->bad($qid,$aid) != FALSE)
          {
             $this->db->select('good,bad');
             $query = $this->db->get_where('q2a_answer',array('id' => $aid));
             if ($query->num_rows() > 0)
             { 
                $message = $query-> row_array();
                $message['mygood'] = $this->qa_center_model->get_mygood($aid);
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
  function question_follow_get($qid)
  {
      $message = '';
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           if (!$this->qa_center_model->question_follow($message,$qid))
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

  function get_answer_get($aid)
  {
      $message = '';
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           if (!$this->qa_center_model->get_answer($message,$aid))
           {
              $message['state'] = "fail";
              $this->response($message,200);
           }
           else
           {
              $message['mygood'] = $this->qa_center_model->get_mygood($aid);
              $this->response($message,200);
           }
        }
        else
        {
           $message['state'] = "fail";
           $message['detail'] = "You didn't login!";
           $this->response($message,200);
        }
  }
}