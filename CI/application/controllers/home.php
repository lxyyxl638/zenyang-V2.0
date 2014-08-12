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

class home extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('home_model');
        $this->load->model('public_model');
        $this->load->model('qa_center_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }

/*显示一个月内的提问*/
	function question_date_list_get($limit = 10,$offset = 0)
    {
        $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           if (!$this->home_model->question_date_get($message,$limit,$offset))
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

/*显示用户关注的话题*/ 
  function question_focus_list_get($limit = 10,$offset = 0)  
    {
        $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           if (!$this->home_model->question_focus_get($message,$limit,$offset))
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

/*显示当日最多浏览*/
  function question_day_list_get($limit = 10,$offset = 0)
   {
     $status = $this->session->userdata('status');

     if (isset($status) && $status === 'OK')
     {
        if (!$this->home_model->question_day_get($message,$limit,$offset))
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

   /*亟待解决的问题*/ 
  function question_hurry_list_get($limit = 10,$offset = 0)  
    {
        $status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           if (!$this->home_model->question_hurry_get($message,$limit,$offset))
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