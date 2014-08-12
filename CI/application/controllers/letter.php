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

class Letter extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('public_model');
        $this->load->model('letter_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }
    

   /*回复或者发站内信*/
  function letter_send_post()
  {
      $status = $this->session->userdata('status');
      if (isset($status) && $status === 'OK')
          {
              $message = '';
              if (!$this->letter_model->letter_send($message))
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

  /*未读信息提醒*/
  function letter_notify_get()
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->letter_model->letter_notify($message))
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

  /*私信主页*/
  function letter_home_get($limit = 10,$offset = 0)
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->letter_model->letter_home($message,$limit,$offset))
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

  /*聊天历史*/
  function letter_talk_get($uid)
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->letter_model->letter_talk($message,$uid))
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
  
  /*全部设为已读*/
  function letter_set_look_get()
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->letter_model->letter_set_look($message))
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