<?php

  class Personal_center_model extends CI_Model{
     
     function __construct()
     {
     	parent::__construct();
     	$this->load->library('upload');
     	$this->load->library('session');
        $this->load->model('public_model');
     }
     
     function profile_get(& $message,$uid)
     {
        $this->db->where('uid',$uid);
        $query = $this->db->get_where('user_profile');
        if ($query->num_rows() > 0)
        {
            $message = $query->row_array();
            if ($uid == $this->session->userdata('uid'))
            {
                $message['myprofile'] = 1; 
            }
            else
            {
                $message['myprofile'] = 0;
            }
            $message['location'] = $this->public_model->large_photo_get($uid);
            return TRUE;
        }
        else
        {
            $message['detail'] = "no this man's profile";
            return FALSE;
        }
     }
     function modify_profile(& $message)
     {
     	$uid = $this->session->userdata('uid');
     	$email = $this->session->userdata('email');
        $query = $this->db->get_where('user_profile',array('uid' => $uid));
        $row = $query->row_array();
        unset($row['id']);
        unset($row['uid']);
        unset($row['photo']);
        foreach ($row as $key => $value)
        {
        	if (!$this->input->post($key))
        	{
                $row[$key] = $value;
        	}
        	else
        	{
                $row[$key] = $this->input->post($key);
                $message[$key] = $this->input->post($key);
                $keyinput = $this->input->post($key);
                if ($key == "realname" && (!empty($keyinput))) 
                {
                    $this->session->set_userdata('realname',$this->input->post($key));
                    $data = array(
                                    'realname' => $keyinput
                                 );
                    $this->db->where('id',$uid);
                    $this->db->update('user',$data);
                    $this->db->where('uid',$uid);
                    $this->db->update('q2a_question',$data);
                    $this->db->where('uid',$uid);
                    $this->db->update('q2a_answer',$data);
                }
        	}
        }
        // $message = $row;
        // return TRUE;
        $this->db->where('uid',$uid);
        if (!$this->db->update('user_profile',$row))
        {
            return FALSE;
        }
        $config['upload_path'] = 'uploads';
        $config['allowed_types'] = 'jpg|png';
        $config['file_name'] = "'$uid'.jpg";
        $config['overwrite'] = TRUE;
        $this->load->library('upload',$config);
        $this->upload->initialize($config);
        $supery = '';
        $userfile = 'userfile';
        if (!$this->upload->do_upload($userfile))
         {
         }
         else
         {
            $data = array(
                            'photo_upload' => 'Y' 
                         );
            $this->db->where('uid',$uid);
            $this->db->update('user_profile',$data);
         	$data = $this->upload->data();
            $config = '';
         	$config['image_library'] = 'gd2';
         	$config['source_image'] = $data['full_path'];
            $config['new_image'] = $data['file_path'].$uid."_large".$data['file_ext'];
         	//$config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 100;
            $config['height'] = 100;
            $this->load->library('image_lib',$config);
            $this->image_lib->resize();
            
            // $message = $data;
            // return TRUE;
            $config['new_image'] = $data['file_path'].$uid."_middle".$data['file_ext'];
            $config['width'] = 38;
            $config['height'] = 38;
            // $message = $config;
            // return TRUE;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $config['new_image'] = $data['file_path'].$uid."_small".$data['file_ext'];
            $config['width'] = 27;
            $config['height'] = 27;
            // $message = $config;
            // return TRUE;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $config['new_image'] = $data['file_path'].$uid."_tiny".$data['file_ext'];
            $config['width'] = 16;
            $config['height'] = 16;
            $this->load->library('image_lib',$config);
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
         }
        // $query = $this->db->get_where('user_profile',array('uid' => $uid));
        // $message = $query->row_array();
        return TRUE;
     }
         /*我的提问*/
     function my_question(& $message,$uid,$limit = 10,$offset = 0)
     {
        $this->db->order_by('date','desc');
        $this->db->limit($limit,$offset);
        $query = $this->db->get_where('q2a_question',array('uid'=>$uid));
        $message = $query->result_array();
        return TRUE;
     }
     
     /*我的回答*/
     function my_answer(& $message,$uid,$limit = 10,$offset = 0)
     {
        $this->db->order_by('date','desc');
        $this->db->where('uid',$uid);
        $this->db->limit($limit,$offset);
        $query = $this->db->get('q2a_answer');
        $result = $query->result_array();
        foreach ($result as $key => $value)
        {
            $qid = $value['qid'];
            $this->db->where('id',$qid);
            $query = $this->db->get('q2a_question');
            $row = $query->row_array();
            $value['title'] = $row['title'];
            $value['description'] = $row['content'];
            $message[$key] = $value;
        }
        return TRUE;
     }

     /*修改我的回答*/
     function modify_my_answer(&$message)
     {
         if ($this->form_validation->run('answer'))
         {
            $message['detail'] = form_error('answer');
            return FALSE;
         }
         else
         {
            $content = $this->input->post('content');
            $data = array(
                          'content' => $content
                        );
            $aid = $this->input->post('aid');
            $this->db->where('aid',$aid);
            $this->db->update('q2a_answer',$data);
            return TRUE;
         }
     }
    /*我关注的问题*/
    function my_follow_question(& $message,$uid,$limit = 10,$offset = 0)
    {
        $this->db->order_by('date','desc');
        $this->db->select('qid');
        $this->db->where('uid',$uid);
        $this->db->limit($limit,$offset);
        $query = $this->db->get('user_question');
        $result = $query->result_array();
        foreach ($result as $key => $value)
         {
            $qid = $value['qid'];
            $query = $this->db->get_where('q2a_question',array('id' => $qid));
            $message[$key] = $query->row_array();
         }
        return TRUE;
    }

    function follow(&$message,$uid)
    {
        $myuid = $this->session->userdata('uid');
        $this->db->where('follower',$myuid);
        $this->db->where('master',$uid);
        $this->db->from('master_follower');
        $sum = $this->db->count_all_results();
        if ($sum == 0)
        {
            $data = array(
                            'follower' => $myuid,
                            'master' => $uid
                         );
            $this->db->insert('master_follower',$data);
            $message['follow'] = 'Y';
            return TRUE;
        }
        else
        {
            $this->db->where('follower',$myuid);
            $this->db->where('master',$uid);
            $this->db->where('active','Y');
            $this->db->from('master_follower');
            $sum = $this->db->count_all_results();
            if ($sum == 0)
            {
                $data = array(
                           'active' => 'Y'
                        );
                $this->db->where('follower',$myuid);
                $this->db->where('master',$uid);
                $this->db->update('master_follower',$data);
                $message['follow'] = 'Y';
                return TRUE;
            }
            else
            {
                $data = array(
                           'active' => 'N'
                        );
                $this->db->where('follower',$myuid);
                $this->db->where('master',$uid);
                $this->db->update('master_follower',$data);
                $message['follow'] = 'N';
                return TRUE;   
            }
        }
    }

};
?>