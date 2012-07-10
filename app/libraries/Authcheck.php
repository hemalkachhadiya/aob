<? if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * library for managing user auth
 */
class AuthCheck
{
	protected $CI;
	function __construct(){
		$this->CI =& get_instance();
	}
    /**
     * create user session
     * @param $email_hash
     * @param $pass_hash
     * @param string $redirect destination route to redirect
     * @param string $expirationDays limit for user session
     * @return bool
     */
	public function login($email_hash, $pass_hash, $redirect='',$expirationDays=''){

		$result = mysql_query("
						SELECT id, firstName, lastName, PasswordRestored FROM users 
						WHERE auth_email_hash = '{$email_hash}' AND auth_pass_hash = '{$pass_hash}'
					");
		if (mysql_num_rows($result) > 0)
		{
			$user_info = mysql_fetch_assoc($result);

			$this->CI->session->set_userdata(array('UserID' => $user_info ['id']));
            if (!empty($expirationDays)){
                $this->CI->input->set_cookie('UserID',$user_info ['id'],0); // 14 days = (time() +60*60*24*14)  seconds/minutes/hours/days
            }else{
                $this->CI->input->set_cookie('UserID',$user_info ['id'],(time() +60*60*2)); // 2 hours
            }
            $this->setLocalCron();
			$this->CI->input->set_cookie("pommelouser", json_encode($user_info), (time() + 2592000));
			if ($user_info['PasswordRestored'] > 0)
			{
				$this->CI->db->update("users", array("PasswordRestored" => 0), array("id" => $user_info['id']));
				redirect('profile/change_password');
			}
			else
				redirect($redirect);
		}
		return FALSE;
	}
    /**
     * facade pattern cleaning flash items from db
     * @return void
     */
    public function setLocalCron(){
        $this->CI->load->model('ProductManager');
        $this->CI->load->model('GroupManager');
        $this->CI->ProductManager->clearProducts($this->CI->session->userdata('UserID'));
        $this->CI->GroupManager->clearGroups($this->CI->session->userdata('UserID'));
    }
	/**
     * unset user session
     * @param string $redirect
     */
	public function logout($redirect=''){
        $this->CI->session->sess_destroy();
        $this->CI->input->set_cookie("pommelouser", FALSE);
        $this->CI->input->set_cookie("UserID", FALSE);
		/*if ($this->CI->input->cookie('pommelouser')){
            $this->CI->input->set_cookie("pommelouser", FALSE, (time() - 2592000));
        }*/
        if (!empty($redirect)){

            redirect($redirect);
        }
	}
    /**
     * check if user is authed
     * @return bool
     */
    public function getLoginStatus(){
        if ($this->CI->session->userdata('UserID')){
            return true;
        }else{
            if ($this->CI->input->cookie('UserID')){
                $this->CI->session->set_userdata('UserID',$this->CI->input->cookie('UserID'));
                return true;
            }else{
                return false;
            }
        }
    }
	/**
     * @deprecated
     * @return bool|mixed
     */
	public function isLogged(){
		if (isset($_POST['makeExit'])) $this->logout($_POST['makeExit']);
		if ($this->CI->input->cookie('pommelouser'))
			return json_decode($this->CI->input->cookie('pommelouser'), true);
		else
			return FALSE;
	}

}


/* End of file Auth.php */