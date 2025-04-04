<?php
class Auth_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db->query("SET SQL_BIG_SELECTS=1");
		$this->db->query("SET @MAX_QUESTIONS=0");
		$this->db->query("SET SQL_MODE=''");
		date_default_timezone_set('Europe/London');
	}
	/* REGISTRATION & LOGIN */

	function checkUserExists($email_id)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `email_id` = '" . trim($email_id) . "'");
		return $query->num_rows();
	}

	function setUserRegistration($data)
	{

		$query = $this->db->query("INSERT INTO `customer` SET
								`name` = '" . $data['name'] . "',
								`email_id` = '" . $data['email_id'] . "',
								`password` = '" . password_hash($data['password'], PASSWORD_DEFAULT) . "',
								`status` = '" . $data['status'] . "'");

		$id = $this->db->insert_id();

		$user_code = sprintf("%06d", $id);

		$this->db->query("UPDATE `customer` set `user_code` = '" . $user_code . "' WHERE `id` = '" . $id . "'");

		$this->db->query("INSERT INTO `login_data` SET `cust_id` = '" . $id . "', `email_id` = '" . $data['email_id'] . "', `password` = '" . password_hash($data['password'], PASSWORD_DEFAULT) . "', `type` = 'user'");

		return strval($id);
	}

	function getUserPersonalProfile($id)
	{
		$query = $this->db->query("SELECT `id`, `user_code`, `name`, `email_id`, `age`, `height`, `weight`, `gender`, `activity_lavel`, `goal`, `allergies`, `foodddislike`, `profile_picture` as image
								FROM `customer`
								WHERE `id` = '" . $id . "'");

		return $query->row();
	}

	function getUserLogin($data)
	{
		$query = $this->db->query("SELECT * FROM `login_data` where `email_id` = '" . trim($data['email_id']) . "'");
		$num = $query->num_rows();

		if ($num) {
			return $query->row();
		} else {
			return false;
		}
	}

	function fetchUserDataById($id)
	{
		$query = $this->db->query("SELECT * FROM `customer` where `id` = '" . $id . "'");
		$num = $query->num_rows();

		if ($num) {
			return $query->row();
		} else {
			return false;
		}
	}


	function forgetOtp($data)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `email_id` = '" . trim($data['email_id']) . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			$this->db->query("UPDATE `customer` SET `forget_otp` = '" . $data['otp'] . "' WHERE `id` = '" . $row->id . "'");
			return $row->id;
		} else {
			return 0;
		}
	}

	function chkOtp($data)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `id` = '" . trim($data['cust_id']) . "' AND `forget_otp` = '" . trim($data['otp']) . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			return $row->id;
		} else {
			return 0;
		}
	}

	function checkUserExistsWithId($email_id, $id)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `email_id` = '" . trim($email_id) . "' AND `id` != '" . $id . "'");
		return $query->num_rows();
	}


	public function updatePassword($data)
	{
		$this->db->query("UPDATE `customer` SET
		`password` = '" . password_hash($data['password'], PASSWORD_DEFAULT) . "'
		WHERE `id` = '" . $data['cust_id'] . "'");

		$this->db->query("UPDATE `login_data` SET `password` = '" . password_hash($data['password'], PASSWORD_DEFAULT) . "' WHERE `cust_id` = '" . $data['cust_id'] . "'");

		return $data['cust_id'];
	}

	public function updateProfileData($data)
	{
		$this->db->query("UPDATE `customer` SET
		`name` = '" . $data['name'] . "',
		`email_id` = '" . $data['email_id'] . "',
		`age` = '" . $data['age'] . "',
		`height` = '" . $data['height'] . "',
		`weight` = '" . $data['weight'] . "',
		`gender` = '" . $data['gender'] . "',
		`country` = '" . $data['country'] . "'
		WHERE `id` = '" . $data['cust_id'] . "'");

		return $data['cust_id'];
	}

	public function updateFitnessData($data)
	{
		$this->db->query("UPDATE `customer` SET
		`health_problem` = '" . $data['health_problem'] . "',
		`allergies` = '" . $data['allergies'] . "',
		`activity_lavel` = '" . $data['activity_lavel'] . "'
		WHERE `id` = '" . $data['cust_id'] . "'");

		return $data['cust_id'];
	}

	public function setGoal($data)
	{

		$query = $this->db->query("SELECT `id` FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
		$row = $query->row();

		/* `weight_goal` = '" . $data['weight_goal'] . "',
						`week_target` = '" . $data['week_target'] . "',
						`week_calorie_less` = '" . $data['week_calorie_less'] . "',
						`day_less_calorie` = '" . $data['day_less_calorie'] . "', */
		if (isset($row->id) && $row->id > 0) {
			$this->db->query("UPDATE `customer_goal` SET 
						`cust_id` = '" . $data['cust_id'] . "',
						`goal_type` = '" . $data['goal_type'] . "',
						`start_date` = '" . $data['start_date'] . "'
						WHERE `id` = '" . $row->id . "'");
		} else {

			$this->db->query("INSERT INTO `customer_goal` SET 
						`cust_id` = '" . $data['cust_id'] . "',
						`goal_type` = '" . $data['goal_type'] . "',
						`start_date` = '" . $data['start_date'] . "'");
		}

		$query = $this->db->query("SELECT * FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");

		$result =  $query->row();

		return $result;
	}

	public function getGoal($id)
	{
		$query = $this->db->query("SELECT * FROM `customer_goal` WHERE `cust_id` = '" . $id . "'");

		$result =  $query->row();

		return $result;
	}

	public function getAllergies()
	{
		$query = $this->db->query("SELECT `allergies_name` as name, `id` FROM `allergies` WHERE 1 AND `status` = 'Y'");

		return $query->result();
	}

	public function getHealthProblem()
	{
		$query = $this->db->query("SELECT `health_problem_name` as name, `id` FROM `health_problem` WHERE 1 AND `status` = 'Y'");

		return $query->result();
	}

	function setProfilePicture($name, $userid)

	{

		$this->db->query("UPDATE `customer` SET 

						`profile_picture` = '" . $name . "' 

						WHERE `id` = '" . $userid . "'");
	}

	public function getMealByCustDate($data)
	{
		$query = $this->db->query("SELECT sum(t.min_calorie) as total_calorie
		FROM  customer_meal c 
		LEFT JOIN meals t ON t.id = c.meal_id
		WHERE c.cust_id = '" . $data['cust_id'] . "'
		AND c.meal_date = '" . strtotime($data['date']) . "'");

		return $query->row();
	}
}
