<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'agents',
    'menuActive'        => 'agents/index',
    'pageTitle'         => 'Agents',
    'requireJs'         => ['libs/bootstrap-switch.min.js', 'libs/select2.full.min.js', 'libs/datedropper.min.js', 'pages/agents.js'],
    'requireCSS'        => ['libs/bootstrap-switch.min.css', 'libs/select2.min.css', 'libs/date_filter.css', 'libs/datedropper.min.css']
  ]
); ?>


<?php $this->load->view('layout/footer'); ?>