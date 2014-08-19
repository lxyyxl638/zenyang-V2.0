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

class Update extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function message_get()
    {
       $message['0']['update'] = "增加了修改个人信息，我的问题，我的回答功能,请完善您的信息吧么么哒~";
       $message['0']['date'] = "2014.8.13";
       $message['1']['update'] = "增加了注册时自动补全功能,更方便了哦么么哒~";
       $message['1']['date'] = "2014.8.14";
       $message['2']['update'] = "增加了搜索功能，请使劲往搜索框打字吧！暂不支持拼音，我们会努力开发哒！么么哒！";
       $message['2']['date'] = "2014.8.15";
       $message['3']['update'] = "敬请期待标签功能哦~有什么建议或者意见请猛戳上面灯泡告诉我们吧~么么哒！";
       $message['3']['date'] = "2014.8.15";
       $this->response($message,200);
    }

}