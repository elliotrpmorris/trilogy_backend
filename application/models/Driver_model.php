<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver_model extends CI_Model
{
	public function __construct()
	{
	    parent::__construct();
	    date_default_timezone_set('UTC');
	}

	/*Driver Profile Management*/
	public function get_profile_by_id($user_id)
	{		
		$query = $this->db->get_where('drivers_profile', array('user_id'=>$user_id));
		return $query->result();
	}
	public function get_driver_profile_by_id($user_id)
	{		
		$query = $this->db->get_where('users', array('user_id'=>$user_id));
		return $query->row();
	}
	public function update_profile($user_id, $profile)
	{		
		$query = $this->db->get_where('users', array('user_id'=>$user_id));
		if($query->num_rows() >0)
		{			
			$this->db->update('users',$profile,array('user_id'=>$user_id));
			if($this->db->affected_rows()>0)
			{
				return true;
			}else{
				return false;
			}
		}
	}
	
	public function active_profile($user_id)
	{
		$this->db->update('users',array('first_login'=>1), array('user_id'=>$user_id));
	}

	/*Add Availability*/
	public function get_availability($user_id)
	{		
		$query = $this->db->order_by('available_date', 'DESC')->get_where('drivers_availability', array('user_id'=> $user_id) );
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}

	public function add_availability($data)
	{				
		$query = $this->db->get_where('drivers_availability', ['user_id'=>$data['user_id'], 'available_date'=> $data['available_date']]); // check

		if($query->num_rows() > 0)
		{
			return false;
		}else{
			$this->db->insert('drivers_availability',$data);
			if($this->db->insert_id()>0)
			{
				return true;
			}else{
				return false;
			}
		}		
	}

	public function set_availability($available_data)
	{
		$query=$this->db->get_where('drivers_availability', ['user_id'=>$this->session->userdata['user_id']]);
		if($query->num_rows()>0)
		{
			$result=$query->row();
			$this->db->update('drivers_availability', $available_data, ['da_id'=>$result->da_id] );
			if($this->db->affected_rows()>0)
			{
				return true;
			}else{
				return false;
			}
		}else{
			$this->db->insert('drivers_availability', $available_data);
			if($this->db->insert_id()>0)
			{
				return true;
			}else{
				return false;
			}
		}		
	}	

	public function get_driver_availability_by_id($da_id)
	{		
		$query = $this->db->get_where('drivers_availability', array('da_id'=>$da_id));
		return $query->row();
	}

	public function edit_availability($da_id, $available_data)
	{
		$this->db->update('drivers_availability',$available_data, array('da_id'=>$da_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function delete_availability_by_id($da_id)
	{
		$this->db->delete('drivers_availability', array('da_id'=>$da_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/*Set Costing*/
	public function add_vehicle_model($vehicle_model_data)
	{
		$this->db->insert('vehicles_model',$vehicle_model_data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function get_vehicles()
	{
		$query = $this->db->get('vehicles_model');
		return $query->result();
	}
	public function add_driving_costing($driving_costing_data)
	{
		$this->db->insert('driven_rates',$driving_costing_data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function get_driving_rate_by_user_id($user_id)
	{
		$query=$this->db->get_where('driven_rates', array('user_id'=>$user_id) );
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}

	public function get_driving_rate_by_driven_rates_id($driven_rates_id)
	{
		$query = $this->db->get_where('driven_rates', array('driven_rates_id '=> $driven_rates_id ) );
		if($query->num_rows()>0)
		{
			return $query->row();
		}else{
			return false;
		}		
	}

	public function edit_driven_rate($driven_rates_id, $driven_rate)
	{		
		$this->db->where('driven_rates_id',$driven_rates_id);
		$this->db->update('driven_rates', $driven_rate);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function delete_driving_rate_by_driven_rates_id($driven_rates_id)
	{
		$this->db->delete('driven_rates', array('driven_rates_id'=>$driven_rates_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function add_transported_rate($transported_rate_data)
	{
		$this->db->insert('transport_rates',$transported_rate_data);
		if($this->db->insert_id()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function get_transport_rate_by_user_id($user_id)
	{
		$query=$this->db->get_where('transport_rates', array('user_id'=>$user_id) );
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	public function get_transport_rate_by_transport_rates_id($transport_rate_id)
	{
		$query = $this->db->get_where('transport_rates', array('transport_rate_id '=> $transport_rate_id) );
		if($query->num_rows()>0)
		{
			return $query->row();
		}else{
			return false;
		}		
	}
	public function update_transported_rate($transport_rate_id,$transported_rate_data)
	{
		$this->db->where('transport_rate_id',$transport_rate_id);
		$this->db->update('transport_rates', $transported_rate_data);
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function delete_transport_rate_by_transport_rate_id($transport_rate_id)
	{
		$this->db->delete('transport_rates', array('transport_rate_id'=>$transport_rate_id) );
		if($this->db->affected_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	/* Job Management */
	public function get_job_list_by_id($user_id)
	{
		$this->db->select('*');
		$this->db->from('jobs as j');		
		$this->db->join('users as u', 'u.user_id=j.customer_id');				
		//$this->db->join('job_sheets as js', 'js.customer_id=j.customer_id');
		
		$this->db->where('j.is_job_accept', '');
		$this->db->or_where('j.is_job_accept', 'Declined');
		$this->db->where('j.assign_driver_id',$user_id);

		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	public function get_accepted_job_list_by_id($user_id)
	{
		$this->db->select('*');
		$this->db->from('jobs as j');		
		$this->db->join('users as u', 'u.user_id=j.customer_id');
		//$this->db->join('job_sheets as js', 'js.customer_id=u.user_id');
		$this->db->where('j.assign_driver_id',$user_id);
		$this->db->where('j.is_job_accept', 'Accepted');

		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->result();
		}else{
			return false;
		}
	}
	public function get_job_by_job_id($job_id)
	{
		$this->db->select('*');
		$this->db->from('jobs as j');
		$this->db->join('users as u', 'u.user_id=j.customer_id');
		//$this->db->join('job_sheets as js','js.customer_id=u.user_id');
		$this->db->where('j.job_id',$job_id);
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return $query->row();
		}else{
			return false;
		}
	}
	public function job_acceptance($job_id,$job_acceptance)
	{
		$data= ['is_job_accept'=>$job_acceptance,'job_accepted_date_time'=>date('Y-m-d H:i:s')];
		$this->db->update('jobs', $data, ['job_id'=>$job_id]);
		if($this->db->affected_rows() > 0)
		{
			$this->db->select('is_job_accept');
			$this->db->from('jobs');
			$this->db->where('job_id',$job_id);			
			$result=$this->db->get()->row();
			return $result;
		}else{
			return false;
		}
	}
	public function get_tsr_rates_by_id()
	{
		$this->db->select('*');
        $this->db->from('tsr_rates');        
        $this->db->order_by('tsr_rate_id', 'ASC');        
        $result=$this->db->get()->result();
        return $result;
	}
	/*
	Add to Job Sheet
	*/
	public function add_to_job_sheet($user_id,$job_id,$data)
	{				
		$data['user_id'] = $user_id;

		$tsr_layout_type = $data['tsr_layout_type'];
		$tsr_layout_name = $data['tsr_layout_name'];
		$tsr_layout_category = $data['tsr_layout_category'];
		$tsr_damage_name = $data['tsr_damage_name'];

		$check = $this->db->get_where('job_cart', ['job_id'=>$job_id, 'user_id'=>$user_id, 'tsr_layout_type'=>$tsr_layout_type, 'tsr_layout_name'=>$tsr_layout_name, 'tsr_layout_category'=>$tsr_layout_category])->result();

		
		if(count($check) > 0)
		{
			// update
			if(($tsr_layout_type != NULL) && ($tsr_layout_name !=NULL) && ($tsr_layout_category !=NULL)){

				if($tsr_damage_name != NULL){
					$statment = "SELECT tsr_damage_name,tsr_damage_repair_rate FROM tsr_rates WHERE `tsr_layout_type`='$tsr_layout_type' AND `tsr_layout_name`='$tsr_layout_name' AND `tsr_layout_category`='$tsr_layout_category'";		
					$query = $this->db->query($statment);			
					$result = $query->result();

					if ($result[0]->tsr_damage_name != NULL) {
						$tsr_damage_name = $result[0]->tsr_damage_name;
						$tsr_damage_repair_rate = $result[0]->tsr_damage_repair_rate;

						$tdn = explode(',', $tsr_damage_name);
						$i=0;
						foreach ($tdn as $key => $value) {
							if($value == $data['tsr_damage_name'])
							{
								$tdrr = explode(',', $tsr_damage_repair_rate);
								$udata['tsr_damage_repair_rate'] = $tdrr[$i];
							}
							$i++;
						}
						$udata['tsr_damage_name'] = $data['tsr_damage_name'];
						$udata['no_damage'] = 0;
						$udata['na'] = 0;
						$udata['tsr_js_modified'] = date('Y-m-d');

						$this->db->update('job_cart', $udata, ['jsc_id'=>$check[0]->jsc_id]);
						$last_id = $this->db->affected_rows();
						if($last_id > 0){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					$udata['tsr_damage_name'] = '';
					$udata['tsr_damage_repair_rate'] = '';
					$udata['no_damage'] = $data['no_damage'];
					$udata['na'] = $data['na'];					
					$udata['tsr_js_modified'] = date('Y-m-d');

					$this->db->update('job_cart', $udata, ['jsc_id'=>$check[0]->jsc_id]);
					$last_id = $this->db->affected_rows();
					if($last_id > 0){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}

		}else{
			// insert
			if(($tsr_layout_type != NULL) && ($tsr_layout_name !=NULL) && ($tsr_layout_category !=NULL)){

				if(isset($tsr_damage_name) && ($tsr_damage_name != NULL)){
					$statment = "SELECT tsr_damage_name,tsr_damage_repair_rate FROM tsr_rates WHERE `tsr_layout_type`='$tsr_layout_type' AND `tsr_layout_name`='$tsr_layout_name' AND `tsr_layout_category`='$tsr_layout_category'";		
					$query = $this->db->query($statment);			
					$result = $query->result();

					if ($result[0]->tsr_damage_name != NULL) {
						$tsr_damage_name = $result[0]->tsr_damage_name;
						$tsr_damage_repair_rate = $result[0]->tsr_damage_repair_rate;

						$tdn = explode(',', $tsr_damage_name);
						$i=0;
						foreach ($tdn as $key => $value) {
							if($value == $data['tsr_damage_name'])
							{
								$tdrr = explode(',', $tsr_damage_repair_rate);
								$data['tsr_damage_repair_rate'] = $tdrr[$i];
							}
							$i++;
						}
						$data['tsr_js_created'] = date('Y-m-d');

						$this->db->insert('job_cart', $data);
						$last_id = $this->db->insert_id();
						if($last_id >0){
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}else{
					$data['tsr_js_created'] = date('Y-m-d');

					$this->db->insert('job_cart', $data);
					$last_id = $this->db->insert_id();
					if($last_id >0){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}	
		}	
	}
	/* Upload damage images */
	public function upload_damages($job_id, $upload_damage)
	{
		// get image file data
		$check= $this->db->get_where('vehicel_damages', array('job_id'=>$job_id));
		if($check->num_rows() > 0){
			$result=$check->result();
			
			if(isset($upload_damage['fos_exterior_vehicel_damages']) && !empty($result[0]->fos_exterior_vehicel_damages)){
				unlink('uploads/vehicel_damages/'.$result[0]->fos_exterior_vehicel_damages);	
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif(isset($upload_damage['ros_exterior_vehicel_damages']) && !empty($result[0]->ros_exterior_vehicel_damages)){
				unlink('uploads/vehicel_damages/'.$result[0]->ros_exterior_vehicel_damages);	
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif(isset($upload_damage['vr_exterior_vehicel_damages']) && !empty($result[0]->vr_exterior_vehicel_damages)){
				unlink('uploads/vehicel_damages/'.$result[0]->vr_exterior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['rns_exterior_vehicel_damages']) &&  !empty($result[0]->rns_exterior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->rns_exterior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['fns_exterior_vehicel_damages']) &&  !empty($result[0]->fns_exterior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->fns_exterior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['vf_exterior_vehicel_damages']) &&  !empty($result[0]->vf_exterior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->vf_exterior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['vroof_exterior_vehicel_damages']) &&  !empty($result[0]->vroof_exterior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->vroof_exterior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['fos_interior_vehicel_damages']) &&  !empty($result[0]->fos_interior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->fos_interior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));	
			}elseif (isset($upload_damage['ros_interior_vehicel_damages']) &&  !empty($result[0]->ros_interior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->ros_interior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));	
			}elseif (isset($upload_damage['boot_interior_vehicel_damages']) &&  !empty($result[0]->boot_interior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->boot_interior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}elseif (isset($upload_damage['rns_interior_vehicel_damages']) &&  !empty($result[0]->rns_interior_vehicel_damages)) {
				unlink('uploads/vehicel_damages/'.$result[0]->rns_interior_vehicel_damages);
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}else{
				$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			}			
			//$this->db->update('vehicel_damages',$upload_damage, array('job_id'=>$job_id));
			$last_id = $this->db->affected_rows();
			if($last_id >0){
				return true;
			}else{
				return false;
			}	
		}else{
			$upload_damage['job_id']=$job_id;
			$this->db->insert('vehicel_damages',$upload_damage);
			$last_id = $this->db->insert_id();
			if($last_id >0){
				return true;
			}else{
				return false;
			}	
		}		
	}
	
	// add damage & missing job sheet
	public function add_damage_missing_jobs($job_id,$user_id)
	{
		//  RED Inspection Sheet
		$s = $this->input->post('ris_damage_name[]');		
		foreach ($s as $key => $ris) {			
			$r = explode(',', $ris);
			
			$query=$this->db->get_where('job_cart',['job_id'=>$job_id,'tsr_layout_type'=>$r[0],'tsr_damage_name'=>$r[1]]);
			if($query->num_rows() > 0)
			{
				$rr = $query->result();			

				$ss = array('tsr_layout_type'=>$r[0],'tsr_damage_name'=>$r[1],'tsr_damage_repair_rate'=>$r[2],'tsr_js_modified'=>date('Y-m-d'));
				$this->db->update('job_cart', $ss, ['jsc_id'=>$rr[0]->jsc_id]);
			}else{			
				$ss=array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$r[0],'tsr_damage_name'=>$r[1],'tsr_damage_repair_rate'=>$r[2],'tsr_js_created'=>date('Y-m-d'));	
				$this->db->insert('job_cart',$ss);				
			}			
		}				
		// Alloys/Tyres Rates
		$t = $this->input->post('ris_at_damage_name');
		$tt = explode(',', $t);
		//var_dump($tt);

		$query1=$this->db->get_where('job_cart', ['job_id'=>$job_id,'tsr_layout_type'=>$tt[0],'tsr_damage_name'=>$tt[1]] );
		if($query1->num_rows() > 0)
		{
			$at = $query1->result();
			echo $at[0]; die();
			$ttt = array('tsr_layout_type'=>$tt[0],'tsr_damage_name'=>$tt[1],'tsr_damage_repair_rate'=>$tt[2],'tsr_js_modified'=>date('Y-m-d'));
			$this->db->update('job_cart', $ttt, ['jsc_id'=>$at[0]->jsc_id]);
		}else{			
			$ttt=array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$tt[0],'tsr_damage_name'=>$tt[1],'tsr_damage_repair_rate'=>$tt[2],'tsr_js_created'=>date('Y-m-d'));	
			$this->db->insert('job_cart',$ttt);
		}
		//$ttt = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$tt[0],'tsr_damage_name'=>$tt[1],'tsr_damage_repair_rate'=>$tt[2],'tsr_js_created'=>date('Y-m-d'));
		//  TSR Inspection Sheet
		$u1 = $this->input->post('tsr_inspection_sheet_1');
		$uu1 = explode(',', $u1);
		$uuu1 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu1[0],'tsr_damage_name'=>$uu1[1],'tsr_damage_repair_rate'=>$uu1[2],'tsr_js_created'=>date('Y-m-d'));		
		$u2 = $this->input->post('tsr_inspection_sheet_2');
		$uu2 = explode(',', $u2);
		$uuu2 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu2[0],'tsr_damage_name'=>$uu2[1],'tsr_damage_repair_rate'=>$uu2[2],'tsr_js_created'=>date('Y-m-d'));		
		$u3 = $this->input->post('tsr_inspection_sheet_3');
		$uu3 = explode(',', $u3);
		$uuu3 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu3[0],'tsr_damage_name'=>$uu3[1],'tsr_damage_repair_rate'=>$uu3[2],'tsr_js_created'=>date('Y-m-d'));		
		$u4 = $this->input->post('tsr_inspection_sheet_4');
		$uu4 = explode(',', $u4);
		$uuu4 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu4[0],'tsr_damage_name'=>$uu4[1],'tsr_damage_repair_rate'=>$uu4[2],'tsr_js_created'=>date('Y-m-d'));		
		$u5 = $this->input->post('tsr_inspection_sheet_5');
		$uu5 = explode(',', $u5);
		$uuu5 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu5[0],'tsr_damage_name'=>$uu5[1],'tsr_damage_repair_rate'=>$uu5[2],'tsr_js_created'=>date('Y-m-d'));		
		$u6 = $this->input->post('tsr_inspection_sheet_6');
		$uu6 = explode(',', $u6);
		$uuu6 = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$uu6[0],'tsr_damage_name'=>$uu6[1],'tsr_damage_repair_rate'=>$uu6[2],'tsr_js_created'=>date('Y-m-d'));		
		// Damage Levels
		$v = $this->input->post('dl_damages');
		$vv = explode(',', $v);
		$vvv = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$vv[0],'tsr_damage_name'=>$vv[1],'quantity'=>$vv[2],'tsr_js_created'=>date('Y-m-d'));
		// Alloys/Tyres Damages
		$w = $this->input->post('at_damages');
		$ww = explode(',', $w);
		$www = array('job_id'=>$job_id,'user_id'=>$user_id,'tsr_layout_type'=>$ww[0],'tsr_damage_name'=>$ww[1],'quantity'=>$ww[2],'tsr_js_created'=>date('Y-m-d'));
				
		$final = [$sss1,$sss2,$sss3,$sss4,$sss5,$ttt,$uuu1,$uuu2,$uuu3,$uuu4,$uuu5,$uuu6,$vvv,$www];
		foreach ($final as $key => $value) {			
			$this->db->insert('job_cart',$value);
		}		
		if($this->db->insert_id() > 0)
		{
			return true;
		}elseif ($this->db->affected_rows() >0) {
			return true;
		}else{
			return false;
		}

	}
	// Upload PDF job sheet 
	public function upload_job_sheet($job_id,$upload_job_sheet)
	{
		$check= $this->db->get_where('jobs', array('job_id'=>$job_id, 'is_job_accept'=>'Accepted'));
		if($check->num_rows() > 0){
			$result=$check->result();
			$job_sheet_exist = $result['0']->job_sheet;
			if($job_sheet_exist != NULL){
				unlink('uploads/job_sheets/'.$job_sheet_exist);
			}
			$upload_job_sheet['job_completion_date_time'] = date('Y-m-d H:i:s');
			$this->db->update('jobs', $upload_job_sheet, array('job_id'=>$job_id));
			$last_id = $this->db->affected_rows();
			if($last_id >0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/* Invoice management */
	public function all_driver_jobs()
	{		
		$query = $this->db->get_where('jobs', array('assign_driver_id' => $this->session->userdata('user_id'), 'job_status'=>1, 'pre_invoicing_status'=> 0));
		$result = $query->result();

		$final = array();
		if($result>0){
			foreach ($result as $key => $value) {
				$customer_id = $value->customer_id;
				$driver_id = $value->assign_driver_id;

				$this->db->select('jobs.customer_id,users.name');
				$this->db->from('users');
				$this->db->where('user_id', $customer_id);
				$this->db->join('jobs', 'jobs.customer_id=users.user_id', 'left');
				$cres=$this->db->get()->result();				

				$this->db->select('name');
				$this->db->from('users');
				$this->db->where('user_id', $driver_id);
				$this->db->join('jobs', 'jobs.assign_driver_id=users.user_id', 'left');
				$dres=$this->db->get()->result();

				$this->db->select_sum('tsr_damage_repair_rate');
				$this->db->from('job_cart'); 
		        $this->db->where('job_id', $value->job_id);   		        
		        $price = $this->db->get()->row();
		        				
				$temp['job_code']	= $value->job_code;	
				$temp['job_id']	= $value->job_id;	
				$temp['customer_id'] = $cres[0]->customer_id;
				$temp['customer_name'] = $cres[0]->name;
				$temp['driver_name'] = $dres[0]->name;
				$temp['user_type_id']	= $value->user_type_id;	
				$temp['job_type']	= $value->job_type;	
				$temp['job_status']	= $value->job_status;
				$temp['is_job_accept']	= $value->is_job_accept;
				$temp['pre_invoicing_status']	= $value->pre_invoicing_status;
				$temp['tsr_damage_repair_rate'] = $price->tsr_damage_repair_rate;
				
				array_push($final, $temp);
			}			
			return $final;
		}else{			
			return false;
		}
	}

	public function all_driver_invoice()
	{		
		$query = $this->db->get_where('pre_invoices', array('user_id' => $this->session->userdata('user_id'), 'invoice_status'=>1));
		$result = $query->result();

		$final = array();
		if($result > 0){
			foreach ($result as $key => $value) {
				$customer_id = $value->customer_id;
				$driver_id = $value->user_id;

				$this->db->select('jobs.job_code,jobs.customer_id,users.name,jobs.job_type,jobs.job_status,jobs.pre_invoicing_status,roles.role_name');
				$this->db->from('users');				
				$this->db->join('jobs', 'jobs.customer_id=users.user_id', 'left');
				$this->db->join('roles', 'roles.rid=jobs.user_type_id', 'left');
				$this->db->where('users.user_id', $customer_id);
				$cres=$this->db->get()->result();				

				$this->db->select('name');
				$this->db->from('users');
				$this->db->where('user_id', $driver_id);
				$this->db->join('jobs', 'jobs.assign_driver_id=users.user_id', 'left');
				$dres=$this->db->get()->result();

				$this->db->select_sum('tsr_damage_repair_rate');
				$this->db->from('job_cart'); 
		        $this->db->where('job_id', $value->job_id);   		        
		        $price = $this->db->get()->row();
		        				
		        				
				$temp['invoice_code'] = $value->invoice_code;	
				$temp['invoice_pdf'] = $value->invoice_pdf;
				$temp['job_code'] = $cres[0]->job_code;	
				$temp['job_id']	= $value->job_id;	
				$temp['customer_id'] = $cres[0]->customer_id;
				$temp['customer_name'] = $cres[0]->name;
				$temp['driver_name'] = $dres[0]->name;
				$temp['user_type_id'] = $cres[0]->role_name;	
				$temp['job_type'] = $cres[0]->job_type;	
				$temp['job_status']	= $cres[0]->job_status;				
				$temp['pre_invoicing_status'] = $cres[0]->pre_invoicing_status;
				$temp['tsr_damage_repair_rate'] = $price->tsr_damage_repair_rate;
				
				array_push($final, $temp);
			}
			return $final;
		}else{			
			return false;
		}
	}
	
	public function get_job_details_by_job_id($job_id)
	{
		$this->db->select('job_id,job_code,assign_driver_id,vehicle_type,job_type,is_job_accept,job_accepted_date_time,job_completion_date_time');
		$query=$this->db->get_where('jobs', ['job_id'=>$job_id]);

		if ($query->num_rows() >0) {
			$result = $query->row();
			date_default_timezone_set('UTC');
			
      $datetime1 = new DateTime($result->job_accepted_date_time);
			$datetime2 = new DateTime($result->job_completion_date_time);
			$interval = $datetime1->diff($datetime2);
			$total_hours = $interval->format('%h').":".$interval->format('%i');

      $th = explode(':', $total_hours);
      $h=$th[0];
      $m =$th[1];

      $this->db->select('j.vehicle_type,tdr.tsr_car_driven_price,tdr.tsr_car_driven_hours,tdr.tsr_car_driven_additional_price,tdr.tsr_car_driven_additional_hours,tdr.tsr_track_driven_price,tdr.tsr_track_driven_hours,tdr.tsr_track_driven_additional_price,tdr.tsr_track_driven_additional_hours');
      $this->db->from('tsr_driven_rates tdr');
      $this->db->join('jobs j', 'j.assign_driver_id=tdr.tsr_driven_driver_id', 'left');
      $this->db->where('j.job_id',$job_id);
      $rd=$this->db->get()->row();

      if($m<= 60)
      { 
      	$ah=1; // if have minute then assign an hour
      }
      $th = ($h + $ah); // total hours
      if($rd->vehicle_type == 'Car')
      {      	      	
			if($th >= $rd->tsr_car_driven_hours){
	        	$car_normal_rate = ($h * $rd->tsr_car_driven_price);
	        	$car_additional_hour_rate = $rd->tsr_car_driven_additional_price;
	        }else{
	        	$car_normal_rate = ($h * $rd->tsr_car_driven_price);
	        	$car_additional_hour_rate = 0;
	        }
	      	
	      	return [
	          	'job_id'=>$result->job_id,
	          	'job_code'=>$result->job_code,
	          	'job_type'=>$result->job_type,
	          	'is_job_accept'=>$result->is_job_accept,
	          	'job_date'=>$result->job_accepted_date_time,
	          	'due_date'=>$result->job_completion_date_time,
	          	'total_hour'=>$total_hours,
	          	'vehicle_type'=>$rd->vehicle_type,
	          	'car_normal_rate'=>$car_normal_rate,           	
	          	'car_additional_rate'=>$car_additional_hour_rate,
	          	'truck_normal_rate'=>'', 
	          	'truck_additional_rate'=>'',          	
	          	'total_rate'=>($car_normal_rate + $car_additional_hour_rate)
          	];

      }else{      		

      		if($th >= $rd->tsr_car_driven_hours){
	        	$truck_normal_rate = ($h * $rd->tsr_track_driven_price);
	        	$truck_additional_hour_rate = $rd->tsr_track_driven_additional_price;
	        }else{
	        	$truck_normal_rate = ($h * $rd->tsr_track_driven_price);
	        	$truck_additional_hour_rate = 0;
	        } 

      		return [
	          	'job_id'=>$result->job_id,
	          	'job_code'=>$result->job_code,
	          	'job_type'=>$result->job_type,
	          	'is_job_accept'=>$result->is_job_accept,
	          	'job_date'=>$result->job_accepted_date_time,
	          	'due_date'=>$result->job_completion_date_time,
	          	'total_hour'=>$total_hours,
	          	'vehicle_type'=>$rd->vehicle_type,
	          	'car_normal_rate'=>'',           	
	          	'car_additional_rate'=>'',
	          	'truck_normal_rate'=> $truck_normal_rate, 
	          	'truck_additional_rate'=> $truck_additional_hour_rate,          	
	          	'total_rate'=>($truck_normal_rate + $truck_additional_hour_rate)
	        ];
      }    	      
		}else{
			return false;
		}
	}

	public function get_job_details_by_job_id_old($job_id)
	{
		$this->db->select('job_type,is_job_accept,job_accepted_date_time,job_completion_date_time');
		$query=$this->db->get_where('jobs', ['job_id'=>$job_id]);

		if ($query->num_rows() >0) {
			return $query->row();
		}else{
			return false;
		}
	}

	/* Save Invoice */
	// save pre invoice
	public function save_pre_invoice($job_id, $user_id, $customer_id)
	{
		$this->db->select('user_type_id');
		$customer_type = $this->db->get_where('jobs', ['job_id'=>$job_id])->row();
		if($customer_type->user_type_id == 5)
		{
			// for damage_missing_total_price
			$this->db->select_sum('tsr_damage_repair_rate');
			$dmp = $this->db->get_where('job_cart', ['job_id'=>$job_id, 'tsr_layout_type'=>'RED Inspection Sheet'])->row();
			$damage_missing_total_price = $dmp->tsr_damage_repair_rate;

			// for damage_missing_total_price
			$this->db->select_sum('tsr_damage_repair_rate');
			$dmp = $this->db->get_where('job_cart', ['job_id'=>$job_id, 'tsr_layout_type'=>'Interior Layout'])->row();
			$interior_damage_total_price = $dmp->tsr_damage_repair_rate;

			// for damage_missing_total_price
			$this->db->select_sum('tsr_damage_repair_rate');
			$dmp = $this->db->get_where('job_cart', ['job_id'=>$job_id, 'tsr_layout_type'=>'Exterior Layout'])->row();
			$exterior_damage_total_price = $dmp->tsr_damage_repair_rate;
		}else{
			$damage_missing_total_price = 0;
			$interior_damage_total_price = 0;
			$exterior_damage_total_price = 0;
		}

		$this->db->select('pre_invoice_id,vehicle_washing_receipt,toll_fee_receipt');
		$query=$this->db->get_where('pre_invoices', ['job_id'=>$job_id]);
		if($query->num_rows() > 0)
		{
			// update invoice details
			$result = $query->row();
			
			$cwr=$result->vehicle_washing_receipt;
			$tfr=$result->toll_fee_receipt;

			if (!empty($_FILES['vehicle_washing_receipt']['name']))
	    	{
		      //upload configuration
		      $config['upload_path']   = 'uploads/receipts/';                
		      $config['allowed_types'] = 'gif|jpg|png|jpeg';
		      $config['encrypt_name'] = TRUE;	        
		      $this->load->library('upload', $config);

		      if ($this->upload->do_upload('vehicle_washing_receipt'))
		      {
		      	$uploadData = $this->upload->data();
		        $vehicle_washing_receipt = $uploadData['file_name'];	                
		      }else{
		      	$vehicle_washing_receipt = "";
		      }
			}else{
				$vehicle_washing_receipt = "";
			}

	      	if (!empty($_FILES['toll_fee_receipt']['name']))
	      	{
	          //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        $config['encrypt_name'] = TRUE;        
		        $this->load->library('upload', $config);

	          if ($this->upload->do_upload('toll_fee_receipt'))
	          {
	          	$uploadData = $this->upload->data();
	            $toll_fee_receipt = $uploadData['file_name'];	                
	          }else{
	          	$toll_fee_receipt = "";	
	          }
	      	}else{
	      		$toll_fee_receipt = "";
	      	}

     		$data = [
				'job_date' => $this->input->post('job_date'),
				'due_date' => $this->input->post('due_date'),
				'movement_type' => $this->input->post('movement_type'),
				'labour_charge' => $this->input->post('labour_charge'),
				'total_hours' => $this->input->post('total_hours'),
				'vehicle_driven_charge' => $this->input->post('vehicle_driven_charge'),
				'vehicle_washing_charge' => $this->input->post('vehicle_washing_charge'),
				'vehicle_washing_receipt' => $vehicle_washing_receipt,
				'toll_fee' => $this->input->post('toll_fee'),
				'toll_fee_receipt' => $toll_fee_receipt,
				'others' => $this->input->post('others'),
				'damage_missing_total_price' => $damage_missing_total_price,
				'interior_damage_total_price' => $interior_damage_total_price,
				'exterior_damage_total_price' => $exterior_damage_total_price,
				'pre_invoice_modified' => date('Y-m-d')
			];

			$this->db->update('pre_invoices', $data, array('pre_invoice_id'=>$result->pre_invoice_id));
			if($this->db->affected_rows() > 0)
			{
				if($cwr != NULL)
				{
					unlink('uploads/receipts/'.$result->car_washing_receipt);
				}
				if($tfr != NULL)
				{
					unlink('uploads/receipts/'.$result->toll_fee_receipt);	
				}								

				return TRUE;
			}else{
				return FALSE;
			}

		}else{
			// insert invoice details			
			if (!empty($_FILES['vehicle_washing_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        $config['encrypt_name'] = TRUE;	        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('vehicle_washing_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	              $vehicle_washing_receipt = $uploadData['file_name'];
	            }
	        }else{
	        	$vehicle_washing_receipt = "";
	        }

	        if (!empty($_FILES['toll_fee_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        $config['encrypt_name'] = TRUE;        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('toll_fee_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	              $toll_fee_receipt = $uploadData['file_name'];
	            }
	        }else{
	        	$toll_fee_receipt = "";
	        }
	      	        					
			$data = [
						'job_id' => $job_id,
						'user_id' => $user_id,
						'customer_id' => $customer_id,
						'job_date' => $this->input->post('job_date'),
						'due_date' => $this->input->post('due_date'),
						'movement_type' => $this->input->post('movement_type'),
						'labour_charge' => $this->input->post('labour_charge'),
						'total_hours' => $this->input->post('total_hours'),
						'vehicle_driven_charge' => $this->input->post('vehicle_driven_charge'),
						'vehicle_washing_charge' => $this->input->post('vehicle_washing_charge'),
						'vehicle_washing_receipt' => $vehicle_washing_receipt,
						'toll_fee' => $this->input->post('toll_fee'),
						'toll_fee_receipt' => $toll_fee_receipt,
						'others' => $this->input->post('others'),
						'damage_missing_total_price' => $damage_missing_total_price,
						'interior_damage_total_price' => $interior_damage_total_price,
						'exterior_damage_total_price' => $exterior_damage_total_price,
						'pre_invoice_create' => date('Y-m-d')
					];
			$this->db->insert('pre_invoices', $data);
			if($this->db->insert_id()>0)
			{
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}

	public function save_invoice($job_id, $user_id, $customer_id)
	{
		$query=$this->db->get_where('invoices', ['job_id'=>$job_id]);
		if($query->num_rows() > 0)
		{
			// update invoice details
			$result = $query->row();
			
			$cwr=$result->car_washing_receipt;
			$tfr=$result->toll_fee_receipt;

			if (!empty($_FILES['car_washing_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        //$config['file_name'] = 'car_washing_receipt_'.$job_id.'_'.$user_id.'_'.$customer_id;
		        $config['encrypt_name'] = TRUE;	        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('car_washing_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	                $car_washing_receipt = $uploadData['file_name'];	                
	            }
	        }else{
	        	$car_washing_receipt = "";
	        }

	        if (!empty($_FILES['toll_fee_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        //$config['file_name'] = 'toll_fee_receipt_'.$job_id.'_'.$user_id.'_'.$customer_id;
		        $config['encrypt_name'] = TRUE;        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('toll_fee_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	                $toll_fee_receipt = $uploadData['file_name'];	                
	            }
	        }else{
	        	$toll_fee_receipt = "";
	        }

	        $data = [
				'job_date' => $this->input->post('job_date'),
				'due_date' => $this->input->post('due_date'),
				'movement_type' => $this->input->post('movement_type'),
				'labour_charge' => $this->input->post('labour_charge'),
				'total_hours' => $this->input->post('total_hours'),
				'car_washing_charge' => $this->input->post('car_washing_charge'),
				'car_washing_receipt' => $car_washing_receipt,
				'toll_fee' => $this->input->post('toll_fee'),
				'toll_fee_receipt' => $toll_fee_receipt,
				'others' => $this->input->post('others'),
				'invoice_modified' => date('Y-m-d')
			];

			$this->db->update('invoices', $data, array('invoice_id'=>$result->invoice_id));
			if($this->db->affected_rows() > 0)
			{
				$data=['pre_invoicing_status' => 1];
				$this->db->update('jobs',$data, ['job_id'=>$this->db->affected_rows()]);

				if($cwr != NULL)
				{
					unlink('uploads/receipts/'.$result->car_washing_receipt);
				}
				if($tfr != NULL)
				{
					unlink('uploads/receipts/'.$result->toll_fee_receipt);	
				}								

				return true;
			}else{
				return false;
			}

		}else{
			// insert invoice details			
			if (!empty($_FILES['car_washing_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        //$config['file_name'] = 'car_washing_receipt_'.$job_id.'_'.$user_id.'_'.$customer_id;
		        $config['encrypt_name'] = TRUE;	        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('car_washing_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	                $car_washing_receipt = $uploadData['file_name'];
	            }
	        }else{
	        	$car_washing_receipt = "";
	        }

	        if (!empty($_FILES['toll_fee_receipt']['name']))
	        {
	            //upload configuration
		        $config['upload_path']   = 'uploads/receipts/';                
		        $config['allowed_types'] = 'gif|jpg|png|jpeg';
		        //$config['file_name'] = 'toll_fee_receipt_'.$job_id.'_'.$user_id.'_'.$customer_id;
		        $config['encrypt_name'] = TRUE;        
		        $this->load->library('upload', $config);

	            if ($this->upload->do_upload('toll_fee_receipt'))
	            {
	            	$uploadData = $this->upload->data();
	                $toll_fee_receipt = $uploadData['file_name'];
	            }
	        }else{
	        	$toll_fee_receipt = "";
	        }
	      	        
			$data = [
				'job_id' => $job_id,
				'user_id' => $user_id,
				'customer_id' => $customer_id,
				'job_date' => $this->input->post('job_date'),
				'due_date' => $this->input->post('due_date'),
				'movement_type' => $this->input->post('movement_type'),
				'labour_charge' => $this->input->post('labour_charge'),
				'total_hours' => $this->input->post('total_hours'),
				'car_washing_charge' => $this->input->post('car_washing_charge'),
				'car_washing_receipt' => $car_washing_receipt,
				'toll_fee' => $this->input->post('toll_fee'),
				'toll_fee_receipt' => $toll_fee_receipt,
				'others' => $this->input->post('others'),				
				'invoice_create' => date('Y-m-d')
			];
			$this->db->insert('invoices', $data);
			if($this->db->insert_id()>0)
			{
				$data=['pre_invoicing_status' => 1];
				$this->db->update('jobs',$data,['job_id'=>$this->db->insert_id()]);

				return true;
			}else{
				return false;
			}
		}
	}

	public function get_invoice_details($job_id, $user_id, $customer_id)
	{
		/* get data from invoices table*/		
		$query=$this->db->get_where('invoices', ['job_id'=>$job_id,'user_id'=>$user_id,'customer_id'=>$customer_id]);		
		
		$invoice=$query->result();
		return $invoice;
	}
	public function get_job_details($job_id, $user_id, $customer_id)
	{
		$this->db->select('users.name,users.email,users.user_address,jobs.job_created,job_sheets.customer_vehicle_reg_number,job_sheets.customer_vehicle_make_model');
		$this->db->from('jobs');
		$this->db->join('job_sheets', 'job_sheets.customer_id = jobs.customer_id');
		$this->db->join('users', 'users.user_id = jobs.customer_id');
		$this->db->where('jobs.customer_id',$customer_id);
		$this->db->where('job_sheets.customer_id',$customer_id);
		$query = $this->db->get();		
		
		$job= $query->result();
		return $job;
	}
	
	/* Generate Driver Invoice */
	public function invoice_generation($user_id, $job_id)
	{
		$result=$this->db->get_where('job_cart', ['user_id'=>$user_id,'job_id'=>$job_id])->result();
		if ($result > 0) {
			return $result;
		}else{
			return false;
		}
	} 

	/* driver invoice archive */
	function driver_invoice_archive()
	{

	}

	public function invoice_num ($input, $pad_len = 7, $prefix = null) {
      if ($pad_len <= strlen($input))
          trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);

      if (is_string($prefix))
          return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

      return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
  	}
  	

  	/* get driver invoice details */
	function get_driver_invoice($user_id)
	{
		$this->db->order_by('invoice_id', 'ASC');
		$results = $this->db->get_where('invoices', ['invoice_status'=>1])->result();

		$iva = [];
		foreach ($results as $key => $iv) {
			$jobs = $iv->job_id;
			$jobs_array = explode(',',$jobs);

			$jr = [];
			foreach ($jobs_array as $key => $job) {
				$this->db->select('*');
				$this->db->from('jobs j');
				$this->db->join('pre_invoices pi','pi.job_id=j.job_id');
				$this->db->where('pi.user_id',$user_id);
				$this->db->where('pi.job_id',$job);
				$ijd = $this->db->get()->row();
				
				$temp['job_id'] = $ijd->job_id;
				$temp['job_code'] = $ijd->job_code;
				$temp['job_type'] = $ijd->job_type;
				$temp['vehicle_washing_receipt'] = $ijd->vehicle_washing_receipt;
				$temp['toll_fee_receipt'] = $ijd->toll_fee_receipt;
				array_push($jr, $temp);
			}

			$tiva['invoice_id'] = $iv->invoice_id;
			$tiva['invoice_code'] = $iv->invoice_code;
			$tiva['invoice_pdf'] = $iv->invoice_pdf;
			$tiva['invoice_status'] = $iv->invoice_status;
			$tiva['jrd'] = $jr;
			array_push($iva, $tiva);
		}
		return $iva;
	}

	// view pdf
	function get_pdf_invoice($invoice_id)
	{
		$this->db->select('invoice_pdf');
		$inv = $this->db->get_where('invoices', ['invoice_id'=>$invoice_id])->row();
		return $inv;
	}
/* End of Driver Model*/
}