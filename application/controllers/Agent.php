<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agent extends App_Controller {

    public function __construct() {

        parent::__construct();

    }

    public function index() {

        $this->load->view('agents/index');

    }

}