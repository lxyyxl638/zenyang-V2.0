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
require APPPATH.'/controllers/predis/autoload.php';
class Draft extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('','');
    }

    function save_get($uid,$qid)
    {
       $message = "";
       if ($uid == $this->session->userdata('uid'))
       {
           $redis = new Predis\Client();
           $message['draft'] = $redis->get("draft:$uid:$qid");
       }
       $this->response($message,200);
    }

    function save_post($uid,$qid)
    {
       $message = "";
       if ($uid == $this->session->userdata('uid'))
       {
          $redis = new Predis\Client();
          $content = $this->input->post('content');
          $redis->set("draft:$uid:$qid",$content);
          $message['state'] = "success";
       }
       else
       {
          $message['state'] = "fail";
       }
       $this->response($message,200);
    }
   
    
}