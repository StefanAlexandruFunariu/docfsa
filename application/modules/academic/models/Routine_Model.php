<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Routine_Model extends MY_Model {
    
    function __construct() {
        parent::__construct();
    }
    
     public function get_section_list($class_id = null ){
        
        if($this->session->userdata('role_id') == STUDENT){
           $class_id = $this->session->userdata('class_id');
        }
        if(!$class_id){ return; }
         
        $this->db->select('S.*, C.name AS class_name');
        $this->db->from('sections AS S');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->where('S.class_id', $class_id);
        
        return $this->db->get()->result();
        
    }
    
    public function get_single_routine($id){
        
        $this->db->select('S.*, C.name AS class_name, T.name AS teacher');
        $this->db->from('subjects AS S');
        $this->db->join('teachers AS T', 'T.id = S.teacher_id', 'left');
        $this->db->join('classes AS C', 'C.id = S.class_id', 'left');
        $this->db->where('S.id', $id);
        return $this->db->get()->row();
        
    }
    
    function duplicate_routine($condition, $id = null){   
            if($id){
                $this->db->where_not_in('id', $id);    
            }
            $this->db->where($condition);
            return $this->db->get('routines')->num_rows();            
    }
        
}
