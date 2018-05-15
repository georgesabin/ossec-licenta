<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends App_Controller {

  public function __construct() {

    parent::__construct();

  }

  public function index() {

    // Load the dashboard view
    $this->load->view(
      'dashboard/index'
    );

  }

  // Create SSE (server sent event)
  public function ossecLogs() {

    
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');

    session_write_close();
    
    while (true) {

      $url = $this->serverIP . 'server/ossecLog';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      $result = curl_exec($ch);
      curl_close($ch);

      // Replace the \n with another string and later create an array which has a each line from log
      $result = str_replace('\n', 'n/n', $result);
      // Decode the json
      $result = json_decode($result);
      
      // Create an array with log lines
      $logLines = explode('n/n', $result->response);
      // Reorder array by key (DESC)
      krsort($logLines);
      
      // echo '<pre>' . print_r($logLines, true) . '</pre>'; exit;
      // echo '<pre>' . print_r(array_slice($logLines, 0, 51), true) . '</pre>'; exit;
      $logLines = array_slice($logLines, 0, 51);
      // krsort($logLines);
      
      $newLog = true;

      echo 'data: ' . json_encode($logLines) . PHP_EOL;
      echo PHP_EOL;

      sleep(1);

    }

  }

}
