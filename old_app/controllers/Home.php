<?php

defined('BASEPATH') or exit('No direct script access allowed');





class Home extends CI_Controller
{

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

		/* $query = $this->db->query("SELECT * FROM `customer` WHERE 1");
		foreach ($query->result() as $key => $val) {
			$user_code = sprintf("%06d", $val->id);

			$this->db->query("UPDATE `customer` SET `user_code` = '" . $user_code . "', `password` = '" . password_hash($val->password, PASSWORD_DEFAULT) . "' WHERE `id` = '" . $val->id . "'");
		}
 */
		$this->load->view('home/header', $data);
		$this->load->view('home/index', $data);
		$this->load->view('home/footer', $data);
	}

	public function lifestyle()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/lifestyle', $data);
		$this->load->view('home/footer', $data);
	}
	
	public function privacypolicy()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/privacypolicy', $data);
		$this->load->view('home/footer', $data);
	}

	public function knowmore()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/knowmore', $data);
		$this->load->view('home/footer', $data);
	}

	public function refundpolicy()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');
		$this->load->view('home/header', $data);
		$this->load->view('home/refundpolicy', $data);
		$this->load->view('home/footer', $data);
	}

	public function termsconditions()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/termsconditions', $data);
		$this->load->view('home/footer', $data);
	}

	public function joinnow()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		if ($this->session->userdata('cust_id') > 0) {
			$data['cust_id'] = $this->session->userdata('cust_id');
			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();

			$this->load->view('home/header', $data);
			$this->load->view('home/joinnow', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('sign-up');
		}
	}


	public function thankyoupage()

	{
		$data['cust_id'] = $this->session->userdata('cust_id');
		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/thankyoupage', $data);
		$this->load->view('home/footer', $data);
	}

	public function successpaymentpage()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/successpaymentpage', $data);
		$this->load->view('home/footer', $data);
	}

	public function cancelpaymentpage()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/cancelpaymentpage', $data);
		$this->load->view('home/footer', $data);
	}

	public function joinnowweeks($location = "")

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		if ($this->session->userdata('cust_id') > 0) {

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();

			$data['location_name'] = $location;

			$this->load->view('home/header', $data);
			$this->load->view('home/joinnowweeks', $data);
			$this->load->view('home/footer', $data);
		} else {
			redirect('sign-up');
		}
	}

	public function sixweeks()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);




		$this->load->view('home/header', $data);
		$this->load->view('home/sixweeks', $data);
		$this->load->view('home/footer', $data);
	}

	/* BUY NOW BOOK */

	public function buyNow($id)

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);
		$data['cust_id'] = $this->session->userdata('cust_id');

		if (isset($id) && $id == 1) {
			$data['book_type'] = 'Paperback';
			$data['unit_price'] = '16.99';
		} elseif (isset($id) && $id == 2) {
			/* $data['book_type'] = 'Hardback';
			$data['unit_price'] = '21.99'; */

			$data['book_type'] = 'Paperback';
			$data['unit_price'] = '16.99';
		}

		$this->load->view('home/header', $data);
		$this->load->view('home/buynow', $data);
		$this->load->view('home/footer', $data);
	}

	public function buynowsubmit()
	{
		$data = $this->input->post();

		$itemName = "Book Purchase";
		$itemAmount = $data['unit_price'];
		$data['item_name'] = $itemName;

		$data['currency_code'] = 'GBP';
		$data['amount'] = $itemAmount * $data['qty'];

		$secret = "nujordan95024"; //"test_nujordan95023";  //"nujordan95024";

		$data['secret'] = $secret;

		$sitesecurity = $data['currency_code'] . $data['amount'] . $secret . date('Y-m-d H:i:s') . "!fj%4>[b[K!d";
		$siteSecurityHash = hash("sha256", $sitesecurity);

		$data['securityHash'] = $siteSecurityHash;
		$this->session->set_userdata('dataBuyNow', $data);

		echo "success";
	}

	public function buynowprocess()

	{

		$data['data'] = $this->session->userdata('dataBuyNow');


		$this->load->view('home/header', $data);
		$this->load->view('home/buynowprocess', $data);
		$this->load->view('home/footer', $data);
	}


	public function paymenthandlerbuynow()
	{
		$transactionreference = $this->input->post_get('transactionreference');
		if (isset($transactionreference) && $transactionreference != "") {
			$data = $this->session->userdata('dataBuyNow');

			if (isset($data['name'], $data['email_id']) && $data['name'] != "" && $data['email_id'] != "") {

				$this->db->query("INSERT INTO `buy_now` SET 
				`book_type` = '" . $data['book_type'] . "',
				`name` = '" . $data['name'] . "',
				`email_id` = '" . $data['email_id'] . "',
				`address` = '" . $data['address'] . "',
				`city` = '" . $data['city'] . "',
				`pincode` = '" . $data['pincode'] . "',
				`country` = '" . $data['country'] . "',
				`qty` = '" . $data['qty'] . "',
				`unit_price` = '" . $data['unit_price'] . "',
				`total_amount` = '" . $data['amount'] . "',
				`tran_id` = '" . $transactionreference . "'");


				/* Admin Mail */

				$message = '
						<p><img src="' . base_url() . 'img/logo.png" alt=""></p>
						<table width="800" cellpadding="5" cellspacing="5">
						<tr>
							<th align="left" width="50%">Copy Type</th>
							<td>
								' . $data['book_type'] . '
							</td>
						</tr>
						<tr>
							<th align="left" width="50%">Name</th>
							<td>
								' . $data['name'] . '
							</td>
						</tr>
						<tr>
							<th align="left" width="50%">Email</th>
							<td>
								' . $data['email_id'] . '
							</td>
						</tr>
						<tr>
							<th align="left" width="50%">Addresss</th>
							<td>
								' . $data['address'] . '
							</td>
						</tr>
						<tr>
							<th align="left" width="50%">City</th>
							<td>
								' . $data['city'] . '
							</td>
						</tr>
						<tr>
							<th align="left">Pincode</th>
							<td>
								' . $data['pincode'] . '
							</td>
						</tr>
						<tr>
							<th align="left">Country</th>
							<td>
								' . $data['country'] . '
							</td>
						</tr>
						<tr>
							<th align="left">Quantity</th>
							<td>
								' . $data['qty'] . '
							</td>
						</tr>
						<tr>
							<th align="left">Transaction ID</th>
							<td>
								' . $transactionreference . '
							</td>
						</tr>
						<tr>
							<th align="left">Payment Amount</th>
							<td>
								GBP ' . $data['amount'] . '
							</td>
						</tr>
					</table>';


				$from_email = $data['email_id'];
				$to_email = "info@nu-by-js.com";

				//Load email library 
				$this->load->library('email');

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
				$this->email->cc($from_email);
				$this->email->subject('Buy now request from nu-by-js.com');
				$this->email->message($message);

				//Send mail 
				$this->email->send();


				$this->session->unset_userdata('dataBuyNow');

				redirect('buy-now-success');
			} else {
				redirect('cancel-payment');
			}
		} else {
			redirect('cancel-payment');
		}
	}

	public function buyNowSuccess()

	{

		$data = array(

			'page'		=> 'home',

			'error'		=> '',

			'message'	=> ''

		);

		$data['cust_id'] = $this->session->userdata('cust_id');

		$this->load->view('home/header', $data);
		$this->load->view('home/thankyoubuynow', $data);
		$this->load->view('home/footer', $data);
	}

	public function validateCouponode()
	{
		$package_id = $this->input->post('package_id');

		$data = $this->input->post();

		/* if (isset($custData->id) && $custData->id > 0) {
			echo "error@||@This email id is already registered with us.";
		} else { */
		$this->db->from('package_master');
		$this->db->select('*');
		$this->db->where('id', $package_id);
		$queryPack = $this->db->get();
		$packData = $queryPack->row();

		$itemName = $packData->package_title;
		$itemAmount = $packData->package_amt;
		$data['item_name'] = $itemName;

		$data['currency_code'] = 'GBP';

		$secret = "nujordan95024"; //"test_nujordan95023";  //"nujordan95024";

		$data['secret'] = $secret;

		$coupon_code = $this->input->post('coupon_code');

		if (isset($coupon_code) && $coupon_code != "") {
			$this->db->from('coupon');
			$this->db->select('*');
			$this->db->where('coupon_code', $coupon_code);
			$query = $this->db->get();
			$returnData = $query->row();

			if (isset($returnData->id) && $returnData->id > 0) {
				if (isset($returnData->expire_date) && time() > $returnData->expire_date) {
					echo "error@||@Sorry!! Coupon code expired";
				} elseif (isset($returnData->coupon_avail) && $returnData->coupon_avail > 0) {
					$discount = ($itemAmount * $returnData->coupon_discount) / 100;
					$data['package_amt'] = $itemAmount;
					$data['coupon_discount'] = $discount;
					$data['amount'] = $itemAmount - $discount;


					$sitesecurity = $data['currency_code'] . $data['amount'] . $secret . date('Y-m-d H:i:s') . "!fj%4>[b[K!d";
					$siteSecurityHash = hash("sha256", $sitesecurity);

					$data['securityHash'] = $siteSecurityHash;
					$this->session->set_userdata('dataJoinNow', $data);
					echo "success@||@";
				} else {
					echo "error@||@Sorry!! Coupon code expired";
				}
			} else {
				echo "error@||@please enter valid coupon code";
			}
		} else {
			$data['package_amt'] = $itemAmount;
			$data['coupon_discount'] = 0;
			$data['amount'] = $itemAmount;

			$sitesecurity = $data['currency_code'] . $data['amount'] . $secret . date('Y-m-d H:i:s') . "!fj%4>[b[K!d";
			$siteSecurityHash = hash("sha256", $sitesecurity);

			$data['securityHash'] = $siteSecurityHash;
			$this->session->set_userdata('dataJoinNow', $data);
			echo "success@||@";
		}
		//}
	}

	/* 6 WEEK TRANSFORMATION PACKAGE FORM SUBMIT START */

	public function joinnow6weeksubmit()

	{

		$data['data'] = $this->session->userdata('dataJoinNow');


		$this->load->view('home/header', $data);
		$this->load->view('home/joinnow6weeksubmit', $data);
		$this->load->view('home/footer', $data);
	}

	public function paymenthandler()

	{
		$transactionreference = $this->input->post_get('transactionreference');
		$errorCode = $this->input->post_get('errorcode');
		if (isset($errorCode) && $errorCode == 0) {
			if (isset($transactionreference) && $transactionreference != "") {
				$data = $this->session->userdata('dataJoinNow');

				if (isset($data['name'], $data['email_id']) && $data['name'] != "" && $data['email_id'] != "") {

					$cust_id = $this->session->userdata('cust_id');


					$this->db->query("UPDATE `customer` SET 
								`name` = '" . trim($data['name']) . "', 
								`email_id` = '" . $data['email_id'] . "', 
								`country` = '" . $data['country'] . "',
								`age` = '" . $data['age'] . "', 
								`height` = '" . $data['height'] . "', 
								`weight` = '" . $data['weight'] . "', 
								`gender` = '" . $data['gender'] . "', 
								`activity_lavel` = '" . $data['activitylavel'] . "',
								`status` = 'Y',
								`location_name` = '" . $data['location_name'] . "'
								WHERE `id` = '" . $cust_id . "'");

					$queryBatch = $this->db->query("SELECT `id`, `batch_date` FROM `six_week_batch` WHERE `status` = 'Y' ORDER BY `id` DESC LIMIT 1");
					$rowBatch = $queryBatch->row();

					$this->db->query("INSERT INTO `customer_package` SET 
								`cust_id` = '" . $cust_id . "',
								`package_id` = '2',
								`package_amt` = '" . $data['package_amt'] . "',
								`coupon_code` = '" . $data['coupon_code'] . "',
								`coupon_discount` = '" . $data['coupon_discount'] . "',
								`payment_amt` = '" . $data['amount'] . "',
								`tran_id` = '" . $transactionreference . "',
								`pay_status` = 'Y',
								`batch_id` = '".$rowBatch->id."'");
					
					if(isset($rowBatch->batch_date) && $rowBatch->batch_date != ""){
						$batchStartDate = date('dS F, Y', $rowBatch->batch_date);
					}else{
						$batchStartDate = "";
					}

					if (isset($data['coupon_code'], $data['coupon_discount']) && $data['coupon_code'] != "" && $data['coupon_discount'] > 0) {
						$this->db->from('coupon');
						$this->db->select('id');
						$this->db->where('coupon_code', $data['coupon_code']);
						$query = $this->db->get();
						$couponData = $query->row();

						if (isset($couponData->id) && $couponData->id > 0) {
							$this->db->query("UPDATE `coupon` SET `coupon_used` = (coupon_used + 1), `coupon_avail` = (coupon_avail - 1) WHERE `id` = '" . $couponData->id . "'");
						}
					}

					/* $pdfName['name'] = $data['name'];
					$this->load->library('pdf');
					$file_name = 'Welcome-kit-' . $this->clean($data['name']) . '.pdf';

					$html = $this->load->view('home/welcomedoc', $pdfName, TRUE);
					$this->pdf->create($html, 'uploads/welcomekit/' . $file_name); */

					/* USER EMAIL */

					$msg = '<p>Hello '.trim($data['name']).'</p>

					<p>&nbsp;</p>
					
					<p>Thank you for signing up to the NU Transformation beginning on 9<sup>th</sup> January 2023.</p>
					
					<p>&nbsp;</p>
					
					<p>As you will already know, the NU Transformation is the most comprehensive and effective body and mind transformation available, and we have many thousands of happy customers to date, who have graduated from out programme and transformed their health and their lives.&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>You can read a couple of their testimonials below so that you and be assured that you are in safe hands with the NU By Jordan Storey Team.<br />
					<br />
					<br />
					Testimonial 1 by Katrina, Leeds, UK</p>
					
					<p>I cannot thank Jordan and the Nu team enough! Just tried on a pair of jeans that was so tight at the begininng of the program now they&#39;re loose. Absolutley buzzing!!!!</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>Testimonial 2&nbsp;by Jack, Wakefield, UK<br />
					24lb total loss, more than happy. Eaten foods I never thought I could plus i now like Avocado haha! Can&#39;t wait for the next one. Sign me up now.</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>In order to start your transformation, all you need to do is:&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>- Open up the NU By Jordan Storey App and make sure all your details are correctly entered so that your food is tailored accurately to you &nbsp;</p>
					
					<p>- Join the Facebook group here: <a data-saferedirecturl="https://www.google.com/url?q=https://www.facebook.com/groups/1334349634056960&amp;source=gmail&amp;ust=1669712176863000&amp;usg=AOvVaw19bbEqEArMQTQzIw1ANgLB" href="https://www.facebook.com/groups/1334349634056960" target="_blank">https://www.facebook.com/<wbr />groups/1334349634056960</a></p>
					
					<p>and start to engage with the community and the NU Team.&nbsp;</p>
					
					<p>- Ask any questions you may have in the community group and these will be answered within 24 hours or alternatively you can email <a href="mailto:info@nu-by-js.com" target="_blank">info@nu-by-js.com</a>.&nbsp;</p>
					
					<p>- Once the programme goes live you can pick your meals from the &ldquo;NU Transformation Menu&rdquo; tab and take a look at the workouts&rsquo;on the &ldquo;NU Workouts&rdquo; tab.&nbsp;</p>
					
					<p>- From this point onwards you will get a weekly email explaining what exactly is happening each coming week and there will also be a weekly live call with Jordan too so you can ask him anything you need to.&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>And that&rsquo;s it!&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>We know you will love taking part in the NU Transformation and we look forward to supporting you on your journey.&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>Don&rsquo;t forget to follow us on social media (links below) and we love to see your photos on there too, don&rsquo;t forget to tag us!&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>Thanks again for signing up,&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>The NU By Jordan Storey Team&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>P.S.</p>
					
					<p>&nbsp;</p>
					
					<p>There is an additional information sheet attached to this email to give you even more knowledge and support and ensure you get great results. Don&rsquo;t forget to check it out!&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>&nbsp;</p>
					
					<p>Kindest regards,</p>
					
					<p>&nbsp;</p>
					
					<p>Jordan Storey</p>
					
					<p>&nbsp;</p>
					
					<p>Founder and CEO of NU by Jordan Storey</p>
					
					<p>Contact Number: +44 7528888861</p>
					
					<p>Instagram:@jordanstorey90/@<wbr />nubyjs</p>
					
					<p>Website: <a data-saferedirecturl="https://www.google.com/url?q=http://nu-by-js.com&amp;source=gmail&amp;ust=1669712176863000&amp;usg=AOvVaw09NCkgLhhICQmGjvm_dnaQ" href="http://nu-by-js.com" target="_blank">nu-by-js.com</a></p>
					';

					$this->load->library('email');

					$from_email = "info@nu-by-js.com";
					$to_email = $data['email_id'];

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
					$this->email->subject('Welcome to NU 6 weeks transformation package');
					$this->email->message($msg);
					$this->email->attach('uploads/email/1.png');
					$this->email->attach('uploads/email/2.png');
					$this->email->attach('uploads/email/3.png');
					//Send mail 
					$this->email->send();


					$this->email->clear();
					/* Admin Mail */

					$message = '
				<p><img src="' . base_url() . 'img/logo.png" alt=""></p>
				<table width="800" cellpadding="5" cellspacing="5">
				<tr>
					<th align="left" width="50%">Name</th>
					<td>
						' . $data['name'] . '
					</td>
				</tr>
				<tr>
					<th align="left" width="50%">Email</th>
					<td>
						' . $data['email_id'] . '
					</td>
				</tr>
				<tr>
					<th align="left" width="50%">Age</th>
					<td>
						' . $data['age'] . '
					</td>
				</tr>
				<tr>
					<th align="left" width="50%">Height</th>
					<td>
						' . $data['height'] . '
					</td>
				</tr>
				<tr>
					<th align="left">Weight</th>
					<td>
						' . $data['weight'] . '
					</td>
				</tr>
				<tr>
					<th align="left">Sex</th>
					<td>
						' . $data['gender'] . '
					</td>
				</tr>
				<tr>
					<th align="left">Country</th>
					<td>
						' . $data['country'] . '
					</td>
				</tr>
				<tr>
					<th align="left">Activity level: Score between 1 & 1.9 [1 not active- 1.9 very active]</th>
					<td>
						' . $data['activitylavel'] . '
					</td>
				</tr>
				<tr>
					<th align="left">Transaction ID</th>
					<td>
						' . $transactionreference . '
					</td>
				</tr>
				<tr>
					<th align="left">Payment Amount</th>
					<td>
						GBP ' . $data['amount'] . '
					</td>
				</tr>
			</table>';

					$subject = "Subscription for " . $data['item_name'] . " From nu-by-js.com";

					$from_email = $data['email_id'];
					$to_email = "info@nu-by-js.com";

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
					$this->email->subject($subject);
					$this->email->message($message);

					//Send mail 
					$this->email->send();


					$this->session->unset_userdata('dataJoinNow');
					redirect('success-payment');
				} else {
					redirect('cancel-payment');
				}
			} else {
				redirect('cancel-payment');
			}
		} else {
			redirect('cancel-payment');
		}
	}

	/* ONE TO ONE PACKAGE FORM SUBMIT START */

	public function joinnowsubmit()

	{

		$data['data'] = $this->session->userdata('dataJoinNow');


		$this->load->view('home/header', $data);
		$this->load->view('home/joinnowsubmit', $data);
		$this->load->view('home/footer', $data);
	}

	public function paymenthandleronetoone()

	{
		$transactionreference = $this->input->post_get('transactionreference');
		if (isset($transactionreference) && $transactionreference != "") {
			$data = $this->session->userdata('dataJoinNow');

			if (isset($data['name'], $data['email_id']) && $data['name'] != "" && $data['email_id'] != "") {

				$cust_id = $this->session->userdata('cust_id');


				$this->db->query("UPDATE `customer` SET 
								`name` = '" . $data['name'] . "', 
								`email_id` = '" . $data['email_id'] . "', 
								`age` = '" . $data['age'] . "', 
								`height` = '" . $data['height'] . "', 
								`weight` = '" . $data['weight'] . "', 
								`gender` = '" . $data['gender'] . "', 
								`activity_lavel` = '" . $data['activitylavel'] . "', 
								`status` = 'Y', 
								`goal` = '" . $data['goal'] . "', 
								`occupation` = '" . $data['occupation'] . "', 
								`gym` = '" . $data['gym'] . "', 
								`traintime` = '" . $data['traintime'] . "', 
								`timesperweek` = '" . $data['timesperweek'] . "', 
								`nogym` = '" . $data['nogym'] . "', 
								`dietary` = '" . $data['dietary'] . "', 
								`allergies` = '" . $data['allergies'] . "', 
								`foodddislike` = '" . $data['foodddislike'] . "', 
								`medical` = '" . $data['medical'] . "', 
								`lackenergy` = '" . $data['lackenergy'] . "', 
								`hungry` = '" . $data['hungry'] . "', 
								`hardfood` = '" . $data['hardfood'] . "', 
								`maingoal` = '" . $data['maingoal'] . "', 
								`note` = '" . $data['note'] . "'
								WHERE `id` = '" . $cust_id . "'");


				$this->db->query("INSERT INTO `customer_package` SET 
							`cust_id` = '" . $cust_id . "',
							`package_id` = '1',
							`package_amt` = '" . $data['package_amt'] . "',
							`coupon_code` = '" . $data['coupon_code'] . "',
							`coupon_discount` = '" . $data['coupon_discount'] . "',
							`payment_amt` = '" . $data['amount'] . "',
							`tran_id` = '" . $transactionreference . "',
							`pay_status` = 'Y'");

				if (isset($data['coupon_code'], $data['coupon_discount']) && $data['coupon_code'] != "" && $data['coupon_discount'] > 0) {
					$this->db->from('coupon');
					$this->db->select('id');
					$this->db->where('coupon_code', $data['coupon_code']);
					$query = $this->db->get();
					$couponData = $query->row();

					if (isset($couponData->id) && $couponData->id > 0) {
						$this->db->query("UPDATE `coupon` SET `coupon_used` = (coupon_used + 1), `coupon_avail` = (coupon_avail - 1) WHERE `id` = '" . $couponData->id . "'");
					}
				}

				/* Admin Mail */

				$message = '
            <p><img src="' . base_url() . 'img/logo.png" alt=""></p>
            <table width="800" cellpadding="5" cellspacing="5">
                <tr>
                    <th align="left" width="50%">Name</th>
                    <td>
                        ' . $data['name'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left" width="50%">Email</th>
                    <td>
                        ' . $data['email_id'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left" width="50%">Age</th>
                    <td>
                        ' . $data['age'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left" width="50%">Height</th>
                    <td>
                        ' . $data['height'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Weight</th>
                    <td>
                        ' . $data['weight'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Sex</th>
                    <td>
                        ' . $data['gender'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Goal</th>
                    <td>
                        ' . $data['goal'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Occupation</th>
                    <td>
                        ' . $data['occupation'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Activity level: Score between 1 & 1.9 [1 not active- 1.9 very active]</th>
                    <td>
                        ' . $data['activitylavel'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Do you go the gym</th>
                    <td>
                        ' . $data['gym'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">If so, what time of the day do you usually train</th>
                    <td>
                        ' . $data['traintime'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">How many times per week do you go</th>
                    <td>
                        ' . $data['timesperweek'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">If you don\'t go to a gym, would you consider going and if so how many times per week</th>
                    <td>
                        ' . $data['nogym'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Dietary requirements</th>
                    <td>
                        ' . $data['dietary'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Any food intolerances/allergies</th>
                    <td>
                        ' . $data['allergies'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Any food dislikes</th>
                    <td>
                        ' . $data['foodddislike'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Any medical conditions</th>
                    <td>
                        ' . $data['medical'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">What part of the day do you lack energy</th>
                    <td>
                        ' . $data['lackenergy'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">What part of the day are you most hungry</th>
                    <td>
                        ' . $data['hungry'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Is there any food you find hard to give up</th>
                    <td>
                        ' . $data['hardfood'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">What is your main goal</th>
                    <td>
                        ' . $data['maingoal'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Add any additional notes</th>
                    <td>
                        ' . $data['note'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Package Name</th>
                    <td>
                        GBP ' . $data['item_name'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Payment Amount</th>
                    <td>
                        GBP ' . $data['amount'] . '
                    </td>
                </tr>
                <tr>
                    <th align="left">Transaction ID</th>
                    <td>
                        ' . $transactionreference . '
                    </td>
                </tr>
			</table>';

				$subject = "Subscription for " . $data['item_name'] . " From nu-by-js.com";

				$this->load->library('email');

				$from_email = $data['email_id'];
				$to_email = "info@nu-by-js.com";

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
				$this->email->subject($subject);
				$this->email->message($message);
				//Send mail 
				$this->email->send();

				$this->email->clear();


				/* USER EMAIL */

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
					  <div style="text-align:center; padding:25px 0px 35px 0px;"> <img src="' . base_url() . 'emailimg/logo.png" alt="logo"
						  style="width: 200px;" /> </div>
					  <div style="clear:both;"></div>
					</div>
					<div style=" background-color:#f9f8f8; padding:20px;">
					  <h4 style="font-size: 18px; padding-bottom: 6px;">YOUR ORDER FOR ONE TO ONE WITH JORDAN HAS BEEN RECEIVED, THANK YOU!</h4>
					  <p>Hello,</p>
					  <p>Thank you for signing up to work with me on your personal journey, I am looking forward to working with you and achieving your goals together. </p>
					  <p><a href="#" style="color: #5312ab;text-decoration: none;">Please click here</a> to access your Welcome Pack, which will provide you with some extra information</p>
					  <p>I hope you enjoy the plan and journey with me, please feel free tag to my page and any pictures you post on social media. </p>
					 
					  <p>If you have any questions please do not hesitate to contact me on <a href="#"
						  style="color: #5312ab;text-decoration: none;">info@nu-by-js.com</a> or through our Facebook/Instagram page.
					  </p>
					  <p>We thank you for your support.</p>
					  <p>Best wishes,</p>
					  <p>Jordan Storey <br>Founder and C.E.O of NU by Jordan Storey <br>
						<a href="#" style="color: #5312ab;text-decoration: none;">nu-by-js.com</a> <br>
						<a href="#" style="color: #5312ab;text-decoration: none;">info@nu-by-js.com</a>
					  </p>
					  <div style=" padding:0px;"> <a href="#"><img src="' . base_url() . 'emailimg/logo.png" alt="logo" style="width: 100px;" /></a> </div>
					</div>
					<div style="margin:20px 0px 0px 0px; text-align:center;">
						<a href="https://www.facebook.com/NUbyJS/"><img src="' . base_url() . 'emailimg/i-f.png" alt="logo" /></a> &nbsp; &nbsp;
						<a href="https://twitter.com/NubyJS?s=08"><img src="' . base_url() . 'emailimg/i-li.png" alt="logo" /></a> &nbsp; &nbsp;
						<a href="https://www.instagram.com/nubyjs/?utm_medium=copy_link"><img src="' . base_url() . 'emailimg/i-in.png" alt="logo" /></a>
					</div>
				  </div>
				</body>
				
				</html>';

				$from_email = "info@nu-by-js.com";
				$to_email = $data['email_id'];

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
				$this->email->subject('Welcome to NU One to one with The Transformation Doctor JORDON package');
				$this->email->message($msg);
				//Send mail 
				$this->email->send();

				$this->session->unset_userdata('dataJoinNow');
				redirect('thank-you');
			} else {
				redirect('cancel-payment');
			}
		} else {
			redirect('cancel-payment');
		}
	}

	public function downloadourapp()
	{
		

			$id = $this->session->userdata('cust_id');

			$this->db->from('customer');
			$this->db->select('*');
			$this->db->where('id', $id);
			$queryCust = $this->db->get();
			$data['custData'] = $queryCust->row();

			$this->load->view('home/header', $data);
			$this->load->view('home/downloadourapp', $data);
			$this->load->view('home/footer', $data);
			
		
	}
}
