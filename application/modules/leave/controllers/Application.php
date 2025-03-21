<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Application.php**********************************
 
 * @type            : Class
 * @class name      : Application
 * @description     : Manage application.  
 	
 * ********************************************************** */

class Application extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Application_Model', 'application', true);  
        if(!$this->academic_year_id){
            error($this->lang->line('academic_year_setting'));
            redirect('setting');
        }
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Application List" user interface                 
    *                    listing    
    * @param           : integer value
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);
                         
        $this->data['applications'] = $this->application->get_application_list();               
        $this->data['classes'] = $this->application->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
                
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').  ' ' .  $this->lang->line('leave') .' '.  $this->lang->line('application') .' | ' . SMS);
        $this->layout->view('application/index', $this->data);
        
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Application" user interface                 
    *                    and process to store "Application" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_application_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_application_data();

                $insert_id = $this->application->insert('leave_applications', $data);
                if ($insert_id) {
                    
                    create_log('Has been added leave application');                     
                    success($this->lang->line('insert_success'));
                    redirect('leave/application/index');
                    
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('leave/application/add');
                }
            } else {
                $this->data['post'] = $_POST;                
            }
        }
        
               
        $this->data['classes'] = $this->application->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
             
        $this->data['applications'] = $this->application->get_application_list(); 
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['add'] = TRUE;
        
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('leave') . ' ' . $this->lang->line('application'). ' | ' . SMS);
        $this->layout->view('application/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Application" user interface                 
    *                    with populated "Application" value 
    *                    and process to update "Application" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('leave/application/index');
        }
       
        if ($_POST) {
            $this->_prepare_application_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_application_data();
                $updated = $this->application->update('leave_applications', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a leave application');                    
                    success($this->lang->line('update_success'));
                    redirect('leave/application/index');
                    
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('leave/application/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['application'] = $this->application->get_single_application($this->input->post('id'));
            }
        }

        if ($id) {
            
            $this->data['application'] = $this->application->get_single_application($id);
            if (!$this->data['application']) {
                redirect('application/index');
            }
        }

              
        $this->data['classes'] = $this->application->get_list('classes', array('status' => 1), '','', '', 'id', 'ASC');
        $this->data['applications'] = $this->application->get_application_list();      
        $this->data['roles'] = $this->application->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
               
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('leave') . ' '. $this->lang->line('application') . ' | ' . SMS);
        $this->layout->view('application/index', $this->data);
    }

       
           
     /*****************Function get_single_application**********************************
     * @type            : Function
     * @function name   : get_single_application
     * @description     : "Load single application information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_application(){
        
       $application_id = $this->input->post('application_id');
       
       $this->data['application'] = $this->application->get_single_application($application_id);
       echo $this->load->view('get-single-application', $this->data);
    }

    
    /*****************Function _prepare_application_validation**********************************
    * @type            : Function
    * @function name   : _prepare_application_validation
    * @description     : Process "application" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_application_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        
        if($this->input->post('role_id') == STUDENT){
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        }
        
        $this->form_validation->set_rules('user_id', $this->lang->line('user'), 'trim|required');
        $this->form_validation->set_rules('type_id', $this->lang->line('type').' '.$this->lang->line('type'), 'trim|required');
        $this->form_validation->set_rules('leave_from', $this->lang->line('leave').' '.$this->lang->line('from'), 'trim|required');
        $this->form_validation->set_rules('leave_to', $this->lang->line('leave').' '.$this->lang->line('to'), 'trim|required|callback_leave_to');
        $this->form_validation->set_rules('leave_reason', $this->lang->line('leave').' '.$this->lang->line('reason'), 'trim');
        $this->form_validation->set_rules('attachment', $this->lang->line('leave').' '.$this->lang->line('attachment'), 'trim|callback_attachment');
        
    }
    
    
                        
    /*****************Function leave_to**********************************
    * @Type            : Function
    * @function name   : leave_to
    * @description     : date schedule check data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function leave_to() {
        
        $leave_from = date('Y-m-d', strtotime($this->input->post('leave_from')));
        $leave_to   = date('Y-m-d', strtotime($this->input->post('leave_to')));
            
        if ($leave_from > $leave_to ) {
            $this->form_validation->set_message('leave_to', $this->lang->line('to_date_must_be_big'));
            return FALSE;
        } else {
            return TRUE;
        }        
    }

    /*****************Function attachment**********************************
    * @type            : Function
    * @function name   : attachment
    * @description     : Process/check attachment document validation                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function attachment() {

        if ($this->input->post('id')) {

            if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {
                $name = $_FILES['attachment']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('attachment', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        } else {

            if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {                
           
                $name = $_FILES['attachment']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'pdf' || $ext == 'doc' || $ext == 'docx' || $ext == 'txt' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('attachment', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        }
    }

    
    
    /*****************Function _get_posted_application_data**********************************
    * @type            : Function
    * @function name   : _get_posted_application_data
    * @description     : Prepare "Application" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_application_data() {

        $items = array();
        $items[] = 'role_id';
        $items[] = 'user_id';
        $items[] = 'class_id';
        $items[] = 'type_id';
        $items[] = 'leave_reason';

        $data = elements($items, $_POST);
        
        $data['leave_date'] = date('Y-m-d', strtotime($this->input->post('leave_date')));
        $data['leave_from'] = date('Y-m-d', strtotime($this->input->post('leave_from')));
        $data['leave_to']   = date('Y-m-d', strtotime($this->input->post('leave_to')));
        
        $start = strtotime($data['leave_from']);
        $end   = strtotime($data['leave_to']);
        $days = ceil(abs($end - $start) / 86400);
        $data['leave_day'] = $days+1;
        
        if ($this->input->post('id')) {
            
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();            
            
        } else {
            
            $data['leave_status'] = 0;
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();                      
            $data['academic_year_id'] = $this->academic_year_id;
            
        }
        

        if (isset($_FILES['attachment']['name'])) {
            $data['attachment'] = $this->_upload_attachment();
        }

        return $data;
    }

        
    /*****************Function _upload_attachment**********************************
    * @type            : Function
    * @function name   : _upload_attachment
    * @description     : Process to to upload attachment in the server
    *                    and return image name                   
    *                       
    * @param           : null
    * @return          : $return_image string value 
    * ********************************************************** */
    private function _upload_attachment() {

        $prev_attachment = $this->input->post('prev_attachment');
        $attachment = $_FILES['attachment']['name'];
        $return_attachment = '';
        if ($attachment != "") {

                $destination = 'assets/uploads/leave/';

                $file_type = explode(".", $attachment);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $attachment_path = 'leave-attachment-' . time() . '-sms.' . $extension;

                move_uploaded_file($_FILES['attachment']['tmp_name'], $destination . $attachment_path);
                // need to unlink previous image
                if ($prev_attachment != "") {
                    if (file_exists($destination . $prev_attachment)) {
                        @unlink($destination . $prev_attachment);
                    }
                }

                $return_attachment = $attachment_path;
          
        } else {
            $return_attachment = $prev_attachment;
        }

        return $return_attachment;
    }

     
    
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Application" from database                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    
    public function delete($id = null) {

        check_permission(VIEW);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('leave/application/index');
        }
        
        $application = $this->application->get_single_application($id);
        
        if ($this->application->delete('leave_applications', array('id' => $id))) {
            
             // delete teacher resume and image
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/leave/' . $application->attachment)) {
                @unlink($destination . '/leave/' . $application->attachment);
            }            
            
            create_log('Has been deleted a leave application : '.$application->type);
            success($this->lang->line('delete_success'));
            redirect('leave/application/index');
            
        } else {
            error($this->lang->line('delete_failed'));
        }
        
        redirect('leave/application/index/');
    }
    
        
    /*****************Function waiting**********************************
     * @type            : Function
     * @function name   : waiting
     * @description     : "update leave status" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function waiting($application_id){
        if(!is_numeric($application_id)){
            error($this->lang->line('unexpected_error'));
            redirect('leave/application/index');     
        }
        
        $leave = $this->application->get_single('leave_applications', array('id'=>$application_id));               
        $status = $this->application->update('leave_applications', array('leave_status'=>1, 'modified_at'=>date('Y-m-d H:i:s') ), array('id'=>$application_id));               
        
        if($status){
            success($this->lang->line('update_success'));
            redirect('leave/application/index');  
        }else{
            error($this->lang->line('update_failed'));
            redirect('leave/application/index');      
        }
    }

}
