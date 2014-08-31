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

class Search_system extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('search_model');
        $this->load->model('public_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }
   
   function search_post()
   {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->search_model->search($message))
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

   function search_user_post()
   {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->search_model->search_user($message))
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

   function search_question_post()
   {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->search_model->search_question($message))
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

   function search_tag_post()
   {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->search_model->search_tag($message))
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

   function search_jd_post()
   {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
           if ($this->search_model->search_jd($message))
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
}