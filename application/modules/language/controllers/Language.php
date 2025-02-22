<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Language.php**********************************
 
 * @type            : Class
 * @class name      : Language
 * @description     : Manage maulti language system.  
 	
 * ********************************************************** */

class Language extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Language_Model', 'language', true);
        
        $this->data['fields'] = $this->language->get_table_fields('languages');
        $this->data['active_lang'] = $this->db->get_where('settings', array('status'=>1))->row()->language;
       
        ini_set('max_input_time', -1);
        ini_set('memory_limit', '2048M');         
        ini_set('memory_limit','1000M');
        ini_set("upload_max_filesize", "500M");
        ini_set("post_max_size", "500M");
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Language List" user interface                 
    *                    
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        check_permission(VIEW);
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_language'). ' | ' . SMS);
        $this->layout->view('index', $this->data);
    }

    
    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Language" user interface                 
    *                    and process to store "Language" column into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);
        
        if ($_POST) {

            $this->_prepare_language_validation();

            if ($this->form_validation->run() === TRUE) {

                $field = str_replace('-', '_', get_slug($this->input->post('name')));
                $this->load->dbforge();
                $fields = array(
                    $field => array('type' => 'LONGTEXT')
                );

                $response = $this->dbforge->add_column('languages', $fields);

                if ($response) {
                    
                    create_log('Has been added a Language : '.$field);
                    
                    success($this->lang->line('insert_success'));
                    redirect('language');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('language');
                }
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('add'). ' ' . $this->lang->line('languge'). ' | ' . SMS);
        $this->layout->view('index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Language" user interface                 
    *                    with populate "Language" value 
    *                    and process to update "Language" column into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($language = null) {

        check_permission(EDIT);
        
        if(get_default_lang_list($language)){
            error($this->lang->line('unexpected_error'));
            redirect('language/index');
        }
        
        if ($_POST) {

            $this->_prepare_language_validation();

            if ($this->form_validation->run() === TRUE) {

                $old_field = $this->input->post('old_name');
                $new_field = $this->input->post('name');
                $this->load->dbforge();
                $fields = array(
                    $old_field => array(
                        'name' => $new_field,
                        'type' => 'LONGTEXT',
                    ),
                );

                $response = $this->dbforge->modify_column('languages', $fields);

                if ($response) {                    
                    
                     create_log('Has been updated a Language : '.$new_field);
                    
                    success($this->lang->line('update_success'));
                    redirect('language');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('language');
                }
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['edit'] = TRUE;
        $this->data['language'] = $language;
        $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('language'). ' | ' . SMS);
        $this->layout->view('index', $this->data);
    }
    
    
        
    /*****************Function activate**********************************
    * @type            : Function
    * @function name   : activate
    * @description     : Process to activate default language status                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function activate($language){
        
        check_permission(EDIT);
         
        if(!$language){
            error($this->lang->line('unexpected_error'));
            redirect('language');
        }else{
            $this->db->update('settings',array('language'=>$language));
            
            // update language file
            $this->update_lang();
            
            create_log('Has been activated a language : '.$language);
            
            success($this->lang->line('update_success'));
            redirect('language');
        }
    }

        
    /*****************Function _prepare_language_validation**********************************
    * @type            : Function
    * @function name   : _prepare_language_validation
    * @description     : Process "language" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_language_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        $this->form_validation->set_rules('name', $this->lang->line('language').' '.$this->lang->line('name'), 'trim|required');
    }

    
        
    
    /*****************Function delete**********************************
    * @type            : Function
    * @function name   : delete
    * @description     : delete "Language" column from database                  
    *                       
    * @param           : $language string value
    * @return          : null 
    * ********************************************************** */
    public function delete($language = null) {
        
        check_permission(DELETE);
        
        if(get_default_lang_list($language)){
            error($this->lang->line('unexpected_error'));
            redirect('language/index');
        }
        
        $this->load->dbforge();
        $response = $this->dbforge->drop_column('languages', $language);
        if ($response) {
            
            create_log('Has been deleted a language : '.$language);
            
            success($this->lang->line('delete_success'));
            redirect('language/index');
        } else {
            error($this->lang->line('delete_failed'));
            redirect('language/index');
        }
        redirect('language/index', 'refresh');
    }

        
    /*****************Function label**********************************
    * @type            : Function
    * @function name   : label
    * @description     : load user interface with label fields for specific language                
    *                       
    * @param           : $language string value
    * @return          : null 
    * ********************************************************** */
    public function label($language = null) {
        
        check_permission(VIEW);
        
        if (!$language) {
            error($this->lang->line('unexpected_error'));
            redirect('language');
        } else {
            $this->data['language'] = $language;
            $this->data['labels'] = $this->language->get_list('languages', array(), "id, label, $language", '', '', 'id', 'ASC');
            $this->layout->title($this->lang->line('edit'). ' ' . $this->lang->line('label'). ' | ' . SMS);
            $this->layout->view('index', $this->data);
        }
    }

    
            
    /*****************Function update**********************************
    * @type            : Function
    * @function name   : update
    * @description     : Process to update all language label                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function update_old() {
        //echo phpinfo();
        check_permission(EDIT);
        
        if ($_POST) {           
            
            $language = $this->input->post('language');
            $label_ids = $_POST['label_id'];
            foreach ($label_ids as $label_id) {
                $this->language->update('languages', array($language => $_POST['label'][$label_id]), array('id' => $label_id));
            }
            
            // update language file
            $this->update_lang();
            
            create_log('Has been updated language label for : '.$language);
            
        }
        success($this->lang->line('update_success'));
        redirect('language/label/' . $language);
    }
    
      
    /*****************Function update**********************************
    * @type            : Function
    * @function name   : update
    * @description     : Process to update all language label                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function update() {

        check_permission(EDIT);
        if ($_POST) {
            
            $language = $this->input->post('language');
            $label_id = $this->input->post('label_id');
            $value = $this->input->post('value');  
            
            if($this->language->update('languages', array($language => $value), array('id' => $label_id))){
                // update language file
                $this->update_lang();  
                echo TRUE;
            }else{
                echo FALSE;
            }
        }
    }

}
