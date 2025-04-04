<?php

defined('BASEPATH') or exit('No direct script access allowed');





class Admin extends CI_Controller
{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('admin_model', 'admin');

	}



	function login()

	{

		$data = array(

			'page'		=> 'login',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);

		/* echo password_hash('123456',PASSWORD_DEFAULT); */

		$this->load->view('admin/login', $data);
	}



	function signout()

	{

		$newdata = array(

			'user_name'  => '',

			'user_email' => '',

			'logged_in' => FALSE,

		);



		$this->session->unset_userdata($newdata);

		$this->session->sess_destroy();



		redirect('admin', 'refresh');
	}



	function dashboard()

	{

		// if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1) ){



		$data = array(

			'page'		=> 'dashboard',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);

		$result = $this->admin->fetchDashboardData();

		$data['totalCust'] = $result['totalCust'];
		$data['totalMeal'] = $result['totalMeal'];
		$data['totalWork'] = $result['totalWork'];
		$data['totalExer'] = $result['totalExer'];
		$data['totalPayment'] = $result['totalPayment'];
		$data['recentCust'] = $result['recentCust'];
		$data['recentTran'] = $result['recentTran'];


		$this->load->view('admin/dashboard', $data);

	}

	function meal_tags_master()
	{

		$data = array(

			'page'		=> 'blank',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);

		$this->load->view('meal_tags_master', $data);
	}



	function meal_types_master()
	{

		$data = array(

			'page'		=> 'meal_types_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('meal_types_master', $data);
	}



	function allergen_master()
	{

		$data = array(

			'page'		=> 'allergens_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('allergens_master', $data);
	}



	function equipment_master()
	{

		$data = array(

			'page'		=> 'equipment_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('equipment_master', $data);
	}



	function dietary_master()
	{

		$data = array(

			'page'		=> 'dietary_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('dietary_master', $data);
	}



	function plan_master()
	{

		$data = array(

			'page'		=> 'plan_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('plan_master', $data);
	}



	function unit_master()
	{

		$data = array(

			'page'		=> 'unit_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('unit_master', $data);
	}



	function ingredients()
	{

		$data = array(

			'page'		=> 'ingredients',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('ingredients', $data);
	}



	function add_ingredient()
	{

		$data = array(

			'page'		=> 'ingredients',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_ingredient_form', $data);
	}



	function edit_ingredient()
	{

		$data = array(

			'page'		=> 'ingredients',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('edit_ingredient_form', $data);
	}



	function meals()
	{

		$data = array(

			'page'		=> 'meals',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('meals', $data);
	}



	function add_meal()
	{

		$data = array(

			'page'		=> 'meals',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_meal', $data);
	}



	function edit_meal()
	{

		$data = array(

			'page'		=> 'meals',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('edit_meal', $data);
	}



	function exercises()
	{

		$data = array(

			'page'		=> 'exercises',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('exercises', $data);
	}



	function add_exercise()
	{

		$data = array(

			'page'		=> 'exercises',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_exercise_form', $data);
	}



	function edit_exercise()
	{

		$data = array(

			'page'		=> 'exercises',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('edit_exercise_form', $data);
	}



	function workouts()
	{

		$data = array(

			'page'		=> 'workouts',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('workouts', $data);
	}



	function add_workout_routine()
	{

		$data = array(

			'page'		=> 'workouts',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_workout_routine_form', $data);
	}



	function plans()
	{

		$data = array(

			'page'		=> 'plans',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('plans', $data);
	}



	function add_plan()
	{

		$data = array(

			'page'		=> 'plans',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_plan_form', $data);
	}



	function recipes()
	{

		$data = array(

			'page'		=> 'recipes',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('recipes', $data);
	}



	function add_recipe()
	{

		$data = array(

			'page'		=> 'recipes',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_recipe_form', $data);
	}



	function blogs()
	{

		$data = array(

			'page'		=> 'blogs',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('blogs', $data);
	}



	function add_blog()
	{

		$data = array(

			'page'		=> 'blogs',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('add_blog_form', $data);
	}



	function blank()
	{

		$data = array(

			'page'		=> 'meal_tags_master',

			'mode'		=> 'admin',

			'error'		=> '',

			'message'	=> ''

		);



		$this->load->view('_blank', $data);
	}





	/* Client Management */

	public function client_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$data['clients'] = $this->admin->get_clients();

			$this->load->view('client_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function add_client()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->form_validation->set_rules('name', 'name', 'required');

			$this->form_validation->set_rules('mobile_number', 'mobile number', 'required');

			$this->form_validation->set_rules('email', 'email', 'required|is_unique[users.email]');

			$this->form_validation->set_rules('user_address', 'user address', 'required');

			$this->form_validation->set_rules('password', 'password', 'required');

			$this->form_validation->set_rules('user_type', 'user type', 'required');

			$this->form_validation->set_rules('job_type', 'job type', 'required');

			$this->form_validation->set_rules('minimal', 'minimal', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/client_management');
			} else {

				$add_client_data = array(

					'role_id' => $this->input->post('user_type'),

					'name' => $this->input->post('name'),

					'mobile_number' => $this->input->post('mobile_number'),

					'email' => $this->input->post('email'),

					'user_address' => $this->input->post('user_address'),

					'password' => md5($this->input->post('password')),

					'job_type' => $this->input->post('job_type'),

					'other_job_type' => $this->input->post('other_job_type'),

					'minimal' => $this->input->post('minimal'),

					'user_status' => 1,

					'created' => date('Y-m-d')

				);

				$result = $this->admin->add_client($add_client_data);

				if ($result == true) {

					$data = array(

						'success' => 'Client information successfully inserted'

					);

					$this->session->set_flashdata($data);

					redirect('admin/client_management');
				} else {

					$data = array(

						'errors' => 'Client information failed to insert!'

					);



					$this->session->set_flashdata($data);

					redirect('admin/client_management');
				}
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function edit_client()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('edit_client', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_client_by_user_id($user_id)

	{

		$result = $this->admin->get_client_by_user_id($user_id);

		echo json_encode($result);
	}

	public function save_edited_client()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->form_validation->set_rules('name', 'name', 'required');

			$this->form_validation->set_rules('mobile_number', 'mobile number', 'required');

			$this->form_validation->set_rules('email', 'email', 'required');

			$this->form_validation->set_rules('user_address', 'user address', 'required');

			$this->form_validation->set_rules('user_type', 'user type', 'required');

			$this->form_validation->set_rules('job_type', 'job type', 'required');

			$this->form_validation->set_rules('minimal', 'minimal', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/client_management');
			} else {

				$user_id = $this->input->post('user_id');

				$reset_password = $this->input->post('password');



				$save_client_data = array(

					'role_id' => $this->input->post('user_type'),

					'name' => $this->input->post('name'),

					'mobile_number' => $this->input->post('mobile_number'),

					'email' => $this->input->post('email'),

					'user_address' => $this->input->post('user_address'),

					'job_type' => $this->input->post('job_type'),

					'other_job_type' => $this->input->post('other_job_type'),

					'minimal' => $this->input->post('minimal'),

					'modified' => date('Y-m-d')

				);

				$result = $this->admin->save_edited_client($user_id, $reset_password, $save_client_data);

				if ($result == true) {

					$data = array(

						'success' => 'Client information successfully updated'

					);

					$this->session->set_flashdata($data);

					redirect('admin/client_management');
				} else {

					$data = array(

						'errors' => 'Client information failed to update!'

					);



					$this->session->set_flashdata($data);

					redirect('admin/client_management');
				}
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_client_by_user_id($user_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$result = $this->admin->delete_client_by_user_id($user_id);

			if ($result == true) {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_client_status_by_user_id($user_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$result = $this->admin->set_client_status_by_user_id($user_id);

			if ($result == true) {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function search_clients()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'client_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['clients'] = $this->admin->search_clients($start_date, $end_date);

			$this->load->view('client_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Driver management */

	public function driver_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'driver_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$data['drivers'] = $this->admin->get_drivers();

			$this->load->view('driver_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function driver_view()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'driver_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$user_id = $this->uri->segment(3);

			$data['profileData'] = $this->admin->get_driver_profile_by_user_id($user_id);

			$data['availabilityData'] = $this->admin->get_driver_availability_by_user_id($user_id);

			$data['drivenRate'] = $this->admin->get_driven_rate_by_user_id($user_id);

			$data['transportRate'] = $this->admin->get_transport_rate_by_user_id($user_id);



			$this->load->view('driver_view', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_driver_status($user_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$result = $this->admin->set_driver_status($user_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function search_drivers()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'driver_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['drivers'] = $this->admin->search_drivers($start_date, $end_date);

			$this->load->view('driver_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	//public function add_driver()

	// {

	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1) ){



	// 		$data = array(

	// 			'page'		=> 'driver_management', 

	// 			'mode'		=> 'admin', 

	// 			'error'		=> '', 

	// 			'message'	=> ''

	// 		);

	// 		$this->load->view('add_driver',$data);

	// 	}else{

	// 		$data = array (

	//             'errors' => 'Session time out!',

	//             'is_login' => false

	//         );

	//         $this->session->set_flashdata($data);

	// 		redirect('admin');

	// 	}



	// }	

	/* Inspaction Management */

	public function inspaction_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'inspaction_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['inspections'] = $this->admin->get_inspection();

			$this->load->view('inspaction_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_instection()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			$this->form_validation->set_rules('inspection_sheet_type', 'inspection sheet type', 'required');

			$this->form_validation->set_rules('damage_level', 'damage level', 'required');

			$this->form_validation->set_rules('user_type', 'user type', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_management');
			} else {



				$result = $this->admin->add_inspection();

				if ($result == true) {

					$data = array(

						'success' => "Inspaction successfully inserted"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_management');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_inspection_status($inspection_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_inspection_status($inspection_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_inspection_by_id($inspection_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->get_inspection_by_id($inspection_id);

			echo json_encode($result);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_inspection_by_id()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			$this->form_validation->set_rules('inspection_sheet_type', 'inspection sheet type', 'required');

			$this->form_validation->set_rules('damage_level', 'damage level', 'required');

			$this->form_validation->set_rules('user_type', 'user type', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_management');
			} else {

				$inspection_id = $this->input->post('inspection_id');

				$inspactionData = array(

					'inspection_sheet_name' => $this->input->post('inspection_sheet_name'),

					'inspection_sheet_type' => $this->input->post('inspection_sheet_type'),

					'damage_level' => $this->input->post('damage_level'),

					'user_type' => $this->input->post('user_type'),

					'inspection_created' => date('Y-m-d')

				);

				$result = $this->admin->update_inspection_by_id($inspection_id, $inspactionData);

				if ($result == true) {

					$data = array(

						'success' => "Inspaction successfully uploaded"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_management');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_inspection_by_id($inspection_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_inspection_by_id($inspection_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function search_inspections()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = array(

				'page'		=> 'inspaction_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['inspections'] = $this->admin->search_inspections($start_date, $end_date);

			$this->load->view('inspaction_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Inspection Sheet Management*/

	public function inspaction_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'inspaction_sheet',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$data['redSheetData'] = $this->admin->get_red_inspection_sheet();

			$data['tsrSheetData'] = $this->admin->get_tsr_inspection_sheet();

			$this->load->view('inspaction_sheet', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_red_inspection_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			} else {



				$result = $this->admin->add_inspection_sheet();

				if ($result == true) {

					$data = array(

						'success' => "RED inspaction sheet successfully added"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_tsr_inspection_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			} else {



				$result = $this->admin->add_inspection_sheet();

				if ($result == true) {

					$data = array(

						'success' => "TSR inspaction sheet successfully added"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_inspection_sheet_by_id($inspection_sheet_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->get_inspection_sheet_by_id($inspection_sheet_id);

			echo json_encode($result);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_inspection_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			} else {



				$result = $this->admin->update_inspection_sheet();

				if ($result == true) {

					$data = array(

						'success' => "Inspaction sheet successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/inspaction_sheet');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function status_inspection_sheet($inspection_sheet_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->status_inspection_sheet($inspection_sheet_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_inspection_sheet($inspection_sheet_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_inspection_sheet($inspection_sheet_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Damage Types */

	public function damage_type()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'damage_type',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['damageTypeData'] = $this->admin->get_damage_type();

			$this->load->view('damage_type', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_damage_type()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('damage_type', 'damage type', 'required');

			$this->form_validation->set_rules('damage_type_rate', 'damage type rate', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damage_type');
			} else {



				$result = $this->admin->add_damage_type();

				if ($result == true) {

					$data = array(

						'success' => "Damage type successfully inserted"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damage_type');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function status_damage_type($damage_type_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->status_damage_type($damage_type_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_damage_type_by_id($damage_type_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->get_damage_type_by_id($damage_type_id);

			echo json_encode($result);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_damage_type()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('damage_type', 'damage type', 'required');

			$this->form_validation->set_rules('damage_type_rate', 'damage type rate', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damage_type');
			} else {



				$result = $this->admin->update_damage_type();

				if ($result == true) {

					$data = array(

						'success' => "Damage type successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damage_type');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_damage_type($damage_type_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_damage_type($damage_type_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Damage Level */

	public function damage_level()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'damage_level',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['damageLevels'] = $this->admin->get_damage_levels();

			$this->load->view('damage_level', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_damage_level()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('damage_level', 'damage level', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damage_level');
			} else {



				$result = $this->admin->add_damage_level();

				if ($result == true) {

					$data = array(

						'success' => "Damage level successfully inserted"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damage_level');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_damage_level_by_id($damage_level_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->get_damage_level_by_id($damage_level_id);

			echo json_encode($result);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_damage_level()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('damage_level', 'damage level', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damage_level');
			} else {



				$result = $this->admin->update_damage_level();

				if ($result == true) {

					$data = array(

						'success' => "Damage Level successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damage_level');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function status_damage_level($damage_level_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->status_damage_level($damage_level_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_damage_level($damage_level_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_damage_level($damage_level_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Assign Pricing */

	public function assign_pricing()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'assign_pricing',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['inspectionsItemPrices'] = $this->admin->get_inspectionsItemPrices();

			$data['driversPrices'] = $this->admin->get_driversPrices();

			$data['clientsPrices'] = $this->admin->get_clientsPrices();



			$this->load->view('assign_pricing', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_inspection_item_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_category', 'inspection category', 'required');

			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			$this->form_validation->set_rules('inspection_type', 'inspection type', 'required');

			$this->form_validation->set_rules('inspection_item_price', 'inspection item price', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->set_inspection_item_price();

				if ($result == true) {

					$data = array(

						'success' => "Inspection Items successfully inserted"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_inspection_price_status($inspection_items_price_id)

	{



		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_inspection_price_status($inspection_items_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_inspection_price_status($inspection_items_price_id)

	{

		$result = $this->admin->get_inspection_price_status($inspection_items_price_id);

		echo json_encode($result);
	}

	public function update_inspection_item_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_category', 'inspection category', 'required');

			$this->form_validation->set_rules('inspection_sheet_name', 'inspection sheet name', 'required');

			$this->form_validation->set_rules('inspection_type', 'inspection type', 'required');

			$this->form_validation->set_rules('inspection_item_price', 'inspection item price', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->update_inspection_item_price();

				if ($result == true) {

					$data = array(

						'success' => "Inspection item price successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_inspection_price_status($inspection_items_price_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_inspection_price_status($inspection_items_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_driver_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('driver_name', 'driver name', 'required');

			$this->form_validation->set_rules('hour_range_start', 'hour range start', 'required');

			$this->form_validation->set_rules('hour_range_end', 'hour range end', 'required');

			$this->form_validation->set_rules('price_per_hour', 'price per hour', 'required');



			$this->form_validation->set_rules('tsr_price', 'tsr price', 'required');

			$this->form_validation->set_rules('additional_hour_start', 'additional hour start', 'required');

			$this->form_validation->set_rules('additional_hour_end', 'additional hour end', 'required');

			$this->form_validation->set_rules('additional_hour_price_per_hour', 'additional hour price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_tsr_price_per_hour', 'additional hour tsr price per hour', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->set_driver_price();

				if ($result == true) {

					$data = array(

						'success' => "Inspection item price successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_driver_price($driver_price_id)

	{

		$result = $this->admin->get_driver_price($driver_price_id);

		echo json_encode($result);
	}

	public function update_driver_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('driver_name', 'driver name', 'required');

			$this->form_validation->set_rules('hour_range_start', 'hour range start', 'required');

			$this->form_validation->set_rules('hour_range_end', 'hour range end', 'required');

			$this->form_validation->set_rules('price_per_hour', 'price per hour', 'required');



			$this->form_validation->set_rules('tsr_price', 'tsr price', 'required');

			$this->form_validation->set_rules('additional_hour_start', 'additional hour start', 'required');

			$this->form_validation->set_rules('additional_hour_end', 'additional hour end', 'required');

			$this->form_validation->set_rules('additional_hour_price_per_hour', 'additional hour price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_tsr_price_per_hour', 'additional hour tsr price per hour', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->update_driver_price();

				if ($result == true) {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_status_driver_price($driver_price_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_status_driver_price($driver_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_driver_price($driver_price_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_driver_price($driver_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_client_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('client_name', 'client name', 'required');

			$this->form_validation->set_rules('hour_range_start', 'hour range start', 'required');

			$this->form_validation->set_rules('hour_range_end', 'hour range end', 'required');

			$this->form_validation->set_rules('price_per_hour', 'price per hour', 'required');



			$this->form_validation->set_rules('tsr_price_per_hour', 'tsr price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_start', 'additional hour start', 'required');

			$this->form_validation->set_rules('additional_hour_end', 'additional hour end', 'required');

			$this->form_validation->set_rules('additional_hour_price_per_hour', 'additional hour price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_tsr_price_per_hour', 'additional hour tsr price per hour', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->set_client_price();

				if ($result == true) {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_client_price($client_price_id)

	{

		$result = $this->admin->get_client_price($client_price_id);

		echo json_encode($result);
	}

	public function update_client_price()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('client_name', 'driver name', 'required');

			$this->form_validation->set_rules('hour_range_start', 'hour range start', 'required');

			$this->form_validation->set_rules('hour_range_end', 'hour range end', 'required');

			$this->form_validation->set_rules('price_per_hour', 'price per hour', 'required');



			$this->form_validation->set_rules('tsr_price_per_hour', 'tsr price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_start', 'additional hour start', 'required');

			$this->form_validation->set_rules('additional_hour_end', 'additional hour end', 'required');

			$this->form_validation->set_rules('additional_hour_price_per_hour', 'additional hour price per hour', 'required');

			$this->form_validation->set_rules('additional_hour_tsr_price_per_hour', 'additional hour tsr price per hour', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			} else {



				$result = $this->admin->update_client_price();

				if ($result == true) {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/assign_pricing');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_status_client_price($client_price_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_status_client_price($client_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_client_assign_price($client_price_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_client_assign_price($client_price_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Upload supportive PDF */

	public function upload_support_doc()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'upload_support_doc',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['pdfData'] = $this->admin->get_supportive_pdf();

			$this->load->view('upload_support_doc', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function upload_supportive_pdf()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_type', 'inspection type', 'required');

			$this->form_validation->set_rules('file', '', 'callback_file_check_pdf');



			$titleName = trim($this->input->post('inspection_type'));

			$pdfString = str_replace(' ', '_', $titleName);



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);

				$this->session->set_flashdata($data);

				redirect('admin/upload_support_doc');
			} else {

				$check_pdf_title = $this->admin->get_supportive_pdf_title($titleName);

				if ($check_pdf_title != NULL) {

					$pdf_title_name = $check_pdf_title->assign_inspection_type;
				}



				if ($pdf_title_name == $titleName) {

					$data = array(

						'errors' => 'Title name already exist! Choose a different one.'

					);

					$this->session->set_flashdata($data);

					redirect('admin/upload_support_doc');
				} else {

					//upload configuration

					$config['upload_path']   = 'uploads/supportive_pdf/';

					$config['allowed_types'] = 'pdf';

					//$config['max_size']      = 1024;

					//$config['encrypt_name'] = TRUE;

					$config['file_name'] = $pdfString;

					$this->load->library('upload', $config);

					//upload file to directory

					if ($this->upload->do_upload('file')) {

						$uploadData = $this->upload->data();

						$supportive_pdf = $uploadData['file_name'];
					} else {

						$data['errors'] = $this->upload->display_errors();
					}



					/*Input data*/

					$pdfData = array(

						'assign_inspection_type' =>	$titleName,

						'supportive_pdf_name' => $supportive_pdf,

						'supportive_pdf_status' => 1,

						'supportive_pdf_created' =>	date('Y-m-d')

					);



					$result = $this->admin->upload_supportive_pdf($pdfData);



					if ($result == true) {

						$data = array(

							'success' => 'Supporting PDF successfully uploaded'

						);

						$this->session->set_flashdata($data);

						redirect('admin/upload_support_doc');
					} else {

						$data = array(

							'errors' => 'Something wrong!'

						);

						$this->session->set_flashdata($data);

						redirect('admin/upload_support_doce');
					}

					$this->session->set_flashdata($data);

					redirect('admin/upload_support_doc');
				}
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_supportive_pdf($supportive_pdf_id)

	{

		$result = $this->admin->get_supportive_pdf_by_id($supportive_pdf_id);

		echo json_encode($result);
	}

	public function update_supportive_pdf()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('inspection_type', 'inspection type', 'required');

			$this->form_validation->set_rules('file', '', 'callback_file_check_pdf');

			// set pdf file name

			$titleName = trim($this->input->post('inspection_type'));

			$pdfString = str_replace(' ', '_', $titleName);



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);

				$this->session->set_flashdata($data);

				redirect('admin/upload_support_doc');
			} else {

				$supportive_pdf_id = $this->input->post('supportive_pdf_id');

				$check_pdf_exist = $this->admin->get_supportive_pdf_by_id($supportive_pdf_id);

				if ($check_pdf_exist != NULL) {

					$pdf_file = $check_pdf_exist->supportive_pdf_name;

					$pdf_title_name = $check_pdf_exist->assign_inspection_type;
				}



				// echo $pdf_title_name;

				// die();



				if ($pdf_title_name == $titleName) {

					$data = array(

						'errors' => 'Title name already exist! Choose a different one.'

					);

					$this->session->set_flashdata($data);

					redirect('admin/upload_support_doc');
				} else {

					//upload configuration

					$config['upload_path']   = 'uploads/supportive_pdf/';

					$config['allowed_types'] = 'pdf';

					//$config['max_size']      = 1024;

					$config['file_name'] = $pdfString;

					//$config['encrypt_name'] = TRUE;

					$this->load->library('upload', $config);

					//upload file to directory

					if ($this->upload->do_upload('file')) {

						$uploadData = $this->upload->data();

						$supportive_pdf = $uploadData['file_name'];
					} else {

						$data['errors'] = $this->upload->display_errors();
					}



					/*Input data*/

					$pdfData = array(

						'assign_inspection_type' =>	$this->input->post('inspection_type'),

						'supportive_pdf_name' => $supportive_pdf,

						'supportive_pdf_modified' =>	date('Y-m-d')

					);



					$result = $this->admin->update_supportive_pdf($supportive_pdf_id, $pdfData);



					if ($result == true) {

						unlink($config['upload_path'] . $pdf_file);

						$data = array(

							'success' => 'Supporting PDF successfully updated'

						);

						$this->session->set_flashdata($data);

						redirect('admin/upload_support_doc');
					} else {

						$data = array(

							'errors' => 'Something wrong!'

						);

						$this->session->set_flashdata($data);

						redirect('admin/upload_support_doc');
					}
				}
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_supportive_pdf_status($supportive_pdf_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_supportive_pdf_status($supportive_pdf_id);

			if ($result == true) {

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_supportive_pdf($supportive_pdf_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$check_pdf_exist = $this->admin->get_supportive_pdf_by_id($supportive_pdf_id);

			if (!empty($check_pdf_exist)) {

				$pdf_file = $check_pdf_exist->supportive_pdf_name;
			}

			//upload configuration

			$path   = 'uploads/supportive_pdf/';



			$result = $this->admin->delete_supportive_pdf($supportive_pdf_id);

			if ($result == true) {

				unlink($path . $pdf_file);

				$data = "YES";
			} else {

				$data = "NO";
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/*

     * file value and type check during validation

     */

	public function file_check_pdf($str)
	{

		$allowed_mime_type_arr = array('application/pdf');

		$mime = get_mime_by_extension($_FILES['file']['name']);

		if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {

			if (in_array($mime, $allowed_mime_type_arr)) {

				return true;
			} else {

				$this->form_validation->set_message('file_check_pdf', 'Please select only pdf file.');

				return false;
			}
		} else {

			$this->form_validation->set_message('file_check_pdf', 'Please choose a pdf file to upload.');

			return false;
		}
	}

	/* Job Sheet Creation */

	public function job_sheet_creation()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'job_sheet_creation',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['jobSheets'] = $this->admin->get_job_sheets();

			$this->load->view('job_sheet_creation', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_job_sheet_by_id($job_sheet_id)

	{

		$data = $this->admin->get_job_sheet_by_id($job_sheet_id);

		echo json_encode($data);
	}

	public function set_job_sheet_creation($job_sheet_id)

	{

		$result = $this->admin->set_job_sheet_creation($job_sheet_id);

		if ($result == 'true') {

			$data = 'YES';
		} else {

			$data = 'NO';
		}

		echo json_encode($data);
	}

	public function add_job_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('customer_name', 'customer name', 'required');

			$this->form_validation->set_rules('customer_vehicle_reg_number', 'vehicle reg number', 'required');

			$this->form_validation->set_rules('customer_vehicle_make_model', 'customer vehicle make/model', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/job_sheet_creation');
			} else {

				$jobData = array(

					'customer_id' => $this->input->post('customer_name'),

					'customer_vehicle_reg_number' => $this->input->post('customer_vehicle_reg_number'),

					'customer_vehicle_make_model' => $this->input->post('customer_vehicle_make_model'),

					'job_sheet_status' => 1,

					'job_sheet_created' => date('Y-m-d')

				);

				$result = $this->admin->add_job_sheet($jobData);

				if ($result == 'true') {

					$data = array(

						'success' => "Record successfully inserted"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/job_sheet_creation');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_job_sheet()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('customer_name', 'customer name', 'required');

			$this->form_validation->set_rules('customer_vehicle_reg_number', 'vehicle reg number', 'required');

			$this->form_validation->set_rules('customer_vehicle_make_model', 'customer vehicle make/model', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/job_sheet_creation');
			} else {

				$job_sheet_id = $this->input->post('job_sheet_id');

				$jobData = array(

					'customer_id' => $this->input->post('customer_name'),

					'customer_vehicle_reg_number' => $this->input->post('customer_vehicle_reg_number'),

					'customer_vehicle_make_model' => $this->input->post('customer_vehicle_make_model'),

					'job_sheet_created' => date('Y-m-d')

				);



				$result = $this->admin->update_job_sheet($job_sheet_id, $jobData);

				if ($result == 'true') {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/job_sheet_creation');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_job_sheet($job_sheet_id)

	{

		$result = $this->admin->delete_job_sheet($job_sheet_id);

		if ($result == 'true') {

			$data = 'YES';
		} else {

			$data = 'NO';
		}

		echo json_encode($data);
	}

	public function search_job_sheets()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'job_sheet_creation',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['jobSheets'] = $this->admin->search_job_sheets($start_date, $end_date);

			$this->load->view('job_sheet_creation', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Job Management */

	public function job_list()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'job_list',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['jobsData'] = $this->admin->get_all_jobs();

			$this->load->view('job_list', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function completed_job_list()

	{



		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'completed_job_list',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['jobsData'] = $this->admin->completed_job_list();

			$this->load->view('completed_job_list', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Autocomplete search customer */

	public function search_customer()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			// Search term

			$searchTerm = $this->input->post('searchTerm');

			// Get users

			$response = $this->admin->search_customer($searchTerm);

			echo json_encode($response);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	/* get driven_rates */

	public function driven_rates_by_id()

	{

		$driverid = 0;



		if (isset($_POST['assign_driver_id'])) {

			//$driverid = $this->db->escape($_POST['assign_driver_id']); // assigned driver id

			$driverid = $_POST['assign_driver_id'];
		}



		$vehicle_model = array();



		if ($driverid > 0) {

			$this->db->select('driven_rates_id,user_id,vehicle_model');

			$this->db->from('driven_rates');

			$this->db->where('user_id', $driverid);

			$query = $this->db->get();

			if ($query->num_rows() > 0) {

				$result = $query->result();

				foreach ($result as $key => $row) {

					$driven_rates_id  = $row->driven_rates_id;

					$model = $row->vehicle_model;



					$vehicle_model[] = array("driven_rates_id" => $driven_rates_id, "vehicle_model" => $model);
				}
			}
		}



		// encoding array to json format

		echo json_encode($vehicle_model);
	}

	/* Add job */

	public function add_job()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('client_name', 'client name', 'required');

			$this->form_validation->set_rules('assign_driver_id', 'assign driver', 'required');

			$this->form_validation->set_rules('assigned_customer_name', 'customer name', 'required');

			$this->form_validation->set_rules('assigned_customer_email', 'customer email', 'required');

			$this->form_validation->set_rules('user_type_id', 'user type', 'required');



			$this->form_validation->set_rules('job_type', 'job type', 'required');

			$this->form_validation->set_rules('vehicle_type', 'vehicle type', 'required');



			$this->form_validation->set_rules('vehicle_reg_number', 'vehicle registration number', 'required');

			$this->form_validation->set_rules('vehicle_make', 'vehicle make', 'required');

			$this->form_validation->set_rules('vehicle_model', 'vehicle model', 'required');



			$this->form_validation->set_rules('pickup_address', 'pickup address', 'required');

			$this->form_validation->set_rules('pickup_city', 'pickup city', 'required');

			$this->form_validation->set_rules('pickup_county', 'pickup county', 'required');

			$this->form_validation->set_rules('pickup_postcode', 'pickup postcode', 'required');

			$this->form_validation->set_rules('drop_address', 'drop address', 'required');

			$this->form_validation->set_rules('drop_city', 'drop city', 'required');

			$this->form_validation->set_rules('drop_county', 'drop county', 'required');

			$this->form_validation->set_rules('drop_postcode', 'drop postcode', 'required');

			$this->form_validation->set_rules('job_date', 'job date', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/job_list');
			} else {

				$pdfdata = [];

				$count = count($_FILES['files']['name']);

				for ($i = 0; $i < $count; $i++) {



					if (!empty($_FILES['files']['name'][$i])) {



						$_FILES['file']['name'] = $_FILES['files']['name'][$i];

						$_FILES['file']['type'] = $_FILES['files']['type'][$i];

						$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];

						$_FILES['file']['error'] = $_FILES['files']['error'][$i];

						$_FILES['file']['size'] = $_FILES['files']['size'][$i];



						$config['upload_path'] = 'uploads/supportive_pdf/';

						$config['allowed_types'] = 'pdf';

						$config['file_name'] = $_FILES['files']['name'][$i];



						$this->load->library('upload', $config);



						if ($this->upload->do_upload('file')) {

							$uploadData = $this->upload->data();

							$filename = $uploadData['file_name'];



							$pdfdata[] = $filename;
						}
					}
				}



				$help_manual = implode(',', $pdfdata);



				$jobData = array(

					'customer_id' => $this->input->post('client_name'),

					'assign_driver_id' => $this->input->post('assign_driver_id'),

					'assigned_customer_name' => $this->input->post('assigned_customer_name'),

					'assigned_customer_email' => $this->input->post('assigned_customer_email'),

					'job_type' => $this->input->post('job_type'),

					'user_type_id' => $this->input->post('user_type_id'),

					'vehicle_type' => $this->input->post('vehicle_type'),

					'vehicle_reg_number' => $this->input->post('vehicle_reg_number'),

					'vehicle_make' => $this->input->post('vehicle_make'),

					'vehicle_model' => $this->input->post('vehicle_model'),

					'pickup_address' => $this->input->post('pickup_address'),

					'pickup_city' => $this->input->post('pickup_city'),

					'pickup_county' => $this->input->post('pickup_county'),

					'pickup_home_phone' => $this->input->post('pickup_home_phone'),

					'pickup_postcode' => $this->input->post('pickup_postcode'),

					'drop_address' => $this->input->post('drop_address'),

					'drop_city' => $this->input->post('drop_city'),

					'drop_county' => $this->input->post('drop_county'),

					'drop_home_phone' => $this->input->post('drop_home_phone'),

					'drop_postcode' => $this->input->post('drop_postcode'),

					'job_date' => $this->input->post('job_date'),

					'help_manual' => $help_manual,

					'job_created' => date('Y-m-d')

				);

				$result = $this->admin->add_job($jobData);

				if ($result == 'true') {

					$data = array(

						'success' => "Job successfully generated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/job_list');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_job_by_id($job_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$client = $this->admin->get_customer_by_job_id($job_id);

			$job = $this->admin->get_job_by_id($job_id);

			$driver = $this->admin->get_driver_by_job_id($job_id);



			$jobsData = array(

				'customer_id' => $client->customer_id,

				'client_name' => $client->name,

				'job_id' => $job->job_id,

				'assign_driver_id' => $driver->assign_driver_id,

				'assigned_customer_name' => $job->assigned_customer_name,

				'assigned_customer_email' => $job->assigned_customer_email,

				'driver_name' => $driver->name,

				'vehicle_type' => $job->vehicle_type,

				'vehicle_reg_number' => $job->vehicle_reg_number,

				'vehicle_make' => $job->vehicle_make,

				'vehicle_model' => $job->vehicle_model,

				'job_type' => $job->job_type,

				'user_type_id' => $job->user_type_id,

				'pickup_address' => $job->pickup_address,

				'pickup_city' => $job->pickup_city,

				'pickup_county' => $job->pickup_county,

				'pickup_home_phone' => $job->pickup_home_phone,

				'pickup_postcode' => $job->pickup_postcode,

				'drop_address' => $job->drop_address,

				'drop_city' => $job->drop_city,

				'drop_county' => $job->drop_county,

				'drop_home_phone' => $job->drop_home_phone,

				'drop_postcode' => $job->drop_postcode,

				'job_date' => $job->job_date

			);

			echo json_encode($jobsData);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function edit_job_by_id()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('client_name', 'client name', 'required');

			$this->form_validation->set_rules('assign_driver_id', 'assign driver', 'required');

			$this->form_validation->set_rules('assigned_customer_name', 'customer name', 'required');

			$this->form_validation->set_rules('assigned_customer_email', 'customer email', 'required');

			$this->form_validation->set_rules('user_type_id', 'user type', 'required');

			$this->form_validation->set_rules('job_type', 'job type', 'required');

			$this->form_validation->set_rules('vehicle_type', 'vehicle type', 'required');

			$this->form_validation->set_rules('pickup_address', 'pickup address', 'required');

			$this->form_validation->set_rules('pickup_city', 'pickup city', 'required');

			$this->form_validation->set_rules('pickup_county', 'pickup county', 'required');

			$this->form_validation->set_rules('pickup_postcode', 'pickup postcode', 'required');

			$this->form_validation->set_rules('drop_address', 'drop address', 'required');

			$this->form_validation->set_rules('drop_city', 'drop city', 'required');

			$this->form_validation->set_rules('drop_county', 'drop county', 'required');

			$this->form_validation->set_rules('drop_postcode', 'drop postcode', 'required');

			$this->form_validation->set_rules('job_date', 'job date', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/job_list');
			} else {

				$job_id = $this->input->post('job_id');

				//upload configuration

				$config['upload_path']   = 'uploads/supportive_pdf/';

				$config['allowed_types'] = 'pdf';

				$this->load->library('upload', $config);



				$pdfdata = [];

				$count = count($_FILES['files']['name']);

				if ($count > 0) {

					$res = $this->db->get_where('jobs', array('job_id' => $job_id))->row();

					if ($res->help_manual != NULL) {

						$x = explode(',', $res->help_manual);

						foreach ($x as $key) {

							unlink($config['upload_path'] . $key);
						}
					}
				}

				for ($i = 0; $i < $count; $i++) {

					if (!empty($_FILES['files']['name'][$i])) {

						$_FILES['file']['name'] = $_FILES['files']['name'][$i];

						$_FILES['file']['type'] = $_FILES['files']['type'][$i];

						$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];

						$_FILES['file']['error'] = $_FILES['files']['error'][$i];

						$_FILES['file']['size'] = $_FILES['files']['size'][$i];



						$config['upload_path'] = 'uploads/supportive_pdf/';

						$config['allowed_types'] = 'pdf';

						$config['file_name'] = $_FILES['files']['name'][$i];



						$this->load->library('upload', $config);



						if ($this->upload->do_upload('file')) {

							$uploadData = $this->upload->data();

							$filename = $uploadData['file_name'];



							$pdfdata[] = $filename;
						}
					}
				}



				$help_manual = implode(',', $pdfdata);



				$jobData = array(

					'customer_id' => $this->input->post('client_name'),

					'assigned_customer_name' => $this->input->post('assigned_customer_name'),

					'assigned_customer_email' => $this->input->post('assigned_customer_email'),

					'assign_driver_id' => $this->input->post('assign_driver_id'),

					'job_type' => $this->input->post('job_type'),

					'user_type_id' => $this->input->post('user_type_id'),

					'vehicle_type' => $this->input->post('vehicle_type'),

					'vehicle_reg_number' => $this->input->post('vehicle_reg_number'),

					'vehicle_make' => $this->input->post('vehicle_make'),

					'vehicle_model' => $this->input->post('vehicle_model'),

					'pickup_address' => $this->input->post('pickup_address'),

					'pickup_city' => $this->input->post('pickup_city'),

					'pickup_county' => $this->input->post('pickup_county'),

					'pickup_home_phone' => $this->input->post('pickup_home_phone'),

					'pickup_postcode' => $this->input->post('pickup_postcode'),

					'drop_address' => $this->input->post('drop_address'),

					'drop_city' => $this->input->post('drop_city'),

					'drop_county' => $this->input->post('drop_county'),

					'drop_home_phone' => $this->input->post('drop_home_phone'),

					'drop_postcode' => $this->input->post('drop_postcode'),

					'job_date' => $this->input->post('job_date'),

					'help_manual' => $help_manual,

					'job_modified' => date('Y-m-d')

				);



				$result = $this->admin->edit_job_by_id($job_id, $jobData);

				if ($result == 'true') {

					$data = array(

						'success' => "Job successfully updated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/job_list');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_job_status($job_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_job_status($job_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function job_search()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'job_list',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['jobsData'] = $this->admin->job_search($start_date, $end_date);

			$this->load->view('job_list', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function completed_job_search()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'completed_job_list',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['jobsData'] = $this->admin->completed_job_search($start_date, $end_date);

			$this->load->view('completed_job_list', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Rate Management */

	public function driven_rates()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'driven_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['tsrDrivenRates'] = $this->admin->get_tsr_driven_rate();

			$this->load->view('driven_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_tsr_driven_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('tsr_driven_driver_id', 'tsr driver driven', 'required');

			//$this->form_validation->set_rules('tsr_driven_hourly_price', 'tsr driven hourly price', 'required');

			//$this->form_validation->set_rules('tsr_driven_additional_hourly_price', 'tsr driven additional hourly price', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/driven_rates');
			} else {

				$existDriver = $this->admin->get_tsr_driven_driver_id($this->input->post('tsr_driven_driver_id'));

				if ($existDriver > 0) {

					$data = array(

						'errors' => "Driver driven rate already exist!"

					);
				} else {

					$tsrDrivenRate = array(

						'tsr_driven_driver_id' => $this->input->post('tsr_driven_driver_id'),

						'tsr_car_driven_price' => $this->input->post('tsr_car_driven_price'),

						'tsr_car_driven_hours' => $this->input->post('tsr_car_driven_hours'),

						'tsr_car_driven_additional_price' => $this->input->post('tsr_car_driven_additional_price'),

						'tsr_car_driven_additional_hours' => $this->input->post('tsr_car_driven_additional_hours'),

						'tsr_track_driven_price' => $this->input->post('tsr_track_driven_price'),

						'tsr_track_driven_hours' => $this->input->post('tsr_track_driven_hours'),

						'tsr_track_driven_additional_price' => $this->input->post('tsr_track_driven_additional_price'),

						'tsr_track_driven_additional_hours' => $this->input->post('tsr_track_driven_additional_hours'),

						'tsr_driven_rate_status' => 1,

						'tsr_driven_rate_created' => date('Y-m-d')

					);

					$result = $this->admin->add_tsr_driven_rate($tsrDrivenRate);

					if ($result == 'true') {

						$data = array(

							'success' => "Driver driven rate successfully updated"

						);
					} else {

						$data = array(

							'errors' => "Something wrong!"

						);
					}
				}



				$this->session->set_flashdata($data);

				redirect('admin/driven_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_tsr_driven_rate_by_id($tsr_driven_driver_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = $this->admin->get_tsr_driven_rate_by_id($tsr_driven_driver_id);

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_tsr_driven_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('tsr_driven_driver_id', 'tsr driver driven', 'required');

			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/driven_rates');
			} else {

				$tsr_driven_rate_id = $this->input->post('tsr_driven_rate_id');

				$tsrDrivenRate = array(

					'tsr_driven_driver_id' => $this->input->post('tsr_driven_driver_id'),

					'tsr_car_driven_price' => $this->input->post('tsr_car_driven_price'),

					'tsr_car_driven_hours' => $this->input->post('tsr_car_driven_hours'),

					'tsr_car_driven_additional_price' => $this->input->post('tsr_car_driven_additional_price'),

					'tsr_car_driven_additional_hours' => $this->input->post('tsr_car_driven_additional_hours'),

					'tsr_track_driven_price' => $this->input->post('tsr_track_driven_price'),

					'tsr_track_driven_hours' => $this->input->post('tsr_track_driven_hours'),

					'tsr_track_driven_additional_price' => $this->input->post('tsr_track_driven_additional_price'),

					'tsr_track_driven_additional_hours' => $this->input->post('tsr_track_driven_additional_hours'),

					'tsr_driven_rate_modified' => date('Y-m-d')

				);

				$result = $this->admin->update_tsr_driven_rate($tsr_driven_rate_id, $tsrDrivenRate);

				if ($result == 'true') {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/driven_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_tsr_driven_rate_status($tsr_driven_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_tsr_driven_rate_status($tsr_driven_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_tsr_driven_rate($tsr_driven_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_tsr_driven_rate($tsr_driven_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function search_tsr_driven_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'driven_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['tsrDrivenRates'] = $this->admin->search_tsr_driven_rate($start_date, $end_date);

			$this->load->view('driven_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* Transported Rates */

	public function transported_rates()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'transported_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$data['tsrTransportRates'] = $this->admin->get_tsr_transported_rate();

			$this->load->view('transported_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_tsr_transported_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$this->form_validation->set_rules('tsr_transported_driver_id', 'tsr transported driver', 'required');

			$this->form_validation->set_rules('tsr_transported_hourly_price', 'tsr transported hourly price', 'required');

			$this->form_validation->set_rules('tsr_transported_additional_hourly_price', 'tsr transported additional hourly price', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/transported_rates');
			} else {

				$existTransportedDriver = $this->admin->get_tsr_transported_driver_id($this->input->post('tsr_transported_driver_id'));

				//var_dump($existTransportedDriver); die();

				if ($existTransportedDriver == 'true') {

					$data = array(

						'errors' => "Transported rate already exist!"

					);
				} else {

					$tsrTransportedRate = array(

						'tsr_transported_driver_id' => $this->input->post('tsr_transported_driver_id'),

						'tsr_transported_hourly_price' => $this->input->post('tsr_transported_hourly_price'),

						'tsr_transported_additional_hourly_price' => $this->input->post('tsr_transported_additional_hourly_price'),

						'tsr_transported_rate_status' => 1,

						'tsr_transported_rate_created' => date('Y-m-d')

					);

					$result = $this->admin->add_tsr_transported_rate($tsrTransportedRate);

					if ($result == 'true') {

						$data = array(

							'success' => "Transported Rate successfully inserted"

						);
					} else {

						$data = array(

							'errors' => "Something wrong!"

						);
					}
				}



				$this->session->set_flashdata($data);

				redirect('admin/transported_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_tsr_transported_rate_by_id($tsr_transported_driver_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = $this->admin->get_tsr_transported_rate_by_id($tsr_transported_driver_id);

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function update_tsr_transported_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_transported_driver_id', 'tsr transported driver', 'required');

			$this->form_validation->set_rules('tsr_transported_hourly_price', 'tsr transported hourly price', 'required');

			$this->form_validation->set_rules('tsr_transported_additional_hourly_price', 'tsr transported additional hourly price', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/transported_rates');
			} else {

				$tsr_transported_rate_id = $this->input->post('tsr_transported_rate_id');

				$tsrTransportedRate = array(

					'tsr_transported_driver_id' => $this->input->post('tsr_transported_driver_id'),

					'tsr_transported_hourly_price' => $this->input->post('tsr_transported_hourly_price'),

					'tsr_transported_additional_hourly_price' => $this->input->post('tsr_transported_additional_hourly_price'),

					'tsr_transported_rate_status' => 1,

					'tsr_transported_rate_created' => date('Y-m-d')

				);

				$result = $this->admin->update_tsr_transported_rate($tsr_transported_rate_id, $tsrTransportedRate);

				if ($result == 'true') {

					$data = array(

						'success' => "Record successfully updated"

					);
				} else {

					$data = array(

						'error' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/transported_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function set_tsr_transported_rate_status($tsr_transported_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_tsr_transported_rate_status($tsr_transported_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_tsr_transported_rate($tsr_transported_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_tsr_transported_rate($tsr_transported_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function search_tsr_transported_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'transported_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$start_date = $this->input->post('start_date');

			$end_date = $this->input->post('end_date');

			$data['tsrTransportRates'] = $this->admin->search_tsr_transported_rate($start_date, $end_date);



			$this->load->view('transported_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	/* Damaged or Missing Rates */

	public function damaged_or_missing_rates()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'damaged_or_missing',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['redRates'] = $this->admin->get_damaged_or_missing_rates();

			$this->load->view('damaged_or_missing_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function add_damaged_missing_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_damage_name', 'tsr damage name', 'required');

			$this->form_validation->set_rules('tsr_damage_repair_rate', 'tsr damage repair rate', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damaged_or_missing_rates');
			} else {



				$dmRate = array(

					'tsr_layout_type' => 'Damaged or Missing Layout',

					'tsr_damage_name' => $this->input->post('tsr_damage_name'),

					'tsr_damage_repair_rate' => $this->input->post('tsr_damage_repair_rate'),

					'tsr_rate_status' => 1,

					'tsr_rate_created' => date('Y-m-d')

				);

				$result = $this->admin->add_damaged_missing_rate($dmRate);

				if ($result == 'true') {

					$data = array(

						'success' => "Damage or missing rate successfully inserted"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damaged_or_missing_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function get_damaged_or_missing_rate_by_id($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = $this->admin->get_damaged_or_missing_rate_by_id($tsr_rate_id);

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function update_damaged_or_missing_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_damage_name', 'tsr damage name', 'required');

			$this->form_validation->set_rules('tsr_damage_repair_rate', 'tsr damage repair rate', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/damaged_or_missing_rates');
			} else {

				$tsr_rate_id = $this->input->post('tsr_rate_id');



				$dmRate = array(

					'tsr_layout_type' => 'Damaged or Missing Layout',

					'tsr_damage_name' => $this->input->post('tsr_damage_name'),

					'tsr_damage_repair_rate' => $this->input->post('tsr_damage_repair_rate'),

					'tsr_rate_modified' => date('Y-m-d')

				);

				$result = $this->admin->update_damaged_or_missing_rate($tsr_rate_id, $dmRate);

				if ($result == 'true') {

					$data = array(

						'success' => "Damaged or Missing rate successfully updated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/damaged_or_missing_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function set_damaged_or_missing_rate_status($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_damaged_or_missing_rate_status($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function delete_damaged_or_missing_rate($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_damaged_missing_rate($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	/* RED TSR Rates */

	public function red_inspection_rates()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'red_inspection_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['redRates'] = $this->admin->get_redRates();

			$this->load->view('red_inspection_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	/* Download receipt */

	public function download_receipt($filename = NULL)

	{

		// load download helder

		$this->load->helper('download');

		// read file contents

		$data = file_get_contents(base_url('/uploads/receipts/' . $filename));

		force_download($filename, $data);
	}



	public function add_red_tsr_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_layout_type', 'tsr layout type', 'required');

			$this->form_validation->set_rules('tsr_layout_name', 'tsr layout name', 'required');

			$this->form_validation->set_rules('tsr_layout_category', 'tsr layout category', 'required');

			//$this->form_validation->set_rules('tsr_damage_name', 'tsr damage name', 'required');

			$this->form_validation->set_rules('tsr_damage_name[]', 'tsr damage name', 'required|callback_check_tsr_damage_name');

			$this->form_validation->set_rules('tsr_damage_repair_rate', 'tsr damage repair rate', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/red_inspection_rates');
			} else {



				$redRate = array(

					'tsr_layout_type' => $this->input->post('tsr_layout_type'),

					'tsr_layout_name' => $this->input->post('tsr_layout_name'),

					'tsr_layout_category' => $this->input->post('tsr_layout_category'),

					'tsr_damage_name' => implode(',', $this->input->post('tsr_damage_name[]')),

					'tsr_damage_repair_rate' => $this->input->post('tsr_damage_repair_rate'),

					'tsr_rate_status' => 1,

					'tsr_rate_created' => date('Y-m-d')

				);

				$result = $this->admin->add_red_tsr_rate($redRate);

				if ($result == 'true') {

					$data = array(

						'success' => "RED inspection rate successfully inserted"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/red_inspection_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function get_red_tsr_rate_by_id($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = $this->admin->get_red_tsr_rate_by_id($tsr_rate_id);

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function update_red_tsr_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_layout_name', 'tsr layout name', 'required');

			$this->form_validation->set_rules('tsr_layout_category', 'tsr layout category', 'required');

			$this->form_validation->set_rules('tsr_damage_name[]', 'tsr damage name', 'required|callback_check_tsr_damage_name');

			$this->form_validation->set_rules('tsr_damage_repair_rate', 'tsr damage repair rate', 'required');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/red_inspection_rates');
			} else {

				$tsr_rate_id = $this->input->post('tsr_rate_id');



				$redRate = array(

					'tsr_layout_type' => $this->input->post('tsr_layout_type'),

					'tsr_layout_name' => $this->input->post('tsr_layout_name'),

					'tsr_layout_category' => $this->input->post('tsr_layout_category'),

					'tsr_damage_name' => implode(',', $this->input->post('tsr_damage_name[]')),

					'tsr_damage_repair_rate' => $this->input->post('tsr_damage_repair_rate'),

					'tsr_rate_modified' => date('Y-m-d')

				);

				$result = $this->admin->update_red_tsr_rate($tsr_rate_id, $redRate);

				if ($result == 'true') {

					$data = array(

						'success' => "RED inspection rate successfully updated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/red_inspection_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function set_red_tsr_rate_status($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_red_tsr_rate_status($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function delete_red_tsr_rate($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_red_tsr_rate($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	/* TSR Rates */

	public function tsr_rates()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'tsr_rates',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['tsrRates'] = $this->admin->get_tsrRates();

			$this->load->view('tsr_rates', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function add_tsr_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_layout_type', 'tsr layout type', 'required');

			$this->form_validation->set_rules('tsr_layout_name', 'tsr layout name', 'required');

			$this->form_validation->set_rules('tsr_layout_category', 'tsr layout category', 'required');

			$this->form_validation->set_rules('tsr_damage_name[]', 'tsr damage name', 'required|callback_check_tsr_damage_name');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/tsr_rates');
			} else {



				$tsrRate = array(

					'tsr_layout_type' => $this->input->post('tsr_layout_type'),

					'tsr_layout_name' => $this->input->post('tsr_layout_name'),

					'tsr_layout_category' => $this->input->post('tsr_layout_category'),

					'tsr_damage_name' => implode(',', $this->input->post('tsr_damage_name[]')),

					'tsr_rate_status' => 1,

					'tsr_rate_created' => date('Y-m-d')

				);

				$result = $this->admin->add_tsr_rate($tsrRate);

				if ($result == 'true') {

					$data = array(

						'success' => "TSR exterior rate successfully inserted"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/tsr_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function get_tsr_rate_by_id($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = $this->admin->get_tsr_rate_by_id($tsr_rate_id);

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function update_tsr_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$this->form_validation->set_rules('tsr_layout_name', 'tsr layout name', 'required');

			$this->form_validation->set_rules('tsr_layout_category', 'tsr layout category', 'required');

			$this->form_validation->set_rules('tsr_damage_name[]', 'tsr damage name', 'required|callback_check_tsr_damage_name');



			if ($this->form_validation->run() == FALSE) {

				$data = array(

					'errors' => validation_errors()

				);



				$this->session->set_flashdata($data);

				redirect('admin/tsr_rates');
			} else {

				$tsr_rate_id = $this->input->post('tsr_rate_id');



				$tsrRate = array(

					'tsr_layout_type' => $this->input->post('tsr_layout_type'),

					'tsr_layout_name' => $this->input->post('tsr_layout_name'),

					'tsr_layout_category' => $this->input->post('tsr_layout_category'),

					'tsr_damage_name' => implode(',', $this->input->post('tsr_damage_name[]')),

					'tsr_rate_modified' => date('Y-m-d')

				);

				$result = $this->admin->update_tsr_rate($tsr_rate_id, $tsrRate);

				if ($result == 'true') {

					$data = array(

						'success' => "TSR rate successfully updated"

					);
				} else {

					$data = array(

						'errors' => "Something wrong!"

					);
				}



				$this->session->set_flashdata($data);

				redirect('admin/tsr_rates');
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function set_tsr_rate_status($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->set_tsr_rate_status($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function delete_tsr_rate($tsr_rate_id)

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$result = $this->admin->delete_tsr_rate($tsr_rate_id);

			if ($result == 'true') {

				$data = 'YES';
			} else {

				$data = 'NO';
			}

			echo json_encode($data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	/* tsr_damage_name multiple select validation callback */

	function check_tsr_damage_name($tsr_damage_name)

	{

		if ($tsr_damage_name == "none") {

			$this->form_validation->set_message('check_tsr_damage_name', 'Please Select Damages.');

			return false;
		} else {

			// User picked something.

			return true;
		}
	}





	// public function add_red_inspecction_damage()

	// {

	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1) ){

	// 		$result=$this->admin->add_red_inspecction_damage();

	// 		if($result == 'true')

	// 		{

	// 			$data = array(

	// 				'page'		=> 'other_rates', 

	// 				'mode'		=> 'admin', 

	// 				'success'		=> 'Successfully set the Red inspecction damage', 

	// 				'message'	=> ''

	// 			);

	// 			//$this->load->view('other_rates',$data);

	// 			redirect('admin/other_rates',$data);	

	// 		}else{

	// 			$data = array(

	// 				'page'		=> 'other_rates', 

	// 				'mode'		=> 'admin', 

	// 				'error'		=> 'Failed to set red inspecction damage', 

	// 				'message'	=> ''

	// 			);				

	// 			$this->load->view('other_rates',$data);	

	// 		}

	// 	}else{

	// 		$data = array (

	//             'errors' => 'Session time out!',

	//             'is_login' => false

	//         );

	//         $this->session->set_flashdata($data);

	// 		redirect('admin');

	// 	}

	// }

	public function add_alloys_tyres_damage_rate()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$result = $this->admin->add_alloys_tyres_damage_rate();

			if ($result == 'true') {

				$data = array(

					'page'		=> 'other_rates',

					'mode'		=> 'admin',

					'success'		=> 'Successfully set Alloys/Tyres damage rate',

					'message'	=> ''

				);

				redirect('admin/other_rates', $data);
			} else {

				$data = array(

					'page'		=> 'other_rates',

					'mode'		=> 'admin',

					'error'		=> 'Failed to set Alloys/Tyres damage rate',

					'message'	=> ''

				);

				redirect('admin/other_rates', $data);
			}
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}





	public function driver_invoice()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'tsr_driver_invoice',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$data['invoices'] = $this->admin->get_driver_invoice();

			$this->load->view('driver_invoice', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function viewInvoicePdf()

	{

		$invoice_id = $this->uri->segment(3);

		$ires = $this->admin->get_pdf_invoice($invoice_id);



		// The location of the PDF file

		// on the server

		$filename = "uploads/invoices/" . $ires->invoice_pdf;

		// Header content type

		header("Content-type: application/pdf");

		header("Content-Length: " . filesize($filename));

		// Send the file to the browser.

		readfile($filename);
	}



	public function client_invoice()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {

			$data = array(

				'page'		=> 'client_invoice',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('client_invoice', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function payment_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'payment_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$this->load->view('payment_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function query_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'query_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('query_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function testimonial_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'testimonial_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$this->load->view('testimonial_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function feedback_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'feedback_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('feedback_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function newsletter_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'newsletter_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('newsletter_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function user_report()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'user_report',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$this->load->view('user_report', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function payment_report()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'payment_report',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('payment_report', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function job_report()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'job_report',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('job_report', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	// public function home_banner()

	// {

	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1) ){



	// 		$data = array(

	// 			'page'		=> 'newsletter_management', 

	// 			'mode'		=> 'admin', 

	// 			'error'		=> '', 

	// 			'message'	=> ''

	// 		);

	// 		$this->load->view('includes/header');

	// 		$this->load->view('home_banner',$data);

	// 	}else{

	// 		$data = array (

	//             'errors' => 'Session time out!',

	//             'is_login' => false

	//         );

	//         $this->session->set_flashdata($data);

	//         redirect('admin');

	// 	}

	// }

	// public function seo()

	// {

	// 	if( ($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1) ){



	// 		$data = array(

	// 			'page'		=> 'seo', 

	// 			'mode'		=> 'admin', 

	// 			'error'		=> '', 

	// 			'message'	=> ''

	// 		);



	// 		$this->load->view('seo',$data);

	// 	}else{

	// 		$data = array (

	//             'errors' => 'Session time out!',

	//             'is_login' => false

	//         );

	//         $this->session->set_flashdata($data);

	//         redirect('admin');

	// 	}		

	// }

	public function profile()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'profile',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('profile', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function roll_management()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'roll_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);



			$this->load->view('roll_management', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}



	public function add_newslatter()

	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'newsletter_management',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$this->load->view('includes/header');

			$this->load->view('add_newslatter', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}

	public function assign_price_client_driver()
	{

		if (($this->session->userdata('status') == 'loged_in') && ($this->session->userdata('role_id') == 1)) {



			$data = array(

				'page'		=> 'assign_price_client_driver',

				'mode'		=> 'admin',

				'error'		=> '',

				'message'	=> ''

			);

			$this->load->view('includes/header');

			$this->load->view('assign_price_client_driver', $data);
		} else {

			$data = array(

				'errors' => 'Session time out!',

				'is_login' => false

			);

			$this->session->set_flashdata($data);

			redirect('admin');
		}
	}
}
