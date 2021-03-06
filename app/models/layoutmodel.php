<?php

    class LayoutModel extends CI_Model{

        public function getLinks($type){
            $this->setMainSelect();
            $this->db->from('menu');
            $this->db->join('menu_types','menu_types.id = menu.id_type');
            $this->db->where('menu_types.title',$type);
            $this->db->order_by('createTime','DESC');
            $query = $this->db->get();
            return $query->result();
        }
        public function editMenu(){
            $this->db->where('id',$this->input->post('id'));
            $this->db->update('menu',array(
                'link'  =>  $this->input->post('link'),
                'title' =>  $this->input->post('title')
            ));
        }
        public function add($typeId){
            $this->db->insert('menu',array(
                'id_type'   => $typeId
            ) );
        }
        public function delete($id){
            $this->db->where('id',$id);
            $this->db->delete('menu');
        }
        private function setMainSelect(){
            $this->db->select('
                            menu_types.title as typeName,
                            menu_types.id as typeId,
                            menu.id,
                            menu.title,
                            menu.link,
                            menu.link_default,
                            menu.id_type,
                            menu.createTime
                    ');
        }
    }