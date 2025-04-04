<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nutrition extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function index()
    {

        $query = $this->db->get('nutrition');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/nutrition',$data);
    }


    public function selectNutritionById()
    {
        $id = $this->input->post('id');

        $this->db->from('nutrition');
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

    public function addUpdateNutrition()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'nutrition_name' => trim($this->input->post('nutrition_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('nutrition');
            } else {
                $this->db->insert('nutrition', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Nutrition data successfully updated";

            $this->session->set_flashdata('msg','Nutrition data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteNutrition($id)
    {
        $this->db->delete('nutrition', array('id' => $id));
        $this->session->set_flashdata('msg', 'Nutrition data successfully updated');

        redirect('admin/nutrition');
    }
}
