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

class Public_function extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->model('public_model');
    }
   
	function myinfo_get()
    {
    	  $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message['myrealname'] = $this->session->userdata('realname');
           $message['myuid'] = $this->session->userdata('uid');
           $uid = $this->session->userdata('uid');
           $message['location_small'] = $this->public_model->small_photo_get($uid);
           $message['location_middle'] = $this->public_model->middle_photo_get($uid);
           $message['location_large'] = $this->public_model->large_photo_get($uid);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
    }
 

    function uidinfo_get($uid)
    {
    	$status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
            $message = '';
            if (!$this->public_model->uidrealname($message,$uid))
            {
              $message['state'] = "fail";
              $this->response($message,200);
            }
            else
            {
              $message['location'] = $this->public_model->small_photo_get($uid);
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

   
/*得到100*100的大头像*/
    function large_photo_get($uid)
    {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message['location'] = $this->public_model->large_photo_get($uid);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
   }
/*得到38*38的大头像*/
   function middle_photo_get($uid)
    {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           $message['location'] = $this->public_model->middle_photo_get($uid);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
   }
/*得到27*27的大头像*/
   function small_photo_get($uid)
    {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           $message['location'] = $this->public_model->small_photo_get($uid);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
   }

   function tiny_photo_get($uid)
    {
      $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           $message['location'] = $this->public_model->tiny_photo_get($uid);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
   }

   function upload_post()
   {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        { 
           $message ='';
           $this->public_model->upload($message);
           $this->response($message,200);
        }
        else
        {
          $message['state'] = "fail";
          $message['detail'] = "Unlogin";
          $this->response($message,200);
        }
   }

   function tag_info_get($tagid)
   {
       if ($this->public_model->tag_info($message,$tagid))
       {
          $message['state'] = "success";
          $this->response($message,200);
       }
       else
       {
          $message['state'] = "fail";
          $this->response($message,200);
       }
   }
    
}