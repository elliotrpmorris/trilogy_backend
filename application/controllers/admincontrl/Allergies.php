<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Allergies extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function viewAllergies()
    {

        $query = $this->db->get('allergies');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/allergies',$data);
    }


    public function selectAllergiesById()
    {
        $id = $this->input->post('id');

        $this->db->from('allergies');
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

    public function addUpdateAllergies()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'allergies_name' => trim($this->input->post('allergies_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('allergies');
            } else {
                $this->db->insert('allergies', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Allergies data successfully updated";

            $this->session->set_flashdata('msg','Allergies data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteAllergies($id)
    {
        $this->db->delete('allergies', array('id' => $id));
        $this->session->set_flashdata('msg', 'Allergies data successfully updated');

        redirect('admin/allergies');
    }
}
