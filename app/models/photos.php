<?php
class Photos extends CI_Model{

    public function getPhotosByID($UserID){
        $this->db->where('UserID',$UserID);
        $query = $this->db->get('photoalbum');
        return $query->result();
    }
    public function getAvatarByID($UserID){
        $this->db->where('main',1);
        $this->db->where('UserID',$UserID);
        $query = $this->db->get('photoalbum');
        if ($this->db->affected_rows() > 0 ){
            return $query->row();
        }else{
            return false;
        }
    }
    /**
     * saving images
     * @param $Data
     * @return mixed
     */
    public function saveImage($Data){
        $this->db->insert('photoalbum',$Data);
        $lastPhoto = $this->db->insert_id();
        return $lastPhoto;
    }
    /**
     * deleting single Photo by id and UserID
     * @param  $PhotoID
     * @param  $UserID
     * @return void
     */
    public function deleteSinglePhoto($PhotoID,$UserID){
        $this->db->where('id',$PhotoID);
        $this->db->where('UserID',$UserID);
        $this->db->delete('photoalbum');
    }
    /**
     * setting single photo attributes name
     * @param  $PhotoID
     * @param  $updateData
     * @param  $UserID
     * @return void
     */
    public function editSinglePhoto($updateData,$UserID,$PhotoID){
        $this->db->where('id',$PhotoID);
        $this->db->where('UserID',$UserID);
        $this->db->update('photoalbum', $updateData);
    }
    /**
     * deleting avatar indication
     * @param $UserID
     */
    public function deleteMainPhoto($UserID){
        $updateData = array(
            'main' => 0
        );
        $this->db->where('UserID',$UserID);
        $this->db->update('photoalbum', $updateData);
    }

    /**
     * get avatar by id
     * @param $userID
     * @return bool
     */
    public function getAvatar($userID){
        $this->db->where('UserID',$userID);
        $this->db->where('main',1);
        $query = $this->db->get('photoalbum');
        if ($this->db->affected_rows() > 0 ) {
            return $query->row()->PhotoLink;
        }else
        {
            return false;
        }
    }

    public function uploadPhoto($template){
        require_once('Zebra_Image.php');

        $config['upload_path'] = "./images_content/{$template}/big";
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']  = 1024 * 8;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        $photoId = false;
        $result['status'] = false;
        if (!$this->upload->do_upload()){
            $msg = $this->upload->display_errors('', '');
            //echo $msg;

        }
        else {
            $data = $this->upload->data();
            if ($data['image_width']>240 || $data['image_width']>240){
                // resizing photo to small one
                $configResized['image_library']     = 'gd2';
                $configResized['source_image']      = "./images_content/{$template}/big/{$data['file_name']}";
                $configResized['new_image']         = "./images_content/{$template}/small/{$data['file_name']}";
                $configResized['maintain_ratio']    = TRUE;
                $configResized['width']             = 150;
                $configResized['height']            = 150;
                $configResized['master_dim']        = 'auto';
                /*
                $this->load->library('image_lib',$configResized);
                $this->image_lib->resize();
                */
                $img = new Zebra_Image();
                $img->source_path = $configResized['source_image'];
                $img->target_path = $configResized['new_image'];
                $img->jpeg_quality = 100;
                $img->preserve_aspect_ratio = true;
                $img->resize(234, 300,ZEBRA_IMAGE_NOT_BOXED );

                $img->target_path = $img->source_path;
                if ($data['image_width']>500){
                    $img->resize(500, 0,ZEBRA_IMAGE_NOT_BOXED );
                }else{
                    $img->resize($data['image_width'], $data['image_height'],ZEBRA_IMAGE_NOT_BOXED );
                }
                $img->target_path = "./images_content/{$template}/square/{$data['file_name']}";
                $img->resize(240, 240,ZEBRA_IMAGE_CROP_CENTER );
                //$this->load->model('Photos');
                $insertData = array(
                                    'PhotoLink' => $data['file_name']
                                    );
                $result['insertData']   = $insertData;
                $result['status']       = true;
            }
        }
        unset($_POST['userfile']);
        return $result;

    }

}