<?
class AuthManager
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }
    public function isSuperAdmin(){
        if ($this->isLogged())
        {
            $this->CI->load->model('Users');
            if ($this->CI->Users->isSuperAdmin($this->CI->session->userdata('UserID'))){
                return true;
            }else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public function isAdmin(){
        if ($this->isLogged())
        {
            $this->CI->load->model('Users');
            if ($this->CI->Users->isAdmin($this->CI->session->userdata('UserID'))){
                return true;
            }else{
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public function isAdminAccessed(){
        if ($this->isAdmin()){
            return true;
        }else{
            redirect();
        }
    }
    public function isAccessed($redirect = 'main/index')
    {
        if ($this->isLogged())
        {
            return true;
        }
        else
        {
            redirect($redirect);
        }
    }

    public function isLogged()
    {
        // session data && expiration time
        $rules = $this->CI->session->userdata('UserID') && $this->CI->input->cookie('expirationTime');
        if ($rules)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function login()
    {

    }
    public function logout($redirect = false)
    {
        $this->CI->session->sess_destroy();
        /*
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');*/
        if (!$redirect)
        {
            redirect('main/index');
        }
        else
        {
            redirect($redirect );
        }

    }
    public function create()
    {

    }
    public function hideLink($link){
        if($this->isLogged()){
            return $link;
        }else{
            return '<a href="" title="" class="loginAction" attr="login" popup="login">';
        }
    }
}