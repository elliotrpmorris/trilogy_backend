<?php



class Auth_model extends CI_Model

{

	public function __construct()

	{

	    parent::__construct();

	    date_default_timezone_set('Asia/Kolkata');

	}



	public function is_admin_login(){

		$email = $this->input->post('email');

		$password = $this->input->post('password');

		

		$this->db->select('*');

		$this->db->from('login_data');

		//$this->db->join('roles', 'roles.rid = users.role_id','left');

		$this->db->where('email_id', $email);
		$this->db->where('type', 'admin');

		$query = $this->db->get();



		$result = $query->result();

		if(count($result) > 0){

			

			if(password_verify($password,$result[0]->password))

			{

			$data = array('message' => 'Successfuly loged in', 'status' => 'loged_in', 'response'=> $result);					

			//$this->db->update('users', array('last_login'=>date('Y-m-d H:i:s')), array('user_id'=>$result[0]->id));

			}else{

				$data = array('message' => 'Password mismatch!', 'status'=>'loged_in_failed', 'response'=>'');

			}				

		}else{

			$data = array('message' => 'User record found!', 'status' => 'loged_in_failed', 'response'=> '');

		}					

		return $data;

	}

	

	public function is_driver_signup()

	{

		$userData=array(

			// Driver details

			'name' => $this->input->post('first_name').' '.$this->input->post('last_name'),

			'driver_address' => $this->input->post('driver_address'),

			'user_pin_code' => $this->input->post('user_pin_code'),

			'mobile_number' => $this->input->post('mobile_number'),

			'home_phone_number' => $this->input->post('home_phone_number'),

			'email' => $this->input->post('email'),

			'password' => md5($this->input->post('password')),

			// Next of Kin

			'kin_name' => $this->input->post('kin_first_name').' '.$this->input->post('kin_last_name'),

			'kin_mobile_number' => $this->input->post('kin_mobile_number'),

			'kin_relationship'	=> $this->input->post('kin_relationship'),

			// Driving Licence Details

			'driving_licence_number'	=> $this->input->post('licence_number'),

			'licence_expiry_date'	=> $this->input->post('licence_expiry_date'),

			'national_insurance_number'	=> $this->input->post('national_insurance_number'),

			// Accident History

			'accident_details'	=> $this->input->post('accident_details'),

			'accident_date'	=> $this->input->post('accident_date'),

			'accident_category'	=> $this->input->post('accident_category'),

			// Convictions

			'conviction_details'	=> $this->input->post('conviction_details'),

			'date_of_conviction'	=> $this->input->post('conviction_date'),

			'conviction_code'	=> $this->input->post('conviction_code'),

			'fine_ban_penalty'	=> $this->input->post('fine_ban_penalty'),

			

			'role_id' => 2,

			'user_status' => 1,

			'created' => date('Y-m-d')

		);



		$this->db->insert('users', $userData);

		$insert_id = $this->db->insert_id();

		if($insert_id > 0){

			$this->db->select('u.user_id,u.name,u.email,u.role_id,r.role_name');

			$this->db->from('users as u');

			$this->db->join('roles as r', 'r.rid = u.role_id');			

			$this->db->where('u.user_status', 1);

			$this->db->where('r.rid', 2);

			$query = $this->db->get();

			$result = $query->result();



			if(count($result) > 0){

				$this->db->update('users', array('last_login'=>date('Y-m-d H:i:s')), array('user_id'=>$result[0]->user_id));

				$data = array('message' => 'Successfuly loged in', 'status' => 'loged_in', 'response'=> $result);							

			}else{

				$data = array('message' => 'User record found!', 'status' => 'loged_in_failed', 'response'=> '');

			}					

			return $data;

		}		

	}



	public function is_driver_login(){

		$email = $this->input->post('email');

		$password = $this->input->post('password');

		

		$this->db->select('*');

		$this->db->from('users');

		$this->db->join('roles', 'roles.rid = users.role_id');

		$this->db->where('users.email', $email);

		$this->db->where('users.user_status', 1);

		$this->db->where('roles.rid', 2);

		$query = $this->db->get();

		$result = $query->result();

		

		if(count($result) > 0){

			if(md5($password) === $result[0]->password)

			{

				$this->db->update('users', array('last_login'=>date('Y-m-d H:i:s')), array('user_id'=>$result[0]->user_id));

				$data = array('message' => 'Successfuly loged in', 'status' => 'loged_in', 'response'=> $result);					

				}else{

					$data = array('message' => 'Password mismatch!', 'status'=>'loged_in_failed', 'response'=>'');

				}				

		}else{

			$data = array('message' => 'User record found!', 'status' => 'loged_in_failed', 'response'=> '');

		}					

		return $data;

	}

	public function is_user_login()

	{

		$email = $this->input->post('email');

		$password = md5($this->input->post('password'));

		$user_type = $this->input->post('user_type');



		$this->db->select('*');

		$this->db->from('users');

		$this->db->join('roles', 'roles.rid = users.role_id');

		$this->db->where('users.email', $email);

		$this->db->where('users.user_status', 1);

		$query = $this->db->get();



		$result = $query->result();

		if(count($result) > 0){

			$this->db->update('users', array('last_login'=>date('Y-m-d H:i:s')), array('user_id'=>$result[0]->user_id));									

			$this->session->set_userdata($data);

			$data = array('message' => 'Successfuly loged in', 'status' => 'loged_in', 'response'=> $result);

			

		}else{

			$data = array('message' => 'User record found!', 'status' => 'loged_in_failed', 'response'=> '');

		}						

		return $data;

	}

	public function if_exist_profile_picture($uid)

	{

		$this->db->select('user_profile_picture');

		$this->db->where('id', $uid );



		return $this->db->get('users')->result();

	}

	public function uploadProfilePicture($user_id, $uploadData)

	{

		$this->db->where('id', $user_id);        

        $this->db->update('users', $uploadData);

        

        return $this->db->affected_rows();

	}

	public function reset_password($user_id, $current_password, $new_password)

	{

		$cpassword = md5($current_password);

		$password = md5($new_password);



		$this->db->where('user_id', $id);      

		$this->db->where('password', $cpassword);		

        $this->db->update('users', array('password' => $password));

        

        return $this->db->affected_rows();

	}

}