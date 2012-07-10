<?php
class AdminManager extends CI_Model
{
    public function getStaticPages(){
        $query = $this->db->get('static_page');
        return $query->result();
    }
}