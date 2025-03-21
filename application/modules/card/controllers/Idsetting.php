<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Schoolidsetting.php**********************************
 
 * @type            : Class
 * @class name      : Setting
 * @description     : Manage school id Card setting.  
 	
 * ********************************************************** */

class Idsetting extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
         $this->load->model('Idsetting_Model', 'setting', true);
    }

    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "School Card Setting Listing" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);             
     
        $this->data['setting'] = $this->setting->get_single('id_card_settings', array('status'=>1));        
        
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('id') . ' ' .$this->lang->line('card') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('id_card/index', $this->data);            
       
    }

    
    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new id card Setting" user interface                 
    *                    and store "id Card Setting" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {
            $this->_prepare_setting_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_setting_data();

                $insert_id = $this->setting->insert('id_card_settings', $data);
                if ($insert_id) {
                    
                    create_log('Has been added id card setting');                     
                    success($this->lang->line('insert_success'));
                    redirect('card/idsetting/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('card/idsetting/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

       
        $this->data['setting'] = $this->setting->get_single('id_card_settings', array('status'=>1));         
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('id') . ' ' . $this->lang->line('card') . ' ' .$this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('id_card/index', $this->data);
    }

    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "id Card Setting" user interface                 
    *                    with populated "id card Setting" value 
    *                    and update "id Card Setting" database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {   
        
        check_permission(EDIT);
       
        if ($_POST) {
            $this->_prepare_setting_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_setting_data();
                $updated = $this->setting->update('id_card_settings', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated id card setting');                     
                    success($this->lang->line('update_success'));
                    redirect('card/idsetting/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('card/idsetting/edit/' . $this->input->post('id'));
                }
            } 
        }
          
        $this->data['setting'] = $this->setting->get_single('id_card_settings', array('status'=>1)); 
        
        $this->data['edit'] = TRUE;       
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('id') . ' ' . $this->lang->line('card') . ' ' . $this->lang->line('setting') . ' | ' . SMS);
        $this->layout->view('id_card/index', $this->data);
    }

            
        
    /*****************Function get_single_id_setting**********************************
     * @type            : Function
     * @function name   : get_single_id_setting
     * @description     : "Load single id card setting information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_id_setting(){
        
       $setting_id = $this->input->post('setting_id');       
       $this->data['setting'] = $this->setting->get_single_id_setting($setting_id);
       echo $this->load->view('id_card/get-single-id-setting', $this->data);
    }

    
    /*****************Function _prepare_setting_validation**********************************
    * @type            : Function
    * @function name   : _prepare_setting_validation
    * @description     : Process "Card setting" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_setting_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');      
        $this->form_validation->set_rules('bottom_text', $this->lang->line('bottom').' '. $this->lang->line('signature'), 'trim|required');    
    }

    
    /*****************Function _get_posted_setting_data**********************************
     * @type            : Function
     * @function name   : _get_posted_setting_data
     * @description     : Prepare "School card setting" user input data to save into database                  
     *                       
     * @param           : null
     * @return          : $data array(); value 
     * ********************************************************** */
    private function _get_posted_setting_data() {

        $items = array();
       
        $items[] = 'border_color';
        $items[] = 'top_bg';
        $items[] = 'bottom_bg';       
        $items[] = 'school_name'; 
        $items[] = 'school_name_font_size';
        $items[] = 'school_name_color';
        $items[] = 'school_address';
        $items[] = 'school_address_color';
        $items[] = 'id_no_font_size'; 
        $items[] = 'id_no_color';
        $items[] = 'id_no_bg';
        $items[] = 'title_font_size';
        $items[] = 'title_color';
        $items[] = 'value_font_size';
        $items[] = 'value_color';
        $items[] = 'bottom_text';
        $items[] = 'bottom_text_color';
        $items[] = 'bottom_text_align';
        
        $data = elements($items, $_POST);  
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
        }    
        
        
        if ($_FILES['logo']['name']) {
            $data['school_logo'] = $this->_upload_logo();
        }

        return $data;
    }
        
            
    /*****************Function _upload_logo**********************************
    * @type            : Function
    * @function name   : _upload_logo
    * @description     : Process to upload institute logo in the server                  
    *                     and return logo name   
    * @param           : null
    * @return          : $logo string value 
    * ********************************************************** */
    private function _upload_logo() {

        $prevoius_logo = @$_POST['logo_prev'];
        $logo_name = $_FILES['logo']['name'];
        $logo_type = $_FILES['logo']['type'];
        $logo = '';


        if ($logo_name != "") {
            if ($logo_type == 'image/jpeg' || $logo_type == 'image/pjpeg' ||
                    $logo_type == 'image/jpg' || $logo_type == 'image/png' ||
                    $logo_type == 'image/x-png' || $logo_type == 'image/gif') {

                $destination = 'assets/uploads/logo/';

                $file_type = explode(".", $logo_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $logo_path = time().'-id-logo.' . $extension;

                copy($_FILES['logo']['tmp_name'], $destination . $logo_path);

                if ($prevoius_logo != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_logo)) {
                        @unlink($destination . $prevoius_logo);
                    }
                }

                $logo = $logo_path;
            }
        } else {
            $logo = $prevoius_logo;
        }

        return $logo;
    }
    
}