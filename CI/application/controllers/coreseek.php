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

class Coreseek extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Coreseek_model');
    }
   
   function search_post()
   {
       // $status = $this->session->userdata('status');
       //  if (isset($status) && $status === 'OK')
       //  {
           $message = '';
           if ($this->Coreseek_model->search($message))
            {
                $this->response($message,200);
            }
           else
           {
              $message['state'] = "fail";
              $this->response($message,200);
           }   
        // }    
        // else
        // {
        //     $message['state'] = "fail";
        //     $message['detail'] = "Unlogin";
        //     $this->response($message,200);    
        // }     
    }
}