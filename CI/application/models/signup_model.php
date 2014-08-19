<?php
class signup_model extends CI_Model{
  
  function __construct()
  {
  	parent::__construct();
  	$this->load->database();
  	$this->load->library(array('session','form_validation'));
    $this->form_validation->set_error_delimiters('','');
  }

  
  function info(& $message)
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status == "OK")
     {
       if ($this->form_validation->run('secondsignup') === FALSE)
       {
          $message['detail'] = form_error('gender');
          if (empty($message['detail'])) 
              { 
                $message['detail'] = form_error('occupation');
              }
          if (empty($message['detail'])) 
              { 
                $message['detail'] = form_error('bio');
              }
          return FALSE;
       }
       else
        {
             $uid = $this->session->userdata('uid');
             $data = array(
                            'gender' => $this->input->post('gender'),
                            'bio' => $this->input->post('bio'),
                            'occupation' => $this->input->post('occupation')
                           );
             $this->db->where('uid',$uid);
             if (!$this->db->update('user_profile',$data))
             {
               $message['detail'] = "update fails";
               return FALSE;
             }
             else
             {
               return TRUE;
             }
        }
     }
     else
     {  
        $message['detail'] = "Unlogin";
     	  return FALSE;
     }
  }

  function more(& $message)
  {
     $status = $this->session->userdata('status');
     if (isset($status) && $status == "OK")
     {
           $uid = $this->session->userdata('uid');
           $this->db->select('occupation');
           $query = $this->db->get_where('user_profile',array('uid' => $uid));
           $row = $query->row_array();
           $occupation = $row['occupation'];
           //is a student
           if ($occupation === 'S')
           {
             if ($this->form_validation->run('thirdsignup_college') === FALSE)
              {
                   $message['detail'] = form_error('province');
                   if (empty($message['detail'])) 
                   { 
                      $message['detail'] = form_error('college');
                   }
                   if (empty($message['detail'])) 
                   { 
                      $message['detail'] = form_error('major');
                   }
                   if (empty($message['detail'])) 
                   { 
                      $message['detail'] = form_error('year');
                   }
                   return FALSE;
              }
              else
              {
                  $college = $this->input->post('college');
                  $major = $this->input->post('major');
                  $year = $this->input->post('year');
                  $collegeextra = $this->input->post('collegeextra');
                  $majorextra = $this->input->post('majorextra');

            
                  if ($collegeextra == "true")
                  {
                     $abbreviation = $this->input->post('collegeabbr');
                     $abbreviation = strtolower($abbreviation);
                     $tmp = array(
                                     'college' => $college,
                                     'abbreviation' => $abbreviation
                                 );
                     $this->db->insert('college_check',$tmp);
                  }
                  if ($majorextra == "true")
                  {
                     $abbreviation = $this->input->post('majorabbr');
                     $abbreviation = strtolower($abbreviation);
                     $tmp = array(
                                   'major' => $major,
                                   'abbreviation' => $abbreviation
                                 );
                     $this->db->insert('major_check',$tmp);
                  }
                  
                  $data = array(
                                // 'province' => $province,
                                 'job' => $major,
                                 'jobplace' => $college,
                                 'jobtime' => $year,
                               );
                  
                  $this->db->where('uid',$uid);
                  $this->db->update('user_profile',$data);
                  return TRUE; 
              }
           }
           //is a worker
           else
           { 
             if ($this->form_validation->run('thirdsignup_work') === FALSE)
             {
                $message['detail'] = form_error('province');
                if (empty($message['detail'])) 
                { 
                   $message['detail'] = form_error('company');
                }
                if (empty($message['detail'])) 
                { 
                   $message['detail'] = form_error('position');
                }
                return FALSE;
             }
             else
             {
                   $company = $this->input->post('company');
                   $position = $this->input->post('position');
                   $companyextra = $this->input->post('companyextra');
                   $positionextra = $this->input->post('positionextra');

                   if ($companyextra == "true") 
                   {
                      $abbreviation = $this->input->post('companyabbr');
                      $abbreviation = strtolower($abbreviation);
                      $tmp = array(
                                    'company' => $company,
                                    'abbreviation' => $abbreviation
                                  );
                      $this->db->insert('company_check',$tmp);
                   }
                   
                   if ($positionextra == "true") 
                   {
                      $abbreviation = $this->input->post('positionabbr');
                      $abbreviation = strtolower($abbreviation);
                      $tmp = array(
                                    'position' => $position,
                                    'abbreviation' => $abbreviation
                                  );
                      $this->db->insert('position_check',$tmp);
                   } 

                   $data = array(
                                   'job' => $position,
                                   'jobplace' => $company
                                );
                
                   $this->db->where('uid',$uid);
                   $this->db->update('user_profile',$data);
                   
                   return TRUE; 
              }
           }
     }
     else
     {  
        $message['detail'] = "Unlogin";
        return FALSE;
     }
  }

};
?>  