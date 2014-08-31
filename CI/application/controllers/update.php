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
       $message['0']['update'] = "话题功能已经上线啦！您可以通过关注话题来获取你感兴趣的问题和答案啦！赶紧来关注话题吧！（您可以通过搜索框搜话题，试着输入“金融”吧！也可以通过点击问题中的标签进入哦！感谢您陪“怎样”共同成长，么么哒~）";
       $message['0']['date'] = "2014.8.20";
       
       $this->response($message,200);
    }

}