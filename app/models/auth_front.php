<?php
	class Auth_front extends CI_Model{
		public function __construct(){
			parent::__construct();
		}
        public function getEmailPassword(){
            $this->db->where('id_user',$this->session->userdata('id_user'));
            $query = $this->db->get(DB_AUTH_FRONT);
            return $query->row();
        }
        /**
         * выставляем тип авторизации
         * @param  $type string AdvancedUser||SimpleUser
         * @return void
         */
        public function setTypeView($type){
            $data = array(
                "typeView" => $type
            );
            $this->db->where('id_user',$this->session->userdata('id_user'));
            $this->db->update(DB_AUTH_FRONT, $data);

        }
		/*
		 * создание пользователя в табличке auth_front
		 */
		public function createAuth($email,$password,$type,$firstName,$lastName){
            
			$data = array(
               'email'             => $email,
               'password'          => md5($password),
			   'confirmation_hash' => md5($email.$password),
               'type'              => $type,
               'firstName'         => $firstName,
                'lastName'         => $lastName,
                'lastLogin'        => date('Y-m-d H:i:s')
            );
			$this->db->insert('users', $data);
			if ($this->db->affected_rows()>0)
				return true;
			else
				return false;
		}
        public function createRemoteAuth($email,$password,$RBAC_type,$name,$surname){
            $nickname = explode ("@",$email);
            $user_login = $username = $email;

            $this->load->model ('Remote_auth');
            $this->Remote_auth->create_blog_local_member ($user_login, md5($password), $nickname[0], $email, $nickname[0]);
            $this->Remote_auth->create_forum_local_member($nickname[0], md5($password), $email);
            // todo turn on confirmation
            

        }
        public function getUser($id){
            $this->db->where('id', $id);
            $query = $this->db->get('users');
            return $query->row();
        }
		/*
		 * проверка существования пользователя по email
		 * $email - почта
		 */
		public function ifAuthExist($email){
			$this->db->select('id');
			$this->db->where('email', $email);
			$this->db->from('users');
			if ( $this->db->count_all_results() > 0)
				return true;
			else		
				return false;
			
		}
        public function confirmUser($confirmation_hash){
            $query  = $this->db->get_where('users',array('confirmation_hash' => $confirmation_hash));
            if ( $this->db->affected_rows() > 0)
            {
                $id = $query->row()->id;
                $data = array ('confirmation_hash' => 1);
                $this->db->where(array('confirmation_hash' => $confirmation_hash));
                $this->db->update('users',$data);
                return $id;
            }
            else
            {
                return false;
            }

        }
		/*
		 * подтверждение пользователя апдейт поля confirmation
		 */
		public function confirmUserD($confirmation_hash){
			$result['status'] = false; // флаг  успешного завершения метода
			// установка флага confirmation в единицу по хешу
			$data = array(
               'confirmation' => 1
            );

			//$this->db->where('confirmation_hash', $confirmation_hash);
            //$this->db->like('confirmation_hash', $confirmation_hash, 'both');
			//$this->db->update(DB_AUTH_FRONT, $data);
            $query = $this->db->query('update '.DB_AUTH_FRONT.' set confirmation = 1 where confirmation_hash="'.$confirmation_hash.'"');
            
			$result['affected_rows'] = $this->db->affected_rows();
            

           /// print_r($result['affected_rows']);

			if ($result['affected_rows'] == 1){ // если установка флага была произведена,то
				$data = array(					// вставить пользователя
					'ci_enter' => 1
            	);
				$this->db->insert('users', $data); 
				
				$id = $this->db->insert_id();	// выбираем последний id под которым было вставлено запись 
												// в таблицу user
				$data = array(					
					'id_user' => $id,
				   	'confirmation_hash' => 0
	            );
				$this->db->where('confirmation_hash', $confirmation_hash);
				$this->db->update('auth_front', $data);		// апдейтим таблицу аккаунтов
						
				//$result['affected_rows_new'] = $this->db->affected_rows();
				
				$this->db->select('email,id_type,tmp_name,tmp_surname,password');
				$this->db->where(array(
									'id_user' => $id
				));
				$this->db->from('auth_front');
				$query = $this->db->get();
					
				$row = $query->row_array();

				$result['id_user']		= $id;
				$result['email']    = $row['email'];
                $result['id_type']    = $row['id_type'];
				$result['status']	= true;
                $result['password'] = $row['password'];

                $this->updateNameSurname(mysql_real_escape_string($row['tmp_name']),mysql_real_escape_string($row['tmp_surname']),$id); // переносим имя и фамилию в табличку users из Auth_front
                // todo разбить дополнительное создание по ролям
				$this->createMailFolder($id,1,0,'Полученные');  // создать папку почты
				$this->createMailFolder($id,0,0,'Отправленные');

                $this->createTimetableRecords($id);             // создать расписание

                $this->createAddressRecords($id,0);             // созадать записи в талице адресов
                $this->createAddressRecords($id,1);
			}
			return $result;
		}
        public function updateNameSurname($name,$surname,$id){
				$data = array(
					'name'          => $name,
				   	'surname'       => $surname,
                    'short_name'    => $name.' '.$surname        
	            );
				$this->db->where('id', $id);
				$this->db->update('users', $data);
        }
        /**
         * @param  $id
         * @param  $type   0 - home 1 - work
         * @return void
         */
        private function createAddressRecords ($id,$type){
            
            $data = array(
                            'address_type' => $type,
                            'id_user' => $id
            );
            $query = $this->db->insert(DB_USER_ADDRESS,$data);

        }
    /**
     * создаем учетные записи для пользователей
     * @return void
     */
        public function createTimetableRecords($id){
            $DayArray = array('Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');
            foreach ($DayArray as $day){
                $data = array(
                                'timeDay' => $day,
                                'id_user' => $id
                );
                $query = $this->db->insert(DB_USER_TIMETABLE,$data);
            }
        }
		/*
		 * добавить папку для писем 
		 * 1 - in
		 * 2 - out
		 */
		public function createMailFolder($id_user,$folder_type,$deletable,$name){
			$data = array(
               'id_user' => $id_user,
               'folder_type' => $folder_type,
               'deletable' => $deletable,
			   'name' => $name
            );

			$this->db->insert('user_mail_additional_folders', $data);
			if ( 1 == $this->db->affected_rows()){
				return true;
			}
			else {
				return false;
			}		
		}
		/*
		 * добавить фотоальбом
		 * 1 - in
		 * 2 - out
		 */
		public function createPhotoAlbum($id_user,$folder_type,$deletable,$name){
			$data = array(
               'id_user' => $id_user,
               'folder_type' => $folder_type,
               'deletable' => $deletable,
			   'name' => $name
            );

			$this->db->insert('user_mail_additional_folders', $data);
			if ( 1 == $this->db->affected_rows()){
				return true;
			}
			else {
				return false;
			}
		}
    /**
     *
     * @param  $email
     * @param  $password - already md5
     * @return
     */
    //todo убрать это безобразие
		public function logInAdminExistance($email,$password){
			$this->db->select('id_user, confirmation, id_type');
			$this->db->where(array(
									'email' => $email,
									'password' => $password
			));
			$this->db->from('auth_front');
			$query = $this->db->get();

			$row = $query->row_array();
			$totalNum = $query->num_rows();
			$row ['number'] = $totalNum;
            if ($row['number'] == 0) {
                $row['confirmation'] = 0;
            }
			return $row;

		}
		/*
		 * проверка существования аккаунта
		 * return array(
		 * 	id_user			- users.id по которому пойдет выборка в users 
		 * 	confirmation	- подтверждение
		 * 	number 			- количество записей выборки
		 * )
		 */
		public function logInExistance($email,$password){
			$this->db->select('id_user, confirmation, id_type');
			$this->db->where(array(
									'email' => $email,
									'password' => md5($password)
			));
			$this->db->from('auth_front');
			$query = $this->db->get();
					
			$row = $query->row_array();
			$totalNum = $query->num_rows();
			$row ['number'] = $totalNum;
            if ($row['number'] == 0) {
                $row['confirmation'] = 0;
            }
			return $row; 
			
		}
		/*
		 * вынуть из базы 
		 * имя и фамилию
		 * $id - айди пользователя в таблице users 
		 */
		public function get_name_surname_by_id($id){
			$this->db->select('name, surname, avatarka_link');
			$this->db->where(array(
									'id' => $id
			));
			$query = $this->db->get(DB_USERS);
					
			$row = $query->row();
			
			return $row; 			
		}
        public function getEncryptedPassword($id_user){
            $this->db->select('encrypted_password');
			$this->db->where(array(
									'id_user' => $id_user
			));
            $query = $this->db->get('auth_front');
            $row = $query->row();
            return $row;
        }
		//от взлома
		public function validateData($email,$f_password,$s_password){
			return true;
		}

        public function PasswordRecovery($email){
			$password_recovery = md5(time().$email);
			$data = array(
               'password_recovery' => $password_recovery
            );

			$this->db->where('email', trim($email));
			$this->db->update('auth_front', $data);

			$this->sendPasswordRecovery($password_recovery,$email);

		}
		public function sendPasswordRecovery($password_recovery,$email){
			$this->load->library('email');
			$this->email->from('info@pomada.ua', 'Администрация');
			$this->email->to($email);
			$this->email->subject('Пожалуйста, подтвердите смену пароля на pomada.ua');
			$text = "Уважаемый пользователь pomada.ua,<br>Пожалуйста, смену пароля на pomada.ua. Пройдя по ссылке :
					<br/> <a href='http://pomada.ua/main/confirm_recovery/".$password_recovery."/'  >http://pomada.ua/main/confirm_recovery/".$password_recovery."</a>";
			$this->email->message($text);
			$this->email->send();
		}
		public function ConfirmPasswordRecovery($password_recovery){
			$password = $this->generate_password(8);

			$this->db->where("password_recovery", $password_recovery);
			$this->db->select('email');
			$query = $this->db->get('auth_front');
            if ($this->db->affected_rows() > 0 ){
                $email = $query->row()->email;

                $this->sendPassword($password,$email);
                $this->db->where("password_recovery", $password_recovery);
                $data = array(
                   'password' => md5($password),
                   'password_recovery' => ""
                );
                $this->db->update('auth_front', $data);
                return true;
            } else{
                return false;
            }
	    }
		public function sendPassword($password,$email){
			$this->load->library('email');
			$this->email->from('info@pomada.ua', 'Администрация');
			$this->email->to($email);
			$this->email->subject('новый пароль на pomada.ua');
			$text = "Уважаемый пользователь pomada.ua, Ваш новый пароль {$password}";
			$this->email->message($text);
			$this->email->send();
		}

		public function generate_password($number) {
			$arr = array('a','b','c','d','e','f',
						 'g','h','i','j','k','l',
						 'm','n','o','p','r','s',
						 't','u','v','x','y','z',
						 'A','B','C','D','E','F',
						 'G','H','I','J','K','L',
						 'M','N','O','P','R','S',
						 'T','U','V','X','Y','Z',
						 '1','2','3','4','5','6',
						 '7','8','9','0','.',',',
						 '(',')','[',']','!','?',
						 '&','^','%','@','*','$',
						 '<','>','/','|','+','-',
						 '{','}','`','~');
			// Генерируем пароль
			$pass = "";
			for($i = 0; $i < $number; $i++)
			{
			  // Вычисляем случайный индекс массива
			  $index = rand(0, count($arr) - 1);
			  $pass .= $arr[$index];
			}
			return $pass;
		  }

        public function addMarker($id,$data){
            $this->db->where ('id',$id);
            $this->db->update (DB_USERS,$data);
        }

        /*
		 * подтверждение пользователя апдейт поля confirmation
		 */
		public function confirmUser1C($confirmation_hash){
			$result['status'] = false; // флаг  успешного завершения метода
			// установка флага confirmation в единицу по хешу
			$data = array(
               'confirmation' => 1
            );

			//$this->db->where('confirmation_hash', $confirmation_hash);

			//$this->db->get(DB_AUTH_FRONT, $data);
            $query = $this->db->query('update '.DB_AUTH_FRONT.' set confirmation = 1 where confirmation_hash="'.$confirmation_hash.'"');

			$result['affected_rows'] = $this->db->affected_rows();


           /// print_r($result['affected_rows']);

			if ($result['affected_rows'] == 1){ // если установка флага была произведена,то

                $this->db->where('confirmation_hash', $confirmation_hash);
                $query = $this->db->get(DB_AUTH_FRONT);
                $id = $query->row()->id_user;



				$data = array(
					'id_user' => $id,
				   	'confirmation_hash' => 0
	            );
				$this->db->where('confirmation_hash', $confirmation_hash);
				$this->db->update('auth_front', $data);		// апдейтим таблицу аккаунтов

				//$result['affected_rows_new'] = $this->db->affected_rows();

				$this->db->select('email,id_type,tmp_name,tmp_surname,password');
				$this->db->where(array(
									'id_user' => $id
				));
				$this->db->from('auth_front');
				$query = $this->db->get();

				$row = $query->row_array();

				$result['id_user']		= $id;
				$result['email']    = $row['email'];
                $result['id_type']    = $row['id_type'];
				$result['status']	= true;
                $result['password'] = $row['password'];

                
                // todo разбить дополнительное создание по ролям
				$this->createMailFolder($id,1,0,'Полученные');  // создать папку почты
				$this->createMailFolder($id,0,0,'Отправленные');

                $this->createTimetableRecords($id);             // создать расписание

                $this->createAddressRecords($id,0);             // созадать записи в талице адресов
                $this->createAddressRecords($id,1);
			}
			return $result;
		}
        public function checkLogin($email,$password){
            $this->db->where (array(
                'email'     => $email,
                'password'  => md5($password),
                //'confirmation_hash' => 1
            ));
            $query = $this->db->get('users');
            if ($this->db->affected_rows()>0)
            {
                return $query->row()->id;
            }
            else
            {
                return false;
            }
        }
}