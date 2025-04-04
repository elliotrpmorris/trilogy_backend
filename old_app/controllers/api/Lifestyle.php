<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lifestyle extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model('api/lifestylemodel', 'sm');

		/* require APPPATH . '/libraries/JWT.php'; */

		// CORS headers
		header("Access-Control-Allow-Methods: GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		header("Access-Control-Allow-Origin: *");
	}

	public function index()
	{
		$data = array();
		$response = array();

		// initialize required variables
		$data['action'] = '';
		$data['api_key'] = '';

		// get all posted variables - get or post
		if ($this->input->method() == 'post') {
			foreach ($_POST as $key => $value) {
				$data[$key] = $this->input->post($key);
			}
		} else {
			foreach ($_GET as $key => $value) {
				$data[$key] = $this->input->get($key);
			}
		}

		// validate the retrieved data
		if ($data['action'] == '' || $data['api_key'] == '') {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else if ($data['api_key'] != API_KEY) {
			$response['success'] = 0;
			$response['message'] = 'Invalid API key.';
		} else {
			switch ($data['action']) {
				case 'setGoal':
					$response = $this->setGoal($data);
					break;
				default:
					$response['success'] = 0;
					$response['message'] = 'Invalid action specified.';
			}
		}

		echo json_encode($response);
	}

	//random string generation
	function random_strings($length_of_string)
	{

		// String of all alphanumeric character 
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		// Shufle the $str_result and returns substring 
		// of specified length 
		return substr(
			str_shuffle($str_result),
			0,
			$length_of_string
		);
	}

	// GET USER PERSONAL PROFILE
	public function calculateBmr($age, $height, $weight, $sex, $activity)
	{
		if (trim($sex) == "Male") {
			$otrFactor = 66.5;
			$ageCal = 6.75 * $age;
			$heightCal = 5.003 * $height;
			$weightCal = 13.75 * $weight;

			$calorie = ((($otrFactor + $weightCal + $heightCal) - $ageCal) * $activity) + 400;
		} elseif (trim($sex) == "Female") {
			$otrFactor = 655.1;
			$ageCal = 4.676 * $age;
			$heightCal = 1.850 * $height;
			$weightCal = 9.563 * $weight;

			$calorie = ((($otrFactor + $weightCal + $heightCal) - $ageCal) * $activity) + 400;
		} else {
			$calorie = "NA";
		}

		return $calorie;
	}

	/* GET ALL PACKAGES */

	public function setGoal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['start_date']) || $data['start_date'] == ''
			|| !isset($data['weightloss']) || $data['weightloss'] == ''
			|| !isset($data['weekcount']) || $data['weekcount'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getUserPersonalProfile($data['cust_id']);
			if ($result) {
				$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);

				$totalCalorieLoss = $data['weightloss'] * 3500;
				$lossPerWeek = $totalCalorieLoss / $data['weekcount'];

				$lossPerDay = $lossPerWeek / 7;

				if (($bmr / 2) >= $lossPerDay) {

					$intakeCalorie = $bmr - $lossPerDay;

					$dataPost = array(
						'cust_id' => $data['cust_id'],
						'start_date' => $data['start_date'],
						'weightloss' => $data['weightloss'],
						'weekcount' => $data['weekcount'],
						'bmr' => $bmr,
						'totalCalorieLoss' => $totalCalorieLoss,
						'lossPerWeek' => $lossPerWeek,
						'lossPerDay' => $lossPerDay,
						'intakeCalorie' => $intakeCalorie,
					);

					$response['success'] = 1;
					$response['data'] = $dataPost;
					$response['message'] = 'Goal has been successfully set.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! You can not loss this much weight.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}
}
