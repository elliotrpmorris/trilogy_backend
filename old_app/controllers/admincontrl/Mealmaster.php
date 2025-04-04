<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mealmaster extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 30000);
        set_time_limit(0);
        $this->load->model('meal_model', 'meals');
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

    public function index()
    {
        /* $query = $this->db->query("SELECT `id`, `food_type` FROM `meals` WHERE 1");
        $row = $query->result();

        if (isset($row) && !empty($row)) {
            foreach ($row as $v) {
                $foodTypeDb = $v->food_type;
                $foodType = explode(', ', $foodTypeDb);

                $food_type_id = '';
                if (isset($foodType) && !empty($foodType)) {
                    foreach ($foodType as $key => $val) {
                        $queryFood = $this->db->query("SELECT `id` FROM `food_type` WHERE lower(food_type_name) = '" . strtolower(trim($val)) . "'");
                        $rowFood = $queryFood->row();
                        $food_type_id = $food_type_id . $rowFood->id . ',';
                    }
                }

                $this->db->query("UPDATE `meals` SET `food_type_id` = '".$food_type_id."' WHERE `id` = '".$v->id."'");
            }
        } */

        $result = $this->meals->fetchMealData();
        $data['resultLog'] = $result['resultLog'];
        $data['nutritionLog'] = $result['nutritionLog'];

        $data['msg'] = $this->session->flashdata('msg');

        $this->load->view('admin/mealsmaster', $data);
    }

    public function addNewMeals()
    {
        $query = $this->db->get('meal_type');
        $data['mealTypeLog'] = $query->result();

        $query = $this->db->get('food_type');
        $data['foodTypeLog'] = $query->result();

        $query = $this->db->get('nutrition');
        $data['nutritionLog'] = $query->result();

        $query = $this->db->get('diet_type');
        $data['dietTypeLog'] = $query->result();

        $this->load->view('admin/mealsmasteraddnew', $data);
    }

    public function updateMeals($id)
    {
        $query = $this->db->get('meal_type');
        $data['mealTypeLog'] = $query->result();

        $query = $this->db->get('food_type');
        $data['foodTypeLog'] = $query->result();

        $query = $this->db->get('nutrition');
        $data['nutritionLog'] = $query->result();

        $query = $this->db->get('diet_type');
        $data['dietTypeLog'] = $query->result();

        $this->db->from('meals');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $data['resultLog'] = $query->row();

        $this->db->from('meals_ingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['ingredientLog'] = $query->result();

        $this->db->from('meals_nutritions');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealNutriLog'] = $query->result();

        $this->db->from('meals_extraingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealExtraLog'] = $query->result();

        $this->load->view('admin/mealsmasteraddnew', $data);
    }
    
    public function duplicateMeal($id)
    {
        $query = $this->db->get('meal_type');
        $data['mealTypeLog'] = $query->result();

        $query = $this->db->get('food_type');
        $data['foodTypeLog'] = $query->result();

        $query = $this->db->get('nutrition');
        $data['nutritionLog'] = $query->result();
        
        $query = $this->db->get('diet_type');
        $data['dietTypeLog'] = $query->result();

        $this->db->from('meals');
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $data['resultLog'] = $query->row();

        $data['resultLog']->id = 0;

        $this->db->from('meals_ingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['ingredientLog'] = $query->result();

        $this->db->from('meals_nutritions');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealNutriLog'] = $query->result();

        $this->db->from('meals_extraingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealExtraLog'] = $query->result();

        $this->load->view('admin/mealsmasteraddnew', $data);
    }


    public function addUpdateMealsData()
    {
        if ($this->input->method() == "post") {
            $id = $this->input->post('id');

            $foodType = $this->input->post('food_type');

            $food_type_id = '';
            if (isset($foodType) && !empty($foodType)) {
                foreach ($foodType as $key => $val) {
                    $queryFood = $this->db->query("SELECT `id` FROM `food_type` WHERE lower(food_type_name) = '" . strtolower(trim($val)) . "'");
                    $rowFood = $queryFood->row();
                    $food_type_id = $food_type_id . $rowFood->id . ',';
                }
            }

            $dataPost = array(
                'meal_type_id' => $this->input->post('meal_type_id'),
                'meal_title' => trim($this->input->post('meal_title')),
                'meal_desc' => $this->input->post('meal_desc'),
                'prep_time' => $this->input->post('prep_time'),
                'cooking_time' => $this->input->post('cooking_time'),
                'status' => $this->input->post('status'),
                'food_type' => implode(', ', $foodType),
                'food_type_id' => $food_type_id,
                'meal_tips' => $this->input->post('meal_tips'),
                'min_calorie' => $this->input->post('min_calorie'),
                'max_calorie' => $this->input->post('max_calorie'),
                'cooking_method' => $this->input->post('cooking_method'),
                'video_type' => $this->input->post('video_type'),
                'diet_type' => $this->input->post('diet_type'),
            );

            $file_name = $_FILES['meal_image']['name'];
            $new_file_name = time() . $this->cleanimage($file_name);

            $config =  array(
                'upload_path'     => 'uploads/image/',
                'allowed_types'   => "gif|jpg|png|jpeg",
                'overwrite'       => TRUE,
                'file_name'        => $new_file_name,
				'max_size' => 1024 * 10,
            );

            $this->load->library('upload', $config);
            $field_name = "meal_image";
            if (!$this->upload->do_upload($field_name)) {
                $data1['pic'] = '';
            } else {
                $data1['pic'] = $this->upload->data();
                $dataPost['meal_image'] = 'uploads/image/' . $data1['pic']['file_name'];

                $actual_name = $dataPost['meal_image'];
                $explode = explode("uploads/image/", $actual_name);

                $imgName = $explode[1];
                $width = 250;
                $height = 250;
                $folder = 'thumb';
                $dataPost['meal_image_thumb'] = $this->imageResize($imgName, $width, $height, $folder);
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
                    'file_name'        => $new_file_name,
					'max_size' => 1024 * 10,
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
                $this->db->update('meals');

                $meal_id = $id;
            } else {
                $this->db->insert('meals', $dataPost);

                $meal_id = $this->db->insert_id();
            }

            if (isset($meal_id) && $meal_id > 0) {

                $dataIngredient = array(
                    'meal_id' => $meal_id,
                    'ingredient_name' => $this->input->post('ingredient_name'),
                    'min_amt' => $this->input->post('min_amt'),
                    'max_amt' => $this->input->post('max_amt'),
                    'calorie_per_gm' => $this->input->post('calorie_per_gm'),
                    'ing_min_calorie' => $this->input->post('ing_min_calorie'),
                    'ing_max_calorie' => $this->input->post('ing_max_calorie'),
                    'unit_of_measure' => $this->input->post('unit_of_measure'),
                    'per_of_meal' => $this->input->post('per_of_meal'),
                );

                $this->meals->addIngredients($dataIngredient);

                $dataNutritions = array(
                    'meal_id' => $meal_id,
                    'nutrition_name' => $this->input->post('nutrition_name'),
                    'nutrition_amount' => $this->input->post('nutrition_amount'),
                );

                $this->meals->addNutritions($dataNutritions);

                $dataExtrIng = array(
                    'meal_id' => $meal_id,
                    'extra_ingredient_name' => $this->input->post('extra_ingredient_name'),
                    'extra_amt' => $this->input->post('extra_amt'),
                    'extra_measure' => $this->input->post('extra_measure'),
                );

                $this->meals->addExtraIngredient($dataExtrIng);

                $return['status'] = 1;
                $return['message'] = "Meals data successfully updated";

                $this->session->set_flashdata('msg', 'Meals data successfully updated');
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

    public function fetchMealsDetails()
    {
        $id = $this->input->post('id');

        $this->db->from('meals m');
        $this->db->select('m.*, t.meal_type_name');
        $this->db->join('meal_type t', 'm.meal_type_id = t.id', 'left');
        $this->db->where('m.id', $id);
        $query = $this->db->get();
        $data['resultLog'] = $query->row();

        $this->db->from('meals_ingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['ingredientLog'] = $query->result();

        $this->db->from('meals_nutritions');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealNutriLog'] = $query->result();

        $this->db->from('meals_extraingredient');
        $this->db->select('*');
        $this->db->where('meal_id', $id);
        $query = $this->db->get();
        $data['mealExtraLog'] = $query->result();

        $this->load->view('admin/mealsviewdetails', $data);
    }

    public function deleteMealType($id)
    {
        $this->db->delete('meal_type', array('id' => $id));
        $this->session->set_flashdata('msg', 'Meal type data successfully updated');

        redirect('admin/mealtype');
    }
}
