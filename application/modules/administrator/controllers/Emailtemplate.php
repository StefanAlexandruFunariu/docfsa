<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Emailtemplate.php**********************************
 
 * @type            : Class
 * @class name      : Emailtemplate
 * @description     : Manage academic template.  
 	
 * ********************************************************** */

class Emailtemplate extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Emailtemplate_Model', 'template', true);
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Email Template List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);
        
        $this->data['roles'] = $this->template->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['templates'] = $this->template->get_template_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage').' ' . $this->lang->line('email'). ' ' . $this->lang->line('template'). ' | ' . SMS);
        $this->layout->view('email_template/index', $this->data);            
       
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Email template" user interface                 
    *                    and store "Email template" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_template_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_template_data();

                $insert_id = $this->template->insert('email_templates', $data);
                if ($insert_id) {
                    
                    create_log('Has been created a email template : '.$data['title']);
                    
                    success($this->lang->line('insert_success'));
                    redirect('administrator/emailtemplate/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('administrator/emailtemplate/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['roles'] = $this->template->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
        $this->data['templates'] = $this->template->get_template_list();
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('template'). ' | ' . SMS);
        $this->layout->view('email_template/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Email template" user interface                 
    *                    with populated "Email template" value 
    *                    and update "Email template" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {   
        
        check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_template_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_template_data();
                $updated = $this->template->update('email_templates', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                     create_log('Has been updated a email template : '.$data['title']);
                    
                    success($this->lang->line('update_success'));
                    redirect('administrator/emailtemplate/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('administrator/emailtemplate/edit/' . $this->input->post('id'));
                }
            } else {
                 $this->data['template'] = $this->template->get_single('email_templates', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['template'] = $this->template->get_single('email_templates', array('id' => $id));
 
                if (!$this->data['template']) {
                     redirect('administrator/emailtemplate/index');
                }
            }
        }

        $this->data['roles'] = $this->template->get_list('roles', array('status' => 1), '', '', '', 'id', 'ASC');
         $this->data['templates'] = $this->template->get_template_list();
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('template'). ' | ' . SMS);
        $this->layout->view('email_template/index', $this->data);
    }

    
    /*****************Function _prepare_template_validation**********************************
    * @type            : Function
    * @function name   : _prepare_template_validation
    * @description     : Process "Email template" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_template_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('title', $this->lang->line('template').' '. $this->lang->line('title'), 'trim|required|callback_title');
        $this->form_validation->set_rules('role_id', $this->lang->line('role'), 'trim|required');
        $this->form_validation->set_rules('template', $this->lang->line('template'), 'trim|required');
    }

            
    /*****************Function title**********************************
    * @type            : Function
    * @function name   : title
    * @description     : Unique check for "Email template" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function title() {
        if ($this->input->post('id') == '') {
            $template = $this->template->duplicate_check($this->input->post('title'), $this->input->post('role_id'));
            if ($template) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $template = $this->template->duplicate_check($this->input->post('title'), $this->input->post('role_id'), $this->input->post('id'));
            if ($template) {
                $this->form_validation->set_message('title', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
    
    /*****************Function _get_posted_template_data**********************************
     * @type            : Function
     * @function name   : _get_posted_template_data
     * @description     : Prepare "Email template" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_template_data() {

        $items = array();
        $items[] = 'title';
        $items[] = 'role_id';
        $items[] = 'template';
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
   * @description     : delete "Email template" from database                  
   *                       
   * @param           : $id integer value
   * @return          : null 
   * ********************************************************** */
    public function delete($id = null) {
                
        check_permission(DELETE);
        
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('administrator/emailtemplate');              
        }
        
        $template = $this->template->get_single('email_templates', array('id' => $id));
        
        if ($this->template->delete('email_templates', array('id' => $id))) { 
            
             create_log('Has been deleted a email template : '.$template->title);
            
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('administrator/emailtemplate/index');
    }

}
