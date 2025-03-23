<?php

class User_model extends CI_Model

{

	public function __construct()

	{

		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
	}

	public function addUserMeal($data)
	{
		$queryMaster = $this->db->query("SELECT `id` FROM `user_meal_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "' AND `week_id` = '" . $data['week'] . "'");

		$rowMaster = $queryMaster->row();

		if (isset($rowMaster->id) && $rowMaster->id > 0) {
			$meal_master_id = $rowMaster->id;
		} else {
			$sqlMaster = "INSERT INTO `user_meal_master` SET 
			`user_id` = '" . $data['userId'] . "',
			`package_id` = '" . $data['packageid'] . "',
			`week_id` = '" . $data['week'] . "'";

			$this->db->query($sqlMaster);

			$meal_master_id = $this->db->insert_id();
		}


		if (isset($data['dayid']) && !empty($data['dayid'])) {
			foreach ($data['dayid'] as $key => $val) {

				$queryMealDay = $this->db->query("SELECT `id` FROM `user_meal_master_day` WHERE `meal_master_id` = '" . $meal_master_id . "' AND `day_id` = '" . $data['dayid'][$key] . "' AND `meal_type_id` = '" . $data['meal_type_id'] . "'");

				$rowMealDay = $queryMealDay->row();

				if (isset($rowMealDay->id) && $rowMealDay->id > 0) {
					$meal_day_id = $rowMealDay->id;
				} else {
					$sqlMealDay = "INSERT INTO `user_meal_master_day` SET
					`meal_master_id` = '" . $meal_master_id . "',
					`day_name` = '" . $data['day'][$key] . "',
					`day_id` = '" . $data['dayid'][$key] . "',
					`meal_type_id` = '" . $data['meal_type_id'] . "',
					`week_meal_calorie` = '" . $data['weekMealCalorie'] . "'";

					$this->db->query($sqlMealDay);

					$meal_day_id = $this->db->insert_id();
				}

				$this->db->query("DELETE FROM `user_meal` WHERE `meal_day_id` = '" . $meal_day_id . "'");
				$this->db->query("DELETE FROM `user_meal_ingredients` WHERE `meal_day_id` = '" . $meal_day_id . "'");

				if (isset($data['selectedMeal'][$val]) && !empty($data['selectedMeal'][$val])) {
					foreach ($data['selectedMeal'][$val] as $k => $meal) {
						if ($meal > 0) {
							$sqlMeal = "INSERT INTO `user_meal` SET 
										`meal_day_id` = '" . $meal_day_id . "',
										`meal_id` = '" . $meal . "',
										`meal_intake_calorie` = '" . $data['mealintakecalorie'][$val][$meal] . "'";

							$this->db->query($sqlMeal);

							$user_meal_id = $this->db->insert_id();

							if (isset($data['usermealingamt'][$val][$meal]) && (!empty($data['usermealingamt'][$val][$meal]))) {
								foreach ($data['usermealingamt'][$val][$meal] as $ki => $vi) {
									if ($vi > 0) {
										$sqlIng = "INSERT INTO `user_meal_ingredients` SET 
													`user_meal_id` = '" . $user_meal_id . "',
													`meal_day_id` = '" . $meal_day_id . "',
													`ingredient_id` = '" . $ki . "',
													`ingredient_amt` = '" . $vi . "',
													`ingredient_intake_calorie` = '" . $data['intakecalorie'][$val][$meal][$ki] . "'";

										$this->db->query($sqlIng);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	public function fetchUserMeal($id, $packid)
	{
		$queryMealMaster = $this->db->query("SELECT * FROM `user_meal_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '" . $packid . "' ORDER BY `week_id` asc");
		$result['mealMaster'] = $queryMealMaster->result();

		if (isset($result['mealMaster']) && !empty($result['mealMaster'])) {
			foreach ($result['mealMaster'] as $key => $day) {
				$queryMealDay = $this->db->query("SELECT u.*, m.meal_type_name, m.meal_type_desc FROM user_meal_master_day u
				LEFT JOIN meal_type m ON m.id = u.meal_type_id
				WHERE u.meal_master_id = '" . $day->id . "'");
				$result['mealDay'][$day->id] = $queryMealDay->result();

				if (isset($result['mealDay'][$day->id]) && !empty($result['mealDay'][$day->id])) {
					foreach ($result['mealDay'][$day->id] as $k => $v) {
						$queryMeal = $this->db->query("SELECT u.*, m.meal_title, m.meal_image_thumb, m.prep_time, m.cooking_time, m.meal_desc, m.food_type FROM user_meal u
						LEFT JOIN meals m ON m.id = u.meal_id
						WHERE u.meal_day_id = '" . $v->id . "'");

						$result['mealLog'][$day->id][$v->id] = $queryMeal->result();

						if (isset($result['mealLog'][$day->id][$v->id]) && !empty($result['mealLog'][$day->id][$v->id])) {
							foreach ($result['mealLog'][$day->id][$v->id] as $ki => $vi) {
								$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, u.ingredient_amt, u.ingredient_intake_calorie FROM  user_meal_ingredients u 
								LEFT JOIN meals_ingredient m ON m.id = u.ingredient_id
								WHERE u.user_meal_id = '" . $vi->id . "'");

								$result['mealIngredientLog'][$day->id][$v->id][$vi->id] = $queryIng->result();
							}
						}
					}
				}
			}
		} else {
			$result['mealDay'] = array();
			$result['mealLog'] = array();
			$result['mealIngredientLog'] = array();
		}
		return $result;
	}


	public function fetchUserMealByWeeknPackage($id, $packid, $week, $mealTypeId)
	{
		$result = array();
		$queryMealMaster = $this->db->query("SELECT * FROM `user_meal_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '" . $packid . "' AND `week_id` = '" . $week . "'");
		$mealMaster = $queryMealMaster->result();

		if (isset($mealMaster) && !empty($mealMaster)) {
			foreach ($mealMaster as $key => $day) {
				$queryMealDay = $this->db->query("SELECT u.*, m.meal_type_name, m.meal_type_desc FROM user_meal_master_day u
				LEFT JOIN meal_type m ON m.id = u.meal_type_id
				WHERE u.meal_master_id = '" . $day->id . "' AND u.meal_type_id = '" . $mealTypeId . "'");
				$mealDay = $queryMealDay->result();

				if (isset($mealDay) && !empty($mealDay)) {
					foreach ($mealDay as $k => $v) {
						$queryMeal = $this->db->query("SELECT u.*, m.meal_title, m.meal_image_thumb, m.prep_time, m.cooking_time, m.meal_desc, m.food_type FROM user_meal u
						LEFT JOIN meals m ON m.id = u.meal_id
						WHERE u.meal_day_id = '" . $v->id . "'");

						$result['userMealLog'][$v->day_id] = $queryMeal->result();

						if (isset($result['userMealLog'][$v->day_id]) && !empty($result['userMealLog'][$v->day_id])) {
							foreach ($result['userMealLog'][$v->day_id] as $ki => $vi) {
								$queryIng = $this->db->query("SELECT m.ingredient_name, m.id, m.calorie_per_gm, u.ingredient_amt, u.ingredient_intake_calorie FROM  user_meal_ingredients u 
								LEFT JOIN meals_ingredient m ON m.id = u.ingredient_id
								WHERE u.user_meal_id = '" . $vi->id . "'");

								$result['mealIngredientLog'][$v->day_id][$vi->meal_id] = $queryIng->result();
							}
						}
					}
				}
			}
		}
		return $result;
	}


	/* FETCH SINGEL DATA BY ID */

	public function fetchSingleDataById($table, $field, $value)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($field, $value);
		$query = $this->db->get();

		return $query->row();
	}

	/* ADD USER MEAL ONE TO ONE */

	public function addUserMealOnetoOne($data)
	{
		$queryMaster = $this->db->query("SELECT `id` FROM `user_meal_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "' AND `one_meal_date` = '" . $data['one_meal_date'] . "'");

		$rowMaster = $queryMaster->row();

		if (isset($rowMaster->id) && $rowMaster->id > 0) {
			$meal_master_id = $rowMaster->id;
		} else {
			$sqlMaster = "INSERT INTO `user_meal_master` SET 
			`user_id` = '" . $data['userId'] . "',
			`package_id` = '" . $data['packageid'] . "',
			`one_meal_date` = '" . $data['one_meal_date'] . "'";

			$this->db->query($sqlMaster);

			$meal_master_id = $this->db->insert_id();
		}


		if (isset($data['meal_type_id']) && !empty($data['meal_type_id'])) {
			foreach ($data['meal_type_id'] as $key => $val) {

				$queryMealDay = $this->db->query("SELECT `id` FROM `user_meal_master_day` WHERE `meal_master_id` = '" . $meal_master_id . "' AND `meal_type_id` = '" . $val . "'");

				$rowMealDay = $queryMealDay->row();

				if (isset($rowMealDay->id) && $rowMealDay->id > 0) {
					$meal_day_id = $rowMealDay->id;
				} else {
					$sqlMealDay = "INSERT INTO `user_meal_master_day` SET
					`meal_master_id` = '" . $meal_master_id . "',
					`meal_type_id` = '" . $val . "',
					`week_meal_calorie` = '" . $data['week_meal_calorie'][$key] . "'";

					$this->db->query($sqlMealDay);

					$meal_day_id = $this->db->insert_id();
				}

				$this->db->query("DELETE FROM `user_meal` WHERE `meal_day_id` = '" . $meal_day_id . "'");
				$this->db->query("DELETE FROM `user_meal_ingredients` WHERE `meal_day_id` = '" . $meal_day_id . "'");

				if (isset($data['selectedMeal'][$val]) && !empty($data['selectedMeal'][$val])) {
					foreach ($data['selectedMeal'][$val] as $k => $meal) {
						if ($meal > 0) {
							$sqlMeal = "INSERT INTO `user_meal` SET 
										`meal_day_id` = '" . $meal_day_id . "',
										`meal_id` = '" . $meal . "',
										`meal_intake_calorie` = '" . $data['mealintakecalorie'][$val][$meal] . "'";

							$this->db->query($sqlMeal);

							$user_meal_id = $this->db->insert_id();

							if (isset($data['usermealingamt'][$val][$meal]) && (!empty($data['usermealingamt'][$val][$meal]))) {
								foreach ($data['usermealingamt'][$val][$meal] as $ki => $vi) {
									if ($vi > 0) {
										$sqlIng = "INSERT INTO `user_meal_ingredients` SET 
													`user_meal_id` = '" . $user_meal_id . "',
													`meal_day_id` = '" . $meal_day_id . "',
													`ingredient_id` = '" . $ki . "',
													`ingredient_amt` = '" . $vi . "',
													`ingredient_intake_calorie` = '" . $data['intakecalorie'][$val][$meal][$ki] . "'";

										$this->db->query($sqlIng);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/* FETCH USER MEAL FRO ONE TO ONE PACKAGE */

	public function fetchUserMealOnetoOne($id, $packid)
	{
		$queryMealMaster = $this->db->query("SELECT * FROM `user_meal_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '" . $packid . "' ORDER BY `one_meal_date` DESC");
		$result['mealMaster'] = $queryMealMaster->result();

		if (isset($result['mealMaster']) && !empty($result['mealMaster'])) {
			foreach ($result['mealMaster'] as $key => $day) {
				$queryMealDay = $this->db->query("SELECT u.*, m.meal_type_name, m.meal_type_desc FROM user_meal_master_day u
				LEFT JOIN meal_type m ON m.id = u.meal_type_id
				WHERE u.meal_master_id = '" . $day->id . "'");
				$result['mealDay'][$day->id] = $queryMealDay->result();

				if (isset($result['mealDay'][$day->id]) && !empty($result['mealDay'][$day->id])) {
					foreach ($result['mealDay'][$day->id] as $k => $v) {
						$queryMeal = $this->db->query("SELECT u.*, m.meal_title, m.meal_image_thumb, m.prep_time, m.cooking_time, m.meal_desc, m.food_type FROM user_meal u
						LEFT JOIN meals m ON m.id = u.meal_id
						WHERE u.meal_day_id = '" . $v->id . "'");

						$result['mealLog'][$day->id][$v->id] = $queryMeal->result();

						if (isset($result['mealLog'][$day->id][$v->id]) && !empty($result['mealLog'][$day->id][$v->id])) {
							foreach ($result['mealLog'][$day->id][$v->id] as $ki => $vi) {
								$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, u.ingredient_amt, u.ingredient_intake_calorie FROM  user_meal_ingredients u 
								LEFT JOIN meals_ingredient m ON m.id = u.ingredient_id
								WHERE u.user_meal_id = '" . $vi->id . "'");

								$result['mealIngredientLog'][$day->id][$v->id][$vi->id] = $queryIng->result();
							}
						}
					}
				}
			}
		} else {
			$result['mealDay'] = array();
			$result['mealLog'] = array();
			$result['mealIngredientLog'] = array();
		}
		return $result;
	}

	public function fetchUserMealOnetoOneBymealMasterId($id, $packid, $meal_master_id)
	{
		$queryMealMaster = $this->db->query("SELECT * FROM `user_meal_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '" . $packid . "' AND `id` = '" . $meal_master_id . "'");
		$result['mealMaster'] = $queryMealMaster->row();
		$day = $result['mealMaster'];

		$queryMealDay = $this->db->query("SELECT u.*, m.meal_type_name, m.meal_type_desc FROM user_meal_master_day u
				LEFT JOIN meal_type m ON m.id = u.meal_type_id
				WHERE u.meal_master_id = '" . $day->id . "'");
		$result['mealDay'] = $queryMealDay->result();

		if (isset($result['mealDay']) && !empty($result['mealDay'])) {
			foreach ($result['mealDay'] as $k => $v) {
				$queryMeal = $this->db->query("SELECT u.meal_id, u.id FROM user_meal u
						WHERE u.meal_day_id = '" . $v->id . "'");

				$result['mealLog'][$v->meal_type_id] = $queryMeal->result();

				if (isset($result['mealLog'][$v->meal_type_id]) && !empty($result['mealLog'][$v->meal_type_id])) {
					foreach ($result['mealLog'][$v->meal_type_id] as $ki => $vi) {
						$queryIng = $this->db->query("SELECT u.ingredient_amt, u.ingredient_intake_calorie, m.id FROM  user_meal_ingredients u 
								LEFT JOIN meals_ingredient m ON m.id = u.ingredient_id
								WHERE u.user_meal_id = '" . $vi->id . "'");

						$result['mealIngredientLog'][$v->meal_type_id][$vi->meal_id] = $queryIng->result();
					}
				}
			}
		}
		return $result;
	}

	/* 6 week user workout fetch */

	public function fetchUserWorkout($id)
	{
		for ($week = 1; $week <= 6; $week++) {
			$query = $this->db->query("SELECT * FROM `workout_routine` WHERE `package_id` = '2' AND `week_id` like '%".$week."%'");
			$result[$week]['routineData'] = $query->result();

			if(isset($result[$week]['routineData']) && !empty($result[$week]['routineData'])){
				foreach($result[$week]['routineData'] as $key => $val){
					$queryExcercise = $this->db->query("SELECT * FROM `workout` WHERE `routine_id` = '".$val->id."'");
					$result[$week]['workoutData'][$val->id] = $queryExcercise->result();
				}
			}else{
				$result[$week]['workoutData'] = array();
			}
		}
		return $result;
	}

	/* ADD 6 WEEK USER WORKOUT DATA */

	public function addUserWorkout($data)
	{
		if (isset($data['week_id']) && !empty($data['week_id'])) {
			foreach ($data['week_id'] as $week) {

				$queryMaster = $this->db->query("SELECT `id` FROM `user_workout_master` WHERE 
												`user_id` = '" . $data['userId'] . "' 
												AND `package_id` = '" . $data['packageid'] . "' 
												AND `week_id` = '" . $week . "'");

				$rowMaster = $queryMaster->row();

				if (isset($rowMaster->id) && $rowMaster->id > 0) {
					$workout_master_id = $rowMaster->id;
				} else {

					$this->db->query("INSERT INTO `user_workout_master` SET 
								`user_id` = '" . $data['userId'] . "',
								`package_id` = '" . $data['packageid'] . "',
								`week_id` = '" . $week . "'");

					$workout_master_id = $this->db->insert_id();
				}

				if (isset($data['day_id'][$week]) && !empty($data['day_id'][$week])) {
					foreach ($data['day_id'][$week] as $k => $day) {

						$queryDay = $this->db->query("SELECT `id` FROM `user_workout_master_day` WHERE
														`workout_master_id` = '" . $workout_master_id . "' 
														AND `day_id` = '" . $day . "'");
						$rowDay = $queryDay->row();

						if (isset($rowDay->id) && $rowDay->id > 0) {
							$workout_day_id = $rowDay->id;
						} else {

							$this->db->query("INSERT INTO `user_workout_master_day` SET
										`workout_master_id` = '" . $workout_master_id . "',
										`day_name` = '" . $data['day_name'][$week][$k] . "',
										`day_id` = '" . $day . "'");

							$workout_day_id = $this->db->insert_id();
						}

						$this->db->query("DELETE FROM `user_workout` WHERE `workout_day_id` = '" . $workout_day_id . "'");

						if (isset($data['routine_id'][$week][$day]) && !empty($data['routine_id'][$week][$day])) {
							foreach ($data['routine_id'][$week][$day] as $key => $val) {
								if ($val != "") {
									$this->db->query("INSERT INTO `user_workout` SET
											`workout_day_id` = '" . $workout_day_id . "',
											`routine_id` = '" . $val . "'");
								}
							}
						}
					}
				}
			}
		}
	}

	/* ONE TO ONE WORKUT ADD LOAD */

	public function onetooneWorkoutLoad($data)
	{

		$queryChk = $this->db->query("SELECT `one_workout_date` FROM `user_workout_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "' ORDER BY `id` DESC LIMIT 1");
		$rowChk = $queryChk->row();

		if (isset($rowChk->one_workout_date) && $rowChk->one_workout_date != "") {
			$returnData['startData'] = strtotime(date('d-m-Y', $rowChk->one_workout_date) . ' + 1day');
		} else {
			$returnData['startData'] = $data['startdate'];
		}

		$this->db->from('workout_routine');
		$this->db->select('*');
		$this->db->where('status', 'Y');
		$this->db->where('package_id', '1');
		$query = $this->db->get();

		$returnData['routineLog'] = $query->result();

		return $returnData;
	}

	public function fetchUserWorkoutUpdateData($data)
	{

		$queryChk = $this->db->query("SELECT `one_workout_date` FROM `user_workout_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "'  AND `id` = '" . $data['workout_master_id'] . "' ORDER BY `id` DESC LIMIT 1");
		$rowChk = $queryChk->row();

		$returnData['startData'] = $rowChk->one_workout_date;

		$this->db->from('workout_routine');
		$this->db->select('*');
		$this->db->where('status', 'Y');
		$this->db->where('package_id', '1');
		$query = $this->db->get();

		$returnData['routineLog'] = $query->result();

		$queryDay = $this->db->query("SELECT u.id FROM user_workout_master_day u
				WHERE u.workout_master_id = '" . $data['workout_master_id'] . "'");
		$returnData['workoutDay'] = $queryDay->result();

		if (isset($returnData['workoutDay']) && !empty($returnData['workoutDay'])) {
			foreach ($returnData['workoutDay'] as $k => $v) {
				$queryWork = $this->db->query("SELECT u.routine_id FROM user_workout u
						WHERE u.workout_day_id = '" . $v->id . "'");

				$returnData['workoutLog'][$v->id] = $queryWork->result();
			}
		}

		return $returnData;
	}

	/* ADD USER WORKOUT DATA FOR ONE TO ONE */

	public function addUserWorkoutOnetoOne($data)
	{
		$queryMaster = $this->db->query("SELECT `id` FROM `user_workout_master` WHERE 
												`user_id` = '" . $data['user_id'] . "' 
												AND `package_id` = '" . $data['package_id'] . "' 
												AND `one_workout_date` = '" . $data['one_workout_date'] . "'");

		$rowMaster = $queryMaster->row();

		if (isset($rowMaster->id) && $rowMaster->id > 0) {
			$workout_master_id = $rowMaster->id;
		} else {

			$this->db->query("INSERT INTO `user_workout_master` SET 
								`user_id` = '" . $data['user_id'] . "',
								`package_id` = '" . $data['package_id'] . "',
								`one_workout_date` = '" . $data['one_workout_date'] . "'");

			$workout_master_id = $this->db->insert_id();
		}

		$queryDay = $this->db->query("SELECT `id` FROM `user_workout_master_day` WHERE
														`workout_master_id` = '" . $workout_master_id . "' 
														AND `day_id` = '0'");
		$rowDay = $queryDay->row();

		if (isset($rowDay->id) && $rowDay->id > 0) {
			$workout_day_id = $rowDay->id;
		} else {

			$this->db->query("INSERT INTO `user_workout_master_day` SET
										`workout_master_id` = '" . $workout_master_id . "',
										`day_id` = '0'");

			$workout_day_id = $this->db->insert_id();
		}

		$this->db->query("DELETE FROM `user_workout` WHERE `workout_day_id` = '" . $workout_day_id . "'");

		if (isset($data['routine_id']) && !empty($data['routine_id'])) {
			foreach ($data['routine_id'] as $key => $val) {
				if ($val != "") {
					$this->db->query("INSERT INTO `user_workout` SET
											`workout_day_id` = '" . $workout_day_id . "',
											`routine_id` = '" . $val . "'");
				}
			}
		}
	}

	/* FET USER WORKOUT ONE TO ONE */

	public function fetchUserWorkoutOnetoOne($id, $packid)
	{
		$queryMaster = $this->db->query("SELECT * FROM `user_workout_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '" . $packid . "' ORDER BY `one_workout_date` DESC");
		$result['workoutMaster'] = $queryMaster->result();

		if (isset($result['workoutMaster']) && !empty($result['workoutMaster'])) {
			foreach ($result['workoutMaster'] as $key => $day) {
				$queryDay = $this->db->query("SELECT u.* FROM user_workout_master_day u
				WHERE u.workout_master_id = '" . $day->id . "'");
				$result['workoutDay'][$day->id] = $queryDay->result();

				if (isset($result['workoutDay'][$day->id]) && !empty($result['workoutDay'][$day->id])) {
					foreach ($result['workoutDay'][$day->id] as $k => $v) {
						$queryWork = $this->db->query("SELECT u.*, m.title, m.image, m.min_time, m.max_time, m.min_calorie, m.max_calorie, m.musclegroup FROM user_workout u
						LEFT JOIN workout_routine m ON m.id = u.routine_id
						WHERE u.workout_day_id = '" . $v->id . "'");

						$result['workoutLog'][$day->id][$v->id] = $queryWork->result();
					}
				}
			}
		} else {
			$result['workoutDay'] = array();
			$result['workoutLog'] = array();
		}
		return $result;
	}






	/* GET MEAL FOR SIX WEEK USERS BY USER ID */

	public function fetchUserMealSixWeek($id)
	{

		$queryMealType = $this->db->query("SELECT * FROM `meal_type` WHERE 1 ORDER BY `id` asc");
		$resultMealType = $queryMealType->result();

		for ($week = 1; $week <= 6; $week++) {
			for ($day = 0; $day <= 6; $day++) {
				if (isset($resultMealType) && !empty($resultMealType)) {
					foreach ($resultMealType as $key => $val) {
						$result[$week][$day][$val->id]['mealTypeName'] = $val->meal_type_name;
						$query = $this->db->query("SELECT m.id,
								m.meal_title, 
								m.meal_image_thumb, 
								m.food_type, 
								m.prep_time, 
								m.cooking_time, 
								m.cooking_method, 
								m.video_type, 
								m.video_path,
								u.meal_calorie
								FROM user_meal_six_week u
								LEFT JOIN meals m ON m.id = u.meal_id
								WHERE u.meal_type_id = '" . $val->id . "'
								AND u.user_id = '" . $id . "'
								AND u.week = '" . $week . "'
								AND u.day = '" . $day . "'");

						$result[$week][$day][$val->id]['mealData'] = $query->result();

						if (isset($result[$week][$day][$val->id]['mealData']) && !empty($result[$week][$day][$val->id]['mealData'])) {
							foreach ($result[$week][$day][$val->id]['mealData'] as $k => $v) {
								$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, m.per_of_meal
									FROM  meals_ingredient m 
									WHERE m.meal_id = '" . $v->id . "'");

								$result[$week][$day][$val->id]['ingredientData'][$v->id] = $queryIng->result();

							}
						}
					}
				}
			}
		}

		return $result;

	}
}
