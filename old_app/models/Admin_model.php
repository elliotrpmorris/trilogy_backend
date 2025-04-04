<?php
class Admin_model extends CI_Model
{
	public function __construct()
	{
	    parent::__construct();
	    date_default_timezone_set('Asia/Kolkata');
	}

	/* DASHBOARD DATA */

	public function fetchDashboardData(){
		$totalCust = $this->db->query("SELECT count(*) as num from `customer` WHERE 1");
		$returnData['totalCust'] = $totalCust->row();

		$totalMeal = $this->db->query("SELECT count(*) as num from `meals` WHERE 1");
		$returnData['totalMeal'] = $totalMeal->row();

		$totalWork = $this->db->query("SELECT count(*) as num from `workout_routine` WHERE 1");
		$returnData['totalWork'] = $totalWork->row();

		$totalExer = $this->db->query("SELECT count(*) as num from `workout` WHERE 1");
		$returnData['totalExer'] = $totalExer->row();
		
		$totalPayment = $this->db->query("SELECT sum(payment_amt) as payment_amt from `customer_package` WHERE `pay_status` = 'Y'");
		$returnData['totalPayment'] = $totalPayment->row();

		$recentCust = $this->db->query("SELECT `name`, `email_id`, `reg_date` from `customer` WHERE 1 order by id desc limit 6");
		$returnData['recentCust'] = $recentCust->result();
		
		$recentTran = $this->db->query("SELECT c.name, s.payment_date, s.payment_amt
		from customer c 
		LEFT JOIN customer_package s ON s.cust_id = c.id 
		WHERE 1 order by s.id desc limit 10");

		$returnData['recentTran'] = $recentTran->result();

		return $returnData;

	}



	/* Start of Admin Model*/
	/* Generate code */
	function generate_num($input, $pad_len = 7, $prefix = null) {
      if ($pad_len <= strlen($input))
          trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

      if (is_string($prefix))
          return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

      return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
  	}
	
	/* Client Management */
	public function get_clients()
	{
		$this->db->select('*');
		$this->db->from('users as u');
		$this->db->join('roles as r', 'r.rid=u.role_id','left');
		$this->db->where_in('u.role_id', ['3','4','5']);
		//$this->db->where_not_in('u.role_id', array(1,2) );
		$this->db->order_by('u.user_id','DESC');
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}	
	public function add_client($add_client_data)
	{
		$this->db->insert('users',$add_client_data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function get_client_by_user_id($user_id)
	{
		$this->db->select('*');
		$this->db->from('users as u');
		$this->db->join('roles as r', 'r.rid=u.role_id', 'left');
		$this->db->where('u.user_id', $user_id);
		$query = $this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row();
		}else{
			return false;
		}
	}
	public function save_edited_client($user_id,$reset_password,$save_client_data)
	{
		if(!empty($reset_password))
		{
			$save_client_data['password']=md5($reset_password);
		}
		$this->db->update('users',$save_client_data,array('user_id' => $user_id, ) );
		if ($this->db->affected_rows() >0) {
			return true;
		}else{
			return false;
		}
	}
	public function delete_client_by_user_id($user_id)
	{
		$this->db->delete('users',array('user_id'=>$user_id));
		if($this->db->affected_rows() >0)
		{
			return true;
		}else{
			return false;
		}		
	}
	public function set_client_status_by_user_id($user_id)
	{

		$result=$this->db->get_where('users',array('user_id'=>$user_id))->row();
		if($result->user_status == 1){
			$this->db->update('users',array('user_status'=>0),array('user_id'=>$user_id));	
		}else{
			$this->db->update('users',array('user_status'=>1),array('user_id'=>$user_id));
		}
		
		if($this->db->affected_rows() >0)
		{
			return true;
		}else{
			return false;
		}		
	}
	public function search_clients($start_date,$end_date)
	{		
		$this->db->select('*');
		$this->db->from('users as u');
		$this->db->join('roles as r', 'r.rid=u.role_id','left');
		$this->db->where_in('u.role_id', ['3','4','5']);		
		$this->db->order_by('u.user_id','DESC');


		$this->db->where('u.created >=', $start_date);
		$this->db->where('u.created <=', $end_date);
		return $this->db->get()->result();
	}
	/* Driver Management */
	public function get_drivers()
	{
		$this->db->select("*");
		$this->db->from("users as u");
		//$this->db->join("drivers_profile as d",'d.user_id=u.user_id','left');
		$this->db->where('u.role_id',2);
		$this->db->order_by('u.user_id','DESC');
		$result = $this->db->get()->result();
		if($result >0)
		{
			return $result;
		}
	}
	function get_driver_profile_by_user_id($user_id)
	{
		$this->db->select("*");
		$this->db->from("users as u");
		$this->db->join("drivers_profile as d",'d.user_id=u.user_id','left');
		$this->db->where('d.user_id',$user_id);		
		$query = $this->db->get();
		if($query->num_rows() >0)
		{
			return $query->row();
		}else{
			return false;
		}
	}
	function get_driver_availability_by_user_id($user_id)
	{
		$query = $this->db->get_where('drivers_availability',array('user_id'=>$user_id));
		if($query->num_rows() >0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	function get_driven_rate_by_user_id($user_id)
	{
		$query = $this->db->get_where('driven_rates',array('user_id'=>$user_id));
		if($query->num_rows() >0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	function get_transport_rate_by_user_id($user_id)
	{
		$query = $this->db->get_where('transport_rates',array('user_id'=>$user_id));
		if($query->num_rows() >0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	function set_driver_status($user_id)
	{
		$result=$this->db->get_where('users',array('user_id'=>$user_id))->row();
		if($result->user_status == 1){
			$user_status=0;
		}else{
			$user_status=1;
		}
		$this->db->update('users',array('user_status'=>$user_status),array('user_id'=>$user_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function search_drivers($start_date,$end_date)
	{
		$this->db->select("*");
		$this->db->from("users as u");
		$this->db->join("drivers_profile as d",'d.user_id=u.user_id','left');
		$this->db->where('d.role_id',2);

		$this->db->where('d.profile_modified >=', $start_date);
		$this->db->where('d.profile_modified <=', $end_date);
		return $this->db->get()->result();
	}
	/* Inspection Management */
	function get_inspection(){
		return $this->db->get('inspections')->result();
	}
	function add_inspection()
	{
		$data = array(
			'inspection_sheet_name'=>$this->input->post('inspection_sheet_name'),
			'inspection_sheet_type'=>$this->input->post('inspection_sheet_type'),
			'damage_level'=>$this->input->post('damage_level'),
			'user_type'=>$this->input->post('user_type'),
			'inspection_status'=>1,
			'inspection_created'=>date('Y-m-d')
		);
		$this->db->insert('inspections',$data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_inspection_status($inspection_id)
	{
		$result=$this->db->get_where('inspections',array('inspection_id'=>$inspection_id))->row();
		if($result->inspection_status == 1){
			$inspection_status=0;			
		}else{
			$inspection_status=1;
		}
		$this->db->update('inspections',array('inspection_status'=> $inspection_status),array('inspection_id'=>$inspection_id));
		if($this->db->affected_rows() >0)
		{
			return true;
		}else{
			return false;
		}
	}
	function get_inspection_by_id($inspection_id)
	{
		return $this->db->get_where('inspections',array('inspection_id' => $inspection_id))->row();
	}
	function update_inspection_by_id($inspection_id,$data)
	{
		$this->db->update('inspections', $data, array('inspection_id'=>$inspection_id));
		if($this->db->affected_rows() >0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_inspection_by_id($inspection_id)
	{
		$this->db->delete('inspections',array('inspection_id'=>$inspection_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function search_inspections($start_date,$end_date)
	{
		$this->db->select('*');
		$this->db->from('inspections');

		$this->db->where('inspection_created >=', $start_date);
		$this->db->where('inspection_created <=', $end_date);
		return $this->db->get()->result();
	}
	/* Inspection Sheet Name */
	function get_red_inspection_sheet()
	{
		return $this->db->get_where('inspection_sheets',array('inspection_sheet_category'=>'RED Inspection Sheet'))->result();
	}
	function get_tsr_inspection_sheet()
	{
		return $this->db->get_where('inspection_sheets',array('inspection_sheet_category'=>'TSR Inspection Sheet'))->result();
	}
	function add_inspection_sheet()
	{
		$data = array(
			'inspection_sheet_category'=>$this->input->post('inspection_sheet_category'),
			'inspection_sheet_name'=>$this->input->post('inspection_sheet_name'),
			'inspection_sheet_status'=>1,
			'inspection_sheet_created'=>date('Y-m-d')
		);
		$this->db->insert('inspection_sheets',$data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function get_inspection_sheet_by_id($inspection_sheet_id)
	{
		return $this->db->get_where('inspection_sheets',array('inspection_sheet_id'=>$inspection_sheet_id))->row();
	}
	function update_inspection_sheet()
	{
		$inspection_sheet_id=$this->input->post('inspection_sheet_id');
		$inspection_sheet_name=$this->input->post('inspection_sheet_name');
		$data = array(			
			'inspection_sheet_name'=>$this->input->post('inspection_sheet_name'),
			'inspection_sheet_modified'=>date('Y-m-d')
		);
		$this->db->update('inspection_sheets',$data,array('inspection_sheet_id'=>$inspection_sheet_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function status_inspection_sheet($inspection_sheet_id)
	{
		$result=$this->db->get_where('inspection_sheets',array('inspection_sheet_id'=>$inspection_sheet_id))->row();
		if($result->inspection_sheet_status == 1){
			$inspection_sheet_status=0;
		}else{
			$inspection_sheet_status=1;
		}
		$this->db->update('inspection_sheets',array('inspection_sheet_status'=>$inspection_sheet_status),array('inspection_sheet_id'=>$inspection_sheet_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_inspection_sheet($inspection_sheet_id)
	{		
		$this->db->delete('inspection_sheets',array('inspection_sheet_id'=>$inspection_sheet_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Damage Type */
	function get_damage_type()
	{
		return $this->db->get('damage_types')->result();
	}
	function get_damage_type_by_id($damage_type_id)
	{
		return $this->db->get_where('damage_types', ['damage_type_id'=>$damage_type_id])->row();
	}
	function add_damage_type()
	{
		$data = array(
			'damage_type' => $this->input->post('damage_type'),
			'damage_type_rate' => $this->input->post('damage_type_rate'),
			'damage_type_status' =>1,
			'damage_type_created' => date('Y-m-d')
			 );
		$this->db->insert('damage_types',$data);
		if($this->db->insert_id()>0)
		{
			return true;		
		}else{
			return false;
		}
	}
	function update_damage_type()
	{
		$damage_type_id=$this->input->post('damage_type_id');
		$damage_type=$this->input->post('damage_type');
		$damage_type_rate=$this->input->post('damage_type_rate');
		$data = array(			
			'damage_type'=>$damage_type,
			'damage_type_rate'=>$damage_type_rate,
			'damage_type_modified'=>date('Y-m-d')
		);
		$this->db->update('damage_types',$data,array('damage_type_id'=>$damage_type_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}	
	}
	function status_damage_type($damage_type_id)
	{
		$result=$this->db->get_where('damage_types',array('damage_type_id'=>$damage_type_id))->row();
		if($result->damage_type_status == 1){
			$damage_type_status=0;
		}else{
			$damage_type_status=1;
		}
		$this->db->update('damage_types',array('damage_type_status'=>$damage_type_status),array('damage_type_id'=>$damage_type_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_damage_type($damage_type_id)
	{
		$this->db->delete('damage_types', array('damage_type_id'=>$damage_type_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Damage level */
	public function get_damage_levels()
	{
		return $this->db->get('damage_levels')->result();
	}
	function add_damage_level()
	{
		$data = array(
			'damage_level' => $this->input->post('damage_level'),
			'damage_level_status' =>1,
			'damage_level_created' => date('Y-m-d')
			 );
		$this->db->insert('damage_levels',$data);
		if($this->db->insert_id()>0)
		{
			return true;		
		}else{
			return false;
		}
	}
	function get_damage_level_by_id($damage_level_id)
	{
		return $this->db->get_where('damage_levels',array('damage_level_id'=>$damage_level_id))->row();
	}
	function update_damage_level()
	{
		$damage_level_id=$this->input->post('damage_level_id');
		$damage_level=$this->input->post('damage_level');
		$data = array(			
			'damage_level'=>$damage_level,
			'damage_level_modified'=>date('Y-m-d')
		);
		$this->db->update('damage_levels',$data,array('damage_level_id'=>$damage_level_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}	
	}
	function status_damage_level($damage_level_id)
	{
		$result=$this->db->get_where('damage_levels',array('damage_level_id'=>$damage_level_id))->row();
		if($result->damage_level_status == 1){
			$damage_level_status=0;
		}else{
			$damage_level_status=1;
		}
		$this->db->update('damage_levels',array('damage_level_status'=>$damage_level_status),array('damage_level_id'=>$damage_level_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_damage_level($damage_level_id)
	{
		$this->db->delete('damage_levels',array('damage_level_id'=>$damage_level_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Assign Pricing */
	function get_inspectionsItemPrices()
	{
		return $this->db->get('inspection_items_prices')->result();
	}
	function get_driversPrices()
	{
		return $this->db->get('driver_prices')->result();	
	}
	function get_clientsPrices()
	{
		return $this->db->get('client_prices')->result();
	}
	function set_inspection_item_price()
	{
		$data = array(
			'inspection_category' => $this->input->post('inspection_category'), 
			'inspection_sheet_name'=> $this->input->post('inspection_sheet_name'),
			'inspection_type'=> $this->input->post('inspection_type'),
			'inspection_item_price'=> $this->input->post('inspection_item_price'),
			'assign_price_status'=>1,
			'assign_price_created'=>date('Y-m-d')
		);
		$this->db->insert('inspection_items_prices',$data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_inspection_price_status($inspection_items_price_id)
	{
		$result=$this->db->get_where('inspection_items_prices',array('inspection_items_price_id'=>$inspection_items_price_id))->row();
		if($result->assign_price_status == 1){
			$assign_price_status=0;
		}else{
			$assign_price_status=1;
		}
		$this->db->update('inspection_items_prices',array('assign_price_status'=>$assign_price_status),array('inspection_items_price_id'=>$inspection_items_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function get_inspection_price_status($inspection_items_price_id)
	{
		return $this->db->get_where('inspection_items_prices',array('inspection_items_price_id'=>$inspection_items_price_id))->row();
	}
	function update_inspection_item_price()
	{
		$inspection_items_price_id =$this->input->post('inspection_items_price_id');		
		$data = array(
			'inspection_category' => $this->input->post('inspection_category'), 
			'inspection_sheet_name'=> $this->input->post('inspection_sheet_name'),
			'inspection_type'=> $this->input->post('inspection_type'),
			'inspection_item_price'=> $this->input->post('inspection_item_price'),
			'assign_price_modified'=>date('Y-m-d')
		);
		$this->db->update('inspection_items_prices',$data,array('inspection_items_price_id'=>$inspection_items_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}	
	}
	function delete_inspection_price_status($inspection_items_price_id)
	{
		$this->db->delete('inspection_items_prices',array('inspection_items_price_id'=>$inspection_items_price_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_driver_price()
	{
		$data = array(
			'driver_name' => $this->input->post('driver_name'), 
			'hour_range_start'=> $this->input->post('hour_range_start'),
			'hour_range_end'=> $this->input->post('hour_range_end'),
			'price_per_hour'=> $this->input->post('price_per_hour'),
			
			'tsr_price'=> $this->input->post('tsr_price'),
			'additional_hour_start'=> $this->input->post('additional_hour_start'),
			'additional_hour_end'=> $this->input->post('additional_hour_end'),
			'additional_hour_price_per_hour'=> $this->input->post('additional_hour_price_per_hour'),
			'additional_hour_tsr_price_per_hour'=> $this->input->post('additional_hour_tsr_price_per_hour'),

			'driver_price_status'=>1,
			'driver_price_created'=>date('Y-m-d')
		);
		$this->db->insert('driver_prices',$data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}	
	}
	function get_driver_price($driver_price_id)
	{
	   return $this->db->get_where('driver_prices',array('driver_price_id'=>$driver_price_id))->row();
	}
	function update_driver_price()
	{
		$driver_price_id =$this->input->post('driver_price_id');		
		$data = array(
			'driver_name' => $this->input->post('driver_name'), 
			'hour_range_start'=> $this->input->post('hour_range_start'),
			'hour_range_end'=> $this->input->post('hour_range_end'),
			'price_per_hour'=> $this->input->post('price_per_hour'),
			
			'tsr_price'=> $this->input->post('tsr_price'),
			'additional_hour_start'=> $this->input->post('additional_hour_start'),
			'additional_hour_end'=> $this->input->post('additional_hour_end'),
			'additional_hour_price_per_hour'=> $this->input->post('additional_hour_price_per_hour'),
			'additional_hour_tsr_price_per_hour'=> $this->input->post('additional_hour_tsr_price_per_hour'),
			'driver_price_created'=>date('Y-m-d')
		);
		$this->db->update('driver_prices',$data,array('driver_price_id'=>$driver_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_status_driver_price($driver_price_id)
	{
		$result=$this->db->get_where('driver_prices',array('driver_price_id'=>$driver_price_id))->row();
		if($result->driver_price_status == 1){
			$driver_price_status=0;
		}else{
			$driver_price_status=1;
		}
		$this->db->update('driver_prices',array('driver_price_status'=>$driver_price_status),array('driver_price_id'=>$driver_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_driver_price($driver_price_id)
	{
		$this->db->delete('driver_prices',array('driver_price_id'=>$driver_price_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_client_price()
	{
		$data = array(
			'client_name' => $this->input->post('client_name'), 
			'hour_range_start'=> $this->input->post('hour_range_start'),
			'hour_range_end'=> $this->input->post('hour_range_end'),
			'price_per_hour'=> $this->input->post('price_per_hour'),
			
			'tsr_price_per_hour'=> $this->input->post('tsr_price_per_hour'),
			'additional_hour_start'=> $this->input->post('additional_hour_start'),
			'additional_hour_end'=> $this->input->post('additional_hour_end'),
			'additional_hour_price_per_hour'=> $this->input->post('additional_hour_price_per_hour'),
			'additional_hour_tsr_price_per_hour'=> $this->input->post('additional_hour_tsr_price_per_hour'),

			'client_price_status'=>1,
			'client_price_created'=>date('Y-m-d')
		);
		$this->db->insert('client_prices',$data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function get_client_price($client_price_id)
	{
	   return $this->db->get_where('client_prices',array('client_price_id'=>$client_price_id))->row();
	}
	function update_client_price()
	{
		$client_price_id =$this->input->post('client_price_id');		
		$data = array(
			'client_name' => $this->input->post('client_name'), 
			'hour_range_start'=> $this->input->post('hour_range_start'),
			'hour_range_end'=> $this->input->post('hour_range_end'),
			'price_per_hour'=> $this->input->post('price_per_hour'),
			
			'tsr_price_per_hour'=> $this->input->post('tsr_price_per_hour'),
			'additional_hour_start'=> $this->input->post('additional_hour_start'),
			'additional_hour_end'=> $this->input->post('additional_hour_end'),
			'additional_hour_price_per_hour'=> $this->input->post('additional_hour_price_per_hour'),
			'additional_hour_tsr_price_per_hour'=> $this->input->post('additional_hour_tsr_price_per_hour'),
			'client_price_modified'=>date('Y-m-d')
		);
		$this->db->update('client_prices',$data,array('client_price_id'=>$client_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_status_client_price($client_price_id)
	{
		$result=$this->db->get_where('client_prices',array('client_price_id'=>$client_price_id))->row();
		if($result->client_price_status == 1){
			$client_price_status=0;
		}else{
			$client_price_status=1;
		}
		$this->db->update('client_prices',array('client_price_status'=>$client_price_status),array('client_price_id'=>$client_price_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_client_assign_price($client_price_id)
	{
		$this->db->delete('client_prices',array('client_price_id'=>$client_price_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Upload supportive PDF */
	function get_supportive_pdf()
	{
		return $this->db->get('supportive_pdf')->result();
	}
	function upload_supportive_pdf($pdfData)
	{		
		$this->db->insert('supportive_pdf',$pdfData);
		if($this->db->insert_id() >0)
		{						
			return true;
		}else{			
			return false;			
		}
	}	
	function set_supportive_pdf_status($supportive_pdf_id)
	{
		$result=$this->db->get_where('supportive_pdf',array('supportive_pdf_id'=>$supportive_pdf_id))->row();
		if($result->supportive_pdf_status == 1){
			$supportive_pdf_status=0;
		}else{
			$supportive_pdf_status=1;
		}
		$this->db->update('supportive_pdf',array('supportive_pdf_status'=>$supportive_pdf_status),array('supportive_pdf_id'=>$supportive_pdf_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function get_supportive_pdf_title($titleName)
	{
		return $this->db->get_where('supportive_pdf',array('assign_inspection_type'=>$titleName))->row();
	}
	function get_supportive_pdf_by_id($supportive_pdf_id)
	{
		return $this->db->get_where('supportive_pdf',array('supportive_pdf_id'=>$supportive_pdf_id))->row();
	}
	function update_supportive_pdf($supportive_pdf_id, $pdfData)
	{
		$this->db->update('supportive_pdf',$pdfData,array('supportive_pdf_id'=>$supportive_pdf_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_supportive_pdf($supportive_pdf_id)
	{		
		$this->db->where('supportive_pdf_id', $supportive_pdf_id);
		$this->db->delete('supportive_pdf');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Job Sheets Creation */
	function get_job_sheets()
	{
		$this->db->select('u.name,u.email,u.mobile_number,u.user_address,j.job_sheet_id,j.customer_id,j.customer_vehicle_reg_number,j.customer_vehicle_make_model,j.job_sheet_status');
		$this->db->from('job_sheets as j');
		$this->db->join('users as u','u.user_id=j.customer_id','left');
		return $this->db->get()->result();
	}
	function get_job_sheet_by_id($job_sheet_id)
	{			
		$this->db->select('u.user_id,u.name,j.job_sheet_id,j.customer_vehicle_reg_number,j.customer_vehicle_make_model');
		$this->db->from('users as u');
		$this->db->join('job_sheets as j','j.customer_id=u.user_id');
		$this->db->where('j.job_sheet_id',$job_sheet_id);
		return $this->db->get()->row();				
	}
	function add_job_sheet($jobData)
	{
		$this->db->insert('job_sheets',$jobData);
		if($this->db->insert_id()>0)
		{			
			return true;
		}else{ 
			return false;
		}
	}
	function update_job_sheet($job_sheet_id, $jobData)
	{
		$this->db->update('job_sheets', $jobData, array('job_sheet_id' => $job_sheet_id));
		if($this->db->affected_rows() > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	function set_job_sheet_creation($job_sheet_id)
	{
		$result=$this->db->get_where('job_sheets',array('job_sheet_id'=>$job_sheet_id))->row();
		if($result->job_sheet_status == 1){
			$job_sheet_status=0;
		}else{
			$job_sheet_status=1;
		}
		$this->db->update('job_sheets',array('job_sheet_status'=>$job_sheet_status),array('job_sheet_id'=>$job_sheet_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}	
	function delete_job_sheet($job_sheet_id)
	{		
		$this->db->where('job_sheet_id', $job_sheet_id);
		$this->db->delete('job_sheets');
		if($this->db->affected_rows() > 0)
		{
			return true;
		}else{
			return false;
		}
	}
	function search_job_sheets($start_date,$end_date)
	{
		$this->db->select('u.name,u.email,u.mobile_number,u.user_address,j.job_sheet_id,j.customer_id,j.customer_vehicle_reg_number,j.customer_vehicle_make_model,j.job_sheet_status');
		$this->db->from('job_sheets as j');
		$this->db->join('users as u','u.user_id=j.customer_id','left');

		$this->db->where('j.job_sheet_created >=', $start_date);
		$this->db->where('j.job_sheet_created <=', $end_date);

		return $this->db->get()->result();
	}
	/* Job Management */
	function get_all_jobs()
	{		
		$this->db->select('j.job_id,j.job_code,j.customer_id,j.job_type,j.assigned_customer_name,j.assign_driver_id,j.user_type_id,j.job_date,j.job_status,u.name,r.role_name');
		$this->db->from('jobs as j');
		$this->db->join('users as u','u.user_id=j.assign_driver_id','left');
		$this->db->join('roles as r','r.rid=j.user_type_id','left');
		$this->db->where_not_in('j.job_status',1);
		$this->db->order_by('j.job_code','DESC');
	
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}
	function completed_job_list()
	{		
		$this->db->select('j.job_id,j.job_code,j.assigned_customer_name,j.customer_id,j.job_type,j.assign_driver_id,j.user_type_id,j.job_date,j.job_status,u.name,r.role_name');
		$this->db->from('jobs as j');
		$this->db->join('users as u','u.user_id=j.assign_driver_id','left');
		$this->db->join('roles as r','r.rid=j.user_type_id','left');
		$this->db->where('j.job_status',1);
		$this->db->order_by('j.job_code','DESC');
	
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}
	function get_job_by_id($job_id)
	{		
		$this->db->select('j.job_id,j.assign_driver_id,j.assigned_customer_name,j.assigned_customer_email,j.user_type_id,j.vehicle_type,j.vehicle_reg_number,j.vehicle_make,j.vehicle_model,j.pickup_address,j.pickup_city,j.pickup_county,j.pickup_home_phone,j.pickup_postcode,j.drop_address,j.drop_city,j.drop_county,j.drop_home_phone,j.drop_postcode,j.job_type,j.job_date,u.name,r.role_name');

		$this->db->from('jobs as j');		
		$this->db->join('users as u', 'u.user_id=j.assign_driver_id','left');
		$this->db->join('roles as r', 'r.rid=j.user_type_id','left');
		$this->db->where('j.job_id', $job_id);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return false;
		}
	}
	
	function get_customer_by_job_id($job_id)
	{
		$this->db->select('j.customer_id,u.name');
		$this->db->from('jobs as j');
		$this->db->join('users as u','u.user_id=j.customer_id','left');
		$this->db->where('j.job_id',$job_id);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return false;
		}
	}
	/* get driver info */
	function get_driver_by_job_id($job_id)
	{
		$this->db->select('j.assign_driver_id,u.name');
		$this->db->from('jobs as j');		
		$this->db->join('users as u','u.user_id=j.assign_driver_id','left');		
		$this->db->where('j.job_id',$job_id);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->row();
		}else{
			return false;
		}
	}

	/* Autocomplete search customer */
	function search_customer($searchTerm="")
	{
		// Fetch users
		$rid = array(1,2);
	    $this->db->select('user_id, name');
	    $this->db->where("name like '%".$searchTerm."%' ");
	    $this->db->where('user_status', 1);
	    $this->db->where_not_in('role_id', $rid);
	    $this->db->order_by('name', 'DESC');
	    $fetched_records = $this->db->get('users');
	    $users = $fetched_records->result_array();

	     // Initialize Array with fetched data
	     $data = array();
	     foreach($users as $user){
	        $data[] = array("id"=>$user['user_id'], "text"=>$user['name']);
	     }
	     return $data;
	}

	function add_job($jobData)
	{
		$this->db->insert('jobs',$jobData);
		if($this->db->insert_id() >0){
			// insert job code
			$job_code = $this->generate_num($this->db->insert_id(), 7, 'JID');
			$this->db->update('jobs', ['job_code'=>$job_code], ['job_id'=>$this->db->insert_id()]);
			
			return true;
		}else{
			return false;
		}
	}
	function edit_job_by_id($job_id, $jobData)
	{
		$this->db->update('jobs', $jobData, array('job_id'=>$job_id));
		if($this->db->affected_rows()>0){
			// update job code
			$job_code = $this->generate_num($job_id, 7, 'JID');
			$this->db->update('jobs', ['job_code'=>$job_code], ['job_id'=>$job_id]);			
			return true;
		}else{
			return false;
		}
	}
	function set_job_status($job_id)
	{
		$result=$this->db->get_where('jobs',array('job_id'=>$job_id))->row();
		if($result->job_status == 2){
			$job_status=0;
		}else{
			$job_status=2;
		}
		$this->db->update('jobs',array('job_status'=>$job_status),array('job_id'=>$job_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function job_search($start_date,$end_date)
	{
		$this->db->select('j.job_id,j.customer_id,j.job_type,j.assign_driver_id,j.user_type_id,j.job_date,j.job_status,u.name,r.role_name');
		$this->db->from('jobs as j');
		$this->db->join('users as u','u.user_id=j.assign_driver_id','left');
		$this->db->join('roles as r','r.rid=j.user_type_id','left');

		$this->db->where('j.job_created >=', $start_date);
		$this->db->where('j.job_created <=', $end_date);
		$this->db->where_not_in('j.job_status',1);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}

	function completed_job_search($start_date,$end_date)
	{
		$this->db->select('j.job_id,j.customer_id,j.job_type,j.assign_driver_id,j.user_type_id,j.job_date,j.job_status,u.name,r.role_name');
		$this->db->from('jobs as j');
		$this->db->join('users as u','u.user_id=j.assign_driver_id','left');
		$this->db->join('roles as r','r.rid=j.user_type_id','left');

		$this->db->where('j.job_created >=', $start_date);
		$this->db->where('j.job_created <=', $end_date);
		$this->db->where('j.job_status',1);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}

	/* Rate Management */
	function get_tsr_driven_rate()
	{
		$this->db->select('u.name,td.tsr_driven_rate_id,td.tsr_car_driven_price,td.tsr_car_driven_hours,td.tsr_car_driven_additional_price,td.tsr_car_driven_additional_hours,td.tsr_track_driven_price,td.tsr_track_driven_hours,td.tsr_track_driven_additional_price,td.tsr_track_driven_additional_hours,td.tsr_driven_rate_status');		

		$this->db->from('tsr_driven_rates as td');
		$this->db->join('users as u','u.user_id=td.tsr_driven_driver_id','left');
		$this->db->order_by('td.tsr_driven_rate_id','DESC');

		return $this->db->get()->result();
	}

	function get_tsr_driven_rate_by_id($tsr_driven_rate_id)
	{
		$this->db->select('u.name,u.user_id,td.tsr_driven_rate_id,td.tsr_car_driven_price,td.tsr_car_driven_hours,td.tsr_car_driven_additional_price,td.tsr_car_driven_additional_hours,td.tsr_track_driven_price,td.tsr_track_driven_hours,td.tsr_track_driven_additional_price,td.tsr_track_driven_additional_hours,td.tsr_driven_rate_status');

		$this->db->from('tsr_driven_rates as td');
		$this->db->join('users as u', 'u.user_id=td.tsr_driven_driver_id','left');
		$this->db->join('roles as r', 'r.rid=u.role_id','left');
		$this->db->where('td.tsr_driven_rate_id',$tsr_driven_rate_id);
		return $this->db->get()->row();		
	}
	function get_tsr_driven_driver_id($tsr_driven_driver_id)
	{
		$query=$this->db->get_where('tsr_driven_rates',array('tsr_driven_driver_id'=>$tsr_driven_driver_id));
		return $query->num_rows();
	}
	function add_tsr_driven_rate($tsrDrivenRate)
	{
		$this->db->insert('tsr_driven_rates',$tsrDrivenRate);
		if($this->db->insert_id() >0){
			return true;
		}else{
			return false;
		}
	}
	function update_tsr_driven_rate($tsr_driven_rate_id,$tsrDrivenRate)
	{
		$this->db->update('tsr_driven_rates',$tsrDrivenRate, array('tsr_driven_rate_id'=>$tsr_driven_rate_id));
		if($this->db->affected_rows() >0){
			return true;
		}else{
			return false;
		}
	}
	function set_tsr_driven_rate_status($tsr_driven_rate_id)
	{
		$result=$this->db->get_where('tsr_driven_rates',array('tsr_driven_rate_id'=>$tsr_driven_rate_id))->row();
		if($result->tsr_driven_rate_status == 1){
			$tsr_driven_rate_status=0;
		}else{
			$tsr_driven_rate_status=1;
		}
		$this->db->update('tsr_driven_rates',array('tsr_driven_rate_status'=>$tsr_driven_rate_status),array('tsr_driven_rate_id'=>$tsr_driven_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_tsr_driven_rate($tsr_driven_rate_id)
	{
		$this->db->delete('tsr_driven_rates',array('tsr_driven_rate_id'=>$tsr_driven_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function search_tsr_driven_rate($start_date,$end_date)
	{
		$this->db->select('u.name,td.tsr_driven_rate_id,td.tsr_driven_hourly_price,td.tsr_driven_additional_hourly_price,td.tsr_driven_rate_status,td.tsr_driven_rate_created');
		$this->db->from('tsr_driven_rates as td');
		$this->db->join('users as u','u.user_id=td.tsr_driven_driver_id','left');

		$this->db->where('td.tsr_driven_rate_created >=', $start_date);
		$this->db->where('td.tsr_driven_rate_created <=', $end_date);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}	
	function get_tsr_transported_rate()
	{
		$this->db->select('u.name,td.tsr_transported_rate_id,td.tsr_transported_driver_id,td.tsr_transported_hourly_price,td.tsr_transported_additional_hourly_price,td.tsr_transported_rate_status');
		$this->db->from('tsr_transported_rates as td');
		$this->db->join('users as u','u.user_id=td.tsr_transported_driver_id','left');

		return $this->db->get()->result();
	}
	
	function get_tsr_transported_rate_by_id($tsr_transported_rate_id)
	{
		$this->db->select('u.name,td.tsr_transported_rate_id,td.tsr_transported_driver_id,td.tsr_transported_hourly_price,td.tsr_transported_additional_hourly_price,td.tsr_transported_rate_status');
		$this->db->from('tsr_transported_rates as td');
		$this->db->join('users as u','u.user_id=td.tsr_transported_driver_id','left');
		$this->db->where('td.tsr_transported_rate_id',$tsr_transported_rate_id);
		return $this->db->get()->row();		
	}
	
	function get_tsr_transported_driver_id($tsr_transported_driver_id)
	{
		$result = $this->db->get_where('tsr_transported_rates', array('tsr_transported_driver_id'=>$tsr_transported_driver_id) )->result();
		if($result > 0 ){
			return true;
		}else{
			return false;
		}
	}
	function add_tsr_transported_rate($tsrTransportedRate)
	{
		$this->db->insert('tsr_transported_rates',$tsrTransportedRate);
		if($this->db->insert_id() >0){
			return true;
		}else{
			return false;
		}
	}
	function update_tsr_transported_rate($tsr_transported_rate_id,$tsrTransportedRate)
	{
		$this->db->update('tsr_transported_rates',$tsrTransportedRate, array('tsr_transported_rate_id'=>$tsr_transported_rate_id));
		if($this->db->affected_rows() >0){
			return true;
		}else{
			return false;
		}
	}
	function set_tsr_transported_rate_status($tsr_transported_rate_id)
	{
		$result=$this->db->get_where('tsr_transported_rates',array('tsr_transported_rate_id'=>$tsr_transported_rate_id))->row();
		if($result->tsr_transported_rate_status == 1){
			$tsr_transported_rate_status=0;
		}else{
			$tsr_transported_rate_status=1;
		}
		$this->db->update('tsr_transported_rates',array('tsr_transported_rate_status'=>$tsr_transported_rate_status),array('tsr_transported_rate_id'=>$tsr_transported_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_tsr_transported_rate($tsr_transported_rate_id)
	{
		$this->db->delete('tsr_transported_rates',array('tsr_transported_rate_id'=>$tsr_transported_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function search_tsr_transported_rate($start_date,$end_date)
	{
		$this->db->select('u.name,td.tsr_transported_rate_id,td.tsr_transported_hourly_price,td.tsr_transported_additional_hourly_price,td.tsr_transported_rate_status,td.tsr_transported_rate_created');
		$this->db->from('tsr_transported_rates as td');
		$this->db->join('users as u','u.user_id=td.tsr_transported_driver_id','left');

		$this->db->where('td.tsr_transported_rate_created >=', $start_date);
		$this->db->where('td.tsr_transported_rate_created <=', $end_date);
		$query=$this->db->get();
		if($query->num_rows()>0){
			return $query->result();
		}else{
			return false;
		}
	}	

	/* Damaged or Missing */
	function get_damaged_or_missing_rates()
	{
		return $this->db->get('damaged_missing_rates')->result();
	}

	function add_damaged_missing_rate($dmRate)
	{
		$tsr_layout_type = $dmRate['tsr_layout_type'];
		$tsr_damage_name = $dmRate['tsr_damage_name'];

		$this->db->select('*');
		$this->db->from('damaged_missing_rates');
		$this->db->where('tsr_layout_type',$tsr_layout_type);
		$this->db->where('tsr_damage_name',$tsr_damage_name);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return false;
		}else{
			$this->db->insert('damaged_missing_rates',$dmRate);
			if($this->db->insert_id() >0){
				return true;
			}else{
				return false;
			}			
		}
	}

	function get_damaged_or_missing_rate_by_id($tsr_rate_id)
	{
		$query=$this->db->get_where('damaged_missing_rates', ['tsr_rate_id'=>$tsr_rate_id]);
		if($query->num_rows() >0){
			return $query->row();
		}else{
			return false;
		}
	}

	function update_damaged_or_missing_rate($tsr_rate_id,$dmRate)
	{
		$this->db->update('damaged_missing_rates', $dmRate, ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows() >0){
			return true;
		}else{
			return false;
		}
	}

	function set_damaged_or_missing_rate_status($tsr_rate_id)
	{
		$result=$this->db->get_where('damaged_missing_rates', ['tsr_rate_id'=>$tsr_rate_id])->row();
		if($result->tsr_rate_status == 1){
			$tsr_rate_status=0;
		}else{
			$tsr_rate_status=1;
		}
		$this->db->update('damaged_missing_rates', ['tsr_rate_status'=>$tsr_rate_status], ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_damaged_missing_rate($tsr_rate_id)
	{
		$this->db->delete('damaged_missing_rates', ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}

	/* RED Inspection Rates */
	function get_redRates()
	{
		return $this->db->get('red_inspection_rates')->result();
	}

	function add_red_tsr_rate($redRate)
	{
		$tsr_layout_type = $redRate['tsr_layout_type'];
		$tsr_layout_name = $redRate['tsr_layout_name'];
		$tsr_layout_category = $redRate['tsr_layout_category'];

		$this->db->select('*');
		$this->db->from('red_inspection_rates');
		$this->db->where('tsr_layout_type',$tsr_layout_type);
		$this->db->where('tsr_layout_name',$tsr_layout_name);
		$this->db->where('tsr_layout_category',$tsr_layout_category);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return false;
		}else{
			$this->db->insert('red_inspection_rates',$redRate);
			if($this->db->insert_id() >0){
				return true;
			}else{
				return false;
			}			
		}
	}

	function get_red_tsr_rate_by_id($tsr_rate_id)
	{
		$query=$this->db->get_where('red_inspection_rates', ['tsr_rate_id'=>$tsr_rate_id]);
		if($query->num_rows() >0){
			return $query->row();
		}else{
			return false;
		}
	}
	function update_red_tsr_rate($tsr_rate_id,$redRate)
	{
		$this->db->update('red_inspection_rates',$redRate, ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows() >0){
			return true;
		}else{
			return false;
		}
	}
	function set_red_tsr_rate_status($tsr_rate_id)
	{
		$result=$this->db->get_where('red_inspection_rates', ['tsr_rate_id'=>$tsr_rate_id])->row();
		if($result->tsr_rate_status == 1){
			$tsr_rate_status=0;
		}else{
			$tsr_rate_status=1;
		}
		$this->db->update('red_inspection_rates', ['tsr_rate_status'=>$tsr_rate_status], ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_red_tsr_rate($tsr_rate_id)
	{
		$this->db->delete('red_inspection_rates', ['tsr_rate_id'=>$tsr_rate_id]);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* TSR Damges */
	function get_tsrRates()
	{
		return $this->db->get('tsr_rates')->result();
	}
	function add_tsr_rate($tsrRate)
	{
		$tsr_layout_type = $tsrRate['tsr_layout_type'];
		$tsr_layout_name = $tsrRate['tsr_layout_name'];
		$tsr_layout_category = $tsrRate['tsr_layout_category'];

		$this->db->select('*');
		$this->db->from('tsr_rates');
		$this->db->where('tsr_layout_type',$tsr_layout_type);
		$this->db->where('tsr_layout_name',$tsr_layout_name);
		$this->db->where('tsr_layout_category',$tsr_layout_category);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return false;
		}else{
			$this->db->insert('tsr_rates',$tsrRate);
			if($this->db->insert_id() >0){
				return true;
			}else{
				return false;
			}
		}
	}
	function get_tsr_rate_by_id($tsr_rate_id)
	{
		$query=$this->db->get_where('tsr_rates',array('tsr_rate_id'=>$tsr_rate_id));
		if($query->num_rows() >0){
			return $query->row();
		}else{
			return false;
		}
	}
	function update_tsr_rate($tsr_rate_id,$tsrRate)
	{
		$this->db->update('tsr_rates',$tsrRate, array('tsr_rate_id'=>$tsr_rate_id));
		if($this->db->affected_rows() >0){
			return true;
		}else{
			return false;
		}
	}
	function set_tsr_rate_status($tsr_rate_id)
	{
		$result=$this->db->get_where('tsr_rates',array('tsr_rate_id'=>$tsr_rate_id))->row();
		if($result->tsr_rate_status == 1){
			$tsr_rate_status=0;
		}else{
			$tsr_rate_status=1;
		}
		$this->db->update('tsr_rates',array('tsr_rate_status'=>$tsr_rate_status),array('tsr_rate_id'=>$tsr_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	function delete_tsr_rate($tsr_rate_id)
	{
		$this->db->delete('tsr_rates',array('tsr_rate_id'=>$tsr_rate_id));
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	
	// public function add_red_inspecction_damage()
	// {
	// 	$data=[
	// 		'ris_level_name'=>'RED Inspection Sheet',
	// 		'ris_damage_name'=>$this->input->post('damage_name'),
	// 		'ris_price'=>$this->input->post('rate'),
	// 		'ris_status'=>1,
	// 		'ris_created'=>date('Y-m-d')
	// 	];
	// 	$this->db->insert('ris_rates',$data);
	// 	if($this->db->insert_id() >0){
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}
	// }
	public function add_alloys_tyres_damage_rate()
	{
		$data=[
			'ris_level_name'=>'Alloys/Tyres',
			'ris_damage_name'=>$this->input->post('damage_name'),
			'ris_quantity'=>$this->input->post('quantity'),
			'ris_price'=>$this->input->post('rate'),
			'ris_status'=>1,
			'ris_created'=>date('Y-m-d')
		];
		$this->db->insert('ris_rates',$data);
		if($this->db->insert_id() >0){
			return true;
		}else{
			return false;
		}
	}

	/* get driver invoice details */
	function get_driver_invoice()
	{
		$this->db->order_by('invoice_id', 'DESC');
		return $this->db->get_where('invoices', ['invoice_status'=>1])->result();
	}

	// view pdf
	function get_pdf_invoice($invoice_id)
	{
		$this->db->select('invoice_pdf');
		$inv = $this->db->get_where('invoices', ['invoice_id'=>$invoice_id])->row();
		return $inv;
	}

	/* End of Admin Model*/	
}