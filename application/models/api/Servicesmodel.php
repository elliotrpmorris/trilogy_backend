<?php
class Servicesmodel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db->query("SET SQL_BIG_SELECTS=1");
		$this->db->query("SET @MAX_QUESTIONS=0");
		$this->db->query("SET SQL_MODE=''");
		date_default_timezone_set('Europe/London');
	}
	/* GET SIX WEEK BATCH */
	public function getSixWeekBatch()
	{
		/* $query = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `status` = 'Y' ORDER BY `id` desc"); */
		$query = $this->db->query("SELECT batch_name, id, batch_date FROM `six_week_batch` WHERE `status` = 'Y' ORDER BY `id` desc");
		return $query->result();
	}
	function getUserLogin($data)
	{
		/* $query = $this->db->query("SELECT * FROM `customer` where `email_id` = '" . trim($data['email']) . "'"); */
		$query = $this->db->query("SELECT * FROM `login_data` where `email_id` = '" . trim($data['email']) . "'");
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
	function getAdminLogin($data)
	{
		$query = $this->db->query("SELECT * FROM `admin` where `email_id` = '" . trim($data['email']) . "' AND `password` = '" . md5(trim($data['password'])) . "'");
		$num = $query->num_rows();
		if ($num) {
			return $query->row();
		} else {
			return false;
		}
	}
	/* GET ALL Users */
	public function getAllOnetoOneUser()
	{
		/* $data */
		/* avail_id, */
		$query = $this->db->query("SELECT distinct(c.id) as user_id, c.name, c.profile_picture, (SELECT count(*) as num FROM chat_db d WHERE d.user_id = c.id AND d.status = '0') as chatCount, (SELECT chat FROM chat_db d WHERE d.user_id = c.id ORDER BY d.id desc limit 1) as lastmsg FROM customer c
		LEFT JOIN customer_package p ON p.cust_id = c.id
		LEFT JOIN callbackslot s ON s.user_id = c.id
		LEFT JOIN callbackavailabilty a ON a.id = s.avail_id
		WHERE 1 AND p.package_id = 1 AND (SELECT chat FROM chat_db d WHERE d.user_id = c.id ORDER BY d.id desc limit 1) != '' ORDER BY (SELECT d.id FROM chat_db d WHERE d.user_id = c.id AND d.status = '0' ORDER BY d.id desc limit 1) desc");
		/* AND a.available_date = '".$data."' */
		$result = $query->result();
		/* $returnData = array();
		if(isset($result) && !empty($result)){
			foreach($result as $key => $val){
				$returnData[$key]['user_id'] = $val->user_id;
				$returnData[$key]['name'] = $val->name;
				$returnData[$key]['profile_picture'] = $val->profile_picture;
				$returnData[$key]['chatCount'] = $val->chatCount;
				$returnData[$key]['lastmsg'] = ($val->lastmsg != "") ? $val->lastmsg : '';
				$querySlot = $this->db->query("SELECT `slot_start_time`, `slot_end_time` FROM `callbackslot` WHERE `avail_id` = '".$val->avail_id."' AND `user_id` = '".$val->user_id."'");
				$returnData[$key]['slot'] = $querySlot->result();
			}
		} */
		return $result;
	}
	/* UPDATE CHAT STATUS */
	public function updateChatStatus($id)
	{
		$this->db->query("UPDATE `chat_db` SET `status` = '1' WHERE `user_id` = '" . $id . "'");
		return $id;
	}
	/* GET ALL PACKAGES */
	public function getPackages()
	{
		$query = $this->db->query("SELECT * FROM `package_master` WHERE 1 AND `status` = 'Y' ORDER BY `id` asc");
		return $query->result();
	}
	/* REGISTRATION & LOGIN */
	function checkUserExists($email_id)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `email_id` = '" . trim($email_id) . "'");
		return $query->num_rows();
	}
	function checkUserExistsById($id)
	{
		$query = $this->db->query("SELECT `id` FROM `customer` WHERE `id` = '" . $id . "'");
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
	public function setUserPersonalProfile($data)
	{
		$this->db->query("UPDATE `customer` SET
								`age` = '" . $data['age'] . "',
								`height` = '" . $data['height'] . "',
								`weight` = '" . $data['weight'] . "',
								`gender` = '" . $data['gender'] . "',
								`activity_lavel` = '" . $data['activity_lavel'] . "',
								`foodddislike` = '" . $data['foodddislike'] . "',
								`allergies` = '" . $data['allergies'] . "'
								WHERE `id` = '" . $data['cust_id'] . "'");
		return $data['cust_id'];
	}
	/* VALIDATE COUPON */
	public function validateCoupon($data)
	{
		$this->db->from('package_master');
		$this->db->select('*');
		$this->db->where('id', $data['package_id']);
		$queryPack = $this->db->get();
		$packData = $queryPack->row();
		$itemAmount = $packData->package_amt;
		$this->db->from('coupon');
		$this->db->select('*');
		$this->db->where('coupon_code', $data['coupon_code']);
		$query = $this->db->get();
		$returnData = $query->row();
		if (isset($returnData->id) && $returnData->id > 0) {
			if (isset($returnData->expire_date) && time() > $returnData->expire_date) {
				$result['status'] = 0;
				$result['msg'] = "Sorry!! Coupon code expired";
			} elseif (isset($returnData->coupon_avail) && $returnData->coupon_avail > 0) {
				$discount = ($itemAmount * $returnData->coupon_discount) / 100;
				$result['data']['package_amt'] = $itemAmount;
				$result['data']['coupon_discount'] = strval($discount);
				$result['data']['amount'] = strval($itemAmount - $discount);
				$result['status'] = 1;
			} else {
				$result['status'] = 0;
				$result['msg'] = "Sorry!! Coupon code expired";
			}
		} else {
			$result['status'] = 0;
			$result['msg'] = "Please enter valid coupon code";
		}
		return $result;
	}
	function setUserPackage($data)
	{
		$this->db->query("UPDATE `customer` SET
								`country` = '" . $data['country'] . "'
								WHERE `id` = '" . $data['cust_id'] . "'");
		if (isset($data['package_id']) && $data['package_id'] == 2) {
			$queryBatch = $this->db->query("SELECT `id` FROM `six_week_batch` WHERE `status` = 'Y' ORDER BY `id` DESC LIMIT 1");
			$rowBatch = $queryBatch->row();
			if (isset($rowBatch->id) && $rowBatch->id > 0) {
				$batch_id = $rowBatch->id;
			} else {
				$batch_id = 0;
			}
		} else {
			$batch_id = 0;
		}
		$query = $this->db->query("INSERT INTO `customer_package` SET
								`cust_id` = '" . $data['cust_id'] . "',
								`package_id` = '" . $data['package_id'] . "',
								`payment_amt` = '" . $data['payment_amt'] . "',
								`package_amt` = '" . $data['package_amt'] . "',
								`coupon_code` = '" . $data['coupon_code'] . "',
								`coupon_discount` = '" . $data['coupon_discount'] . "',
								`tran_id` = '" . $data['tran_id'] . "',
								`pay_status` = '" . $data['pay_status'] . "',
								`batch_id` = '" . $batch_id . "'");
		$id = $this->db->insert_id();
		return strval($id);
	}
	//================= GET USER PROFILE DATA
	function getUserPersonalProfile($id)
	{
		$query = $this->db->query("SELECT `id`, `user_code`, `name`, `email_id`, `age`, `height`, `weight`, `gender`, `activity_lavel`, `goal`, `allergies`, `foodddislike`, `profile_picture` as image
								FROM `customer`
								WHERE `id` = '" . $id . "'");
		return $query->row();
	}
	/* VALIDATE PACKAGE */
	public function validatePackage()
	{
		$queryBatch = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `batch_date` > '" . strtotime(date('d-m-Y') . ' -5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
		return $queryBatch->row();
	}
	//================= GET USER PACKAGE
	function getUserPackage($id)
	{
		$query = $this->db->query("SELECT c.*,
								p.package_title
								FROM customer_package c
								LEFT JOIN package_master p ON p.id = c.package_id
								WHERE c.pay_status = 'Y'
								AND c.cust_id = '" . $id . "'
								ORDER BY c.id DESC");
		$result = $query->result();
		if (isset($result) && !empty($result)) {
			foreach ($result as $key => $val) {
				if ($val->package_id == 1) {
					$packagseStartDate = date('Y-m-d H:i:s', strtotime($val->payment_date . ' + 2 days'));
				} elseif ($val->package_id == 2) {
					$queryBatch = $this->db->query("SELECT * FROM `six_week_batch` WHERE `batch_date` >= '" . strtotime($val->payment_date . ' - 5 days') . "' AND `status` = 'Y' ORDER BY `id` asc limit 1");
					$rowBatch = $queryBatch->row();
					if (isset($rowBatch->id) && $rowBatch->id > 0) {
						$packagseStartDate = date('Y-m-d 00:00:00', $rowBatch->batch_date);
					} else {
						$packagseStartDate = "";
					}
				} elseif ($val->package_id == 3) {
					$packagseStartDate = date('Y-m-d H:i:s', strtotime($val->payment_date . ' + 2 days'));
				}
				$result[$key]->package_start_date = $packagseStartDate;
			}
		}
		return $result;
	}
	/* CHECK USER PACKAGE START DATE */
	function checkUserPackageStart($data)
	{
		$query = $this->db->query("SELECT c.*,
								p.package_title
								FROM customer_package c
								LEFT JOIN package_master p ON p.id = c.package_id
								WHERE c.pay_status = 'Y'
								AND c.cust_id = '" . $data['cust_id'] . "'
								AND c.package_id = '" . $data['package_id'] . "'
								ORDER BY c.id DESC");
		$result = $query->result();
		if (isset($result) && !empty($result)) {
			foreach ($result as $key => $val) {
				if ($val->package_id == 1) {
					$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
				} elseif ($val->package_id == 2) {
					$queryBatch = $this->db->query("SELECT * FROM `six_week_batch` WHERE `batch_date` >= '" . strtotime($val->payment_date . ' - 5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
					$rowBatch = $queryBatch->row();
					if (isset($rowBatch->id) && $rowBatch->id > 0) {
						$packagseStartDate = $rowBatch->batch_date;
					} else {
						$packagseStartDate = "";
					}
				} elseif ($val->package_id == 3) {
					$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
				}
			}
		} else {
			$packagseStartDate = 0;
		}
		return $packagseStartDate;
	}
	/* PROFILE PICTURE UPLOAD */
	function setProfilePicture($name, $userid)
	{
		$this->db->query("UPDATE `customer` SET 
						`profile_picture` = '" . $name . "' 
						WHERE `id` = '" . $userid . "'");
	}
	/* COMPITITION PIC UPLOAD */
	function setCompetitionPicture($name, $userid, $type)
	{
		if (isset($type) && $type == "before") {
			$this->db->query("UPDATE `customer` SET 
							`competition` = 'Y',
							`before_image` = '" . $name . "' 
							WHERE `id` = '" . $userid . "'");
		} elseif (isset($type) && $type == "after") {
			$this->db->query("UPDATE `customer` SET 
							`competition` = 'Y',
							`after_image` = '" . $name . "' 
							WHERE `id` = '" . $userid . "'");
		}
	}
	/* GET ALL MEAL TYPE */
	public function getAllMealType()
	{
		$query = $this->db->query("SELECT * FROM `meal_type` WHERE 1 AND `status` = 'Y' ORDER BY `id` asc");
		return $query->result();
	}
	public function getAllMealTypeByID($id)
	{
		$query = $this->db->query("SELECT * FROM `meal_type` WHERE 1 AND `status` = 'Y' AND `id` = '" . $id . "'");
		return $query->row();
	}
	/* GET WEEK PER DROP */
	public function getWeekPerDropByWeek($week)
	{
		$query = $this->db->query("SELECT * FROM `week_per_drop` WHERE `week` = '" . $week . "'");
		return $query->row();
	}
	/* GET SIX WEEK USER PACKAGE DATE */
	/* public function customerPackageDateSixWeek($cust_id)
	{
		$query = $this->db->query("SELECT b.batch_end_date, b.batch_date, b.id FROM customer_package p
									LEFT JOIN six_week_batch b ON b.id = p.batch_id
									WHERE p.package_id = '2'
									AND p.cust_id = '" . $cust_id . "'
									ORDER BY p.id DESC limit 1");
		return $query->row();
	} */
	public function customerPackageDateSixWeek($cust_id)
	{
		$queryBatch = $this->db->query("SELECT `id` FROM `six_week_batch` WHERE `batch_date` <= '" . time() . "' AND `batch_end_date` >= '" . time() . "'");
		$rowCurrentBatch = $queryBatch->row();
		if (isset($rowCurrentBatch->id) && $rowCurrentBatch->id > 0) {
			$query = $this->db->query("SELECT b.batch_end_date, b.batch_date, b.id FROM customer_package p
									LEFT JOIN six_week_batch b ON b.id = p.batch_id
									WHERE p.package_id = '2'
									AND p.cust_id = '" . $cust_id . "'
									AND p.batch_id = '" . $rowCurrentBatch->id . "'");
			if ($query->num_rows() > 0) {
				return $query->row();
			} else {
				$query = $this->db->query("SELECT b.batch_end_date, b.batch_date, b.id FROM customer_package p
									LEFT JOIN six_week_batch b ON b.id = p.batch_id
									WHERE p.package_id = '2'
									AND p.cust_id = '" . $cust_id . "'
									ORDER BY p.id DESC limit 1");
				return $query->row();
			}
		} else {
			$query = $this->db->query("SELECT b.batch_end_date, b.batch_date, b.id FROM customer_package p
									LEFT JOIN six_week_batch b ON b.id = p.batch_id
									WHERE p.package_id = '2'
									AND p.cust_id = '" . $cust_id . "'
									ORDER BY p.id DESC limit 1");
			return $query->row();
		}
	}
	/* GET MY ASSIGN MEAL 6 WEEK */
	public function getAssignMealSixWeek($data, $batch_id)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType  = " AND m.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType  = "";
		}
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, m.id as meal_id, m.food_type, m.food_type_id, m.meal_type_id, 
								(SELECT count(*) as num FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.meal_id = m.id AND u.week = '" . $data['week'] . "' AND u.day = '" . $data['day'] . "' AND u.meal_type_id = m.meal_type_id) as selectedMeal 
								FROM meals m
								WHERE m.package_id like '%2%'
								" . $mealType . "
								AND m.batch_id = '" . $batch_id . "'
								AND m.week_id like '%" . $data['week'] . "%'");
		/* AND m.batch_id = '".$batch_id."' */
		return $query->result();
	}
	/* GET MY ASSIGN MEAL 6 WEEK WITH FILTER */
	public function getAssignMealSixWeekFilter($data, $batch_id)
	{
		if (isset($data['foodtype']) && $data['foodtype'] != "") {
			if (isset($data['foodtype']) && $data['foodtype'] == "All") {
				$foodtype = "";
			} else {
				$queryFood = $this->db->query("SELECT `id` FROM `food_type` WHERE lower(food_type_name) = '" . strtolower(trim($data['foodtype'])) . "'");
				$rowFood = $queryFood->row();
				if (isset($rowFood->id) && $rowFood->id > 0) {
					$foodtype = " AND m.food_type_id like '%" . $rowFood->id . "%'";
				} else {
					$foodtype = "";
				}
			}
		} else {
			$foodtype = "";
		}
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType  = " AND m.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType  = "";
		}
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, m.id as meal_id, m.food_type, m.food_type_id, m.meal_type_id,
								(SELECT count(*) as num FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.meal_id = m.id AND u.week = '" . $data['week'] . "' AND u.day = '" . $data['day'] . "' AND u.meal_type_id = m.meal_type_id) as selectedMeal 
								FROM meals m
								WHERE m.package_id like '%2%'
								" . $mealType . "
								AND m.batch_id = '" . $batch_id . "'
								AND m.week_id like '%" . $data['week'] . "%'" . $foodtype);
		/* AND m.batch_id = '".$batch_id."' */
		return $query->result();
	}
	/* GET WORKOUT GROUP */
	public function getWorkOutGroup()
	{
		$query = $this->db->query("SELECT `workout_group_type_name` as group_name, `id` as group_id FROM `workout_group_type` WHERE 1 AND `status` = 'Y'");
		return $query->result();
	}
	/* GET DIETARY */
	public function getDietary()
	{
		$query = $this->db->query("SELECT `food_type_name`, `id` FROM `food_type` WHERE 1 AND `status` = 'Y'");
		return $query->result();
	}
	/* SET SELECTED MEAL */
	public function setSelectedMeal($data)
	{
		$query = $this->db->query("SELECT count(*) as num FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.meal_id = '" . $data['meal_id'] . "' AND u.week = '" . $data['week'] . "' AND u.day = '" . $data['day'] . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
		$row = $query->row();
		if (isset($row->num) && $row->num > 0) {
			return 0;
		} else {
			$queryExist = $this->db->query("SELECT `id` FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.week = '" . $data['week'] . "' AND u.day = '" . $data['day'] . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
			$rowExists = $queryExist->row();
			if (isset($rowExists->id) && $rowExists->id > 0) {
				$this->db->query("UPDATE `user_meal_six_week` SET
								`user_id` = '" . $data['cust_id'] . "',
								`meal_id` = '" . $data['meal_id'] . "',
								`week` = '" . $data['week'] . "',
								`day` = '" . $data['day'] . "',
								`meal_calorie` = '" . $data['mealCalorie'] . "',
								`meal_type_id` = '" . $data['mealTypeId'] . "'
								WHERE `id` = '" . $rowExists->id . "'");
				return $rowExists->id;
			} else {
				$this->db->query("INSERT INTO `user_meal_six_week` SET
								`user_id` = '" . $data['cust_id'] . "',
								`meal_id` = '" . $data['meal_id'] . "',
								`week` = '" . $data['week'] . "',
								`day` = '" . $data['day'] . "',
								`meal_calorie` = '" . $data['mealCalorie'] . "',
								`meal_type_id` = '" . $data['mealTypeId'] . "'");
				return $this->db->insert_id();
			}
		}
	}
	/* SIX WEEK ALL MEAL BY WEEK & MEAL TYPE ID */
	public function getSixWeekAllMealByType($data, $batch_id)
	{
		$query = $this->db->query("SELECT u.day, m.meal_title FROM user_meal_six_week u
									LEFT JOIN meals m ON u.meal_id = m.id
									WHERE u.user_id = '" . $data['cust_id'] . "'
									AND u.meal_type_id = '" . $data['meal_type_id'] . "'
									AND u.week = '" . $data['week'] . "'
									AND m.batch_id = '" . $batch_id . "'");
		return $query->result();
	}
	/* SET SELECTED MEAL SIX WEEK MULTIPLE */
	public function setSelectedMealSixWeekMultiple($data)
	{
		if (isset($data['day']) && !empty($data['day'])) {
			foreach ($data['day'] as $key => $val) {
				$query = $this->db->query("SELECT count(*) as num FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.meal_id = '" . $data['meal_id'] . "' AND u.week = '" . $data['week'] . "' AND u.day = '" . $val . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
				$row = $query->row();
				/* echo $row->num; */
				if (isset($row->num) && $row->num > 0) {
					//do nothing
				} else {
					$queryExist = $this->db->query("SELECT `id` FROM user_meal_six_week u WHERE u.user_id = '" . $data['cust_id'] . "' AND u.week = '" . $data['week'] . "' AND u.day = '" . $val . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
					$rowExists = $queryExist->row();
					if (isset($rowExists->id) && $rowExists->id > 0) {
						$this->db->query("UPDATE `user_meal_six_week` SET
										`user_id` = '" . $data['cust_id'] . "',
										`meal_id` = '" . $data['meal_id'] . "',
										`week` = '" . $data['week'] . "',
										`day` = '" . $val . "',
										`meal_calorie` = '" . $data['mealCalorie'] . "',
										`meal_type_id` = '" . $data['mealTypeId'] . "'
										WHERE `id` = '" . $rowExists->id . "'");
						//return $rowExists->id;
					} else {
						$this->db->query("INSERT INTO `user_meal_six_week` SET
										`user_id` = '" . $data['cust_id'] . "',
										`meal_id` = '" . $data['meal_id'] . "',
										`week` = '" . $data['week'] . "',
										`day` = '" . $val . "',
										`meal_calorie` = '" . $data['mealCalorie'] . "',
										`meal_type_id` = '" . $data['mealTypeId'] . "'");
						//return $this->db->insert_id();
					}
				}
			}
			return 1;
		} else {
			return 0;
		}
	}
	/* GET MEAL RECIPE FOR SIX WEEK USER */
	public function getMealRecipeforSixWeekByUser($data)
	{
		$query = $this->db->query("SELECT m.meal_title, 
								m.meal_desc, 
								m.meal_image, 
								m.prep_time, 
								m.cooking_time, 
								m.cooking_method, 
								m.video_type, 
								m.video_path,
								m.food_type,
								m.food_type_id,
								m.meal_tips
								FROM meals m 
								WHERE m.id = '" . $data['meal_id'] . "'");
		$result['mealData'] = $query->row();
		$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, m.per_of_meal, m.max_amt, m.min_amt
									FROM  meals_ingredient m 
									WHERE m.meal_id = '" . $data['meal_id'] . "'");
		$result['ingredientData'] = $queryIng->result();
		$this->db->from('meals_nutritions');
		$this->db->select('*');
		$this->db->where('meal_id', $data['meal_id']);
		$query = $this->db->get();
		$result['nutriData'] = $query->result();
		$this->db->from('meals_extraingredient');
		$this->db->select('id, extra_ingredient_name as ingredient_name, extra_measure as unit_of_measure, extra_amt as amount');
		$this->db->where('meal_id', $data['meal_id']);
		$query = $this->db->get();
		$result['extraData'] = $query->result();
		return $result;
	}
	/* GET MY SELECTED MEAL FOR SIX WEEK USER */
	public function getMySelectedMealSixWeek($data, $batch_id)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = " AND u.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT  m.meal_title, m.meal_image_thumb, m.id as meal_id, u.meal_calorie, m.food_type, m.meal_type_id
							FROM user_meal_six_week u 
							LEFT JOIN meals m On m.id = u.meal_id
							WHERE u.user_id = '" . $data['cust_id'] . "' 
							AND u.week = '" . $data['week'] . "' 
							AND m.batch_id = '" . $batch_id . "'
							AND u.day = '" . $data['day'] . "'
							" . $mealType);
		/* AND m.batch_id = '".$batch_id."' */
		return $query->result();
	}
	/* GET MY SELECTED MEAL FOR SIX WEEK USER */
	public function getMySelectedMealSixWeekByWeeek($data, $batch_id)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = " AND u.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT  m.meal_title, m.meal_image_thumb, m.id as meal_id, u.meal_calorie, m.food_type
							FROM user_meal_six_week u 
							LEFT JOIN meals m On m.id = u.meal_id
							WHERE u.user_id = '" . $data['cust_id'] . "' 
							AND u.week = '" . $data['week'] . "' 
							AND m.batch_id = '" . $batch_id . "'
							" . $mealType);
		/* AND m.batch_id = '".$batch_id."' */
		return $query->result();
	}
	/* GET ALL DATE FOR ONE TO ONE BY CUST ID */
	public function getOnetoOneMaster($id)
	{
		$query = $this->db->query("SELECT `one_meal_date`, `week_calorie`, `id` FROM `user_meal_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '1'");
		return $query->result();
	}
	/* GET ASSIGN MEAL ONE TO ONE */
	public function getAssignMealOnetoOne($data)
	{
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, u.selectedmeal, u.meal_day_id, u.id as user_meal_id, u.meal_intake_calorie as calorie, m.id as meal_id FROM 
								user_meal_master mm
								LEFT JOIN user_meal_master_day d ON d.meal_master_id = mm.id
								LEFT JOIN user_meal u ON u.meal_day_id = d.id
								LEFT JOIN meals m ON m.id = u.meal_id
								WHERE mm.user_id = '" . $data['cust_id'] . "'
								AND mm.package_id = '1'
								AND mm.id = '" . $data['masterId'] . "'
								AND d.meal_type_id = '" . $data['mealTypeId'] . "'");
		return $query->result();
	}
	/* GET MEAL RECIPE ONE TO ONE*/
	public function getMealRecipeOnetoOne($data)
	{
		$query = $this->db->query("SELECT m.meal_title, 
								m.meal_desc, 
								m.meal_image, 
								m.prep_time, 
								m.cooking_time, 
								m.cooking_method, 
								m.video_type, 
								m.video_path, 
								u.meal_intake_calorie
								FROM meals m 
								LEFT JOIN user_meal u ON u.meal_id = m.id
								WHERE u.id = '" . $data['user_meal_id'] . "'");
		$result['mealData'] = $query->row();
		$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, u.ingredient_amt, u.ingredient_intake_calorie FROM  user_meal_ingredients u 
								LEFT JOIN meals_ingredient m ON m.id = u.ingredient_id
								WHERE u.user_meal_id = '" . $data['user_meal_id'] . "'");
		$result['ingredientData'] = $queryIng->result();
		$this->db->from('meals_nutritions');
		$this->db->select('*');
		$this->db->where('meal_id', $data['meal_id']);
		$query = $this->db->get();
		$result['nutriData'] = $query->result();
		return $result;
	}
	/* GET CALLBACK AVAILABLITY */
	public function getCallbackAvailability($date)
	{
		$query = $this->db->query("SELECT c.*
		FROM callbackavailabilty c 
		WHERE 1 
		AND c.available_date = '" . $date . "'
		ORDER BY c.available_date asc");
		return $query->result();
	}
	/* GET CALLBACK SLOTS */
	public function getCallbackSlots($date)
	{
		$query = $this->db->query("SELECT s.id as slot_id, s.slot_start_time, s.slot_end_time, s.status, c.available_date
		FROM callbackslot s
		LEFT JOIN callbackavailabilty c ON c.id = s.avail_id
		WHERE c.available_date = '" . $date . "'
		AND s.status = 'Y'
		ORDER BY s.id asc");
		$result['slotData'] = $query->result();
		return $result;
	}
	/* SET CALLBACK BOOK */
	public function setCallback($data)
	{
		$query = $this->db->query("SELECT `status`
		FROM callbackslot c 
		WHERE c.id = '" . $data['slot_id'] . "'");
		$row = $query->row();
		if (isset($row->status) && $row->status == 'Y') {
			$this->db->query("UPDATE `callbackslot` SET `user_id` = '" . $data['cust_id'] . "', `status` = 'N', `book_date` = '" . time() . "' WHERE `id` = '" . $data['slot_id'] . "'");
			return 3;
		} else {
			return 1;
		}
	}
	/* GET MY CALLBACK BOOKINGS */
	public function getMyCallback($data)
	{
		$query = $this->db->query("SELECT c.id as slot_id, c.slot_start_time, c.slot_end_time, DATE_FORMAT(FROM_UNIXTIME(c.book_date), '%d-%m-%Y') AS date, b.available_date
		FROM callbackavailabilty b 
		LEFT JOIN callbackslot c ON c.avail_id = b.id
		WHERE c.user_id = '" . $data['cust_id'] . "'");
		return $query->result();
	}
	/* GET & SET CHAT DATA */
	public function getChatData($id)
	{
		$query = $this->db->query("SELECT `chat` as message, `sender`, CAST(chat_date AS DATE) AS date, CAST(chat_date AS TIME) AS time FROM `chat_db` WHERE `user_id` = '" . $id . "'");
		return $query->result();
	}
	public function setChatData($data)
	{
		$this->db->query("INSERT INTO `chat_db` SET
						`sender` = '" . $data['sender'] . "',
						`user_id` = '" . $data['cust_id'] . "',
						`chat` = '" . $data['message'] . "'");
		$id = $this->db->insert_id();
		return strval($id);
	}
	/* GET WORKOUT ROUTINE FOR 6 WEEK BY WEEK & DAY */
	public function getWorkoutSixWeek($data)
	{
		$query = $this->db->query("SELECT r.id as workout_id, r.title, r.max_time, r.image, r.musclegroup FROM  
						workout_routine r
						WHERE r.package_id = '2'
						AND r.week_id like '%" . $data['week'] . "%' 
						AND `workout_group_type_id` = '" . $data['workout_group'] . "'");
		return $query->result();
	}
	/* SIX WEEK TRAINER LIST */
	public function getTrainersSixWeek()
	{
		$query = $this->db->query("SELECT `id` as trainer_id, `trainer_name`, `trainer_experties`, `profile_image` FROM `sixweek_trainer` WHERE 1 AND `status` = 'Y'");
		return $query->result();
	}
	/* GET TRAINER WORKOUT LIST FOR SIX WEEK */
	public function getTrainersWorkoutSixWeek($data)
	{
		$query = $this->db->query("SELECT w.*, t.trainer_name, 
								(SELECT count(*) as num FROM user_workout_trainer u WHERE u.workout_id = w.id AND u.cust_id = '" . $data['cust_id'] . "') as selectedWorkout
								FROM sixweek_trainer_workout w
								LEFT JOIN sixweek_trainer t ON t.id = w.trainer_id
								WHERE w.status = 'Y' 
								AND w.trainer_id = '" . $data['trainer_id'] . "'
								ORDER BY w.id desc");
		return $query->result();
	}
	public function setTrainersWorkoutSixWeekFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_trainer WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			//do nothing
		} else {
			$this->db->query("INSERT INTO `user_workout_trainer` SET 
							`workout_id` = '" . $data['workout_id'] . "',
							`cust_id` = '" . $data['cust_id'] . "',
							`trainer_id` = '" . $data['trainer_id'] . "'");
			return $this->db->insert_id();
		}
	}
	public function removeTrainersWorkoutSixWeekFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_trainer WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			$this->db->query("DELETE FROM `user_workout_trainer` WHERE 
							`workout_id` = '" . $data['workout_id'] . "'
							AND `cust_id` = '" . $data['cust_id'] . "'
							AND `trainer_id` = '" . $data['trainer_id'] . "'");
			return $rowChk->num;
		}
	}
	public function getTrainersWorkoutSixWeekFavoriteList($id)
	{
		/* w.id as workout_id, w.trainer_id, w.workout_title, t.trainer_name  */
		$query = $this->db->query("SELECT w.*, t.trainer_name 
									FROM user_workout_trainer u 
									LEFT JOIN sixweek_trainer_workout w ON u.workout_id = w.id AND u.trainer_id = w.trainer_id
									LEFT JOIN sixweek_trainer t ON t.id = w.trainer_id
									WHERE u.cust_id = '" . $id . "'
									ORDER BY w.id desc");
		return $query->result();
	}
	/* GET HOME WORKOUT LIST FOR SIX WEEK */
	public function getHomeWorkoutSixWeek($data)
	{
		$query = $this->db->query("SELECT w.*,
								(SELECT count(*) as num FROM user_workout_home u WHERE u.workout_id = w.id AND u.cust_id = '" . $data['cust_id'] . "') as selectedWorkout
								FROM sixweek_home_workout w
								WHERE w.status = 'Y' 
								ORDER BY w.id desc");
		return $query->result();
	}
	public function setHomeWorkoutSixWeekFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_home WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			//do nothing
		} else {
			$this->db->query("INSERT INTO `user_workout_home` SET 
							`workout_id` = '" . $data['workout_id'] . "',
							`cust_id` = '" . $data['cust_id'] . "'");
			return $this->db->insert_id();
		}
	}
	public function removeHomeWorkoutSixWeekFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_home WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			$this->db->query("DELETE FROM `user_workout_home` WHERE 
							`workout_id` = '" . $data['workout_id'] . "'
							AND `cust_id` = '" . $data['cust_id'] . "'");
			return $rowChk->num;
		}
	}
	public function getHomeWorkoutSixWeekFavoriteList($id)
	{
		/* w.id as workout_id, w.trainer_id, w.workout_title, t.trainer_name  */
		$query = $this->db->query("SELECT w.*
									FROM user_workout_home u 
									LEFT JOIN sixweek_home_workout w ON u.workout_id = w.id
									WHERE u.cust_id = '" . $id . "'
									ORDER BY w.id desc");
		return $query->result();
	}
	/* SET WORKOUT SELECTED FOR 6 WEEK */
	/* public function setSelectedWorkout($data)
	{
		$this->db->query("UPDATE `user_workout` SET `selectedworkout` = 'N' WHERE `workout_day_id` = '" . $data['workout_day_id'] . "'");
		$this->db->query("UPDATE `user_workout` SET `selectedworkout` = 'Y' WHERE `id` = '" . $data['user_workout_id'] . "'");
		return $data['user_workout_id'];
	} */
	/* GET EXERCISE LIST BY WORKOUT ROUTINE ID */
	public function getExerciseList($data)
	{
		$query = $this->db->query("SELECT w.id as excerciseId, 
		w.workout_title, 
		w.workout_image_thumb, 
		w.rep, 
		w.sets,
		(SELECT count(*) as num FROM user_workout_gym u WHERE u.excerciseId = w.id AND u.cust_id = '" . $data['cust_id'] . "') as selectedexercise
		FROM workout w 
		WHERE w.routine_id = '" . $data['workout_id'] . "' 
		AND w.status = 'Y'");
		return $query->result();
	}
	/* GET EXERCISE DETAILS BY ID */
	public function getExerciseDetails($data)
	{
		$query = $this->db->query("SELECT * FROM `workout` WHERE `id` = '" . $data['excerciseId'] . "' AND `status` = 'Y'");
		return $query->row();
	}
	/* SET EXERCISE TO FAVORITE */
	public function setExerciseToFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_gym WHERE excerciseId = '" . $data['excerciseId'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			//do nothing
		} else {
			$this->db->query("INSERT INTO `user_workout_gym` SET 
							`excerciseId` = '" . $data['excerciseId'] . "',
							`cust_id` = '" . $data['cust_id'] . "'");
			return $this->db->insert_id();
		}
	}
	/* REMOVE FROM MY WORKOUT LIST GYM*/
	public function removeExerciseFromFavorite($data)
	{
		$this->db->query("DELETE FROM `user_workout_gym` WHERE `cust_id` = '" . $data['cust_id'] . "' AND `excerciseId` = '" . $data['excerciseId'] . "'");
		return 1;
	}
	/* MY FAVORITE EXCERIE GYM */
	public function getGymWorkoutSixWeekFavoriteList($id)
	{
		$query = $this->db->query("SELECT w.*
									FROM user_workout_gym u 
									LEFT JOIN workout w ON w.id = u.excerciseId
									WHERE u.cust_id = '" . $id . "'
									ORDER BY w.id desc");
		return $query->result();
	}
	/* GET ONE TO ONE WEEK DATA FRO WORKOUT */
	public function getWeekDataOnetoOneForWorkout($id)
	{
		$query = $this->db->query("SELECT `one_workout_date`, `id` FROM `user_workout_master` WHERE `user_id` = '" . $id . "' AND `package_id` = '1'");
		return $query->result();
	}
	/* ONE TO ONE WORK OUT LIST FETCH */
	public function getOnetoOneWorkoutList($data)
	{
		$query = $this->db->query("SELECT r.id as workout_id, r.title, r.max_time, r.image, r.musclegroup FROM  
						workout_routine r
						LEFT JOIN  user_workout u ON u.routine_id = r.id
						LEFT JOIN  user_workout_master_day d ON u.workout_day_id = d.id
						LEFT JOIN  user_workout_master m ON d.workout_master_id = m.id
						WHERE m.user_id = '" . $data['cust_id'] . "'
						AND m.id = '" . $data['masterId'] . "'");
		return $query->result();
	}
	/* LIFESTYLE START */
	/* UPLOAD LIFE STYLE GOAL SETTINGS */
	public function setLifeStyleGoal($data)
	{
		$query = $this->db->query("SELECT `id` FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
		$row = $query->row();
		if (isset($row->id) && $row->id > 0) {
			$this->db->query("UPDATE `customer_goal` SET 
						`cust_id` = '" . $data['cust_id'] . "',
						`weight_goal` = '" . $data['weight_goal'] . "',
						`week_target` = '" . $data['week_target'] . "',
						`week_calorie_less` = '" . $data['week_calorie_less'] . "',
						`day_less_calorie` = '" . $data['day_less_calorie'] . "',
						`start_date` = '" . $data['start_date'] . "'
						WHERE `id` = '" . $row->id . "'");
			return $row->id;
		} else {
			$this->db->query("INSERT INTO `customer_goal` SET 
						`cust_id` = '" . $data['cust_id'] . "',
						`weight_goal` = '" . $data['weight_goal'] . "',
						`week_target` = '" . $data['week_target'] . "',
						`week_calorie_less` = '" . $data['week_calorie_less'] . "',
						`day_less_calorie` = '" . $data['day_less_calorie'] . "',
						`start_date` = '" . $data['start_date'] . "'");
			return $this->db->insert_id();
		}
	}
	/* END LIFE STYLE GOAL */
	public function endLifeStyleGoal($id)
	{
		$query = $this->db->query("SELECT `id` FROM `customer_goal` WHERE `cust_id` = '" . $id . "'");
		$row = $query->row();
		if (isset($row->id) && $row->id > 0) {
			$this->db->query("DELETE FROM `customer_goal` WHERE `cust_id` = '" . $id . "'");
			$this->db->query("DELETE FROM `user_meal_lifestyle` WHERE `cust_id` = '" . $id . "'");
			$this->db->query("DELETE FROM `user_workout_home_lifestyle` WHERE `cust_id` = '" . $id . "'");
			$this->db->query("DELETE FROM `user_workout_lifestyle` WHERE `cust_id` = '" . $id . "'");
			return 1;
		} else {
			return 0;
		}
	}
	/* GET LIFE STYLE GOAL */
	public function getLifeStyleGoal($data)
	{
		$query = $this->db->query("SELECT * FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
		$result =  $query->row();
		if (isset($result->weight_goal)) {
			if ($result->weight_goal > 0) {
				$result->weight_type = 'loose_weight';
			} else {
				$result->weight_type = 'maintain_weight';
			}
		}
		return $result;
	}
	/* GET LIFESTYLE MEAL BY ID */
	public function getLifeStyleMealByTypeWeekDay($data)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = " AND m.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, m.id as meal_id, m.food_type, m.food_type_id, m.meal_type_id,
								(SELECT count(*) as num FROM user_meal_lifestyle u WHERE u.cust_id = '" . $data['cust_id'] . "' AND u.meal_id = m.id AND u.meal_type_id = '" . $data['mealTypeId'] . "' AND u.week_id = '" . $data['week_id'] . "' AND u.day_id = '" . $data['day_id'] . "') as selectedMeal 
								FROM meals m
								WHERE 1 AND m.status = 'Y' AND m.package_id like '%3%' " . $mealType);
		/* AND m.package_id like '%3%' */
		return $query->result();
	}
	/* SET LIFESTYLE MY SELECTED MEAL */
	public function setLifeStyleMealByType($data)
	{
		$queryExist = $this->db->query("SELECT `id` FROM user_meal_lifestyle u WHERE u.cust_id = '" . $data['cust_id'] . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "' AND u.meal_id = '" . $data['meal_id'] . "' AND u.week_id = '" . $data['week_id'] . "' AND u.day_id = '" . $data['day_id'] . "'");
		$rowExists = $queryExist->row();
		if (isset($rowExists->id) && $rowExists->id > 0) {
			return $rowExists->id;
		} else {
			$query = $this->db->query("SELECT `id` FROM user_meal_lifestyle u WHERE u.cust_id = '" . $data['cust_id'] . "' AND u.week_id = '" . $data['week_id'] . "' AND u.day_id = '" . $data['day_id'] . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
			$row = $query->row();
			if (isset($row->id) && $row->id > 0) {
				$this->db->query("UPDATE `user_meal_lifestyle` SET
								`cust_id` = '" . $data['cust_id'] . "',
								`meal_id` = '" . $data['meal_id'] . "',
								`meal_type_id` = '" . $data['mealTypeId'] . "',
								`week_id` = '" . $data['week_id'] . "',
								`day_id` = '" . $data['day_id'] . "'
								WHERE `id` = '" . $row->id . "'");
				return $row->id;
			} else {
				$this->db->query("INSERT INTO `user_meal_lifestyle` SET
								`cust_id` = '" . $data['cust_id'] . "',
								`meal_id` = '" . $data['meal_id'] . "',
								`meal_type_id` = '" . $data['mealTypeId'] . "',
								`week_id` = '" . $data['week_id'] . "',
								`day_id` = '" . $data['day_id'] . "'");
				return $this->db->insert_id();
			}
		}
	}
	/* SET LIFESTYLE MY SELECTED MEAL */
	public function setLifeStyleMultipleMealByType($data)
	{
		if (isset($data['day_id']) && !empty($data['day_id'])) {
			foreach ($data['day_id'] as $key => $val) {
				$query = $this->db->query("SELECT `id` FROM user_meal_lifestyle u WHERE u.cust_id = '" . $data['cust_id'] . "' AND u.week_id = '" . $data['week_id'] . "' AND u.day_id = '" . $val . "' AND u.meal_type_id = '" . $data['mealTypeId'] . "'");
				$row = $query->row();
				if (isset($row->id) && $row->id > 0) {
					$this->db->query("UPDATE `user_meal_lifestyle` SET
										`cust_id` = '" . $data['cust_id'] . "',
										`meal_id` = '" . $data['meal_id'] . "',
										`meal_type_id` = '" . $data['mealTypeId'] . "',
										`week_id` = '" . $data['week_id'] . "',
										`day_id` = '" . $val . "'
										WHERE `id` = '" . $row->id . "'");
				} else {
					$this->db->query("INSERT INTO `user_meal_lifestyle` SET
										`cust_id` = '" . $data['cust_id'] . "',
										`meal_id` = '" . $data['meal_id'] . "',
										`meal_type_id` = '" . $data['mealTypeId'] . "',
										`week_id` = '" . $data['week_id'] . "',
										`day_id` = '" . $val . "'");
				}
			}
			return 1;
		} else {
			return 0;
		}
	}
	/* GET LIFESTYLE MY SELECTED MEAL */
	public function getLifeStyleMySelectedMeal($data)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = "AND u.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, m.id as meal_id, m.food_type, m.food_type_id, m.meal_type_id
								FROM user_meal_lifestyle u
								LEFT JOIN meals m ON m.id = u.meal_id
								WHERE 1
								" . $mealType . "
								AND u.cust_id = '" . $data['cust_id'] . "'
								AND u.week_id = '" . $data['week_id'] . "' 
								AND u.day_id = '" . $data['day_id'] . "'");
		return $query->result();
	}
	/* GET LIFESTYLE MY SELECTED MEAL ALL WEEK */
	public function getLifeStyleMySelectedMealWeekAll($data)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = "AND u.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT m.meal_title, u.day_id
								FROM user_meal_lifestyle u
								LEFT JOIN meals m ON m.id = u.meal_id
								WHERE 1
								" . $mealType . "
								AND u.cust_id = '" . $data['cust_id'] . "'
								AND u.week_id = '" . $data['week_id'] . "'");
		return $query->result();
	}
	public function getLifeStyleMySelectedMealWeek($data)
	{
		if (isset($data['mealTypeId']) && $data['mealTypeId'] > 0) {
			$mealType = "AND u.meal_type_id = '" . $data['mealTypeId'] . "'";
		} else {
			$mealType = "";
		}
		$query = $this->db->query("SELECT m.meal_title, m.meal_image_thumb, m.id as meal_id, m.food_type, m.food_type_id, m.meal_type_id
								FROM user_meal_lifestyle u
								LEFT JOIN meals m ON m.id = u.meal_id
								WHERE 1
								" . $mealType . "
								AND u.cust_id = '" . $data['cust_id'] . "'
								AND u.week_id = '" . $data['week_id'] . "'");
		return $query->result();
	}
	public function removeLifeStyleMySelectedMeal($data)
	{
		$query = $this->db->query("DELETE
								FROM user_meal_lifestyle
								WHERE `meal_type_id` = '" . $data['mealTypeId'] . "' 
								AND `cust_id` = '" . $data['cust_id'] . "'
								AND `meal_id` = '" . $data['meal_id'] . "'
								AND `week_id` = '" . $data['week_id'] . "' 
								AND `day_id` = '" . $data['day_id'] . "'");
		return true;
	}
	/* LIFESTYLE USER MEAL CALORIE CALULATION START */
	/* GET MEAL TYPE BY ID */
	public function getMealTypeById($id)
	{
		$query = $this->db->query("SELECT * FROM `meal_type` WHERE `id` = '" . $id . "'");
		return $query->row();
	}
	/* GET MEAL DETAILS */
	public function getLifeStyleMealDetailsByID($data)
	{
		$query = $this->db->query("SELECT m.meal_title, 
								m.meal_desc, 
								m.meal_image, 
								m.prep_time, 
								m.cooking_time, 
								m.cooking_method, 
								m.video_type, 
								m.video_path,
								m.food_type,
								m.food_type_id,
								m.meal_tips
								FROM meals m 
								WHERE m.id = '" . $data['meal_id'] . "'");
		$result['mealData'] = $query->row();
		$queryIng = $this->db->query("SELECT m.ingredient_name, m.calorie_per_gm, m.unit_of_measure, m.per_of_meal, m.max_amt, m.min_amt
									FROM  meals_ingredient m 
									WHERE m.meal_id = '" . $data['meal_id'] . "'");
		$result['ingredientData'] = $queryIng->result();
		$this->db->from('meals_nutritions');
		$this->db->select('*');
		$this->db->where('meal_id', $data['meal_id']);
		$query = $this->db->get();
		$result['nutriData'] = $query->result();
		$this->db->from('meals_extraingredient');
		$this->db->select('id, extra_ingredient_name as ingredient_name, extra_measure as unit_of_measure, extra_amt as amount');
		$this->db->where('meal_id', $data['meal_id']);
		$query = $this->db->get();
		$result['extraData'] = $query->result();
		return $result;
	}
	/* LIFESTYLE USER MEAL CALORIE CALULATION END */
	/* GET TRAINERS LIST */
	public function getTrainersList()
	{
		$query = $this->db->query("SELECT `id` as trainer_id, `trainer_name`, `trainer_experties`, `profile_image` FROM `trainer` WHERE 1 AND `status` = 'Y'");
		return $query->result();
	}
	/* GET TRAINER WORKOUT ROUTINE */
	public function getTrainersWorkoutRoutines($data)
	{
		$query = $this->db->query("SELECT r.id as workout_id, r.title, r.max_time, r.image, r.musclegroup, r.trainer_id FROM  
						trainer_workout_routine r
						WHERE 1 AND r.status = 'Y'
						AND r.trainer_id = '" . $data['trainer_id'] . "'");
		return $query->result();
	}
	/* SELECTE WORKOUT */
	public function setTrainerWorkoutasSelected($data)
	{
		$query = $this->db->query("SELECT `id` FROM `user_workout_lifestyle` 
		WHERE `cust_id` = '" . $data['cust_id'] . "' 
		AND `trainer_id` = '" . $data['trainer_id'] . "' 
		AND `workout_id` = '" . $data['exercise_id'] . "'");
		$row = $query->row();
		if (isset($row->id) && $row->id > 0) {
			return 'no';
		} else {
			$this->db->query("INSERT INTO `user_workout_lifestyle` SET 
							`cust_id` = '" . $data['cust_id'] . "',
							`trainer_id` = '" . $data['trainer_id'] . "',
							`workout_id` = '" . $data['exercise_id'] . "'");
			return $this->db->insert_id();
		}
	}
	/* GET MY SELECTED WORKOUT LIST */
	public function getMySelectedWorkoutList($data)
	{
		$query = $this->db->query("SELECT tw.*, u.workout_id, u.trainer_id, t.trainer_name, t.profile_image, r.title, r.max_time, r.image, r.musclegroup FROM 
								user_workout_lifestyle u
								LEFT JOIN trainer_workout tw  ON tw.id = u.workout_id
								LEFT JOIN trainer_workout_routine r ON r.id = tw.routine_id
								LEFT JOIN trainer t ON t.id = u.trainer_id
								WHERE u.cust_id = '" . $data['cust_id'] . "'");
		return $query->result();
	}
	/* REMOVE FROM MY WORKOUT LIST */
	public function removeWorkoutFromMySelectedList($data)
	{
		$this->db->query("DELETE FROM `user_workout_lifestyle` WHERE `cust_id` = '" . $data['cust_id'] . "' AND `trainer_id` = '" . $data['trainer_id'] . "' AND `workout_id` = '" . $data['exercise_id'] . "'");
		return 1;
	}
	/* GET EXERCISE LIST */
	public function getExerciseListByWorkoutId($data)
	{
		if (isset($data['workout_id']) && $data['workout_id'] > 0) {
			$routine = "AND t.routine_id = '" . $data['workout_id'] . "'";
		} else {
			$routine = "";
		}
		$query = $this->db->query("SELECT t.id as excerciseId, t.workout_title, t.workout_image_thumb, t.rep, t.sets, t.video_type, t.url_type, t.video_path,
		(SELECT count(*) as num FROM user_workout_lifestyle u WHERE u.cust_id = '" . $data['cust_id'] . "' AND u.workout_id = t.id) as selectedWorkout
		FROM trainer_workout t WHERE 1 " . $routine . " 
		AND t.status = 'Y'");
		return $query->result();
	}
	/* GET EXERCISE DETAILS BY ID */
	public function getTrainerExerciseDetails($id)
	{
		$query = $this->db->query("SELECT * FROM `trainer_workout` WHERE `id` = '" . $id . "' AND `status` = 'Y'");
		return $query->row();
	}
	/* delete account  */
	public function deleteAccount($id)
	{
		$this->db->query("DELETE FROM `customer` WHERE `id` = '" . $id . "'");
		$this->db->query("DELETE FROM `login_data` WHERE `cust_id` = '" . $id . "'");
		$this->db->query("DELETE FROM `customer_package` WHERE `cust_id` = '" . $id . "'");
		return '1';
	}
	/* GET HOME WORKOUT LIST FOR LIFE STYLE */
	public function getGymWorkoutRoutineLifeStyle($data)
	{
		$query = $this->db->query("SELECT w.*
								FROM  lifestyle_gym_workout_routine w
								WHERE w.status = 'Y' 
								ORDER BY w.id desc");
		return $query->result();
	}
	/* GET HOME WORKOUT LIST FOR LIFE STYLE */
	public function getGymWorkoutLifeStyle($data)
	{
		$query = $this->db->query("SELECT w.*, r.title as routine_name,
								(SELECT count(*) as num FROM user_workout_home_lifestyle u WHERE u.workout_id = w.id AND u.cust_id = '" . $data['cust_id'] . "') as selectedWorkout
								FROM  lifestyle_gym_workout w
								LEFT JOIN  lifestyle_gym_workout_routine r ON r.id = w.routine_id
								WHERE w.status = 'Y' 
								AND w.routine_id = '" . $data['routine_id'] . "'
								ORDER BY w.id desc");
		return $query->result();
	}
	public function setGymWorkoutLifeStyleFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_home_lifestyle WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			//do nothing
		} else {
			$this->db->query("INSERT INTO `user_workout_home_lifestyle` SET 
							`workout_id` = '" . $data['workout_id'] . "',
							`cust_id` = '" . $data['cust_id'] . "'");
			return $this->db->insert_id();
		}
	}
	public function removeGymWorkoutLifeStyleFavorite($data)
	{
		$queryChk = $this->db->query("SELECT count(*) as num FROM user_workout_home_lifestyle WHERE workout_id = '" . $data['workout_id'] . "' AND cust_id = '" . $data['cust_id'] . "'");
		$rowChk = $queryChk->row();
		if (isset($rowChk->num) && $rowChk->num > 0) {
			$this->db->query("DELETE FROM `user_workout_home_lifestyle` WHERE 
							`workout_id` = '" . $data['workout_id'] . "'
							AND `cust_id` = '" . $data['cust_id'] . "'");
			return $rowChk->num;
		}
	}
	public function getGymWorkoutLifeStyleFavoriteList($id)
	{
		/* w.id as workout_id, w.trainer_id, w.workout_title, t.trainer_name  */
		$query = $this->db->query("SELECT w.*, r.title as routint_name
									FROM user_workout_home_lifestyle u 
									LEFT JOIN  lifestyle_gym_workout w ON u.workout_id = w.id
									LEFT JOIN  lifestyle_gym_workout_routine r ON r.id = w.routine_id
									WHERE u.cust_id = '" . $id . "'
									ORDER BY w.id desc");
		return $query->result();
	}
	/* SET WORKOUT SELECTED FOR LIFE STYLE */
}
