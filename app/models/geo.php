<?php
class Geo extends CI_Model{
    /**
     * get all countries
     * @return bool
     */
    public function getCountry(){
        $this->db->order_by('active','DESC');
        $this->db->order_by('name','ASC');
        $query = $this->db->get_where('geo_country'/*, array('active'=>1)*/);

        if ($this->db->affected_rows()>0)
        {
            return $query->result();
        }
        else{
            return false;
        }
    }
    /**
     * get cities in country
     * @param $countryId
     * @return bool
     */
    public function getCity($countryId){
        $query = $this->db->get_where('geo_city', array('id_country'=>$countryId));
        if ($this->db->affected_rows()>0)
        {
            return $query->result();
        }
        else{
            return false;
        }
    }
}