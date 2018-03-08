<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends App_Controller {

    public function __construct() {

        parent::__construct();

    }

    public function index() {

        $this->load->view('agents/index');

    }

    public function addAgent() {

        // Load form validation library
        $this->load->library('form_validation');

        // Set the rules for form
        $formValidationRules = [
            [
                'field' => 'agent_name',
                'rules' => 'trim|required|alpha',
                'label' => 'Agent Name'
            ],
            [
                'field' => 'agent_ip',
                'rules' => 'trim|required|valid_ip',
                'label' => 'Agent IP'
            ]
        ];

        // Validate rules
        $this->form_validation->set_rules($formValidationRules);

        // Check if exists errors
        if ($this->form_validation->run() === false) {
            echo json_encode([
                'has_errors' => true,
                'errors'    => $this->form_validation->error_array()
            ]);
            exit;
        }

        // Make a request from Python Module (agent/add - POST)
        $url = $this->serverIP . 'agent/add';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'agent_name'    => (string)$this->input->post('agent_name'),
            'agent_ip'      => $this->input->post('agent_ip')
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);
        echo $output;
        // echo '<pre>' . print_r($output, true) . '</pre>';
        // echo '<pre>' . print_r($error, true) . '</pre>';
        // echo '<pre>' . print_r($info, true) . '</pre>';

    }

}