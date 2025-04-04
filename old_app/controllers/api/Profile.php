<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/London');

class Profile extends CI_Controller
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

	public function calculateBmr($age, $height, $weight, $sex, $activity)
	{
		if (strtoupper(trim($sex)) == "MALE") {
			$otrFactor = 66.4730;
			$ageCal = 6.7550  * $age;
			$heightCal = 5.0033 * $height;
			$weightCal = 13.7516 * $weight;

			$calorie = ((($otrFactor + $weightCal + $heightCal) - ($ageCal))) * $activity;
		} elseif (strtoupper(trim($sex)) == "FEMALE") {
			$otrFactor = 655.0955;
			$ageCal = 4.6756 * $age;
			$heightCal = 1.8496 * $height;
			$weightCal = 9.5634 * $weight;

			$calorie = ((($otrFactor + $weightCal + $heightCal) - ($ageCal))) * $activity;
		} else {
			$calorie = "NA";
		}

		return $calorie;
	}

	public function resetPassword()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['password'] = $this->input->post('password');

		if (
			!isset($data['password']) || $data['password'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {


			$result = $this->am->updatePassword($data);
			if ($result) {
				$response['success'] = 1;
				$response['message'] = 'Password data successfully updated.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Invalid user.';
			}
		}

		echo json_encode($response);
	}

	public function forgetPasswordOtp()
	{
		$data['email_id'] = $this->input->post('email_id');
		if (
			!isset($data['email_id']) || $data['email_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$data['otp'] = rand(1111, 9999);
			$result = $this->am->forgetOtp($data);
			if (isset($result) && $result > 0) {

				$this->load->library('email');

				$this->email->from('admin@trilogy.com');
				$this->email->to($data['email_id']);
				$this->email->subject('Reset pasword from Trilogy');
				$this->email->message('<p><img src="http://mobileappsgamesstudio.com/works/trilogy/assets/images/logo.png"></p><p>Dear user,</p>
					<p>You have requested to reset your password.</p>
					<p>You OTP is: 4590' . $data['otp'].'</p>
					<p>Please input this code into your app screen and rest your password.</p>
					<p>Thanks, team Trilogy health. </p>');
				$this->email->send();

				/* $from_email = "info@nu-by-js.com";
				$to_email = $email_id;

				$config = array();
				$config['protocol'] = 'smtp';
				$config['smtp_host'] = 'smtp.ionos.co.uk';
				$config['smtp_user'] = 'jordan@nu-by-js.com';
				$config['smtp_pass'] = 'Mrbond007!';
				$config['smtp_port'] = 587;
				$config['smtp_crypto'] = 'tls';
				$config['mailtype'] = 'html';
				$config['charset']      = 'iso-8859-1';
				$this->email->initialize($config);
				$this->email->set_newline("\r\n");

				$this->email->from($from_email);
				$this->email->to($to_email);
				$this->email->subject('Reset pasword link from NU');
				$this->email->message($msg);
				//Send mail 
				$this->email->send(); */

				$response['success'] = 1;
				$response['data'] = array(
					'cust_id' => $result,
					'otp' => $data['otp']
				);
				$response['message'] = 'Otp successfully sent.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Invalid user.';
			}
		}

		echo json_encode($response);
	}

	public function forgetPasswordVerify()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['otp'] = $this->input->post('otp');
		$data['password'] = $this->input->post('password');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['otp']) || $data['otp'] == ''
			|| !isset($data['password']) || $data['password'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->am->chkOtp($data);
			if (isset($result) && $result > 0) {

				$result = $this->am->updatePassword($data);
				if ($result) {
					$response['success'] = 1;
					$response['message'] = 'Password data successfully updated.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Invalid user.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'otp does not match.';
			}
		}

		echo json_encode($response);
	}

	public function updatePersonalData()
	{
		$data['name'] = $this->input->post('name');
		$data['email_id'] = $this->input->post('email_id');
		$data['age'] = $this->input->post('age');
		$data['gender'] = $this->input->post('gender');
		$data['height'] = $this->input->post('height');
		$data['weight'] = $this->input->post('weight');
		$data['country'] = $this->input->post('country');
		$data['cust_id'] = $this->input->post('cust_id');

		if (
			!isset($data['name']) || $data['name'] == ''
			|| !isset($data['email_id']) || $data['email_id'] == ''
			|| !isset($data['age']) || $data['age'] == ''
			|| !isset($data['gender']) || $data['gender'] == ''
			|| !isset($data['height']) || $data['height'] == ''
			|| !isset($data['weight']) || $data['weight'] == ''
			|| !isset($data['country']) || $data['country'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLog = $this->am->checkUserExistsWithId($data['email_id'], $data['cust_id']);
			if ($resultLog) {
				$response['success'] = 0;
				$response['message'] = 'Email  already exists.';
			} else {

				$result = $this->am->updateProfileData($data);
				if ($result) {
					$response['success'] = 1;
					$response['data']['cust_id'] = $result;

					$returnData = $this->am->fetchUserDataById($result);

					$response['data']['cust_id'] = $returnData->id;
					$response['data']['user_code'] = $returnData->user_code;
					$response['data']['name'] = $returnData->name;
					$response['data']['email_id'] = $returnData->email_id;
					$response['data']['age'] = $returnData->age;
					$response['data']['height'] = $returnData->height;
					$response['data']['weight'] = $returnData->weight;
					$response['data']['gender'] = $returnData->gender;
					$response['data']['activity_lavel'] = $returnData->activity_lavel;
					$response['data']['allergies'] = $returnData->allergies;
					$response['data']['health_problem'] = $returnData->health_problem;
					$response['data']['profile_picture'] = $returnData->profile_picture;
					$response['data']['baseurl'] = site_url();
					$response['success'] = 1;
					$response['type'] = 'user';
					$response['message'] = 'Profile data successfully updated.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Invalid user.';
				}
			}
		}

		echo json_encode($response);
	}

	public function updateFitnessData()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['allergies'] = $this->input->post('allergies');
		$data['health_problem'] = $this->input->post('health_problem');
		$data['activity_lavel'] = $this->input->post('activity_lavel');

		if (
			!isset($data['activity_lavel']) || $data['activity_lavel'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else if (isset($data['activity_lavel']) && (floatval($data['activity_lavel']) < 1 || floatval($data['activity_lavel']) > 1.9)) {
			$response['success'] = 0;
			$response['message'] = 'Please select the number between 1 to 1.9.';
		} else {
			$result = $this->am->updateFitnessData($data);
			if ($result) {
				$response['success'] = 1;
				$response['data']['cust_id'] = $result;

				$returnData = $this->am->fetchUserDataById($result);

				$response['data']['cust_id'] = $returnData->id;
				$response['data']['user_code'] = $returnData->user_code;
				$response['data']['name'] = $returnData->name;
				$response['data']['email_id'] = $returnData->email_id;
				$response['data']['age'] = $returnData->age;
				$response['data']['height'] = $returnData->height;
				$response['data']['weight'] = $returnData->weight;
				$response['data']['gender'] = $returnData->gender;
				$response['data']['activity_lavel'] = $returnData->activity_lavel;
				$response['data']['allergies'] = $returnData->allergies;
				$response['data']['health_problem'] = $returnData->health_problem;
				$response['data']['profile_picture'] = $returnData->profile_picture;
				$response['data']['baseurl'] = site_url();
				$response['success'] = 1;
				$response['type'] = 'user';
				$response['message'] = 'Fitness data successfully updated.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Invalid user.';
			}
		}

		echo json_encode($response);
	}

	public function setGoal()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		//$data['week'] = $this->input->post('week');
		$data['goal_type'] = $this->input->post('goal_type');
		$data['start_date'] = $this->input->post('start_date');
		//$data['weight'] = $this->input->post('weight');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			//|| !isset($data['weight']) || $data['weight'] == ''
			//|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['goal_type']) || $data['goal_type'] == ''
			|| !isset($data['start_date']) || $data['start_date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Please choose your start date';
		} else {
			/* if (isset($data['weight']) && $data['weight'] > 0) {
				if (($data['weight'] / $data['week']) > 2) {
					$response['success'] = 0;
					$response['message'] = 'The amount of weight in the number of weeks you have selected is not healthy. Please reset your goals';
				} else { */
					$result = $this->am->getUserPersonalProfile($data['cust_id']);
					/* echo "<pre>";
					print_r($result);
					die(); */
					if ($result) {
						$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);

						/* $dataPost['weight_goal'] = $data['weight'];
						$dataPost['week_target'] = $data['week'];

						$totalCalorieLess = $dataPost['weight_goal'] * 3500;

						$dataPost['week_calorie_less'] = $totalCalorieLess / $data['week'];
						$dataPost['day_less_calorie'] = $dataPost['week_calorie_less'] / 7; */


						$dataPost['cust_id'] = $data['cust_id'];
						$dataPost['start_date'] = $data['start_date'];
						$dataPost['goal_type'] = $data['goal_type'];

						$result = $this->am->setGoal($dataPost);
						/* if (isset($data['goal_type']) && $data['goal_type'] == 1) {
							$result->day_intake_calorie = strval(round($bmr - $result->day_less_calorie));
						} elseif (isset($data['goal_type']) && $data['goal_type'] == 2) {
							$result->day_intake_calorie = strval(round($bmr + $result->day_less_calorie));
						} elseif (isset($data['goal_type']) && $data['goal_type'] == 3) {
							$result->day_intake_calorie = strval(round($bmr));
						} */
						if (isset($data['goal_type']) && $data['goal_type'] == 1) {
							$result->day_intake_calorie = strval(round($bmr - 300));
						} elseif (isset($data['goal_type']) && $data['goal_type'] == 2) {
							$result->day_intake_calorie = strval(round($bmr + 300));
						} elseif (isset($data['goal_type']) && $data['goal_type'] == 3) {
							$result->day_intake_calorie = strval(round($bmr));
						}

						if ($result) {
							$response['success'] = 1;
							$response['data'] = $result;
							$response['message'] = 'Your goal has been successfully updated.';
						} else {
							$response['success'] = 0;
							$response['message'] = 'Sorry!! no user found.';
						}
					} else {
						$response['success'] = 0;
						$response['message'] = 'Sorry!! no user found.';
					}
				/* }
			} else {
				$response['success'] = 0;
				$response['message'] = 'Please choose weight you want to loose.';
			} */
		}

		echo json_encode($response);
	}

	public function getUserGoal()
	{
		$data['cust_id'] = $this->input->post('cust_id');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Please choose your start date';
		} else {

			$result = $this->am->getUserPersonalProfile($data['cust_id']);
			if ($result) {
				$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);
				$resultGoal = $this->am->getGoal($data['cust_id']);

				if ($resultGoal) {

					if (isset($resultGoal->goal_type) && $resultGoal->goal_type == 1) {
						$resultGoal->day_intake_calorie = strval(round($bmr - 300));
					} elseif (isset($resultGoal->goal_type) && $resultGoal->goal_type == 2) {
						$resultGoal->day_intake_calorie = strval(round($bmr + 300));
					} elseif (isset($resultGoal->goal_type) && $resultGoal->goal_type == 3) {
						$resultGoal->day_intake_calorie = strval(round($bmr));
					}
					//$resultGoal->day_intake_calorie = strval(round($bmr - $resultGoal->day_less_calorie));

					$response['success'] = 1;
					$response['data'] = $resultGoal;
					$response['message'] = 'Your goal has been successfully updated.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no user found.';
			}
		}

		echo json_encode($response);
	}

	public function getDayRemainingCalorie()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->am->getUserPersonalProfile($data['cust_id']);
			if ($result) {
				$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);
				$result = $this->am->getGoal($data['cust_id']);

				if ($result) {

					if (isset($result->goal_type) && $result->goal_type == 1) {
						$resultGoal['day_intake_calorie'] = strval(round($bmr - 300));
					} elseif (isset($result->goal_type) && $result->goal_type == 2) {
						$resultGoal['day_intake_calorie'] = strval(round($bmr + 300));
					} elseif (isset($result->goal_type) && $result->goal_type == 3) {
						$resultGoal['day_intake_calorie'] = strval(round($bmr));
					}


					$mealData = $this->am->getMealByCustDate($data);
					/* if (isset($mealData->total_calorie) && $mealData->total_calorie > 0) {
						$caroiePer = $mealData->total_calorie;
					} else {
						$caroiePer = 0;
					}

					$mealIntakeCalori = ($resultGoal['day_intake_calorie']  * $caroiePer) / 100; */
					if (isset($mealData->total_calorie) && $mealData->total_calorie > 0) {
						$mealIntakeCalori = $mealData->total_calorie;
					} else {
						$mealIntakeCalori = 0;
					}

					$resultGoal['day_remaining_calorie'] = strval(round($resultGoal['day_intake_calorie'] - $mealIntakeCalori));

					$response['success'] = 1;
					$response['data'] = $resultGoal;
					$response['message'] = 'Your goal has been successfully updated.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no user found.';
			}
		}

		echo json_encode($response);
	}

	public function setProfilePicture()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($_FILES['image']) || $_FILES['image'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$target_file = "uploads/profilepic/" . basename($_FILES["image"]["name"]);
			move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

			$this->am->setProfilePicture($target_file, $data['cust_id']);

			$response['success'] = 1;
			$response['message'] = 'Image Uploaded Successfully.';
		}

		echo json_encode($response);
	}

	public function fetchProfileData()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Please choose your start date';
		} else {

			$returnData = $this->am->fetchUserDataById($data['cust_id']);

			if ($returnData) {
				$response['data']['cust_id'] = $returnData->id;
				$response['data']['user_code'] = $returnData->user_code;
				$response['data']['name'] = $returnData->name;
				$response['data']['email_id'] = $returnData->email_id;
				$response['data']['age'] = $returnData->age;
				$response['data']['height'] = $returnData->height;
				$response['data']['weight'] = $returnData->weight;
				$response['data']['gender'] = $returnData->gender;
				$response['data']['activity_lavel'] = $returnData->activity_lavel;
				$response['data']['allergies'] = $returnData->allergies;
				$response['data']['health_problem'] = $returnData->health_problem;
				$response['data']['profile_picture'] = $returnData->profile_picture;
				$response['data']['baseurl'] = site_url();
				$response['success'] = 1;
				$response['type'] = 'user';
				$response['message'] = 'Your goal has been successfully updated.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no user found.';
			}
		}

		echo json_encode($response);
	}

	public function getAllergiesList()
	{
		$result = $this->am->getAllergies();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Allergies data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		echo json_encode($response);
	}

	public function getHealthProblem()
	{
		$result = $this->am->getHealthProblem();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Allergies data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		echo json_encode($response);
	}

	public function getTotal()
	{
		$queryMeal = $this->db->query("SELECT count(*) as num FROM `meals` WHERE `status` = 'Y'");
		$rowMeal = $queryMeal->row();
		$queryPhy = $this->db->query("SELECT count(*) as num FROM `physio_program` WHERE `status` = 'Y'");
		$rowPhy = $queryPhy->row();
		$queryWork = $this->db->query("SELECT count(*) as num FROM `workout_routine` WHERE `status` = 'Y'");
		$rowWork = $queryWork->row();

		$response['success'] = 1;
		$response['data'] = array(
			'meal' => $rowMeal->num,
			'physio' => $rowPhy->num,
			'workout' => $rowWork->num,
		);
		$response['message'] = 'Data successfully fetched.';

		echo json_encode($response);
	}

	public function deleteAccount()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Please choose your start date';
		} else {

			$this->db->query("DELETE FROM `customer` WHERE `id` = '" . $data['cust_id'] . "'");
			$this->db->query("DELETE FROM `login_data` WHERE `cust_id` = '" . $data['cust_id'] . "'");
			$this->db->query("DELETE FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
			$this->db->query("DELETE FROM `customer_meal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
			$this->db->query("DELETE FROM `customer_physio` WHERE `cust_id` = '" . $data['cust_id'] . "'");
			$this->db->query("DELETE FROM `customer_workout` WHERE `cust_id` = '" . $data['cust_id'] . "'");

			$response['success'] = 1;
			$response['message'] = 'Account successfully deleted.';
		}

		echo json_encode($response);
	}
}
