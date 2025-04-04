<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Workoutmaster extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        //$this->load->model('admin_model', 'admin');
    }

    public function cleanimage($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9_.\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
    }

    /* WORKOUT TYPE */

    public function viewworkoutlevel()
    {

        $query = $this->db->get('workout_level');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutlevel', $data);
    }


    public function selectworkoutlevelById()
    {
        $id = $this->input->post('id');

        $this->db->from('workout_level');
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

    public function addUpdateworkoutlevel()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'workout_level_name' => trim($this->input->post('workout_level_name')),
                'workout_level_desc' => trim($this->input->post('workout_level_desc')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_level');
            } else {
                $this->db->insert('workout_level', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Workout level data successfully updated";

            $this->session->set_flashdata('msg', 'Workout level data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteworkoutlevel($id)
    {
        $this->db->delete('workout_level', array('id' => $id));
        $this->session->set_flashdata('msg', 'Workout type data successfully updated');

        redirect('admin/workout/workoutlevel');
    }

    /* MUSCLE GROUP MANAGEMENT */
    public function musclegroup()
    {

        $query = $this->db->get('workout_musclegroup');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/musclegroup',$data);
    }


    public function selectMuscleGroupById()
    {
        $id = $this->input->post('id');

        $this->db->from('workout_musclegroup');
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

    public function addUpdateMuscleGroup()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'muscle_type' => trim($this->input->post('muscle_type')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_musclegroup');
            } else {
                $this->db->insert('workout_musclegroup', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Muscle Group data successfully updated";

            $this->session->set_flashdata('msg','Muscle Group data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteMuscleGroup($id)
    {
        $this->db->delete('workout_musclegroup', array('id' => $id));
        $this->session->set_flashdata('msg', 'Muscle Group data successfully updated');

        redirect('admin/workout/musclegroup');
    }

    /* WORKOUT TYPE MANAGEMENT */
    public function workouttypes()
    {

        $query = $this->db->get('workout_types');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workouttypes',$data);
    }


    public function selectWorkoutTypesById()
    {
        $id = $this->input->post('id');

        $this->db->from('workout_types');
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

    public function addUpdateWorkoutTypes()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'workout_type' => trim($this->input->post('workout_type')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_types');
            } else {
                $this->db->insert('workout_types', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Workout types data successfully updated";

            $this->session->set_flashdata('msg','Workout types data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteWorkoutTypes($id)
    {
        $this->db->delete('workout_types', array('id' => $id));
        $this->session->set_flashdata('msg', 'Workout types data successfully updated');

        redirect('admin/workout/workouttypes');
    }

    /* WORKOUT EQUIPMENTS MANAGEMENT */
    public function workoutequipments()
    {

        $query = $this->db->get('workout_equipments');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutequipments',$data);
    }


    public function selectWorkoutEquipmentsById()
    {
        $id = $this->input->post('id');

        $this->db->from('workout_equipments');
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

    public function addUpdateWorkoutEquipments()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'equipment_name' => trim($this->input->post('equipment_name')),
                'status' => $this->input->post('status'),
            );

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_equipments');
            } else {
                $this->db->insert('workout_equipments', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Workout types data successfully updated";

            $this->session->set_flashdata('msg','Workout types data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteWorkoutEquipments($id)
    {
        $this->db->delete('workout_equipments', array('id' => $id));
        $this->session->set_flashdata('msg', 'Workout types data successfully updated');

        redirect('admin/workout/equipments');
    }

    /* COACH MASTER */
    public function viewworkoutcoach()
    {

        $query = $this->db->get('workout_coach');
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutcoach', $data);
    }


    public function selectworkoutcoachById()
    {
        $id = $this->input->post('id');

        $this->db->from('workout_coach');
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

    public function workoutAddUpdatecoach($id=0){
        $this->db->from('workout_coach');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();
        /* echo "<pre>";
        print_r($data['resultLog']);
        die(); */

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutcoachaddnew', $data);
    }

    public function addUpdateworkoutcoach()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');
            $dataPost = array(
                'workout_coach_name' => trim($this->input->post('workout_coach_name')),
                'workout_coach_desc' => trim($this->input->post('workout_coach_desc')),
                'status' => $this->input->post('status'),
            );

            $file_name = $_FILES['coach_image']['name'];
            $new_file_name = time() . $this->cleanimage($file_name);

            $config =  array(
                'upload_path'     => 'uploads/image/',
                'allowed_types'   => "gif|jpg|png|jpeg",
                'overwrite'       => TRUE,
                'file_name'        => $new_file_name
            );

            $this->load->library('upload', $config);
            $field_name = "coach_image";
            if (!$this->upload->do_upload($field_name)) {
                $data1['pic'] = '';
            } else {
                $data1['pic'] = $this->upload->data();
                $dataPost['coach_image'] = 'uploads/image/' . $data1['pic']['file_name'];
            }

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_coach');
            } else {
                $this->db->insert('workout_coach', $dataPost);
            }
            $return['status'] = 1;
            $return['message'] = "Coach data successfully updated";

            $this->session->set_flashdata('msg', 'Coach data successfully updated');
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteworkoutcoach($id)
    {
        $this->db->delete('workout_coach', array('id' => $id));
        $this->session->set_flashdata('msg', 'Workout type data successfully updated');

        redirect('admin/workout/workoutcoach');
    }
}
