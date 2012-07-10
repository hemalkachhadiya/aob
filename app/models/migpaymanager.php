<?php

class MigPayManager extends CI_Model
{
    /** unique order_nr for mig-pay
     */
    public function setOrderNr ($amount,$id_system = 1){
        $data = array(
            'id_user'   => $this->session->userdata('UserID'),
            'mig_sum'   => $amount,
            'id_system' => $id_system,
            'id_user_owner'=>$this->session->userdata('UserID')
        );
        $this->db->insert('users_payment', $data);
        return $this->db->insert_id();
    }
    public function setOrderStatus($id,$status){
        $this->db->where('id',$id);
        $this->db->update('users_payment',array('status'=>$status));
    }


}