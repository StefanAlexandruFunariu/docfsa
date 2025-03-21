<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Feedback.php**********************************
 * @type            : Class
 * @class name      : Feedback
 * @description     : Manage school Guardian Feedback.  
 	
 * ********************************************************** */

class Feedback extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Feedback_Model', 'feedback', true);        
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Notice List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);
        
        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').' '.$this->lang->line('feedback') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new feedback" user interface                 
    *                    and process to store "feedback" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_feedback_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feedback_data();

                $insert_id = $this->feedback->insert('guardian_feedbacks', $data);
                if ($insert_id) {
                    
                    create_log('Has been add feedback');
                    success($this->lang->line('insert_success'));
                    redirect('administrator/feedback/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/feedback/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('feedback') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "feedback" user interface                 
    *                    with populated "feedback" value 
    *                    and process update "feedback" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('administrator/feedback/index');
        }
        
        if ($_POST) {
            $this->_prepare_feedback_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_feedback_data();
                $updated = $this->feedback->update('guardian_feedbacks', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been update feedback');
                    
                    success($this->lang->line('update_success'));
                    redirect('administrator/feedback/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('administrator/feedback/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['feedback'] = $this->feedback->get_single('guardian_feedbacks', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['feedback'] = $this->feedback->get_single('guardian_feedbacks', array('id' => $id));

            if (!$this->data['feedback']) {
                redirect('administrator/feedback/index');
            }
        }

        $this->data['feedbacks'] = $this->feedback->get_feedback_list();
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('feedback') . ' | ' . SMS);
        $this->layout->view('feedback/index', $this->data);
    }
        
           
     /*****************Function get_single_feedback**********************************
     * @type            : Function
     * @function name   : get_single_feedback
     * @description     : "Load single feedback information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_feedback(){
        
       $feedback_id = $this->input->post('feedback_id');
       
       $this->data['feedback'] = $this->feedback->get_single_feedback($feedback_id);
       echo $this->load->view('feedback/get-single-feedback', $this->data);
    }

        
    /*****************Function _prepare_feedback_validation**********************************
    * @type            : Function
    * @function name   : _prepare_feedback_validation
    * @description     : Process "Notice" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_feedback_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('feedback', $this->lang->line('feedback'), 'trim|required');
    }
    
    /*****************Function _get_posted_feedback_data**********************************
    * @type            : Function
    * @function name   : _get_posted_feedback_data
    * @description     : Prepare "Notice" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_feedback_data() {

        $items = array();
        $items[] = 'feedback';
        $data = elements($items, $_POST);


        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['guardian_id'] = $this->session->userdata('profile_id');
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
    * @description     : delete "feedback" from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(VIEW);

        if(!is_numeric($id)){
            error($this->lang->line('unexpected_error'));
            redirect('administrator/feedback/index');
        }
        
        if ($this->feedback->delete('guardian_feedbacks', array('id' => $id))) {
            
            create_log('Has been deleted feedback');
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
       redirect('administrator/feedback/index');
    }
    
    
     /*     * **************Function activate**********************************
     * @type            : Function
     * @function name   : activate
     * @description     :            
     * @param           : $id integer value; 
     * @return          : null 
     * ********************************************************** */

    public function activate($id = null) {

        check_permission(EDIT);

        if ($id == '') {
            error($this->lang->line('update_failed'));
            redirect('administrator/feedback/index');
        }

        
        $this->feedback->update('guardian_feedbacks', array('is_publish' => 1), array('id' => $id));
     
        create_log('Has been activated a feedback');
        success($this->lang->line('update_success'));
        redirect('administrator/feedback/index');
    }
    
    
    
     /*     * **************Function activate**********************************
     * @type            : Function
     * @function name   : activate
     * @description     :            
     * @param           : $id integer value; 
     * @return          : null 
     * ********************************************************** */

    public function deactivate($id = null) {

        check_permission(EDIT);

        if ($id == '') {
            error($this->lang->line('update_failed'));
            redirect('administrator/feedback/index');
        }

        $this->feedback->update('guardian_feedbacks', array('is_publish' => 0), array('id' => $id));
     
        create_log('Has been deactivated a feedback');
        success($this->lang->line('update_success'));
        redirect('administrator/feedback/index');
    }
    
    
}