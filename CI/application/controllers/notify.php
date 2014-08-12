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

class Notify extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('notify_model');
        $this->load->model('public_model');
        $this->load->helper('url');
        $this->load->helper('form');
    }

  
  

  /*关注问题有了新回答*/
  function follow_new_answer_get($limit = 5,$offset = 0)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            $num_1 = 0;
            if (!$this->notify_model->follow_new_answer($message,$num_1,$limit,$offset))
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

  /*清空关注问题的回答提示*/
  function follow_new_answer_flush_get($qid)
  {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            $uid = $this->session->userdata('uid');
            $this->db->where('uid',$uid);
            $this->db->where('qid',$qid);
            $data = array( 
                           'flushtime_of_new_answer' => date('Y-m-d H:i:s',time())
                         );
            $this->db->update('user_question',$data);
            $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }  
  }

/*我的回答被赞了*/
  function myanswer_get_good_get($limit = 5,$offset = 0)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            $num_1 = 0;
            if (!$this->notify_model->myanswer_get_good($message,$num_1,$limit,$offset))
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

/*清空回答被点赞的提示*/
  function myanswer_get_good_flush_get($qid)
  {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message ='';
            $uid = $this->session->userdata('uid');
            $this->db->where('uid',$uid);
            $this->db->where('qid',$qid);
            $data = array( 
                          'flushtime_of_myanswer_get_good' => date('Y-m-d H:i:s',time())
                        );
            $this->db->update('q2a_answer',$data);
            $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }  
  }

/*我的问题得到新回答*/
  function myquestion_new_answer_get($limit = 5,$offset = 0)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            $num_1 = 0;
            if (!$this->notify_model->myquestion_new_answer($message,$num_1,$limit,$offset))
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
/*清空我的问题得到回答提示*/
  function myquestion_new_answer_flush_get($qid)
  {
       $status = $this->session->userdata('status');
       if (isset($status) && $status === 'OK')
       {
           $message ='';
           $uid = $this->session->userdata('uid');
           $this->db->where('uid',$uid);
           $this->db->where('id',$qid);
           $data = array( 
                         'flushtime_of_myquestion_new_answer' => date('Y-m-d H:i:s',time())
                       );
           $this->db->update('q2a_question',$data);
           $this->response($message,200);
       }
       else
       {
         $message['state'] = "fail";
         $message['detail'] = "Unlogin";
         $this->response($message,200);
       }  
  }

  function followed_get($limit = 5,$offset = 0)
  {
       $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            $num_3 = 0;
            if ($this->notify_model->followed($message,$num_3,$limit,$offset))
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

/*新通知数*/
   function new_notification_get()
   {
       $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
             $message = '';
             $num = 0;
             $num_1 = 0;
             $num_2 = 0;
             $num_3 = 0;
             $this->notify_model->follow_new_answer($message,$num_1,0,0);
             $this->notify_model->myquestion_new_answer($message,$num_1,0,0);
             $this->notify_model->myanswer_get_good($message,$num_2,0,0);
             $this->notify_model->followed($message,$num_3,0,0);
             $message = '';
             $num = $num_1 + $num_2 + $num_3;
             $message['num'] = $num;
             $message['num_1'] = $num_1;
             $message['num_2'] = $num_2;
             $message['num_3'] = $num_3;
             $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }  
   }

}