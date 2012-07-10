<?php

class PageManager extends CI_Model{
    private function setMainSelect(){
        $this->db->select('
                        types.name as typeName,
                        page_list.id,
                        page_list.title,
                        page_list.body,
                        page_list.link,
                        page_list.picture,
                        page_list.createTime,
                        page_list.additional_info
                ');
    }
    public function get($entityType){
        $this->setMainSelect();
        $this->db->from('page_list');
        $this->db->join('types','types.id = page_list.type');
        $this->db->where('types.name',$entityType);
        $this->db->order_by('createTime','DESC');
        $query = $this->db->get();
        return $query->result();
    }
    public function getSystemTemplate($template){
        $this->setMainSelect();
        $this->db->where('template',$template);
        $this->db->from('page_list');
        $this->db->join('types','types.id = page_list.type');
        $query = $this->db->get();
        return $query->row();
    }
    public function get_news_item(){
        return $this->getItem($this->input->get('id'));
    }
    public function get_reviews(){
        return array(
                    'reviews' =>  $this->get('review')
        );
    }
    public function get_news(){
        return array(
                    'news' =>  $this->get('news')
        );
    }
    public function get_books(){
        return array(
            'books' =>  $this->get('books'),
            'quotes' =>  $this->get('quote')
        );
    }
    public function get_contact_us(){

    }
    public function get_random($template){
        $data = $this->get($template);
        if (!empty($data)){
            shuffle ($data);
            return $data;
        }else{
            return false;
        }

    }
    public function get_index(){
        return array(
            'RandomReview'  => $this->get_random('review'),
            'RandomBook'    => $this->get_random('books'),
            'RandomNews'    => $this->get_random('news')
        );
    }
    /** never used */
    public function get_login(){

    }
    public function get_consulting(){

    }
    public function editTemplate(){
        //echo "editTemplate";
        if($this->input->post('id')){
            $this->db->where('id',$this->input->post('id'));
            unset($_POST['id']);


            //var_dump ($_FILES);
            ///var_dump ($_POST);
            //die();
            $this->load->model('Photos');
            $photo = $this->Photos->uploadPhoto($this->input->post('photo_template'));

            if ($photo['status']) :
                $_POST['picture'] = $photo['insertData']['PhotoLink'];
            endif;

            unset($_POST['photo_template']);

            $this->db->update('page_list',$this->input->post());
        }
    }
    public function add ($template){
        $query = $this->db->get_where('types', array('name' => $template));
        $typeId = $query->row()->id;
        $this->db->insert('page_list',array(
           'type'   =>  $typeId,
           'title'  => 'новая страница - редактировать',
           'body'   => 'новая страница - редактировать',
        ));
    }
    public function delete($id){
        $this->db->where('id',$id);
        $this->db->delete('page_list');
    }
    public function getItem($id){
        $this->setMainSelect();
        $this->db->from('page_list');
        $this->db->join('types','types.id = page_list.type');
        $this->db->where('page_list.id',$id);
        $this->db->order_by('createTime','DESC');
        $query = $this->db->get();
        return $query->row();
    }

}