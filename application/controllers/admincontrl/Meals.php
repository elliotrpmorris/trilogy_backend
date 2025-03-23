<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meals extends CI_Controller
{
    public function __construct()

    {
		ini_set('memory_limit', '-1');
		ini_set('upload_max_filesize', '200M');
		ini_set('post_max_size', '200M');
		ini_set('max_input_time', 30000);
		set_time_limit(0);
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function viewMealType()
    {

        $query = $this->db->get('meal_type');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/mealtype', $data);
    }


    public function selectMealTypeById()
    {
        $id = $this->input->post('id');

        $this->db->from('meal_type');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $returnData = $query->row();

        if ($returnData) {
            $return['status'] = 1;
            $return['message'] = "Successful";
            $return['returnData'] = $returnData;
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! No data found";
        }

        echo json_encode($return);
    }

    public function addUpdateMealType()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'meal_type_name' => trim($this->input->post('meal_type_name')),
                'meal_type_desc' => trim($this->input->post('meal_type_desc')),
                //'calorie_per' => trim($this->input->post('calorie_per')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('meal_type');
            } else {
                $this->db->insert('meal_type', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Meal type data successfully updated";

            $this->session->set_flashdata('msg', 'Meal type data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteMealType($id)
    {
        $this->db->delete('meal_type', array('id' => $id));
        $this->session->set_flashdata('msg', 'Meal type data successfully updated');

        redirect('admin/mealtype');
    }
	
	public function deletemeal($id){
		$this->db->query("delete from `meals` where `id` = '".$id."'");
		$this->db->query("delete from `customer_meal` where `meal_id` = '".$id."'");
		
		redirect('admin/meals');
	}

}
