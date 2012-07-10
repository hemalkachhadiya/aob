<?
class GeoHelper extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Geo');
    }
    public function getCities()
    {
        $result['data'] = $this->Geo->getCity($this->input->post('countryId'));
        if (!empty($result['data'])){
            $result['status'] = true;
        }else{
            $result['status'] = true;
        }
        echo json_encode($result);
    }
}
