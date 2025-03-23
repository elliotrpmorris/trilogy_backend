<?php

class Physio_model extends CI_Model

{

	public function __construct()

	{

		parent::__construct();

		date_default_timezone_set('Asia/Kolkata');
	}

	public function fetchAllExcercise(){
		$query = $this->db->query("SELECT w.*, r.title as program_name
		FROM physio_exercise w
		LEFT JOIN physio_program r ON r.id = w.program_id
		ORDER BY w.id desc");

		return $query->result();
	}
}
