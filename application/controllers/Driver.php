<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('driver_model', 'driver');
	}
	
	public function index()
	{		
		$data = array(
			'page'		=> 'login', 
			'mode'		=> 'driver', 
			'errors'		=> '', 
			'message'	=> ''
		);
		$this->load->view('driver/login', $data);
	}

	public function signup()
	{
		$data = array(
			'page'		=> 'signup', 
			'mode'		=> 'driver', 
			'errors'		=> '', 
			'message'	=> ''
		);
		$this->load->view('driver/signup', $data);
	}

	public function dashboard()
	{		
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'dashboard', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);
						
			$this->load->view('driver/index', $data);		
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
	}

	/* Start Driver Profile Section */
	public function set_profile()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'profile', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);			

			$this->load->view('driver/set_profile',$data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}		
	}

	public function get_driver_profile($user_id)
	{
		$result = $this->driver->get_driver_profile_by_id($user_id);
		echo json_encode($result);
	}

	public function update_profile()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){
			$data = array(
				'page'		=> 'profile', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			$this->form_validation->set_rules('driver_name', 'driver name', 'required');
			$this->form_validation->set_rules('driver_address', 'driver address', 'required');
			$this->form_validation->set_rules('mobile_number', 'mobile number', 'required');
			$this->form_validation->set_rules('home_phone_number', 'home phone number', 'required');
			//$this->form_validation->set_rules('email', 'email', 'required');
			$this->form_validation->set_rules('kin_name', 'kin name', 'required');
			$this->form_validation->set_rules('kin_mobile_number', 'kin mobile number', 'required');
			$this->form_validation->set_rules('kin_relationship', 'kin relationship', 'required');
			$this->form_validation->set_rules('driving_licence_number', 'driving licence number', 'required');
			$this->form_validation->set_rules('licence_expiry_date', 'licence expiry date', 'required');
			$this->form_validation->set_rules('national_insurance_number', 'national insurance number', 'required');
			$this->form_validation->set_rules('accident_date', 'accident date', 'required');
			$this->form_validation->set_rules('accident_category', 'accident category', 'required');
			$this->form_validation->set_rules('date_of_conviction', 'date of conviction', 'required');
			$this->form_validation->set_rules('conviction_code', 'conviction code', 'required');
			$this->form_validation->set_rules('fine_ban_penalty', 'fine/ban/penalty', 'required');
			// $this->form_validation->set_rules('bank_name', 'bank name', 'required');
			// $this->form_validation->set_rules('bank_branch_name', 'bank branch name', 'required');
			// $this->form_validation->set_rules('bank_account_number', 'bank account number', 'required');
			$this->form_validation->set_rules('utr_vat', 'utr or vat', 'required');
			// $this->form_validation->set_rules('file1', '', 'callback_file_check_pdf');
			// $this->form_validation->set_rules('file2', '', 'callback_file_check_docs');

			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_profile');
            }else{
            	$user_id = $this->session->userdata('user_id');
            	$check_profile = $this->driver->get_profile_by_id($user_id);            	
            	if($check_profile>0){
            		$pdf_file=$check_profile[0]->working_procedures_pdf;	
            		$doc_file=$check_profile[0]->insurance_docs;
            	}

	        	//upload configuration
	            $config['upload_path']   = 'uploads/users_profile/';                
	            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|gif|jpg|png';
	            //$config['max_size']      = 1024;
	            $config['encrypt_name'] = TRUE;
	            $this->load->library('upload', $config);
	            //upload file to directory
	            if($this->upload->do_upload('file1')){
	                $uploadData = $this->upload->data();
	                $working_procedures_pdf = $uploadData['file_name'];
	            }else{
	            	$data['errors'] = $this->upload->display_errors();
	            }
	            if($this->upload->do_upload('file2')){
	                $uploadData = $this->upload->data();
	                $insurance_docs = $uploadData['file_name'];
	            }else{
	            	$data['errors'] = $this->upload->display_errors();	
	            }
                
	            /*Input data*/
	            $profile = array(
	                'name'					=>	$this->input->post('driver_name'),
					'driver_address'		=>	$this->input->post('driver_address'),
					'mobile_number'  		=>	$this->input->post('mobile_number'),
					'home_phone_number'		=>	$this->input->post('home_phone_number'),
					//'email'				=>	$this->input->post('email'),
					'kin_name'				=>	$this->input->post('kin_name'),
					'kin_mobile_number'		=>	$this->input->post('kin_mobile_number'),
					'kin_relationship'		=>	$this->input->post('kin_relationship'),
					'driving_licence_number'=>	$this->input->post('driving_licence_number'),
					'licence_expiry_date'	=>$this->input->post('licence_expiry_date'),
					'national_insurance_number' =>$this->input->post('national_insurance_number'),
					'accident_date'			=>	$this->input->post('accident_date'),
					'accident_category'		=>	$this->input->post('accident_category'),
					'date_of_conviction'	=>	$this->input->post('date_of_conviction'),
					'conviction_code' 		=> $this->input->post('conviction_code'),
					'fine_ban_penalty'		=>	$this->input->post('fine_ban_penalty'),
					'bank_name'				=>	$this->input->post('bank_name'),
					'bank_branch_name'		=>	$this->input->post('bank_branch_name'),
					'bank_account_number'	=>	$this->input->post('bank_account_number'),
					'utr_vat'				=>	$this->input->post('utr_vat'),
					'working_procedures_pdf'=> $working_procedures_pdf,
					'insurance_docs'		=> $insurance_docs,
					'profile_modified'		=>	date('Y-m-d')
				);

	        	$result = $this->driver->update_profile($user_id, $profile);
			
				if($result == true){
					unlink($config['upload_path'].$pdf_file);
	        		unlink($config['upload_path'].$doc_file);

	        		$this->driver->active_profile($user_id);

	        		$data = array (
			            'success' => 'Driver profile successfully updated'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_profile');
				}else{
					$data = array (
			            'errors' => 'Driver profile failed to update!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_profile');
				}
				$this->session->set_flashdata($data);
		        redirect('driver/set_profile');
	        }
			
		}else{
			$data = array(
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );

	        $this->session->set_flashdata($data);
			redirect('driver');
		}		
	}
	
	/*
     * file value and type check during validation
     */
    public function file_check_pdf($str){
        $allowed_mime_type_arr = array('application/pdf');
        $mime = get_mime_by_extension($_FILES['file1']['name']);
        if(isset($_FILES['file1']['name']) && $_FILES['file1']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check_pdf', 'Please select only pdf file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check_pdf', 'Please choose a pdf file to upload.');
            return false;
        }
    }
    public function file_check_docs($str){
        $allowed_mime_type_arr = array('application/vnd.ms-office','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword','application/vnd.msexcel','application/excel','application/msexcel', 'application/x-msexcel','application/x-ms-excel','application/x-excel', 'application/x-dos_ms_excel', 'application/xls','application/x-xls','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel' );
        //$allowed_mime_type_arr = array('application/msword','application/rtf','application/vnd.ms-excel','application/vnd.ms-powerpoint','application/vnd.ms-docx','application/vnd.ms-xlsx');
        $mime = get_mime_by_extension($_FILES['file2']['name']);
        if(isset($_FILES['file2']['name']) && $_FILES['file2']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check_docs', 'Please select only doc file.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check_docs', 'Please choose a doc file to upload.');
            return false;
        }
    }
    /* End Driver Profile Section */
    /* Start Driver Availability Section */
	public function set_availability()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'availability', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			$data['availability'] = $this->driver->get_availability($this->session->userdata('user_id'));

			$this->load->view('driver/set_availability', $data);	
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}
	}

	public function add_availability()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){
			
			$this->form_validation->set_rules('available_date', 'available date', 'required');
			$this->form_validation->set_rules('availability', 'availability', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_availability');
            }else{
            	if(($this->input->post('availability') == 'All Day') || ($this->input->post('availability') == 'Available with time restrictions'))
            	{
            		$availability_status = 1;
            	}else{
            		$availability_status = 0;
            	}

            	$available_data = array(
            		'user_id'=>$this->session->userdata('user_id'),
            		'available_date'=>$this->input->post('available_date'),
            		'availability'=>$this->input->post('availability'),
					'st1'=>$this->input->post('st1'),
					'et1'=>$this->input->post('et1'),
					'st2'=>$this->input->post('st2'),
					'et2'=>$this->input->post('et2'),
					'st3'=>$this->input->post('st3'),
					'et3'=>$this->input->post('et3'),
					'availability_status'=>$availability_status,
					'notes'=>$this->input->post('notes'),
					'availability_created'=>date('Y-m-d')
				);
				$result = $this->driver->add_availability($available_data);
				//$result = $this->driver->set_availability($available_data);
				
				if($result == TRUE)
				{
					$data = array (
			            'success' => 'Driver availability successfully added'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_availability');
				}else{
					$data = array (
			            'errors' => 'Driver availability failed to add!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_availability');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}

	public function get_driver_availability_by_id($da_id)
	{
		$result = $this->driver->get_driver_availability_by_id($da_id);
		echo json_encode($result);
	}

	public function update_availability()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			
			$this->form_validation->set_rules('available_date', 'available date', 'required');
			$this->form_validation->set_rules('availability', 'availability', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_availability');
            }
            else
            {
            	$da_id = $this->input->post('da_id');

            	if(($this->input->post('availability') == 'All Day') || ($this->input->post('availability') == 'Available with time restrictions'))
            	{
            		$availability_status = 1;
            	}else{
            		$availability_status = 0;
            	}

            	$available_data = array(
            		//'user_id'=>$this->session->userdata('user_id'),
            		'availability'=>$this->input->post('availability'),
					'available_date'=>$this->input->post('available_date'),
					'st1'=>$this->input->post('st1'),
					'et1'=>$this->input->post('et1'),
					'st2'=>$this->input->post('st2'),
					'et2'=>$this->input->post('et2'),
					'st3'=>$this->input->post('st3'),
					'et3'=>$this->input->post('et3'),
					'availability_status'=>$availability_status,
					'notes'=>$this->input->post('notes'),
					'availability_modified'=>date('Y-m-d')
				);
				//$result = $this->driver->add_availability($available_data);
				$result = $this->driver->edit_availability($da_id, $available_data);
				
				if($result == true)
				{
					$data = array (
			            'success' => 'Driver availability successfully updated'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_availability');
				}else{
					$data = array (
			            'errors' => 'Driver availability failed to update!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_availability');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}

	
	
	// public function edit_driver_availability()
	// {
	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

	// 		$this->form_validation->set_rules('availability', 'availability', 'required');
			
	// 		if ($this->form_validation->run() == FALSE)
 //            {
 //                $data = array (
	// 	            'errors' => validation_errors()
	// 	        );
	// 	        $this->session->set_flashdata($data);
	// 	        redirect('driver/set_availability');
 //            }
 //            else
 //            {            	
 //            	$available_data = array(
 //            		'user_id'=>$this->session->userdata('user_id'),
 //            		'availability'=>$this->input->post('availability'),
	// 				'available_date'=>$this->input->post('available_date'),
	// 				'st1'=>$this->input->post('st1'),
	// 				'et1'=>$this->input->post('et1'),
	// 				'st2'=>$this->input->post('st2'),
	// 				'et2'=>$this->input->post('et2'),
	// 				'st3'=>$this->input->post('st3'),
	// 				'et3'=>$this->input->post('et3'),
	// 				'availability_status'=>$availability_status,
	// 				'notes'=>$this->input->post('notes'),
	// 				'availability_created'=>date('Y-m-d')
	// 			);				
	// 			$result = $this->driver->set_availability($available_data);
	// 			if($result == true)
	// 			{
	// 				$data = array (
	// 		            'success' => 'Driver availability successfully updated'
	// 		        );
	// 		        $this->session->set_flashdata($data);
	// 		        redirect('driver/set_availability');
	// 			}else{
	// 				$data = array (
	// 		            'errors' => 'Driver availability failed to update!'
	// 		        );
	// 		        $this->session->set_flashdata($data);
	// 		        redirect('driver/set_availability');
	// 			}
 //            }
 //        }else{
 //        	$data = array (
	//             'errors' => 'Session time out!',
	//             'is_login' => false
	//         );
	//         $this->session->flashdata($data);
	// 		redirect('driver');
 //        }
	// }

	public function delete_availability_by_id($da_id)
	{
		$result = $this->driver->delete_availability_by_id($da_id);
		if($result == true)
		{
			$data = 'YES';
		}else{
			$data = 'NO';
		}
		echo json_encode($data);
	}
	/* End Driver Availability Section */
	
	/* Start set costing section */
	public function set_costing()
	{	
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'costing', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);
		
			$data['vehicles'] = $this->driver->get_vehicles();
			$data['driving_rate_data'] = $this->driver->get_driving_rate_by_user_id($this->session->userdata('user_id'));
			$data['transport_rate_data'] = $this->driver->get_transport_rate_by_user_id($this->session->userdata('user_id'));
			$this->load->view('driver/set_costing', $data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}			
	}

	public function add_vehicle_model()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_costing');
            }
            else
            {            	
            	$vehicle_model_data = array(            		
            		'vehicle_model'=>$this->input->post('vehicle_model'),
					'vehicle_model_status'=> 1,
					'vehicle_model_created'=> date('Y-m-d')
				);
				$result = $this->driver->add_vehicle_model($vehicle_model_data);
				if($result == true)
				{
					$data = array (
			            'success' => 'Vehicle Model successfully added'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}else{
					$data = array (
			            'errors' => 'Vehicle Model failed to add!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}

	public function add_driving_costing()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');
			$this->form_validation->set_rules('driven_start_time', 'driven start time', 'required');
			$this->form_validation->set_rules('driven_end_time', 'driven end time', 'required');
			$this->form_validation->set_rules('rate_per_normal_hour', 'rate per normal hour', 'required');
			$this->form_validation->set_rules('rate_per_addition_hour', 'rate per addition hour', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_costing');
            }else{
            	$driving_costing_data = array(            		
            		'user_id'=>$this->session->userdata('user_id'),
            		'vehicle_model'=>$this->input->post('vehicle_model'),
            		'driven_start_time'=>$this->input->post('driven_start_time'),
            		'driven_end_time'=>$this->input->post('driven_end_time'),
            		'rate_per_normal_hour'=>$this->input->post('rate_per_normal_hour'),
            		'rate_per_addition_hour'=>$this->input->post('rate_per_addition_hour'),
					'driven_rate_status'=> 1,
					'driven_rate_created'=> date('Y-m-d')
				);
				$result = $this->driver->add_driving_costing($driving_costing_data);
				if($result == true)
				{
					$data = array (
			            'success' => 'Vehicle driving rate successfully added'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}else{
					$data = array (
			            'errors' => 'Vehicle driving rate failed to add!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }	
	}
	public function get_driving_rate_by_driven_rates_id($driven_rates_id)
	{
		$result = $this->driver->get_driving_rate_by_driven_rates_id($driven_rates_id);
		echo json_encode($result);
	}
	public function edit_driven_rate()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');
			$this->form_validation->set_rules('driven_start_time', 'driven start time', 'required');
			$this->form_validation->set_rules('driven_end_time', 'driven end time', 'required');
			$this->form_validation->set_rules('rate_per_normal_hour', 'rate per normal hour', 'required');
			$this->form_validation->set_rules('rate_per_addition_hour', 'rate per addition hour', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_costing');
            }
            else
            {       
            	$driven_rates_id = $this->input->post('driven_rates_id');    	
            	$driven_rate = array(          		            		
            		'vehicle_model'=>$this->input->post('vehicle_model'),
            		'driven_start_time'=>$this->input->post('driven_start_time'),
            		'driven_end_time'=>$this->input->post('driven_end_time'),
            		'rate_per_normal_hour'=>$this->input->post('rate_per_normal_hour'),
            		'rate_per_addition_hour'=>$this->input->post('rate_per_addition_hour'),
					'driven_rate_modified'=> date('Y-m-d')
				);
				$result = $this->driver->edit_driven_rate($driven_rates_id, $driven_rate);
				if($result == true)
				{
					$data = array (
			            'success' => 'Vehicle driven rate successfully updated'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}else{
					$data = array (
			            'errors' => 'Vehicle driven rate failed to update!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}
	public function delete_driving_rate_by_driven_rates_id($driven_rates_id)
	{
		$result = $this->driver->delete_driving_rate_by_driven_rates_id($driven_rates_id);
		if($result == true)
		{
			$data = 'YES';
		}else{
			$data = 'NO';
		}
		echo json_encode($data);
	}
	public function add_transported_rate()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');
			$this->form_validation->set_rules('transport_start_time', 'transport start time', 'required');
			$this->form_validation->set_rules('transport_end_time', 'transport end time', 'required');
			$this->form_validation->set_rules('rate_per_normal_hour', 'rate per normal hour', 'required');
			$this->form_validation->set_rules('rate_per_addition_hour', 'rate per addition hour', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_costing');
            }
            else
            {            	
            	$transported_rate_data = array(            		
            		'user_id'=>$this->session->userdata('user_id'),
            		'vehicle_model'=>$this->input->post('vehicle_model'),
            		'transport_start_time'=>$this->input->post('transport_start_time'),
            		'transport_end_time'=>$this->input->post('transport_end_time'),
            		'rate_per_normal_hour'=>$this->input->post('rate_per_normal_hour'),
            		'rate_per_addition_hour'=>$this->input->post('rate_per_addition_hour'),
					'transport_rate_status'=> 1,
					'transport_rate_created'=> date('Y-m-d')
				);
				$result = $this->driver->add_transported_rate($transported_rate_data);
				if($result == true)
				{
					$data = array (
			            'success' => 'Vehicle transported rate successfully added'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}else{
					$data = array (
			            'errors' => 'Vehicle transported rate failed to add!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}
	public function get_transport_rate_by_transport_rates_id($transport_rate_id)
	{
		$result = $this->driver->get_transport_rate_by_transport_rates_id($transport_rate_id);
		echo json_encode($result);
	}
	public function save_edit_transport_rate()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');
			$this->form_validation->set_rules('transport_start_time', 'transport start time', 'required');
			$this->form_validation->set_rules('transport_end_time', 'transport end time', 'required');
			$this->form_validation->set_rules('rate_per_normal_hour', 'rate per normal hour', 'required');
			$this->form_validation->set_rules('rate_per_addition_hour', 'rate per addition hour', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = array (
		            'errors' => validation_errors()
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/set_costing');
            }
            else
            {       
            	$transport_rate_id = $this->input->post('transport_rate_id');
            	$transported_rate_data = array(            		            		
            		'vehicle_model'=>$this->input->post('vehicle_model'),
            		'transport_start_time'=>$this->input->post('transport_start_time'),
            		'transport_end_time'=>$this->input->post('transport_end_time'),
            		'rate_per_normal_hour'=>$this->input->post('rate_per_normal_hour'),
            		'rate_per_addition_hour'=>$this->input->post('rate_per_addition_hour'),
					'transport_rate_modified'=> date('Y-m-d')
				);
				$result = $this->driver->update_transported_rate($transport_rate_id,$transported_rate_data);
				if($result == true)
				{
					$data = array (
			            'success' => 'Vehicle transported rate successfully updated'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}else{
					$data = array (
			            'errors' => 'Vehicle transported rate failed to update!'
			        );
			        $this->session->set_flashdata($data);
			        redirect('driver/set_costing');
				}
            }
        }else{
        	$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
        }
	}
	public function delete_transport_rate_by_transport_rate_id($transport_rate_id)
	{
		$result = $this->driver->delete_transport_rate_by_transport_rate_id($transport_rate_id);
		if($result == true)
		{
			$data = 'YES';
		}else{
			$data = 'NO';
		}
		echo json_encode($data);
	}
	/* End set costing section */
	// public function assign_pricing()
	// {
	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

	// 		$data = array(
	// 			'page'		=> 'pricing', 
	// 			'mode'		=> 'driver', 
	// 			'error'		=> '', 
	// 			'message'	=> ''
	// 		);
					
	// 		$this->load->view('driver/assign_pricing', $data);
	// 	}else{
	// 		$data = array (
	//             'errors' => 'Session time out!',
	//             'is_login' => false
	//         );
	//         $this->session->flashdata($data);
	// 		redirect('driver');
	// 	}
		
	// }
	public function job_list()
	{				
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'job_list', 
				'mode'		=> 'driver', 
				'error'		=> '', 
				'message'	=> ''
			);
			$data['jobList']=$this->driver->get_job_list_by_id($this->session->userdata('user_id'));
			$data['acceptedjobList']=$this->driver->get_accepted_job_list_by_id($this->session->userdata('user_id'));
			
			$this->load->view('driver/job_list',$data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}
	}
	public function get_job_by_job_id($job_id)
	{
		$jobData=$this->driver->get_job_by_job_id($job_id);
		echo json_encode($jobData);
	}
	public function job_acceptance()
	{
		$job_id=$this->input->post('job_id');
		$job_acceptance=$this->input->post('job_acceptance');
		$result=$this->driver->job_acceptance($job_id,$job_acceptance);
		
		if($result->is_job_accept == 'Accepted')
		{
			$data = array (
	            'success' => 'Job accepted'
	        );
	        $this->session->set_flashdata($data);
		}else{
			$data = array (
	            'errors' => 'Job decliened'
	        );
	        $this->session->flashdata($data);
		}
		redirect('driver/job_list');
	}
	public function job_submission()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'job_submission', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);
			$data['acceptedjobList']=$this->driver->get_accepted_job_list_by_id($this->session->userdata('user_id'));
			$this->load->view('driver/job_submission', $data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}		
	}
	public function job_cart()
	{		
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'job_submission', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);		

			$job_id=$this->uri->segment(3);
			$data['job_id']=$this->uri->segment(3);

            $data['result'] = $this->driver->get_tsr_rates_by_id();
			$this->load->view('driver/job_cart', $data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}
	}	

	/* Submit Exterior & Interior job sheet */
	public function add_to_job_sheet()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){
			$data = array(
				'page'		=> 'job_submission', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			//upload configuration
	        $config['upload_path']   = 'uploads/vehicel_damages/';                
	        $config['allowed_types'] = 'gif|jpg|png|jpeg';
	        $config['encrypt_name'] = TRUE;
	        $this->load->library('upload', $config);

			// Job ID
			$job_id = $this->uri->segment(3);
			$user_id = $this->session->userdata('user_id');
			$tsr_layout_type = $this->input->post('tsr_layout_type');		

			/* Exterior Job Sheet Submission */
			if( null !== $this->input->post('layout_name_x_fos') )
			{
				// Front Wing			
				$data1 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Front Wing',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_front_wing'),
					'no_damage'=>$this->input->post('no_damege_x_fos_front_wing'),
					'na'=>$this->input->post('na_x_fos_front_wing')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data1);
				// Tyre Wall		
				$data2 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Tyre Wall',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_tyre_wall'),
					'no_damage'=>$this->input->post('no_damage_x_fos_tyre_wall'),
					'na'=>$this->input->post('na_x_fos_tyre_wall')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data2);
				// Tyre Tread
				$data3 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Tyre Tread',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_tyre_tread'),
					'no_damage'=>$this->input->post('no_damage_x_fos_tyre_tread'),
					'na'=>$this->input->post('na_x_fos_tyre_tread')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data3);
				// Alloy Wheel
				$data4 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Alloy Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_alloy_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_fos_alloy_wheel'),
					'na'=>$this->input->post('na_x_fos_alloy_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data4);
				// Steel Wheel
				$data5 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Steel Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_steel_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_fos_steel_wheel'),
					'na'=>$this->input->post('na_x_fos_steel_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data5);
				// Door
				$data6 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_door'),
					'no_damage'=>$this->input->post('no_damage_x_fos_door'),
					'na'=>$this->input->post('na_x_fos_door')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data6);
				// Door Mirror
				$data7 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Door Mirror',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_door_mirror'),
					'no_damage'=>$this->input->post('no_damage_x_fos_door_mirror'),
					'na'=>$this->input->post('na_x_fos_door_mirror')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data7);
				// Plastic Trim
				$data8 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Plastic Trim',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_plastic_trim'),
					'no_damage'=>$this->input->post('no_damage_x_fos_plastic_trim'),
					'na'=>$this->input->post('na_x_fos_plastic_trim')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data8);
				// Sill
				$data9 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fos'),
					'tsr_layout_category'=>'Sill',
					'tsr_damage_name'=>$this->input->post('dn_x_fos_sill'),
					'no_damage'=>$this->input->post('no_damage_x_fos_sill'),
					'na'=>$this->input->post('na_x_fos_sill')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data9);
				// Upload F/O/S - Front Driver Side - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file1']['name'])){
			        if($this->upload->do_upload('file1')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }
			        $upload_damage = array(
			        	'user_id' => $user_id,
			        	'fos_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );	        
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
		    	}
			}				           
			// R/O/S - Rear Driver Side - Exterior
			if( null !== $this->input->post('layout_name_x_ros') )
			{
		        // Sill
				$data10 = array(
					'job_id' => $job_id,
					'tsr_layout_type' => $tsr_layout_type,
					'tsr_layout_name' => $this->input->post('layout_name_x_ros'),
					'tsr_layout_category' => 'Sill',
					'tsr_damage_name' => $this->input->post('dn_x_ros_sill'),
					'no_damage' => $this->input->post('no_damage_x_ros_sill'),
					'na' => $this->input->post('na_x_ros_sill')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id, $data10);
				// plastic_trim2
				$data11 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Plastic Trim',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_plastic_trim'),
					'no_damage'=>$this->input->post('no_damage_x_ros_plastic_trim'),
					'na'=>$this->input->post('na_x_ros_plastic_trim')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data11);
				// Door
				$data12 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_door'),
					'no_damage'=>$this->input->post('no_damage_x_ros_door'),
					'na'=>$this->input->post('na_x_ros_door')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data12);
				// Tyre Wall
				$data13 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Tyre Wall',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_tyre_wall'),
					'no_damage'=>$this->input->post('no_damage_x_ros_tyre_wall'),
					'na'=>$this->input->post('na_x_ros_tyre_wall')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data13);
				// Tyre Tread
				$data14 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Tyre Tread',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_tyre_tread'),
					'no_damage'=>$this->input->post('no_damage_x_ros_tyre_tread'),
					'na'=>$this->input->post('na_x_ros_tyre_tread')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data14);
				// Alloy Wheel
				$data15 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Alloy Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_alloy_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_ros_alloy_wheel'),
					'na'=>$this->input->post('na_x_ros_alloy_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data15);
				// Steel Wheel
				$data16 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Steel Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_Steel_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_ros_Steel_wheel'),
					'na'=>$this->input->post('na_x_ros_Steel_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data16);
				// Rear Wing
				$data17 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Rear Wing',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_rear_wing'),
					'no_damage'=>$this->input->post('no_damage_x_ros_rear_wing'),
					'na'=>$this->input->post('na_x_ros_rear_wing')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data17);
				// Fuel Lid & Filler Cap
				$data18 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_ros'),
					'tsr_layout_category'=>'Fuel Lid & Filler Cap',
					'tsr_damage_name'=>$this->input->post('dn_x_ros_flfc'),
					'no_damage'=>$this->input->post('no_damage_x_ros_flfc'),
					'na'=>$this->input->post('na_x_ros_flfc')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data18);
				// Upload R/O/S - Rear Driver Side - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file2']['name'])){
			        if($this->upload->do_upload('file2')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }
			        $upload_damage = array(
			        	'user_id' => $user_id,
			        	'ros_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }		
			}	
	        // Vehicle Rear
	        if( null !== $this->input->post('layout_name_x_vr') )
			{
		        // Spoiler
				$data19 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Spoiler',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_spoiler'),
					'no_damage'=>$this->input->post('no_damage_x_vr_spoiler'),
					'na'=>$this->input->post('na_x_vr_spoiler')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data19);
				// Rear Screen
				$data20 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Rear Screen',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_rear_screen'),
					'no_damage'=>$this->input->post('no_damage_x_vr_rear_screen'),
					'na'=>$this->input->post('na_x_vr_rear_screen')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data20);
				// Boot Lid
				$data21 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Boot Lid',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_boot_lid'),
					'no_damage'=>$this->input->post('no_damage_x_vr_boot_lid'),
					'na'=>$this->input->post('na_x_vr_boot_lid')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data21);
				// Rear Lights - O/S or N/S
				$data22 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Rear Lights - O/S or N/S',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_rl_os_ns'),
					'no_damage'=>$this->input->post('no_damage_x_vr_rl_os_ns'),
					'na'=>$this->input->post('na_x_vr_rl_os_ns')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data22);
				// Rear Bumper
				$data23 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Rear Bumper',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_rear_bumper'),
					'no_damage'=>$this->input->post('no_damage_x_vr_rear_bumper'),
					'na'=>$this->input->post('na_x_vr_rear_bumper')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data23);
				// Rear Number Plate
				$data24 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Rear Number Plate',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_rnp'),
					'no_damage'=>$this->input->post('no_damage_x_vr_rnp'),
					'na'=>$this->input->post('na_x_vr_rnp')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id, $data24);
				// Rear Valance
				$data25 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vr'),
					'tsr_layout_category'=>'Rear Valance',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_rv'),
					'no_damage'=>$this->input->post('no_damage_x_vr_rv'),
					'na'=>$this->input->post('na_x_vr_rv')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id, $data25);
				// Upload Vehicle Rear - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file3']['name'])){
			        if($this->upload->do_upload('file3')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }
			        $upload_damage = array(
			        	'user_id' => $user_id,
			        	'vr_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }			
			}	
	        // R/N/S - Rear Passenger Side - Exterior
	        if( null !== $this->input->post('layout_name_x_rns') )
			{
		        // Rear Wing
				$data26 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Rear Wing',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_rear_wing'),
					'no_damage'=>$this->input->post('no_damage_x_rns_rear_wing'),
					'na'=>$this->input->post('na_x_rns_rear_wing')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data26);
				// Fuel Lid & Filler Cap
				$data27 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Fuel Lid & Filler Cap',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_flfc'),
					'no_damage'=>$this->input->post('no_damage_x_rns_flfc'),
					'na'=>$this->input->post('na_x_rns_flfc')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data27);
				// Tyre Wall
				$data28 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Tyre Wall',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_tyre_wall'),
					'no_damage'=>$this->input->post('no_damage_x_rns_tyre_wall'),
					'na'=>$this->input->post('na_x_rns_tyre_wall')
				);
				$last=$this->driver->add_to_job_sheet($user_id, $job_id,$data28);
				// Tyre Tread
				$data29 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Tyre Tread',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_tyre_tread'),
					'no_damage'=>$this->input->post('no_damage_x_rns_tyre_tread'),
					'na'=>$this->input->post('na_x_rns_tyre_tread')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data29);
				// Alloy Wheel
				$data30 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Alloy Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_alloy_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_rns_alloy_wheel'),
					'na'=>$this->input->post('na_x_rns_alloy_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data30);
				// Steel Wheel
				$data31 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Steel Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_steel_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_rns_steel_wheel'),
					'na'=>$this->input->post('na_x_rns_steel_wheel')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data31);
				// Door
				$data32 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_door'),
					'no_damage'=>$this->input->post('no_damage_x_rns_door'),
					'na'=>$this->input->post('na_x_rns_door')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data32);
				// Plastic Trim
				$data33 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Plastic Trim',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_plastic_trim'),
					'no_damage'=>$this->input->post('no_damage_x_rns_plastic_trim'),
					'na'=>$this->input->post('na_x_rns_plastic_trim')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data33);
				// Sill
				$data34 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_rns'),
					'tsr_layout_category'=>'Sill',
					'tsr_damage_name'=>$this->input->post('dn_x_rns_sill'),
					'no_damage'=>$this->input->post('no_damage_x_rns_sill'),
					'na'=>$this->input->post('na_x_rns_sill')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data34);
				// Upload Vehicle Rear - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file4']['name'])){
			        if($this->upload->do_upload('file4')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }
			        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'rns_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }		
			}	
	        // F/N/S - Front Passenger Side - Exterior
	        if( null !== $this->input->post('layout_name_x_fns') )
			{
		        // Sill
				$data35 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Sill',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_sill'),
					'no_damage'=>$this->input->post('no_damage_x_fns_sill'),
					'na'=>$this->input->post('na_x_fns_sill')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data35);
				// Plastic Trim
				$data36 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Plastic Trim',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_plastic_trim'),
					'no_damage'=>$this->input->post('no_damage_x_fns_plastic_trim'),
					'na'=>$this->input->post('na_x_fns_plastic_trim')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data36);
				// Door
				$data37 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_door'),
					'no_damage'=>$this->input->post('no_damage_x_fns_door'),
					'na'=>$this->input->post('na_x_fns_door')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data37);
				// Door Mirror
				$data38 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Door Mirror',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_door_mirror'),
					'no_damage'=>$this->input->post('no_damage_x_fns_door_mirror'),
					'na'=>$this->input->post('na_x_fns_door_mirror')
				);
				$last= $this->driver->add_to_job_sheet($user_id, $job_id, $data38);
				// Tyre Wall
				$data39 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Tyre Wall',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_tyre_wall'),
					'no_damage'=>$this->input->post('no_damage_x_fns_tyre_wall'),
					'na'=>$this->input->post('na_x_fns_tyre_wall')
				);
				$last= $this->driver->add_to_job_sheet($user_id, $job_id, $data39);
				// Tyre Tread
				$data40 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Tyre Tread',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_tyre_tread'),
					'no_damage'=>$this->input->post('no_damage_x_fns_tyre_tread'),
					'na'=>$this->input->post('na_x_fns_tyre_tread')
				);
				$last= $this->driver->add_to_job_sheet($user_id, $job_id, $data40);
				// Alloy Wheel
				$data41 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Alloy Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_alloy_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_fns_alloy_wheel'),
					'na'=>$this->input->post('na_x_fns_alloy_wheel')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data41);
				// Steel Wheel
				$data42 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Steel Wheel',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_steel_wheel'),
					'no_damage'=>$this->input->post('no_damage_x_fns_steel_wheel'),
					'na'=>$this->input->post('na_x_fns_steel_wheel')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data42);
				// Front Wing
				$data43 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_fns'),
					'tsr_layout_category'=>'Front Wing',
					'tsr_damage_name'=>$this->input->post('dn_x_fns_front_wing'),
					'no_damage'=>$this->input->post('no_damage_x_fns_front_wing'),
					'na'=>$this->input->post('na_x_fns_front_wing')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data43);
				// Upload Vehicle Rear - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file5']['name'])){
			        if($this->upload->do_upload('file5')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'fns_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last= $this->driver->upload_damages($job_id,$upload_damage);
			    }		
			}	
	        // Vehicle Front - Exterior
	        if( null !== $this->input->post('layout_name_x_vf') )
			{
		        // Front Screen
				$data44 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Screen',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_front_screen'),
					'no_damage'=>$this->input->post('no_damage_x_vf_front_screen'),
					'na'=>$this->input->post('na_x_vf_front_screen')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data44);
				// Bonnet
				$data45 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Bonnet',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_bonnet'),
					'no_damage'=>$this->input->post('no_damage_x_vf_bonnet'),
					'na'=>$this->input->post('na_x_vf_bonnet')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data45);
				// Front Lights - O/S or N/S
				$data46 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Lights - O/S or N/S',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_fl_os_ns'),
					'no_damage'=>$this->input->post('no_damage_x_vf_fl_os_ns'),
					'na'=>$this->input->post('na_x_vf_fl_os_ns')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data46);
				// Front Grill
				$data47 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Grill',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_front_grill'),
					'no_damage'=>$this->input->post('no_damage_x_vf_front_grill'),
					'na'=>$this->input->post('na_x_vf_front_grill')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data47);
				// Front Bumper
				$data48 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Bumper',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_front_bumper'),
					'no_damage'=>$this->input->post('no_damage_x_vf_front_bumper'),
					'na'=>$this->input->post('na_x_vf_front_bumper')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data48);
				// Front Number Plate
				$data49 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Number Plate',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_fnp'),
					'no_damage'=>$this->input->post('no_damage_x_vf_fnp'),
					'na'=>$this->input->post('na_x_vf_fnp')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data49);
				// Front Valance
				$data50 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vf'),
					'tsr_layout_category'=>'Front Valance',
					'tsr_damage_name'=>$this->input->post('dn_x_vf_fv'),
					'no_damage'=>$this->input->post('no_damage_x_vf_fv'),
					'na'=>$this->input->post('na_x_vf_fv')
				);
				$last= $this->driver->add_to_job_sheet($user_id,$job_id,$data50);
				// Upload Vehicle Rear - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file6']['name'])){
			        if($this->upload->do_upload('file6')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'vf_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last= $this->driver->upload_damages($job_id,$upload_damage);
			    }		
			}	
			// Vehicle Roof - Exterior
			if( null !== $this->input->post('layout_name_x_vrf') )
			{
				// Aerial
				$data51 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vrf'),
					'tsr_layout_category'=>'Aerial',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_aerial'),
					'no_damage'=>$this->input->post('no_damage_x_vr_aerial'),
					'na'=>$this->input->post('na_x_vr_aerial')
				);
				$last=$this->driver->add_to_job_sheet($user_id, $job_id, $data51);
				// Roof
				$data52 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_x_vrf'),
					'tsr_layout_category'=>'Roof',
					'tsr_damage_name'=>$this->input->post('dn_x_vr_roof'),
					'no_damage'=>$this->input->post('no_damage_x_vr_roof'),
					'na'=>$this->input->post('na_x_vr_roof')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data52);
				// Upload Vehicle Rear - Exterior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file7']['name'])){
			        if($this->upload->do_upload('file7')){
			            $uploadData = $this->upload->data();
			            $vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'vroof_exterior_vehicel_damages' => $vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }		
			}
			/*
			Interior Job Sheet Submission
			*/
			if( null !== $this->input->post('layout_name_i_fos') )
			{
				// Carpet
				$data53 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Carpet',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_carpet'),
					'no_damage'=>$this->input->post('no_damage_i_fos_carpet'),
					'na'=>$this->input->post('na_i_fos_carpet')
				);				
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data53);
				// Seat
				$data54 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Seat',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_seat'),
					'no_damage'=>$this->input->post('no_damage_i_fos_seat'),
					'na'=>$this->input->post('na_i_fos_seat')
				);
				$last =$this->driver->add_to_job_sheet($user_id,$job_id,$data54);
				// Head Restraint
				$data55 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Head Restraint',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_head_restraint'),
					'no_damage'=>$this->input->post('no_damage_i_fos_head_restraint'),
					'na'=>$this->input->post('na_i_fos_head_restraint')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data55);
				// Headlining
				$data56 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Headlining',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_headlining'),
					'no_damage'=>$this->input->post('no_damage_i_fos_headlining'),
					'na'=>$this->input->post('na_i_fos_headlining')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data56);
				// Door
				$data57 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_door'),
					'no_damage'=>$this->input->post('no_damage_i_fos_door'),
					'na'=>$this->input->post('na_i_fos_door')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data57);
				// Steering Wheel
				$data58 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Steering Wheel',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_sw'),
					'no_damage'=>$this->input->post('no_damage_i_fos_sw'),
					'na'=>$this->input->post('na_i_fos_sw')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data58);
				// Dashboard
				$data59 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Dashboard',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_dashboard'),
					'no_damage'=>$this->input->post('no_damage_i_fos_dashboard'),
					'na'=>$this->input->post('na_i_fos_dashboard')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data59);
				// Central Area (Handbrake/Gearbox)
				$data60 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Central Area (Handbrake/Gearbox)',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_ca'),
					'no_damage'=>$this->input->post('no_damage_i_fos_ca'),
					'na'=>$this->input->post('na_i_fos_ca')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data60);
				// Radio Screen
				$data61 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fos'),
					'tsr_layout_category'=>'Radio Screen',
					'tsr_damage_name'=>$this->input->post('dn_i_fos_rs'),
					'no_damage'=>$this->input->post('no_damage_i_fos_rs'),
					'na'=>$this->input->post('na_i_fos_rs')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data61);
				// Upload R/O/S - Rear Driver Side  - Interior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file8']['name'])){
			        if($this->upload->do_upload('file8')){
			            $uploadData = $this->upload->data();
			            $fos_interior_vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'fos_interior_vehicel_damages' => $fos_interior_vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }			
			}
			// R/O/S - Rear Driver Side - Interior
			if( null !== $this->input->post('layout_name_i_ros') )
			{
				// Carpet
				$data62 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_ros'),
					'tsr_layout_category'=>'Carpet',
					'tsr_damage_name'=>$this->input->post('dn_i_ros_carpet'),
					'no_damage'=>$this->input->post('no_damage_i_ros_carpet'),
					'na'=>$this->input->post('na_i_ros_carpet')
				);
				$last=$this->driver->add_to_job_sheet($user_id, $job_id, $data62);
				// Seat
				$data63 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_ros'),
					'tsr_layout_category'=>'Seat',
					'tsr_damage_name'=>$this->input->post('dn_i_ros_seat'),
					'no_damage'=>$this->input->post('no_damage_i_ros_seat'),
					'na'=>$this->input->post('na_i_ros_seat')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data63);
				// Head Restraint
				$data64 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_ros'),
					'tsr_layout_category'=>'Head Restraint',
					'tsr_damage_name'=>$this->input->post('dn_i_ros_hr'),
					'no_damage'=>$this->input->post('no_damage_i_ros_hr'),
					'na'=>$this->input->post('na_i_ros_hr')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data64);
				// Headlining
				$data65 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_ros'),
					'tsr_layout_category'=>'Headlining',
					'tsr_damage_name'=>$this->input->post('dn_i_ros_headlining'),
					'no_damage'=>$this->input->post('no_damage_i_ros_headlining'),
					'na'=>$this->input->post('na_i_ros_headlining')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data65);
				// Door
				$data66 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_ros'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_i_ros_door'),
					'no_damage'=>$this->input->post('no_damage_i_ros_door'),
					'na'=>$this->input->post('na_i_ros_door')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data66);
				
				// Upload R/O/S - Rear Driver Side  - Interior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file9']['name'])){
			        if($this->upload->do_upload('file9')){
			            $uploadData = $this->upload->data();
			            $ros_interior_vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'ros_interior_vehicel_damages' => $ros_interior_vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }			
			}
			// Boot - Interior
			if( null !== $this->input->post('layout_name_i_boot') )
			{
				// Parcel Shelf
				$data67 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Parcel Shelf',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_parcel_shelf'),
					'no_damage'=>$this->input->post('no_damage_i_boot_parcel_shelf'),
					'na'=>$this->input->post('na_i_boot_parcel_shelf')
				);
				$last=$this->driver->add_to_job_sheet($user_id,$job_id,$data67);
				// Boot Lining
				$data68 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Boot Lining',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_boot_lining'),
					'no_damage'=>$this->input->post('no_damage_i_boot_boot_lining'),
					'na'=>$this->input->post('na_i_boot_i_boot_boot_lining')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data68);
				// Boot Lid
				$data69 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Boot Lid',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_boot_lid'),
					'no_damage'=>$this->input->post('no_damage_i_boot_boot_lid'),
					'na'=>$this->input->post('na_i_boot_boot_lid')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data69);
				// Tools
				$data70 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Tools',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_tools'),
					'no_damage'=>$this->input->post('no_damage_i_boot_tools'),
					'na'=>$this->input->post('na_i_boot_tools')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data70);
				// Inflator Kit Pack
				$data71 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Inflator Kit Pack',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_ikp'),
					'no_damage'=>$this->input->post('no_damage_i_boot_ikp'),
					'na'=>$this->input->post('na_i_boot_ikp')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data71);
				// Inflator Kit Refill
				$data72 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Inflator Kit Refill',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_ikr'),
					'no_damage'=>$this->input->post('no_damage_i_boot_ikr'),
					'na'=>$this->input->post('na_i_boot_ikr')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data72);
				// Locking Wheel Nut
				$data73 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Locking Wheel Nut',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_lwn'),
					'no_damage'=>$this->input->post('no_damage_i_boot_lwn'),
					'na'=>$this->input->post('na_i_boot_lwn')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data73);
				// Spare Wheel
				$data74 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_boot'),
					'tsr_layout_category'=>'Spare Wheel',
					'tsr_damage_name'=>$this->input->post('dn_i_boot_spare_wheel'),
					'no_damage'=>$this->input->post('no_damage_i_boot_spare_wheel'),
					'na'=>$this->input->post('na_i_boot_spare_wheel')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data74);
				
				// Upload R/O/S - Rear Driver Side  - Interior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file10']['name'])){
			        if($this->upload->do_upload('file10')){
			            $uploadData = $this->upload->data();
			            $boot_interior_vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'boot_interior_vehicel_damages' => $boot_interior_vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }			
			}
			// R/N/S - Rear Passenger Side - Interior
			if( null !== $this->input->post('layout_name_i_rns') )
			{
				// Carpet
				$data75 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_rns'),
					'tsr_layout_category'=>'Carpet',
					'tsr_damage_name'=>$this->input->post('dn_i_rns_carpet'),
					'no_damage'=>$this->input->post('no_damage_i_rns_carpet'),
					'na'=>$this->input->post('na_i_rns_carpet')
				);
				$last=$this->driver->add_to_job_sheet($user_id, $job_id, $data75);
				// Seat
				$data76 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_rns'),
					'tsr_layout_category'=>'Seat',
					'tsr_damage_name'=>$this->input->post('dn_i_rns_seat'),
					'no_damage'=>$this->input->post('no_damage_i_rns_seat'),
					'na'=>$this->input->post('na_i_rns_seat')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data76);
				// Head Restraint
				$data77 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_rns'),
					'tsr_layout_category'=>'Head Restraint',
					'tsr_damage_name'=>$this->input->post('dn_i_rns_head_restraint'),
					'no_damage'=>$this->input->post('no_damage_i_rns_head_restraint'),
					'na'=>$this->input->post('na_i_rns_head_restraint')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data77);
				// Headlining
				$data78 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_rns'),
					'tsr_layout_category'=>'Headlining',
					'tsr_damage_name'=>$this->input->post('dn_i_rns_headlining'),
					'no_damage'=>$this->input->post('no_damage_i_rns_headlining'),
					'na'=>$this->input->post('na_i_rns_headlining')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data78);
				// Door
				$data79 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_rns'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_i_rns_door'),
					'no_damage'=>$this->input->post('no_damage_i_rns_door'),
					'na'=>$this->input->post('na_i_rns_door')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data79);		
				// Upload R/N/S - Rear Passenger Side  - Interior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file11']['name'])){
			        if($this->upload->do_upload('file11')){
			            $uploadData = $this->upload->data();
			            $rns_interior_vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'rns_interior_vehicel_damages' => $rns_interior_vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }			
			}
			// F/N/S - Front Passenger Side - Interior
			if( null !== $this->input->post('layout_name_i_fns') )
			{
				// Carpet
				$data80 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Carpet',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_carpet'),
					'no_damage'=>$this->input->post('no_damage_i_fns_carpet'),
					'na'=>$this->input->post('na_i_fns_carpet')
				);
				$last=$this->driver->add_to_job_sheet($user_id, $job_id, $data80);
				// Seat
				$data81 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Seat',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_seat'),
					'no_damage'=>$this->input->post('no_damage_i_fns_seat'),
					'na'=>$this->input->post('na_i_fns_seat')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data81);
				// Head Restraint
				$data82 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Head Restraint',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_head_restraint'),
					'no_damage'=>$this->input->post('no_damage_i_fns_head_restraint'),
					'na'=>$this->input->post('na_i_fns_head_restraint')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data82);
				// Headlining
				$data83 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Headlining',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_headlining'),
					'no_damage'=>$this->input->post('no_damage_i_fns_headlining'),
					'na'=>$this->input->post('na_i_fns_headlining')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data83);
				// Door
				$data84 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Door',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_door'),
					'no_damage'=>$this->input->post('no_damage_i_fns_door'),
					'na'=>$this->input->post('na_i_fns_door')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data84);
				// Glove box
				$data85 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Glove box',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_gb'),
					'no_damage'=>$this->input->post('no_damage_i_fns_gb'),
					'na'=>$this->input->post('na_i_fns_gb')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data85);
				// Dashboard
				$data86 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Dashboard',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_dashboard'),
					'no_damage'=>$this->input->post('no_damage_i_fns_dashboard'),
					'na'=>$this->input->post('na_i_fns_dashboard')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data86);
				// Vehicle Manuals
				$data87 = array(
					'job_id'=>$job_id,
					'tsr_layout_type'=>$tsr_layout_type,
					'tsr_layout_name'=>$this->input->post('layout_name_i_fns'),
					'tsr_layout_category'=>'Vehicle Manuals',
					'tsr_damage_name'=>$this->input->post('dn_i_fns_vm'),
					'no_damage'=>$this->input->post('no_damage_i_fns_vm'),
					'na'=>$this->input->post('na_i_fns_vm')
				);
				$last =$this->driver->add_to_job_sheet($user_id, $job_id, $data87);
				// Upload R/N/S - Rear Passenger Side  - Interior damage picture
		        //upload file to directory
		        if(!empty($_FILES['file12']['name'])){
			        if($this->upload->do_upload('file12')){
			            $uploadData = $this->upload->data();
			            $fns_interior_vehicel_damages = $uploadData['file_name'];
			        }else{
			        	$data['errors'] = $this->upload->display_errors();
			        }        
			        $upload_damage = array(        	
			        	'user_id' => $user_id,
			        	'fns_interior_vehicel_damages' => $rns_interior_vehicel_damages,
			        	'vd_created' => date('Y-m-d')
			        );
			        $last=$this->driver->upload_damages($job_id,$upload_damage);
			    }						
			}

			if($last= 'true'){		    		
	    		$data = array (
		            'success' => 'TSR job rate successfully submitted'
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/job_cart/'.$job_id);
	    	}else{		    		
	    		$data = array (
		            'errors' => 'Somthing went wrong!'
		        );
		        $this->session->set_flashdata($data);
		        redirect('driver/job_cart/'.$job_id);
	    	}
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->flashdata($data);
			redirect('driver');
		}
	}
	/* End Exterior & Interior damage submission */

	// Submit damage_missing_job Sheet
	public function add_damage_missing_jobs()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){
			$job_id=$this->uri->segment(3);
			$user_id=$this->session->userdata('user_id');

			$result = $this->driver->add_damage_missing_jobs($job_id,$user_id);
			if ($result == 'true') {
				$data = array (
		            'success' => 'Data successfully submitted'
		        );
		        $this->session->set_flashdata($data);
				redirect('driver/job_cart/'.$job_id);
			}else{
				$data = array (
		            'errors' => 'Failed to submit data!'
		        );
		        $this->session->set_flashdata($data);
				redirect('driver/job_cart/'.$job_id);
			}
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
	}
	public function upload_job_sheet()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$job_id = $this->input->post('job_id');			

			if ($_FILES && $_FILES['userfile']['name']) {
				
				//upload configuration
		        $config['upload_path']   = 'uploads/job_sheets/';                
		        $config['allowed_types'] = 'pdf';
		        $config['file_name'] = 'Job_Sheet_'.$job_id;
		        //$config['encrypt_name'] = TRUE;		        
		        $this->load->library('upload', $config);
		        
		        if($this->upload->do_upload('userfile')){
		            $uploadData = $this->upload->data();
		            $job_sheet = $uploadData['file_name'];

		            $upload_job_sheet = array(
			        	'job_sheet' => $job_sheet,
			        	'job_status'=>1,
			        	'job_modified' => date('Y-m-d')
			        );

			        $result=$this->driver->upload_job_sheet($job_id,$upload_job_sheet);
			        if($result == 'true')
			        {
			        	$data['success'] = "Successfully upload the job sheet of job ID: ".$job_id;

			        	$this->session->set_flashdata($data);
				        redirect('driver/job_submission');
			        }else{
			        	$data['success'] = "Somthing wrong! Failed to upload the job sheet of job ID: ".$job_id;

			        	$this->session->set_flashdata($data);
				        redirect('driver/job_submission');
			        }
		        }else{
		        	$data['errors'] = $this->upload->display_errors();

		        	$this->session->set_flashdata($data);
			        redirect('driver/job_submission');
		        }        		        		       
		    }
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}	
	}
	public function job_summary()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'job_summary', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			$this->load->view('driver/job_summary',$data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
		
	}	
	public function driver_invoice()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'driver_invoice', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			$user_id = $this->session->userdata('user_id');
			$data['invoices'] = $this->driver->get_driver_invoice($user_id);			
			$this->load->view('driver/driver_invoice',$data);
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}		
	}

	public function submit_driver_invoice_form($job_id,$customer_id)
	{		
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){
			$data = array(
				'page'		=> 'driver_invoice', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> '',
				'job_id'=> $job_id,
				'customer_id'=> $customer_id
			);

			$data['job'] = $this->driver->get_job_details_by_job_id($job_id);
			$this->load->view('driver/driver_invoice_form.php', $data);		
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
	}

	public function save_pre_invoice(){
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'pre_driver_invoice', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);

			$this->form_validation->set_rules('job_date', 'job date', 'required');
			$this->form_validation->set_rules('due_date', 'due date', 'required');
			$this->form_validation->set_rules('movement_type', 'movement type', 'required');
			//$this->form_validation->set_rules('labour_charge', 'labour charge', 'required');
			$this->form_validation->set_rules('total_hours', 'total hours', 'required');						
			$file1=$_FILES['car_washing_receipt']['name'];
			$file2=$_FILES['toll_fee_receipt']['name'];
			
			if ($this->form_validation->run() == FALSE)
            {
                $data = ['errors' => validation_errors()];
		        $this->session->set_flashdata($data);
		        redirect('driver/driver_invoice');
            }else{
            	$job_id = $this->input->post('job_id');
            	$user_id = $this->input->post('user_id');
            	$customer_id = $this->input->post('customer_id');
            	
            	$response = $this->driver->save_pre_invoice($job_id, $user_id, $customer_id);
            	if($response == TRUE)
            	{
            		$ginv = $this->generate_driver_invoice_by_job_id($job_id);
            		if($ginv == TRUE)
            		{
            			$data['success'] = 'Invoicing successfully generated and send to mail';

						$this->session->set_flashdata($data);
			        	redirect('driver/driver_invoice');
            		}else{
            			$data = array (
			            'errors' => 'Failed to generate invoice!'
				        );
		        		$this->session->set_flashdata($data);
						redirect('driver/driver_invoice');
            		}
            	}else{
            		$data = array (
			            'errors' => 'Failed to submit pre invoice!'
			        );
	        		$this->session->set_flashdata($data);
					redirect('driver/driver_invoice');
            	}
            }
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
	}

	public function generate_driver_invoice_by_job_id($job_id)
	{
		ini_set('memory_limit', '512M'); // set memory limit
		$job_id = $this->input->post('job_id');
		$data['job_id'] = $job_id;

		$pivr = $this->db->get_where('pre_invoices', ['job_id'=>$job_id])->row();
		
		require_once (APPPATH.'./third_party/dompdf/autoload.inc.php');
        	
		$dompdf = new Dompdf\Dompdf();        
  		$dompdf->setPaper(defined('DOMPDF_PAPER_SIZE') ? DOMPDF_PAPER_SIZE : 'A4', defined('DOMPDF_PAPER_ORIENTATION') ? DOMPDF_PAPER_ORIENTATION : 'landscape');
        $options = $dompdf->getOptions();
        $options->set(array('isRemoteEnabled' => TRUE));
        // allow setting a different DPI value
        if (defined('DOMPDF_DPI')) {
            $options->set(array('dpi' => '300'));
        }
        $options->setDefaultFont('Courier');
        $dompdf->setOptions($options);

        $html = $this->load->view('driver/pdf_templates/driver_invoice', $data, true);        
        $dompdf->loadHtml($html,'UTF-8');
        
        $dompdf->render();
        $pdf = $dompdf->output(); 

        $file = file_put_contents('uploads/invoices/'.$this->invoice_num($job_id, 7, 'INVOICE#').'.pdf', $pdf);
        ini_set('memory_limit', '-1'); // decrease the momory limit again
        if($file>0)
        {
        	$invoice_code = $this->invoice_num($job_id, 7, 'INVOICE#');
        	$file_name = $invoice_code.'.pdf';
        	$this->db->update('pre_invoices', ['invoice_code'=>$invoice_code,'invoice_pdf'=>$file_name, 'invoice_status'=> 1,'pre_invoice_modified'=>date('Y-m-d')], ['pre_invoice_id' => $pivr->pre_invoice_id]);
        	if($this->db->affected_rows() > 0)
        	{
       			$this->db->select('u.email,j.user_type_id');
	    		$this->db->from('jobs j');
	    		$this->db->join('users u','u.user_id=j.assign_driver_id', 'left');
	    		$this->db->where('j.job_id',$job_id);
	    		$r = $this->db->get()->row();
	    		$email = $r->email;
	    		$customer_type = $r->user_type_id;
	    		if($customer_type == 5)
	    		{
	    			$to = $r->email;
	    		}else{
	    			$to = '';
	    		}

         		/* SMTP email protocol configuration */
    			// $this->load->library('email');
				// $config = array();
				// $config['protocol'] = 'smtp';
				// $config['smtp_host'] = 'mail.mobileappsgamesstudio.com';
				// $config['smtp_user'] = 'tsr@mobileappsgamesstudio.com';
				// $config['smtp_pass'] = 'tsr@demo.com';
				// $config['smtp_port'] = '465';
				// $config['charset'] = "utf-8";
    			// $config['mailtype'] = "html";
				// $this->email->initialize($config);
				// $this->email->set_newline("\r\n");

		        // send email
				$this->load->library('email');
				$from_email = "tsr@mobileappsgamesstudio.com";		        
		        $to_email = $to;
		        $cc_email = 'nelotpalpadhan@gmail.com';
				$bcc_email = 'bhavikleeds@hotmail.co.uk';
		        //Load email library
		        $this->email->from($from_email, 'TSR | LTD');
		        $this->email->to($to_email);
		        $this->email->cc($cc_email);
		        $this->email->subject('Driver Invoice');
		        $this->email->message('This is your driver invoice '.$this->invoice_num($job_id, 7, 'INVOICE#'));
		        $this->email->attach('uploads/invoices/'.$file_name);
		        //Send mail
		        if($this->email->send())
		        {    
		        	return TRUE;
		        }else{
		        	return FALSE;			            
		        }

		        return TRUE;
        	}else{
        		return FALSE;
        	}        	
        }else{
	        return FALSE;
        }       
	}

	public function invoice_archive()
	{
		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'invoice_archive', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);
			
			$data['result'] = $this->driver->all_driver_invoice();
			$this->load->view('driver/driver_invoice_archive.php', $data);
		
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
	}

	public function download(){
		$job_id = $this->uri->segment(4);
        if(!empty($job_id)){
          //load download helper
          $this->load->helper('download');
          $this->load->helper('url');
          //$this->load->database();
          $this->db->select('invoice_pdf');
          $inv=$this->db->get_where('pre_invoices', array('job_id'=>$job_id))->row();

          //$file = 'uploads/invoices/'.$inv->invoice_pdf;
          $data   = file_get_contents('uploads/invoices/'.$inv->invoice_pdf);
          force_download($data, NULL);
        }
    }

    public function loadinvoice()
  	{
  		if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 2) ){

			$data = array(
				'page'		=> 'invoice_archive', 
				'mode'		=> 'driver', 
				'errors'		=> '', 
				'message'	=> ''
			);
			
			$job_id = $this->uri->segment(3);  		

		    //load download helper
		    $this->load->helper('download');
		    $this->load->helper('url');	    
		    
		    $this->db->select('invoice_pdf');
		    //$this->db->from('pre_invoices');
		    //$this->db->where('job_id',$job_id);
		    $inv = $this->db->get_where('pre_invoices', ['job_id'=>$job_id,])->row();
	    
			// The location of the PDF file
			// on the server
			$filename = 'uploads/invoices/'.$inv->invoice_pdf;		  
			// Header content type
			header("Content-type: application/pdf");		  
			header("Content-Length: " . filesize($filename));		  
			// Send the file to the browser.
			readfile($filename);
		
		}else{
			$data = array (
	            'errors' => 'Session time out!',
	            'is_login' => false
	        );
	        $this->session->set_flashdata($data);
			redirect('driver');
		}
  	}

  	public function invoice_num ($input, $pad_len = 7, $prefix = null) {
      if ($pad_len <= strlen($input))
          trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

      if (is_string($prefix))
          return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

      return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
  	}

  	/* Download receipt */
	public function download_receipt($filename = NULL)
	{
	    // load download helder
		$this->load->helper('download');
		// read file contents
		$data = file_get_contents(base_url('/uploads/receipts/'.$filename));
		force_download($filename, $data);
	}
	/* viewInvoicePdf */
	public function viewInvoicePdf()
	{
		$invoice_id = $this->uri->segment(3);		
		$ires = $this->driver->get_pdf_invoice($invoice_id);
		//echo $invoice_id;
		//var_dump($ires);
		//die();
		// The location of the PDF file
		// on the server
		$filename = "uploads/invoices/".$ires->invoice_pdf;	  
		// Header content type
		header("Content-type: application/pdf");		  
		header("Content-Length: " . filesize($filename));		  
		// Send the file to the browser.
		readfile($filename);
	}

###################
}