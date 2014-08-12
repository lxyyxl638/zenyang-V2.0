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

class tag_system extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('tag_system_model');
        $this->load->model('public_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }
    
    function tag_show_get()
    {
    	$status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           if ($this->tag_system_model->tag_show($message))
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

    function user_set_tag_post()
    {
    	$status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
           $message['state'] = "success";
           if ($this->tag_system_model->user_set_tag($message))
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

    function question_set_tag_post($qid)
    {
    	$status = $this->session->userdata('status');

        if (isset($status) && $status === 'OK')
        {
        	$message['state'] = "success";
           if ($this->tag_system_model->question_set_tag($message,$qid))
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