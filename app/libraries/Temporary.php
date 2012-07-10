<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Temporary
{
    private $TemplateView = array();
    /**
     * @var \CI_Controller instanve
     */
    private $CI;
    public function __construct(){
        $this->CI =& get_instance();
    }
    public function getCompanyName (){
        $this->CI->load->model('Users');
        $UserID = $this->CI->session->userdata('UserID');
        return $this->CI->Users->getCompanyName($UserID);
    }


    
}