<?php
class Workout_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db->query("SET SQL_BIG_SELECTS=1");
		$this->db->query("SET @MAX_QUESTIONS=0");
		$this->db->query("SET SQL_MODE=''");
		date_default_timezone_set('Europe/London');
	}

	public function getWorkoutLevel()
	{
		$query = $this->db->query("SELECT `id` as level_id, `workout_level_name` as name, `workout_level_desc` as description FROM `workout_level` where `status` = 'Y' ORDER BY `id` asc");

		return $query->result();
	}

	public function getWorkoutProgram($data)
	{

		if (isset($data['cust_id']) && $data['cust_id'] > 0) {
			$cust_id = " AND c.cust_id = '" . $data['cust_id'] . "'";
		} else {
			$cust_id = "";
		}
		
		if (isset($data['workout_level_id']) && $data['workout_level_id'] > 0) {
			$workout_level_id = " AND w.workout_level_id = '" . $data['workout_level_id'] . "'";
		} else {
			$workout_level_id = "";
		}

		$query = $this->db->query("SELECT w.id as program_id, 
		w.title as name, 
		w.description as description, 
		w.image, 
		w.no_of_week,
		g.workout_level_name,
		w.workout_level_id,
		(SELECT count(*) as num FROM customer_workout c WHERE c.program_id = w.id" . $cust_id . ") as selected
		 FROM workout_routine w 
		 LEFT JOIN workout_level g on g.id = w.workout_level_id
		 where w.status = 'Y' ".$workout_level_id." ORDER BY w.id asc");

		return $query->result();
	}

	public function getCoaches()
	{
		$query = $this->db->query("SELECT `id` as coach_id, `workout_coach_name` as name, `workout_coach_desc` as description, `coach_image` as image FROM `workout_coach` where `status` = 'Y' ORDER BY `id` asc");

		return $query->result();
	}

	public function getExerciseList($data)
	{
		if (isset($data['program_id']) && $data['program_id'] > 0) {
			$routine_id = " AND w.routine_id = '" . $data['program_id'] . "'";
		} else {
			$routine_id = "";
		}

		if (isset($data['week']) && $data['week'] > 0) {
			$week = " AND w.week_no = '" . $data['week'] . "'";
		} else {
			$week = "";
		}

		if (isset($data['day']) && $data['day'] > 0) {
			$day = " AND w.day_no = '" . $data['day'] . "'";
		} else {
			$day = "";
		}

		$query = $this->db->query("SELECT w.id as exercise_id, 
		w.workout_title as name, 
		w.workout_desc as description, 
		w.image
		FROM workout w where 1 " .  $routine_id . $week .  $day . " AND w.status = 'Y' ORDER BY w.id asc");

		return $query->result();

		return $query->result();
	}

	public function getExerciseListByCoach($data)
	{
		if (isset($data['coach_id']) && $data['coach_id'] > 0) {
			$coach_id = " AND w.coach_id = '" . $data['coach_id'] . "'";
		} else {
			$coach_id = "";
		}

		if (isset($data['cust_id']) && $data['cust_id'] > 0) {
			$cust_id = " AND c.cust_id = '" . $data['cust_id'] . "'";
		} else {
			$cust_id = "";
		}

		if (isset($data['date']) && $data['date'] != '') {
			$date = " AND c.workout_date = '" . strtotime($data['date']) . "'";
		} else {
			$date = "";
		}

		$query = $this->db->query("SELECT w.id as exercise_id, 
		w.workout_title as name, 
		w.workout_desc as description, 
		w.image,
		(SELECT count(*) as num FROM customer_workout c WHERE c.exercise_id = w.id" . $cust_id . $date . ") as selected
		FROM workout w where 1 " .  $coach_id . " AND w.status = 'Y' ORDER BY w.id asc");

		return $query->result();
	}

	public function getExerciseDetails($id)
	{
		$query = $this->db->query("SELECT w.id, 
		w.workout_title as name,
		w.workout_tipe,
		w.workout_desc as description,
		w.equipments,
		w.rep,
		w.sets,
		w.musclegroup,
		w.image,
		w.workout_image_thumb as thumb_image,
		w.video_type,
		w.url_type as video_url_type,
		w.video_path,
		w.workout_level_id as level_id,
		w.routine_id as program_id,
		w.week_no as week,
		w.day_no as day,
		r.title as program_name, 
		l.workout_level_name as level
		FROM workout w
		LEFT JOIN workout_routine r ON r.id = w.routine_id
		LEFT JOIN workout_level l ON l.id = w.workout_level_id
		WHERE w.id = '" . $id . "'
		ORDER BY w.id desc");

		return $query->row();
	}

	public function selectWorkout($data)
	{
		$query = $this->db->query("SELECT count(*) as num FROM `customer_workout` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `program_id` = '" . $data['program_id'] . "'");
		$row = $query->row();

		if (isset($row->num) && $row->num > 0) {
			return 0;
		} else {
			$this->db->query("INSERT INTO `customer_workout` SET 
			`cust_id` = '" . $data['cust_id'] . "',
			`program_id` = '" . $data['program_id'] . "'");

			return 1;
		}
		/* $query = $this->db->query("SELECT count(*) as num FROM `customer_workout` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `workout_date` = '" . strtotime($data['date']) . "' AND `exercise_id` = '" . $data['exercise_id'] . "'");
		$row = $query->row();

		if (isset($row->num) && $row->num > 0) {
			return 0;
		} else {
			$this->db->query("INSERT INTO `customer_workout` SET 
			`cust_id` = '" . $data['cust_id'] . "',
			`workout_date` = '" . strtotime($data['date']) . "',
			`exercise_id` = '" . $data['exercise_id'] . "'");

			return 1;
		} */
	}

	public function removeWorkoutProgram($data)
	{
		/* $query = $this->db->query("SELECT `id` FROM `customer_workout` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `workout_date` = '" . strtotime($data['date']) . "' AND `exercise_id` = '" . $data['exercise_id'] . "'");
		$row = $query->row(); */

		$query = $this->db->query("SELECT `id` FROM `customer_workout` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `program_id` = '" . $data['program_id'] . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			$this->db->query("DELETE FROM `customer_workout` WHERE `id` = '" . $row->id . "'");
			return 1;
		} else {
			return 0;
		}
	}

	public function mySelectedWorkoutProgram($data)
	{
		/* $query = $this->db->query("SELECT w.id as exercise_id, 
		w.workout_title as name, 
		w.workout_desc as description, 
		w.image,
		DATE_FORMAT(FROM_UNIXTIME(c.workout_date), '%d-%m-%Y') AS date_selected
		FROM customer_workout c
		LEFT JOIN workout w ON w.id = c.exercise_id
		where 1 AND c.workout_date = '" . strtotime($data['date']) . "' AND c.cust_id = '" . $data['cust_id'] . "' ORDER BY c.id asc"); */

		$query = $this->db->query("SELECT w.id as program_id, 
		w.title as name, 
		w.description as description, 
		w.image,
		w.no_of_week		
		FROM customer_workout c
		LEFT JOIN workout_routine w ON w.id = c.program_id
		where 1 AND c.cust_id = '" . $data['cust_id'] . "' ORDER BY c.id asc");

		return $query->result();
	}

	public function getProgram($data)
	{
		$query = $this->db->query("SELECT `no_of_week` FROM workout_routine where `id` = '" . $data['program_id'] . "'");
		$row = $query->row();
		$queryCust = $this->db->query("SELECT * FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
		$rowCust = $queryCust->row();

		return array('no_of_week' => $row->no_of_week,'start_date' => $rowCust->start_date);
	}
}
