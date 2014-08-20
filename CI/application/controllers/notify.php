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

  

/*新通知数*/
   function new_notification_get()
   {
       $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
             $message = '';
             if (!$this->notify_model->new_notification($message))
             {
                $message['state'] = "fail";
             }
             $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }  
   }

/*通知历史*/
  function notify_his_get($type,$limit,$offset)
  {
     $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            if ($this->notify_model->notify_his($message,$type,$limit,$offset))
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

  /*通知下拉框*/
  function notify_show_get($limit,$offset)
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status === 'OK')
     {
         $message ="";
         if ($this->notify_model->notify_show($message,$limit,$offset))
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

  function notify_clear_get($type)
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status === 'OK')
     {
         $message ="";
         if ($this->notify_model->notify_clear($message,$type))
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