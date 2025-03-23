<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/London');

class Meal extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();

		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model("api/auth_model", 'am');
		$this->load->model("api/meal_model", 'mm');
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

	public function getMealType()
	{
		$resultData = $this->mm->getMealType();
		if ($resultData) {
			/* $result[] = array();//array('meal_type_id' => 0, 'name' => 'All', 'description' => 'All');
			if (isset($resultData) && !empty($resultData)) {
				foreach ($resultData as $key => $val) {
					$resultTemp = array('meal_type_id' => $val->meal_type_id, 'name' => $val->name, 'description' => $val->description);
					array_push($result,$resultTemp);
				}
			} */
			$response['success'] = 1;
			$response['data'] = $resultData;
			$response['message'] = 'Data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		echo json_encode($response);
	}

	public function getMealList()
	{
		$data['meal_type_id'] = $this->input->post('meal_type_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_type_id']) || $data['meal_type_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->mm->getMealList($data);
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

	public function selectMeal()
	{
		$data['meal_type_id'] = $this->input->post('meal_type_id');
		$data['meal_id'] = $this->input->post('meal_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_type_id']) || $data['meal_type_id'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			if (isset($data['meal_type_id']) && $data['meal_type_id'] > 0) {
				$day_remaining_calorie = 0;
				$resultCust = $this->am->getUserPersonalProfile($data['cust_id']);
				if ($resultCust) {
					$bmr = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
					$resultGoal = $this->am->getGoal($data['cust_id']);

					if ($resultGoal) {

						if(isset($resultGoal->goal_type) && $resultGoal->goal_type == 1){
							$day_intake_calorie = strval(round($bmr - 300));
						}elseif(isset($resultGoal->goal_type) && $resultGoal->goal_type == 2){
							$day_intake_calorie = strval(round($bmr + 300));
						}elseif(isset($resultGoal->goal_type) && $resultGoal->goal_type == 3){
							$day_intake_calorie = strval(round($bmr));
						}

						$mealData = $this->am->getMealByCustDate($data);
						if (isset($mealData->total_calorie) && $mealData->total_calorie > 0) {
							$mealIntakeCalori = $mealData->total_calorie;
						} else {
							$mealIntakeCalori = 0;
						}

						$day_remaining_calorie = strval(round($day_intake_calorie - $mealIntakeCalori));
					}
				}

				if (isset($day_remaining_calorie) && $day_remaining_calorie > 0) {

					$resultMeal = $this->mm->mealDetails($data['meal_id']);
					$mealCalorie = $resultMeal['mealData']->calorie;

					if (isset($mealCalorie) && $day_remaining_calorie >= $mealCalorie) {

						$result = $this->mm->selectMeal($data);
						if ($result) {
							$response['success'] = 1;
							$response['message'] = 'Meal successfully added.';
						} else {
							$response['success'] = 0;
							$response['message'] = 'Meal already added.';
						}
					} else {
						$response['success'] = 0;
						$response['message'] = 'Please check your remaining calorie requirement';
					}
				} else {
					$response['success'] = 0;
					$response['message'] = 'You have already consume required calorie.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Invalid data or required parameter missing.';
			}
		}

		echo json_encode($response);
	}

	public function removeMeal()
	{
		$data['meal_type_id'] = $this->input->post('meal_type_id');
		$data['meal_id'] = $this->input->post('meal_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_type_id']) || $data['meal_type_id'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->mm->removeMeal($data);
			if ($result) {
				$response['success'] = 1;
				$response['message'] = 'Meal successfully removed.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Meal already removed.';
			}
		}

		echo json_encode($response);
	}

	public function mySelectedMeal()
	{
		$data['meal_type_id'] = $this->input->post('meal_type_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['date'] = $this->input->post('date');

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_type_id']) || $data['meal_type_id'] == ''
			|| !isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->mm->mySelectedMeal($data);
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

	public function mealDetails()
	{
		$data['meal_id'] = $this->input->post('meal_id');
		$data['cust_id'] = $this->input->post('cust_id');
		$data['mealTypeId'] = $this->input->post('meal_type_id');

		if (
			!isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			/* $resultCust = $this->am->getUserPersonalProfile($data['cust_id']);
			if ($resultCust) {
				$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
			} else {
				$totalCalorie = 0;
			}
			$resultGoal = $this->am->getGoal($data['cust_id']);
			$shouldTakeTotalCalorie = strval(round($totalCalorie - $resultGoal->day_less_calorie)); */

			/* $resultMealType = $this->mm->getMealTypeById($data['mealTypeId']);
			if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
				$caroiePer = $resultMealType->calorie_per;
			} else {
				$caroiePer = 0;
			}

			$mealIntakeCalori = ($shouldTakeTotalCalorie * $caroiePer) / 100;

			$result = $this->mm->mealDetails($data['meal_id']);
			if ($result) {
				$responseData['mealCalorieRequiement'] = round($mealIntakeCalori);
				$responseData['mealData'] = $result['mealData'];
				$responseData['nutriData'] = $result['nutriData'];

				if (isset($result['ingredientData']) && !empty($result['ingredientData'])) {
					foreach ($result['ingredientData'] as $key => $val) {
						$responseData['ingredientData'][$key] = new stdClass();
						$responseData['ingredientData'][$key]->ingredient_name = $val->ingredient_name;
						$responseData['ingredientData'][$key]->calorie_per_measurement = $val->calorie_per_gm;
						$responseData['ingredientData'][$key]->unit_of_measure = $val->unit_of_measure;
						$responseData['ingredientData'][$key]->per_of_meal = $val->per_of_meal;
						$per_of_meal = $val->per_of_meal;

						if (isset($val->calorie_per_gm) && $val->calorie_per_gm > 0) {
							if (isset($per_of_meal) && $per_of_meal > 0) {
								$intakeAmt = round(((($mealIntakeCalori * $per_of_meal) / 100) / $val->calorie_per_gm));
							} else {
								$intakeAmt = 0;
							}
						} else {
							$intakeAmt = round(((($mealIntakeCalori * $per_of_meal) / 100)));
						}

						if (isset($val->max_amt, $intakeAmt) && $intakeAmt < $val->max_amt && $intakeAmt > $val->min_amt) {
							$responseData['ingredientData'][$key]->should_take_unit = strval($intakeAmt);
						} elseif (isset($val->min_amt, $intakeAmt) && $val->min_amt > $intakeAmt) {
							$responseData['ingredientData'][$key]->should_take_unit = strval($val->min_amt);
						} else {
							$responseData['ingredientData'][$key]->should_take_unit = strval($val->max_amt);
						}

						$responseData['ingredientData'][$key]->should_take_measurement =  $val->unit_of_measure;
					}
				}

				$responseData['extraIngredientData'] = $result['extraData'];

				$response['success'] = 1;
				$response['data'] = $responseData;
				$response['message'] = 'Meal details successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			} */

			$result = $this->mm->mealDetails($data['meal_id']);
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
}
