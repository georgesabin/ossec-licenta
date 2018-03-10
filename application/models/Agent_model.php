<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent_model extends App_Model {

    public function __construct() {

        parent::__construct();

    }

    public function getAgents() {

        return $this->db
            ->select('*')
            ->from('agents')
            ->where('agent_flag_removed', 0)
            ->get()->result();

    }

}