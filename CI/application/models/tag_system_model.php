<?php
class tag_system_model extends CI_model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('home_model');
		$this->load->model('qa_center_model');
	}

	function tag_show(&$message)
	{
        /*问答系统的tag*/
        $this->db->select('tagname,tagid');
        $this->db->limit(6,0);
        $this->db->where('review','Y');
        $query = $this->db->get('tag_type');
        $result = $query->result_array();
        foreach ($result as $key => $value)
          {
          	 $tmp = "tagpic/".$value['tagid'].".jpg";
          	 $value['tagpic'] = base_url($tmp);
             $message['other'][$key] = $value;
          }
        
        /*JD的tag*/
        $this->db->select('tagname,tagid');
        $this->db->where('belong',1);
        $this->db->or_where('belong',2);
        $this->db->or_where('belong',3);
        $this->db->limit(6,0);
        $this->db->where('active',1);
        $query = $this->db->get('jd_tag');
        $result = $query->result_array();
        foreach ($result as $key => $value) 
        {
            $tmp = "jd_tagpic/".$value['tagid'].".jpg";
            $value['tagpic'] = base_url($tmp);
            $message['jd'][$key] = $value;
        }
        return TRUE;
	}

    function tag_search(&$message)
    {
   	    $keyword = $this->input->post('keyword');
   	    $query = "select tagid,tagname from tag_type where review = 'Y' AND (tagname like (\"%$keyword%\") OR tagabbr like (\"%$keyword%\"))";
   	    $query = $this->db->query($query);
   	    $message = $query->result_array();
        return TRUE;      
   }

	function user_set_tag(&$message)
	{
      /*普通问答的tag选择*/
		  $tag = $this->input->post('tag');
		  $uid = $this->session->userdata('uid');
		  foreach ($tag as $key => $value)
        {
            $tagname = $tag[$key]['tagname'];
              $tagid = $tag[$key]['tagid'];

              $this->db->where('uid',$uid);
              $this->db->where('tagid',$tagid);
              $this->db->from('user_tag');
              if ($this->db->count_all_results() > 0)
              {
                  $this->db->where('uid',$uid);
                  $this->db->where('tagid',$tagid);
                  $this->db->delete('user_tag');
              }
              else
              {
                $tmp = array(
                             'uid' => $uid,
                             'tagid' => $tagid,
                             'tagname' => $tagname
                          );
                $this->db->insert('user_tag',$tmp);
              }
           }
      
      /*JD的tag选择*/
      $tag = $this->input->post('jd_tag');
      $uid = $this->session->userdata('uid');
      foreach ($tag as $key => $value)
        {
            $tagname = $tag[$key]['tagname'];
            $tagid = $tag[$key]['tagid'];

              $this->db->where('uid',$uid);
              $this->db->where('tagid',$tagid);
              $this->db->from('jd_user_tag');
              if ($this->db->count_all_results() > 0)
              {
                  $this->db->where('uid',$uid);
                  $this->db->where('tagid',$tagid);
                  $this->db->delete('jd_user_tag');
              }
              else
              {
                $tmp = array(
                             'uid' => $uid,
                             'tagid' => $tagid,
                             'tagname' => $tagname
                          );
                $this->db->insert('jd_user_tag',$tmp);
              }
          }     
		  return TRUE;
	}

	function question_set_tag(&$message,$qid)
	{
		foreach ($_POST as $key => $value)
		  {
		  	 $this->db->where('tag',$value);
		  	 $this->db->from('tag_type');
		  	 if ($this->db->count_all_results() == 0)
		  	 {
		  	 	$data = array(
		  	 		      'tag' => $value
		  	 		    );
		  	 	$this->db->insert('tag_type',$data);
		  	 }
             $data = array(
             	             'qid' => $qid,
             	             'tag' => $value
             	          );
             $this->db->insert('question_tag',$data);
		  }
		return TRUE;
	}

	function tag_modify(&$message)
	{
		$qid = $this->input->post('qid');
		$tag = $this->input->post('tag');
		$this->db->where('qid',$qid);
        $this->db->delete('question_tag');        
        foreach ($tag as $key => $value)
         {
            $tagname = $tag[$key]['tagname'];
            $tagid = $tag[$key]['tagid'];
            
            if ($tagid == 0)
            {
            	$tagabbr = strtolower($tag[$key]['tagabbr']);
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
         return TRUE;
	}

	function tag_question_list(&$message,$tagid,$limit,$offset)
	{
        $query = "select id,title,uid,realname,follow_num,answer_num,view_num,date from q2a_question where id in (select qid from question_tag where tagid = $tagid) order by id desc limit $offset,$limit";
        $query = $this->db->query($query);
        $result = $query->result_array();
         foreach ($result as $key => $value)
           {
             $uid = $value['uid'];
             $value['location'] = $this->public_model->middle_photo_get($uid);
             $value['follow'] = $this->qa_center_model->get_follow($value['id']);
             $message[$key] = $value;
           }
        return TRUE;
	}
   

    function tag_hot_question_list(&$message,$tagid,$limit,$offset)
    {
        $query = "select id,title,uid,realname,follow_num,answer_num,view_num,date from q2a_question where answer_num > 0 AND id in (select qid from question_tag where tagid = $tagid) order by follow_num desc,answer_num desc,id desc limit $offset,$limit";
        $query = $this->db->query($query);
        $result = $query->result_array();
         foreach ($result as $key => $value)
           {

             $message[$key] = $this->home_model->get_best_answer($value['id']);
             $message[$key]['follow'] = $this->qa_center_model->get_follow($value['id']);
             $message[$key]['title'] = $value['title'];
             $message[$key]['qid'] = $value['id'];
           }
        return TRUE;
    }

	function tag_info(&$message,$tagid)
    {
    	$uid = $this->session->userdata('uid');
        $this->db->select('tagname,tagbio');
        $this->db->where('tagid',$tagid);
        $query = $this->db->get('tag_type');
        $message = $query->row_array();
        $message['tagpic'] = base_url("tagpic/$tagid.jpg");
        $this->db->where('uid',$uid);
    	$this->db->where('tagid',$tagid);
    	$this->db->from('user_tag');
        if ($this->db->count_all_results() > 0)
        {
        	$message['follow'] = 1;
        }
        else 
        {
        	$message['follow'] = 0;
        }
        return TRUE;
    }
  
  function jd_tag_info(&$message,$tagid)
    {
        $uid = $this->session->userdata('uid');
        $this->db->select('tagname');
        $this->db->where('tagid',$tagid);
        $query = $this->db->get('jd_tag');
        $message = $query->row_array();
        $message['tagpic'] = base_url("jd_tagpic/$tagid.jpg");
        $this->db->where('uid',$uid);
      $this->db->where('tagid',$tagid);
      $this->db->from('jd_user_tag');
        if ($this->db->count_all_results() > 0)
        {
          $message['follow'] = 1;
        }
        else 
        {
          $message['follow'] = 0;
        }
        return TRUE;
    }

  function user_tag_get(& $message,$uid)
  {
      /*普通回答的标签*/
      $this->db->select('tagid,tagname');
      $this->db->where('uid',$uid);
      $query = $this->db->get('user_tag');
      $message['tag'] = $query->result_array();

      /*JD的标签*/
      $this->db->select('tagid,tagname');
      $this->db->where('uid',$uid);
      $query = $this->db->get('jd_user_tag');
      $result = $query->result_array();
      foreach ($result as $key => $value) 
      {
         $this->db->select('belong');
         $this->db->where('tagid',$value['tagid']);
         $query = $this->db->get('jd_tag');
         $row = $query->row_array();
         switch ($row['belong'])
         {
             case "1":
                   $message['jd_tag']['industry'][] = $value;
                   break;
             case "2":
                   $message['jd_tag']['company'][] = $value;
                   break;
             case "3":
                   $message['jd_tag']['occupation'][] = $value;
                   break;        
         }
      }
      return TRUE;
  }
}
?>