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
        $this->db->select('tagname,tagid');
        $this->db->limit(12,0);
        $this->db->where('review','Y');
        $query = $this->db->get('tag_type');
        $result = $query->result_array();
        foreach ($result as $key => $value)
          {
          	 $tmp = "tagpic/".$value['tagid']."jpg";
          	 $value['tagpic'] = base_url($tmp);
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

	function tag_question_list(&$message,$tagid)
	{
        $query = "select id,title,uid,realname,follow_num,answer_num,view_num,date from q2a_question where id in (select qid from question_tag where tagid = $tagid) order by id desc";
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
   

    function tag_hot_question_list(&$message,$tagid)
    {
        $query = "select id,title,uid,realname,follow_num,answer_num,view_num,date from q2a_question where id in (select qid from question_tag where tagid = $tagid) order by follow_num desc,answer_num desc,id desc";
        $query = $this->db->query($query);
        $result = $query->result_array();
         foreach ($result as $key => $value)
           {
             $uid = $value['uid'];
             $value['location'] = $this->public_model->middle_photo_get($uid);
             $value['best_answer'] = $this->home_model->get_best_answer($value['id']);
             $value['follow'] = $this->qa_center_model->get_follow($value['id']);
             $message[$key] = $value;
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
        	$message['follow'] = 'Y';
        }
        else 
        {
        	$message['follow'] = 'N';
        }
        return TRUE;
    }
}
?>