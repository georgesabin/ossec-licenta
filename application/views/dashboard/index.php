<?php
$this->load->view(
  'layout/header',
  array(
    'assetsNamespace'   => 'dashboard',
    'menuActive'        => 'dashboard',
    'requireJs'         => array('libs/select2.full.min.js', 'pages/dashboard.js'),
    'requireCSS'        => array('libs/select2.min.css', 'pages/dashboard.css'),
    'pageTitle'         => 'Dashboard'
  )
); ?>

<div class="row">
  <div class="col-xs-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-10">
              <h4 class="card-title">OSSEC Logs in real time</h4>
            </div>
            <div class="col-md-2">
              <select id="logs-time" class="form-control pull-right">
                <option value="">Select the time</option>
                <option value="5000">5 seconds</option>
                <option value="10000">10 seconds</option>
                <option value="15000">15 seconds</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-content">
          <div id="ossec-logs"></div>
        </div>
      </div>
  </div>
</div>

<?php $this->load->view('layout/footer'); ?>
