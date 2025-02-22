<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************About.php**********************************
 * @type            : Class
 * @class name      : About
 * @description     : Manage application about text  
 	
 * ********************************************************** */

class About extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('About_Model', 'about', true);        
    }

        
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "General About" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW); 
     
        $this->data['setting'] = $this->about->get_single('settings', array('status' => 1));
        $this->data['edit'] = TRUE;
        
        $this->layout->title($this->lang->line('about'). ' '. $this->lang->line('school') . ' | ' . SMS);
        $this->layout->view('about/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update " About Text" user interface                 
    *                    with populate "About Text" value 
    *                    and process to update "About Text" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit() {

        check_permission(EDIT);      
        
        if ($_POST) {
            
            $this->_prepare_about_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_about_data();
                $updated = $this->about->update('settings', $data, array('id' => $this->input->post('id')));

                if ($updated) {    
                    
                    create_log('Has been updated a frontend about page');
                    
                    success($this->lang->line('update_success'));
                    redirect('frontend/about/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('frontend/about/edit/' . $this->input->post('id'));
                }
             }
        }
     
        $this->layout->title($this->lang->line('frontend') . ' ' . $this->lang->line('about') . ' | ' . SMS);
        $this->layout->view('about/index', $this->data);
    }
    

        
    /*****************Function _prepare_about_validation**********************************
    * @type            : Function
    * @function name   : _prepare_about_validation
    * @description     : Process "About Text" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_about_validation() {
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('about_text', $this->lang->line('frontend') .' '. $this->lang->line('about'), 'trim|required');
        $this->form_validation->set_rules('about_image', $this->lang->line('about').' '.$this->lang->line('image'), 'trim|callback_about_image');
    }
    
    
    /*****************Function about_image**********************************
    * @type            : Function
    * @function name   : about_image
    * @description     : validate  about_image type/format                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function about_image() {
        
        if ($this->input->post('id')) {
            if (!empty($_FILES['about_image']['name'])) {
                $name = $_FILES['about_image']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('about_image', $this->lang->line('select_valid_file_format'));
                    return FALSE;
                }
            }
        }
    }


       
    /*****************Function _get_posted_about_data**********************************
    * @type            : Function
    * @function name   : _get_posted_about_data
    * @description     : Prepare "About Text" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_about_data() {

        $items = array();
        $items[] = 'about_text';            
        
        $data = elements($items, $_POST); 
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = logged_in_user_id();
       
        if ($_FILES['about_image']['name']) {
            $data['about_image'] = $this->_upload_about_image();
        }       

        return $data;
    }

           
    /*****************Function _upload_about_image**********************************
    * @type            : Function
    * @function name   : _upload_about_image
    * @description     : Process to upload about image in the server                  
    *                     and return image name   
    * @param           : null
    * @return          : $image string value 
    * ********************************************************** */
    private function _upload_about_image() {

        $prevoius_about_image = @$_POST['prev_about_image'];
        $about_image_name = $_FILES['about_image']['name'];
        $about_image_type = $_FILES['about_image']['type'];
        $about_image = '';


        if ($about_image_name != "") {
            if ($about_image_type == 'image/jpeg' || $about_image_type == 'image/pjpeg' ||
                    $about_image_type == 'image/jpg' || $about_image_type == 'image/png' ||
                    $about_image_type == 'image/x-png' || $about_image_type == 'image/gif') {

                $destination = 'assets/uploads/about/';

                $file_type = explode(".", $about_image_name);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $about_image_path = time().'-about-image.' . $extension;

                copy($_FILES['about_image']['tmp_name'], $destination . $about_image_path);

                if ($prevoius_about_image != "") {
                    // need to unlink previous image
                    if (file_exists($destination . $prevoius_about_image)) {
                        @unlink($destination . $prevoius_about_image);
                    }
                }

                $about_image = $about_image_path;
            }
        } else {
            $about_image = $prevoius_about_image;
        }

        return $about_image;
    }

}