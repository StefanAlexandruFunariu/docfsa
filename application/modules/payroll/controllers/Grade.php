<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Grade.php**********************************
 
 * @type            : Class
 * @class name      : Grade
 * @description     : Manage all Salary Grades as per payroll.  
 	
 * ********************************************************** */

class Grade extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Grade_Model', 'grade', true);            
    }

    
        
    /*****************Function index**********************************
     * @type            : Function
     * @function name   : index
     * @description     : Load "Salary Grades Listing" user interface                 
     *                        
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);
        $this->data['grades'] = $this->grade->get_list('salary_grades', array('status'=> 1));     
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_salary_grade'). ' | ' . SMS);
        $this->layout->view('grade/index', $this->data);            
       
    }

    
    /*****************Function add**********************************
     * @type            : Function
     * @function name   : add
     * @description     : Load "Salary Grade" user interface                 
     *                    and store "Salary Grade" into database 
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_grade_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_grade_data();

                $insert_id = $this->grade->insert('salary_grades', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a Salary Grade : '.$data['grade_name']);
                    
                    success($this->lang->line('insert_success'));
                    redirect('payroll/grade/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('payroll/grade/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['grades'] = $this->grade->get_list('salary_grades', array('status'=> 1));     
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('grade/index', $this->data);
    }

    
     /*****************Function edit**********************************
     * @type            : Function
     * @function name   : edit
     * @description     : Load Update "Salary Grade" user interface                 
     *                    with populated "Salary Grade" value 
     *                    and update "Salary Grade" database    
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function edit($id = null) {       
       
        check_permission(EDIT);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/grade/index');
        }
                
        if ($_POST) {
            $this->_prepare_grade_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_grade_data();
                $updated = $this->grade->update('salary_grades', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a Salary Grade : '.$data['grade_name']);
                    
                    success($this->lang->line('update_success'));
                    redirect('payroll/grade/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('payroll/grade/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['grade'] = $this->grade->get_single('salary_grades', array('id' => $this->input->post('id')));
            }
        }
        
        if ($id) {
            $this->data['grade'] = $this->grade->get_single('salary_grades', array('id' => $id));

            if (!$this->data['grade']) {
                 redirect('payroll/grade/index');
            }
        }

        $this->data['grades'] = $this->grade->get_list('salary_grades', array('status'=> 1));     
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('grade/index', $this->data);
    }

    
    /*****************Function view**********************************
     * @type            : Function
     * @function name   : view
     * @description     : Load user interface with specific Salary Grade data                 
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function view($id = null){
        
        check_permission(VIEW);        
         
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/grade/index');
        }
        
        $this->data['grade'] = $this->grade->get_single('salary_grades', array('id' => $id));   
        $this->data['grades'] = $this->grade->get_list('salary_grades', array('status'=> 1)); 
        $this->data['detail'] = TRUE;       
        $this->layout->title($this->lang->line('view'). ' ' . $this->lang->line('salary_grade'). ' | ' . SMS);
        $this->layout->view('grade/index', $this->data); 
    }
    
        
           
     /*****************Function get_single_grade**********************************
     * @type            : Function
     * @function name   : get_single_grade
     * @description     : "Load single grade information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_grade(){
        
       $grade_id = $this->input->post('grade_id');
       
       $this->data['grade'] = $this->grade->get_single('salary_grades', array('id' => $grade_id));
       echo $this->load->view('grade/get-single-grade', $this->data);
    }

    
     /*****************Function _prepare_grade_validation**********************************
     * @type            : Function
     * @function name   : _prepare_grade_validation
     * @description     : Process "Salary Grade" user input data validation                 
     *                       
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    private function _prepare_grade_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('grade_name', $this->lang->line('grade_name'), 'trim|required|callback_grade_name');   
        $this->form_validation->set_rules('basic_salary', $this->lang->line('basic_salary'), 'trim|required');   
        $this->form_validation->set_rules('house_rent', $this->lang->line('house_rent'), 'trim');   
        $this->form_validation->set_rules('transport', $this->lang->line('transport'), 'trim');   
        $this->form_validation->set_rules('medical', $this->lang->line('medical'), 'trim');   
        $this->form_validation->set_rules('over_time_hourly_rate', $this->lang->line('over_time_hourly_rate'), 'trim');   
        $this->form_validation->set_rules('provident_fund', $this->lang->line('provident_fund'), 'trim');   
        $this->form_validation->set_rules('hourly_rate', $this->lang->line('hourly_rate'), 'trim|required');   
        $this->form_validation->set_rules('total_allowance', $this->lang->line('total'). ' '. $this->lang->line('allowance'), 'trim');   
        $this->form_validation->set_rules('total_deduction', $this->lang->line('total') .' '. $this->lang->line('deduction'), 'trim');   
        $this->form_validation->set_rules('gross_salary', $this->lang->line('gross_salary'), 'trim|required');   
        $this->form_validation->set_rules('net_salary', $this->lang->line('net_salary'), 'trim|required');   
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');   
    }
    
    
     /*****************Function grade_name**********************************
     * @type            : Function
     * @function name   : grade_name
     * @description     : Unique check for "Grade Name" data/value                  
     *                       
     * @param           : null
     * @return          : boolean true/false 
     * ********************************************************** */  
   public function grade_name(){       
       
      if($this->input->post('id') == '')
      {   
          $grade = $this->grade->duplicate_check('grade_name',$this->input->post('grade_name')); 
          if($grade){
                $this->form_validation->set_message('grade_name',  $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }          
      }else if($this->input->post('id') != ''){   
         $grade = $this->grade->duplicate_check('grade_name',$this->input->post('grade_name'), $this->input->post('id')); 
          if($grade){
                $this->form_validation->set_message('grade_name', $this->lang->line('already_exist'));         
                return FALSE;
          } else {
              return TRUE;
          }
      }   
   }

   
    /*****************Function _get_posted_grade_data**********************************
     * @type            : Function
     * @function name   : _get_posted_grade_data
     * @description     : Prepare "Grade Name" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_grade_data() {

        $items = array();
        $items[] = 'grade_name';
        $items[] = 'basic_salary';
        $items[] = 'house_rent';
        $items[] = 'transport';
        $items[] = 'medical';
        $items[] = 'over_time_hourly_rate';
        $items[] = 'provident_fund';
        $items[] = 'hourly_rate';
        $items[] = 'total_allowance';
        $items[] = 'total_deduction';
        $items[] = 'gross_salary';
        $items[] = 'net_salary';
        $items[] = 'note';
        
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

        return $data;
    }

    
    /*****************Function delete**********************************
     * @type            : Function
     * @function name   : delete
     * @description     : delete "Salry Grade" from database                  
     *                       
     * @param           : $id integer value
     * @return          : null 
     * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
        
        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('payroll/grade/index');
        }
        
        $grade = $this->grade->get_single('salary_grades', array('id' => $id));
        
        if ($this->grade->delete('salary_grades', array('id' => $id))) { 
            
            create_log('Has been deleted a Salary Grade : '. $grade->grade_name);            
            success($this->lang->line('delete_success'));
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('payroll/grade/index');
    }

}
