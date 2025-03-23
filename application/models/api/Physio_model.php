<?php
class Physio_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db->query("SET SQL_BIG_SELECTS=1");
		$this->db->query("SET @MAX_QUESTIONS=0");
		$this->db->query("SET SQL_MODE=''");
		date_default_timezone_set('Europe/London');
	}

	public function getPhysioProgram($data)
	{

		if (isset($data['cust_id']) && $data['cust_id'] > 0) {
			$cust_id = " AND c.cust_id = '" . $data['cust_id'] . "'";
		} else {
			$cust_id = "";
		}

		$query = $this->db->query("SELECT w.id as program_id, 
		w.title as name, 
		w.description as description, 
		w.image, 
		w.no_of_week,
		(SELECT count(*) as num FROM customer_physio c WHERE c.program_id = w.id" . $cust_id . ") as selected
		 FROM physio_program w where w.status = 'Y' ORDER BY `id` asc");

		return $query->result();
	}

	public function getExerciseList($data)
	{
		if (isset($data['program_id']) && $data['program_id'] > 0) {
			$program_id = " AND w.program_id = '" . $data['program_id'] . "'";
		} else {
			$program_id = "";
		}

		if (isset($data['week']) && $data['week'] > 0) {
			$week = " AND w.week_no = '" . $data['week'] . "'";
		} else {
			$week = "";
		}

		/*if (isset($data['day']) && $data['day'] > 0) {
			$day = " AND w.day_no = '" . $data['day'] . "'";
		} else {*/
			$day = "";
		//}

		$query = $this->db->query("SELECT w.id as exercise_id, 
		w.title as name, 
		w.description as description, 
		w.image
		FROM physio_exercise w where 1 " .  $program_id . $week .  $day . " AND w.status = 'Y' ORDER BY w.id asc");

		return $query->result();

		return $query->result();
	}

	public function getExerciseDetails($id)
	{
		//w.day_no as day,
		$query = $this->db->query("SELECT w.id, 
		w.title as name,
		w.tipes,
		w.description as description,
		w.equipments,
		w.rep,
		w.sets,
		w.worktime,
		w.musclegroup,
		w.image,
		w.image_thumb as thumb_image,
		w.video_type,
		w.url_type as video_url_type,
		w.video_path,
		w.program_id as program_id,
		w.week_no as week,
		r.title as program_name
		FROM physio_exercise w
		LEFT JOIN physio_program r ON r.id = w.program_id
		WHERE w.id = '" . $id . "'
		ORDER BY w.id desc");

		return $query->row();
	}

	public function selectPhysioProgram($data)
	{
		$query = $this->db->query("SELECT count(*) as num FROM `customer_physio` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `program_id` = '" . $data['program_id'] . "'");
		$row = $query->row();

		if (isset($row->num) && $row->num > 0) {
			return 0;
		} else {
			$this->db->query("INSERT INTO `customer_physio` SET 
			`cust_id` = '" . $data['cust_id'] . "',
			`program_id` = '" . $data['program_id'] . "'");

			return 1;
		}
		/* $query = $this->db->query("SELECT count(*) as num FROM `customer_physio` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `physio_date` = '" . strtotime($data['date']) . "' AND `exercise_id` = '" . $data['exercise_id'] . "'");
		$row = $query->row();

		if (isset($row->num) && $row->num > 0) {
			return 0;
		} else {
			$this->db->query("INSERT INTO `customer_physio` SET 
			`cust_id` = '" . $data['cust_id'] . "',
			`physio_date` = '" . strtotime($data['date']) . "',
			`exercise_id` = '" . $data['exercise_id'] . "'");

			return 1;
		} */
	}

	public function removePhysioProgram($data)
	{
		/* $query = $this->db->query("SELECT `id` FROM `customer_physio` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `physio_date` = '" . strtotime($data['date']) . "' AND `exercise_id` = '" . $data['exercise_id'] . "'");
		$row = $query->row(); */

		$query = $this->db->query("SELECT `id` FROM `customer_physio` WHERE 1 AND `cust_id` = '" . $data['cust_id'] . "' AND `program_id` = '" . $data['program_id'] . "'");
		$row = $query->row();

		if (isset($row->id) && $row->id > 0) {
			$this->db->query("DELETE FROM `customer_physio` WHERE `id` = '" . $row->id . "'");
			return 1;
		} else {
			return 0;
		}
	}

	public function mySelectedPhysioProgram($data)
	{
		/* $query = $this->db->query("SELECT w.id as exercise_id, 
		w.physio_title as name, 
		w.physio_desc as description, 
		w.image,
		DATE_FORMAT(FROM_UNIXTIME(c.physio_date), '%d-%m-%Y') AS date_selected
		FROM customer_physio c
		LEFT JOIN physio w ON w.id = c.exercise_id
		where 1 AND c.physio_date = '" . strtotime($data['date']) . "' AND c.cust_id = '" . $data['cust_id'] . "' ORDER BY c.id asc"); */

		$query = $this->db->query("SELECT w.id as program_id, 
		w.title as name, 
		w.description as description, 
		w.image,
		w.no_of_week		
		FROM customer_physio c
		LEFT JOIN physio_program w ON w.id = c.program_id
		where 1 AND c.cust_id = '" . $data['cust_id'] . "' ORDER BY c.id asc");

		return $query->result();
	}

	public function getProgram($data)
	{
		$query = $this->db->query("SELECT `no_of_week` FROM physio_program where `id` = '" . $data['program_id'] . "'");
		$row = $query->row();
		$queryCust = $this->db->query("SELECT * FROM `customer_goal` WHERE `cust_id` = '" . $data['cust_id'] . "'");
		$rowCust = $queryCust->row();

		return array('no_of_week' => $row->no_of_week,'start_date' => $rowCust->start_date);
	}
}
