<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Diet extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function viewDietType()
    {

        $query = $this->db->get('diet_type');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/diettype',$data);
    }


    public function selectDietTypeById()
    {
        $id = $this->input->post('id');

        $this->db->from('diet_type');
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

    public function addUpdateDietType()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'diet_type_name' => trim($this->input->post('diet_type_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('diet_type');
            } else {
                $this->db->insert('diet_type', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Diet type data successfully updated";

            $this->session->set_flashdata('msg','Diet type data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteDietType($id)
    {
        $this->db->delete('diet_type', array('id' => $id));
        $this->session->set_flashdata('msg', 'Diet type data successfully updated');

        redirect('admin/diettype');
    }
}
