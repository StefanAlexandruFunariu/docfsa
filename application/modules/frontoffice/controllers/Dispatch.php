<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Dispatch.php**********************************
 
 * @Dispatch            : Class
 * @class name      : Dispatch
 * @description     : Manage Postal dispatch.  
 	
 * ********************************************************** */

class Dispatch extends MY_Controller {

    public $data = array();
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('Dispatch_Model', 'dispatch', true);        
    }

    
    /*****************Function index**********************************
    * @Dispatch            : Function
    * @function name   : index
    * @description     : Load "Dispatch List" user interface                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {
        
        check_permission(VIEW);   
        
        $this->data['dispatches'] = $this->dispatch->get_dispatch();         
        $this->data['list'] = TRUE;
        
        $this->layout->title($this->lang->line('manage').' ' . $this->lang->line('postal').' ' . $this->lang->line('dispatch').' | ' . SMS);
        $this->layout->view('dispatch/index', $this->data);  
    }

    
    /*****************Function add**********************************
    * @Dispatch            : Function
    * @function name   : add
    * @description     : Load "Add new postal Dispatch" user interface                 
    *                    and process to store "postal Dispatch" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

         check_permission(ADD);
        if ($_POST) {
            $this->_prepare_dispatch_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_dispatch_data();

                $insert_id = $this->dispatch->insert('postal_dispatches', $data);
                if ($insert_id) {
                    success($this->lang->line('insert_success'));
                    redirect('frontoffice/dispatch/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('frontoffice/dispatch/add');
                }
            } else {
                $this->data = $_POST;
            }
        }

        $this->data['dispatchse'] = $this->dispatch->get_dispatch(); 
        
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') .' '. $this->lang->line('postal') .' '. $this->lang->line('dispatch'). ' | ' . SMS);
        $this->layout->view('dispatch/index', $this->data);
    }

        
    /*****************Function edit**********************************
    * @Dispatch            : Function
    * @function name   : edit
    * @description     : Load Update "postal Dispatch" user interface                 
    *                    with populate "postal Dispatch" value 
    *                    and process to update "postal Dispatch" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {       

         check_permission(EDIT);
        if ($_POST) {
            $this->_prepare_dispatch_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_dispatch_data();
                $updated = $this->dispatch->update('postal_dispatches', $data, array('id' => $this->input->post('id')));

                if ($updated) {                   
                    
                    success($this->lang->line('update_success'));
                    redirect('frontoffice/dispatch/index');                   
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('frontoffice/dispatch/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['dispatch'] = $this->dispatch->get_single('postal_dispatches', array('id' => $this->input->post('id')));
            }
        } else {
            if ($id) {
                $this->data['dispatch'] = $this->dispatch->get_single('postal_dispatches', array('id' => $id));

                if (!$this->data['dispatch']) {
                     redirect('frontoffice/dispatch/index');
                }
            }
        }

        $this->data['dispatches'] = $this->dispatch->get_dispatch(); 
        
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') .' '. $this->lang->line('postal') .' '. $this->lang->line('dispatch'). ' | ' . SMS);
        $this->layout->view('dispatch/index', $this->data);
    }

   
               
     /*****************Function get_single_dispatch**********************************
     * @type            : Function
     * @function name   : get_single_dispatch
     * @description     : "Load single dispatch information" from database                  
     *                    to the user interface   
     * @param           : null
     * @return          : null 
     * ********************************************************** */
    public function get_single_dispatch(){
        
       $dispatch_id = $this->input->post('dispatch_id');
       
       $this->data['dispatch'] = $this->dispatch->get_single_dispatch($dispatch_id);
       echo $this->load->view('dispatch/get-single-dispatch', $this->data);
    }
    
    /*****************Function _prepare_dispatch_validation**********************************
    * @Dispatch            : Function
    * @function name   : _prepare_dispatch_validation
    * @description     : Process "postal Dispatch" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_dispatch_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        
        $this->form_validation->set_rules('to_title', $this->lang->line('title').' '.$this->lang->line('title'), 'trim|required');
        $this->form_validation->set_rules('reference', $this->lang->line('reference'), 'trim');
        $this->form_validation->set_rules('address', $this->lang->line('address'), 'trim|required');
        $this->form_validation->set_rules('from_title', $this->lang->line('from').' '.$this->lang->line('title'), 'trim|required');
        $this->form_validation->set_rules('dispatch_date', $this->lang->line('dispatch').' '.$this->lang->line('dispatch'), 'trim|required');
        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
    }

    
    /*****************Function _get_posted_dispatch_data**********************************
    * @Dispatch            : Function
    * @function name   : _get_posted_dispatch_data
    * @description     : Prepare "Dispatch" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_dispatch_data() {

        $items = array();
        $items[] = 'to_title';
        $items[] = 'reference';
        $items[] = 'address';
        $items[] = 'from_title';
        $items[] = 'note';
        $data = elements($items, $_POST);        
        
        $data['dispatch_date'] = date('Y-m-d', strtotime($this->input->post('dispatch_date')));
        
        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = logged_in_user_id();
            $data['status'] = 1;
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

                $destination = 'assets/uploads/postal/';

                $file_type = explode(".", $attachment);
                $extension = strtolower($file_type[count($file_type) - 1]);
                $attachment_path = 'dispatch-' . time() . '-sms.' . $extension;

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
    * @Dispatch            : Function
    * @function name   : delete
    * @description     : delete "postal Dispatch" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {
        
        check_permission(DELETE);
         
        if(!is_numeric($id)){
             error($this->lang->line('unexpected_error'));
             redirect('frontoffice/dispatch/index');        
        }
        
        $dispatch = $this->dispatch->get_single('postal_dispatches', array('id' => $id));        
        if ($this->dispatch->delete('postal_dispatches', array('id' => $id))) { 
            
             // delete attachments
            $destination = 'assets/uploads/';
            if (file_exists($destination . '/postal/' . $dispatch->attachment)) {
                @unlink($destination . '/postal/' . $dispatch->attachment);
            }  
            
            success($this->lang->line('delete_success'));
            redirect('frontoffice/dispatch/index');
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('frontoffice/dispatch/index');
    }
}
