<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/London');

class Workout extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();

		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model("api/auth_model", 'am');
		$this->load->model("api/workout_model", 'wm');
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

	public function getWorkoutProgram()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['workout_level_id'] = $this->input->post('workout_level_id');
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			//|| !isset($data['workout_level_id']) || $data['workout_level_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->getWorkoutProgram($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}
		echo json_encode($response);
	}

	public function getWorkoutLevel()
	{
		$result = $this->wm->getWorkoutLevel();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		echo json_encode($response);
	}


	/* public function getCoaches()
	{
		$result = $this->wm->getCoaches();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		echo json_encode($response);
	}

	public function getExerciseListByCoach()
	{
		$data['coach_id'] = $this->input->post('coach_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['coach_id']) || $data['coach_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->getExerciseListByCoach($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}
		echo json_encode($response);
	} */

	public function selectWorkout()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['program_id'] = $this->input->post('program_id');
		/* $data['date'] = $this->input->post('date');
		$data['exercise_id'] = $this->input->post('exercise_id'); */

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['program_id']) || $data['program_id'] == ''
			/* || !isset($data['exercise_id']) || $data['exercise_id'] == ''
			|| !isset($data['date']) || $data['date'] == '' */
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->selectWorkout($data);
			if ($result) {
				$response['success'] = 1;
				$response['message'] = 'Workout Program successfully added';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Workout Program already added';
			}
		}
		echo json_encode($response);
	}

	public function removeWorkoutProgram()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		$data['program_id'] = $this->input->post('program_id');
		/* $data['date'] = $this->input->post('date');
		$data['exercise_id'] = $this->input->post('exercise_id'); */

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['program_id']) || $data['program_id'] == ''
			/* || !isset($data['exercise_id']) || $data['exercise_id'] == ''
			|| !isset($data['date']) || $data['date'] == '' */
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->removeWorkoutProgram($data);
			if ($result) {
				$response['success'] = 1;
				$response['message'] = 'Workout Program successfully removed';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Workout Program already removed';
			}
		}
		echo json_encode($response);
	}

	public function mySelectedWorkoutProgram()
	{
		$data['cust_id'] = $this->input->post('cust_id');
		/* $data['date'] = $this->input->post('date'); */

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			/* || !isset($data['date']) || $data['date'] == '' */
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->mySelectedWorkoutProgram($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}
		echo json_encode($response);
	}

	public function weekList(){
		$data['cust_id'] = $this->input->post('cust_id');
		$data['program_id'] = $this->input->post('program_id');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['program_id']) || $data['program_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->getProgram($data);
			if ($result) {
				$returnData = array();
				if(isset($result['no_of_week']) && $result['no_of_week'] > 0){
					for($i = 0; $i < $result['no_of_week']; $i++){
						$returnData[$i]['week'] = ($i + 1);
						$endDay = (($i +1) * 7) - 1;
						$startDay = $endDay - 6;
						$returnData[$i]['startdate'] = date('d-m-Y',strtotime($result['start_date']. " + ".$startDay."days"));
						$returnData[$i]['enddate'] = date('d-m-Y',strtotime($result['start_date']. " + ".$endDay."days"));
					}
				}

				$response['success'] = 1;
				$response['data'] = $returnData;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}
		echo json_encode($response);
	}


	public function getExerciseList()
	{
		$data['program_id'] = $this->input->post('program_id');
		$data['week'] = $this->input->post('week');
		$data['day'] = $this->input->post('day');

		if (
			!isset($data['program_id']) || $data['program_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->wm->getExerciseList($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}
		echo json_encode($response);
	}

	public function getExerciseDetails()
	{
		$data['exercise_id'] = $this->input->post('exercise_id');

		if (isset($data['exercise_id']) && $data['exercise_id'] > 0) {
			$result = $this->wm->getExerciseDetails($data['exercise_id']);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! Please input required field.';
		}
		echo json_encode($response);
	}
}
