<?php

class Meal_model extends CI_Model

{

	public function __construct()

	{

		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
	}

	public function fetchMealData()
	{
		$query = $this->db->query("SELECT m.*, t.meal_type_name FROM meals m
		LEFT JOIN meal_type t ON t.id = m.meal_type_id
		WHERE 1 ORDER BY m.id DESC");

		$result['resultLog'] = $query->result();

		if (isset($result['resultLog']) && !empty($result['resultLog'])) {
			foreach ($result['resultLog'] as $key => $val) {
				$queryNutrition = $this->db->query("SELECT * FROM `meals_nutritions` WHERE `meal_id` = '" . $val->id . "'");
				$result['nutritionLog'][$val->id] = $queryNutrition->result();
			}
		} else {
			$result['nutritionLog'] = array();
		}

		return $result;
	}

	public function addIngredients($data)
	{
		if (isset($data['meal_id']) && $data['meal_id'] > 0) {
			$this->db->query("DELETE FROM `meals_ingredient` WHERE `meal_id` =  '" . $data['meal_id'] . "'");

			if (isset($data['ingredient_name']) && !empty($data['ingredient_name'])) {
				foreach ($data['ingredient_name'] as $key => $val) {
					if ($val != "") {
						$this->db->query("INSERT INTO `meals_ingredient` SET
						`meal_id` = '" . $data['meal_id'] . "',
						`ingredient_name` = '" . $val . "',
						`calorie_per_gm` = '" . $data['calorie_per_gm'][$key] . "',
						`ing_min_calorie` = '" . $data['ing_min_calorie'][$key] . "',
						`ing_max_calorie` = '" . $data['ing_max_calorie'][$key] . "',
						`min_amt` = '" . $data['min_amt'][$key] . "',
						`unit_of_measure` = '" . $data['unit_of_measure'][$key] . "'");
						/* $this->db->query("INSERT INTO `meals_ingredient` SET
						`meal_id` = '" . $data['meal_id'] . "',
						`ingredient_name` = '" . $val . "',
						`calorie_per_gm` = '" . $data['calorie_per_gm'][$key] . "',
						`ing_min_calorie` = '" . $data['ing_min_calorie'][$key] . "',
						`ing_max_calorie` = '" . $data['ing_max_calorie'][$key] . "',
						`min_amt` = '" . $data['min_amt'][$key] . "',
						`max_amt` = '" . $data['max_amt'][$key] . "',
						`unit_of_measure` = '" . $data['unit_of_measure'][$key] . "',
						`per_of_meal` = '" . $data['per_of_meal'][$key] . "'"); */
					}
				}
			}
		}
	}

	public function addNutritions($data)
	{
		if (isset($data['meal_id']) && $data['meal_id'] > 0) {
			$this->db->query("DELETE FROM `meals_nutritions` WHERE `meal_id` =  '" . $data['meal_id'] . "'");

			if (isset($data['nutrition_name']) && !empty($data['nutrition_name'])) {
				foreach ($data['nutrition_name'] as $key => $val) {
					if ($data['nutrition_amount'][$key] != "") {
						$this->db->query("INSERT INTO `meals_nutritions` SET
						`meal_id` = '" . $data['meal_id'] . "',
						`nutrition_name` = '" . $val . "',
						`nutrition_amount` = '" . $data['nutrition_amount'][$key] . "'");
					}
				}
			}
		}
	}
	
	public function addExtraIngredient($data)
	{
		if (isset($data['meal_id']) && $data['meal_id'] > 0) {
			$this->db->query("DELETE FROM `meals_extraingredient` WHERE `meal_id` =  '" . $data['meal_id'] . "'");

			if (isset($data['extra_ingredient_name']) && !empty($data['extra_ingredient_name'])) {
				foreach ($data['extra_ingredient_name'] as $key => $val) {
					if ($val != "") {
						$this->db->query("INSERT INTO `meals_extraingredient` SET
						`meal_id` = '" . $data['meal_id'] . "',
						`extra_ingredient_name` = '" . $val . "',
						`extra_amt` = '" . $data['extra_amt'][$key] . "',
						`extra_measure` = '" . $data['extra_measure'][$key] . "'");
					}
				}
			}
		}
	}

	/* FETCH MEAL DATA WITH INGRIDIENT BY MEAL TYPE ID */

	public function fetchMealbyIdnCalorie($id, $calorie)
	{
		$this->db->from('meals');
		$this->db->select('*');
		$this->db->where('meal_type_id', $id);
		$this->db->where('status', 'Y');
		$this->db->where('min_calorie <=', $calorie);
		$this->db->where('max_calorie >=', $calorie);
		$queryMeal = $this->db->get();

		$result['mealLog'] = $queryMeal->result();

		if (isset($result['mealLog']) && !empty($result['mealLog'])) {
			foreach ($result['mealLog'] as $key => $val) {
				$queryIng = $this->db->query("SELECT * FROM `meals_ingredient` WHERE `meal_id` = '" . $val->id . "'");
				$result['ingredientLog'][$val->id] = $queryIng->result();
			}
		} else {
			$result['ingredientLog'] = array();
		}

		return $result;
	}

	/* ONO TO ONE MEAL LOAD */

	public function onetooneMealLoad($data)
	{

		$queryChk = $this->db->query("SELECT `one_meal_date` FROM `user_meal_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "' ORDER BY `id` DESC LIMIT 1");
		$rowChk = $queryChk->row();

		if (isset($rowChk->one_meal_date) && $rowChk->one_meal_date != "") {
			$returnData['startData'] = strtotime(date('d-m-Y', $rowChk->one_meal_date) . ' + 1day');
		} else {
			$returnData['startData'] = $data['startdate'];
		}

		$queryMealType = $this->db->query("SELECT * FROM `meal_type` WHERE 1 AND `status` = 'Y' ORDER BY `id` asc");
		$returnData['mealTypeLog'] = $queryMealType->result();

		if (isset($returnData['mealTypeLog']) && !empty($returnData['mealTypeLog'])) {
			foreach ($returnData['mealTypeLog'] as $key => $val) {
				$totalCalorie = $data['totalCalorie'];

				if (isset($val->calorie_per) && $val->calorie_per > 0) {
					$caloriePer = $val->calorie_per;
				} else {
					$caloriePer = 0;
				}

				$mealCalorie = ($totalCalorie * $caloriePer) / 100;

				$returnData['mealClorie'][$val->id] = $mealCalorie;

				$this->db->from('meals');
				$this->db->select('*');
				$this->db->where('meal_type_id', $val->id);
				$this->db->where('status', 'Y');
				$this->db->where('min_calorie <=', $mealCalorie);
				$this->db->where('max_calorie >=', $mealCalorie);
				$queryMeal = $this->db->get();

				$returnData['mealLog'][$val->id] = $queryMeal->result();

				if (isset($returnData['mealLog'][$val->id]) && !empty($returnData['mealLog'][$val->id])) {
					foreach ($returnData['mealLog'][$val->id] as $k => $v) {
						$queryIng = $this->db->query("SELECT * FROM `meals_ingredient` WHERE `meal_id` = '" . $v->id . "'");
						$returnData['ingredientLog'][$v->id] = $queryIng->result();
					}
				} 
			}
		}else {
			$returnData['ingredientLog'] = array();
		}

		return $returnData;
	}
	
	public function onetooneMealLoadUpdate($data)
	{

		$queryChk = $this->db->query("SELECT `one_meal_date` FROM `user_meal_master` WHERE `user_id` = '" . $data['userId'] . "' AND `package_id` = '" . $data['packageid'] . "' AND `id` = '".$data['meal_master_id']."' ORDER BY `id` DESC LIMIT 1");
		$rowChk = $queryChk->row();

		$returnData['startData'] = $rowChk->one_meal_date;

		$queryMealType = $this->db->query("SELECT * FROM `meal_type` WHERE 1 AND `status` = 'Y' ORDER BY `id` asc");
		$returnData['mealTypeLog'] = $queryMealType->result();

		if (isset($returnData['mealTypeLog']) && !empty($returnData['mealTypeLog'])) {
			foreach ($returnData['mealTypeLog'] as $key => $val) {
				$totalCalorie = $data['totalCalorie'];

				if (isset($val->calorie_per) && $val->calorie_per > 0) {
					$caloriePer = $val->calorie_per;
				} else {
					$caloriePer = 0;
				}

				$mealCalorie = ($totalCalorie * $caloriePer) / 100;

				$returnData['mealClorie'][$val->id] = $mealCalorie;

				$this->db->from('meals');
				$this->db->select('*');
				$this->db->where('meal_type_id', $val->id);
				$this->db->where('status', 'Y');
				$this->db->where('min_calorie <=', $mealCalorie);
				$this->db->where('max_calorie >=', $mealCalorie);
				$queryMeal = $this->db->get();

				$returnData['mealLog'][$val->id] = $queryMeal->result();

				if (isset($returnData['mealLog'][$val->id]) && !empty($returnData['mealLog'][$val->id])) {
					foreach ($returnData['mealLog'][$val->id] as $k => $v) {
						$queryIng = $this->db->query("SELECT * FROM `meals_ingredient` WHERE `meal_id` = '" . $v->id . "'");
						$returnData['ingredientLog'][$v->id] = $queryIng->result();
					}
				} 
			}
		}else {
			$returnData['ingredientLog'] = array();
		}

		return $returnData;
	}
}
