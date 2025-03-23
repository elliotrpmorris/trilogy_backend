<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Healthproblem extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function viewHealthProblem()
    {

        $query = $this->db->get('health_problem');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/healthproblem',$data);
    }


    public function selectHealthProblemById()
    {
        $id = $this->input->post('id');

        $this->db->from('health_problem');
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

    public function addUpdateHealthProblem()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'health_problem_name' => trim($this->input->post('health_problem_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('health_problem');
            } else {
                $this->db->insert('health_problem', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Health Problem data successfully updated";

            $this->session->set_flashdata('msg','Health Problem data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteHealthProblem($id)
    {
        $this->db->delete('health_problem', array('id' => $id));
        $this->session->set_flashdata('msg', 'Health Problem data successfully updated');

        redirect('admin/healthproblem');
    }
}
