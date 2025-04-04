<?php

defined('BASEPATH') or exit('No direct script access allowed');





class Profile extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		ini_set('memory_limit', '-1');
		ini_set('upload_max_filesize', '200M');
		ini_set('post_max_size', '200M');
		ini_set('max_input_time', 30000);
		$this->CI = &get_instance();
		parse_str($_SERVER['QUERY_STRING'], $_REQUEST);
	}

	function clean($string)
	{
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9_\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', strtolower($string)); // Replaces multiple hyphens with single one.
	}

	public function index()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);
		$data['cust_id'] = $this->session->userdata('cust_id');

		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();


			$this->load->view('home/header', $data);
			$this->load->view('home/myaccount', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('login');
		}
	}

	public function updateprofiledata()
	{

		if ($this->session->userdata('cust_id') > 0) {
			$dataPost = array(
				'name' => $this->input->post('name'),
				'country' => $this->input->post('country'),
				'age' => $this->input->post('age'),
				'height' => $this->input->post('height'),
				'weight' => $this->input->post('weight'),
				'gender' => $this->input->post('gender'),
				'activity_lavel' => $this->input->post('activity_lavel'),
				'allergies' => $this->input->post('allergies'),
			);

			$this->db->set($dataPost);
			$this->db->where('id', $_SESSION['cust_id']);
			$this->db->update('customer');

			echo "success";
		} else {
			echo "error@||@Authentication failed.";
		}
	}


	public function login()

	{
		$data = array(

			'page'		=> 'Login',

			'error'		=> '',

			'message'	=> ''

		);
		if ($this->session->userdata('cust_id') > 0) {
			redirect('profile');
		} else {

			$this->load->view('home/header', $data);
			$this->load->view('home/login', $data);
			$this->load->view('home/footer', $data);
		}
	}

	public function forgotpassword()

	{
		$data = array(

			'page'		=> 'Login',

			'error'		=> '',

			'message'	=> ''

		);
		if ($this->session->userdata('cust_id') > 0) {
			redirect('profile');
		} else {

			$this->load->view('home/header', $data);
			$this->load->view('home/forgotpassword', $data);
			$this->load->view('home/footer', $data);
		}
	}

	public function forgetpassworddatasubmit()
	{
		$email_id = $this->input->post('email_id');

		$this->db->from('customer c');
		$this->db->select('c.id, c.name');
		$this->db->where('c.email_id', $email_id);
		$queryCust = $this->db->get();
		$custData = $queryCust->row();
		if (isset($custData->id) && $custData->id > 0) {
			$data = array('id' => $custData->id, ' email_id' => $email_id);
			$link = base_url() . 'reset-pasword/' . urlencode(base64_encode(serialize($data)));

			$msg = '<!doctype html>
			<html>
			
			<head>
			  <meta charset="utf-8">
			  <link rel="preconnect" href="https://fonts.googleapis.com">
			  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
			  <title>Email-templat (NU)</title>
			  <style>
				body {
				  background-color: #F0F0F0;
				  font-family: \'Roboto\', sans-serif;
				  font-size: 14px;
				}
			  </style>
			</head>
			<body style="width:600px; margin:0px auto; padding:0px;">
			  <div style=" background-color:#fff; padding:15px;">
				<div style=" margin-top:0px; padding-top:10px;">
				  <div style="text-align:center; padding:25px 0px 35px 0px;"> <img src="' . base_url() . '/emailimg/logo.png" alt="logo"
					  style="width: 200px;" /> </div>
				  <div style="clear:both;"></div>
				</div>
				<div style=" background-color:#f9f8f8; padding:20px;">
				  <h4 style="font-size: 18px; padding-bottom: 6px;">NU BY JORDAN STOREY PASSWORD RESET</h4>
				  <p>Hello ' . $custData->name . ',</p>
				  <p>Forgot your password?</p>
				  <p>That\'s okay, it happens! </p>
				  <p>Please click the link below to reset your password.</p>
				  <p><a href="' . $link . '">' . $link . '</a></p>

				  <p>We thank you for your support.</p>
				  <p>Best wishes,</p>
				  <p>Jordan Storey <br>Founder and C.E.O of NU by Jordan Storey <br>
					<a href="#" style="color: #5312ab;text-decoration: none;">nu-by-js.com</a> <br>
					<a href="#" style="color: #5312ab;text-decoration: none;">info@nu-by-js.com</a>
				  </p>
				  <div style=" padding:0px;"> <a href="#"><img src="' . base_url() . '/emailimg/logo.png" alt="logo" style="width: 100px;" /></a> </div>
				</div>
				<div style="margin:20px 0px 0px 0px; text-align:center;">
					<a href="https://www.facebook.com/NUbyJS/"><img src="' . base_url() . 'emailimg/i-f.png" alt="logo" /></a> &nbsp; &nbsp;
					<a href="https://twitter.com/NubyJS?s=08"><img src="' . base_url() . 'emailimg/i-li.png" alt="logo" /></a> &nbsp; &nbsp;
					<a href="https://www.instagram.com/nubyjs/?utm_medium=copy_link"><img src="' . base_url() . 'emailimg/i-in.png" alt="logo" /></a>
				</div>
			  </div>
			</body>
			
			</html>';

			$this->load->library('email');

			$from_email = "info@nu-by-js.com";
			$to_email = $email_id;

			$config = array();
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'smtp.ionos.co.uk';
			$config['smtp_user'] = 'jordan@nu-by-js.com';
			$config['smtp_pass'] = 'Mrbond007!';
			$config['smtp_port'] = 587;
			$config['smtp_crypto'] = 'tls';
			$config['mailtype'] = 'html';
			$config['charset']      = 'iso-8859-1';
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");

			$this->email->from($from_email);
			$this->email->to($to_email);
			$this->email->subject('Reset pasword link from NU');
			$this->email->message($msg);
			//Send mail 
			$this->email->send();
			/* show_error($this->email->print_debugger()); */

			echo "success@||@Thank you for your request. An email has been sent to your registered email id to reset your password.";
		} else {
			echo 'error@||@Sorry!! This email id is not register with us';
		}
	}

	public function resetpassword($string = "")

	{
		$data = array(

			'page'		=> 'Login',

			'error'		=> '',

			'message'	=> ''

		);
		if ($this->session->userdata('cust_id') > 0) {
			redirect('profile');
		} else {

			if (isset($string) && $string != "") {
				$data = unserialize(base64_decode(urldecode($string)));

				if (isset($data['id']) && $data['id'] > 0) {

					$this->db->from('customer c');
					$this->db->select('c.id, c.name');
					$this->db->where('c.id', $data['id']);
					$queryCust = $this->db->get();
					$custData = $queryCust->row();
					if (isset($custData->id) && $custData->id > 0) {

						$this->load->view('home/header', $data);
						$this->load->view('home/resetpassword', $data);
						$this->load->view('home/footer', $data);
					} else {
						redirect('login');
					}
				} else {
					redirect('login');
				}
			} else {
				redirect('login');
			}
		}
	}

	public function resetpasswoeddatasubmit()
	{
		$id = $this->input->post('id');
		$newpass = $this->input->post('newpassword');

		if (isset($id) && $id > 0) {
			$this->db->query("UPDATE `customer` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $id . "'");
			$this->db->query("UPDATE `login_data` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `cust_id` = '" . $id . "'");

			echo "success@||@You have successfully changed your password.";
		} else {
			echo "error@||@Sorry!! Authentication failed.";
		}
	}

	public function registration()

	{
		$data = array(

			'page'		=> 'Sign up',

			'error'		=> '',

			'message'	=> ''

		);
		/* if ($this->session->userdata('cust_id') > 0) {
			redirect('profile');
		} else { */

		$data['cust_id'] = $this->session->userdata('cust_id');
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();
		}

		$this->db->from('package_master');
		$this->db->select('*');
		$queryPackage = $this->db->get();
		$data['packageData'] = $queryPackage->result();

		$queryBatch = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `batch_date` > '" . strtotime(date('d-m-Y') . ' -5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
		$data['packStarted'] = $queryBatch->row();

		if (isset($data['packStarted']->id) && $data['packStarted']->id > 0) {
			$batchId = $data['packStarted']->id;
		} else {
			$batchId = 0;
		}

		$data['batch_id'] = $batchId;

		$this->load->view('home/header', $data);
		$this->load->view('home/registration', $data);
		$this->load->view('home/footer', $data);
		//}
	}

	public function registrationsubmit()
	{
		$password = $this->input->post('password');
		$dataPost = array(
			'name' => $this->input->post('name'),
			'email_id' => $this->input->post('email_id'),
			'password' => password_hash($password, PASSWORD_DEFAULT),
		);

		$this->db->from('customer c');
		$this->db->select('c.id');
		$this->db->where('c.email_id', $dataPost['email_id']);
		$queryCust = $this->db->get();
		$custData = $queryCust->row();
		if (isset($custData->id) && $custData->id > 0) {
			echo "error@||@This email id is already registered with us.";
		} else {
			$this->db->insert('customer', $dataPost);
			$id = $this->db->insert_id();

			$user_code = sprintf("%06d", $id);

			$this->db->query("UPDATE `customer` SET `user_code` = '" . $user_code . "' WHERE `id` = '" . $id . "'");

			$dataLogData = array(
				'email_id' => $dataPost['email_id'],
				'password' => password_hash($password, PASSWORD_DEFAULT),
				'cust_id' => $id,
				'type' => 'user'
			);
			$this->db->insert('login_data', $dataLogData);


			$this->session->set_userdata('cust_id', $id);

			$msg = '<!doctype html>
			<html>
			
			<head>
			  <meta charset="utf-8">
			  <link rel="preconnect" href="https://fonts.googleapis.com">
			  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
			  <title>Email-templat (NU)</title>
			  <style>
				body {
				  background-color: #F0F0F0;
				  font-family: \'Roboto\', sans-serif;
				  font-size: 14px;
				}
			  </style>
			</head>
			<body style="width:600px; margin:0px auto; padding:0px;">
			  <div style=" background-color:#fff; padding:15px;">
				<div style=" margin-top:0px; padding-top:10px;">
				  <div style="text-align:center; padding:25px 0px 35px 0px;"> <img src="' . base_url() . '/emailimg/logo.png" alt="logo"
					  style="width: 200px;" /> </div>
				  <div style="clear:both;"></div>
				</div>
				<div style=" background-color:#f9f8f8; padding:20px;">
				  <h4 style="font-size: 18px; padding-bottom: 6px;">NU BY JORDAN STOREY REGISTRATION</h4>
				  <p>Hello,</p>
				  <p>WELCOME TO NU BY JORDAN STOREY!</p>
				  <p>THANK YOU FOR SIGNING UP TO THE NU BY JORDAN STOREY APP!</p>
				  <p>By joining my app you have taken the first step towards a happier, healthier lifestyle.</p>
				  <p>Me and my team will do everything we can to help and support you on your NU journey.</p>
				  <p>Make sure to tag us in any of your transformations, cooking, workouts or progress #nubyjs</p>
				  <p>Please enjoy the free forums on the app!</p>

				  <p>We thank you for your support.</p>
				  <p>Best wishes,</p>
				  <p>Jordan Storey <br>Founder and C.E.O of NU by Jordan Storey <br>
					<a href="#" style="color: #5312ab;text-decoration: none;">nu-by-js.com</a> <br>
					<a href="#" style="color: #5312ab;text-decoration: none;">info@nu-by-js.com</a>
				  </p>
				  <div style=" padding:0px;"> <a href="#"><img src="' . base_url() . '/emailimg/logo.png" alt="logo" style="width: 100px;" /></a> </div>
				</div>
				<div style="margin:20px 0px 0px 0px; text-align:center;">
					<a href="https://www.facebook.com/NUbyJS/"><img src="' . base_url() . 'emailimg/i-f.png" alt="logo" /></a> &nbsp; &nbsp;
					<a href="https://twitter.com/NubyJS?s=08"><img src="' . base_url() . 'emailimg/i-li.png" alt="logo" /></a> &nbsp; &nbsp;
					<a href="https://www.instagram.com/nubyjs/?utm_medium=copy_link"><img src="' . base_url() . 'emailimg/i-in.png" alt="logo" /></a>
				</div>
			  </div>
			</body>
			
			</html>';

			$this->load->library('email');

			$from_email = "info@nu-by-js.com";
			$to_email = $dataPost['email_id'];

			$config = array();
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'smtp.ionos.co.uk';
			$config['smtp_user'] = 'jordan@nu-by-js.com';
			$config['smtp_pass'] = 'Mrbond007!';
			$config['smtp_port'] = 587;
			$config['smtp_crypto'] = 'tls';
			$config['mailtype'] = 'html';
			$config['charset']      = 'iso-8859-1';
			$this->email->initialize($config);
			$this->email->set_newline("\r\n");

			$this->email->from($from_email);
			$this->email->to($to_email);
			$this->email->subject('Welcome to NU');
			$this->email->message($msg);
			//Send mail 
			//$this->email->send();


			echo "success"; //@||@".$id.'@||@'.$dataPost['email_id'].'@||@'.$dataPost['name']
		}
	}

	public function loginsubmit()
	{
		$password = $this->input->post('password');
		$dataPost = array(
			'email_id' => $this->input->post('email_id'),
		);

		$this->db->from('login_data');
		$this->db->select('*');
		$this->db->where('email_id', $dataPost['email_id']);
		$queryCust = $this->db->get();
		$custData = $queryCust->row();
		if (isset($custData->id) && $custData->id > 0) {
			if (password_verify($password, $custData->password)) {
				$this->session->set_userdata('cust_id', $custData->cust_id);
				echo "success";
			} else {
				echo "error@||@Sorry!! password does not matched.";
			}
		} else {
			echo "error@||@Sorry!! This email id is not registered with us.";
		}
	}

	public function joinLifeStyle()
	{
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();

			$this->load->view('home/header', $data);
			$this->load->view('home/joinnowlifestyle', $data);
			$this->load->view('home/footer', $data);
			
		} else {
			redirect('sign-up');
		}
	}
	
	
	
	public function joinLifeStyleSubmit()
	{
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$query = $this->db->query("SELECT * FROM `customer_package` WHERE `cust_id` = '" . $id . "' AND `package_id` = '3'");
			$num = $query->num_rows();

			if (isset($num) && $num > 0) {
				//do nothing
			} else {
				$this->db->query("INSERT INTO `customer_package` SET
								`cust_id` = '" . $id . "',
								`package_id` = '3',
								`payment_amt` = '0',
								`package_amt` = '0',
								`coupon_discount` = '0',
								`tran_id` = 'test12345',
								`pay_status` = 'Y'");
			}

			echo "success@||@";
		} else {
			echo "error@||@please enter valid data";
		}
	}

	public function successpaymentpagelifestyle()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/successpaymentpagelifestyle', $data);
		$this->load->view('home/footer', $data);
	}
	/* PLANS */
	public function myplans()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();


			$this->db->from('package_master');
			$this->db->select('*');
			$queryPackage = $this->db->get();
			$data['packageData'] = $queryPackage->result();

			$queryBatch = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `batch_date` > '" . strtotime(date('d-m-Y') . ' -5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
			$data['packStarted'] = $queryBatch->row();

			if (isset($data['packStarted']->id) && $data['packStarted']->id > 0) {
				$batchId = $data['packStarted']->id;
			} else {
				$batchId = 0;
			}

			$packPurchase = array();
			if (isset($data['packageData']) && !empty($data['packageData'])) {
				foreach ($data['packageData'] as $key => $value) {
					$query = $this->db->query("SELECT c.*,
									p.package_title
									FROM customer_package c
									LEFT JOIN package_master p ON p.id = c.package_id
									WHERE c.pay_status = 'Y'
									AND c.cust_id = '" . $id . "'
									AND c.package_id = '" . $value->id . "'
									ORDER BY c.id DESC");

					$result = $query->row();
					if (isset($result->package_id) && $result->package_id == 1) {
						$packagseStartDate = strtotime($result->payment_date . ' + 2 days');
					} elseif (isset($result->package_id) && $result->package_id == 2) {
						if ($result->batch_id == $batchId) {
							$packagseStartDate = $result->payment_date;
						} else {
							$packagseStartDate = 0;
						}
					} elseif (isset($result->package_id) && $result->package_id == 3) {
						$packagseStartDate = strtotime($result->payment_date . ' + 2 days');
					}else{
						$packagseStartDate = 0;
					}
					$data['packagseStartDate'][$value->id] = $packagseStartDate;
				}
			}



			$this->load->view('home/header', $data);
			$this->load->view('home/myplans', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('login');
		}
	}
	
	/* PLANS */
	public function plans()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		//if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();


			$this->db->from('package_master');
			$this->db->select('*');
			$queryPackage = $this->db->get();
			$data['packageData'] = $queryPackage->result();

			$queryBatch = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `batch_date` > '" . strtotime(date('d-m-Y') . ' -5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
			$data['packStarted'] = $queryBatch->row();

			if (isset($data['packStarted']->id) && $data['packStarted']->id > 0) {
				$batchId = $data['packStarted']->id;
			} else {
				$batchId = 0;
			}

			if (isset($data['packageData']) && !empty($data['packageData'])) {
				foreach ($data['packageData'] as $key => $value) {
					$query = $this->db->query("SELECT c.*,
									p.package_title
									FROM customer_package c
									LEFT JOIN package_master p ON p.id = c.package_id
									WHERE c.pay_status = 'Y'
									AND c.cust_id = '" . $id . "'
									AND c.package_id = '" . $value->id . "'
									ORDER BY c.id DESC");

					$result = $query->result();

					if (isset($result) && !empty($result)) {
						foreach ($result as $key => $val) {
							if ($val->package_id == 1) {
								$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
							} elseif ($val->package_id == 2) {

								if ($val->batch_id == $batchId) {
									$packagseStartDate = $val->payment_date;
								} else {
									$packagseStartDate = 0;
								}
							} elseif ($val->package_id == 3) {
								$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
							}
						}
					} else {
						$packagseStartDate = 0;
					}
					$data['packagseStartDate'][$value->id] = $packagseStartDate;
				}
			}



			$this->load->view('home/header', $data);
			$this->load->view('home/plans', $data);
			$this->load->view('home/footer', $data);
		/* } else {
			redirect('login');
		} */
	}

	public function planDetails($name = "", $packageId = 0)
	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		//if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();


			$this->db->from('package_master');
			$this->db->select('*');
			$this->db->where('id', $packageId);
			$queryPackage = $this->db->get();
			$data['packageData'] = $queryPackage->row();

			$queryBatch = $this->db->query("SELECT batch_name, id, DATE_FORMAT(FROM_UNIXTIME(batch_date), '%d-%m-%Y') as batch_date FROM `six_week_batch` WHERE `batch_date` > '" . strtotime(date('d-m-Y') . ' -5 days') . "' AND `status` = 'Y' ORDER BY `id` desc limit 1");
			$data['packStarted'] = $queryBatch->row();

			if (isset($data['packStarted']->id) && $data['packStarted']->id > 0) {
				$batchId = $data['packStarted']->id;
			} else {
				$batchId = 0;
			}

			$query = $this->db->query("SELECT c.*,
									p.package_title
									FROM customer_package c
									LEFT JOIN package_master p ON p.id = c.package_id
									WHERE c.pay_status = 'Y'
									AND c.cust_id = '" . $id . "'
									AND c.package_id = '" . $data['packageData']->id . "'
									ORDER BY c.id DESC");

			$result = $query->result();

			if (isset($result) && !empty($result)) {
				foreach ($result as $key => $val) {
					if ($val->package_id == 1) {
						$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
					} elseif ($val->package_id == 2) {

						if ($val->batch_id == $batchId) {
							$packagseStartDate = $val->payment_date;
						} else {
							$packagseStartDate = 0;
						}
					} elseif ($val->package_id == 3) {
						$packagseStartDate = strtotime($val->payment_date . ' + 2 days');
					}
				}
			} else {
				$packagseStartDate = 0;
			}
			$data['packagseStartDate'] = $packagseStartDate;

			$this->load->view('home/header', $data);
			if (isset($packageId) && $packageId == 1) {
				$this->load->view('home/plandetailsone', $data);
			} elseif (isset($packageId) && $packageId == 2) {
				$this->load->view('home/plandetails', $data);
			} elseif (isset($packageId) && $packageId == 3) {
				$this->load->view('home/plandetailslifestyle', $data);
			}
			$this->load->view('home/footer', $data);
		/* } else {
			redirect('login');
		} */
	}

	/* BILLING */
	public function billing()

	{

		/* echo password_hash('Ac%heln', PASSWORD_DEFAULT);
		die(); */

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();


			$this->db->from('customer_package c');
			$this->db->join('package_master p', 'c.package_id = p.id', 'left');
			$this->db->select('c.*, p.package_title');
			$this->db->where('c.cust_id', $id);
			$queryPackage = $this->db->get();
			$data['packageData'] = $queryPackage->result();

			$this->load->view('home/header', $data);
			$this->load->view('home/billing', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('login');
		}
	}

	/* CHANGE PASSWORD */
	public function changepassword()

	{

		/* echo password_hash('Ac%heln', PASSWORD_DEFAULT);
		die(); */

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();

			$this->load->view('home/header', $data);
			$this->load->view('home/changepassword', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('login');
		}
	}

	public function uploadprofileimagedata()
	{
		$id = $this->session->userdata('cust_id');


		$config =  array(
			'upload_path'     => './uploads/profilepic/',
			'allowed_types'   => "gif|jpg|png|jpeg",
			'overwrite'       => TRUE
		);

		$this->load->library('upload', $config);

		$field_name = "profile_picture";
		if (!$this->upload->do_upload($field_name)) {
			$data1['pic'] = '';
		} else {
			$data1['pic'] = $this->upload->data();
			$profile_picture = 'uploads/profilepic/' . $data1['pic']['file_name'];
		}
		$this->db->query("UPDATE `customer` SET `profile_picture` = '" . $profile_picture . "' WHERE `id` = '" . $id . "'");
	}

	public function updatepassworddata()
	{
		$data['cust_id'] = $this->session->userdata('cust_id');
		if ($this->session->userdata('cust_id') > 0) {
			$oldpass = $this->input->post('oldpass');
			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $data['cust_id']);
			$queryCust = $this->db->get();
			$custData = $queryCust->row();

			if (isset($custData->id) && $custData->id > 0) {
				if (password_verify($oldpass, $custData->password)) {
					$newpass = $this->input->post('newpass');
					$this->db->query("UPDATE `customer` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $custData->id . "'");
					$this->db->query("UPDATE `login_data` SET `password` = '" . password_hash($newpass, PASSWORD_DEFAULT) . "' WHERE `cust_id` = '" . $custData->id . "'");

					echo "success@||@You have successfully changed your password.";
				} else {
					echo "error@||@Sorry!! Old password does not matched.";
				}
			} else {
				echo "error@||@Sorry!! Authentication failed.";
			}
		} else {
			echo "error@||@Sorry!! Authentication failed.";
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('cust_id');
		redirect();
	}
}
