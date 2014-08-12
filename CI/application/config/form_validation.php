<?php $config = array(
         'signup'=>array(
		             array(
					        'field'=>'email',
							'label'=>'email',
							'rules'=>'required|valid_email|is_unique[user.email]'
					      ),
				     array(
					        'field'=>'password',
							'label'=>'password',
							'rules'=>'required|min_length[6]|max_length[16]|alpha_dash'
					      ),		  
				     array(
					        'field'=>'firstname',
							'label'=>'firstname',
							'rules'=>'required|alpha_numeric_chinese|max_length[8]'
					      ),
				     array( 
				     	    'field'=>'lastname',
				     	    'label'=>'lastname',
				     	    'rules'=>'required|alpha_numeric_chinese|max_length[8]'
				     	  ),
		              ),
        'secondsignup'=>array
                      (
                      	 array(
                      	 	'field'=>'gender',
                      	 	'label'=>'gender',
                      	 	'rules'=>'required'
                      	 	),
                      	 array(
                      	 	'field'=>'occupation',
                      	 	'label'=>'occupation',
                      	 	'rules'=>'required',
                      	 	),
                      	 array(
                      	 	'field'=>'bio',
                      	 	'label'=>'bio',
                      	 	'rules'=>'max_length[140]'
                      	 	),
        	          ),
		'thirdsignup_college'=>array
                      (
                         array(
					         'field'=>'college',
							 'label'=>'college',
							 'rules'=>'required'
					       ),
					     array(
					     	  'field'=>'major',
					     	  'label'=>'major',
					     	  'rules'=>'required'
					     	), 					
					      array(
					      	   'field'=>'year',
					      	   'label'=>'year',
					      	   'rules'=>'required|numeric'
					      	)	  
					  ),
	    'thirdsignup_work' => array
	                  (
	            
	                  	 array(
	                  	 	  'field'=>'company',
	                  	 	  'label'=>'company',
	                  	 	  'rules'=>'required'
	                  	 	),
	                  	 array(
	                  	 	   'field'=>'position',
	                  	 	   'label'=>'position',
	                  	 	   'rules'=>'required'
	                  	 	)
	    	          ),
        'change_password'=>array
                      (
					     array(
					        'field'=>'OldPassword',
							'label'=>'OldPassword',
							'rules'=>'required|min_length[6]|max_length[16]'
					       ),
                         array(
					         'field'=>'NewPassword',
							'label'=>'NewPassword',
							'rules'=>'required|min_length[6]|max_length[16]'
					        ),
                         array(
					         'field'=>'Passconf',
							'label'=>'Passconf',
							'rules'=>'required|matches[NewPassword]'
					       )						   
					  ),
		'letter_send' => array
		          (
		          	array(
		          		'field' => 'uid',
		          		'label' => 'uid',
		          		'rules' => 'required'
		          		),
		          	array(
		          		'field' => 'letter',
		          		'label' => 'letter',
		          		'rules' => 'required|max_length[400]'
		          		),
		          ),
		       'ask' => array
		          (
		          	array(
		          		   'field' => 'title',
		          		   'label' => 'title',
		          		   'rules' => 'required|min_length[6]|max_length[40]'
		          		 ), 
		          	array(
		          		   'field' => 'content',
		          		   'label' => 'content',
		          		   'rules' => 'max_length[400]'
		          		 )
		          ),
		       'answer' => array
		          (
		            array(
		            	   'field' => 'content',
		            	   'label' => 'content',
		            	   'rules' => 'required|min_length[6]|max_length[4000]'
		            	 )
		          )		  						    
               );			   
?>
