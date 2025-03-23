<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Physio extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 30000);
        set_time_limit(0);
        $this->load->model('physio_model', 'physio');
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


    /* PHYSIO ROUTINBE GROUP MANAGEMENT */
    public function physioprogram()
    {
        $this->db->from('physio_program w');
        /* $this->db->join('physio_level g', 'g.id = w.physio_level_id', 'left'); */
        $this->db->select('w.*');
        $query = $this->db->get();
        $data['resultLog'] = $query->result();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/physioprogram', $data);
    }

    public function physioAddUpdateProgram($id = 0)
    {

        $this->db->from('physio_program');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/physioprogramaddnew', $data);
    }

    public function addUpdateProgramData()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');

            

            $dataPost = array(
                'title' => $this->input->post('title'),
                'no_of_week' => $this->input->post('no_of_week'),
                'description' => trim($this->input->post('description')),
                'status' => $this->input->post('status'),
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
                $this->db->update('physio_program');

                $program_id = $id;
            } else {
                $this->db->insert('physio_program', $dataPost);

                $program_id = $this->db->insert_id();
            }

            if (isset($program_id) && $program_id > 0) {

                $return['status'] = 1;
                $return['message'] = "Program data successfully updated";

                $this->session->set_flashdata('msg', 'Program data successfully updated');
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

    public function deleteProgram($id)
    {
        $this->db->delete('physio_program', array('id' => $id));
        $this->session->set_flashdata('msg', 'Program data successfully updated');

        redirect('admin/physio/program');
    }

    /* PHYSIO MANAGEMENT */
    public function index()
    {

        $data['resultLog'] = $this->physio->fetchAllExcercise();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/physio', $data);
    }


    public function addUpdateExcercise($id = 0)
    {
        $this->db->from('physio_program');
        $this->db->select('*');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        $data['programLog'] = $query->result();

        $this->db->from('workout_equipments');
        $this->db->select('*');
        $this->db->order_by('equipment_name', 'asc');
        $query = $this->db->get();
        $data['equipmentLog'] = $query->result();
        
        $this->db->from('workout_musclegroup');
        $this->db->select('*');
        $this->db->order_by('muscle_type', 'asc');
        $query = $this->db->get();
        $data['muscleLog'] = $query->result();

        $this->db->from('physio_exercise');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/physioaddnew', $data);
    }
    
    public function addDuplicateExcercise($id = 0)
    {
        $this->db->from('physio_program');
        $this->db->select('*');
        $this->db->order_by('title', 'asc');
        $query = $this->db->get();
        $data['programLog'] = $query->result();

        $this->db->from('workout_equipments');
        $this->db->select('*');
        $this->db->order_by('equipment_name', 'asc');
        $query = $this->db->get();
        $data['equipmentLog'] = $query->result();
        
        $this->db->from('workout_musclegroup');
        $this->db->select('*');
        $this->db->order_by('muscle_type', 'asc');
        $query = $this->db->get();
        $data['muscleLog'] = $query->result();

        $this->db->from('physio_exercise');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query1 = $this->db->get();
        $data['resultLog'] = $query1->row();
        $data['resultLog']->id = 0;

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/physioaddnew', $data);
    }

    public function addUpdatePhysioData()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');

            $equipments = $this->input->post('equipments');
            $musclegroup = $this->input->post('musclegroup');

            $dataPost = array(
                'program_id' => $this->input->post('program_id'),
                'title' => trim($this->input->post('title')),
                'tipes' => trim($this->input->post('tipes')),
                'description' => trim($this->input->post('description')),
                'week_no' => $this->input->post('week_no'),
                'day_no' => $this->input->post('day_no'),
                'equipments' => implode(', ', $equipments),
                'musclegroup' => implode(', ', $musclegroup),
                'rep' => $this->input->post('rep'),
                'sets' => $this->input->post('sets'),
                'worktime' => $this->input->post('worktime'),
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
                $dataPost['image_thumb'] = $this->imageResize($imgName, $width, $height, $folder);
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
                $this->db->update('physio_exercise');

                $program_id = $id;
            } else {
                $this->db->insert('physio_exercise', $dataPost);

                $program_id = $this->db->insert_id();
            }

            if (isset($program_id) && $program_id > 0) {

                $return['status'] = 1;
                $return['message'] = "Physio data successfully updated";

                $this->session->set_flashdata('msg', 'Physio data successfully updated');
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
        $this->db->delete('physio_exercise', array('id' => $id));
        $this->session->set_flashdata('msg', 'Physio data successfully updated');

        redirect('admin/physio');
    }
}
