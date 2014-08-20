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

class Signup extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library(array('session','encrypt'));
        $this->load->model('signup_model');
        $this->form_validation->set_error_delimiters('','');
    }


    function basic_post()
    {
                 
         if ($this->form_validation->run('signup') === FALSE)
          {
             $message['state'] = "fail";
             $message['detail'] = form_error('email');
             if (empty($message['detail'])) 
              { 
                $message['detail'] = form_error('password');
              }
             if (empty($message['detail'])) 
              { 
                $message['detail'] = form_error('firstname');
              }
             if (empty($message['detail'])) 
              { 
                $message['detail'] = form_error('lastname');
              }
            
              $this->response($message,200);
          }
         else 
          { 
            // if ($this->input->post('CDK') != "0811")
            //   {
            //     $message['state'] = "fail";
            //     $message['detail'] = "CDKInvalid";
            //     $this->response($message,200);
            //   }
             $email = $this->input->post('email');
             $password = $this->input->post('password');
             $lastname = $this->input->post('lastname');
             $firstname = $this->input->post('firstname');
             $realname = $lastname.$firstname;
             $password = $this->encrypt->encode($password);
             $signupdate = date('Y-m-d H:i:s',time());
             $data = array( 
                            'email'=> $email,
                            'password'=> $password,
                            'realname' => $realname,
                            'signupdate' => $signupdate,
                            'lastlogin'=> $signupdate,
                            //'lastloginfail'=> date("Y-m-d H:i:s",0),
                            'numloginfail' => 0
                           );
             $this->db->insert('user',$data);
             $query = $this->db->get_where('user',array('email' => $email));
             $row = $query->row_array();
             $temp = array(
                             'uid' => $row['id'], 
                             'photo_upload' => 'N',
                             'realname' => $realname,
                             'lastask' => date("Y-m-d H:i:s",1)
                          );
             $this->db->insert('user_profile',$temp);             
             $query = $this->db->get_where('user',array('email'=>$email));
             $row = $query->row_array();
             $id = $row['id'];
             $newdata = array(
               'email' => $email,
               'password' => $password,
               'uid' => $id,
               'realname' => $realname,
               'status' => 'OK'
               );             
             $this->session->set_userdata($newdata);            
             $message['state'] = 'success';
             $this->response($message,200);
               
         }
            
    }

    function info_post()
    {
       $message = '';
       //$_POST = $this->initial();
       if (!$this->signup_model->info($message))
       {
          $message['state'] = "fail"; 
       }
       else
       {
          $message['state'] = "success";
       }

       $this->response($message,200);
    }

    function more_post()
    {
       $message ='';
      // $_POST = $this->initial();
       if (!$this->signup_model->more($message))
       {
         $message['state'] = "fail";
       }
       else
       {
         $message['state'] = "success";
       }

       $this->response($message,200);
    }

    // function provincelist_post()
    // {
    //    $this->db->select('province');
    //    $query = $this->db->get('user_province');
    //    $data = $query->result_array();
    //    $num = $query->num_rows();
    //    for ($i = 0; $i < $num; $i++)
    //    {
    //      $message[$i] = $data[$i]['province'];
    //    }
    //    $this->response($message,200);
    // }

    function collegelist_post()
    {
       $college = $this->input->post('college');
       $this->db->select('college');
       $this->db->like('college',$college,'after');
       $this->db->or_like('abbreviation',$college,'after');
       $this->db->limit(5,0);
       $query = $this->db->get('user_college');
       $message = $query->result_array();
       $this->response($message,200);
    }

    function majorlist_post()
    {
       $major = $this->input->post('major');
       $this->db->select('major');
       $this->db->like('major',$major,'after');
       $this->db->or_like('abbreviation',$major,'after');
       $this->db->limit(5,0);
       $query = $this->db->get('user_major');
       $message = $query->result_array();
       $this->response($message,200);
    }

    function companylist_post()
    {
       $company = $this->input->post('company');
       $this->db->select('company');
       $this->db->like('company',$company,'after');
       $this->db->or_like('abbreviation',$company,'after');
       $this->db->limit(5,0);
       $query = $this->db->get('user_company');
       $message = $query->result_array();
       $this->response($message,200);
    }
    
    function positionlist_post()
    {
       $position = $this->input->post('position');
       $this->db->select('position');
       $this->db->like('position',$position,'after');
       $this->db->or_like('abbreviation',$position,'after');
       $this->db->limit(5,0);
       $query = $this->db->get('user_position');
       $message = $query->result_array();
       $this->response($message,200);
    }

}