<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/London');
class Services extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$this->load->model('api/servicesmodel', 'sm');

		/* require APPPATH . '/libraries/JWT.php'; */

		// CORS headers
		header("Access-Control-Allow-Methods: GET, POST");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
		header("Access-Control-Allow-Origin: *");

		date_default_timezone_set('Europe/London');
	}

	public function index()
	{
		/* $sql = $this->db->query("INSERT INTO `login_data` SET `cust_id` = '1', `email_id` = 'bhavik@web-brain.co.uk', `password` = '".password_hash('Mrbond008!',PASSWORD_DEFAULT)."', `type` = 'admin'");
		$query = $this->db->query("SELECT `id`, `email_id`, `password` FROM `customer` WHERE 1 ORDER BY `id` asc");
		foreach($query->result() as $key => $val){
			$sql = $this->db->query("INSERT INTO `login_data` SET `cust_id` = '".$val->id."', `email_id` = '".$val->email_id."', `password` = '".$val->password."', `type` = 'user'");
		}


		die(); */
		// initialize array variable
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
				case 'getPackages':
					$response = $this->getPackages($data);
					break;
				case 'getSixWeekBatch':
					$response = $this->getSixWeekBatch($data);
					break;
				case 'setUserRegistration':
					$response = $this->setUserRegistration($data);
					break;
				case 'getUserLogin':
					$response = $this->getUserLogin($data);
					break;
				case 'setUserPersonalProfile':
					$response = $this->setUserPersonalProfile($data);
					break;
				case 'getUserPersonalProfile':
					$response = $this->getUserPersonalProfile($data);
					break;
				case 'validatePackage':
					$response = $this->validatePackage($data);
					break;
				case 'validateCoupon':
					$response = $this->validateCoupon($data);
					break;
				case 'setUserPackage':
					$response = $this->setUserPackage($data);
					break;
				case 'getUserPackage':
					$response = $this->getUserPackage($data);
					break;
				case 'checkUserPackageStart':
					$response = $this->checkUserPackageStart($data);
					break;
				case 'setProfilePicture':
					$response = $this->setProfilePicture($data);
					break;
				case 'setCompetitionPicture':
					$response = $this->setCompetitionPicture($data);
					break;
				case 'getDietary':
					$response = $this->getDietary($data);
					break;
				case 'getWorkOutGroup':
					$response = $this->getWorkOutGroup($data);
					break;
				case 'getAssignMealSixWeek':
					$response = $this->getAssignMealSixWeek($data);
					break;
				case 'getAssignMealSixWeekFilter':
					$response = $this->getAssignMealSixWeekFilter($data);
					break;
				case 'setSelectedMeal':
					$response = $this->setSelectedMeal($data);
					break;
				case 'getMySelectedMealSixWeek':
					$response = $this->getMySelectedMealSixWeek($data);
					break;
				case 'getMealRecipeforSixWeekByUser':
					$response = $this->getMealRecipeforSixWeekByUser($data);
					break;
				case 'getWorkoutSixWeek':
					$response = $this->getWorkoutSixWeek($data);
					break;
				case 'getTrainersSixWeek':
					$response = $this->getTrainersSixWeek($data);
					break;
				case 'getTrainersWorkoutSixWeek':
					$response = $this->getTrainersWorkoutSixWeek($data);
					break;
				case 'setTrainersWorkoutSixWeekFavorite':
					$response = $this->setTrainersWorkoutSixWeekFavorite($data);
					break;
				case 'removeTrainersWorkoutSixWeekFavorite':
					$response = $this->removeTrainersWorkoutSixWeekFavorite($data);
					break;
				case 'getTrainersWorkoutSixWeekFavoriteList':
					$response = $this->getTrainersWorkoutSixWeekFavoriteList($data);
					break;
				case 'getHomeWorkoutSixWeek':
					$response = $this->getHomeWorkoutSixWeek($data);
					break;
				case 'setHomeWorkoutSixWeekFavorite':
					$response = $this->setHomeWorkoutSixWeekFavorite($data);
					break;
				case 'removeHomeWorkoutSixWeekFavorite':
					$response = $this->removeHomeWorkoutSixWeekFavorite($data);
					break;
				case 'getHomeWorkoutSixWeekFavoriteList':
					$response = $this->getHomeWorkoutSixWeekFavoriteList($data);
					break;
				case 'getSixWeekTodayMealAll':
					$response = $this->getSixWeekTodayMealAll($data);
					break;
				case 'getMealTypeByWeekOnetoOne':
					$response = $this->getMealTypeByWeekOnetoOne($data);
					break;
				case 'getAssignMealOnetoOne':
					$response = $this->getAssignMealOnetoOne($data);
					break;
				case 'getMealRecipeOnetoOne':
					$response = $this->getMealRecipeOnetoOne($data);
					break;
				case 'getCallbackAvailability':
					$response = $this->getCallbackAvailability($data);
					break;
				case 'getCallbackSlots':
					$response = $this->getCallbackSlots($data);
					break;
				case 'setCallback':
					$response = $this->setCallback($data);
					break;
				case 'getMyCallback':
					$response = $this->getMyCallback($data);
					break;
				case 'getChatData':
					$response = $this->getChatData($data);
					break;
				case 'setChatData':
					$response = $this->setChatData($data);
					break;
				case 'getWeekDataOnetoOneForWorkout':
					$response = $this->getWeekDataOnetoOneForWorkout($data);
					break;
				case 'getOnetoOneWorkoutList':
					$response = $this->getOnetoOneWorkoutList($data);
					break;
				case 'getExerciseList':
					$response = $this->getExerciseList($data);
					break;
				case 'getExerciseDetails':
					$response = $this->getExerciseDetails($data);
					break;
				case 'setExerciseToFavorite':
					$response = $this->setExerciseToFavorite($data);
					break;
				case 'removeExerciseFromFavorite':
					$response = $this->removeExerciseFromFavorite($data);
					break;
				case 'getGymWorkoutSixWeekFavoriteList':
					$response = $this->getGymWorkoutSixWeekFavoriteList($data);
					break;
				case 'getAllOnetoOneUser':
					$response = $this->getAllOnetoOneUser($data);
					break;
				case 'updateChatStatus':
					$response = $this->updateChatStatus($data);
					break;
				case 'getIosJwtReferrence':
					$response = $this->getIosJwtReferrence($data);
					break;
				case 'getDecodedJwtReferrence':
					$response = $this->getDecodedJwtReferrence($data);
					break;
				case 'setLifeStyleGoal':
					$response = $this->setLifeStyleGoal($data);
					break;
				case 'getLifeStyleGoal':
					$response = $this->getLifeStyleGoal($data);
					break;
				case 'getLifeStyleWeekList':
					$response = $this->getLifeStyleWeekList($data);
					break;
				case 'getLifeStyleMealByTypeWeekDay':
					$response = $this->getLifeStyleMealByTypeWeekDay($data);
					break;
				case 'setLifeStyleMealByType':
					$response = $this->setLifeStyleMealByType($data);
					break;
				case 'getLifeStyleMySelectedMeal':
					$response = $this->getLifeStyleMySelectedMeal($data);
					break;
				case 'removeLifeStyleMySelectedMeal':
					$response = $this->removeLifeStyleMySelectedMeal($data);
					break;
				case 'getLifeStyleMealDetailsByID':
					$response = $this->getLifeStyleMealDetailsByID($data);
					break;
				case 'getTrainersList':
					$response = $this->getTrainersList($data);
					break;
				case 'getTrainersWorkoutRoutines':
					$response = $this->getTrainersWorkoutRoutines($data);
					break;
				case 'setTrainerWorkoutasSelected':
					$response = $this->setTrainerWorkoutasSelected($data);
					break;
				case 'getMySelectedWorkoutList':
					$response = $this->getMySelectedWorkoutList($data);
					break;
				case 'removeWorkoutFromMySelectedList':
					$response = $this->removeWorkoutFromMySelectedList($data);
					break;
				case 'getExerciseListByWorkoutId':
					$response = $this->getExerciseListByWorkoutId($data);
					break;
				case 'getTrainerExerciseDetails':
					$response = $this->getTrainerExerciseDetails($data);
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

	/* GET IOS JWT REFERRENCE */

	public function getIosJwtReferrence($data)
	{
		if (
			!isset($data['payment_amt']) || $data['payment_amt'] == ''
			|| !isset($data['sitereference']) || $data['sitereference'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$key = "56-fb9313c5070edcb8f2b986c48c7a9539d46049cb848505933506595439a884f0";

			$payload = array(
				'iat' => time(),
				'iss' => "jwt@storeynutrition.com",
				'payload' => array(
					"accounttypedescription" => "ECOM",
					"baseamount" => $data['payment_amt'] * 100,
					"currencyiso3a" => "GBP",
					"sitereference" => $data['sitereference'],
					"termurl" => "https://payments.securetrading.net/process/payments/mobilesdklistener",
					"requesttypedescriptions" => ['THREEDQUERY', 'AUTH'],
				),
			);

			$jwt = JWT::encode($payload, $key, 'HS256');

			if ($jwt) {
				$response['success'] = 1;
				$response['data'] = $jwt;
				$response['message'] = 'JWT referrence successfully generated.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	public function getDecodedJwtReferrence($data)
	{
		if (
			!isset($data['jwtresponse']) || $data['jwtresponse'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$jwt = $data['jwtresponse'];

			$key = "56-fb9313c5070edcb8f2b986c48c7a9539d46049cb848505933506595439a884f0";

			$decode = JWT::decode($jwt, $key, true);

			if ($decode) {
				$response['success'] = 1;
				$response['data'] = $decode->payload->response;
				$response['message'] = 'JWT referrence successfully decoded.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* GET ALL Batch Six Week */

	public function getSixWeekBatch($data)
	{
		$result = $this->sm->getSixWeekBatch();
		if ($result) {
			$batchData = array();
			if (isset($result) && !empty($result)) {
				foreach ($result as $key => $val) {
					$batchData[] = array(
						"batch_name" =>  $val->batch_name,
						"id" =>  $val->id,
						"batch_date" =>  date('d-m-Y', $val->batch_date),
					);
				}
			}


			$response['success'] = 1;
			$response['data'] = $batchData;
			$response['message'] = 'Batch data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}

		return $response;
	}

	/* GET ALL PACKAGES */

	public function getAllOnetoOneUser($data)
	{
		/* if (
			!isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else { */
		/* $data['date']; */
		$result = $this->sm->getAllOnetoOneUser();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'User data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		//}

		return $response;
	}

	/*  GET DIETARY*/
	public function getDietary($data)
	{
		/* if (
			!isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else { */
		/* $data['date']; */
		$result = $this->sm->getDietary();
		if ($result) {
			$responseData = array();
			$responseData[0]['food_type_name'] = 'All';
			$responseData[0]['id'] = 0;
			foreach ($result as $key => $val) {
				$responseData[($key + 1)] = $val;
			}

			$response['success'] = 1;
			$response['data'] = $responseData;
			$response['message'] = 'Dietary data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		//}

		return $response;
	}


	/* UPDATE CHAT STATUS */
	public function updateChatStatus($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->updateChatStatus($data['cust_id']);
			if ($result) {
				$response['success'] = 1;
				$response['message'] = 'Chat successfully read.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* GET ALL PACKAGES */

	public function getPackages($data)
	{
		$result = $this->sm->getPackages();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Package data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}

		return $response;
	}

	// user registration
	public function setUserRegistration($data)
	{
		if (
			!isset($data['name']) || $data['name'] == ''
			|| !isset($data['email_id']) || $data['email_id'] == ''
			|| !isset($data['password']) || $data['password'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->checkUserExists($data['email_id']);
			if ($result) {
				$response['success'] = 0;
				$response['message'] = 'Email  already exists.';
			} else {
				$data['status'] = 'Y';

				$user_id = $this->sm->setUserRegistration($data);
				$response['success'] = 1;
				$response['data']['cust_id'] = $user_id;

				$returnData = $this->sm->getUserPersonalProfile($user_id);
				$response['data']['user_code'] = $returnData->user_code;
				$response['data']['name'] = $returnData->name;
				$response['data']['email_id'] = $returnData->email_id;
				$response['data']['age'] = $returnData->age;
				$response['data']['height'] = $returnData->height;
				$response['data']['weight'] = $returnData->weight;
				$response['data']['gender'] = $returnData->gender;
				$response['data']['activity_lavel'] = $returnData->activity_lavel;
				$response['data']['allergies'] = $returnData->allergies;
				$response['data']['foodddislike'] = $returnData->foodddislike;
				$response['data']['profile_picture'] = $returnData->image;
				$response['data']['onetoone'] = 'N';
				$response['data']['sixweek'] = 'N';
				$response['data']['lifestyle'] = 'N';

				$packageData = $this->sm->getUserPackage($user_id);

				if (isset($packageData) && !empty($packageData)) {
					foreach ($packageData as $key => $val) {
						if ($val->package_id == 1) {
							$response['data']['onetoone'] = 'Y';
						} elseif ($val->package_id == 2) {
							$response['data']['sixweek'] = 'Y';
						} elseif ($val->package_id == 3) {
							$response['data']['lifestyle'] = 'Y';
						}
					}
				}

				$response['data']['packageData'] = $packageData;
				$response['message'] = 'User registered successfully.';
			}
		}

		return $response;
	}

	// user Personal Profile
	public function setUserPersonalProfile($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['age']) || $data['age'] == ''
			|| !isset($data['height']) || $data['height'] == ''
			|| !isset($data['weight']) || $data['weight'] == ''
			|| !isset($data['gender']) || $data['gender'] == ''
			|| !isset($data['activity_lavel']) || $data['activity_lavel'] == ''
			|| !isset($data['allergies']) || $data['allergies'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else if (isset($data['activity_lavel']) && (floatval($data['activity_lavel']) < 1 || floatval($data['activity_lavel']) > 1.9)) {
			$response['success'] = 0;
			$response['message'] = 'Please select the number between 1 to 1.9.';
		} else {
			if (isset($data['foodddislike']) && $data['foodddislike'] != "") {
				// do nothing
			} else {
				$data['foodddislike'] = '';
			}

			$result = $this->sm->setUserPersonalProfile($data);
			if ($result) {
				$response['success'] = 1;
				$response['data']['cust_id'] = $data['cust_id'];
				$response['message'] = 'Personal data updated successfully.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Somthing went wrong.';
			}
		}

		return $response;
	}

	/* VALIDATE PACKAGE FOR SIX WEEK */

	public function validatePackage($data)
	{
		/* if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['package_id']) || $data['package_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
 */
		$result = $this->sm->validatePackage();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Successfull.';
		} else {
			$response['success'] = 0;
			$response['message'] = "Sorry!! Batch already started.";
		}
		/* } */

		return $response;
	}

	/* VALIDATE COUPON */
	public function validateCoupon($data)
	{
		if (
			!isset($data['coupon_code']) || $data['coupon_code'] == ''
			|| !isset($data['package_id']) || $data['package_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->validateCoupon($data);
			if (isset($result) && $result['status'] == 1) {
				$response['success'] = 1;
				$response['data'] = $result['data'];
				$response['message'] = 'Successfull.';
			} elseif (isset($result) && $result['status'] == 0) {
				$response['success'] = 0;
				$response['message'] = $result['msg'];
			} else {
				$response['success'] = 0;
				$response['message'] = "Sorry!! No data found";
			}
		}

		return $response;
	}

	// user registration
	public function setUserPackage($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['payment_amt']) || $data['payment_amt'] == ''
			|| !isset($data['package_amt']) || $data['package_amt'] == ''
			|| !isset($data['package_id']) || $data['package_id'] == ''
			|| !isset($data['tran_id']) || $data['tran_id'] == ''
			|| !isset($data['country']) || $data['country'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$chkExists = $this->sm->checkUserExistsById($data['cust_id']);
			if ($chkExists) {
				$data['pay_status'] = 'Y';

				$payment_id = $this->sm->setUserPackage($data);
				$response['success'] = 1;
				$response['data']['payment_id'] = $payment_id;
				$response['message'] = 'Payment successfully done.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'User does not exists.';
			}
		}

		return $response;
	}

	// check user login 
	public function getUserLogin($data)
	{
		if (
			!isset($data['email']) || $data['email'] == ''
			|| !isset($data['password']) || $data['password'] == ''
			/* || !isset($data['type']) || $data['type'] == '' */
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			/* if ($data['type'] == "user") { */
			$resultLog = $this->sm->getUserLogin($data);
			if ($resultLog) {
				if (!password_verify($data['password'], $resultLog->password)) {
					$response['success'] = 0;
					$response['message'] = "Password doesn't match.";
				} else {

					if (isset($resultLog->type) && $resultLog->type == "user") {
						$result = $this->sm->fetchUserDataById($resultLog->cust_id);

						if ($result->status == 'Y') {

							$packageData = $this->sm->getUserPackage($result->id);


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
							$response['data']['foodddislike'] = $result->foodddislike;
							$response['data']['profile_picture'] = $result->profile_picture;
							$response['data']['onetoone'] = 'N';
							$response['data']['sixweek'] = 'N';
							$response['data']['lifestyle'] = 'N';
							$response['data']['baseurl'] = site_url();
							if (isset($packageData) && !empty($packageData)) {
								foreach ($packageData as $key => $val) {
									if ($val->package_id == 1) {
										$response['data']['onetoone'] = 'Y';
									} elseif ($val->package_id == 2) {
										$response['data']['sixweek'] = 'Y';
									} elseif ($val->package_id == 3) {
										$response['data']['lifestyle'] = 'Y';
									}
								}
							}
							//if ($response['data']['lifestyle'] == 'Y' || $response['data']['sixweek'] == 'Y' || $response['data']['onetoone'] == 'Y') {
							$response['success'] = 1;
							$response['data']['packageData'] = $packageData;
							$response['type'] = 'user';
							$response['message'] = 'User successfully logged in.';
							/* } else {
								$response = array();
								$response['success'] = 0;
								$response['message'] = 'In order to access this feature please sign up to the transformation on website first and then log back in. Thanks NU team..';
							} */
						} else {
							$response['success'] = 0;
							$response['message'] = 'User is inactive or package expired.';
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
			/* } elseif ($data['type'] == "admin") {
				$result = $this->sm->getAdminLogin($data);
				if ($result) {
					$response['success'] = 1;
					$response['data']['admin_id'] = $result->id;
					$response['type'] = $data['type'];
					$response['message'] = 'Admin successfully logged in.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'User is inactive or does not exist.';
				}
			} */
		}

		return $response;
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
	
	public function getUserPersonalProfile($data)
	{

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getUserPersonalProfile($data['cust_id']);
			if ($result) {
				$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);

				$result->calorie = $bmr;
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'No package found';
			}
		}

		return $response;
	}

	// GET USER PACKAGE LIST
	public function getUserPackage($data)
	{

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getUserPackage($data['cust_id']);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'No package found';
			}
		}

		return $response;
	}

	// GET USER PACKAGE LIST
	public function checkUserPackageStart($data)
	{

		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['package_id']) || $data['package_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->checkUserPackageStart($data);
			if ($result) {
				if (isset($result) && $result <= time()) {
					$response['success'] = 1;
					$response['data'] = date('d-m-Y H:i:s', $result);
					$response['message'] = 'Package has started.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! Your package start date is ' . date('d-m-Y H:i:s', $result);
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'No package found';
			}
		}

		return $response;
	}

	/* COMPITITION PIC UPLOAD */
	public function setProfilePicture($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($_FILES['image']) || $_FILES['image'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$target_file = "uploads/profilepic/" . basename($_FILES["image"]["name"]);
			move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

			$this->sm->setProfilePicture($target_file, $data['cust_id']);

			$response['success'] = 1;
			$response['message'] = 'Image Uploaded Successfully.';
		}

		return $response;
	}

	/* COMPITITION PIC UPLOAD */
	public function setCompetitionPicture($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($_FILES['image']) || $_FILES['image'] == ''
			|| !isset($data['type']) || $data['type'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);


			if (isset($data['type']) && $data['type'] == "after") {
				if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
					$target_file = "uploads/competition/" . basename($_FILES["image"]["name"]);
					move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

					$this->sm->setCompetitionPicture($target_file, $data['cust_id'], $data['type']);

					$response['success'] = 1;
					$response['message'] = 'Image Uploaded Successfully.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! You can upload imgae after batch ends.';
				}
			} else {

				$target_file = "uploads/competition/" . basename($_FILES["image"]["name"]);
				move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

				$this->sm->setCompetitionPicture($target_file, $data['cust_id'], $data['type']);

				$response['success'] = 1;
				$response['message'] = 'Image Uploaded Successfully.';
			}
		}

		return $response;
	}

	/* GET MEAL TYPE FOR 6 WEEK TRANSFORMATION BY WEEK AND DAY */

	/* public function getMealTypeByWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$days = array(
				0 => 'Sunday',
				1 => 'Monday',
				2 => 'Tuesday',
				3 => 'Wednesday',
				4 => 'Thursday',
				5 => 'Friday',
				6 => 'Saturday'
			);

			$resultMealType = $this->sm->getAllMealType();

			$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

			$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);


			$week = $data['week'];
			$returnData['week'] = $week;

			$resultPerDrop = $this->sm->getWeekPerDropByWeek($week);

			if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
				$perDrop = $resultPerDrop->per_drop;
			} else {
				$perDrop = 0;
			}

			if (isset($perDrop) && $perDrop > 0) {
				$returnData['weekMealCalorie'] = $totalCalorie - (($totalCalorie * $perDrop) / 100);
			} else {
				$returnData['weekMealCalorie'] = $totalCalorie;
			}

			for ($k = 0; $k <= 6; $k++) {
				$returnData[$k]['dayId'] = $k;
				$returnData[$k]['dayName'] = $days[$k];

				if (isset($resultMealType) && !empty($resultMealType)) {
					foreach ($resultMealType as $key => $val) {

						$returnData[$k][$key]['mealTypeId'] = $val->id;
						$returnData[$k][$key]['mealTypeName'] = $val->meal_type_name;

						if (isset($val->calorie_per) && $val->calorie_per > 0) {
							$caloriePer = $val->calorie_per;
						} else {
							$caloriePer = 0;
						}

						if (isset($caloriePer) && $caloriePer > 0) {
							$mealCalorie = ($totalCalorie * $caloriePer) / 100;
							if (isset($perDrop) && $perDrop > 0) {
								$returnData[$k][$key]['mealCalorie'] = $mealCalorie - (($mealCalorie * $perDrop) / 100);
							} else {
								$returnData[$k][$key]['mealCalorie'] = $mealCalorie;
							}
						} else {
							$returnData[$k][$key]['mealCalorie'] = $totalCalorie;
						}
					}
				}
			}


			$response['success'] = 1;
			$response['data'] = $returnData;
			$response['message'] = 'Data successfully fetched.';
		}

		return $response;
	} */

	/*********************** SIX WEEK TRANSFORMATION ********************************/

	/* GET MY ASSIGN MEALS 6 WEEK */
	public function getAssignMealSixWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {

				if (isset($resultLatestPack->id) && $resultLatestPack->id > 0) {
					$batch_id = $resultLatestPack->id;
				} else {
					$batch_id = 0;
				}

				/* echo $batch_id; */

				$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

				if ($resultCust) {
					$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
				} else {
					$totalCalorie = 0;
				}

				$week = $data['week'];
				$resultPerDrop = $this->sm->getWeekPerDropByWeek($week);

				if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
					$perDrop = $resultPerDrop->per_drop;
				} else {
					$perDrop = 0;
				}

				$resultMealType = $this->sm->getAllMealTypeByID($data['mealTypeId']);

				if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
					$caloriePer = $resultMealType->calorie_per;
				} else {
					$caloriePer = 0;
				}

				if (isset($caloriePer) && $caloriePer > 0) {
					$mealCalorie = ($totalCalorie * $caloriePer) / 100;
					if (isset($perDrop) && $perDrop > 0) {
						$response['mealCalorie'] = $mealCalorie - (($mealCalorie * $perDrop) / 100);
					} else {
						$response['mealCalorie'] = $mealCalorie;
					}
				} else {
					$response['mealCalorie'] = $totalCalorie;
				}

				$result = $this->sm->getAssignMealSixWeek($data, $batch_id);

				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'My assign meals data successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}

		return $response;
	}

	public function getAssignMealSixWeekFilter($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['foodtype']) || $data['foodtype'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {

				if (isset($resultLatestPack->id) && $resultLatestPack->id > 0) {
					$batch_id = $resultLatestPack->id;
				} else {
					$batch_id = 0;
				}

				$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

				if ($resultCust) {
					$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
				} else {
					$totalCalorie = 0;
				}

				$week = $data['week'];
				$resultPerDrop = $this->sm->getWeekPerDropByWeek($week);

				if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
					$perDrop = $resultPerDrop->per_drop;
				} else {
					$perDrop = 0;
				}

				$resultMealType = $this->sm->getAllMealTypeByID($data['mealTypeId']);

				if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
					$caloriePer = $resultMealType->calorie_per;
				} else {
					$caloriePer = 0;
				}

				if (isset($caloriePer) && $caloriePer > 0) {
					$mealCalorie = ($totalCalorie * $caloriePer) / 100;
					if (isset($perDrop) && $perDrop > 0) {
						$response['mealCalorie'] = $mealCalorie - (($mealCalorie * $perDrop) / 100);
					} else {
						$response['mealCalorie'] = $mealCalorie;
					}
				} else {
					$response['mealCalorie'] = $totalCalorie;
				}

				$result = $this->sm->getAssignMealSixWeekFilter($data, $batch_id);

				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'My assign meals data successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}

		return $response;
	}

	/* SET SELECTED MEAL */

	public function setSelectedMeal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['mealCalorie']) || $data['mealCalorie'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->setSelectedMeal($data);

			if ($result > 0) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Meal data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! you have already selected this meal.';
			}
		}

		return $response;
	}

	/* GET MY SELECTED MEAL FOR SIX WEEK */

	public function getMySelectedMealSixWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {

				if (isset($resultLatestPack->id) && $resultLatestPack->id > 0) {
					$batch_id = $resultLatestPack->id;
				} else {
					$batch_id = 0;
				}


				$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

				if ($resultCust) {
					$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
				} else {
					$totalCalorie = 0;
				}

				$week = $data['week'];
				$resultPerDrop = $this->sm->getWeekPerDropByWeek($week);

				if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
					$perDrop = $resultPerDrop->per_drop;
				} else {
					$perDrop = 0;
				}

				$resultMealType = $this->sm->getAllMealTypeByID($data['mealTypeId']);

				if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
					$caloriePer = $resultMealType->calorie_per;
				} else {
					$caloriePer = 0;
				}

				if (isset($caloriePer) && $caloriePer > 0) {
					$mealCalorie = ($totalCalorie * $caloriePer) / 100;
					if (isset($perDrop) && $perDrop > 0) {
						$mealCalorie = $mealCalorie - (($mealCalorie * $perDrop) / 100);
					} else {
						$mealCalorie = $mealCalorie;
					}
				} else {
					$mealCalorie = $totalCalorie;
				}

				$result = $this->sm->getMySelectedMealSixWeek($data, $batch_id);

				if ($result) {
					if (isset($result) && !empty($result)) {
						foreach ($result as $key => $val) {
							$returnData[$key]['meal_title'] = $val->meal_title;
							$returnData[$key]['meal_image_thumb'] = $val->meal_image_thumb;
							$returnData[$key]['meal_id'] = $val->meal_id;
							$returnData[$key]['meal_calorie'] = $mealCalorie;
							$returnData[$key]['food_type'] = $val->food_type;
						}
					}

					$response['success'] = 1;
					$response['data'] = $returnData;
					$response['message'] = 'Meal data successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}

		return $response;
	}

	/* GET MEAL RECIPE FOR SIX WEEK USER BY USER ID */

	public function getMealRecipeforSixWeekByUser($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['mealCalorie']) || $data['mealCalorie'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getMealRecipeforSixWeekByUser($data);

			if ($result) {

				$responseData['mealData'] = $result['mealData'];
				$responseData['nutriData'] = $result['nutriData'];

				if (isset($result['ingredientData']) && !empty($result['ingredientData'])) {
					foreach ($result['ingredientData'] as $key => $val) {
						$responseData['ingredientData'][$key] = new stdClass();
						$responseData['ingredientData'][$key]->ingredient_name = $val->ingredient_name;
						$responseData['ingredientData'][$key]->calorie_per_measurement = strval($val->calorie_per_gm);
						$responseData['ingredientData'][$key]->unit_of_measure = $val->unit_of_measure;
						$responseData['ingredientData'][$key]->per_of_meal = strval($val->per_of_meal);
						$per_of_meal = $val->per_of_meal;

						if (isset($per_of_meal, $val->calorie_per_gm) && $per_of_meal > 0 && $val->calorie_per_gm > 0) {
							$intakeAmt = round((($data['mealCalorie'] * $per_of_meal) / 100) / $val->calorie_per_gm);
						} else {
							$intakeAmt = 0;
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
				$response['message'] = 'Meal data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/*  GET WORKOUT GROUP*/
	public function getWorkOutGroup($data)
	{
		/* if (
			!isset($data['date']) || $data['date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else { */
		/* $data['date']; */
		$result = $this->sm->getWorkOutGroup();
		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Dietary data successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! No data found.';
		}
		//}

		return $response;
	}

	/* SIX WEEK WORKOUT LIST */
	public function getWorkoutSixWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['workout_group']) || $data['workout_group'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {

				$result = $this->sm->getWorkoutSixWeek($data);
				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'Workout data successfully inserted.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! No data found.';
				}
			}
		}

		return $response;
	}

	/* SIX WEEK TRAINERS */

	public function getTrainersSixWeek()
	{
		$result = $this->sm->getTrainersSixWeek();

		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Trainers List successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! no data found.';
		}
		return $response;
	}

	/* SIX WEEK TRAINERS Workouts */

	public function getTrainersWorkoutSixWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['trainer_id']) || $data['trainer_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {
				$result = $this->sm->getTrainersWorkoutSixWeek($data);

				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'Trainers Workout List successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}
		return $response;
	}

	/* SET SIX WEEK TRAINERS Workouts To My Favorite */

	public function setTrainersWorkoutSixWeekFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->setTrainersWorkoutSixWeekFavorite($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully added to favourite.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Already added to favourite';
			}
		}
		return $response;
	}

	/* REMOVE SIX WEEK TRAINERS Workouts To My Favorite */

	public function removeTrainersWorkoutSixWeekFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->removeTrainersWorkoutSixWeekFavorite($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully remove from favourite.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}
		return $response;
	}

	/* GET SIX WEEK TRAINERS Workouts My Favorite LIST */

	public function getTrainersWorkoutSixWeekFavoriteList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getTrainersWorkoutSixWeekFavoriteList($data['cust_id']);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}
		return $response;
	}

	/* SIX WEEK Home Workouts */

	public function getHomeWorkoutSixWeek($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {
				$result = $this->sm->getHomeWorkoutSixWeek($data);

				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'Trainers Workout List successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}
		return $response;
	}

	/* SET SIX WEEK Home Workouts To My Favorite */

	public function setHomeWorkoutSixWeekFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->setHomeWorkoutSixWeekFavorite($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully added to favourite.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Already added to favourite';
			}
		}
		return $response;
	}

	/* REMOVE SIX WEEK Home Workouts To My Favorite */

	public function removeHomeWorkoutSixWeekFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->removeHomeWorkoutSixWeekFavorite($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully remove from favourite.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}
		return $response;
	}

	/* GET SIX WEEK Home Workouts My Favorite LIST */

	public function getHomeWorkoutSixWeekFavoriteList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getHomeWorkoutSixWeekFavoriteList($data['cust_id']);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}
		return $response;
	}

	/* GET SIX WEEK TODAY MEAL ALL */

	public function getSixWeekTodayMealAll($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['day']) || $data['day'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$resultLatestPack = $this->sm->customerPackageDateSixWeek($data['cust_id']);

			if (isset($resultLatestPack->batch_end_date) && $resultLatestPack->batch_end_date < time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is already ended.';
			} elseif (isset($resultLatestPack->batch_date) && $resultLatestPack->batch_date > time()) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! Batch is not started.';
			} else {

				if (isset($resultLatestPack->id) && $resultLatestPack->id > 0) {
					$batch_id = $resultLatestPack->id;
				} else {
					$batch_id = 0;
				}

				$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

				if ($resultCust) {
					$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
				} else {
					$totalCalorie = 0;
				}

				$week = $data['week'];
				$resultPerDrop = $this->sm->getWeekPerDropByWeek($week);

				if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
					$perDrop = $resultPerDrop->per_drop;
				} else {
					$perDrop = 0;
				}

				/* echo "<pre>"; */

				$resultMealType = $this->sm->getAllMealType();
				/* print_r($resultMealType); */
				$result = array();
				if (isset($resultMealType) && !empty($resultMealType)) {
					foreach ($resultMealType as $key => $val) {

						$caloriePer = 0;
						$result['mealData'][$key]['meal_type'] = $val->meal_type_name;

						if (isset($val->calorie_per) && $val->calorie_per > 0) {
							$caloriePer = $val->calorie_per;
						} else {
							$caloriePer = 0;
						}
						if (isset($caloriePer) && $caloriePer > 0) {
							$mealCalorie = ($totalCalorie * $caloriePer) / 100;
							if (isset($perDrop) && $perDrop > 0) {
								$result['mealData'][$key]['mealCalorie'] = $mealCalorie - (($mealCalorie * $perDrop) / 100);
							} else {
								$result['mealData'][$key]['mealCalorie'] = $mealCalorie;
							}
						} else {
							$result['mealData'][$key]['mealCalorie'] = $totalCalorie;
						}

						$mealCalorieReq = $result['mealData'][$key]['mealCalorie'];

						$dataPost = array(
							'cust_id' => $data['cust_id'],
							'week' => $data['week'],
							'day' => $data['day'],
							'mealTypeId' => $val->id,
						);
						$assignMeal = $this->sm->getMySelectedMealSixWeek($dataPost, $batch_id);
						$result['mealData'][$key]['meal'] = array();
						/* print_r($assignMeal); */
						if (isset($assignMeal) && !empty($assignMeal)) {
							foreach ($assignMeal as $km => $vm) {
								$result['mealData'][$key]['meal'][$km]['mealTitle'] = $vm->meal_title;


								$dataIngPost = array(
									'mealCalorie' => $mealCalorieReq,
									'meal_id' => $vm->meal_id,
									'cust_id' => $data['cust_id'],
								);

								$returnIngedient = $this->sm->getMealRecipeforSixWeekByUser($dataIngPost);

								if ($returnIngedient) {
									if (isset($returnIngedient['ingredientData']) && !empty($returnIngedient['ingredientData'])) {
										foreach ($returnIngedient['ingredientData'] as $ki => $vi) {
											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki] = new stdClass();


											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->ingredient_name = $vi->ingredient_name;
											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->calorie_per_measurement = strval($vi->calorie_per_gm);
											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->unit_of_measure = $vi->unit_of_measure;
											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->per_of_meal = strval($vi->per_of_meal);
											$per_of_meal = $vi->per_of_meal;

											if (isset($per_of_meal, $vi->calorie_per_gm) && $per_of_meal > 0 && $vi->calorie_per_gm > 0) {
												$intakeAmt = round((($mealCalorieReq * $per_of_meal) / 100) / $vi->calorie_per_gm);
											} else {
												$intakeAmt = 0;
											}
											if (isset($vi->max_amt, $intakeAmt) && $intakeAmt < $vi->max_amt && $intakeAmt > $vi->min_amt) {
												$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->should_take_unit = strval($intakeAmt);
											} elseif (isset($vi->min_amt, $intakeAmt) && $vi->min_amt > $intakeAmt) {
												$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->should_take_unit = strval($vi->min_amt);
											} else {
												$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->should_take_unit = strval($vi->max_amt);
											}
											$result['mealData'][$key]['meal'][$km]['ingredientData'][$ki]->should_take_measurement =  $vi->unit_of_measure;
										}
									}
								}
							}
						}
					}
				}


				if ($result) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'My assign meals data successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! no data found.';
				}
			}
		}

		return $response;
	}

	/* GET MEAL TYPE BY WEEK AND DAY FOR ONE TO ONE */

	public function getMealTypeByWeekOnetoOne($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$days = array(
				0 => 'Sunday',
				1 => 'Monday',
				2 => 'Tuesday',
				3 => 'Wednesday',
				4 => 'Thursday',
				5 => 'Friday',
				6 => 'Saturday'
			);

			$resultMealType = $this->sm->getAllMealType();

			$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

			if ($resultCust) {
				$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
			} else {
				$totalCalorie = 0;
			}

			$resultMealMaster = $this->sm->getOnetoOneMaster($data['cust_id']);

			$countDay = 0;
			$week = 1;
			if (isset($resultMealMaster) && !empty($resultMealMaster)) {
				foreach ($resultMealMaster as $k => $v) {
					$i = $week - 1;

					$date = $v->one_meal_date;
					$dayName = date('l', $date);
					$dayId = array_search($dayName, $days);

					$returnData['day'][$countDay]['date'] = date('d-m-Y', $date);
					$returnData['day'][$countDay]['dayName'] = $dayName;
					$returnData['day'][$countDay]['dayId'] = $dayId;
					$returnData['day'][$countDay]['masterId'] = $v->id;

					$countDay++;
				}
			}

			if (isset($returnData) && !empty($returnData)) {
				$response['success'] = 1;
				$response['data'] = $returnData;
				$response['message'] = 'Data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* GET ASSIGN MEAL ONE TO ONE */

	public function getAssignMealOnetoOne($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['masterId']) || $data['masterId'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getAssignMealOnetoOne($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'My assign meals data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET MEAL RECIPE ONE TO ONE*/

	public function getMealRecipeOnetoOne($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_day_id']) || $data['meal_day_id'] == ''
			|| !isset($data['user_meal_id']) || $data['user_meal_id'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getMealRecipeOnetoOne($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Meal recipe data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET CALLBACK AVAILABILITY */

	public function getCallbackAvailability($data)
	{
		if (
			!isset($data['month']) || $data['month'] == ''
			|| !isset($data['year']) || $data['year'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$response['success'] = 1;
			$startDate = '1-' . $data['month'] . '-' . $data['year'];
			$endDate = date('t', strtotime($startDate)) . '-' . $data['month'] . '-' . $data['year'];

			$i = 0;
			while (strtotime($startDate) <= strtotime($endDate)) {
				$response['data'][$i]['date'] = date("d-m-Y", strtotime($startDate));
				$response['data'][$i]['day'] = date("l", strtotime($startDate));
				$result = $this->sm->getCallbackAvailability($response['data'][$i]['date']);

				if ($result) {
					$response['data'][$i]['available'] = 1;
					$response['data'][$i]['slots'] = $result;
				} else {
					$response['data'][$i]['available'] = 0;
				}


				$startDate = date("d-m-Y", strtotime("+1 day", strtotime($startDate)));
				$i++;
			}

			$response['message'] = 'Slot data successfully fetched.';
		}

		return $response;
	}

	/* GET CALLBACK SLOT */

	public function getCallbackSlots($data)
	{
		if (
			!isset($data['available_date']) || $data['available_date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getCallbackSlots($data['available_date']);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Slot data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* SET CALLBACK BOOK */

	public function setCallback($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['slot_id']) || $data['slot_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->setCallback($data);
			if ($result == 1) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No slot available.';
			} else if ($result == 2) {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! You have already booked this slot.';
			} else {
				$response['success'] = 1;
				$response['message'] = 'Successsfully booked.';
			}
		}

		return $response;
	}

	/* GET MY CALLBACK BOOKINGS */

	public function getMyCallback($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getMyCallback($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Bookings data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* GET CHAT DATA */

	public function getChatData($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getChatData($data['cust_id']);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Chat data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* GET CHAT DATA */

	public function setChatData($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['sender']) || $data['sender'] == ''
			|| !isset($data['message']) || $data['message'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->setChatData($data);
			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Chat data successfully inserted.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No data found.';
			}
		}

		return $response;
	}

	/* SET SELECTED WORKOUT SIX WEEK */

	/* public function setSelectedWorkout($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_day_id']) || $data['workout_day_id'] == ''
			|| !isset($data['user_workout_id']) || $data['user_workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->setSelectedWorkout($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Workout data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	} */

	/* GET 6 WEEK WORKOUT BY ROUTINE ID */

	/* GET ONE TO ONE EXERCISE LIST */

	public function getExerciseList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getExerciseList($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Exercise data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET EXERCISE DETAILS */

	public function getExerciseDetails($data)
	{
		if (
			!isset($data['excerciseId']) || $data['excerciseId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getExerciseDetails($data);

			if ($result) {
				$responseData = array();

				$responseData['id'] = $result->id;
				$responseData['routine_id'] = $result->routine_id;
				$responseData['workout_title'] = $result->workout_title;
				$responseData['workout_tipe'] = $result->workout_tipe;
				$responseData['workout_desc'] = $result->workout_desc;
				$responseData['workout_type'] = $result->workout_type;
				$responseData['equipments'] = $result->equipments;
				$responseData['rep'] = $result->rep;
				$responseData['sets'] = $result->sets;
				$responseData['worktime'] = $result->worktime;
				$responseData['image'] = $result->image;
				$responseData['video_type'] = $result->video_type;
				$responseData['url_type'] = $result->url_type;
				$responseData['video_path'] = ($result->video_path != "") ? $result->video_path : 'https://nu-by-js.com/';
				$responseData['status'] = $result->status;
				$responseData['update_on'] = $result->update_on;
				$responseData['workout_image_thumb'] = $result->workout_image_thumb;

				$response['success'] = 1;
				$response['data'] = $responseData;
				$response['message'] = 'Exercise data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* SET SIX WEEK EXERCISE TO FAVORITE */

	public function setExerciseToFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['excerciseId']) || $data['excerciseId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$returnData = $this->sm->setExerciseToFavorite($data);
			if ($returnData) {
				$response['success'] = 1;
				$response['data'] = $returnData;
				$response['message'] = 'Exercise data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'You have already added this exercise to your favorite list..';
			}
		}

		return $response;
	}


	/* Remove SIX WEEK EXERCISE TO FAVORITE */

	public function removeExerciseFromFavorite($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['excerciseId']) || $data['excerciseId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$returnData = $this->sm->removeExerciseFromFavorite($data);
			if ($returnData) {
				$response['success'] = 1;
				$response['data'] = $returnData;
				$response['message'] = 'Exercise data successfully removed.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}

		return $response;
	}
	
	/* MY FAVORITE LIST SIX WEEK EXERCISE GYM */

	public function getGymWorkoutSixWeekFavoriteList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getGymWorkoutSixWeekFavoriteList($data['cust_id']);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found';
			}
		}

		return $response;
	}

	/* GET ONE TO ONE WEEK DATA */

	public function getWeekDataOnetoOneForWorkout($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$days = array(
				0 => 'Sunday',
				1 => 'Monday',
				2 => 'Tuesday',
				3 => 'Wednesday',
				4 => 'Thursday',
				5 => 'Friday',
				6 => 'Saturday'
			);

			$resultWorkoutMaster = $this->sm->getWeekDataOnetoOneForWorkout($data['cust_id']);

			$countDay = 0;
			$week = 1;
			if (isset($resultWorkoutMaster) && !empty($resultWorkoutMaster)) {
				foreach ($resultWorkoutMaster as $k => $v) {
					$i = $week - 1;

					$date = $v->one_workout_date;
					$dayName = date('l', $date);
					$dayId = array_search($dayName, $days);

					$returnData['day'][$countDay]['date'] = date('d-m-Y', $date);
					$returnData['day'][$countDay]['dayName'] = $dayName;
					$returnData['day'][$countDay]['dayId'] = $dayId;
					$returnData['day'][$countDay]['masterId'] = $v->id;

					$countDay++;
				}
				if (isset($returnData) && !empty($returnData)) {
					$response['success'] = 1;
					$response['data'] = $returnData;
					$response['message'] = 'Data successfully fetched.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! No data Found.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! No user Found.';
			}
		}

		return $response;
	}

	/* GET Workout LIST ONE TO ONE */

	public function getOnetoOneWorkoutList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['masterId']) || $data['masterId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getOnetoOneWorkoutList($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Workout data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET ONE TO ONE EXERCISE LIST */

	/* public function getExerciseListOnetoOne($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_day_id']) || $data['workout_day_id'] == ''
			|| !isset($data['user_workout_id']) || $data['user_workout_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getExerciseListOnetoOne($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Exercise data successfully selected.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	} */

	/* LIFTSTYLE START */

	/* SET GOAL */
	public function setLifeStyleGoal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['weight']) || $data['weight'] == ''
			|| !isset($data['week']) || $data['week'] == ''
			|| !isset($data['start_date']) || $data['start_date'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {
			$result = $this->sm->getUserPersonalProfile($data['cust_id']);
			if ($result) {
				$bmr = $this->calculateBmr($result->age, $result->height, $result->weight, $result->gender, $result->activity_lavel);

				$dataPost['weight_goal'] = $data['weight'];
				$dataPost['week_target'] = $data['week'];

				$totalCalorieLess = $dataPost['weight_goal'] * 3500;

				$dataPost['week_calorie_less'] = $totalCalorieLess / $data['week'];
				$dataPost['day_less_calorie'] = $dataPost['week_calorie_less'] / 7;

				if (($bmr / 2) >= $dataPost['day_less_calorie']) {
					$dataPost['cust_id'] = $data['cust_id'];
					$dataPost['start_date'] = $data['start_date'];

					$result = $this->sm->setLifeStyleGoal($dataPost);

					if ($result) {
						$response['success'] = 1;
						$response['message'] = 'Your goal has been successfully set.';
					} else {
						$response['success'] = 0;
						$response['message'] = 'Sorry!! no data found.';
					}
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

	/* GET GOAL */
	public function getLifeStyleGoal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getLifeStyleGoal($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Your goal has been successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}


	/* GET TRAINERS LIST */

	public function getTrainersList($data)
	{

		$result = $this->sm->getTrainersList();

		if ($result) {
			$response['success'] = 1;
			$response['data'] = $result;
			$response['message'] = 'Trainers List successfully fetched.';
		} else {
			$response['success'] = 0;
			$response['message'] = 'Sorry!! no data found.';
		}
		return $response;
	}

	/* GET TRAINERS LIST */

	public function getTrainersWorkoutRoutines($data)
	{

		if (
			!isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getTrainersWorkoutRoutines($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Trainers Workout List successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* SELECT TRAINER WORKOUT */

	public function setTrainerWorkoutasSelected($data)
	{
		if (
			!isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->setTrainerWorkoutasSelected($data);

			if ($result) {
				if ($result > 0) {
					$response['success'] = 1;
					$response['data'] = $result;
					$response['message'] = 'You have successfully added this workout to your list.';
				} else {
					$response['success'] = 0;
					$response['message'] = 'Sorry!! You have already selected this workout.';
				}
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* public function GET MY SELECTED WORKOUT LIST */

	public function getMySelectedWorkoutList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getMySelectedWorkoutList($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Your selected workout data successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* REMOVE WORKOUT FROM MY LIST */

	public function removeWorkoutFromMySelectedList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->removeWorkoutFromMySelectedList($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Workout removed successfully from my list.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET EXERCISE LIST BY WORKOUT ID */

	public function getExerciseListByWorkoutId($data)
	{
		if (
			!isset($data['trainer_id']) || $data['trainer_id'] == ''
			|| !isset($data['workout_id']) || $data['workout_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getExerciseListByWorkoutId($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Exercise list successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET EXERCISE DETAILS */
	public function getTrainerExerciseDetails($data)
	{
		if (
			!isset($data['excerciseId']) || $data['excerciseId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getTrainerExerciseDetails($data['excerciseId']);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Exercise details successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET LIFESTYLE WEEK LIST */

	public function getLifeStyleWeekList($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$resultLife = $this->sm->getLifeStyleGoal($data);

			if (isset($resultLife->week_target) && $resultLife->week_target > 0) {
				$week = $resultLife->week_target;
				$startDate = $resultLife->start_date;

				$weekList = array();
				for ($i = 0; $i < $week; $i++) {
					$endDate = date('d-m-Y', strtotime($startDate . ' + 6 days'));
					$weekList[$i] = array(
						'weekNo' => ($i + 1),
						'startDate' => $startDate,
						'endData' => $endDate,
					);

					$startDate = date('d-m-Y', strtotime($startDate . ' + 7 days'));
				}

				$response['success'] = 1;
				$response['data'] = $weekList;
				$response['message'] = 'weeklist successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry! Goal not set.';
			}
		}

		return $response;
	}

	/* LIFESTYLE MEAL BY TYPE */
	public function getLifeStyleMealByTypeWeekDay($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
			|| !isset($data['day_id']) || $data['day_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getLifeStyleMealByTypeWeekDay($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Your goal has been successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* SET MEAL TO MY SELECTED MEAL LIFESTYLE */

	public function setLifeStyleMealByType($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
			|| !isset($data['day_id']) || $data['day_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->setLifeStyleMealByType($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'You have successfully added this meal.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* GET MY SELECTED MEAL BY MEAL TYPE LIFESTYLE */

	public function getLifeStyleMySelectedMeal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
			|| !isset($data['day_id']) || $data['day_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->getLifeStyleMySelectedMeal($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'meal list successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	/* REMOVE MY SELECTED MEAL BY MEAL TYPE LIFESTYLE */

	public function removeLifeStyleMySelectedMeal($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['week_id']) || $data['week_id'] == ''
			|| !isset($data['day_id']) || $data['day_id'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$result = $this->sm->removeLifeStyleMySelectedMeal($data);

			if ($result) {
				$response['success'] = 1;
				$response['data'] = $result;
				$response['message'] = 'Meal successfully removed from your list.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}

	public function getLifeStyleMealDetailsByID($data)
	{
		if (
			!isset($data['cust_id']) || $data['cust_id'] == ''
			|| !isset($data['meal_id']) || $data['meal_id'] == ''
			|| !isset($data['mealTypeId']) || $data['mealTypeId'] == ''
		) {
			$response['success'] = 0;
			$response['message'] = 'Invalid data or required parameter missing.';
		} else {

			$resultCust = $this->sm->getUserPersonalProfile($data['cust_id']);

			if ($resultCust) {
				$totalCalorie = $this->calculateBmr($resultCust->age, $resultCust->height, $resultCust->weight, $resultCust->gender, $resultCust->activity_lavel);
			} else {
				$totalCalorie = 0;
			}

			$resultGoal = $this->sm->getLifeStyleGoal($data);

			if (isset($resultGoal->day_less_calorie) && $resultGoal->day_less_calorie > 0) {
				$lessCalorie = $resultGoal->day_less_calorie;
			} else {
				$lessCalorie = 0;
			}

			$resultMealType = $this->sm->getMealTypeById($data['mealTypeId']);
			if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
				$caroiePer = $resultMealType->calorie_per;
			} else {
				$caroiePer = 0;
			}

			$shouldTakeTotalCalorie = $totalCalorie - $lessCalorie;

			$shouldMealIntakeCalori = ($shouldTakeTotalCalorie * $caroiePer) / 100;


			$result = $this->sm->getLifeStyleMealDetailsByID($data);



			if ($result) {
				$responseData['mealCalorieRequiement'] = $shouldMealIntakeCalori;
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

						if (isset($per_of_meal) && $per_of_meal > 0) {
							$intakeAmt = round(((($shouldMealIntakeCalori * $per_of_meal) / 100) / $val->calorie_per_gm), 2);
						} else {
							$intakeAmt = 0;
						}

						$responseData['ingredientData'][$key]->should_take_unit = $intakeAmt;
						$responseData['ingredientData'][$key]->should_take_measurement =  $val->unit_of_measure;
					}
				}

				$response['success'] = 1;
				$response['data'] = $responseData;
				$response['message'] = 'Meal details successfully fetched.';
			} else {
				$response['success'] = 0;
				$response['message'] = 'Sorry!! no data found.';
			}
		}

		return $response;
	}
}
