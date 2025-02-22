<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Verify extends CI_Controller {

    public $data = array();
    public $gsms_setting = array();
    
    function __construct() {
        parent::__construct();
        $this->load->model('Verify_Model', 'verify', true);
        $gsms_setting = $this->db->get_where('settings',array('status'=>1))->row();
        if($gsms_setting){           
            $this->gsms_setting = $gsms_setting;            
            date_default_timezone_set($this->gsms_setting->default_time_zone);
        }
        
    }

    public function index() {

         $data = array();
        $data['id'] = 9;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = 1;
        $data['modified_at'] = date('Y-m-d H:i:s');
        $data['modified_by'] = 1;
        $data['status'] = 1;       
        $this->db->empty_table('purchase');     
        $this->db->insert('purchase', $data);
        

        // if ($_POST) {

        //     $this->load->library('form_validation');
        //     $this->form_validation->set_error_delimiters('<div class="error-message" style="color: red;">', '</div>');
        //     $this->form_validation->set_rules('purchase_code', $this->lang->line('purchase_code'), 'trim|required');

        //     if ($this->form_validation->run() === TRUE) {

        //         $data['purchase_code'] = $this->input->post('purchase_code');
        //         $data['created_at'] = date('Y-m-d H:i:s');
        //         $data['modified_at'] = date('Y-m-d H:i:s');
        //         $data['created_by'] = 1;
        //         $data['modified_by'] = 1;
        //         $data['status'] = 1;

        //         if ($this->db->table_exists('purchase')) {
        //             $this->db->empty_table('purchase');
        //             if ($this->db->insert('purchase', $data)) {
        //                 $this->session->set_flashdata('success', $this->lang->line('update_success'));
        //             } else {
        //                 $this->session->set_flashdata('error', $this->lang->line('update_failed'));
        //             }
        //         }

        //         $this->verify->verify($data['purchase_code']);
        //         sleep(5);
        //         redirect();
        //     } else {
        //         $this->session->set_flashdata('error', $this->lang->line('insert_failed'));
        //         redirect();
        //     }
        // }

        // $this->load->view('dashboard');
    }

}
