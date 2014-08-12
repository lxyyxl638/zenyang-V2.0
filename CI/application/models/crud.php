<?php
class Crud extends CI_Model
{
	var $controller;

	function __construct()
	{
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('form_validation','table'));
        $this->load->database();		
	}


/*Return data depending on $id*/

	function read($controller,$id)
	{
        $message['state'] = "fail";
		$query = $this->db->get_where($controller,array('id'=>$id));
		if ($query->num_rows() > 0)
        {
            $message = $query->row_array();
            $message['state'] = "success";
            return $message;
        }
        else 
        {
            return $message;
        }
	}

/*delete data depending on id*/
	function delete($controller,$id)
	{
      $message['state'] = "fail";
	   	if(isset($id) && $id > 0)
	   	 {      
         	$this->db->delete($controller,array('id' => $id));
         	$change = $this->db->affected_rows();
            if ($change == 1)
            {
               $message['state'] = 'success';
               return $message;
            }            
            else
            {
                return $message;
            }
	     }
        else
        {
            return $message;
        } 	
	}

/*Insert a new entry or Update a entry*/
   function insert($controller = '', $data = '')
    {
        $message['state'] = "fail";
        if (!isset($data) || empty($data)) return $message;

    	$id = $data['id'];
    	$currentvalue = array();
/*There is a id and this is a update*/
  
    	if (isset($id) && $id > 0)
    	{
    		$query = $this->db->get_where($controller,array('id' => $id));
    		if ($query->num_rows() > 0)
    		{
    			$row = $query->row();
    			 foreach($row as $key => $value)
    			 {
    			 	if (isset($data[$key]))
    			 	{
    			 		$currentvalue[$key] = $data[$key];
    			 	}
    			 	else
    			 	{
    			 		$currentvalue[$key] = $value;
    			 	}
    			 }

                 unset($currentvalue['id']);
                 $this->db->update($controller,$currentvalue,array('id' => $id));
                   if ($this->db->affected_rows() == 1)
                     {
                        $query = $this->db->get_where($controller,array('id' => $id));
                        $message = $query->row_array();
                        $message['state'] = "success";
                        return $message;
                     }
                   else
                    {
                        return $message; 
                    }    
                
    		}
    		else
    		{
    			return $message;
    		}
    	}
    	else
    	{
            /*There was no ID number, so there is a new entry*/	
    	   foreach($data as $key => $value)
    	   	 {
                $currentvalue[$key] = $data[$key];
    	  	 }
           unset($currentvalue['id']);
    	     $this->db->insert($controller,$data);
           if ($this->db->affected_rows() == 1)
              {
                 $query = $this->db->get_where($controller,array('id' => $id));
                 $message = $query -> row_array();
                 $message['state'] = "success";
                 return $message;
              }   
              else
              {
                 return $message;
              }
    	}
    }

};
?>