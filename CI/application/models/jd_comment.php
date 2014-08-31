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
class Jd_comment extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('public_model');
        $this->form_validation->set_error_delimiters('','');
    }

    
   function user_comment_post()
    {
       if ($this->form_validation->run('comment') == FALSE)
       {
          $message['state'] = "fail";
          $message['detail'] = "LengthInvalid";
       }
       else
       {
          $data = array(
                           'type' => $this->input->post('type'),
                           'rece_id' => $this->input->post('rece_id'),
                           'send_id' => $this->input->post('send_id'),
                           'jdid' => $this->input->post('jdid'),
                           'aid' => $this->input->post('aid'),
                           'comment' => $this->input->post('comment'),
                           'date' => date('Y-m-d:H:i:s',time())
                        );
          $aid = $this->input->post('aid');
          $redis = new Predis\Client();
          $str = implode(' ', $data);
          $redis->rpush("jd_comment:$aid",$str);
          // $data = array(
          //                 'rece_id' => $rece_id,
          //                 'send_id' => $send_id,
          //                 'qid' => $qid,
          //                 'aid' => $aid,
          //                 'date' => $date
          //              );
          // $this->db->insert('user_comment',$data);
          // $this->db->set('comment_num','comment_num + 1',FALSE);
          // $this->db->where('id',$aid);
          // $this->db->update('q2a_answer');
          $message['state'] = "success";
       }

       $this->response($message,200);
    }

    function user_comment_get($aid,$limit,$offset)
    {
        $status = $this->session->userdata('status');
        if (isset($status) && $status === 'OK')
            {
                $limit = (int) $limit;
                $offset = (int) $offset;
                $redis = new Predis\Client();
                // if ($redis->exists('comment:$aid'))
                // {
                    $message = "";
                    $data = $redis->lrange("jd_comment:$aid",$offset,$limit);
                
                    foreach ($data as $key => $value)
                     {
                        $tmp = explode(' ', $value);
                        $message[$key]['type'] = $tmp[0];
                        $message[$key]['rece_id'] = $tmp[1];
                        $message[$key]['send_id'] = $tmp[2];
                        $message[$key]['comment'] = $tmp[5];
                        $message[$key]['date'] = $tmp[6];
                        $message[$key]['rece_realname'] = $this->public_model->get_realname($tmp[1]);
                        $message[$key]['send_realname'] = $this->public_model->get_realname($tmp[2]);
                        $message[$key]['location'] = $this->public_model->middle_photo_get($tmp[2]);
                      }
                // }   
                // else
                // {
                //   $message = "";
                // } 
                $this->response($message,200);
            }
            else
            {
               $message['state'] = "fail";
               $message['detail'] = "Unlogin";
               $this->response($message,200);
            }     
    }
    
}