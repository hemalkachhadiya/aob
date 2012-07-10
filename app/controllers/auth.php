<?
    /**
     *  Managing user's session
     *  and all actions related to user auth
     */
	class Auth extends CI_Controller {
	
		public function __construct(){
			parent::__construct();
			$this->load->database();
			$this->load->library(array('zmail'));
			$this->load->helper(array('form', 'url'));
            $this->load->model('Mail');
		}
		public function main(){
			redirect('auth/main');
		}
        /**
         * front page
         */
		public function index(){
	            $ConfigData = array("ContentTemplate" => "common/index");
	            $this->layoutmanager->getOutTemplate($ConfigData);
		}
		/**
         * login action for user auth
         * getting post params
         */
		public function login() {
			$data = array();
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required');
			
			if ($this->form_validation->run() == TRUE)
			{
                if ($this->input->post('remember_me')){
                    $expiretionDays = 14;
                    $this->input->set_cookie('rememberEmail',$_POST['email'],0);
                    $this->input->set_cookie('rememberPassword',$_POST['password'],0);
                }else{
                    $expiretionDays = '';
                    $this->input->set_cookie('rememberEmail',false);
                    $this->input->set_cookie('rememberPassword',false);
                }
                $redirect = 'profile';
                if ($this->input->post('redirect') && $this->input->post('redirect')!= 'auth/login'){
                    $redirect = $this->input->post('redirect');
                }
				$success = $this->authcheck->login(sha1( "left" . md5(mb_strtolower($_POST['email'])) . "right" ), sha1( "right" . md5($_POST['password']) . "left" ), $redirect,$expiretionDays);
				if ($success == FALSE)
					$data['error'] = true;
			}
			
			$ConfigData = array("ContentTemplate" => "auth/login");
			
			$this->layoutmanager->getOutTemplate($ConfigData,$data);
		}

        /**
         * invite page
         */
		public function invite() {
			$this->load->model('address');
			$data = array("states" => $this->address->getStatesList('title,code'));
			
			$this->form_validation->set_message('required', 'Please enter your %s');
			$this->form_validation->set_message('is_unique', 'This email is already taken.');
			$this->form_validation->set_message('valid_email', 'Please enter a valid email address.');
			$this->form_validation->set_message('regex_match', 'Please enter a valid %s.');
			
			if ($this->form_validation->run() == FALSE)
			{	
				$ConfigData = array("ContentTemplate" => "auth/invite");
			}
			else
			{
				$data['scenario'] = $this->inviteScenarios($_POST['email']);
				
				if ($data['scenario'] == 'ignored')
					$this->db->update('invite_requests', array("intime" => time()), array("email" => $_POST['email']));
				
				if ($data['scenario'] == 'rejected')
					$this->db->delete('invite_requests', array("email" => $_POST['email'], "used" => 2));
				
				if ($data['scenario'] == 'none' || $data['scenario'] == 'rejected'){
					$this->db->insert(
						"invite_requests", 
						array("intime" => time(), "email" => $_POST['email'], "fullName" => $_POST['firstName'] . " " . $_POST['lastName'] . " from " . $_POST['company'], "sRequest" => serialize($_POST))
					);
				}
			
                		$ConfigData = array("ContentTemplate" => "auth/invite_sent");
			}


			$this->layoutmanager->getOutTemplate($ConfigData,$data);

		}
        /**
         * method-helper for invite action
         */
		public function inviteScenarios($email){
			// scenario 1: User requested an invite and we have approved it.  Perhaps the user didn't see the approval email and tries to request invite again.
			$result = mysql_result(mysql_query("SELECT COUNT(id) FROM users WHERE email = '{$email}' AND CloseAccountCauseID = 0"), 0);
			$result2 = mysql_result(mysql_query("SELECT COUNT(id) FROM invite_requests WHERE email = '{$email}' AND inviteCode <> ''"), 0);
			if ($result > 0 || $result2 > 0)
				return 'existing';
			
			// scenario 2: User requested an invite, but we reject the request.  The user continues to request an invite.
			$result = mysql_result(mysql_query("SELECT COUNT(id) FROM invite_requests WHERE email = '{$email}' AND used = 2"), 0);
			if ($result > 0)
				return 'rejected';
				
			// scenario 3: User requested an invite, but we overlooked the request in the queue, so user sends another request.
			$result = mysql_result(mysql_query("SELECT COUNT(id) FROM invite_requests WHERE email = '{$email}' AND used = 0 AND inviteCode = ''"), 0);
			if ($result > 0)
				return 'ignored';
				
			return 'none';
		}
		
		
		/**
         * action for password restore
         */
		public function remind() {
			$data = array();

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			
			if ($this->form_validation->run() == FALSE)
			{
				$data['esult'] = false;
				$data['error'] = "Please enter valid email.";
			}
			else
			{
				$query = $this->db->get_where('users', array("email" => $_POST['email'], "CloseAccountCauseID" => 0), 1);
				if ($query->num_rows() > 0)
				{
					$user = $query->row_array();
					$pass = substr(md5(date("iHY") . rand(0, 99)) , 3, 10);
					$this->db->update('users', array("auth_pass_hash" => sha1( "right" . md5($pass) . "left" ), "PasswordRestored" => 1 ), "id = {$user['id']}");

                    $data = array("pass"     => $pass,
                                  "name"     => $user['firstName'] . " " . $user['lastName'],
                                  "template" => "pass_remind",
                                  "email"    => $user['email'],
                                  "subject"  => "Your Pommelo password has been reset");
                    $this->Mail->sendMail($data);


					$data['email'] = $user['email'];
					$data['esult'] = true;
				}
				else
				{
					$data['esult'] = false;
					$data['error'] = "This email is not registered with Pommelo. Please check the email you entered.";
				}
			}
			echo json_encode($data);
		}
		
		/**
         * registration page
         * @param string $code - Code from email
         */
		public function register($code='') {

			if (empty($code))
				redirect('auth/invite/', 'refresh');
					
			$data['pageTitle'] = "Registration Page";
			
			$this->db->where(array("inviteCode" => $code, "used" => 0))->order_by('id', 'desc');
			$query = $this->db->get('invite_requests', 1);
            //var_dump ($query->row_array());
			$data['prefill'] = $query->row_array();
            
            // list of industries
            $this->load->model("Industry");
            $data['industryList'] = $this->Industry->getItems();
			if (empty($data['prefill'])):
				
				$ConfigData = array("ContentTemplate" => "auth/register_nocode");

			else:
				$this->load->model('address');
				
				$data['step'] = !isset($_POST['step']) ? 1 : $_POST['step'];
				$data['prefill'] = unserialize($data['prefill']['sRequest']);
				$data['states'] = $this->address->getStatesList();
				
				switch ($data['step'])
				{
					case 2: 
						
						if ($this->form_validation->run('auth/register_step1') == FALSE)
						{
							$data['step'] = 1;
						}
						else
						{	
							$this->session->set_userdata('reg', array(
									"email" => mb_strtolower($_POST['email']),
									"auth_email_hash" => sha1( "left" . md5(mb_strtolower($_POST['email'])) . "right" ),
									"auth_pass_hash" => sha1( "right" . md5($_POST['password']) . "left" ),
									"firstName" => $_POST['firstName'], "lastName" => $_POST['lastName'], "active" => 0
								)
							);
						}
												
					break;
					
					case 3: 
						
						if (!is_array($this->session->userdata('reg')))
							$data['step'] = 1;
						
						if ($this->form_validation->run('auth/register_step2') == FALSE)
							$data['step'] = 2;
							
						
						if ($data['step'] == 3)
						{
							$data['user'] = $this->session->userdata('reg');
							
							$this->db->update("invite_requests", array("used" => 1), "inviteCode = '{$code}'");
							$this->db->insert("users", $data['user']);
							
							$_POST['UserID'] = $this->db->insert_id();
							$_POST['registered'] = time();
							$_POST['CompanyEmail'] = $data['user']['email'];
							
							unset($_POST['step']);
							
							$this->db->insert("companies", $_POST);


							// filling users_info for additional products
							$this->db->insert("users_info",array('UserID' => $_POST['UserID']) );
							// percentege counter
							$this->db->insert("percentage_meter",array('userID' => $_POST['UserID']) );
							// user prefernces 
							$this->db->insert("users_preferences",array('userID' => $_POST['UserID']) );
							//todo remove to model
							$settingsQuery = $this->db->get ('users_communication_settings');
							foreach ($settingsQuery->result() as $item ){
								$settingsArray = array( 'UserID' => $_POST['UserID'], 'SettingsID' => $item->id  );
								$this->db->insert('users_communication_connections',$settingsArray);
							}

                            $maildata = array(  "name"      => $data['user']['firstName'] . " " . $data['user']['lastName'],
                                            "template"  => "user_register",
                                            "email"     => array("uatrance@gmail.com",$_POST['CompanyEmail']),
                                            "subject"   => "Your registration on Pommelo.com");
                            $this->Mail->sendMail($maildata);
						}
												
					break;
				}	
				
				$ConfigData = array("ContentTemplate" => "auth/register");
			endif;		
			$this->layoutmanager->getOutTemplate($ConfigData, $data);
		}
        /**
         * destroying user session and redirecting to new another page
         * @param string $redirect - Place where user would be taken after logout
         */
		public function logout ($redirect=''){
            if (!empty($redirect)){
                $redirect = str_replace("_","/",$redirect);
            }
			$this->load->library('authcheck');
			$this->authcheck->logout($redirect);
		}

        /**
         * Checking via ajax
         * user existance in database
         */
		public function checkAjaxLogIn(){
            //echo $this->input->post('password').sha1( "right" . md5(trim($this->input->post('password'))) . "left" );
			$whereData = array(
			                    'auth_email_hash' => sha1( "left" . md5(mb_strtolower($this->input->post('email'))) . "right" ),
			                    'auth_pass_hash'  => sha1( "right" . md5(trim($this->input->post('password'))) . "left" ));
			$this->load->model('Users');
			$tmpUser = $this->Users->checkAjaxLogIn($whereData);
			
			
			if (!empty($tmpUser)){
				$resultArray["status"] = true;
				
				if ( $tmpUser->CloseAccountCauseID > 0 ){
					$resultArray["status"] = false;
					$resultArray["message"] = '<label class="error">account is closed</label>';
				}
			} else {
				$resultArray["status"] = false;
				$resultArray["message"] = '<label class="error">login and password do not match</label>';
			}
				
			echo json_encode($resultArray);
		}

		/**
         * password validatiion
         * @param $pass
         * @return bool
         */
		public function CB_password($pass){
			$gendalf = FALSE;
			
			if (!preg_match("/((?=.*[a-z]).{6,20})/", $pass) && !preg_match("/((?=.*[A-Z]).{6,20})/", $pass))
				$gendalf = "YOU SHALL NOT PASS!!!";
				
			if (!preg_match("/((?=.*\d).{6,20})/", $pass))
				$gendalf = "YOU SHALL NOT PASS!!!";
			
			if (!preg_match("/((?=.*[!@#$%^&*()]).{6,20})/", $pass))
				$gendalf = "YOU SHALL NOT PASS!!!";
				
			if ($gendalf == FALSE)
				return TRUE;
			else {
				$this->form_validation->set_message('CB_password', 'Your password must contain at least one lowercase or one uppercase letter, one number and one symbol (!@#$%^&*).');
				return FALSE;
			}
		}
        public function getPassword($password){
            echo sha1( "right" . md5($password) . "left" );
        }
        
        /*
        public function create10000users(){
        
        	for ($i = 10006; $i < 20001; $i++):
        	
	        	$data['user'] = array(
				"email" => mb_strtolower("fakeemail{$i}@pommelo.com"),
				"auth_email_hash" => sha1( "left" . md5("fakeemail{$i}@pommelo.com") . "right" ),
				"auth_pass_hash" => sha1( "right" . md5("fp{$i}pf") . "left" ),
				"firstName" => "FakeUser", "lastName" => $i, "active" => 0
	        	);
	        
	        	$this->db->insert("users", $data['user']);
								
			$_POST['UserID'] = $this->db->insert_id();
			
			$data['company'] = array(
				'UserID' => $_POST['UserID'],
				'registered' => time(),
				'CompanyEmail' => "fakeemail{$i}@pommelo.com",
				'CompanyName' => 'Fake Inc. #' . $i,
				'industry' => rand(1,15)
			);
			
			$this->db->insert("companies", $data['company']);
	
			$this->db->insert("users_info",array('UserID' => $_POST['UserID']) );
			$this->db->insert("percentage_meter",array('userID' => $_POST['UserID']) );
			$this->db->insert("users_preferences",array('userID' => $_POST['UserID']) );

			$settingsQuery = $this->db->get ('users_communication_settings');
			foreach ($settingsQuery->result() as $item ){
				$this->db->insert('users_communication_connections', array( 'UserID' => $_POST['UserID'], 'SettingsID' => $item->id  ));
			}
		endfor;
		
		$this->load->view("_layouts/test", $data);

      }
	*/
		
	}