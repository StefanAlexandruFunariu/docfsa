<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Routine.php**********************************
 
 * @type            : Class
 * @class name      : Routine
 * @description     : Manage academic class routine time schedule.  
 	
 * ********************************************************** */

class Routine extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();    
        $this->load->model('Routine_Model', 'routine', true);
        
        // check running session
        if(!$this->academic_year_id){
            error($this->lang->line('academic_year_setting'));
            redirect('setting');
        }       
        
    }

    
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Class routine" user interface                 
     *                    with section wise routine    
     * @param           : $class_id integer value
     * @return          : null 
     * ********************************************************** */
    public function index($class_id = null) {
        
        check_permission(VIEW);
        
        if(isset($class_id) && !is_numeric($class_id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/routine/index');
        }
        
        $this->data['class_id'] = $class_id;
        $this->data['sections'] = $this->routine->get_section_list($class_id);        
        $this->data['classes'] = $this->routine->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['teachers'] = $this->routine->get_list('teachers', array('status' => 1), '','', '', 'id', 'ASC');
        
         $this->data['single_class'] = $this->routine->get_single('classes', array('id' => $class_id));
         
         //$this->data['academic_year_id'] = $this->academic_year_id;
         $this->data['academic_year_id'] = '';
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_routine'). ' | ' . SMS);
        if($this->session->userdata('role_id') == TEACHER){ 
            $this->layout->view('routine/teacher', $this->data); 
        }else{
            $this->layout->view('routine/index', $this->data);             
        }            
    }

    
     /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Add new Class Routine" user interface                 
     *                    and store "Class Routine" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_routine_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_routine_data();

                $insert_id = $this->routine->insert('routines', $data);
                if ($insert_id) {
                    
                    $class = $this->routine->get_single('classes', array('id' => $data['class_id']));
                    create_log('Has been created a routine for class : '. $class->name);
                    
                    success($this->lang->line('insert_success'));
                    redirect('academic/routine/index/'.$data['class_id']);
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('academic/routine/add/'.$data['class_id']);
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }
        
        $class_id = $this->uri->segment(4);
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
        $this->data['class_id'] = $class_id;
        $this->data['sections'] = $this->routine->get_section_list($class_id);        
        $this->data['classes'] = $this->routine->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['teachers'] = $this->routine->get_list('teachers', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['single_class'] = $this->routine->get_single('classes', array('id' => $class_id));
         
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('routine'). ' | ' . SMS);
        if($this->session->userdata('role_id') == TEACHER){ 
            $this->layout->view('routine/teacher', $this->data); 
        }else{
            $this->layout->view('routine/index', $this->data);             
        } 
    }

     /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Class Routine" user interface                 
     *                    with populated "class routine" value 
     *                    and update "Class routine" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/routine/index');     
        }
        
        if ($_POST) {
            $this->_prepare_routine_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_routine_data();
                $updated = $this->routine->update('routines', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    $class = $this->routine->get_single('classes', array('id' => $data['class_id']));
                    create_log('Has been updated a routine for class : '. $class->name);
                    
                    success($this->lang->line('update_success'));
                    redirect('academic/routine/index/'.$data['class_id']);                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('academic/routine/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['routine'] = $this->routine->get_single('routines', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['routine'] = $this->routine->get_single('routines', array('id' => $id));

            if (!$this->data['routine']) {
                 redirect('academic/routine/index');
            }
        }
        
        $class_id = $this->data['routine']->class_id;
        if(!$class_id){
          $class_id = $this->input->post('class_id');
        }
      
        $this->data['class_id'] = $class_id;
        $this->data['sections'] = $this->routine->get_section_list($class_id);        
        $this->data['classes'] = $this->routine->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['teachers'] = $this->routine->get_list('teachers', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['single_class'] = $this->routine->get_single('classes', array('id' => $class_id));
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('routine'). ' | ' . SMS);
        if($this->session->userdata('role_id') == TEACHER){ 
            $this->layout->view('routine/teacher', $this->data); 
        }else{
            $this->layout->view('routine/index', $this->data);             
        } 
    }

    
    /*****************Function _prepare_routine_validation**********************************
     * @type            : Function
     * @function name   : _prepare_routine_validation
     * @description     : Process "Class Routine" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_routine_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');   
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');   
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|callback_subject_id');   
        $this->form_validation->set_rules('teacher_id', $this->lang->line('teacher'), 'trim|required|callback_teacher_id');   
        $this->form_validation->set_rules('day', $this->lang->line('day'), 'trim|required');   
        $this->form_validation->set_rules('start_time', $this->lang->line('start_time'), 'trim|required');   
        $this->form_validation->set_rules('end_time', $this->lang->line('end_time'), 'trim|required');   
        $this->form_validation->set_rules('room_no', $this->lang->line('room_no'), 'trim|required|callback_room_no');
    }
    
    
    /*****************Function subject_id**********************************
     * @type            : Function
     * @function name   : subject_id
     * @description     : Unique check for "Subject" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */ 
   public function subject_id()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'section_id'=> $this->input->post('section_id'),
              'subject_id'=> $this->input->post('subject_id'),
              'day'=> $this->input->post('day')
          );
          $routine = $this->routine->duplicate_routine($condition);          
          if($routine){
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'section_id'=> $this->input->post('section_id'),
              'subject_id'=> $this->input->post('subject_id'),
              'day'=> $this->input->post('day')
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('subject_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }
   
   
     /*****************Function teacher_id**********************************
     * @type            : Function
     * @function name   : teacher_id
     * @description     : Unique check for "teacher" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */ 
   public function teacher_id()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'teacher_id'=> $this->input->post('teacher_id'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time'),
          );
          $routine = $this->routine->duplicate_routine($condition); 
          if($routine){
                $this->form_validation->set_message('teacher_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'teacher_id'=> $this->input->post('teacher_id'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time')             
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('teacher_id', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }
   
   
    /*****************Function room_no**********************************
     * @type            : Function
     * @function name   : room_no
     * @description     : Unique check for "room_no" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */ 
   public function room_no()
   {             
      if($this->input->post('id') == '')
      {   
          $condition = array(
              'room_no'=> $this->input->post('room_no'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time'),
          );
          $routine = $this->routine->duplicate_routine($condition); 
          if($routine){
                $this->form_validation->set_message('room_no', $this->lang->line('this_room_already_allocated'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){  
          
          $condition = array(
              'room_no'=> $this->input->post('room_no'),
              'day'=> $this->input->post('day'),
              'start_time'=> $this->input->post('start_time')            
          );
          $routine = $this->routine->duplicate_routine($condition, $this->input->post('id'));
          
          if($routine){
                $this->form_validation->set_message('room_no', $this->lang->line('this_room_already_allocated'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }     
   }
   
   
     /*****************Function _get_posted_routine_data**********************************
     * @type            : Function
     * @function name   : _get_posted_routine_data
     * @description     : Prepare "Class Routine" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */ 
    private function _get_posted_routine_data() {

        $items = array();
        $items[] = 'class_id';
        $items[] = 'section_id';
        $items[] = 'subject_id';
        $items[] = 'teacher_id';
        $items[] = 'day';
        $items[] = 'start_time';
        $items[] = 'end_time';
        $items[] = 'room_no';
        
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
            
        } else {
            
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id(); 
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        }

        $data['academic_year_id'] = $this->academic_year_id;
        
        return $data;
    }

    
     /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Class Routine" from database                  
     *                       
     * @param           : $id, $class_id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('academic/routine/index');  
        }
        
        $routine = $this->routine->get_single('routines', array('id' => $id));
        
        if ($this->routine->delete('routines', array('id' => $id))) {   
            
            $class = $this->routine->get_single('classes', array('id' => $routine->class_id));
            create_log('Has been delete a routine for class : '. $class->name);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('academic/routine/index/'.$routine->class_id);
    }
    
    
   

}
