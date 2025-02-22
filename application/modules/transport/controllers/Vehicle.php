<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * *****************Vehicle.php**********************************
 
 * @type            : Class
 * @class name      : Vehicle
 * @description     : Manage transport vehicle.  
 	
 * ********************************************************** */

class Vehicle extends MY_Controller {

    public $data = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Vehicle_Model', 'vehicle', true);       
    }

    
    
    /*****************Function index**********************************
    * @type            : Function
    * @function name   : index
    * @description     : Load "Vehicle List" user interface                 
    *                     
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function index() {

        check_permission(VIEW);
        $this->data['vehicles'] = $this->vehicle->get_vehicle_list();
        $this->data['list'] = TRUE;
        $this->layout->title($this->lang->line('manage_vehicle') . ' | ' . SMS);
        $this->layout->view('vehicle/index', $this->data);
    }

    /*****************Function add**********************************
    * @type            : Function
    * @function name   : add
    * @description     : Load "Add new Vehicle" user interface                 
    *                    and process to store "Vehicle" into database 
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    public function add() {

        check_permission(ADD);

        if ($_POST) {
            $this->_prepare_vehicle_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_vehicle_data();

                $insert_id = $this->vehicle->insert('vehicles', $data);
                if ($insert_id) {
                    
                    create_log('Has been added a Vehicle : '.$data['number']);
                    success($this->lang->line('insert_success'));
                    redirect('transport/vehicle/index');
                } else {
                    error($this->lang->line('insert_failed'));
                    redirect('transport/vehicle/add');
                }
            } else {
                $this->data['post'] = $_POST;
            }
        }

        $this->data['vehicles'] = $this->vehicle->get_vehicle_list();
        $this->data['add'] = TRUE;
        $this->layout->title($this->lang->line('add') . ' ' . $this->lang->line('vehicle') . ' | ' . SMS);
        $this->layout->view('vehicle/index', $this->data);
    }
    
    /*****************Function edit**********************************
    * @type            : Function
    * @function name   : edit
    * @description     : Load Update "Vehicle" user interface                 
    *                    with populate "Vehicle" value 
    *                    and process to update "Vehicle" into database    
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function edit($id = null) {

        check_permission(EDIT);

        if(!is_numeric($id)){
           error($this->lang->line('unexpected_error'));
          redirect('transport/vehicle/index');
        }
        
        if ($_POST) {
            $this->_prepare_vehicle_validation();
            if ($this->form_validation->run() === TRUE) {
                $data = $this->_get_posted_vehicle_data();
                $updated = $this->vehicle->update('vehicles', $data, array('id' => $this->input->post('id')));

                if ($updated) {
                    
                    create_log('Has been updated a Vehicle : '.$data['number']);
                    success($this->lang->line('update_success'));
                    redirect('transport/vehicle/index');
                } else {
                    error($this->lang->line('update_failed'));
                    redirect('transport/vehicle/edit/' . $this->input->post('id'));
                }
            } else {
                $this->data['vehicle'] = $this->vehicle->get_single('vehicles', array('id' => $this->input->post('id')));
            }
        }

        if ($id) {
            $this->data['vehicle'] = $this->vehicle->get_single('vehicles', array('id' => $id));

            if (!$this->data['vehicle']) {
                redirect('transport/vehicle/index');
            }
        }

        $this->data['vehicles'] = $this->vehicle->get_vehicle_list();
        $this->data['edit'] = TRUE;
        $this->layout->title($this->lang->line('edit') . ' ' . $this->lang->line('vehicle') . ' | ' . SMS);
        $this->layout->view('vehicle/index', $this->data);
    }
    
    
    /*****************Function view**********************************
    * @type            : Function
    * @function name   : view
    * @description     : Load user interface with specific Vehicle data                 
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function view($id = null) {

        check_permission(VIEW);
        if(!is_numeric($id)){
           error($this->lang->line('unexpected_error'));
          redirect('transport/vehicle/index');
        }
        
        $this->data['vehicle'] = $this->vehicle->get_single('vehicles', array('id' => $id));
        $this->data['vehicles'] = $this->vehicle->get_vehicle_list();
        $this->data['detail'] = TRUE;
        $this->layout->title($this->lang->line('view') . ' ' . $this->lang->line('vehicle') . ' | ' . SMS);
        $this->layout->view('vehicle/index', $this->data);
    }
    
    /*****************Function _prepare_vehicle_validation**********************************
    * @type            : Function
    * @function name   : _prepare_vehicle_validation
    * @description     : Process "Vehicle" user input data validation                 
    *                       
    * @param           : null
    * @return          : null 
    * ********************************************************** */
    private function _prepare_vehicle_validation() {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div vehicle="error-message" style="color: red;">', '</div>');

        $this->form_validation->set_rules('note', $this->lang->line('note'), 'trim');
        $this->form_validation->set_rules('contact', $this->lang->line('vehicle_contact'), 'trim|required');
        $this->form_validation->set_rules('license', $this->lang->line('vehicle_license'), 'trim');
        $this->form_validation->set_rules('driver', $this->lang->line('driver'), 'trim');
        $this->form_validation->set_rules('model', $this->lang->line('vehicle_model'), 'trim');
        $this->form_validation->set_rules('number', $this->lang->line('vehicle') . ' ' . $this->lang->line('number'), 'required|trim|callback_number');
    }

        
    /*****************Function number**********************************
    * @type            : Function
    * @function name   : number
    * @description     : Unique check for "Vehicle Number" data/value                  
    *                       
    * @param           : null
    * @return          : boolean true/false 
    * ********************************************************** */ 
    public function number() {
        if ($this->input->post('id') == '') {
            $vehicle = $this->vehicle->duplicate_check($this->input->post('number'));
            if ($vehicle) {
                $this->form_validation->set_message('number', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else if ($this->input->post('id') != '') {
            $vehicle = $this->vehicle->duplicate_check($this->input->post('number'), $this->input->post('id'));
            if ($vehicle) {
                $this->form_validation->set_message('number', $this->lang->line('already_exist'));
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
   
   
    /*****************Function _get_posted_vehicle_data**********************************
    * @type            : Function
    * @function name   : _get_posted_vehicle_data
    * @description     : Prepare "Vehicle" user input data to save into database                  
    *                       
    * @param           : null
    * @return          : $data array(); value 
    * ********************************************************** */
    private function _get_posted_vehicle_data() {

        $items = array();
        $items[] = 'number';
        $items[] = 'model';
        $items[] = 'driver';
        $items[] = 'license';
        $items[] = 'contact';
        $items[] = 'note';

        $data = elements($items, $_POST);

        if ($this->input->post('id')) {
            $data['modified_at'] = date('Y-m-d H:i:s');
            $data['modified_by'] = logged_in_user_id();
        } else {
            $data['status'] = 1;
            $data['is_allocated'] = 0;
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
    * @description     : delete "Vehicle" data from database                  
    *                       
    * @param           : $id integer value
    * @return          : null 
    * ********************************************************** */
    public function delete($id = null) {

        check_permission(DELETE);
        
        if(!is_numeric($id)){
           error($this->lang->line('unexpected_error'));
          redirect('transport/vehicle/index');
        }
        
        $vehicle = $this->vehicle->get_single('vehicles', array('id' => $id));
        
        if ($this->vehicle->delete('vehicles', array('id' => $id))) {
            
            create_log('Has been deleted a Vehicle : '.$vehicle->number);
            success($this->lang->line('delete_success'));
        } else {
            error($this->lang->line('delete_failed'));
        }
        redirect('transport/vehicle/index');
    }

}
