<?php
class Meal_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db->query("SET SQL_BIG_SELECTS=1");
		$this->db->query("SET @MAX_QUESTIONS=0");
		$this->db->query("SET SQL_MODE=''");
		date_default_timezone_set('Europe/London');
	}

	public function getMealType()
	{
		$query = $this->db->query("SELECT `id` as meal_type_id, `meal_type_name` as name, `meal_type_desc` as description FROM `meal_type` where `status` = 'Y' ORDER BY `id` asc");

		return $query->result();
	}

	public function getMealTypeById($id)
	{
		$query = $this->db->query("SELECT * FROM `meal_type` WHERE `id` = '" . $id . "'");
		return $query->row();
	}

	public function getMealList($data)
	{
		if (isset($data['cust_id']) && $data['cust_id'] > 0) {
			$cust_id = " AND c.cust_id = '" . $data['cust_id'] . "'";
		} else {
			$cust_id = "";
		}

		if (isset($data['date']) && $data['date'] != '') {
			$date = " AND c.meal_date = '" . strtotime($data['date']) . "'";
		} else {
			$date = "";
		}

		if (isset($data['meal_type_id']) && $data['meal_type_id'] > 0) {
			$meal_type_id = " AND c.meal_type_id = '" . $data['meal_type_id'] . "'";
			$meal_type = " AND m.meal_type_id = '" . $data['meal_type_id'] . "'";
		} else {
			$meal_type_id = "";
			$meal_type = '';
		}

		$query = $this->db->query("SELECT 
		m.id as meal_id, 
		m.meal_type_id as meal_type_id, 
		m.meal_title as name, 
		m.meal_desc as description, 
		m.meal_image as image, 
		m.cooking_time,
		m.min_calorie as calorie,
		(SELECT count(*) as num FROM customer_meal c WHERE c.meal_id = m.id" . $cust_id . $date . $meal_type_id . ") as selected
		FROM meals m where 
		m.status = 'Y' 
		" . $meal_type . "
		ORDER BY m.id asc");

		return $query->result();
	}

	public function selectMeal($data)
	{
		$query = $this->db->query("SELECT `id`, `meal_id` FROM `customer_meal` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `meal_date` = '" . strtotime($data['date']) . "' AND `meal_type_id` = '" . $data['meal_type_id'] . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			if (isset($row->meal_id) && $row->meal_id == $data['meal_id']) {
				return 0;
			} else {
				$this->db->query("UPDATE `customer_meal` SET
							`meal_id` = '" . $data['meal_id'] . "'
							where `id` = '" . $row->id . "'");

				return 1;
			}
		} else {
			$this->db->query("INSERT INTO `customer_meal` SET 
			`cust_id` = '" . $data['cust_id'] . "',
			`meal_date` = '" . strtotime($data['date']) . "',
			`meal_id` = '" . $data['meal_id'] . "',
			`meal_type_id` = '" . $data['meal_type_id'] . "'");

			return 1;
		}
	}

	public function removeMeal($data)
	{
		$query = $this->db->query("SELECT `id` FROM `customer_meal` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `meal_date` = '" . strtotime($data['date']) . "' AND `meal_id` = '" . $data['meal_id'] . "' AND `meal_type_id` = '" . $data['meal_type_id'] . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			$this->db->query("DELETE FROM `customer_meal` WHERE `id` = '" . $row->id . "'");
			return 1;
		} else {
			return 0;
		}
	}

	public function mySelectedMeal($data)
	{
		if (isset($data['cust_id']) && $data['cust_id'] > 0) {
			$cust_id = " AND c.cust_id = '" . $data['cust_id'] . "'";
		} else {
			$cust_id = "";
		}

		if (isset($data['date']) && $data['date'] != '') {
			$date = " AND c.meal_date = '" . strtotime($data['date']) . "'";
		} else {
			$date = "";
		}

		if (isset($data['meal_type_id']) && $data['meal_type_id'] > 0) {
			$meal_type_id = " AND c.meal_type_id = '" . $data['meal_type_id'] . "'";
		} else {
			$meal_type_id = "";
		}

		$query = $this->db->query("SELECT 
		m.id as meal_id, 
		m.meal_type_id as meal_type_id, 
		m.meal_title as name, 
		m.meal_desc as description, 
		m.meal_image as image, 
		m.cooking_time,
		m.min_calorie as calorie,
		DATE_FORMAT(FROM_UNIXTIME(c.meal_date), '%d-%m-%Y') AS date_selected
		FROM customer_meal c
		LEFT JOIN meals m ON m.id = c.meal_id 
		where 1 " . $cust_id . $date . $meal_type_id . "		
		ORDER BY c.id asc");

		return $query->result();
	}

	public function mealDetails($id)
	{
		$query = $this->db->query("SELECT 
								m.id as meal_id,
								m.meal_title, 
								m.meal_desc, 
								m.meal_image, 
								m.prep_time, 
								m.cooking_time, 
								m.cooking_method, 
								m.video_type, 
								m.video_path,
								m.food_type,
								m.food_type_id,
								m.meal_tips,
								m.min_calorie as calorie
								FROM meals m 
								WHERE m.id = '" . $id . "'");
		$result['mealData'] = $query->row();

		$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, m.per_of_meal, m.max_amt, m.min_amt
									FROM  meals_ingredient m 
									WHERE m.meal_id = '" . $id . "'");
		$result['ingredientData'] = $queryIng->result();

		$this->db->from('meals_nutritions');
		$this->db->select('*');
		$this->db->where('meal_id', $id);
		$query = $this->db->get();
		$result['nutriData'] = $query->result();

		$this->db->from('meals_extraingredient');
		$this->db->select('id, extra_ingredient_name as ingredient_name, extra_measure as unit_of_measure, extra_amt as amount');
		$this->db->where('meal_id', $id);
		$query = $this->db->get();
		$result['extraData'] = $query->result();

		return $result;
	}
}
