<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Food extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function viewFoodType()
    {

        $query = $this->db->get('food_type');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/foodtype',$data);
    }


    public function selectFoodTypeById()
    {
        $id = $this->input->post('id');

        $this->db->from('food_type');
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

    public function addUpdateFoodType()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'food_type_name' => trim($this->input->post('food_type_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('food_type');
            } else {
                $this->db->insert('food_type', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Food type data successfully updated";

            $this->session->set_flashdata('msg','Food type data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteFoodType($id)
    {
        $this->db->delete('food_type', array('id' => $id));
        $this->session->set_flashdata('msg', 'Food type data successfully updated');

        redirect('admin/foodtype');
    }
}
