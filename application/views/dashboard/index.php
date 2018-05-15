<?php
$this->load->view(
  'layout/header',
  array(
    'assetsNamespace'   => 'dashboard',
    'menuActive'        => 'dashboard',
    'requireJs'         => array('libs/Chart.bundle.min.js', 'libs/Chart.min.js', 'libs/select2.full.min.js', 'pages/dashboard.js'),
    'requireCSS'        => array('libs/select2.min.css', 'pages/dashboard.css'),
    'pageTitle'         => 'Dashboard'
  )
); ?>

<div class="row">
  <div class="col-xs-12">
      <div class="card">
        <div class="card-content">
          <div class="row">
            <div class="col-lg-4">
              <label for="" class="not_required">CPU Load</label>
              <div class="row">
                <div class="col-xs-4">
                  <strong data-name="cpu_1min">0%</strong> <small class="text-muted">1 min avg</small>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" style="width: 0%" data-name="cpu_1min_bar">
                      <span class="sr-only" data-name="cpu_1min">0%</span>
                    </div>
                  </div>
                </div>
                <div class="col-xs-4">
                  <strong data-name="cpu_5min">0%</strong> <small class="text-muted">5 min avg</small>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" style="width: 0%" data-name="cpu_5min_bar">
                      <span class="sr-only" data-name="cpu_5min">0%</span>
                    </div>
                  </div>
                </div>
                <div class="col-xs-4">
                  <strong data-name="cpu_15min">0%</strong> <small class="text-muted">15 min avg</small>
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" style="width: 0%" data-name="cpu_15min_bar">
                      <span class="sr-only" data-name="cpu_15min">0%</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <label for="" class="not_required">RAM Memory</label>
              <div class="row">
                <div class="col-xs-12">
                  <strong data-name="ram_used_text">0GB / 0GB</strong> <small data-name="ram_cached_text" class="text-muted">0 GB cached</small>
                  <div class="progress">
                    <div class="progress-bar progress-bar-info" style="width: 0%" data-name="ram_bar_used">
                      <span class="sr-only">Used RAM</span>
                    </div>
                    <div class="progress-bar progress-bar-warning" style="width: 0%" data-name="ram_bar_cached">
                      <span class="sr-only">Used RAM</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-2">
              <label for="" class="not_required">Disk</label>
              <div class="row">
                <div class="col-xs-12">
                  <strong data-name="disk_used_text">0GB / 0GB</strong>
                  <div class="progress">
                    <div class="progress-bar progress-bar-warning" style="width: 0%" data-name="disk_used_bar">
                      <span class="sr-only">Used Space</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-2">
              <?php if(isset($callTags)) { ?>
                <label for="" class="not_required">Filter tag</label>
                <select class="form-control input-sm tagSelector" style="width: 150px">
                  <option value="">All tags</option>
                  <?php foreach($callTags as $callTag) { ?>
                    <option value="<?= $callTag->rule_tag; ?>" <?= ($callTag->rule_tag == get_cookie('selectedCallTag') ? 'selected' : ''); ?>><?= $callTag->rule_tag; ?></option>
                  <?php } ?>
                </select>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>

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
