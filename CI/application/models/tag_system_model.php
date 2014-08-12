<?php
class tag_system_model extends CI_model
{
	function __construct()
	{
		parent::__construct();

	}

	function tag_show(&$message)
	{
		$this->db->select('tag');
		$query = $this->db->get('tag_type');
        $result = $query->result_array();
        foreach ($result as $key => $value)
        {
        	$message[$key] = $value['tag'];
        }
        return TRUE;    
	}

	function user_set_tag(&$message)
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
		  	
		  	 $myuid = $this->session->userdata('uid'); 
             $data = array(
             	             'uid' => $myuid,
             	             'tag' => $value
             	          );
             $this->db->insert('user_tag',$data);
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
}
?>