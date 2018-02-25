<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cli extends App_Controller {

  public function __construct() {

    parent::__construct();

    // Load the directory helper
    $this->load->helper('directory');

  }

  public function cleanAssets() {

    // Get the assets minified files
    $assetsFolder = directory_map($this->config->item('assets_dir'), 1);

    // Remove the minified files
    foreach($assetsFolder as $file) {
      if($file != '') {
        unlink($this->config->item('assets_dir').'/'.$file);
      }
    }
  }

  /**
  *
  * Gitlab deployment
  * -- This requires some changes on the server,
  * -- the web user needs access to run /usr/bin/git/
  * -- and /usr/bin/composer. This can be done by
  * -- adding the next two lines in VISUDO :
  * -- 	apache ALL=(ALL) NOPASSWD:/usr/bin/git
  * -- 	apache ALL=(ALL) NOPASSWD:/usr/bin/composer
  *
  */
  public function gitHook() {

    // Make a pull request
    shell_exec('sudo git pull 2>&1');

    // Update the composer packages
    shell_exec('composer update');

    // Clean the assets
    $this->cleanAssets();

  }

  public function initApplication($voipCode = '', $voipPassword = '') {

    // Add the webhook to gitlab
    $client = new \Gitlab\Client('http://gitlab.software-dep.net/api/v3/');
    $client->authenticate('i-zfhsJuvPs9QYv7LfDb', \Gitlab\Client::AUTH_URL_TOKEN);
    $client->api('projects')->addHook(9, 'http://'.$_SERVER['SERVER_ADDR'].':8000/cli/gitHook', ['push_events' => true, 'enable_ssl_verification' => false]);

  }

}
