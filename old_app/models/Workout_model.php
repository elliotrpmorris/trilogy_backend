<?php

class Workout_model extends CI_Model

{

	public function __construct()

	{

		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
	}

	public function fetchAllExcercise(){
		$query = $this->db->query("SELECT w.*, r.title , l.workout_level_name
		FROM workout w
		LEFT JOIN workout_routine r ON r.id = w.routine_id
		LEFT JOIN workout_level l ON l.id = w.workout_level_id
		ORDER BY w.id desc");

		return $query->result();
	}
}
