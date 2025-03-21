<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Type.php**********************************
 
 * @Type            : Class
 * @class name      : Type
 * @description     : Manage student type.  
 	
 * ********************************************************** */

class Type extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Type_Model', 'type', true);        
    }

    
    /*****************Function index**********************************
    * @Type            : Function
    * @function name   : index
    * @description     : Load "Type List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);        
        $this->data['types'] = $this->type->get_type();        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').' '.$this->lang->line('student').' ' . $this->lang->line('type').' | ' . SMS);
        $this->layout->view('type/index', $this->data);  
    }

    
    /*****************Function add**********************************
    * @Type            : Function
    * @function name   : add
    * @description     : Load "Add new student Type" user interface                 
    *                    and process to store "student Type" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

         check_permission(ADD);
        if ($_POST) {
            $this->_prepare_type_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_type_data();

                $insert_id = $this->type->insert('student_types', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a student Type : '.$data['type']);                    
                    success($this->lang->line('insert_success'));
                    redirect('student/type/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('student/type/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['types'] = $this->type->get_type(); 
               
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('student') .' '. $this->lang->line('type'). ' | ' . SMS);
        $this->layout->view('type/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @Type            : Function
    * @function name   : edit
    * @description     : Load Update "student Type" user interface                 
    *                    with populate "student Type" value 
    *                    and process to update "student Type" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {       

         check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_type_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_type_data();
                $updated = $this->type->update('student_types', $data, array('id' => $this->input->post('id')));

                if ($updated) {                    
                    create_log('Has been updated a student Type : '.$data['type']);                    
                    success($this->lang->line('update_success'));
                    redirect('student/type/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('student/type/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['type'] = $this->type->get_single('student_types', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['type'] = $this->type->get_single('student_types', array('id' => $id));

                if (!$this->data['type']) {
                     redirect('student/type/index');
                }
            }
        }
       
        $this->data['types'] = $this->type->get_type();       
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit'). ' ' .$this->lang->line('student') .' '. $this->lang->line('type'). ' | ' . SMS);
        $this->layout->view('type/index', $this->data);
    }

        
    /*****************Function _prepare_type_validation**********************************
    * @Type            : Function
    * @function name   : _prepare_type_validation
    * @description     : Process "student Type" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_type_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('type', $this->lang->line('student').' '.$this->lang->line('type'), 'trim|required|callback_type');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

                    
    /*****************Function type**********************************
    * @Type            : Function
    * @function name   : type
    * @description     : Unique check for "student Type " data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function type() {
        if ($this->input->post('id') == '') {
            $type = $this->type->duplicate_check($this->input->post('type'));
            if ($type) {
                $this->form_validation->set_message('type', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $type = $this->type->duplicate_check($this->input->post('type'), $this->input->post('id'));
            if ($type) {
                $this->form_validation->set_message('type', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
       
    /*****************Function _get_posted_type_data**********************************
    * @Type            : Function
    * @function name   : _get_posted_type_data
    * @description     : Prepare "Type" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_type_data() {

        $items = array();
        $items[] = 'type';
        $items[] = 'note';
        $data = elements($items, $_POST);        
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
        }
        return $data;
    }    
        
    
    /*****************Function delete**********************************
    * @Type            : Function
    * @function name   : delete
    * @description     : delete "student Type" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
         
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('student/type/index');        
        }
        
        $type = $this->type->get_single('student_types', array('id' => $id));        
        if ($this->type->delete('student_types', array('id' => $id))) { 
            
            create_log('Has been deleted a student Type : '.$type->type);
            success($this->lang->line('delete_success'));
            redirect('student/type/index');
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('student/type/index');
    }
}
