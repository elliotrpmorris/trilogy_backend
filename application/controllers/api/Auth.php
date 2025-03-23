<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/London');

class Auth extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();

		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model("api/auth_model", 'am');
		/* require APPPATH . '/libraries/JWT.php'; */

		// CORS headers
		header("Access-Control-Allow-Methods: GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		header("Access-Control-Allow-Origin: *");

		date_default_timezone_set('Europe/London');
	}

	public function registration()
	{
		$data['name'] = $this->input->post('name');
		$data['email_id'] = $this->input->post('email_id');
		$data['password'] = $this->input->post('password');

		if (
			!isset($data['name']) || $data['name'] == ''
			|| !isset($data['email_id']) || $data['email_id'] == ''
			|| !isset($data['password']) || $data['password'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->am->checkUserExists($data['email_id']);
			if ($result) {
				$response['success'] = 0;
				$response['message'] = 'Email  already exists.';
			} else {
				$data['status'] = 'Y';

				$user_id = $this->am->setUserRegistration($data);
				$response['success'] = 1;
				$response['data']['cust_id'] = $user_id;

				$result = $this->am->fetchUserDataById($user_id);
				$response['data']['cust_id'] = $result->id;
				$response['data']['user_code'] = $result->user_code;
				$response['data']['name'] = $result->name;
				$response['data']['email_id'] = $result->email_id;
				$response['data']['age'] = $result->age;
				$response['data']['height'] = $result->height;
				$response['data']['weight'] = $result->weight;
				$response['data']['gender'] = $result->gender;
				$response['data']['activity_lavel'] = $result->activity_lavel;
				$response['data']['allergies'] = $result->allergies;
				$response['data']['health_problem'] = $result->health_problem;
				$response['data']['profile_picture'] = $result->profile_picture;
				$response['data']['baseurl'] = site_url();
				$response['success'] = 1;
				$response['type'] = 'user';

				$response['message'] = 'User registered successfully.';
			}
		}

		echo json_encode($response);
	}

	public function login()
	{
		$data['email_id'] = $this->input->post('email_id');
		$data['password'] = $this->input->post('password');

		if (
			!isset($data['email_id']) || $data['email_id'] == ''
			|| !isset($data['password']) || $data['password'] == ''
			/* || !isset($data['type']) || $data['type'] == '' */
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			/* if ($data['type'] == "user") { */
			$resultLog = $this->am->getUserLogin($data);
			if (isset($resultLog->cust_id) && $resultLog->cust_id > 0) {
				if (!password_verify($data['password'], $resultLog->password)) {
					$response['success'] = 0;
					$response['message'] = "Password doesn't match.";
				} else {

					if (isset($resultLog->type) && $resultLog->type == "user") {
						$result = $this->am->fetchUserDataById($resultLog->cust_id);

						if (isset($result->status) && $result->status == 'Y') {

							$response['data']['cust_id'] = $result->id;
							$response['data']['user_code'] = $result->user_code;
							$response['data']['name'] = $result->name;
							$response['data']['email_id'] = $result->email_id;
							$response['data']['age'] = $result->age;
							$response['data']['height'] = $result->height;
							$response['data']['weight'] = $result->weight;
							$response['data']['gender'] = $result->gender;
							$response['data']['activity_lavel'] = $result->activity_lavel;
							$response['data']['allergies'] = $result->allergies;
							$response['data']['health_problem'] = $result->health_problem;
							$response['data']['profile_picture'] = $result->profile_picture;
							$response['data']['baseurl'] = site_url();
							$response['success'] = 1;
							$response['type'] = 'user';
							$response['message'] = 'User successfully logged in.';
						} else {
							$response['success'] = 0;
							$response['message'] = 'User not found.';
						}
					} elseif (isset($resultLog->type) && $resultLog->type == "admin") {
						$response['success'] = 1;
						$response['data']['cust_id'] = $resultLog->cust_id;
						$response['type'] = 'admin';
						$response['message'] = 'Admin successfully logged in.';
					}
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'User is inactive or does not exist.';
			}
		}
		echo json_encode($response);
	}
}
