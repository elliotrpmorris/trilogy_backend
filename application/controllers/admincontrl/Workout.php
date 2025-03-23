<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Workout extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 30000);
        set_time_limit(0);
        $this->load->model('workout_model', 'workout');
        $this->load->library('image_lib');
    }

    public function cleanimage($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9_.\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '_', $string); // Replaces multiple hyphens with single one.
    }

    function imageResize($imgName, $width, $height, $folder)
    {
        $img_path =  'uploads/';

        // Configuration
        $config['image_library'] = 'gd2';
        $config['source_image'] = $img_path . 'image/' . $imgName;
        $config['new_image'] = $img_path . $folder . '/' . time() . strtolower($this->cleanimage($imgName));
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = "";
        $config['width'] = $width;
        $config['height'] = $height;
        $config['quality'] = '72';

        // Load the Library

        $this->image_lib->initialize($config);
        // resize image
        $this->image_lib->resize();
        // handle if there is any problem
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }

        $this->image_lib->clear();

        return $config['new_image'];
    }


    /* WORKOUT ROUTINBE GROUP MANAGEMENT */
    public function workoutroutine()
    {
        $this->db->from('workout_routine w');
        $this->db->join('workout_level g', 'g.id = w.workout_level_id', 'left');
        $this->db->select('w.*, g.workout_level_name');
        $query = $this->db->get();
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutroutine', $data);
    }

    public function workoutAddUpdateRoutine($id = 0)
    {

        $this->db->from('workout_routine');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();

        $this->db->from('workout_level');
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $queryPack = $this->db->get();
        $data['workGroupLog'] = $queryPack->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutroutineaddnew', $data);
    }

    public function addUpdateRoutineData()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');

            

            $dataPost = array(
                'title' => $this->input->post('title'),
                'no_of_week' => $this->input->post('no_of_week'),
                'description' => trim($this->input->post('description')),
                /* 'min_time' => $this->input->post('min_time'),
                'max_time' => $this->input->post('max_time'),
                'min_calorie' => $this->input->post('min_calorie'),
                'max_calorie' => $this->input->post('max_calorie'), */
                'status' => $this->input->post('status'),
                'workout_level_id' => $this->input->post('workout_level_id'),
            );

            $file_name = $_FILES['image']['name'];
            $new_file_name = time() . $this->cleanimage($file_name);

            $config =  array(
                'upload_path'     => 'uploads/image/',
                'allowed_types'   => "gif|jpg|png|jpeg",
                'overwrite'       => TRUE,
                'file_name'        => $new_file_name
            );

            $this->load->library('upload', $config);
            $field_name = "image";
            if (!$this->upload->do_upload($field_name)) {
                $data1['pic'] = '';
            } else {
                $data1['pic'] = $this->upload->data();
                $dataPost['image'] = 'uploads/image/' . $data1['pic']['file_name'];

                $actual_name = $dataPost['image'];
                $explode = explode("uploads/image/", $actual_name);
            }

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout_routine');

                $routine_id = $id;
            } else {
                $this->db->insert('workout_routine', $dataPost);

                $routine_id = $this->db->insert_id();
            }

            if (isset($routine_id) && $routine_id > 0) {

                $return['status'] = 1;
                $return['message'] = "Routine data successfully updated";

                $this->session->set_flashdata('msg', 'Routine data successfully updated');
            } else {
                $return['status'] = 0;
                $return['message'] = "Sorry!! Something went wrong. Please try again.";
            }
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteRoutine($id)
    {
        $this->db->delete('workout_routine', array('id' => $id));
        $this->session->set_flashdata('msg', 'Routine data successfully updated');

        redirect('admin/workout/routine');
    }

    /* WORKOUT MANAGEMENT */
    public function index()
    {

        $data['resultLog'] = $this->workout->fetchAllExcercise();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workouts', $data);
    }


    public function addUpdateExcercise($id = 0)
    {
        $this->db->from('workout_routine');
        $this->db->select('*');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        $data['routineLog'] = $query->result();

        /* $this->db->from('workout_types');
        $this->db->select('*');
        $this->db->order_by('workout_type', 'asc');
        $query = $this->db->get();
        $data['typeLog'] = $query->result(); */

        $this->db->from('workout_equipments');
        $this->db->select('*');
        $this->db->order_by('equipment_name', 'asc');
        $query = $this->db->get();
        $data['equipmentLog'] = $query->result();

        $this->db->from('workout_level');
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $queryPack = $this->db->get();
        $data['workGroupLog'] = $queryPack->result();
        
        /* $this->db->from('workout_coach');
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $queryPack = $this->db->get();
        $data['coachLog'] = $queryPack->result(); */

        $this->db->from('workout_musclegroup');
        $this->db->select('*');
        $this->db->order_by('muscle_type', 'asc');
        $query = $this->db->get();
        $data['muscleLog'] = $query->result();

        $this->db->from('workout');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutaddnew', $data);
    }

    public function duplicateExcercise($id = 0)
    {
        $this->db->from('workout_routine');
        $this->db->select('*');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        $data['routineLog'] = $query->result();
        

        /* $this->db->from('workout_types');
        $this->db->select('*');
        $this->db->order_by('workout_type', 'asc');
        $query = $this->db->get();
        $data['typeLog'] = $query->result(); */

        $this->db->from('workout_equipments');
        $this->db->select('*');
        $this->db->order_by('equipment_name', 'asc');
        $query = $this->db->get();
        $data['equipmentLog'] = $query->result();

        $this->db->from('workout_level');
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $queryPack = $this->db->get();
        $data['workGroupLog'] = $queryPack->result();
        
        /* $this->db->from('workout_coach');
        $this->db->select('*');
        $this->db->order_by('id', 'asc');
        $queryPack = $this->db->get();
        $data['coachLog'] = $queryPack->result(); */

        $this->db->from('workout_musclegroup');
        $this->db->select('*');
        $this->db->order_by('muscle_type', 'asc');
        $query = $this->db->get();
        $data['muscleLog'] = $query->result();

        $this->db->from('workout');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();

        $data['resultLog']->id = 0;

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/workoutaddnew', $data);
    }

    public function addUpdateWorkoutData()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');

            $equipments = $this->input->post('equipments');
            $musclegroup = $this->input->post('musclegroup');

            $dataPost = array(
                'routine_id' => $this->input->post('routine_id'),
                'workout_title' => trim($this->input->post('workout_title')),
                'workout_tipe' => trim($this->input->post('workout_tipe')),
                'workout_desc' => trim($this->input->post('workout_desc')),
                'workout_level_id' => $this->input->post('workout_level_id'),
                //'coach_id' => $this->input->post('coach_id'),
                'week_no' => $this->input->post('week_no'),
                'day_no' => $this->input->post('day_no'),
                'equipments' => implode(', ', $equipments),
                'musclegroup' => implode(', ', $musclegroup),
                'rep' => $this->input->post('rep'),
                'sets' => $this->input->post('sets'),
                'worktime' => $this->input->post('worktime'),
                'min_calorie' => $this->input->post('min_calorie'),
                'max_calorie' => $this->input->post('max_calorie'),
                'status' => $this->input->post('status'),
                'video_type' => $this->input->post('video_type'),
                'url_type' => $this->input->post('url_type'),
            );

            $file_name = $_FILES['image']['name'];
            $new_file_name = time() . $this->cleanimage($file_name);

            $config =  array(
                'upload_path'     => 'uploads/image/',
                'allowed_types'   => "gif|jpg|png|jpeg",
                'overwrite'       => TRUE,
                'file_name'        => $new_file_name
            );

            $this->load->library('upload', $config);
            $field_name = "image";
            if (!$this->upload->do_upload($field_name)) {
                $data1['pic'] = '';
            } else {
                $data1['pic'] = $this->upload->data();
                $dataPost['image'] = 'uploads/image/' . $data1['pic']['file_name'];

                $actual_name = $dataPost['image'];
                $explode = explode("uploads/image/", $actual_name);

                $imgName = $explode[1];
                $width = 250;
                $height = 250;
                $folder = 'thumb';
                $dataPost['workout_image_thumb'] = $this->imageResize($imgName, $width, $height, $folder);
            }

            if (isset($dataPost['video_type']) && $dataPost['video_type'] == "URL") {
                $dataPost['video_path'] = $this->input->post('video_path');
            } elseif (isset($dataPost['video_type']) && $dataPost['video_type'] == "file") {
                $file_name = $_FILES['video_path_file']['name'];
                $new_file_name = time() . $this->cleanimage($file_name);

                $config =  array(
                    'upload_path'     => 'uploads/image/',
                    'allowed_types'   => "*",
                    'overwrite'       => TRUE,
                    'file_name'        => $new_file_name
                );

                $this->upload->initialize($config);
                $field_name = "video_path_file";
                if (!$this->upload->do_upload($field_name)) {
                    $data1['pic'] = '';
                } else {
                    $data1['pic'] = $this->upload->data();
                    $dataPost['video_path'] = 'uploads/image/' . $data1['pic']['file_name'];
                }
            }

            if (isset($id) && $id > 0) {
                $this->db->set($dataPost);
                $this->db->where('id', $id);
                $this->db->update('workout');

                $routine_id = $id;
            } else {
                $this->db->insert('workout', $dataPost);

                $routine_id = $this->db->insert_id();
            }

            if (isset($routine_id) && $routine_id > 0) {

                $return['status'] = 1;
                $return['message'] = "Workout data successfully updated";

                $this->session->set_flashdata('msg', 'Workout data successfully updated');
            } else {
                $return['status'] = 0;
                $return['message'] = "Sorry!! Something went wrong. Please try again.";
            }
        } else {
            $return['status'] = 0;
            $return['message'] = "Sorry!! Invalid request";
        }

        echo json_encode($return);
    }

    public function deleteexcercise($id)
    {
        $this->db->delete('workout', array('id' => $id));
        $this->session->set_flashdata('msg', 'Workout data successfully updated');

        redirect('admin/workout');
    }
}
