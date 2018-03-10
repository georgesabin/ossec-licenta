<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends App_Controller {

    public function __construct() {

        parent::__construct();

        // Load the agent model
        $this->load->model('agent_model');

    }

    public function index() {

        $this->load->view('agents/index');

    }

    public function getAgents() {

        $dataAgent = $this->agent_model->getAgents();

        foreach ($dataAgent as $key => $agent) {
            $agent->action = '<button class="btn btn-danger btn-xs" onclick="removeAgent(\'' . $agent->agent_id . '\');">Remove</button> ';
            $agent->action .= '<button class="btn btn-info btn-xs" onclick="getAgentKey(\'' . $agent->agent_id . '\');">Generate Key</button>';
        }

        echo json_encode([
            'data'            => $dataAgent,
            'recordsTotal'    => (int)count($dataAgent),
            'recordsFiltered' => (int)count($dataAgent)
          ]);

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
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);
        echo $result;
        // echo '<pre>' . print_r($result, true) . '</pre>';
        // echo '<pre>' . print_r($error, true) . '</pre>';
        // echo '<pre>' . print_r($info, true) . '</pre>';

    }

    public function removeAgent(string $agent_id) {

        $url = $this->serverIP . 'agent/remove/' . $agent_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;

    }

    public function getAgentKey(string $agent_id) {

        $url = $this->serverIP . 'agent/key/' . $agent_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;

    }

}