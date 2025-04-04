<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Auth extends MY_Controller {



	public function __construct()

	{

		parent::__construct();

		

		$this->load->helper('url', 'form');

        $this->load->library('form_validation');



		$this->load->model('auth_model','auth');

	}



	public function is_admin_login(){	

		$this->form_validation->set_rules('email', 'Email', 'required');

		$this->form_validation->set_rules('password', 'Password', 'required');



		if ($this->form_validation->run() == false) 

        {

            $data = array (

	            'errors' => validation_errors(),

	            'is_login' => false

	        );

	        $this->session->set_flashdata($data);	        

			redirect('admin');				

        }else{

        	$result = $this->auth->is_admin_login();

        	//var_dump($result); die();

            if($result["status"] == 'loged_in'){

				$data = array(					

					'user_id'	=>	$result['response'][0]->cust_id,

					/* 'role_id'	=>	$result['response'][0]->role_id,

					'role_name' =>	$result['response'][0]->role_name,

					'name'		=>	$result['response'][0]->name,
 */
					'email'		=>	$result['response'][0]->email_id,

					/* 'status'	=>	$result['status'] */

				);						



				$this->session->set_userdata($data);

				$this->session->set_flashdata('success', 'Successfully log in');

				redirect('admin/dashboard');

			}else{

				$data = array (

		            'errors' => 'Login failed!',

		            'is_login' => false

		        );

		        $this->session->set_flashdata($data);

				redirect('admin');

			}            

        }

	}


	public function uploadProfileImage()

	{	

		$this->load->helper('file');

		$config['upload_path']          = 'uploads/users/profile_pictures/';

        $config['allowed_types']        = 'gif|jpg|png';

        $config['max_size']             = 100;

        $config['remove_spaces'] = TRUE;

		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		$this->upload->initialize($config);		

		

		$existProfile = $this->auth_model->if_exist_profile_picture($this->session->userdata('uid'));

		if($existProfile>0){

			$image = $existProfile[0]->user_profile_picture;

			$path = $config['upload_path'].$image;				

		}

		

		if((null !== $this->session->userdata('uid')) && ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2))

		{	

			

            if ( ! $this->upload->do_upload('userfile')){

            	$error = $this->upload->display_errors();

            	

            	$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>$this->upload->display_errors()

                	);

	            $this->session->set_flashdata('message', $alert);



            	redirect('event/profile');

            }else{            	

                $this->load->helper('file');

                $upload_data = $this->upload->data();

                //get the uploaded file name

                $uploadData['user_profile_picture'] = $upload_data['file_name'];

                //store pic data to the db

                $result = $this->auth_model->uploadProfilePicture($this->session->userdata('uid'), $uploadData);

                if($result > 0){ 

                	unlink($path);               	

                	$alert = array(

                		'alert_status'=>TRUE,

                		'message'=>'Profile picture successfully uploaded'

                	);

	            	$this->session->set_flashdata('message', $alert);

	            	

	            	redirect('event/profile');                	

                }else{

	            	$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>'Profile picture upload failed!'

                	);

	            	$this->session->set_flashdata('message', $alert);



	            	redirect('event/profile');	                	

                }

            }

         

		}elseif((null !== $this->session->userdata('uid')) && ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 3))

		{

			if ( ! $this->upload->do_upload('userfile')){

            	$error = $this->upload->display_errors();

            	

            	$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>$this->upload->display_errors()

                	);

	            $this->session->set_flashdata('message', $alert);



            	redirect('event/profile');

            }else{            	

                $this->load->helper('file');

                $upload_data = $this->upload->data();

                //get the uploaded file name

                $uploadData['user_profile_picture'] = $upload_data['file_name'];

                //store pic data to the db

                $result = $this->auth_model->uploadProfilePicture($this->session->userdata('uid'), $uploadData);

                if($result > 0){ 

                	unlink($path);               	

                	$alert = array(

                		'alert_status'=>TRUE,

                		'message'=>'Profile picture successfully uploaded'

                	);

	            	$this->session->set_flashdata('message', $alert);

	            	

	            	redirect('event/profile');                	

                }else{

	            	$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>'Profile picture upload failed!'

                	);

	            	$this->session->set_flashdata('message', $alert);



	            	redirect('event/profile');	                	

                }

            }

		}else{

			$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>'You are not allowed to upload profile picture!'

                	);

	            	$this->session->set_flashdata('message', $alert);

			redirect('event/profile');	

		}

	}

	public function userPasswordReset()

	{

		$id = $this->session->userdata('uid');

		$status = $this->session->userdata('status');



		$current_password = $this->input->post('current_password');

		$new_password = $this->input->post('new_password');



		if( (null !== $id) && ($status == 'loged_in') && (null !== $current_password) && (null !== $new_password))

		{

			$result = $this->auth_model->reset_password($id, $current_password, $new_password);

			if($result > 0)

			{

				$alert = array(

                		'alert_status'=>TRUE,

                		'message'=>'User password successfully reset'

                	);

	            	$this->session->set_flashdata('message', $alert);

	            redirect('/event/profile');	

			}else{	       		

	       		$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>'User password reset failed, enter you current password correctly'

                	);

	            	$this->session->set_flashdata('message', $alert);

	            redirect('/event/profile');	

			}

		}else{

			$alert = array(

                		'alert_status'=>FALSE,

                		'message'=>'Form validation occurd!'

                	);

	            	$this->session->set_flashdata('message', $alert);

	        redirect('/event/profile');	

		}

	}



/* End of Auth Controller */

}