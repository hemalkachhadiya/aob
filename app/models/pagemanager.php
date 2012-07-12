<?php

class PageManager extends CI_Model{
    private function setMainSelect(){
        $this->db->select('
                        types.name as typeName,
                        page_list.id,
                        page_list.published,
                        page_list.title,
                        page_list.body,
                        page_list.link,
                        page_list.picture,
                        page_list.createTime,
                        page_list.additional_info,
                        page_list.template,
                ');
    }
    public function get($entityType,$page = false,$search=false,$addConfig = false){
        $this->setMainSelect();
        $this->db->from('page_list');
        $this->db->join('types','types.id = page_list.type');

        $this->db->order_by('createTime','DESC');

        if (!empty($search)) :
            $this->db->like('title',$search,'both');
            $this->db->or_like('body',$search,'both');
        else:
            $this->db->where('types.name',$entityType);
        endif;

        if ($addConfig) {
            $this->db->where($addConfig);
        }

        if (!empty($page)) :
            //specially for ajax items loading and no more else
            if ($this->input->post('amount')){
                $amount = $this->input->post('amount');
            }else{
                $amount = NEWS_RESULTS;
            }
            $this->db->limit($amount , ($page-1)*$amount );
        endif;
        $query = $this->db->get();
        if ($this->db->affected_rows() > 0){
            $list = $query->result();
            foreach ($list as $item):
                $item->shortBody    = smarty_modifier_mb_truncate(trim($item->body),250,'...',false,'UTF-8',false );
                $item->createTime   = setDate($item->createTime);
                if($item->template)
                    $item->link = "/page/{$item->template}";
                else
                    $item->link = "/page?id={$item->id}";
            endforeach;
            return $list;
        }
        else
            return false;
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
        if (!$this->input->get('id'))
            redirect('main/error');
        else
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
    public function get_random($template,$addConfig = false){
        $data = $this->get($template,false,false,$addConfig);
        if (!empty($data)){
            shuffle ($data);
            return $data;
        }else{
            return false;
        }

    }
    public function get_index(){
        return array(
            'RandomUsefulList'  => $this->get_random('useful',array("published" => 1))
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
    public function get_page(){
        return array(
                            'list' =>  $this->get('page')
                );
    }
    public function get_menu(){}
    public function get_useful() {

    }
    public function isTemplate($template){
        $this->db->get_where('page_list',array(
            'template'  => $template
        ));
        if ($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    public function get_search(){
        return array(
            'list'      => $this->get(false,false,$this->input->post('search')),
            'search'    => $this->input->post('search')
        );
    }

}