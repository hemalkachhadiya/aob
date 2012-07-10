<?php
class MailController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->authmanager->isAccessed();
        $this->load->model('MessageManager');

    }
    public function index()
    {
        $data = array(
            'list'  => $this->MessageManager->getChainsList($this->session->userdata('UserID'),true),
            'amount'=> $this->MessageManager->getChainsList($this->session->userdata('UserID')),
        );

        $ConfigData = array(    //'wide'   => true,
                                "ContentTemplate"   => "mail/chains");
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function chain($id = false){
        $dataSlave = array();
        if (!$id and $this->input->get('idTo')){
            $id = $this->MessageManager->getChainByUsers($this->session->userdata('UserID'),$this->input->get('idTo'));
        }
        if ($id and $this->MessageManager->isChainExist($id)){
            $this->MessageManager->setChainMailRead($id);
            $dataSlave = array(
                'list'  => $this->MessageManager->getChain($id,true),
                'amount'=> $this->MessageManager->getChain($id));
         }

        $dataMaster = array (
            'chainId' => $id,
            'idTo'    => $this->input->get('idTo')
        );
        $data = array_merge($dataMaster,$dataSlave);

        $ConfigData = array( "ContentTemplate"   => "mail/chain" );
        $this->layoutmanager->getSimpleTemplate($ConfigData,$data);
    }
    public function create(){
        $data = $this->input->post();
        if (!$data['chain']){
             $data['chain'] = $this->MessageManager->setChain();
             $data['chain']++;
        }
        $data['id_from'] = $this->session->userdata('UserID');
        $this->MessageManager->create($data);
        redirect ('mail/chain/'.$data['chain']);
    }
    public function deleteMessage($chainId,$id){
        $this->MessageManager->deleteMessage($id);
        redirect('mail/chain/'.$chainId);
    }
}
