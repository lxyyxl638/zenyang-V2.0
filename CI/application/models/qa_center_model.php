<?php
class QA_center_model extends CI_Model
{
   function __construct()
   {
     parent::__construct();
	   $this->load->database();
	   $this->load->library('session');
     $this->load->model('public_model');
   } 


 function ask(&$message)
  {
     $uid = $this->session->userdata('uid');
     $email = $this->session->userdata('email');
	   $realname = $this->session->userdata('realname');

     if (!empty($_POST['qid']))
     {
         if ($this->form_validation->run('ask') == FALSE)
            { 
                $message['detail'] = form_error('title');
                if (empty($message['detail']))
                   {
                      $message['detail'] = form_error('content');
                   }
                return FALSE;
            } 
         else
            {
                $data = array(
                               'title' => $_POST['title'],
                               'content' => $_POST['content']
                             );
                $this->db->where('uid',$uid);
                $this->db->where('id',$_POST['qid']);
                $this->db->update('q2a_question',$data);
                return TRUE;
            }      
     }

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
                  if (empty($message['detail']))
                    {
                      $message['detail'] = form_error('content');
                    }

                return FALSE;
            } 
	         $data = array(
	                         'uid' => $uid,
	                         'realname' => $realname,
	                         'date' => date('Y-m-d H:i:s',$datetime),
	                         'title' => $this->input->post('title'),
	                         'follow_num' => 0,
	                         'view_num' => 0,
	                         'answer_num' => 0
	                      );
          if (!empty($_POST['content']))
          {
             $data['content'] = $this->input->post('content'); 
          } 
          else
          {
            $data['content'] = "";
          }
          $this->db->set($data,FALSE);
	        if ($this->db->insert('q2a_question'))
	   	       {
	   	      	   $this->db->select('id');
	   	      	   $this->db->order_by("id","desc");
	   	      	   $query = $this->db->get('q2a_question');
                 $row = $query->row_array();
                 $qid = $row['id'];
                 $message['qid'] = $qid;
	   	           $data = array(
	   	    	                    'lastask' => date('Y-m-d H:i:s',$datetime)
	   	    	                  );
	   	    	     $this->db->update('user_profile',$data,array('uid' => $uid));
                
                 /*插入标签*/

                 $tag = $this->input->post('tag');
                 foreach ($tag as $key => $value)
                 {
                    $tagname = $tag[$key]['tagname'];
                    $tagid = $tag[$key]['tagid'];
                    $tagabbr = strtolower($tag[$key]['tagabbr']);
                       
                    if ($tagid == 0)
                    {
                        $tmp = array(
                                      'tagname' => $tagname,
                                      'tagabbr' => $tagabbr,
                                      'review' => 'N',
                                    );
                        $this->db->insert('tag_type',$tmp);
                        $this->db->select('tagid');
                        $this->db->where('tagname',$tagname);
                        $query = $this->db->get('tag_type');
                        $row = $query->row_array();
                        $tagid = $row['tagid'];
                    }
                    $tmp = array(
                                   'qid' => $qid,
                                   'tagid' => $tagid,
                                   'tagname' => $tagname
                                );
                    $this->db->insert('question_tag',$tmp);
                 }
                 /*自动设为关注*/
                $tmp = "";
                $this->question_follow($tmp,$qid);
	              return TRUE;
             }
	      }
	   else 
       {
           $message['detail'] = "timeInterval";
           return FALSE;
       }	
       return TRUE;
  }


	function answer(&$message,$qid)
	{
       if ($this->form_validation->run('answer') === FALSE)
         {
             $message['detail'] = form_error('content');
             return FALSE;
         }

		   $uid = $this->session->userdata('uid');
		   $realname = $this->session->userdata('realname');
       $content = $this->input->post('content');
       if (!empty($_POST['aid']))
       {
           $data = array(
                           'content' => $content
                        );
           $this->db->where('id',$_POST['aid']);
           $this->db->where('uid',$uid);
           $this->db->update('q2a_answer',$data);
           return TRUE;
       }
       $data = array(
       	             'qid' => $qid,
       	             'content' => $content,
       	             'realname' => $realname,
       	             'uid' => $uid,
       	             'good' => 0,
       	             'bad' => 0,
       	             'date' => date('Y-m-d H:i:s',time())
       	           );
       $this->db->insert('q2a_answer',$data);
       $this->db->set('answer_num','answer_num + 1',FALSE);
       $this->db->where('id',$qid);
       $this->db->update('q2a_question');

      /*通知所有关注此问题的人*/
       $this->db->select('uid');
       $this->db->where('qid',$qid);
       $query = $this->db->get('user_question');
       $result = $query->result_array();
       foreach ($result as $key => $value)
        {
            $notify_host = $value['uid'];
            $title = $this->public_model->get_qtitle($qid);
            if ($notify_host == $this->public_model->get_quid($qid))
            {
               $type = 1;
            }
            else
            {
               $type = 2;
            }
            $data = array(
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

	function good($qid,$aid)
	{
		  $uid = $this->session->userdata('uid');
		  $query = $this->db->get_where('answer_vote',array('uid' => $uid,'aid' => $aid));
		  if ($query->num_rows() > 0)
		  {
           /*之前有过评论*/
           $row = $query->row_array();
           if ($row['vote'] == 1)
           {
           	   /*已赞，则取消赞*/
               $this->public_model->unset_notify_good($uid,$qid,$aid);
           	   $this->db->delete('answer_vote',array('uid' => $uid,'aid' => $aid));
               $this->db->set('good','good - 1',FALSE);
               $this->db->where('id',$aid);
               return $this->db->update('q2a_answer');
           }
           else
           {
           	/*已踩*/
               $this->db->delete('answer_vote',array('uid' => $uid,'aid' => $aid));
               $this->db->set('bad','bad - 1',FALSE);
               $this->db->where('id',$aid);
               return $this->db->update('q2a_answer');
           }
		  }
		  else
		  {
              $this->public_model->set_notify_good($uid,$qid,$aid);
              $data = array(
              	        'qid' => $qid,
              	        'uid' => $uid,
              	        'aid' => $aid,
              	        'vote' => 1 
              	         );
              $this->db->insert('answer_vote',$data);
              $data = array(
              	         'uid' => $uid,
              	         'aid' => $aid
              	         );
              
              $this->db->set('good','good + 1',FALSE);
              $this->db->where('id',$aid);
              return $this->db->update('q2a_answer');
		  }
	}

	function bad($qid,$aid)
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->get_where('answer_vote',array('uid' => $uid,'aid' => $aid));
		if ($query->num_rows() > 0)
		{
            /*之前有过评论*/
            $row = $query->row_array();
            if ($row['vote'] == -1)
            {
            	/*已踩*/
            	$this->db->delete('answer_vote',array('uid' => $uid,'aid' => $aid));
                $this->db->set('bad','bad - 1',FALSE);
                $this->db->where('id',$aid);
                return $this->db->update('q2a_answer');
            }
            else
            {
            	/*已赞*/
                $this->public_model->unset_notify_good($uid,$qid,$aid);
                $this->db->delete('answer_vote',array('uid' => $uid,'aid' => $aid));
                $this->db->set('good','good - 1',FALSE);
                $this->db->where('id',$aid);
                return $this->db->update('q2a_answer');
            }
		}
		else
		{
            $data = array(
            	        'qid' => $qid,
            	        'uid' => $uid,
            	        'aid' => $aid,
            	        'vote' => -1 
            	         );
            $this->db->insert('answer_vote',$data);
              $this->db->set('bad','bad + 1',FALSE);
            $this->db->where('id',$aid);
            return $this->db->update('q2a_answer');
		}
	}
	
	function question_follow(&$message,$qid)
	{
		  $uid = $this->session->userdata('uid');
		  $query = $this->db->get_where('user_question',array('uid' => $uid,'qid' => $qid));
		  if ($query->num_rows() > 0)
		  {
		     if (!$this->db->delete('user_question',array('uid' => $uid,'qid' => $qid)))
		     {
		     	  $message['detail'] = "delete fails";
		     	  return FALSE;
		     }			
		     else 
		     {
		     	  $message['follow'] = 'N';
            $this->db->set('follow_num','follow_num - 1',FALSE);
            $this->db->where('id',$qid);
            $this->db->update('q2a_question');
            $this->db->select('follow_num');
            $this->db->where('id',$qid);
            $query = $this->db->get('q2a_question');
            $row = $query -> row_array();
            $message['follow_num'] = $row['follow_num'];
		     	  return TRUE;
		     }
		  }
		  else
		  {
		  	$data = array(
		  		            'uid' => $uid,
		  		            'qid' => $qid,
		  		            'date' => date('Y-m-d H:i:s',time())
		  		         );
		  	if (!$this->db->insert('user_question',$data))
		  	{
		  		$message['detail'] = "insert user_question fails";
		  		return FALSE;
		  	}
		  	else
		  	{
		  	    $message['follow'] = 'Y';
            $this->db->set('follow_num','follow_num + 1',FALSE);
            $this->db->where('id',$qid);
            $this->db->update('q2a_question');
            $this->db->select('follow_num');
            $this->db->where('id',$qid);
            $query = $this->db->get('q2a_question');
            $row = $query -> row_array();
            $message['follow_num'] = $row['follow_num'];
            return TRUE;
		  	}
		  }
	}
   
   function view_question_get(& $message,$qid)
   {
       $this->db->set('view_num','view_num + 1',FALSE);
       $this->db->where('id',$qid);
       $this->db->update('q2a_question');
       $query = $this->db->get_where('q2a_question',array('id' => $qid));
       if ($query->num_rows() > 0)
        {
             $row = $query->row_array();
             $message = $query->row_array();
             $message['location'] = $this->public_model->middle_photo_get($message['uid']);
             $message['follow'] = $this->get_follow($qid);
             $this->db->select('tagname,tagid');
             $this->db->where('qid',$qid);
             $query = $this->db->get('question_tag');
             $message['tag'] = $query->result_array();
             
             $myuid = $this->session->userdata('uid');
             $this->db->set('read',1);
             $this->db->where('qid',$qid);
             $this->db->where('myuid',$myuid);
             $this->db->update('notify_history');

             $this->db->where('uid',$myuid);
             $this->db->where('qid',$qid);
             $this->db->from('q2a_answer');
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

   function view_answer_get(&$message,$qid = 0,$aid = 0,$limit = 10,$offset = 0)
   {
       if ($aid == 0)
        {
           $this->db->order_by("good","desc");
           $this->db->limit($limit,$offset);
           $query = $this->db->get_where('q2a_answer',array('qid' => $qid));
           $result = $query->result_array();
           foreach ($result as $key => $value)
           {
              $value['mygood'] = $this->qa_center_model->get_mygood($value['id']);
              $value['location'] = $this->public_model->middle_photo_get($value['uid']);
              $message[$key] = $value;
           }
           return TRUE;
        }
        else
        {
            $this->db->where('id',$aid);
            $query = $this->db->get('q2a_answer');
            if ($query->num_rows() > 0)
            {
               $message = $query->row_array();
               $message['mygood'] = $this->qa_center_model->get_mygood($aid);
               $uid = $message['uid']; 
               $message['location'] = $this->public_model->middle_photo_get($uid);
               return TRUE;
            }
            else
            {
               $message['detail'] = "Unlogin";
               return FALSE;
            }
        }
   }

   /*检测当前用户是否关注此问题*/
	function get_follow($qid)
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->get_where('user_question',array('uid' => $uid,'qid' => $qid));
		if ($query->num_rows() > 0)
		{
            return 'Y';
		}
		else
		{
            return 'N';
		}
	}

	
    
    function get_mygood($aid)
    {
    	  $uid = $this->session->userdata('uid');
    	  $this->db->select('vote');
    	  $this->db->where('uid',$uid);
    	  $this->db->where('aid',$aid);
    	  $query = $this->db->get('answer_vote');
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