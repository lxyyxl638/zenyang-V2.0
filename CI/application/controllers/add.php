<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<?php
class Add extends CI_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('form_validation');
		$this->load->helper('url','form');
	}

	function index()
	{
		$this->db->select('coid,name');
		$query = $this->db->get('college');
		$college = $query->result_array();
		foreach ($college as $key => $value)
		{
			$collegeID = $value['coid'];
			$collegeName = $value['name'];
            $this->db->where('collegeID',$collegeID);
            $data = array(
            	           'college' => $collegeName 
            	         );
            $this->db->update('school',$data);
		}
        $this->load->view('add');
	}
}
?>