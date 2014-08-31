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

class Jd_home extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->load->model('jd_home_model');
        $this->load->model('public_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->form_validation->set_error_delimiters('','');
    }

   function checkbox_post()
   {
       $message = "";
       if ($this->jd_home_model->checkbox($message))
       {
          $this->response($message,200);
       }
       else
       {
          $message['state'] = "fail";
          $this->response($message,200);
       }
   }

   function checkbox_show_get()
   {
      $message = "";
       if ($this->jd_home_model->checkbox_show($message))
       {
          $this->response($message,200);
       }
       else
       {
          $message['state'] = "fail";
          $this->response($message,200);
       }
   }
   
   // function tag_jd_list_get($limit,$offset)
   // {
   //     $status = $this->session->userdata('status');

   //      if (isset($status) && $status === 'OK')
   //      {
   //         if (!$this->jd_home_model->tag_jd_list($message,$limit,$offset))
   //         {
   //             $message['state'] = "fail";
   //             $this->response($message,200);
   //         }
   //         else
   //         {
   //             $this->response($message,200);
   //         }
   //      }
   //      else
   //      {
   //         $message['state'] = "fail";
   //         $message['detail'] = "Unlogin";
   //         $this->response($message,200);
   //      }
   // }
}