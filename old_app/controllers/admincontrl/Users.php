<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()

    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 30000);
        set_time_limit(0);
        $this->CI = &get_instance();
        $this->load->model('User_model', 'user');
        $this->load->model('Meal_model', 'meal');
    }

    public function calculateBmr($age, $height, $weight, $sex, $activity)
    {
        if (trim($sex) == "Male") {
            $otrFactor = 66.5;
            $ageCal = 6.75 * $age;
            $heightCal = 5.003 * $height;
            $weightCal = 13.75 * $weight;

            $calorie = ((($otrFactor + $weightCal + $heightCal) - $ageCal) * $activity) + 400;
        } elseif (trim($sex) == "Female") {
            $otrFactor = 655.1;
            $ageCal = 4.676 * $age;
            $heightCal = 1.850 * $height;
            $weightCal = 9.563 * $weight;

            $calorie = ((($otrFactor + $weightCal + $heightCal) - $ageCal) * $activity) + 400;
        } else {
            $calorie = "NA";
        }

        return $calorie;
    }

    public function viewpayments()
    {

        $data = array(

            'page'        => 'blank',

            'mode'        => 'users',

            'error'        => '',

            'message'    => '',
            'title' => 'Payment History',

        );

        /* $this->db->from('customer_package p');
            $this->db->select('p.*, b.batch_name, c.name, c.email_id');
            $this->db->join('customer c', 'p.cust_id = c.id', 'left');
            $this->db->join('six_week_batch  b', 'b.id = p.batch_id', 'left');
            $this->db->order_by("p.id", "desc"); */

        $query = $this->db->query("SELECT p.*, b.batch_name, c.name, c.email_id FROM
         customer_package p
         LEFT JOIN customer c ON p.cust_id = c.id
         LEFT JOIN six_week_batch  b ON b.id = p.batch_id
        WHERE 1 ORDER BY p.id DESC");
        $data['userLog'] = $query->result();

        $this->load->view('admin/payments', $data);
    }

    public function viewUser($type = "")
    {

        if (isset($type) && $type == "sixweek") {
            $title = '6 weeks transformation';
            $packId = 2;
        } elseif (isset($type) && $type == "onetoone") {
            $title = '1 to 1 with Jordon';
            $packId = 1;
        } elseif (isset($type) && $type == "lifestyle") {
            $title = 'Life Style';
            $packId = 3;
        } else {
            $title = 'All Users';
            $packId = 0;
        }

        $data = array(

            'page'        => 'blank',

            'mode'        => 'users',

            'error'        => '',

            'message'    => '',
            'title' => $title,

        );


        $this->db->from('customer c');
        $this->db->select('c.*');
        $this->db->order_by("c.id", "desc");

        $query = $this->db->get();
        $data['userLog'] = $query->result();

        $this->load->view('admin/users', $data);
    }

    function viewuserdetails()
    {

        $data = array(

            'page'        => 'blank',

            'mode'        => 'users',

            'error'        => '',

            'message'    => '',

        );

        $data['id'] = $this->input->post('id');
        $data['packageid'] = $this->input->post('packageid');

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $data['packageid']);
        $this->db->where('c.id', $data['id']);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        $this->load->view('admin/usersdetails', $data);
    }

    public function checkUserMealExists($userId, $packageID, $week, $mealTypeId, $mealID)
    {
        $query = $this->db->query("SELECT m.id FROM user_meal m
        LEFT JOIN user_meal_week u ON u.id = m.meal_week_id
        WHERE u.user_id = '" . $userId . "'
        AND u.package_id = '" . $packageID . "'
        AND u.week = '" . $week . "'
        AND u.meal_type_id = '" . $mealTypeId . "'
        AND m.meal_id = '" . $mealID . "'");

        return $query->num_rows();
    }

    public function addUserPackageMealByUserId($id, $packID)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        if (isset($packID) && $packID == 2) {
            $query = $this->db->get('meal_type');
            $data['mealTypeLog'] = $query->result();

            $queryWekDrop = $this->db->get('week_per_drop');
            $data['weekDropLog'] = $queryWekDrop->result();

            $this->load->view('admin/usermealadd', $data);
        } elseif (isset($packID) && $packID == 1) {
            $this->load->view('admin/usermealaddone', $data);
        }
    }

    /* 6 WEEK TRANSFORMATION START */

    /* FETCH MEAL CONTENT BY WEEK AND MEAL TYPE ID */

    public function loadMealByWeeknMealType()
    {
        $dataPost['mealTypeId'] = $this->input->post('mealTypeId');
        $dataPost['week'] = $this->input->post('week');
        $dataPost['totalCalorie'] = $this->input->post('totalCalorie');
        $dataPost['userId'] = $this->input->post('userId');
        $dataPost['packageid'] = $this->input->post('packageid');

        $returnData = $this->user->fetchUserMealByWeeknPackage($dataPost['userId'], $dataPost['packageid'], $dataPost['week'], $dataPost['mealTypeId']);

        $resultPerDrop = $this->user->fetchSingleDataById('week_per_drop', 'week', $dataPost['week']);
        $resultMealType = $this->user->fetchSingleDataById('meal_type', 'id', $dataPost['mealTypeId']);

        if (isset($resultPerDrop->per_drop) && $resultPerDrop->per_drop > 0) {
            $perDrop = $resultPerDrop->per_drop;
        } else {
            $perDrop = 0;
        }

        if (isset($resultMealType->calorie_per) && $resultMealType->calorie_per > 0) {
            $caloriePer = $resultMealType->calorie_per;
        } else {
            $caloriePer = 0;
        }

        if (isset($caloriePer) && $caloriePer > 0) {
            $mealCalorie = ($dataPost['totalCalorie'] * $caloriePer) / 100;
            if (isset($perDrop) && $perDrop > 0) {
                $returnData['weekMealCalorie'] = $mealCalorie - (($mealCalorie * $perDrop) / 100);
            } else {
                $returnData['weekMealCalorie'] = $mealCalorie;
            }
        } else {
            $returnData['weekMealCalorie'] = $dataPost['totalCalorie'];
        }

        $resultMeal = $this->meal->fetchMealbyIdnCalorie($dataPost['mealTypeId'], $returnData['weekMealCalorie']);
        $returnData['mealLog'] = $resultMeal['mealLog'];
        $returnData['ingredientLog'] = $resultMeal['ingredientLog'];

        /* echo "<pre>";
        print_r($returnData);
        die(); */

        $dataReturn['status'] = 1;
        $dataReturn['message'] = "success";
        $dataReturn['returnData'] = $this->load->view('admin/loadsixweekusermealcontent', $returnData, TRUE);

        echo json_encode($dataReturn);
    }

    /* UPDATE USER MEAL DATA */

    public function updateUserMealByID()
    {
        /* echo "<pre>";
        print_r($this->input->post());
        die(); */

        $data['userId'] = $this->input->post('userId');
        $data['packageid'] = $this->input->post('packageid');
        $data['totalCalorie'] = $this->input->post('totalCalorie');
        $data['meal_type_id'] = $this->input->post('meal_type_id');
        $data['week'] = $this->input->post('week');
        $data['weekMealCalorie'] = $this->input->post('weekMealCalorie');
        $data['day'] = $this->input->post('day');
        $data['dayid'] = $this->input->post('dayid');
        $data['selectedMeal'] = $this->input->post('selectedMeal');
        $data['usermealingamt'] = $this->input->post('usermealingamt');
        $data['intakecalorie'] = $this->input->post('intakecalorie');
        $data['mealintakecalorie'] = $this->input->post('mealintakecalorie');

        $this->user->addUserMeal($data);

        $this->session->set_flashdata('msg', 'User Meal data successfully updated');
        redirect('admin/users/viewMeal/' . $data['userId'] . '/' . $data['packageid']);
    }

    public function viewUserMeal($id, $packID)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;

        $data['msg'] = $this->session->userdata('msg');

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        /* $resultUserMeal = $this->user->fetchUserMeal($id, $packID);
        $data['mealMaster'] = $resultUserMeal['mealMaster'];
        $data['mealDay'] = $resultUserMeal['mealDay'];
        $data['mealLog'] = $resultUserMeal['mealLog'];
        $data['mealIngredientLog'] = $resultUserMeal['mealIngredientLog']; */

        $resultUserMeal = $this->user->fetchUserMealSixWeek($id);
        $data['userMealLog'] = $resultUserMeal;

        /* echo "<pre>";
        print_r($resultUserMeal);
        die(); */
        $this->load->view('admin/usermealview', $data);
    }

    /* 6 WEEK TRANSFORMATION END */

    /* ONE TO ONE TRANSFORMATION START */

    public function loadMealDataforOnetoOne()
    {
        $data['userId'] = $this->input->post('userId');
        $data['packageid'] = $this->input->post('packageid');
        $data['totalCalorie'] = $this->input->post('totalCalorie');
        $data['startdate'] = $this->input->post('startdate');

        $returnData = $this->meal->onetooneMealLoad($data);
        /* echo "<pre>";
        print_r($returnData);
        die(); */
        $dataReturn['status'] = 1;
        $dataReturn['message'] = "success";
        $dataReturn['returnData'] = $this->load->view('admin/loadonotoonemealdata', $returnData, TRUE);

        echo json_encode($dataReturn);
    }

    public function updateUserOnetoOneMealById()
    {
        /* echo "<pre>";
        print_r($this->input->post());
        die(); */

        $data['userId'] = $this->input->post('userId');
        $data['packageid'] = $this->input->post('packageid');
        $data['totalCalorie'] = $this->input->post('totalCalorie');
        $data['meal_type_id'] = $this->input->post('meal_type_id');
        $data['one_meal_date'] = $this->input->post('one_meal_date');
        $data['week_meal_calorie'] = $this->input->post('week_meal_calorie');
        $data['selectedMeal'] = $this->input->post('selectedMeal');
        $data['usermealingamt'] = $this->input->post('usermealingamt');
        $data['intakecalorie'] = $this->input->post('intakecalorie');
        $data['mealintakecalorie'] = $this->input->post('mealintakecalorie');

        $this->user->addUserMealOnetoOne($data);
        $this->session->set_flashdata('msg', 'User Meal data successfully updated');
        redirect('admin/users/viewMealOnetoOne/' . $data['userId'] . '/' . $data['packageid']);
    }

    public function viewUserMealOne($id, $packID)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;

        $data['msg'] = $this->session->userdata('msg');

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        $resultUserMeal = $this->user->fetchUserMealOnetoOne($id, $packID);

        $data['mealMaster'] = $resultUserMeal['mealMaster'];
        $data['mealDay'] = $resultUserMeal['mealDay'];
        $data['mealLog'] = $resultUserMeal['mealLog'];
        $data['mealIngredientLog'] = $resultUserMeal['mealIngredientLog'];

        $this->load->view('admin/usermealviewonetoone', $data);
    }


    public function updateOnoetoOneMealByUserId($id, $packID, $meal_master_id)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;
        $data['meal_master_id'] = $meal_master_id;

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();


        $resultUserMeal = $this->user->fetchUserMealOnetoOneBymealMasterId($id, $packID, $meal_master_id);
        $data['mealMaster'] = $resultUserMeal['mealMaster'];
        $data['mealDay'] = $resultUserMeal['mealDay'];
        $data['userMealLogData'] = $resultUserMeal['mealLog'];
        $data['userMmealIngredientLog'] = $resultUserMeal['mealIngredientLog'];

        $dataPost['userId'] = $id;
        $dataPost['packageid'] = $packID;
        $dataPost['meal_master_id'] = $meal_master_id;
        $dataPost['totalCalorie'] = $this->calculateBmr($data['userLog']->age, $data['userLog']->height, $data['userLog']->weight, $data['userLog']->gender, $data['userLog']->activity_lavel);

        $returnData = $this->meal->onetooneMealLoadUpdate($dataPost);
        $data['startData'] = $returnData['startData'];
        $data['mealTypeLog'] = $returnData['mealTypeLog'];
        $data['mealClorie'] = $returnData['mealClorie'];
        $data['mealLog'] = $returnData['mealLog'];
        $data['ingredientLog'] = $returnData['ingredientLog'];

        $this->load->view('admin/usermealupdateone', $data);
    }

    /* ONE TO ONE TRANSFORMATION END */

    /* 6 WEEK USER WORKOUT */

    public function viewUserWorkout($id, $packID)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;

        $data['msg'] = $this->session->userdata('msg');

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        /* $resultUserWork = $this->user->fetchUserWorkout($id, $packID);
        $data['workoutMaster'] = $resultUserWork['workoutMaster'];
        $data['workoutDay'] = $resultUserWork['workoutDay'];
        $data['workoutLog'] = $resultUserWork['workoutLog']; */

        $resultUserWork = $this->user->fetchUserWorkout($id);
        $data['workout'] = $resultUserWork;

        $this->load->view('admin/userworkoutview', $data);
    }

    /* ADD USER PACKAGE WISE WORKOUT */

    public function addUserPackageWorkoutByUserId($id, $packID)
    {

        $data['id'] = $id;
        $data['packageid'] = $packID;

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        if (isset($packID) && $packID == 2) {

            $this->db->from('workout_routine');
            $this->db->select('*');
            $this->db->where('status', 'Y');
            $query = $this->db->get();

            $data['routineLog'] = $query->result();

            $resultUserWork = $this->user->fetchUserWorkout($id, $packID);
            $data['workoutMaster'] = $resultUserWork['workoutMaster'];
            $data['workoutDay'] = $resultUserWork['workoutDay'];
            $data['workoutLog'] = $resultUserWork['workoutLog'];

            $this->load->view('admin/userworkoutadd', $data);
        } elseif (isset($packID) && $packID == 1) {
            $this->load->view('admin/userworkoutaddone', $data);
        }
    }

    /* 6 week user workout update */

    public function updateUserWrokoutByID()
    {
        /* echo "<pre>";
        print_r($this->input->post());
        die(); */

        $data['userId'] = $this->input->post('userId');
        $data['packageid'] = $this->input->post('packageid');
        $data['week_id'] = $this->input->post('week_id');
        $data['day_id'] = $this->input->post('day_id');
        $data['day_name'] = $this->input->post('day_name');
        $data['routine_id'] = $this->input->post('routine_id');

        $this->user->addUserWorkout($data);

        $this->session->set_flashdata('msg', 'User Meal data successfully updated');
        redirect('admin/users/viewWorkout/' . $data['userId'] . '/' . $data['packageid']);
    }

    /* VIEW USER WORKOUT ONE TO ONE */
    public function viewUserWorkoutOne($id, $packID)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;

        $data['msg'] = $this->session->userdata('msg');

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();

        $resultUserMeal = $this->user->fetchUserWorkoutOnetoOne($id, $packID);
        $data['workoutMaster'] = $resultUserMeal['workoutMaster'];
        $data['workoutDay'] = $resultUserMeal['workoutDay'];
        $data['workoutLog'] = $resultUserMeal['workoutLog'];

        $this->load->view('admin/userworkoutviewonetoone', $data);
    }

    /* ONE TO ONE WORKOUT START */

    public function loadWorkoutDataforOnetoOne()
    {
        $data['userId'] = $this->input->post('userId');
        $data['packageid'] = $this->input->post('packageid');
        $data['startdate'] = $this->input->post('startdate');

        $returnData = $this->user->onetooneWorkoutLoad($data);
        /* echo "<pre>";
        print_r($returnData);
        die(); */
        $dataReturn['status'] = 1;
        $dataReturn['message'] = "success";
        $dataReturn['returnData'] = $this->load->view('admin/loadonotooneworkoutdata', $returnData, TRUE);

        echo json_encode($dataReturn);
    }

    public function updateUserOnetoOneWorkoutById()
    {

        $dataPost['user_id'] = $this->input->post('userId');
        $dataPost['package_id'] = $this->input->post('packageid');
        $dataPost['one_workout_date'] = $this->input->post('one_workout_date');
        $dataPost['routine_id'] = $this->input->post('routine_id');

        $this->user->addUserWorkoutOnetoOne($dataPost);

        $this->session->set_flashdata('msg', 'User Meal data successfully updated');
        redirect('admin/users/viewWorkoutOnetoOne/' . $dataPost['user_id'] . '/' . $dataPost['package_id']);
    }

    public function updateOnoetoOneWorkoutByUserId($id, $packID, $workout_master_id)
    {
        $data['id'] = $id;
        $data['packageid'] = $packID;
        $data['workout_master_id'] = $workout_master_id;

        $this->db->from('customer c');
        $this->db->select('c.*, p.payment_date, p.payment_amt, p.tran_id, p.package_id');
        $this->db->join('customer_package p', 'p.cust_id = c.id', 'left');
        $this->db->where('p.package_id', $packID);
        $this->db->where('c.id', $id);
        $this->db->order_by("c.id", "desc");
        $query = $this->db->get();
        $data['userLog'] = $query->row();


        $dataPost['userId'] = $id;
        $dataPost['packageid'] = $packID;
        $dataPost['workout_master_id'] = $workout_master_id;

        $returnData = $this->user->fetchUserWorkoutUpdateData($dataPost);
        /* echo "<pre>";
        print_r($returnData);
        die(); */
        $data['startData'] = $returnData['startData'];
        $data['routineLog'] = $returnData['routineLog'];
        $data['workoutDay'] = $returnData['workoutDay'];
        $data['workoutLog'] = $returnData['workoutLog'];

        $this->load->view('admin/userworkoutupdateone', $data);
    }

    /* BUY NOW REQUEST */

    public function orderHistory()
    {
        $data = array(

            'page'        => 'blank',

            'mode'        => 'users',

            'error'        => '',

            'message'    => '',

        );

        $this->db->from('buy_now');
        $this->db->select('*');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        $data['buyLog'] = $query->result();

        $this->load->view('admin/buynow', $data);
    }

    public function changepassword()
    {
        $id = $this->input->post('passid');
        $newpass = $this->input->post('newpass');

        $this->db->query("UPDATE `customer` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $id . "'");
        $this->db->query("UPDATE `login_data` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `cust_id` = '" . $id . "'");

        echo "success";
    }

    public function exportUser()
    {

        $filename = "User-list" . date('d-m-Y') . ".csv";
        $fp = fopen('php://output', 'w');

        $header[] = 'Sl No';
        $header[] = 'Registration Date';
        $header[] = 'Location';
        $header[] = 'Name';
        $header[] = 'Email';
        $header[] = 'Age';
        $header[] = 'Height';
        $header[] = 'Weight';
        $header[] = 'Gender';
        $header[] = 'Activity level';
        $header[] = 'Current Package';
        $header[] = 'Payment date';
        $header[] = 'Amount';
        $header[] = 'Transaction ID';
        $header[] = '6 Week Batch';


        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        fputcsv($fp, $header);


        $query = $this->db->query("SELECT c.*
         FROM customer c WHERE 1 ORDER BY c.id desc");
        $result = $query->result();

        if (isset($result) && !empty($result)) {
            foreach ($result as $key => $val) {

                $queryPack = $this->db->query("SELECT p.payment_date, p.payment_amt, p.tran_id, p.package_id, m.package_title, s.batch_name 
                FROM customer_package p 
                LEFT JOIN package_master m ON m.id = p.package_id
                LEFT JOIN six_week_batch s ON s.id = p.batch_id
                WHERE p.cust_id = '" . $val->id . "' 
                ORDER BY p.id desc limit 1");

                $pack = $queryPack->row();

                $sl = $key + 1;

                $row[$key][] = $sl;
                $row[$key][] = $val->reg_date;
                $row[$key][] = $val->location_name;
                $row[$key][] = $val->name;
                $row[$key][] = $val->email_id;
                $row[$key][] = $val->age . ' Yrs.';
                $row[$key][] = $val->height . ' cm';
                $row[$key][] = $val->weight . ' kg.';
                $row[$key][] = $val->gender;
                $row[$key][] = $val->activity_lavel;
                $row[$key][] = (isset($pack->package_title) && $pack->package_title != "") ? $pack->package_title : '';
                $row[$key][] = (isset($pack->payment_date) && $pack->payment_date != "") ? $pack->payment_date : '';
                $row[$key][] = (isset($pack->payment_amt) && $pack->payment_amt != "") ? sprintf("%01.2f", $pack->payment_amt) : '0.00';
                $row[$key][] = (isset($pack->tran_id) && $pack->tran_id != "") ? $pack->tran_id : '';
                $row[$key][] = (isset($pack->batch_name) && $pack->batch_name != "") ? $pack->batch_name : '';



                fputcsv($fp, $row[$key]);
            }
        }
        exit;
    }
}
