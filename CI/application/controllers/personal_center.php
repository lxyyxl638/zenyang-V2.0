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

class personal_center extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('personal_center_model');
        $this->load->model('public_model');
        $this->load->helper('url');
    }

/*获取个人信息*/
  function get_profile_get($uid)
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->profile_get($message,$uid))
            {
              $message['state'] = "fail";
              $this->response($message,200);
            }
            else
            {
               $message['state'] = "success";
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
/*个人信息完善*/
	function modify_profile_post()
    {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->modify_profile($message))
            {
            	$message['state'] = "fail";
            	$this->response($message,200);
            }
            else
            {
               $message['state'] = "success";
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
  
  /*我的提问*/
  function my_question_get($uid,$limit = 10,$offset = 0)
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->my_question($message,$uid,$limit,$offset))
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
  /*我的回答*/
  function my_answer_get($uid,$limit = 10,$offset = 0)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->my_answer($message,$uid,$limit,$offset))
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

  /*修改我的回答*/
  function modify_my_answer_post()
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
      {
          $message = '';
          if (!$this->personal_center_model->modify_my_answer($message))
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

  /*我关注的问题*/
  function my_follow_question_get($uid,$limit = 10,$offset = 0)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->my_follow_question($message,$uid,$limit,$offset))
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

  /*取消关注一个人*/
  function follow_get($uid)
  {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->personal_center_model->follow($message,$uid))
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

  function change_password_post()
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status === 'OK')
     {
         $message = '';
         if (!$this->personal_center_model->change_password($message))
         {
            $message['state'] = "fail";
            $this->response($message,200);
         }
         else
         {
            $message['state'] = "success";
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