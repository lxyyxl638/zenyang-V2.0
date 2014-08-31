<?php

class ADD extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('form_validation');
	}

	function index()
	{
		// if ($this->form_validation->run('add') == FALSE)
		// {
  //           $this->load->view('add_jd');
		// }
		// else
		// {
		    $this->load->view('add_jd');

		        
        
	}
    
    function add_jd()
    {
    	   $industry = $this->get_jd_tag($this->input->post('industry'));
            $company = $this->get_jd_tag($this->input->post('company'));
            $occupation = $this->get_jd_tag($this->input->post('occupation'));
            $salary = $this->get_jd_tag($this->input->post('salary'));
            $place = $this->input->post('place');
            $title = $this->input->post('title');
		    $data = array(
            				'industry' => $industry,
            				'company' => $company,
            				'occupation' => $occupation,
            				'salary' => $salary,
    		 	            'title' => $title,
    		 	            'date' => date('Y-m-d:H:i:s',time())
     		 	         );
		    $this->db->insert('jd_jd',$data);
            $this->db->select('jdid');
            $this->db->order_by('jdid','desc');
            $this->db->limit(1,0);
            $query = $this->db->get('jd_jd');
            $row = $query->row_array();
            $jdid = $row['jdid'];

            $place = explode(' ', $place);
            foreach ($place as $key => $value)
            {
               $tmp = $this->get_jd_tag($value);	
               $data = array(
               	              'jdid' => $jdid,
               	              'tagid' => $tmp
               	            );
               $this->db->insert('jd_belong_tag',$data);	
            }
          

            $data = array(
            	           'jdid' => $jdid,
                           'tagid' => $industry
                         );
            $this->db->insert('jd_belong_tag',$data);

            $data = array(
            	           'jdid' => $jdid,
                           'tagid' => $company
                          );
            $this->db->insert('jd_belong_tag',$data);

            $data = array(
            	            'jdid' => $jdid,
                           'tagid' => $occupation
                          );
            $this->db->insert('jd_belong_tag',$data);
            
            $data = array(
            	            'jdid' => $jdid,
                           'tagid' => $salary
                          );
            $this->db->insert('jd_belong_tag',$data); 
             $this->load->view('add_jd');
    }
	function add_tag()
	{
		$this->load->view('add_tag');
		$tagname = $this->input->post('tagname');
		$this->db->set('tagname',$tagname);
		$this->db->insert('jd_tag');
		//$this->load->view('add_tag');
	}

	function get_jd_tag($tagname)
	{
		$this->db->select('tagid');
		$this->db->where('tagname',$tagname);
		$query = $this->db->get('jd_tag');
		$row = $query->row_array();
		return $row['tagid'];
	}

}
?>