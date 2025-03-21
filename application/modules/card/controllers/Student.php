<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Student.php**********************************
 
 * @type            : Class
 * @class name      : Generate
 * @description     : Manage all type of system student listing.  
 	
 * ********************************************************** */

class Student extends MY_Controller {

    public $data = array();
      
   public function __construct() {
        parent::__construct();
                
        $this->load->model('Student_Model', 'student', true);                
    }

  

   

    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load user filtering interface                 
     *                      
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index(){
        
        
        check_permission(VIEW);
        
        $this->data['students'] = '';
        
         if ($_POST) {
             
            $class_id = $this->input->post('class_id'); 
            $section_id = $this->input->post('section_id'); 
            $student_id = $this->input->post('student_id');
            $this->data['cards'] = $this->student->get_student_list($class_id, $section_id, $student_id);
            $this->data['setting'] = $this->student->get_single('id_card_settings', array('status'=>1));
            $this->data['class_id'] = $class_id;
            $this->data['section_id'] = $section_id;
            $this->data['student_id'] = $student_id;
            
         }
                 
        $this->data['classes'] = $this->student->get_list('classes', array('status'=>1), '','', '', 'id', 'ASC');    
        
        $this->layout->title($this->lang->line('generate') .' ' . $this->lang->line('student').' ' . $this->lang->line('id') .' ' . $this->lang->line('card') .' | ' . SMS);
        $this->layout->view('student/index', $this->data);         
    } 
}
