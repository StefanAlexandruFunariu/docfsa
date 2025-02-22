<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Employee.php**********************************
 
 * @type            : Class
 * @class name      : Generate
 * @description     : Manage all type of system employee listing.  
 	
 * ********************************************************** */

class Employee extends MY_Controller {

    public $data = array();
      
   public function __construct() {
        parent::__construct();
                
        $this->load->model('Employee_Model', 'employee', true);                
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
        
        $this->data['employees'] = '';
        
         if ($_POST) {             
           
            $employee_id = $this->input->post('employee_id');
            $this->data['cards'] = $this->employee->get_employee_list($employee_id);
            $this->data['setting'] = $this->employee->get_single('id_card_settings', array('status'=>1));
            $this->data['employee_id'] = $employee_id;
         }
                  
        $this->data['employees'] = $this->employee->get_list('employees', array('status'=>1), '','', '', 'id', 'ASC');
       
        
        $this->layout->title($this->lang->line('generate') .' ' . $this->lang->line('employee').' ' . $this->lang->line('id') .' ' . $this->lang->line('card') .' | ' . SMS);
        $this->layout->view('employee/index', $this->data);         
    }    

}
