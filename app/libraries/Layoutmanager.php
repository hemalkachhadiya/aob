<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Managing layouts
 */
class LayoutManager
{
    private $ComponentFolder = 'master';
    private $TemplateData = array();
    private $TemplateView = array();
    /**
     * @var CI_Controller instance
     */
    private $CI;
    public function __construct(){
        $this->CI =& get_instance();
    }
    public function setMetaTags($configData){
        if (empty($configData['metaTags'])){
            $this->CI->load->model('MetaTags');
            if (!empty($configData["ContentTemplate"])){ // getOutTemplate
                $template = $configData["ContentTemplate"];
                $template = str_replace ("/","_",$template );
                $configData['metaTags'] = $this->CI->MetaTags->getMetaTagsByTemplate($template );
            }else{ // getCabinetTemplate
                $template = strtolower ($configData["TemplateFolder"])."_".strtolower ($configData["TemplateView"]);
                $template = str_replace ("/","_",$template );
                $configData['metaTags'] = $this->CI->MetaTags->getMetaTagsByTemplate($template );
            }

        }
        return array();
    }
    /**
     * @param  $ContentData data for content
     * @param  $ConfigData  data to config js and css
     * @return void
     */
    public function getCabinetTemplate($ConfigData,$ContentData) {
        $this->CI->load->model('Connections');
        $this->CI->load->model('Mail');

        // кол-во непрочитанных сообщений
        $ContentData['mailboxAmount'] = $this->CI->Mail->getNotReadMessages($this->CI->session->userdata('UserID'));
        // кол-во полученных запросов
        $ContentData['connectionRequestAmount']   = $this->CI->Connections->getConnectionRequestsAmountByUserId($this->CI->session->userdata('UserID')) ;
        
        $ConfigData['AccountBool'] = true;
        $ConfigData = array_merge($ConfigData,$ContentData);
        $ConfigData = $this->setMetaTags($ConfigData);
        $this->CI->load->view('main_layouts/typical/component',$ConfigData);

    }
    public function setMenu(){
        $this->CI->load->model('LayoutModel');
        $this->TemplateData['menu'] = array(
            'top_left'  => $this->CI->LayoutModel->getLinks('top_left'),
            'top_right' => $this->CI->LayoutModel->getLinks('top_right'),
            'top_middle' => $this->CI->LayoutModel->getLinks('top_middle'),
            'bottom'    => $this->CI->LayoutModel->getLinks('bottom')
        );

    }
    public function getSimpleTemplate($ConfigData,$ContentData = array()){
            $this->getUserData();
            $this->setMenu();
            $this->setComponentFolder($ConfigData);
            $ConfigData = array_merge($ConfigData,$ContentData);
            $ConfigData = array_merge($ConfigData,$this->TemplateData);


            $this->CI->load->view("main_layouts/{$this->ComponentFolder}/component",$ConfigData);
    }
    /**
     * settinf ComponentFolder path
     * @param  $ConfigData
     * @return void
     */
    public function setComponentFolder($ConfigData){
        if(!empty($ConfigData['ComponentFolder'])){
            $this->ComponentFolder = $ConfigData['ComponentFolder'];
        }
    }
    public function getAdminTemplate($ConfigData,$ContentData = array()){
        $this->CI->load->model('AdminManager');
        $ContentData['StaticPages'] = $this->CI->AdminManager->getStaticPages();
        $ConfigData = array_merge($ConfigData,$ContentData);
        $ConfigData = array_merge($ConfigData,$this->TemplateData);
        $this->CI->load->view('main_layouts/admin/component',$ConfigData);
    }
    public function getByRating(){
        $this->CI->load->model('Users');
        return $this->CI->Users->getByRating();
    }
    public function getUserData(){
        if($this->CI->authmanager->isLogged()){
            $this->CI->load->model('Users');
            $this->TemplateData["userData"]  = $this->CI->Users->getUser($this->CI->session->userdata('UserID'));


            if ($this->TemplateData["userData"]->firstName && $this->TemplateData["userData"]->lastName)
            {

                $this->TemplateData["userData"]->displayName = $this->TemplateData["userData"]->firstName." ".$this->TemplateData["userData"]->lastName;
            }
            else
            {
                $this->TemplateData["userData"]->displayName = $this->TemplateData["userData"]->email;
            }
            $this->TemplateData["userData"]->frontPanelDisplayName = $this->TemplateData["userData"]->displayName ;
            if ($this->TemplateData["userData"]->nickname ){
                $this->TemplateData["userData"]->frontPanelDisplayName = $this->TemplateData["userData"]->nickname ;
            }
        }
    }

}