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
        $this->load->helper('url');
        $this->form_validation->set_error_delimiters('','');
    }
    
    function tag_show_get()
    {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message = '';
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

    // function tag_search_post()
    // {
    // 	$status = $this->session->userdata('status');

    //     if (isset($status) && $status === 'OK')
    //     {
    //        $message = '';
    //        if ($this->tag_system_model->tag_search($message))
    //        {
    //        	  $this->response($message,200);
    //        }
    //        else
    //        {
    //        	  $message['state'] = "fail";
    //        	  $this->response($message,200);
    //        }
    //     }    
    //     else
    //     {
    //         $message['state'] = "fail";
    //         $message['detail'] = "Unlogin";
    //         $this->response($message,200);    
    //     }
    // }

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


    function tag_question_list_get($tagid,$limit,$offset)
    {
       $message = '';
       if ($this->tag_system_model->tag_question_list($message,$tagid,$limit,$offset))
        {
            $this->response($message,200);
        }
       else
       {
           $message['state'] = "fail";
           $this->response($message,200); 
       }
    }
   
   /*修改问题标签*/
    function tag_modify_post()
    {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           $message['state'] = "success";
           if ($this->tag_system_model->tag_modify($message))
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

    function tag_info_get($tagid)
    {
       if ($this->tag_system_model->tag_info($message,$tagid))
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

    function jd_tag_info_get($tagid)
    {
       if ($this->tag_system_model->jd_tag_info($message,$tagid))
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

    function tag_hot_question_list_get($tagid,$limit,$offset)
    {
       if ($this->tag_system_model->tag_hot_question_list($message,$tagid,$limit,$offset))
       {
           $this->response($message,200);
       }
       else
       {
           $message['state'] = "fail";
           $this->response($message,200);
       }
    }

    function user_tag_get($uid)
    {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
        {
           if ($this->tag_system_model->user_tag_get($message,$uid))
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