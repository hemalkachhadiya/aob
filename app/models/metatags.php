<?php
/**
 * class for managing static
 * meta tags through pommelo structure
 *
 * !IMPORTANT all templates are in lower case
 */
class MetaTags extends CI_Model{
    /**
     * setting default meta tag object for dynamic pages
     * @param  $title
     * @param  $description
     * @param  $keywords
     * @return void
     */
    public function setMetaTags($title,$description='',$keywords=''){
        $obj = new stdClass();
        $obj->title = $title;
        $obj->description = $description;
        $obj->keywords = $keywords;
        return $obj;
    }
    /**
     * functions for using in structure
     * @param  $temlate
     * @return void
     */
    public function getMetaTagsByTemplate($template){
        $this->db->where("template like '%{$template}%'");
        $query = $this->db->get('meta_tags');
        if ($this->db->affected_rows()>0){
            return $query->row();
        }else{
            return false;
        }
    }
    public function getMetaTagsById($id){
        $this->db->where('id',$id);
        $query = $this->db->get('meta_tags');
        if ($this->db->affected_rows()>0){
            return $query->row();
        }else{
            return false;
        }
    }
    /**
     * functions for using in admin-panel
     * @param  $temlate
     * @return void
     */
    public function getMetaTagAmount($directory='',$type='human',$search=''){
        if (!empty($search))
        {
            $this->db->where($this->generateSearch($search,$type));
        }
        if (!empty($directory))
        {
            $this->db->where('directory',$directory);
        }

        $this->db->from('meta_tags');
        $this->db->join('meta_tags_directory','meta_tags.directory = meta_tags.id','left');
        $query = $this->db->get();
        return $this->db->affected_rows();
    }
    public function getMetaTagList($page = 1,$directory='',$type='human',$search=''){
        $postLimit = ($page-1)*SEARCH_RESULTS;
        $preLimit = SEARCH_RESULTS;
        if (!empty($search))
        {
            $this->db->where($this->generateSearch($search,$type));
        }
        if (!empty($directory))
        {
            $this->db->where('directory',$directory);
        }

        $this->db->from('meta_tags');
        $this->db->join('meta_tags_directory','meta_tags.directory = meta_tags.id','left');
        $this->db->limit($preLimit,$postLimit );
        $query = $this->db->get();

        return $query->result();
    }
    /**
     * helper function for search with all priviliges or only bu human-like names
     * @param  $search
     * @param  $type
     * @return string
     */
    protected function generateSearch($search,$type){
        switch($type){
            case "human":
                $search = "(template like '%{$search}%' or
                            title like '%{$search}%' or
                            description like '%{$search}%' or
                	        keywords like '%{$search}%' or
                	        admin_title like '%{$search}%' or
                	        admin_description like '%{$search}%'or
                	        directory like '%{$search}%') ";
                break;
            case "all":
                $search = "(admin_title	 like '%{$search}%' or
                            admin_description like '%{$search}%') ";
                break;

        }
        return $search;
    }
    /**
     * list of meta tahs global directories like account or info
     * @return
     */
    public function getMetaTagsDirectory(){
        $query = $this->db->get('meta_tags_directory');
        return $query->result();
    }
}