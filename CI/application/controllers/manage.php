<?php

class Manage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('form_validation');
	}

	function index()
	{
		$this->load->view('index');
	}
	function home()
	{
		$this->load->view('home');
	}

	function navbar()
	{
		$this->load->view('navbar');
	}

	function header()
	{
		$this->load->view('header');
	}

	function view_user()
	{
		$id = $this->input->post('id');
		if (isset($id) && !empty($id))
		{
			// $this->db->set('active','Y');
			// $this->db->where('id',$id);
			// $this->db->update('user_profile');

			$this->db->set('active','N');
			$this->db->where('id',$id);
			$this->db->update('user');
		}
		$query = "select user.id,user.email,user.realname,user.lastlogin,user_profile.job,user_profile.jobplace from user,user_profile where user.id=user_profile.id and user.active='Y'";
		$query = $this->db->query($query);
		$result['user'] = $query->result_array();
		$this->load->view('view_user',$result);
	}

	function view_jd()
	{
		$jdid = $this->input->post('jdid');
		if (isset($jdid) && !empty($jdid))
		{
			$this->db->set('active','0');
			$this->db->where('jdid',$jdid);
			$this->db->update('jd_jd');
		}
		$query = "select jdid,occupation,industry,company,place from jd_jd where active=1";
		$query = $this->db->query($query);
		$result['jd'] = $query->result_array();
        foreach ($result['jd'] as $key => $value) 
        {
            $result['jd'][$key]['occupation'] = $this->get_jd_tag_name($value['occupation']);
            $result['jd'][$key]['industry'] = $this->get_jd_tag_name($value['industry']);
            $result['jd'][$key]['company'] = $this->get_jd_tag_name($value['company']);
            $result['jd'][$key]['place'] = $this->get_jd_tag_name($value['place']);    
        }
		$this->load->view('view_jd',$result);
	}



	function add_jd()
    {
    	  if ($this->form_validation->run('add_jd') != FALSE)
    	  {
    	        $industry = $this->input->post('industry');
                $company = $this->input->post('company');
                $occupation = $this->input->post('occupation');
                $salary_down = $this->input->post('salary_down');
                $salary_up = $this->input->post('salary_up');
                $place = $this->input->post('place');
                $content=$this->input->post('content');
		        $data = array(
                				'industry' => $industry,
                				'company' => $company,
                				'occupation' => $occupation,
                				'salary_down' => $salary_down,
                                'salary_up' => $salary_up,
                                'place' => $place,
     	                        'content' => $content,
     	                        'date' => date('Y-m-d:H:i:s',time())
      	               );
                $this->db->insert('jd_jd',$data);
		        // $this->db->insert('jd_jd',$data);
          //       $this->db->select('jdid');
          //       $this->db->order_by('jdid','desc');
          //       $this->db->limit(1,0);
          //       $query = $this->db->get('jd_jd');
          //       $row = $query->row_array();
          //       $jdid = $row['jdid'];
                // $place = explode(' ', $place);
                // foreach ($place as $key => $value)
                // {
                //    $tmp = $this->get_jd_tag($value);	
                //    $data = array(
                //    	              'jdid' => $jdid,
                //    	              'tagid' => $tmp
                //    	            );
                //    $this->db->insert('jd_belong_tag',$data);	
                // }
              
                // $data = array(
                // 	           'jdid' => $jdid,
                //                'tagid' => $industry
                //              );
                // $this->db->insert('jd_belong_tag',$data);
                // $data = array(
                // 	           'jdid' => $jdid,
                //                'tagid' => $company
                //               );
                // $this->db->insert('jd_belong_tag',$data);
                // $data = array(
                // 	            'jdid' => $jdid,
                //                'tagid' => $occupation
                //               );
                // $this->db->insert('jd_belong_tag',$data);
                
                // $data = array(
                // 	            'jdid' => $jdid,
                //                'tagid' => $salary
                //               );
                // $this->db->insert('jd_belong_tag',$data); 
          }
          else
          {

          }
         if ($this->input->is_ajax_request())
          {
             $this->output->set_output('success');
          }
         else
          {
             $this->load->view('add_jd');
          }   
    }


    function add_tag()
	{
        $industry = $this->input->post('industry');
		$tagname = $this->input->post('tagname');
        $belong = $this->input->post('belong');
        if (isset($tagname) && !empty($tagname))
         {
         	/*查看是否已有*/
         	$this->db->where('tagname',$tagname);
         	$this->db->from('jd_tag');
         	if ($this->db->count_all_results() > 0)
         	{
         		$this->db->set('active','1');
         		$this->db->where('tagname',$tagname);
         		$this->db->update('jd_tag');
         	}
         	else	
         	{
                if (isset($industry)&&(!empty($industry)))
                {
                    $this->db->set('inside',$industry);
                }
				$this->db->set('tagname',$tagname);
           		$this->db->set('belong',$belong);
           		$this->db->set('active','1');
				$this->db->insert('jd_tag');
			}
		}
        if ($this->input->is_ajax_request())
          {
             $this->output->set_output('success');
          }
         else
          {
             $this->load->view('add_tag');
          }   
	}

	function add_qa_tag()
	{
		$tagname = $this->input->post('tagname');
		$tagabbr = $this->input->post('tagabbr');
        if (isset($tagname) && !empty($tagname))
         {
			$this->db->set('tagname',$tagname);
			$this->db->set('tagabbr',$tagabbr);
			$this->db->set('review','Y');
			$this->db->insert('tag_type');
		}
		$this->load->view('add_qa_tag');
	}

	function get_jd_tag($tagname)
	{
		$this->db->select('tagid');
		$this->db->where('tagname',$tagname);
		$query = $this->db->get('jd_tag');
		if ($query->num_rows() > 0)
		{
			$row =$query->row_array();
			return $row['tagid'];
		}
		else
		{
			return 0;
		}
	}
function get_jd_tag_name($tagid)
       {
         $this->db->select('tagname');
         $this->db->where('tagid',$tagid);
         $query = $this->db->get('jd_tag');
         if ($query->num_rows() > 0)
         {
           $row =$query->row_array();
           return $row['tagname'];
         }
         else
         {
           return "未填写";
         }
       }
	function view_question()
	{
		$id = $this->input->post('id');
		if (isset($id) && !empty($id))
		{
			$this->db->set('active','0');
			$this->db->where('id',$id);
			$this->db->update('q2a_question');
		}
		$query = "select id,title,realname,date from q2a_question where active='1'";
		$query = $this->db->query($query);
		$result['question'] = $query->result_array();
		$this->load->view('view_question',$result);
	}

    function checkbox_industry()
    {
        $this->db->select('tagid,tagname');
        $this->db->where('belong','1');
        $query = $this->db->get('jd_tag');
        $data = $query->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function checkbox_place()
    {
        $this->db->select('tagid,tagname');
        $this->db->where('belong','4');
        $query = $this->db->get('jd_tag');
        $data = $query->result_array();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function checkbox_other()
    {
        $tagid=$this->input->get('tagid');
        $this->db->select('tagid,tagname');
        $this->db->where('belong','2');
        $this->db->where('inside',$tagid);
        $query=$this->db->get('jd_tag');
        $message['company'] = $query->result_array();

        $this->db->select('tagid,tagname');
        $this->db->where('belong','3');
        $this->db->where('inside',$tagid);
        $query=$this->db->get('jd_tag');
        $message['occupation'] = $query->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($message));
    }
}
?>