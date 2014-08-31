<?php

class Jd_qa_center_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function question_ask(&$message)
	{
		$uid = $this->session->userdata('uid');
      
        if (!empty($_POST['qid']))
        {
            if ($this->form_validation->run('ask') == FALSE)
               { 
                   $message['detail'] = form_error('title');
                   return FALSE;
               } 
            else
               {
                   $data = array(
                                  'title' => $this->input->post('title')
                                );
                   $this->db->where('uid',$uid);
                   $qid = $this->input->post('qid');
                   $this->db->where('id',$qid);
                   $this->db->update('jd_question',$data);
                   return TRUE;
               }      
        }
   
        /*获取上次问问题的时间*/
	    $datetime = time();
        $this->db->select('lastask');
	    $query = $this->db->get_where('user_profile',array('uid' => $uid));
	    $row = $query->row_array();
        $lastask = $row['lastask'];
	    $lastask = strtotime($lastask);
   
	    if (!isset($lastask) || ($datetime - $lastask) > 60)
         {	
             if ($this->form_validation->run('ask') == FALSE)
               { 
                   $message['detail'] = form_error('title');
                   return FALSE;
               } 
             $jdid = $this->input->post('jdid');  
	         $data = array(
	         	             'jdid' => $jdid, 
	                         'uid' => $uid,
	                         'date' => date('Y-m-d H:i:s',time()),
	                         'title' => $this->input->post('title'),
	                         'view_num' => 0,
	                         'answer_num' => 0
	                      );
	       if ($this->db->insert('jd_question',$data))
	          {
	          	   /*更新问问题时间*/
	         	   $this->db->select('qid');
	         	   $this->db->order_by("qid","desc");
	         	   $query = $this->db->get('jd_question');
                   $row = $query->row_array();
                   $qid = $row['qid'];
                   $message['qid'] = $qid;
	               $data = array(
	       	                       'lastask' => date('Y-m-d H:i:s',time())
	       	                    );
	       	       $this->db->update('user_profile',$data,array('uid' => $uid));    
              }
	      }
	    else 
        {
        	/*问问题的时间间隔太短*/
            $message['detail'] = "timeInterval";
            return FALSE;
        }	
        return TRUE;
	}


	function jd_answer(&$message)
	{
       if ($this->form_validation->run('answer') === FALSE)
         {
             $message['detail'] = form_error('content');
             return FALSE;
         }

	   $uid = $this->session->userdata('uid');

	   /*读取输入*/
       $content = $this->input->post('content');
       $qid = $this->input->post('qid');
       $jdid = $this->input->post('jdid');

       /*修改答案*/
       if (!empty($_POST['aid']))
       {
           $data = array(
                           'content' => $content
                        );
           $aid = $this->input->post('aid');
           $this->db->where('aid',$aid);
           $this->db->where('uid',$uid);
           $this->db->update('jd_answer',$data);
           return TRUE;
       }
      /*这个id是否已经回答过一次*/
       $this->db->where('jdid',$jdid);
       $this->db->where('qid',$qid);
       $this->db->where('uid',$uid);
       $this->db->from('jd_answer');
       if ($this->db->count_all_results() > 0)
       {
       	  $message['detail'] = "answerDeny";
       	  return FALSE;
       }

      /*这是新评论/回答*/
       $data = array(
       	             'jdid' => $jdid, 
       	             'qid' => $qid,
       	             'content' => $content,
       	             'uid' => $uid,
       	             'good' => 0,
       	             'bad' => 0,
       	             'date' => date('Y-m-d H:i:s',time())
       	           );
       $this->db->insert('jd_answer',$data);
       
       /*评论/回答数增加*/
       $this->db->set('answer_num','answer_num + 1',FALSE);
       $this->db->where('jdid',$jdid);
       $this->db->update('jd_jd');

       /*某个问题回答数增加*/
       if ($qid > 0)
       {
          $this->db->set('answer_num','answer_num + 1',FALSE);
          $this->db->where('qid',$qid);
          $this->db->update('jd_question');
       }

      /*通知所有关注此JD的人*/
       $this->db->select('uid');
       $this->db->where('jdid',$jdid);
       $query = $this->db->get('jd_user_jd');
       $result = $query->result_array();
       foreach ($result as $key => $value)
        {
            $notify_host = $value['uid'];
            $title = $this->public_model->get_jd_title($jdid);
            $realname = $this->public_model->get_realname($uid);
            if ($qid == 0) 
            {
              $type = 5;
            }
            else
            {
               $type = 6;
            }
            $data = array(
                             'jdid' => $jdid,
                             'myuid' => $notify_host,
                             'type' => $type,
                             'qid' => $qid,
                             'title' => $title,
                             'uid' => $uid,
                             'realname' => $realname,
                             'read' => 0,
                             'date' => date('Y-m-d H:i:s',time())
                         );
            $this->db->insert('notify_history',$data);
        }
       return TRUE;
	}


	function view_jd_get(&$message,$jdid)
    {
   	   /*访问数+1*/
       $this->db->set('view_num','view_num + 1',FALSE);
       $this->db->where('jdid',$jdid);
       $this->db->update('jd_jd');
       $query = $this->db->get_where('jd_jd',array('jdid' => $jdid));
       
       if ($query->num_rows() > 0)
        {
        	 /*取出JD内容*/
             $row = $query->row_array();
             $message = $query->row_array();
             $message['follow'] = $this->get_follow($jdid);

             // $this->db->select('tagname,tagid');
             // $this->db->where('qid',$qid);
             // $query = $this->db->get('question_tag');
             // $message['tag'] = $query->result_array();
             
             // $myuid = $this->session->userdata('uid');
             // $this->db->set('read',1);
             // $this->db->where('qid',$qid);
             // $this->db->where('myuid',$myuid);
             // $this->db->update('notify_history');

             /*判断此JD当前用户是否评论过*/
             $myuid = $this->session->userdata('uid');
             $this->db->where('uid',$myuid);
             $this->db->where('jdid',$jdid);
             $this->db->where('qid',0);
             $this->db->from('jd_answer');
             if ($this->db->count_all_results() > 0)
             {
                $message['answerdeny'] = 'Y';
             }
             else
             {
                $message['answerdeny'] = 'N';
             }
             return TRUE;
        }
        else
        {
            $message['detail'] = "Unlogin";
            return FALSE;
        }  
       return TRUE;
    }

    function view_jd_answer_get(&$message,$jdid = 1,$aid = 0,$limit = 10,$offset = 0)
    {
        if ($aid == 0)
         {
            $this->db->order_by("good","desc");
            $this->db->limit($limit,$offset);
            $query = $this->db->get_where('jd_answer',array('jdid' => $jdid));
            $result = $query->result_array();
            foreach ($result as $key => $value)
            {
               $value['qtitle'] = $this->public_model->get_jd_qtitle($value['qid']);
               $value['mygood'] = $this->jd_qa_center_model->get_mygood($value['aid']);
               $value['location'] = $this->public_model->middle_photo_get($value['uid']);
               $value['realname'] = $this->public_model->get_realname($value['uid']);
               $message[$key] = $value;
            }
            return TRUE;
         }
         else
         {
             $this->db->where('aid',$aid);
             $query = $this->db->get('jd_answer');
             if ($query->num_rows() > 0)
             {
                $message = $query->row_array();
                $message['mygood'] = $this->jd_qa_center_model->get_mygood($aid);
                $uid = $message['uid']; 
                $message['location'] = $this->public_model->middle_photo_get($uid);
                $message['realname'] = $this->public_model->get_realname($uid);
                return TRUE;
             }
             else
             {
                $message['detail'] = "Unlogin";
                return FALSE;
             }
         }
    }

    function mark_answer(&$message,$jdid,$qid)
    {
        /*问题游览数加1*/
        $this->db->set('view_num','view_num + 1',FALSE);
        $this->db->where('qid',$qid);
        $this->db->where('jdid',$jdid);
        $this->db->update('jd_question');

    	/*显示答案*/
    	$this->db->select('aid');
    	$this->db->where('qid',$qid);
    	$this->db->where('jdid',$jdid);
    	$query = $this->db->get('jd_answer');
    	$message = $query->result_array();
    	return TRUE;
    }

    function good($jdid,$aid)
	{
		  $uid = $this->session->userdata('uid');
		  $query = $this->db->get_where('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
		  if ($query->num_rows() > 0)
		  {
                /*之前有过评论*/
                $row = $query->row_array();
                if ($row['vote'] == 1)
                {
                	   /*已赞，则取消赞*/
                    $this->public_model->unset_notify_jd_good($uid,$jdid,$aid);
                 	  $this->db->delete('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
                    $this->db->set('good','good - 1',FALSE);
                    $this->db->where('aid',$aid);
                    return $this->db->update('jd_answer');
                }
                else
                {
                	/*已踩*/
                    $this->db->delete('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
                    $this->db->set('bad','bad - 1',FALSE);
                    $this->db->where('aid',$aid);
                    return $this->db->update('jd_answer');
                }
		  }
		  else
		  {
              $this->public_model->set_notify_jd_good($uid,$jdid,$aid);
              $data = array(
              	            'jdid' => $jdid,
              	            'uid' => $uid,
              	            'aid' => $aid,
              	            'vote' => 1 
              	         );
              $this->db->insert('jd_answer_vote',$data);
              
              $this->db->set('good','good + 1',FALSE);
              $this->db->where('aid',$aid);
              return $this->db->update('jd_answer');
		  }
	}

	function bad($jdid,$aid)
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->get_where('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
		if ($query->num_rows() > 0)
		{
            /*之前有过评论*/
            $row = $query->row_array();
            if ($row['vote'] == -1)
            {
            	/*已踩*/
            	$this->db->delete('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
                $this->db->set('bad','bad - 1',FALSE);
                $this->db->where('aid',$aid);
                return $this->db->update('jd_answer');
            }
            else
            {
            	/*已赞*/
                $this->public_model->unset_notify_jd_good($uid,$jdid,$aid);
                $this->db->delete('jd_answer_vote',array('uid' => $uid,'aid' => $aid));
                $this->db->set('good','good - 1',FALSE);
                $this->db->where('aid',$aid);
                return $this->db->update('jd_answer');
            }
		}
		else
		{
            $data = array(
            	        'jdid' => $jdid,
            	        'uid' => $uid,
            	        'aid' => $aid,
            	        'vote' => -1 
            	         );
            $this->db->insert('jd_answer_vote',$data);
              $this->db->set('bad','bad + 1',FALSE);
            $this->db->where('aid',$aid);
            return $this->db->update('jd_answer');
		}
	}
	
	function jd_follow(&$message,$jdid)
	{
		  $uid = $this->session->userdata('uid');
		  $query = $this->db->get_where('jd_user_jd',array('uid' => $uid,'jdid' => $jdid));
		  if ($query->num_rows() > 0)
		  {
		     if (!$this->db->delete('jd_user_jd',array('uid' => $uid,'jdid' => $jdid)))
		     {
		     	  $message['detail'] = "delete fails";
		     	  return FALSE;
		     }			
		     else 
		     {
		     	  $message['follow'] = 0;
                  $this->db->set('follow_num','follow_num - 1',FALSE);
                  $this->db->where('jdid',$jdid);
                  $this->db->update('jd_jd');
                  $this->db->select('follow_num');
                  $this->db->where('jdid',$jdid);
                  $query = $this->db->get('jd_jd');
                  $row = $query -> row_array();
                  $message['follow_num'] = $row['follow_num'];
		     	  return TRUE;
		     }
		  }
		  else
		  {
		  	    $data = array(
		  	    	            'uid' => $uid,
		  	    	            'jdid' => $jdid,
		  	    	            'date' => date('Y-m-d H:i:s',time()),
		  	    	         );
		  	    if (!$this->db->insert('jd_user_jd',$data))
		  	    {
		  	    	$message['detail'] = "insert user_question fails";
		  	    	return FALSE;
		  	    }
		  	    else
		  	    {
		  	        $message['follow'] = 1;
                    $this->db->set('follow_num','follow_num + 1',FALSE);
                    $this->db->where('jdid',$jdid);
                    $this->db->update('jd_jd');
                    $this->db->select('follow_num');
                    $this->db->where('jdid',$jdid);
                    $query = $this->db->get('jd_jd');
                    $row = $query -> row_array();
                    $message['follow_num'] = $row['follow_num'];
                    return TRUE;
		  	    }
		  }
	}

	function get_follow($jdid)
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->get_where('user_jd',array('uid' => $uid,'jdid' => $jdid));
		if ($query->num_rows() > 0)
		{
            return '1';
		}
		else
		{
            return '0';
		}
	}


	function get_mygood($aid)
    {
    	  $uid = $this->session->userdata('uid');
    	  $this->db->select('vote');
    	  $this->db->where('uid',$uid);
    	  $this->db->where('aid',$aid);
    	  $query = $this->db->get('jd_answer_vote');
    	  if ($query->num_rows()>0)
    	  {
    	  	$row = $query->row_array();
    	  	return $row['vote'];
    	  }
    	  else
    	  {
    	  	return 0;
    	  }
    }

}
?>